<div class="page-section">
    <section class="card card-fluid">
        <div class="listing">
            <header style="margin-bottom: 2rem;">
                <header class="card-header">
                    Inventory Counts

                    <a href="<?= HOST_NAME ?>pos/inventory_count_add" class="btn btn-success" style="float: right">Add Inventory count</a>
                </header>
            </header>
            <div id="work_listing listing">
                <div id="listing_loc_matches">
                    <div class="container" style="max-width: 100%">

                        <table cellpadding="0" cellspacing="0" border="0" class="g-datatable datatable table table-striped table-bordered table-status-colors">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>STATUS</th>
                                <th>CREATED</th>
                                <th>LAST MODIFIED</th>
                                <th class="text-center"># COUNTED</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (isset($data) && $data !== false) : ?>
                                <?php foreach ($data as $item) : ?>
                                    <?php $order_item_status = 'status-grey'; ?>
                                    <?php if ($item->status == 'finished') : $order_item_status = 'status-green'; ?>
                                    <?php elseif ($item->status == 'counting') : $order_item_status = 'status-orange'; ?>
                                    <?php elseif ($item->status == 'archived') : $order_item_status = 'status-red'; ?>
                                    <?php endif; ?>

                                    <tr class="gradeX <?= $order_item_status ?>">
                                        <td><a href="<?= HOST_NAME . 'pos/inventory_count/' . $item->id ?>">#<?= $item->id ?></a></td>

                                        <td class="string  nowrap">
                                            <span class="status-label"><a href="<?= HOST_NAME . 'pos/inventory_count/' . $item->id ?>"><?= strtoupper($item->status) ?></a></span>
                                        </td>
                                        <td class="date ">
                                            <time datetime="<?= $item->created ?>"><?= \Framework\lib\Helper::ConvertDateFormat($item->created, true) ?></time>
                                        </td>
                                        <td class="date ">
                                            <time datetime="<?= $item->updated ?>"><?= \Framework\lib\Helper::ConvertDateFormat($item->updated, true) ?></time>
                                        </td>
                                        <td class="text-center"><strong><?= $item->counted_items ?: 0 ?></strong></td>
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
    .btn-success {color: #589141;background: #dbebd6;border-radius: 0;border: solid 1px #CCC}
    .btn-success:hover {background: #bddab4;color: #436e31;border-color: #6f6f6f;}
</style>