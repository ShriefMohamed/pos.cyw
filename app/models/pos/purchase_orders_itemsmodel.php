<?php


namespace Framework\models\pos;


use Framework\Lib\AbstractModel;

class Purchase_orders_itemsModel extends AbstractModel
{
    public $id;
    public $order_id;
    public $item_id;
    public $quantity;
    public $buy_price;
    public $percentage;
    public $price;
    public $total;
    public $status; //ordered, received, completed
    public $quantity_received;
    public $created;

    protected static $tableName = 'purchase_orders_items';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'order_id' => self::DATA_TYPE_INT,
        'item_id' => self::DATA_TYPE_STR,
        'quantity' => self::DATA_TYPE_INT,
        'buy_price' => self::DATA_TYPE_FLOAT,
        'percentage' => self::DATA_TYPE_INT,
        'price' => self::DATA_TYPE_FLOAT,
        'total' => self::DATA_TYPE_FLOAT,
        'status' => self::DATA_TYPE_STR,
        'quantity_received' => self::DATA_TYPE_INT,
        'created' => self::DATA_TYPE_STR
    );

    public static function getPurchaseOrderItems($options = '')
    {
        $sql = "SELECT purchase_orders_items.*,
                    items.item,
                    (SELECT SUM(items_inventory.qoh) 
                     FROM items_inventory
                     WHERE items_inventory.item_id = items.id && items_inventory.qoh != 0
                     ) AS available_stock
                     
                FROM purchase_orders_items
                LEFT JOIN items ON purchase_orders_items.item_id = items.id
                $options";
        return parent::getSQL($sql);
    }

    public static function getOrderItems_itemDetails($options = '')
    {
        $sql = "SELECT purchase_orders_items.*,
                    purchase_orders.vendor_id,
                    vendors.name AS vendor_name,
                    items.uid, items.item
                FROM purchase_orders_items
                LEFT JOIN purchase_orders ON purchase_orders_items.order_id = purchase_orders.id
                LEFT JOIN vendors ON purchase_orders.vendor_id = vendors.id
                LEFT JOIN items ON purchase_orders_items.item_id = items.id
                $options";
        return parent::getSQL($sql);
    }

    public static function getCompletedItemsTotal($order_id)
    {
        return parent::getColumns(
            ['SUM(purchase_orders_items.total) AS total'],
            "purchase_orders_items.order_id = '$order_id'&& purchase_orders_items.status = 'completed'",
            true
        );
    }
}