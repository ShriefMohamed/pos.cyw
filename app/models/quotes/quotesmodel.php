<?php


namespace Framework\models\quotes;


use Framework\lib\AbstractModel;

class QuotesModel extends AbstractModel
{
    public $id;
    public $uid;
    public $customer_id;
    public $printed_note;
    public $internal_note;

    public $DBP;
    public $margin;
    public $system_total;
    public $subtotal;
    public $GST;
    public $labor;
    public $total;

    public $viewed;
    public $expired;

    public $status; // saved, sent
    public $created_by;
    public $created;
    public $updated;

    protected static $tableName = 'quotes';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'uid' => self::DATA_TYPE_STR,
        'customer_id' => self::DATA_TYPE_INT,
        'printed_note' => self::DATA_TYPE_STR,
        'internal_note' => self::DATA_TYPE_INT,

        'DBP' => self::DATA_TYPE_FLOAT,
        'margin' => self::DATA_TYPE_FLOAT,
        'system_total' => self::DATA_TYPE_FLOAT,
        'subtotal' => self::DATA_TYPE_FLOAT,
        'GST' => self::DATA_TYPE_FLOAT,
        'labor' => self::DATA_TYPE_FLOAT,
        'total' => self::DATA_TYPE_FLOAT,

        'viewed' => self::DATA_TYPE_STR,
        'expired' => self::DATA_TYPE_STR,

        'status' => self::DATA_TYPE_STR,
        'created_by' => self::DATA_TYPE_INT,
        'created' => self::DATA_TYPE_STR,
        'updated' => self::DATA_TYPE_STR
    );

    public static function getQuotes($options = '', $shift = false)
    {
        $sql = "SELECT quotes.*, LPAD(quotes.id,7,'0') AS quote_reference,
                    CONCAT(users.firstName,' ', users.lastName) AS customer_name,
                    CONCAT(customers.address, ', ', customers.suburb, ' ', customers.zip) AS customer_address,
                    users.email AS customer_email, users.phone AS customer_phone,
                    (SELECT COUNT(quotes_items.id) 
                     FROM quotes_items 
                     WHERE quotes_items.quote_id = quotes.id
                    ) AS items_count
                    FROM quotes
                LEFT JOIN customers ON quotes.customer_id = customers.id
                LEFT JOIN users ON users.id = customers.user_id
                $options";
        return parent::getSQL($sql, '', $shift);
    }

    public static function getQuote($options = '', $shift = false) {
        $sql = "SELECT quotes.*, LPAD(quotes.id,7,'0') AS quote_reference,
                    customers.user_id,
                    CONCAT(users_customer_table.firstName,' ', users_customer_table.lastName) AS customer_name,
                    users_customer_table.lastName, users_customer_table.email AS customer_email,
                    users_customer_table.phone AS customer_phone, users_customer_table.phone2 AS customer_mobile,
                    
                    CONCAT(users_admin_table.firstName,' ', users_admin_table.lastName) AS admin_name,
                    
                    (SELECT SUM(leader_items.DBP)
                     FROM leader_items
                     LEFT JOIN quotes_items ON quotes_items.item_id = leader_items.id
                     WHERE quotes_items.item_id = quotes.id
                    ) AS cost
                    
                FROM quotes 
                LEFT JOIN customers ON customers.id = quotes.customer_id
                LEFT JOIN users AS users_customer_table ON customers.user_id = users_customer_table.id
                LEFT JOIN users AS users_admin_table ON quotes.created_by = users_admin_table.id
                $options";
        return parent::getSQL($sql, false, $shift);
    }


    public static function getNextID($options = '')
    {
        $sql = "SELECT MAX(auto_increment) AS next_id, LPAD(MAX(auto_increment),7,'0') AS next_reference FROM INFORMATION_SCHEMA.TABLES WHERE table_name = 'quotes' LIMIT 1";
        return parent::getSQL($sql, '', true);
    }
}