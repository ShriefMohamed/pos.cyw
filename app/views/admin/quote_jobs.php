<div class="page-section">
    <section class="card card-fluid">
        <header class="card-header">
            All Quotes Jobs

        </header>
        <div class="card-body">

            <table cellpadding="0" cellspacing="0" border="0" class="g-datatable table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Job ID</th>
                    <th>Customer Name</th>
                    <th>Contact Number</th>
                    <th>Last Update</th>
                    <th>Job Status</th>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($data) && $data !== false) : ?>
                    <?php foreach ($data as $item) : ?>

                        <tr class="gradeX">
                            <td><a href="<?= HOST_NAME.'admin/quote_job/'.$item->id ?>"><?= $item->uid ?></a></td>
                            <td><?= $item->customer_name ?></td>
                            <td><?= $item->customer_phone ?></td>
                            <td><?= \Framework\lib\Helper::ConvertDateFormat($item->updated) ?></td>
                            <td><?= ucfirst($item->status) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
