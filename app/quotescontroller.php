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
use Framework\models\quotes\Leader_itemsModel;
use Framework\models\quotes\Quotes_customersModel;
use Framework\models\quotes\Quotes_itemsModel;
use Framework\models\quotes\QuotesModel;
use Mpdf\Mpdf;
use PhpOffice\PhpWord\MailMergeTemplateProcessor;
use PhpOffice\PhpWord\TemplateProcessor;


class QuotesController extends AbstractController
{
    public function DefaultAction()
    {
        Redirect::To('quotes/quotes');
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

    public function QuoteAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        if ($id !== false) {

            $this->RenderQuotes([
                'quote' => QuotesModel::getQuotes("WHERE quotes.id = '$id'", true),
                'quote_items' => Quotes_itemsModel::getQuoteItems("WHERE quote_id = '$id' ORDER BY component")
            ]);
        }
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
                $variables = array();
                $variables['CUSTOMER'] = Request::Post('f_name').' '.Request::Post('l_name');
                $variables['UID'] = strtoupper(substr(md5(microtime()),rand(0,26),8));
                $variables['DATE'] = date('d M Y');
                $variables['EXPIRE'] = date('d M Y', strtotime('+7 days'));


                $items_html = '';
                $total = 0;
                foreach ($_POST['items'] as $quote_item) {
                    $total += floatval(str_replace(",", "", $quote_item['item_price'])) * intval($quote_item['item_qty']);

                    if (isset($_POST['item_merge_component'][$quote_item['component']])) {
                        $items_html .= "<tr>
                        <td class=\"description\"><div class=\"line_description\">".$quote_item['item_name']."</div></td>
                        <td class=\"quantity\"></td>
                        <td class=\"amount\"></td>
                        <td class=\"amount\"></td>
                       </tr>";
                    } else {
                        $items_html .= "<tr>
                        <td class=\"description\"><div class=\"line_description\">".$quote_item['item_name']."</div></td>
                        <td class=\"quantity\">".$quote_item['item_qty']."</td>
                        <td class=\"amount\">$".number_format(str_replace(",", "", $quote_item['item_price']), 2)."</td>
                            <td class=\"amount\">$".number_format(str_replace(",", "", $quote_item['item_price']) * $quote_item['item_qty'], 2)."</td>
                        </tr>";
                    }
                }

                $total_system = Request::Check('system_total') ? Request::Post('system_total') : 0;
                $gst = floatval($total) / 10;
                $subtotal = floatval($total) - floatval($gst);

                $variables['ITEMS'] = $items_html;
                $variables['TOTAL_SYSTEM'] = $total_system;
                $variables['GST'] = number_format($gst, 2);
                $variables['SUBTOTAL'] = number_format($subtotal, 2);
                $variables['TOTAL'] = number_format($total, 2);

                $variables['NOTES'] = Request::Check('printed_note') ?
                    "<h2 class=\"notesTitle\">NOTES</h2><table><tbody><tr><td>".FilterInput::String(Request::Post('printed_note'))."</td></tr></tbody></table>" :
                    "";

                $template = Helper::GenerateTemplate('quote-pdf-template', $variables);
                if ($template) {
                    $results['status'] = true;
                    $results['result'] = $template;
                } else {
                    $results['msg'] = "Failed to generate quote preview!";
                }
            } else {
                $results['msg'] = "No items were selected!";
            }
        }

        die(json_encode($results));
    }

    public function Quote_addAction()
    {
        if (!empty($_POST)) {
            if (isset($_POST['items']) && !empty($_POST['items'])) {
                $customer = new Quotes_customersModel();
                $customer->firstName = FilterInput::String(Request::Post('f_name'));
                $customer->lastName = FilterInput::String(Request::Post('l_name'));
                $customer->email = FilterInput::String(Request::Post('email'));
                $customer->phone = FilterInput::String(Request::Post('phone'));
                $customer->address = FilterInput::String(Request::Post('address'));
                $customer->suburb = FilterInput::String(Request::Post('suburb'));
                $customer->zip = FilterInput::String(Request::Post('zip'));
                if ($customer->Save()) {
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

                    $quote->GST = $quote_total ? $quote_total / 10 : 0;
                    $quote->subtotal = $quote_total - $quote->GST;
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
                            $quote_item->original_price = $item['item_original_price'];
                            $quote_item->price = $item['item_price'];
                            if (!$quote_item->Save()) {
                                $this->logger->error("Quote was created, but some items weren't saved!", Helper::AppendLoggedin(['Quote UID' => $quote->uid, 'Item ID' => $quote_item->item_id]));
                            }
                        }


                        $this->logger->info("Quote was saved successfully.", Helper::AppendLoggedin(['Quote UID' => $quote->uid]));


                        // Generate quote PDF
                        $variables = array();
                        $variables['CUSTOMER'] = $customer->firstName.' '.$customer->lastName;
                        $variables['UID'] = $quote->uid;
                        $variables['DATE'] = date('d M Y');
                        $variables['EXPIRE'] = date('d M Y', strtotime('+7 days'));

                        $quote_items = Quotes_itemsModel::getAll("WHERE quote_id = '$quote->id' ORDER BY merged DESC");
                        if ($quote_items) {
                            $ordered = array();

                            foreach ($quote_items as $quote_item) {
                                $ordered[$quote_item->component][] = $quote_item;
                            }

                            // sort selected items based on which component has most items
                            arsort($ordered);

                            $quote_items_ordered_merged = call_user_func_array('array_merge', $ordered);

                            $items_html = '';
                            $total_system = $total = $subtotal = $gst = 0;
                            foreach ($quote_items_ordered_merged as $quote_item) {
                                $total += floatval(str_replace(",", "", $quote_item->price)) * intval($quote_item->quantity);

                                if (isset($_POST['item_merge_component'][$quote_item->component])) {
                                    $total_system += floatval(str_replace(",", "", $quote_item->price)) * intval($quote_item->quantity);
                                    $items_html .= "<tr>
                                        <td class=\"description\"><div class=\"line_description\">".$quote_item->item_name."</div></td>
                                        <td class=\"quantity\"></td>
                                        <td class=\"amount\"></td>
                                        <td class=\"amount\"></td>
                                       </tr>";
                                } else {
                                    $items_html .= "<tr>
                                        <td class=\"description\"><div class=\"line_description\">".$quote_item->item_name."</div></td>
                                        <td class=\"quantity\">".$quote_item->quantity."</td>
                                        <td class=\"amount\">$".number_format(str_replace(",", "", $quote_item->price), 2)."</td>
                                            <td class=\"amount\">$".number_format(str_replace(",", "", $quote_item->price) * intval($quote_item->quantity), 2)."</td>
                                        </tr>";
                                }
                            }
                        }

                        $gst = floatval($total) / 10;
                        $subtotal = floatval($total) - floatval($gst);

                        $variables['ITEMS'] = $items_html;
                        $variables['TOTAL_SYSTEM'] = $total_system;
                        $variables['GST'] = number_format($gst, 2);
                        $variables['SUBTOTAL'] = number_format($subtotal, 2);
                        $variables['TOTAL'] = number_format($total, 2);

                        $variables['NOTES'] = $quote->printed_note ?
                            "<h2 class=\"notesTitle\">NOTES</h2><table><tbody><tr><td>".$quote->printed_note."</td></tr></tbody></table>" :
                            "";

                        $template = Helper::GenerateTemplate('quote-pdf-template', $variables);

                        $mpdf = new Mpdf([
                            'mode' => 'utf-8',
                            'margin_top' => 5,
                            'margin_bottom' => 5
                        ]);
                        $mpdf->WriteHTML($template);

                        $document = $quote->uid.'.pdf';
                        $mpdf->Output(QUOTES_PATH.$document);
//                        $mpdf->Output();

                        if (file_exists(QUOTES_PATH.$document)) {
                            // Generate email
                            $email_variables = array();
                            $email_variables['CUSTOMER'] = $customer->firstName.' '.$customer->lastName;
                            $email_variables['UID'] = $quote->uid;
                            $email_variables['DATE'] = date('d M Y');
                            $email_variables['PDF_URL'] = QUOTES_DIR.$document;
                            $email_variables['QUOTE_URL'] = HOST_NAME.'index/quote_preview/'.$quote->id.'?c='.$customer->id;
                            $email_variables['TOTAL'] = number_format($quote->total, 2);

                            // Send the quote to customer.
                            $mail = new MailModel();
                            $mail->from_email = CONTACT_EMAIL;
                            $mail->from_name = CONTACT_NAME;
                            $mail->to_email = $customer->email;
                            $mail->to_name = $customer->firstName.' '.$customer->lastName;
                            $mail->subject = "Quote From Compute Your World";
                            $mail->message = Helper::GenerateTemplate('quote-email-template', $email_variables);
                            $mail->attachment = [QUOTES_PATH.$document];

                            if ($mail->Send()) {
                                $this->logger->info("Quote email was sent to customer.", Helper::AppendLoggedin(['Quote ID' => $quote->uid]));
                                Session::Set('messages', ['success', "Quote email was sent to customer successfully."]);
                            } else {
                                $this->logger->error('Failed to send quote email!', Helper::AppendLoggedin(['Quote ID' => $quote->uid]));
                                Session::Set('messages', ['error', "Failed to send quote email!"]);
                            }
                        } else {
                            $this->logger->error('Failed to send quote email! Couldn\'t generate PDF receipt PDF.', Helper::AppendLoggedin(['Quote ID' => $quote->uid]));
                            Session::Set('messages', ['error', "Failed to send quote email! Couldn't generate PDF receipt."]);
                        }


                        Redirect::To('quotes/quotes');
                    } else {
                        // quote saving error
                        $dll_customer = new CustomersModel();
                        $dll_customer->id = $customer->id;
                        $dll_customer->Delete();

                        $this->logger->error("Failed to save quote. Quote error!", Helper::AppendLoggedin([]));
                        Session::Set('messages', ['error', "Failed to save quote. Something wrong while saving quote!"]);
                    }
                } else {
                    // customer issue
                    $this->logger->error("Failed to save quote. Customer error!", Helper::AppendLoggedin([]));
                    Session::Set('messages', ['error', "Failed to save quote. Something wrong while saving customer details!"]);
                }
            } else {
                $this->logger->error("Failed to save quote. No items were selected!", Helper::AppendLoggedin([]));
                Session::Set('messages', ['error', "Failed to save quote. No items were selected!"]);
            }

        }

        $order = array('Cases & Accessories', 'Motherboards', 'CPU', 'Fan & Cooling Products', 'Memory', 'Hard Disk Drives - SSD', 'Hard Disk Drives - SATA', 'Video/Graphics Cards', 'Power Supplies', 'DVD & Bluray Drives', 'Network - Consumer', 'Software');
        $categories = array_values(Leader_itemsModel::getCategories());
        $categories = $categories ? Helper::sortArrayByArray($categories, $order) : false;
        $this->RenderQuotes([
            'categories' => $categories,
            'sub_categories' => Leader_itemsModel::getSubCategories()
        ]);
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
                Session::Set('messages', ['error', "Error: Unable to upload ".$extension." file, must be doc or docx"]);
            } else {
                move_uploaded_file($tmp_name, PUBLIC_PATH.'Quote.'.$extension);

                if ($extension == 'doc') {
                    exec("libreoffice --convert-to docx ".PUBLIC_PATH.'Quote.doc', $logs);
                    $this->logger->info("Converting Quotes Template from Doc to Docx.", $logs);
                }

                Session::Set('messages', ['success', "Quote Template was uploaded successfully."]);
            }
        }

        $this->RenderQuotes([]);
    }




    public function testAction()
    {

//        // Word file to be opened
//        $newFile = QUOTES_TEMPLATES_PATH.'Quote.docx';
//
//        // Extract the document.xml file from the DOCX archive
//        $zip = new \ZipArchive();
//        if( $zip->open( $newFile, \ZIPARCHIVE::CHECKCONS ) !== TRUE ) { echo 'failed to open template'; exit; }
//        $file = 'word/document.xml';
//        $data = $zip->getFromName( $file );
//        $zip->close();
//
//        // Create the XML parser and create an array of the results
//        $parser = xml_parser_create_ns();
//        xml_parse_into_struct($parser, $data, $vals, $index);
//        xml_parser_free($parser);
//
//        // Cycle the index array looking for the important key and save those items to another array
//        foreach ($index as $key => $indexitem) {
//            if ($key == 'HTTP://SCHEMAS.OPENXMLFORMATS.ORG/WORDPROCESSINGML/2006/MAIN:INSTRTEXT') {
//                $found = $indexitem;
//                break;
//            }
//        }
//
//        // Cycle *that* array looking for "MERGEFIELD" and grab the field name to yet another array
//        // Make sure to check for duplicates since fields may be re-used
//        if ($found) {
//            $mergefields = array();
//            foreach ($found as $field) {
//                if (!in_array(strtolower(trim(substr($vals[$field]['value'], 12))), $mergefields)) {
//                    var_dump(substr($vals[$field]['value'], 12));
//                    $mergefields[] = strtolower(trim(substr($vals[$field]['value'], 12)));
//                }
//            }
//        }

        // View the fruits of your labor
//        print_r($mergefields);




//        $quote_template = new TemplateProcessor(QUOTES_TEMPLATES_PATH.'Quote.docx');
//        var_dump($quote_template);die();

//        $quote_template->setValue('«', '$(');
//        $quote_template->setValue('»', ')');
//        $quote_template->setValue('INVOICETITLE', 'test');
//        $quote_template->saveAs(QUOTES_TEMPLATES_PATH.'quote-template.docx');





        $templateProcessor = new MailMergeTemplateProcessor(PUBLIC_PATH.'test/Quote.docx');
        $doc_variables = $templateProcessor->getMailMergeVariables();

        var_dump($doc_variables);die();

//        $variables['INVOICETITLE'] = "Quote";
//        $variables['ContactName'] = Request::Post('f_name').' '.Request::Post('l_name');;
//        $variables['ContactPostalAddress'] = Request::Post('address') . ', '.Request::Post('suburb').' '.Request::Post('zip');
//        $variables['InvoiceDate'] = date('d M Y');
//        $variables['ExpiryDate'] = date('d M Y', strtotime('+7 days'));
//        $variables['Reference'] = 'id';
//        $variables['InvoiceNumber'] = strtoupper(substr(md5(microtime()),rand(0,26),8));

//        $variables['Description'];
//        $variables['Quantity'];
//        $variables['UnitAmount'];
//        $variables['DiscountPercentage'];
//        $variables['LineAmount'];


//        $variables['TotalDiscountAmount'];
//        $variables['InvoiceSubTotal'];
//        $variables['TaxCode'];
//        $variables['TaxTotal'];
//        $variables['InvoiceAmountDue'];
//        $variables['InvoiceCurrency'] = 'AMOUNT AUD';

//        foreach ($doc_variables as $doc_variable) {
//            if (!in_array($doc_variable, array_keys($variables))) {
//                $variables[$doc_variable] = ' ';
//            }
//        }


        $templateProcessor->setMergeData([
            'ContactName' => 'shrief',
            'Description' => "
            
            something 
            </w:t>
            </w:r>
            </w:p>
             
            <w:p>
                <w:pPr>
                  <w:pStyle w:val=\"Normal\"/>
                  <w:spacing w:lineRule=\"auto\" w:line=\"240\" w:before=\"0\" w:after=\"0\"/>
                  <w:rPr/>
                </w:pPr>
                
                <w:r>
                <w:rPr/>
                <w:t>
                soemthing2     
                </w:t>
                </w:r>
            </w:p>
            
            <w:p>
                <w:pPr>
                  <w:pStyle w:val=\"Normal\"/>
                  <w:spacing w:lineRule=\"auto\" w:line=\"240\" w:before=\"0\" w:after=\"0\"/>
                  <w:rPr/>
                </w:pPr>
                <w:r>
                <w:rPr/>
                <w:t> 
                something3
            
            ",
            'Quantity' => "
            
            2 
            </w:t>
            </w:r>
            </w:p>

            <w:p>
            <w:pPr>
            <w:pStyle w:val=\"Normal\"/>
            <w:spacing w:lineRule=\"auto\" w:line=\"240\" w:before=\"0\" w:after=\"0\"/>
            <w:jc w:val=\"right\"/>
            <w:rPr>
            <w:szCs w:val=\"18\"/>
            </w:rPr>
            </w:pPr>
            <w:r>
            <w:rPr>
            <w:szCs w:val=\"18\"/>
            </w:rPr>
            <w:t>
            
            3
            
            </w:t>
            </w:r>
            </w:p>
            
            
            <w:p>
            <w:pPr>
            <w:pStyle w:val=\"Normal\"/>
            <w:spacing w:lineRule=\"auto\" w:line=\"240\" w:before=\"0\" w:after=\"0\"/>
            <w:jc w:val=\"right\"/>
            <w:rPr>
            <w:szCs w:val=\"18\"/>
            </w:rPr>
            </w:pPr>
            <w:r>
            <w:rPr>
            <w:szCs w:val=\"18\"/>
            </w:rPr>
            <w:t>
            
            2
            
            "
        ]);
        $templateProcessor->doMerge();
        $templateProcessor->saveAs(PUBLIC_PATH.'test/quote-template.docx');



        die();
        $quote = QuotesModel::getOne(1);
        // Generate email
        $email_variables = array();
        $email_variables['CUSTOMER'] = "Shrief Mohamed";
        $email_variables['UID'] = $quote->uid;
        $email_variables['DATE'] = date('d M Y');
        $email_variables['PDF_URL'] = QUOTES_DIR;
        $email_variables['QUOTE_URL'] = HOST_NAME.'index/quote_preview/'.$quote->id.'?c=';
        $email_variables['TOTAL'] = number_format($quote->total, 2);

        // Send the quote to customer.
        $mail = new MailModel();
        $mail->from_email = CONTACT_EMAIL;
        $mail->from_name = CONTACT_NAME;
        $mail->to_email = "shriefmohamed@live.com";
        $mail->to_name = "Shrief Mohamed";
        $mail->subject = "Quote From Compute Your World";
        $mail->message = Helper::GenerateTemplate('quote-email-template', $email_variables);
        $mail->Send();



        die();
        $quote_items = Quotes_itemsModel::getAll("WHERE quote_id = '3' ORDER BY merged");
        $ordered = array();

//        foreach ($quote_items as $quote_item) {
//            $ordered[$quote_item->component][] = $quote_item;
//        }


var_dump($quote_items);
//        var_dump($ordered);
        foreach ($ordered as $key => $value) {
            var_dump($value);
        }
        arsort($ordered);
        var_dump(array_keys($ordered));
        foreach (array_keys($ordered) as $component) {
            var_dump(count($ordered[$component]));
        }

        die();
$items_html = '';
$total_system = $total = $subtotal = $gst = 0;
foreach ($quote_items as $quote_item) {
    $total += $quote_item->price * $quote_item->quantity;

    if (count($ordered[$quote_item->component]) > 1) {
        $total_system += $quote_item->price * $quote_item->quantity;
        $items_html .= "<tr>
                        <td class=\"description\"><div class=\"line_description\">".$quote_item->item_name."</div></td>
                        <td class=\"quantity\"></td>
                        <td class=\"amount\"></td>
                        <td class=\"amount\"></td>
                       </tr>";
    } else {
        $items_html .= "<tr>
                        <td class=\"description\"><div class=\"line_description\">".$quote_item->item_name."</div></td>
                        <td class=\"quantity\">".$quote_item->quantity."</td>
                        <td class=\"amount\">$".number_format($quote_item->price, 2)."</td>
                            <td class=\"amount\">$".number_format($quote_item->price * $quote_item->quantity, 2)."</td>
                        </tr>";
    }
}



        die();
        $quote = QuotesModel::getOne(2);
        $customer = Quotes_customersModel::getOne($quote->customer_id);
        // Generate quote PDF
        $variables = array();
        $variables['CUSTOMER'] = Request::Post('f_name').' '.Request::Post('l_name');
        $variables['UID'] = substr(md5(microtime()),rand(0,26),8);
        $variables['DATE'] = date('d M Y');


        $quote_items = $_POST['items'];
        $ordered = array();

        foreach ($quote_items as $quote_item) {
            $ordered[$quote_item->component][] = $quote_item;
        }

        $items_html = '';
        $total_system = $total = $subtotal = $gst = 0;
        foreach ($quote_items as $quote_item) {
            $total += $quote_item->price * $quote_item->quantity;

            if (count($ordered[$quote_item->component]) > 1) {
                $total_system += $quote_item->price * $quote_item->quantity;
                $items_html .= "<tr>
                        <td class=\"description\"><div class=\"line_description\">".$quote_item->item_name."</div></td>
                        <td class=\"quantity\"></td>
                        <td class=\"amount\"></td>
                        <td class=\"amount\"></td>
                       </tr>";
            } else {
                $items_html .= "<tr>
                        <td class=\"description\"><div class=\"line_description\">".$quote_item->item_name."</div></td>
                        <td class=\"quantity\">".$quote_item->quantity."</td>
                        <td class=\"amount\">$".number_format($quote_item->price, 2)."</td>
                            <td class=\"amount\">$".number_format($quote_item->price * $quote_item->quantity, 2)."</td>
                        </tr>";
            }
        }

        $gst = floatval($total) / 10;
        $subtotal = floatval($total) - floatval($gst);

        $variables['ITEMS'] = $items_html;
//                $variables['GST'] = number_format($gst, 2);
//                $variables['SUBTOTAL'] = number_format($subtotal, 2);
        $variables['TOTAL'] = number_format($total, 2);


        $variables['NOTES'] = $quote->printed_note ?
            "<h2 class=\"notesTitle\">NOTES</h2><table><tbody><tr><td>".$quote->printed_note."</td></tr></tbody></table>" :
            "";

        $template = Helper::GenerateTemplate('quote-pdf-template', $variables);

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'margin_top' => 5,
            'margin_bottom' => 5
        ]);
        $mpdf->WriteHTML($template);

        $document = $quote->uid.'.pdf';
        $mpdf->Output();
    }
}