<?php


namespace Framework\models;


use Framework\lib\AbstractModel;

class Invoices_ordersModel extends AbstractModel
{
    public $id;
    public $invoice_id;
    public $order_id;
    public $type; //sale, quote, job!

    protected static $tableName = 'invoices_orders';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'invoice_id' => self::DATA_TYPE_INT,
        'order_id' => self::DATA_TYPE_INT,
        'type' => self::DATA_TYPE_STR
    );
}