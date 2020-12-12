<div class="page-section">
    <section class="card card-fluid">
        <div class="listing">
            <header style="margin-bottom: 2rem;">
                <header class="card-header">
                    Customers Quotes

                    <div class="btn-group" style="float: right">
                        <a href="<?= HOST_NAME ?>pos/sale_add" class="btn btn-warning">Add Sale</a>
                        <a href="<?= HOST_NAME ?>quotes/quote_add" class="btn btn-primary">Add Quote</a>
                    </div>
                </header>
            </header>
            <div class="tabs tab-content">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="#quotes-tab" data-toggle="tab">Quotes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#sales-quotes-tab" data-toggle="tab">Sales Quotes</a>
                    </li>
                </ul>

                <div id="quotes-tab" class="tab tab-pane active">
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
                                <?php if (isset($quotes) && $quotes !== false) : ?>
                                    <?php foreach ($quotes as $item) : ?>
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

                <div id="sales-quotes-tab" class="tab tab-pane">
                    <div id="listing_loc_matches">
                        <div class="container" style="max-width: 100%">

                            <table cellpadding="0" cellspacing="0" border="0" class="g-datatable datatable table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th width="15%">Sale UID - ID</th>
                                    <th>CUSTOMER</th>
                                    <th>DATE</th>
                                    <th>QTY.</th>
                                    <th>SUBTOTAL</th>
                                    <th>DISCOUNT</th>
                                    <th>TAX</th>
                                    <th>TOTAL</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (isset($data) && $data !== false) : ?>
                                    <?php foreach ($data as $item) : ?>
                                        <tr class="gradeX">
                                            <td><a href="<?= HOST_NAME . 'pos/sale/' . $item->id ?>"><?= $item->uid.' - #'.$item->id ?></a></td>
                                            <td><a title="Edit Record" href="<?= $item->customer_id ? HOST_NAME.'customers/customer/'.$item->customer_id : '#' ?>"><span><?= $item->customer_name ?: '' ?></span></a></td>
                                            <td class="date ">
                                                <time datetime="<?= $item->created ?>"><?= \Framework\lib\Helper::ConvertDateFormat($item->created) ?></time>
                                            </td>
                                            <td><?= $item->quantity ?></td>
                                            <td>$<?= number_format($item->subtotal, 2) ?></td>
                                            <td>$<?= number_format($item->discount, 2) ?></td>
                                            <td>$<?= number_format($item->tax, 2) ?></td>
                                            <td>$<?= number_format($item->total, 2) ?></td>
                                            <td class="center">
                                                <a href="<?= HOST_NAME.'pos/sale/'.$item->id ?>">View</a>

                                                <?php if ($item->sale_status == 'partial_payment' || $item->sale_status == 'awaiting_payment') : ?>
                                                    <a href="<?= HOST_NAME.'pos/sale_payment/'.$item->id ?>" style="margin-left: 15px;">Pay</a>
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
        </div>
    </section>
</div>

<style>
    .nav-item {width: auto;}
    .listing .tabs {height: auto;border-bottom: 0}
    .nav-tabs {margin-bottom: 20px;}
    .tab .container {padding: 0}
    .nav-tabs .nav-link {color: #2a6fb1;border: 1px solid transparent;border-width: 0 0 3px;}
</style>