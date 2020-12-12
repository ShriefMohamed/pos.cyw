<div class="page-section">
    <section class="card card-fluid">
        <header class="card-header">Tags</header>
        <div class="card-body">

            <table cellpadding="0" cellspacing="0" border="0" class="g-datatable datatable table table-striped table-bordered">
                <thead>
                <tr>
                    <th width="60%">Items' IDs</th>
                    <th>Tag</th>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($data) && $data !== false) : ?>
                    <?php foreach ($data as $key => $value) : ?>
                        <tr class="gradeX">
                            <td>
                                <?php foreach ($value as $item) : ?>
                                <a href="<?= HOST_NAME.'pos/item/'.$item['id'] ?>">#<?= $item['id'] ?></a>
                                <?php endforeach; ?>
                            </td>
                            <td><?= $key ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
