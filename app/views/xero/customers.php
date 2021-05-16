<div class="page-section">
    <section class="card card-fluid">
        <div class="listing">
            <header style="margin-bottom: 2rem;">
                <header class="card-header">
                    Xero Customers

                    <a href="<?= HOST_NAME ?>xero/sync/customers" class="btn btn-success" style="float: right">Sync Customers</a>
                </header>
            </header>
            <div id="work_listing listing">
                <div id="listing_loc_matches">
                    <div class="container" style="max-width: 100%">
                        <?php if (isset($data) && $data !== false) : ?>

                        <table cellpadding="0" cellspacing="0" border="0" class="default-datatable datatable table table-striped table-bordered table-status-colors">
                            <thead>
                            <tr>
                                <th>Archived</th>
                                <th>Full Name</th>
                                <th>Xero ID</th>
                                <th>Email</th>
                                <th>Region</th>
                                <th>Phone</th>
                                <th>Last Update</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (isset($data->data) && $data->data !== false) : ?>
                                <?php foreach ($data->data as $item) : ?>
                                    <?php $item_status = $item->status != 'archived' ? 'status-green' : 'status-red'; ?>

                                    <tr class="gradeX <?= $item_status ?>">
                                        <td class="string nowrap">
                                            <span class="status-label"><?= $item->status != 'archived' ? 'No' : 'Yes' ?></span>
                                        </td>
                                        <td><a href="<?= HOST_NAME.'customers/customer/'.$item->id ?>"><?= $item->fullname ?></a> </td>
                                        <td><?= $item->xero_ContactID ?></td>
                                        <td><?= $item->email ?></td>
                                        <td><?= $item->suburb ?></td>
                                        <td><?= $item->phone ?></td>
                                        <td class="date ">
                                            <time datetime="<?= $item->lastUpdate ?>"><?= \Framework\lib\Helper::ConvertDateFormat($item->lastUpdate, true) ?></time>
                                        </td>
                                        <td style="text-align: center">
                                            <?php if ($item->status != 'archived') : ?>
                                                <a href="<?= HOST_NAME.'ajax/archive/'.$item->customer_id.'?target=customers&returnURL=xero/customers' ?>" title="Archive"><i class="fa fa-trash"></i></a>
                                            <?php else : ?>
                                                <a href="<?= HOST_NAME.'ajax/un_archive/'.$item->customer_id.'?target=customers&returnURL=xero/customers' ?>" title="Un-Archive"><i class="fa fa-link"></i></a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>

                        <?= $data->pagination ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .btn-success {color: #589141;background: #dbebd6;border-radius: 0;border: solid 1px #CCC}
    .btn-success:hover {background: #bddab4;color: #436e31;border-color: #6f6f6f;}
</style>