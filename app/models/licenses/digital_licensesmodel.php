<?php


namespace Framework\models\licenses;


use Framework\lib\AbstractModel;

class Digital_licensesModel extends AbstractModel
{
    public $id;
    public $item_id;
    public $license;
    public $expiration_years;
    public $expiration_months;
    public $template;
    public $created;
    public $used;
    public $expired;

    protected static $tableName = 'digital_licenses';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'item_id' => self::DATA_TYPE_STR,
        'license' => self::DATA_TYPE_STR,
        'expiration_years' => self::DATA_TYPE_INT,
        'expiration_months' => self::DATA_TYPE_INT,
        'template' => self::DATA_TYPE_STR,
        'used' => self::DATA_TYPE_INT,
        'expired' => self::DATA_TYPE_INT
    );

    public static function getLicensesWithItem($where = '', $shift = false)
    {
        $sql = "SELECT digital_licenses.*,
                    items.item
                FROM digital_licenses
                LEFT JOIN items ON digital_licenses.item_id = items.id
                $where";
        return parent::getSQL($sql, '', $shift);
    }

    public static function getItemsWithLicensesSummary()
    {
        $sql = "SELECT items.id, items.uid, items.item, 
                    items_pricing.buy_price, items_pricing.rrp_price, 
                     (SELECT SUM(items_inventory.qoh) 
                      FROM items_inventory
                      WHERE items_inventory.item_id = items.id && items_inventory.qoh != 0
                     ) AS available_stock,
                     
                     SUM(CASE WHEN digital_licenses.item_id = items.id THEN 1 ELSE 0 END) AS licenses_count,
                     SUM(CASE WHEN digital_licenses.item_id = items.id && digital_licenses.used != '1' && digital_licenses.expired != '1' THEN 1 ELSE 0 END) AS available_licenses_count,
                     SUM(CASE WHEN digital_licenses.item_id = items.id && digital_licenses.used = '1' && digital_licenses.expired != '1' THEN 1 ELSE 0 END) AS used_licenses_count,
                     SUM(CASE WHEN digital_licenses.item_id = items.id && digital_licenses.expired = '1' THEN 1 ELSE 0 END) AS expired_licenses_count
                     
                 FROM items
                 LEFT JOIN items_pricing ON items_pricing.id = (
                    SELECT items_pricing.id
                    FROM items_pricing
                    WHERE items.id = items_pricing.item_id
                    ORDER BY items_pricing.id ASC 
                    LIMIT 1)
                LEFT JOIN digital_licenses ON digital_licenses.item_id = items.id
                GROUP BY items.id
                HAVING licenses_count > 0";
        return parent::getSQL($sql);
    }
}