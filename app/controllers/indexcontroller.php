<?php


namespace Framework\controllers;


use Framework\Lib\AbstractController;
use Framework\lib\FilterInput;
use Framework\lib\Helper;
use Framework\lib\MailModel;
use Framework\lib\Redirect;
use Framework\lib\Request;
use Framework\Lib\Session;
use Framework\models\quotes\Quotes_itemsModel;
use Framework\models\quotes\QuotesModel;
use Framework\models\UsersModel;
use Framework\models\CustomersModel;
use Framework\models\jobs\Repair_notesModel;
use Framework\models\jobs\Repair_quotesModel;
use Framework\models\jobs\Repair_stages_durationModel;
use Framework\models\jobs\Repair_stagesModel;
use Framework\models\jobs\RepairsModel;
use Framework\models\jobs\StagesModel;
use Framework\models\jobs\TechniciansModel;

class IndexController extends AbstractController
{
    /* Jobs System */
    public function DefaultAction()
    {
        if (Request::Check('submit-checkin')) {
            $repair = new RepairsModel();
            $repair->device = FilterInput::String(Request::Post('devices', false, true));
            $repair->deviceManufacture = FilterInput::String(Request::Post('device-make', false, true));
            $repair->devicePassword = $this->cipher->Encrypt(Request::Post('device-password', false, true));
            $repair->deviceAltPassword = $this->cipher->Encrypt(Request::Post('device-password-alt', false, true));
            $repair->issue = FilterInput::String(Request::Post('issue-description', false, true));

            if (Request::Post('found-customer-id') !== '') {
                $repair->user_id = Request::Post('found-customer-id');

                if (Request::Check('phone-2-1')) {
                    $user = new UsersModel();
                    $user->id = Request::Post('found-customer-id', false, true);
                    $user->phone2 = FilterInput::Int(Request::Post('phone-2-1', false, true));
                    $user->Save();
                }
            } else {
                $user = new UsersModel();
                $user->firstName = FilterInput::String(Request::Post('first-name', false, true));
                $user->lastName = FilterInput::String(Request::Post('last-name', false, true));
                $user->username = FilterInput::String(Request::Post('username', false, true));
                $user->password = Helper::Hash(Request::Post('password', false, false));
                $user->email = FilterInput::Email(Request::Post('email', false, true));
                $user->phone = FilterInput::Int(Request::Post('phone', false, true));
                $user->phone2 = FilterInput::Int(Request::Post('phone2', false, true));
                $user->role = 'customer';

                if ($user->Save()) {
                    $repair->user_id = $user->id;

                    $customer = new CustomersModel();
                    $customer->user_id = $user->id;
                    $customer->companyName = FilterInput::String(Request::Post('company-name', false, true));
                    $customer->address = FilterInput::String(Request::Post('address', false, true));
                    $customer->Save();
                }
            }

            if (Request::Check('insurance-assessment')) {
                $repair->is_insurance = 1;
                $repair->insuranceCompany = FilterInput::String(Request::Post('insurance-company', false, true));
                $repair->insuranceNumber = FilterInput::String(Request::Post('insurance-claim-number', false, true));
                $repair->insuranceEmail = FilterInput::Email(Request::Post('insurance-email-address', false, true));
            }

//            $customer_details = UsersModel::getOne($user->id);
//            $customer_name = preg_replace('/\s+/','', $customer_details->lastName);
//            $customer_name = preg_replace('/&/', '', $customer_name);
//            $last_id = RepairsModel::getIDs("ORDER BY id DESC");
//            $last_id = ($last_id) ? $last_id->id + 1 : 1;
            $rand = RepairsModel::GenerateJobUniqueNumber();
            $repair->job_id = ucfirst(substr($repair->device, 0, 1)) . $rand->random_num;

            $repair->heardAboutUs = FilterInput::String(Request::Post('heared-about-us', false, true));
            $repair->itemsLeft = (Request::Check('left-items')) ? implode('|', $_POST['left-items']) : '';
            $repair->emailUpdates = Request::Check('automatic-updates-email') ? 1 : 2;
            $repair->smsUpdates = Request::Check('automatic-updates-sms') ? 1 : 2;

            if ($repair->Save()) {
                if (Request::Check('signature')) {
                    $signature = $_POST['signature'];
                    $signature = explode(',', $signature);
                    file_put_contents(SIGNATURES_PATH . $repair->job_id . '.png', base64_decode($signature[1]));
                }

                $stage = StagesModel::getAll(" WHERE stage LIKE '%Awaiting diagnosis%' LIMIT 1", true);
                if (!$stage) {
                    $stage = new StagesModel();
                    $stage->stage = 'Awaiting diagnosis';
                    $stage->Save();
                }

                $repair_stage = new Repair_stagesModel();
                $repair_stage->repair_id = $repair->id;
                $repair_stage->job_id = $repair->job_id;
                $repair_stage->stage_id = $stage->id;
                if ($repair_stage->Save()) {
                    $repair_stage_duration = new Repair_stages_durationModel();
                    $repair_stage_duration->repair_stage_id = $repair_stage->id;
                    $repair_stage_duration->repair_id = $repair->id;
                    $repair_stage_duration->stage_id = $stage->id;
                    $repair_stage_duration->Save();
                }

                // assign technician based on the tags & user device.
                $technicians = TechniciansModel::getTechniciansDetails();
                if ($technicians) {
                    foreach ($technicians as $technician) {
                        $tags = str_replace('/', '|', $technician->tags);
                        $tags = explode('|', strtolower($tags));
                        if (in_array(strtolower($repair->device), $tags)) {
                            $tech_repair = new RepairsModel();
                            $tech_repair->id = $repair->id;
                            $tech_repair->technician_id = $technician->id;
                            if ($tech_repair->Save()) {
                                $this->logger->info("Technician $technician->lastName was assigned a new job automatically.", array('job_id' => $repair->job_id));

                                // Send email to technician.
                                try {
                                    $url = HOST_NAME . 'admin/job_edit/'.$repair->id;

                                    // variables to be replaced from the template to the real values the user sent.
                                    $variables = array();
                                    $variables['name'] = $technician->firstName.' '.$technician->lastName;
                                    $variables['url'] = $url;
                                    $variables['uid'] = $repair->job_id;
                                    $variables['job_fields'] =
                                        "Device Type: " . $repair->device."<br>".
                                        "Manufacture: " . $repair->deviceManufacture."<br>".
                                        "Date Booked in & Time: " . date('d-m-Y h:i')."<br>".
                                        "Reported Issue: " . $repair->issue."<br>";

                                    // check if the file exists, if not then create it.
                                    if (!file_exists(EMAIL_TEMPLATES_PATH . 'technician-job-notification-email-template.html')) {
                                        touch(EMAIL_TEMPLATES_PATH . 'technician-job-notification-email-template.html');
                                    }
                                    // get the content of the file.
                                    $template = file_get_contents(EMAIL_TEMPLATES_PATH . 'technician-job-notification-email-template.html');
                                    // replace the variables from the file with the actual data.
                                    foreach ($variables as $key => $value) {
                                        $template = str_replace('{{ ' . $key . ' }}', $value, $template);
                                    }

                                    // set the object values in order to send the email by the email class.
                                    $mail = new MailModel();
                                    $mail->from_email = CONTACT_EMAIL;
                                    $mail->from_name = CONTACT_NAME;
                                    $mail->to_email = $technician->email;
                                    $mail->to_name = $technician->firstName.' '.$technician->lastName;
                                    $mail->subject = "New Job Notification";
                                    $mail->message = $template;

                                    if ($mail->Send()) {
                                        $this->logger->info("New Job Notification Email was sent to technician $technician->lastName Regarding Job ID: $repair->job_id ", array('job_id' => $repair->job_id));
                                    } else {
                                        throw new \Exception();
                                    }
                                } catch (\Exception $e) {
                                    $this->logger->error("Failed to send new job notification email to technician $technician->lastName", array('job_id' => $repair->job_id));
                                }

                                break;
                            }
                        }
                    }
                }


                // send email to customer.
                $customer_details = UsersModel::getOne($repair->user_id);
                if ($repair->emailUpdates == 1) {
                    try {
                        $url = HOST_NAME;

                        // variables to be replaced from the template to the real values the user sent.
                        $variables = array();
                        $variables['name'] = $customer_details->firstName.' '.$customer_details->lastName;
                        $variables['url'] = $url;
                        $variables['uid'] = $repair->job_id;
                        $variables['status'] = "Your job was created successfully and awaiting diagnosis";
                        $variables['job_fields'] =
                            "Device Type: " . $repair->device."<br>".
                            "Manufacture: " . $repair->deviceManufacture."<br>";
                        if ($repair->insuranceNumber) {
                            $variables['job_fields'] .= "Insurance Claim Number: ".$repair->insuranceNumber;
                        }
                        $variables['job_fields'] .=
                            "Date Booked in & Time: " . date('d-m-Y h:i')."<br>".
                            "Reported Issue: " . $repair->issue."<br>";

                        // check if the file exists, if not then create it.
                        if (!file_exists(EMAIL_TEMPLATES_PATH . 'job-notification-email-template.html')) {
                            touch(EMAIL_TEMPLATES_PATH . 'job-notification-email-template.html');
                        }
                        // get the content of the file.
                        $template = file_get_contents(EMAIL_TEMPLATES_PATH . 'job-notification-email-template.html');
                        // replace the variables from the file with the actual data.
                        foreach ($variables as $key => $value) {
                            $template = str_replace('{{ ' . $key . ' }}', $value, $template);
                        }

                        // set the object values in order to send the email by the email class.
                        $mail = new MailModel();
                        $mail->from_email = CONTACT_EMAIL;
                        $mail->from_name = CONTACT_NAME;
                        $mail->to_email = $customer_details->email;
                        $mail->to_name = $customer_details->firstName.' '.$customer_details->lastName;
                        $mail->subject = "New Repair Job Notification";
                        $mail->message = $template;

                        if ($mail->Send()) {
                            $this->logger->info("Job Notification Email was sent to customer $customer_details->lastName Regarding Job ID: $customer_details->job_id ", array('job_id' => $repair->job_id));
                        } else {
                            throw new \Exception();
                        }
                    } catch (\Exception $e) {
                        $this->logger->error("Failed to send notification email to customer $customer_details->lastName", array('job_id' => $repair->job_id));
                    }
                }

                if ($repair->smsUpdates == 1) {
                    $message = "Hello ".$customer_details->firstName.", 
                             Your job was created successfully.
                             To check your job please visit: https://cyw.repair/".$repair->job_id."
                             Compute Your World";

                    $sendResponse = $this->SendSMS($message, $customer_details->phone);

                    if ($sendResponse){
                        $this->logger->info('SMS notification about job creation sent successfully to customer: '. $customer_details->lastName, array('Job_id: ' => $repair->job_id));
                    } else {
                        $this->logger->error('Failed to send sms notification about job creation to customer: '. $customer_details->lastName, array('Job_id: ' => $repair->job_id));
                    }
                }

                Session::Set('job-checkin', "Success, Repair job was created successfully.");
                $this->logger->info("Created new repair job", array('Job ID' => $repair->id));
            } else {
                Session::Set('job-checkin', "Error, Failed to create repair job. Please try again later.");
                $this->logger->error("Failed to create new repair job, Unknowing reason", array('Job ID' => $repair->id));
            }
        }

        $this->_template->SetViews(['view'])->Render();
    }

    public function JobAction()
    {
        $repair_code = ($this->_params) != null ? $this->_params[0] : false;
        $repair = RepairsModel::getRepairs("WHERE repairs.job_id = '$repair_code'", true);
        if ($repair) {
            $notes = Repair_notesModel::getNotesWithUser($repair->id);
            $quotes = Repair_quotesModel::getAll("WHERE repair_id = '$repair->id'");

            $this->_template
                ->SetData([
                    'repair' => $repair,
                    'notes' => $notes,
                    'quotes' => $quotes
                ])
                ->SetViews(['view'])
                ->Render();

        } else {
            Session::Set('job-checkin', "Couldn't find any repair jobs associated with repair code \"<strong>$repair_code</strong>\".");
            header("location: " . HOST_NAME . 'index');
        }
    }


    /* Quotes System */
    public function Quote_previewAction($id)
    {
        echo $this->QuoteReceipt($id);
    }


    /* Unsubscribe */
    public function UnsubscribeAction($target = '')
    {
        $customer_id = Request::Check('c', 'get') ? Request::Get('c') : false;
        if ($target == 'licenses') {
            if ($customer_id) {
                $customer = new CustomersModel();
                $customer->id = $customer_id;
                $customer->licensesNotifications = '2';
                if ($customer->Save()) {
                    $this->logger->info("Customer unsubscribed from digital licenses notifications emails", ['Customer ID' => $customer_id]);
                } else {
                    $this->logger->error("Failed to unsubscribe customer from digital licenses notifications emails", ['Customer ID' => $customer_id]);
                }
            }
        }


        $this->_template->SetViews(['view'])->Render(true);
    }

    /* Ajax functions */
    public function Check_customerAction()
    {
        $phone_lastname = (isset($this->_params)) ? $this->_params[0] : null;
        if ($phone_lastname) {
            $phone_lastname = json_decode(urldecode($phone_lastname));
            $client = CustomersModel::getCustomers(
                " WHERE users.role = 'customer' && (users.phone = '$phone_lastname' || users.lastName = '$phone_lastname') ",
                true
            );
            die(json_encode($client));
        }
    }
}