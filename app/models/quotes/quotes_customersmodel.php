<?php


namespace Framework\models\quotes;


use Framework\lib\AbstractModel;

class Quotes_customersModel extends AbstractModel
{
    public $id;
    public $firstName;
    public $lastName;
    public $email;
    public $phone;
    public $address;
    public $suburb;
    public $zip;
    public $created;

    protected static $tableName = 'quotes_customers';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'firstName' => self::DATA_TYPE_STR,
        'lastName' => self::DATA_TYPE_STR,
        'email' => self::DATA_TYPE_STR,
        'phone' => self::DATA_TYPE_STR,
        'address' => self::DATA_TYPE_STR,
        'suburb' => self::DATA_TYPE_STR,
        'zip' => self::DATA_TYPE_STR,
        'created' => self::DATA_TYPE_STR
    );
}