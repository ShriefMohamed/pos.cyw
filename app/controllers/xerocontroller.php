<?php


namespace Framework\controllers;


use Framework\Lib\AbstractController;
use Framework\lib\AbstractModel;
use Framework\lib\FilterInput;
use Framework\lib\Helper;
use Framework\lib\Redirect;
use Framework\Lib\Session;
use Framework\lib\storageclass;
use Framework\models\CustomersModel;
use Framework\models\Invoice_linesModel;
use Framework\models\Invoices_paymentsModel;
use Framework\models\InvoicesModel;
use Framework\models\pos\Item_xero_accountsModel;
use Framework\models\pos\ItemsModel;
use Framework\models\pos\Tax_classesModel;
use Framework\models\UsersModel;
use Framework\models\xero\Xero_accountsModel;

use Framework\models\xero\Xero_sync_logsModel;
use GuzzleHttp\Client;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use XeroAPI\XeroPHP\Api\IdentityApi;
use XeroAPI\XeroPHP\Configuration;
use XeroAPI\XeroPHP\Api\AccountingApi;


class XeroController extends AbstractController
{
    public function DefaultAction()
    {
        // check if there's a cookie for sync request but redirected to auth first!
        if (Session::CookieExists('xero_sync_request')) {
            Redirect::To('xero/sync/'.Session::GetCookie('xero_sync_request'));
        }

        $this->RenderPos();
    }


    public function CustomersAction()
    {
        $this->RenderPos([
            'data' => (new CustomersModel())->getCustomersSimple("&& !ISNULL(customers.xero_ContactID)")->paginate()
        ]);
    }


    public function ItemsAction()
    {
        $this->RenderPos([
            'data' => (new ItemsModel)->getItemsAvgPrice("WHERE items.is_misc != 1 && !ISNULL(items.xero_ItemID) GROUP BY items.id")->paginate(),
        ]);
    }

    public function InvoicesAction()
    {
        $this->RenderPos([
            'invoices' => (new InvoicesModel)->getAllInvoicesWithCustomer()->paginate()
        ]);
    }


    public function AccountsAction()
    {
        $this->RenderPos([
            'data' => (new Xero_accountsModel)->fetch()->paginate()
        ]);
    }










    public function SyncAction($type)
    {
        if ((new StorageClass())->getHasExpired()) {
            Session::SetCookie('xero_sync_request', $type, 5);
            Redirect::To('xero/authorization');
        }
        $this->XeroOps($type);
    }

    private function XeroOps($type)
    {
        $type = in_array($type, $this->xeroModel->sync_types) ? $type : 'all';


        $storage = new StorageClass();
        $xeroTenantId = (string)$storage->getSession()['tenant_id'];

        if ($storage->getHasExpired()) {
            $this->xeroModel->RefreshToken($storage, $xeroTenantId);
        }

        $config = Configuration::getDefaultConfiguration()->setAccessToken( (string)$storage->getSession()['token'] );
        $apiInstance = new AccountingApi(
            new Client(),
            $config
        );


        if ($type == 'customers' || $type == 'invoicess' || $type == 'all') {
            $log_sync_result = 'fail';
            $last_successful_customers_sync = Xero_sync_logsModel::getLastSuccessfulSync('customers');

            $customers_to_push_modified_since = $last_successful_customers_sync ? " && (customers.created > '$last_successful_customers_sync->created' || customers.updated > '$last_successful_customers_sync->created') " : '';
            $customers_to_push = (new CustomersModel())->getCustomersSimple("$customers_to_push_modified_since && (ISNULL(customers.xero_ContactID) || customers.xero_ContactID = '') && customers.status = 'active' && customers.source != 'xero'")->fetchAll();


            $contacts_pull_results = $contacts_push_results = ['successful' => [], 'failed' => []];


            // Get customers from xero and create/update db
            $is_next_page = true;
            $i = 1;
            while ($is_next_page) {
                $existing_customers = CustomersModel::getCustomerColumns("users.id, customers.id AS customer_id, customers.xero_ContactID, customers.companyName, CONCAT(users.firstName,' ', users.lastName) AS fullname, users.email", '1', 'assoc');
                $existing_customers = $existing_customers ?: [];

                try {
                    $if_modified_since = $last_successful_customers_sync ? new \DateTime($last_successful_customers_sync->created."+10:30") : '';
                    $where = 'isCustomer=true';
//                    $results = $apiInstance->getContacts($xeroTenantId, $if_modified_since, $where, null, null, null, null, null, $i);
                    $results = $apiInstance->getContacts($xeroTenantId, null, $where, null, null, null, null, null, null);

                    if (count($results) > 0) {
                        $i++;
                        $log_sync_result = 'success';

                        foreach ($results as $contact) {
                            // search current customers by contact_id and name, if exists in the system then update, else create.
                            $search_array_index = false;
                            if ($existing_customers) {
                                $array_search_id = array_search($contact['contact_id'], array_column($existing_customers, 'xero_ContactID'));
                                $array_search_name = array_search($contact['first_name'].' '.$contact['last_name'], array_column($existing_customers, 'fullname'));
                                $array_search_email = array_search($contact['email_address'], array_column($existing_customers, 'email'));
                                $array_search_companyName = array_search($contact['name'], array_column($existing_customers, 'companyName'));

                                $search_array_index = Helper::ReturnOnlyNonFalse([$array_search_id, $array_search_name, $array_search_email, $array_search_companyName]);
                            }

                            $user = new UsersModel();
                            if ($search_array_index !== false && isset($existing_customers[$search_array_index]['id'])) {
                                $user->id = $existing_customers[$search_array_index]['id'];
                            }
                            $user->role = 'customer';
                            $user->firstName = $contact['first_name'];
                            $user->lastName = $contact['last_name'];
                            $user->email = $contact['email_address'];

                            if (isset($contact['phones']) && !empty($contact['phones'])) {
                                $user->phone = isset($contact['phones'][0]['phone_number']) && $contact['phones'][0]['phone_number'] ?
                                    $contact['phones'][0]['phone_number'] :
                                    '';
                                $user->phone2 = isset($contact['phones'][1]['phone_number']) && $contact['phones'][1]['phone_number'] ?
                                    $contact['phones'][1]['phone_number'] :
                                    '';
                            }
                            $user->lastUpdate = date('Y-m-d H:i:s');


                            if ($user->Save()) {
                                array_push($contacts_pull_results['successful'], $contact['contact_id']);

                                $customer = new CustomersModel();
                                if ($search_array_index !== false && isset($existing_customers[$search_array_index]['customer_id'])) {
                                    $customer->id = $existing_customers[$search_array_index]['customer_id'];
                                    $customer->xero_ContactID = $existing_customers[$search_array_index]['xero_ContactID'];
                                } else {
                                    $customer->source = 'xero';
                                    $customer->xero_ContactID = $contact['contact_id'];
                                }

                                $customer->user_id = $user->id;
                                $customer->companyName = $contact['name'];
                                if (isset($contact['addresses']) && !empty($contact['addresses'])) {
                                    $customer->address = isset($contact['addresses'][0]['address_line1']) && $contact['addresses'][0]['address_line1'] ?
                                        $contact['addresses'][0]['address_line1'] :
                                        '';
                                    $customer->address2 = isset($contact['addresses'][0]['address_line2']) && $contact['addresses'][0]['address_line2'] ?
                                        $contact['addresses'][0]['address_line2'] :
                                        '';
                                    $customer->city = isset($contact['addresses'][0]['city']) && $contact['addresses'][0]['city'] ?
                                        $contact['addresses'][0]['city'] :
                                        '';
                                    $customer->suburb = isset($contact['addresses'][0]['region']) && $contact['addresses'][0]['region'] ?
                                        $contact['addresses'][0]['region'] :
                                        '';
                                    $customer->zip = isset($contact['addresses'][0]['postal_code']) && $contact['addresses'][0]['postal_code'] ?
                                        $contact['addresses'][0]['postal_code'] :
                                        '';
                                }

                                if (!$customer->Save()) {
                                    $this->logger->error("User was created/updated, but failed to create/update customer in the website. check database logs!", Helper::AppendLoggedin(['Customer' => $customer->companyName, 'Contact ID' => $customer->xero_ContactID]));
                                }
                            } else {
                                array_push($contacts_pull_results['failed'], $contact['contact_id']);
                            }
                        }
                    } else {
                        $is_next_page = false;
                    }
                } catch (\Exception $e) {
                    if ($e->getCode() == 429) {
                        Helper::SetFeedback('error',  "[429] Client error: Too Many Requests, API maxed out, Please try again later!");
                        Redirect::To('xero/customers');
                    }

                    $this->logger->error('Exception when calling AccountingApi->getContacts: '. $e->getMessage());
                }
            }



die();
            /*-----------------------------------------------------------*/


            // get customers from db and push to xero
            if ($customers_to_push) {
                $existing_customers = CustomersModel::getCustomerColumns("users.id, customers.id AS customer_id, customers.xero_ContactID, customers.companyName, CONCAT(users.firstName,' ', users.lastName) AS fullname, users.email", '1', 'assoc');
                $existing_customers = $existing_customers ?: [];

                if (count($customers_to_push) > 500) {
                    $customers_to_push_chunks = array_chunk($customers_to_push, 500);
                } else {
                    $customers_to_push_chunks[] = $customers_to_push;
                }

               foreach ($customers_to_push_chunks as $customers_to_push_chunk) {
                   $contacts = '{"Contacts": [';
                   $counter = 1;
                   foreach ($customers_to_push_chunk as $customer_to_push) {
                       $contact = '{';
                       $contact .= $customer_to_push->fullname ? '"Name": "' . FilterInput::CleanString($customer_to_push->firstName) . '",' : '';
                       $contact .= $customer_to_push->firstName ? '"FirstName": "' . FilterInput::CleanString($customer_to_push->firstName) . '",' : '';
                       $contact .= $customer_to_push->lastName ? '"LastName": "' . FilterInput::CleanString($customer_to_push->lastName) . '",' : '';
                       $contact .= FilterInput::Email($customer_to_push->email) ? '"EmailAddress": "' . FilterInput::Email($customer_to_push->email) . '",' : '';
                       if ($customer_to_push->address != '') {
                           $contact .= '"Addresses": [{"AddressType": "STREET","AddressLine1": "' . FilterInput::CleanString($customer_to_push->address) . '",';
                           if ($customer_to_push->address2 != '') {
                               $contact .= '"AddressLine2": "' . FilterInput::CleanString($customer_to_push->address2) . '",';
                           }

                           $contact .= $customer_to_push->city ? '"City": "' . FilterInput::CleanString($customer_to_push->city) . '",' : '';
                           $contact .= $customer_to_push->suburb ? '"Region": "' . FilterInput::CleanString($customer_to_push->suburb) . '",' : '';
                           $contact .= $customer_to_push->zip ? '"PostalCode": "' . FilterInput::CleanString($customer_to_push->zip) . '",' : '';
                           $contact .= '"AttentionTo": "' . FilterInput::CleanString($customer_to_push->fullname) . '"}],';
                       }

                       $contact .= '"Phones": [';
                       if ($customer_to_push->phone != '') {
                           $contact .= '{"PhoneType": "DEFAULT","PhoneNumber": "' . $customer_to_push->phone . '"}';
                           $contact .= $customer_to_push->phone2 != '' ? ',' : '';
                       }
                       if ($customer_to_push->phone2 != '') {
                           $contact .= '{"PhoneType": "MOBILE","PhoneNumber": "' . $customer_to_push->phone2 . '"}';
                       }
                       $contact .= '],';
                       $contact .= '"IsSupplier": false,"IsCustomer": true,"DefaultCurrency": "AUD"}';

                       if (json_decode($contact)) {
                           $contacts .= $contact . ($counter >= 1 && $counter < count($customers_to_push_chunk) ? ', ' : '');
                       }
                       $counter++;
                   }
                   $contacts .= '] }';

                   try {
                       $results = $apiInstance->updateOrCreateContacts($xeroTenantId, $contacts, true);
                       if (count($results) > 0) {
                           $log_sync_result = 'success';

                           foreach ($results as $result) {
                               $customer_contact_id = $result['contact_id'];
                               $search_customers_by_name = array_search($result['first_name'].' '.$result['last_name'], array_column($existing_customers, 'fullname'));

                               if ($customer_contact_id && $search_customers_by_name !== false) {
                                   $customer_update = new CustomersModel();
                                   $customer_update->id = $existing_customers[$search_customers_by_name]['customer_id'];
                                   $customer_update->xero_ContactID = $customer_contact_id;
                                   if ($customer_update->Save()) {
                                       array_push($contacts_push_results['successful'], $customer_contact_id);
                                   } else {
                                       array_push($contacts_push_results['failed'], $customer_contact_id);
                                   }
                               } else {
                                   array_push($contacts_push_results['failed'], $customer_contact_id);
                               }
                           }
                       }
                   } catch (\Exception $e) {
                       $log_sync_result = 'fail';

                       if ($e->getCode() == 429) {
                           Helper::SetFeedback('error',  "[429] Client error: Too Many Requests, API maxed out, Please try again later!");
                           Redirect::To('xero/customers');
                       }

                       $this->logger->error('Exception when calling AccountingApi->updateOrCreateContacts: '.$e->getMessage());
                   }
               }
            }



            // add final results' feedback and log detailed sync results for each operation
            if ($log_sync_result == 'success') {
                Helper::SetFeedback('success', 'Customers sync completed. Check the logs for status.');
            } else {
                Helper::SetFeedback('error', 'Exception while syncing customers. Check the logs for details.');
            }
            $this->logXeroSyncResults('Customers', 'pull', $contacts_pull_results);
            $this->logXeroSyncResults('Customers', 'push', $contacts_push_results);


            $log_sync = new Xero_sync_logsModel();
            $log_sync->type = 'customers';
            $log_sync->result = $log_sync_result;
            $log_sync->Save();
        }

        if ($type == 'accounts' || $type == 'all') {
            $existing_accounts = Xero_accountsModel::getColumns(['id', 'xero_AccountID']);
            $log_sync_result = 'fail';

            try {
                $last_successful_accounts_sync = Xero_sync_logsModel::getLastSuccessfulSync('accounts');
                $if_modified_since = $last_successful_accounts_sync ? new \DateTime($last_successful_accounts_sync->created."+10:30") : '';

                $results = $apiInstance->getAccounts($xeroTenantId, $if_modified_since);
                if (!empty($results)) {
                    $accounts_pull_results = ['successful' => [], 'failed' => []];
                    foreach ($results as $account) {
                        $array_search = $existing_accounts
                            ? array_search($account['account_id'], array_column($existing_accounts, 'xero_AccountID'))
                            : false;

                        $xero_account = new Xero_accountsModel();
                        if ($array_search !== false) {
                            $xero_account->id = $existing_accounts[$array_search]['id'];
                        }
                        $xero_account->xero_AccountID = $account['account_id'];
                        $xero_account->Code = $account['code'];
                        $xero_account->Name = $account['name'];
                        $xero_account->Type = $account['type'];
                        $xero_account->TaxType = $account['tax_type'];
                        if ($xero_account->Save()) {
                            array_push($accounts_pull_results['successful'], $xero_account->Code);
                        } else {
                            array_push($accounts_pull_results['failed'], $account['account_id']);
                        }
                    }

                    $log_sync_result = 'success';
                    $this->logXeroSyncResults('Accounts', 'pull', $accounts_pull_results);

                    Helper::SetFeedback('success', 'Accounts sync completed. Check the logs for status.');
                }
            } catch (\Exception $e) {
                $this->logger->error('Exception when calling AccountingApi->getAccount: '. $e->getMessage());
                Helper::SetFeedback('error', 'Exception when calling AccountingApi->getAccount: '. $e->getMessage());
            }

            $log_sync = new Xero_sync_logsModel();
            $log_sync->type = 'accounts';
            $log_sync->result = $log_sync_result;
            $log_sync->Save();
        }

        if ($type == 'items' || $type == 'all') {
            $log_sync_result = 'fail';
            $last_successful_items_sync = Xero_sync_logsModel::getLastSuccessfulSync('items');


            $existing_items = ItemsModel::getColumns(['id', 'shop_sku', 'xero_ItemID']);
            $existing_items = $existing_items ?: [];
            $items_to_push_modified_since = $last_successful_items_sync->created ? " && (items.created > '$last_successful_items_sync->created' || items.updated > '$last_successful_items_sync->created') && items.source != 'xero' " : '';
            $items_to_push = ItemsModel::getItemsForXeroPush(" $items_to_push_modified_since ");


            // empty arrays to hold the results
            $items_pull_results = $items_push_results = ['successful' => [], 'failed' => []];


            // get items from xero to update database
            for ($i = 1; $i <= 10; $i++) {
                try {
                    $if_modified_since = $last_successful_items_sync ? new \DateTime($last_successful_items_sync->created."+10:30") : '';
                    $results = $apiInstance->getItems($xeroTenantId, $if_modified_since, null, null, null, null, null, null, $i);

                    if (count($results) > 0) {
                        $log_sync_result = 'success';

                        foreach ($results as $result) {
                            // search current items by item_id and name, if exists in the system then update, else create.
                            $search_array_index = false;
                            if ($existing_items) {
                                $array_search_id = array_search($result['item_id'], array_column($existing_items, 'xero_ItemID'));
                                $array_search_code = array_search($result['code'], array_column($existing_items, 'shop_sku'));
                                $array_search_xeroID = array_search($result['item_id'], array_column($existing_items, 'xero_ItemID'));
                                if ($array_search_id !== false ||
                                    $array_search_code !== false ||
                                    $array_search_xeroID !== false
                                ) {
                                    if ($array_search_id !== false) {
                                        $search_array_index = $array_search_id;
                                    } else if ($array_search_code !== false) {
                                        $search_array_index = $array_search_code;
                                    } else if ($array_search_xeroID !== false) {
                                        $search_array_index = $array_search_xeroID;
                                    }
                                }
                            }

                            $item = new ItemsModel();
                            if ($search_array_index !== false && isset($existing_items[$search_array_index]['id'])) {
                                $item->id = $existing_items[$search_array_index]['id'];
                            } else {
                                $item->source = 'xero';
                            }
                            $item->xero_ItemID = $result['item_id'];
                            $item->item = $result['name'] ?: $result['code'];
                            $item->description = $result['description'];
                            $item->shop_sku = $result['code'] ? FilterInput::CleanString($result['code']) : '';
                            $item->is_tracked_as_inventory = $result['is_tracked_as_inventory'] == true ? 1 : 2;

                            $item->buy_price = $result['purchase_details']['unit_price'] ?
                                FilterInput::Float($result['purchase_details']['unit_price']) :
                                0;
                            $item->rrp_price = $result['sales_details']['unit_price'] ?
                                FilterInput::Float($result['sales_details']['unit_price']) :
                                0;
                            if ($item->buy_price > 0 && $item->rrp_price > 0) {
                                $item->rrp_percentage = substr(((($item->rrp_price - $item->buy_price) / $item->buy_price) * 100), 0, 3);
                            }


                            $gst_tax = Tax_classesModel::getColumns(['id'], "class LIKE '%GST%'", true);
                            if ($gst_tax) {
                                $item->tax_class = $gst_tax;
                            }


                            $uid = substr($item->item, 0, 3);
                            $uq_number = ItemsModel::generateUniqueNumber();
                            $item->uid = strtoupper($uid) . $uq_number;

                            $upc_code = 425667 . sprintf("%05d", ItemsModel::NextID());
                            $item->upc = $upc_code . Helper::CalculateUpcCheckDigit($upc_code);

                            $item->updated = date('Y-m-d H:i:s');
                            if ($item->Save()) {
                                array_push($items_pull_results['successful'], $result['code']);

                                // get the formatted database item ID
                                $item_id = ItemsModel::getColumns(['id'], "id = '$item->id'", true);


                                // update item keywords
                                $get_item_keywords = ItemsModel::getItemKeywords($item_id);
                                $get_item_keywords = $get_item_keywords ? array_filter(array_shift($get_item_keywords), function($value) {return !is_null($value) && $value !== '';}) : [];
                                if ($get_item_keywords) {
                                    $item_keywords = new ItemsModel();
                                    $item_keywords->id = $item_id;
                                    $item_keywords->search_keywords = json_encode($get_item_keywords);
                                    $item_keywords->Save();
                                }


                                // create xero accounts record
                                $item_xero_accounts = new Item_xero_accountsModel();
                                $item_xero_accounts->item_id = $item_id;
                                $item_xero_accounts->item_uid = $item->uid;
                                if ($result['inventory_asset_account_code']) {
                                    $item_xero_accounts->inventory_asset_xero_account_code = $result['inventory_asset_account_code'];
                                    if ($item_xero_accounts->inventory_asset_xero_account_code) {
                                        $item_xero_accounts->inventory_asset_xero_account_id = Xero_accountsModel::getColumns(['id'], "Code = '$item_xero_accounts->inventory_asset_xero_account_code'", true);
                                    }
                                }
                                if ($result['purchase_details']) {
                                    if ($result['purchase_details']['cogs_account_code'] || $result['purchase_details']['account_code']) {
                                        $item_xero_accounts->purchase_xero_account_code = $result['purchase_details']['cogs_account_code'] ?: $result['purchase_details']['account_code'];
                                        if ($item_xero_accounts->purchase_xero_account_code) {
                                            $item_xero_accounts->purchase_xero_account_id = Xero_accountsModel::getColumns(['id'], "Code = '$item_xero_accounts->purchase_xero_account_code'", true);
                                        }
                                    }
                                }
                                if ($result['sales_details']) {
                                    $item_xero_accounts->sales_xero_account_code = $result['sales_details']['account_code'];
                                    if ($item_xero_accounts->sales_xero_account_code) {
                                        $item_xero_accounts->sales_xero_account_id = Xero_accountsModel::getColumns(['id'], "Code = '$item_xero_accounts->sales_xero_account_code'", true);
                                    }
                                }
                                $item_xero_accounts->Save();
                            } else {
                                array_push($items_pull_results['failed'], $result['code']);
                            }
                        }
                    } else {
                        break;
                    }
                } catch (\Exception $e) {
                    if ($e->getCode() == 429) {
                        Helper::SetFeedback('error',  "[429] Client error: Too Many Requests, API maxed out, Please try again later!");
                        Redirect::To('xero/items');
                    }

                    $this->logger->error('Exception when calling AccountingApi->getItems: '. $e->getMessage());
                }
            }


            /*-----------------------------------------------------------*/


            // get items that got created/updated since last sync, to create them at xero or update,
            if ($items_to_push) {
                if (count($items_to_push) > 500) {
                    $items_to_push_chunks = array_chunk($items_to_push, 500);
                } else {
                    $items_to_push_chunks[] = $items_to_push;
                }

                foreach ($items_to_push_chunks as $items_to_push_chunk) {
                    $items = '{ "Items": [';
                    $counter = 1;
                    foreach ($items_to_push_chunk as $item_to_push) {
                        $item = '{ ';
                        $item .= '"Code": "'.FilterInput::CleanString($item_to_push->shop_sku).'",';
                        $item .= '"Name": "'.FilterInput::CleanString($item_to_push->item).'",';
                        $item .= $item_to_push->description ? '"Description": "'.FilterInput::CleanString($item_to_push->description).'",' : '';
                        $item .= '"IsTrackedAsInventory": '.($item_to_push->is_tracked_as_inventory == 1 ? 'true' : 'false').',';
                        $item .= $item_to_push->inventory_asset_xero_account_code ? '"InventoryAssetAccountCode": "'.$item_to_push->inventory_asset_xero_account_code.'",' : '';
                        $item .= $item_to_push->quantity_on_hand ? '"QuantityOnHand": '.FilterInput::Int($item_to_push->quantity_on_hand).',' : '';

                        if ($item_to_push->buy_price) {
                            $item .= '"PurchaseDetails": {
                            "UnitPrice": '.number_format(FilterInput::Float($item_to_push->buy_price), 2).',
                            "COGSAccountCode": "'.$item_to_push->purchase_xero_account_code.'",
                            "TaxType": "INPUT"
                            },';
                        }
                        if ($item_to_push->rrp_price) {
                            $item .= '"SalesDetails": {
                            "UnitPrice": '.number_format(FilterInput::Float($item_to_push->rrp_price), 2).',
                            "AccountCode": "'.$item_to_push->sales_xero_account_code.'",
                            "TaxType": "OUTPUT"
                          },';
                        }

                        $item .= '"IsSold": true, "IsPurchased": true }';

                        if (json_decode($item)) {
                            $items .= $item . ($counter >= 1 && $counter < count($items_to_push_chunk) ? ', ' : '');
                        }
                        $counter++;
                    }
                    $items .= '] }';

                    try {
                        $results = $apiInstance->updateOrCreateItems($xeroTenantId, $items, true);
                        if (count($results) > 0) {
                            $log_sync_result = 'success';

                            foreach ($results as $result) {
                                $item_xero_id = $result['item_id'];
                                $array_search_by_code = array_search(FilterInput::CleanString($result['code']), array_column($existing_items, 'shop_sku'));

                                if ($item_xero_id && $array_search_by_code !== false) {
                                    $item_update = new ItemsModel();
                                    $item_update->id = $existing_items[$array_search_by_code]['id'];
                                    $item_update->xero_ItemID = $item_xero_id;
                                    $item_update->updated = date('Y-m-d H:i:s');
                                    if ($item_update->Save()) {
                                        array_push($items_push_results['successful'], $item_update->xero_ItemID);
                                    } else {
                                        array_push($items_push_results['failed'], $item_update->id);
                                    }
                                } else {
                                    array_push($items_push_results['failed'], $item_xero_id);
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        $log_sync_result = 'fail';
                        if ($e->getCode() == 429) {
                            Helper::SetFeedback('error',  "[429] Client error: Too Many Requests, API maxed out, Please try again later!");
                            Redirect::To('xero/items');
                        }
                        $this->logger->error('Exception when calling AccountingApi->updateOrCreateItems: '.$e->getMessage());
                    }
                }
            }



            // add final results feedback and log detailed sync results for each operation
            if ($log_sync_result == 'success') {
                Helper::SetFeedback('success', 'Items sync completed. Check the logs for results.');
            } else {
                Helper::SetFeedback('error', 'Exception while syncing items! Check the logs for details!');
            }
            $this->logXeroSyncResults('Items', 'pull', $items_pull_results);
            $this->logXeroSyncResults('Items', 'push', $items_push_results);


            $log_sync = new Xero_sync_logsModel();
            $log_sync->type = 'items';
            $log_sync->result = $log_sync_result;
            $log_sync->Save();
        }

        if ($type == 'invoices' || $type == 'all') {
            $log_sync_result = 'fail';
            $last_successful_invoices_sync = Xero_sync_logsModel::getLastSuccessfulSync('invoices');


            $existing_customers = CustomersModel::getCustomerColumns("customers.id AS customer_id, customers.xero_ContactID", '1', 'assoc');
            $existing_customers = $existing_customers ?: [];

            $invoices_to_push_modified_since = $last_successful_invoices_sync ? " (invoices.created > '$last_successful_invoices_sync->created' || invoices.updated > '$last_successful_invoices_sync->created') && " : '';
            $invoices_to_push = (new InvoicesModel())->getAllInvoicesWithCustomer("WHERE $invoices_to_push_modified_since (ISNULL(invoices.xero_InvoiceID) || invoices.xero_InvoiceID = '') && invoices.source != 'xero' && invoices.status != 'voided'")->fetchAll();

            // empty arrays to hold the results
            $invoices_pull_results = $invoices_push_results = $payments_push_results =['successful' => [], 'failed' => []];




            // Get invoices from xero and create/update db
            $if_modified_since = $last_successful_invoices_sync ? new \DateTime($last_successful_invoices_sync->created."+10:30") : '';
            for ($i = 1; $i <= 10; $i++) {
                $existing_invoices = InvoicesModel::getColumns(['id', 'reference']);
                $existing_invoices = $existing_invoices ?: [];

                try {
                    $results = $apiInstance->getInvoices($xeroTenantId, $if_modified_since, null, null, null, null, null, null, $i);
                    if (count($results) > 0) {
                        $log_sync_result = 'success';

                        foreach ($results as $result) {
                            // search current invoices by reference, if exists in the system then update, else create.
                            $inv_xeroID = $result['invoice_id'];
                            $inv_number = $result['invoice_number'];
                            $array_search_by_reference = array_search(FilterInput::CleanInt($result['reference']), array_column($existing_invoices, 'reference'));

                            $invoice = new InvoicesModel();
                            $invoice->source = 'xero';
                            if ($existing_invoices && $array_search_by_reference !== false) {
                                $invoice->id = $existing_invoices[$array_search_by_reference]['id'];
                                $invoice->updated = date('Y-m-d H:i:s');
                            } else {
                                $invoice->reference = $result['reference']
                                    ? FilterInput::CleanString($result['reference'])
                                    : InvoicesModel::NextID("LPAD(MAX(auto_increment),5,'0')");
                            }

                            $invoice->xero_InvoiceID = $inv_xeroID;
                            $invoice->xero_InvoiceNumber = $inv_number;
                            $invoice->subtotal = $result['sub_total'];
                            $invoice->tax = $result['total_tax'];
                            $invoice->total = $result['total'];
                            $invoice->amount_due = $result['amount_due'];
                            $invoice->amount_paid = $result['amount_paid'];
                            $invoice->status = $invoice->amount_due > 0 ? $invoice->amount_paid > 0 ? 'semi-paid' : 'unpaid' : 'paid';

                            // test later to check it you need to create the whole user-customer or previous customers sync is enough
                            $invoice->xero_ContactID = $result['contact']['contact_id'];

                            $array_search_customers_by_contactID = array_search($result['contact']['contact_id'], array_column($existing_customers, 'xero_ContactID'));
                            if ($existing_customers && $array_search_customers_by_contactID !== false) {
                                $invoice->customer_id = $existing_customers[$array_search_customers_by_contactID]['customer_id'];
                            }

                            if ($invoice->Save()) {
                                if (isset($result['line_items']) && !empty($result['line_items'])) {
                                    foreach ($result['line_items'] as $line_item) {
                                        $invoice_line = new Invoice_linesModel();
                                        $invoice_line->invoice_id = $invoice->id;
                                        $invoice_line->product_name = FilterInput::CleanString($line_item['description']);
                                        if ($array_search_by_reference !== false) {
                                            $check_invoice_line = Invoice_linesModel::getColumns(['id'], "invoice_id = '$invoice->id' && product_name LIKE '%".FilterInput::CleanString($invoice_line->product_name)."%'", true);
                                            if ($check_invoice_line) {
                                                $invoice_line->id = $check_invoice_line;
                                            }
                                        }
                                        $invoice_line->quantity = $line_item['quantity'];
                                        $invoice_line->unit_price = $line_item['unit_amount'];
                                        $invoice_line->discount = $line_item['discount_amount'];
                                        $invoice_line->tax = $line_item['tax_amount'];
                                        $invoice_line->total = $line_item['line_amount'];
                                        if (!$invoice_line->Save()) {
                                            $this->logger->error("Invoice was created/updated, but failed to create/update invoice line in the database. check database logs!", Helper::AppendLoggedin(['Invoice' => $invoice->reference, 'Line Item' => $invoice_line->product_name]));
                                        }
                                    }
                                }
                            } else {
                                array_push($invoices_pull_results['failed'], $inv_number);
                            }
                        }
                    } else {
                        break;
                    }
                } catch (\Exception $e) {
                    if ($e->getCode() == 429) {
                        Helper::SetFeedback('error', "[429] Client error: Too Many Requests, API maxed out, Please try again later!");
                        Redirect::To('xero/invoices');
                    }

                    $this->logger->error('Exception when calling AccountingApi->getInvoices: ' . $e->getMessage());
                }
            }


            /*-----------------------------------------------------------*/


            // get invoices from db and push to xero
            if ($invoices_to_push) {
                $existing_invoices = InvoicesModel::getColumns(['id', 'reference']);
                $existing_invoices = $existing_invoices ?: [];

                if (count($invoices_to_push) > 500) {
                    $invoices_to_push_chunks = array_chunk($invoices_to_push, 500);
                } else {
                    $invoices_to_push_chunks[] = $invoices_to_push;
                }

                foreach ($invoices_to_push_chunks as $invoices_to_push_chunk) {
                    $invoices = '{"Invoices": [ ';
                    $counter = 1;
                    foreach ($invoices_to_push_chunk as $invoice_to_push) {
                        $invoice_to_push_lines = (new Invoice_linesModel)->getInvoiceLineWithItem($invoice_to_push->id)->fetchAll();

                        $invoice ='{';
                        $invoice .= '"Reference": "POS-' . $invoice_to_push->reference . '",';

                        if ($invoice_to_push->total < 0) {
                            $invoice .= '"Type": "ACCPAY",';
                        } else {
                            $invoice .= '"Type": "ACCREC",';
                        }

                        $contact_name = !$invoice_to_push->firstName && !$invoice_to_push->lastName
                            ? "Cash Sales"
                            : FilterInput::CleanString($invoice_to_push->firstName).' '.FilterInput::CleanString($invoice_to_push->lastName);

                        $invoice .= '"Contact": {';
                        $invoice .= $invoice_to_push->xero_ContactID
                            ? '"ContactID": "' . $invoice_to_push->xero_ContactID . '",'
                            : '';
                        $invoice .= '"Name": "' . $contact_name . '"}, ';

                        $invoice .= '"DateString": "' . date('Y-m-d\TH:i:s', strtotime($invoice_to_push->created)) . '",
                                "DueDateString": "' . date('Y-m-d\TH:i:s', strtotime($invoice_to_push->created)) . '",';

                        $invoice .= '"LineAmountTypes": "Inclusive",
                                "Status": "AUTHORISED",
                                "CurrencyCode": "AUD",';
                        $invoice .= '"SubTotal": "' . (floatval($invoice_to_push->subtotal) - floatval($invoice_to_push->tax)) . '",';
                        $invoice .= '"Total": "' . FilterInput::Float($invoice_to_push->total) . '",';

                        if (isset($invoice_to_push->discount) && $invoice_to_push->discount) {
                            $invoice .= '"TotalDiscount":"'.FilterInput::Float($invoice_to_push->discount).'",';
                        }


                        if ($invoice_to_push_lines) {
                            $invoice .= '"LineItems": [';

                            $items_counter = 1;
                            foreach ($invoice_to_push_lines as $invoice_to_push_line) {
                                $item = '{';
                                $item .= '"Description": "' . FilterInput::CleanString($invoice_to_push_line->product_name) . '",';
                                $item .= '"Quantity": "' . FilterInput::Int($invoice_to_push_line->quantity) . '",';
                                $item .= '"UnitAmount": "' . FilterInput::Float($invoice_to_push_line->unit_price) . '",';
//                                $item .= '"ItemCode": "' . (FilterInput::CleanString($invoice_to_push_line->shop_sku) ?: $invoice_to_push_line->product_id) . '", ';
                                $item .= '"AccountCode": "'. (FilterInput::Int($invoice_to_push_line->sales_xero_account_code) ?: '210') .'", ';
                                $item .= '"TaxType": "OUTPUT", ';
                                $item .= '"TaxAmount": "'. FilterInput::Float($invoice_to_push_line->tax) .'"';
                                $item .= '}';
                                if (json_decode($item)) {
                                    $invoice .= $item . ($items_counter >= 1 && $items_counter < count($invoice_to_push_lines) ? ', ' : '');
                                }
                                $items_counter++;
                            }

                            $invoice .= ']';
                        }
                        $invoice .= '}';


                        if (json_decode($invoice)) {
                            $invoices .= $invoice . ($counter >= 1 && $counter < count($invoices_to_push_chunk) ? ', ' : '');
                        }


                        $counter++;
                    }

                    $invoices .= ']}';


                    try {
                        $results = $apiInstance->updateOrCreateInvoices($xeroTenantId, $invoices, true);
                        if (count($results) > 0) {
                            $log_sync_result = 'success';

                            foreach ($results as $result) {
                                $inv_xeroID = $result['invoice_id'];
                                $inv_number = $result['invoice_number'];
                                $array_search_by_reference = array_search(FilterInput::CleanInt($result['reference']), array_column($existing_invoices, 'reference'));
                                if ($array_search_by_reference !== false && $inv_xeroID) {
                                    $invoice_update = new InvoicesModel();
                                    $invoice_update->id = $existing_invoices[$array_search_by_reference]['id'];
                                    $invoice_update->xero_InvoiceID = $inv_xeroID;
                                    $invoice_update->xero_InvoiceNumber = $inv_number;
                                    if ($invoice_update->Save()) {
                                        array_push($invoices_push_results['successful'], $inv_xeroID);
                                    } else {
                                        array_push($invoices_push_results['failed'], $inv_xeroID);
                                    }
                                } else {
                                    array_push($invoices_push_results['failed'], $inv_xeroID);
                                }
                            }
                        }
                    } catch (\Exception $e) {
//                        var_dump($e->getMessage());
                        $log_sync_result = 'fail';
                        if ($e->getCode() == 429) {
                            Helper::SetFeedback('error',  "[429] Client error: Too Many Requests, API maxed out, Please try again later!");
                            Redirect::To('xero/invoices');
                        }

                        $this->logger->error('Exception when calling AccountingApi->updateOrCreateInvoices: '.$e->getMessage());
                    }


                    sleep(3);
                }
            }



            /*-----------------------------------------------------------*/


            // get pushed invoices' payments to send to xero
            if ($invoices_to_push) {
                $invoices_to_push_ids = implode(', ', array_column($invoices_to_push, 'id'));
                $payments_to_push = (new Invoices_paymentsModel())->getInvoicesPayments("WHERE invoice_id IN ($invoices_to_push_ids) && !ISNULL(invoices.xero_InvoiceID) && invoices.xero_InvoiceID != '' && ISNULL(invoices_payments.xero_PaymentID) ")->fetchAll();
                $existing_payments = Invoices_paymentsModel::getExistingPaymentsColumns_byInvIDs($invoices_to_push_ids);
                $existing_payments = $existing_payments ?: [];

                if (count($payments_to_push) > 500) {
                    $payments_to_push_chunks = array_chunk($payments_to_push, 500);
                } else {
                    $payments_to_push_chunks[] = $payments_to_push;
                }

                foreach ($payments_to_push_chunks as $payments_to_push_chunk) {
                    $payments = '{"Payments": [';
                    $counter = 1;
                    foreach ($payments_to_push_chunk as $payment_to_push) {
                        if ($payment_to_push->amount > 0) {
                            $payment =
                                '{
                              "Invoice": { "InvoiceID": "'.$payment_to_push->xero_InvoiceID.'" },
                              "Account": { "Code": "120" },
                              "Date": "'.$payment_to_push->created.'",
                              "Status": "AUTHORISED",
                              "Amount": '.floatval($payment_to_push->amount).'
                             }';

                            if (json_decode($payment)) {
                                $payments .= $payment . ($counter >= 1 && $counter < count($payments_to_push_chunk) ? ', ' : '');
                            }
                            $counter++;
                        }
                    }
                    $payments .= ']}';


                    try {
                        $results = $apiInstance->createPayment($xeroTenantId, $payments, true);
                        if (count($results) > 0) {
                            foreach ($results as $result) {
                                $payment_xeroID = $result['payment_id'];
                                $invoice_xeroID = $result['invoice']['invoice_id'];

                                $array_search_by_inv_id = array_search($invoice_xeroID, array_column($existing_payments, 'xero_InvoiceID'));
                                if ($array_search_by_inv_id !== false && $payment_xeroID) {
                                    $payment_update = new Invoices_paymentsModel();
                                    $payment_update->id = $existing_payments[$array_search_by_inv_id]['id'];
                                    $payment_update->xero_PaymentID = $payment_xeroID;
                                    if ($payment_update->Save()) {
                                        array_push($payments_push_results['successful'], $invoice_xeroID);
                                    } else {
                                        array_push($payments_push_results['failed'], $invoice_xeroID);
                                    }
                                } else {
                                    array_push($payments_push_results['failed'], $invoice_xeroID);
                                }

                            }
                        }
                    } catch (\Exception $e) {
//                        var_dump($e->getMessage());
                        if ($e->getCode() == 429) {
                            Helper::SetFeedback('error',  "[429] Client error: Too Many Requests, API maxed out, Please try again later!");
                            Redirect::To('xero/invoices');
                        }

                        $this->logger->error('Exception when calling AccountingApi->createPayments: '.$e->getMessage());
                    }
                }
            }




            // add final results feedback and log detailed sync results for each operation
            if ($log_sync_result == 'success') {
                Helper::SetFeedback('success', 'Invoices sync completed. Check the logs for status.');
            } else {
                Helper::SetFeedback('error', 'Exception while syncing invoices! Check the logs for details.');
            }
            $this->logXeroSyncResults('Invoices', 'pull', $invoices_pull_results);
            $this->logXeroSyncResults('Invoices', 'push', $invoices_push_results);
            $this->logXeroSyncResults('Invoices', 'push', $payments_push_results);


            $log_sync = new Xero_sync_logsModel();
            $log_sync->type = 'invoices';
            $log_sync->result = $log_sync_result;
            $log_sync->Save();
        }
    }







    // Authentication
    public function AuthorizationAction()
    {
        $provider = $this->xeroModel->Provider();

        // Scope defines the data your app has permission to access.
        // Learn more about scopes at https://developer.xero.com/documentation/oauth2/scopes
        $options = [
            'scope' => ['openid email profile offline_access accounting.settings accounting.transactions accounting.contacts accounting.journals.read accounting.reports.read accounting.attachments']
        ];

        // This returns the authorizeUrl with necessary parameters applied (e.g. state).
        $authorizationUrl = $provider->getAuthorizationUrl($options);

        // Save the state generated for you and store it to the session.
        // For security, on callback we compare the saved state with the one returned to ensure they match.
        $_SESSION['oauth2state'] = $provider->getState();

        // Redirect the user to the authorization URL.
        header('Location: ' . $authorizationUrl);
        exit();
    }

    public function CallbackAction()
    {
        // Storage Class uses sessions for storing token > extend to your DB of choice
        $storage = new StorageClass();
        $provider = $this->xeroModel->Provider();

        // If we don't have an authorization code then get one
        if (!isset($_GET['code'])) {
            echo "Something went wrong, no authorization code found";
            exit("Something went wrong, no authorization code found");

            // Check given state against previously stored one to mitigate CSRF attack
        } elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
            echo "Invalid State";
            unset($_SESSION['oauth2state']);
            exit('Invalid state');
        } else {
            try {
                // Try to get an access token using the authorization code grant.
                $accessToken = $provider->getAccessToken('authorization_code', [
                    'code' => $_GET['code']
                ]);

                $config = Configuration::getDefaultConfiguration()->setAccessToken( (string)$accessToken->getToken() );
                $identityApi = new IdentityApi(
                    new Client(),
                    $config
                );

                $result = $identityApi->getConnections();

                // Save my tokens, expiration tenant_id
                $storage->setToken(
                    $accessToken->getToken(),
                    $accessToken->getExpires(),
                    $result[0]->getTenantId(),
                    $accessToken->getRefreshToken(),
                    $accessToken->getValues()["id_token"]
                );

                header('Location: '. HOST_NAME .'xero');
                exit();
            } catch (IdentityProviderException $e) {
                echo "Callback failed";
                exit();
            }
        }
    }
    // Authentication

    // Logging
    public function logXeroSyncResults($name, $type, $results)
    {
        if ($type == 'pull') {
            if (!empty($results['successful'])) {
                $this->logger->info("Xero $name were created/updated successfully.", Helper::AppendLoggedin(["$name (Xero Code)" => json_encode($results['successful'])]));
            }
            if (!empty($results['failed'])) {
                $this->logger->error("Failed to create/update xero $name!", Helper::AppendLoggedin(["Items ($name Code)" => json_encode($results['failed'])]));
            }
        }
        if ($type == 'update') {
            if (!empty($results['successful'])) {
                $this->logger->info("$name were found at Xero. Updated xeroID in our the database successfully.", ["$name (Xero Code)" => json_encode($results['successful'])]);
            }
            if (!empty($results['failed'])) {
                $this->logger->error("$name were found at Xero. failed to update xeroID in our the database", ["$name (Xero Code)" => json_encode($results['failed'])]);
            }
        }
        if ($type == 'push') {
            if (!empty($results['successful'])) {
                $this->logger->info("$name were pushed to Xero successfully.", ["$name (Xero Code)" => json_encode($results['successful'])]);
            }
            if (!empty($results['failed'])) {
                $this->logger->error("Failed to push $name to Xero.", ["$name (Xero Code)" => json_encode($results['failed'])]);
            }
        }

    }
}