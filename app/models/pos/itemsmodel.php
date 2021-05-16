<?php


namespace Framework\models\pos;


use Framework\Lib\AbstractModel;

class ItemsModel extends AbstractModel
{
    public $id;
    public $uid;
    public $item_number;
    public $xero_ItemID;

    public $item;
    public $description;
    public $item_type;
    public $serialized;

    public $upc;
    public $shop_sku;
    public $man_sku;


    // new
    public $buy_price;
    public $rrp_percentage;
    public $rrp_price;
    //


    public $department;
    public $department_code;
    public $category;
    public $brand;
    public $tags;

    public $discountable;
    public $tax_class;

    public $is_misc;
    public $is_digital;
    public $is_tracked_as_inventory;

    public $created;
    public $updated;

    public $status;
    public $source; //'manual','xero','csv_import'

    public $search_keywords;

    protected static $tableName = 'items';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'uid' => self::DATA_TYPE_STR,
        'item_number' => self::DATA_TYPE_INT,
        'xero_ItemID' => self::DATA_TYPE_STR,
        'item' => self::DATA_TYPE_STR,
        'description' => self::DATA_TYPE_STR,
        'item_type' => self::DATA_TYPE_STR,
        'serialized' => self::DATA_TYPE_INT,

        'upc' => self::DATA_TYPE_STR,
        'shop_sku' => self::DATA_TYPE_STR,
        'man_sku' => self::DATA_TYPE_STR,

        'buy_price' => parent::DATA_TYPE_FLOAT,
        'rrp_percentage' => parent::DATA_TYPE_INT,
        'rrp_price' => parent::DATA_TYPE_FLOAT,

        'department' => self::DATA_TYPE_STR,
        'department_code' => self::DATA_TYPE_STR,
        'category' => self::DATA_TYPE_INT,
        'brand' => self::DATA_TYPE_INT,
        'tags' => self::DATA_TYPE_STR,

        'discountable' => self::DATA_TYPE_INT,
        'tax_class' => self::DATA_TYPE_INT,

        'is_misc' => self::DATA_TYPE_INT,
        'is_digital' => self::DATA_TYPE_INT,
        'is_tracked_as_inventory' => self::DATA_TYPE_INT,

        'created' => self::DATA_TYPE_STR,
        'updated' => self::DATA_TYPE_STR,
        'status' => self::DATA_TYPE_STR,
        'source' => self::DATA_TYPE_STR,
        'search_keywords' => self::DATA_TYPE_STR
    );


    // for creating an item
    public static function getAllTags($options = '')
    {
        $sql = "SELECT tags FROM items";
        $results = parent::getSQL($sql);
        $ftags = array();
        if ($results) {
            foreach ($results as $tags) {
                if ($tags->tags != '') {
                    $tag = explode(',', $tags->tags);
                    for ($i = 0; $i < sizeof($tag); $i++) {
                        if (!in_array($tag[$i], $ftags)) {
                            array_push($ftags, $tag[$i]);
                        }
                    }
                }
            }
        }
        return (!empty($ftags)) ? $ftags : false;
    }

    public static function getItemsTags()
    {
        $sql = "SELECT items.id, items.item, items.tags 
                FROM items
                GROUP BY items.tags";
        $results = parent::getSQL($sql);

        $tags = array();
        foreach ($results as $result) {
            $result_tags = explode(',', $result->tags);
            if ($result_tags[0]) {
                foreach ($result_tags as $result_tag) {
                    if (!isset($tags[$result_tag])) {
                        $tags[$result_tag][$result->id]['id'] = $result->id;
                        $tags[$result_tag][$result->id]['tag'] = $result_tag;
                    } else {
                        if (!isset($tags[$result_tag][$result->id])) {
                            $tags[$result_tag][$result->id]['id'] = $result->id;
                            $tags[$result_tag][$result->id]['tag'] = $result_tag;
                        }
                    }
                }
            }
        }
        return !empty($tags) ? $tags : false;
    }

    public static function getAllDepartments($options = '')
    {
        $sql = "SELECT department FROM items";
        $results = parent::getSQL($sql);
        $fdepartments = array();
        if ($results) {
            foreach ($results as $departments) {
                if ($departments->department != '') {
                    $department = explode(',', $departments->department);
                    for ($i = 0; $i < sizeof($department); $i++) {
                        if (!in_array($department[$i], $fdepartments)) {
                            array_push($fdepartments, $department[$i]);
                        }
                    }
                }
            }
        }
        return (!empty($fdepartments)) ? $fdepartments : false;
    }

    public static function generateUniqueNumber(): int
    {
        $sql = "SELECT FLOOR(RAND() * 99999) AS random_num
                FROM items 
                WHERE 'random_num' NOT IN (SELECT items.uid FROM items)
                LIMIT 1";
        $random_num = parent::getSQL($sql, '', true);
        return $random_num ? $random_num->random_num : 42561;
    }


    // retrieve items
//    public static function getItems($options = '', $shift = false)
//    {
//        $sql = "SELECT items.*, items.id AS item_o_id,
//                    items_pricing.*, items_pricing.id AS item_pricing_id,
//                    items_inventory.*, items_inventory.id AS item_inventory_id,
//                    categories.category AS category_name, brands.brand AS brand_name,
//                    tax_classes.class, tax_classes.rate,
//
//                    ((
//                        SELECT SUM(items_inventory.quantity) FROM items_inventory WHERE items.id = items_inventory.item_id
//                    )
//                    -
//                    (
//                        IFNULL((
//                            SELECT SUM(sales_items.quantity) AS sold FROM sales_items
//                                WHERE sales_items.item_id = items.id
//                            ), 0)
//                        +
//                        IFNULL((
//                            SELECT SUM(vendor_return_items.quantity) AS returned
//                            FROM vendor_returns
//                            LEFT JOIN vendor_return_items ON vendor_returns.id = vendor_return_items.vendor_return_id
//                            WHERE vendor_return_items.item_id = items.id && (vendor_returns.status = 'sent' || vendor_returns.status = 'closed')
//                            ), 0)
//                    )
//                    ) AS available_quantity,
//
//                    (SELECT AVG(items_pricing.rrp_price)
//                     FROM items_pricing
//                     WHERE items.id = items_pricing.item_id
//                    ) AS items_avg_price
//
//                FROM items
//                LEFT JOIN items_pricing ON items.id = items_pricing.item_id
//                LEFT JOIN items_inventory ON items.id = items_inventory.item_id && items_inventory.pricing_id = items_pricing.id
//                LEFT JOIN categories ON items.category = categories.id
//                LEFT JOIN brands ON items.brand = brands.id
//                LEFT JOIN tax_classes ON items.tax_class = tax_classes.id
//                $options
//                GROUP BY items.id";
//        return parent::getSQL($sql, '', $shift);
//$sql = "SELECT items.*, items.id AS item_o_id,
//                    items_pricing.*, items_pricing.id AS item_pricing_id,
//                    items_inventory.*, items_inventory.id AS item_inventory_id,
//                    items_inventory_header.qoh,
//
//                    categories.category AS category_name, brands.brand AS brand_name,
//                    tax_classes.class, tax_classes.rate
//
//                FROM items
//
//                LEFT JOIN items_pricing ON items_pricing.id = (
//                    SELECT items_pricing.id
//                    FROM items_pricing
//                    WHERE items.id = items_pricing.item_id
//                    ORDER BY items_pricing.id ASC
//                    LIMIT 1)
//                LEFT JOIN items_inventory ON items_inventory.id = (
//                    SELECT items_inventory.id
//                    FROM items_inventory
//                    WHERE items.id = items_inventory.item_id &&
//                        items_inventory.pricing_id = items_pricing.id
//                    ORDER BY items_inventory.id ASC
//                    LIMIT 1)
//                LEFT JOIN items_inventory_header ON
//                    items.id = items_inventory_header.item_id &&
//                    items_inventory.inventory_header_id = items_inventory_header.id
//
//                LEFT JOIN categories ON items.category = categories.id
//                LEFT JOIN brands ON items.brand = brands.id
//                LEFT JOIN tax_classes ON items.tax_class = tax_classes.id
//                $options
//                GROUP BY items.id";
//    }



    public static function getAllItemsDetails($options = '', $shift = false)
    {
        $sql = "SELECT items.*, items.id AS item_o_id, 
                    items_inventory.id AS item_inventory_id, items_inventory.vendor_id, 
                        items_inventory.quantity,    
                    (SELECT SUM(items_inventory.qoh) 
                     FROM items_inventory
                     WHERE items_inventory.item_id = items.id && items_inventory.qoh != 0
                     ) AS available_stock,

                    items_xero_accounts.id AS items_xero_accounts_id,
                    items_xero_accounts.inventory_asset_xero_account_id, 
                    items_xero_accounts.inventory_asset_xero_account_code, 
                    items_xero_accounts.purchase_xero_account_id, 
                    items_xero_accounts.purchase_xero_account_code, 
                    items_xero_accounts.sales_xero_account_id, 
                    items_xero_accounts.sales_xero_account_code,
                    
                    items_auto_reorder.id AS items_auto_reorder_id, 
                    items_auto_reorder.auto_reorder, items_auto_reorder.reorder_point, 
                    items_auto_reorder.reorder_level, 
                    
                    categories.category AS category_name, brands.brand AS brand_name,
                    tax_classes.class, tax_classes.rate
                      
                FROM items 
                
                LEFT JOIN items_inventory ON items_inventory.id = (
                    SELECT items_inventory.id
                    FROM items_inventory
                    WHERE items.id = items_inventory.item_id
                    ORDER BY items_inventory.id ASC
                    LIMIT 1)
                LEFT JOIN items_xero_accounts ON items.id = items_xero_accounts.item_id
                LEFT JOIN items_auto_reorder ON items.id = items_auto_reorder.item_id
                LEFT JOIN categories ON items.category = categories.id
                LEFT JOIN brands ON items.brand = brands.id
                LEFT JOIN tax_classes ON items.tax_class = tax_classes.id
                $options
                GROUP BY items.id";
        return parent::getSQL($sql, '', $shift);
    }

    public static function getItems($options = '', $shift = false)
    {
        $sql = "SELECT items.*, items.id AS item_o_id, 
                    items_inventory.id AS item_inventory_id, items_inventory.vendor_id, 
                        items_inventory.quantity,    
                    (SELECT SUM(items_inventory.qoh) 
                     FROM items_inventory
                     WHERE items_inventory.item_id = items.id && items_inventory.qoh != 0
                     ) AS available_stock,

                    categories.category AS category_name, brands.brand AS brand_name,
                    tax_classes.class, tax_classes.rate
                      
                FROM items 
                
                LEFT JOIN items_inventory ON items_inventory.id = (
                    SELECT items_inventory.id
                    FROM items_inventory
                    WHERE items.id = items_inventory.item_id
                    ORDER BY items_inventory.id ASC
                    LIMIT 1)
                LEFT JOIN categories ON items.category = categories.id
                LEFT JOIN brands ON items.brand = brands.id
                LEFT JOIN tax_classes ON items.tax_class = tax_classes.id
                $options
                GROUP BY items.id";
        return parent::getSQL($sql, '', $shift);
    }

    public static function getItemKeywords($item_id)
    {
        $sql = "SELECT items.item, items.item_number, items.description, 
                    items.uid, items.upc, items.shop_sku, items.man_sku, 
                    items.department, items.department_code, items.tags, 
                    categories.category, 
                    brands.brand
                FROM items
                LEFT JOIN categories ON items.category = categories.id
                LEFT JOIN brands ON items.brand = brands.id
                WHERE items.id = '$item_id' ";
        return parent::getSQL($sql, '', 'assoc');
    }

    public static function getItemsSimple($options = '', $shift = false)
    {
        $sql = "SELECT items.id, items.uid, items.item, items.description,
                     (SELECT SUM(items_inventory.qoh) 
                     FROM items_inventory
                     WHERE items_inventory.item_id = items.id && items_inventory.qoh != 0
                     ) AS available_stock
                FROM items
                $options";
        return parent::getSQL($sql, '', $shift);
    }

    public static function getItemsForXeroPush($options = '')
    {
        $sql = "SELECT items.id, items.item, items.description, items.shop_sku, 
                    items.is_tracked_as_inventory, items.tax_class,
                    items.buy_price, items.rrp_price,
                    
                    items_xero_accounts.inventory_asset_xero_account_code, 
                    items_xero_accounts.purchase_xero_account_code, 
                    items_xero_accounts.sales_xero_account_code, 
                    
                    (SELECT SUM(items_inventory.qoh)
                    FROM items_inventory
                    WHERE items_inventory.item_id = items.id && items_inventory.qoh != 0
                    ) AS quantity_on_hand
                FROM items
                LEFT JOIN items_xero_accounts ON items_xero_accounts.item_id = items.id
                WHERE (!ISNULL(items.shop_sku) && !ISNULL(items.item)) $options GROUP BY items.id";
        return parent::getSQL($sql);
    }

    //
    public static function getItemAvailableQuantity($item_id)
    {
        $sql = "SELECT items.item,
                     (SELECT SUM(items_inventory.qoh) 
                     FROM items_inventory
                     WHERE items_inventory.item_id = items.id && items_inventory.qoh != 0
                     ) AS available_stock
                FROM items
                WHERE items.id = '$item_id'";
        return parent::getSQL($sql, '', true);
    }

    public static function getItemInventoryPricingTaxDetails($item_id)
    {
        $sql = "SELECT items.*, items.id AS item_o_id,
                    items_inventory.id AS item_inventory_id, items_inventory.vendor_id, 
                    items_inventory.quantity, items_inventory.qoh,
                     
                    tax_classes.class, tax_classes.rate
                FROM items 
                
                LEFT JOIN items_inventory ON items_inventory.id = (
                    SELECT items_inventory.id
                    FROM items_inventory
                    WHERE items.id = items_inventory.item_id && items_inventory.qoh > 0 
                    ORDER BY items_inventory.id ASC
                    LIMIT 1)
                    
                LEFT JOIN tax_classes ON items.tax_class = tax_classes.id
                WHERE items.id = '$item_id'";
        return parent::getSQL($sql, '', true);
    }

    // add shipping too
    public static function getAwaitingDispatch($item_id)
    {
        $sql = "SELECT items.*,
                    (SELECT SUM(sales_items.quantity) 
                     FROM sales_items
                     LEFT JOIN sales ON sales_items.sale_id = sales.id
                     WHERE sales.sale_status = 'awaiting_payment' && sales_items.item_id = items.id
                     LIMIT 1
                     ) AS awaiting_dispatch
                FROM items
                WHERE items.id = '$item_id'";
        return parent::getSQL($sql, '', true);
    }

    public static function Search($key, $options = '')
    {
        $search = parent::getColumns(['id'], "search_keywords LIKE '%$key%'");
        if ($search) {
            $items = implode(', ', $search);

            $sql = "SELECT items.*, items.id AS item_o_id,
                    items_inventory.id AS item_inventory_id, items_inventory.vendor_id, 
                        items_inventory.quantity,

                    categories.category AS category_name, 
                    brands.brand AS brand_name,
                    tax_classes.class, tax_classes.rate,
                    
                    (SELECT SUM(items_inventory.qoh) 
                     FROM items_inventory
                     WHERE items_inventory.item_id = items.id && items_inventory.qoh != 0
                    ) AS available_stock
                      
                FROM items 
                
                LEFT JOIN items_inventory ON items_inventory.id = (
                    SELECT items_inventory.id
                    FROM items_inventory
                    WHERE items.id = items_inventory.item_id
                    ORDER BY items_inventory.id ASC
                    LIMIT 1)
        
                LEFT JOIN categories ON items.category = categories.id
                LEFT JOIN brands ON items.brand = brands.id
                LEFT JOIN tax_classes ON items.tax_class = tax_classes.id
                
                WHERE items.id IN ($items) 
                $options
                GROUP BY items.id";
            return parent::getSQL($sql);
        }
        return false;
    }





    /* new */
    public function getItemsAvgPrice($options = ''): ItemsModel
    {
        $this->_sql = "SELECT items.*,
                        
                    (SELECT SUM(items_inventory.qoh) 
                     FROM items_inventory
                     WHERE items_inventory.item_id = items.id && items_inventory.qoh != 0
                     ) AS available_stock,
                  
                    (SELECT AVG(items_inventory.rrp_price)
                     FROM items_inventory
                     WHERE items.id = items_inventory.item_id                      
                    ) AS items_avg_price,
                    
                    categories.category AS category_name, brands.brand AS brand_name,
                    tax_classes.class, tax_classes.rate
                      
                FROM items
                    
                LEFT JOIN categories ON items.category = categories.id
                LEFT JOIN brands ON items.brand = brands.id
                LEFT JOIN tax_classes ON items.tax_class = tax_classes.id";
        $this->_options = $options;
        return $this;
    }
}
