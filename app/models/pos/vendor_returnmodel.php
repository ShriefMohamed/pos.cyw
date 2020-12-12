<?php


namespace Framework\models\pos;


use Framework\lib\AbstractModel;

class Vendor_returnModel extends AbstractModel
{
    public $id;
    public $vendor_id;
    public $reference;
    public $notes;

    public $return_value;
    public $shipping;
    public $other;
    public $total;

    public $status; // open, sent, closed, archived
    public $sending_date;
    public $closing_date;

    public $created;
    public $created_by;

    protected static $tableName = 'vendor_returns';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'vendor_id' => self::DATA_TYPE_INT,
        'reference' => self::DATA_TYPE_STR,
        'notes' => self::DATA_TYPE_STR,
        'return_value' => self::DATA_TYPE_FLOAT,
        'shipping' => self::DATA_TYPE_FLOAT,
        'other' => self::DATA_TYPE_FLOAT,
        'total' => self::DATA_TYPE_FLOAT,

        'status' => self::DATA_TYPE_STR,
        'sending_date' => self::DATA_TYPE_STR,
        'closing_date' => self::DATA_TYPE_STR,

        'created_by' => self::DATA_TYPE_INT,
        'created' => self::DATA_TYPE_STR
    );

    public static function getVendorReturns($options = '')
    {
        $sql = "SELECT vendor_returns.*,
                    vendors.name AS vendor_name,
                    CONCAT(users.firstName,' ', users.lastName) AS admin_name,
                    (SELECT SUM(vendor_return_items.quantity) 
                     FROM vendor_return_items
                     WHERE vendor_returns.id = vendor_return_items.vendor_return_id
                     ) AS total_returned
                FROM vendor_returns
                LEFT JOIN vendors ON vendor_returns.vendor_id = vendors.id
                LEFT JOIN users ON vendor_returns.created_by = users.id
                LEFT JOIN vendor_return_items ON vendor_returns.id = vendor_return_items.vendor_return_id
                $options";
        return parent::getSQL($sql);
    }

    public static function getVendorReturn($id)
    {
        $sql = "SELECT vendor_returns.*,
                    vendors.name AS vendor_name,
                    CONCAT(users.firstName,' ', users.lastName) AS admin_name
                FROM vendor_returns
                LEFT JOIN vendors ON vendor_returns.vendor_id = vendors.id
                LEFT JOIN users ON vendor_returns.created_by = users.id
                WHERE vendor_returns.id = '$id'";
        return parent::getSQL($sql, '', true);
    }
}