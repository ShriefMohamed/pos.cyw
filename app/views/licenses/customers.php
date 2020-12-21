<div class="page-section">
    <section class="card card-fluid">
        <header class="card-header">
            Customers With Licences

            <a href="<?= HOST_NAME ?>licenses/license_assign" class="btn btn-success" style="float: right" target="_blank">Assign License</a>
        </header>
        <div class="card-body">

            <table cellpadding="0" cellspacing="0" border="0" class="g-datatable datatable table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Phone</th>
                    <th>Email</th>

                    <th>Active Licenses</th>
                    <th>Expired Licenses</th>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($data) && $data !== false) : ?>
                    <?php foreach ($data as $item) : ?>
                        <tr class="gradeX" data-customer-id="<?= $item->customer_id ?>">
                            <td><a href="<?= HOST_NAME.'customers/customer/'.$item->user_id ?>"><?= $item->firstName.' '.$item->lastName ?></a></td>
                            <td><?= $item->phone ?></td>
                            <td><?= $item->email ?></td>
                            <td>
                                <?= $item->customer_active_licenses > 0 ? "<a href='#' data-type='active' class='view-customer-licenses-btn' title='View Active Licenses'>".$item->customer_active_licenses."</a>" : $item->customer_active_licenses ?>
                            </td>
                            <td>
                                <?= $item->customer_expired_licenses > 0 ? "<a href='#' data-type='expired' class='view-customer-licenses-btn' title='View Expired Licenses'>".$item->customer_expired_licenses."</a>" : $item->customer_expired_licenses ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="customer-licenses-modal">
    <div class="modal-dialog" role="document" style="max-width: 85%;">
        <div class="modal-content">
            <div class="modal-header bg-info-dark">
                <h5 class="modal-title">Customer's Licenses</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <td>Product</td>
                        <td>License</td>
                        <td>Status</td>
                        <td>Assigned</td>
                        <td>Expiration Date</td>
                        <td></td>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.view-customer-licenses-btn').on('click', function (e) {
            var customer_id = $(this).parents('tr').data('customer-id');
            var type = $(this).data('type');

            $.ajax({
                url: "/ajax/get_licenses_by_customer/"+customer_id,
                data: {type: type},
                type: 'post',
                dataType: 'json',
                complete: function (xhr) {
                    console.log(xhr);
                    if (xhr.responseJSON) {
                        var response = xhr.responseJSON;
                        var table_body = '';

                        for (var i = 0; i < response.length; i++) {
                            table_body += "<tr>\n" +
                                "   <td>"+response[i].item+"</td>\n" +
                                "   <td>"+response[i].license+"</td>\n" +
                                "   <td>"+ucfirst(response[i].license_status)+(response[i].license_status == 'active' ? " - "+ ucfirst(response[i].status) : '')+"</td>\n" +
                                "   <td>"+new Date(response[i].created).toLocaleString('AU')+"</td>\n" +
                                "   <td>"+new Date(response[i].expiration_date).toLocaleDateString('AU')+"</td>\n";
                            if (response[i].license_status == 'active') {
                                table_body += "<td>" +
                                    "   <a href='/licenses/license_resend/"+response[i].id+"/"+response[i].assigned_license_id+"' title='Resend License Code to Customer'>Resend Code</a> | " +
                                    "   <a href='/licenses/license_renew/"+response[i].id+"' title='Renew License'>Renew License</a>" +
                                    "</td>";
                            } else if (response[i].license_status == 'expired') {
                                table_body += "<td><a href='/licenses/license_renew/"+response[i].id+"' title='Renew License'>Renew License</a></td>";
                            }
                            table_body +=
                                "</tr>";
                        }

                        $("#customer-licenses-modal .modal-body table tbody").html(table_body);
                        $('#customer-licenses-modal').modal('show');
                    }
                }
            });
        });
    });
</script>
