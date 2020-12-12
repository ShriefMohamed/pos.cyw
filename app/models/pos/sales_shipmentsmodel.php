<?php


namespace Framework\models\pos;


use Framework\lib\AbstractModel;

class Sales_shipmentsModel extends AbstractModel
{
    public $id;
    public $sale_id;
    public $customer_id;
    public $shipping_instructions;
    public $shipped;
    public $shipped_at;
    public $created;

    protected static $tableName = 'sales_shipments';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'sale_id' => self::DATA_TYPE_INT,
        'customer_id' => self::DATA_TYPE_INT,
        'shipping_instructions' => self::DATA_TYPE_STR,
        'shipped' => self::DATA_TYPE_STR,
        'shipped_at' => self::DATA_TYPE_STR,
        'created' => self::DATA_TYPE_STR
    );

    public static function getShipmentsCustomerSales($options = '', $shift = false) {
        $sql = "SELECT sales_shipments.*, sales_shipments.id AS sales_shipments_id,
                    users.firstName, users.lastName, users.phone, users.phone2, users.email,
                    customers.id AS customer_user_id, customers.companyName, customers.address, 
                    customers.address2, customers.city, customers.suburb, customers.zip,
                    sales.sale_status
                FROM sales_shipments
                LEFT JOIN users ON users.id = sales_shipments.customer_id
                LEFT JOIN customers ON customers.user_id = sales_shipments.customer_id
                LEFT JOIN sales ON sales.id = sales_shipments.sale_id
                $options";
        return parent::getSQL($sql, '', $shift);
    }
}