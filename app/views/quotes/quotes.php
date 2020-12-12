<div class="page-section">
    <section class="card card-fluid">
        <header class="card-header">
            Saved Quotes

            <div class="btn-group" style="float: right">
                <a href="<?= HOST_NAME ?>pos/quotes" class="btn btn-success" target="_blank">Approved Quote</a>
                <a href="<?= HOST_NAME ?>quotes/quote_add" class="btn btn-info">Create Quote</a>
            </div>
        </header>
        <div class="card-body">

            <table cellpadding="0" cellspacing="0" border="0" class="g-datatable datatable table table-striped table-bordered">
                <thead>
                <tr>
                    <th width="15%">Quote ID</th>
                    <th>Customer</th>
                    <th>QTY.</th>
                    <th>DBP System</th>
                    <th>Mark up %</th>
                    <th>System Total</th>
                    <th>Total</th>
                    <th>Viewed</th>
                    <th>Created</th>
                    <th>Status</th>
                    <th style="width: 12%"></th>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($data) && $data !== false) : ?>
                    <?php foreach ($data as $item) : ?>
                        <tr class="gradeX">
                            <td><a href="<?= HOST_NAME.'quotes/quote/'.$item->id ?>"><?= $item->uid ?></a></td>
                            <td><?= $item->customer_name ?></td>
                            <td><?= $item->items_count ?></td>
                            <td>$<?= number_format($item->DBP, 2) ?></td>
                            <td><?= $item->margin ?>%</td>
                            <td>
                                <strong>$<?= number_format($item->system_total, 2) ?></strong>
                            </td>
                            <td>
                                <strong>$<?= number_format($item->total, 2) ?></strong>
                            </td>
                            <td><?= $item->viewed == 1 ? 'Yes' : 'No' ?></td>
                            <td><?= \Framework\lib\Helper::ConvertDateFormat($item->created, true) ?></td>
                            <td><?= ucfirst($item->status) ?></td>
                            <td style="display: flex;justify-content: space-between;">
                                <?php if ($item->status == 'approved') : ?>
                                    <a href="<?= HOST_NAME.'pos/quote/'.$item->id ?>" target="_blank" title="View @ POS"><i class="fa fa-reply"></i></a>
                                <?php endif; ?>
                                <?php if ($item->status == 'sent') : ?>
                                    <a href="<?= HOST_NAME.'quotes/quote_approve/'.$item->id ?>" title="Approve"><i class="fa fa-check"></i></a>
                                <?php endif; ?>
                                <a href="<?= HOST_NAME.'index/quote_preview/'.$item->id ?>" target="_blank" title="Preview Online"><i class="fa fa-eye"></i></a>
                                <a href="<?= QUOTES_DIR.$item->uid.'.pdf' ?>" target="_blank" title="View PDF"><i class="fa fa-file-pdf"></i></a>
                                <a href="<?= HOST_NAME.'quotes/quote_send/'.$item->id ?>" title="Send Quote"><i class="fa fa-envelope"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
