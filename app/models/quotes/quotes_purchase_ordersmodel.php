<?php


namespace Framework\models\quotes;


use Framework\lib\AbstractModel;

class Quotes_purchase_ordersModel extends AbstractModel
{
    public $id;
    public $quote_id;
    public $purchase_order;
    public $purchase_order_items;
    public $purchase_order_status;
    public $created;
    public $updated;

    protected static $tableName = 'quotes_purchase_orders';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'quote_id' => self::DATA_TYPE_INT,
        'purchase_order' => self::DATA_TYPE_STR,
        'purchase_order_items' => self::DATA_TYPE_STR,
        'purchase_order_status' => self::DATA_TYPE_STR,
        'created' => self::DATA_TYPE_STR,
        'updated' => self::DATA_TYPE_STR
    );
}