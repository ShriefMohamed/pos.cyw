<?php


namespace Framework\models\pos;


use Framework\lib\AbstractModel;

class Inventory_countsModel extends AbstractModel
{
    public $id;
    public $name;
    public $status; // counting, archived, finished, empty
    public $created_by;
    public $created;
    public $updated;

    protected static $tableName = 'inventory_counts';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'name' => parent::DATA_TYPE_STR,
        'status' => parent::DATA_TYPE_STR,
        'created_by' => parent::DATA_TYPE_INT,
        'created' => parent::DATA_TYPE_STR,
        'updated' => parent::DATA_TYPE_STR
    );

    public static function getInventoryCount($id)
    {
        $sql = "SELECT inventory_counts.*,
                    CONCAT(users.firstName,' ', users.lastName) AS admin_name
                FROM inventory_counts
                LEFT JOIN users ON inventory_counts.created_by = users.id
                WHERE inventory_counts.id = '$id'";
        return parent::getSQL($sql, '', true);
    }

    public static function getInventoryCounts($options = '')
    {
        $sql = "SELECT inventory_counts.*,
                    COUNT(inventory_counts_items.id) AS counted_items
                FROM inventory_counts
                LEFT JOIN inventory_counts_items ON inventory_counts.id = inventory_counts_items.inventory_count_id
                $options";
        return parent::getSQL($sql);
    }

    public static function getInventoryCountMissedItems($id)
    {
        $sql = "SELECT items_inventory.item_id, SUM(items_inventory.qoh) AS available_stock,
                    items.item
                FROM items_inventory
                LEFT JOIN items ON items_inventory.item_id = items.id 
                WHERE items_inventory.item_id NOT IN (
                    SELECT item_id 
                    FROM inventory_counts_items 
                    WHERE inventory_counts_items.inventory_count_id = '$id') 
                GROUP BY items_inventory.item_id 
                HAVING available_stock > 0";
        return parent::getSQL($sql);
    }

    public static function getItemsWithStockCount()
    {
        $sql = "SELECT SUM(items_inventory.qoh) AS available_stock 
                FROM items_inventory 
                GROUP BY items_inventory.item_id
                HAVING available_stock > 0";
        return parent::getSQL($sql, '', 'column');
    }

    public static function getItemsWithStock()
    {
        $sql = "SELECT items_inventory.item_id, SUM(items_inventory.qoh) AS available_stock,
                    items.item
                FROM items_inventory
                LEFT JOIN items ON items_inventory.item_id = items.id  
                GROUP BY items_inventory.item_id 
                HAVING available_stock > 0";
        return parent::getSQL($sql);
    }
}