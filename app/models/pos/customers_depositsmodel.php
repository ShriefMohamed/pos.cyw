<?php


namespace Framework\models\pos;


use Framework\Lib\AbstractModel;

class Customers_depositsModel extends AbstractModel
{
    public $id;
    public $customer_id;
    public $sale_id;
    public $amount;
    public $created;

    protected static $tableName = 'customers_deposits';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'customer_id' => self::DATA_TYPE_INT,
        'sale_id' => self::DATA_TYPE_INT,
        'amount' => self::DATA_TYPE_FLOAT
    );
}