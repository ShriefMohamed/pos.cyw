<?php


namespace Framework\models;


use Framework\Lib\AbstractModel;

class CustomersModel extends AbstractModel
{
    public $id;
    public $user_id;
    public $xero_ContactID;
    public $companyName;

    public $discount_id;
    public $address;
    public $address2;
    public $city;
    public $suburb;
    public $zip;
    public $website;
    public $notes;

    public $credit_limit;

    public $smsNotifications;
    public $emailNotifications;
    public $licensesNotifications;

    public $created;
    public $updated;
    public $status; //archived, active
    public $source; //xero, manual

    protected static $tableName = 'customers';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'user_id' => self::DATA_TYPE_INT,
        'xero_ContactID' => self::DATA_TYPE_STR,
        'companyName' => self::DATA_TYPE_STR,

        'discount_id' => self::DATA_TYPE_INT,
        'address' => self::DATA_TYPE_STR,
        'address2' => self::DATA_TYPE_STR,
        'city' => self::DATA_TYPE_STR,
        'suburb' => self::DATA_TYPE_STR,
        'zip' => self::DATA_TYPE_STR,
        'website' => self::DATA_TYPE_STR,
        'notes' => self::DATA_TYPE_STR,

        'credit_limit' => self::DATA_TYPE_FLOAT,

        'smsNotifications' => self::DATA_TYPE_INT,
        'emailNotifications' => self::DATA_TYPE_INT,
        'licensesNotifications' => self::DATA_TYPE_INT,
        'created' => self::DATA_TYPE_STR,
        'updated' => self::DATA_TYPE_STR,
        'status' => self::DATA_TYPE_STR,
        'source' => self::DATA_TYPE_STR,
    );

    public static function getCustomers($options = '', $shift = false)
    {
        $sql = "SELECT users.*, 
                    customers.id AS customer_id, customers.xero_ContactID, customers.companyName, customers.discount_id, customers.address, customers.address2,
                    customers.city, customers.suburb, customers.zip, customers.website, customers.notes, customers.credit_limit,
                    customers.emailNotifications, customers.smsNotifications,
                    discounts.title, discounts.type, discounts.discount,
                    
                    (
                    (SELECT SUM(sales_payments.amount) 
                     FROM sales_payments 
                     LEFT JOIN sales ON sales_payments.sale_id = sales.id 
                     WHERE sales_payments.payment_method = 'account' && sales.customer_id = users.id)
                    -
                    IFNULL((SELECT SUM(customers_deposits.amount) 
                     FROM customers_deposits 
                     WHERE customers_deposits.customer_id = users.id), 0)
                    ) AS owed
                     
                FROM users
                LEFT JOIN customers ON users.id = customers.user_id
                LEFT JOIN discounts ON customers.discount_id = discounts.id
                $options";
        return parent::getSQL($sql, null, $shift);
    }

    public static function getCustomer($user_id)
    {
        $sql = "SELECT users.*, 
                    customers.id AS customer_id, customers.companyName, customers.discount_id, customers.address, customers.address2,
                    customers.city, customers.suburb, customers.zip, customers.website, customers.notes, customers.credit_limit,
                    customers.emailNotifications, customers.smsNotifications
                FROM users
                LEFT JOIN customers ON users.id = customers.user_id
                WHERE users.role = 'customer' && users.id = '$user_id'";
        return parent::getSQL($sql, null, true);
    }

    public static function getCustomerColumns($cols = '*', $where = '1', $return = '')
    {
        $sql = "SELECT ".$cols." 
                FROM customers
                LEFT JOIN users ON customers.user_id = users.id
                WHERE " . $where;
        return parent::getSQL($sql, '', $return);
    }

    public static function getCustomersForQuotes($where = '1', $shift = false)
    {
        $sql = "SELECT users.id, users.firstName, users.lastName, users.email, users.phone, 
                customers.id AS customer_id, customers.companyName, customers.address, customers.address2,
                customers.city, customers.suburb, customers.zip 
                FROM users
                LEFT JOIN customers ON customers.user_id = users.id
                WHERE users.role = 'customer' && !ISNULL(customers.id) ". $where;
        return parent::getSQL($sql, '', $shift);
    }

    public static function Search($key)
    {
        $sql = "SELECT users.*, 
                    customers.id AS customer_id, customers.companyName, customers.discount_id, customers.address, customers.address2,
                    customers.city, customers.suburb, customers.zip, customers.website, customers.notes,customers.emailNotifications, 
                    customers.smsNotifications, customers.created,
                    discounts.title, discounts.type, discounts.discount
                FROM customers
                
                LEFT JOIN users ON users.id = customers.user_id
                LEFT JOIN discounts ON customers.discount_id = discounts.id
                
                WHERE 
                    users.firstName LIKE '%$key%' ||
                    users.lastName LIKE '%$key%' ||
                    users.email LIKE '%$key%' ||
                    users.phone LIKE '%$key%' ||
                    users.phone2 LIKE '%$key%' ||
                    customers.companyName LIKE '%$key%' ||
                    customers.city LIKE '%$key%' ||
                    customers.suburb LIKE '%$key%' ||
                    customers.zip LIKE '%$key%'
                    
                GROUP BY customers.id";
        return parent::getSQL($sql);
    }




    public function getCustomersSimple($options = ''): CustomersModel
    {
        $this->_sql = "SELECT users.*, CONCAT(users.firstName,' ', users.lastName) AS fullname, 
                    customers.id AS customer_id, customers.xero_ContactID, customers.companyName, customers.address, customers.address2,
                    customers.city, customers.suburb, customers.zip, customers.website, customers.notes,
                    customers.status
                     
                FROM users
                LEFT JOIN customers ON users.id = customers.user_id
                WHERE users.role = 'customer'
                $options";
        $this->_options = $options;
        return $this;
    }

    public function getCustomersPOS($options = ''): CustomersModel
    {
        $this->_sql = "SELECT users.*, 
                    customers.id AS customer_id, customers.xero_ContactID, customers.companyName, customers.discount_id, customers.address, customers.address2,
                    customers.city, customers.suburb, customers.zip, customers.website, customers.notes, customers.credit_limit,
                    customers.emailNotifications, customers.smsNotifications,
                    discounts.title, discounts.type, discounts.discount,
                    
                    (
                    (SELECT SUM(sales_payments.amount) 
                     FROM sales_payments 
                     LEFT JOIN sales ON sales_payments.sale_id = sales.id 
                     WHERE sales_payments.payment_method = 'account' && sales.customer_id = users.id)
                    -
                    IFNULL((SELECT SUM(customers_deposits.amount) 
                     FROM customers_deposits 
                     WHERE customers_deposits.customer_id = users.id), 0)
                    ) AS owed
                     
                FROM users
                LEFT JOIN customers ON users.id = customers.user_id
                LEFT JOIN discounts ON customers.discount_id = discounts.id
                $options";
        $this->_options = $options;
        return $this;
    }

}