<?php


namespace Framework\models\pos;


use Framework\Lib\AbstractModel;

class DiscountsModel extends AbstractModel
{
    public $id;
    public $title;
    public $type;
    public $discount;
    public $created;

    protected static $tableName = 'discounts';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'title' => self::DATA_TYPE_STR,
        'type' => self::DATA_TYPE_STR,
        'discount' => self::DATA_TYPE_FLOAT,
        'created' => self::DATA_TYPE_STR
    );
}