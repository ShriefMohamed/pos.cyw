<?php


namespace Framework\controllers;


use Framework\lib\AbstractController;
use Framework\lib\FilterInput;
use Framework\lib\Helper;
use Framework\lib\LoggerModel;
use Framework\lib\Redirect;
use Framework\lib\Request;
use Framework\Lib\Session;
use Framework\models\CustomersModel;
use Framework\models\licenses\Digital_licenses_assignModel;
use Framework\models\pos\DiscountsModel;
use Framework\models\pos\Sales_paymentsModel;
use Framework\models\pos\SalesModel;
use Framework\models\quotes\QuotesModel;
use Framework\models\UsersModel;

class CustomersController extends AbstractController
{
    public function DefaultAction()
    {
        $this->RenderPos();
    }

    public function CustomersAction()
    {
        $where = "WHERE users.role = 'customer' ";
        if (Request::Check('filter', 'get')) {
            $where .= Request::Get('filter') == 'xero' ? " && !ISNULL(customers.ContactID) " : "";
        }

        $this->RenderPos([
            'data' => CustomersModel::getCustomers($where)
        ]);
    }

    public function CustomerAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        if ($id !== false) {
            $customer = CustomersModel::getCustomers("WHERE users.id = '$id'", true);
            $customer_sales = SalesModel::getAllSales("WHERE sales.customer_id = '$id'");
            $customer_sales_items = SalesModel::getCustomerSalesItems($id);


            $logs_stream = LoggerModel::getLogStream($id, 'customers');
            $logs = (file_exists($logs_stream)) ? file_get_contents($logs_stream) : null;
            $logs = array_reverse(explode('*', $logs));

            $totals = array();
            $original_subtotal = $discounts = $sub_total = $cost = 0;

            if ($customer_sales_items) {
                foreach ($customer_sales_items as $customer_sales_item) {
                    if ($customer_sales_item->item_total > 0) {
                        $original_subtotal += $customer_sales_item->original_price * $customer_sales_item->quantity;
                        $discounts += $customer_sales_item->item_discount * $customer_sales_item->quantity;
                        $sub_total += $customer_sales_item->item_total;
                        $cost += $customer_sales_item->buy_price * $customer_sales_item->quantity;
                    }
                }

                $totals = array(
                    'original_sub_total' => $original_subtotal,
                    'discounts' => $discounts,
                    'sub_total' => $sub_total,
                    'cost' => $cost,
                    'profit' => $sub_total - $cost,
                    'margin' => substr((($sub_total - $cost) / $cost) * 100, 0, 5)
                );
            }

            $this->RenderPos([
                'customer' => $customer,
                'discounts' => DiscountsModel::getAll(),
                'sales' => $customer_sales,
                'sales_totals' => $totals ? (Object)$totals : $totals,
                'sales_payments' => Sales_paymentsModel::getCustomerSalesPayments($id),
                'quotes' => QuotesModel::getQuotes("WHERE customer_id = '$customer->customer_id'"),
                'logs' => $logs,
                'licenses' => Digital_licenses_assignModel::getCustomerLicenses("WHERE digital_licenses_assign.customer_id = '$customer->customer_id' GROUP BY digital_licenses_assign.id")
            ]);
        }
    }

    public function Customer_addAction()
    {
        if (Request::Check('submit')) {
            $user = new UsersModel();
            $user->firstName = FilterInput::String(Request::Post('f_name'));
            $user->lastName = FilterInput::String(Request::Post('l_name'));
            $user->phone = FilterInput::String(Request::Post('mobile'));
            $user->phone2 = FilterInput::String(Request::Post('phone'));
            $user->email = FilterInput::Email(Request::Post('email'));
            $user->role = 'customer';

            if ($user->Save()) {
                $customer = new CustomersModel();
                $customer->user_id = $user->id;
                $customer->companyName = FilterInput::String(Request::Post('company'));
                $customer->discount_id = FilterInput::Int(Request::Post('discount_id'));
                $customer->address = FilterInput::String(Request::Post('address1'));
                $customer->address2 = FilterInput::String(Request::Post('address2'));
                $customer->city = FilterInput::String(Request::Post('city'));
                $customer->suburb = FilterInput::String(Request::Post('suburb'));
                $customer->zip = FilterInput::String(Request::Post('zip'));
                $customer->website = FilterInput::String(Request::Post('website'));
                $customer->notes = FilterInput::String(Request::Post('notes'));

                $customer->emailNotifications = Request::Check('emailNotification') ? 1 : 2;
                $customer->smsNotifications = Request::Check('smsNotification') ? 1 : 2;

                if ($customer->Save()) {
                    Helper::SetFeedback('success', "Customer was created successfully.");
                    $this->logger->info("New customer was created", Helper::AppendLoggedin(['Customer: ' => $user->firstName.' '.$user->lastName]));
                    LoggerModel::Instance($user->id, 'customers')
                        ->InitializeLogger()
                        ->info('New customer was created.', Helper::AppendLoggedin());

                    Redirect::To('customers/customers');
                } else {
                    Helper::SetFeedback('error', "Failed to create new customer!");
                    $this->logger->error("Failed to create new customer. Customer error.", Helper::AppendLoggedin(['Customer: ' => $user->firstName.' '.$user->lastName]));
                    LoggerModel::Instance($user->id, 'customers')
                        ->InitializeLogger()
                        ->info('New customer was created.', Helper::AppendLoggedin());
                }
            } else {
                Helper::SetFeedback('error', "Failed to create new customer!");
                $this->logger->error("Failed to create new customer. User error.", Helper::AppendLoggedin(['Customer: ' => $user->firstName.' '.$user->lastName]));
            }
        }

        $this->RenderPos([
            'discounts' => DiscountsModel::getAll()
        ]);
    }

    public function Customer_updateAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        if ($id !== false) {
            if (!empty($_POST)) {
                $user = new UsersModel();
                $user->id = $id;
                $user->firstName = FilterInput::String(Request::Post('f_name'));
                $user->lastName = FilterInput::String(Request::Post('l_name'));
                $user->phone = FilterInput::String(Request::Post('mobile'));
                $user->phone2 = FilterInput::String(Request::Post('phone'));
                $user->email = FilterInput::Email(Request::Post('email'));

                if ($user->Save()) {
                    $customer = new CustomersModel();
                    $customer->id = FilterInput::Int(Request::Post('customer_id'));
                    $customer->companyName = FilterInput::String(Request::Post('company'));
                    $customer->discount_id = FilterInput::Int(Request::Post('discount_id'));
                    $customer->address = FilterInput::String(Request::Post('address1'));
                    $customer->address2 = FilterInput::String(Request::Post('address2'));
                    $customer->city = FilterInput::String(Request::Post('city'));
                    $customer->suburb = FilterInput::String(Request::Post('suburb'));
                    $customer->zip = FilterInput::String(Request::Post('zip'));
                    $customer->website = FilterInput::String(Request::Post('website'));
                    $customer->notes = FilterInput::String(Request::Post('notes'));

                    $customer->credit_limit = FilterInput::Float(Request::Post('credit_limit'));

                    $customer->emailNotifications = Request::Check('emailNotification') ? 1 : 2;
                    $customer->smsNotifications = Request::Check('smsNotification') ? 1 : 2;

                    if ($customer->Save()) {
                        Helper::SetFeedback('success', "Customer was updated successfully.");

                        $this->logger->info("Customer was updated", Helper::AppendLoggedin(['Customer: ' => $user->firstName . ' ' . $user->lastName]));
                        LoggerModel::Instance($user->id, 'customers')
                            ->InitializeLogger()
                            ->info('Customer was updated.', Helper::AppendLoggedin());

                        Redirect::To('customers/customers');
                    } else {
                        Helper::SetFeedback('error', "Failed to updated customer!");

                        $this->logger->error("Failed to updated customer. Customer error.", Helper::AppendLoggedin(['Customer: ' => $user->firstName . ' ' . $user->lastName]));
                        LoggerModel::Instance($user->id, 'customers')
                            ->InitializeLogger()
                            ->error("Failed to updated customer. Customer error.", Helper::AppendLoggedin());
                    }
                } else {
                    Helper::SetFeedback('error', "Failed to updated customer!");

                    $this->logger->error("Failed to updated customer. User error.", Helper::AppendLoggedin(['Customer: ' => $user->firstName . ' ' . $user->lastName]));
                    LoggerModel::Instance($user->id, 'customers')
                        ->InitializeLogger()
                        ->error("Failed to updated customer. User error.", Helper::AppendLoggedin());
                }
            }

            Redirect::To('customers/customer/'.$id);
        }
    }

    public function Customers_notes_searchAction()
    {
        $customers_with_notes = CustomersModel::getCustomers("WHERE users.role = 'customer' && !ISNULL(notes)");

        $this->RenderPos([
            'data' => $customers_with_notes
        ]);
    }

    public function Customers_quotesAction()
    {
        $quotes = QuotesModel::getQuotes(" WHERE !ISNULL(quotes.customer_id)");
        $sales = SalesModel::getAllSales(" WHERE sales.sale_type = 'quote'");
        $this->RenderPos([
            'quotes' => $quotes,
            'data' => $sales
        ]);
    }

}