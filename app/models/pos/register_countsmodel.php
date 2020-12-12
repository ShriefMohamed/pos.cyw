<?php


namespace Framework\models\pos;


use Framework\Lib\AbstractModel;

class Register_countsModel extends AbstractModel
{
    public $id;
    public $count_purpose; //open, close, add, payout
    public $method; // cash, payment methods
//    public $status; //confirmed, unconfirmed
//
//    public $bill_100;
//    public $bill_50;
//    public $bill_20;
//    public $bill_10;
//    public $bill_5;
//    public $bill_1;
//    public $bill_25c;
//    public $bill_10c;
//    public $bill_5c;
//    public $bill_1c;
//    public $extra;

    public $total;
    public $notes;
    public $counted_by;
    public $counted;

    protected static $tableName = 'register_counts';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'count_purpose' => self::DATA_TYPE_STR,
        'method' => self::DATA_TYPE_STR,
        'total' => self::DATA_TYPE_FLOAT,
        'notes' => self::DATA_TYPE_STR,
        'counted_by' => self::DATA_TYPE_INT,
        'counted' => self::DATA_TYPE_STR
    );

    public static function getCountsSinceOpen($options = '')
    {
        $sql = "SELECT register_counts.*, payment_methods.method AS method_name 
                FROM register_counts
                LEFT JOIN payment_methods ON register_counts.method = payment_methods.method_key
                $options";
        return parent::getSQL($sql);
    }
}