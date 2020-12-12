<main class="app-main">
    <div class="wrapper">
        <div class="page">
            <div class="page-inner">
                <div class="page-section">
                    <section class="card card-fluid">
                        <header class="card-header">
                            Jobs assigned to you

                            <a href="<?= HOST_NAME ?>technician/job_add" class="btn btn-info" style="float: right">Create Job</a>
                            <?php if (!isset($_GET['others'])) : ?>
                            <a href="<?= HOST_NAME ?>technician/default?others" class="btn btn-danger" style="margin-left: 5%">Other Jobs</a>
                            <?php else : ?>
                            <a href="<?= HOST_NAME ?>technician/default" class="btn btn-danger" style="margin-left: 5%">Your Jobs</a>
                            <?php endif; ?>
                        </header>
                        <div class="card-body">
                            <table cellpadding="0" cellspacing="0" border="0" class="jobs-datatable table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>Job ID</th>
                                    <th>Job Booking Date</th>
                                    <th>Customer Name</th>
                                    <?php if (isset($_GET['others'])) : ?>
                                    <th>Technician Assigned</th>
                                    <?php endif; ?>
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
                                        <td><a href="<?= HOST_NAME.'technician/job/'.$item->id ?>"><?= $item->job_id ?></a></td>
                                        <td><?= $item->created ?></td>
                                        <td><?= $item->firstName . ' ' . $item->lastName ?></td>
                                        <?php if (isset($_GET['others'])) : ?>
                                            <td><?= $item->technician_name ?></td>
                                        <?php endif; ?>
                                        <td>
                                            <?= $item->phone ?>
                                            <?php echo ($item->phone2) ? ' & '. $item->phone2 : '' ?>
                                        </td>
                                        <td><?= $item->stage ?></td>

                                        <td class="center">
                                            <div class="dropdown">
                                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Actions</button>
                                                <div class="dropdown-menu">
                                                    <a href="<?= HOST_NAME ?>technician/job/<?= $item->id ?>" title="View / Edit" class="dropdown-item">
                                                        <i class="fa fa-pencil-alt"></i>
                                                        Edit
                                                    </a>
                                                    <a href="<?= HOST_NAME.'technician/job/'.$item->id.'#notes-section' ?>" title="Add Notes" class="dropdown-item">
                                                        <i class="fa fa-pencil-alt"></i>
                                                        Add Notes
                                                    </a>
                                                    <a href="<?= HOST_NAME ?>technician/job_print/<?= $item->id ?>" target="_blank" title="Print Sticker" class="dropdown-item">
                                                        <i class="fa fa-print"></i>
                                                        Print Sticker
                                                    </a>
                                                    <a data-toggle="modal" data-content="<?= $item->id ?>" data-job="<?= $item->job_id ?>" data-target="#technicianModal" id="technicianModalBtn" title="Assign Technician" class="dropdown-item">
                                                        <i class="fas fa-external-link-alt"></i>
                                                        Assign Technician
                                                    </a>
                                                    <?php if ($item->tracking_info) : ?>
                                                        <a href="<?= HOST_NAME ?>technician/job_track/<?= $item->id ?>" title="Parcel Tracking Log" class="dropdown-item">
                                                            <i class="fa fa-truck-loading"></i>
                                                            Tracking Log
                                                        </a>
                                                    <?php endif; ?>
                                                    <a href="<?= HOST_NAME ?>technician/job_insurance_report/<?= $item->id ?>" title="Generate Insurance Report" class="dropdown-item">
                                                        <i class="fa fa-print"></i>
                                                        Generate Insurance Report
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endif; ?>

                                <?php endforeach; ?>
                                <?php endif; ?>
                            </table>

                            <div class="modal fade" id="technicianModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form method="post" action="" id="technician-assign-form">
                                            <input type="hidden" name="job_id" value="" id="technicianModal-job_id">
                                            <div class="modal-header bg-info-dark">
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
                                                                <?php if ($technician->id !== \Framework\Lib\Session::Get('loggedin')->id) : ?>
                                                                    <option value="<?= $technician->id ?>"><?= $technician->firstName.' '.$technician->lastName ?></option>
                                                                <?php endif; ?>
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

        $(document).on('click', '#technicianModalBtn', function (e) {
            $('#technicianModal-job_id').val(e.target.dataset.content);
            $('#technician-modal-job_id').html(e.target.dataset.job);
        });

        $(document).on('submit', '#technician-assign-form', function (e) {
            e.preventDefault();
            let job_id = $('input[name="job_id"]').val(),
                technician = $('#technician option:selected').val();
            $.ajax({
                url: "<?= HOST_NAME ?>technician/job_assign_technician/"+job_id+"/"+technician,
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