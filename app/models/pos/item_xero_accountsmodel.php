<?php


namespace Framework\models\pos;


use Framework\lib\AbstractModel;

class Item_xero_accountsModel extends AbstractModel
{
    public $id;
    public $item_id;
    public $item_uid;
    public $inventory_asset_xero_account_id;
    public $inventory_asset_xero_account_code;
    public $purchase_xero_account_id;
    public $purchase_xero_account_code;
    public $sales_xero_account_id;
    public $sales_xero_account_code;

    protected static $tableName = 'items_xero_accounts';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'item_id' => parent::DATA_TYPE_STR,
        'item_uid' => parent::DATA_TYPE_STR,
        'inventory_asset_xero_account_id' => self::DATA_TYPE_INT,
        'inventory_asset_xero_account_code' => self::DATA_TYPE_STR,
        'purchase_xero_account_id' => self::DATA_TYPE_INT,
        'purchase_xero_account_code' => self::DATA_TYPE_STR,
        'sales_xero_account_id' => self::DATA_TYPE_INT,
        'sales_xero_account_code' => self::DATA_TYPE_STR,
    );
}