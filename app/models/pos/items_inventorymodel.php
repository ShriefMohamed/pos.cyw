<?php


namespace Framework\models\pos;


use Framework\Lib\AbstractModel;

class Items_inventoryModel extends AbstractModel
{
    public $id;
    public $item_id;
    public $item_uid;
    public $vendor_id;
    public $buy_price;
    public $rrp_price;
    public $quantity;
    public $qoh;
    public $created;

    protected static $tableName = 'items_inventory';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'item_id' => parent::DATA_TYPE_STR,
        'item_uid' => parent::DATA_TYPE_STR,
        'vendor_id' => parent::DATA_TYPE_INT,
        'buy_price' => parent::DATA_TYPE_FLOAT,
        'rrp_price' => parent::DATA_TYPE_FLOAT,
        'quantity' => parent::DATA_TYPE_INT,
        'qoh' => parent::DATA_TYPE_INT
    );


    public static function getAVGItemPrice($item_id)
    {
        $sql = "SELECT 
                    AVG(items_inventory.buy_price) AS avg_buy_price, 
                    AVG(items_inventory.rrp_price) AS avg_rrp_price
                FROM items_inventory
                WHERE items_inventory.item_id = '$item_id'";
        return parent::getSQL($sql, '', true);
    }

    public static function getItemInventory($item_id)
    {
        $sql = "SELECT items_inventory.*,
                    vendors.name AS vendor_name
              
                FROM items_inventory
                LEFT JOIN vendors ON items_inventory.vendor_id = vendors.id
                WHERE items_inventory.item_id = '$item_id'";
        return parent::getSQL($sql);
    }
}