<main class="app-main">
    <div class="wrapper">
        <div class="page">
            <div class="page-inner">
                <div class="page-section">
                    <section class="card card-fluid">
                        <header class="card-header">
                            All Repair Jobs

                            <a href="<?= HOST_NAME ?>" class="btn btn-info" style="float: right">Add New Job</a>
                        </header>
                        <div class="card-body">

                            <table cellpadding="0" cellspacing="0" border="0" class="jobs-datatable table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>Job ID</th>
                                    <th>Job Booking Date</th>
                                    <th>Customer Name</th>
                                    <th>Contact Number</th>
                                    <th>Job Stage</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <?php if (isset($data) && $data !== false) : ?>
                                    <?php foreach ($data as $item) : ?>

                                        <?php $show = true; ?>
                                        <?php if (\Framework\lib\Request::Check('status', 'get') && \Framework\lib\Request::Get('status') == 'new') : ?>
                                            <?php if (strtolower($item->stage) !== "awaiting diagnosis") : ?>
                                                <?php $show = false; ?>
                                            <?php endif; ?>
                                        <?php endif; ?>


                                        <?php if ($show) : ?>
                                        <tr class="gradeX">
                                            <td><?= $item->job_id ?></td>
                                            <td><?= $item->created ?></td>
                                            <td><?= $item->firstName . ' ' . $item->lastName ?></td>
                                            <td>
                                                <?= $item->phone ?>
                                                <?php echo ($item->phone2) ? ' & '. $item->phone2 : '' ?>
                                            </td>
                                            <td><?= $item->stage ?></td>

                                            <td class="center">
                                                <a href="<?= HOST_NAME ?>customer/job_print/<?= $item->id ?>" target="_blank" title="Print Sticker"><i class="fa fa-print"></i></a>
                                                <a href="<?= HOST_NAME ?>customer/job/<?= $item->id ?>" title="View / Edit"><i class="fa fa-eye"></i></a>
                                            </td>
                                        </tr>
                                        <?php endif; ?>

                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </table>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    $(document).ready(function() {
        $('.jobs-datatable').dataTable({"order": [[1, "desc"]]});
        $('.jobs-datatable').each(function(){
            var datatable = $(this);
            // SEARCH - Add the placeholder for Search and Turn this into in-line form control
            var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
            search_input.attr('placeholder', 'Search');
            search_input.addClass('form-control input-sm');
            // LENGTH - Inline-Form control
            var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
            length_sel.addClass('form-control input-sm');
        });
    });
</script>