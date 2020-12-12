<div class="page-section">
    <section class="card card-fluid">
        <div class="listing">
            <header style="margin-bottom: 2rem;">
                <header class="card-header">
                    Vendor Returns

                    <a href="#" class="btn btn-success" style="float: right" data-toggle="modal" data-target="#add-vendor-modal">Add Vendor Return</a>
                </header>
            </header>
            <div id="work_listing listing">
                <div id="listing_loc_matches">
                    <div class="container" style="max-width: 100%">
                        <table cellpadding="0" cellspacing="0" border="0" class="g-datatable datatable table table-striped table-bordered table-status-colors">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>REF #</th>
                                <th>STATUS</th>
                                <th>VENDOR</th>
                                <th>NOTE</th>
                                <th>QTY.</th>
                                <th>CREATED</th>
                                <th>SENT</th>
                                <th>CLOSED</th>
                                <th>TOTAL COST</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (isset($data) && $data !== false) : ?>
                                <?php foreach ($data as $item) : ?>
                                    <?php $order_item_status = 'status-grey'; ?>
                                    <?php if ($item->status == 'sent') : $order_item_status = 'status-orange'; ?>
                                    <?php elseif ($item->status == 'closed') : $order_item_status = 'status-green'; ?>
                                    <?php elseif ($item->status == 'archived') : $order_item_status = 'status-red'; ?>
                                    <?php endif; ?>

                                    <tr class="gradeX <?= $order_item_status ?>">
                                        <td><a href="<?= HOST_NAME.'pos/vendor_return/'.$item->id ?>"><?= $item->id ?></a></td>
                                        <td class="string  nowrap">#<?= $item->reference ?></td>
                                        <td class="string  nowrap">
                                            <span class="status-label"><a href="<?= HOST_NAME.'pos/vendor_return/'.$item->id ?>"><?= strtoupper($item->status) ?></a></span>
                                        </td>
                                        <td><?= $item->vendor_name ?></td>
                                        <td><?= substr($item->notes, 0, 80) ?>..</td>
                                        <td class="string  nowrap"><?= $item->total_returned ?></td>
                                        <td class="date ">
                                            <time datetime="<?= $item->created ?>"><?= \Framework\lib\Helper::ConvertDateFormat($item->created, true) ?></time>
                                        </td>
                                        <td class="date ">
                                            <time datetime="<?= $item->sending_date ?>"><?= \Framework\lib\Helper::ConvertDateFormat($item->sending_date, true) ?></time>
                                        </td>
                                        <td class="date ">
                                            <time datetime="<?= $item->closing_date ?>"><?= \Framework\lib\Helper::ConvertDateFormat($item->closing_date, true) ?></time>
                                        </td>
                                        <td>$<?= number_format($item->total, 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="add-vendor-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="post" action="<?= HOST_NAME ?>pos/vendor_return_add">
                        <div class="modal-header bg-info-dark">
                            <h5 class="modal-title"> New Vendor Return </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <fieldset>
                                <div class="form-group">
                                    <label class="custom-form-label">Vendor</label>
                                    <select name="vendor-id" class="form-control form-round">
                                        <?php if (isset($vendors) && $vendors !== false) : ?>
                                        <?php foreach ($vendors as $vendor) : ?>
                                        <option value="<?= $vendor->id ?>"><?= $vendor->name ?></option>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </fieldset>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                            <button type="submit" name="submit-vendor" class="btn btn-success2">Create Vendor Return</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .btn-success {color: #589141;background: #dbebd6;border-radius: 0;border: solid 1px #CCC}
    .btn-success:hover {background: #bddab4;color: #436e31;border-color: #6f6f6f;}

    .btn-success2 {background-image: linear-gradient(rgb(14, 173, 48), rgb(5, 145, 24));color: rgb(255, 255, 255);
        box-shadow: rgba(255, 255, 255, 0.15) 0px 1px 0px 0px inset;border-color: rgb(0, 111, 7);border-radius: 0.1875rem;}
    .btn-success2 {color: rgb(255, 255, 255);background-color: rgb(5, 145, 24);
        background-image: linear-gradient(rgb(5, 145, 24), rgb(5, 145, 24));}
</style>