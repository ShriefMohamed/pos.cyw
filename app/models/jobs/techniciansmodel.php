<?php


namespace Framework\models\jobs;


use Framework\Lib\AbstractModel;

class TechniciansModel extends AbstractModel
{
    public $id;
    public $user_id;
    public $tags;
    public $created;

    protected static $tableName = 'technicians';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'user_id' => self::DATA_TYPE_INT,
        'tags' => self::DATA_TYPE_STR,
        'created' => self::DATA_TYPE_STR
    );

    public static function getTechniciansDetails($options = '', $shift = false)
    {
        $sql = "SELECT users.*, technicians.tags
                FROM users
                LEFT JOIN technicians ON users.id = technicians.user_id
                WHERE users.role = 'technician'
                $options";
        return parent::getSQL($sql, '', $shift);
    }
}