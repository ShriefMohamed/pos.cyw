<div class="page-section">
    <section class="card card-fluid">
        <header class="card-header">
            Categories

            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModal" style="float: right">Add Category</button>
        </header>
        <div class="card-body">

            <table cellpadding="0" cellspacing="0" border="0" class="g-datatable datatable table table-striped table-bordered">
                <thead>
                <tr>
                    <th width="15%">Category ID</th>
                    <th>Category</th>
                    <th>Items in Category</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($data) && $data !== false) : ?>
                    <?php foreach ($data as $item) : ?>
                        <tr class="gradeX">
                            <td>#<?= $item->id ?></td>
                            <td><?= $item->category ?></td>
                            <td><?= $item->items_count ?></td>

                            <td class="center">
                                <a href="#" title="Delete" class="delete-btn" data-id="<?= $item->id ?>" data-function="category"><i class="far fa-trash-alt"></i></a>
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
                                <h5 class="modal-title"> Add New Category </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <fieldset>
                                    <div class="form-group">
                                        <label class="custom-form-label">Category</label>
                                        <input type="text" class="form-control form-round" name="category" autofocus>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="submit" class="btn btn-info-dark">Submit</button>
                                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
