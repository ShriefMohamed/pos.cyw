<?php


namespace Framework\models\pos;


use Framework\Lib\AbstractModel;

class VendorsModel extends AbstractModel
{
    public $id;
    public $name;
    public $account_number;
    public $contact_f_name;
    public $contact_l_name;
    public $contact_phone;
    public $contact_mobile;
    public $address;
    public $address2;
    public $city;
    public $suburb;
    public $zip;
    public $website;
    public $email;
    public $notes;
    public $created;
    public $updated;

    protected static $tableName = 'vendors';
    protected static $pk = 'id';

    protected $tableSchema = array(
        'name' => self::DATA_TYPE_STR,
        'account_number' => self::DATA_TYPE_STR,
        'contact_f_name' => self::DATA_TYPE_STR,
        'contact_l_name' => self::DATA_TYPE_STR,
        'contact_phone' => self::DATA_TYPE_STR,
        'contact_mobile' => self::DATA_TYPE_STR,
        'address' => self::DATA_TYPE_STR,
        'address2' => self::DATA_TYPE_STR,
        'city' => self::DATA_TYPE_STR,
        'suburb' => self::DATA_TYPE_STR,
        'zip' => self::DATA_TYPE_STR,
        'website' => self::DATA_TYPE_STR,
        'email' => self::DATA_TYPE_STR,
        'notes' => self::DATA_TYPE_STR,
        'created' => self::DATA_TYPE_STR,
        'updated' => self::DATA_TYPE_STR
    );
}