<?php


namespace Framework\models\pos;


use Framework\Lib\AbstractModel;

class Payment_methodsModel extends AbstractModel
{
    public $id;
    public $method;
    public $method_key;
    public $refund_as;
    public $created;

    protected static $tableName = 'payment_methods';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'method' => self::DATA_TYPE_STR,
        'method_key' => self::DATA_TYPE_STR,
        'refund_as' => self::DATA_TYPE_STR,
        'created' => self::DATA_TYPE_STR
    );
}