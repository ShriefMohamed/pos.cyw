<div class="page-section">
    <section class="card card-fluid">
        <header class="card-header">
            All Repair Jobs

            <a href="<?= HOST_NAME ?>admin/job_add" class="btn btn-primary" style="float: right; margin-left: 2%">Create Job</a>

            <?php if (\Framework\lib\Request::Check('status', 'get') && \Framework\lib\Request::Get('status') == 'trash') : ?>
                <a href="<?= HOST_NAME ?>admin/jobs" class="btn btn-info" style="float: right">Active Jobs</a>
            <?php else : ?>
                <a href="<?= HOST_NAME ?>admin/jobs?status=trash" class="btn btn-info" style="float: right">Trash Jobs</a>
            <?php endif; ?>
        </header>
        <div class="card-body">

            <table cellpadding="0" cellspacing="0" border="0" class="g-datatable table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Job ID</th>
                    <th>Job Booking Date</th>
                    <th>Customer Name</th>
                    <th>Contact Number</th>
                    <th>Job Stage</th>
                    <th class="text-center">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($data) && $data !== false) : ?>
                    <?php foreach ($data as $item) : ?>

                    <?php $show = true; ?>
                    <?php if (\Framework\lib\Request::Check('status', 'get')) : ?>

                        <?php if (\Framework\lib\Request::Get('status') == 'new') : ?>
                            <?php if (strtolower($item->stage) !== "awaiting diagnosis") : ?>
                                <?php $show = false; ?>
                            <?php endif; ?>
                        <?php elseif (\Framework\lib\Request::Get('status') == 'diagnosing') : ?>
                            <?php if (strtolower($item->stage) !== "technician diagnosing") : ?>
                                <?php $show = false; ?>
                            <?php endif; ?>
                        <?php elseif (\Framework\lib\Request::Get('status') == 'repairing') : ?>
                            <?php if (strtolower($item->stage) !== "repairing") : ?>
                                <?php $show = false; ?>
                            <?php endif; ?>
                        <?php elseif (\Framework\lib\Request::Get('status') == 'parts') : ?>
                            <?php if (strtolower($item->stage) !== "awaiting parts") : ?>
                                <?php $show = false; ?>
                            <?php endif; ?>
                        <?php elseif (\Framework\lib\Request::Get('status') == 'completed') : ?>
                            <?php if (strtolower($item->stage) !== "called customer, awaiting collection, completed") : ?>
                                <?php $show = false; ?>
                            <?php endif; ?>
                        <?php endif; ?>

                    <?php endif; ?>


                    <?php if ($show) : ?>
                        <tr class="gradeX">
                            <td><a href="<?= HOST_NAME ?>admin/job_edit/<?= $item->id ?>" title="Edit"><?= $item->job_id ?></a></td>
                            <td><?= $item->created ?></td>
                            <td><?= $item->firstName . ' ' . $item->lastName ?></td>
                            <td>
                                <?= $item->phone ?>
                                <?php echo ($item->phone2) ? ' & '. $item->phone2 : '' ?>
                            </td>
                            <td><?= $item->stage ?></td>

                            <td class="center">
                            <?php if (\Framework\lib\Request::Check('status', 'get') && \Framework\lib\Request::Get('status') == 'trash') : ?>
                                <a href="<?= HOST_NAME ?>admin/job_restore/<?= $item->id ?>" title="Restore"><i class="fas fa-external-link-alt"></i></a>
                                <a href="<?= HOST_NAME ?>admin/job_trash/<?= $item->id ?>" title="Delete" class="delete-btn"><i class="far fa-trash-alt"></i></a>
                            <?php else : ?>
                                <div class="dropdown">
                                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Actions</button>
                                    <div class="dropdown-menu">
                                        <a href="<?= HOST_NAME ?>admin/job_edit/<?= $item->id ?>" title="Edit" class="dropdown-item">
                                            <i class="fa fa-pencil-alt"></i>
                                            Edit
                                        </a>
                                        <a href="<?= HOST_NAME ?>admin/job_trash/<?= $item->id ?>" title="Delete" class="dropdown-item">
                                            <i class="far fa-trash-alt"></i>
                                            Delete
                                        </a>
                                        <a href="<?= HOST_NAME ?>admin/job_print/<?= $item->id ?>" target="_blank" title="Print Sticker" class="dropdown-item">
                                            <i class="fa fa-print"></i>
                                            Print Sticker
                                        </a>
                                        <a data-toggle="modal" data-content="<?= $item->id ?>" data-job="<?= $item->job_id ?>" data-target="#technicianModal" id="technicianModalBtn" title="Assign Technician" class="dropdown-item">
                                            <i class="fas fa-external-link-alt"></i>
                                            Assign Technician
                                        </a>
                                        <?php if ($item->tracking_info) : ?>
                                            <a href="<?= HOST_NAME ?>admin/job_track/<?= $item->id ?>" title="Parcel Tracking Log" class="dropdown-item">
                                                <i class="fa fa-truck-loading"></i>
                                                Tracking Log
                                            </a>
                                        <?php endif; ?>
                                        <a href="<?= HOST_NAME ?>admin/job_insurance_report/<?= $item->id ?>" title="Generate Insurance Report" class="dropdown-item">
                                            <i class="fa fa-print"></i>
                                            Generate Insurance Report
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>

                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>

            <div class="modal fade" id="technicianModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form method="post" action="" id="technician-assign-form">
                            <input type="hidden" name="job_id" value="" id="technicianModal-job_id">
                            <div class="modal-header">
                                <h5 class="modal-title"> Assign Technician To Job <strong id="technician-modal-job_id"></strong> </h5>
                            </div>
                            <div class="modal-body">
                                <fieldset>
                                    <div class="form-group">
                                        <label class="custom-form-label">Technicians</label>
                                        <select class="custom-select" name="technician" id="technician">
                                        <?php if (isset($technicians) && $technicians) : ?>
                                            <option selected disabled>Select Technician</option>
                                            <?php foreach ($technicians as $technician) : ?>
                                            <option value="<?= $technician->id ?>"><?= $technician->firstName.' '.$technician->lastName ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                        </select>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                <button type="button" class="btn btn-light close-modal" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        $(document).on('click', '#technicianModalBtn', function (e) {
            $('#technicianModal-job_id').val(e.target.dataset.content);
            $('#technician-modal-job_id').html(e.target.dataset.job);
        });

        $(document).on('submit', '#technician-assign-form', function (e) {
            e.preventDefault();
            let job_id = $('input[name="job_id"]').val(),
                technician = $('#technician option:selected').val();
            $.ajax({
                url: "<?= HOST_NAME ?>admin/job_assign_technician/"+job_id+"/"+technician,
                type: "POST",
                dataType: "json"
            }).done(function (data) {
                if (data == 1) {
                    alert('Technician was assigned to job successfully.');
                    $('.close-modal').trigger('click');
                } else {
                    alert('Failed to assign technician!');
                }
            });
        });
    });
</script>
<style>
    #technicianModalBtn {cursor: pointer}
    #technicianModalBtn:hover {color: #fff;background-color: #346cb0;}
</style>