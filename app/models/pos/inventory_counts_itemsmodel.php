<?php


namespace Framework\models\pos;


use Framework\lib\AbstractModel;

class Inventory_counts_itemsModel extends AbstractModel
{
    public $id;
    public $inventory_count_id;
    public $item_id;
    public $should_have;
    public $counted;
    public $created_by;
    public $created;
    public $updated;

    protected static $tableName = 'inventory_counts_items';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'inventory_count_id' => parent::DATA_TYPE_INT,
        'item_id' => parent::DATA_TYPE_STR,
        'should_have' => parent::DATA_TYPE_INT,
        'counted' => parent::DATA_TYPE_INT,
        'created_by' => parent::DATA_TYPE_INT,
        'created' => parent::DATA_TYPE_STR,
        'updated' => parent::DATA_TYPE_STR
    );

    public static function getInventoryCountItems($inventory_count_id)
    {
        $sql = "SELECT inventory_counts_items.*,
                    items.item,
                    CONCAT(users.firstName,' ', users.lastName) AS admin_name,
                    (SELECT SUM(items_inventory.qoh) 
                     FROM items_inventory
                     WHERE items_inventory.item_id = items.id && items_inventory.qoh != 0
                     ) AS available_stock
                     
                FROM inventory_counts_items
                LEFT JOIN items ON inventory_counts_items.item_id = items.id
                LEFT JOIN users ON inventory_counts_items.created_by = users.id
                WHERE inventory_counts_items.inventory_count_id = '$inventory_count_id'";
        return parent::getSQL($sql);
    }
}