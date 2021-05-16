<?php


namespace Framework\models;


use Framework\lib\AbstractModel;

class Np_pdf_ordersModel extends AbstractModel
{
    public $id;
    public $code;
    public $details;
    public $quantity;

    protected static $tableName = 'np_pdf_orders';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'code' => self::DATA_TYPE_STR,
        'details' => self::DATA_TYPE_STR,
        'quantity' => self::DATA_TYPE_INT
    );


}