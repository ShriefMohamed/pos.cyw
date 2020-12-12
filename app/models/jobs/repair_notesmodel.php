<?php


namespace Framework\models\jobs;


use Framework\Lib\AbstractModel;

class Repair_notesModel extends AbstractModel
{
    public $id;
    public $repair_id;
    public $user_id;
    public $type;
    public $note;
    public $created;

    protected static $tableName = 'repair_notes';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'repair_id' => self::DATA_TYPE_STR,
        'user_id' => self::DATA_TYPE_INT,
        'type' => self::DATA_TYPE_STR,
        'note' => self::DATA_TYPE_STR,
        'created' => self::DATA_TYPE_STR
    );

    public static function getNotesWithUser($repair_id)
    {
        $sql = "SELECT ".static::$tableName.".*, users.firstName, users.lastName, users.username, users.role
                FROM ".static::$tableName." 
                LEFT JOIN users ON ".static::$tableName.".user_id = users.id
                WHERE ".static::$tableName.".repair_id = '$repair_id' ";
        return parent::getSQL($sql);
    }
}