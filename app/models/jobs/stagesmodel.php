<?php


namespace Framework\models\jobs;


use Framework\Lib\AbstractModel;

class StagesModel extends AbstractModel
{
    public $id;
    public $stage;
    public $created;

    protected static $tableName = 'stages';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'stage' => self::DATA_TYPE_STR,
        'created' => self::DATA_TYPE_STR
    );

    public static function getStagesWithJobCount($options = '')
    {
        $sql = "SELECT ".static::$tableName.".*, 
                    (SELECT COUNT(repair_stages.id) 
                     FROM repair_stages
                     WHERE repair_stages.stage_id = stages.id && repair_stages.status = 1 
                     ) AS jobs_count
                FROM ".static::$tableName." 
                $options";
        return parent::getSQL($sql);
    }
}