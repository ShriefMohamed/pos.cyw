<?php


namespace Framework\models\pos;


use Framework\lib\AbstractModel;

class Items_inventory_movementsModel extends AbstractModel
{
    public $id;
    public $item_id;
    public $inventory_header_id;
    public $type;
    public $quantity;
    public $source;
    public $created;

    protected static $tableName = 'items_inventory_movements';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'item_id' => parent::DATA_TYPE_STR,
        'inventory_header_id' => parent::DATA_TYPE_INT,
        'type' => parent::DATA_TYPE_STR,
        'quantity' => parent::DATA_TYPE_INT,
        'source' => parent::DATA_TYPE_STR
    );
}