<?php


namespace Framework\models\jobs;


use Framework\Lib\AbstractModel;

class Repair_quotesModel extends AbstractModel
{
    public $id;
    public $repair_id;
    public $user_id;
    public $item;
    public $cost;
    public $quote;
    public $created;

    protected static $tableName = 'repair_quotes';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'repair_id' => self::DATA_TYPE_INT,
        'user_id' => self::DATA_TYPE_INT,
        'item' => self::DATA_TYPE_STR,
        'cost' => self::DATA_TYPE_FLOAT,
        'quote' => self::DATA_TYPE_FLOAT,
        'created' => self::DATA_TYPE_STR
    );

    public static function getRepairQuotesActions($repair_id)
    {
        $sql = "SELECT repair_quotes.id AS quote_id, repair_quotes.item, repair_quotes.cost, repair_quotes.quote,
                    repair_quotes_actions.action, repair_quotes_actions.created,
                    users.firstName, users.lastName, users.username, users.role
                FROM repair_quotes
                LEFT JOIN repair_quotes_actions ON repair_quotes.id = repair_quotes_actions.repair_quote_id
                LEFT JOIN users ON repair_quotes_actions.approved_by = users.id
                WHERE repair_quotes.repair_id = '$repair_id'";
        return parent::getSQL($sql);
    }
}