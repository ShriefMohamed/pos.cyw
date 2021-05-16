<?php if (isset($invoice) && $invoice) : ?>
    <link rel="stylesheet" href="<?= CSS_DIR ?>pos-invoice.css">

    <div id="view" style="display: block;">
        <div class="view">

            <div class="xui-pageheading--content xui-pageheading--content-no-tabs xui-pageheading--content-layout functions">
                <div class="xui-pageheading--leftcontent">
                    <ol class="xui-pageheading--breadcrumbtrail xui-breadcrumbtrail">
                        <li class="xui-breadcrumb">
                            <a href="<?= HOST_NAME ?>pos/sales" class="xui-breadcrumb--link">Sales Dashboard</a>
                        </li>
                        <li class="xui-breadcrumb-arrow">
                            <div class="xui-breadcrumb--icon xui-iconwrapper xui-iconwrapper-medium">
                                <svg class="xui-icon xui-icon-rotate-270" focusable="false" height="4" viewBox="0 0 7 4" width="7">
                                    <path d="M3.5 2.588L.75 0 0 .706 3.5 4 7 .706 6.25 0z" role="presentation"></path>
                                </svg>
                            </div>
                        </li>
                        <li class="xui-breadcrumb">
                            <a href="<?= HOST_NAME ?>pos/invoices" class="xui-breadcrumb--link">Invoices</a>
                        </li>
                    </ol>

                    <div class="xui-pageheading--titlewrapper xui-pageheading--titlewrapper-has-tags">
                        <h1 class="xui-pageheading--title">Invoice</h1>
                        <div class="xui-pageheading--tags">
                        <span class="xui-tag xui-tag-small xui-tag-medium">
                            <span class="xui-tagcontent"><?= strtolower($invoice->status) ?></span>
                        </span>
                        </div>
                    </div>
                </div>

                <div class="xui-pageheading--rightcontent xui-pageheading--rightcontent-no-tabs">
                    <button id="printInvoiceButton" title="Print Receipt"><i class="fa fa-print"></i> Print</button>
                </div>
            </div>
            <div class="main">

                <div class="xui-gridarea-detail xui-width-xsmall-up xui-width-small-up xui-width-medium-up xui-width-large-up">
                    <div class="ReadOnlyInvoice xui-panel">
                        <div class="ReadOnlyInvoice-invoiceDetails xui-panel--section xui-row">
                            <div class="ReadOnlyInvoice-invoiceDetails-wrap xui-column-12-of-12-wide xui-margin-none">
                                <div class="ReadOnlyInvoice-detail ReadOnlyInvoice-contacts">
                                    <h2 class="ReadOnlyInvoice-detailHeading xui-heading-2xsmall">
                                        <span class="ReadOnlyInvoice-detailHeadingText">To</span>
                                        <svg class="xui-icon ReadOnlyInvoice-detailHeadingIcon xui-margin-horizontal-small" focusable="false" height="12" viewBox="0 0 12 12" width="12">
                                            <path d="M6 6a3 3 0 1 0 0-6 3 3 0 1 0 0 6zm-6 5v1h12v-1c0-2.5-2.5-4-6-4s-6 1.5-6 4z" role="presentation"></path>
                                        </svg>
                                    </h2>
                                    <a href="<?= $invoice->user_id ? HOST_NAME.'customers/customer/'.$invoice->user_id : '#' ?>" target="_blank" rel="noopener"><?= $invoice->firstName.' '.$invoice->lastName ?></a>
                                </div>
                                <div class="ReadOnlyInvoice-detail ReadOnlyInvoice-invoiceNumber">
                                    <h2 class="ReadOnlyInvoice-detailHeading xui-heading-2xsmall">
                                        <span class="ReadOnlyInvoice-detailHeadingText">Invoice ID</span>
                                        <svg class="xui-icon ReadOnlyInvoice-detailHeadingIcon xui-margin-horizontal-small" focusable="false" height="12" viewBox="0 0 13 12" width="13">
                                            <path d="M7.96 7.028l.657-2.556H5.198l-.64 2.556H7.96zm1.412 0h2.897l-.5 1.444H9.01l-.882 3.52-1.448.014.908-3.534H4.195l-.882 3.52-1.448.014.909-3.534H-.231l.5-1.444h2.876l.657-2.556H.732l.5-1.444h2.942l.774-3.01L6.32-.006l-.76 3.034h3.43l.771-3 1.375-.034-.76 3.034h2.856l-.5 1.444h-2.718l-.64 2.556z" role="presentation"></path>
                                        </svg>
                                    </h2>
                                    #<?= $invoice->id ?>
                                </div>
                                <div class="ReadOnlyInvoice-detail ReadOnlyInvoice-reference">
                                    <h2 class="ReadOnlyInvoice-detailHeading xui-heading-2xsmall">
                                        <span class="ReadOnlyInvoice-detailHeadingText">Reference</span>
                                        <svg class="xui-icon ReadOnlyInvoice-detailHeadingIcon xui-margin-horizontal-small" focusable="false" height="12" viewBox="0 0 10 12" width="10">
                                            <path d="M1 0h8c.5 0 1 .5 1 1v11L5 9l-5 3V1c0-.5.5-1 1-1z" role="presentation"></path>
                                        </svg>
                                    </h2>
                                    #<?= $invoice->reference ?>
                                </div>
                                <div class="ReadOnlyInvoice-detail ReadOnlyInvoice-date">
                                    <h2 class="ReadOnlyInvoice-detailHeading xui-heading-2xsmall">
                                        <span class="ReadOnlyInvoice-detailHeadingText">Issue date</span>
                                        <svg class="xui-icon ReadOnlyInvoice-detailHeadingIcon xui-margin-horizontal-small" focusable="false" height="13" viewBox="0 0 12 13" width="12">
                                            <path d="M10 1V0H8v1H4V0H2v1H1C.5 1 0 1.5.01 2L0 12c0 .5.5 1 1 1h10c.5 0 1-.5 1-1V2c0-.5-.5-1-1-1h-1zM1 12V4h10v8H1zm1-7h3v3H2V5z" role="presentation"></path>
                                        </svg>
                                    </h2>
                                    <span class="ReadOnlyInvoice-dateHeading">Issued&nbsp;</span><?= (new \DateTime($invoice->created))->format('D, d M Y') ?>
                                </div>
                            </div>
                        </div>

                        <div class="InvoiceGrid-separatorTitle">Item / description</div>
                        <div class="ReadOnlyLineItems" role="table">
                            <div class="ReadOnlyLineItems--header xui-u-flex xui-margin-none xui-padding-bottom-small" role="row">
                                <span class="ReadOnlyLineItem--column xui-text-label">Item / description</span>
                                <span class="ReadOnlyLineItem--column xui-text-label xui-text-align-right xui-u-hidden-narrow">Quantity</span>
                                <span class="ReadOnlyLineItem--column xui-text-label xui-text-align-right xui-u-hidden-narrow">Price</span>
                                <span class="ReadOnlyLineItem--column xui-text-label xui-text-align-right xui-u-hidden-narrow">Discount</span>
                                <span class="ReadOnlyLineItem--column xui-text-label xui-text-align-right xui-u-hidden-narrow">Tax amount</span>
                                <span class="ReadOnlyLineItem--column xui-text-label xui-text-align-right">Amount</span>
                            </div>
                            <div class="ReadOnlyLineItems--body" role="rowgroup">
                                <?php if (isset($invoice_lines) && $invoice_lines) : ?>
                                    <?php foreach ($invoice_lines as $invoice_line) : ?>
                                        <div class="ReadOnlyLineItem" role="row">
                                            <div class="xui-u-flex">
                                                <div class="ReadOnlyLineItem--column xui-margin-none">
                                                    <p class="ReadOnlyLineItem--description xui-margin-none" role="cell"><?= $invoice_line->product_name ?></p>
                                                </div>
                                                <p class="ReadOnlyLineItem--column xui-margin-none xui-u-hidden-narrow" role="cell"><?= $invoice_line->quantity ?></p>
                                                <p class="ReadOnlyLineItem--column xui-margin-none xui-u-hidden-narrow" role="cell"><?= number_format($invoice_line->unit_price, 2) ?></p>
                                                <p class="ReadOnlyLineItem--column xui-margin-none xui-u-hidden-narrow" role="cell">-<?= number_format($invoice_line->discount, 2) ?></p>
                                                <p class="ReadOnlyLineItem--column xui-margin-none xui-u-hidden-narrow" role="cell"><?= number_format($invoice_line->tax, 2) ?></p>
                                                <p class="ReadOnlyLineItem--column xui-margin-none" role="cell"><?= number_format($invoice_line->total, 2) ?></p>
                                            </div>
                                            <div class="ReadOnlyLineItem--details xui-text-minor">
                                                <span class="ReadOnlyLineItem--detail invoice-hiddenDesktop" role="cell"><?= $invoice_line->quantity ?>
                                                    <span class="xui-margin-horizontal-2xsmall">×</span><?= number_format($invoice_line->unit_price, 2) ?>
                                                </span>
                                                <span class="ReadOnlyLineItem--accountCode ReadOnlyLineItem--detail" role="cell">Account:
                                                    <span><?= $invoice_line->Name ?></span>
                                                </span>
                                                <span class="ReadOnlyLineItem--taxType ReadOnlyLineItem--detail" role="cell">Tax Rate:
                                                    <span><?= $invoice_line->class && $invoice_line->rate ? $invoice_line->class.' ('.$invoice_line->rate.'%)' : 'None' ?></span>
                                                </span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="InvoiceFooter">
                            <div class="InvoiceFooter-content">
                                <div class="InvoiceFooter-totals">
                                    <div class="InvoiceFooter-subTotal xui-row-flex xui-margin-bottom-2xsmall">
                                    <span class="xui-column-6-of-12">
                                        <span>Subtotal incl. tax</span>
                                    </span>
                                        <span class="xui-column-6-of-12 xui-text-align-right"><?= number_format($invoice->subtotal, 2) ?></span>
                                    </div>

                                    <div class="InvoiceFooter-tax xui-row-flex">
                                        <span class="xui-column-6-of-12">Total Discount</span>
                                        <span class="xui-column-6-of-12 xui-text-align-right">-<?= number_format($invoice->discount, 2) ?></span>
                                    </div>
                                    <div class="InvoiceFooter-tax xui-row-flex">
                                        <span class="xui-column-6-of-12">Total Tax</span>
                                        <span class="xui-column-6-of-12 xui-text-align-right"><?= number_format($invoice->tax, 2) ?></span>
                                    </div>
                                    <div class="TotalDeductions-invoiceTotal xui-row-flex">
                                        <span class="xui-column-6-of-12">Total</span>
                                        <span class="xui-column-6-of-12 xui-text-align-right"><?= number_format($invoice->total, 2) ?></span>
                                    </div>
                                    <?php if ($invoice->source != 'xero') : ?>
                                    <div class="TotalDeductions-deductions">
                                        <div class="TotalDeductions-payments TotalDeductions-deduction">
                                            <div class="xui-row-flex xui-margin-bottom-2xsmall">
                                                <div class="xui-column-8-of-12">
                                                    <a href="<?= $invoice->amount_paid > 0 ? HOST_NAME.'pos/sale/'.$invoice->order_id.'#tabs-menu-payments-link' : '#' ?>" target="_blank">Payments</a>
                                                </div>
                                                <div class="xui-column-4-of-12 xui-text-align-right">
                                                    <a href="<?= $invoice->amount_paid > 0 ? HOST_NAME.'pos/sale/'.$invoice->order_id.'#tabs-menu-payments-link' : '#' ?>" target="_blank"><?= number_format($invoice->amount_paid, 2) ?></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <div class="InvoiceFooter-total">Amount due
                                        <span class="InvoiceFooter-amountDueNumber xui-text-align-right xui-padding-left xui-heading-xlarge">
                                        <span class="xui-padding-left xui-text-emphasis"><?= number_format($invoice->amount_due, 2) ?></span>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="PageFooter xui-margin-vertical-large">
                        <div class="PageFooter--left">
                            <button class="xui-button HistoryToggleButton xui-button-borderless-main xui-button-small" tabindex="0" type="button">
                                <svg class="xui-icon xui-margin-right-small" focusable="false" height="14" viewBox="0 0 16 14" width="16">
                                    <path d="M2 7c0-3.873 3.127-7 7-7s7 3.127 7 7-3.127 7-7 7a6.978 6.978 0 0 1-4.917-2.012l1.061-1.061A5.482 5.482 0 0 0 9 12.5c3.043 0 5.5-2.457 5.5-5.5S12.043 1.5 9 1.5A5.493 5.493 0 0 0 3.5 7H6l-3 3-3-3h2zm6-3h1v3h3v1H8V4z" role="presentation"></path>
                                </svg>
                                Show history and notes
                            </button>
                            <button class="xui-button AddNoteButton xui-margin-left-small xui-button-borderless-main xui-button-small" tabindex="0" type="button">
                                <svg class="xui-icon xui-margin-right-small" focusable="false" height="12" viewBox="0 0 12 12" width="12">
                                    <path d="M7 5h5v2H7v5H5V7H0V5h5V0h2v5z" role="presentation"></path>
                                </svg>
                                Add note
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="invoice-modal" style="display: none !important">
        <div class="modal-dialog">
            <div class="modal-content">
                <iframe id="invoice_preview_iframe" name="invoice_preview_iframe" src="" height="400px" style="border: 1px solid rgb(136, 136, 136);padding: 10px"></iframe>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#printInvoiceButton').on('click', function (e) {
                // $("#invoice-modal").modal();
                document.getElementById("invoice_preview_iframe").src = "<?= HOST_NAME.'pos/invoice_receipt/'.$invoice->id ?>";
                window.frames["invoice_preview_iframe"].focus();
                setTimeout(function () {
                    window.frames["invoice_preview_iframe"].print();
                }, 1000);
            });
        });
    </script>
<?php endif; ?>
