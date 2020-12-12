<?php


namespace Framework\models\pos;


use Framework\Lib\AbstractModel;

class Tax_classesModel extends AbstractModel
{
    public $id;
    public $class;
    public $rate;
    public $created;

    protected static $tableName = 'tax_classes';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'class' => self::DATA_TYPE_STR,
        'rate' => self::DATA_TYPE_INT,
        'created' => self::DATA_TYPE_STR
    );
}