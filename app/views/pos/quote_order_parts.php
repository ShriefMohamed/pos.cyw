<div class="page-section">
    <section class="card card-fluid">
        <div class="listing">
            <header style="margin-bottom: 2rem;">
                <header class="card-header">
                    Order Parts

                    <a href="<?= HOST_NAME.'pos/quote/'.$quote_id ?>" class="btn btn-success" style="float: right">View Quote</a>
                </header>
            </header>
            <div id="work_listing">
                <div id="listing_loc_matches">
                    <div class="container" style="max-width: 100%">
                        <form method="post" id="purchase-order-form">

                            <table cellpadding="0" cellspacing="0" border="0" class="datatable table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th width="20%">ITEM</th>
                                    <th width="10%">SKU</th>
                                    <th>Available STOCK</th>
                                    <th>QTY.</th>
                                    <th>DBP</th>
                                    <th>PRICE</th>
                                    <th>Availability</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (isset($data) && $data !== false) : ?>
                                    <?php foreach ($data as $item) : ?>
                                        <tr class="gradeX">
                                            <td>
                                                <label for="item-<?= $item->id ?>" title="Add to Purchase Order">Order
                                                    <input type="checkbox" <?= !$item->available_stock || $item->available_stock < 1 ? 'checked' : '' ?> id="item-<?= $item->id ?>" class="order-component-btn" data-component="<?= $item->id ?>" name="po-items[]" value="<?= $item->id ?>">
                                                </label>
                                            </td>
                                            <td>
                                                <?php if ($item->pos_item_id) : ?>
                                                <a target="_blank" title="Edit Item" href="<?= HOST_NAME . 'pos/item/' . $item->pos_item_id ?>"><?= $item->item_name ?></a>
                                                <?php else : ?>
                                                <span><?= $item->item_name ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= $item->item_sku ?></td>
                                            <td><?= $item->available_stock ?: 0 ?></td>
                                            <td><?= $item->quantity ?></td>
                                            <td>$<?= number_format($item->original_price, 2) ?></td>
                                            <td>$<?= number_format($item->price, 2) ?></td>
                                            <td><?= $item->available_stock && $item->available_stock > 0 ? "Stock" : "LeaderSystems" ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>

                            <div class="purchase-order-container">
                                <iframe id="po_preview_iframe" name="po_preview_iframe" width="100%" height="400px" style="max-width: 100%; border: 1px solid rgb(136, 136, 136); padding: 10px;margin: 20px auto;display: block;"></iframe>
                            </div>

                            <div class="functions btn-group" style="float: right">
                                <button type="submit" name="save" class="btn btn-warning">Save PO</button>
                                <button type="submit" name="save-continue" class="btn btn-info-dark">Save & Continue</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<style>
    .btn-success {color: #589141;background: #dbebd6;border-radius: 0;border: solid 1px #CCC}
    .btn-success:hover {background: #bddab4;color: #436e31;border-color: #6f6f6f;}
    .listing {padding-bottom: 2rem;}
</style>

<script>
    $(document).ready(function () {
        <?php if (isset($quote_po) && $quote_po != false && $quote_po->purchase_order) : ?>
        reloadIFrame("<?= QUOTES_PO_DIR.$quote_po->purchase_order.'.pdf' ?>");
        <?php else : ?>
        generatePO("<?= $quote_id ?>");
        <?php endif; ?>

        $('.order-component-btn').on('change', function (e) {
            generatePO(<?= $quote_id ?>);
        });
    });
</script>