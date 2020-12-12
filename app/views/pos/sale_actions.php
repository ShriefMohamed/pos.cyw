<?php if (isset($sale) && $sale !== false) : ?>
<div id="register">
    <?php if (!$sale->total > 0 && ($sale->total_paid - $sale->total) > 0) : ?>
    <div id="notificationBarTypePermanent" class="alert highlight big">
        <p id="popdisplay_change">
            Change: <var>$<?= number_format($sale->total_paid - $sale->total, 2) ?></var>
        </p>
    </div>
    <?php endif; ?>

    <article id="sale_complete_next_section" class="section">
        <h2>Next Step</h2>
        <p id="sale_complete_next_section_explanation">The transaction has been recorded. Next you probably want to do one of the following:</p>
        <ul class="options">
            <li><button onclick="window.frames['receipt_preview_iframe'].focus(); window.frames['receipt_preview_iframe'].print(); event.preventDefault();" id="printReceiptButton"><i class="fa fa-print"></i><span>Print Receipt</span></button></li>
            <li><a href="<?= HOST_NAME.'pos/sale_email_receipt/'.$sale->id ?>"><button id="emailReceiptButton"><i class="fa fa-envelope"></i><span>Email Receipt</span></button></a></li>
            <li>
                <a href="<?= HOST_NAME ?>pos/sale_add"><button id="newSaleButton"><i class="fa fa-plus"></i><span>New Sale</span></button></a>
                <p>Start a new sale.</p>
            </li>
        </ul>
    </article>

    <div id="print_holder">
        <iframe id="receipt_preview_iframe" name="receipt_preview_iframe" src="<?= HOST_NAME.'pos/sale_receipt/'.$sale->id ?>" width="100%" height="400px" style="max-width: 600px; border: 1px solid rgb(136, 136, 136); padding: 10px; margin: 20px;"></iframe>
    </div>
</div>
<style>
    .page-inner {padding-top: 4px}
</style>
<?php endif; ?>