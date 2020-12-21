<?php


namespace Framework\models\licenses;


use Framework\lib\AbstractModel;

class Digital_licenses_templatesModel extends AbstractModel
{
    public $id;
    public $template_name; // subscription-about-to-expire, Subscription-expired
    public $template;
    public $created;

    protected static $tableName = 'digital_licenses_templates';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'template_name' => self::DATA_TYPE_STR,
        'template' => self::DATA_TYPE_STR,
    );

    public function getTemplatesWithProducts()
    {
        $sql = "SELECT digital_licenses_templates.*,
                   (SELECT GROUP_CONCAT(DISTINCT items.item SEPARATOR ' | ')
                    FROM digital_licenses
                    LEFT JOIN items ON digital_licenses.item_id = items.id
                    WHERE digital_licenses.template = digital_licenses_templates.template_name
                    LIMIT 1
                   ) AS products
                FROM digital_licenses_templates
                ";
        return parent::getSQL($sql);
    }
}