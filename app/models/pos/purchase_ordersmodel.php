<?php


namespace Framework\models\pos;


use Framework\Lib\AbstractModel;

class Purchase_ordersModel extends AbstractModel
{
    public $id;
    public $vendor_id;
    public $status;
    public $reference;
    public $ordered;
    public $expected;
    public $shipping_notes;
    public $general_notes;

    public $order_subtotal;
    public $shipping;
    public $other;
    public $discount;
    public $discount_amount;
    public $order_total;

    public $created;
    public $created_by;

    protected static $tableName = 'purchase_orders';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'vendor_id' => self::DATA_TYPE_INT,
        'status' => self::DATA_TYPE_STR,
        'reference' => self::DATA_TYPE_STR,
        'ordered' => self::DATA_TYPE_STR,
        'expected' => self::DATA_TYPE_STR,
        'shipping_notes' => self::DATA_TYPE_STR,
        'general_notes' => self::DATA_TYPE_STR,

        'order_subtotal' => self::DATA_TYPE_FLOAT,
        'shipping' => self::DATA_TYPE_FLOAT,
        'other' => self::DATA_TYPE_FLOAT,
        'discount' => self::DATA_TYPE_INT,
        'discount_amount' => self::DATA_TYPE_FLOAT,
        'order_total' => self::DATA_TYPE_FLOAT,

        'created' => self::DATA_TYPE_STR,
        'created_by' => self::DATA_TYPE_INT
    );

    public static function getPurchaseOrders($where = '')
    {
        $sql = "SELECT purchase_orders.*,
                    vendors.name AS vendor_name,
                    (SELECT SUM(purchase_orders_items.quantity) 
                     FROM purchase_orders_items
                     WHERE purchase_orders.id = purchase_orders_items.order_id && purchase_orders_items.status = 'ordered'
                     ) AS total_ordered,
                    (SELECT SUM(purchase_orders_items.quantity) 
                     FROM purchase_orders_items
                     WHERE purchase_orders.id = purchase_orders_items.order_id &&
                        (purchase_orders_items.status = 'received' || purchase_orders_items.status = 'completed')
                     ) AS total_received 

                FROM purchase_orders
                LEFT JOIN vendors ON purchase_orders.vendor_id = vendors.id
                $where";
        return parent::getSQL($sql);
    }

    public static function getPurchaseOrder($order_id)
    {
        $sql = "SELECT purchase_orders.*,
                    total_order, total_order_cost,
                    total_received, total_received_cost,
                    CONCAT(users.firstName,' ', users.lastName) AS admin_name
                    
                    FROM purchase_orders
                    
                    LEFT JOIN (
                        SELECT purchase_orders_items.order_id AS purchase_orders_items_order_id, 
                            SUM(purchase_orders_items.quantity) AS total_order,
                            SUM(purchase_orders_items.buy_price * purchase_orders_items.quantity) AS total_order_cost
                     	FROM purchase_orders_items 
                     	WHERE purchase_orders_items.order_id = '$order_id' && purchase_orders_items.status = 'ordered'
                    ) order_items ON order_items.purchase_orders_items_order_id = purchase_orders.id
                    LEFT JOIN (
                        SELECT purchase_orders_items.order_id AS purchase_orders_items_order_id, 
                            SUM(purchase_orders_items.quantity) AS total_received,
                            SUM(purchase_orders_items.buy_price * purchase_orders_items.quantity) AS total_received_cost
                     	FROM purchase_orders_items
                        WHERE purchase_orders_items.order_id = '$order_id' &&
                            (purchase_orders_items.status = 'received' || purchase_orders_items.status = 'completed')
                    ) recieved_items ON recieved_items.purchase_orders_items_order_id = purchase_orders.id
                    
                    LEFT JOIN users ON purchase_orders.created_by = users.id
                    WHERE purchase_orders.id = '$order_id'
                    ";
        return parent::getSQL($sql, '', true);
    }

    public static function getPurchaseOrders_item($where = '')
    {
        $sql = "SELECT purchase_orders.*,
                    vendors.name AS vendor_name,
                    (SELECT SUM(purchase_orders_items.quantity) 
                     FROM purchase_orders_items
                     WHERE purchase_orders.id = purchase_orders_items.order_id && purchase_orders_items.status = 'ordered'
                     ) AS total_ordered,
                    (SELECT SUM(purchase_orders_items.quantity) 
                     FROM purchase_orders_items
                     WHERE purchase_orders.id = purchase_orders_items.order_id &&
                        (purchase_orders_items.status = 'received' || purchase_orders_items.status = 'completed')
                     ) AS total_received 

                FROM purchase_orders
                LEFT JOIN vendors ON purchase_orders.vendor_id = vendors.id
                LEFT JOIN purchase_orders_items ON purchase_orders.id = purchase_orders_items.order_id
                $where
                GROUP BY purchase_orders.id";
        return parent::getSQL($sql);
    }
}