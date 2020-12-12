<?php


namespace Framework\models\jobs;


use Framework\Lib\AbstractModel;

class Repair_quotes_actionsModel extends AbstractModel
{
    public $id;
    public $repair_quote_id;
    public $action;
    public $approved_by;
    public $created;

    protected static $tableName = 'repair_quotes_actions';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'repair_quote_id' => self::DATA_TYPE_INT,
        'action' => self::DATA_TYPE_STR,
        'approved_by' => self::DATA_TYPE_INT
    );
}