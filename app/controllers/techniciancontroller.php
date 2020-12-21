<?php


namespace Framework\controllers;


use Framework\Lib\AbstractController;
use Framework\lib\FilterInput;
use Framework\lib\Helper;
use Framework\lib\MailModel;
use Framework\lib\Request;
use Framework\Lib\Session;
use Framework\models\UsersModel;
use Framework\models\jobs\Insurance_companies_emailsModel;
use Framework\models\jobs\Insurance_companiesModel;
use Framework\models\jobs\Insurance_reportsModel;
use Framework\models\jobs\Repair_attachmentsModel;
use Framework\models\jobs\Repair_notesModel;
use Framework\models\jobs\Repair_quotes_actionsModel;
use Framework\models\jobs\Repair_quotesModel;
use Framework\models\jobs\Repair_stages_durationModel;
use Framework\models\jobs\Repair_stagesModel;
use Framework\models\jobs\Repair_trackingModel;
use Framework\models\jobs\RepairsModel;
use Framework\models\jobs\StagesModel;
use Framework\models\jobs\TechniciansModel;
use Mpdf\Mpdf;

class TechnicianController extends AbstractController
{
    public function DefaultAction()
    {
        $param = isset($_GET['others']) ? true : false;
        $condition = $param !== false ?
            "WHERE repairs.technician_id != ". Session::Get('loggedin')->id :
            "WHERE repairs.technician_id = ". Session::Get('loggedin')->id;

        $jobs = RepairsModel::getRepairs($condition);
        $technicians = TechniciansModel::getTechniciansDetails();
        $this->RenderTechnician(['data' => $jobs, 'technicians' => $technicians]);
    }

    public function Job_addAction()
    {
        if (Request::Check('submit')) {
            $repair = new RepairsModel();
            $repair->user_id = FilterInput::Int(Request::Post('customer', false. true));
            $repair->emailUpdates = Request::Check('automatic-updates-email') ? 1 : 2;
            $repair->smsUpdates = Request::Check('automatic-updates-sms') ? 1 : 2;
            $repair->device = FilterInput::String(Request::Post('devices', false, true));
            $repair->deviceManufacture = FilterInput::String(Request::Post('device-make', false, true));
            $repair->deviceModel = FilterInput::String(Request::Post('model', false, true));
            $repair->serialNumber = FilterInput::String(Request::Post('serial-number', false, true));
            $repair->IMEI = FilterInput::String(Request::Post('imei', false, true));
            $repair->devicePassword = $this->cipher->Encrypt(Request::Post('device-password', false, true));
            $repair->deviceAltPassword = $this->cipher->Encrypt(Request::Post('device-password-alt', false, true));
            $repair->issue = FilterInput::String(Request::Post('issue-description', false, true));
            $repair->itemsLeft = (Request::Check('left-items')) ? implode('|', $_POST['left-items']) : '';
            $repair->technician_id = FilterInput::Int(Request::Post('technician', false, true));

            if (Request::Check('insuranceCompany') && Request::Check('insuranceNumber')) {
                $repair->is_insurance = 1;
                $repair->insuranceCompany = FilterInput::String(Request::Post('insuranceCompany', false, true));
                $repair->insuranceNumber = FilterInput::String(Request::Post('insuranceNumber', false, true));
            }

            $rand = RepairsModel::GenerateJobUniqueNumber();
            $repair->job_id = ucfirst(substr($repair->device, 0, 1)) . $rand->random_num;

            if ($repair->Save()) {
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

                if ($repair->technician_id) {
                    $technician = TechniciansModel::getTechniciansDetails(" && users.id = '$repair->technician_id'", true);

                    // Send email to technician.
                    try {
                        $url = HOST_NAME . 'technician/job/'.$repair->id;

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
                            $this->logger->info("New Job Notification Email was sent to technician $technician->lastName Regarding Job ID: $repair->job_id ", array('job_id' => $repair->job_id, 'technician' => Session::Get('loggedin')->username));
                        } else {
                            throw new \Exception();
                        }
                    } catch (\Exception $e) {
                        $this->logger->error("Failed to send new job notification email to technician $technician->lastName", array('job_id' => $repair->job_id, 'technician' => Session::Get('loggedin')->username));
                    }
                }

                // send email and sms to customer.
                $cutomer_details = UsersModel::getOne($repair->user_id);
                if ($repair->emailUpdates == 1) {
                    try {
                        $url = HOST_NAME;

                        // variables to be replaced from the template to the real values the user sent.
                        $variables = array();
                        $variables['name'] = $cutomer_details->firstName.' '.$cutomer_details->lastName;
                        $variables['url'] = $url;
                        $variables['uid'] = $repair->job_id;
                        $variables['status'] = "A new repair job was created.";
                        $variables['job_fields'] =
                            "Device Type: " . $repair->device."<br>".
                            "Manufacture: " . $repair->deviceManufacture."<br>";
                        if ($repair->insuranceNumber) {
                            $variables['job_fields'] .= "Insurance Claim Number: ".$repair->insuranceNumber."<br>";
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
                        $mail->to_email = $cutomer_details->email;
                        $mail->to_name = $cutomer_details->firstName.' '.$cutomer_details->lastName;
                        $mail->subject = "New Repair Job was Created";
                        $mail->message = $template;

                        if ($mail->Send()) {
                            $this->logger->info("Job Notification Email was sent to customer $cutomer_details->lastName.", array('job_id' => $repair->job_id, 'technician' => Session::Get('loggedin')->username));
                        } else {
                            throw new \Exception();
                        }
                    } catch (\Exception $e) {
                        $this->logger->error("Failed to send job notification email to customer!", array('customer_id' => $cutomer_details->id, 'job_id' => $repair->job_id, 'technician' => Session::Get('loggedin')->username));
                    }
                }

                if ($repair->smsUpdates == 1) {
                    $message = "Hello ".$cutomer_details->firstName.", 
                             Repair job was created successfully.
                             To check your job please visit: https://cyw.repair/".$repair->job_id."
                             Compute Your World";

                    $sendResponse = $this->SendSMS($message, $cutomer_details->phone);

                    if ($sendResponse){
                        $this->logger->info('SMS notification about job creation sent successfully to customer: '. $cutomer_details->lastName, array('Job_id: ' => $repair->job_id));
                    } else {
                        $this->logger->error('Failed to send sms notification about job creation to customer: '. $cutomer_details->lastName, array('Job_id: ' => $repair->job_id));
                    }
                }

                Helper::SetFeedback('success', 'Repair Job was created successfully.');
                $this->logger->info("Repair Job (ID: $repair->job_id) was created successfully ", array('Job ID' => $repair->job_id, 'technician' => Session::Get('loggedin')->username));

                header("location: " . HOST_NAME . 'technician/job/' . $repair->id);
            } else {
                Helper::SetFeedback('error', "Failed to create repair job. Please try again later.");
                $this->logger->error("Failed to create new repair job, Unknowing reason", array('technician' => Session::Get('loggedin')->username));
            }
        }

        $customers = UsersModel::getAll("WHERE role = 'customer'");
        $insurance = Insurance_companiesModel::getAll();
        $technicians = TechniciansModel::getTechniciansDetails();
        $this->RenderTechnician(['customers' => $customers, 'technicians' => $technicians, 'insurance' => $insurance]);
    }

    public function JobAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        if ($id !== false) {
            $job = RepairsModel::getRepairs("WHERE repairs.id = '$id' ", true);

            $this->RenderTechnician([
                'item' => $job,
                'customer' => UsersModel::getUser($job->user_id),
                'technicians' => UsersModel::getAll("WHERE role = 'technician'"),
                'notes' => Repair_notesModel::getNotesWithUser($id),
                'quotes' => Repair_quotesModel::getAll("WHERE repair_id = '$id'"),
                'quotes_actions' => Repair_quotesModel::getRepairQuotesActions($id),
                'attachments' => Repair_attachmentsModel::getAll("WHERE repair_id = '$id'"),
                'stages' => StagesModel::getAll(),
                'job_stages' => Repair_stagesModel::getRepairStages($id, "ORDER BY created DESC"),
                'tracking_info' => Repair_trackingModel::getAll("WHERE repair_id = '$id'"),
                'insurance' => Insurance_companiesModel::getAll()
            ]);
        }
    }

    public function Job_editAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        if ($id !== false) {
            $old_repair = RepairsModel::getOne($id);

            if ($old_repair) {
                $repair = new RepairsModel();
                $repair->id = $old_repair->id;
                $repair->deviceModel = FilterInput::String(Request::Post('model', false, true));
                $repair->serialNumber = FilterInput::String(Request::Post('serial-number', false, true));
                $repair->IMEI = FilterInput::String(Request::Post('imei', false, true));
                $repair->insuranceCompany = FilterInput::String(Request::Post('insuranceCompany', false, true));
                $repair->insuranceNumber = FilterInput::String(Request::Post('insuranceNumber', false, true));
                $repair->technician_id = FilterInput::Int(Request::Post('technician', false, true));

                $repair->last_update = date('Y-m-d H:i:s');
                if ($repair->Save()) {
                    Helper::SetFeedback('success', 'Job was updated successfully.');
                    $this->logger->info("Job (ID: $old_repair->job_id) was updated successfully ", array('job_id' => $old_repair->job_id, 'technician' => Session::Get('loggedin')->username));

                    $updated_repair = RepairsModel::getOne($repair->id);
                    $cutomer_details = UsersModel::getOne($updated_repair->user_id);
                    $technician = TechniciansModel::getTechniciansDetails(" && users.id = '$updated_repair->technician_id'", true);

                    $selected_stage = FilterInput::Int(Request::Post('stage', false, true));
                    $selected_stage = ($selected_stage) ? StagesModel::getOne($selected_stage) : false;
                    $check_stage = Repair_stagesModel::getRepairStages($repair->id,
                        "&& repair_stages.status = '1' && repair_stages.ended IS NULL",
                        true
                    );

                    if ($check_stage) {
                        if ($check_stage->stage_id !== $selected_stage->id) {
                            // end current stage
                            $end_stage = new Repair_stagesModel();
                            $end_stage->id = $check_stage->id;
                            $end_stage->status = 2;
                            $end_stage->ended = date('Y-m-d H:i:s');

                            if ($end_stage->Save()) {
                                // update the duration for ended stage.
                                $ended_repair_stage_duration = Repair_stagesModel::getStages_duration("WHERE repair_stage_id = '$check_stage->id' ");
                                if ($ended_repair_stage_duration) {
                                    $ended_repair_stage_duration = array_shift($ended_repair_stage_duration);
                                    $now = date('Y-m-d H:i:s', SERVER_TIMESTAMP);
                                    if ($ended_repair_stage_duration->paused == '$$') {
                                        if ($ended_repair_stage_duration->continued == '$$' || $ended_repair_stage_duration->continued == '') {
                                            $diff = Helper::DateDiff($ended_repair_stage_duration->created, $now, true);
                                        } else {
                                            $diff = Helper::DateDiff($ended_repair_stage_duration->continued, $now, true);
                                        }

                                        if ($diff) {
                                            $update_ended_repair_stage_duration = new Repair_stages_durationModel();
                                            $update_ended_repair_stage_duration->id = $ended_repair_stage_duration->stage_duration_id;
                                            $update_ended_repair_stage_duration->duration = intval($ended_repair_stage_duration->duration) + intval($diff);
                                            $update_ended_repair_stage_duration->Save();
                                        }
                                    }
                                }

                                // create the new repair stage.
                                $repair_stage = new Repair_stagesModel();
                                $repair_stage->repair_id = $repair->id;
                                $repair_stage->job_id = $old_repair->job_id;
                                $repair_stage->stage_id = $selected_stage->id;
                                $repair_stage->created = date('Y-m-d H:i:s', SERVER_TIMESTAMP);

                                if ($repair_stage->Save()) {
                                    $this->logger->info("Repair status changed successfully for job (ID: $old_repair->job_id)", array('job_id' => $old_repair->job_id, 'technician' => Session::Get('loggedin')->username));

                                    // create the newly created stage's duration
                                    $repair_stage_duration = new Repair_stages_durationModel();
                                    $repair_stage_duration->repair_stage_id = $repair_stage->id;
                                    $repair_stage_duration->repair_id = $repair->id;
                                    $repair_stage_duration->stage_id = $selected_stage->id;
                                    $repair_stage_duration->Save();

                                    // status change. send email/sms
                                    if ($updated_repair->emailUpdates == 1) {
                                        try {
                                            $url = HOST_NAME;

                                            // variables to be replaced from the template to the real values the user sent.
                                            $variables = array();
                                            $variables['name'] = $cutomer_details->firstName.' '.$cutomer_details->lastName;
                                            $variables['url'] = $url;
                                            $variables['uid'] = $updated_repair->job_id;
                                            $variables['status'] = "Your job status has changed from: '".$check_stage->stage." to: '".$selected_stage->stage;
                                            $variables['job_fields'] =
                                                "Device Type: " . $updated_repair->device."<br>".
                                                "Manufacture: " . $updated_repair->deviceManufacture."<br>";
                                            if ($updated_repair->insuranceNumber) {
                                                $variables['job_fields'] .= "Insurance Claim Number: ".$updated_repair->insuranceNumber;
                                            }
                                            $variables['job_fields'] .=
                                                "Date Booked in & Time: " . Helper::ConvertDateFormat($updated_repair->created, true)."<br>".
                                                "Technician Assigned: " . $technician->firstName.' '.$technician->lastName."<br>".
                                                "Reported Issue: " . $updated_repair->issue."<br>";

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
                                            $mail->subject = "Job Notification";
                                            $mail->message = $template;

                                            if ($mail->Send()) {
                                                $this->logger->info("Job Notification Email was sent to customer $cutomer_details->lastName Regarding Job ID: $updated_repair->job_id ", array('job_id' => $updated_repair->job_id, 'technician' => Session::Get('loggedin')->username));
                                            } else {
                                                throw new \Exception();
                                            }
                                        } catch (\Exception $e) {
                                            $this->logger->error("Failed to send notification email to customer", array('job_id' => $updated_repair->job_id, 'technician' => Session::Get('loggedin')->username));
                                        }
                                    }

                                    if ($updated_repair->smsUpdates == 1) {
                                        if (strtolower($selected_stage->stage) == "called customer, awaiting collection, completed") {
                                            $message = "Hello ".$cutomer_details->firstName.",
                                                    Your device is ready for collection.
                                                    Please call (08) 8461 9552 or drop into the store to arrange pickup.
                                                    Compute Your World";
                                        } elseif (strtolower($selected_stage->stage) == "awaiting approval on quote") {
                                            $message = "Hello ".$cutomer_details->firstName.", 
                                                    Your job is currently pending approval,
                                                    Please call (08) 8461 9552 or visit https://cyw.repair/".$repair->job_id."
                                                    Compute Your World";
                                        } else {
                                            $message = "Hello ".$cutomer_details->firstName.", 
                                                    Your job status has changed,
                                                    To check your job please visit: https://cyw.repair/".$repair->job_id."
                                                    Compute Your World";
                                        }
                                        $sendResponse = $this->SendSMS($message, $cutomer_details->phone);

                                        if ($sendResponse){
                                            $this->logger->info('SMS notification about job status change sent successfully', array('Job_id: ' => $updated_repair->job_id));
                                        } else {
                                            $this->logger->error('Failed to send sms notification about job status change', array('Job_id: ' => $updated_repair->job_id));
                                        }
                                    }

                                    $this->logger->info("Job's status type was changed from: '".$check_stage->stage." to: '".$selected_stage->stage, array('job_id' => $old_repair->job_id ,'technician' => Session::Get('loggedin')->username));
                                }
                            }
                        }
                    }

                    // handle quotes/costs
                    if (Request::Check('quote') || Request::Check('cost')) {
//                        $dl_repair_quote = new Repair_quotesModel();
//                        $dl_repair_quote->Delete("repair_id = '$repair->id' ");

                        for ($i = 1; $i < sizeof($_POST['quote']) + 1; $i++) {
                            $repair_quote = new Repair_quotesModel();
                            if (isset($_POST['quote']['quote-id-'.$i]) && !empty($_POST['quote']['quote-id-'.$i])) {
                                $repair_quote->id = $_POST['quote']['quote-id-'.$i];
                            }
                            $repair_quote->repair_id = $repair->id;
                            $repair_quote->user_id = Session::Get('loggedin')->id;
                            $repair_quote->item = isset($_POST['quote']['item-details-'.$i]) && !empty($_POST['quote']['item-details-'.$i]) ? $_POST['quote']['item-details-'.$i] : '';
                            $repair_quote->quote = isset($_POST['quote']['quote-repair-'.$i]) && !empty(floatval($_POST['quote']['quote-repair-'.$i])) ? floatval($_POST['quote']['quote-repair-'.$i]) : '';
                            $repair_quote->cost = isset($_POST['cost']['item-cost-'.$i]) && !empty(floatval($_POST['cost']['item-cost-'.$i])) ? floatval($_POST['cost']['item-cost-'.$i]) : '';
                            $repair_quote->Save();
                        }
                    }

                    // handle technician change. (send him an email).
                    if ($repair->technician_id !== $old_repair->technician_id) {
                        if ($technician) {
                            // send email to technician
                            try {
                                $url = HOST_NAME . 'admin/job/'.$repair->id;

                                // variables to be replaced from the template to the real values the user sent.
                                $variables = array();
                                $variables['name'] = $technician->firstName.' '.$technician->lastName;
                                $variables['url'] = $url;
                                $variables['uid'] = $repair->job_id;

                                $variables['job_fields'] =
                                    "Device Type: " . $updated_repair->device."<br>".
                                    "Manufacture: " . $updated_repair->deviceManufacture."<br>".
                                    "Date Booked in & Time: " . Helper::ConvertDateFormat($updated_repair->created, true)."<br>".
                                    "Reported Issue: " . $updated_repair->issue."<br>";

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
                                    $this->logger->info("New Job Notification Email was sent to technician $technician->lastName Regarding Job ID: $old_repair->job_id ", array('job_id' => $old_repair->job_id));
                                } else {
                                    throw new \Exception();
                                }
                            } catch (\Exception $e) {
                                $this->logger->error("Failed to send new job notification email to technician $technician->lastName", array('job_id' => $old_repair->job_id));
                            }
                        }
                    }
                } else {
                    Helper::SetFeedback('error', 'Failed to update job.');
                    $this->logger->info("Failed to update job (ID: $old_repair->job_id).", array('job_id' => $old_repair->job_id, 'technician' => Session::Get('loggedin')->username));
                }

                header("location: " . HOST_NAME . 'technician/job/' . $id);
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
                            $this->logger->info("Quote was approved by technician.", array('repair_id' => $repair->id, 'technician' => Session::Get('loggedin')->username));
                        }
                    }
                }

                $repair_updated = new RepairsModel();
                $repair_updated->id = $id;
                $repair_updated->last_update = date('Y-m-d H:i:s');
                $repair_updated->Save();

                $technician = TechniciansModel::getTechniciansDetails(" && users.id = '$repair->technician_id'", true);
                if ($technician) {
                    try {
                        $url = HOST_NAME;

                        // variables to be replaced from the template to the real values the user sent.
                        $variables = array();
                        $variables['name'] = $technician->firstName.' '.$technician->lastName;
                        $variables['url'] = $url;
                        $variables['uid'] = $repair->job_id;
                        $variables['status'] = "Quote approved by technician.";
                        $variables['job_fields'] =
                            "Device Type: " . $repair->device."<br>".
                            "Manufacture: " . $repair->deviceManufacture."<br>";
                        if ($repair->insuranceNumber) {
                            $variables['job_fields'] .= "Insurance Claim Number: ".$repair->insuranceNumber."<br>";
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
                        $mail->subject = "Job Notification, Quote Approved by Technician";
                        $mail->message = $template;

                        if ($mail->Send()) {
                            $this->logger->info("Job Notification Email was sent to technician $technician->lastName about quote approval.", array('job_id' => $repair->job_id, 'technician' => Session::Get('loggedin')->username));
                        } else {
                            throw new \Exception();
                        }
                    } catch (\Exception $e) {
                        $this->logger->error("Failed to send notification email to technician about quote approval!", array('technician_id' => $technician->id, 'job_id' => $repair->job_id, 'technician' => Session::Get('loggedin')->username));
                    }
                }
            }

            header("location: " . HOST_NAME . 'technician/job/' . $id);
        }
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

                            $this->logger->info("Added attachment", array('job_id' => $id ,'technician' => Session::Get('loggedin')->username));
                            Helper::SetFeedback('success', 'Attachment was added successfully.');
                        } else {
                            $this->logger->info("Failed to add new attachment", array('job_id' => $id ,'technician' => Session::Get('loggedin')->username));
                            Helper::SetFeedback('error', 'Failed to upload attachment!');
                        }

                        header("location: " . HOST_NAME . 'technician/job/'. $id);
                    }
                }

                $repair_updated = new RepairsModel();
                $repair_updated->id = $id;
                $repair_updated->last_update = date('Y-m-d H:i:s');
                $repair_updated->Save();
            }
        }
    }

    public function Job_attachment_deleteAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        $res = 0;
        if ($id !== false) {
            $file = new Repair_attachmentsModel();
            $file->id = $id;
            if ($file->Delete()) {
                $this->logger->info("Deleted attachment", array('attachment_id' => $id ,'technician' => Session::Get('loggedin')->username));
                $res = 1;
            }
        }

        die(json_encode($res));
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
                $repair_updated = new RepairsModel();
                $repair_updated->id = $id;
                $repair_updated->last_update = date('Y-m-d H:i:s');
                $repair_updated->Save();

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
                                $variables['job_fields'] .= "Insurance Claim Number: ".$repair->insuranceNumber."<br>";
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
                                $this->logger->info("Job Notification Email was sent to customer $cutomer_details->lastName about new note.", array('job_id' => $repair->job_id, 'technician' => Session::Get('loggedin')->username));
                            } else {
                                throw new \Exception();
                            }
                        } catch (\Exception $e) {
                            $this->logger->error("Failed to send notification email to customer about new note!", array('customer_id' => $cutomer_details->id, 'job_id' => $repair->job_id, 'technician' => Session::Get('loggedin')->username));
                        }
                    }

                    if ($repair->smsUpdates == 1) {
                        $message = "Hello ".$cutomer_details->firstName.", 
                                    A technician has posted an update to your job.
                                    Please Visit: https://cyw.repair/".$repair->job_id."
                                    Compute Your World";
                        $sendResponse = $this->SendSMS($message, $cutomer_details->phone);

                        if ($sendResponse){
                            $this->logger->info('SMS notification about job note sent successfully', array('Job_id: ' => $repair->job_id));
                        } else {
                            $this->logger->error('Failed to send sms notification about added job note', array('Job_id: ' => $repair->job_id));
                        }
                    }
                }


                $this->logger->info("Created note", array('note_id' => $note->id ,'technician' => Session::Get('loggedin')->username));
                Helper::SetFeedback('success', 'New note was added successfully.');
            } else {
                $this->logger->info("Failed to create note", array('job_id' => $id ,'technician' => Session::Get('loggedin')->username));
                Helper::SetFeedback('error', 'Failed to create new note!');
            }

            header("location: " . HOST_NAME . 'technician/job/'. $id);
        }
    }

    public function Job_note_getAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        $note = Repair_notesModel::getOne($id);
        die(json_encode($note));
    }

    public function Job_note_editAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        if (Request::Check('submit') && $id !== false) {
            $note = new Repair_notesModel();
            $note->id = Request::Post('edit-note-id');
            $note->note = FilterInput::String(Request::Post('edit-note-note', false, true));
            if ($note->Save()) {
                $repair_updated = new RepairsModel();
                $repair_updated->id = $id;
                $repair_updated->last_update = date('Y-m-d H:i:s');
                $repair_updated->Save();

                $this->logger->info("Updated job note", array('note_id' => $note->id ,'technician' => Session::Get('loggedin')->username));
                Helper::SetFeedback('success', 'Note was updated successfully.');
            } else {
                $this->logger->info("Failed to update note", array('note_id' => $note->id ,'technician' => Session::Get('loggedin')->username));
                Helper::SetFeedback('error', 'Failed to update note!');
            }

            header("location: " . HOST_NAME . 'technician/job/'. $id);
        }
    }

    public function Job_note_deleteAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        $res = 0;
        if ($id !== false) {
            $note = new Repair_notesModel();
            $note->id = $id;
            if ($note->Delete()) {
                $this->logger->info("Deleted note", array('note_id' => $id ,'technician' => Session::Get('loggedin')->username));
                $res = 1;
            }
        }

        die(json_encode($res));
    }

    public function Job_quote_deleteAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        $res = 0;
        if ($id !== false) {
            $quote = new Repair_quotesModel();
            $quote->id = $id;
            if ($quote->Delete()) {
                $this->logger->info("Deleted quote", array('quote_id' => $id ,'technician' => Session::Get('loggedin')->username));
                $res = 1;
            }
        }

        die(json_encode($res));
    }

    public function Job_tracking_info_addAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        if (Request::Check('submit') && $id !== false) {
            $item = new Repair_trackingModel();
            $item->repair_id = $id;
            $item->carrier = FilterInput::String(Request::Post('carrier', false, true));
            $item->tracking_number = FilterInput::String(Request::Post('tracking-number', false, true));
            $item->expected_delivery = FilterInput::String(Request::Post('expected_delivery', false, true));

            if ($item->Save()) {
                $repair_updated = new RepairsModel();
                $repair_updated->id = $id;
                $repair_updated->last_update = date('Y-m-d H:i:s');
                $repair_updated->Save();

                $this->logger->info("Added tracking info", array('job_id' => $id ,'technician' => Session::Get('loggedin')->username));
                Helper::SetFeedback('success', 'Tracking info was added successfully.');
            } else {
                $this->logger->info("Failed to add tracking info", array('job_id' => $id ,'technician' => Session::Get('loggedin')->username));
                Helper::SetFeedback('error', 'Failed to add tracking info!');
            }

            header("location: " . HOST_NAME . 'technician/job/'. $id);
        }
    }

    public function Job_tracking_info_deleteAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        $res = 0;
        if ($id !== false) {
            $item = new Repair_trackingModel();
            $item->id = $id;
            if ($item->Delete()) {
                $this->logger->info("Deleted tracking info", array('technician' => Session::Get('loggedin')->username));
                $res = 1;
            }
        }

        die(json_encode($res));
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

    public function Job_assign_technicianAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        $tech_id = (isset($this->_params) && isset($this->_params[1])) ? $this->_params[1] : false;
        $technician = TechniciansModel::getTechniciansDetails(" && users.id = '$tech_id'", true);
        $o_repair = RepairsModel::getOne($id);
        $res = false;
        if ($id && $technician) {
            $repair = new RepairsModel();
            $repair->id = $id;
            $repair->technician_id = $tech_id;
            $repair->last_update = date('Y-m-d H:i:s');
            if ($repair->Save()) {
                $res = 1;

                // send email to technician
                try {
                    $url = HOST_NAME . 'admin/job_edit/'.$repair->id;

                    // variables to be replaced from the template to the real values the user sent.
                    $variables = array();
                    $variables['name'] = $technician->firstName.' '.$technician->lastName;
                    $variables['url'] = $url;
                    $variables['uid'] = $repair->job_id;
                    $variables['job_fields'] =
                        "Device Type: " . $o_repair->device."<br>".
                        "Manufacture: " . $o_repair->deviceManufacture."<br>".
                        "Date Booked in & Time: " . Helper::ConvertDateFormat($o_repair->created, true)."<br>".
                        "Reported Issue: " . $o_repair->issue."<br>";

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
                        $this->logger->info("New Job Notification Email was sent to technician $technician->lastName Regarding Job ID: $o_repair->job_id ", array('job_id' => $o_repair->job_id));
                    } else {
                        throw new \Exception();
                    }
                } catch (\Exception $e) {
                    $this->logger->error("Failed to send new job notification email to technician $technician->lastName", array('job_id' => $o_repair->job_id));
                }
            }
        }
        die(json_encode($res));
    }

    public function Job_trackAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        if ($id !== false) {
            $tracking_info = Repair_trackingModel::getRepairTrackings($id);

            $this->_template->SetData(['tracking_info' => $tracking_info])
                ->SetViews(['topbar', 'view'])
                ->Render();
        }
    }

    public function Job_insurance_reportAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        if ($id !== false) {
            $repair = RepairsModel::getRepairs("WHERE repairs.id = '$id' ", true);
            $companies = Insurance_companiesModel::getAll();

            if ($repair) {
                if (isset($_POST) && !empty($_POST)) {
                    $reference = FilterInput::String(Request::Post('reference', false, true));
                    $info = htmlentities($_POST['info'], ENT_QUOTES, 'UTF-8');

                    $variables = array();
                    $variables['IMAGEPATH'] = IMAGES_DIR;
                    $variables['DATE'] = date('d F, Y');
                    $variables['REFERENCE'] = $reference;
                    $variables['CLIENT'] = $repair->firstName.' '.$repair->lastName;
                    $variables['PHONE'] = $repair->phone;
                    $variables['NAME'] = Session::Get('loggedin')->firstName.' '.Session::Get('loggedin')->lastName;
                    $variables['MAKE'] = $repair->deviceManufacture;

                    $variables['MODEL'] = ($repair->deviceModel) ? "<P class='p ft1'>Model: ".$repair->deviceModel."</P>" : '';
                    $variables['SERIAL'] = ($repair->serialNumber) ? "<P class='p ft0'>Serial Number: ".$repair->serialNumber."</P>" : '';
                    $variables['IMEI'] = ($repair->IMEI) ? "<P class='p ft0'>IMEI: ".$repair->IMEI."</P>" : '';

                    if ($info) {
                        $variables['INFO'] = "<P class='p ft1'>We have assessed your clients Device</P>";
                        $variables['INFO'] .= html_entity_decode($info);
                    } else {
                        $variables['INFO'] = '';
                    }

                    // check if the file exists, if not then create it.
                    if (!file_exists(EMAIL_TEMPLATES_PATH . 'insurance-report.html')) {
                        touch(EMAIL_TEMPLATES_PATH . 'insurance-report.html');
                    }
                    // get the content of the file.
                    $template = file_get_contents(EMAIL_TEMPLATES_PATH . 'insurance-report.html');
                    // replace the variables from the file with the actual data.
                    foreach ($variables as $key => $value) {
                        $template = str_replace('{{ ' . $key . ' }}', $value, $template);
                    }
                    
                    $mpdf = new Mpdf([
                        'mode' => 'utf-8',
                        'margin_top' => 5,
                        'margin_bottom' => 5
                    ]);
                    $mpdf->WriteHTML($template);
                    $mpdf->SetHTMLFooter(
                        "<P class=\"p ft1\">_________________________________________________________________________________________________</P>
                                <div style=\"text-align: center\">
                                    <img src=\"".IMAGES_DIR."bottom-logos.jpg\">
                                </div></div></body></html>
                    ");

                    $document = $repair->job_id.'-'.date('d.m.Y').'-'.rand(99999999, 9).'.pdf';

                    if (isset($_POST['submit'])) {
                        $selected_emails = $_POST['selected-emails'];
                        $selected_company = $_POST['company'];
                        $company = Insurance_companiesModel::getOne($selected_company);

                        $mpdf->Output(INSURANCE_REPORTS_PATH.$document, 'F');

                        $save_report = new Insurance_reportsModel();
                        $save_report->repair_id = $repair->id;
                        $save_report->job_id = $repair->job_id;
                        $save_report->company_id = $selected_company;
                        $save_report->company_name = $company->name;
                        $save_report->document = $document;
                        if ($save_report->Save()) {
                            Helper::SetFeedback('success', 'Report was generated & saved successfully with the name "'.$document.'"');
                        }

                        foreach ($selected_emails as $selected_email) {
                            try {
                                $mail = new MailModel();
                                $mail->from_email = CONTACT_EMAIL;
                                $mail->from_name = CONTACT_NAME;
                                $mail->to_email = $selected_email;
                                $mail->to_name = $company->name;
                                $mail->subject = "Insurance Report";
                                $mail->message = $template;
                                $mail->attachment = array(INSURANCE_REPORTS_PATH.$document);

                                if ($mail->Send()) {
                                    $this->logger->info("Email was sent to insurance company $company->name.", array('job_id' => $repair->job_id, 'technician' => Session::Get('loggedin')->username));
                                } else {
                                    throw new \Exception();
                                }
                            } catch (\Exception $e) {
                                $this->logger->error("Failed to send email to insurance company $company->name", array('job_id' => $repair->job_id, 'technician' => Session::Get('loggedin')->username));
                            }
                        }

                        header("location: " . HOST_NAME . 'technician/insurance_reports');
                    } elseif (isset($_POST['preview'])) {
                        $mpdf->Output();
                    }
                }

                $this->_template
                    ->SetData(['companies' => $companies, 'selected_company' => $repair->insuranceCompany])
                    ->SetViews(['topbar', 'view'])
                    ->Render();

            }
        }
    }

    public function Get_company_emailsAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        $emails = Insurance_companies_emailsModel::getAll("WHERE insurance_company_id = '$id'");
        die(json_encode($emails));
    }


    public function Insurance_reportsAction()
    {
        $reports = Insurance_reportsModel::getAll();
        $this->_template->SetData(['data' => $reports])
            ->SetViews(['topbar', 'view'])
            ->Render();
    }

    public function Insurance_report_deleteAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        if ($id !== false) {
            $document = Insurance_reportsModel::getOne($id);

            $item = new Insurance_reportsModel();
            $item->id = $id;

            if ($item->Delete()) {
                @unlink(INSURANCE_REPORTS_PATH.$document->document);

                $this->logger->info("Deleted Insurance Report", array('Report' => $document->document, 'Technician' => Session::Get('loggedin')->username));
                Helper::SetFeedback('success', 'Insurance Report Was Deleted Successfully.');
                header("location: " . HOST_NAME . 'technician/insurance_reports');
            } else {
                $this->logger->error("Failed to delete Insurance Report, Unknown error.", array('Report' => $document->document, 'Technician' => Session::Get('loggedin')->username));
                Helper::SetFeedback('error', 'Failed to delete insurance report, Unknowing Error!');
            }
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
            $item->address = FilterInput::String(Request::Post('address', false, true));
            $item->twoFA = Request::Check('2fa') ? 1 : 2;
            $item->lastUpdate = date('Y-m-d h:i:s');

            $check_email = ($item->email) ? UsersModel::Count(" WHERE email = '$item->email' ") : false;
            $check_username = ($item->username) ? UsersModel::Count(" WHERE username = '$item->username' ") : false;
            $check_phone = ($item->phone) ? UsersModel::Count(" WHERE phone = '$item->phone' ") : false;

            if ($check_email !== false && $check_email > 0 && $user->email !== $item->email) {
                $this->logger->error("Failed to update technician profile", array('error: ' => "Email already exists" ,'technician' => Session::Get('loggedin')->username));
                Helper::SetFeedback('error', 'Failed to update profile, Email already exists!');
            } elseif ($check_username !== false && $check_username > 0 && $user->username !== $item->username) {
                $this->logger->error("Failed to update technician profile", array('error: ' => "Username already exists" ,'technician' => Session::Get('loggedin')->username));
                Helper::SetFeedback('error', 'Failed to update profile, Username already exists!');
            } elseif ($check_phone !== false && $check_phone > 0 && $user->phone !== $item->phone) {
                $this->logger->error("Failed to update technician profile", array('error: ' => "Phone number already exists" ,'technician' => Session::Get('loggedin')->username));
                Helper::SetFeedback('error', 'Failed to update profile, Phone number already exists!');
            } else {
                if ($item->Save()) {
                    $this->logger->info("Updated technician profile", array('technician' => Session::Get('loggedin')->username));
                    Helper::SetFeedback('success', 'Profile Was Updated Successfully.');
                    header("location: " . HOST_NAME . 'technician/profile');
                } else {
                    $this->logger->error("Failed to update technician profile, Unknown saving error.", array('technician' => Session::Get('loggedin')->username));
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