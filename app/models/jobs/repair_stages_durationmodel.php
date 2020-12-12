<?php


namespace Framework\models\jobs;


use Framework\Lib\AbstractModel;

class Repair_stages_durationModel extends AbstractModel
{
    public $id;
    public $repair_stage_id;
    public $repair_id;
    public $stage_id;

    public $duration;
    public $paused;
    public $continued;

    protected static $tableName = 'repair_stages_duration';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'repair_stage_id' => self::DATA_TYPE_INT,
        'repair_id' => self::DATA_TYPE_INT,
        'stage_id' => self::DATA_TYPE_INT,
        'duration' => self::DATA_TYPE_INT,
        'paused' => self::DATA_TYPE_STR,
        'continued' => self::DATA_TYPE_STR
    );
}