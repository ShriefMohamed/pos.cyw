<?php


namespace Framework\models\xero;


use Framework\lib\AbstractModel;

class Xero_sync_logsModel extends AbstractModel
{
    public $id;
    public $type; // customers, accounts, invoices
    public $result; // success, fail
    public $created;

    protected static $tableName = 'xero_sync_logs';
    protected static $pk = 'id';

    protected $tableSchema = array(
        'type' => self::DATA_TYPE_STR,
        'result' => self::DATA_TYPE_STR
    );

    public static function getLastSuccessfulSync($type)
    {
        $sql = "SELECT created 
                FROM xero_sync_logs
                WHERE type = '$type' && result = 'success'";
        return parent::getSQL($sql, '', true);
    }
}