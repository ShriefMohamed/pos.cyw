<?php


namespace Framework\models\jobs;


use Framework\Lib\AbstractModel;

class Repair_stagesModel extends AbstractModel
{
    public $id;
    public $repair_id;
    public $job_id;
    public $stage_id;
    public $status;
    public $created;
    public $ended;

    protected static $tableName = 'repair_stages';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'repair_id' => self::DATA_TYPE_INT,
        'job_id' => self::DATA_TYPE_STR,
        'stage_id' => self::DATA_TYPE_INT,
        'status' => self::DATA_TYPE_INT,
        'created' => self::DATA_TYPE_STR,
        'ended' => self::DATA_TYPE_STR
    );

    public static function getRepairStages($id, $options = '', $shift = false)
    {
        $sql = "SELECT ".static::$tableName.".*, stages.stage,
                    repair_stages_actions.action, repair_stages_actions.created AS repair_stages_action_date
                FROM ".static::$tableName."
                LEFT JOIN stages ON ".static::$tableName.".stage_id = stages.id
                LEFT JOIN repair_stages_actions ON 
                    repair_stages.repair_id = repair_stages_actions.repair_id AND
                    repair_stages.stage_id = repair_stages_actions.stage_id AND
                    repair_stages.id = repair_stages_actions.repair_stage_id
                WHERE ".static::$tableName.".repair_id = '$id' 
                $options ";
        return parent::getSQL($sql, '', $shift);
    }

    public static function getStages_duration($options = '')
    {
        $sql = "SELECT repair_stages.*,
                    stages.stage, 
                    repair_stages_duration.id AS stage_duration_id, repair_stages_duration.duration, 
                    repair_stages_duration.paused, repair_stages_duration.continued 
                FROM repair_stages
                LEFT JOIN repair_stages_duration ON repair_stages.id = repair_stages_duration.repair_stage_id
                LEFT JOIN stages ON repair_stages.stage_id = stages.id
                $options";
        return parent::getSQL($sql);
    }

    public static function getRepairsCountByStage()
    {
        $sql = "SELECT COUNT(repairs.id) AS count, stages.stage 
                FROM repairs
                LEFT JOIN repair_stages ON repairs.id = repair_stages.repair_id
                LEFT JOIN stages ON repair_stages.stage_id = stages.id
                WHERE repair_stages.status = 1 && 
                    repair_stages.ended IS NULL &&
                    repairs.status = 'active'
                GROUP BY stages.stage";
        return parent::getSQL($sql);
    }

    public static function getRepairsCompletedCount($period = 'd')
    {
        if ($period == 'w') {
            $date = date('Y-m-d', strtotime('-7 days'));
            $period_filter = "repair_stages.created >= '$date 00:00:00'";
        } elseif ($period == 'm') {
            $date = date('Y-m-d', strtotime('-30 days'));
            $period_filter = "repair_stages.created >= '$date 00:00:00'";
        } else {
            $period_filter = "repair_stages.created BETWEEN '".date('Y-m-d')." 00:00:00' AND '".date('Y-m-d H:i:s')."'";
        }

        $sql = "SELECT COUNT(repairs.id) AS count, stages.stage
                FROM repairs
                LEFT JOIN repair_stages ON repairs.id = repair_stages.repair_id
                LEFT JOIN stages ON repair_stages.stage_id = stages.id
                WHERE stages.stage LIKE '%completed%' &&
                    $period_filter ";

        return parent::getSQL($sql, '', true);
    }

    public static function getAVGStagesDuration()
    {
        $sql = "SELECT repair_stages.repair_id, repair_stages.created,
                    stages.stage,
                    repair_stages_duration.duration
                FROM repair_stages
                LEFT JOIN stages ON repair_stages.stage_id = stages.id
                LEFT JOIN repair_stages_duration ON repair_stages.id = repair_stages_duration.repair_stage_id
                ORDER BY repair_stages.repair_id, repair_stages.created";
        return parent::getSQL($sql);
    }
}