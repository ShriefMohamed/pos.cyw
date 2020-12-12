<?php


namespace Framework\models\pos;


use Framework\Lib\AbstractModel;

class BrandsModel extends AbstractModel
{
    public $id;
    public $brand;
    public $created;

    protected static $tableName = 'brands';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'brand' => self::DATA_TYPE_STR,
        'created' => self::DATA_TYPE_STR
    );

    public static function getBrandsWithItemsCount($options = '')
    {
        $sql = "SELECT ".static::$tableName.".*, 
                    (SELECT COUNT(items.id) 
                     FROM items
                     WHERE items.brand = brands.id 
                     ) AS items_count
                FROM ".static::$tableName." 
                $options";
        return parent::getSQL($sql);
    }
}