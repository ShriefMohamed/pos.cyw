<?php


namespace Framework\controllers;


use Framework\Lib\AbstractController;
use Framework\lib\FilterInput;
use Framework\lib\Helper;
use Framework\lib\Redirect;
use Framework\lib\Request;
use Framework\Lib\Session;
use Framework\lib\SSP;
use Framework\models\licenses\Digital_licenses_assignModel;
use Framework\models\licenses\Digital_licenses_templatesModel;
use Framework\models\licenses\Digital_licensesModel;
use Framework\models\pos\Inventory_counts_itemsModel;
use Framework\models\pos\Inventory_counts_tmp_print_itemsModel;
use Framework\models\pos\Inventory_countsModel;
use Framework\models\pos\Items_inventory_movementsModel;
use Framework\models\pos\Items_inventoryModel;
use Framework\models\pos\Purchase_orders_itemsModel;
use Framework\models\pos\Purchase_ordersModel;
use Framework\models\pos\Sales_shipmentsModel;
use Framework\models\quotes\Leader_itemsModel;
use Framework\models\quotes\Quotes_itemsModel;
use Framework\models\quotes\QuotesModel;
use Framework\models\UsersModel;
use Framework\models\CustomersModel;
use Framework\models\pos\BrandsModel;
use Framework\models\pos\CategoriesModel;
use Framework\models\pos\Customers_depositsModel;
use Framework\models\pos\DiscountsModel;
use Framework\models\pos\Items_pricingModel;
use Framework\models\pos\ItemsModel;
use Framework\models\pos\Payment_methodsModel;
use Framework\models\pos\Pricing_levelsModel;
use Framework\models\pos\Sales_itemsModel;
use Framework\models\pos\Sales_paymentsModel;
use Framework\models\pos\SalesModel;
use Framework\models\pos\Tax_classesModel;
use Framework\models\pos\Vendor_return_reasonsModel;
use Framework\models\pos\VendorsModel;
use Framework\models\xero\Xero_accountsModel;
use XeroAPI\XeroPHP\Models\Accounting\Item;
use function Matrix\trace;

class AjaxController extends AbstractController
{

/*POS Functions*/
    /*Delete Actions*/
    public function Vendor_deleteAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        $response = [];
        if ($id !== false) {
            $item = VendorsModel::getOne($id);
            if ($this->DeleteMethod($id, new VendorsModel())) {
                $this->logger->info("Vendor was deleted", array('Vendor: ' => $item->name, 'Admin: ' => Session::Get('loggedin')->username));
                $response = ['status' => 1];
            } else {
                $this->logger->error("Failed to delete vendor", array('Vendor: ' => $item->name, 'Admin: ' => Session::Get('loggedin')->username));
                $response = ['status' => 0, 'msg' => "Failed to delete vendor!"];
            }
        }
        die(json_encode($response));
    }

    public function Category_deleteAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        $response = [];
        if ($id !== false) {
            $item = CategoriesModel::getOne($id);
            if ($this->DeleteMethod($id, new CategoriesModel())) {
                $this->logger->info("Category was deleted", array('Category: ' => $item->category, 'Admin: ' => Session::Get('loggedin')->username));
                $response = ['status' => 1];
            } else {
                $this->logger->error("Failed to delete category", array('Category: ' => $item->category, 'Admin: ' => Session::Get('loggedin')->username));
                $response = ['status' => 0, 'msg' => "Failed to delete category!"];
            }
        }
        die(json_encode($response));
    }

    public function Brand_deleteAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        $response = [];
        if ($id !== false) {
            $item = BrandsModel::getOne($id);
            if ($this->DeleteMethod($id, new BrandsModel())) {
                $this->logger->info("Brand was deleted", array(Helper::AppendLoggedin(['Brand: ' => $item->brand])));
                $response = ['status' => 1];
            } else {
                $this->logger->error("Failed to delete brand", array(Helper::AppendLoggedin(['Brand: ' => $item->brand])));
                $response = ['status' => 0, 'msg' => "Failed to delete brand!"];
            }
        }
        die(json_encode($response));
    }

    public function Payment_method_deleteAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        $response = [];
        if ($id !== false) {
            $item = Payment_methodsModel::getOne($id);
            if ($this->DeleteMethod($id, new Payment_methodsModel())) {
                $this->logger->info("Payment method was deleted", Helper::AppendLoggedin(['Payment method: ' => $item->method]));
                $response = ['status' => 1];
            } else {
                $this->logger->error("Failed to delete Payment method", Helper::AppendLoggedin(['Payment method: ' => $item->method]));
                $response = ['status' => 0, 'msg' => "Failed to delete payment method!"];
            }
        }
        die(json_encode($response));
    }

    public function Tax_class_deleteAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        $response = [];
        if ($id !== false) {
            $item = Tax_classesModel::getOne($id);
            if ($this->DeleteMethod($id, new Tax_classesModel())) {
                $this->logger->info("Tax class was deleted", Helper::AppendLoggedin(['Tax class: ' => $item->class]));
                $response = ['status' => 1];
            } else {
                $this->logger->error("Failed to delete tax class", Helper::AppendLoggedin(['Tax class: ' => $item->class]));
                $response = ['status' => 0, 'msg' => "Failed to delete tax class!"];
            }
        }
        die(json_encode($response));
    }

    public function Pricing_level_deleteAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        $response = [];
        if ($id !== false) {
            $item = Pricing_levelsModel::getOne($id);
            if ($this->DeleteMethod($id, new Pricing_levelsModel())) {
                $this->logger->info("Pricing level was deleted", Helper::AppendLoggedin(['Pricing level: ' => $item->teir]));
                $response = ['status' => 1];
            } else {
                $this->logger->error("Failed to delete pricing level", Helper::AppendLoggedin(['Pricing level: ' => $item->teir]));
                $response = ['status' => 0, 'msg' => "Failed to delete pricing level!"];
            }
        }
        die(json_encode($response));
    }

    public function Xero_account_deleteAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        $response = [];
        if ($id !== false) {
            $item = Xero_accountsModel::getOne($id);
            if ($this->DeleteMethod($id, new Xero_accountsModel())) {
                $this->logger->info("Xero Account was deleted", Helper::AppendLoggedin(['Account Code: ' => $item->Code]));
                $response = ['status' => 1];
            } else {
                $this->logger->error("Failed to delete xero acount", Helper::AppendLoggedin(['Account Code: ' => $item->Code]));
                $response = ['status' => 0, 'msg' => "Failed to delete xero account!"];
            }
        }
        die(json_encode($response));
    }

    public function Return_reason_deleteAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        $response = [];
        if ($id !== false) {
            $item = Vendor_return_reasonsModel::getOne($id);
            if ($this->DeleteMethod($id, new Vendor_return_reasonsModel())) {
                $this->logger->info("Return reason was deleted", Helper::AppendLoggedin(['Return reason: ' => $item->reason]));
                $response = ['status' => 1];
            } else {
                $this->logger->error("Failed to delete return reason", Helper::AppendLoggedin(['Return reason: ' => $item->reason]));
                $response = ['status' => 0, 'msg' => "Failed to delete return reason!"];
            }
        }
        die(json_encode($response));
    }

    public function Discount_deleteAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        $response = [];
        if ($id !== false) {
            $item = DiscountsModel::getOne($id);
            if ($this->DeleteMethod($id, new DiscountsModel())) {
                $this->logger->info("Discount was deleted", Helper::AppendLoggedin(['Discount: ' => $item->title]));
                $response = ['status' => 1];
            } else {
                $this->logger->error("Failed to delete discount", Helper::AppendLoggedin(['Discount: ' => $item->title]));
                $response = ['status' => 0, 'msg' => "Failed to delete discount!"];
            }
        }
        die(json_encode($response));
    }

    public function Item_deleteAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        $response = [];
        if ($id !== false) {
            $item = ItemsModel::getOne($id);

            $object = new ItemsModel();
            $object->id = $id;

            if ($object->Delete()) {
                $inv = new Items_inventoryModel();
                $inv->Delete("item_id = '$id'");

                $pricing = new Items_pricingModel();
                $pricing->Delete("item_id = '$id'");

                $this->logger->info("Item was deleted", Helper::AppendLoggedin(['Item UID: ' => $item->uid]));
                $response = ['status' => 1];
            } else {
                $this->logger->error("Failed to delete item", Helper::AppendLoggedin(['Item UID: ' => $item->uid]));
                $response = ['status' => 0, 'msg' => "Failed to delete item!"];
            }
        }
        die(json_encode($response));
    }

    public function Customer_deleteAction($id)
    {
        $item = CustomersModel::getCustomer($id);

        $user = new UsersModel();
        $user->id = $item->id;
        if ($user->Delete()) {
            $customer = new CustomersModel();
            $customer->id = $item->customer_id ?: 0;
            $customer->Delete();

            $this->logger->info("Customer was deleted", Helper::AppendLoggedin(['Customer: ' => $item->firstName.' '.$item->lastName]));
            $response = ['status' => 1];
        } else {
            $this->logger->error("Failed to delete customer", Helper::AppendLoggedin(['Customer: ' => $item->firstName.' '.$item->lastName]));
            $response = ['status' => 0, 'msg' => "Failed to delete customer!"];
        }
        die(json_encode($response));
    }




    /* Fetch */
    public function Search_customersAction()
    {
        if (Request::Check('key')) {
            $data = CustomersModel::Search(FilterInput::String(Request::Post('key')));
            die(json_encode($data));
        }
    }

    public function Get_customerAction()
    {
        if (Request::Check('id')) {
            $id = FilterInput::Int(Request::Post('id'));
            $data = CustomersModel::getCustomer($id);
            die(json_encode($data));
        }
    }

    public function Search_itemsAction()
    {
        if (Request::Check('key')) {
            $options = '';
            if (Request::Check('vendor_id')) {
                $options = " && items_inventory.vendor_id = '".FilterInput::Int(Request::Post('vendor_id'))."'";
            }

            $data = ItemsModel::Search(FilterInput::String(Request::Post('key')), $options);
            die(json_encode($data));
        }
    }

    public function Get_itemAction()
    {
        if (Request::Check('id')) {
            $id = FilterInput::Int(Request::Post('id'));
            $data = ItemsModel::getItems("WHERE items.id = '$id'", true);
            die(json_encode($data));
        }
    }

    public function Get_item_simpleAction()
    {
        if (Request::Check('id')) {
            $id = FilterInput::Int(Request::Post('id'));
            $data = ItemsModel::getItemsSimple("WHERE items.id = '$id'", true);
            die(json_encode($data));
        }
    }

    public function GetItem_vendorReturnAction()
    {
        if (Request::Check('id')) {
            $id = FilterInput::Int(Request::Post('id'));
            $data = [
                'item' => ItemsModel::getItems("WHERE items.id = '$id'", true),
                'return_reasons' => Vendor_return_reasonsModel::getAll()
            ];

            die(json_encode($data));
        }
    }

    public function Get_items_purchase_ordersAction()
    {
        if (Request::Check('id')) {
            $id = FilterInput::Int(Request::Post('id'));

            $data = Purchase_orders_itemsModel::getOrderItems_itemDetails(
                "WHERE purchase_orders_items.item_id = '$id' &&
                 (purchase_orders_items.status = 'received' || purchase_orders_items.status = 'completed') 
                 ORDER BY created DESC"
            );

            die(json_encode($data));
        }
    }

    public function Get_taxClassesAction()
    {
        die(json_encode(array(
            'taxClasses' => Tax_classesModel::getAll()
        )));
    }

    public function Get_inventory_count_printout_itemAction()
    {
        if (Request::Check('id') && Request::Check('count_by_id_id')) {
            $inventory_count_id = FilterInput::Int(Request::Post('id'));
            $second_id = FilterInput::Int(Request::Post('count_by_id_id'));
            $data = Inventory_counts_tmp_print_itemsModel::getInventoryCountTmpItem($inventory_count_id, $second_id);
            die(json_encode($data));
        }
    }



    /* Create */
    public function Customer_addAction()
    {
        if (!empty($_POST)) {
            $result = false;

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
                $customer->notes = FilterInput::String(Request::Post('notes'));

                if ($customer->Save()) {
                    $result = $user;
                    $this->logger->info("New customer was created during sale.", Helper::AppendLoggedin(['Customer: ' => $user->firstName.' '.$user->lastName]));
                } else {
                    $this->logger->error("Failed to create new customer during sale. Customer error.", Helper::AppendLoggedin(['Customer: ' => $user->firstName.' '.$user->lastName]));
                }
            } else {
                $this->logger->error("Failed to create new customer during sale. User error.", Helper::AppendLoggedin(['Customer: ' => $user->firstName.' '.$user->lastName]));
            }

            die(json_encode($result));
        }
    }

    public function Item_misc_addAction()
    {
        $result = false;
        if (!empty($_POST)) {
            $item = new ItemsModel();
            $item->is_misc = 1;
            $item->item = FilterInput::String(Request::Post('item'));
            $item->description = FilterInput::String(Request::Post('item'));
            $item->tax_class = FilterInput::Int(Request::Post('tax_class'));

            // generate uid (first 3 char of the item name)
            $uid = substr($item->item, 0, 3);
            $uq_number = ItemsModel::generateUniqueNumber() ? ItemsModel::generateUniqueNumber()->random_num : 42561;
            $item->uid = strtoupper($uid) . $uq_number;

            // generate upc
            $upc_code = 425667 . sprintf("%05d", ItemsModel::getNextID()->next_id);
            $item->upc = $upc_code . Helper::CalculateUpcCheckDigit($upc_code);

            if ($item->Save()) {
                $pricing = new Items_pricingModel();
                $pricing->item_id = $item->id;
                $pricing->item_uid = $item->uid;
                $pricing->buy_price = FilterInput::Float(Request::Post('cost'));
                $pricing->rrp_price = FilterInput::Float(Request::Post('price'));
                $pricing->rrp_percentage = $pricing->buy_price && $pricing->rrp_price ?
                    substr(($pricing->rrp_price - $pricing->buy_price) / $pricing->buy_price * 100 , 0, 3) :
                    0;

                if ($pricing->Save()) {
                    $this->logger->info("New Misc Item was created successfully.", Helper::AppendLoggedin(['Item UID' => $item->uid]));
                    $result = ItemsModel::getItems("WHERE items.id = '$item->id'", true);
                } else {
                    $del_item = new ItemsModel();
                    $del_item->id = $item->id;
                    $del_item->Delete();

                    $this->logger->info("Failed to create new misc item. Item pricing error!", Helper::AppendLoggedin(['Item UID' => $item->uid]));
                }
            } else {
                $this->logger->info("Failed to create new misc item. Item insert error!", Helper::AppendLoggedin(['Item UID' => $item->uid]));
            }
        }

        die(json_encode($result));
    }

    public function Add_depositAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        if (Request::Check('customer_id') && Request::Check('deposit-amount')){
            $deposit = new Customers_depositsModel();
            $deposit->customer_id = FilterInput::Int(Request::Post('customer_id'));
            $deposit->sale_id = $id;
            $deposit->amount = FilterInput::Float(Request::Post('deposit-amount'));
            if ($deposit->Save()) {
                $this->logger->info("Customer deposit was saved.", Helper::AppendLoggedin(['Deposit: ' => $deposit->amount]));
                Helper::SetFeedback('success', "Customer deposit was saved successfully.");
            } else {
                $this->logger->error("Failed to save customer deposit.", Helper::AppendLoggedin(['Deposit: ' => $deposit->amount]));
                Helper::SetFeedback('error', "Failed to save customer deposit");
            }
        }

        Redirect::To('pos/sale_payment/' . $id);
    }



    /* Update */
    public function Shipment_updateAction()
    {
        $id = Request::Post('id');
        $shipped = Request::Post('shipped');
        $response = [];
        if ($id) {
            $shipment = new Sales_shipmentsModel();
            $shipment->id = $id;
            $shipment->shipped = $shipped == 'true' ? '1' : '0';
            $shipment->shipped_at = $shipped == true ? date('Y-m-d H:i:s') : '';
            if ($shipment->Save()) {
                $response = ['status' => 1];
            } else {
                $response = ['status' => 0, 'msg' => "Failed to update sale's shipment!"];
            }
        }
        die(json_encode($response));
    }


    public function Sale_remove_customerAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        if ($id !== false) {
            $sale = new SalesModel();
            $sale->id = $id;
            $sale->customer_id = 0;
            if ($sale->Save()) {
                $this->logger->info("Customer was removed from sale.", Helper::AppendLoggedin(['Sale ID: ' => $id]));
                Helper::SetFeedback('success', "Customer was removed from sale.");
            } else {
                $this->logger->info("Failed to remove customer from sale.", Helper::AppendLoggedin(['Sale ID: ' => $id]));
                Helper::SetFeedback('error', "Failed to remove customer from sale.");
            }

            Redirect::ReturnURL();
        }
    }

    public function Sale_remove_itemAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        $response = [];
        if ($id !== false) {
            if ($this->DeleteMethod($id, new Sales_itemsModel())) {
                $response = ['status' => 1];
            } else {
                $response = ['status' => 0, 'msg' => "Failed to remove sale's item!"];
            }
        }
        die(json_encode($response));
    }

    public function Sale_add_customerAction()
    {
        $sale_id = ($this->_params) != null ? $this->_params[0] : false;
        $customer_id = $this->_params && isset($this->_params[1]) && $this->_params[1] ? $this->_params[1] : false;
        if ($sale_id && $customer_id) {
            $sale = new SalesModel();
            $sale->id = $sale_id;
            $sale->customer_id = $customer_id;
            if ($sale->Save()) {
                $this->logger->info("Customer was added to sale.", Helper::AppendLoggedin(['Sale ID: ' => $sale_id]));
                Helper::SetFeedback('success', "Customer was added to sale.");
            } else {
                $this->logger->info("Failed to add customer to sale.", Helper::AppendLoggedin(['Sale ID: ' => $sale_id]));
                Helper::SetFeedback('error', "Failed to add customer to sale.");
            }

            Redirect::ReturnURL();
        }
    }

    public function Sale_voidAction($sale_id)
    {
        if ($sale_id !== false) {
            $sale = new SalesModel();
            $sale->id = $sale_id;
            $sale->sale_type = 'voided';
            if ($sale->Save()) {
                $this->logger->info("Sale was voided successfully.", Helper::AppendLoggedin(['Sale ID: ' => $sale_id]));
                Helper::SetFeedback('success', "Sale was voided successfully.");

                // get all sale's items, return every item's inventory
                $original_sale_items = Sales_itemsModel::getAll("WHERE sale_id = '$sale_id'");
                if ($original_sale_items) {
                    foreach ($original_sale_items as $original_sale_item) {
                        $inventory = new Items_inventoryModel();
                        $inventory->id = $original_sale_item->inventory_id;
                        $inventory->qoh = $original_sale_item->qoh + $original_sale_item->quantity;
                        if (!$inventory->Save()) {
                            $this->logger->error("Save was voided, but failed to update inventory, correct manually!", Helper::AppendLoggedin(['Sale ID' => $original_sale_item->sale_id, 'Item ID' => $original_sale_item->item_id]));
                        }

                        $inventory_movement = new Items_inventory_movementsModel();
                        $inventory_movement->item_id = $original_sale_item->item_id;
                        $inventory_movement->inventory_header_id = $original_sale_item->inventory_id;
                        $inventory_movement->type = 'sale void';
                        $inventory_movement->quantity = '+'.$original_sale_item->quantity;
                        $inventory_movement->source = "pos/sale/$sale_id";
                        if (!$inventory_movement->Save()) {
                            $this->logger->error("Sale was voided, but failed to create inventory movement record!", Helper::AppendLoggedin(['Sale ID' => $original_sale_item->sale_id, 'Item ID' => $original_sale_item->item_id]));
                        }
                    }
                }

                $sale_items = new Sales_itemsModel();
                if (!$sale_items->Delete("sale_id = '$sale_id'")) {
                    $this->logger->error("Sale was voided but not all sale items were removed, MUST REMOVE MANUALLY FOR CORRECT INVENTORY NUMBERS!", Helper::AppendLoggedin(['Sale ID: ' => $sale_id]));
                }

                $sale_payments = new Sales_paymentsModel();
                if (!$sale_payments->Delete("sale_id = '$sale_id'")) {
                    $this->logger->error("Sale was voided but not all sale payments were removed, MUST REMOVE MANUALLY FOR CORRECT MONEY CALCULATIONS!", Helper::AppendLoggedin(['Sale ID: ' => $sale_id]));
                }

                Redirect::To('pos/sales_list');
            } else {
                $this->logger->error("Failed to void sale!", Helper::AppendLoggedin(['Sale ID: ' => $sale_id]));
                Helper::SetFeedback('error', "Failed to void sale!");
                Redirect::To('pos/sale/'.$sale_id);
            }
        }
    }

    public function Quote_voidAction($quote_id)
    {
        $quote = new QuotesModel();
        $quote->id = $quote_id;
        $quote->status = 'voided';
        if ($quote->Save()) {
            $this->logger->info("Quote was voided successfully.", Helper::AppendLoggedin(['Quote ID: ' => $quote_id]));
            Helper::SetFeedback('success', "Quote was voided successfully.");
            Redirect::To('pos/quotes');
        } else {
            $this->logger->error("Failed to void quote!", Helper::AppendLoggedin(['Quote ID: ' => $quote_id]));
            Helper::SetFeedback('error', "Failed to void quote!");
            Redirect::To('pos/quotes/'.$quote_id);
        }
    }


    public function Purchase_order_remove_itemAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        $response = ['status' => 0, 'msg' => "Failed to remove item!"];
        if ($id !== false) {
            $order_item = Purchase_orders_itemsModel::getOne($id);
            $order = Purchase_ordersModel::getOne($order_item->order_id);
            if ($order_item && $order) {
                $item = new Purchase_orders_itemsModel();
                $item->id = $id;
                if ($item->Delete()) {
                    $this->logger->info("Item was removed from purchase order!", Helper::AppendLoggedin(['Order ID' => $order_item->order_id, 'Item ID: ' => $order_item->item_id]));

                    $p_order = new Purchase_ordersModel();
                    $p_order->id = $order->id;
                    $p_order->order_subtotal = floatval($order->order_subtotal) - floatval($order_item->total);
                    $p_order->order_total = floatval($order->order_subtotal) - floatval($order_item->total);
                    if ($p_order->Save()) {
                        $this->logger->info("Purchase order's total was updated after item was removed!", Helper::AppendLoggedin(['Order ID' => $order_item->order_id, 'Item ID: ' => $order_item->item_id]));
                        $response = ['status' => 1];
                    } else {
                        $this->logger->error("Failed to update purchase order's total after item removal! MUST REMOVE MANUALLY FOR CORRECT TOTALS!", Helper::AppendLoggedin(['Order ID' => $order_item->order_id, 'Item ID: ' => $order_item->item_id]));
                    }
                } else {
                    $this->logger->error("Failed to remove item from purchase order!", Helper::AppendLoggedin(['Order ID' => $order_item->order_id, 'Item ID: ' => $order_item->item_id]));
                }
            }

        }
        die(json_encode($response));
    }


    public function Inventory_count_remove_itemAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        $response = ['status' => 0, 'msg' => "Failed to remove item!"];
        if ($id !== false) {

            $count_item = Inventory_counts_itemsModel::getOne($id);
            if ($count_item) {
                $item = new Inventory_counts_itemsModel();
                $item->id = $id;
                if ($item->Delete()) {
                    $this->logger->info("Item was removed from inventory count!", Helper::AppendLoggedin(['Inventory Count ID' => $count_item->inventory_count_id, 'Item ID: ' => $count_item->item_id]));
                    $response = ['status' => 1];
                } else {
                    $this->logger->error("Failed to remove item from inventory count!", Helper::AppendLoggedin(['Inventory Count ID' => $count_item->inventory_count_id, 'Item ID: ' => $count_item->item_id]));
                }
            }

        }
        die(json_encode($response));
    }

    public function Inventory_count_zero_item_inventoryAction()
    {
        $inventory_count_id = ($this->_params) != null ? $this->_params[0] : false;
        $item_id = $this->_params && isset($this->_params[1]) ? $this->_params[1] : false;
        if ($item_id) {
            $item_inventories = Items_inventoryModel::getAll("WHERE item_id = '$item_id'");
            if ($item_inventories) {
                foreach ($item_inventories as $item_inventory) {
                    $item_inventory_update = new Items_inventoryModel();
                    $item_inventory_update->id = $item_inventory->id;
                    $item_inventory_update->qoh = 0;
                    if ($item_inventory_update->Save()) {
                        $inventory_movement = new Items_inventory_movementsModel();
                        $inventory_movement->item_id = $item_id;
                        $inventory_movement->inventory_header_id = $item_inventory->id;
                        $inventory_movement->type = 'inventory count, zero item inventory';
                        $inventory_movement->quantity = '-'.$item_inventory->qoh;
                        $inventory_movement->source = "pos/inventory_count/$inventory_count_id";
                        if (!$inventory_movement->Save()) {
                            $this->logger->error("Item's inventory was zeroed during inventory count, but failed to create inventory movement record!", Helper::AppendLoggedin(['Inventory Count ID' => $inventory_count_id, 'Item ID' => $item_id]));
                        }

                        $this->logger->info("Item's inventory was zeroed during inventory count.", Helper::AppendLoggedin(['Inventory Count ID' => $inventory_count_id, 'Item ID: ' => $item_id]));
                    }
                }
            }
        }

        Redirect::To('pos/inventory_count/'.$inventory_count_id.'#missed');
    }

    public function Inventory_count_reconcileAction()
    {
        $inventory_count_id = ($this->_params) != null ? $this->_params[0] : false;
        if ($inventory_count_id) {
            $inventory_count_items = Inventory_counts_itemsModel::getAll(
                "WHERE inventory_count_id = '$inventory_count_id' && should_have != counted"
            );

            if ($inventory_count_items) {
                foreach ($inventory_count_items as $inventory_count_item) {
                    $inventory_change = $inventory_count_item->counted - $inventory_count_item->should_have;
                    $inventoryItem_itemInventory = Items_inventoryModel::getAll(
                        "WHERE item_id = '$inventory_count_item->item_id'"
                    , true);
                    if ($inventoryItem_itemInventory) {
                        $inventory_update = new Items_inventoryModel();
                        $inventory_update->id = $inventoryItem_itemInventory->id;
                        $inventory_update->qoh = $inventoryItem_itemInventory->qoh + $inventory_change;
                        if ($inventory_update->Save()) {
                            $inventory_movement = new Items_inventory_movementsModel();
                            $inventory_movement->item_id = $inventory_count_item->item_id;
                            $inventory_movement->inventory_header_id = $inventoryItem_itemInventory->id;
                            $inventory_movement->type = "inventory count reconcile";
                            $inventory_movement->quantity = $inventory_change;
                            $inventory_movement->source = 'pos/inventory_count/'.$inventory_count_id;
                            if (!$inventory_movement->Save()) {
                                $this->logger->error("Item's inventory was reconciled during inventory count, but failed to create inventory movement record!", Helper::AppendLoggedin(['Inventory Count ID' => $inventory_count_id, 'Item ID' => $inventory_count_item->item_id]));
                            }

                            $this->logger->info("Item's inventory was reconciled during inventory count.", Helper::AppendLoggedin(['Inventory Count ID' => $inventory_count_id, 'Item ID' => $inventory_count_item->item_id]));
                        }
                    } else {
                        $this->logger->error("Failed to reconcile item's inventory during inventory count. Couldn't find inventory record.", Helper::AppendLoggedin(['Inventory Count ID' => $inventory_count_id, 'Item ID' => $inventory_count_item->item_id]));
                    }
                }
            }
        }

        Redirect::To('pos/inventory_count/'.$inventory_count_id.'#totals');
    }
/*POS Functions*/



/*Quotes System functions*/
    /*public function Search_leaderItemsAction()
    {
        $category = Request::Check('category') ? Request::Post('category') : '';
        $sub_category = Request::Check('subcategory') ? Request::Post('subcategory') : '';
//        $search = isset($_POST['search']) && isset($_POST['search']['value']) ? $_POST['search']['value'] : '';
//        $filter = $category ? "CategoryName LIKE '%$category%' " : '';
//        $filter .= $sub_category ?
//            $filter ? "&& SubcategoryName LIKE '%$sub_category%'" : "SubcategoryName LIKE '%$sub_category%'"
//            : '';
//        $where = $filter ? "WHERE " . $filter : '';

        $filter = array();
        if ($category) {
            $filter[] = "CategoryName LIKE '%$category%'";
        }
        if ($sub_category) {
            $filter[] = "SubcategoryName LIKE '%$sub_category%'";
        }
//        if ($search) {
//            $filter[] = "ProductName LIKE '%$search%'";
//        }


        $table = 'leader_items';
        $primaryKey = 'id';
        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'IMAGE', 'dt' => 1),
            array('db' => 'ProductName', 'dt' => 2),
            array('db' => 'StockCode', 'dt' => 3),
            array('db' => 'Manufacturer', 'dt' => 4),
            array(
                'db' => 'DBP',
                'dt' => 5,
                'formatter' => function ($d, $row) {
                    return '$' . number_format($d, 2);
                }
            ),
            array(
                'db' => 'RRP',
                'dt' => 6,
                'formatter' => function ($d, $row) {
                    return '$' . number_format($d, 2);
                }
            ),
            array('db' => 'Description', 'dt' => 7),
            array('db' => 'AA', 'dt' => 8),
            array('db' => 'AQ', 'dt' => 9),
            array('db' => 'AN', 'dt' => 10),
            array('db' => 'AV', 'dt' => 11),
            array('db' => 'AW', 'dt' => 12),

            array('db' => 'ETAA', 'dt' => 13),
            array('db' => 'ETAQ', 'dt' => 14),
            array('db' => 'ETAN', 'dt' => 15),
            array('db' => 'ETAV', 'dt' => 16),
            array('db' => 'ETAW', 'dt' => 17)
        );

        echo json_encode(
            SSP::complex($_POST, $table, $primaryKey, $columns, null, $filter)
        );


//
//        $data = Leader_itemsModel::getAll($where);
//        die(json_encode($data));
    }*/

    public function Quotes_search_customersAction()
    {
        $first_name_input = Request::Check('first_name_input') ? Request::Post('first_name_input') : '';
        $last_name_input = Request::Check('last_name_input') ? Request::Post('last_name_input') : '';
        $email_input = Request::Check('email_input') ? Request::Post('email_input') : '';
        $phone_number_input = Request::Check('phone_number_input') ? Request::Post('phone_number_input') : '';

        $filter = $first_name_input ? " && users.firstName LIKE '%$first_name_input%' " : '';
        $filter .= $last_name_input ? " && users.lastName LIKE '%$last_name_input%' " : '';
        $filter .= $email_input ? " && users.email LIKE '%$email_input%' " : '';
        $filter .= $phone_number_input ? " && users.phone LIKE '%$phone_number_input%' " : '';

        $data = CustomersModel::getCustomersForQuotes($filter);

        die(json_encode($data));
    }

    public function Quotes_get_customerAction($id)
    {
        $response = false;
        if ($id != false) {
            $response = CustomersModel::getCustomersForQuotes(" && users.id = '$id'", true);
        }
        die(json_encode($response));
    }

    public function Search_leaderItemsAction()
    {
        $page = ($this->_params) != null && is_numeric($this->_params[0]) ? $this->_params[0] : 1;
        $per_page = isset($this->_params[1]) && $this->_params[1] && is_numeric($this->_params[1]) ? $this->_params[1] : 10;

        $category = Request::Check('category') ? Request::Post('category') : '';
        $sub_category = Request::Check('subcategory') ? Request::Post('subcategory') : '';
        $manufacturer = Request::Check('manufacturer') ? Request::Post('manufacturer') : '';
        $search = Request::Check('search') ? addslashes(Request::Post('search')) : '';
        $minPrice = Request::Check('minPrice') && intval(Request::Post('minPrice')) ? Request::Post('minPrice') : 0;
        $maxPrice = Request::Check('maxPrice') && intval(Request::Post('maxPrice')) ? Request::Post('maxPrice') : false;
        $selectedPrice = Request::Post('selectedPrice');
        $showOnlyAvailable = Request::Check('showOnlyAvailable') ? Request::Post('showOnlyAvailable') : '';
        $sortBy = Request::Check('sortBy') ? Request::Post('sortBy') : '';
        $sortByType = Request::Check('sortByType') ? Request::Post('sortByType') : '';

        // Add category filter
        $filter1 = $category ?
            "CategoryName LIKE '%$category%' " : '';
        // Add sub-category filter
        $filter1 .= $sub_category ?
            $filter1 ? "&& SubcategoryName LIKE '%$sub_category%' " : "SubcategoryName LIKE '%$sub_category%' " : '';
        // Add manufacturer filter
        $filter1 .= $manufacturer ?
            $filter1 ? "&& Manufacturer LIKE '%$manufacturer%' " : "Manufacturer LIKE '%$manufacturer%' " : '';
        // Add text filter
        $filter1 .= $search ?
            $filter1 ?
                '&& (StockCode LIKE "%'.$search.'%" || ProductName LIKE "%'.$search.'%" || Description LIKE "%'.$search.'%") ' :
                '(StockCode LIKE "%'.$search.'%" || ProductName LIKE "%'.$search.'%" || Description LIKE "%'.$search.'%") '
            : '';


        // Add min & max price filter
        $filter = $filter1;
        $filter .= $maxPrice != false && $maxPrice > 0 ?
            $filter ? "&& (DBP BETWEEN '$minPrice' AND '$maxPrice') " : " (DBP BETWEEN '$minPrice' AND '$maxPrice') " : '';
        $filter .= $showOnlyAvailable == 'true' ?
            $filter ? "&& (AT > 0 || AT = '>5') " : "(AT > 0 || AT = '>5') " : '';

        $where = $filter ? "WHERE " . $filter : '';

        $order_by = 'ORDER BY id ';
        if ($sortBy) {
            $order_by_no_cases_array = array('StockCode', 'ProductName', 'Manufacturer', 'DBP', 'RRP');
            if (in_array($sortBy, $order_by_no_cases_array)) {
                $order_by = " ORDER BY ".$sortBy;
            } else {
                switch ($sortBy) {
                    case 'AvailAA':
                        $order_by = "ORDER BY CASE WHEN AA > 0 THEN 1 WHEN AA = '>5' THEN 2 WHEN AA = 'CALL' THEN 3 END";
                        break;
                    case 'AvailAQ':
                        $order_by = "ORDER BY CASE WHEN AQ > 0 THEN 1 WHEN AQ = 'CALL' THEN 3 END";
                        break;
                    case 'AvailAV':
                        $order_by = "ORDER BY CASE WHEN AV > 0 THEN 1 WHEN AV = 'CALL' THEN 3 END";
                        break;
                    case 'AvailAN':
                        $order_by = "ORDER BY CASE WHEN AN > 0 THEN 1 WHEN AN = 'CALL' THEN 3 END";
                        break;
                    case 'AvailAW':
                        $order_by = "ORDER BY CASE WHEN AW > 0 THEN 1 WHEN AW = 'CALL' THEN 3 END";
                        break;
                    default:
                        $order_by = 'ORDER BY id ';
                        break;
                }
            }
        }

        $order_by .= $sortByType == 'true' ? ' DESC ' : ' ASC ';

        $total_records = Leader_itemsModel::Count($where);
        $start_from = ($page-1) * $per_page;
        $total_pages = ceil($total_records / $per_page);

        $limit = " LIMIT " . $start_from . ', ' . $per_page;

        $data = Leader_itemsModel::getAll($where.$order_by.$limit);

        $max_dbp = Leader_itemsModel::getColumns(['MAX(dbp) AS max'], ($filter1 ?: '1'), true);
        $current_price = false;
        if ($selectedPrice == 'true') {
            $current_price['min'] = $minPrice;
            $current_price['max'] = $maxPrice && $maxPrice > 0 && $maxPrice < $max_dbp ? $maxPrice : false;
        }

        die(json_encode(array(
            'where' => $where.$order_by.$limit,
            'data' => $data,
            'max_price' => $max_dbp,
            'current_price' => $current_price,
            'total_records' => $total_records,
            'total_pages' => $total_pages
        )));
    }

    public function Get_leaderItemAction()
    {
        if (Request::Check('id')) {
            $id = FilterInput::Int(Request::Post('id'));
            $data = Leader_itemsModel::getOne($id);
            die(json_encode($data));
        }
    }

    public function Get_subcategoriesAction()
    {
        $category = Request::Check('category') ? Request::Post('category') : '';
        die(json_encode(Leader_itemsModel::getSubCategories("WHERE CategoryName LIKE '%$category%'")));
    }

    public function Get_manufacturersAction()
    {
        $category = Request::Check('category') ? Request::Post('category') : '';
        $subCategory = Request::Check('subcategory') ? Request::Post('subcategory') : '';
        die(json_encode(Leader_itemsModel::getManufacturers("WHERE CategoryName LIKE '%$category%' && SubcategoryName LIKE '%$subCategory%'")));
    }

    public function Quote_item_deleteAction()
    {
        $id = Request::Check('id') ? Request::Post('id') : '';
        $quote_id = Request::Check('quote_id') ? Request::Post('quote_id') : '';
        $response = false;
        if ($id && $quote_id) {
            $original_item = Quotes_itemsModel::getOne($id);
            $original_quote = QuotesModel::getOne($quote_id);

            $item = new Quotes_itemsModel();
            $item->id = $id;
            if ($item->Delete()) {
                $quote = new QuotesModel();
                $quote->id = $quote_id;
                $quote->total = $original_quote->total - $original_item->price;
                $quote->GST = $quote->total ? $quote->total / 10 : 0;
                $quote->subtotal = $quote->total - ($quote->GST + $original_quote->labor);

                if ($original_item->merged == '1') {
                    $quote->DBP = $original_quote->DBP - $original_item->original_price;
                    $quote->system_total = $original_quote->system_total - $original_item->price;
                }

                if ($quote->Save()) {
                    $response = true;
                }
            }
        }
        die(json_encode($response));
    }
/* End Quotes System*/


/* Licenses System */
    public function Get_licenses_by_productAction($id)
    {
        die(json_encode(Digital_licensesModel::getAll("WHERE item_id = '$id'")));
    }

    public function get_license_templates_by_productAction($product_id)
    {
        $response = false;

        $license = Digital_licensesModel::getAll("WHERE item_id = '$product_id' && used != '1' && expired != '1'", true);
        if ($license) {
            $product_templates = [];
            $templates = array_column(Digital_licenses_templatesModel::getAll(), 'template_name');
            if (in_array($license->template, $templates) &&
                file_exists(DIGITAL_LICENCES_TEMPLATES_PATH.$license->template.'.html')
            ) {
                $product_templates[] = $license->template;
            }

            $response = [
                'license' => $license,
                'templates' => $product_templates ?: $templates
            ];
        }

        die(json_encode($response));
    }

    public function get_licenses_by_customerAction($customer_id)
    {
        $type = Request::Check('type') ? Request::Post('type') : '';
        $where = '';
        if ($type) {
            $where = "WHERE digital_licenses_assigned_licenses.license_status = '$type' ";
            $where .= $type == 'expired' ? "&& digital_licenses_assign.status = 'expired' " : " && digital_licenses_assign.status != 'expired'";
            $where .= "GROUP BY digital_licenses_assign.id";
        }
        die(json_encode(Digital_licenses_assignModel::getCustomerLicenses($where)));
    }
/* Licenses System */



/* Global Functions*/
    private function DeleteMethod($id, $object)
    {
        $object->id = $id;
        return $object->Delete() ? true : false;
    }


    public function DeleteAction($id)
    {
        $class = Request::Check('target', 'get') ? '\Framework\models\\'.Request::Get('target').'Model' : '';
        if (class_exists($class)) {
            $item = new $class;
            $item->id = $id;
            if ($item->Delete()) {
                $this->logger->info("Item was deleted successfully.", Helper::AppendLoggedin(['Class Name' => Request::Get('target')]));
                $response = ['status' => 1];
            } else {
                $this->logger->error("Failed to delete item.", Helper::AppendLoggedin(['Class Name' => Request::Get('target')]));
                $response = ['status' => 0, 'msg' => "Failed to delete item!"];
            }
        } else {
            $this->logger->error("Failed to delete item. Class doesn't exist!", Helper::AppendLoggedin(['Class Name' => Request::Get('target')]));
            $response = ['status' => 0, 'msg' => "Failed to delete item!"];
        }

        die(json_encode($response));
    }

    public function ArchiveAction($id)
    {
        $class = Request::Check('target', 'get') ? '\Framework\models\\'.Request::Get('target').'Model' : '';
        if (class_exists($class)) {
            $item = new $class;
            $item->id = $id;
            $item->status = 'archived';
            if ($item->Save()) {
                $this->logger->info("Item was archived successfully.", Helper::AppendLoggedin(['Class Name' => Request::Check('target', 'get')]));
            } else {
                $this->logger->error("Failed to archive item.", Helper::AppendLoggedin(['Class Name' => Request::Check('target', 'get')]));
            }
        } else {
            $this->logger->error("Failed to archive item. Class doesn't exist!", Helper::AppendLoggedin(['Class Name' => Request::Check('target', 'get')]));
        }

        Redirect::ReturnURL();
    }

    public function Un_archiveAction($id)
    {
        $class = Request::Check('target', 'get') ? '\Framework\models\\'.Request::Get('target').'Model' : '';
        if (class_exists($class)) {
            $item = new $class;
            $item->id = $id;
            $item->status = 'active';
            if ($item->Save()) {
                $this->logger->info("Item was un-archived successfully.", Helper::AppendLoggedin(['Class Name' => Request::Check('target', 'get')]));
            } else {
                $this->logger->error("Failed to un-archive item.", Helper::AppendLoggedin(['Class Name' => Request::Check('target', 'get')]));
            }
        } else {
            $this->logger->error("Failed to un-archive item. Class doesn't exist!", Helper::AppendLoggedin(['Class Name' => Request::Check('target', 'get')]));
        }

        Redirect::ReturnURL();
    }
}