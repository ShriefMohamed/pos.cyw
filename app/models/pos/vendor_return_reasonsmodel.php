<?php


namespace Framework\models\pos;


use Framework\Lib\AbstractModel;

class Vendor_return_reasonsModel extends AbstractModel
{
    public $id;
    public $reason;
    public $reason_order;
    public $created;

    protected static $tableName = 'vendor_return_reasons';
    protected static $pk = 'id';

    protected $tableSchema = array(
        'reason' => self::DATA_TYPE_STR,
        'reason_order' => self::DATA_TYPE_INT
    );

    public static function getReasonsWithUsedCount($options = '')
    {

    }
}