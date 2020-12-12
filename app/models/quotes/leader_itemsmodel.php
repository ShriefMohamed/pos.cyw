<?php


namespace Framework\models\quotes;


use Framework\lib\AbstractModel;

class Leader_itemsModel extends AbstractModel
{
    public $id;
    public $ProductName;
    public $Description;
    public $StockCode;
    public $CategoryCode;
    public $CategoryName;
    public $SubcategoryName;
    public $BarCode;
    public $Manufacturer;
    public $ManufacturerSKU;

    public $DBP_Excl;
    public $DBP;
    public $RRP_Excl;
    public $RRP;
    public $NetoRRP;

    public $IMAGE;

    public $Length;
    public $Width;
    public $Height;
    public $Weight;
    public $WarrantyLength;

    public $AT;
    public $AA;
    public $AQ;
    public $AN;
    public $AV;
    public $AW;
    public $ETAA;
    public $ETAQ;
    public $ETAN;
    public $ETAV;
    public $ETAW;

    protected static $tableName = 'leader_items';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'CategoryCode' => self::DATA_TYPE_STR,
        'CategoryName' => self::DATA_TYPE_STR,
        'SubcategoryName' => self::DATA_TYPE_STR,
        'StockCode' => self::DATA_TYPE_STR,
        'BarCode' => self::DATA_TYPE_INT,
        'ManufacturerSKU' => self::DATA_TYPE_STR,
        'ProductName' => self::DATA_TYPE_STR,
        'Description' => self::DATA_TYPE_STR,
        'WarrantyLength' => self::DATA_TYPE_STR,
        'Manufacturer' => self::DATA_TYPE_STR,
        'DBP_Excl' => self::DATA_TYPE_FLOAT,
        'DBP' => self::DATA_TYPE_FLOAT,
        'RRP_Excl' => self::DATA_TYPE_FLOAT,
        'RRP' => self::DATA_TYPE_FLOAT,
        'NetoRRP' => self::DATA_TYPE_FLOAT,
        'IMAGE' => self::DATA_TYPE_STR,
        'Length' => self::DATA_TYPE_INT,
        'Width' => self::DATA_TYPE_INT,
        'Height' => self::DATA_TYPE_INT,
        'Weight' => self::DATA_TYPE_INT,
        'AT' => self::DATA_TYPE_STR,
        'AA' => self::DATA_TYPE_STR,
        'AQ' => self::DATA_TYPE_STR,
        'AN' => self::DATA_TYPE_STR,
        'AV' => self::DATA_TYPE_STR,
        'AW' => self::DATA_TYPE_STR,
        'ETAA' => self::DATA_TYPE_STR,
        'ETAQ' => self::DATA_TYPE_STR,
        'ETAN' => self::DATA_TYPE_STR,
        'ETAV' => self::DATA_TYPE_STR,
        'ETAW' => self::DATA_TYPE_STR
    );

    public static function getStockCode()
    {
        $sql = "SELECT id, StockCode FROM leader_items";
        $res = parent::getSQL($sql);
        $items = [];
        if ($res) {
            foreach ($res as $item) {
                $items[$item->StockCode][] = $item->id;
            }
        }
        return $items;
    }

    public static function getCategories($options = '')
    {
        $sql = "SELECT CategoryName FROM leader_items $options GROUP BY CategoryName";
        return parent::getSQL($sql, '', 'column');
    }

    public static function getSubCategories($options = '')
    {
        $sql = "SELECT SubcategoryName FROM leader_items $options GROUP BY SubcategoryName";
        return parent::getSQL($sql, '', 'column');
    }

    public static function getManufacturers($options = '')
    {
        $sql = "SELECT Manufacturer FROM leader_items $options GROUP BY Manufacturer";
        return parent::getSQL($sql, '', 'column');
    }

    public static function getFilteredItems($options = '')
    {
        $sql = "SELECT leader_items.*
                FROM leader_items
                $options";
        return parent::getSQL($sql);
    }
}