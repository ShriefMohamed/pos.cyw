<?php


namespace Framework\controllers;


use Framework\lib\AbstractController;
use Framework\lib\FilterInput;
use Framework\lib\Helper;
use Framework\lib\MailModel;
use Framework\lib\Redirect;
use Framework\lib\Request;
use Framework\models\CustomersModel;
use Framework\models\licenses\Digital_licenses_assigned_licensesModel;
use Framework\models\licenses\Digital_licenses_assignModel;
use Framework\models\licenses\Digital_licenses_templatesModel;
use Framework\models\licenses\Digital_licensesModel;
use Framework\models\pos\ItemsModel;
use Framework\models\UsersModel;

class LicensesController extends AbstractController
{
    public function DefaultAction()
    {
        $this->RenderPos();
    }


    public function LicensesAction()
    {
        $filter = Request::Check('status', 'get') ? Request::Get('status') : '';
        $where = '';
        if ($filter) {
            switch ($filter) {
                case 'not_assigned':
                    $where = "WHERE digital_licenses.used != '1' && digital_licenses.expired != '1'";
                    break;
                case 'assigned':
                    $where = "WHERE digital_licenses.used = '1' && digital_licenses.expired != '1'";
                    break;
                case 'expired':
                    $where = "WHERE digital_licenses.expired = '1'";
                    break;
                default:
                    $where = "";
                    break;
            }
        }

        $this->RenderPos([
            'data' => Digital_licensesModel::getLicensesWithItem($where)
        ]);
    }

    public function License_addAction()
    {
        if (Request::Check('save')) {
            $item_id = Request::Post('item');

            if ($item_id && $_POST['licenses']) {
                $expiration_year = Request::Post('expiration-year');
                $expiration_month = Request::Post('expiration-month');
                $template = Request::Post('email-template');

                $licenses = [];
                $licenses_by_lines = explode(PHP_EOL, $_POST['licenses']);
                foreach ($licenses_by_lines as $license_by_line) {
                    $license_by_comma = explode(',', $license_by_line);
                    if ($license_by_comma) {
                        $licenses = array_merge($license_by_comma, $licenses);
                    }
                }

                if ($licenses) {
                    $update_item = new ItemsModel();
                    $update_item->id = $item_id;
                    $update_item->is_digital = 1;
                    if (!$update_item->Save()) {
                        $this->logger->error("Failed to update product to digital.", Helper::AppendLoggedin(['Product ID' => $item_id]));
                    }

                    $licenses_saving_errors = false;

                    foreach ($licenses as $license) {
                        if (strlen(trim($license)) > 0) {
                            $license_obj = new Digital_licensesModel();
                            $license_obj->item_id = $item_id;
                            $license_obj->license = $license;
                            $license_obj->expiration_years = $expiration_year;
                            $license_obj->expiration_months = $expiration_month;
                            $license_obj->template = $template;
                            if (!$license_obj->Save()) {
                                $licenses_saving_errors = true;
                                $this->logger->error("License couldn't be saved, something wrong happened.", Helper::AppendLoggedin(['Product ID' => $item_id, 'Faulty License' => $license]));
                            }
                        }
                    }

                    if ($licenses_saving_errors) {
                        Helper::SetFeedback('error', "NOT ALL licenses were saved. check logs for specifics.");
                    } else {
                        Helper::SetFeedback('success', "Licenses were saved successfully.");
                    }
                    $this->logger->info("Licenses were saved successfully.", Helper::AppendLoggedin(['Product ID' => $item_id]));
                }
            } else {
                Helper::SetFeedback('error', "There were no licenses entered!");
            }

            Redirect::To('licenses/licenses');
        }


        $this->RenderPos([
            'items' => ItemsModel::getAll("WHERE status != 'archived'"),
            'templates' => Digital_licenses_templatesModel::getAll()
        ]);
    }


    public function License_assignAction($id = null)
    {
        if (Request::Check('save')) {
            $product_id = Request::Post('product');
            $selected_license_id = Request::Post('license-id');
            $selected_template = Request::Post('email-template');

            $selected_license = Digital_licensesModel::getLicensesWithItem("WHERE digital_licenses.id = '$selected_license_id'", true);


            $expiration_date = $selected_license->expiration_years > 0 ? date('d-m-Y', strtotime('+'.$selected_license->expiration_years.' years')) : date('d-m-Y');
            $expiration_date = $selected_license->expiration_months > 0 ? date('d-m-Y', strtotime($expiration_date.' +'.$selected_license->expiration_months.' months')) : $expiration_date;

            if (Request::Check('user-id') && Request::Check('customer-id')) {
                $user = new UsersModel();
                $user->id = Request::Post('user-id');
                $user->email = FilterInput::String(Request::Post('email'));
                $user->phone = FilterInput::String(Request::Post('phone'));
                $user->Save();

                if (Request::Check('address') || Request::Check('suburb') || Request::Check('zip')) {
                    $customer = new CustomersModel();
                    $customer->id = Request::Post('customer-id');
                    $customer->address = Request::Check('address') ? FilterInput::String(Request::Post('address')) : '';
                    $customer->suburb = Request::Check('suburb') ? FilterInput::String(Request::Post('suburb')) : '';
                    $customer->zip = Request::Check('zip') ? FilterInput::String(Request::Post('zip')) : '';
                    $customer->Save();
                }

                $customer_data = CustomersModel::getCustomer($user->id);

                $license_assign = new Digital_licenses_assignModel();
                $license_assign->customer_id = $customer_data->customer_id;
                $license_assign->product_id = $product_id;
                $license_assign->template = $selected_template;
                if ($license_assign->Save()) {
                    $license_assign_license = new Digital_licenses_assigned_licensesModel();
                    $license_assign_license->license_assign_id = $license_assign->id;
                    $license_assign_license->license_id = $selected_license_id;
                    $license_assign_license->license = $selected_license->license;
                    $license_assign_license->expiration_years = $selected_license->expiration_years;
                    $license_assign_license->expiration_months = $selected_license->expiration_months;
                    $license_assign_license->expiration_date = $expiration_date ? date('Y-m-d', strtotime($expiration_date)) : date('Y-m-d');
                    if ($license_assign_license->Save()) {
                        $this->logger->info("License was assigned to customer successfully.", Helper::AppendLoggedin(['License assign ID' => $license_assign->id]));
                        Helper::SetFeedback('success', "License was assigned to customer successfully.");

                        $update_license = new Digital_licensesModel();
                        $update_license->id = $selected_license_id;
                        $update_license->used = '1';
                        if (!$update_license->Save()) {
                            $this->logger->error("Failed to update license to mark it as used", Helper::AppendLoggedin(['License assign ID' => $license_assign->id]));
                        }

                        // send email to customer & to services@cyw with the license.
                        if (file_exists(DIGITAL_LICENCES_TEMPLATES_PATH.$selected_template.'.html')) {
                            $variables = [
                                'IMAGES' => EMAIL_IMAGES_DIR,
                                'URL' => HOST_NAME.'/index/unsubscribe',

                                'first_name' => Request::Post('f_name'),
                                'last_name' => Request::Post('l_name'),
                                'product_name' => $selected_license->item,
                                'license_code' => $selected_license->license,
                                'expiration_period' => Helper::getLicenseExpirationPeriod($selected_license->expiration_years, $selected_license->expiration_months),
                                'expiration_period_in_days' => Helper::DateDiff(date('Y-m-d'), $expiration_date)->days,
                                'expiration_date' => $expiration_date
                            ];

                            $template = file_get_contents(DIGITAL_LICENCES_TEMPLATES_PATH.$selected_template.'.html');
                            foreach ($variables as $key => $value) {
                                $template = str_replace('{'.$key.'}', $value, $template);
                            }

                            $mail = new MailModel();
                            $mail->from_email = CONTACT_EMAIL;
                            $mail->from_name = CONTACT_NAME;
                            $mail->to_email = $customer_data->email;
                            $mail->to_name = $customer_data->firstName.' '.$customer_data->lastName;
                            $mail->cc = ["Compute Your World", "service@computeyourworld.com.au"];
                            $mail->subject = "Your License Code From Compute Your World";
                            $mail->message = $template;
                            if ($mail->Send()) {
                                $this->logger->info('Email was sent with license code successfully!', Helper::AppendLoggedin(['Product' => $selected_license->item, 'License' => $selected_license->license]));
                                Helper::SetFeedback('success', "Email was sent with license code successfully!");
                                Redirect::To('licenses/customers');
                            } else {
                                $this->logger->error('Failed to send license code email!', Helper::AppendLoggedin(['Product' => $selected_license->item, 'License' => $selected_license->license]));
                                Helper::SetFeedback('error', "Failed to send license code email!");
                            }
                        } else {
                            $this->logger->error("Failed to send license email to customer. couldn't load selected template!", Helper::AppendLoggedin(['Product' => $selected_license->item, 'License' => $selected_license->license]));
                            Helper::SetFeedback('error', "Failed to send license email to customer. couldn't load selected template!");
                        }
                    } else {
                        $this->logger->error("Failed to assign license. saving license error!", Helper::AppendLoggedin(['Product' => $selected_license->item, 'License' => $selected_license->license]));
                        Helper::SetFeedback('error', "Failed to assign license. Something wrong while saving license!");
                    }
                } else {
                    $this->logger->error("Failed to assign license, saving license error!", Helper::AppendLoggedin(['Product' => $selected_license->item, 'License' => $selected_license->license]));
                    Helper::SetFeedback('error', "Failed to assign license. Something wrong while saving license details!");
                }
            } else {
                $this->logger->error("Failed to assign license. No customer error!", Helper::AppendLoggedin(['Product' => $selected_license->item, 'License' => $selected_license->license]));
                Helper::SetFeedback('error', "Failed to assign license. Something wrong while saving customer details!");
            }
        }


        $pre_selected_license = [];
        if ($id) {
            $license = Digital_licensesModel::getOne($id);
            if ($license && $license->used != '1' && $license->expired != '1') {
                $product_templates = [];
                $templates = array_column(Digital_licenses_templatesModel::getAll(), 'template_name');
                if (in_array($license->template, $templates) &&
                    file_exists(DIGITAL_LICENCES_TEMPLATES_PATH.$license->template.'.html')
                ) {
                    $product_templates[] = $license->template;
                }

                $pre_selected_license = ['license' => $license, 'templates' => $product_templates ?: $templates];
            }
        }
        $this->RenderPos([
            'products' => Digital_licensesModel::getLicensesWithItem("WHERE digital_licenses.used != '1' GROUP BY digital_licenses.item_id"),
            'pre_selected_license' => $pre_selected_license
        ]);
    }

    public function License_assign_previewAction()
    {
        if (isset($_POST) && !empty($_POST)) {
            $results = ['status' => false, 'msg' => '', 'result' => ''];

            $selected_license_id = Request::Post('license-id');
            $selected_license = Digital_licensesModel::getLicensesWithItem("WHERE digital_licenses.id = '$selected_license_id'", true);


            $selected_template = Request::Post('email-template');
            $expiration_year = $selected_license->expiration_years;
            $expiration_month = $selected_license->expiration_months;

            $expiration_date = $expiration_year > 0 ? date('d-m-Y', strtotime('+'.$expiration_year.' years')) : date('d-m-Y');
            $expiration_date = $expiration_month > 0 ? date('d-m-Y', strtotime($expiration_date.' +'.$expiration_month.' months')) : $expiration_date;

            if (file_exists(DIGITAL_LICENCES_TEMPLATES_PATH.$selected_template.'.html')) {
                $variables = array(
                    'IMAGES' => EMAIL_IMAGES_DIR,
                    'URL' => HOST_NAME.'/index/unsubscribe',
                    'first_name' => Request::Post('f_name'),
                    'last_name' => Request::Post('l_name'),
                    'product_name' => $selected_license->item,
                    'license_code' => $selected_license->license,
                    'expiration_period' => Helper::getLicenseExpirationPeriod($expiration_year, $expiration_month),
                    'expiration_period_in_days' => Helper::DateDiff(date('Y-m-d'), $expiration_date)->days,
                    'expiration_date' => $expiration_date,
                );

                $template = file_get_contents(DIGITAL_LICENCES_TEMPLATES_PATH.$selected_template.'.html');
                foreach ($variables as $key => $value) {
                    $template = str_replace('{'.$key.'}', $value, $template);
                }
                $results['status'] = true;
                $results['result'] = $template;
            } else {
                $results['msg'] = "Failed to generate preview, couldn't load selected template!";
            }

            die(json_encode($results));
        }
    }

    public function License_resendAction($assign_id, $assigned_licenses_id)
    {
        $assigned_license = Digital_licenses_assignModel::getSpecificLicenseAssignAllDetails($assign_id, $assigned_licenses_id);
        if ($assigned_license) {
            // send email to customer & to services@cyw with the license.
            if (file_exists(DIGITAL_LICENCES_TEMPLATES_PATH.$assigned_license->template.'.html')) {
                $variables = array(
                    'IMAGES' => EMAIL_IMAGES_DIR,
                    'URL' => HOST_NAME.'/index/unsubscribe',
                    'first_name' => $assigned_license->firstName,
                    'last_name' => $assigned_license->lastName,
                    'product_name' => $assigned_license->item,
                    'license_code' => $assigned_license->license,
                    'expiration_period' => Helper::getLicenseExpirationPeriod($assigned_license->expiration_years, $assigned_license->expiration_months),
                    'expiration_period_in_days' => Helper::DateDiff(date('Y-m-d'), $assigned_license->expiration_date)->days,
                    'expiration_date' => date('d-m-Y', strtotime($assigned_license->expiration_date)),
                );

                $template = file_get_contents(DIGITAL_LICENCES_TEMPLATES_PATH.$assigned_license->template.'.html');
                foreach ($variables as $key => $value) {
                    $template = str_replace('{'.$key.'}', $value, $template);
                }

                $mail = new MailModel();
                $mail->from_email = CONTACT_EMAIL;
                $mail->from_name = CONTACT_NAME;
                $mail->to_email = $assigned_license->email;
                $mail->to_name = $assigned_license->firstName.' '.$assigned_license->lastName;
                $mail->cc = ["Compute Your World", "service@computeyourworld.com.au"];
                $mail->subject = "Your License Code From Compute Your World";
                $mail->message = $template;
                if ($mail->Send()) {
                    $this->logger->info('Email was re-sent with license code successfully!', Helper::AppendLoggedin(['Product' => $assigned_license->item, 'License' => $assigned_license->license]));
                    Helper::SetFeedback('success', "Email was re-sent with license code successfully!");
                } else {
                    $this->logger->error('Failed to re-send license code email!', Helper::AppendLoggedin(['Product' => $assigned_license->item, 'License' => $assigned_license->license]));
                    Helper::SetFeedback('error', "Failed to re-send license code email!");
                }
            } else {
                $this->logger->error("Failed to re-send license email to customer. couldn't load selected template!", Helper::AppendLoggedin(['Product' => $assigned_license->item, 'License' => $assigned_license->license]));
                Helper::SetFeedback('error', "Failed to re-send license email to customer. couldn't load selected template!");
            }

            Redirect::To('licenses/customers');
        }
    }

    public function License_renewAction($assign_id)
    {
        $assign_record = Digital_licenses_assignModel::getLicenseAssignDetails($assign_id);
        $expires_after = Helper::DateDiff(date('Y-m-d'), $assign_record->expiration_date)->days;
        if ($expires_after) {
            Helper::SetFeedback('warning', "Existing assigned license expires after: $expires_after Days.");
        }

        $license = Digital_licensesModel::getAll("WHERE item_id = '$assign_record->product_id' && used != '1' && expired != '1'", true);
        if (!$license) {
            Helper::SetFeedback('error', "There's no available licenses for this product!");
        }

        $product_templates = [];
        $templates = array_column(Digital_licenses_templatesModel::getAll(), 'template_name');
        if (in_array($assign_record->template, $templates) &&
            file_exists(DIGITAL_LICENCES_TEMPLATES_PATH.$license->template.'.html')
        ) {
            $product_templates[] = $license->template;
        }

        if (Request::Check('save')) {
            $selected_template = Request::Post('email-template');

            $expiration_date = $license->expiration_years > 0 ? date('d-m-Y', strtotime('+'.$license->expiration_years.' years')) : date('d-m-Y');
            $expiration_date = $license->expiration_months > 0 ? date('d-m-Y', strtotime($expiration_date.' +'.$license->expiration_months.' months')) : $expiration_date;

            $license_assign = new Digital_licenses_assignModel();
            $license_assign->id = $assign_record->id;
            $license_assign->template = $selected_template;
            $license_assign->updated = date('Y-m-d H:i:s');
            if ($license_assign->Save()) {
                // set old license as expired (in case of early renewal)
                $expire_licenses = new Digital_licenses_assigned_licensesModel();
                $expire_licenses->license_status = 'expired';
                $expire_licenses->UpdateMany("license_assign_id = '$assign_record->id'");

                // assign the new license
                $license_assign_license = new Digital_licenses_assigned_licensesModel();
                $license_assign_license->license_assign_id = $assign_record->id;
                $license_assign_license->license_id = $license->id;
                $license_assign_license->license = $license->license;
                $license_assign_license->expiration_years = $license->expiration_years;
                $license_assign_license->expiration_months = $license->expiration_months;
                $license_assign_license->expiration_date = $expiration_date ? date('Y-m-d', strtotime($expiration_date)) : date('Y-m-d');
                if ($license_assign_license->Save()) {
                    $this->logger->info("License was renewed successfully.", Helper::AppendLoggedin(['License assign ID' => $assign_record->id]));
                    Helper::SetFeedback('success', "License was renewed successfully.");

                    //set license as used
                    $update_license = new Digital_licensesModel();
                    $update_license->id = $license->id;
                    $update_license->used = 1;
                    if (!$update_license->Save()) {
                        $this->logger->error("Failed to update license to mark it as used", Helper::AppendLoggedin(['License assign ID' => $assign_record->id]));
                    }

                    //set license assign as renewed
                    $update_license_assign = new Digital_licenses_assignModel();
                    $update_license_assign->id = $assign_record->id;
                    $update_license_assign->status = 'renewed';
                    $update_license_assign->updated = date('Y-m-d H:i:s');
                    if (!$update_license_assign->Save()) {
                        $this->logger->error("Failed to update license assign to mark it as renewed", Helper::AppendLoggedin(['License assign ID' => $assign_record->id]));
                    }


                    // send email to customer & to services@cyw with the license.
                    if (file_exists(DIGITAL_LICENCES_TEMPLATES_PATH.$selected_template.'.html')) {
                        $variables = array(
                            'IMAGES' => EMAIL_IMAGES_DIR,
                            'URL' => HOST_NAME.'/index/unsubscribe',
                            'first_name' => $assign_record->firstName,
                            'last_name' => $assign_record->lastName,
                            'product_name' => $assign_record->item,
                            'license_code' => $license->license,
                            'expiration_period' => Helper::getLicenseExpirationPeriod($license->expiration_years, $license->expiration_months),
                            'expiration_period_in_days' => Helper::DateDiff(date('Y-m-d'), $expiration_date)->days,
                            'expiration_date' => $expiration_date
                        );

                        $template = file_get_contents(DIGITAL_LICENCES_TEMPLATES_PATH.$selected_template.'.html');
                        foreach ($variables as $key => $value) {
                            $template = str_replace('{'.$key.'}', $value, $template);
                        }

                        $mail = new MailModel();
                        $mail->from_email = CONTACT_EMAIL;
                        $mail->from_name = CONTACT_NAME;
                        $mail->to_email = $assign_record->email;
                        $mail->to_name = $assign_record->firstName.' '.$assign_record->lastName;
                        $mail->cc = ["Compute Your World", "service@computeyourworld.com.au"];
                        $mail->subject = "Your License Code From Compute Your World";
                        $mail->message = $template;
                        if ($mail->Send()) {
                            $this->logger->info('Email was sent with renewal license code successfully!', Helper::AppendLoggedin(['Product' => $assign_record->item, 'License' => $license->license]));
                            Helper::SetFeedback('success', "Email was sent with renewal license code successfully!");
                            Redirect::To('licenses/customers');
                        } else {
                            $this->logger->error('Failed to send renewal license code email!', Helper::AppendLoggedin(['Product' => $assign_record->item, 'License' => $license->license]));
                            Helper::SetFeedback('error', "Failed to send renewal license code email!");
                        }
                    } else {
                        $this->logger->error("Failed to send renewal license email to customer. couldn't load selected template!", Helper::AppendLoggedin(['Product' => $assign_record->item, 'License' => $license->license]));
                        Helper::SetFeedback('error', "Failed to send renewal license email to customer. couldn't load selected template!");
                    }
                } else {
                    $this->logger->error("Failed to assign license. saving license error!", Helper::AppendLoggedin(['Product' => $assign_record->item, 'License' => $license->license]));
                    Helper::SetFeedback('error', "Failed to assign license. Something wrong while saving license!");
                }
            } else {
                $this->logger->error("Failed to assign license, saving license error!", Helper::AppendLoggedin(['Product' => $assign_record->item, 'License' => $license->license]));
                Helper::SetFeedback('error', "Failed to assign license. Something wrong while saving license details!");
            }
        }

        $this->RenderPos([
            'data' => $assign_record,
            'license' => $license,
            'templates' => $product_templates ?: $templates
        ]);
    }


    public function TemplatesAction()
    {
        $this->RenderPos([
            'data' => Digital_licenses_templatesModel::getTemplatesWithProducts()
        ]);
    }

    public function TemplateAction($id)
    {
        $original_template = Digital_licenses_templatesModel::getOne($id);
        if (Request::Check('save')) {
            if ($_POST['template']) {
                $template_content = $_POST['template'];

                $base_template = file_get_contents(EMAIL_TEMPLATES_PATH.'base-template.html');
                $base_template = str_replace('{{TEMPLATE}}', $template_content, $base_template);

                if (file_put_contents(DIGITAL_LICENCES_TEMPLATES_PATH.$original_template->template_name.'.html', $base_template)) {
                    $template = new Digital_licenses_templatesModel();
                    $template->id = $id;
                    $template->template = $template_content;
                    if ($template->Save()) {
                        Helper::SetFeedback('success', "Template was updated successfully.");
                        Redirect::To('licenses/templates');
                    } else {
                        Helper::SetFeedback('error', "Failed to update template.");
                    }
                } else {
                    Helper::SetFeedback('error', "Failed to update template.");
                }
            } else {
                Helper::SetFeedback('error', "Failed to update template, template text box seems to be empty!");
            }
        }

        $this->RenderPos([
            'template' => $original_template
        ]);
    }

    public function Template_addAction()
    {
        if (Request::Check('save')) {
            if ($_POST['template']) {
                $template_name = Request::Post('template-name') ?
                    str_replace(' ', '-', Request::Post('template-name')) :
                    substr(md5(microtime()),rand(0,26),4).'-template';
                $template_content = $_POST['template'];


                if (!file_exists(DIGITAL_LICENCES_TEMPLATES_PATH.$template_name.'.html')) {
                    $base_template = file_get_contents(EMAIL_TEMPLATES_PATH.'base-template.html');
                    $base_template = str_replace('{{TEMPLATE}}', $template_content, $base_template);

                    if (file_put_contents(DIGITAL_LICENCES_TEMPLATES_PATH.$template_name.'.html', $base_template)) {
                        $template = new Digital_licenses_templatesModel();
                        $template->template_name = $template_name;
                        $template->template = $template_content;
                        if ($template->Save()) {
                            Helper::SetFeedback('success', "Template was saved successfully.");
                            Redirect::To('licenses/templates');
                        }
                    } else {
                        Helper::SetFeedback('error', "Failed to write template.");
                    }
                } else {
                    Helper::SetFeedback('error', "Failed to write template, a file with the same name exists.");
                }
            } else {
                Helper::SetFeedback('error', "Failed to write template, template text box seems to be empty!");
            }
        }

        $this->RenderPos();
    }

    public function Template_previewAction($template = '')
    {
        $template = !$template ? EMAIL_TEMPLATES_PATH.'base-template.html' : DIGITAL_LICENCES_TEMPLATES_PATH.$template.'.html';
        if (file_exists($template)) {
            $template = file_get_contents($template);
            $variables = ['IMAGES' => EMAIL_IMAGES_DIR, 'URL' => HOST_NAME.'/index/unsubscribe',];
            foreach ($variables as $key => $value) {
                $template = str_replace('{'.$key.'}', $value, $template);
            }
            echo $template;
        }
    }



    public function ItemsAction()
    {
        $this->RenderPos([
            'data' => Digital_licensesModel::getItemsWithLicensesSummary()
        ]);
    }

    public function CustomersAction()
    {
        $this->RenderPos([
            'data' => Digital_licenses_assignModel::getCustomersWithLicenses()
        ]);
    }

}