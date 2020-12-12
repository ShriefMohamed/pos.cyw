<?php


namespace Framework\models\jobs;


use Framework\Lib\AbstractModel;

class Repair_attachmentsModel extends AbstractModel
{
    public $id;
    public $repair_id;
    public $user_id;
    public $name;
    public $name_on_server;
    public $created;

    protected static $tableName = 'repair_attachments';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'repair_id' => self::DATA_TYPE_INT,
        'user_id' => self::DATA_TYPE_INT,
        'name' => self::DATA_TYPE_STR,
        'name_on_server' => self::DATA_TYPE_STR,
        'created' => self::DATA_TYPE_STR
    );
}