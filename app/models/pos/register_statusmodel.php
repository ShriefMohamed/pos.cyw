<?php


namespace Framework\models\pos;


use Framework\Lib\AbstractModel;

class Register_statusModel extends AbstractModel
{
    public $id;
    public $status; //open, closed
    public $updated;
    public $updated_by;

    protected static $tableName = 'register_status';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'status' => self::DATA_TYPE_STR,
        'updated' => self::DATA_TYPE_STR,
        'updated_by' => self::DATA_TYPE_STR
    );

}