<?php


namespace Framework\controllers;


use Framework\lib\AbstractController;
use Framework\lib\FilterInput;
use Framework\lib\Helper;
use Framework\lib\MailModel;
use Framework\lib\Redirect;
use Framework\lib\Request;
use Framework\Lib\Session;
use Framework\models\CustomersModel;
use Framework\models\pos\SalesModel;
use Framework\models\quotes\Leader_itemsModel;
use Framework\models\quotes\Quotes_customersModel;
use Framework\models\quotes\Quotes_itemsModel;
use Framework\models\quotes\QuotesModel;
use Framework\models\UsersModel;
use Mpdf\Mpdf;
use PHPMailer\PHPMailer\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpWord\MailMergeTemplateProcessor;
use PhpOffice\PhpWord\TemplateProcessor;


class QuotesController extends AbstractController
{
    public function DefaultAction()
    {
        $this->RenderPos();
    }

    public function CronAction($target)
    {
        $action = '';
        switch ($target) {
            case 'leader_items':
                $action = 'sync_leader_items';
                break;
            case 'neto_items':
                $action = 'update_neto_products';
                break;
            case 'expired_quotes':
                $action = 'expire_quotes';
                break;
        }

        if ($action) {
            $action_name = ucfirst($action) . 'Action';

            $cron = new CronController('cron', $action, $this->_params);
            if (method_exists($cron, $action_name)) {
                $cron->$action_name();
            }

            Helper::SetFeedback('success', "Sync finished. check CRON logs for results.");
        }

        Redirect::To('quotes');
    }

    public function QuotesAction()
    {
        $filter = 'WHERE quotes.expired ';
        if (Request::Check('ex', 'get')) {
            $filter .= " = '1'";
        } else {
            $filter .= " != '1'";
        }

        $this->RenderQuotes([
            'data' => QuotesModel::getQuotes($filter)
        ]);
    }

    public function QuoteAction($id)
    {
        if ($id !== false) {
            if (!empty($_POST)) {
                if (isset($_POST['items']) && !empty($_POST['items'])) {
                    $quote_action = Request::Check('save') ? 'save' : 'send';

                    $user = new UsersModel();
                    if (Request::Check('customer_user_id')) {
                        $user->id = Request::Post('customer_user_id');
                    }
                    $user->firstName = FilterInput::String(Request::Post('f_name'));
                    $user->lastName = FilterInput::String(Request::Post('l_name'));
                    $user->email = FilterInput::String(Request::Post('email'));
                    $user->phone = FilterInput::String(Request::Post('phone'));
                    $user->role = 'customer';
                    if ($user->Save()) {
                        $customer = new CustomersModel();
                        if (Request::Check('customer_id')) {
                            $customer->id = Request::Post('customer_id');
                        }
                        $customer->user_id = $user->id;
                        $customer->address = FilterInput::String(Request::Post('address'));
                        $customer->suburb = FilterInput::String(Request::Post('suburb'));
                        $customer->zip = FilterInput::String(Request::Post('zip'));
                        if ($customer->Save()) {
                            $customer_data = CustomersModel::getCustomer($user->id);

                            $quote = new QuotesModel();
                            $quote->id = $id;
                            $quote->uid = Request::Post('quote_uid');
                            $quote->customer_id = $customer->id;
                            $quote->printed_note = FilterInput::String(Request::Post('printed_note'));
                            $quote->internal_note = FilterInput::String(Request::Post('internal_note'));

                            $quote_total = 0;
                            foreach ($_POST['items'] as $item) {
                                $quote_total += floatval(str_replace(",", "", $item['item_price'])) * intval($item['item_qty']);
                            }

                            $quote->DBP = Request::Check('system_dbp') ? Request::Post('system_dbp') : 0;
                            $quote->margin = Request::Check('system_margin') ? Request::Post('system_margin') : 0;
                            $quote->system_total = Request::Check('system_total') ? Request::Post('system_total') : 0;
                            $quote->labor = Request::Check('labor') ? Request::Post('labor') : 0;

                            $quote_total = $quote_total + $quote->labor;

                            $quote->GST = $quote_total ? $quote_total / 10 : 0;
                            $quote->subtotal = $quote_total - ($quote->GST + $quote->labor);
                            $quote->total = $quote_total;


                            $quote->created_by = Session::Get('loggedin')->id;
                            if ($quote->Save()) {
                                foreach ($_POST['items'] as $item) {
                                    $quote_item = new Quotes_itemsModel();
                                    $quote_item->id = isset($item['quote_item_id']) && $item['quote_item_id'] ? $item['quote_item_id'] : null;
                                    $quote_item->quote_id = $quote->id;
                                    $quote_item->item_id = $item['item_id'];
                                    $quote_item->quantity = $item['item_qty'];
                                    $quote_item->component = $item['component'];
                                    $quote_item->merged = isset($_POST['item_merge_component'][$item['component']]) ? '1' : '0';
                                    $quote_item->item_name = $item['item_name'];
                                    $quote_item->item_sku = $item['item_sku'];
                                    $quote_item->original_price = $item['item_original_price'];
                                    $quote_item->price_margin = $item['item_price_percent'];
                                    $quote_item->price = $item['item_price'];
                                    if (!$quote_item->Save()) {
                                        $this->logger->error("Quote was updated, but some items weren't saved!", Helper::AppendLoggedin(['Quote UID' => $quote->uid, 'Item ID' => $quote_item->item_id]));
                                    }
                                }


                                $this->logger->info("Quote was updated successfully.", Helper::AppendLoggedin(['Quote UID' => $quote->uid]));

                                $saved_quote = QuotesModel::getQuote("WHERE quotes.id = '$quote->id'", true);

                                // Generate quote PDF
                                $templateProcessor = new MailMergeTemplateProcessor(PUBLIC_PATH.'Quote.docx');
                                $doc_variables = $templateProcessor->getMailMergeVariables();

                                $variables = array();
                                $variables['InvoiceTitle'] = "QUOTE";
                                $variables['ContactName'] = $customer_data->firstName.' '.$customer_data->lastName;
                                $variables['ContactPostalAddress'] = $customer_data->address . ', '.$customer_data->suburb.' '.$customer_data->zip;
                                $variables['InvoiceDate'] = date('d M Y', strtotime($saved_quote->created));
                                $variables['ExpiryDate'] = date('d M Y', strtotime($saved_quote->created.' +7 days'));
                                $variables['Reference'] = $saved_quote->lastName ?: ' ';
                                $variables['InvoiceNumber'] = 'Q-'.$saved_quote->quote_reference;
                                $variables['InvoiceCurrency'] = 'AUD';

                                $variables['Description'] = '';
                                $variables['Quantity'] = '';
                                $variables['UnitAmount'] = '';
                                $variables['DiscountPercentage'] = '';
                                $variables['LineAmount'] = '';

                                $quote_items = Quotes_itemsModel::getAll("WHERE quote_id = '$quote->id' ORDER BY merged DESC");
                                $counter = 1;
                                $opening_tag = "<w:r><w:rPr/><w:t>";
                                $closing_tag = "</w:t></w:r><w:br/><w:br/>";

//                        $total_system = $total = $subtotal = $gst = 0;
                                if ($quote_items) {
                                    foreach ($quote_items as $quote_item) {
//                                $total += floatval(str_replace(",", "", $quote_item->price)) * intval($quote_item->quantity);

                                        if ($counter > 1) {
                                            $variables['Description'] .= $opening_tag;
                                            $variables['Quantity'] .= $opening_tag;
                                            $variables['UnitAmount'] .= $opening_tag;
                                            $variables['DiscountPercentage'] .= $opening_tag;
                                            $variables['LineAmount'] .= $opening_tag;
                                        }

                                        $variables['Description'] .= FilterInput::CleanString($quote_item->item_name);

                                        if (!isset($_POST['item_merge_component'][$quote_item->component])) {
//                                    $total_system += floatval(str_replace(",", "", $quote_item->price)) * intval($quote_item->quantity);

                                            $variables['Quantity'] .= $quote_item->quantity;
                                            $variables['UnitAmount'] .= preg_quote('$').number_format(str_replace(",", "", $quote_item->price), 2);
                                            $variables['DiscountPercentage'] .= '0';
                                            $variables['LineAmount'] .= preg_quote('$').number_format(floatval(str_replace(",", "", $quote_item->price)) * $quote_item->quantity, 2);
                                        }

                                        if ($counter != count($quote_items)) {
                                            $variables['Description'] .= $closing_tag;
                                            $variables['Quantity'] .= $closing_tag;
                                            $variables['UnitAmount'] .= $closing_tag;
                                            $variables['DiscountPercentage'] .= $closing_tag;
                                            $variables['LineAmount'] .= $closing_tag;
                                        }

                                        $counter++;
                                    }
                                }


//                        $gst = floatval($total) / 10;
//                        $subtotal = floatval($total) - floatval($gst);

                                $variables['TotalDiscountAmount'] = '0';
                                $variables['InvoiceSubTotal'] = preg_quote('$').number_format($quote->subtotal, 2);
                                $variables['TaxCode'] = 'GST';
                                $variables['TaxTotal'] = preg_quote('$').number_format($quote->GST, 2);
                                $variables['InvoiceAmountDue'] = preg_quote('$').number_format($quote->total, 2);

                                foreach ($doc_variables as $doc_variable) {
                                    if (!in_array($doc_variable, array_keys($variables))) {
                                        $variables[$doc_variable] = ' ';
                                    }
                                }

                                $document = QUOTES_PATH.$quote->uid;
                                $templateProcessor->setMergeData($variables);
                                $templateProcessor->doMerge();
                                $templateProcessor->saveAs($document.'.docx');


                                if (file_exists($document.'.docx')) {
                                    exec("libreoffice --convert-to pdf ".$document.".docx --outdir ".QUOTES_PATH, $logs);

                                    if ($quote_action == 'send') {
                                        if (file_exists(QUOTES_PATH.$quote->uid.'.pdf')) {
                                            // Generate email
                                            $email_variables = array();
                                            $email_variables['CUSTOMER'] = $customer_data->firstName.' '.$customer_data->lastName;
                                            $email_variables['ADDRESS'] = $customer_data->address.', '.$customer_data->suburb.' '.$customer_data->zip;
                                            $email_variables['UID'] = $quote->uid;
                                            $email_variables['DATE'] = date('d M Y');
                                            $email_variables['PDF_URL'] = QUOTES_DIR.$quote->uid.'.pdf';
                                            $email_variables['QUOTE_URL'] = HOST_NAME.'index/quote_preview/'.$quote->id.'?c='.$customer->id;
                                            $email_variables['TOTAL'] = number_format($quote->total, 2);

                                            // Send the quote to customer.
                                            $mail = new MailModel();
                                            $mail->from_email = CONTACT_EMAIL;
                                            $mail->from_name = CONTACT_NAME;
                                            $mail->to_email = $customer_data->email;
                                            $mail->to_name = $customer_data->firstName.' '.$customer_data->lastName;
                                            $mail->subject = "Quote From Compute Your World";
                                            $mail->message = Helper::GenerateTemplate('quote-email-template', $email_variables);
                                            $mail->attachment = [QUOTES_PATH.$quote->uid.'.pdf'];

                                            if ($mail->Send()) {
                                                $quote_sent = new QuotesModel();
                                                $quote_sent->id = $quote->id;
                                                $quote_sent->status = 'sent';
                                                $quote_sent->Save();

                                                $this->logger->info("Quote email was sent to customer.", Helper::AppendLoggedin(['Quote ID' => $quote->uid]));
                                                Helper::SetFeedback('success', "Quote email was sent to customer successfully.");
                                            } else {
                                                $this->logger->error('Failed to send quote email!', Helper::AppendLoggedin(['Quote ID' => $quote->uid]));
                                                Helper::SetFeedback('error', "Failed to send quote email!");
                                            }
                                        } else {
                                            $this->logger->error('Failed to send quote email! Couldn\'t generate PDF receipt.', Helper::AppendLoggedin(['Quote ID' => $quote->uid]));
                                            Helper::SetFeedback('error', "Failed to send quote email! Couldn't generate PDF receipt.");
                                        }
                                    }
                                } else {
                                    $this->logger->error('Failed to save quote! Couldn\'t generate Docx receipt.', Helper::AppendLoggedin(['Quote ID' => $quote->uid]));
                                    Helper::SetFeedback('error', "Failed to save quote! Couldn't generate PDF receipt.");
                                }

                                Redirect::To('quotes/quotes');
                            } else {
                                // quote saving error
                                $dll_customer = new CustomersModel();
                                $dll_customer->id = $customer->id;
                                $dll_customer->Delete();

                                $this->logger->error("Failed to update quote. Quote error!", Helper::AppendLoggedin([]));
                                Helper::SetFeedback('error', "Failed to update quote. Something wrong while saving quote!");
                            }
                        } else {
                            // customer issue
                            $this->logger->error("Failed to update quote. Customer error!", Helper::AppendLoggedin([]));
                            Helper::SetFeedback('error', "Failed to update quote. Something wrong while saving customer details!");
                        }
                    } else {
                        $this->logger->error("Failed to save quote. User error!", Helper::AppendLoggedin([]));
                        Helper::SetFeedback('error', "Failed to save quote. Something wrong while saving customer details!");
                    }
                } else {
                    $this->logger->error("Failed to update quote. No items were selected!", Helper::AppendLoggedin([]));
                    Helper::SetFeedback('error', "Failed to update quote. No items were selected!");
                }
            }




            $order = array('Cases & Accessories', 'Motherboards', 'CPU', 'Fan & Cooling Products', 'Memory', 'Hard Disk Drives - SSD', 'Hard Disk Drives - SATA', 'Video/Graphics Cards', 'Power Supplies', 'DVD & Bluray Drives', 'Network - Consumer', 'Software');
            $categories = Leader_itemsModel::getCategories() ? array_values(Leader_itemsModel::getCategories()) : false;
            $categories = $categories ? Helper::sortArrayByArray($categories, $order) : false;

            $quote = QuotesModel::getOne($id);
            $quote_customer = CustomersModel::getCustomersForQuotes(" && customers.id = '$quote->customer_id'", true);
            $quote_items = Quotes_itemsModel::getQuoteItems("WHERE quote_id = '$id' ORDER BY component");

            $quote_items_grouped = [];
            foreach ($quote_items as $quote_item) {
                $quote_items_grouped[$quote_item->component][] = $quote_item;
            }

            $this->RenderQuotes([
                'categories' => $categories,
                'quote' => $quote,
                'quote_customer' => $quote_customer,
                'quote_items' => $quote_items_grouped,
                'max_price' => Leader_itemsModel::getColumns(['MAX(DBP)'], '1', true)
            ]);
        }
    }

    public function Quote_addAction()
    {
        if (!empty($_POST)) {
            if (isset($_POST['items']) && !empty($_POST['items'])) {
                $quote_action = Request::Check('save') ? 'save' : 'send';

                $user = new UsersModel();
                if (Request::Check('customer_user_id')) {
                    $user->id = Request::Post('customer_user_id');
                }
                $user->firstName = FilterInput::String(Request::Post('f_name'));
                $user->lastName = FilterInput::String(Request::Post('l_name'));
                $user->email = FilterInput::String(Request::Post('email'));
                $user->phone = FilterInput::String(Request::Post('phone'));
                $user->role = 'customer';
                if ($user->Save()) {
                    $customer = new CustomersModel();
                    if (Request::Check('customer_id')) {
                        $customer->id = Request::Post('customer_id');
                    }
                    $customer->user_id = $user->id;
                    $customer->address = FilterInput::String(Request::Post('address'));
                    $customer->suburb = FilterInput::String(Request::Post('suburb'));
                    $customer->zip = FilterInput::String(Request::Post('zip'));

                    if ($customer->Save()) {
                        $customer_data = CustomersModel::getCustomer($user->id);

                        $quote = new QuotesModel();
                        $quote->uid = strtoupper(substr(md5(microtime()),rand(0,26),8));

                        $quote->customer_id = $customer->id;
                        $quote->printed_note = FilterInput::String(Request::Post('printed_note'));
                        $quote->internal_note = FilterInput::String(Request::Post('internal_note'));

                        $quote_total = 0;
                        foreach ($_POST['items'] as $item) {
                            $quote_total += floatval(str_replace(",", "", $item['item_price'])) * intval($item['item_qty']);
                        }

                        $quote->DBP = Request::Check('system_dbp') ? Request::Post('system_dbp') : 0;
                        $quote->margin = Request::Check('system_margin') ? Request::Post('system_margin') : 0;
                        $quote->system_total = Request::Check('system_total') ? Request::Post('system_total') : 0;
                        $quote->labor = Request::Check('labor') ? Request::Post('labor') : 0;

                        $quote_total = $quote_total + $quote->labor;

                        $quote->GST = $quote_total ? $quote_total / 10 : 0;
                        $quote->subtotal = $quote_total - ($quote->GST + $quote->labor);
                        $quote->total = $quote_total;


                        $quote->created_by = Session::Get('loggedin')->id;
                        if ($quote->Save()) {
                            foreach ($_POST['items'] as $item) {
                                $quote_item = new Quotes_itemsModel();
                                $quote_item->quote_id = $quote->id;
                                $quote_item->item_id = $item['item_id'];
                                $quote_item->quantity = $item['item_qty'];
                                $quote_item->component = $item['component'];
                                $quote_item->merged = isset($_POST['item_merge_component'][$item['component']]) ? '1' : '0';
                                $quote_item->item_name = $item['item_name'];
                                $quote_item->item_sku = $item['item_sku'];
                                $quote_item->original_price = $item['item_original_price'];
                                $quote_item->price_margin = $item['item_price_percent'];
                                $quote_item->price = $item['item_price'];
                                if (!$quote_item->Save()) {
                                    $this->logger->error("Quote was created, but some items weren't saved!", Helper::AppendLoggedin(['Quote UID' => $quote->uid, 'Item ID' => $quote_item->item_id]));
                                }
                            }


                            $this->logger->info("Quote was saved successfully.", Helper::AppendLoggedin(['Quote UID' => $quote->uid]));

                            $saved_quote = QuotesModel::getQuote("WHERE quotes.id = '$quote->id'", true);

                            // Generate quote PDF
                            $templateProcessor = new MailMergeTemplateProcessor(PUBLIC_PATH.'Quote.docx');
                            $doc_variables = $templateProcessor->getMailMergeVariables();

                            $variables = array();
                            $variables['InvoiceTitle'] = "QUOTE";
                            $variables['ContactName'] = $customer_data->firstName.' '.$customer_data->lastName;
                            $variables['ContactPostalAddress'] = $customer_data->address . ', '.$customer_data->suburb.' '.$customer_data->zip;
                            $variables['InvoiceDate'] = date('d M Y');
                            $variables['ExpiryDate'] = date('d M Y', strtotime('+7 days'));
                            $variables['Reference'] = $saved_quote->lastName ?: ' ';
                            $variables['InvoiceNumber'] = 'Q-'.$saved_quote->quote_reference;
                            $variables['InvoiceCurrency'] = 'AUD';

                            $variables['Description'] = '';
                            $variables['Quantity'] = '';
                            $variables['UnitAmount'] = '';
                            $variables['DiscountPercentage'] = '';
                            $variables['LineAmount'] = '';

                            $quote_items = Quotes_itemsModel::getAll("WHERE quote_id = '$quote->id' ORDER BY merged DESC");
                            $counter = 1;
                            $opening_tag = "<w:r><w:rPr/><w:t>";
                            $closing_tag = "</w:t></w:r><w:br/><w:br/>";

//                        $total_system = $total = $subtotal = $gst = 0;
                            if ($quote_items) {
                                foreach ($quote_items as $quote_item) {
//                                $total += floatval(str_replace(",", "", $quote_item->price)) * intval($quote_item->quantity);

                                    if ($counter > 1) {
                                        $variables['Description'] .= $opening_tag;
                                        $variables['Quantity'] .= $opening_tag;
                                        $variables['UnitAmount'] .= $opening_tag;
                                        $variables['DiscountPercentage'] .= $opening_tag;
                                        $variables['LineAmount'] .= $opening_tag;
                                    }

                                    $variables['Description'] .= FilterInput::CleanString($quote_item->item_name);

                                    if (!isset($_POST['item_merge_component'][$quote_item->component])) {
//                                    $total_system += floatval(str_replace(",", "", $quote_item->price)) * intval($quote_item->quantity);

                                        $variables['Quantity'] .= $quote_item->quantity;
                                        $variables['UnitAmount'] .= preg_quote('$').number_format(str_replace(",", "", $quote_item->price), 2);
                                        $variables['DiscountPercentage'] .= '0';
                                        $variables['LineAmount'] .= preg_quote('$').number_format(floatval(str_replace(",", "", $quote_item->price)) * $quote_item->quantity, 2);
                                    }

                                    if ($counter != count($quote_items)) {
                                        $variables['Description'] .= $closing_tag;
                                        $variables['Quantity'] .= $closing_tag;
                                        $variables['UnitAmount'] .= $closing_tag;
                                        $variables['DiscountPercentage'] .= $closing_tag;
                                        $variables['LineAmount'] .= $closing_tag;
                                    }

                                    $counter++;
                                }
                            }


//                        $gst = floatval($total) / 10;
//                        $subtotal = floatval($total) - floatval($gst);

                            $variables['TotalDiscountAmount'] = '0';
                            $variables['InvoiceSubTotal'] = preg_quote('$').number_format($quote->subtotal, 2);
                            $variables['TaxCode'] = 'GST';
                            $variables['TaxTotal'] = preg_quote('$').number_format($quote->GST, 2);
                            $variables['InvoiceAmountDue'] = preg_quote('$').number_format($quote->total, 2);

                            foreach ($doc_variables as $doc_variable) {
                                if (!in_array($doc_variable, array_keys($variables))) {
                                    $variables[$doc_variable] = ' ';
                                }
                            }

                            $document = QUOTES_PATH.$quote->uid;
                            $templateProcessor->setMergeData($variables);
                            $templateProcessor->doMerge();
                            $templateProcessor->saveAs($document.'.docx');


                            if (file_exists($document.'.docx')) {
                                exec("libreoffice --convert-to pdf ".$document.".docx --outdir ".QUOTES_PATH, $logs);

                                if ($quote_action == 'send') {
                                    if (file_exists(QUOTES_PATH.$quote->uid.'.pdf')) {
                                        // Generate email
                                        $email_variables = array();
                                        $email_variables['CUSTOMER'] = $customer_data->firstName.' '.$customer_data->lastName;
                                        $email_variables['ADDRESS'] = $customer_data->address.', '.$customer_data->suburb.' '.$customer_data->zip;
                                        $email_variables['UID'] = $quote->uid;
                                        $email_variables['DATE'] = date('d M Y');
                                        $email_variables['PDF_URL'] = QUOTES_DIR.$quote->uid.'.pdf';
                                        $email_variables['QUOTE_URL'] = HOST_NAME.'index/quote_preview/'.$quote->id.'?c='.$customer->id;
                                        $email_variables['TOTAL'] = number_format($quote->total, 2);

                                        // Send the quote to customer.
                                        $mail = new MailModel();
                                        $mail->from_email = CONTACT_EMAIL;
                                        $mail->from_name = CONTACT_NAME;
                                        $mail->to_email = $customer_data->email;
                                        $mail->to_name = $customer_data->firstName.' '.$customer_data->lastName;
                                        $mail->subject = "Quote From Compute Your World";
                                        $mail->message = Helper::GenerateTemplate('quote-email-template', $email_variables);
                                        $mail->attachment = [QUOTES_PATH.$quote->uid.'.pdf'];

                                        if ($mail->Send()) {
                                            $quote_sent = new QuotesModel();
                                            $quote_sent->id = $quote->id;
                                            $quote_sent->status = 'sent';
                                            $quote_sent->Save();

                                            $this->logger->info("Quote email was sent to customer.", Helper::AppendLoggedin(['Quote ID' => $quote->uid]));
                                            Helper::SetFeedback('success', "Quote email was sent to customer successfully.");
                                        } else {
                                            $this->logger->error('Failed to send quote email!', Helper::AppendLoggedin(['Quote ID' => $quote->uid]));
                                            Helper::SetFeedback('error', "Failed to send quote email!");
                                        }
                                    } else {
                                        $this->logger->error('Failed to send quote email! Couldn\'t generate PDF receipt.', Helper::AppendLoggedin(['Quote ID' => $quote->uid]));
                                        Helper::SetFeedback('error', "Failed to send quote email! Couldn't generate PDF receipt.");
                                    }
                                }
                            } else {
                                $this->logger->error('Failed to save quote! Couldn\'t generate Docx receipt.', Helper::AppendLoggedin(['Quote ID' => $quote->uid]));
                                Helper::SetFeedback('error', "Failed to save quote! Couldn't generate PDF receipt.");
                            }

                            Redirect::To('quotes/quotes');
                        } else {
                            // quote saving error
                            $dll_customer = new CustomersModel();
                            $dll_customer->id = $customer->id;
                            $dll_customer->Delete();

                            $this->logger->error("Failed to save quote. Quote error!", Helper::AppendLoggedin([]));
                            Helper::SetFeedback('error', "Failed to save quote. Something wrong while saving quote!");
                        }
                    } else {
                        $this->logger->error("Failed to save quote. Customer error!", Helper::AppendLoggedin([]));
                        Helper::SetFeedback('error', "Failed to save quote. Something wrong while saving customer details!");
                    }
                } else {
                    $this->logger->error("Failed to save quote. User error!", Helper::AppendLoggedin([]));
                    Helper::SetFeedback('error', "Failed to save quote. Something wrong while saving customer details!");
                }
            } else {
                $this->logger->error("Failed to save quote. No items were selected!", Helper::AppendLoggedin([]));
                Helper::SetFeedback('error', "Failed to save quote. No items were selected!");
            }
        }

        $order = array('Cases & Accessories', 'Motherboards', 'CPU', 'Fan & Cooling Products', 'Memory', 'Hard Disk Drives - SSD', 'Hard Disk Drives - SATA', 'Video/Graphics Cards', 'Power Supplies', 'DVD & Bluray Drives', 'Network - Consumer', 'Software');
        $categories = Leader_itemsModel::getCategories() ? array_values(Leader_itemsModel::getCategories()) : false;
        $categories = $categories ? Helper::sortArrayByArray($categories, $order) : false;

        $this->RenderQuotes([
            'categories' => $categories,
            'sub_categories' => Leader_itemsModel::getSubCategories(),
            'manufacturers' => Leader_itemsModel::getManufacturers(),
            'max_price' => Leader_itemsModel::getColumns(['MAX(DBP)'], '1', true)
        ]);
    }


    public function Quote_previewAction()
    {
        $results = ['status' => false, 'msg' => '', 'result' => ''];

        if (!empty($_POST)) {
            if (isset($_POST['items']) && !empty($_POST['items'])) {
                $total = 0;
                foreach ($_POST['items'] as $item) {
                    $total += floatval(str_replace(",", "", $item['item_price'])) * intval($item['item_qty']);
                }

                // generate template
                $templateProcessor = new MailMergeTemplateProcessor(PUBLIC_PATH.'Quote.docx');
                $doc_variables = $templateProcessor->getMailMergeVariables();

                $variables = array();
                $variables['InvoiceTitle'] = "QUOTE";
                $variables['ContactName'] = Request::Post('f_name').' '.Request::Post('l_name');
                $variables['ContactPostalAddress'] = Request::Post('address') . ', '.Request::Post('suburb').' '.Request::Post('zip');
                $variables['InvoiceDate'] = date('d M Y');
                $variables['ExpiryDate'] = date('d M Y', strtotime('+7 days'));
                $variables['Reference'] = Request::Post('l_name');
                $variables['InvoiceNumber'] = 'Q-'.QuotesModel::getNextID()->next_reference;
                $variables['InvoiceCurrency'] = 'AUD';

                $variables['Description'] = '';
                $variables['Quantity'] = '';
                $variables['UnitAmount'] = '';
                $variables['DiscountPercentage'] = '';
                $variables['LineAmount'] = '';

                $items_merge_count = 0;
                foreach ($_POST['items'] as $count_item) {
                    if (isset($_POST['item_merge_component'][$count_item['component']])) {
                        $items_merge_count++;
                    }
                }

                $total = 0;
                $counter = 1;
                $opening_tag = "<w:r><w:rPr/><w:t>";
                $closing_tag = "</w:t></w:r><w:br/><w:br/>";

                foreach ($_POST['items'] as $quote_item) {
                    $total += floatval(str_replace(",", "", $quote_item['item_price'])) * intval($quote_item['item_qty']);

                    if ($counter > 1) {
                        $variables['Description'] .= $opening_tag;
                        $variables['Quantity'] .= $opening_tag;
                        $variables['UnitAmount'] .= $opening_tag;
                        $variables['DiscountPercentage'] .= $opening_tag;
                        $variables['LineAmount'] .= $opening_tag;
                    }

                    $variables['Description'] .= FilterInput::CleanString($quote_item['item_name']);

                    if (!isset($_POST['item_merge_component'][$quote_item['component']])) {
                        $variables['Quantity'] .= $quote_item['item_qty'];
                        $variables['UnitAmount'] .= preg_quote('$').number_format(str_replace(",", "", $quote_item['item_price']), 2);
                        $variables['DiscountPercentage'] .= '0';
                        $variables['LineAmount'] .= preg_quote('$').number_format(floatval(str_replace(",", "", $quote_item['item_price'])) * $quote_item['item_qty'], 2);
                    }

                    if ($counter != count($_POST['items'])) {
                        $variables['Description'] .= $closing_tag;
                        $variables['Quantity'] .= $closing_tag;
                        $variables['UnitAmount'] .= $closing_tag;
                        $variables['DiscountPercentage'] .= $closing_tag;
                        $variables['LineAmount'] .= $closing_tag;
                    }

                    $counter++;
                }

                $total_system = Request::Check('system_total') ? Request::Post('system_total') : 0;
                $labor_amount = Request::Post('labor');
                $total = $total + $labor_amount;
                $gst = floatval($total) / 10;
                $subtotal = floatval($total) - (floatval($gst) + floatval($labor_amount));

                $variables['TotalDiscountAmount'] = '0';
                $variables['InvoiceSubTotal'] = preg_quote('$').number_format($subtotal, 2);
                $variables['TaxCode'] = 'GST';
                $variables['TaxTotal'] = preg_quote('$').number_format($gst, 2);
                $variables['InvoiceAmountDue'] = preg_quote('$').number_format($total, 2);


                foreach ($doc_variables as $doc_variable) {
                    if (!in_array($doc_variable, array_keys($variables))) {
                        $variables[$doc_variable] = ' ';
                    }
                }

                $document = QUOTES_PATH.'tmp-'.$variables['InvoiceNumber'];
                $templateProcessor->setMergeData($variables);
                $templateProcessor->doMerge();
                $templateProcessor->saveAs($document.'.docx');

                if (file_exists($document.'.docx')) {
                    exec("libreoffice --convert-to pdf ".$document.".docx --outdir ".QUOTES_PATH, $logs);

                    if (file_exists($document.'.pdf')) {
                        $results['status'] = true;
                        $results['result'] = QUOTES_DIR.'tmp-'.$variables['InvoiceNumber'].'.pdf';
                    } else {
                        $results['msg'] = "Failed to generate quote preview!";
                    }
                } else {
                    $results['msg'] = "Failed to generate quote preview!";
                }
            } else {
                $results['msg'] = "No items were selected!";
            }
        }

        die(json_encode($results));
    }

    public function Quote_sendAction($id)
    {
        if ($id !== false) {
            $quote = QuotesModel::getQuotes("WHERE quotes.id = '$id'", true);

            if (file_exists(QUOTES_PATH.$quote->uid.'.pdf')) {
                // Generate email
                $email_variables = array();
                $email_variables['CUSTOMER'] = $quote->customer_name;
                $email_variables['ADDRESS'] = $quote->customer_address;
                $email_variables['UID'] = $quote->uid;
                $email_variables['DATE'] = Helper::ConvertDateFormat($quote->created, false);
                $email_variables['PDF_URL'] = QUOTES_DIR.$quote->uid.'.pdf';
                $email_variables['QUOTE_URL'] = HOST_NAME.'index/quote_preview/'.$quote->id.'?c='.$quote->customer_id;
                $email_variables['TOTAL'] = number_format($quote->total, 2);

                // Send the quote to customer.
                $mail = new MailModel();
                $mail->from_email = CONTACT_EMAIL;
                $mail->from_name = CONTACT_NAME;
                $mail->to_email = $quote->customer_email;
                $mail->to_name = $quote->customer_name;
                $mail->subject = "Quote From Compute Your World";
                $mail->message = Helper::GenerateTemplate('quote-email-template', $email_variables);
                $mail->attachment = [QUOTES_PATH.$quote->uid.'.pdf'];

                if ($mail->Send()) {
                    $quote_sent = new QuotesModel();
                    $quote_sent->id = $quote->id;
                    $quote_sent->status = 'sent';
                    $quote_sent->updated = date('Y-m-d H:i:s');
                    $quote_sent->Save();

                    $this->logger->info("Quote email was sent to customer.", Helper::AppendLoggedin(['Quote ID' => $quote->uid]));
                    Helper::SetFeedback('success', "Quote email was sent to customer successfully.");
                } else {
                    $this->logger->error('Failed to send quote email!', Helper::AppendLoggedin(['Quote ID' => $quote->uid]));
                    Helper::SetFeedback('error', "Failed to send quote email!");
                }
            } else {
                $this->logger->error('Failed to send quote email! Couldn\'t generate PDF receipt.', Helper::AppendLoggedin(['Quote ID' => $quote->uid]));
                Helper::SetFeedback('error', "Failed to send quote email! Couldn't generate PDF receipt.");
            }

            Redirect::To('quotes/quotes');
        }
    }

    public function Quote_templateAction()
    {
        if (isset($_FILES["template"])) {
            $file = $_FILES['template'];

            $tmp_name = $file['tmp_name'];
            $file_name = $file['name'];
            $extension = strtolower(pathinfo($file_name)["extension"]);

            if ($extension != "doc" && $extension != "docx") {
                unlink($tmp_name);
                $this->logger->error("Failed to upload quotes template. file is not doc nor docx!", Helper::AppendLoggedin([]));
                Helper::SetFeedback('error', "Error: Unable to upload ".$extension." file, must be doc or docx");
            } else {
                move_uploaded_file($tmp_name, PUBLIC_PATH.'Quote.'.$extension);

                if ($extension == 'doc') {
                    exec("libreoffice --convert-to docx ".PUBLIC_PATH.'Quote.doc', $logs);
                    $this->logger->info("Converting Quotes Template from Doc to Docx.", $logs);
                }

                Helper::SetFeedback('success', "Quote Template was uploaded successfully.");
            }
        }

        $this->RenderQuotes([]);
    }

    public function Quote_approveAction($id)
    {
        if ($id) {
            $quote = new QuotesModel();
            $quote->id = $id;
            $quote->status = 'approved';
            $quote->updated = date('Y-m-d H:i:s');
            if ($quote->Save()) {
                $this->logger->info("Quote was approved successfully.", Helper::AppendLoggedin(['Quote ID' => $id]));
                Helper::SetFeedback('success', "Quote was approved successfully.");
            } else {
                Helper::SetFeedback('error', "Failed to approved quote.");
            }
            Redirect::To('pos/quote/'.$id);
//            $quote = QuotesModel::getOne($id);
//            $sale = new SalesModel();
//            $sale->customer_id = $quote->customer_id;
//            $sale->printed_note = $quote->printed_note;
//            $sale->internal_note = $quote->internal_note;
//            $sale->subtotal = $quote->subtotal;
//            $sale->total = $quote->total;
//            $sale->tax = $quote->GST;
//            $sale->sale_type = 'quotes_system';
//            $sale->created_by = $quote->created_by;
//            $sale->created = $quote->created;
//            $sale->updated = date('Y-m-d H:i:s');
//            if ($sale->Save()) {
//                $this->logger->info("Quote was approved successfully.", Helper::AppendLoggedin(['Sale UID' => $sale->uid]));
//            }
        }
    }




//    public function testAction()
//    {
//        exec("libreoffice --convert-to docx ".PUBLIC_PATH.'test/Quote.doc', $logs);
//        var_dump($logs);
//        //leader datafeed cron
//        die();
//
//        $sku_1 = [ 'FCE3', 'FC.4/3', 'FC.5', 'FC17', 'CB-P', 'FC.1', 'FC.4', 'CHPL3', 'RER1000', 'CB1050-L', 'CB1050C', 'SDL48', '56300', 'BPNAP2Q1/8-N', 'B-CC-832', 'B-HL-96', 'BBLSB', 'RO70SC', 'QD-11655', 'MG075100', 'CUPHF08', 'GPIND', 'BB-KLT', 'PBB6', '11240U', 'PRL', 'CTG22', 'WN2030', '18110_2', 'LDW255', 'DISHBRITE.20', 'JAZ.20', 'SCMWWD.5', 'RO100', 'ROLD', 'PLPL230B', 'PLLB3057PL', 'SCSPM', 'BNG3883', 'BNG3584', 'BNG3884', 'BNG3885', 'CUPPL215', 'BTS', 'CF1930L', 'CF7119', 'CF7419', 'CF7419L', 'ROS', 'RO.70', 'WCCW5', 'D-213BLK', 'D-216 BLACK', 'NOVA.20', 'TP2PJ', 'CHMP', 'PBW.23', 'PBSOS06B', 'K227S0247', 'TICC340507', 'GPHGF', 'MG150230', 'MG100125', 'MG075125', 'LFT050050', 'LFT340100', 'BNG3886', 'GB73', 'BBDB', '8109', 'PSWBC25', 'PBW2CC_P', 'SHANCBMR', 'KC6411', '82BINBLU', 'WN2432', 'WN1724', 'Newsprint Bundle', 'BC-12W', 'BC-16W', 'BCL-8PLA', 'BCL-12PLA', 'BC-8W', 'E25121', '71728', '10629', 'WCOGC5', 'IFS1L', 'HYPROOD.5', 'BNA5824', 'TP2PIL250', 'B-HL-91', 'B-BL-32-N', 'B-BLL-RPET(D)', 'BM-407', '56301', 'CHSWN', 'PBGPL1', 'WDST190', 'TA4875CP', 'FS1.21001', 'BB-WLBM-8', 'B-BL-24-N', 'B-SC-60-N', '20068485', 'B-BL-32', 'B-BL-40', 'BCL-8W', 'BSCK-8', 'BCK-12DW-GS', 'BCK-8DW-GS(90)', 'BCL-12.16.20B', 'BBFP', 'BBBB', 'NAP1LWC', 'NAP2CW', 'BBHDC', 'R-90', 'BC-12(80)W', 'B-BLL-W', 'BC-8-ART SERIES', 'B-BLL-N', 'CHEMPUREDISP', 'PSCBODYWASH.CTN', 'RT-DPS', '616504_750', 'DB50T', 'PCBN12', 'PCBN18', 'BLOG', 'TISSBC', 'PAPERCUT', 'TISST', 'TISSLBL', 'TISSG', 'PBWCC6', 'PBCCI6', 'CUPPLW185', 'PBB.24', 'GPPN', 'CHPL6_A', 'ECOSL250_NL', 'T30360', 'PBSOS16', 'PBSOS12HD', 'BSCL-12.16.32', 'PPG20105', 'PBL12', 'SBDXS', 'PIPING', 'PBB2.5', 'BSC-12', 'BCL-12.16.20W', 'BC-16-ART SERIES', 'BC-12(90)-ART SERIES', 'B-HL-66', 'V175S0064', 'V489S0064', 'V049S0064', 'CUTFW', 'CUTKW', 'DSJBIOB', 'LD453050', 'PBB4', 'PBB.75', 'CBVMW', 'WCB5 4%', 'PPGS23105', 'PP1610', 'PP2317', 'CE230178', 'PBB1', 'BNA5823', 'U463', 'U246', 'SCRINSE.20', 'REL', 'RE.500', 'CUTWF', 'BSCL-8', 'BCK-8-GS', 'BC-8W(90)', 'BCL-8W-Sipper', '102642', 'LA092', 'PREMIUM', 'FTIBD87', 'FTBC075', 'PBGPL.25S', 'CD75', 'CDB', 'PCBN09', 'PCBN15', 'PBL09', 'RO50SC', 'BBLSB_PAC', 'BNG3882', 'T380S0064', 'SBS', 'L-DNQ18W', 'RO440', 'WCER20', 'WCCGFC20', 'CLBL20', 'RO500', 'RO280', 'RE1000', 'ROL', 'RO220', 'RO600', 'RO.40', 'B-HL-93', 'GC056', 'PBGPL.25', 'PBGPL.75L', 'CBVLW', 'CB-FELPT', 'GPBK4033', 'BNG7873', 'SS2424', 'WCFKHS5', 'T48021G', 'T34415', 'PBW7', 'CF7313', 'CF7223', 'GB120-25', 'GPINDF', 'FC.4/1', 'BKEBPL', 'WDST', 'PBW.22', 'PBS2W', 'PBW1', 'PCBW15PL', 'PBGPL1L', 'SS3544', 'WCRA5', 'WCLDC5', 'WCCGFC5', 'WCLFD20', 'WCNRS(RTU)5LT', 'DOR260', 'TWPA', 'CWB7514', 'CF4520', 'RT300CFA', '18340_2', '27001', 'BNG3583', 'BNG9884', 'PTILC', 'BB-LGE', 'BB-STD', 'CUTWT', 'L-LN14-2PW', 'T578S0064', 'CUTWD', 'S225S0022', 'V082S0064', 'BBRSB', 'JP-PBS-6X197-B', 'SIPA', 'CWB450350', 'SHBO20D', 'HDBPL', 'BBFSWL', 'BBCOB', 'RT080P', 'WCLFD5', 'BBFT5', 'RTD-SS', 'T60005', 'PBW2CC', 'BC-8DW(90)-ARTSERIES', 'BC-16DW-ART SERIES', 'BC-12DW-ART', 'CUPHR08BC', 'GD-6.5AKFN-B', 'BSCK-24-GS', 'CHPL4', '1600', 'BCK-8DW-GS', 'BBFT3', 'BBFT1', 'SDTFRD', 'RER.750', 'RERL', 'DFS', 'HDB130LC', 'LDB240LCR', '41045', 'BCK-6-GS', 'BCK-4-GS', 'C-121', 'B-DHL-81', 'P-240', 'TA4875B', '100011sp', '29891', '10', '2780', '11020000', 'BSCK-16', 'B-BL-40-N', 'P-360', 'BCK-16DW-GS', 'P-500', 'R-500Y', 'C-96D(B)', 'HY-16S-COATED', 'JP-PBS-10X197', 'P-700', '5725', 'KTPR9', 'B-HL-91_P', '04540C', '71733', '10596', '71731', '70927', 'GPH4033', 'SCSPS', 'RO700', 'PSW23C', 'PSW25C', 'PBGPL.75', 'FILMFV45', 'R-420Y', 'B-SLBL-W', 'B-SLB-280-W', 'B-SLB-480-W', 'PCBW12PL', 'DIGGERSIMS.20', 'WCIFIHS1', 'MS15S', 'MST15', 'MW60_150', 'GT5', 'S279S0022', 'QD-Slimline', '27000', 'B-PL-06', 'B-PL-09', 'GD-6KFN-B', 'PPG31105', 'TRAY4', 'L-LN18-1PW', 'FHRB', 'MW50', 'NOTEPAD', 'T30051', 'RO100SC', 'JP-PBS-10X197-BS', 'C-TR8080', 'L-LN18-1PN', 'NAP2CB', '102744', 'COCA1225', 'R-360Y', 'FOILAP44', 'NEVSHAMPOO.CTN', 'SPECTRUM.20', 'NEVSOAP.CTN', '5', '402260', 'GB240', 'GLEAM.5', '1850', 'MG100180', 'MG125200', 'WCCMDWL20', 'QP-UNI Ocean', 'SCMWWD.5', 'HYPROOD.20', 'CHLR', 'C-96D(N)', 'GT029', 'WELFARE', '12358924', 'HandT4440A', 'FS1.21001_P2450', 'FS1.21002', 'CBVJW', 'PBW4', 'C-96F', 'CHRRC', 'CHRRC_NL_200', 'BB08060', 'BB13565', '226800', 'DIS-222288', '69460KC', '2306898', 'B-131119HB', 'CLR1L', '4430KC', '11293', 'WCFHS800', 'WCACC1L', 'WCCW20', 'WCRA20', 'WCDS5', 'WCLDC20', 'WCPK20', 'WCPB20', 'WCFS20', 'LD231550', 'CBPBB', 'CBPBM', 'CBPBS', 'GD-6KFSN-R', 'COD3908S', 'COD3909S', 'CUTWK', 'HDT', 'HDBPT', 'WCALB5', 'TISCHW', 'CBPBMI', 'CBPBL', 'P10-SP_500', 'BWP10EC', '150242', 'BSCL-12.16.32(D)', 'NOVA.20', 'HSC15.20', 'SAPPHIRE 10 2.5L', 'FG069650', 'PBW.24', 'STAPLES19', 'PBSOS20', 'PCBW09PL', 'L-SSDN-W', 'HS1-R', '345811', 'BCL-8B', 'F499', '71730', 'T34423', '11252', '347610', '30149', 'B-11517', 'UberBag305x305x175', 'SCSPL', 'DIS2127', 'AZU1L', '10688', 'CUPHR08LW', 'KTL', 'B-BL-24', 'L-BCNCE-N', 'COCA1525', 'GC130R', 'GC170G', 'GC210B', 'RE.750', 'L-DNCE14-W', 'L-LN18-2PN', 'WCHDD20', 'PLPLBB', 'FOILHD44', 'WCOGC20', 'CF7219', '2299', 'PLAS16L', 'PLASW16', 'BCK-8DWW(90)', 'BCK-12DWW', 'BC-16DWW', 'PSB-C-0014', '1610', 'AC2328', '61042530', '25', 'QSAN.20', '100011va', 'T70212', 'CF7131', '07088CP', 'RO150', 'E18048', 'FOILAP30', '3470', '1856', '1857', '1861', '62052264', '104353', '04540F', 'B-10404', '5720', '5230wm', 'RW08BLK', '26412', 'MG05075', '29', '12028303', '11294', 'PBW2', '71727', 'AC2304', 'TA4875HM', 'B500MLBTLCYL_No Rinse Label', 'PTDP-SF', '144650', '144660', '144670', '3860', 'RER.500', 'RO850', 'G1281', 'PSBL12', 'BWHP', 'PTILXL', 'L-LN1/8-2PN-KICCO_S', 'PTPBS150', 'BC-4W', 'BCL-4', 'BC-12(80)-ART SERIES', 'B-CC-802', '56302', 'FM140710', 'ESGLTX', 'S353S0022', 'T192', 'PSB-C-0001', 'PSB-C-0005', '2308325', 'LDB82LBS', 'CCW13', 'CCW10', 'CCW08', 'CW45', 'CBVSW', 'HDBY', 'PBSOS08', 'J242', 'TAWPR', 'L-CDN-W', 'LDMV4630', 'PSWM23', 'B-LB-1000-W', 'RE.750BC', 'RE.500BC', 'RW12BLK', 'BNG3585', '290059', 'NOTEPAD_Y', 'BS20PF', 'CF898', '120155', 'Nano_Anti Bac', 'CUTTW', 'FAT180', 'CAPRT_Single', 'K2V400', 'R24100', '21359CP', '2000', '24514CP', '2020', '67821666', 'QD-TR700', 'QD-11240C', 'QCPT300M', 'BC-8(90)-ARTSERIES', 'GD-6F-B', 'GD-6K-B', 'BWP10.4', 'R164000', 'PLAO16B', 'WCLHSP5', 'WCTBL4', 'WCBC20', 'WCPK5', 'WCHDD5', 'WCSD20', 'WCEW20', 'BTL', 'BCK-12-GS', 'BCK-8-GS(90)', 'CHPL2', 'BNG3594', 'CHSPL', 'CHSNA11', '100002', 'BNA5895', 'PPPS525420', 'RT090T', '27003', '11291', '58017', 'CHRC-534015A', 'PTDPAL', 'B-HL-91-N', 'B-12151F', 'PSWJB1', 'T30142', 'T79381', 'T79382', 'BNA5893', '57073_2', 'T74203', 'J317', 'J318', 'L-TP-400S-2PLY-48', 'PSWJB1_20', 'MG090150', '20205661', 'ECOPL230R', 'CF7131L', 'L-LN1/4-1PN', 'BSC-16', 'B-SCL-60', 'B-SC-60', 'B500MLNATCLY_RTU Label', 'BKR7570', 'EPC', 'JIMDEANAP', '13408', 'MTC Clearance_Flexi Straws', 'PSBL12M', '34918', 'SANI2020', 'WCCS20', 'PSPETGR15', 'PET-1609E', '19002', '58006', '58052', '41254', 'C12303', 'T31000W', 'CIE08', 'CLVD5', '820', 'JP-PBS-6X197-BS', 'BC-6W', 'GD-6S-B', '14340', '7912', 'CUPHR12BC', '39253', '40246', 'PBBOT1', 'PBBOT2', 'PBBOT3', 'B-PL-10', 'ClassRT', 'RE.650', 'PLR156C', 'PLR15L', 'SBT', 'SEAL19S', 'MST19', 'MS19S', 'SCRINSE.20', 'HSC15.20', 'CLOS5', '19195', 'WMHSD', 'B12301', '24516CP', 'MG230305', 'NEVBATH.CTN', 'NEVCONDITIONER.CTN', 'CC596', 'B-10403FB', 'T61112', 'CUPHCH12', 'OTSKEWKNO90', 'BP40', 'ES162', 'SURGE.20', 'OILTEX.20', 'BLEACH4.5', 'COMCHLOR.D', 'PTDP-C', '12169542', 'OTSKEWPAD90', 'GP-725', 'BNG2834', 'PSWD', 'SEAL16P', 'BU19W', 'MS16PET', 'BB-SW-MEDIUM2', 'BB-WLBS-1', 'BWP10.3', 'COL1PLASTD', 'CUTGS', 'CHLBCS', 'PB10', 'OTSKEWPAD90_100', 'OTSKEWPAD240', '18106', 'WCFKHS500', 'WCWC5', 'DS210BIOB', 'C-76F', 'GD-6.5AS-B', 'R-250', 'U-30Y', 'BC-8DWW', 'BCL-12.16.20W-Sipper', 'BSC-24', 'C644S0010', 'CS000025', 'GS', 'PPB3020', 'LD916150', 'LFT36550', 'LD664050', 'JAZ.20', 'B-LBL-W', 'PTIAU1168', 'B-PL-07', 'BC-4-ART SERIES', 'ECOSL250', 'LD9151100', 'MG200250', '5ND2PMS', 'ROSD', 'CHTELID', 'CB0750-L', 'CB0750W', 'CHTE520', '100034', '559030', 'CFT-300', '555000', 'BSG11324', 'CAPW', 'ROPCD100', 'ROPCD100L', 'BWP102EC', 'BWP10SE', 'MAXIPACK6', 'BWP10.2', 'INTENSE', '29000', 'LIQSOAPDPS', 'CF7223L', 'B500MLNATCLY_Lemon Disinfectant Label', 'WCDSP5', 'WCDSPANNER', 'WCAF20', 'Broom14930', 'DSINDBIOBLK', 'BB-LBM-8', 'BB-LBL-3', 'C-143', 'CBPRHBM', 'HW-031', 'R-31', '290068', 'TA48MR', 'WCEBLP25', '5080', 'BNA5894', '367436', 'FT200/2', 'BBFP_P', 'T756S0064', 'B-DHL-83', 'HD2520', 'ZAKSGP', '2001', 'CUTC', 'SHBO24F', 'WNROLL900', 'SHBO16D', 'BCK-10-GL', 'T0205350', 'PBW.75', '5210', 'PP-1506H', 'BSC-8', 'BCH-12', 'CUPHR08W', 'DB100T', 'WCNRS20', 'CLBL5', 'T17253', 'GOJOFMX', 'Artwork', '100006', '210', 'KENYAN', '55306', 'Coffee Repairs', '402278', '2770', 'PC25B', 'BNR21135', 'BBFT4_240', 'CAPRT', 'KICCOB1_S', 'PKNSNA14', 'BBFT4', 'Rental', 'CBLDLHP', 'CBLDLR', 'CBLDSLG', 'CBLDSR', 'C-76D(X)', 'L648', 'L650', 'J647', '18340_3', 'PC18C', 'GPPC', 'SHBO16F', '104183', 'BB-NB-16', 'BBFT2', 'CUPHR12W', 'CUPHR16W', 'V150S0029', 'HONEYTUB.76', 'HONEYTUB.76LID', 'T852600', '58015', 'PBL15', 'BLB', 'L-LN1/8-2PN-KICCO_P', 'T50700', '57072_2', 'GF449', 'BP30', 'U051', 'CUTSODAW', 'CB1050W', 'JP-PBS-4.5X120 COCKTAIL-B', 'JP-PBS-6X197-RS', 'JP-PBS-10x197-ART', 'B-SLB-480-N', 'BSCK-12', 'L-SSD-TT', 'B-LB-750-W', 'B-LBL-RPET(D)', 'GD-6.5AK-B', 'GD-6.5AF-B', 'Q-500', 'CBLDSW', 'CBLDLW', 'MAXIPACK2', 'PLPLBW', '371223M', 'T48019BL', 'T48021Y', 'T48021BN', 'VGP400', 'DORE2535', 'BBQSauce', 'FST85D', 'FST85D', 'NPW2LT', '290067', 'LD150094060', 'C-76', 'SHBO12D', 'PVC400EVERSPAN', 'GPR', 'T18154', 'RO50SC_100', 'R-200Y', 'CF7313L', '30203', '95', 'CUTSW', 'CFT-300B', 'BNG2833', 'B-LB-600-W', 'B-LB-500-W', 'B-LBL-W-SMALL', 'P-700W', 'BNA5822', 'GH733', 'DN716', '5731', 'GP-723', 'GSHPC', 'BNG2523', 'COL01PLATTE', 'NM140000', 'HYPROCD.5', 'LDB7545LB', 'T512S0064', 'B-PL-11', 'B-BL-12', 'WLK2', 'SHBO08D', 'WCSD5', 'WCABHWP800', 'WCPK2_20', 'HY-16KFN', '41307', 'L-SSDN-N', 'CT-DPSW', 'MG280380', 'CUPHR08R', 'CUPHR08LB', 'T48009R', 'T48009W', 'CB155', 'CB156', 'CUTCSW', 'BB20080', 'PTIL8430', 'VP350250', 'PPGS40157', 'BNG2832', 'WL1 BLK', 'CLASRA25', 'PTILSF', 'BNG2524', 'PodsPrem', 'GladGo50', 'CF4123', 'CF2110', 'CF2111', 'CP60C', 'BNG9883', 'SC-100', 'RollTKC44199', '56304', 'FTI1114', 'MEAT12', '58010', '18133', 'L416', 'BNG7833', 'WCABHW5', '28560', 'KTM', 'GB240HT', 'LD-901F', '19857Cit', 'HDCLEANER10.5', 'MH-CO-30', 'S494S0022', 'T70271', 'TNW', 'T48835', '10634', 'ELAG233', 'B-LBL-RPET(D) SMALL', 'T34812', 'DP515.20', 'LD352350', 'BCL-4W', 'CBLDSBC', 'CBLDLBC', 'B-LB-1000-N', 'ECOPL180R', 'PKNSAL06', 'FS1.004510', 'STSTCLPO1', 'C-VB300450', 'BC-12', 'BWPDB', 'MAXIPACK3', 'MAXIPACK1', '3910', 'WCGPDC5', 'RSRS5P500', 'B500MLBTLCYL_Glass & Window Label', 'B500MLBTLCYL_Lemon Foaming Detergent Label', 'B500MLBTLCYL_Heavy Duty Degreaser Label', 'B500MLBTLCYL_Baclean Label', '279', 'MS19P', 'PP-1909HB', '45', 'CUPHR08BR', 'T30164', 'TAFRO', 'CBLDLLG', 'CBLDLO', 'CBLDSHP', 'CBHSW', 'CBLDLY', 'CBLDLP', 'CBLDLBL', 'CBLDSC', 'CBLDSP', 'CBLDSBL', 'CBLDSO', 'CBLDSY', 'HDCL', '150156', '371223F', 'B-SLBL-RPET(D)', 'E700', 'ENVBLOCKS.10', 'E092'];
//        $sku_2 = ['BNG3894','BCK-6-GL','CLEAR.20','U456','TP3PQ','LIM2LT','63101399','CW33','B500MLNATCLY','EPC900','PBTTSE','BNG2522','PP3020','U248','Grooming Kit','NONCAUOVEN.5','T852606','R-700Y','R-300Y','10009','19344','WCMGW5','DOR125','GA441','CTG23','FLOW.20','TISSHP','BNG7835','CE305205','BTAPCAP','SHBO24D','WCSH20','10633','10636','10637','3945','HY-14ST','PBS','CUPHR16BC','CUPHR16BR','CRR7676CL','CF4423','1853','PBSOS08_1000','TISSDBL','TISSR','NEUTRAL3.5','T30753','19702','B-LBL-N','HY-16C','B-LB-2C-W','BB-LBXL-4','CUPPL425','PCTLS','NM100000','NM200000','1860','1897','GD-6SP-B','BNG7876','J134','j316','Pro Foam Gun','102203','CDMFC1L','LD4530100','TP6D133','CEG290140','3865','Guard.5','827','1896','100850','3740','APPRAISE.5','OXYP.10','PINEAWAY.5','REVIVE.20','28570','TA1866','KC38522','CaterersBlend','41309','12083132','CUPHR1216LW','VPFP-CS','71732','CUPPL050','BNG7834','GH700','PBWFLW','ACSHLO25','T70676','C557','T40321','T30150','T34816','T72120','10568','PWCTL','ZAKSHD','HHC420501','GA440','DISHBRITE.20','LD3830100','LD252050','HS300','BB-LBS-1','B-HL-96_P','ITALIAN','QD-FT100','208350','L669','136277','BTRIGSP500ML','BTRIGBTLP750ML_Red','WCFKHS800','SDSANI','12238305','LD302050','344BK','1177','GJ532702','27004','PO2-100','WB1WR','CBHLB','BWP20','70963','WCTHC5','WL2 BLK','LD140061050','PSB-C-0015','SOLLBAGS.CTN','U241','GB120N','LD663530','71604','CB887','VACROLL25','L-CDN-N','GT028','CP069','E093','BBPSMALL','FCS','T47011','U460','CUTDW','BNG3593','DS240BIOB','CTG19','SS5176','FTBC095I','LDMVS5030','SS4469','CE155102','BNG7874','OF','236050','PSBLW19','BX-NU-6','BKR4580','BKR80900','CLAC5','10049','GOJO','3244','4-5491-04_PK4','BNG3893','DB50D','C-VB210300','CE038','BC-6-ARTSERIES','235','06670Party','52553','B-SLBL-N','L-LN14-2PN','WCALB20','WCFCP25','30','CFPURELL','FG066345','WCNW20','B-HL-96-2','PTDP-US','CBPWS','CBPWM','GJ5391-02','NM061990','NM038040','T74204','STRBC','BNG8884','PSSBK','FRHD44C','CF7231','MS15P','T408304','DSSPBIOB','B-LB-750-N','GPH2538','CF28070','CCW11-5','C-VB210400','HNHEADSET','IPH-288BT','CUPHR04B','57071_2','DN739','BNG3892','T30760','TATLO','PS-500HI','GJ509','DN738','SD-200R','4L/808704401800','TAM2450','RRT4476','FT2','BCK-16-GS','HandTK4456F','2530','T48021BL','T48021R','LFT400100','2310','LABELS_Glass&Window','LABELS_FoamLemon','WCLLD5','27002','GJ5161','GPPDWC_Sell','CUPHR16R','MDA.5','AZU120TF','29890','PPPS200300','PPPS230330','PPPS280380','PPPS355510','LFT50100','C529','WCBC5','SCP250','SCPLID','CIE07','TIP964000x','BU12P','DL891-A','V461S0064','T72030','PP 408','GL267','J608','CB154','CB157','CF913','61000L','MISC','TAS4830','WCLHSP20','WCS5','WCSANI2025','MUSHROOMBAG','3543','1138-A','BTRIGSP500ML_Blue','CLSC25','VP450350','F120S0010','5200','B-LB-3C-W','B-LBL-2/3C-W','B-LB-3C-N-LARGE','B-LB-3C-N-LARGE','B-LBL-3C-N-LARGE','CTG24','15','CHTE280','WCIHS800','WCFS5','WCPK1','WCHYD20','DSTBIO','ENVIE','CUTFB','CUTKB','BNG8894','HDB77LC','CLSS0272','CAPBL','18116','BC-4','B-SLB-630-W','NEUTRAL3.20','PLLB3795','12161486','BU15P','PSBC19HD','DE-150P','CWB-6P','LD915610100','R-60','56303','T45286','LZ-0524','270','RE1000BC','ENVPXL','JPSSR','TISSW','PR2040843','SCGRILL.20','CE210152','CEG295114','WCBLLD20','WCNEW20','WCWC20','WCUC5','V543S0001','CTG20','PP700','DIGGERSIMS4.CTN','CBPWB','CBPWB','5197_240','5100','WB3DTC','5151','5150','BNG2835','53331','PBWCC12','19858','27104','JRT-DPS','57070_2','19703','BJAR250MLTBTL','BWP20.2','V281S0064','B-HL-1156-2','SAPPHIRE1RTU','NM001990','PE001990','BB-WLBL-3','STFLBC','K2V-700','CLAO5','CHTELID_500s','CF912','100010sp','SHBO12F','LDMV3020','BNG2525','TA2475CE','CCW12','CIE09','19704_2','CCB13','PSSB','JPSSB','10600','CD100','HYPRODES.5','WCLDC25','LD45080100','CF7113','PBSOS03','TOASTBAG','BC-10-GL','3875','HYPROWC.5','WCTFDC5','DOR190','VACROLL30','B-PL-16-1','B-PL-16-2','GOJOFMX','4-5327','DCHCSVENDC','FTBC085D','KICCOB1_P','CUTTB','32022','CB-FELPL','HD6145','MG330330','CCW11-50','29003','PBWSQ7','CLM5','C-VB165250','CI03F','DOR300','DOR165','CBHLW','BBHCH','JP-PBS-10x197-RS','ZAKS15','ZAKS12','MRKIL5','100010va','PSWM20BC','JRT-2-95','TAWPY','TIP964000','WELFARE_P','61000M','BNG3582','11292','29001','RK-500HI','CE190127','BBWLOAFWH','GG749','T70075','BNG7875','MAXIPACK4','MAXIPACK5','4-80396','R-600Y','RDIC05','HDBLPL','T17260','PBCCI12','P-960','Q-360x','LFT15050','4510','CE13089','PPG26105','T18160','61025470','92','BSW2412','C789','CBMPL','PJDISP','HSRK300','CLSTAT5','TISSLP','B-18154','FTIBD119','LFT14070','BB-NB-26','BB-NB-26','BB-NB-26','C580','B-11116','J210','F343','19065','1890KC','BC-16DW','B-SLB-280-N','NM900000','BSW2322','94173','B60109.EA','CLVIT 5','2310916','290163','TERRAB','MF-048B','REFRE800','B17838','SM-003','OXY2LT','B-11115B','B-60120','8921KC','CLLAZER5','AB4456','4976KC','4404KC','OXY1L','BUCKT9AA..EA','MF-065','B17823','CG229','K983','DL671','K982','U464','GH731','J604','T30009','F348101','29002','1','LD312138','104170_Ctn','108491','CLAP5','IW-101','12106910','T30745','18135','18131','T51855','897','SHBO32F','10314','10602','10077','70251','10337','10590','10087','10531','B-BL-16','FTW119D-I','53331-NW','LFT300100','WCBLLD5','WCHYDP5','WCNEU5','85','PTILSFS','T17255','WCAFO5','BTRIGBTLP750ML_Blue','BPUMP5LT','R-150Y','CUPHR1216LB','DJRTDPSW','PBEKBT','1869','T17060','T17072','PPB180130','SM-043','BTRIGBTLP750ML_Yellow','LD452550','LD252075WT','PINEAWAY.20','LFT150100','STAD05','JPSSBK','BCK-12(80)-GL','41308','BS25PF','BNG9885','DN701','D632','CN108','DN709','T40309','ti45290','TIPS','18154','LD60035060','BTRIGBLACK','299KC','A-100487','B-TRAY-6X5','BNG2831','DP028','GH730','105238','PPPSH230165','R-88-8','NM800000','5230sa','T60929','894','CPSSBK','DSCBIO','PLPL180B','SBM','JETSAFE.20','SBL','SHBO32D','WG185R','B-LBL-2/3C-N','RTK','SBWT750-Multi','PSCHANDSAT.CTN','BOPRACC','18023','SAPPHIRE9RTU','00799GPerc','CBLDBG','JP-PBS-4.5X120 COCKTAIL','7719','HD4530','PodsIntense','10689','K393','GD035','C544','C853','RO440B','T19920','PLPL230W','T40004','PLPL180W','CUPHR12BR','ECOPL300R','WCLDP5','PPGS31125','70','58016','PTA9','T30674','BTRIGBTL500ML','313.1','T48005-GN','T48005-BL','T48005-R','4002R','300008','0-3232W','BC-12DW','D-213DB','371223Sp','HDB18LW','PTDSS-US','RRB4476','JRT-DPSW','4029','CUTDB','2560','PPPS3323','Q-250','PSB-C-0013','EZ222','CE11565','236051','L-LN18-2PW','CLSC5','SLE505','LDMV4125','HD8546T','CUPPLCHAMR','138','FS1.21002_P2450','CB162','CF-SB-32','JP-PBS-6X197-ART','HYPROLS.5','CUPPL104','F338','BR650C','V002S0064','CD984-A','TICC340512','KICCOBAG_S','DN711','CG046','100180_Ctn','DL895-A','WCGPDC20','106013','D-213R','JPSSY','SHBO08F','PLASB16','58011','TI30050','BC-8DW(90)','PSSR','100182_Ctn','GC070','SOLCHAISCO','CLSTAT25','D475','T30785','5085','5198','1859','D628','J229','CD561-A','K551','C320','K548','L031','CM815','L413-A','CLTAP','DM023','DN723','DP022','8TLP','8TLPB','5-ECO','2-ECO','PC23C','40','SDBUC16LM','BSLID','PRS','FP08H','BZFMTBB/2','850Perc','STP','GC961','PC821','06670Snakes','MAGNUM.5','10282','NM300000','NM400000','PLAO20L','71508','Pallet bag','K158','K157','C575S0010','D396','T40320','T17254','62052261','62052272','400.6','CP068','2170301','BCH-32','M810','B-SLB-630-N','B-11583-B','C-96D(X)','B-19507','821','LD1500940100','L977','DN707','CK717','S630','CB734','CT484','LD662430','LD660405150','MTC Clearance_Slim Cocktail Straws','SI-34','T34912-Old','L659','PBWSQ9','CF-SBL-24/32','BCH-16','H10','236052','Smartybags','CUPHF08L','18216','LFT30050','QSAN.20','BBPLARGE','T649S0064','TAWPW','TAWPW','ICT5RL','CUPPL285','TPPLAIN','6676','1140','400.18','LD355230100WT','GT5L','PTA12','LDMV4525','CS770','5515BK','5511Bk','GB080','TI41309','T48020-BN','DN715','GC970','PET-1607E','L443','ICT2','CEG190102','C568','BRAB0021','BRAB0018','ECOBO180R','B-LB-2C-N','B-LB-2C-N','BCAPPOPTOP','BB-NB-8','UP51LP','CCGAS','KNS-16','SEAL15S','TEN1219PET','DOR240','PSSBamboo','CUPHR12R','28580','CBMPDS','PSWJBD','FABITC','CL11800','1104','10207','VP170150','TAWPB','FG017000','L-TCD-TT','LFT51075','TEN1519S','TEN1219P','L434','PSW20C','19850','28550','Urinalmat10','33105','70972','DN719','SHBO20F','L-DNQ1/8- Loxton_P','CPSSB','PBS1B','BC-8DW','COFCAPBAGI','BC-8','DOR200','WCNW5','CTG191','BWWMP','K910','T70221','B-ST-SMALL','B-STL-SMALL','L-DN1/8-W','27100','QP-U1SMA Mat','K163','JPSSG','ORANGEDET','T70100','STSP_R','SEAL19P','B-PL-93','BS20NF','232','FCE7','FSS30','100182','CF-SB-24','PLAO20B','BNR21224','SAPPHIRE8RTU','C868','5110','5197_180','5207','562000','32020','CF6332','PSSO','PSB-C-0003','SCOURGR','5070','CF247','CTPBAG4','T70891','HNEARPHONES','OSR.5','CF4118','1865','6523','Kicco Wrap_S','Kicco Wrap_P','200','T40231','VIN5','KOT','L930','L931','L932','L933','L934','L935','L936','JP-PBS-6X197','100180','SPACLEAN.20','MoninGM','LDMV3225','PCR425','BC-6','BRAB0016','GLPCD','10460','PLAO16W','PLAO16L','179902','T18172','T18155','1125','CEG255114','TISSSY','C20ECO','BC-8DW-ART','T51910','T891020','T37305','T30060','65','899','18598','56303_2','56303_2','T30356','PORBURGP','PLLB3965','GardenWRP_S','VP400300','DN705','DN375','GM253','MoninH','MoninC','MoninI','CUPHT16','820_L','0820_P','4-86896','552138','BM-BCR-12-W','BSCK-32','BSC-32','HY-21CSIW','SCP125','431A','21041201','61043320','HDP4_Sleeve','B12200F','WCLLD20','CHRC-12015A','PTHTAS','V150S00001','B-TL-16','GD-4ATS-B','JP-PBS-6X197-W','L-TDN-N','BCL-90C-PS(F)','GH707','PLR12L','T12072','T12060','CEG230152','VP210300','ELAG233_250','ELAG227','1102','CIE11','CLTFS','LD9151100_150','CUPHT12','U459','R-EXP 0255','WILDCAT25','T064S0064','K657','T30670','T30685','104170','PSSP','BNG7832','K550','FTIBD1114','BC-8DW-CCAB','BCK-8-GL','BC-8-GL','BC-8(90)','BC-6-GL','BC-20','BC-16','BC-12DW-CCAB','TAM4850','BC-12(80)-GL','900BOWLW','PLR126C','WCSYD5','C-DC0562','C-DC0561','WCESD5','PBSOS06B_2000','WCMIS','CC752045','PORBURGP_P','PBTTLE','KICCOBAG_P','D-213GY','T23542','CAPDI','K909','QP-U1SKG Mat','WCESD20'];
//        $sku_3 = ['5086_240','DSPAPERB','5502BK','VINEGAR20LT','NAP2LW','LD4024100','CD204-A','PLR124C','B-BLL-PP','MSTDM','CEG230102','BTRIGBTLP750ML_Green','56211','TISSO','D838','GG930','GC973','STAD20','JPSSO','BKR6080','HVY2LDP','B1LTCYLBTLCAP','WCAF5','100091','JPSSP','C545','C546','K489','U469','PLAO20W','CLSQB','W832','PTPBS180','SSMJug1','SSMJug1.5','J319','Y725-A','CUPHR04W','B-12310','CTPBAG2','TICC20672','TICC20660','400.223R','CF159','6659','102283','CIT4LTR','GH473','TAM1850','CUPHR04LW','BNA00HB','71647','10015','T37440','CLSTROBE5','PEC-16DWB','PPPS160120','ESJ500','TOM500053','AB4720','RGEARTHSU100','SANI205','T17055','556000','5504GY','PLPLLOVW','T23509','AUSLB5BLK','DN724','119400','290163','556000','114273','207350','285mm','310mm','MTC Clearance_Flexi Clear','ClasSlimInter','ClasUSlimInter','5856','CPlunger','TongsSS40','DispFoldNapW','HandTHyg','HandTKC','Party','Misc5Star','MTC Clearance_Flexi Straw Striped','138953','138950','KP104','HONEYTUN1.2LID','Labour','18118','EW-SS-1L','5275','5350','M1012','ATLANTUS','29777','28366','TAMP58','5737','28350','J25','5790','CTK1BK','CMBC10PK','TMS','6320','838233','308393','FRIIS1','DOGW200','248341','500070','158049','19859','B-12142','CC-122YW','00799GDrip','SSJug0.6','PTIL0370','NAP1LAC','NAP2LAR','NAP2LDG','NAP2LG','NAP2LLA','NAP2LO','BOT250','CLASRA5','HST','WVBL','WVR','WVY','PEC-8DWB','PEC-12DWB','CBPRHWM','CBPBT','SUITBAG','SOS2','LMR','CBPBXMAS','CBPCCJLM','CBPCCJPI','CBPCCMLM','CBPCCMPI','CBPCCSBC','CBPCCTLM','CBPGLLBC','CBPGLMBC','CBPGLMG','CBPGLMR','CBPGLMS','CBPGLMW','FP26H-25','CUPHRECO16B','NAPEXAC','2836','ESCULENT8','ESCULENT12','ESCULENT16','C382S0231','R417S0275','58018','61000S','61000XL','56111','18960','CL19170','CL19175','1277','1385A','1497','1550','1890','1893','1961','1962','2086','3009','3010','CL19251','CL19252','CL19360','CL19361','CL19365','CL19370','CL19371','CL19375','CL19376','CL19380','CL19381','CL21065','18130','18132','1285','1506','10140','18121','5000','19018','1395','2085','1992A','1991A','1990','2105','2104','V136S0064','B033S0001','B441S0001','FP16H','V399S0064','V853S0064','CUPHRECO08B','CUPHRECO08LW','CUPHRECO1216LW','CUPHRECO12B','PLACOBL','PLACOWL','COD24811','COD31181','COD3912','BOXRT','Podbox','N899S0001','BBFT560','AUSDLIFT','MJ400','PP280380','FTW075','STRBL','VACROLL20','GBKNOCK','CWBWKT250465','PPPS360250','PPPS125130','PPPS115170','CWB250465','CWB300500','PPPSH9035','PPPSH7550','PPPSH10075','PPPS6550','PPPS8050','PPPS9065','CWHB','PPPS160105','PPPS300180','V107401268','V107403804','V11730410','V1408618000','V1471089500','V1471098500','V1471100500','V21573001','V21640503','V22132000','V22352200','302001143','1471236500','TISSV','WNR750','WCCR80900','WNR450','TISSPU','LDMV2620','BKR4570','BKR6070','TISSCB','TISSCO','PLPLOVB','PLPLOVW','CUPPL320','AF1129','DC 200','RO770B','RER.750WH','G220B','PLPLLOVB','PLACR','Bento5','ECO-RECTM','PF-PWG200','PF-PWGMT','Philips Clock Radio','Elements 1TB HD','SESONYBLUERAY','PPS5070CL','PPR60CL','PPR70CL','PPR70CL37','PPR90CLT','W745509','PPS6060','PPS3030','CLIONICDIFILTER','29101','41171','01979A','41210','PSCL12M','1980','29004','10020','28590','19040','19061','18136','10010','10011','18134','18739','27012','27014','19124','11256','10395','11303','11301','11302','11304','11305','41021','41022','41030','41040','41031','41303','41304','41305','41306','41306','18345','18115','19074','56110','58005','56210','PLLB3096','WNC8080WB','TI30466','GPRED','T978024','T70176','TI74120','TXSNA10','RPKO','SG32','PKNSAL07','TADHH','SEAL12S','T18159x','T48020-R','PBCCI2','PBWLG7','T74141','ZAKSGP_P','TI61230','LF102','FTI119SH','PLLB15240','PLLB15242','PLLB15237','PLLB15243','PLLB15256','PLLB15235','PLLB3794','MS19SZR','PWB-5B','CST-20','RK-600HI','HS500','SSC-04','PS-2010HC','KPD600','EP62','ENVP','ENVPA4','CUPHW08BC','CUPHW12BC','CUPHW16BC','CUPHWLBC','CLTT5','CLKO5','CLST5','CLST25','CLTOPS5','CLTOPS25','CLWW 5','MDHB','PS-200HI','PN-1957','MDPS','RK-400HI','PS-200HC','CLTE735','CLDITO9900107268','CLDITOABC7100000','CLDITOPXB1F16SS','CLDITOPXS4F04SS','CLDITOSBW2900000','CLDL5','CLMDE5','CLRA5','CLRA15','CLRAPUMP','CLRO5','CLSB','PWB-4B','PS-450WB','PS-500WD','PS-600HI','BP-P3S','CMS-19','CLSTROBE25','CLSL25','CLAP25','CLAC15','CLAMD5','CLAMD15','BOPRVI','ECHOLA20','ZEROBAC5','PERFEC5','DRYMAT25','MASBLA5','BOPRVE','BOPRSRA','BOPRST','BOPRSC','BOPRATIN','BOPRCH','BOPRKL','BOPRLE','BOPROV','BOPRTO1','BOPRON1','BOPRMIA','BOPRMIG','BOPRMIGL','ATTRW','BOPRACTO','CLKO15','ZEROBAC15','BWP102ECS','PROBOW5','MOFLHAB','DUACOA20','JATEOX5','CitClean5','JALTERG15','GEMINI5','GEMINI25','LAZERSUPER25','FP579-50','CLVD25','CLVIT25','DINOU','MICGLA1','MICAME1','MICGEN1','KLEBLU25','CLCHLOR','VPREDATORMK2','PO1-1.5X300','MP00','MP000','CTPBAG6','P10-SP_700/400','CHMMP','921-509-STD-MEN','245-814','600663SPB','KN','KNBHD','NAP2DWGT','GLPC','FS1.21004','FS1.21001_cut to 600','FS1.21001_300_250_350','SGC','D-213DG','D-213DP','D-213GOLD','D-500-Duplicate','PSBC19 XHD-PC','PTDPM-US','PTDSS-SF','EPM','CHSW1','WSR','WCB6BR','SOAPDISP1200ss','PP1165','CB153','U454','u458','GD016','CD273','C801','M926','U486','U245','U247','E150','GH407','GL348-A','CG041','CD969-A','GP361','GP346','GP349','SA278','GP360','GP366','B243-L','B243-XL','GF922','CB808','GP835','DN704','GP344','GP345','GP362','GP363','GP350','GP364','GP365','SA281','SA285','GP342','cf949','GH694','T763','DL545','E401','J320','Y137','CC896','CB492','GM581','GG925','U221','GT134','CC595','J120','E912','CL582','GG927','GB068','CB735','J738','C126','DN708','DN714','DN713','DN736','DN737','S007-A','DM022','J092','E406','S483','WSB55E','CMP350V','J089','GC964','GJ498','CB441','CB442','J371','GK094','GH111-P','GH104-P','GH105-P','S631','GL190-A','GH349','DN728','S547','J258','K892','Y753','Y752','U884','CG043','K473','K470','K298','K261','K253','K252','K973','K885','K859','K724','K704','K633','GH722','S047-A','K770','K771','CD751','J158','J379','DN729','GH347','S811','K349','K338','DL898-A','GH059','GH058','M802','T193','GJ527','GC086','GB629','WH05L','GM596','GH735','GH709','K425','K423','J424','GL873','E842','Y726A','GJ562','C116','C117','C120','C121','C122','T984','T984','S088','F203','C064','DL522','K442','K444','GH695','GE753','GH356','F987','GH051','CK477-A','CD082-A','GG485','C791','C788','CF205','G791A','GC801','N129','C386','K406','CE307','CE308','GH708','L279','CK440','DL672','D824','GC803','GC802','CK379','C136','CE685','J850','C558','T134','M950','DL840','K834','K835','K490','GJ561','K908','U044','GG819','CK406','GF316','U202','C581','CF928','CF927','DM128','DP122','F978','K474','F975','C167','C168','C175','C176','C187','C542','C803','C810','C842','C851','C861','C883','C895','C816','C850','CK432','J939','J935','C886','GE751','GE750','S766','D472','D471','GJ518','J252','J253','J254','J255','J256','J257','J232','J231','J230','D719','CG977','GM583','AC894','AC886','AC885','N126','AC878','C879','C886-Duplicate','GF923','GF920','1060084','1009010','1009020','DSDISPS','CE678','AC891','U234','DL615','DN717','DN727','DN725','DN740','DN730','T184','U249','T8712100','TWHIP','C890','5503GY','5502GY','5505GY','5501GY','5512GY','5513GY','5511GN','5509GN','5508GN','5510GN','5507GN','5506GN','5515GN','5514GN','5504GN','5503GN','5505GN','5501GN','5512BK','5513GN','5509BK','5508BK','5510BK','400.131','400.132','413BM','413ABM','412BM','411BM','410BM','494BM','495BM','496BM','497BM','498BM','340BK','347BK','1126','1129','2212','2212A','1151','1152','1152A','1153','1154','1132','1133','1134','1135','9934-GY','9935-GY','9936-GY','9937-GY','9950-GY','9933-GY','9933A-GY','9933B','9942-BK','9943-BK','9938-SBR','9937-SBR','9936-SBR','9935-SBR','9934-SBR','9933-SBR','9933B-SBR','9952-SWH','9953-SWH','SHELF-1.5','12171201','12071011','12141201','COMB-BK18','C-6J6','1140A','1131','313.2','324A','1139','C-VB350450','C-VB350550','C-VB400600','1103','1178','5507BK','5506BK','5513BK','5501RD','GL-PS10','1101','1106','1107','1108','1109','2210','2211','1120','1121','1173','1174','1175','Foam12oz','EC-SW0403','PMRK-9','PMRK-12','ASD662-4','ASB325','B-60207','B-60207-1','R-33','SC-001C','CHCR-20015A','CHCR-20224','CHRC-30224','MF-073G','MF-074G','B-11548-B','MF-059','HW-037','MF-040H','MotorScrub','SC-903','D722','460','400.117B','5505BK','5501BL','5501YE','5504BK','400.168','400.167','400.166','6667','6667A','6667B','U470','U472','U473','U467','U468','D772','CG975','DN720','DN721','GH721','GH720','DL558','U462','400.202','400.203','400.204','400.220B','400.219B','400.218B','5514BK','5501BK','6651','6652','6653','6650','6654','6655','9937-BK','9936-BK','9935-BK','9934-BK','9954-BK','5512WH','5513WH','1160','1161','2215','2220','2221','2222','400.104','400.105','400.106','400.107','400.59','HD180MCP','400.179','400.123','400.117','431','400.61','400.61A','400.156','400.157','400.158','400.159','400.223G','9949','400.209W','5512R','400.13','400.18','400.2','400.6','400.176','817','819','818','829','830','9937-WHT','9943-WHT','394','345BK','352BK','343BK','341BK','349BK','348BK','340OR','348OR','344OR','347OR','342OR','345OR','346OR','343OR','341OR','349OR','815','816','826','201','202','203','200A','201A','202A','200.1','5511GY','5509GY','5508GY','5510GY','5507GY'];
//        $sku_4 = ['5506GY','5515GY','5514GY','GJ5655-02','FP-638','BZ280FS/SIL','USB2GBP','USB4GBP','USB4GB','TVPRO600','ST1002','VSM900','HM35UW','ACCOFLEX','7420','7420/76LB','TSQ-035','STAPLES16','TAC4825B','TAC4825W','CUPHT08','CUPHTL','CUPHTLB','IBB1','CBMPDL','V32410184','VELBOW','VGNBAGS','V15EXT','VPV14BE','VPV15BE','VPVBAGS','VQC5','VQC54','33200008','33400176','33400003','VMEGAGULPER','31220442','31220400','ESB109','11500179','QC4','UNI601','31150309','TAPVC19','BNR22431','BNR36226','BNR63321','BWR1H','BNR21234','DEVOUR.20','GOLDPLUS.20','GOLDPLUS.5','GRAFFITI.20','ZIPSTRIP.20','Spanner','SAPPHIRE2RTU','SAPPHIRE3RTU','SAPPHIRE5RTU','SAPPHIRE6RTU','SAPPHIRE7RTU','BNG8895','BNG01WB','BNG03WB','BNG8885','BNG8893','LD403838','LD151550','GTQD-217DB','D-216 DG','GTQD-217R','GTQD-217MP','D-216 RED','TA1218D','KNBS','KNB','LAW2449','LD1103750','LD11067075','LD803350','LFT125100','LFT130075','LD332230','LD4845100','LD603050','LD6451100','NAP3DW','NAP1LGY','NAP1LR','D-213HP','D-213MP','D-213PU','L-213AQ','L-213B','L-213BK','L-213G','L-213P','L-213PH','L-213Z','TC-50W','D-500','D-31310','650R','D-213BU','SD-800R','TPDJ','SOAP-238/4','L-216 B','RE-400','IF-75000','IF-75002','IF-75003AUSV','IF-76002','LD10151000100','Matt Bag 100um','LD55025050WT','LD28038050','LD260610100','LFT60075','LD404050','ld275050','LFT75100','LFT27050','LD10151400150','LFT80080','MGB','CIE10','CIE06','D200','CCELL','CCELS','LFT25050','LFT12050','LFT60050','PB2.5','LFT16075','LFT18050','LFT35075','LFT305150','12L100400','LFT9050','MattressBag','LD105270','RSPUVW','LD500500100WT','LDWUV450900450','LDCF10050BLK','LFTB27050','LFT100100','LD272450','LD292650','LFT10050','LD74515038','LFT61050','LFTB27070','LD662420','LDCF100100BLK','LDCF100100NAT','RLN/050300','LDPS565632','DM338','DN646','DN642','Y215','GT124','GH327','K981','D773','GC804','U455','C806','D786','D053','CF102','J628','GH512','GF929','CP951','F172','AF270','C195','CS468','C161','L382','GP347','J027','AA082','AA077','U225','U244','F201-A','CD229-A','G784-A','AA078','N283','AA080','K094','CE334','GC968','A310','J730','K499','CE039','CS627-A','CE341','GF921','T640','DN703','F997','C461','C459','K092','CF963','CN956','DM059','L715-A','C266','DM025','UACLIP12','EHDC','GT135','GT138','GF927','F993','CF759-A','T555','T147','DA524','D393','GH101-P','A554','DL815-A','J153','CB937','MEGACHLOR.20','MEGACHLOR.5','NM001995','GOJO8713.EA.SA','NM000016','ORADET750BTL','JETSAFE.5','JETSUPA.5','JETXTRA.20','JETXTRA.20','LAUNDRETTE.25','LAUNDRETTE.25','MAGNUM9.CTN','LAZER.5','LCALKALAI.CTN','LCCHLORINE.CTN','LCCWS.25','LCCWS2.CTN','LCCONDITIONER.CTN','LCDETERGENT.CTN','LCENVBREAK.20','LCENVBREAK.CTN','LCSOUR.CTN','LIMIT.20','GELWHIZ.20','GLEAM.25','GREASEGRAB.5L','HANDCARE.5','JETCLEAN.20','APPRAISE.5','FG079748','FG080000','FG800001','TRIUMPH.10','FG068000','FG073495','SSP.5','FG076495','SCGRILL.5','OXYBLEACH.20','PHOSFREE.10','REDBARON.20','FG065490','REFLEX.20','HYPRORD.20','HYPRORD.5','POWERPACK.20','POWERPACK.5','PRONTO.20','PRONTO.5','FG063745','CLEANSE.10','CLEAR.20','EDGE.20','ELBOWGR.20','ELBOWGR.20','ENCORE.20','ENCORE.20','ENVIROBLUE.NRB','ENVIROBLUE.NRB','ENVIROCARE.10','ENVIROGREEN.20','ENVIROGREEN.20','FABRICARE.20','FABRICARE.20','FLOORSEALERPLUS.5','ARIDCLEAN.CTN','HYPROBSC.5','BIOZYME.25','BIOZYME.25','BLOUTNEW.20','BLEACH4.5','CAUSOD.5','CCQ.5','CHLOR-CLEAN.100','CHLOR-CLEAN.1LPACK','COMCHLOR.D','CWLAUNDET.20','CONCORD.5','TEMPO.20','TEMPO.20','TEMPO.20','COMCHLOR.D','CHOICE.5','CITDSCALER.5','SOFTGR.EA','TERRY4465.CTN6X75','ECOBGEL20.CTN500','ECOBL20.CTN500','HYDEK.PK250','HYSEK.PK500','VANITY.EA','BETWEEN.PKY1500','BIBS.CTN500','COF12BL.SV25','COF16BL.CTN20','COF16BL.SV20','COF4BL.SV50','COF8BL.CTN20','COFLIDBL.CTN10','COFLIDBL.SV50','DQWH.CTN1000','DUSTMASK.PKT50','N1QWH.CTN6','VAC.PKT','HTCFPR.CTN4','HTUF.CTN16','BM3550J.EA','BM450J.EA','BUCKTWINDOW.EA','CLOTHASYEL.PKT','DBCOMPACK.EA','DECKSCRUB.EA','DUSTPANIND.EA','EBPADBLU.EA','EBPADWHT.EA','FLOORPAD30RED.EA','JANCART.EA','MARINE.EA','OILFILT.PKT50','PROF35.EA','SCJONA.PK12','SAPPHIRE10RTU','NM500000','NM600000','NM700000','NM010000','MULTISTRIP.5','NEAT.5','NEOZYME.20','NEOZYME.5','NM150000','NOURISH.20','NOURISH.5','ODORBANPLUS6.CTN','ODORSAFE.5','HYPROOE.5','T23520','T23524','T23541','T18158','T18161','T23548','T30007','T23501','T23508','T17258','T17261','T17272','T18159','SPDWB285','T06900','PTA15','PTA18','PTAP12','PTAP15','PTAP18','PTAP9','CRR5745TH','PTILCLOTH','T48005-W','T48005-BK','T70110','T36984','PCD75','CF5326','T260200','T31316','BKC395395','BKC395195','DOWSOUW2','DOWSOUW75','JumboClear','PPCHOC750','CF7313LW','DMGASSTEEL','4LR-WWB','4MR','4WLH-WWG','4WW-H&S','CLR1LSTPK','OXY1LSTPK','SOL4LTR','SOLC4STPK','SUN1LSTPK','FPH32','T30690','C6210','PAT550C','BEARD','PLLB3796','PLLB3796','TCOVB','T30067','CHPL2SH','SW25','43061-W','PLLB5110','CF5221','ZAKSW2','T48020-GN','MD2DB','CC752705','N15240','T76804','C15ECO','A20ECO','B20ECO','C20PWECO','DWOP20','MLPR20','LMFR15','DWGP20','ATRA20','ATDW20','RTUS05','STRO05','SBSQ1000D','SBSQ250D','SBSQ250F','SBSQ350D','SBSQ350F','SBSQ450D','SBSQ450F','SBSQ500F','SBSQ500D','SBSQ600F','SBSQ600D','SBSQ750D','WCTHC20','WCFCP20','WCGRD5','WCGRD20','GPPDWC_Purchase','GardenWRP_P','SB3C','SBWT750-RTU','SBWT750-Lemon','DWOP05','2900018','PSSY','WCAUW20','WCPC5','Swab test','WCTW20','WCLDP20','WCLFD1','WCCMC1','WCOGC1','SDFHSP','QP-U1SSL SLime','S154138-Z','QP-U1SCM Mat','PC25C','WCSSP5','421','WCBW20','WCDC5','SOLCHAISVA','COFESP1/35','COFTAZD/B5','COFCAPBAGR','COFCAPBAGE','CHCR11015A','IW-005BLK','61000XL_P','AARPF','TF2WHI','5687649','HH14336','HH12102','B-11104','B11123','ML833-W','ML833-SS','554030','860','821','520701','ECOFAST01','803','472028','472193','2321133','8SWC-B','CONF7131','LDBINNAT82','HW-091','2170339','2314372','2314371','7403','TamperF','ECODIP90S2','13490','4196','SM5000','SQUEEGEEGREY','5ND2PLI','5ND2PMG','5ND2PY','5ND2PPU','5ND2PP','5ND2PDB','5ND2PDG','5ND2PW','5ND2PM','5ND2PEB','BLA1218','ML727W','CHAW50002','19422GR','WB-120R','LDB72LY','TR3DPS','ML727SS','SM-138','58012_2','38001KC','MF-060x','MF-065_10','FMT-015','2170271','PYZAPP','2170286','B-13121','CHCR40015A','2308271','B-10403FR','EV011','19857Lavender','71126/71026','17870','16477','TONGUE-100','15825','HandTRegKRG','XPressNapW','HandTRegUIT','B12323','84081','TERRAM','JA-004','IW-105','SPTDPSD','B12146','BB28DGY','B-11120','B11119','19324','19701','IW-005B','18117','41043KC','19857Vanilla','4480','Spilfix','CL19180','CLBU1','CLTK7023','B-BY0556B','LDB72LBRIO','CHCR-40015','CHRC-500015A','ES400','PSWV','19703_2','19700_2','FP522-50','4221','SM-136','MF-034B','B-12306','SM-009','NIP-070','B-60111','FP534-33','CHRC-35015','CHRC-39315','CHRC-39315A','FP53540','FP52140','JA-005','DM-001','SM-013','B-10207','B-40035','B-60121','B-17701','B-21001','MF-042','CHRC-52003','CHRC-601106','CHCR-88015A','CHRC-60224','MF-033','BM-104','B-12426-B','FP-600','19190','SC-055','SP-102','MF-031R','MF-031B','MF-031G','PB-007','IW-050','B-17836','B-40007','MF-073B','MF-073R','JA-48','JA-49','MS-009','R-88','BM-103','MF077G','B12320','MixedNuts','ChipsBBQ','ChipsSalt&Vinegar','ChipsChicken','ChipsCheese&Onion','ChipsOriginal','30203','BI-43003716','LA092_250','C469S0231','850Esp','828Drip','CaramelGround','896','13788','13785','B30TABS150','RWTHERMS','E25598','MoninV','Am_Caramel','Am_Irish Cream','AM10','860Drip','850Drip','900','915','920','5590','R-EXP 0500','00799GEsp','860Perc','CBPWMI','C045S0004','C629S0249','C034S0249','C502S0249','TR2T','AgarOrch','D01','DTR','3705','3725','AUSDJUM','STRR','831','828Esp','A117','835','850','4030','PP30','TAC4825G','1090','1090','471R','440R','12026839','4028','4025','4027','4026','4039','4031','SP500','8958','20278020','33149','30205','KSUMOS03','AllensLollypops','CatertrayM','CatertrayLidM','EW-SS-5L','EW-SS-1Lr','EW-FC-1L','EW-FC-5L','EW-CS-1LE','DEPOSIT','CBFELI1KG','CBCREM1KG','CBRUBY1KG','61043310','61043580','1858','06670RedF','06670Pineapples','06670Straw','06670Chicos','Milo750','06670Bananas','41','20920','100092','6010','HALESORANGE5','104354','106015','104447','12028307','12026958','12026833','12189071','12262328','109098','109095','12285808','109105','12292087','12292191','109107','100549','12126357','T37515','T37520','T37523','T37528','T40206','T37302','T51432','T51697','T30761','T30815','T40310','T40436','T40450','T408405','T41729','T41848','T42058','T42062','T42092','T42096','RRB5757','PTSR12','PTSR12D','PTSR15','PTSR9','T30156','T30147','T30294','T30297','T30299','T30342','T30061','T30115','T30118','T30120','T30125','T30128','T30131','T30135','T34916','T34425','T31010','T31022','T31200','T31252','T34413','T70116','T70118','T72045','T72055','T72080','T73818BK','T73820BK','T74611','T74621','T75003','T75012','T75020','T75032','T75310','T76312','T76318','T47011C','T48009BK','T48019BN','T48019G','T48019R','T48019Y','T48020BL','T48020Y','T79383','T84275','T84280','T850102','T850104','T850202','T850204','T850206','T852310','T852400','T852410','T852610','T852100','T852200','T852210','T850606','T850402','T70850','T70937','T72004','T61220','T61225','T61408','T61416','T61420','T61424','T68503','T69018R','T69522','T69874','T70067','STLBL','STLR','T70272','T70282','T51920','T51950','T30384','T30660','T61108','T61110','T72180','T30680','T61432','PG300','TIPRHD','CHDW15','TFIFOCB16','SBDLPL','SBDSPL','T885201','T8712000','T91022W','T91030BK','T91030W','T91032BK','T91034BK','T91034W','T91040BK','T91040R','T91040W','T91042BK','T91042R','T91042W','T91222-L','T91222BK','T91222R','T91224BK','T91224W','T8716100','T8714065','T8713000','T8713065','T8711100','T70462','T70479','TRAY4P','TPCLG','TPDD','TPLDV9400','TPLSC001','DOR215','DOR140','DOR150','LA-AMON','LA-BTUE','LA-CWED','LA-DTHU','LA-EFRI','LA-FSAT','LA-GSUN','PBG1','NAPD','NAPDLFGT','PBGPL2CS','T176801','T76811','T76802','T76841','T76846','T76848','NAP2QGTR','PDBQ','PDBT','PDD','PG200','T40508','PBWRSS','HD6645','FCL','FC.7','DC16','DC20','DCFL','DOO1','DOO2','DOO3','DOO4','ICE35P','ICT1','ICT1L','HICE','CCB15','CCB17','CBS250','CBS400','CEG380100','CHSPS','CF4121','CF4130','CCW04','CCW06','CF7419LPVC','CFROAST','CHFMP','CS10','CSE10','CIF11','PBWSQPD','CLTS1D','CLTFSD','DOWSOUW2_Purchase','DCSHOT','T41200-BK','T91042Y','T70885','DOWSOUW75_P','PT9SE','PCR065','T17053','T18860','T18872','c7123L','C7123','T17058','ICEBAG5_1','T8711095','HONEY1000','PAT340','OEDO3245S','TI852301','LDIB5PL','PP2685','C7621'];
//        $sku_5 = [ 'C7420L', 'T91022BK', 'PBCCI12_A', 'PBCCI6_A', 'T30665', 'CRR5757TH', 'MG9060', 'HDP4', 'CCF08', 'T76193', 'PT12SE', 'FABITU', 'FABCF', 'T48005-Y', 'C7620', 'T76880', 'T41800-W', 'T30154', 'SWCBB', 'BWPBF', 'C7420', 'TI978374', 'TI978354', 'TI978394', 'PBWSQ11', 'PLLB2318', 'TRAY4P_300', 'WNG8060', 'TI72005', 'TIPSS', 'FIFOCB24', 'FIFOCB32', 'T978504', 'T978604', 'T978004', 'T978094', 'T978014', 'T978724', 'T19943', 'SA 1D', '5111_240', '5130_240', '3SHOTDISP', 'PlasJar', '5012_240', '5217', '5218', '5219', '5010', '5011', '5012_180', '5013', '5069', '5180', '5188', '5189', '5190', '5111_180', '5130', '5140', '5086_180', '5090', 'PCBW26PL', 'TI74140', 'TI30080', 'ICT2L', 'CUPHR04BR', 'GC90Y', 'Tork Handsanatiser 420101', 'c229', 'T17262', 'CPLW', 'T76205', 'T76271', 'BCK790790', 'T408455', 'CCF8', 'TR-528', 'CBS12', 'TI70310', 'MCSOAP', 'CCF10', '119400', '114273', '860', '207350', '803', '400.2', '285mm', '310mm', '520701', '400.13', '554030', '552138', '472028', '472193', '7403', '5342'];
//
////        for ($i = 0; $i < count($sku_1); $i++) {
//////            if ($i == 5) {break;}
//////            $tempDir = PUBLIC_PATH.'test';
////            $tempDir = 'var/import/images';
////            $imageUrl = 'https://www.noarlungapackaging.com.au/assets/full/'.$sku_1[$i].'.jpg';
////            exec("cd $tempDir && wget --quiet $imageUrl");
////        }
//
//
//        $inputFileType = IOFactory::identify(PUBLIC_PATH.'test/products.csv');
//        $reader = IOFactory::createReader($inputFileType);
//        $reader->setReadDataOnly(true);
//        $spreadsheet = $reader->load(PUBLIC_PATH.'test/products.csv');
//        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
//
//        for ($i = 1; $i < sizeof($sheetData) + 1; $i++) {
//            if ($i == 5) {break;}
//
////            $url = preg_replace('#[^0-9a-z]+#i', '-', array_values($sheetData[$i])[0]);
////            $urlKey = strtolower($url);
//
//
////            echo $urlKey . '<br>';
//
//
//
//
//                if ($sheetData[$i]) {
//                    $tempDir = PUBLIC_PATH.'test';
//                    $img_name = array_values($sheetData[$i])[0];
//                    $imageUrl = 'https://www.noarlungapackaging.com.au/assets/full/'.$img_name.'.jpg';
//                    exec("cd $tempDir && wget --quiet $imageUrl");
//                }
//
//        }
//
//
//
//    die();
////        // Word file to be opened
////        $newFile = QUOTES_TEMPLATES_PATH.'Quote.docx';
////
////        // Extract the document.xml file from the DOCX archive
////        $zip = new \ZipArchive();
////        if( $zip->open( $newFile, \ZIPARCHIVE::CHECKCONS ) !== TRUE ) { echo 'failed to open template'; exit; }
////        $file = 'word/document.xml';
////        $data = $zip->getFromName( $file );
////        $zip->close();
////
////        // Create the XML parser and create an array of the results
////        $parser = xml_parser_create_ns();
////        xml_parse_into_struct($parser, $data, $vals, $index);
////        xml_parser_free($parser);
////
////        // Cycle the index array looking for the important key and save those items to another array
////        foreach ($index as $key => $indexitem) {
////            if ($key == 'HTTP://SCHEMAS.OPENXMLFORMATS.ORG/WORDPROCESSINGML/2006/MAIN:INSTRTEXT') {
////                $found = $indexitem;
////                break;
////            }
////        }
////
////        // Cycle *that* array looking for "MERGEFIELD" and grab the field name to yet another array
////        // Make sure to check for duplicates since fields may be re-used
////        if ($found) {
////            $mergefields = array();
////            foreach ($found as $field) {
////                if (!in_array(strtolower(trim(substr($vals[$field]['value'], 12))), $mergefields)) {
////                    var_dump(substr($vals[$field]['value'], 12));
////                    $mergefields[] = strtolower(trim(substr($vals[$field]['value'], 12)));
////                }
////            }
////        }
//
//        // View the fruits of your labor
////        print_r($mergefields);
//
//
//
////        $quote_template = new TemplateProcessor(QUOTES_TEMPLATES_PATH.'Quote.docx');
////        var_dump($quote_template);die();
//
////        $quote_template->setValue('«', '$(');
////        $quote_template->setValue('»', ')');
////        $quote_template->setValue('INVOICETITLE', 'test');
////        $quote_template->saveAs(QUOTES_TEMPLATES_PATH.'quote-template.docx');
//
//
//
//
//
//        $templateProcessor = new MailMergeTemplateProcessor(PUBLIC_PATH.'Quote.docx');
//        $doc_variables = $templateProcessor->getMailMergeVariables();
//
////        $variables['INVOICETITLE'] = "Quote";
////        $variables['ContactName'] = Request::Post('f_name').' '.Request::Post('l_name');;
////        $variables['ContactPostalAddress'] = Request::Post('address') . ', '.Request::Post('suburb').' '.Request::Post('zip');
////        $variables['InvoiceDate'] = date('d M Y');
////        $variables['ExpiryDate'] = date('d M Y', strtotime('+7 days'));
////        $variables['Reference'] = 'id';
////        $variables['InvoiceNumber'] = strtoupper(substr(md5(microtime()),rand(0,26),8));
//
//
//
////        $variables['Description'];
////        $variables['Quantity'];
////        $variables['UnitAmount'];
////        $variables['DiscountPercentage'];
////        $variables['LineAmount'];
//
//
////        $variables['TotalDiscountAmount'];
////        $variables['InvoiceSubTotal'];
////        $variables['TaxCode'];
////        $variables['TaxTotal'];
////        $variables['InvoiceAmountDue'];
////        $variables['InvoiceCurrency'] = 'AMOUNT AUD';
//
////        foreach ($doc_variables as $doc_variable) {
////            if (!in_array($doc_variable, array_keys($variables))) {
////                $variables[$doc_variable] = ' ';
////            }
////        }
//
//
//        $variables = [
//            'TableEnd:TaxSubTotal' => ' ',
//            'TableEnd:LineItem' => ' ',
//
//            'InvoiceCurrency' => "Amount AUD",
//
//            'Description' => "
//
//            something
//            </w:t>
//            </w:r>
//            </w:p>
//
//            <w:p>
//                <w:pPr>
//                  <w:pStyle w:val=\"Normal\"/>
//                  <w:spacing w:lineRule=\"auto\" w:line=\"240\" w:before=\"0\" w:after=\"0\"/>
//                  <w:rPr/>
//                </w:pPr>
//
//                <w:r>
//                <w:rPr/>
//                <w:t>
//                     something 2
//                </w:t>
//                </w:r>
//            </w:p>
//
//            <w:p>
//                <w:pPr>
//                  <w:pStyle w:val=\"Normal\"/>
//                  <w:spacing w:lineRule=\"auto\" w:line=\"240\" w:before=\"0\" w:after=\"0\"/>
//                  <w:rPr/>
//                </w:pPr>
//                <w:r>
//                <w:rPr/>
//                <w:t>
//                    something3
//
//            ",
//            'Quantity' => "
//
//            2
//            </w:t>
//            </w:r>
//            </w:p>
//
//            <w:p>
//            <w:pPr>
//            <w:pStyle w:val=\"Normal\"/>
//            <w:spacing w:lineRule=\"auto\" w:line=\"240\" w:before=\"0\" w:after=\"0\"/>
//            <w:jc w:val=\"right\"/>
//            <w:rPr>
//            <w:szCs w:val=\"18\"/>
//            </w:rPr>
//            </w:pPr>
//            <w:r>
//            <w:rPr>
//            <w:szCs w:val=\"18\"/>
//            </w:rPr>
//            <w:t>
//
//            3
//
//            </w:t>
//            </w:r>
//            </w:p>
//
//            1
//
//            <w:p>
//            <w:pPr>
//            <w:pStyle w:val=\"Normal\"/>
//            <w:spacing w:lineRule=\"auto\" w:line=\"240\" w:before=\"0\" w:after=\"0\"/>
//            <w:jc w:val=\"right\"/>
//            <w:rPr>
//            <w:szCs w:val=\"18\"/>
//            </w:rPr>
//            </w:pPr>
//            <w:r>
//            <w:rPr>
//            <w:szCs w:val=\"18\"/>
//            </w:rPr>
//            <w:t>
//
//            2
//
//            ",
//            'UnitAmount' => preg_quote('$10'),
//            'DiscountPercentage' => "0 0 0",
//            'LineAmount' => preg_quote('$10'),
//        ];
//        $variables2 = [
//            'Description' =>
//                'something
//
//                    </w:t></w:r>
//                    <w:br/>
//
//                    <w:r><w:rPr/><w:t>
//                        something 2
//
//                ',
//            'Quantity' =>
//                '1
//                </w:t></w:r>
//                <w:br/>
//
//                <w:r><w:rPr/><w:t>
//                2
//                '
//        ];
//
//
//        $templateProcessor->setMergeData($variables2);
//        $templateProcessor->doMerge();
//        $templateProcessor->saveAs(PUBLIC_PATH.'test/quote-template.docx');
//
//
//
//        die();
//        $quote = QuotesModel::getOne(1);
//        // Generate email
//        $email_variables = array();
//        $email_variables['CUSTOMER'] = "Shrief Mohamed";
//        $email_variables['UID'] = $quote->uid;
//        $email_variables['DATE'] = date('d M Y');
//        $email_variables['PDF_URL'] = QUOTES_DIR;
//        $email_variables['QUOTE_URL'] = HOST_NAME.'index/quote_preview/'.$quote->id.'?c=';
//        $email_variables['TOTAL'] = number_format($quote->total, 2);
//
//        // Send the quote to customer.
//        $mail = new MailModel();
//        $mail->from_email = CONTACT_EMAIL;
//        $mail->from_name = CONTACT_NAME;
//        $mail->to_email = "shriefmohamed@live.com";
//        $mail->to_name = "Shrief Mohamed";
//        $mail->subject = "Quote From Compute Your World";
//        $mail->message = Helper::GenerateTemplate('quote-email-template', $email_variables);
//        $mail->Send();
//
//
//
//        die();
//        $quote_items = Quotes_itemsModel::getAll("WHERE quote_id = '3' ORDER BY merged");
//        $ordered = array();
//
////        foreach ($quote_items as $quote_item) {
////            $ordered[$quote_item->component][] = $quote_item;
////        }
//
//
//var_dump($quote_items);
////        var_dump($ordered);
//        foreach ($ordered as $key => $value) {
//            var_dump($value);
//        }
//        arsort($ordered);
//        var_dump(array_keys($ordered));
//        foreach (array_keys($ordered) as $component) {
//            var_dump(count($ordered[$component]));
//        }
//
//        die();
//$items_html = '';
//$total_system = $total = $subtotal = $gst = 0;
//foreach ($quote_items as $quote_item) {
//    $total += $quote_item->price * $quote_item->quantity;
//
//    if (count($ordered[$quote_item->component]) > 1) {
//        $total_system += $quote_item->price * $quote_item->quantity;
//        $items_html .= "<tr>
//                        <td class=\"description\"><div class=\"line_description\">".$quote_item->item_name."</div></td>
//                        <td class=\"quantity\"></td>
//                        <td class=\"amount\"></td>
//                        <td class=\"amount\"></td>
//                       </tr>";
//    } else {
//        $items_html .= "<tr>
//                        <td class=\"description\"><div class=\"line_description\">".$quote_item->item_name."</div></td>
//                        <td class=\"quantity\">".$quote_item->quantity."</td>
//                        <td class=\"amount\">$".number_format($quote_item->price, 2)."</td>
//                            <td class=\"amount\">$".number_format($quote_item->price * $quote_item->quantity, 2)."</td>
//                        </tr>";
//    }
//}
//
//
//
//        die();
//        $quote = QuotesModel::getOne(2);
//        $customer = Quotes_customersModel::getOne($quote->customer_id);
//        // Generate quote PDF
//        $variables = array();
//        $variables['CUSTOMER'] = Request::Post('f_name').' '.Request::Post('l_name');
//        $variables['UID'] = substr(md5(microtime()),rand(0,26),8);
//        $variables['DATE'] = date('d M Y');
//
//
//        $quote_items = $_POST['items'];
//        $ordered = array();
//
//        foreach ($quote_items as $quote_item) {
//            $ordered[$quote_item->component][] = $quote_item;
//        }
//
//        $items_html = '';
//        $total_system = $total = $subtotal = $gst = 0;
//        foreach ($quote_items as $quote_item) {
//            $total += $quote_item->price * $quote_item->quantity;
//
//            if (count($ordered[$quote_item->component]) > 1) {
//                $total_system += $quote_item->price * $quote_item->quantity;
//                $items_html .= "<tr>
//                        <td class=\"description\"><div class=\"line_description\">".$quote_item->item_name."</div></td>
//                        <td class=\"quantity\"></td>
//                        <td class=\"amount\"></td>
//                        <td class=\"amount\"></td>
//                       </tr>";
//            } else {
//                $items_html .= "<tr>
//                        <td class=\"description\"><div class=\"line_description\">".$quote_item->item_name."</div></td>
//                        <td class=\"quantity\">".$quote_item->quantity."</td>
//                        <td class=\"amount\">$".number_format($quote_item->price, 2)."</td>
//                            <td class=\"amount\">$".number_format($quote_item->price * $quote_item->quantity, 2)."</td>
//                        </tr>";
//            }
//        }
//
//        $gst = floatval($total) / 10;
//        $subtotal = floatval($total) - floatval($gst);
//
//        $variables['ITEMS'] = $items_html;
////                $variables['GST'] = number_format($gst, 2);
////                $variables['SUBTOTAL'] = number_format($subtotal, 2);
//        $variables['TOTAL'] = number_format($total, 2);
//
//
//        $variables['NOTES'] = $quote->printed_note ?
//            "<h2 class=\"notesTitle\">NOTES</h2><table><tbody><tr><td>".$quote->printed_note."</td></tr></tbody></table>" :
//            "";
//
//        $template = Helper::GenerateTemplate('quote-pdf-template', $variables);
//
//        $mpdf = new Mpdf([
//            'mode' => 'utf-8',
//            'margin_top' => 5,
//            'margin_bottom' => 5
//        ]);
//        $mpdf->WriteHTML($template);
//
//        $document = $quote->uid.'.pdf';
//        $mpdf->Output();
//    }
}