<div class="page-section">
    <section class="card card-fluid">
        <div class="listing">
            <header style="margin-bottom: 2rem;">
                <header class="card-header">
                    Quotes History

                    <a href="<?= HOST_NAME ?>quotes/quote_add" class="btn btn-success" style="float: right">Create Quote</a>
                </header>
            </header>
            <div id="work_listing">
                <div id="listing_loc_matches">
                    <div class="container" style="max-width: 100%">

                        <table cellpadding="0" cellspacing="0" border="0" class="g-datatable datatable table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th width="15%">Quote ID</th>
                                <th>CUSTOMER</th>
                                <th>DATE</th>
                                <th>QTY.</th>
                                <th>System DBP</th>
                                <th>System Total</th>
                                <th>TAX</th>
                                <th>TOTAL</th>
                                <th>STATUS</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (isset($data) && $data !== false) : ?>
                                <?php foreach ($data as $item) : ?>
                                    <tr class="gradeX">
                                        <td><a href="<?= HOST_NAME . 'pos/quote/' . $item->id ?>"><?= $item->uid ?></a></td>
                                        <td><a title="Edit Record" href="<?= $item->customer_id ? HOST_NAME.'customers/customer/'.$item->customer_id : '#' ?>"><span><?= $item->customer_name ?: '' ?></span></a></td>
                                        <td class="date ">
                                            <time datetime="<?= $item->created ?>"><?= \Framework\lib\Helper::ConvertDateFormat($item->created) ?></time>
                                        </td>
                                        <td><?= $item->items_count ?></td>
                                        <td>$<?= number_format($item->DBP, 2) ?></td>
                                        <td>$<?= number_format($item->system_total, 2) ?></td>
                                        <td>$<?= number_format($item->GST, 2) ?></td>
                                        <td>$<?= number_format($item->total, 2) ?></td>
                                        <td><?= ucwords(str_replace('-', ' ', $item->status)) ?></td>

                                        <td class="center">
                                            <?php if ($item->status == 'approved') : ?>
                                            <a href="<?= HOST_NAME.'pos/quote_order_parts/'.$item->id ?>">Order Parts</a>
                                            <?php endif; ?>

                                            <?php if ($item->status == 'parts-ordered') : ?>
                                                <a href="<?= HOST_NAME.'pos/quote_create_job/'.$item->id ?>" title="Send Job to Technician @ Jobs System">Create Job</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
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