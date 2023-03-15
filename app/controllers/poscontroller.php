<?php


namespace Framework\controllers;


use Framework\Lib\AbstractController;
use Framework\lib\AbstractModel;
use Framework\lib\FilterInput;
use Framework\lib\Helper;
use Framework\lib\LoggerModel;
use Framework\lib\MailModel;
use Framework\lib\Redirect;
use Framework\lib\Request;
use Framework\Lib\Session;
use Framework\models\Invoice_linesModel;
use Framework\models\Invoices_ordersModel;
use Framework\models\Invoices_paymentsModel;
use Framework\models\InvoicesModel;
use Framework\models\pos\Inventory_counts_itemsModel;
use Framework\models\pos\Inventory_counts_tmp_print_itemsModel;
use Framework\models\pos\Inventory_countsModel;
use Framework\models\pos\Item_xero_accountsModel;
use Framework\models\pos\Items_auto_reorderModel;
use Framework\models\pos\Items_inventory_movementsModel;
use Framework\models\pos\Sales_shipmentsModel;
use Framework\models\pos\Vendor_return_itemsModel;
use Framework\models\pos\Vendor_returnModel;
use Framework\models\quotes\Quotes_itemsModel;
use Framework\models\quotes\Quotes_purchase_ordersModel;
use Framework\models\quotes\QuotesModel;
use Framework\models\UsersModel;
use Framework\models\CustomersModel;
use Framework\models\pos\BrandsModel;
use Framework\models\pos\CategoriesModel;
use Framework\models\pos\DiscountsModel;
use Framework\models\pos\Items_inventoryModel;
use Framework\models\pos\ItemsModel;
use Framework\models\pos\Payment_methodsModel;
use Framework\models\pos\Pricing_levelsModel;
use Framework\models\pos\Purchase_orders_itemsModel;
use Framework\models\pos\Purchase_ordersModel;
use Framework\models\pos\Register_countsModel;
use Framework\models\pos\Register_statusModel;
use Framework\models\pos\Sales_itemsModel;
use Framework\models\pos\Sales_paymentsModel;
use Framework\models\pos\SalesModel;
use Framework\models\pos\Tax_classesModel;
use Framework\models\pos\Vendor_return_reasonsModel;
use Framework\models\pos\VendorsModel;
use Framework\models\xero\Xero_accountsModel;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpWord\MailMergeTemplateProcessor;

class PosController extends AbstractController
{
    public function DefaultAction()
    {
        $this->RenderPos();
    }


/*Inventory*/
    public function InventoryAction()
    {
        $this->RenderPos();
    }

    /*Items*/
    public function ItemsAction()
    {
        $this->RenderPos([
            'data' => (new ItemsModel)->getItemsAvgPrice("WHERE items.is_misc != 1 GROUP BY items.id")->paginate(),
            'departments' => ItemsModel::getAllDepartments(),
            'categories' => CategoriesModel::getAll(),
            'brands' => BrandsModel::getAll(),
            'vendors' => VendorsModel::getAll(),
            'tax_classes' => Tax_classesModel::getAll()
        ]);
    }

    public function ItemAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        if ($id !== false) {
            /* Calculate the item's sales */
            $item_sales = SalesModel::getItemSales($id);

            $customers_items = $totals = $customers_totals = array();
            $original_subtotal = $discounts = $sub_total = $cost = 0;
            $customers_original_subtotal = $customers_discounts = $customers_subtotal = $customers_cost = 0;

            if ($item_sales) {
                foreach ($item_sales as $item_sale) {
                    if ($item_sale->item_total > 0) {
                        $original_subtotal += $item_sale->original_price * $item_sale->quantity;
                        $discounts += $item_sale->item_discount * $item_sale->quantity;
                        $sub_total += $item_sale->item_total;
                        $cost += $item_sale->buy_price * $item_sale->quantity;
                    }
                    if ($item_sale->customer_id) {
                        if (isset($customers_items[$item_sale->customer_id])) {
                            $customers_items[$item_sale->customer_id]['customer_id'] = $item_sale->customer_id;
                            $customers_items[$item_sale->customer_id]['name'] = $item_sale->customer_name;
                            $customers_items[$item_sale->customer_id]['qty'] += $item_sale->quantity;
                            $customers_items[$item_sale->customer_id]['original_subtotal'] += $item_sale->original_price * $item_sale->quantity;
                            $customers_items[$item_sale->customer_id]['discounts'] += $item_sale->item_discount * $item_sale->quantity;
                            $customers_items[$item_sale->customer_id]['subtotal'] += $item_sale->item_total;
                            $customers_items[$item_sale->customer_id]['cost'] += $item_sale->buy_price * $item_sale->quantity;
                        } else {
                            $customers_items[$item_sale->customer_id]['customer_id'] = $item_sale->customer_id;
                            $customers_items[$item_sale->customer_id]['name'] = $item_sale->customer_name;
                            $customers_items[$item_sale->customer_id]['qty'] = $item_sale->quantity;
                            $customers_items[$item_sale->customer_id]['original_subtotal'] = $item_sale->original_price * $item_sale->quantity;
                            $customers_items[$item_sale->customer_id]['discounts'] = $item_sale->item_discount * $item_sale->quantity;
                            $customers_items[$item_sale->customer_id]['subtotal'] = $item_sale->item_total;
                            $customers_items[$item_sale->customer_id]['cost'] = $item_sale->buy_price * $item_sale->quantity;
                        }

                        if ($item_sale->item_total > 0) {
                            $customers_original_subtotal += $item_sale->original_price * $item_sale->quantity;
                            $customers_discounts += $item_sale->item_discount * $item_sale->quantity;
                            $customers_subtotal += $item_sale->item_total;
                            $customers_cost += $item_sale->buy_price * $item_sale->quantity;
                        }
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

                if ($customers_subtotal && $customers_cost) {
                    $customers_totals = array(
                        'original_sub_total' => $customers_original_subtotal,
                        'discounts' => $customers_discounts,
                        'sub_total' => $customers_subtotal,
                        'cost' => $customers_cost,
                        'profit' => $customers_subtotal - $customers_cost,
                        'margin' => substr((($customers_subtotal - $customers_cost) / $customers_cost) * 100, 0, 5)
                    );
                }
            }

            $this->RenderPos([
                'item' => ItemsModel::getAllItemsDetails("WHERE items.id = '$id'", true),
                'item_inventory' => ItemsModel::getAwaitingDispatch($id),

                'item_avg_price' => Items_inventoryModel::getAVGItemPrice($id),
                'xero_accounts' => Xero_accountsModel::getAll(),
                'departments' => ItemsModel::getAllDepartments(),
                'categories' => CategoriesModel::getAll(),
                'brands' => BrandsModel::getAll(),
                'tags' => ItemsModel::getAllTags(),

                'vendors' => VendorsModel::getAll(),
                'tax_classes' => Tax_classesModel::getAll(),
                'pricing_levels' => Pricing_levelsModel::getAll(),

                'inventory' => Items_inventoryModel::getItemInventory($id),
                'inventory_movements' => Items_inventory_movementsModel::getAll("WHERE item_id = '$id'"),

                'sales' => $item_sales,
                'sales_totals' => $totals ? (Object)$totals : $totals,
                'customers_items' => $customers_items ? (Object)$customers_items : $customers_items,
                'customers_totals' => $customers_totals ? (Object)$customers_totals : $customers_totals,

                'purchase_orders' => Purchase_ordersModel::getPurchaseOrders_item("WHERE purchase_orders_items.item_id = '$id'"),
                'vendor_returns' => Vendor_returnModel::getVendorReturns("WHERE vendor_return_items.item_id = '$id'"),
            ]);
        }
    }

    public function Item_addAction()
    {
        if (Request::Check('submit')) {
            $item = new ItemsModel();
            $item->item = FilterInput::CleanString(Request::Post('item'));
            $item->description = FilterInput::String(Request::Post('description'));
            $item->item_type = FilterInput::String(Request::Post('item_type'));
            $item->serialized = Request::Check('serialized') ? 1 : 2;
            $item->shop_sku = FilterInput::CleanString(Request::Post('shop_sku'));
            $item->man_sku = FilterInput::CleanString(Request::Post('man_sku'));
            $item->department = FilterInput::String(Request::Post('department'));
            $item->department_code = FilterInput::String(Request::Post('department'));
            $item->category = FilterInput::Int(Request::Post('category'));
            $item->brand = FilterInput::Int(Request::Post('brand'));
            $item->tags = Request::Re_Check('tags') ? implode(',', $_POST['tags']) : '';

            $item->buy_price = FilterInput::Float(Request::Post('buy_price'));
            $item->rrp_percentage = FilterInput::Int(Request::Post('rrp_percentage'));
            $item->rrp_price = FilterInput::Float(Request::Post('rrp_price'));

            $item->discountable = Request::Check('discountable') ? 1 : 2;
            $item->tax_class = FilterInput::Int(Request::Post('tax_class'));
            $item->is_tracked_as_inventory = Request::Check('is_tracked_as_inventory') ? 1 : 2;

            // generate uid (first 3 char of the item name)
            $uid = substr($item->item, 0, 3);
            $uq_number = ItemsModel::generateUniqueNumber();
            $item->uid = strtoupper($uid) . $uq_number;

            // generate upc
            $upc_code = 425667 . sprintf("%05d", ItemsModel::NextID());
            $item->upc = $upc_code . Helper::CalculateUpcCheckDigit($upc_code);

            if ($item->Save()) {
                $this->logger->info("New Item was created successfully.", Helper::AppendLoggedin(['Item UID' => $item->uid]));
                Helper::SetFeedback('success', "Item was created successfully.");

                $item_id = ItemsModel::getColumns(['id'], "id = '$item->id'", true);

                // update item keywords
                $this->UpdateItemKeywords($item_id);


                // create xero accounts record
                $item_xero_accounts = new Item_xero_accountsModel();
                $item_xero_accounts->item_id = $item_id;
                $item_xero_accounts->item_uid = $item->uid;
                $xero_ia_account = Request::Re_Check('xero_ia_account') ? FilterInput::String(Request::Post('xero_ia_account')) : false;
                $xero_p_account = Request::Re_Check('xero_p_account') ? FilterInput::String(Request::Post('xero_p_account')) : false;
                $xero_s_account = Request::Re_Check('xero_s_account') ? FilterInput::String(Request::Post('xero_s_account')) : false;

                if ($xero_ia_account !== false && $xero_ia_account !== '0') {
                    $xero_ia_account_ex = explode('|||', $xero_ia_account);
                    $item_xero_accounts->inventory_asset_xero_account_id = $xero_ia_account_ex[0];
                    $item_xero_accounts->inventory_asset_xero_account_code = $xero_ia_account_ex[1];
                }
                if ($xero_p_account !== false && $xero_p_account !== '0') {
                    $xero_p_account_ex = explode('|||', $xero_p_account);
                    $item_xero_accounts->purchase_xero_account_id = $xero_p_account_ex[0];
                    $item_xero_accounts->purchase_xero_account_code = $xero_p_account_ex[1];
                }
                if ($xero_s_account !== false && $xero_s_account !== '0') {
                    $xero_s_account_ex = explode('|||', $xero_s_account);
                    $item_xero_accounts->sales_xero_account_id = $xero_s_account_ex[0];
                    $item_xero_accounts->sales_xero_account_code = $xero_s_account_ex[1];
                }
                if (!$item_xero_accounts->Save()) {
                    $this->logger->error("Failed to create item's xero accounts link.", Helper::AppendLoggedin(['Item UID' => $item->uid]));
                }



                // create item's auto re-order details
                $item_auto_reorder = new Items_auto_reorderModel();
                $item_auto_reorder->item_id = $item_id;
                $item_auto_reorder->item_uid = $item->uid;
                if (Request::Check('auto_reorder')) {
                    $item_auto_reorder->auto_reorder = 1;
                    $item_auto_reorder->reorder_point = FilterInput::Int(Request::Post('reorder_point'));
                    $item_auto_reorder->reorder_level = FilterInput::Int(Request::Post('reorder_level'));
                }



                // create item's inventory record
                if ($item->is_tracked_as_inventory == 1) {
                    if (Request::Re_Check('quantity')) {
                        $inventory = new Items_inventoryModel();
                        $inventory->item_id = $item_id;
                        $inventory->item_uid = $item->uid;
                        $inventory->vendor_id = FilterInput::Int(Request::Post('vendor'));
                        $inventory->buy_price = $item->buy_price;
                        $inventory->rrp_price = $item->rrp_price;
                        $inventory->quantity = FilterInput::Int(Request::Post('quantity')) ? FilterInput::Int(Request::Post('quantity')) : 0;
                        $inventory->qoh = $inventory->quantity;
                        if ($inventory->Save()) {
                            $inventory_movement = new Items_inventory_movementsModel();
                            $inventory_movement->item_id = $item_id;
                            $inventory_movement->inventory_header_id = $inventory->id;
                            $inventory_movement->type = 'new item';
                            $inventory_movement->quantity = $inventory->quantity;
                            $inventory_movement->source = "pos/item/".$item_id;
                            $inventory_movement->Save();
                        } else {
                            $this->logger->info("Item saved. but failed to create item's inventory!", Helper::AppendLoggedin(['Item UID' => $item->uid]));
                            Helper::SetFeedback('error', "Item saved, but failed to create item's inventory.");
                        }
                    }
                }


                Redirect::To('pos/items');
            } else {
                $this->logger->error("Failed to create new item. Item insert error!", Helper::AppendLoggedin(['Item UID' => $item->uid]));
                Helper::SetFeedback('error', "Failed to create item. Unknown error!");
            }
        }

        $this->RenderPos([
            'xero_accounts' => Xero_accountsModel::getAll(),
            'departments' => ItemsModel::getAllDepartments(),
            'categories' => CategoriesModel::getAll(),
            'brands' => BrandsModel::getAll(),
            'tags' => ItemsModel::getAllTags(),

            'vendors' => VendorsModel::getAll(),
            'tax_classes' => Tax_classesModel::getAll(),
            'pricing_levels' => Pricing_levelsModel::getAll()
        ]);
    }

    public function Item_updateAction($id)
    {
        if (!empty($_POST)) {
            $item = new ItemsModel();
            $item->id = $id;
            $item->item = FilterInput::CleanString(Request::Post('item'));
            $item->description = FilterInput::String(Request::Post('description'));
            $item->item_type = FilterInput::String(Request::Post('item_type'));
            $item->serialized = Request::Check('serialized') ? 1 : 2;

            $item->upc = FilterInput::Int(Request::Post('upc'));
            $item->shop_sku = FilterInput::CleanString(Request::Post('shop_sku'));
            $item->man_sku = FilterInput::CleanString(Request::Post('man_sku'));
            $item->department = FilterInput::String(Request::Post('department'));
            $item->department_code = FilterInput::String(Request::Post('department'));
            $item->category = FilterInput::Int(Request::Post('category'));
            $item->brand = FilterInput::Int(Request::Post('brand'));
            $item->tags = Request::Re_Check('tags') ? implode(',', $_POST['tags']) : '';

            $item->buy_price = FilterInput::Float(Request::Post('buy_price'));
            $item->rrp_percentage = FilterInput::Int(Request::Post('rrp_percentage'));
            $item->rrp_price = FilterInput::Float(Request::Post('rrp_price'));

            $item->discountable = Request::Check('discountable') ? 1 : 2;
            $item->tax_class = FilterInput::Int(Request::Post('tax_class'));
            $item->is_tracked_as_inventory = Request::Check('is_tracked_as_inventory') ? 1 : 2;

            if ($item->Save()) {
                $this->logger->info("Item was updated successfully.", Helper::AppendLoggedin(['Item UID' => $item->uid]));
                Helper::SetFeedback('success', "Item was updated successfully.");

                $item_id = ItemsModel::getColumns(['id'], "id = '$item->id'", true);

                // update item keywords
                $this->UpdateItemKeywords($item_id);


                // create xero accounts record
                $item_xero_accounts = new Item_xero_accountsModel();
                $item_xero_accounts->id = FilterInput::Int(Request::Post('xero_accounts_id'));
                $item_xero_accounts->item_id = $item_id;
                $item_xero_accounts->item_uid = $item->uid;
                $xero_ia_account = Request::Re_Check('xero_ia_account') ? FilterInput::String(Request::Post('xero_ia_account')) : false;
                $xero_p_account = Request::Re_Check('xero_p_account') ? FilterInput::String(Request::Post('xero_p_account')) : false;
                $xero_s_account = Request::Re_Check('xero_s_account') ? FilterInput::String(Request::Post('xero_s_account')) : false;

                if ($xero_ia_account !== false && $xero_ia_account !== '0') {
                    $xero_ia_account_ex = explode('|||', $xero_ia_account);
                    $item_xero_accounts->inventory_asset_xero_account_id = $xero_ia_account_ex[0];
                    $item_xero_accounts->inventory_asset_xero_account_code = $xero_ia_account_ex[1];
                }
                if ($xero_p_account !== false && $xero_p_account !== '0') {
                    $xero_p_account_ex = explode('|||', $xero_p_account);
                    $item_xero_accounts->purchase_xero_account_id = $xero_p_account_ex[0];
                    $item_xero_accounts->purchase_xero_account_code = $xero_p_account_ex[1];
                }
                if ($xero_s_account !== false && $xero_s_account !== '0') {
                    $xero_s_account_ex = explode('|||', $xero_s_account);
                    $item_xero_accounts->sales_xero_account_id = $xero_s_account_ex[0];
                    $item_xero_accounts->sales_xero_account_code = $xero_s_account_ex[1];
                }
                if (!$item_xero_accounts->Save()) {
                    $this->logger->error("Failed to update item's xero accounts link.", Helper::AppendLoggedin(['Item UID' => $item->uid]));
                }



                // create item's auto re-order details
                $item_auto_reorder = new Items_auto_reorderModel();
                $item_auto_reorder->id = FilterInput::Int(Request::Post('auto_reorder_id'));
                $item_auto_reorder->item_id = $item_id;
                $item_auto_reorder->item_uid = $item->uid;
                if (Request::Check('auto_reorder')) {
                    $item_auto_reorder->auto_reorder = 1;
                    $item_auto_reorder->reorder_point = FilterInput::Int(Request::Post('reorder_point'));
                    $item_auto_reorder->reorder_level = FilterInput::Int(Request::Post('reorder_level'));
                }
            } else {
                $this->logger->error("Failed to update item. database error!", Helper::AppendLoggedin(['Item UID' => $item->uid]));
                Helper::SetFeedback('error', "Failed to update item. Unknown database error!");
            }

            Redirect::To('pos/item/'.$item_id);
        }
    }

    public function Items_importAction()
    {
        if (isset($_FILES["file"])) {
            $file = $_FILES['file'];

            $tmp_name = $file['tmp_name'];
            $file_name = pathinfo($file['name']);
            $extension = strtolower($file_name["extension"]);
            $new_file_name = $file_name["filename"] . '-' . rand(99999999, 9) . '.' . $extension;

            if ($extension != "csv" && $extension != "xls" && $extension != "xlsx") {
                unlink($tmp_name);
                $this->logger->error("Failed to upload items export. file is not csv nor xls nor xlsx!", Helper::AppendLoggedin([]));
                Helper::SetFeedback('error', "Error: Unable to upload ".$extension." file, must be csv or xls or xlsx");
            } else {
                if (move_uploaded_file($tmp_name, PUBLIC_PATH.'items_imports'.DS.$new_file_name)) {
                    $exported_file = PUBLIC_PATH.'items_imports'.DS.$new_file_name;

                    $inputFileType = IOFactory::identify($exported_file);
                    $reader = IOFactory::createReader($inputFileType);
                    $reader->setReadDataOnly(true);
                    $spreadsheet = $reader->load($exported_file);
                    $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

                    $varArray = array();
                    for ($i = 1; $i < sizeof($sheetData) + 1; $i++) {
                        $array = array_values($sheetData[$i]);
                        if ($array) {
//                            if ($i == 10) {break;}

                            if ($i == 1) {
                                foreach ($array as $array_row) {
                                    if (!empty($array_row)) {
                                        $varArray[$array_row] = Helper::getArrayKey($array, $array_row);
                                    }
                                }
                            }


                            if ($i > 1) {
                                if (!empty($varArray)) {
                                    $item = new ItemsModel();
                                    $item->source = 'csv_import';
                                    $item->item_number = isset($array[$varArray['Item Number']]) ? FilterInput::Int($array[$varArray['Item Number']]) : '';

                                    if ($item->item_number) {
                                        $check_item = ItemsModel::getColumns(['id'], "item_number = '$item->item_number'", true);
                                        if ($check_item) {
                                            $item->id = $check_item;
                                        }
                                    }

                                    $item->item = FilterInput::String($array[$varArray['Item Name']]) ?: '';
                                    $item->description = FilterInput::String($array[$varArray['Item Description']]);
                                    $item->department = FilterInput::String($array[$varArray['Department Name']]);
                                    $item->department_code = FilterInput::String($array[$varArray['Department Code']]);
                                    $item->discountable = isset($array[$varArray['Eligible for Commission']]) && FilterInput::String($array[$varArray['Eligible for Commission']]) == "Yes" ? 1 : 2;

                                    $item->buy_price = isset($array[$varArray['Average Unit Cost']]) && $array[$varArray['Average Unit Cost']] ?
                                        FilterInput::Float($array[$varArray['Average Unit Cost']]) :
                                        0;
                                    $item->rrp_price = isset($array[$varArray['Regular Price']]) && $array[$varArray['Regular Price']] ?
                                        FilterInput::Float($array[$varArray['Regular Price']]) :
                                        0;
                                    if ($item->buy_price > 0 && $item->rrp_price > 0) {
                                        $item->rrp_percentage = substr(((($item->rrp_price - $item->buy_price) / $item->buy_price) * 100), 0, 3);
                                    }

                                    $tax_code = isset($array[$varArray['Tax Code']]) && FilterInput::String($array[$varArray['Tax Code']]) ? FilterInput::String($array[$varArray['Tax Code']]) : false;
                                    if ($tax_code) {
                                        $tax_class_id = Tax_classesModel::getColumns(['id'], "class LIKE '%$tax_code%'", true);
                                        if ($tax_class_id) {
                                            $item->tax_class = $tax_class_id;
                                        } else {
                                            $tax_class = new Tax_classesModel();
                                            $tax_class->class = $tax_code;
                                            $tax_class->rate = 0;
                                            if ($tax_class->Save()) {
                                                $item->tax_class = $tax_class->id;
                                            }
                                        }
                                    } else {
                                        $gst_tax = Tax_classesModel::getColumns(['id'], "class LIKE '%GST%'", true);
                                        if ($gst_tax) {
                                            $item->tax_class = $gst_tax;
                                        }
                                    }


                                    // generate uid (first 3 char of the item name)
                                    $uid = substr($item->item, 0, 3);
                                    $uq_number = ItemsModel::generateUniqueNumber();
                                    $item->uid = strtoupper($uid) . $uq_number;

                                    // generate upc
                                    $upc_code = 425667 . sprintf("%05d", ItemsModel::NextID());
                                    $item->upc = $upc_code . Helper::CalculateUpcCheckDigit($upc_code);

                                    if ($item->Save()) {
                                        $item_id = ItemsModel::getColumns(['id'], "id = '$item->id'", true);

                                        // update item keywords
                                        $this->UpdateItemKeywords($item_id);

                                        // create xero accounts record
                                        $item_xero_accounts = new Item_xero_accountsModel();
                                        $item_xero_accounts->item_id = $item_id;
                                        $item_xero_accounts->item_uid = $item->uid;

                                        if ($item->department == 'Service' || $item->department == 'Services' || $item->department == 'Labour') {
                                            $xero_account_code = "200";
                                        } elseif ($item->department == 'Phone Parts' || $item->department == 'Mobile & Tablet Parts') {
                                            $xero_account_code = "209";
                                        } elseif ($item->department == 'EFT') {
                                            $xero_account_code = "220";
                                        } else {
                                            $xero_account_code = "210";
                                        }
                                        $check_xero_account = Xero_accountsModel::getColumns(['id'], "Code = '$xero_account_code'", true);
                                        if ($check_xero_account) {
                                            $item_xero_accounts->inventory_asset_xero_account_id = $check_xero_account;
                                            $item_xero_accounts->inventory_asset_xero_account_code = $xero_account_code;
                                            $item_xero_accounts->purchase_xero_account_id = $check_xero_account;
                                            $item_xero_accounts->purchase_xero_account_code = $xero_account_code;
                                            $item_xero_accounts->sales_xero_account_id = $check_xero_account;
                                            $item_xero_accounts->sales_xero_account_code = $xero_account_code;
                                        }

                                        $item_xero_accounts->Save();


                                        // create item's inventory record
                                        if (isset($array[$varArray['Qty 1']]) && $array[$varArray['Qty 1']]) {
                                            $inventory = new Items_inventoryModel();
                                            $inventory->item_id = $item_id;
                                            $inventory->item_uid = $item->uid;
                                            $inventory->buy_price = $item->buy_price;
                                            $inventory->rrp_price = $item->rrp_price;
                                            $inventory->quantity = FilterInput::Int($array[$varArray['Qty 1']]);
                                            $inventory->qoh = $inventory->quantity;
                                            if ($inventory->Save()) {
                                                $inventory_movement = new Items_inventory_movementsModel();
                                                $inventory_movement->item_id = $item_id;
                                                $inventory_movement->inventory_header_id = $inventory->id;
                                                $inventory_movement->type = 'imported item';
                                                $inventory_movement->quantity = $inventory->quantity;
                                                $inventory_movement->source = "pos/item/" . $item_id;
                                                $inventory_movement->Save();
                                            } else {
                                                $this->logger->info("Failed to create inventory for imported item. Item inventory error!", Helper::AppendLoggedin(['Item UID' => $item->uid, 'Item' => $item->item]));
                                            }
                                        }
                                    } else {
                                        $this->logger->error("Failed to import item. Item insert error!", Helper::AppendLoggedin(['Item UID' => $item->uid, 'Item' => $item->item]));
                                    }

                                }
                            }
                        }
                    }

                    Helper::SetFeedback('success', "File was possessed successfully,check the log to make sure all items were imported successfully.");
                    Redirect::To('pos/items');
                } else {
                    $this->logger->error("Failed to upload items export. couldn't upload the file to server!", Helper::AppendLoggedin([]));
                    Helper::SetFeedback('error', "Error: Unable to upload ".$file_name["filename"].'.'.$extension."couldn't upload the file to server!");
                }
            }
        }

        $this->RenderPos();
    }

    /*Inventory*/
    public function Inventory_addAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        if ($id !== false) {
            $item_id = ItemsModel::getColumns(['id', 'uid'], "id = '$id'", true);
            if ($item_id) {
                if (Request::Check('submit')) {
                    $item = new ItemsModel();
                    $item->id = $id;
                    $item->buy_price = FilterInput::Float(Request::Post('add_inventory1_cost'));
                    $item->rrp_percentage = 30;
                    $rrp = (30 / 100) * $item->buy_price;
                    $item->rrp_price = $rrp + $item->buy_price;
                    if ($item->Save()) {
                        $item_inventory = new Items_inventoryModel();
                        $item_inventory->item_id = $item_id['id'];
                        $item_inventory->item_uid = $item_id['uid'];
                        $item_inventory->vendor_id = FilterInput::Int(Request::Post('add_inventory1_vendor'));
                        $item_inventory->buy_price = $item->buy_price;
                        $item_inventory->rrp_price = $item->rrp_price;
                        $item_inventory->quantity = FilterInput::Int(Request::Post('add_inventory1_quantity'));
                        $item_inventory->qoh = FilterInput::Int(Request::Post('add_inventory1_quantity'));
                        if ($item_inventory->Save()) {
                            $inventory_movement = new Items_inventory_movementsModel();
                            $inventory_movement->item_id = $item_id['id'];
                            $inventory_movement->inventory_header_id = $item_inventory->id;
                            $inventory_movement->type = 'manually added';
                            $inventory_movement->quantity = $item_inventory->quantity;
                            $inventory_movement->source = "pos/item/$id";
                            $inventory_movement->Save();

                            $this->logger->info("Item inventory was created successfully.", Helper::AppendLoggedin(['Item UID' => $item_id['uid']]));
                            Helper::SetFeedback('success', "Inventory was created successfully.");
                        } else {
                            $this->logger->info("Failed to add inventory. Item inventory error!", Helper::AppendLoggedin(['Item UID' => $item_id['uid']]));
                            Helper::SetFeedback('error', "Failed to add inventory. Something wrong with quantity!");
                        }
                    } else {
                        $this->logger->info("Failed to add inventory. Item pricing update error!", Helper::AppendLoggedin(['Item UID' => $item_id['uid']]));
                        Helper::SetFeedback('error', "Failed to add inventory. Something wrong with pricing!");
                    }

                    Redirect::To('pos/item/' . $id);
                }
            }
        }
    }


    /*Purchase Orders*/
    public function Purchase_ordersAction()
    {
        $this->RenderPos([
            'data' => Purchase_ordersModel::getPurchaseOrders()
        ]);
    }

    public function Purchase_order_addAction()
    {
        if (!empty($_POST)) {
            $order = new Purchase_ordersModel();
            $order->vendor_id = FilterInput::Int(Request::Post('vendor'));
            $order->reference = FilterInput::String(Request::Post('ref_num'));
            $order->ordered = FilterInput::String(Request::Post('ordered'));
            $order->expected = FilterInput::String(Request::Post('arrival_date'));
            $order->shipping_notes = FilterInput::String(Request::Post('ship_instructions'));
            $order->general_notes = FilterInput::String(Request::Post('general_notes'));
            $order->created_by = Session::Get('loggedin')->id;

            if (isset($_POST['items']) && !empty($_POST['items'])) {
                $items_subtotal = 0;
                foreach ($_POST['items'] as $item_post) {
                    $items_subtotal += floatval($item_post['buy_price']) * intval($item_post['quantity']);
                }

                $order->order_subtotal = $items_subtotal;
                $order->order_total = $items_subtotal;

                if (Request::Check('save')) {
                    $order->status = 'ordered';
                } elseif (Request::Check('check-in')) {
                    $order->status = 'checkin';
                } elseif (Request::Check('archive')) {
                    $order->status = 'archived';
                }

                if ($order->Save()) {
                    foreach ($_POST['items'] as $item_post) {
                        $order_item = new Purchase_orders_itemsModel();
                        $order_item->order_id = $order->id;
                        $order_item->item_id = FilterInput::String($item_post['id']);
                        $order_item->quantity = $item_post['quantity'] ? FilterInput::Int($item_post['quantity']) : 1;
                        $order_item->buy_price = FilterInput::Float($item_post['buy_price']);
                        $order_item->price = FilterInput::Float($item_post['price']);
                        $order_item->percentage = $order_item->buy_price && $order_item->price ? ($order_item->price - $order_item->buy_price) / $order_item->buy_price * 100 : 0;
                        $order_item->total = $order_item->buy_price && $order_item->quantity ? $order_item->buy_price * $order_item->quantity : 0;

                        if (!$order_item->Save()) {
                            $this->logger->error("Purchase order was created, but some items weren't saved!", Helper::AppendLoggedin(['Order ID' => $order->id, 'Item ID' => $order_item->item_id]));
                        }
                    }

                    $this->logger->info("Purchase order was saved successfully.", Helper::AppendLoggedin(['Order ID' => $order->id]));
                    Helper::SetFeedback('success', "Purchase order was created successfully.");
                    Redirect::To('pos/purchase_order/' . $order->id);
                } else {
                    $this->logger->error("Failed to create new purchase order. General saving error!", Helper::AppendLoggedin([]));
                    Helper::SetFeedback('error', "Failed to save purchase order. Something went wrong!");
                }
            } else {
                $this->logger->error("Failed to create new purchase order. No items selected!", Helper::AppendLoggedin([]));
                Helper::SetFeedback('error', "Failed to save purchase order. No items were selected!");
            }
        }

        $this->RenderPos([
            'vendors' => VendorsModel::getAll()
        ]);
    }

    public function Purchase_orderAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        if ($id !== false) {
            if (!empty($_POST)) {
                $total = 0;

                if (isset($_POST['items']) && !empty($_POST['items'])) {
                    foreach ($_POST['items'] as $item_post) {
                        $order_item = new Purchase_orders_itemsModel();
                        $order_item->order_id = $id;
                        $order_item->item_id = FilterInput::String($item_post['id']);
                        $order_item->quantity = $item_post['quantity'] ? FilterInput::Int($item_post['quantity']) : 1;
                        $order_item->buy_price = FilterInput::Float($item_post['buy_price']);
                        $order_item->price = FilterInput::Float($item_post['price']);
                        $order_item->percentage = $order_item->buy_price && $order_item->price ? ($order_item->price - $order_item->buy_price) / $order_item->buy_price * 100 : 0;
                        $order_item->total = $order_item->buy_price && $order_item->quantity ? $order_item->buy_price * $order_item->quantity : 0;
                        if (!$order_item->Save()) {
                            $this->logger->error("Purchase order was updated, but some new added items weren't saved!", Helper::AppendLoggedin(['Order ID' => $id, 'Item ID' => $order_item->item_id]));
                        } else {
                            $total += $order_item->total;
                        }
                    }
                }

                if (isset($_POST['po-items']) && !empty($_POST['po-items'])) {
                    foreach ($_POST['po-items'] as $item_post) {
                        $order_item = new Purchase_orders_itemsModel();
                        $order_item->id = FilterInput::String($item_post['id']);
                        $order_item->quantity = $item_post['quantity'] ? FilterInput::Int($item_post['quantity']) : '';
                        $order_item->buy_price = FilterInput::Float($item_post['buy_price']);
                        $order_item->price = FilterInput::Float($item_post['price']);
                        $order_item->percentage = $order_item->buy_price && $order_item->price ? ($order_item->price - $order_item->buy_price) / $order_item->buy_price * 100 : 0;
                        $order_item->total = $order_item->buy_price && $order_item->quantity ? $order_item->buy_price * $order_item->quantity : 0;

                        $order_item->quantity_received = FilterInput::Int($item_post['quantity_received']);
                        $order_item->status = $order_item->quantity_received >= $order_item->quantity ? 'received' : 'ordered';

                        if (!$order_item->Save()) {
                            $this->logger->error("Purchase order was updated, but some existing items weren't updated!", Helper::AppendLoggedin(['Order ID' => $id, 'Order Item ID' => $order_item->id]));
                        } else {
                            $total += $order_item->total;
                        }
                    }
                }

                $total_completed = Purchase_orders_itemsModel::getCompletedItemsTotal($id);
                if ($total_completed > 0) {
                    $total += floatval($total_completed);
                }

                $order = new Purchase_ordersModel();
                $order->id = $id;
                $order->status = FilterInput::String(Request::Post('status'));
                $order->vendor_id = FilterInput::Int(Request::Post('vendor'));
                $order->reference = FilterInput::String(Request::Post('ref_num'));
                $order->ordered = FilterInput::String(Request::Post('ordered'));
                $order->expected = FilterInput::String(Request::Post('arrival_date'));
                $order->shipping_notes = FilterInput::String(Request::Post('ship_instructions'));
                $order->general_notes = FilterInput::String(Request::Post('general_notes'));

                $order->order_subtotal = $total;
                $order->shipping = Request::Post('ship_cost');
                $order->other = Request::Post('other_cost');

                $total = $order->shipping ? floatval($total) + floatval($order->shipping) : $total;
                $total = $order->other ? floatval($total) + floatval($order->other) : $total;

                $order->discount = intval(Request::Post('discount'));
                $order->discount_amount = $order->discount ? (floatval($total) * intval($order->discount) / 100) : 0;

                $total = $order->discount_amount ? $total - $order->discount_amount : $total;

                $order->order_total = $total;

                if ($order->Save()) {
                    $this->logger->info("Purchase order was updated successfully.", Helper::AppendLoggedin(['Order ID' => $order->id]));
                    Helper::SetFeedback('success', "Purchase order was updated successfully.");
                    Redirect::To('pos/purchase_order/'.$id);
                } else {
                    $this->logger->error("Failed to update purchase order. General saving error!", Helper::AppendLoggedin(['Order ID' => $order->id]));
                    Helper::SetFeedback('error', "Failed to save purchase order. Something went wrong!");
                }
            }

            $order_items_status = Purchase_orders_itemsModel::getColumns(['status'], "order_id = '$id'");
            if ($order_items_status) {
                $order_items_completed = true;
                foreach ($order_items_status as $order_item_status) {
                    if ($order_item_status !== 'completed') {
                        $order_items_completed = false;
                    }
                }
            }

            $this->RenderPos([
                'order' => Purchase_ordersModel::getPurchaseOrder($id),
                'order_items' => Purchase_orders_itemsModel::getPurchaseOrderItems("WHERE order_id = '$id'"),
                'order_items_completed' => isset($order_items_completed) ? $order_items_completed : false,
                'vendors' => VendorsModel::getAll()
            ]);
        }
    }

    public function Purchase_order_check_inAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        if ($id !== false) {
            $order = new Purchase_ordersModel();
            $order->id = $id;
            $order->status = 'checkin';
            $order->Save();

            Redirect::To('pos/purchase_order/'.$id);
        }
    }

    public function Purchase_order_finishAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        if ($id !== false) {
            $order = new Purchase_ordersModel();
            $order->id = $id;
            $order->status = 'finished';
            $order->Save();
            Redirect::To('pos/purchase_order/'.$id);
        }
    }

    public function Purchase_order_receive_itemsAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        if ($id !== false) {
            $order_items = Purchase_orders_itemsModel::getAll("WHERE order_id = '$id' && status != 'completed'");
            if ($order_items) {
                foreach ($order_items as $order_item) {
                    $item = new Purchase_orders_itemsModel();
                    $item->id = $order_item->id;
                    $item->quantity_received = $order_item->quantity;
                    $item->status = 'received';
                    $item->Save();
                }
            }

            Redirect::To('pos/purchase_order/'.$id);
        }
    }

    public function Purchase_order_received_to_inventoryAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        if ($id !== false) {
            $order_items = Purchase_orders_itemsModel::getOrderItems_itemDetails("WHERE purchase_orders_items.order_id = '$id' && purchase_orders_items.status = 'received'");
            $return = true;
            if ($order_items) {
                foreach ($order_items as $order_item) {
                    $item = new ItemsModel();
                    $item->id = $order_item->item_id;
                    $item->buy_price = $order_item->buy_price;
                    $item->rrp_percentage = $order_item->percentage;
                    $item->rrp_price = $order_item->price;
                    if ($item->Save()) {
                        $inventory = new Items_inventoryModel();
                        $inventory->item_id = $order_item->item_id;
                        $inventory->item_uid = $order_item->uid;
                        $inventory->vendor_id = $order_item->vendor_id;
                        $inventory->buy_price = $item->buy_price;
                        $inventory->rrp_price = $item->rrp_price;
                        $inventory->quantity = $order_item->quantity;
                        $inventory->qoh = $order_item->quantity;
                        if ($inventory->Save()) {
                            $inventory_movement = new Items_inventory_movementsModel();
                            $inventory_movement->item_id = $order_item->item_id;
                            $inventory_movement->inventory_header_id = $inventory->id;
                            $inventory_movement->type = 'purchase order';
                            $inventory_movement->quantity = $inventory->quantity;
                            $inventory_movement->source = "pos/purchase_order/$id";
                            $inventory_movement->Save();

                            $po_item = new Purchase_orders_itemsModel();
                            $po_item->id = $order_item->id;
                            $po_item->status = 'completed';
                            if ($po_item->Save()) {
                                $this->logger->info("Purchase order's received item was moved to inventory successfully.", Helper::AppendLoggedin(['Order ID' => $order_item->order_id, 'Item UID' => $order_item->uid]));
                            } else {
                                $this->logger->error("Purchase order's received item was moved to inventory, but failed to mark item as complete!", Helper::AppendLoggedin(['Order ID' => $order_item->order_id, 'Item UID' => $order_item->uid]));
                            }
                        } else {
                            $this->logger->error("Failed to move purchase order's received item to inventory. Item inventory error!", Helper::AppendLoggedin(['Order ID' => $order_item->order_id, 'Item UID' => $order_item->uid]));
                            $return = false;
                        }
                    } else {
                        $this->logger->error("Failed to move purchase order's received item to inventory. Item pricing update error!", Helper::AppendLoggedin(['Order ID' => $order_item->order_id, 'Item UID' => $order_item->uid]));
                        $return = false;
                    }
                }
            }

            if ($return !== false) {
                Helper::SetFeedback('success', "Received items were moved to inventory successfully.");
            } else {
                Helper::SetFeedback('error', "Something wrong happened during moving items to inventory. Check the logs!");
            }

            Redirect::To('pos/purchase_order/'.$id);
        }
    }

    /* Vendor Return */
    public function Vendor_returnsAction()
    {
        $this->RenderPos([
            'vendors' => VendorsModel::getAll(),
            'data' => Vendor_returnModel::getVendorReturns()
        ]);
    }

    public function Vendor_return_addAction()
    {
        if (!empty($_POST) && !Request::Check('submit-vendor')) {
            $return = new Vendor_returnModel();
            $return->vendor_id = FilterInput::Int(Request::Post('vendor_id'));
            $return->reference = FilterInput::String(Request::Post('refNum'));
            $return->notes = FilterInput::String(Request::Post('notes'));

            $return->shipping = FilterInput::Float(Request::Post('shipCost'));
            $return->other = FilterInput::Float(Request::Post('otherCost'));
            $return->created_by = Session::Get('loggedin')->id;

            $items_subtotal = 0;
            if (isset($_POST['items']) && !empty($_POST['items'])) {
                foreach ($_POST['items'] as $item_post) {
                    $items_subtotal += floatval($item_post['cost']) * intval($item_post['quantity']);
                }
            }
            $return->return_value = $items_subtotal;
            $return->total = $return->shipping + $return->other + $items_subtotal;

            if (Request::Check('save')) {
                $return->status = 'open';
            } elseif (Request::Check('send')) {
                $return->status = 'sent';
                $return->sending_date = date('Y-m-d H:i:s');
            } elseif (Request::Check('archive')) {
                $return->status = 'archived';
            }

            if ($return->Save()) {
                foreach ($_POST['items'] as $key => $item_post) {
                    $item_details = ItemsModel::getItemInventoryPricingTaxDetails(FilterInput::String($key));
                    if ($item_details) {
                        $return_item = new Vendor_return_itemsModel();
                        $return_item->vendor_return_id = $return->id;
                        $return_item->purchase_order_item_id = isset($item_post['purchase_order_item_id']) ? FilterInput::Int($item_post['purchase_order_item_id']) : '';
                        $return_item->return_reason_id = FilterInput::Int($item_post['return_reason']);
                        $return_item->item_id = FilterInput::String($key);
                        $return_item->inventory_id = $item_details->item_inventory_id;
                        $return_item->quantity = $item_post['quantity'] ? FilterInput::Int($item_post['quantity']) : 0;
                        $return_item->cost = FilterInput::Float($item_post['cost']);
                        $return_item->subtotal = $return_item->quantity && $return_item->cost ? $return_item->quantity * $return_item->cost : 0;

                        if (!$return_item->Save()) {
                            $this->logger->error("Vendor return was created, but some items weren't saved!", Helper::AppendLoggedin(['Order ID' => $return->id, 'Item ID' => $return_item->item_id]));
                        } else {
                            $inventory = new Items_inventoryModel();
                            $inventory->id = $item_details->item_inventory_id;
                            $inventory->qoh = $item_details->qoh - $return_item->quantity;
                            $inventory->Save();

                            $inventory_movement = new Items_inventory_movementsModel();
                            $inventory_movement->item_id = $return_item->item_id;
                            $inventory_movement->inventory_header_id = $item_details->item_inventory_id;
                            $inventory_movement->type = 'vendor return';
                            $inventory_movement->quantity = $return_item->quantity;
                            $inventory_movement->source = "pos/vendor_return/$return->id";
                            $inventory_movement->Save();
                        }
                    } else {
                        $this->logger->error("Vendor return was created, but some items weren't saved, no item details found!", Helper::AppendLoggedin(['Order ID' => $return->id, 'Item ID' => $return_item->item_id]));
                    }
                }

                $this->logger->info("Vendor return was saved successfully.", Helper::AppendLoggedin(['Order ID' => $return->id]));
                Helper::SetFeedback('success', "Vendor return was created successfully.");
                Redirect::To('pos/vendor_return/' . $return->id);
            }
        }


        $vendor_id = false;
        if (Request::Check('submit-vendor')) {
            if (Request::Check('vendor-id')) {
                $vendor_id = FilterInput::Int(Request::Post('vendor-id'));
            }
        }
        $this->RenderPos([
            'vendor_id' => $vendor_id,
            'vendor_name' => $vendor_id != false ? VendorsModel::getColumns(['name'], "id = '$vendor_id'", true) : false,
            'vendors' => VendorsModel::getAll()
        ]);
    }

    public function Vendor_returnAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        if ($id !== false) {
            if (!empty($_POST)) {
                $total = 0;

                if (isset($_POST['items']) && !empty($_POST['items'])) {
                    foreach ($_POST['items'] as $key => $item_post) {
                        $item_details = ItemsModel::getItemInventoryPricingTaxDetails(FilterInput::String($key));
                        if ($item_details) {
                            $return_item = new Vendor_return_itemsModel();
                            $return_item->vendor_return_id = $id;
                            $return_item->purchase_order_item_id = isset($item_post['purchase_order_item_id']) ? FilterInput::Int($item_post['purchase_order_item_id']) : 0;
                            $return_item->return_reason_id = isset($item_post['return_reason']) ? FilterInput::Int($item_post['return_reason']) : 0;
                            $return_item->item_id = FilterInput::String($key);
                            $return_item->inventory_id = $item_details->item_inventory_id;
                            $return_item->quantity = isset($item_post['quantity']) ? FilterInput::Int($item_post['quantity']) : 0;
                            $return_item->cost = isset($item_post['cost']) ? FilterInput::Float($item_post['cost']) : 0;
                            $return_item->subtotal = $return_item->quantity && $return_item->cost ? $return_item->quantity * $return_item->cost : 0;

                            if (!$return_item->Save()) {
                                $this->logger->error("Vendor return was updated, but some items weren't saved!", Helper::AppendLoggedin(['Order ID' => $id, 'Item ID' => $return_item->item_id]));
                            } else {
                                $total += $return_item->subtotal;

                                $inventory = new Items_inventoryModel();
                                $inventory->id = $item_details->item_inventory_id;
                                $inventory->qoh = $item_details->qoh - $return_item->quantity;
                                $inventory->Save();

                                $inventory_movement = new Items_inventory_movementsModel();
                                $inventory_movement->item_id = $return_item->item_id;
                                $inventory_movement->inventory_header_id = $item_details->item_inventory_id;
                                $inventory_movement->type = 'vendor return';
                                $inventory_movement->quantity = '-'.$return_item->quantity;
                                $inventory_movement->source = "pos/vendor_return/$id";
                                $inventory_movement->Save();
                            }
                        } else {
                            $this->logger->error("Vendor return was updated, but some items weren't saved, no item details found!", Helper::AppendLoggedin(['Order ID' => $id, 'Item ID' => $return_item->item_id]));
                        }
                    }
                }

                if (isset($_POST['vr-items']) && !empty($_POST['vr-items'])) {
                    foreach ($_POST['vr-items'] as $key => $item_post) {
                        $return_item = new Vendor_return_itemsModel();
                        $return_item->id = FilterInput::String($item_post['id']);
                        $return_item->purchase_order_item_id = isset($item_post['purchase_order_item_id']) ? FilterInput::Int($item_post['purchase_order_item_id']) : 0;
                        $return_item->return_reason_id = isset($item_post['return_reason']) ? FilterInput::Int($item_post['return_reason']) : 0;
                        $return_item->quantity = isset($item_post['quantity']) ? FilterInput::Int($item_post['quantity']) : 0;
                        $return_item->cost = isset($item_post['cost']) ? FilterInput::Float($item_post['cost']) : 0;
                        $return_item->subtotal = $return_item->quantity && $return_item->cost ? $return_item->quantity * $return_item->cost : 0;

                        if (!$return_item->Save()) {
                            $this->logger->error("Vendor return was updated, but some existing items weren't updated!", Helper::AppendLoggedin(['Order ID' => $id, 'Item ID' => $return_item->item_id]));
                        } else {
                            $total += $return_item->subtotal;

                            $original_quantity = $item_post['original-quantity'] ? FilterInput::Int($item_post['original-quantity']) : 0;
                            if ($original_quantity !== $return_item->quantity) {
                                $inventory_qoh = FilterInput::Int($item_post['inventory_qoh']) ? FilterInput::Int($item_post['inventory_qoh']) : 0;

                                $inventory = new Items_inventoryModel();
                                $inventory->id = FilterInput::Int($item_post['inventory_id']);
                                $inventory->qoh = ($inventory_qoh + $original_quantity) - $return_item->quantity;
                                $inventory->Save();

                                $return_item_inventory_movement = Items_inventory_movementsModel::getAll("WHERE item_id = '$key' && type = 'vendor return' && source = 'pos/vendor_return/$id'", true);
                                if ($return_item_inventory_movement) {
                                    $inventory_movement = new Items_inventory_movementsModel();
                                    $inventory_movement->id = $return_item_inventory_movement->id;
                                    $inventory_movement->quantity = '-'.$return_item->quantity;
                                    $inventory_movement->Save();
                                }
                            }
                        }
                    }
                }


                $return = new Vendor_returnModel();
                $return->id = $id;
                $return->vendor_id = FilterInput::Int(Request::Post('vendor_id'));
                $return->reference = FilterInput::String(Request::Post('refNum'));
                $return->notes = FilterInput::String(Request::Post('notes'));

                $return->shipping = FilterInput::Float(Request::Post('shipCost'));
                $return->other = FilterInput::Float(Request::Post('otherCost'));

                $return->return_value = $total;
                $return->total = $return->shipping + $return->other + $return->return_value;

                if (Request::Check('save') || Request::Check('open')) {
                    $return->status = 'open';
                } elseif (Request::Check('send')) {
                    $return->status = 'sent';
                    $return->sending_date = date('Y-m-d H:i:s');
                } elseif (Request::Check('closed')) {
                    $return->status = 'closed';
                    $return->closing_date = date('Y-m-d H:i:s');
                } elseif (Request::Check('archive')) {
                    $return->status = 'archived';
                }

                if ($return->Save()) {
                    $this->logger->info("Vendor return was updated successfully.", Helper::AppendLoggedin(['Order ID' => $return->id]));
                    Helper::SetFeedback('success', "Vendor return was updated successfully.");
                    Redirect::To('pos/vendor_return/' . $return->id);
                } else {
                    $this->logger->error("Failed to update vendor return. General saving error!", Helper::AppendLoggedin(['Order ID' => $return->id]));
                    Helper::SetFeedback('error', "Failed to update vendor return. Something went wrong!");
                }
            }

            $this->RenderPos([
                'order' => Vendor_returnModel::getVendorReturn($id),
                'order_items' => Vendor_return_itemsModel::getVendorReturnItems("WHERE vendor_return_items.vendor_return_id = '$id'"),
                'return_reasons' => Vendor_return_reasonsModel::getAll(),
                'vendors' => VendorsModel::getAll()
            ]);
        }
    }

    /* Shipments */
    public function ShipmentsAction()
    {
        $where = Request::Check('shipped', 'get') ? "WHERE shipped != '1' " : "WHERE (shipped = '1' || shipped = '0') ";
        $where .= Request::Check('search_name', 'get') ?
            "&& (users.firstName LIKE '%".Request::Get('search_name')."%' || 
                 users.lastName LIKE '%".Request::Get('search_name')."%' || 
                 customers.companyName LIKE '%".Request::Get('search_name')."%') " :
            '';
        $where .= Request::Check('search_address', 'get') ? "&& (customers.address LIKE '%".Request::Get('search_address')."%')" : '';

        $this->RenderPos([
            'data' => Sales_shipmentsModel::getShipmentsCustomerSales($where)
        ]);
    }

    public function ShipmentAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        if ($id !== false) {
            $order = Sales_shipmentsModel::getShipmentsCustomerSales("WHERE sales_shipments.id = '$id'", true);

            if (!empty($_POST)) {
                $user_id = FilterInput::Int(Request::Post('customer-id'));
                $customer_id = FilterInput::Int(Request::Post('customer-user-id'));

                if ($user_id && $customer_id) {
                    $user = new UsersModel();
                    $user->id = $user_id;
                    $user->firstName = FilterInput::String(Request::Post('f_name'));
                    $user->lastName = FilterInput::String(Request::Post('l_name'));
                    $user->phone = FilterInput::String(Request::Post('phone'));
                    $user->role = 'customer';
                    $user->Save();

                    $customer = new CustomersModel();
                    $customer->id = $customer_id;
                    $customer->address = Request::Post('address1');
                    $customer->address2 = Request::Post('address2');
                    $customer->city = Request::Post('city');
                    $customer->suburb = Request::Post('suburb');
                    $customer->zip = Request::Post('zip');
                    $customer->companyName = Request::Post('company');
                    $customer->Save();

                    if (Request::Post('ship_note')) {
                        $shipment = new Sales_shipmentsModel();
                        $shipment->id = $id;
                        $shipment->shipping_instructions = FilterInput::String(Request::Post('ship_note'));
                        if ($shipment->Save()) {
                            $this->logger->info("Sale Shipment was updated successfully.", Helper::AppendLoggedin(['Sale ID' => $order->id]));
                            Helper::SetFeedback('success', "Shipment was updated successfully.");
                            Redirect::To('pos/shipment/' . $id);
                        } else {
                            $this->logger->error("Failed to update sale shipment. General saving error!", Helper::AppendLoggedin(['Sale ID' => $order->id]));
                            Helper::SetFeedback('error', "Failed to update shipment. Something went wrong!");
                        }
                    }
                }
            }

            $this->RenderPos([
                'item' => $order,
                'sale_items' => Sales_itemsModel::getSaleItems($order->sale_id)
            ]);
        }
    }

    /* Inventory Counts */
    public function Inventory_countsAction()
    {
        $this->RenderPos([
            'data' => Inventory_countsModel::getInventoryCounts()
        ]);
    }

    public function Inventory_count_addAction()
    {
        if (!empty($_POST)) {
            $count = new Inventory_countsModel();
            $count->name = FilterInput::String(Request::Post('name'));
            $count->created_by = Session::Get('loggedin')->id;


            if (Request::Check('save')) {
                if (isset($_POST['items']) && !empty($_POST['items'])) {
                    $count->status = 'counting';
                }
            }

            if ($count->Save()) {
                if (isset($_POST['items']) && !empty($_POST['items'])) {
                    foreach ($_POST['items'] as $key => $item_post) {
                        $count_item = new Inventory_counts_itemsModel();
                        $count_item->inventory_count_id = $count->id;
                        $count_item->item_id = $key;
                        $count_item->should_have = FilterInput::Int($item_post['should_have']);
                        $count_item->counted = FilterInput::Int($item_post['quantity']);
                        $count_item->created_by = Session::Get('loggedin')->id;
                        if (!$count_item->Save()) {
                            $this->logger->error("Inventory count was created, but some items weren't saved!", Helper::AppendLoggedin(['Count Name' => $count->name, 'Item ID' => $key]));
                        }
                    }
                }

                $this->logger->info("Inventory count was saved successfully.", Helper::AppendLoggedin(['Count Name' => $count->name]));
                Helper::SetFeedback('success', "Inventory count was created successfully.");
                Redirect::To('pos/inventory_count/' . $count->id);
            } else {
                $this->logger->error("Failed to create new inventory count. General saving error!", Helper::AppendLoggedin([]));
                Helper::SetFeedback('error', "Failed to save inventory count. Something went wrong!");
            }
        }


        $this->RenderPos([]);
    }

    public function Inventory_countAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        if ($id !== false) {
            $inventory_count = Inventory_countsModel::getInventoryCount($id);
            $inventory_count_items = Inventory_counts_itemsModel::getInventoryCountItems($id);
            $inventory_count_missed_items = Inventory_countsModel::getInventoryCountMissedItems($id);

            if (!empty($_POST)) {
                $count = new Inventory_countsModel();
                $count->id = $id;
                $count->name = FilterInput::String(Request::Post('name'));
                $count->updated = date('Y-m-d H:i:s');

                if (Request::Check('save')) {
                    if (isset($_POST['invCount-items']) && !empty($_POST['invCount-items'])) {
                        $count->status = 'counting';
                    } else {
                        $count->status = 'empty';
                    }
                } elseif (Request::Check('finished')) {
                    $finish = true;
                    if ($inventory_count_missed_items !== false) {
                        Helper::SetFeedback('error', "Failed to finish count, you missed some items!");
                        $finish = false;
                    }
                    if ($inventory_count_items) {
                        foreach ($inventory_count_items as $inventory_count_item) {
                            if ($inventory_count_item->should_have !== $inventory_count_item->counted) {
                                Helper::SetFeedback('error', "Failed to finish count, some items are not reconciled!");
                                $finish = false;
                                break;
                            }
                        }
                    }

                    if ($finish) {
                        $count->status = 'finished';
                    }
                } elseif (Request::Check('archived')) {
                    $count->status = 'archived';
                }

                if ($count->Save()) {
                    if (isset($_POST['items']) && !empty($_POST['items'])) {
                        foreach ($_POST['items'] as $key => $item_post) {
                            $item_quantity = FilterInput::Int($item_post['quantity']);
                            if ($item_quantity && $item_quantity > 0) {
                                $count_item = new Inventory_counts_itemsModel();
                                $count_item->inventory_count_id = $count->id;
                                $count_item->item_id = $key;
                                $count_item->should_have = FilterInput::Int($item_post['should_have']);
                                $count_item->counted = FilterInput::Int($item_post['quantity']);
                                $count_item->created_by = Session::Get('loggedin')->id;
                                if (!$count_item->Save()) {
                                    $this->logger->error("Inventory count was updated, but some items weren't saved!", Helper::AppendLoggedin(['Count Name' => $inventory_count->name, 'Item ID' => $key]));
                                }
                            }
                        }
                    }

                    if (isset($_POST['invCount-items']) && !empty($_POST['invCount-items'])) {
                        foreach ($_POST['invCount-items'] as $key => $item_post) {
                            $count_item = new Inventory_counts_itemsModel();
                            $count_item->id = FilterInput::Int($item_post['id']);
                            $count_item->should_have = FilterInput::Int($item_post['should_have']);
                            $count_item->counted = FilterInput::Int($item_post['quantity']);
                            $count_item->updated = date('Y-m-d H:i:s');
                            if (!$count_item->Save()) {
                                $this->logger->error("Inventory count was updated, but some items weren't updated!", Helper::AppendLoggedin(['Count Name' => $inventory_count->name, 'Item ID' => $key]));
                            }
                        }
                    }

                    if (isset($_POST['missed-items']) && !empty($_POST['missed-items'])) {
                        foreach ($_POST['missed-items'] as $key => $item_post) {
                            $item_quantity = FilterInput::Int($item_post['quantity']);
                            if ($item_quantity && $item_quantity > 0) {
                                $count_item = new Inventory_counts_itemsModel();
                                $count_item->inventory_count_id = $count->id;
                                $count_item->item_id = $key;
                                $count_item->should_have = FilterInput::Int($item_post['should_have']);
                                $count_item->counted = FilterInput::Int($item_post['quantity']);
                                $count_item->created_by = Session::Get('loggedin')->id;
                                if (!$count_item->Save()) {
                                    $this->logger->error("Inventory count was updated, but some items weren't saved!", Helper::AppendLoggedin(['Count Name' => $inventory_count->name, 'Item ID' => $key]));
                                }
                            }
                        }
                    }

                    if (isset($_POST['printout-items']) && !empty($_POST['printout-items'])) {
                        foreach ($_POST['printout-items'] as $key => $item_post) {
                            $item_quantity = FilterInput::Int($item_post['quantity']);
                            if ($item_quantity && $item_quantity > 0) {
                                $check_inventory_count_item = Inventory_counts_itemsModel::getAll("WHERE inventory_count_id = '$count->id' && item_id = '$key'", true);

                                $count_item = new Inventory_counts_itemsModel();
                                if ($check_inventory_count_item) {
                                    $count_item->id = $check_inventory_count_item->id;
                                }
                                $count_item->inventory_count_id = $count->id;
                                $count_item->item_id = $key;
                                $count_item->should_have = FilterInput::Int($item_post['should_have']);
                                $count_item->counted = $item_quantity;
                                $count_item->counted = FilterInput::Int($item_post['quantity']);
                                $count_item->created_by = Session::Get('loggedin')->id;
                                $count_item->updated = date("Y-m-d H:i:s");
                                if (!$count_item->Save()) {
                                    $this->logger->error("Inventory count was updated, but some items weren't saved!", Helper::AppendLoggedin(['Count Name' => $inventory_count->name, 'Item ID' => $key]));
                                }
                            }
                        }
                    }

                    $this->logger->info("Inventory count was updated successfully.", Helper::AppendLoggedin(['Count Name' => $inventory_count->name]));



                    /* Check if printout tab was clicked*/
                    /* Check if tmp printout items were created or it's count doesn't match the items to be printed. */
                    if (Request::Check('printout-tab')) {
                        $inventory_count_tmp_print_items = Inventory_counts_tmp_print_itemsModel::Count("WHERE inventory_count_id = '$id'");
                        $inventory_count_tmp_print_items = $inventory_count_tmp_print_items ?: 0;
                        $inventory_count_all_print_items = Inventory_countsModel::getItemsWithStockCount();
                        $inventory_count_all_print_items = $inventory_count_all_print_items ? count($inventory_count_all_print_items) : 0;

                        if ($inventory_count_tmp_print_items != $inventory_count_all_print_items) {
                            if ($inventory_count_tmp_print_items > 0) {
                                $inventory_count_tmp_print_items_delete = new Inventory_counts_tmp_print_itemsModel();
                                $inventory_count_tmp_print_items_delete->Delete("inventory_count_id = '$id'");
                            }

                            $items_to_be_printed = Inventory_countsModel::getItemsWithStock();
                            if ($items_to_be_printed) {
                                foreach ($items_to_be_printed as $key => $item_to_be_printed) {
                                    $inventory_count_tmp_print_item = new Inventory_counts_tmp_print_itemsModel();
                                    $inventory_count_tmp_print_item->second_id = $key + 1;
                                    $inventory_count_tmp_print_item->inventory_count_id = $id;
                                    $inventory_count_tmp_print_item->item_id = $item_to_be_printed->item_id;
                                    if (!$inventory_count_tmp_print_item->Save()) {
                                        $this->logger->error("Failed to save inventory printout items during inventory count update. General saving error!", Helper::AppendLoggedin([]));
                                    }
                                }
                            }
                        }
                    }


                    Redirect::To('pos/inventory_count/' . $count->id);
                } else {
                    $this->logger->error("Failed to update inventory count. General saving error!", Helper::AppendLoggedin([]));
                    Helper::SetFeedback('error', "Failed to update inventory count. Something went wrong!");
                }
            }


            $this->RenderPos([
                'inventory_count' => $inventory_count,
                'inventory_count_items' => $inventory_count_items,
                'inventory_count_missed_items' => $inventory_count_missed_items,
            ]);
        }
    }

    public function Inventory_count_printoutAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        if ($id !== false) {
            $orderBy = Request::Check('order-by', 'get') ? FilterInput::String(Request::Get('order-by')) : false;
            $orderBy_allowed_list = ['category', 'brand'];
            $orderBy = $orderBy && in_array($orderBy, $orderBy_allowed_list) ? ", $orderBy" : "";
            $this->_template
                ->SetViews(['view'])
                ->SetData([
                    'inventory_count_printout_items' => Inventory_counts_tmp_print_itemsModel::getInventoryCountsTmpItems(
                        "WHERE inventory_count_id = '$id' ", "ORDER BY item_id ", $orderBy
                    )
                ])
                ->Render(true);
        }
    }


    /*Vendors*/
    public function VendorsAction()
    {
        $this->RenderPos([
            'data' => VendorsModel::getAll()
        ]);
    }

    public function Vendor_addAction()
    {
        if (Request::Check('submit')) {
            $item = new VendorsModel();
            $item->name = FilterInput::String(Request::Post('name'));
            $item->account_number = FilterInput::String(Request::Post('account_number'));
            $item->contact_f_name = FilterInput::String(Request::Post('contact_f_name'));
            $item->contact_l_name = FilterInput::String(Request::Post('contact_l_name'));
            $item->contact_phone = FilterInput::String(Request::Post('contact_phone'));
            $item->contact_mobile = FilterInput::String(Request::Post('contact_mobile'));
            $item->address = FilterInput::String(Request::Post('address1'));
            $item->address2 = FilterInput::String(Request::Post('address2'));
            $item->city = FilterInput::String(Request::Post('city'));
            $item->suburb = FilterInput::String(Request::Post('suburb'));
            $item->zip = FilterInput::String(Request::Post('zip'));
            $item->website = FilterInput::String(Request::Post('website'));
            $item->email = FilterInput::Email(Request::Post('email'));
            $item->notes = FilterInput::String(Request::Post('notes'));
            if ($item->Save()) {
                $this->logger->info("New vendor was created.", Helper::AppendLoggedin(['Vendor: ' => $item->name]));
                Helper::SetFeedback('success', "Vendor was created successfully.");
                Redirect::To('pos/vendors');
            } else {
                $this->logger->error("Failed to create vendor", Helper::AppendLoggedin(['Vendor' => $item->name]));
                Helper::SetFeedback('error', "Failed to create vendor!");
            }
        }

        $this->RenderPos();
    }

    public function Vendor_editAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        if ($id !== false) {
            $vendor = VendorsModel::getOne($id);

            if (Request::Check('submit')) {
                $item = new VendorsModel();
                $item->id = $id;
                $item->name = FilterInput::String(Request::Post('name'));
                $item->account_number = FilterInput::String(Request::Post('account_number'));
                $item->contact_f_name = FilterInput::String(Request::Post('contact_f_name'));
                $item->contact_l_name = FilterInput::String(Request::Post('contact_l_name'));
                $item->contact_phone = FilterInput::String(Request::Post('contact_phone'));
                $item->contact_mobile = FilterInput::String(Request::Post('contact_mobile'));
                $item->address = FilterInput::String(Request::Post('address1'));
                $item->address2 = FilterInput::String(Request::Post('address2'));
                $item->city = FilterInput::String(Request::Post('city'));
                $item->suburb = FilterInput::String(Request::Post('suburb'));
                $item->zip = FilterInput::String(Request::Post('zip'));
                $item->website = FilterInput::String(Request::Post('website'));
                $item->email = FilterInput::Email(Request::Post('email'));
                $item->notes = FilterInput::String(Request::Post('notes'));

                if ($item->Save()) {
                    $this->logger->info("Vendor updated.", Helper::AppendLoggedin(['Vendor: ' => $vendor->name]));
                    Helper::SetFeedback('success', "Vendor was updated successfully.");
                    Redirect::To('pos/vendors');
                } else {
                    $this->logger->error("Failed to update vendor", Helper::AppendLoggedin(['Vendor' => $vendor->name]));
                    Helper::SetFeedback('error', "Failed to update vendor!");
                }
            }

            $this->RenderPos(['vendor' => $vendor]);
        }
    }

    /*Categories*/
    public function CategoriesAction()
    {
        if (Request::Check('submit')) {
            $item = new CategoriesModel();
            $item->category = FilterInput::String(Request::Post('category'));
            if ($item->Save()) {
                Helper::SetFeedback('success', "Category was created successfully.");
                $this->logger->info("New category was created", array('Category: ' => $item->category, 'Admin: ' => Session::Get('loggedin')->username));
            } else {
                Helper::SetFeedback('error', "Failed to create new category!");
                $this->logger->error("Failed to create new category.", array('Category: ' => $item->category, 'Admin: ' => Session::Get('loggedin')->username));
            }
            Redirect::To('pos/categories');
        }

        $this->RenderPos([
            'data' => CategoriesModel::getCategoriesWithItemsCount()
        ]);
    }

    /*Tags*/
    public function TagsAction()
    {
        $this->RenderPos([
            'data' => ItemsModel::getItemsTags()
        ]);
    }

    /*Brands*/
    public function BrandsAction()
    {
        if (Request::Check('submit')) {
            $item = new BrandsModel();
            $item->brand = FilterInput::String(Request::Post('brand'));
            if ($item->Save()) {
                Helper::SetFeedback('success', "Brand was created successfully.");
                $this->logger->info("New brand was created", array('Brand: ' => $item->brand, 'Admin: ' => Session::Get('loggedin')->username));
            } else {
                Helper::SetFeedback('error', "Failed to create new brand!");
                $this->logger->error("Failed to create new brand.", array('Brand: ' => $item->brand, 'Admin: ' => Session::Get('loggedin')->username));
            }
            Redirect::To('pos/brands');
        }

        $this->RenderPos([
            'data' => BrandsModel::getBrandsWithItemsCount()
        ]);
    }

    /*Vendor Return Reasons*/
    public function Vendor_return_reasonsAction()
    {
        if (Request::Check('submit')) {
            $item = new Vendor_return_reasonsModel();
            $item->reason = FilterInput::String(Request::Post('reason'));
            $item->reason_order = FilterInput::Int(Request::Post('order'));
            if ($item->Save()) {
                Helper::SetFeedback('success', "Return reason was created successfully.");
                $this->logger->info("New Return reason was created", array('Reason: ' => $item->reason, 'Admin: ' => Session::Get('loggedin')->username));
            } else {
                Helper::SetFeedback('error', "Failed to create new return reason!");
                $this->logger->error("Failed to create new return reason.", array('Reason: ' => $item->reason, 'Admin: ' => Session::Get('loggedin')->username));
            }
            Redirect::To('pos/vendor_return_reasons');
        }

        $this->RenderPos([
            'data' => Vendor_return_reasonsModel::getAll()
        ]);
    }




/*Settings*/
    public function SettingsAction()
    {
        $this->RenderPos();
    }

    /*Payment*/
    public function Payment_methodsAction()
    {
        if (Request::Check('submit')) {
            $item = new Payment_methodsModel();
            $item->method = FilterInput::String(Request::Post('method'));
            $item->method_key = strtolower(str_replace(' ', '', $item->method));
            $item->refund_as = FilterInput::String(Request::Post('refund_as'));
            if ($item->Save()) {
                Helper::SetFeedback('success', "Payment Method was created successfully.");
                $this->logger->info("New Payment Method was created", Helper::AppendLoggedin(['Payment Method: ' => $item->method]));
            } else {
                Helper::SetFeedback('error', "Failed to create new payment method!");
                $this->logger->error("Failed to create new payment method.", Helper::AppendLoggedin(['Payment Method: ' => $item->method]));
            }
            Redirect::To('pos/payment_methods');
        }

        $this->RenderPos([
            'data' => Payment_methodsModel::getAll()
        ]);
    }

    /*Tax Classes*/
    public function Tax_classesAction()
    {
        if (Request::Check('submit')) {
            $item = new Tax_classesModel();
            $item->class = FilterInput::String(Request::Post('class'));
            $item->rate = FilterInput::Int(Request::Post('rate'));
            if ($item->Save()) {
                Helper::SetFeedback('success', "Tax Class was created successfully.");
                $this->logger->info("New Tax Class was created", Helper::AppendLoggedin(['Tax Class: ' => $item->class]));
            } else {
                Helper::SetFeedback('error', "Failed to create new tax class!");
                $this->logger->error("Failed to create new tax class.", Helper::AppendLoggedin(['Tax Class: ' => $item->class]));
            }
            Redirect::To('pos/tax_classes');
        }

        $this->RenderPos([
            'data' => Tax_classesModel::getAll()
        ]);
    }

    /*Pricing Levels*/
    public function Pricing_levelsAction()
    {
        if (Request::Check('submit')) {
            $item = new Pricing_levelsModel();
            $item->teir = FilterInput::String(Request::Post('teir'));
            $item->rate = FilterInput::Int(Request::Post('rate'));
            if ($item->Save()) {
                Helper::SetFeedback('success', "Pricing level was created successfully.");
                $this->logger->info("New Pricing level was created", Helper::AppendLoggedin(['Pricing level: ' => $item->teir]));
            } else {
                Helper::SetFeedback('error', "Failed to create new pricing level!");
                $this->logger->error("Failed to create new pricing level.", Helper::AppendLoggedin(['Pricing level: ' => $item->teir]));
            }
            Redirect::To('pos/pricing_levels');
        }

        $this->RenderPos([
            'data' => Pricing_levelsModel::getAll()
        ]);
    }

    /*Discounts*/
    public function DiscountsAction()
    {
        if (Request::Check('submit')) {
            $item = new DiscountsModel();
            $item->title = FilterInput::String(Request::Post('title'));
            $item->type = FilterInput::String(Request::Post('type'));
            $item->discount = FilterInput::Float(Request::Post('discount'));

            if ($item->Save()) {
                Helper::SetFeedback('success', "Discount was created successfully.");
                $this->logger->info("New discount was created", Helper::AppendLoggedin(['Discount: ' => $item->title]));
                Redirect::To('pos/discounts');
            } else {
                Helper::SetFeedback('error', "Failed to create new discount!");
                $this->logger->error("Failed to create new discount.", Helper::AppendLoggedin(['Discount: ' => $item->title]));
            }
        }

        $this->RenderPos([
            'data' => DiscountsModel::getAll()
        ]);
    }



/*Sales*/
    public function SalesAction()
    {
        $register = Register_statusModel::getAll('', true);
        if (!$register) {
            $new_register = new Register_statusModel();
            $new_register->status = 'closed';
            $new_register->updated = date('Y-m-d H:i:s');
            $new_register->updated_by = Session::Get('loggedin')->id;
            $new_register->Save();
        }

        $this->RenderPos([
            'register' => $register && $register->status == 'open'
        ]);
    }

    /*Register*/
    public function Register_openAction()
    {
        if (Request::Check('submit')) {
            if ($count = $this->RegisterCount('open')) {
                $register_o = Register_statusModel::getAll('', true);
                $register = new Register_statusModel();
                $register->id = $register_o->id;
                $register->status = 'open';
                $register->updated = date('Y-m-d H:i:s');
                $register->updated_by = Session::Get('loggedin')->id;

                if ($register->Save()) {
                    $this->logger->info("Register was opened.", Helper::AppendLoggedin(['Opening Count' => number_format($count->total, 2)]));
                    Helper::SetFeedback('Success', "Register open. $".number_format($count->total, 2)." added to the register.");
                    Redirect::To('pos/sales');
                }
            } else {
                $this->logger->error("Failed to open register, couldn't save count.", Helper::AppendLoggedin([]));
                Helper::SetFeedback('error', "Failed to open register, error saving count!");
            }
        }

        $this->RenderPos();
    }

    public function Register_closeAction()
    {
        $page = Request::Check('form_name') ? Request::Post('form_name') : 'init-count' ;
        if ($page == 'init-count') {
            $sum_all = $totals = array();
            /// since last open
            $counts = Register_countsModel::getCountsSinceOpen();
            $sales_payments = Sales_paymentsModel::getSalesPayments();

            foreach ($counts as $count) {
                $sum_all[$count->method]['method'] = $count->method_name;

                if ($count->count_purpose == 'open' || $count->count_purpose == 'add') {
                    $sum_all[$count->method]['open_add'] = isset($sum_all[$count->method]['open_add']) ?
                        $sum_all[$count->method]['open_add'] + $count->total :
                        $count->total;
                } else if ($count->count_purpose == 'payout') {
                    $sum_all[$count->method]['remove'] = isset($sum_all[$count->method]['remove']) ?
                        $sum_all[$count->method]['remove'] + $count->total :
                        $count->total;
                }
            }

            foreach ($sales_payments as $sales_payment) {
                $sum_all[$sales_payment->payment_method]['method'] = $sales_payment->method_name;

                $sum_all[$sales_payment->payment_method]['payments'] = isset($sum_all[$sales_payment->payment_method]['payments']) ?
                    $sum_all[$sales_payment->payment_method]['payments'] + $sales_payment->amount :
                    $sales_payment->amount;
            }

            foreach (array_keys($sum_all) as $payment_method) {
                $add = @$sum_all[$payment_method]['open_add'] ?: 0;
                $payment = @$sum_all[$payment_method]['payments'] ?: 0;
                $remove = @$sum_all[$payment_method]['remove'] ?: 0;
                $sum_all[$payment_method]['remaining'] = ($add + $payment) - $remove;

                $totals['open_add'] = isset($totals['open_add']) ? $totals['open_add'] + $add : $add;
                $totals['payments'] = isset($totals['payments']) ? $totals['payments'] + $payment : $payment;
                $totals['remove'] = isset($totals['remove']) ? $totals['remove'] + $remove : $remove;
                $totals['remaining'] = isset($totals['remaining']) ? $totals['remaining'] + (($add + $payment) - $remove) : ($add + $payment) - $remove;
            }

            $this->RenderPos([
                'page' => 'count',
                'counts' => $sum_all,
                'totals' => $totals
            ]);
        }

        if ($page == 'count') {
            $counts = isset($_POST['counts']) && !empty($_POST['counts']) ? $_POST['counts'] : array();
            $counts['cash']['counted'] = Request::Check('total') ? Request::Post('total') : 0;

            foreach ($counts as $key => $count) {
                $calculated = isset($count['calculated']) && $count['calculated'] ? $count['calculated'] : 0;
                $counts[$key]['diff'] = round($count['counted'] - $calculated, 2);
            }

            $this->RenderPos([
                'page' => 'closing_counts',
                'counts' => $counts
            ]);
        }

        if ($page == 'closing_counts') {
            $count_save = true;
            if (isset($_POST['counted']) && !empty($_POST['counted'])) {
                foreach ($_POST['counted'] as $key => $total) {
                    $count = new Register_countsModel();
                    $count->count_purpose = 'close';
                    $count->method = $key;
                    $count->total = FilterInput::Float($total);
                    $count->notes = Request::Check('notes') ? FilterInput::String(Request::Post('notes')) : '';
                    $count->counted_by = Session::Get('loggedin')->id;
                    $count->counted = date("Y-m-d H:i:s");
                    if ($count->Save()) {
                        $this->logger->info("New register count was submitted on close.", Helper::AppendLoggedin(['Total' => $count->total, 'Method' => ucfirst($count->method)]));
                    } else {
                        $count_save = false;
                        $this->logger->error("Failed to save count on close.", Helper::AppendLoggedin(['Total' => $count->total, 'Method' => ucfirst($count->method)]));
                    }
                }
            }

            $register_o = Register_statusModel::getAll('', true);
            $register = new Register_statusModel();
            $register->id = $register_o->id;
            $register->status = 'closed';
            $register->updated = date('Y-m-d H:i:s');
            $register->updated_by = Session::Get('loggedin')->id;
            if ($register->Save()) {
                if ($count_save) {
                    $this->logger->info("Register was closed and count saved.", Helper::AppendLoggedin([]));
                    Helper::SetFeedback('success', "Register was closed and count saved.");
                } else {
                    $this->logger->error("Register was closed but some or all counts wasn't saved, check previous logs.", Helper::AppendLoggedin([]));
                    Helper::SetFeedback('error', "Register was closed but some or all counts wasn't saved, check the logs.");
                }
            } else {
                $this->logger->error("Failed to close register.", Helper::AppendLoggedin([]));
                Helper::SetFeedback('error', "Failed to close register.");
            }

            Redirect::To('pos/sales');
        }
    }

    public function Register_cash_addAction()
    {
        if (Request::Check('submit')) {
            if ($count = $this->RegisterCount('add')) {
                $this->logger->info("Cash was added to the register.", Helper::AppendLoggedin(['Amount' => number_format($count->total, 2)]));
                Helper::SetFeedback('success', "$".number_format($count->total, 2)." added to the register.");
                Redirect::To('pos/sales');
            }
        }

        $this->RenderPos([
            'payment_methods' => Payment_methodsModel::getAll()
        ]);
    }

    public function Register_cash_removeAction()
    {
        if (Request::Check('submit')) {
            if ($count = $this->RegisterCount('payout')) {
                $this->logger->info("Payout was withdrawn from the register.", Helper::AppendLoggedin(['Amount' => number_format($count->total, 2)]));
                Helper::SetFeedback('Success', "$".number_format($count->total, 2)." withdrawn from the register.");
                Redirect::To('pos/sales');
            }
        }

        $this->RenderPos([
            'payment_methods' => Payment_methodsModel::getAll()
        ]);
    }

    /*Sales*/
    public function Sales_listAction()
    {
        $status_filter = Request::Check('status', 'get') ? Request::Get('status') : false;
        $type_filter = Request::Check('type', 'get') ? Request::Get('type') : false;
        $where = $status_filter || $type_filter ? " WHERE " : "";
        $where .= $status_filter ? " sales.sale_status = '$status_filter'" : "";
        $where .= $status_filter && $type_filter ? " && " : "";
        $where .= $type_filter ? " sales.sale_type = '$type_filter'" : "";

        $this->RenderPos([
            'data' => SalesModel::getAllSales($where)
        ]);
    }

    public function SaleAction($id)
    {
        $sale_payments = Sales_paymentsModel::getSalesPayments("WHERE sales_payments.sale_id = '$id'");
        $sale_payments_totals = array();
        if ($sale_payments) {
            foreach ($sale_payments as $sale_payment) {
                if (isset($sale_payments_totals[$sale_payment->payment_method])) {
                    $sale_payments_totals[$sale_payment->payment_method]['total'] += $sale_payment->amount;
                } else {
                    $sale_payments_totals[$sale_payment->payment_method]['total'] = $sale_payment->amount;
                }
                $sale_payments_totals[$sale_payment->payment_method]['method'] = $sale_payment->method_name ?: $sale_payment->payment_method;
            }
        }

        $this->RenderPos([
            'sale_id' => $id,
            'sale' => SalesModel::getSale("WHERE sales.id = '$id'", true),
            'sale_items' => Sales_itemsModel::getSaleItems($id),
            'sale_payments' => $sale_payments,
            'sale_payments_totals' => $sale_payments_totals
        ]);
    }

    public function Sale_addAction()
    {
        if (!empty($_POST)) {
            $sale = new SalesModel();

            $uid_code = 445137 . sprintf("%05d", SalesModel::NextID());
            $sale->uid = $uid_code . Helper::CalculateUpcCheckDigit($uid_code);
            $sale->customer_id = FilterInput::Int(Request::Post('customer-id'));
            $sale->pricing_level = FilterInput::Int(Request::Post('pricing-level'));
            $sale->printed_note = FilterInput::String(Request::Post('printed_note'));
            $sale->internal_note = FilterInput::String(Request::Post('internal_note'));

            $user_id = FilterInput::Int(Request::Post('user-id'));

            if (isset($_POST['items']) && !empty($_POST['items'])) {
                $items = array();
                $subtotal = $total = $sale_discount = $tax = 0;

                foreach ($_POST['items'] as $item_post) {
                    if (isset($item_post['id']) && !empty($item_post['id'])) {
                        $item = new \stdClass();
                        $item->item_id = $item_post['id'];
                        $item->item_type = $item_post['type'] == 'refund' ? 'refund' : 'sale';
                        $item->discount_id = isset($item_post['discount']) && $item_post['discount'] ? $item_post['discount'] : 0;
                        $item->quantity = isset($item_post['qty']) && $item_post['qty'] ? $item_post['qty'] : 1;

                        $item_details = ItemsModel::getItemInventoryPricingTaxDetails($item->item_id);

                        if ($item_details) {
                            $item->item = $item_details->item;
                            $item->tax_id = $item_details->tax_class;
                            $item->inventory_id = $item_details->item_inventory_id;

                            // calc item's price based on the pricing level (if any, else use the RRP price)
                            $item->original_price = $item_details->rrp_price;
                            if ($sale->pricing_level && $sale->pricing_level != 0) {
                                $pricing_level = Pricing_levelsModel::getOne($sale->pricing_level);
                                if ($pricing_level) {
                                    $level_rate = strtolower($pricing_level->teir) == "teir 2" ? ($item_details->rrp_percentage / 2) : $pricing_level->rate;
                                    $item->original_price = substr((($level_rate / 100) * $item_details->buy_price), 0, 5) + $item_details->buy_price;
                                } else {
                                    // log that pricing level not found so used the rrp price
                                    $this->logger->info("Couldn't find pricing level while adding sale items, used RRP Price instead.", Helper::AppendLoggedin(['Sale UID' => $sale->uid]));
                                }
                            }

                            // calc item's discount (if any, else use the original price as price)
                            $item->price = $item->item_type == 'refund' ? '-'.$item->original_price : $item->original_price;
                            if ($item->discount_id !== 0) {
                                $discount_details = DiscountsModel::getOne($item->discount_id);
                                if ($discount_details) {
                                    $item->discount = $discount_details->type == 'fixed' ?
                                        $discount_details->discount :
                                        ($item->original_price * $discount_details->discount) / 100;
                                    $item->price = $item->item_type == 'refund' ? '-'.($item->original_price - $item->discount) : $item->original_price - $item->discount;
                                }
                            }

                            $item->total = $item->price * abs($item->quantity);
                            $item->tax = ($item->price * abs($item->quantity)) * $item_details->rate / 100;

                            $sale_discount += isset($item->discount) ? $item->discount * abs($item->quantity) : 0;
                            $tax += $item->tax;
                            $subtotal += $item->price * abs($item->quantity);
                            $total += $item->price * abs($item->quantity);
                        }

                        array_push($items, $item);
                    }
                }

                $sale->subtotal = $subtotal;
                $sale->discount = $sale_discount;
                $sale->total = $total;
                $sale->tax = $tax;
                $sale->created_by = Session::Get('loggedin')->id;

                if (Request::Check('payment')) {
                    $sale->sale_type = 'sale';
                } elseif (Request::Check('quote')) {
                    $sale->sale_type = 'quote';
                } elseif (Request::Check('cancel')) {
                    $sale->sale_type = 'cancel';
                }

                if ($sale->Save()) {
                    // Create sale invoice.
                    $invoice = new InvoicesModel();
                    $invoice->reference = InvoicesModel::NextID("LPAD(MAX(auto_increment),5,'0')");
                    $invoice->customer_id = $sale->customer_id ?: 0;
                    $invoice->subtotal = $sale->subtotal;
                    $invoice->discount = $sale->discount;
                    $invoice->tax = $sale->tax;
                    $invoice->total = $sale->total;
                    $invoice->amount_due = $sale->total;
                    $invoice->status = 'unpaid';
                    if (!$invoice->Save()) {
                        $this->logger->error("Failed to create sale's invoice. Must be corrected manually.", Helper::AppendLoggedin(['Sale' => $sale->uid]));
                        Helper::SetFeedback('error', "Failed to create sale's invoice. Must be corrected manually.");
                    } else {
                        $invoices_orders = new Invoices_ordersModel();
                        $invoices_orders->invoice_id = $invoice->id;
                        $invoices_orders->order_id = $sale->id;
                        $invoices_orders->type = 'sale';
                        if (!$invoices_orders->Save()) {
                            $this->logger->error("Failed to create sale - invoice link. Must be corrected manually.", Helper::AppendLoggedin(['Sale' => $sale->uid, 'Invoice ID' => $invoice->id]));
                        }

                        // update invoice keywords
                        $this->UpdateInvoiceKeywords($invoice->id);
                    }


                    foreach ($items as $std_item) {
                        $sale_item = new Sales_itemsModel();
                        $sale_item->sale_id = $sale->id;

                        foreach (get_object_vars($std_item) as $key => $value) {
                            $sale_item->$key = $value;
                        }

                        if (!$sale_item->Save()) {
                            $this->logger->error("Sale was saved, but some items weren't saved!", Helper::AppendLoggedin(['Sale UID' => $sale->uid, 'Item ID' => $std_item->item_id]));
                            Helper::SetFeedback('error', "Sale was saved, but some items weren't saved!");
                        }


                        // invoice lines
                        if ($invoice->id) {
                            $invoice_line = new Invoice_linesModel();
                            $invoice_line->invoice_id = $invoice->id;
                            $invoice_line->product_id = $std_item->item_id;
                            $invoice_line->tax_id = $std_item->tax_id;
                            $invoice_line->product_name = $std_item->item;
                            $invoice_line->quantity = $std_item->quantity;
                            $invoice_line->unit_price = $std_item->original_price;
                            $invoice_line->discount = $std_item->discount;
                            $invoice_line->tax = $std_item->tax;
                            $invoice_line->total = $std_item->total;
                            if (!$invoice_line->Save()) {
                                $this->logger->error("Sale's invoice was saved, but some items weren't saved!", Helper::AppendLoggedin(['Sale UID' => $sale->uid, 'Item ID' => $std_item->item_id]));
                                Helper::SetFeedback('error', "Sale's invoice was saved, but some items weren't saved!");
                            }
                        }
                    }

                    if (Request::Check('register-customer-shipping')) {
                        $shipment_user_update = new UsersModel();
                        $shipment_user_update->id = $user_id;
                        $shipment_user_update->firstName = Request::Post('f_name');
                        $shipment_user_update->lastName = Request::Post('l_name');
                        $shipment_user_update->phone = Request::Post('phone');
                        $shipment_user_update->phone2 = Request::Post('mobile');
                        $shipment_user_update->Save();

                        $shipment_customer_update = new CustomersModel();
                        $shipment_customer_update->id = $sale->customer_id;
                        $shipment_customer_update->address = Request::Post('address');
                        $shipment_customer_update->address2 = Request::Post('address2');
                        $shipment_customer_update->city = Request::Post('city');
                        $shipment_customer_update->suburb = Request::Post('suburb');
                        $shipment_customer_update->zip = Request::Post('zip');
                        $shipment_customer_update->companyName = Request::Post('company');
                        $shipment_customer_update->Save();

                        $sale_shipment = new Sales_shipmentsModel();
                        $sale_shipment->customer_id = $sale->customer_id;
                        $sale_shipment->sale_id = $sale->id;
                        $sale_shipment->shipping_instructions = Request::Post('ship_note');
                        $sale_shipment->shipped = Request::Check('shipped') ? 1 : 0;
                        $sale_shipment->shipped_at = Request::Check('shipped') ? date('Y-m-d H:i:s') : '';
                        $sale_shipment->Save();
                    }


                    $this->logger->info("Sale was saved successfully.", Helper::AppendLoggedin(['Sale UID' => $sale->uid]));
                    if ($sale->customer_id) {
                        LoggerModel::Instance($user_id, 'customers')
                            ->InitializeLogger()
                            ->info("Sale was created for customer.", Helper::AppendLoggedin(['Sale ID' => $sale->uid]));
                    }
                    if ($sale->sale_type == 'sale') {
                        Redirect::To('pos/sale_payment/' . $sale->id);
                    } else {
                        Redirect::To('pos/sale_actions/' . $sale->id);
                    }
                } else {
                    $this->logger->error("Failed to create new sale. General saving error!", Helper::AppendLoggedin([]));
                    if ($sale->customer_id) {
                        LoggerModel::Instance($user_id, 'customers')
                            ->InitializeLogger()
                            ->error("Failed to create new sale for customer.", Helper::AppendLoggedin());
                    }

                    Helper::SetFeedback('error', "Failed to save sale. Something went wrong!");
                }
            } else {
                $this->logger->error("Failed to create new sale. No items selected!", Helper::AppendLoggedin([]));
                Helper::SetFeedback('error', "Failed to save sale. No items were selected!");
            }
        }

        $this->RenderPos([
            'pricing_levels' => Pricing_levelsModel::getAll(),
            'discounts' => DiscountsModel::getAll()
        ]);
    }

    public function Sale_refundAction()
    {
        Redirect::To('pos/sale_add');
    }

    public function Sale_continue_checkAction()
    {
        $sale = SalesModel::getAll("WHERE sales.sale_status = 'awaiting_payment' && sales.updated >= '".date('Y-m-d')."'  ORDER BY sales.updated DESC", true);
        if ($sale) {
            Redirect::To('pos/sale_continue/'.$sale->id);
        } else {
            Redirect::To('pos/sale_add');
        }
    }

    public function Sale_continueAction($id)
    {
        if (!empty($_POST)) {
            $sale = new SalesModel();
            $sale->id = $id;
            $sale->customer_id = Request::Re_Check('customer-id') ? FilterInput::Int(Request::Post('customer-id')) : 0;
            $sale->pricing_level = FilterInput::Int(Request::Post('pricing-level'));
            $sale->printed_note = FilterInput::String(Request::Post('printed_note'));
            $sale->internal_note = FilterInput::String(Request::Post('internal_note'));

            $user_id = Request::Re_Check('user-id') ? FilterInput::Int(Request::Post('user-id')) : 0;


            if (isset($_POST['items']) && !empty($_POST['items'])) {
                $items = array();
                $subtotal = $total = $sale_discount = $tax = 0;
                foreach ($_POST['items'] as $item_post) {
                    if (isset($item_post['id']) && !empty($item_post['id'])) {
                        $item = new \stdClass();
                        $item->sale_item_id = isset($item_post['sale_item_id']) && $item_post['sale_item_id'] ? $item_post['sale_item_id'] : 0;
                        $item->invoice_line_id = isset($item_post['invoice_line_id']) && $item_post['invoice_line_id'] ? $item_post['invoice_line_id'] : 0;
                        $item->item_id = $item_post['id'];
                        $item->item_type = $item_post['type'] == 'refund' ? 'refund' : 'sale';
                        $item->discount_id = isset($item_post['discount']) && $item_post['discount'] ? intval($item_post['discount']) : 0;
                        $item->quantity = isset($item_post['qty']) && $item_post['qty'] ? intval($item_post['qty']) : 1;

                        $item_details = ItemsModel::getItemInventoryPricingTaxDetails($item->item_id);
                        if ($item_details) {
                            $item->item = $item_details->item;
                            $item->tax_id = $item_details->tax_class;
                            $item->inventory_id = $item_details->item_inventory_id;

                            // calc item's price based on the pricing level (if any, else use the RRP price)
                            $item->original_price = $item_details->rrp_price;
                            if ($sale->pricing_level && $sale->pricing_level != 0) {
                                $pricing_level = Pricing_levelsModel::getOne($sale->pricing_level);
                                if ($pricing_level) {
                                    $level_rate = strtolower($pricing_level->teir) == "teir 2" ? ($item_details->rrp_percentage / 2) : $pricing_level->rate;
                                    $item->original_price = substr((($level_rate / 100) * $item_details->buy_price), 0, 5) + $item_details->buy_price;
                                } else {
                                    // log that pricing level not found so used the rrp price
                                    $this->logger->info("Couldn't find pricing level while adding sale items, used RRP Price instead.", Helper::AppendLoggedin(['Sale UID' => $sale->uid]));
                                }
                            }

                            // calc item's discount (if any, else use the original price as price)
                            $item->price = $item->item_type == 'refund' ? '-'.$item->original_price : $item->original_price;
                            if ($item->discount_id !== 0) {
                                $discount_details = DiscountsModel::getOne($item->discount_id);
                                if ($discount_details) {
                                    $item->discount = $discount_details->type == 'fixed'
                                        ? $discount_details->discount
                                        : ($item->original_price * $discount_details->discount) / 100;
                                    $item->price = $item->item_type == 'refund' ? '-'.($item->original_price - $item->discount) : $item->original_price - $item->discount;
                                }
                            }

                            $item->total = $item->price * abs($item->quantity);
                            $item->tax = ($item->price * abs($item->quantity)) * $item_details->rate / 100;

                            $sale_discount += isset($item->discount) ? $item->discount * $item->quantity : 0;
                            $tax += $item->tax;
                            $subtotal += $item->original_price * $item->quantity;
                            $total += $item->price * $item->quantity;
                        }

                        array_push($items, $item);
                    }
                }

                $sale->subtotal = $subtotal;
                $sale->discount = $sale_discount;
                $sale->total = $total;
                $sale->tax = $tax;

                if (Request::Check('payment')) {
                    $sale->sale_type = 'sale';
                } elseif (Request::Check('quote')) {
                    $sale->sale_type = 'quote';
                } elseif (Request::Check('cancel')) {
                    $sale->sale_type = 'cancel';
                }

                if ($sale->Save()) {
                    // update sale's invoice.
                    $existing_invoice = SalesModel::getSaleInvoiceID($id);

                    $invoice = new InvoicesModel();
                    if ($existing_invoice) {
                        $invoice->id = $existing_invoice->invoice_id;
                    } else {
                        $invoice->reference = InvoicesModel::NextID("LPAD(MAX(auto_increment),5,'0')");
                    }

                    $invoice->customer_id = $sale->customer_id ?: 0;

                    $invoice->subtotal = $sale->subtotal;
                    $invoice->discount = $sale->discount;
                    $invoice->tax = $sale->tax;
                    $invoice->total = $sale->total;
                    $invoice->amount_due = $sale->total;
                    $invoice->status = 'unpaid';
                    if ($invoice->Save()) {
                        if (!$existing_invoice) {
                            $invoices_orders = new Invoices_ordersModel();
                            $invoices_orders->invoice_id = $invoice->id;
                            $invoices_orders->order_id = $sale->id;
                            $invoices_orders->type = 'sale';
                            if (!$invoices_orders->Save()) {
                                $this->logger->error("Failed to create sale - invoice link. Must be corrected manually.", Helper::AppendLoggedin(['Sale' => $sale->uid, 'Invoice ID' => $invoice->id]));
                            }
                        }

                        // update invoice keywords
                        $this->UpdateInvoiceKeywords($invoice->id);
                    } else {
                        $this->logger->error("Failed to create sale's invoice. Must be corrected manually.", Helper::AppendLoggedin(['Sale' => $sale->uid]));
                        Helper::SetFeedback('error', "Failed to create sale's invoice. Must be corrected manually.");
                    }

                    foreach ($items as $std_item) {
                        $sale_item = new Sales_itemsModel();
                        $invoice_line = new Invoice_linesModel();

                        if ($std_item->sale_item_id && $std_item->sale_item_id !== 0) {
                            $sale_item->id = $std_item->sale_item_id;
                            unset($std_item->sale_item_id);
                        }
                        if ($std_item->invoice_line_id && $std_item->invoice_line_id !== 0) {
                            $invoice_line->id = $std_item->invoice_line_id;
                            unset($std_item->invoice_line_id);
                        }

                        // sale lines
                        $sale_item->sale_id = $sale->id;
                        foreach (get_object_vars($std_item) as $key => $value) {
                            $sale_item->$key = $value;
                        }

                        if (!$sale_item->Save()) {
                            $this->logger->error("Sale was updated, but some items weren't saved!", Helper::AppendLoggedin(['Sale ID' => $sale->id, 'Item ID' => $std_item->item_id]));
                        }

                        // invoice lines
                        $invoice_line->invoice_id = $invoice->id;
                        $invoice_line->product_id = $std_item->item_id;
                        $invoice_line->tax_id = $std_item->tax_id;
                        $invoice_line->product_name = $std_item->item;
                        $invoice_line->quantity = $std_item->quantity;
                        $invoice_line->unit_price = $std_item->original_price;
                        $invoice_line->discount = $std_item->discount;
                        $invoice_line->tax = $std_item->tax;
                        $invoice_line->total = $std_item->total;
                        if (!$invoice_line->Save()) {
                            $this->logger->error("Sale's invoice was saved, but some items weren't saved!", Helper::AppendLoggedin(['Sale UID' => $sale->uid, 'Item ID' => $std_item->item_id]));
                            Helper::SetFeedback('error', "Sale's invoice was saved, but some items weren't saved!");
                        }
                    }

                    $this->logger->info("Sale was updated successfully.", Helper::AppendLoggedin(['Sale ID' => $sale->id]));
                    if ($sale->customer_id) {
                        LoggerModel::Instance($user_id, 'customers')
                            ->InitializeLogger()
                            ->info("Customer's sale was updated.", Helper::AppendLoggedin(['Sale ID' => $sale->id]));
                    }

                    if ($sale->sale_type == 'sale') {
                        Redirect::To('pos/sale_payment/' . $sale->id);
                    } else {
                        Redirect::To('pos/sale_actions');
                    }
                } else {
                    $this->logger->error("Failed to update sale. General saving error!", Helper::AppendLoggedin([]));
                    Helper::SetFeedback('error', "Failed to save sale. Something went wrong!");
                }
            } else {
                $this->logger->error("Failed to update sale. No items selected!", Helper::AppendLoggedin([]));
                Helper::SetFeedback('error', "Failed to save sale. No items were selected!");
            }

        }

        $this->RenderPos([
            'sale' => SalesModel::getSale("WHERE sales.id = '$id'", true),
            'sale_items' => Sales_itemsModel::getSaleItems_invoiceLines("WHERE sales_items.sale_id = '$id'"),
            'pricing_levels' => Pricing_levelsModel::getAll(),
            'discounts' => DiscountsModel::getAll()
        ]);
    }

    public function Sale_paymentAction($id)
    {
        $sale = SalesModel::getSale__salePayment("WHERE sales.id = '$id'", true);

        if (!empty($_POST)) {
            if (isset($_POST['payments']) && !empty($_POST['payments'])) {
                $total_payments = array_sum($_POST['payments']);
                $invoice_id = SalesModel::getSaleInvoiceID($id)->invoice_id;

                foreach ($_POST['payments'] as $method => $amount) {
                    if ($amount > 0) {
                        $sale_payment = new Sales_paymentsModel();
                        $sale_payment->sale_id = $id;
                        $sale_payment->payment_method = $method;
                        $sale_payment->amount = FilterInput::Float($amount);
                        $sale_payment->created_by = Session::Get('loggedin')->id;


                        $invoice_payment = new Invoices_paymentsModel();
                        $invoice_payment->invoice_id = $invoice_id;
                        $invoice_payment->payment_method = $method;
                        $invoice_payment->amount = FilterInput::Float($amount);

                        if (!$sale_payment->Save()) {
                            $this->logger->error("Some sale payments weren't saved!", Helper::AppendLoggedin(['Sale ID' => $id, 'Payment Method' => $method, 'Amount' => $amount]));
                        }
                        if (!$invoice_payment->Save()) {
                            $this->logger->error("Some invoice payments weren't saved!", Helper::AppendLoggedin(['Invoice ID' => $invoice_id, 'Payment Method' => $method, 'Amount' => $amount]));
                        }
                    }
                }

                $sale_update = new SalesModel();
                $sale_update->id = $id;

                if (($total_payments + $sale->total_paid) >= $sale->total) {
                    $sale_update->sale_status = 'paid';
                } elseif ($total_payments + $sale->total_paid != 0) {
                    $sale_update->sale_status = 'partial_payment';
                } elseif ($total_payments + $sale->total_paid == 0) {
                    $sale_update->sale_status = 'awaiting_payment';
                }

                if ($sale_update->Save()) {
                    // update invoice
                    $invoice = new InvoicesModel();
                    $invoice->id = $invoice_id;
                    $invoice->amount_paid = $total_payments + $sale->total_paid;
                    $invoice->amount_due = $sale->total - $invoice->amount_paid > 0 ? $sale->total - $invoice->amount_paid : 0;
                    if ($sale->total == $invoice->amount_due) {
                        $invoice->status = 'unpaid';
                    } elseif ($invoice->amount_due == 0) {
                        $invoice->status = 'paid';
                    } elseif ($sale->total > $invoice->amount_due) {
                        $invoice->status = 'semi-paid';
                    }

                    if (!$invoice->Save()) {
                        $this->logger->error("Payments were saved, But failed to update sale's invoice..", Helper::AppendLoggedin(['Sale ID' => $id]));
                    } else {
                        // update invoice keywords
                        $this->UpdateInvoiceKeywords($invoice->id);
                    }



                    $sale_items = Sales_itemsModel::getSaleItems_inventory($id);
                    if ($sale_items) {
                        foreach ($sale_items as $sale_item) {
                            if ($sale_item->inventory_id) {
                                $inventory = new Items_inventoryModel();
                                $inventory->id = $sale_item->inventory_id;
                                $inventory->qoh = $sale_item->qoh - $sale_item->quantity;
                                if (!$inventory->Save()) {
                                    $this->logger->error("Payment was saved, but failed to update inventory header, correct manually!", Helper::AppendLoggedin(['Sale ID' => $sale_item->sale_id, 'Item ID' => $sale_item->item_id]));
                                }

                                $inventory_movement = new Items_inventory_movementsModel();
                                $inventory_movement->item_id = $sale_item->item_id;
                                $inventory_movement->inventory_header_id = $sale_item->inventory_id;
                                $inventory_movement->type = 'sale';
                                $inventory_movement->quantity = '-'.$sale_item->quantity;
                                $inventory_movement->source = "pos/sale/$id";
                                if (!$inventory_movement->Save()) {
                                    $this->logger->error("Payment was saved, but failed to create inventory movement record!", Helper::AppendLoggedin(['Sale ID' => $sale_item->sale_id, 'Item ID' => $sale_item->item_id]));
                                }
                            }
                        }
                    }


                    $this->logger->info("Sale payment update. payment status: ".str_replace('_', ' ', $sale_update->sale_status), Helper::AppendLoggedin(['Sale ID' => $id, 'Amount' => $total_payments]));
                    if ($sale->customer_id) {
                        LoggerModel::Instance($sale->customer_id, 'customers')
                            ->InitializeLogger()
                            ->info("Sale payment update. payment status: ".str_replace('_', ' ', $sale_update->sale_status), Helper::AppendLoggedin(['Sale ID' => $id, 'Amount' => $total_payments]));
                    }

                    Redirect::To('pos/sale_actions/' . $id);
                } else {
                    $this->logger->error("Payments were saved, But failed to mark sale as paid.", Helper::AppendLoggedin(['Sale ID' => $id]));
                    Helper::SetFeedback('error', "Payments were saved, But failed to mark sale as paid.");
                }
            } else {
                $this->logger->error("Failed to save sale payments. No payments were submitted!", Helper::AppendLoggedin(['Sale ID' => $id]));
                Helper::SetFeedback('error', "Failed to save sale payments. No payments were submitted!");
            }
        }

        if ($sale) {
            $customer = $sale->customer_id ? CustomersModel::getCustomers("WHERE users.id = '$sale->customer_id'", true) : false;
            $this->RenderPos([
                'sale' => $sale,
                'sale_items' => Sales_itemsModel::getSaleItems($id),
                'customer' => $customer,
                'payment_methods' => Payment_methodsModel::getAll()
            ]);
        }
    }

    public function Sale_actionsAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        if ($id !== false) {
            $sale = SalesModel::getPaidSale("WHERE sales.id = '$id'", true);

            $this->RenderPos([
                'sale' => $sale
            ]);
        }
    }

    public function Sale_receiptAction($id)
    {
        echo $this->SaleReceipt($id);
    }

    public function Sale_email_receiptAction()
    {
        $id = ($this->_params) != null ? $this->_params[0] : false;
        if ($id !== false) {
            $this->RenderPos([
                'sale_id' => $id
            ]);
        }
    }

    public function Sale_receipt_email_actionAction($sale_id)
    {
        if ($sale_id !== false && !empty($_POST)) {
            $to_email = FilterInput::Email(Request::Post('to_email'));
            $to_name = FilterInput::String(Request::Post('to_name'));
            $to_subject = FilterInput::String(Request::Post('to_subject'));
            $message = FilterInput::String(Request::Post('message'));


            // Convert html receipt to PDF to attach to email.
            $template = $this->SaleReceipt($sale_id);
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'margin_top' => 5,
                'margin_bottom' => 5
            ]);
            $mpdf->WriteHTML($template);

            $document = $sale_id.'-'.date('d.m.Y').'-'.rand(99999999, 9).'.pdf';
            $mpdf->Output(SALES_RECEIPTS_PATH.$document, 'F');

            if (file_exists(SALES_RECEIPTS_PATH.$document)) {
                $mail = new MailModel();
                $mail->from_email = CONTACT_EMAIL;
                $mail->from_name = CONTACT_NAME;
                $mail->to_email = $to_email;
                $mail->to_name = $to_name;
                $mail->subject = $to_subject;
                $mail->message = Helper::GenerateTemplate('sale-receipt-email-template', ['MESSAGE' => $message]);
                $mail->attachment = [SALES_RECEIPTS_PATH.$document];

                if ($mail->Send()) {
                    $this->logger->info("Sale receipt email was sent.", Helper::AppendLoggedin(['Sale ID' => $sale_id]));
                    Helper::SetFeedback('success', "Sale receipt email was sent successfully.");
                } else {
                    $this->logger->error('Failed to send sale receipt email!', Helper::AppendLoggedin(['Sale ID' => $sale_id]));
                    Helper::SetFeedback('error', "Failed to send sale receipt email!");
                }
            }
        } else {
            $this->logger->error('Failed to send sale receipt email! Couldn\'t generate PDF receipt PDF.', Helper::AppendLoggedin(['Sale ID' => $sale_id]));
            Helper::SetFeedback('error', "Failed to send sale receipt email! Couldn't generate PDF receipt.");
        }

        Redirect::To('pos/sales');
    }


    /* Quotes */
    public function QuotesAction()
    {
        $status_filter = Request::Check('status', 'get') ? Request::Get('status') : false;
        $where = $status_filter ? " WHERE quotes.status = '$status_filter'" : "";

        $quotes = QuotesModel::getQuotes($where);
        $this->RenderPos([
            'data' => $quotes
        ]);
    }

    public function QuoteAction($id)
    {
        $this->RenderPos([
            'quote' => QuotesModel::getQuote("WHERE quotes.id = '$id'", true),
            'quote_items' => Quotes_itemsModel::getQuoteItemsForPOS("WHERE quotes_items.quote_id = '$id'"),
            'quote_po' => Quotes_purchase_ordersModel::getAll("WHERE quote_id = '$id'", true)
        ]);
    }

    public function Quote_receiptAction($id)
    {
        echo $this->QuoteReceipt($id);
    }

    public function Quote_order_partsAction($id)
    {
        $quote_items = Quotes_itemsModel::getQuoteItemsForPO("WHERE quotes_items.quote_id = '$id'");
        $quote_existing_purchase_order = Quotes_purchase_ordersModel::getAll("WHERE quote_id = '$id'", true);

        if (Request::Check('save') || Request::Check('save-continue')) {
            $requested_action = Request::Check('save-continue') ? 'continue' : 'save';

//            else {
//                $generate_po = $this->Quote_purchase_order_generateAction($id);
//                if ($generate_po['result'] == true && $generate_po['document']) {
//                    $purchase_order = $generate_po['document'];
//                }
//            }

            if (Request::Check('purchase-order')) {
                $purchase_order = Request::Post('purchase-order');

                $quote_purchase_order = new Quotes_purchase_ordersModel();
                if ($quote_existing_purchase_order) {
                    $quote_purchase_order->id = $quote_existing_purchase_order->id;
                }
                $quote_purchase_order->quote_id = $id;
                $quote_purchase_order->purchase_order = $purchase_order;
                $quote_purchase_order->purchase_order_items = json_encode($_POST['po-items']);
                $quote_purchase_order->updated = date('Y-m-d H:i:s');

                if ($quote_purchase_order->Save()) {
                    $this->logger->info('Quote\'s purchase order was generated & saved successfully.', Helper::AppendLoggedin(['Quote ID' => $id]));
                    if ($requested_action == 'continue') {
                        Redirect::To('pos/quote/'.$id.'#tabs-menu-po-link');
                    } else {
                        Redirect::To('pos/quote/'.$id);
                    }
                } else {
                    $this->logger->error('Failed to save quote\'s purchase order!', Helper::AppendLoggedin(['Quote ID' => $id]));
                }
            } else {
                $this->logger->error('Failed to generate & save quote\'s purchase order! couldn\'t generate file.', Helper::AppendLoggedin(['Quote ID' => $id]));
            }
        }

        $this->RenderPos([
            'quote_id' => $id,
            'quote_po' => $quote_existing_purchase_order,
            'data' => $quote_items
        ]);
    }

    public function Quote_purchase_order_generateAction($id)
    {
        $quote_items_where = '';
        if (Request::Check('items')) {
            if (!empty($_POST['items'])) {
                $quote_items_where = " && quotes_items.id IN (".FilterInput::String(Request::Post('items')).")";
            }
        } else {
            $quote_items_where = " HAVING available_stock > 0 ";
        }

        $quote = QuotesModel::getQuote("WHERE quotes.id = '$id'", true);
        $quote_items = false;
        if ($quote_items_where) {
            $quote_items = Quotes_itemsModel::getQuoteItemsForPO("WHERE quotes_items.quote_id = '$quote->id' " . $quote_items_where);
        }

        // Generate quote PDF
        $templateProcessor = new MailMergeTemplateProcessor(PUBLIC_PATH.'QuotePurchaseOrder.docx');
        $doc_variables = $templateProcessor->getMailMergeVariables();

        $variables = array();
        $variables['InvoiceTitle'] = "PURCHASE ORDER";
        $variables['InvoiceDate'] = date('d M Y');
        $variables['InvoiceNumber'] = 'PO-'.$quote->quote_reference;
        $variables['Reference'] = $quote->lastName ?: ' ';

        $variables['DeliveryAddress'] = '';
        $variables['Description'] = '';
        $variables['Quantity'] = '';
        $variables['UnitAmount'] = '';
        $variables['TaxPercentageOrName'] = '';
        $variables['LineAmount'] = '';

        $counter = 1;
        $opening_tag = "<w:r><w:rPr/><w:t>";
        $closing_tag = "</w:t></w:r><w:br/><w:br/>";

        $total = 0;
        if ($quote_items) {
            foreach ($quote_items as $quote_item) {
                $update_quote_item = new Quotes_itemsModel();
                $update_quote_item->id = $quote_item->id;
                $update_quote_item->is_purchase_order = 1;
                $update_quote_item->Save();


                $total += floatval(str_replace(",", "", $quote_item->original_price)) * intval($quote_item->quantity);

                if ($counter > 1) {
                    $variables['Description'] .= $opening_tag;
                    $variables['Quantity'] .= $opening_tag;
                    $variables['UnitAmount'] .= $opening_tag;
                    $variables['TaxPercentageOrName'] .= $opening_tag;
                    $variables['LineAmount'] .= $opening_tag;
                }

                $variables['Description'] .= $quote_item->item_sku.'</w:t><w:br/><w:t>'. FilterInput::CleanString($quote_item->item_name);
                $variables['Quantity'] .= $quote_item->quantity;
                $variables['UnitAmount'] .= preg_quote('$').number_format(str_replace(",", "", $quote_item->original_price), 2);
                $variables['TaxPercentageOrName'] .= '10%';
                $variables['LineAmount'] .= preg_quote('$').number_format(floatval(str_replace(",", "", $quote_item->original_price)) * $quote_item->quantity, 2);

                if ($counter != count($quote_items)) {
                    $variables['Description'] .= $closing_tag;
                    $variables['Quantity'] .= $closing_tag;
                    $variables['UnitAmount'] .= $closing_tag;
                    $variables['TaxPercentageOrName'] .= $closing_tag;
                    $variables['LineAmount'] .= $closing_tag;
                }

                $counter++;
            }
        }

        $tax = floatval($total) / 10;
        $subtotal = floatval($total) - (floatval($tax));

        $variables['InvoiceSubTotal'] = preg_quote('$').number_format($subtotal, 2);
        $variables['TaxTotal'] = preg_quote('$').number_format($tax, 2);
        $variables['InvoiceAmountDue'] = preg_quote('$').number_format($total, 2);

        foreach ($doc_variables as $doc_variable) {
            if (!in_array($doc_variable, array_keys($variables))) {
                $variables[$doc_variable] = ' ';
            }
        }

        $document_name = $quote->uid.'-Purchase-Order';
        $document = QUOTES_PO_PATH.$document_name;
        $templateProcessor->setMergeData($variables);
        $templateProcessor->doMerge();
        $templateProcessor->saveAs($document.'.docx');


        if (file_exists($document.'.docx')) {
            exec("libreoffice --convert-to pdf ".$document.".docx --outdir ".QUOTES_PO_PATH, $logs);

            if (file_exists($document.'.pdf')) {
                $results['status'] = true;
                $results['document'] = $document_name;
                $results['result'] = QUOTES_PO_DIR.$document_name.'.pdf';
                $this->logger->info('Quote\'s purchase order was generated.', Helper::AppendLoggedin(['Quote ID' => $quote->uid]));
            } else {
                $results['msg'] = "Failed to generate purchase order!";
                $this->logger->error('Failed to generate quote\'s purchase order! Couldn\'t convert Docx to PDF.', Helper::AppendLoggedin(['Quote ID' => $quote->uid]));
            }
        } else {
            $results['msg'] = "Failed to generate purchase order!";
            $this->logger->error('Failed to generate quote\'s purchase order! Couldn\'t generate Docx file.', Helper::AppendLoggedin(['Quote ID' => $quote->uid]));
        }

        die(json_encode($results));
    }

    public function Quote_regenerate_purchase_orderAction($id, $quote_id)
    {
        $purchase_order = new Quotes_purchase_ordersModel();
        $purchase_order->id = $id;
        if ($purchase_order->Delete()) {
            Redirect::To('pos/quote_order_parts/'.$quote_id);
        } else {
            Helper::SetFeedback('error', "Failed to reset purchase order!");
            Redirect::To('pos/quote/'.$quote_id.'#tabs-menu-po-link');
        }
    }

    public function Quote_purchase_order_email_actionAction($quote_id)
    {
        $purchase_order = Quotes_purchase_ordersModel::getAll("WHERE quote_id = '$quote_id'", true);

        if ($purchase_order) {
            $to_email = FilterInput::Email(Request::Post('to_email'));
            $to_name = FilterInput::String(Request::Post('to_name'));
            $to_subject = FilterInput::String(Request::Post('to_subject'));
            $message = FilterInput::String(Request::Post('message'));

            if (file_exists(QUOTES_PO_PATH.$purchase_order->purchase_order.'.pdf')) {
                $mail = new MailModel();
                $mail->from_email = CONTACT_EMAIL;
                $mail->from_name = CONTACT_NAME;
                $mail->to_email = $to_email;
                $mail->to_name = $to_name;
                $mail->subject = $to_subject;
                $mail->message = Helper::GenerateTemplate('quote-purchase-order-email-template', ['MESSAGE' => $message]);
                $mail->attachment = [QUOTES_PO_PATH.$purchase_order->purchase_order.'.pdf'];

                if ($mail->Send()) {
                    $this->logger->info("Purchase order email was sent successfully.", Helper::AppendLoggedin(['Quote ID' => $quote_id]));
                    Helper::SetFeedback('success', "Purchase order email was sent successfully.");

                    $update_purchase_order = new Quotes_purchase_ordersModel();
                    $update_purchase_order->id = $purchase_order->id;
                    $update_purchase_order->purchase_order_status = 'sent';
                    if ($update_purchase_order->Save()) {
                        $update_quote = new QuotesModel();
                        $update_quote->id = $quote_id;
                        $update_quote->status = 'parts-ordered';
                        if (!$update_quote->Save()) {
                            $this->logger->error("Failed to update quote's status to 'parts-ordered'", Helper::AppendLoggedin(['Quote ID' => $quote_id]));
                            Helper::SetFeedback('success', "Purchase order email was sent successfully. but some update errors happened, check logs.");
                        }
                    } else {
                        $this->logger->error("Failed to update quote's purchase order's status to 'sent'", Helper::AppendLoggedin(['Quote ID' => $quote_id]));
                        Helper::SetFeedback('success', "Purchase order email was sent successfully. but some update errors happened, check logs.");
                    }
                } else {
                    $this->logger->error('Failed to send purchase order email!', Helper::AppendLoggedin(['Quote ID' => $quote_id]));
                    Helper::SetFeedback('error', "Failed to send purchase order email!");
                }
            }
        } else {
            Redirect::To('pos/quote_order_parts/'.$quote_id);
        }

        Redirect::To('pos/quote/'.$quote_id.'#tabs-menu-po-link');
    }

    public function Quote_create_jobAction($id)
    {
        $quote = new QuotesModel();
        $quote->id = $id;
        $quote->status = 'job';
        $quote->updated = date('Y-m-d H:i:s');
        if ($quote->Save()) {
            $this->logger->info("Quote was converted to job successfully.", Helper::AppendLoggedin(['Quote ID' => $id]));
            Helper::SetFeedback('success', "Job was created for technician successfully.");
        } else {
            Helper::SetFeedback('error', "Failed to create quote job.");
        }
        Redirect::To('admin/quote_job/'.$id);
    }



/* Invoices */
    public function InvoicesAction()
    {
        $status = Request::Check('status', 'get') ? Request::Get('status') : false;
        $where = $status ? "WHERE invoices.status = '$status'" : '';

        $this->RenderPos([
            'invoices' => (new InvoicesModel)->getAllInvoicesWithCustomer($where)->paginate()
        ]);
    }

    public function InvoiceAction($id)
    {
        $this->RenderPos([
            'invoice' => (new InvoicesModel)->getAllInvoicesWithCustomer()->fetchAll("WHERE invoices.id = '$id'", true),
            'invoice_lines' => (new Invoice_linesModel)->getInvoiceLineWithItem($id)->fetchAll()
        ]);
    }

    public function Invoice_receiptAction($id)
    {
        $invoice = (new InvoicesModel)->getAllInvoicesWithCustomer()->fetchAll("WHERE invoices.id = '$id'", true);
        $invoice_lines = (new Invoice_linesModel)->getInvoiceLineWithItem($id)->fetchAll();

        $variables = array();
        $variables['IMAGEPATH'] = EMAIL_IMAGES_DIR;
        $variables['CUSTOMER_NAME'] = $invoice->firstName.' '.$invoice->lastName;
        $variables['ADDRESS'] = $invoice->address.'<br>'.$invoice->suburb.' '.$invoice->zip;
        $variables['INVOICE_NUMBER'] = $invoice->id;
        $variables['INVOICE_REFERENCE'] = $invoice->reference;
        $variables['DATE'] = (new \DateTime($invoice->created))->format('D, d M Y');

        if ($invoice_lines) {
            $lines = '';
            foreach ($invoice_lines as $invoice_line) {
                $lines .= '<tr class="ods-line-item--small-screen-divider">
                                <td class="xui-text-align-left">'.$invoice_line->product_name.'</td>
                                <td class="xui-text-align-right ods-hide-when-small">'.$invoice_line->quantity.'</td>
                                <td class="xui-text-align-right ods-hide-when-small">'.number_format($invoice_line->unit_price, 2).'</td>
                                <td class="xui-text-align-right ods-hide-when-small">'.number_format($invoice_line->discount, 2).'</td>
                                <td class="xui-text-align-right ods-hide-when-small">'.($invoice_line->rate ? : '0').'%</td>
                                <td class="xui-text-align-right"><strong>'.number_format($invoice_line->total, 2).'</strong></td>
                            </tr>
                            <tr class="ods-hide-when-large">
                                <td colspan="2" class="ods-line-item--small-screen">'.$invoice_line->quantity.' x '.number_format($invoice_line->unit_price, 2).'</td>
                            </tr>
                            <tr class="ods-hide-when-large">
                                <td colspan="2" class="ods-line-item--small-screen">Discount: '.number_format($invoice_line->discount, 2).'</td>
                            </tr>
                            <tr class="ods-hide-when-large">
                                <td colspan="2" class="ods-line-item--small-screen">GST: '.($invoice_line->rate ? : '0').'%</td>
                            </tr>';
            }

            $variables['INVOICE_LINES'] = $lines;
        }

        $variables['SUBTOTAL'] = floatval($invoice->total) - floatval($invoice->tax);
        $variables['DISCOUNT'] = $invoice->discount > 0 ? '(includes a discount of '.number_format($invoice->discount, 2).')' : '';
        $variables['TAX'] = $invoice->tax;
        $variables['AMOUNT_DUE'] = number_format($invoice->amount_due, 2);

        echo Helper::GenerateTemplate('invoice-pdf-template', $variables);
    }

    public function testAction()
    {
        $accounts_update_results = ['successful' => ['ss', 'sd'], 'failed' => []];
        var_dump(empty($accounts_update_results['failed']));
    }
}