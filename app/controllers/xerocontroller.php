<?php


namespace Framework\controllers;


use Framework\Lib\AbstractController;
use Framework\lib\FilterInput;
use Framework\lib\Helper;
use Framework\lib\Redirect;
use Framework\Lib\Session;
use Framework\lib\storageclass;
use Framework\models\CustomersModel;
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


    // products !!

    public function CustomersAction()
    {
        $this->RenderPos([
            'data' => CustomersModel::getCustomersSimple("&& !ISNULL(customers.ContactID)")
        ]);
    }

    public function AccountsAction()
    {
        $this->RenderPos([
            'data' => Xero_accountsModel::getAll()
        ]);
    }

    public function InvoicesAction()
    {

    }


    public function SyncAction($type)
    {
        if (!isset($_SESSION['oauth2'])) {
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

        $where = 'isCustomer=true,';
        $result = $apiInstance->getContacts($xeroTenantId, '', $where, '', '', 1);
print_r($result);
        die();

        if ($type == 'customers' || $type == 'all') {
            // get customers from db and push to xero
            $customers_to_push = CustomersModel::getCustomersSimple("&& ISNULL(customers.ContactID) && customers.status = 'active'");
            if ($customers_to_push) {
                foreach ($customers_to_push as $customer_to_push) {

                    $where = 'Name.Contains("' . $customer_to_push->fullName . '") OR Name.Contains("' . $customer_to_push->companyName . '")';
                    try {
                        $result = $apiInstance->getContacts($xeroTenantId, '', $where);
                        if ($result && !empty($result['contacts'])) {
                            $customer_contact_id = $result['contacts'][0]['contact_id'];
                            $customer_update = new CustomersModel();
                            $customer_update->id = $customer_to_push->customer_id;
                            $customer_update->ContactID = $customer_contact_id;
                            if ($customer_update->Save()) {
                                $user_update = new UsersModel();
                                $user_update->id = $customers_to_push->id;
                                $user_update->lastUpdate = date('Y-m-d H:i:s');
                                $user_update->Save();

                                $this->logger->info("Customer was found at Xero. Updated xeroID in the database successfully.", ['xeroID' => $customer_contact_id, 'Customer Name' => $customer_to_push->fullname]);
                            } else {
                                $this->logger->error("Customer was found at Xero. failed to update xeroID in the database", ['xeroID' => $customer_contact_id, 'Customer Name' => $customer_to_push->fullname]);
                            }
                        } else {
                            $contacts = '{"Contacts": [{';
                            $contacts .= $customer_to_push->fullName ? '"Name": "' . FilterInput::CleanString($customer_to_push->fullName) . '",' : '';
                            $contacts .= $customer_to_push->firstName ? '"FirstName": "' . FilterInput::CleanString($customer_to_push->firstName) . '",' : '';
                            $contacts .= $customer_to_push->lastName ? '"LastName": "' . FilterInput::CleanString($customer_to_push->lastName) . '",' : '';
                            $contacts .= FilterInput::Email($customer_to_push->email) ? '"EmailAddress": "' . FilterInput::Email($customer_to_push->email) . '",' : '';
                            if ($customer_to_push->address != '') {
                                $contacts .= '"Addresses": [{"AddressType": "STREET","AddressLine1": "' . FilterInput::CleanString($customer_to_push->address) . '",';
                                if ($customer_to_push->address2 != '') {
                                    $contacts .= '"AddressLine2": "' . FilterInput::CleanString($customer_to_push->address2) . '",';
                                }

                                $contacts .= $customer_to_push->city ? '"City": "' . FilterInput::CleanString($customer_to_push->city) . '",' : '';
                                $contacts .= $customer_to_push->suburb ? '"Region": "' . FilterInput::CleanString($customer_to_push->suburb) . '",' : '';
                                $contacts .= $customer_to_push->zip ? '"PostalCode": "' . FilterInput::CleanString($customer_to_push->zip) . '",' : '';
                                $contacts .= '"AttentionTo": "' . FilterInput::CleanString($customer_to_push->fullName) . '"}],';
                            }

                            $contacts .= '"Phones": [';
                            if ($customer_to_push->phone != '') {
                                $contacts .= '{"PhoneType": "DEFAULT","PhoneNumber": "' . $customer_to_push->phone . '"}';
                                $contacts .= $customer_to_push->phone2 != '' ? ',' : '';
                            }
                            if ($customer_to_push->phone2 != '') {
                                $contacts .= '{"PhoneType": "MOBILE","PhoneNumber": "' . $customer_to_push->phone2 . '"}';
                            }
                            $contacts .= '],';
                            $contacts .= '"IsSupplier": false,"IsCustomer": true,"DefaultCurrency": "AUD"}]}';

                            try {
                                $result = $apiInstance->updateOrCreateContacts($xeroTenantId, $contacts, true);
                                if ($result && !empty($result['contacts']) && $result['contacts'][0]['contact_id']) {
                                    $customer_contact_id = $result['contacts'][0]['contact_id'];
                                    $customer_update = new CustomersModel();
                                    $customer_update->id = $customer_to_push->customer_id;
                                    $customer_update->ContactID = $customer_contact_id;
                                    if ($customer_update->Save()) {
                                        $this->logger->info("Customer was pushed to Xero.", ['ContactID' => $customer_contact_id, 'Customer Name' => $customer_to_push->fullname]);
                                    } else {
                                        $this->logger->error("Customer was pushed to Xero. But failed to update xeroID in the database", ['xeroID' => $customer_contact_id, 'Customer Name' => $customer_to_push->fullname]);
                                    }
                                }
                            } catch (\Exception $e) {
                                if ($e->getCode() == 429) {
                                    Helper::SetFeedback('error',  "[429] Client error: Too Many Requests, API maxed out, Please try again later!");
                                    Redirect::To('xero/customers');
                                }
                                $this->logger->error('Exception when calling AccountingApi->updateOrCreateContacts: '.$e->getMessage(), ['Customer Name' => $customer_to_push->fullname]);
                            }

                            sleep(2);
                        }
                    } catch (\Exception $e) {
                        if ($e->getCode() == 429) {
                            Helper::SetFeedback('error',  "[429] Client error: Too Many Requests, API maxed out, Please try again later!");
                            Redirect::To('xero/customers');
                        }
                        $this->logger->error('Exception when calling AccountingApi->getContacts: '.$e->getMessage(), ['Customer Name' => $customer_to_push->fullname]);
                    }

                }
            }


            // Get customers from xero and create/update db
            $existing_customers = CustomersModel::getCustomerColumns("users.id, customers.id AS customer_id, customers.ContactID, CONCAT(users.firstName,' ', users.lastName) AS fullname", '1', 'assoc');
            $log_sync_result = 'fail';

            try {
                $last_successful_customers_sync = Xero_sync_logsModel::getLastSuccessfulSync('customers');
                $if_modified_since = $last_successful_customers_sync ? new \DateTime($last_successful_customers_sync->created."+10:30") : '';
                $where = 'isCustomer=true';
                $result = $apiInstance->getContacts($xeroTenantId, $if_modified_since, $where);
                if ($result && $result['contacts']) {
                    foreach ($result['contacts'] as $contact) {
                        // search current customers by contact_id and name, if exists in the system then update, else create.
                        $search_array_index = false;
                        $array_search_id = array_search($contact['contact_id'], array_column($existing_customers, 'ContactID'));
                        $array_search_name = array_search($contact['first_name'].' '.$contact['last_name'], array_column($existing_customers, 'fullname'));
                        if ($array_search_id !== false || $array_search_name !== false) {
                            $search_array_index = $array_search_id !== false ? $array_search_id : $array_search_name;
                        }


                        $user = new UsersModel();
                        $user->id = $search_array_index !== false && isset($existing_customers[$search_array_index]['id']) ? $existing_customers[$search_array_index]['id'] : '';
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
                            $this->logger->info("Xero customer was created/updated in the website successfully.", Helper::AppendLoggedin(['Customer' => $user->firstName.' '.$user->lastName]));

                            $customer = new CustomersModel();
                            $customer->user_id = $user->id;
                            $customer->id = $search_array_index !== false && isset($existing_customers[$search_array_index]['customer_id']) ? $existing_customers[$search_array_index]['customer_id'] : '';
                            $customer->ContactID = $contact['contact_id'];
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
                                $this->logger->error("User was created/updated, but failed to create/update customer in the website!", Helper::AppendLoggedin(['Customer' => $customer->companyName, 'Contact ID' => $customer->ContactID]));
                            }
                        } else {
                            $this->logger->error("Failed to create/update customer in the website!", Helper::AppendLoggedin(['Customer' => $user->firstName.' '.$user->lastName]));
                        }
                    }

                    $log_sync_result = 'success';
                    Helper::SetFeedback('success', 'Customers sync completed. Check the logs for status.');
                }
            } catch (\Exception $e) {
                if ($e->getCode() == 429) {
                    Helper::SetFeedback('error',  "[429] Client error: Too Many Requests, API maxed out, Please try again later!");
                    Redirect::To('xero/customers');
                }

                $this->logger->error('Exception when calling AccountingApi->getAccount: '. $e->getMessage());
                Helper::SetFeedback('error', 'Exception when calling AccountingApi->getAccount: '. $e->getMessage());
                Redirect::To('xero/customers');
            }


            $log_sync = new Xero_sync_logsModel();
            $log_sync->type = 'customers';
            $log_sync->result = $log_sync_result;
            $log_sync->Save();
        }

        if ($type == 'accounts' || $type == 'all') {
            $existing_accounts = Xero_accountsModel::getAccountsID();
            $log_sync_result = 'fail';

            try {
                $last_successful_accounts_sync = Xero_sync_logsModel::getLastSuccessfulSync('accounts');
                $if_modified_since = $last_successful_accounts_sync ? new \DateTime($last_successful_accounts_sync->created."+10:30") : '';

                $result = $apiInstance->getAccounts($xeroTenantId, $if_modified_since);
                if ($result && $result['accounts']) {
                    foreach ($result['accounts'] as $account) {
                        $array_search = array_search($account['account_id'], array_keys($existing_accounts));
                        $xero_account = new Xero_accountsModel();

                        if ($array_search !== false && array_values($existing_accounts)[$array_search][0]) {
                            $xero_account->id = array_values($existing_accounts)[$array_search][0];
                        }

                        $xero_account->AccountID = $account['account_id'];
                        $xero_account->Code = $account['code'];
                        $xero_account->Name = $account['name'];
                        $xero_account->Type = $account['type'];
                        $xero_account->TaxType = $account['tax_type'];
                        if ($xero_account->Save()) {
                            $this->logger->info("Xero account was created/updated successfully.", Helper::AppendLoggedin(['Account Code' => $xero_account->Code]));
                        } else {
                            $this->logger->error("Failed to create/update xero account!", Helper::AppendLoggedin(['Account Code' => $xero_account->Code]));
                        }
                    }

                    $log_sync_result = 'success';
                    Helper::SetFeedback('success', 'Accounts sync completed. Check the logs for status.');
                }
            } catch (\Exception $e) {
                $this->logger->error('Exception when calling AccountingApi->getAccount: '. $e->getMessage());
                Helper::SetFeedback('error', 'Exception when calling AccountingApi->getAccount: '. $e->getMessage());
                Redirect::To('xero/accounts');
            }

            $log_sync = new Xero_sync_logsModel();
            $log_sync->type = 'accounts';
            $log_sync->result = $log_sync_result;
            $log_sync->Save();
        }


        if ($type == 'invoices' || $type == 'all') {

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
        // Storage Classe uses sessions for storing token > extend to your DB of choice
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
}