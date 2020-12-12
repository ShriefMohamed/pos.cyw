<?php


namespace Framework\models\pos;


use Framework\lib\AbstractModel;

class Inventory_counts_tmp_print_itemsModel extends AbstractModel
{
    public $id;
    public $second_id;
    public $inventory_count_id;
    public $item_id;
    public $item;
    public $count;
    public $created;

    protected static $tableName = 'inventory_counts_tmp_print_items';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'second_id' => parent::DATA_TYPE_INT,
        'inventory_count_id' => parent::DATA_TYPE_INT,
        'item_id' => parent::DATA_TYPE_STR,
        'item' => parent::DATA_TYPE_STR,
        'sount' => parent::DATA_TYPE_INT,
        'created' => parent::DATA_TYPE_STR
    );

    public static function getInventoryCountsTmpItems($where, $order_by, $extra_order_by = '')
    {
        $sql = "SELECT inventory_counts_tmp_print_items.* ";
        $sql .= $extra_order_by !== '' ? ", categories.category, brands.brand " : '';
        $sql .= "FROM inventory_counts_tmp_print_items ";
        $sql .= $extra_order_by !== '' ?
            "LEFT JOIN items ON inventory_counts_tmp_print_items.item_id = items.id 
             LEFT JOIN categories ON items.category = categories.id 
             LEFT JOIN brands ON items.brand = brands.id " :
            '';
        $sql .= $where.$order_by.$extra_order_by;
        return parent::getSQL($sql);
    }

    public static function getInventoryCountTmpItem($inventory_count_id, $second_id)
    {
        $sql = "SELECT inventory_counts_tmp_print_items.*,
                     (SELECT SUM(items_inventory.qoh) 
                     FROM items_inventory
                     WHERE items_inventory.item_id = inventory_counts_tmp_print_items.item_id && items_inventory.qoh != 0
                     ) AS available_stock
                 FROM inventory_counts_tmp_print_items
                 WHERE inventory_counts_tmp_print_items.inventory_count_id = '$inventory_count_id' && 
                    inventory_counts_tmp_print_items.second_id = '$second_id'";
        return parent::getSQL($sql, '', true);
    }
}