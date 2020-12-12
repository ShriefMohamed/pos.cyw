<?php


namespace Framework\models\pos;


use Framework\Lib\AbstractModel;

class Sales_itemsModel extends AbstractModel
{
    public $id;
    public $sale_id;

    public $item_id;
    public $item_type;
    public $tax_id;

    public $discount_id;
    public $discount;
    public $quantity;

    public $inventory_id;
    public $pricing_id;
    public $original_price;
    public $price;

    public $total;
    public $created;

    protected static $tableName = 'sales_items';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'sale_id' => self::DATA_TYPE_INT,
        'item_id' => self::DATA_TYPE_STR,
        'item_type' => self::DATA_TYPE_STR,
        'tax_id' => self::DATA_TYPE_INT,
        'discount_id' => self::DATA_TYPE_INT,
        'discount' => self::DATA_TYPE_FLOAT,
        'quantity' => self::DATA_TYPE_INT,
        'inventory_id' => self::DATA_TYPE_INT,
        'pricing_id' => self::DATA_TYPE_INT,
        'original_price' => self::DATA_TYPE_FLOAT,
        'price' => self::DATA_TYPE_FLOAT,
        'total' => self::DATA_TYPE_FLOAT,
        'created' => self::DATA_TYPE_STR
    );

    public static function getSaleItems($sale_id) {
        $sql = "SELECT sales_items.*, 
                    items.item, items.description, items.upc,
                    
                    items_pricing.id AS item_pricing_id, items_pricing.buy_price, 
                        items_pricing.rrp_percentage, items_pricing.rrp_price,
                    
                    (SELECT SUM(items_inventory.qoh) 
                     FROM items_inventory
                     WHERE items_inventory.item_id = sales_items.item_id && items_inventory.qoh != 0
                     ) AS available_stock,
                        
                    tax_classes.class, tax_classes.rate,
                    discounts.title, discounts.type, discounts.discount AS discount_value
                FROM sales_items
                LEFT JOIN items ON sales_items.item_id = items.id
                
                LEFT JOIN items_pricing ON items_pricing.id = (
                    SELECT items_pricing.id
                    FROM items_pricing
                    WHERE items.id = items_pricing.item_id
                    ORDER BY items_pricing.id ASC 
                    LIMIT 1)
                    
                LEFT JOIN tax_classes ON sales_items.tax_id = tax_classes.id
                LEFT JOIN discounts ON sales_items.discount_id = discounts.id
                WHERE sales_items.sale_id = '$sale_id'";
        return parent::getSQL($sql);
    }

    public static function getSaleItems_inventory($sale_id)
    {
        $sql = "SELECT sales_items.*,
                    items_inventory.quantity AS inventory_quantity, items_inventory.qoh
                FROM sales_items
                LEFT JOIN items_inventory ON items_inventory.id = sales_items.inventory_id
                WHERE sales_items.sale_id = '$sale_id'";
        return parent::getSQL($sql);
    }
}