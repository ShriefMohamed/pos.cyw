<?php


namespace Framework\models\pos;


use Framework\lib\AbstractModel;

class Vendor_return_itemsModel extends AbstractModel
{
    public $id;
    public $vendor_return_id;
    public $purchase_order_item_id;
    public $return_reason_id;
    public $item_id;
    public $inventory_id;
    public $pricing_id;
    public $quantity;
    public $cost;
    public $subtotal;
    public $created;

    protected static $tableName = 'vendor_return_items';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'vendor_return_id' => self::DATA_TYPE_INT,
        'purchase_order_item_id' => self::DATA_TYPE_INT,
        'return_reason_id' => self::DATA_TYPE_INT,
        'item_id' => self::DATA_TYPE_STR,
        'inventory_id' => self::DATA_TYPE_INT,
        'pricing_id' => self::DATA_TYPE_INT,
        'quantity' => self::DATA_TYPE_INT,
        'cost' => self::DATA_TYPE_FLOAT,
        'subtotal' => self::DATA_TYPE_FLOAT
    );

    public static function getVendorReturnItems($options = '')
    {
        $sql = "SELECT vendor_return_items.*,
                    items.item,
                    items_inventory.qoh,
                   (SELECT SUM(items_inventory.qoh) 
                     FROM items_inventory
                     WHERE items_inventory.item_id = items.id && items_inventory.qoh != 0
                    ) AS available_stock
                FROM vendor_return_items
                LEFT JOIN items ON vendor_return_items.item_id = items.id
                LEFT JOIN items_inventory ON vendor_return_items.inventory_id = items_inventory.id
                $options";
        return parent::getSQL($sql);
    }
}