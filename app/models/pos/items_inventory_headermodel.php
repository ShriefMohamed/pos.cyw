<?php


namespace Framework\models\pos;


use Framework\lib\AbstractModel;

class Items_inventory_headerModel extends AbstractModel
{
    public $id;
    public $item_id;
    public $item_uid;
    public $vendor_id;
    public $buy_price;
    public $retail_price;
    public $qoh;
    public $created;

    protected static $tableName = 'items_inventory_header';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'item_id' => parent::DATA_TYPE_STR,
        'item_uid' => parent::DATA_TYPE_STR,
        'vendor_id' => parent::DATA_TYPE_INT,
        'buy_price' => parent::DATA_TYPE_FLOAT,
        'retail_price' => parent::DATA_TYPE_FLOAT,
        'qoh' => parent::DATA_TYPE_INT
    );
}