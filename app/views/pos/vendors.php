<div class="page-section">
    <section class="card card-fluid">
        <header class="card-header">
            Vendors

            <a href="<?= HOST_NAME ?>pos/vendor_add" class="btn btn-info" style="float: right">Add Vendor</a>
        </header>
        <div class="card-body">

            <table cellpadding="0" cellspacing="0" border="0" class="g-datatable datatable table table-striped table-bordered">
                <thead>
                <tr>
                    <th width="15%">Vendor ID</th>
                    <th>Vendor</th>
                    <th>Contact</th>
                    <th>Mobile</th>
                    <th>Phone</th>
                    <th class="text-center">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($data) && $data !== false) : ?>
                    <?php foreach ($data as $item) : ?>
                        <tr class="gradeX">
                            <td>#<?= $item->id ?></td>
                            <td><?= $item->name ?></td>
                            <td><?= $item->contact_f_name.' '.$item->contact_l_name ?></td>
                            <td><?= $item->contact_mobile ?></td>
                            <td><?= $item->contact_phone ?></td>

                            <td class="center">
                                <a href="<?= HOST_NAME . 'pos/vendor_edit/' . $item->id ?>" title="Update"><i class="far fa-edit"></i></a>
                                <a href="#" title="Delete" class="delete-btn" data-id="<?= $item->id ?>" data-function="vendor"><i class="far fa-trash-alt"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>