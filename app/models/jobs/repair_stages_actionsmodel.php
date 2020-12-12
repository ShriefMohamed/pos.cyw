<?php


namespace Framework\models\jobs;


use Framework\Lib\AbstractModel;

class Repair_stages_actionsModel extends AbstractModel
{
    public $id;
    public $repair_id;
    public $stage_id;
    public $repair_stage_id;
    public $user_id;
    public $action;
    public $created;

    protected static $tableName = 'repair_stages_actions';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'repair_id' => self::DATA_TYPE_INT,
        'stage_id' => self::DATA_TYPE_INT,
        'repair_stage_id' => self::DATA_TYPE_INT,
        'user_id' => self::DATA_TYPE_INT,
        'action' => self::DATA_TYPE_INT,
        'created' => self::DATA_TYPE_STR
    );
}