<?php


namespace Framework\models;


use Framework\lib\AbstractModel;

class InvoicesModel extends AbstractModel
{
    public $id;
    public $reference;

    public $xero_InvoiceID;
    public $xero_InvoiceNumber;

    public $customer_id;
    public $xero_ContactID;

    public $subtotal;
    public $discount;
    public $tax;
    public $total;

    public $amount_due;
    public $amount_paid;

    public $status; //unpaid, semi-paid, paid, voided
    public $notes;

    public $created;
    public $updated;

    public $source; //pos, xero, import

    public $search_keywords;

    protected static $tableName = 'invoices';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'reference' => self::DATA_TYPE_STR,
        'xero_InvoiceID' => self::DATA_TYPE_STR,
        'xero_InvoiceNumber' => self::DATA_TYPE_STR,
        'customer_id' => self::DATA_TYPE_INT,
        'xero_ContactID' => self::DATA_TYPE_STR,
        'subtotal' => self::DATA_TYPE_FLOAT,
        'discount' => self::DATA_TYPE_FLOAT,
        'tax' => self::DATA_TYPE_FLOAT,
        'total' => self::DATA_TYPE_FLOAT,
        'amount_due' => self::DATA_TYPE_FLOAT,
        'amount_paid' => self::DATA_TYPE_FLOAT,
        'status' => self::DATA_TYPE_STR,
        'notes' => self::DATA_TYPE_STR,
        'created' => self::DATA_TYPE_STR,
        'updated' => self::DATA_TYPE_STR,
        'source' => self::DATA_TYPE_STR,
        'search_keywords' => self::DATA_TYPE_STR
    );


    public function getAllInvoicesWithCustomer($options = ''): InvoicesModel
    {
        $this->_sql = "SELECT invoices.*,
                    invoices_orders.order_id,    
                    users.id AS user_id, users.firstName, users.lastName, customers.xero_ContactID, 
                    customers.address, customers.suburb, customers.zip
                FROM invoices
                LEFT JOIN invoices_orders ON invoices_orders.invoice_id = invoices.id
                LEFT JOIN customers ON customers.id = invoices.customer_id || customers.xero_ContactID = invoices.xero_ContactID
                LEFT JOIN users ON users.id = customers.user_id
                $options";
        $this->_options = $options;
        return $this;
    }


    public static function getInvoiceKeywords($invoice_id)
    {
        $sql = "SELECT invoices.reference, invoices.xero_InvoiceID, 
                    invoices.xero_InvoiceNumber, invoices.xero_ContactID, invoices.notes,
                    invoices_orders.order_id, 
       
                    sales.uid, sales.printed_note, sales.internal_note,
                    
                    users.firstName, users.lastName, 
                    customers.companyName, customers.xero_ContactID
       
                FROM invoices
                LEFT JOIN invoices_orders ON invoices_orders.invoice_id = invoices.id
                LEFT JOIN sales ON sales.id = invoices_orders.order_id
                LEFT JOIN customers ON customers.id = invoices.customer_id || customers.xero_ContactID = invoices.xero_ContactID
                LEFT JOIN users ON users.id = customers.user_id

                WHERE invoices.id = '$invoice_id'";
        return parent::getSQL($sql, '', 'assoc');
    }


    public function Search($key, $options = '')
    {
        $search = parent::getColumns(['id'], "search_keywords LIKE '%$key%'");
        if ($search) {
            $items = implode(', ', $search);

            $this->_sql = "SELECT invoices.*,
                        invoices_orders.order_id,    
                        users.id AS user_id, users.firstName, users.lastName, customers.xero_ContactID, 
                        customers.address, customers.suburb, customers.zip
                    FROM invoices
                    LEFT JOIN invoices_orders ON invoices_orders.invoice_id = invoices.id
                    LEFT JOIN customers ON customers.id = invoices.customer_id || customers.xero_ContactID = invoices.xero_ContactID
                    LEFT JOIN users ON users.id = customers.user_id

                    WHERE invoices.id IN ($items)
                    $options
                    GROUP BY invoices.id";
            $this->_options = $options;
        }
        return $this;
    }

}