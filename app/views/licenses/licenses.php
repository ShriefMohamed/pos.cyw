<?php $selected_status = \Framework\lib\Request::Check('status', 'get') ? \Framework\lib\Request::Get('status') : ''; ?>
<div class="page-section">
    <section class="card card-fluid">
        <header class="card-header">
            <?= ucwords(str_replace('_', ' ', $selected_status)) ?> Digital Licences

            <a href="<?= HOST_NAME ?>licenses/license_add" class="btn btn-success" style="float: right" target="_blank">Add Licenses</a>
        </header>
        <div class="card-body">
            <table cellpadding="0" cellspacing="0" border="0" class="licenses-table table table-striped table-bordered">
                <thead>
                <tr>
                    <th width="20%">Product</th>
                    <th>License</th>
                    <th>Valid for</th>
                    <th>Email Template</th>
                    <th>Assigned</th>
                    <th>Expired</th>
                    <th>Created</th>
                    <th style=""></th>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($data) && $data !== false) : ?>
                    <?php foreach ($data as $item) : ?>
                        <tr class="gradeX">
                            <td><a href="<?= HOST_NAME.'pos/item/'.$item->item_id ?>"><?= $item->item ?></a></td>
                            <td><?= $item->license ?></td>
                            <td style="white-space: nowrap;">
                                <?=
                                ($item->expiration_years > 0 ? $item->expiration_years.' Year'.($item->expiration_years > 1 ? 's' : '') : '') .
                                ($item->expiration_years && $item->expiration_months ? ' & ' : '') .
                                ($item->expiration_months > 0 ? $item->expiration_months.' Month'.($item->expiration_months > 1 ? 's' : '') : '')
                                ?>
                            </td>
                            <td><?= $item->template ?></td>
                            <td><?= $item->used == '1' ? 'Yes' : 'No' ?></td>
                            <td><?= $item->expired == '1' ? 'Yes' : 'No' ?></td>
                            <td><?= \Framework\lib\Helper::ConvertDateFormat($item->created, true) ?></td>
                            <td style="text-align: center;white-space: nowrap;">
                                <?php if ($item->used != '1' && $item->expired != '1') : ?>
                                    <a href="<?= HOST_NAME.'licenses/license_assign/'.$item->id ?>" title="Assign License"><i class="fa fa-reply"></i></a>
                                <?php endif; ?>
                                <a href="#" data-id="<?= $item->id ?>" data-classname="licenses\digital_licenses" class="ajax-delete" title="Delete License"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<script>
    $(document).ready(function () {
        var table = $('.licenses-table').DataTable({
            dom: 'Bfrtip'
        });

        $('.licenses-table').each(function() {
            var datatable = $(this);
            var filter_div = datatable.closest('.dataTables_wrapper').find('div[id$=_filter]');
            var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');

            filter_div.css({'text-align':'left'});
            search_input.attr('placeholder', 'Search');
            search_input.addClass('form-control input-sm');
            search_input.css({'display':'block'});

            filter_div.find('label').html(search_input);

            var filter = "<div class=\"form-group\" style='display: inline-block;margin-right: 2rem;'>\n" +
                "                    <select class=\"form-control\" id=\"licenses-status-filter\">\n" +
                "                        <option value=\"\">Licenses Status</option>\n" +
                "                        <option <?= $selected_status == 'not_assigned' ? 'selected' : '' ?> value=\"not_assigned\">Not Assigned</option>\n" +
                "                        <option <?= $selected_status == 'assigned' ? 'selected' : '' ?> value=\"assigned\">Assigned</option>\n" +
                "                        <option <?= $selected_status == 'expired' ? 'selected' : '' ?> value=\"expired\">Expired</option>\n" +
                "                    </select>\n" +
                "                </div>";
            filter_div.prepend(filter);
        });

        $(document).on('change', '#licenses-status-filter', function (e) {
            if ($(this).val()) {
                window.location.search = "status="+$(this).val();
            }
        });
    })
</script>
