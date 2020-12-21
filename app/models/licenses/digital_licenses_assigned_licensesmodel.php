<?php


namespace Framework\models\licenses;


use Framework\lib\AbstractModel;

class Digital_licenses_assigned_licensesModel extends AbstractModel
{
    public $id;
    public $license_assign_id;
    public $license_id;
    public $license;
    public $expiration_years;
    public $expiration_months;
    public $expiration_date;
    public $license_status; // active, expired, disabled
    public $_60_days_notification;
    public $_30_days_notification;
    public $_1_day_notification;
    public $created;

    protected static $tableName = 'digital_licenses_assigned_licenses';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'license_assign_id' => self::DATA_TYPE_INT,
        'license_id' => self::DATA_TYPE_INT,
        'license' => self::DATA_TYPE_STR,
        'expiration_years' => self::DATA_TYPE_INT,
        'expiration_months' => self::DATA_TYPE_INT,
        'expiration_date' => self::DATA_TYPE_STR,
        'license_status' => self::DATA_TYPE_STR,
        '_60_days_notification' => self::DATA_TYPE_STR,
        '_30_days_notification' => self::DATA_TYPE_STR,
        '_1_day_notification' => self::DATA_TYPE_STR
    );
}