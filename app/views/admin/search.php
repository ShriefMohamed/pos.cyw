<?php

use Framework\lib\Request;

?>
<div class="page-section">

    <?php if (isset($users) && $users != false) : ?>
    <section class="card card-fluid">
        <div class="listing">
            <header style="margin-bottom: 2rem;">
                <header class="card-header">
                    Users (Admins, Technicians) Contain <strong style="color: #2b5a92">"<?= Request::Get('key'); ?>"</strong>

                </header>
            </header>
            <div id="work_listing">
                <div id="listing_loc_matches">
                    <div class="container" style="max-width: 100%">

                        <table cellpadding="0" cellspacing="0" border="0" class="g-datatable datatable table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 5%">ID</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Contact Number</th>
                                <th>Role</th>
                                <th>Member Since</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($users as $user) : ?>
                                <tr class="gradeX">
                                    <td>#<?= $user->id ?></td>
                                    <td><?= $user->firstName . ' ' . $user->lastName ?></td>
                                    <td><?= $user->username ?></td>
                                    <td><?= $user->email ?></td>
                                    <td>
                                        <?= $user->phone ?>
                                        <?php echo ($user->phone2) ? ' & '. $user->phone2 : '' ?>
                                    </td>
                                    <td><?= ucfirst($user->role) ?></td>
                                    <td><?= \Framework\lib\Helper::ConvertDateFormat($user->created) ?></td>
                                    <td class="center">
                                        <a href="<?= HOST_NAME ?>admin/user_edit/<?= $user->id ?>" title="Edit"><i class="fa fa-pencil-alt"></i></a>
                                        <a href="#" data-id="<?= $user->id ?>" data-classname="users" title="Delete" class="ajax-delete"><i class="far fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <hr>

    <?php if (isset($customers) && $customers != false) : ?>
        <section class="card card-fluid">
            <div class="listing">
                <header style="margin-bottom: 2rem;">
                    <header class="card-header">
                        Customers Contain <strong style="color: #2b5a92">"<?= Request::Get('key'); ?>"</strong>

                    </header>
                </header>
                <div id="work_listing">
                    <div id="listing_loc_matches">
                        <div class="container" style="max-width: 100%">

                            <table cellpadding="0" cellspacing="0" border="0" class="g-datatable datatable table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th style="width: 5%">ID</th>
                                    <th>Name</th>
                                    <th>Company</th>
                                    <th>Mobile/Phone</th>
                                    <th>Email</th>
                                    <th>Member Since</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($customers as $customer) : ?>
                                    <tr class="gradeX">
                                        <td><a href="<?= HOST_NAME . 'customers/customer/' . $customer->id ?>">#<?= $customer->id ?></a></td>
                                        <td><a href="<?= HOST_NAME . 'customers/customer/' . $customer->id ?>">#<?= $customer->firstName.' '.$customer->lastName ?></a></td>
                                        <td><?= $customer->companyName ?></td>

                                        <td><?= $customer->phone. ($customer->phone2 ? ' / '. $customer->phone2 : '') ?></td>
                                        <td><?= $customer->email ?></td>
                                        <td><?= \Framework\lib\Helper::ConvertDateFormat($customer->created, true) ?></td>

                                        <td class="text-center">
                                            <a href="<?= HOST_NAME.'customers/customer/'.$customer->id ?>"><i class="fa fa-edit"></i></a>
                                            <a href="#" data-id="<?= $customer->id ?>" data-classname="users" data-extra-action="customer_delete" title="Delete" class="ajax-delete"><i class="far fa-trash-alt"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <hr>

    <?php if (isset($items) && $items != false) : ?>
        <section class="card card-fluid">
            <div class="listing">
                <header style="margin-bottom: 2rem;">
                    <header class="card-header">
                        Items <strong style="color: #2b5a92">"<?= Request::Get('key'); ?>"</strong>

                    </header>
                </header>
                <div id="work_listing">
                    <div id="listing_loc_matches">
                        <div class="container" style="max-width: 100%">

                            <table cellpadding="0" cellspacing="0" border="0" class="g-datatable datatable table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th width="15%">Item UID</th>
                                    <th>Item</th>
                                    <th>Type</th>
                                    <th>Shop SKU</th>
                                    <th>Department</th>
                                    <th>Brand</th>
                                    <th>Category</th>
                                    <th>Tax</th>
                                    <th style="width: 5%">Available QTY</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($items as $item) : ?>
                                    <tr class="gradeX">
                                        <td><a href="<?= HOST_NAME . 'pos/item/' . $item->id ?>">#<?= $item->uid ?></a></td>
                                        <td><a href="<?= HOST_NAME . 'pos/item/' . $item->id ?>"><?= $item->item ?></a></td>
                                        <td><?= $item->item_type ?></td>

                                        <td><?= $item->shop_sku ?></td>
                                        <td><?= $item->department ?></td>
                                        <td><?= $item->brand_name ?></td>
                                        <td><?= $item->category_name ?></td>
                                        <td><?= $item->rate ? $item->class.' ('.$item->rate.'%)' : 'None' ?></td>
                                        <td style="width: 5%"><?= $item->available_stock ?></td>

                                        <td class="center">
                                            <a href="#" title="Archive" class="archive-btn" data-id="<?= $item->id ?>" data-function="item"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <hr>

    <?php if (isset($invoices) && $invoices !== false) : ?>
    <section class="card card-fluid">
        <div class="listing">
            <header style="margin-bottom: 2rem;">
                <header class="card-header">
                    Invoices: <strong style="color: #2b5a92">"<?= Request::Get('key'); ?>"</strong>

                </header>
            </header>
            <div id="work_listing">
                <div id="listing_loc_matches">
                    <div class="container" style="max-width: 100%">

                        <table cellpadding="0" cellspacing="0" border="0" class="g-datatable datatable table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th width="15%">#ID</th>
                                <th>#REF</th>
                                <th>CUSTOMER</th>
                                <th>DATE</th>
                                <th>SUBTOTAL</th>
                                <th>DISCOUNT</th>
                                <th>TAX</th>
                                <th>TOTAL</th>
                                <th>PAID</th>
                                <th>DUE</th>
                                <th>STATUS</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($invoices as $invoice) : ?>
                                <tr class="gradeX">
                                    <td><a href="<?= HOST_NAME . 'pos/invoice/' . $invoice->id ?>">#<?= $invoice->id ?></a></td>
                                    <td><a href="<?= HOST_NAME . 'pos/invoice/' . $invoice->id ?>">#<?= $invoice->reference ?></a></td>
                                    <td><a target="_blank" href="<?= $invoice->user_id ? HOST_NAME.'customers/customer/'.$invoice->user_id : '#' ?>"><?= $invoice->firstName.' '.$invoice->lastName ?></a></td>
                                    <td class="date ">
                                        <time datetime="<?= $invoice->created ?>"><?= \Framework\lib\Helper::ConvertDateFormat($invoice->created) ?></time>
                                    </td>
                                    <td>$<?= number_format($invoice->subtotal, 2) ?></td>
                                    <td>$<?= number_format($invoice->discount, 2) ?></td>
                                    <td>$<?= number_format($invoice->tax, 2) ?></td>
                                    <td>$<?= number_format($invoice->total, 2) ?></td>
                                    <td>$<?= number_format($invoice->amount_paid, 2) ?></td>
                                    <td>$<?= number_format($invoice->amount_due, 2) ?></td>
                                    <td><?= strtoupper($invoice->status) ?></td>

                                    <td class="center">
                                        <?php if ($invoice->status != 'voided') : ?>
                                            <a href="<?= HOST_NAME.'pos/invoice_void/'.$invoice->id ?>">Void</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

</div>

<style>
    .page-inner {padding-right: 0;padding-left: 0;}
    hr {margin-top: 2rem;margin-bottom: 2rem;}
</style>