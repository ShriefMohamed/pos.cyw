<?php


namespace Framework\models\pos;


use Framework\lib\AbstractModel;

class Items_auto_reorderModel extends AbstractModel
{
    public $id;
    public $item_id;
    public $item_uid;
    public $auto_reorder;
    public $reorder_point;
    public $reorder_level;

    protected static $tableName = 'items_auto_reorder';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'item_id' => parent::DATA_TYPE_STR,
        'item_uid' => parent::DATA_TYPE_STR,
        'auto_reorder' => self::DATA_TYPE_INT,
        'reorder_point' => self::DATA_TYPE_INT,
        'reorder_level' => self::DATA_TYPE_INT,
    );
}