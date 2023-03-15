<?php


namespace Framework\models;


use Framework\Lib\AbstractModel;

class UsersModel extends AbstractModel
{
    public $id;
    public $firstName;
    public $lastName;
    public $username;
    public $email;
    public $password;
    public $phone;
    public $phone2;
    public $image;
    public $role;
    public $created;
    public $lastUpdate;
    public $lastSeen;
    public $forgotPasswordToken;
    public $forgotPasswordToken_time;
    public $twoFA;
    public $authCode;
    public $authCode_time;
    public $loginAttempts;
    public $loginAttempts_time;

    protected static $tableName = 'users';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'firstName' => self::DATA_TYPE_STR,
        'lastName' => self::DATA_TYPE_STR,
        'username' => self::DATA_TYPE_STR,
        'email' => self::DATA_TYPE_STR,
        'password' => self::DATA_TYPE_STR,
        'phone' => self::DATA_TYPE_STR,
        'phone2' => self::DATA_TYPE_STR,
        'image' => self::DATA_TYPE_STR,
        'role' => self::DATA_TYPE_STR,

        'created' => self::DATA_TYPE_STR,
        'lastUpdate' => self::DATA_TYPE_STR,
        'lastSeen' => self::DATA_TYPE_STR,

        'forgotPasswordToken' => self::DATA_TYPE_STR,
        'forgotPasswordToken_time' => self::DATA_TYPE_STR,
        'twoFA' => self::DATA_TYPE_INT,
        'authCode' => self::DATA_TYPE_INT,
        'authCode_time' => self::DATA_TYPE_STR,
        'loginAttempts' => self::DATA_TYPE_INT,
        'loginAttempts_time' => self::DATA_TYPE_STR
    );

    public static function Authenticate($username, $password)
    {
        $sql = "SELECT users.* 
                FROM " . static::$tableName . " 
                WHERE (users.username = :username OR users.email = :email) AND users.password = :password ";
        return parent::getSQL($sql, array(
            'username' => array(parent::DATA_TYPE_STR, $username),
            'email' => array(parent::DATA_TYPE_STR, $username),
            'password' => array(parent::DATA_TYPE_STR, $password)
        ), true);
    }

    public static function getUser($id)
    {
        $sql = "SELECT users.*, 
                    customers.id AS customer_id, customers.companyName, customers.smsNotifications, customers.emailNotifications,
                    technicians.id AS technician_id, technicians.tags
                FROM users
                LEFT JOIN customers ON users.id = customers.user_id
                LEFT JOIN technicians ON users.id = technicians.user_id
                WHERE users.id = '$id' 
                LIMIT 1";
        return parent::getSQL($sql, '', true);
    }


    public function Search($key): UsersModel
    {
        $this->_sql = "SELECT users.id, users.firstName, users.lastName, users.username, users.email, 
                    users.phone, users.phone2, users.image, users.role,
                    users.created, users.lastUpdate, users.lastSeen
                FROM users 
                WHERE 
                    users.role != 'customer' &&
                    (
                      firstName LIKE '%$key%' || lastName LIKE '%$key%' || 
                      username LIKE '%$key%' || email LIKE '%$key%' || 
                      phone LIKE '%$key%' || phone2 LIKE '%$key%'
                    )";
        return $this;
    }
}