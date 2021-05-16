<?php


namespace Framework\models\quotes;


use Framework\lib\AbstractModel;

class Quotes_itemsModel extends AbstractModel
{
    public $id;
    public $quote_id;
    public $item_id;
    public $component;
    public $merged;
    public $item_sku;
    public $item_name;
    public $quantity;
    public $original_price;
    public $price_margin;
    public $price;
    public $is_purchase_order;
    public $created;

    protected static $tableName = 'quotes_items';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'quote_id' => self::DATA_TYPE_INT,
        'item_id' => self::DATA_TYPE_INT,
        'component' => self::DATA_TYPE_STR,
        'merged' => self::DATA_TYPE_STR,
        'item_sku' => self::DATA_TYPE_STR,
        'item_name' => self::DATA_TYPE_STR,
        'quantity' => self::DATA_TYPE_INT,
        'original_price' => self::DATA_TYPE_FLOAT,
        'price_margin' => self::DATA_TYPE_FLOAT,
        'price' => self::DATA_TYPE_FLOAT,
        'is_purchase_order' => self::DATA_TYPE_INT,
        'created' => self::DATA_TYPE_STR
    );

    public static function getQuoteItems($options = '')
    {
        $sql = "SELECT quotes_items.*,
                    leader_items.CategoryName, leader_items.SubcategoryName, leader_items.StockCode, 
                    leader_items.IMAGE, leader_items.DBP, leader_items.RRP
                FROM quotes_items
                LEFT JOIN leader_items ON quotes_items.item_id = leader_items.id
                $options";
        return parent::getSQL($sql);
    }

    public static function getQuoteItemsForPO($options= '')
    {
        $sql = "SELECT quotes_items.*,
                    leader_items.StockCode, leader_items.DBP, leader_items.RRP,
                    items.id AS pos_item_id, items.shop_sku, 
                    items.buy_price, items.rrp_price, 
                    (SELECT SUM(items_inventory.qoh) 
                     FROM items_inventory
                     WHERE items_inventory.item_id = items.id && items_inventory.qoh != 0
                     ) AS available_stock
                 FROM quotes_items
                 LEFT JOIN leader_items ON quotes_items.item_id = leader_items.id
                 LEFT JOIN items ON leader_items.StockCode = items.shop_sku
                 $options";
        return parent::getSQL($sql);
    }

    public static function getQuoteItemsForPOS($options= '')
    {
        $sql = "SELECT quotes_items.*,
                    items.id AS pos_item_id, items.shop_sku
                 FROM quotes_items
                 LEFT JOIN items ON quotes_items.item_sku = items.shop_sku
                 $options";
        return parent::getSQL($sql);
    }
}