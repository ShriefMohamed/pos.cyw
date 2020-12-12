<?php


namespace Framework\models\jobs;


use Framework\Lib\AbstractModel;

class Repair_tracking_logsModel extends AbstractModel
{
    public $id;
    public $repair_tracking_id;
    public $tracking_number;
    public $details;
    public $location;
    public $date_time;
    public $created;

    protected static $tableName = 'repair_tracking_logs';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'repair_tracking_id' => parent::DATA_TYPE_INT,
        'tracking_number' => parent::DATA_TYPE_STR,
        'details' => parent::DATA_TYPE_STR,
        'location' => parent::DATA_TYPE_STR,
        'date_time' => parent::DATA_TYPE_STR,
    );
}