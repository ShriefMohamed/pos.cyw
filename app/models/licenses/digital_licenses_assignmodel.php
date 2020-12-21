<?php


namespace Framework\models\licenses;


use Framework\lib\AbstractModel;

class Digital_licenses_assignModel extends AbstractModel
{
    public $id;
    public $customer_id;
    public $product_id;
    public $template;
    public $status; // new, expired, renewed
    public $created;
    public $updated;

    protected static $tableName = 'digital_licenses_assign';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'customer_id' => self::DATA_TYPE_INT,
        'product_id' => self::DATA_TYPE_STR,
        'template' => self::DATA_TYPE_STR,
        'status' => self::DATA_TYPE_STR
    );

    public static function getCustomersWithLicenses($where = '')
    {
        $sql = "SELECT users.id AS user_id, users.firstName, users.lastName, users.email, users.phone,
                    customers.id AS customer_id, customers.address, customers.address2, 
                    customers.city, customers.suburb, customers.zip,
                    (SELECT COUNT(digital_licenses_assign.id) 
                     FROM digital_licenses_assign
                     WHERE digital_licenses_assign.customer_id = customers.id && digital_licenses_assign.status != 'expired'
                     ) AS customer_active_licenses,
                     
                    (SELECT COUNT(digital_licenses_assign.id) 
                     FROM digital_licenses_assign
                     WHERE digital_licenses_assign.customer_id = customers.id && digital_licenses_assign.status = 'expired'
                     ) AS customer_expired_licenses
                
                 FROM users
                 LEFT JOIN customers ON users.id = customers.user_id
                 $where
                 HAVING (customer_active_licenses > 0 || customer_expired_licenses > 0)";
        return parent::getSQL($sql);
    }

    public static function getCustomerLicenses($where = '')
    {
        $sql = "SELECT digital_licenses_assign.*, 
                    items.item,
                    digital_licenses_assigned_licenses.id AS assigned_license_id, 
                    digital_licenses_assigned_licenses.license, digital_licenses_assigned_licenses.expiration_date,
                    digital_licenses_assigned_licenses.license_status, digital_licenses_assigned_licenses.created
                FROM digital_licenses_assign
                LEFT JOIN items ON digital_licenses_assign.product_id = items.id
                LEFT JOIN digital_licenses_assigned_licenses ON 
                    digital_licenses_assigned_licenses.license_assign_id = digital_licenses_assign.id
                $where";
        return parent::getSQL($sql);
    }

    public static function getSpecificLicenseAssignAllDetails($license_assign_id, $assigned_licenses_id)
    {
        $sql = "SELECT digital_licenses_assign.*,
                    digital_licenses_assigned_licenses.*,
                    items.item,
                    users.id AS user_id, users.firstName, users.lastName, users.email, users.phone,
                    customers.id AS customer_id, customers.address, customers.address2, 
                    customers.city, customers.suburb, customers.zip, customers.licensesNotifications
                FROM digital_licenses_assign
                LEFT JOIN digital_licenses_assigned_licenses ON
                    digital_licenses_assigned_licenses.license_assign_id = digital_licenses_assign.id && 
                    digital_licenses_assigned_licenses.id = '$assigned_licenses_id'
                LEFT JOIN items ON digital_licenses_assign.product_id = items.id
                LEFT JOIN customers ON digital_licenses_assign.customer_id = customers.id
                LEFT JOIN users ON customers.user_id = users.id
                WHERE digital_licenses_assign.id = '$license_assign_id'";
        return parent::getSQL($sql, '', true);
    }

    public static function getLicenseAssignDetails($license_assign_id)
    {
        $sql = "SELECT digital_licenses_assign.*,
                    digital_licenses_assigned_licenses.license,
                    digital_licenses_assigned_licenses.expiration_years, 
                    digital_licenses_assigned_licenses.expiration_months,
                    digital_licenses_assigned_licenses.expiration_date,
                    items.item,
                    users.id AS user_id, users.firstName, users.lastName, users.email, users.phone,
                    customers.id AS customer_id, customers.address, customers.address2, 
                    customers.city, customers.suburb, customers.zip, customers.licensesNotifications
                FROM digital_licenses_assign
                LEFT JOIN digital_licenses_assigned_licenses ON 
                    digital_licenses_assigned_licenses.license_assign_id = digital_licenses_assign.id
                LEFT JOIN items ON digital_licenses_assign.product_id = items.id
                LEFT JOIN customers ON digital_licenses_assign.customer_id = customers.id
                LEFT JOIN users ON customers.user_id = users.id
                WHERE digital_licenses_assign.id = '$license_assign_id'";
        return parent::getSQL($sql, '', true);
    }

    public static function getLicenseAssign($license_assign_id)
    {
        $sql = "SELECT digital_licenses_assign.*,
                    items.item,
                    users.id AS user_id, users.firstName, users.lastName, users.email, users.phone,
                    customers.id AS customer_id, customers.address, customers.address2, 
                    customers.city, customers.suburb, customers.zip, customers.licensesNotifications
                FROM digital_licenses_assign
                LEFT JOIN items ON digital_licenses_assign.product_id = items.id
                LEFT JOIN customers ON digital_licenses_assign.customer_id = customers.id
                LEFT JOIN users ON customers.user_id = users.id
                WHERE digital_licenses_assign.id = '$license_assign_id'";
        return parent::getSQL($sql, '', true);
    }
}