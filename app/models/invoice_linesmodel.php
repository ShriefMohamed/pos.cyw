<?php


namespace Framework\models;


use Framework\lib\AbstractModel;

class Invoice_linesModel extends AbstractModel
{
    public $id;
    public $invoice_id;
    public $tax_id;
    public $product_id; //either item_id, or xero_item_id
    public $product_name;
    public $quantity;
    public $unit_price;
    public $discount;
    public $tax;
    public $total;
    public $created;

    protected static $tableName = 'invoice_lines';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'invoice_id' => self::DATA_TYPE_INT,
        'product_id' => self::DATA_TYPE_INT,
        'tax_id' => self::DATA_TYPE_INT,
        'product_name' => self::DATA_TYPE_STR,
        'quantity' => self::DATA_TYPE_INT,
        'unit_price' => self::DATA_TYPE_FLOAT,
        'discount' => self::DATA_TYPE_FLOAT,
        'tax' => self::DATA_TYPE_FLOAT,
        'total' => self::DATA_TYPE_FLOAT,
        'created' => self::DATA_TYPE_STR
    );

    public function getInvoiceLineWithItem($invoice_id): Invoice_linesModel
    {
        $this->_sql = "SELECT invoice_lines.*,
                    items.shop_sku,
                    items_xero_accounts.sales_xero_account_id, items_xero_accounts.sales_xero_account_code,
                    xero_accounts.Name,
                    tax_classes.class, tax_classes.rate
                FROM invoice_lines
                LEFT JOIN items ON items.id = invoice_lines.product_id
                LEFT JOIN items_xero_accounts ON items_xero_accounts.item_id = invoice_lines.product_id
                LEFT JOIN xero_accounts ON xero_accounts.id = items_xero_accounts.sales_xero_account_id
                LEFT JOIN tax_classes ON tax_classes.id = invoice_lines.tax_id
                WHERE invoice_lines.invoice_id = '$invoice_id'";
        return $this;
    }
}