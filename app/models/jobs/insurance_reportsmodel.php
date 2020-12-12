<?php


namespace Framework\models\jobs;


use Framework\Lib\AbstractModel;

class Insurance_reportsModel extends AbstractModel
{
    public $id;
    public $repair_id;
    public $job_id;
    public $company_id;
    public $company_name;
    public $document;
    public $created;

    protected static $tableName = 'insurance_reports';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'repair_id' => parent::DATA_TYPE_INT,
        'job_id' => parent::DATA_TYPE_STR,
        'company_id' => parent::DATA_TYPE_INT,
        'company_name' => parent::DATA_TYPE_STR,
        'document' => parent::DATA_TYPE_STR
    );

}