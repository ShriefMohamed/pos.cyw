<?php

namespace Framework\lib;


use Framework\models\pos\Register_countsModel;
use Framework\models\pos\Sales_itemsModel;
use Framework\models\pos\Sales_paymentsModel;
use Framework\models\pos\SalesModel;
use Framework\models\quotes\Quotes_itemsModel;
use Framework\models\quotes\QuotesModel;
use Framework\models\UsersModel;
use Picqer\Barcode\Barcode;
use Picqer\Barcode\BarcodeGeneratorPNG;

//Load Composer's autoloader
require APP_PATH . 'vendor/autoload.php';


// all the controllers extends this abstract controller
class AbstractController
{
    protected $_controller;
    protected $_action;
    protected $_params;
    protected $_template;

    public $logger;
    public $logs_types = [
        'admin' => 'jobs',
        'cron' => 'cron',
        'customers' => 'customers',
        'customer' => 'customer',
        'insurance' => 'insurance',
        'licenses' => 'licenses',
        'pos' => 'pos',
        'quotes' => 'quotes',
        'technicians' => 'technicians',
        'xero' => 'xero'
    ];

    public function __construct($controllerName, $actionName, $params)
    {
        $this->_controller = $controllerName;
        $this->_action = $actionName;
        $this->_params = $params;

        if ($this->_controller == 'xero') {
            $this->xeroModel = new XeroModel();
        }

        $this->InitializeTemplate();
        $this->InitializeLogger();
    }


    ##### NotFoundAction ##########
    // Parameters :- None
    // Return Type :- None
    // Purpose :- at the frontcontroller if the controller class or the function/action doesn't exist then the not found
    // action is the one that get called to display a 404 not found
    ###########################
    public function NotFoundAction()
    {
        $this->_template->SetViews(['view'])->Render();
    }




    public function InitializeTemplate()
    {
        $this->_template = new Template($this->_controller, $this->_action);
    }

    public function InitializeLogger()
    {
        // Set Default Log
        $logs_name = in_array($this->_controller, $this->logs_types)
            ? $this->logs_types[$this->_controller]
            : 'logs';

        $this->logger = LoggerModel::Instance($logs_name)->InitializeLogger();
    }



    protected function RenderPos($data = array())
    {
        $this->_template->SetData($data)
            ->SetViews(['topbar', 'leftbar', 'view'])
            ->Render();
    }

    protected function RenderTechnician($data = array())
    {
        $this->_template->SetData($data)
            ->SetViews(['topbar', 'view'])
            ->Render();
    }

    protected function RenderQuotes($data = array())
    {
        $this->_template->SetData($data)
            ->SetViews(['topbar', 'leftbar', 'view'])
            ->Render();
    }



    protected function SendSMS($message, $phone): bool
    {
        if (!$message || !$phone) {
            return false;
        }

        $mail = new MailModel();
        $mail->from_email = CONTACT_EMAIL;
        $mail->from_name = CONTACT_NAME;
        $mail->to_email = $phone.'@sms.cyw.net.au';
        $mail->message = $message;
        return $mail->Send();
    }



    /*POS Functions*/
    protected function RegisterCount($purpose)
    {
        if (Request::Check('submit')) {
            $count = new Register_countsModel();
            $count->count_purpose = $purpose;
            $count->total = FilterInput::Float(Request::Post('total'));

            $count->method = Request::Check('payment_type') ? FilterInput::String(Request::Post('payment_type')) : '';
            $count->notes = Request::Check('notes') ? FilterInput::String(Request::Post('notes')) : '';
            $count->counted_by = Session::Get('loggedin')->id;
            $count->counted = date("Y-m-d H:i:s");

            if ($count->Save()) {
                $this->logger->info("New register count was submitted on $purpose.", Helper::AppendLoggedin(['Total' => $count->total]));
                return $count;
            }
        }
        return false;
    }

    protected function GenerateBarcode($upc)
    {
        if ($upc) {
            $generator = new BarcodeGeneratorPNG();
            return $generator->getBarcode($upc, $generator::TYPE_UPC_A);
        }
        return false;
    }

    protected function SaleReceipt($sale_id)
    {
        $sale = SalesModel::getSale("WHERE sales.id = '$sale_id'", true);
        $sale_items = Sales_itemsModel::getSaleItems($sale_id);
        $sale_payments = Sales_paymentsModel::getSalesPayments("WHERE sales_payments.sale_id = '$sale_id'");

        // variables to be replaced from the template to the real values.
        $variables = array();
        $variables['DATETIME'] = Helper::ConvertDateFormat($sale->created, true);
        $variables['UID'] = $sale->uid;
        $variables['ADMIN'] = Session::Get('loggedin')->firstName;

        $variables['SUBTOTAL'] = number_format($sale->subtotal, 2);
        $variables['TAX'] = number_format($sale->tax, 2);
        $variables['DISCOUNT'] = number_format($sale->discount, 2);
        $variables['TOTAL'] = number_format($sale->total, 2);

        if ($sale_items) {
            $items = '';
            foreach ($sale_items as $sale_item) {
                $items .= "<tr>
                            <td class=\"description\"><div class=\"line_description\">".$sale_item->description."</div></td>
                            <td class=\"quantity\">".$sale_item->quantity."</td>
                            <td class=\"amount\">$".number_format($sale_item->total, 2)."</td>
                            <td class=\"amount\">Tax ($".number_format($sale_item->total, 2)." @ ".$sale_item->rate."%)  $".number_format($sale_item->total * $sale_item->rate / 100, 2)."</td>
                        </tr>";
            }

            $variables['ITEMS'] = $items;
        }

        $payments = '';
        if ($sale_payments) {
            foreach ($sale_payments as $sale_payment) {
                $payment_method = $sale_payment->method_name ?: ucfirst($sale_payment->payment_method);
                $payments .= "<tr><td class=\"label\">".$payment_method."</td><td id=\"receiptPaymentsCash\" class=\"amount\">$".number_format($sale_payment->amount, 2)."</td></tr>";
            }
            $payments .= ($sale->total > $sale->total_paid) ? "<tr><td class=\"label\">Change</td><td id=\"receiptPaymentsChange\" class=\"amount\">$".number_format($sale->total_paid - $sale->total, 2)."</td></tr>" : '';
        } else {
            $payments .= "<tr><td class=\"label\"></td><td id=\"receiptPaymentsCash\" class=\"amount\">$0.00</td></tr>";
        }
        $variables['PAYMENTS'] = $payments;

        $variables['NOTES'] = $sale->printed_note ?
            "<h2 class=\"notesTitle\">NOTES</h2><table><tbody><tr><td>".$sale->printed_note."</td></tr></tbody></table>" :
            "";

        if ($barcode = $this->GenerateBarcode($sale->uid)) {
            $variables['BARCODE'] = '<img id="barcodeImage" height="50" width="250" class="barcode" src="data:image/png;base64,' . base64_encode($barcode) . '">';
            $variables['BARCODE_NUMBER'] = $sale->uid;
        } else {
            $variables['BARCODE'] = '';
            $variables['BARCODE_NUMBER'] = '';
            $this->logger->error("Failed to generate barcode for sale receipt.", Helper::AppendLoggedin(['Sale ID' => $sale_id]));
        }

        return Helper::GenerateTemplate('sale_receipt', $variables);
    }

    protected function QuoteReceipt($id)
    {
        $quote = QuotesModel::getQuotes("WHERE quotes.id = '$id'", true);
        $quote_items = Quotes_itemsModel::getAll("WHERE quote_id = '$id' ORDER BY merged DESC");

        if ($quote->viewed != 1 && Request::Check('c', 'get')) {
            if (Request::Get('c') == $quote->customer_id) {
                $quote_viewed = new QuotesModel();
                $quote_viewed->id = $id;
                $quote_viewed->viewed = '1';
                $quote_viewed->Save();
            }
        }

        $variables = array();
        $variables['CUSTOM_CSS'] = "#container-body {width: 65%; padding: 15px;margin: auto}";
        $variables['CUSTOMER'] = $quote->customer_name;
        $variables['ADDRESS'] = $quote->customer_address;
        $variables['UID'] = 'Q-'.$quote->quote_reference;
        $variables['DATE'] = date('d M Y');
        $variables['EXPIRE'] = date('d M Y', strtotime('+7 days'));

        if ($quote_items) {
//                $ordered = array();

//                foreach ($quote_items as $quote_item) {
//                    $ordered[$quote_item->component][] = $quote_item;
//                }

            // sort selected items based on which component has most items
//                arsort($ordered);

//                $quote_items_ordered_merged = call_user_func_array('array_merge', $ordered);

            $items_html = '';
            $total_system = $total = $subtotal = $gst = 0;
            foreach ($quote_items as $quote_item) {
                $total += floatval(str_replace(",", "", $quote_item->price)) * intval($quote_item->quantity);

                if ($quote_item->merged) {
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

//            $gst = floatval($total) / 10;
//            $subtotal = floatval($total) - floatval($gst);

        $variables['ITEMS'] = $items_html;
        $variables['TOTAL_SYSTEM'] = $quote->system_total;
        $variables['GST'] = number_format($quote->GST, 2);
        $variables['LABOR'] = number_format($quote->labor, 2);
        $variables['SUBTOTAL'] = number_format($quote->subtotal, 2);
        $variables['TOTAL'] = number_format($quote->total, 2);

        $variables['NOTES'] = $quote->printed_note ?
            "<h2 class=\"notesTitle\">NOTES</h2><table><tbody><tr><td>".$quote->printed_note."</td></tr></tbody></table>" :
            "";

        return Helper::GenerateTemplate('quote-pdf-template', $variables);
    }
}
