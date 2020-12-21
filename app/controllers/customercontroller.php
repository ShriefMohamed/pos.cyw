<?php


namespace Framework\controllers;


use Framework\Lib\AbstractController;
use Framework\lib\FilterInput;
use Framework\lib\Helper;
use Framework\lib\MailModel;
use Framework\lib\Request;
use Framework\Lib\Session;
use Framework\models\CustomersModel;
use Framework\models\UsersModel;
use Framework\models\jobs\Repair_attachmentsModel;
use Framework\models\jobs\Repair_notesModel;
use Framework\models\jobs\Repair_quotes_actionsModel;
use Framework\models\jobs\Repair_quotesModel;
use Framework\models\jobs\Repair_stages_actionsModel;
use Framework\models\jobs\Repair_stagesModel;
use Framework\models\jobs\RepairsModel;
use Framework\models\jobs\StagesModel;
use Framework\models\jobs\TechniciansModel;

use function GuzzleHttp\headers_from_lines;

class CustomerController extends AbstractController
{

    public function DefaultAction()
    {
        $jobs = RepairsModel::getRepairs("WHERE repairs.user_id = ". Session::Get('loggedin')->id);
        $this->_template->SetData(['data' => $jobs])
            ->SetViews(['topbar', 'view'])
            ->Render();
    }

    public function Job_printAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        if ($id !== false) {
            $repair = RepairsModel::getRepairs("WHERE repairs.id = '$id'", true);
            $url = HOST_NAME . 'admin/users?filter=' . $repair->user_id;
            $barcode = '<img src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl='.urlencode($url).'&choe=UTF-8"/>';

            $this->_template->SetData(['barcode' => $barcode, 'repair' => $repair])
                ->SetViews(['view'])
                ->Render(true);
        }
    }

    public function JobAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        if ($id !== false) {
            $job = RepairsModel::getRepairs("WHERE repairs.id = '$id' ", true);
            $customer = UsersModel::getUser($job->user_id);
            $notes = Repair_notesModel::getNotesWithUser($id);
            $quotes = Repair_quotesModel::getAll("WHERE repair_id = '$id'");
            $quotes_actions = Repair_quotesModel::getRepairQuotesActions($id);
            $attachments = Repair_attachmentsModel::getAll("WHERE repair_id = '$id'");
            $job_stages = Repair_stagesModel::getRepairStages($id, "ORDER BY created ASC");

//            $job_stage = Repair_stagesModel::getRepairStages($id,
//                "&& repair_stages.status = '1' && repair_stages.ended IS NULL",
//                true
//            );
//            $stage_action = Repair_stages_actionsModel::getAll(
//                "WHERE repair_id = '$id' &&
//                    stage_id = '$job_stage->stage_id' &&
//                    repair_stage_id = '$job_stage->id' &&
//                    action = 1"
//                );

            $this->_template->SetData([
                    'item' => $job,
                    'customer' => $customer,
                    'notes' => $notes,
                    'quotes' => $quotes,
                    'quotes_actions' => $quotes_actions,
                    'attachments' => $attachments,
                    'job_stages' => $job_stages
                ])
                ->SetViews(['topbar', 'view'])
                ->Render();
        }
    }

    public function Job_editAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        if ($id !== false) {
            if (Request::Check('submit')) {
                $old_repair = RepairsModel::getOne($id);
                if ($old_repair) {
                    $repair = new RepairsModel();
                    $repair->id = $old_repair->id;
                    $repair->devicePassword = Request::Check('device-password') ?
                        $this->cipher->Encrypt(Request::Post('device-password', false, true)) : '';
                    $repair->deviceAltPassword = Request::Check('device-alt-password') ?
                        $this->cipher->Encrypt(Request::Post('device-alt-password', false, true)) : '';
                    $repair->emailUpdates = Request::Check('automatic-updates-email') ? 1 : 2;
                    $repair->smsUpdates = Request::Check('automatic-updates-sms') ? 1 : 2;
                    if ($repair->Save()) {
                        Helper::SetFeedback('success', 'Job was updated successfully.');
                        $this->logger->info("Job (ID: $old_repair->job_id) was updated successfully ", array('job_id' => $old_repair->job_id, 'Customer' => Session::Get('loggedin')->username));
                    } else {
                        Helper::SetFeedback('error', 'Failed to update job.');
                        $this->logger->info("Failed to update job (ID: $old_repair->job_id).", array('job_id' => $old_repair->job_id, 'Customer' => Session::Get('loggedin')->username));
                    }

                    header("location: " . HOST_NAME . 'customer/job/' . $id);
                }
            }
        }
    }

    public function Approve_quotesAction()
    {
        $id = $id = ($this->_params) != null ? $this->_params[0] : false;
        if ($id !== false) {
            if (isset($_POST['quote-approval'])) {
                $quote_ids = $_POST['quote-approval'];
                $repair = RepairsModel::getOne($id);
                $saved = 0;
                foreach ($quote_ids as $quote_id) {
                    $check_quote_action = Repair_quotes_actionsModel::getAll(
                        "WHERE repair_quote_id = '$quote_id'"
                    );
                    if (!$check_quote_action) {
                        $quote_action = new Repair_quotes_actionsModel();
                        $quote_action->repair_quote_id = $quote_id;
                        $quote_action->action = '1';
                        $quote_action->approved_by = Session::Get('loggedin')->id;
                        if ($quote_action->Save()) {
                            $saved = true;
                            $this->logger->info("Quote was approved by customer.", array('repair_id' => $repair->id, 'Customer' => Session::Get('loggedin')->username));
                        }
                    }
                }

                $technician = TechniciansModel::getTechniciansDetails(" && users.id = '$repair->technician_id'", true);
                if ($technician) {
                    try {
                        $url = HOST_NAME;

                        // variables to be replaced from the template to the real values the user sent.
                        $variables = array();
                        $variables['name'] = $technician->firstName.' '.$technician->lastName;
                        $variables['url'] = $url;
                        $variables['uid'] = $repair->job_id;
                        $variables['status'] = "Quote approved by customer.";
                        $variables['job_fields'] =
                            "Device Type: " . $repair->device."<br>".
                            "Manufacture: " . $repair->deviceManufacture."<br>";
                        if ($repair->insuranceNumber) {
                            $variables['job_fields'] .= "Insurance Claim Number: ".$repair->insuranceNumber;
                        }
                        $variables['job_fields'] .=
                            "Date Booked in & Time: " . Helper::ConvertDateFormat($repair->created, true)."<br>".
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
                        $mail->to_email = $technician->email;
                        $mail->to_name = $technician->firstName.' '.$technician->lastName;
                        $mail->subject = "Job Notification, Quote Approved by Customer";
                        $mail->message = $template;

                        if ($mail->Send()) {
                            $this->logger->info("Job Notification Email was sent to technician $technician->lastName about quote approval.", array('job_id' => $repair->job_id, 'Customer' => Session::Get('loggedin')->username));
                        } else {
                            throw new \Exception();
                        }
                    } catch (\Exception $e) {
                        $this->logger->error("Failed to send notification email to technician about quote approval!", array('technician_id' => $technician->id, 'job_id' => $repair->job_id, 'Customer' => Session::Get('loggedin')->username));
                    }
                }
            }

            header("location: " . HOST_NAME . 'customer/job/' . $id);
        }
    }

    public function Approve_quoteAction()
    {
        $id = $id = ($this->_params) != null ? $this->_params[0] : false;
        $res = 0;
        if ($id !== false) {
            $repair = RepairsModel::getOne($id);
            $job_stage = Repair_stagesModel::getRepairStages($repair->id,
                "&& repair_stages.status = '1' && repair_stages.ended IS NULL",
                true
            );

            if ($repair && $job_stage) {
                $stage_action = new Repair_stages_actionsModel();
                $stage_action->repair_id = $repair->id;
                $stage_action->stage_id = $job_stage->stage_id;
                $stage_action->repair_stage_id = $job_stage->id;
                $stage_action->user_id = Session::Get('loggedin')->id;
                $stage_action->action = 1;

                if ($stage_action->Save()) {
                    $res = 1;
                    $technician = TechniciansModel::getTechniciansDetails(" && users.id = '$repair->technician_id'", true);

                    try {
                        $url = HOST_NAME;

                        // variables to be replaced from the template to the real values the user sent.
                        $variables = array();
                        $variables['name'] = $technician->firstName.' '.$technician->lastName;
                        $variables['url'] = $url;
                        $variables['uid'] = $repair->job_id;
                        $variables['status'] = "Quote approved by customer.";
                        $variables['job_fields'] =
                            "Device Type: " . $repair->device."<br>".
                            "Manufacture: " . $repair->deviceManufacture."<br>";
                        if ($repair->insuranceNumber) {
                            $variables['job_fields'] .= "Insurance Claim Number: ".$repair->insuranceNumber;
                        }
                        $variables['job_fields'] .=
                            "Date Booked in & Time: " . Helper::ConvertDateFormat($repair->created, true)."<br>".
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
                        $mail->to_email = $technician->email;
                        $mail->to_name = $technician->firstName.' '.$technician->lastName;
                        $mail->subject = "Job Notification, Quote Approved by Customer";
                        $mail->message = $template;

                        if ($mail->Send()) {
                            $this->logger->info("Job Notification Email was sent to technician $technician->lastName about quote approval.", array('job_id' => $repair->job_id, 'Customer' => Session::Get('loggedin')->username));
                        } else {
                            throw new \Exception();
                        }
                    } catch (\Exception $e) {
                        $this->logger->error("Failed to send notification email to technician about quote approval!", array('technician_id' => $technician->id, 'job_id' => $repair->job_id, 'Customer' => Session::Get('loggedin')->username));
                    }

                    $this->logger->info("Quote was approved by customer.", array('job_id' => $repair->job_id, 'Customer' => Session::Get('loggedin')->username));
                }
            }
        }

        die(json_encode($res));
    }

    public function Job_attachment_addAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        if (Request::Check('submit') && $id !== false) {
            $files = Helper::ReArrayFiles($_FILES['attachments']);
            if (isset($files[0]) && !empty($files[0]['name'])) {
                foreach ($files as $file) {
                    if ($file['name']) {
                        $tmp_name = $file['tmp_name'];

                        $attachment = new Repair_attachmentsModel();
                        $attachment->repair_id = $id;
                        $attachment->user_id = Session::Get('loggedin')->id;
                        $attachment->name = $file['name'];
                        $attachment->name_on_server = rand(99999999, 9) . '-' .$file['name'];

                        if ($attachment->Save()) {
                            move_uploaded_file($tmp_name, ATTACHMENTS_PATH . $attachment->name_on_server);

                            $this->logger->info("Added attachment", array('job_id' => $id ,'Customer' => Session::Get('loggedin')->username));
                            Helper::SetFeedback('success', 'Attachment was added successfully.');
                        } else {
                            $this->logger->info("Failed to add new attachment", array('job_id' => $id ,'Customer' => Session::Get('loggedin')->username));
                            Helper::SetFeedback('error', 'Failed to upload attachment!');
                        }

                        header("location: " . HOST_NAME . 'customer/job/'. $id);
                    }
                }
            }
        }
    }

    public function Job_note_addAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;

        if (Request::Check('submit') && $id !== false) {
            $note = new Repair_notesModel();
            $note->repair_id = $id;
            $note->user_id = Session::Get('loggedin')->id;
            $note->type = Request::Post('add-note-type');
            $note->note = FilterInput::String(Request::Post('add-note-note', false, true));
            if ($note->Save()) {
                if ($note->type !== 'internal') {
                    $repair = RepairsModel::getOne($id);
                    $cutomer_details = UsersModel::getOne($repair->user_id);
                    $technician = TechniciansModel::getTechniciansDetails(" && users.id = '$repair->technician_id'", true);

                    // Note added. send email/sms
                    if ($repair->emailUpdates == 1) {
                        try {
                            $url = HOST_NAME;

                            // variables to be replaced from the template to the real values the user sent.
                            $variables = array();
                            $variables['name'] = $cutomer_details->firstName.' '.$cutomer_details->lastName;
                            $variables['url'] = $url;
                            $variables['uid'] = $repair->job_id;
                            $variables['status'] = "A note was added to your repair job.";
                            $variables['job_fields'] =
                                "Device Type: " . $repair->device."<br>".
                                "Manufacture: " . $repair->deviceManufacture."<br>";
                            if ($repair->insuranceNumber) {
                                $variables['job_fields'] .= "Insurance Claim Number: ".$repair->insuranceNumber;
                            }
                            $variables['job_fields'] .=
                                "Date Booked in & Time: " . Helper::ConvertDateFormat($repair->created, true)."<br>".
                                "Technician Assigned: " . $technician->firstName.' '.$technician->lastName."<br>".
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
                            $mail->to_email = $cutomer_details->email;
                            $mail->to_name = $cutomer_details->firstName.' '.$cutomer_details->lastName;
                            $mail->subject = "Job Notification, New Note was Added";
                            $mail->message = $template;

                            if ($mail->Send()) {
                                $this->logger->info("Job Notification Email was sent to customer $cutomer_details->lastName about new note.", array('job_id' => $repair->job_id, 'Admin' => Session::Get('loggedin')->username));
                            } else {
                                throw new \Exception();
                            }
                        } catch (\Exception $e) {
                            $this->logger->error("Failed to send notification email to customer about new note!", array('customer_id' => $cutomer_details->id, 'job_id' => $repair->job_id, 'Admin' => Session::Get('loggedin')->username));
                        }
                    }

                    if ($repair->smsUpdates == 1) {
                        $message = "Job Notification: Job ID #".$repair->job_id.". A new note was added to your job,
                             if you have opted for email notifications as well please check your email for an up to date progress report.
                             or log into the portal '".HOST_NAME."' and enter your Job ID. Thanks Compute Your World";
                        $sendResponse = $this->SendSMS($message, $cutomer_details->phone);

                        if ($sendResponse->getStatusCode() == 200){
                            $this->logger->info('SMS notification about job note sent successfully', array('Job_id: ' => $repair->job_id, "Details: " => $sendResponse->getBody()));
                        } else {
                            $this->logger->error('Failed to send sms notification about added job note', array('Job_id: ' => $repair->job_id, "Error details: " => $sendResponse->getBody()));
                        }
                    }
                }


                $this->logger->info("Created note", array('note_id' => $note->id ,'Customer' => Session::Get('loggedin')->username));
                Helper::SetFeedback('success', 'New note was added successfully.');
            } else {
                $this->logger->info("Failed to create note", array('job_id' => $id ,'Customer' => Session::Get('loggedin')->username));
                Helper::SetFeedback('error', 'Failed to create new note!');
            }

            header("location: " . HOST_NAME . 'customer/job/'. $id);
        }
    }


    public function ProfileAction()
    {
        $user = UsersModel::getUser(Session::Get('loggedin')->id);

        if (Request::Check('submit')) {
            $item = new UsersModel();
            $item->id = $user->id;
            $item->firstName = FilterInput::String(Request::Post('firstName', false, true));
            $item->lastName = FilterInput::String(Request::Post('lastName', false, true));
            $item->username = FilterInput::String(Request::Post('username', false, true));
            $item->email = FilterInput::Email(Request::Post('email', false, true));
            $item->password = Helper::Hash(Request::Post('password', false, false));
            $item->phone = FilterInput::String(Request::Post('phone', false, true));
            $item->phone2 = FilterInput::String(Request::Post('phone2', false, true));
            $item->twoFA = Request::Check('2fa') ? 1 : 2;
            $item->lastUpdate = date('Y-m-d h:i:s');

            $check_email = ($item->email) ? UsersModel::Count(" WHERE email = '$item->email' ") : false;
            $check_username = ($item->username) ? UsersModel::Count(" WHERE username = '$item->username' ") : false;
            $check_phone = ($item->phone) ? UsersModel::Count(" WHERE phone = '$item->phone' ") : false;

            if ($check_email !== false && $check_email > 0 && $user->email !== $item->email) {
                $this->logger->error("Failed to update customer profile", array('error: ' => "Email already exists" ,'Customer' => Session::Get('loggedin')->username));
                Helper::SetFeedback('error', 'Failed to update profile, Email already exists!');
            } elseif ($check_username !== false && $check_username > 0 && $user->username !== $item->username) {
                $this->logger->error("Failed to update customer profile", array('error: ' => "Username already exists" ,'Customer' => Session::Get('loggedin')->username));
                Helper::SetFeedback('error', 'Failed to update profile, Username already exists!');
            } elseif ($check_phone !== false && $check_phone > 0 && $user->phone !== $item->phone) {
                $this->logger->error("Failed to update customer profile", array('error: ' => "Phone number already exists" ,'Customer' => Session::Get('loggedin')->username));
                Helper::SetFeedback('error', 'Failed to update profile, Phone number already exists!');
            } else {
                if ($item->Save()) {
                    $customer = new CustomersModel();
                    if (Request::Check('customer_id')) {
                        $customer->id = Request::Post('customer_id');
                    }
                    $customer->user_id = $item->id;
                    $customer->companyName = FilterInput::String(Request::Post('company', false, true));
                    $customer->address = FilterInput::String(Request::Post('address', false, true));
                    $customer->smsNotifications = Request::Check('automatic-updates-sms') ? 1 : 2;
                    $customer->emailNotifications = Request::Check('automatic-updates-email') ? 1 : 2;
                    $customer->Save();

                    $this->logger->info("Updated customer profile", array('Customer' => Session::Get('loggedin')->username));
                    Helper::SetFeedback('success', 'Profile Was Updated Successfully.');
                    header("location: " . HOST_NAME . 'customer/profile');
                } else {
                    $this->logger->error("Failed to update customer profile, Unknown saving error.", array('Customer' => Session::Get('loggedin')->username));
                    Helper::SetFeedback('error', 'Failed to update profile, Unknowing Error!');
                }
            }
        }

        $this->_template
            ->SetData(['user' => $user])
            ->SetViews(['topbar', 'view'])
            ->Render();
    }

    public function SignoutAction()
    {
        Session::Remove('loggedin');
        header("location: " . HOST_NAME . 'login');
    }
}