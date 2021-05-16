<div class="page-section">
    <section class="card card-fluid">
        <div class="listing">
            <header style="margin-bottom: 2rem;">
                <header class="card-header">
                    Items

                    <a href="<?= HOST_NAME ?>pos/item_add" class="btn btn-success" style="float: right">Add New Item</a>
                </header>
            </header>
            <div id="work_listing">
                <div id="listing_loc_matches">
                    <div class="container" style="max-width: 100%">
                        <?php if (isset($data) && $data !== false) : ?>
                            <table cellpadding="0" cellspacing="0" border="0" class="default-datatable datatable table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th width="15%">Item UID</th>
                                    <th>Item</th>
                                    <th>Type</th>
                                    <th>Shop SKU</th>
                                    <th>Department</th>
                                    <th>Brand</th>
                                    <th>Category</th>
                                    <th>Tax</th>
                                    <th style="width: 5%">Available QTY</th>
                                    <th style="width: 5%;">AVG Retail Price</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (isset($data->data) && $data->data !== false) : ?>
                                    <?php foreach ($data->data as $item) : ?>
                                        <tr class="gradeX">
                                            <td><a href="<?= HOST_NAME . 'pos/item/' . $item->id ?>">#<?= $item->uid ?></a></td>
                                            <td><a href="<?= HOST_NAME . 'pos/item/' . $item->id ?>"><?= $item->item ?></a></td>
                                            <td><?= $item->item_type ?></td>

                                            <td><?= $item->shop_sku ?></td>
                                            <td><?= $item->department ?></td>
                                            <td><?= $item->brand_name ?></td>
                                            <td><?= $item->category_name ?></td>
                                            <td><?= $item->rate ? $item->class.' ('.$item->rate.'%)' : 'None' ?></td>
                                            <td style="width: 5%"><?= $item->available_stock ?></td>
                                            <td>$<?= $item->items_avg_price ? number_format(substr($item->items_avg_price, 0, 5), 2) : 0 ?></td>

                                            <td class="center">
                                                <a href="#" title="Archive" class="archive-btn" data-id="<?= $item->id ?>" data-function="item"><i class="fa fa-trash"></i></a>
                                                <a href="#" title="Print Label"><i class="fa fa-print"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>

                            <?= $data->pagination ?>
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