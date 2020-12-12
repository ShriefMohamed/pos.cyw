<?php


namespace Framework\models\pos;


use Framework\Lib\AbstractModel;

class Sales_paymentsModel extends AbstractModel
{
    public $id;
    public $sale_id;
    public $payment_method;
    public $amount;
    public $created_by;
    public $created;

    protected static $tableName = 'sales_payments';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'sale_id' => self::DATA_TYPE_INT,
        'payment_method' => self::DATA_TYPE_STR,
        'amount' => self::DATA_TYPE_FLOAT,
        'created_by' => self::DATA_TYPE_INT,
        'created' => self::DATA_TYPE_STR
    );

    public static function getSalesPayments($options = '')
    {
        $sql = "SELECT sales_payments.*, payment_methods.method AS method_name
                FROM sales_payments
                LEFT JOIN payment_methods ON sales_payments.payment_method = payment_methods.method_key
                $options";
        return parent::getSQL($sql);
    }

    public static function getSalePaidAmount($sale_id)
    {
        $sql = "SELECT SUM(sales_payments.amount) AS total_paid
                FROM sales_payments
                WHERE sales_payments.sale_id = '$sale_id'";
        return parent::getSQL($sql, '', true);
    }

    public static function getCustomerSalesPayments($customer_id)
    {
        $sql = "SELECT sales_payments.*, payment_methods.method AS method_name,
                    sales.uid, sales.customer_id
                FROM sales_payments
                LEFT JOIN payment_methods ON sales_payments.payment_method = payment_methods.method_key
                LEFT JOIN sales ON sales.id = sales_payments.sale_id
                WHERE sales.customer_id = '$customer_id'";
        return parent::getSQL($sql);
    }
}