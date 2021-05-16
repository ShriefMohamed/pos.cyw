<div class="page-section">
    <section class="card card-fluid">
        <div class="listing">
            <header style="margin-bottom: 2rem;">
                <header class="card-header">
                    Invoices
                </header>
            </header>
            <div id="work_listing">
                <div id="listing_loc_matches">
                    <div class="container" style="max-width: 100%">
                        <?php if (isset($invoices) && $invoices !== false) : ?>
                        <table cellpadding="0" cellspacing="0" border="0" class="default-datatable datatable table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th width="15%">#ID</th>
                                <th>#REF</th>
                                <th>CUSTOMER</th>
                                <th>DATE</th>
                                <th>SUBTOTAL</th>
                                <th>DISCOUNT</th>
                                <th>TAX</th>
                                <th>TOTAL</th>
                                <th>PAID</th>
                                <th>DUE</th>
                                <th>STATUS</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (isset($invoices->data) && $invoices->data) : ?>
                                <?php foreach ($invoices->data as $item) : ?>
                                    <tr class="gradeX">
                                        <td><a href="<?= HOST_NAME . 'pos/invoice/' . $item->id ?>">#<?= $item->id ?></a></td>
                                        <td><a href="<?= HOST_NAME . 'pos/invoice/' . $item->id ?>">#<?= $item->reference ?></a></td>
                                        <td><a target="_blank" href="<?= $item->user_id ? HOST_NAME.'customers/customer/'.$item->user_id : '#' ?>"><?= $item->firstName.' '.$item->lastName ?></a></td>
                                        <td class="date ">
                                            <time datetime="<?= $item->created ?>"><?= \Framework\lib\Helper::ConvertDateFormat($item->created) ?></time>
                                        </td>
                                        <td>$<?= number_format($item->subtotal, 2) ?></td>
                                        <td>$<?= number_format($item->discount, 2) ?></td>
                                        <td>$<?= number_format($item->tax, 2) ?></td>
                                        <td>$<?= number_format($item->total, 2) ?></td>
                                        <td>$<?= number_format($item->amount_paid, 2) ?></td>
                                        <td>$<?= number_format($item->amount_due, 2) ?></td>
                                        <td><?= strtoupper($item->status) ?></td>

                                        <td class="center">
                                            <?php if ($item->status != 'voided') : ?>
                                                <?php if ($item->status == 'unpaid' || $item->status == 'semi-paid') : ?>
                                                    <!--<a href="<?= HOST_NAME.'pos/sales_payments/'.$item->id ?>" style="margin-left: 15px;">Pay</a>-->
                                                <?php endif; ?>

                                                <a href="<?= HOST_NAME.'pos/invoice_void/'.$item->id ?>">Void</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>

                        <?= $invoices->pagination ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .page-inner {padding-right: 0;padding-left: 0;}
    .btn-success {color: #589141;background: #dbebd6;border-radius: 0;border: solid 1px #CCC}
    .btn-success:hover {background: #bddab4;color: #436e31;border-color: #6f6f6f;}
</style>