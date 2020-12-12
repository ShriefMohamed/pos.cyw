<div class="page-section">
    <section class="card card-fluid">
        <div class="listing">
            <header style="margin-bottom: 2rem;">
                <header class="card-header">
                    Sales History

                    <a href="<?= HOST_NAME ?>pos/sale_add" class="btn btn-success" style="float: right">Add Sale</a>
                </header>
            </header>
            <div id="work_listing">
                <div id="listing_loc_matches">
                    <div class="container" style="max-width: 100%">

                        <table cellpadding="0" cellspacing="0" border="0" class="g-datatable datatable table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th width="15%">Sale UID - ID</th>
                                <th>DATE</th>
                                <th>QTY.</th>
                                <th>SUBTOTAL</th>
                                <th>DISCOUNT</th>
                                <th>TAX</th>
                                <th>TOTAL</th>
                                <th>PAID</th>
                                <th>CUSTOMER</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (isset($data) && $data !== false) : ?>
                                <?php foreach ($data as $item) : ?>
                                    <tr class="gradeX">
                                        <?php if ($item->sale_type == 'voided') : ?>
                                        <td><?= $item->uid.' - #'.$item->id ?></td>
                                        <?php else : ?>
                                        <td><a href="<?= HOST_NAME . 'pos/sale/' . $item->id ?>"><?= $item->uid.' - #'.$item->id ?></a></td>
                                        <?php endif; ?>
                                        <td class="date ">
                                            <time datetime="<?= $item->created ?>"><?= \Framework\lib\Helper::ConvertDateFormat($item->created) ?></time>
                                        </td>
                                        <td><?= $item->quantity ?></td>
                                        <td>$<?= number_format($item->subtotal, 2) ?></td>
                                        <td>$<?= number_format($item->discount, 2) ?></td>
                                        <td>$<?= number_format($item->tax, 2) ?></td>
                                        <td>$<?= number_format($item->total, 2) ?></td>
                                        <td>$<?= number_format($item->total_paid, 2) ?></td>
                                        <td><a title="Edit Record" href="<?= $item->customer_id ? HOST_NAME.'customers/customer/'.$item->customer_id : '#' ?>"><span><?= $item->customer_name ?: '' ?></span></a></td>

                                        <td class="center">
                                            <?php if ($item->sale_type == 'voided') : ?>
                                            <span>Voided</span>
                                            <?php else : ?>
                                                <a href="<?= HOST_NAME.'pos/sale/'.$item->id ?>">View</a>

                                                <?php if ($item->sale_status == 'partial_payment' || $item->sale_status == 'awaiting_payment') : ?>
                                                    <a href="<?= HOST_NAME.'pos/sale_payment/'.$item->id ?>" style="margin-left: 15px;">Pay</a>
                                                <?php endif; ?>
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