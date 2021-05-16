<?php


namespace Framework\controllers;

use Browser\Casper;
use Framework\Lib\AbstractController;
use Framework\lib\FilterInput;
use Framework\lib\Helper;
use Framework\lib\MailModel;
use Framework\models\jobs\Repair_stages_durationModel;
use Framework\models\jobs\Repair_stagesModel;
use Framework\models\jobs\Repair_tracking_logsModel;
use Framework\models\jobs\Repair_trackingModel;
use Framework\models\jobs\RepairsModel;
use Framework\models\jobs\TechniciansModel;
use Framework\models\licenses\Digital_licenses_assigned_licensesModel;
use Framework\models\licenses\Digital_licenses_assignModel;
use Framework\models\licenses\Digital_licensesModel;
use Framework\models\Np_pdf_ordersModel;
use Framework\models\pos\Purchase_orders_itemsModel;
use Framework\models\pos\VendorsModel;
use Framework\models\quotes\Leader_itemsModel;
use Framework\models\quotes\QuotesModel;
use Framework\models\UsersModel;
use GuzzleHttp\Client;
use http\Client\Request;
use KubAT\PhpSimple\HtmlDomParser;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CronController extends AbstractController
{
    /* Jobs System Cron Jobs */
    public function Pause_active_stagesAction()
    {
        $day = date('D', SERVER_TIMESTAMP);
        $hour = date('H', SERVER_TIMESTAMP);
        $continue = false;

        if ($day == 'Sun') {
            $continue = false;
        } else if ($day == 'Sat') {
            if ($hour == '13') {
                $continue = true;
            }
        } else {
            if ($hour == '18') {
                $continue = true;
            }
        }

        if ($continue !== false) {
            $active_stages = Repair_stagesModel::getStages_duration("WHERE repair_stages.status = '1' & repair_stages.ended IS NULL");
            if ($active_stages) {
                $now = date('Y-m-d H:i:s', SERVER_TIMESTAMP);
                foreach ($active_stages as $active_stage) {
                    if (strtolower($active_stage->stage) !== "called Customer, awaiting collection, completed") {
                        if ($active_stage->continued == '' || $active_stage->continued == '$$') {
                            $diff = Helper::DateDiff($active_stage->created, $now, true);
                        } else {
                            if (strtotime($active_stage->continued) !== false) {
                                $diff = Helper::DateDiff($active_stage->continued, $now, true);
                            }
                        }

                        if ($diff) {
                            $update_stage_duration = new Repair_stages_durationModel();
                            $update_stage_duration->id = $active_stage->stage_duration_id;
                            $update_stage_duration->paused = $now;
                            $update_stage_duration->continued = '$$';
                            $update_stage_duration->duration = intval($active_stage->duration) + intval($diff);
                            if ($update_stage_duration->Save()) {
                                $this->logger->info("Repair Stage paused by cron job.", array('job id: ' => $active_stage->job_id, 'stage_id: ' => $active_stage->stage_id));
                            }
                        }
                    }
                }
            }
        }
    }

    public function Continue_active_paused_stagesAction()
    {
        $day = date('D', SERVER_TIMESTAMP);
        $hour = date('H', SERVER_TIMESTAMP);

        if ($day !== 'Sun') {
            if ($hour == '09') {
                $active_stages = Repair_stagesModel::getStages_duration("WHERE repair_stages.status = '1' & repair_stages.ended IS NULL");
                if ($active_stages) {
                    $now = date('Y-m-d H:i:s', SERVER_TIMESTAMP);
                    foreach ($active_stages as $active_stage) {
                        if ($active_stage->paused !== '' && $active_stage->paused !== '$$') {
                            if (strtotime($active_stage->paused)) {
                                $update_stage_duration = new Repair_stages_durationModel();
                                $update_stage_duration->id = $active_stage->stage_duration_id;
                                $update_stage_duration->paused = '$$';
                                $update_stage_duration->continued = $now;
                                if ($update_stage_duration->Save()) {
                                    $this->logger->info("Repair Stage continued by cron job.", array('job id: ' => $active_stage->job_id, 'stage_id: ' => $active_stage->stage_id));
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function Save_tracking_logsAction()
    {
        $all_tracking_info = Repair_trackingModel::getAll("WHERE status_e = 1");
        if ($all_tracking_info) {
            $urls = array(
                'auspost' => 'https://auspost.com.au/mypost/track/#/details/',
                'couriersplease' => 'https://www.couriersplease.com.au/tools-track/',
                'fastway' => 'https://www.fastway.com.au/tools/track?l=',
                'apdparcel' => 'http://apdparcel.com.au/'
            );
            foreach ($all_tracking_info as $item) {
                $casper = new Casper();
                $casper->setOptions(array('ignore-ssl-errors' => 'yes'));

                if (isset($urls[$item->carrier])) {
                    $casper->start($urls[$item->carrier] . $item->tracking_number);
                    $casper->wait(5000);

                    if ($item->carrier == 'auspost') {
                        $casper->evaluate("document.querySelector('.accordion-button').click()");
                    }
                    if ($item->carrier == 'couriersplease') {
                        $casper->evaluate(
                            "document.querySelector('#txttrackingnumber').value = 'CPWSGE000014919';
                            document.querySelector('#btngo').click();"
                        );
                        $casper->wait(3000);
                    }

                    $casper->run();

                    $html = $casper->getHtml();
                    if ($html) {
                        $dom = HtmlDomParser::str_get_html($html);
                        if ($dom) {
                            if ($item->carrier == 'auspost') {
                                foreach($dom->find(".tracking-history-list .ng-star-inserted") as $div) {
                                    $details_div = $div->find('.details p', 0);
                                    $location_div = $div->find('.details p', 1);
                                    $time_div = $div->find('.time p', 0);

                                    $details = $details_div->plaintext;
                                    $location = $location_div->plaintext;
                                    $time = $time_div->plaintext;

                                    $this->CheckTrackingLogAndSave($item->id, $item->tracking_number, $details, $location, $time);
                                }

                                // check the delivery status, and update if changed.
                                foreach ($dom->find("app-milestones .milestone .status") as $status) {
                                    if ($item->status !== $status->plaintext) {
                                        $updated_status = new Repair_trackingModel();
                                        $updated_status->id = $item->id;
                                        $updated_status->status = $status->plaintext;
                                        $updated_status->status_e = $status->plaintext == 'Delivered' ? 2 : 1;
                                        $updated_status->Save();
                                    }
                                }
                            } elseif ($item->carrier == 'couriersplease') {
                                foreach ($dom->find(".classquoteTable table tr") as $table_tr) {
                                    $date_div = $table_tr->find('td', 0);
                                    $time_div = $table_tr->find('td', 1);
                                    $location_div = $table_tr->find('td', 2);
                                    $details_div = $table_tr->find('td', 3);

                                    $details = $details_div->plaintext;
                                    $location = $location_div->plaintext;
                                    $time = $date_div->plaintext . ' ' . $time_div->plaintext;

                                    $this->CheckTrackingLogAndSave($item->id, $item->tracking_number, $details, $location, $time);
                                }

                                // check the delivery status, and update if changed.
                                foreach ($dom->find('.trackingImg .active-img p') as $status) {
                                    $status_text = ($status->plaintext == 'Delivery') ? 'Delivered' : $status->plaintext;
                                    if ($item->status !== $status_text) {
                                        $this->UpdateTrackingStatus($item->id, $status_text);
                                    }
                                }
                            } elseif ($item->carrier == 'fastway') {
                                foreach ($dom->find("#trackDetailWrapper .trackDetail") as $div) {
                                    $details_div = $div->find('.track_column1 .status_heading', 0);
                                    $details_div1 = $div->find('.track_column1 .status_description', 0);
                                    $details = '<strong>'.$details_div->plaintext.', </strong>';
                                    $details .= $details_div1->plaintext;

                                    $location_div = $div->find('.track_column2 .track_locationName', 0);
                                    $location = $location_div->plaintext;

                                    $date_div = $div->find('.track_column3 .track_date', 0);
                                    $time_div = $div->find('.track_column3 .track_time', 0);
                                    $time = $date_div->plaintext . ' ' . $time_div->plaintext;

                                    $this->CheckTrackingLogAndSave($item->id, $item->tracking_number, $details, $location, $time);
                                }

                                // check the delivery status, and update if changed.
                                foreach ($dom->find(".infoHeaderRightDelivered .infoTitleContent .infoTitle") as $status) {
                                    if ($item->status !== $status->plaintext) {
                                        $this->UpdateTrackingStatus($item->id, $status->plaintext);
                                    }
                                }
                            } elseif ($item->carrier == 'apdparcel') {

                            }
                        }
                    }
                }
            }
        }
    }


    public function Technician_queue_jobs_24Action()
    {
        $this->logger->info("Technicians '24 hours in queue jobs alert emails' Started. Results/Errors will be logged next (if any).");

        $technicians = UsersModel::getAll("WHERE role = 'technician'");
        if ($technicians) {
            foreach ($technicians as $technician) {
                $technician_jobs = RepairsModel::getRepairs("WHERE stages.stage LIKE '%awaiting diagnosis%' && repairs.status = 'active' && repairs.technician_id = '$technician->id' && repairs.technician_24_email != 1 ");
                if ($technician_jobs) {
                    foreach ($technician_jobs as $repair) {
                        $diff = Helper::DateDiff($repair->created, date('Y-m-d H:i:s'), true);
                        if ($diff >= 1440) {
                            try {
                                $url = HOST_NAME . 'technician/job/'.$repair->id;

                                // variables to be replaced from the template to the real values the user sent.
                                $variables = array();
                                $variables['name'] = $technician->firstName.' '.$technician->lastName;
                                $variables['url'] = $url;
                                $variables['uid'] = $repair->job_id;
                                $variables['status'] = "This is an automated email from our system to alert you that job <strong>#".$repair->job_id."</strong> has been in queue for more than 24hrs (".(round($diff / 60, 2))."hrs)";
                                $variables['job_fields'] =
                                    "Device Type: " . $repair->device."<br>".
                                    "Manufacture: " . $repair->deviceManufacture."<br>";
                                if ($repair->insuranceNumber) {
                                    $variables['job_fields'] .= "Insurance Claim Number: ".$repair->insuranceNumber."<br>";
                                }
                                $variables['job_fields'] .=
                                    "Date Booked in & Time: " . Helper::ConvertDateFormat($repair->created, true)."<br>".
                                    "Reported Issue: " . $repair->issue."<br>".
                                    "Job Status: " . $repair->stage."<br>";

                                // check if the file exists, if not then create it.
                                if (!file_exists(EMAIL_TEMPLATES_PATH . 'technician-daily-job-notifications.html')) {
                                    touch(EMAIL_TEMPLATES_PATH . 'technician-daily-job-notifications.html');
                                }
                                // get the content of the file.
                                $template = file_get_contents(EMAIL_TEMPLATES_PATH . 'technician-daily-job-notifications.html');
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
                                $mail->subject = "Job in Queue for 24hrs Alert";
                                $mail->message = $template;

                                if ($mail->Send()) {
                                    $updated_repair = new RepairsModel();
                                    $updated_repair->id = $repair->id;
                                    $updated_repair->technician_24_email = 1;
                                    $updated_repair->Save();

                                    $this->logger->info("Job in queue for 24hrs alert email was sent to technician.", array('technician' => $technician->username, 'job_id' => $repair->job_id));
                                } else {
                                    throw new \Exception();
                                }
                            } catch (\Exception $e) {
                                $this->logger->error("Failed to send email to technician regarding job in queue for 24hrs", array('technician' => $technician->username, 'job_id' => $repair->job_id));
                            }
                        }
                    }
                }
            }
        }
    }

    public function Technicians_queue_jobs_48Action()
    {
        $this->logger->info("Technicians '48 hours in queue jobs alert emails' Started. Results/Errors will be logged next (if any).");

        $jobs = RepairsModel::getRepairs("WHERE stages.stage LIKE '%awaiting diagnosis%' && repairs.status = 'active' && repairs.technician_48_emails != 1 ");
        if ($jobs) {
            $technicians = TechniciansModel::getTechniciansDetails();
            foreach ($jobs as $repair) {
                $diff = Helper::DateDiff($repair->created, date('Y-m-d H:i:s'), true);
                if ($diff >= 2880) {
                    foreach ($technicians as $technician) {
                        try {
                            $url = HOST_NAME . 'technician/job/'.$repair->id;

                            // variables to be replaced from the template to the real values the user sent.
                            $variables = array();
                            $variables['name'] = $technician->firstName.' '.$technician->lastName;
                            $variables['url'] = $url;
                            $variables['uid'] = $repair->job_id;
                            $variables['status'] = "This is an automated email from our system to alert you that job <strong>#".$repair->job_id."</strong> has been in queue for more than 48hrs (".(round($diff / 60, 2))."hrs)";
                            $variables['job_fields'] =
                                "Device Type: " . $repair->device."<br>".
                                "Manufacture: " . $repair->deviceManufacture."<br>";
                            if ($repair->insuranceNumber) {
                                $variables['job_fields'] .= "Insurance Claim Number: ".$repair->insuranceNumber."<br>";
                            }
                            $variables['job_fields'] .=
                                "Date Booked in & Time: " . Helper::ConvertDateFormat($repair->created, true)."<br>".
                                "Reported Issue: " . $repair->issue."<br>".
                                "Job Status: " . $repair->stage."<br>".
                                "Assigned To: " . $repair->technician_name."<br>";

                            // check if the file exists, if not then create it.
                            if (!file_exists(EMAIL_TEMPLATES_PATH . 'technician-daily-job-notifications.html')) {
                                touch(EMAIL_TEMPLATES_PATH . 'technician-daily-job-notifications.html');
                            }
                            // get the content of the file.
                            $template = file_get_contents(EMAIL_TEMPLATES_PATH . 'technician-daily-job-notifications.html');
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
                            $mail->subject = "Job in Queue for 24hrs Alert";
                            $mail->message = $template;

                            if ($mail->Send()) {
                                $updated_repair = new RepairsModel();
                                $updated_repair->id = $repair->id;
                                $updated_repair->technician_48_emails = 1;
                                $updated_repair->Save();

                                $this->logger->info("Job in queue for 48hrs alert email was sent to technician.", array('technician' => $technician->username, 'job_id' => $repair->job_id));
                            } else {
                                throw new \Exception();
                            }
                        } catch (\Exception $e) {
                            $this->logger->error("Failed to send email to technician regarding job in queue for 48hrs", array('technician' => $technician->username, 'job_id' => $repair->job_id));
                        }
                    }
                }
            }
        }
    }

    public function Technicians_daily_jobs_reportAction()
    {
        $this->logger->info("Technicians 'Daily jobs report emails' Started. Results/Errors will be logged next (if any).");

        $jobs = RepairsModel::getRepairs("WHERE stages.stage NOT LIKE '%called customer, awaiting collection, completed%' && repairs.status = 'active'");
        foreach ($jobs as $repair) {
            $technician = UsersModel::getOne($repair->technician_id);
            if ($technician) {
                try {
                    $url = HOST_NAME . 'technician/job/'.$repair->id;

                    // variables to be replaced from the template to the real values the user sent.
                    $variables = array();
                    $variables['name'] = $technician->firstName.' '.$technician->lastName;
                    $variables['url'] = $url;
                    $variables['uid'] = $repair->job_id;
                    $variables['status'] = "This is a daily automated email from our system, containing job <strong>#".$repair->job_id."</strong> report";
                    $variables['job_fields'] =
                        "Device Type: " . $repair->device."<br>".
                        "Manufacture: " . $repair->deviceManufacture."<br>";
                    if ($repair->insuranceNumber) {
                        $variables['job_fields'] .= "Insurance Claim Number: ".$repair->insuranceNumber."<br>";
                    }
                    $variables['job_fields'] .=
                        "Date Booked in & Time: " . Helper::ConvertDateFormat($repair->created, true)."<br>".
                        "Reported Issue: " . $repair->issue."<br>".
                        "Job Status: " . $repair->stage."<br>";

                    // check if the file exists, if not then create it.
                    if (!file_exists(EMAIL_TEMPLATES_PATH . 'technician-daily-job-notifications.html')) {
                        touch(EMAIL_TEMPLATES_PATH . 'technician-daily-job-notifications.html');
                    }
                    // get the content of the file.
                    $template = file_get_contents(EMAIL_TEMPLATES_PATH . 'technician-daily-job-notifications.html');
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
                    $mail->subject = "Daily Jobs Report";
                    $mail->message = $template;

                    if ($mail->Send()) {
                        $this->logger->info("Daily jobs report email was sent to technician.", array('technician' => $technician->username, 'job_id' => $repair->job_id));
                    } else {
                        throw new \Exception();
                    }
                } catch (\Exception $e) {
                    $this->logger->error("Failed to send daily jobs report email to technician", array('technician' => $technician->username, 'job_id' => $repair->job_id));
                }
            }
        }
    }
    /* End Jobs System Cron Jobs */



    /* Quotes System Cron Jobs */
    public function Sync_leader_itemsAction()
    {
        $this->logger->info("LeaderSystems Products Sync Started. Errors will be logged next (if any).");

        $url = 'https://www.leadersystems.com.au/WSDataFeed.asmx/DownLoad?CustomerCode=U2FsdGVkX18aQJf3QsJ0RlFYzCqjNd4mEkajPTQLfvErPAhmTxeyF3Tq6kkRjqvE&WithHeading=true&WithLongDescription=true&DataType=0';
        file_put_contents(LEADER_DATAFEED_PATH."datafeed.zip", fopen($url, 'r'));

        if (file_exists(LEADER_DATAFEED_PATH.'datafeed.zip')) {
            $zip = new \ZipArchive;
            $res = $zip->open(LEADER_DATAFEED_PATH.'datafeed.zip');
            if ($res === TRUE) {
                $file_name = $zip->getNameIndex(0);
                $zip->extractTo(LEADER_DATAFEED_PATH);
                $zip->close();

                $existing_stock_code = Leader_itemsModel::getStockCode();
                if (file_exists(LEADER_DATAFEED_PATH.$file_name)) {
                    $inputFileType = IOFactory::identify(LEADER_DATAFEED_PATH.$file_name);
                    $reader = IOFactory::createReader($inputFileType);
                    $reader->setReadDataOnly(true);
                    $spreadsheet = $reader->load(LEADER_DATAFEED_PATH.$file_name);
                    $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

                    $varArray = array();
                    for ($i = 1; $i < sizeof($sheetData) + 1; $i++) {
                        $array = array_values($sheetData[$i]);
                        if ($array) {
//                            if ($i == 50) {break;}

                            if ($i == 1) {
                                foreach ($array as $array_row) {
                                    if (!empty($array_row)) {
                                        $varArray[$array_row] = Helper::getArrayKey($array, $array_row);
                                    }
                                }
                            }

                            if ($i > 1 && !empty($varArray)) {
                                $array_search = array_search($array[$varArray['STOCK CODE']], array_keys($existing_stock_code));
                                $obj = new Leader_itemsModel();
                                if ($array_search !== false && array_values($existing_stock_code)[$array_search][0]) {
                                    $obj->id = array_values($existing_stock_code)[$array_search][0];
                                }
                                $obj->StockCode = $array[$varArray['STOCK CODE']];
                                $obj->ProductName = $array[$varArray['SHORT DESCRIPTION']];
                                $obj->Description = $array[$varArray['LONG DESCRIPTION']];
                                $obj->CategoryCode = $array[$varArray['CATEGORY CODE']];
                                $obj->CategoryName = $array[$varArray['CATEGORY NAME']];
                                $obj->SubcategoryName = $array[$varArray['SUBCATEGORY NAME']];
                                $obj->BarCode = $array[$varArray['BAR CODE']];
                                $obj->Manufacturer = $array[$varArray['MANUFACTURER']];
                                $obj->ManufacturerSKU = $array[$varArray['MANUFACTURER SKU']];
                                $obj->DBP_Excl = floatval($array[$varArray['DBP']]) ?: 0;
                                $obj->DBP = floatval($array[$varArray['DBP']]) ? (floatval($array[$varArray['DBP']]) / 10) + floatval($array[$varArray['DBP']]) : 0;
                                $obj->RRP_Excl = floatval($array[$varArray['RRP']]) ?: 0;
                                $obj->RRP = floatval($array[$varArray['RRP']]) ? (floatval($array[$varArray['RRP']]) / 10) + floatval($array[$varArray['RRP']]) : 0;
                                $obj->NetoRRP = $this->GetNetoRetailPrice($obj->DBP);
                                $obj->IMAGE = $array[$varArray['IMAGE']];
                                $obj->Length = $array[$varArray['LENGTH']];
                                $obj->Width = $array[$varArray['WIDTH']];
                                $obj->Height = $array[$varArray['HEIGHT']];
                                $obj->Weight = $array[$varArray['WEIGHT']];
                                $obj->WarrantyLength = $array[$varArray['WARRANTY']];
                                $obj->AT = $array[$varArray['AT']];
                                $obj->AA = $array[$varArray['AA']];
                                $obj->AQ = $array[$varArray['AQ']];
                                $obj->AN = $array[$varArray['AN']];
                                $obj->AV = $array[$varArray['AV']];
                                $obj->AW = $array[$varArray['AW']];
                                $obj->ETAA = $array[$varArray['ETAA']];
                                $obj->ETAQ = $array[$varArray['ETAQ']];
                                $obj->ETAN = $array[$varArray['ETAN']];
                                $obj->ETAV = $array[$varArray['ETAV']];
                                $obj->ETAW = $array[$varArray['ETAW']];
                                $obj->Save();
                            }
                        }
                    }
                }
            } else {
                $this->logger->error("Failed to sync leadersystems items. failed to unzip downloaded datafeed csv file.");
            }
        } else {
            $this->logger->error("Failed to sync leadersystems items. failed to download the datafeed csv file.");
        }
    }

    public function Expire_quotesAction()
    {
        $this->logger->info("Check for expired quotes job started. Results/Errors will be logged next (if any).");

        $quotes = QuotesModel::getAll("WHERE expired != '1' && created < '".date('Y-m-d', strtotime('-7 Days'))."'");
        if ($quotes) {
            foreach ($quotes as $quote) {
                $expire = new QuotesModel();
                $expire->id = $quote->id;
                $expire->expired = '1';
                if ($expire->Save()) {
                    $this->logger->info("Quote expired!", ['Quote UID', $quote->uid]);
                }
            }
        }
    }
    /* End Quotes System Cron Jobs */



    /* CYW Neto Cron Jobs */
    public function Update_neto_productsAction()
    {
        // Warehouses IDs
        /*
         * 1 => New South Wales
         * 2 => Adelaide
         * 3 => Queensland
         * 4 => Western Australia
         * 5 => Victoria
         *
         * 2 3 1 5 4
         * */

        // LeaderitemsModel IDs
        /*
         * public $AA;
         * public $AQ;
         * public $AN;
         * public $AV;
         * public $AW;
         * */


//        $body = '{
//            "Item": [
//
//            {
//                "SKU": "92SFAN",
//                "WarehouseQuantity": [
//                    {
//                        "WarehouseID": 2,
//                        "Quantity": 10
//                    }, {
//                        "WarehouseID": 3,
//                        "Quantity": 11
//                    }, {
//                        "WarehouseID": 1,
//                        "Quantity": 11
//                    }, {
//                        "WarehouseID": 5,
//                        "Quantity": 11
//                    }, {
//                        "WarehouseID": 4,
//                        "Quantity": 11
//                    }
//                ],
//                "CostPrice": 20,
//                "RRP": 35,
//                "PriceGroups": {
//                    "PriceGroup": [{
//                      "Group": "1",
//                      "Price": 29.50
//                    }]
//                }
//            },
//
//               {
//                "SKU": "CBSCSILVD8CON",
//                "PriceGroups": {
//                    "PriceGroup": [{
//                      "Group": "1",
//                      "Price": 36
//                    }]
//                }
//            }
//
//            ]
//        }';




        $this->logger->info("Neto Products Update Started. Results/Errors will be logged next.");

        // first get neto's items in order to compare with leader items (update only when there's a difference, no need to update 8000+ items)
        //"Limit":100,
        $getItems_json = '{
            "Filter": {
                "Approved":true,
                
                "OutputSelector": ["WarehouseQuantity", "DefaultPrice", "CostPrice", "RRP", "PriceGroups"]
            }}';
        $all_neto_items = $this->CallNetoAPI('GetItem', $getItems_json);
        $all_neto_items_array = [];
        if ($all_neto_items && json_decode($all_neto_items) && json_decode($all_neto_items)->Item) {
            foreach (json_decode($all_neto_items)->Item as $neto_item) {
                $item_to_push = [
                    'SKU' => $neto_item->SKU,
                    'DefaultPrice' => $neto_item->DefaultPrice,
                    'CostPrice' => $neto_item->CostPrice,
                    'RRP' => $neto_item->RRP
                ];

                if (isset($neto_item->PriceGroups) && is_array($neto_item->PriceGroups)) {
                    if (isset($neto_item->PriceGroups[0]->PriceGroup) && is_array($neto_item->PriceGroups[0]->PriceGroup)) {
                        foreach ($neto_item->PriceGroups[0]->PriceGroup as $price_group) {
                            if ($price_group->Group == 'A' && $price_group->GroupID == '1') {
                                $item_to_push['RetailPrice'] = $price_group->Price;
                                break;
                            }
                        }
                    }
                }

                if (isset($neto_item->WarehouseQuantity) && is_array($neto_item->WarehouseQuantity)) {
                    foreach ($neto_item->WarehouseQuantity as $warehouse) {
                        switch ($warehouse->WarehouseID) {
                            case '1':
                                $item_to_push['NewSouthWales'] = $warehouse->Quantity;
                                break;
                            case '2':
                                $item_to_push['Adelaide'] = $warehouse->Quantity;
                                break;
                            case '3':
                                $item_to_push['Queensland'] = $warehouse->Quantity;
                                break;
                            case '4':
                                $item_to_push['WesternAustralia'] = $warehouse->Quantity;
                                break;
                            case '5':
                                $item_to_push['Victoria'] = $warehouse->Quantity;
                                break;
                        }
                    }
                }

                array_push($all_neto_items_array, $item_to_push);
            }
        } else {
            $this->logger->error("Neto Products Update Failed", ['Error' => "Failed to retrieve neto's existing products."]);
        }


        // get leadersystems items
        $all_items = Leader_itemsModel::getAll();
        $items_json_body = '{ "Item": [';
        $first_item_obj = true;
        if ($all_items) {
            foreach ($all_items as $item) {
                $search_neto_items_by_sku = array_search($item->StockCode, array_column($all_neto_items_array, 'SKU'));
                if ($search_neto_items_by_sku !== false) {
                    // check if prices or qoh match

                    $sanitized_dbp = FilterInput::Float($item->DBP_Excl) ?: 0;
                    $sanitized_rrp = FilterInput::Float($item->RRP) ?: 0;
                    $sanitized_retail_price = FilterInput::Float($item->NetoRRP) ?: 0;

                    if ($sanitized_dbp > 0 && $sanitized_rrp > 0 && $sanitized_retail_price > 0) {
                        if ($sanitized_retail_price != $all_neto_items_array[$search_neto_items_by_sku]['RetailPrice'] ||
                            $sanitized_dbp != $all_neto_items_array[$search_neto_items_by_sku]['CostPrice'] ||
                            $item->AA != $all_neto_items_array[$search_neto_items_by_sku]['Adelaide'] ||
                            $item->AQ != $all_neto_items_array[$search_neto_items_by_sku]['Queensland'] ||
                            $item->AN != $all_neto_items_array[$search_neto_items_by_sku]['NewSouthWales'] ||
                            $item->AV != $all_neto_items_array[$search_neto_items_by_sku]['Victoria'] ||
                            $item->AW != $all_neto_items_array[$search_neto_items_by_sku]['WesternAustralia']
                        ) {
                            $sanitized_aa = $item->AA == 'CALL' ? '' :
                                ($item->AA == '>5' ? 5 : $item->AA);
                            $sanitized_aa = FilterInput::Int($sanitized_aa) ?: 0;

                            $sanitized_aq = $item->AQ == 'CALL' ? '' :
                                ($item->AQ == '>5' ? 5 : $item->AQ);
                            $sanitized_aq = FilterInput::Int($sanitized_aq) ?: 0;

                            $sanitized_an = $item->AN == 'CALL' ? '' :
                                ($item->AN == '>5' ? 5 : $item->AN);
                            $sanitized_an = FilterInput::Int($sanitized_an) ?: 0;

                            $sanitized_av = $item->AV == 'CALL' ? '' :
                                ($item->AV == '>5' ? 5 : $item->AV);
                            $sanitized_av = FilterInput::Int($sanitized_av) ?: 0;

                            $sanitized_aw = $item->AW == 'CALL' ? '' :
                                ($item->AW == '>5' ? 5 : $item->AW);
                            $sanitized_aw = FilterInput::Int($sanitized_aw) ?: 0;


                            $json_item_comma = !$first_item_obj ? ', ' : '';
                            $json_item = '{
                            "SKU": "'.$item->StockCode.'",
                            "WarehouseQuantity": [ 
                                {
                                    "WarehouseID": 2,
                                    "Quantity": '.$sanitized_aa.'
                                }, {
                                    "WarehouseID": 3,
                                    "Quantity": '.$sanitized_aq.'
                                }, {
                                    "WarehouseID": 1,
                                    "Quantity": '.$sanitized_an.'
                                }, {
                                    "WarehouseID": 5,
                                    "Quantity": '.$sanitized_av.'
                                }, {
                                    "WarehouseID": 4,
                                    "Quantity": '.$sanitized_aw.'
                                }
                            ],
                            "CostPrice": '.$sanitized_dbp.',
                            "RRP": '.$sanitized_rrp.',
                            "PriceGroups": {
                                "PriceGroup": [{
                                  "Group": "1",
                                  "Price": '.$sanitized_retail_price.'
                                }]
                            } 
                        }';

                            // validate if object is json before adding it to the main json body
                            if (json_decode($json_item) == null) {
                                $this->logger->error("Neto Product Update Failed.", ['Error' => 'Bad JSON string', 'SKU' => $item->StockCode, 'JSON' => $json_item]);
                            } else {
                                $items_json_body .= $json_item_comma;
                                $items_json_body .= $json_item;
                            }

                            $first_item_obj = false;
                        }
                    }
                }
            }
        } else {
            $this->logger->error("Neto Products Update Failed", ['Error' => "Failed to retrieve leadersystems items from our database!"]);
        }

        $items_json_body .= '] }';

        $response = $this->CallNetoAPI('UpdateItem', $items_json_body);
        $response = json_decode($response) ? json_decode($response) : false;
        if (is_object($response)) {
            if (isset($response->Item)) {
//                $this->logger->info("Neto Products Updated.", ['Products' => str_replace('"', '', json_encode($response->Item))]);
                $this->logger->info("Neto Products Updated.");
            }
            if (isset($response->Messages)) {
                $this->logger->error("Neto Products Update Failed.", ['Response' => str_replace('"', '', json_encode($response->Messages))]);
            }
        } else {
            $this->logger->warning("Neto products update results unclear. Neto responded with a non-object.");
        }
    }
    /* End CYW Neto Cron Jobs */



    /* Licenses System Cron Jobs */
    // Daily
    public function Expire_digital_licensesAction()
    {
        $this->logger->info("Check for expired digital licenses started. Results/Errors will be logged next (if any).");

        $expired_licenses = Digital_licenses_assigned_licensesModel::getColumns(
            ['id', 'license_assign_id'],
            " expiration_date <= DATE('".date('Y-m-d')."') 
            && license_status != 'expired'"
        );

        if ($expired_licenses) {
            foreach ($expired_licenses as $expired_license) {
                $license_assign_details = Digital_licenses_assignModel::getSpecificLicenseAssignAllDetails($expired_license['license_assign_id'], $expired_license['id']);

                // expire digital_licenses_assign & digital_licenses_assigned_licenses && digital_licenses
                $license_assign = new Digital_licenses_assignModel();
                $license_assign->id = $license_assign_details->license_assign_id;
                $license_assign->status = 'expired';
                if ($license_assign->Save()) {
                    $license_assigned_license = new Digital_licenses_assigned_licensesModel();
                    $license_assigned_license->id = $license_assign_details->id;
                    $license_assigned_license->license_status = 'expired';
                    if ($license_assigned_license->Save()) {
                        $license = new Digital_licensesModel();
                        $license->id = $license_assign_details->license_id;
                        $license->expired = '1';
                        if ($license->Save()) {
                            $this->logger->info("Customer's license expired.", ['Assigned License ID' => $license_assign_details->id, 'License' => $license_assign_details->license]);

                            // Send email to customer & alerts@cyw.
                            if (file_exists(DIGITAL_LICENCES_TEMPLATES_PATH.'Your-Subscription-has-expired.html')) {
                                // Send notification email to customer.
                                $variables = [
                                    'IMAGES' => EMAIL_IMAGES_DIR,
                                    'URL' => HOST_NAME . 'index/unsubscribe/licenses?c=' . $license_assign_details->customer_id,

                                    'first_name' => $license_assign_details->firstName,
                                    'last_name' => $license_assign_details->lastName,
                                    'product_name' => $license_assign_details->item,
                                    'license_code' => $license_assign_details->license,
                                    'expiration_period' => Helper::getLicenseExpirationPeriod($license_assign_details->expiration_years, $license_assign_details->expiration_months),
                                    'expiration_period_in_days' => Helper::DateDiff(date('Y-m-d'), $license_assign_details->expiration_date)->days,
                                    'expiration_date' => date('d-m-Y', strtotime($license_assign_details->expiration_date))
                                ];

                                $template = file_get_contents(DIGITAL_LICENCES_TEMPLATES_PATH.'Your-Subscription-is-about-to-expire.html');
                                foreach ($variables as $key => $value) {
                                    $template = str_replace('{'.$key.'}', $value, $template);
                                }

                                $mail = new MailModel();
                                $mail->from_email = CONTACT_EMAIL;
                                $mail->from_name = CONTACT_NAME;
                                $mail->to_email = $license_assign_details->email;
                                $mail->to_name = $license_assign_details->firstName.' '.$license_assign_details->lastName;
                                $mail->cc = ["Compute Your World", "alerts@computeyourworld.com.au"];
                                $mail->subject = "Your License has Expired";
                                $mail->message = $template;
                                if ($mail->Send()) {
                                    $this->logger->info('License expired email notification was sent to customer successfully!', ['Customer' => $license_assign_details->firstName.' '.$license_assign_details->lastName, 'Product' => $license_assign_details->item, 'License' => $license_assign_details->license]);
                                } else {
                                    $this->logger->error('Failed to send 30 Days license expiration email notification to customer!', ['Customer' => $license_assign_details->firstName.' '.$license_assign_details->lastName, 'Product' => $license_assign_details->item, 'License' => $license_assign_details->license]);
                                }
                            } else {
                                $this->logger->error('Failed to send expired license email notification to customer! Email template doesn\'t exist!', ['Customer' => $license_assign_details->firstName.' '.$license_assign_details->lastName, 'Product' => $license_assign_details->item, 'License' => $license_assign_details->license, 'Template' => 'Your-Subscription-is-about-to-expire']);
                            }

                        } else {
                            $this->logger->error("Failed to expired customer's license. Digital_licensesModel Error.", ['Assigned License ID' => $expired_license->id, 'License' => $expired_license->license]);
                        }
                    } else {
                        $this->logger->error("Failed to expired customer's license. Digital_licenses_assigned_licensesModel Error.", ['Assigned License ID' => $expired_license->id, 'License' => $expired_license->license]);
                    }
                } else {
                    $this->logger->error("Failed to expired customer's license. Digital_licenses_assignModel Error.", ['Assigned License ID' => $expired_license->id, 'License' => $expired_license->license]);
                }
            }
        }
    }

    // Daily
    public function About_to_expire_digital_licenses_notificationsAction()
    {
        $this->logger->info("Check for 'about to expire digital licenses' started. Results/Errors will be logged next (if any).");

        $after_60_days = (new \DateTime())->modify('+60 days')->format('Y-m-d');
        $after_30_days = (new \DateTime())->modify('+30 days')->format('Y-m-d');
        $after_1_day = (new \DateTime())->modify('+1 days')->format('Y-m-d');

        $licenses_60_days = Digital_licenses_assigned_licensesModel::getColumns(
            ['id', 'license_assign_id'],
            " (expiration_date > DATE('".$after_30_days."') && DATE(expiration_date) <= DATE('".$after_60_days."')) 
            && license_status = 'active'
            && _60_days_notification != '1'"
        );
        $licenses_30_days = Digital_licenses_assigned_licensesModel::getColumns(
            ['id', 'license_assign_id'],
            " (expiration_date > DATE('".$after_1_day."') && DATE(expiration_date) <= DATE('".$after_30_days."')) 
            && license_status = 'active'
            && _30_days_notification != '1'"
        );
        $licenses_1_day = Digital_licenses_assigned_licensesModel::getColumns(
            ['id', 'license_assign_id'],
            " expiration_date <= DATE('".$after_1_day."') 
            && license_status = 'active'
            && _1_day_notification != '1'"
        );

        if ($licenses_60_days) {
            foreach ($licenses_60_days as $license_60_days) {
                $license_assign_details = Digital_licenses_assignModel::getSpecificLicenseAssignAllDetails($license_60_days['license_assign_id'], $license_60_days['id']);
                if ($license_assign_details->licensesNotifications != '1') {
                    $this->logger->info("Customer's license is about to expire in 60 days, but no notifications sent. customer unsubscribed.", ['Customer' => $license_assign_details->firstName.' '.$license_assign_details->lastName, 'Product' => $license_assign_details->item, 'License' => $license_assign_details->license]);
                } else {
                    if (file_exists(DIGITAL_LICENCES_TEMPLATES_PATH.'Your-Subscription-is-about-to-expire.html')) {
                        // Send notification email to customer.
                        $variables = [
                            'IMAGES' => EMAIL_IMAGES_DIR,
                            'URL' => HOST_NAME . 'index/unsubscribe/licenses?c=' . $license_assign_details->customer_id,

                            'first_name' => $license_assign_details->firstName,
                            'last_name' => $license_assign_details->lastName,
                            'product_name' => $license_assign_details->item,
                            'license_code' => $license_assign_details->license,
                            'expiration_period' => Helper::getLicenseExpirationPeriod($license_assign_details->expiration_years, $license_assign_details->expiration_months),
                            'expiration_period_in_days' => Helper::DateDiff(date('Y-m-d'), $license_assign_details->expiration_date)->days,
                            'expiration_date' => date('d-m-Y', strtotime($license_assign_details->expiration_date))
                        ];

                        $template = file_get_contents(DIGITAL_LICENCES_TEMPLATES_PATH.'Your-Subscription-is-about-to-expire.html');
                        foreach ($variables as $key => $value) {
                            $template = str_replace('{'.$key.'}', $value, $template);
                        }

                        $mail = new MailModel();
                        $mail->from_email = CONTACT_EMAIL;
                        $mail->from_name = CONTACT_NAME;
                        $mail->to_email = $license_assign_details->email;
                        $mail->to_name = $license_assign_details->firstName.' '.$license_assign_details->lastName;
                        $mail->subject = "Your License is About to Expire in 60 Days";
                        $mail->message = $template;
                        if ($mail->Send()) {
                            $this->logger->info('60 Days license expiration email notification was sent to customer successfully!', ['Customer' => $license_assign_details->firstName.' '.$license_assign_details->lastName, 'Product' => $license_assign_details->item, 'License' => $license_assign_details->license]);

                            $update_license = new Digital_licenses_assigned_licensesModel();
                            $update_license->id = $license_assign_details->id;
                            $update_license->_60_days_notification = '1';
                            $update_license->Save();
                        } else {
                            $this->logger->error('Failed to send 60 Days license expiration email notification to customer!', ['Customer' => $license_assign_details->firstName.' '.$license_assign_details->lastName, 'Product' => $license_assign_details->item, 'License' => $license_assign_details->license]);
                        }
                    } else {
                        $this->logger->error('Failed to send 1 Day license expiration email notification to customer! Email template doesn\'t exist!', ['Customer' => $license_assign_details->firstName.' '.$license_assign_details->lastName, 'Product' => $license_assign_details->item, 'License' => $license_assign_details->license, 'Template' => 'Your-Subscription-is-about-to-expire']);
                    }
                }
            }
        }

        if ($licenses_30_days) {
            foreach ($licenses_30_days as $license_30_days) {
                $license_assign_details = Digital_licenses_assignModel::getSpecificLicenseAssignAllDetails($license_30_days['license_assign_id'], $license_30_days['id']);
                if ($license_assign_details->licensesNotifications != '1') {
                    $this->logger->info("Customer's license is about to expire in 30 days, but no notifications sent. customer unsubscribed.", ['Customer' => $license_assign_details->firstName.' '.$license_assign_details->lastName, 'Product' => $license_assign_details->item, 'License' => $license_assign_details->license]);
                } else {
                    // Send notification email to customer.
                    if (file_exists(DIGITAL_LICENCES_TEMPLATES_PATH.'Your-Subscription-is-about-to-expire.html')) {
                        // Send notification email to customer.
                        $variables = [
                            'IMAGES' => EMAIL_IMAGES_DIR,
                            'URL' => HOST_NAME . 'index/unsubscribe/licenses?c=' . $license_assign_details->customer_id,

                            'first_name' => $license_assign_details->firstName,
                            'last_name' => $license_assign_details->lastName,
                            'product_name' => $license_assign_details->item,
                            'license_code' => $license_assign_details->license,
                            'expiration_period' => Helper::getLicenseExpirationPeriod($license_assign_details->expiration_years, $license_assign_details->expiration_months),
                            'expiration_period_in_days' => Helper::DateDiff(date('Y-m-d'), $license_assign_details->expiration_date)->days,
                            'expiration_date' => date('d-m-Y', strtotime($license_assign_details->expiration_date))
                        ];

                        $template = file_get_contents(DIGITAL_LICENCES_TEMPLATES_PATH.'Your-Subscription-is-about-to-expire.html');
                        foreach ($variables as $key => $value) {
                            $template = str_replace('{'.$key.'}', $value, $template);
                        }

                        $mail = new MailModel();
                        $mail->from_email = CONTACT_EMAIL;
                        $mail->from_name = CONTACT_NAME;
                        $mail->to_email = $license_assign_details->email;
                        $mail->to_name = $license_assign_details->firstName.' '.$license_assign_details->lastName;
                        $mail->subject = "Your License is About to Expire in 30 Days";
                        $mail->message = $template;
                        if ($mail->Send()) {
                            $this->logger->info('30 Days license expiration email notification was sent to customer successfully!', ['Customer' => $license_assign_details->firstName.' '.$license_assign_details->lastName, 'Product' => $license_assign_details->item, 'License' => $license_assign_details->license]);

                            $update_license = new Digital_licenses_assigned_licensesModel();
                            $update_license->id = $license_assign_details->id;
                            $update_license->_30_days_notification = '1';
                            $update_license->Save();
                        } else {
                            $this->logger->error('Failed to send 30 Days license expiration email notification to customer!', ['Customer' => $license_assign_details->firstName.' '.$license_assign_details->lastName, 'Product' => $license_assign_details->item, 'License' => $license_assign_details->license]);
                        }
                    } else {
                        $this->logger->error('Failed to send 1 Day license expiration email notification to customer! Email template doesn\'t exist!', ['Customer' => $license_assign_details->firstName.' '.$license_assign_details->lastName, 'Product' => $license_assign_details->item, 'License' => $license_assign_details->license, 'Template' => 'Your-Subscription-is-about-to-expire']);
                    }
                }
            }
        }

        if ($licenses_1_day) {
            foreach ($licenses_1_day as $license_1_day) {
                $license_assign_details = Digital_licenses_assignModel::getSpecificLicenseAssignAllDetails($license_1_day['license_assign_id'], $license_1_day['id']);
                if ($license_assign_details->licensesNotifications != '1') {
                    $this->logger->info("Customer's license will expire tomorrow, but no notifications sent. customer unsubscribed.", ['Customer' => $license_assign_details->firstName.' '.$license_assign_details->lastName, 'Product' => $license_assign_details->item, 'License' => $license_assign_details->license]);
                } else {
                    // Send notification email to customer.
                    if (file_exists(DIGITAL_LICENCES_TEMPLATES_PATH.'Your-Subscription-is-about-to-expire.html')) {
                        $variables = [
                            'IMAGES' => EMAIL_IMAGES_DIR,
                            'URL' => HOST_NAME . 'index/unsubscribe/licenses?c=' . $license_assign_details->customer_id,

                            'first_name' => $license_assign_details->firstName,
                            'last_name' => $license_assign_details->lastName,
                            'product_name' => $license_assign_details->item,
                            'license_code' => $license_assign_details->license,
                            'expiration_period' => Helper::getLicenseExpirationPeriod($license_assign_details->expiration_years, $license_assign_details->expiration_months),
                            'expiration_period_in_days' => Helper::DateDiff(date('Y-m-d'), $license_assign_details->expiration_date)->days,
                            'expiration_date' => date('d-m-Y', strtotime($license_assign_details->expiration_date))
                        ];


                        $template = file_get_contents(DIGITAL_LICENCES_TEMPLATES_PATH.'Your-Subscription-is-about-to-expire.html');
                        foreach ($variables as $key => $value) {
                            $template = str_replace('{'.$key.'}', $value, $template);
                        }

                        $mail = new MailModel();
                        $mail->from_email = CONTACT_EMAIL;
                        $mail->from_name = CONTACT_NAME;
                        $mail->to_email = $license_assign_details->email;
                        $mail->to_name = $license_assign_details->firstName.' '.$license_assign_details->lastName;
                        $mail->subject = "Your License will Expire Tomorrow";
                        $mail->message = $template;
                        if ($mail->Send()) {
                            $this->logger->info('1 Day license expiration email notification was sent to customer successfully!', ['Customer' => $license_assign_details->firstName.' '.$license_assign_details->lastName, 'Product' => $license_assign_details->item, 'License' => $license_assign_details->license]);

                            $update_license = new Digital_licenses_assigned_licensesModel();
                            $update_license->id = $license_assign_details->id;
                            $update_license->_1_day_notification = '1';
                            $update_license->Save();
                        } else {
                            $this->logger->error('Failed to send 1 Day license expiration email notification to customer!', ['Customer' => $license_assign_details->firstName.' '.$license_assign_details->lastName, 'Product' => $license_assign_details->item, 'License' => $license_assign_details->license]);
                        }
                    } else {
                        $this->logger->error('Failed to send 1 Day license expiration email notification to customer! Email template doesn\'t exist!', ['Customer' => $license_assign_details->firstName.' '.$license_assign_details->lastName, 'Product' => $license_assign_details->item, 'License' => $license_assign_details->license, 'Template' => 'Your-Subscription-is-about-to-expire']);
                    }
                }
            }
        }
    }
    /* End Licenses System Cron Jobs */




    /* Private Functions */
    private function UpdateTrackingStatus($id, $status)
    {
        $updated_status = new Repair_trackingModel();
        $updated_status->id = $id;
        $updated_status->status = $status;
        $updated_status->status_e = $status == 'Delivered' ? 2 : 1;
        $updated_status->Save();
    }

    private function CheckTrackingLogAndSave($repair_tracking_id, $tracking_number, $details, $location, $time)
    {
        $check_if_exists = Repair_tracking_logsModel::getAll(
            "WHERE repair_tracking_id = '$repair_tracking_id' &&
                    tracking_number = '$tracking_number' &&
                    details LIKE '%$details%' &&
                    location LIKE '%$location%' &&
                    date_time LIKE '%$time%'"
            );

        if (!$check_if_exists) {
            $tracking_log = new Repair_tracking_logsModel();
            $tracking_log->repair_tracking_id = $repair_tracking_id;
            $tracking_log->tracking_number = $tracking_number;
            $tracking_log->details = $details;
            $tracking_log->location = $location;
            $tracking_log->date_time = $time;
            if ($tracking_log->Save()) {
                $this->logger->info("Tracking log was added.", array('Tracking Number: ' => $tracking_log->tracking_number, 'Details: ' => $tracking_log->details));
            }
        }
    }

    private function CallNetoAPI($action, $body, $method = 'POST')
    {
        $endpoint = "https://store.computeyourworld.com.au/do/WS/NetoAPI";
        $headers = [
            "NETOAPI_KEY: Axhjwx2pVkgsjGEB6KHEorIYFawG7tNZ",
            "NETOAPI_ACTION: $action",
            "Content-Type: application/json",
            "Accept: application/json"
        ];


        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        curl_setopt($curl, CURLOPT_URL, $endpoint);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($curl);
        if (!$result) {
            die("Connection Failure");
        }
        curl_close($curl);
        return $result;
    }

    private function GetNetoRetailPrice($dbp)
    {
        $rrp = 0;
        if ($dbp) {
            $dbp = floatval($dbp);
            switch ($dbp) {
                case $dbp > 0 && $dbp <= 1000:
                    $rrp = (($dbp * 40) / 100) + $dbp;
                    break;
                case $dbp > 1000 && $dbp <= 1500:
                    $rrp = (($dbp * 30) / 100) + $dbp;
                    break;
                case $dbp > 1500 && $dbp <= 2000:
                    $rrp = (($dbp * 25) / 100) + $dbp;
                    break;
                case $dbp > 2000 && $dbp <= 3000 :
                    $rrp = (($dbp * 20) / 100) + $dbp;
                    break;
                case $dbp > 3000:
                    $rrp = (($dbp * 15) / 100) + $dbp;
                    break;
            }

            $rrp = $rrp ? number_format((float)$rrp, 2) : 0;
        }

        return $rrp;
    }



    public function testAction()
    {
        $dir = CONTROLLERS_PATH.'Orders';

        //list all files in a dir
//        if ($handle = opendir($dir)) {
//
//            while (false !== ($entry = readdir($handle))) {
//
//                if ($entry != "." && $entry != "..") {
//
//                    echo "$entry\n";
//                    echo "<br>";
//                }
//            }
//
//            closedir($handle);
//        }



        // read PDF and save to db
//        $exported_file = $dir.'/merged-pdfs-converted/manual.xlsx';
////         $exported_file = $dir.'/merged-pdfs-converted/Aberfoyle Hub Preschool 200720-converted.xlsx';
//        $inputFileType = IOFactory::identify($exported_file);
//        $reader = IOFactory::createReader($inputFileType);
//        $reader->setReadDataOnly(true);
//
////        $reader->setLoadAllSheets();
////        $sheets = $reader->listWorksheetNames($exported_file);
//        $spreadsheet = $reader->load($exported_file);
//        //        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
//
//
//        $sheetCount = $spreadsheet->getSheetCount();
//        for ($i = 0; $i < $sheetCount; $i++) {
//            $sheet = $spreadsheet->getSheet($i);
//            $sheetData = $sheet->toArray(null, true, true, true);
//
//            $data_table_started = false;
//            foreach ($sheetData as $sheetData_array) {
//                if ($data_table_started) {
//                    if ($sheetData_array['A'] && $sheetData_array['B'] && $sheetData_array['C']) {
//                        $existing = Np_pdf_ordersModel::getColumns(['id', 'quantity'], "code LIKE '%".$sheetData_array['A']."%'", true);
//
//                        $np_order = new Np_pdf_ordersModel();
//                        $np_order->code = $sheetData_array['A'];
//                        $np_order->details = $sheetData_array['B'];
//
//                        if ($existing) {
//                            $np_order->id = $existing['id'];
//                            $np_order->quantity = intval($existing['quantity']) + intval($sheetData_array['C']);
//                        } else {
//                            $np_order->quantity = intval($sheetData_array['C']);
//                        }
//
//                        var_dump($sheetData_array['A']);
//                        var_dump($sheetData_array['B']);
//                        var_dump($sheetData_array['C']);
//                        var_dump($np_order->Save());
//                        echo "<br><br><br>";
//                    }
//                }
//
//                if (strtolower($sheetData_array['A']) == 'code' && strtolower($sheetData_array['B']) == 'details') {
//                    $data_table_started = true;
//                }
//            }
//
//
//        }


        //export data to csv
//        $FileName = 'orders';
//        $header = array('Code', 'Details', 'Quantity');
//        header('Content-Type: text/csv');
//        header('Content-Disposition: attachment; filename=' . $FileName . '.csv');
//        header('Pragma: no-cache');
//        header("Expires: 0");
//        $outstream = fopen("php://output", "w");
//        fputcsv($outstream, $header);
//
//        $orders = Np_pdf_ordersModel::getAll();
//        foreach ($orders as $order) {
//            $row = array(
//                $order->code,
//                $order->details,
//                $order->quantity
//            );
//            fputcsv($outstream, $row);
//        }
//
//        exit();
    }
}