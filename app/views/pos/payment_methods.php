<div class="page-section">
    <section class="card card-fluid">
        <header class="card-header">
            Payment Methods

            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModal" style="float: right">Add Payment Method</button>
        </header>
        <div class="card-body">

            <table cellpadding="0" cellspacing="0" border="0" class="g-datatable datatable table table-striped table-bordered">
                <thead>
                <tr>
                    <th width="15%">Method ID</th>
                    <th>Payment Method</th>
                    <th>Refund As</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($data) && $data !== false) : ?>
                    <?php foreach ($data as $item) : ?>
                        <tr class="gradeX">
                            <td>#<?= $item->id ?></td>
                            <td><?= $item->method ?></td>
                            <td><?= $item->refund_as ?></td>

                            <td class="center">
                                <a href="#" title="Delete" class="delete-btn" data-id="<?= $item->id ?>" data-function="payment_method"><i class="far fa-trash-alt"></i></a>
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
                                <h5 class="modal-title"> Add New Payment Method </h5>
                            </div>
                            <div class="modal-body">
                                <fieldset>
                                    <div class="form-group">
                                        <label class="custom-form-label">Payment Method</label>
                                        <input type="text" class="form-control form-round" name="method" autofocus>
                                    </div>
                                    <div class="form-group">
                                        <label class="custom-form-label">Refund As</label>
                                        <select class="form-control form-round" name="refund_as">
                                            <option value="All">All</option>
                                            <?php if (isset($data) && $data != false) : ?>
                                            <?php foreach ($data as $method) : ?>
                                            <option value="<?= $method->method ?>"><?= $method->method ?></option>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
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
