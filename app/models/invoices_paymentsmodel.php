<?php


namespace Framework\models;


use Framework\lib\AbstractModel;

class Invoices_paymentsModel extends AbstractModel
{
    public $id;
    public $invoice_id;
    public $xero_PaymentID;
    public $payment_method;
    public $amount;
    public $created;

    protected static $tableName = 'invoices_payments';
    protected static $pk = 'id';
    protected $tableSchema = array(
        'invoice_id' => self::DATA_TYPE_INT,
        'xero_PaymentID' => self::DATA_TYPE_STR,
        'payment_method' => self::DATA_TYPE_STR,
        'amount' => self::DATA_TYPE_FLOAT,
        'created' => self::DATA_TYPE_STR
    );


    public function getInvoicesPayments($options = ''): Invoices_paymentsModel
    {
        $this->_sql = "SELECT invoices_payments.*,
                            invoices.xero_InvoiceID
                       FROM invoices_payments
                       LEFT JOIN invoices ON invoices_payments.invoice_id = invoices.id 
                       $options";
        $this->_options = $options;
        return $this;
    }

    public static function getExistingPaymentsColumns_byInvIDs($invoices_ids)
    {
        $sql = "SELECT invoices_payments.id, invoices.xero_InvoiceID
                FROM invoices_payments
                LEFT JOIN invoices ON invoices_payments.invoice_id = invoices.id
                WHERE invoices_payments.invoice_id IN ($invoices_ids)";
        return parent::getSQL($sql, '', 'assoc');
    }
}