<?php


namespace Framework\models\pos;


use Framework\Lib\AbstractModel;

class Items_pricingModel extends AbstractModel
{
    public $id;
    public $item_id;
    public $item_uid;
    public $buy_price;
    public $rrp_percentage;
    public $rrp_price;
    public $created;

    protected static $tableName = 'items_pricing';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'item_id' => parent::DATA_TYPE_STR,
        'item_uid' => parent::DATA_TYPE_STR,
        'buy_price' => parent::DATA_TYPE_FLOAT,
        'rrp_percentage' => parent::DATA_TYPE_INT,
        'rrp_price' => parent::DATA_TYPE_FLOAT
    );

    public static function getAVGItemPrice($item_id)
    {
        $sql = "SELECT AVG(items_pricing.buy_price) AS avg_buy_price, AVG(items_pricing.rrp_price) AS avg_rrp_price
                FROM items_pricing
                WHERE items_pricing.item_id = '$item_id'";
        return parent::getSQL($sql, '', true);
    }

    public static function getItemInventory($item_id)
    {
//        (items_inventory.quantity
//            -
//            (
//            IFNULL((
//            SELECT SUM(sales_items.quantity)
//                            FROM sales_items
//                            WHERE sales_items.item_id = '$item_id' &&
//    sales_items.inventory_id = items_inventory.id
//                            ), 0)
//                        +
//                        IFNULL((
//                        SELECT SUM(vendor_return_items.quantity)
//                            FROM vendor_returns
//                            LEFT JOIN vendor_return_items ON vendor_returns.id = vendor_return_items.vendor_return_id
//                            WHERE vendor_return_items.item_id = '$item_id' &&
//    (vendor_returns.status = 'sent' || vendor_returns.status = 'closed') &&
//    vendor_return_items.inventory_id = items_inventory.id
//                            ), 0)
//                    )
//                    ) AS available_quantity

        $sql = "SELECT items_pricing.*,
                    items_inventory.id AS inventory_id, items_inventory.vendor_id, items_inventory.quantity,
                    items_inventory.qoh,
                    vendors.name AS vendor_name
              
                FROM items_pricing
                LEFT JOIN items_inventory ON items_pricing.id = items_inventory.pricing_id
                LEFT JOIN vendors ON items_inventory.vendor_id = vendors.id
                WHERE items_pricing.item_id = '$item_id'";
        return parent::getSQL($sql);
    }
}