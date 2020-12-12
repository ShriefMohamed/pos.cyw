<?php


namespace Framework\models\pos;


use Framework\Lib\AbstractModel;

class Pricing_levelsModel extends AbstractModel
{
    public $id;
    public $teir;
    public $rate;
    public $created;

    protected static $tableName = 'pricing_levels';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'teir' => self::DATA_TYPE_STR,
        'rate' => self::DATA_TYPE_STR,
        'created' => self::DATA_TYPE_STR
    );
}