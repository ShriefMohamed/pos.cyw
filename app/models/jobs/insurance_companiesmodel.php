<?php


namespace Framework\models\jobs;


use Framework\Lib\AbstractModel;

class Insurance_companiesModel extends AbstractModel
{
    public $id;
    public $name;
    public $created;

    protected static $tableName = 'insurance_companies';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'name' => parent::DATA_TYPE_STR
    );

    public static function getCompanies($options = '')
    {
        $sql = "SELECT insurance_companies.*, insurance_companies_emails.id AS email_id, insurance_companies_emails.email
                FROM insurance_companies
                LEFT JOIN insurance_companies_emails ON insurance_companies.id = insurance_companies_emails.insurance_company_id
                $options";
        return parent::getSQL($sql);
    }
}