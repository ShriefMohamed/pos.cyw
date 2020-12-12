<?php


namespace Framework\models\pos;


use Framework\Lib\AbstractModel;

class SalesModel extends AbstractModel
{
    public $id;
    public $uid;
    public $customer_id; //
    public $pricing_level; //
    public $printed_note;//
    public $internal_note;//

    public $subtotal;
    public $discount;
    public $total;
    public $tax;

    public $sale_type;
    public $sale_status; //awaiting_payment, paid, partial_payment

    public $created_by;
    public $created;
    public $updated;

    protected static $tableName = 'sales';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'uid' => self::DATA_TYPE_STR,
        'customer_id' => self::DATA_TYPE_INT,
        'pricing_level' => self::DATA_TYPE_INT,
        'printed_note' => self::DATA_TYPE_STR,
        'internal_note' => self::DATA_TYPE_STR,

        'subtotal' => self::DATA_TYPE_FLOAT,
        'discount' => self::DATA_TYPE_FLOAT,
        'total' => self::DATA_TYPE_FLOAT,
        'tax' => self::DATA_TYPE_FLOAT,

        'sale_type' => self::DATA_TYPE_STR,
        'sale_status' => self::DATA_TYPE_STR,

        'created_by' => self::DATA_TYPE_INT,
        'created' => self::DATA_TYPE_STR,
        'updated' => self::DATA_TYPE_STR
    );

    public static function getNextID($options = '')
    {
        $sql = "SELECT MAX(auto_increment) AS next_id FROM INFORMATION_SCHEMA.TABLES WHERE table_name = 'sales' LIMIT 1";
        return parent::getSQL($sql, '', true);
    }

    public static function getSale($options = '', $shift = false) {
        $sql = "SELECT sales.*, 
                    CONCAT(users_customer_table.firstName,' ', users_customer_table.lastName) AS customer_name,
                    users_customer_table.phone AS customer_phone, users_customer_table.phone2 AS customer_mobile,
                    users_customer_table.email AS customer_email,
                    
                    CONCAT(users_admin_table.firstName,' ', users_admin_table.lastName) AS admin_name,
                    
                    (SELECT SUM(sales_payments.amount) 
                     FROM sales_payments
                     WHERE sales_payments.sale_id = sales.id
                    ) AS total_paid,
                    
                    (SELECT SUM(items_pricing.buy_price)
                     FROM sales_items
                     LEFT JOIN items_pricing ON sales_items.pricing_id = items_pricing.id
                     WHERE sales_items.sale_id = sales.id &&
                        sales_items.total > 0
                    ) AS cost
                    
                FROM sales 
                LEFT JOIN users AS users_customer_table ON sales.customer_id = users_customer_table.id
                LEFT JOIN users AS users_admin_table ON sales.created_by = users_admin_table.id
                $options";
        return parent::getSQL($sql, false, $shift);
    }

    public static function getPaidSale($options = '', $shift = false) {
        $sql = "SELECT sales.*, 
                    CONCAT(users.firstName,' ', users.lastName) AS customer_name,
                    (SELECT SUM(sales_payments.amount) 
                     FROM sales_payments 
                     WHERE sales_payments.sale_id = sales.id
                     ) AS total_paid
                FROM sales 
                LEFT JOIN users ON sales.customer_id = users.id
                $options";
        return parent::getSQL($sql, false, $shift);
    }

    public static function getItemSales($item_id)
    {
        $sql = "SELECT sales.*, 
                    CONCAT(users.firstName,' ', users.lastName) AS customer_name,
                    sales_items.item_id, sales_items.original_price, sales_items.discount AS item_discount, 
                    sales_items.quantity, sales_items.price, sales_items.total AS item_total,
                    items_pricing.buy_price, items_pricing.rrp_percentage, items_pricing.rrp_price,
                    tax_classes.class, tax_classes.rate
                FROM sales 
                LEFT JOIN users ON sales.customer_id = users.id
                LEFT JOIN sales_items ON sales.id = sales_items.sale_id
                LEFT JOIN items_pricing ON sales_items.pricing_id = items_pricing.id
                LEFT JOIN tax_classes ON sales_items.tax_id = tax_classes.id
                WHERE sales_items.item_id = '$item_id'
                GROUP BY sales.id";
        return parent::getSQL($sql);
    }

    public static function getCustomerSalesItems($customer_id)
    {
        $sql = "SELECT sales_items.item_id, sales_items.original_price, sales_items.discount AS item_discount, 
                    sales_items.quantity, sales_items.price, sales_items.total AS item_total,
                    sales.customer_id,
                    items_pricing.buy_price, items_pricing.rrp_percentage, items_pricing.rrp_price,
                    tax_classes.class, tax_classes.rate
                FROM sales_items 
                LEFT JOIN sales ON sales_items.sale_id = sales.id
                LEFT JOIN items_pricing ON sales_items.pricing_id = items_pricing.id
                LEFT JOIN tax_classes ON sales_items.tax_id = tax_classes.id
                WHERE sales.customer_id = '$customer_id'";
        return parent::getSQL($sql);
    }

    public static function getAllSales($options = '')
    {
        $sql = "SELECT sales.*, 
                    CONCAT(users.firstName,' ', users.lastName) AS customer_name,
                    (SELECT SUM(sales_items.quantity) 
                     FROM sales_items
                     WHERE sales_items.sale_id = sales.id
                    ) AS quantity,
                    (SELECT SUM(sales_payments.amount) 
                     FROM sales_payments
                     WHERE sales_payments.sale_id = sales.id
                    ) AS total_paid
                    
                FROM sales 
                LEFT JOIN users ON sales.customer_id = users.id
                $options";
        return parent::getSQL($sql);
    }
}