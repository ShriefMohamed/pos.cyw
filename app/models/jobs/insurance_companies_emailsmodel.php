<?php


namespace Framework\models\jobs;


use Framework\Lib\AbstractModel;

class Insurance_companies_emailsModel extends AbstractModel
{
    public $id;
    public $insurance_company_id;
    public $email;

    protected static $tableName = 'insurance_companies_emails';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'insurance_company_id' => parent::DATA_TYPE_INT,
        'email' => parent::DATA_TYPE_STR
    );
}