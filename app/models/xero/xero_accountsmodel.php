<?php


namespace Framework\models\xero;


use Framework\Lib\AbstractModel;

class Xero_accountsModel extends AbstractModel
{
    public $id;
    public $AccountID;
    public $Code;
    public $Name;
    public $Type;
    public $TaxType;
    public $created;
    public $status; //archived, active

    protected static $tableName = 'xero_accounts';
    protected static $pk = 'id';

    protected $tableSchema = array(
        'AccountID' => self::DATA_TYPE_STR,
        'Code' => self::DATA_TYPE_STR,
        'Name' => self::DATA_TYPE_STR,
        'Type' => self::DATA_TYPE_STR,
        'TaxType' => self::DATA_TYPE_STR,
        'status' => self::DATA_TYPE_STR
    );

    public static function getAccountsID()
    {
        $sql = "SELECT id, AccountID FROM xero_accounts";
        $res = parent::getSQL($sql);
        $items = [];
        if ($res) {
            foreach ($res as $item) {
                $items[$item->AccountID][] = $item->id;
            }
        }
        return $items;
    }
}