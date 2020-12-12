<div class="page-section">
    <section class="card card-fluid">
        <header class="card-header">
            Manage Users

            <a href="<?= HOST_NAME ?>admin/user_add" type="button" class="btn btn-info" style="float: right">Add User</a>
        </header>
        <div class="card-body">

            <table cellpadding="0" cellspacing="0" border="0" class="g-datatable datatable table table-striped table-bordered">
                <thead>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Contact Number</th>
                    <?php if (!\Framework\lib\Request::Check('type', 'get')) : ?>
                    <th>Role</th>
                    <?php endif; ?>
                    <th>Member Since</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($data) && $data !== false) : ?>
                    <?php foreach ($data as $item) : ?>
                        <tr class="gradeX">
                            <td>#<?= $item->id ?></td>
                            <td><?= $item->firstName . ' ' . $item->lastName ?></td>
                            <td><?= $item->username ?></td>
                            <td><?= $item->email ?></td>
                            <td>
                                <?= $item->phone ?>
                                <?php echo ($item->phone2) ? ' & '. $item->phone2 : '' ?>
                            </td>

                            <?php if (!\Framework\lib\Request::Check('type', 'get')) : ?>
                            <td><?= $item->role ?></td>
                            <?php endif; ?>

                            <td><?= \Framework\lib\Helper::ConvertDateFormat($item->created) ?></td>

                            <td class="center">
                                <a href="<?= HOST_NAME ?>admin/user_edit/<?= $item->id ?>" title="Edit"><i class="fa fa-pencil-alt"></i></a>
                                <a href="#" data-id="<?= $item->id ?>" data-classname="users" title="Delete" class="ajax-delete"><i class="far fa-trash-alt"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
