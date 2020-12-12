<?php


namespace Framework\models\pos;


use Framework\Lib\AbstractModel;

class CategoriesModel extends AbstractModel
{
    public $id;
    public $category;
    public $created;

    protected static $tableName = 'categories';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'category' => self::DATA_TYPE_STR,
        'created' => self::DATA_TYPE_STR
    );

    public static function getCategoriesWithItemsCount($options = '')
    {
        $sql = "SELECT ".static::$tableName.".*, 
                    (SELECT COUNT(items.id) 
                     FROM items
                     WHERE items.category = categories.id 
                     ) AS items_count
                FROM ".static::$tableName." 
                $options";
        return parent::getSQL($sql);
    }
}