<?php


namespace Framework\models\jobs;


use Framework\Lib\AbstractModel;

class RepairsModel extends AbstractModel
{
    public $id;
    public $job_id;
    public $user_id;
    public $technician_id;
    public $device;
    public $deviceManufacture;
    public $deviceModel;
    public $serialNumber;
    public $IMEI;
    public $devicePassword;
    public $deviceAltPassword;
    public $issue;
    public $heardAboutUs;
    public $itemsLeft;
    public $emailUpdates;
    public $smsUpdates;

    public $is_insurance;
    public $insuranceCompany;
    public $insuranceNumber;
    public $insuranceEmail;
    public $status;
    public $created;
    public $last_update;

    public $technician_24_email;
    public $technician_48_emails;

    protected static $tableName = 'repairs';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'job_id' => self::DATA_TYPE_STR,
        'user_id' => self::DATA_TYPE_INT,
        'technician_id' => self::DATA_TYPE_INT,
        'device' => self::DATA_TYPE_STR,
        'deviceManufacture' => self::DATA_TYPE_STR,
        'deviceModel' => self::DATA_TYPE_STR,
        'serialNumber' => self::DATA_TYPE_STR,
        'IMEI' => self::DATA_TYPE_STR,
        'devicePassword' => self::DATA_TYPE_STR,
        'deviceAltPassword' => self::DATA_TYPE_STR,
        'issue' => self::DATA_TYPE_STR,
        'heardAboutUs' => self::DATA_TYPE_STR,
        'itemsLeft' => self::DATA_TYPE_STR,
        'emailUpdates' => self::DATA_TYPE_INT,
        'smsUpdates' => self::DATA_TYPE_INT,
        'is_insurance' => self::DATA_TYPE_INT,
        'insuranceCompany' => self::DATA_TYPE_STR,
        'insuranceNumber' => self::DATA_TYPE_STR,
        'insuranceEmail' => self::DATA_TYPE_STR,
        'status' => self::DATA_TYPE_STR,
        'created' => self::DATA_TYPE_STR,
        'last_update' => self::DATA_TYPE_STR,
        'technician_24_email' => self::DATA_TYPE_INT,
        'technician_48_emails' => self::DATA_TYPE_INT
    );

    public static function getIDs($options = '')
    {
        $sql = "SELECT id FROM ".static::$tableName." $options ";
        return parent::getSQL($sql, '', true);
    }

    public static function getRepairs($options = '', $shift = false)
    {
        $sql = "SELECT repairs.*, 
                    users.firstName, users.lastName, users.phone, users.phone2,
                    stages.id AS stage_id, stages.stage,
                    (SELECT repair_tracking_info.id FROM repair_tracking_info
                     WHERE repairs.id = repair_tracking_info.repair_id
                     LIMIT 1
                    ) AS tracking_info,
                    (SELECT CONCAT(users.firstName,' ', users.lastName) FROM users
                     WHERE repairs.technician_id = users.id 
                     LIMIT 1
                     ) AS technician_name
                FROM ".static::$tableName." 
                LEFT JOIN users ON repairs.user_id = users.id
                LEFT JOIN repair_stages ON repair_stages.repair_id = repairs.id && repair_stages.status = 1
                LEFT JOIN stages ON stages.id = repair_stages.stage_id
                $options";
        return parent::getSQL($sql, '', $shift);
    }

    public static function getRepairTechnician($repair_id)
    {
        $sql = "SELECT repairs.*, users.firstName, users.lastName, users.phone, users.email 
                FROM repairs
                LEFT JOIN users ON users.id = repairs.technician_id
                WHERE repairs.id = '$repair_id' ";
        return parent::getSQL($sql, '', true);
    }
}