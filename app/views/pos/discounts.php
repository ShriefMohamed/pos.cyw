<div class="page-section">
    <section class="card card-fluid">
        <header class="card-header">
            Discounts

            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModal" style="float: right">Add Discount</button>
        </header>
        <div class="card-body">

            <table cellpadding="0" cellspacing="0" border="0" class="g-datatable datatable table table-striped table-bordered">
                <thead>
                <tr>
                    <th width="15%">ID</th>
                    <th>Discount Name</th>
                    <th>Type</th>
                    <th>Discount</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($data) && $data !== false) : ?>
                    <?php foreach ($data as $item) : ?>
                        <tr class="gradeX">
                            <td>#<?= $item->id ?></td>
                            <td><?= $item->title ?></td>
                            <td><?= ucfirst($item->type) ?></td>
                            <td><?= $item->type == 'fixed' ? '$'.$item->discount : $item->discount.'%' ?></td>

                            <td class="center">
                                <a href="#" title="Delete" class="delete-btn" data-id="<?= $item->id ?>" data-function="discount"><i class="far fa-trash-alt"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>


            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form method="post">
                            <div class="modal-header bg-info-dark">
                                <h5 class="modal-title"> Add New Discount </h5>
                            </div>
                            <div class="modal-body">
                                <fieldset>
                                    <div class="form-group">
                                        <label class="custom-form-label">Discount Name</label>
                                        <input type="text" class="form-control form-round" name="title" autofocus required>
                                    </div>
                                    <div class="form-group">
                                        <label class="custom-form-label">Type</label>
                                        <select name="type" class="form-control form-round discount-type" required>
                                            <option value="percent">Percent</option>
                                            <option value="fixed">Fixed</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="custom-form-label discount-label">Percent % Off</label>
                                        <input type="text" class="form-control form-round" name="discount" required>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function () {
        $('.discount-type').on('change', function (e) {
            if ($(this).val() == 'fixed') {
                $('.discount-label').html("Amount Off");
            } else {
                $('.discount-label').html("Percent % Off");
            }
        });
    });
</script>