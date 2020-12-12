<?php


namespace Framework\models\jobs;


use Framework\Lib\AbstractModel;

class Repair_trackingModel extends AbstractModel
{
    public $id;
    public $repair_id;
    public $carrier;
    public $tracking_number;
    public $expected_delivery;
    public $status;
    public $status_e;
    public $created;

    protected static $tableName = 'repair_tracking_info';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'repair_id' => parent::DATA_TYPE_INT,
        'carrier' => parent::DATA_TYPE_STR,
        'tracking_number' => parent::DATA_TYPE_STR,
        'expected_delivery' => parent::DATA_TYPE_STR,
        'status' => parent::DATA_TYPE_STR,
        'status_e' => parent::DATA_TYPE_INT
    );

    public static function getRepairTrackings($repair_id, $options = '')
    {
        $sql = "SELECT repair_tracking_info.*, repairs.job_id
                FROM repair_tracking_info
                LEFT JOIN repairs ON repair_tracking_info.repair_id = repairs.id
                WHERE repair_tracking_info.repair_id = '$repair_id'
                $options";
        return parent::getSQL($sql);
    }
}