<div class="page-section">
    <section class="card card-fluid">
        <header class="card-header">
            All Insurance Companies

            <a href="<?= HOST_NAME ?>admin/insurance_add" type="button" class="btn btn-info" style="float: right">Add Company</a>
        </header>
        <div class="card-body">
            <table cellpadding="0" cellspacing="0" border="0" class="g-datatable datatable table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Company ID</th>
                    <th>Company Name</th>
                    <th class="text-center">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($data) && $data !== false) : ?>
                    <?php foreach ($data as $item) : ?>
                        <tr class="gradeX">
                            <td>#<?= $item->id ?></td>
                            <td><?= $item->name ?></td>

                            <td class="center">
                                <a href="<?= HOST_NAME ?>admin/insurance_edit/<?= $item->id ?>" title="Edit"><i class="fa fa-pencil-alt"></i></a>
                                <a href="<?= HOST_NAME ?>admin/insurance_delete/<?= $item->id ?>" title="Delete" class="delete-btn"><i class="far fa-trash-alt"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
