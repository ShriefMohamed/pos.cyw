<div class="page-section">
    <section class="card card-fluid">
        <header class="card-header">
            Products With Licences

            <a href="<?= HOST_NAME ?>licenses/license_add" class="btn btn-success" style="float: right" target="_blank">Add Licenses</a>
        </header>
        <div class="card-body">

            <table cellpadding="0" cellspacing="0" border="0" class="g-datatable datatable table table-striped table-bordered">
                <thead>
                <tr>
                    <th width="15%">Product</th>
                    <th>Available QTY.</th>
                    <th>DBP</th>
                    <th>Retail Price</th>

                    <th>Total Licenses</th>
                    <th>Un-Assigned Licences</th>
                    <th>Assigned Licenses</th>
                    <th>Expired Licences</th>

                    <th style=""></th>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($data) && $data !== false) : ?>
                    <?php foreach ($data as $item) : ?>
                        <tr class="gradeX">
                            <td><a href="<?= HOST_NAME.'pos/item/'.$item->id ?>"><?= $item->item ?></a></td>
                            <td><?= $item->available_stock ?></td>
                            <td>$<?= number_format($item->buy_price, 2) ?></td>
                            <td>$<?= number_format($item->rrp_price, 2) ?></td>
                            <td><?= $item->licenses_count ?></td>
                            <td><?= $item->available_licenses_count ?></td>
                            <td><?= $item->used_licenses_count ?></td>
                            <td><?= $item->expired_licenses_count ?></td>

                            <td style="display: flex;justify-content: space-between;">
                                <a href="#" data-item="<?= $item->id ?>" class="view-product-licenses-btn" title="View Licenses"><i class="fa fa-eye"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="product-licenses-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info-dark">
                <h5 class="modal-title">Product's Licenses</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <table class="table table-bordered table-striped">
                    <tbody>


                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.view-product-licenses-btn').on('click', function (e) {
            var id = $(this).data('item');

            $.ajax({
                url: "/ajax/get_licenses_by_product/"+id,
                data: {},
                type: 'post',
                dataType: 'json',
                success: function (response) {
                    console.log(response)
                    if (response) {
                        var table_body = '';
                        for (var i = 0; i < response.length; i++) {
                            table_body += "<tr>\n" +
                                "    <td style=\"background: #fff;border: 1px solid #999;\">" +
                                "      <div class='row'>\n" +
                                "        <div class=\"col-md-6\">\n" +
                                "            <label>License : </label>\n" + response[i].license +
                                "        </div>\n" +
                                "        <div class=\"col-md-6\">\n" +
                                "            <label>Status : </label>\n" + (response[i].expired == '1' ? 'Expired' : response[i].used == '1' ? 'Assigned' : 'Not Assigned') +
                                "        </div>\n" +
                                "      </div>\n" +
                                "    </td>\n" +
                                "</tr>";
                        }

                    }

                    $("#product-licenses-modal .modal-body table tbody").html(table_body);
                    $('#product-licenses-modal').modal('show');
                }
            });
        });
    });
</script>
