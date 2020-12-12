<main class="app-main">
    <div class="wrapper">
        <div class="page">
            <div class="page-inner">
                <div class="page-section">
                    <section class="card card-fluid">
                        <header class="card-header">
                            All Generated Insurance Reports
                        </header>
                        <div class="card-body">
                            <table cellpadding="0" cellspacing="0" border="0" class="g-datatable datatable table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>Job ID</th>
                                    <th>Company Name</th>
                                    <th>Report</th>
                                    <th>Created</th>
                                    <th class="text-center">Delete</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (isset($data) && $data !== false) : ?>
                                    <?php foreach ($data as $item) : ?>
                                        <tr class="gradeX">
                                            <td>#<?= $item->job_id ?></td>
                                            <td><?= $item->company_name ?></td>
                                            <td><a href="<?= INSURANCE_REPORTS_DIR.$item->document ?>" target="_blank"><?= $item->document ?></a></td>
                                            <td><?= \Framework\lib\Helper::ConvertDateFormat($item->created, true) ?></td>

                                            <td class="center">
                                                <a href="<?= HOST_NAME ?>technician/insurance_report_delete/<?= $item->id ?>" title="Delete" class="delete-btn"><i class="far fa-trash-alt"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</main>