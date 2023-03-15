<div class="page-section">
    <section class="card card-fluid">
        <div class="listing">
            <header style="margin-bottom: 2rem;">
                <header class="card-header">
                    Customers

                    <a href="<?= HOST_NAME ?>customers/customer_add" class="btn btn-success" style="float: right">Add New customer</a>
                </header>
            </header>
            <div id="work_listing">
                <div id="listing_loc_matches">
                    <div class="container" style="max-width: 100%">
                        <?php if (isset($customers) && $customers != false) : ?>
                        <table cellpadding="0" cellspacing="0" border="0" class="default-datatable datatable table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Company</th>
                                <th>Mobile/Phone</th>
                                <th>Email</th>
                                <th>Discount</th>
                                <th>Created</th>
                                <th class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (isset($customers->data) && $customers->data !== false) : ?>
                                <?php foreach ($customers->data as $item) : ?>
                                    <tr class="gradeX">
                                        <td><a href="<?= HOST_NAME . 'customers/customer/' . $item->id ?>">#<?= $item->firstName.' '.$item->lastName ?></a></td>
                                        <td><?= $item->companyName ?></td>

                                        <td><?= $item->phone.' / '. $item->phone2 ?></td>
                                        <td><?= $item->email ?></td>
                                        <?php if ($item->discount_id != 0) : ?>
                                        <td><?= $item->title ?>  (<?= $item->type == 'fixed' ? '$'.$item->discount : $item->discount.'%' ?>)</td>
                                        <?php else : ?>
                                        <td>None</td>
                                        <?php endif;?>

                                        <td><?= \Framework\lib\Helper::ConvertDateFormat($item->created, true) ?></td>

                                        <td class="text-center">
                                            <a href="<?= HOST_NAME.'customers/customer/'.$item->id ?>"><i class="fa fa-edit"></i></a>
                                            <a href="#" data-id="<?= $item->id ?>" data-classname="users" data-extra-action="customer_delete" title="Delete" class="ajax-delete"><i class="far fa-trash-alt"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>

                        <?= $customers->pagination ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .page-inner {padding-right: 0;padding-left: 0;}
    .btn-success {color: #589141;background: #dbebd6;border-radius: 0;border: solid 1px #CCC}
    .btn-success:hover {background: #bddab4;color: #436e31;border-color: #6f6f6f;}
</style>