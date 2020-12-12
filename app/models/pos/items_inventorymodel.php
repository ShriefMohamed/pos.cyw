<?php


namespace Framework\models\pos;


use Framework\Lib\AbstractModel;

class Items_inventoryModel extends AbstractModel
{
    public $id;
    public $item_id;
    public $item_uid;
    public $vendor_id;
    public $pricing_id;
    public $inventory_header_id;
    public $quantity;
    public $qoh;
    public $created;

    protected static $tableName = 'items_inventory';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'item_id' => parent::DATA_TYPE_STR,
        'item_uid' => parent::DATA_TYPE_STR,
        'vendor_id' => parent::DATA_TYPE_INT,
        'pricing_id' => parent::DATA_TYPE_INT,
        'inventory_header_id' => parent::DATA_TYPE_INT,
        'quantity' => parent::DATA_TYPE_INT,
        'qoh' => parent::DATA_TYPE_INT
    );
}