<?php use Framework\lib\FilterInput; ?>
<?php if (isset($quote) && $quote != false) : ?>
    <div class="page-section">
        <div class="row">
            <div class="col-md-12">

                <section class="card card-fluid">
                    <div class="card-body" style="padding: 0;">
                        <form method="post" id="quote-form">
                            <div class="customer-info-container">
                                <div class="functions">
                                    <button type="button" title="Continue" class="btn btn-success continue-to-component" style="padding: .375rem 1.75rem;">Save</button>
                                </div>

                                <div class="customer-form-container" style="padding: 20px 12px;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <legend>Client Information</legend>
                                        </div>
                                        <div class="col-md-12">
                                            <?php if (isset($quote_customer) && $quote_customer != false) : ?>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group first-name-group">
                                                            <label class="custom-form-label">Full Name</label>
                                                            <input type="text" class="form-control form-round first_name_input" name="f_name" placeholder="First Name" required autocomplete="chrome-off" value="<?= $quote_customer ? $quote_customer->firstName : '' ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="custom-form-label">Email</label>
                                                            <input type="email" name="email" class="form-control form-round email_input" placeholder="Email" required autocomplete="chrome-off" value="<?= $quote_customer ? $quote_customer->email : '' ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="custom-form-label">Phone Number</label>
                                                            <input type="text" name="phone" class="form-control form-round phone_number_input" placeholder="Phone Number" required autocomplete="chrome-off" value="<?= $quote_customer ? $quote_customer->phone : '' ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="custom-form-label">Address</label>
                                                            <input type="text" name="address" class="form-control form-round address_input" placeholder="Address" autocomplete="chrome-off" value="<?= $quote_customer ? $quote_customer->address.' - '.$quote_customer->suburb.' '.$quote_customer->zip : '' ?>" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="main">
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered quote-table">
                                        <thead>
                                        <tr>
                                            <th class="th__component" style="width: 15%">Component</th>
                                            <th>System</th>
                                            <th class="th__selection" colspan="3" style="width: 55%">Selection</th>
                                            <th class="th__qty">Qty.</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php if (isset($categories) && $categories) : ?>
                                            <?php foreach ($categories as $category) : ?>
                                                <?php if (isset($quote_items) && $quote_items !== false && isset($quote_items[$category])) : ?>
                                                    <?php foreach ($quote_items[$category] as $key => $quote_item) : ?>
                                                        <tr class="tr__product tr__product-row tr__product-<?= $quote_item->item_id ?> <?= FilterInput::CleanString(str_replace(' ', '', $category)) ?>-tr" id="<?= FilterInput::CleanString(str_replace(' ', '', $category)) ?>-tr" data-class="<?= FilterInput::CleanString(str_replace(' ', '', $category)) ?>-tr" data-component="<?= $category ?>">
                                                            <td class="td__component">
                                                                <?php if ($key == 0) : ?>
                                                                    <a href="#" class="select-part-btn" data-category="<?= $category ?>"><?= $category ?></a>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td class="td__component">
                                                                <label><?= $quote_item->merged == '1' ? 'Yes' : 'No' ?></label>
                                                            </td>
                                                            <td colspan="3">
                                                                <?= $quote_item->IMAGE ? "<a href=\"#\" class=\"preview-img\"><img src=\"".$quote_item->IMAGE."\"></a>" : '' ?>
                                                                <?= $quote_item->item_name ?>
                                                            </td>
                                                            <td>
                                                                <div class="quantity"><input disabled name="items[<?= $quote_item->item_id ?>][item_qty]" type="number" class="display_quantity" value="<?= $quote_item->quantity ?>"></div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>

                                        </tbody>
                                    </table>
                                </div>

                                <hr>

                                <div id="sale-note-wrapper">
                                    <table style="border-top: 0;width: 100%">
                                        <tbody>
                                        <tr style="margin-top: 8px">
                                            <td style="border-bottom: none; padding: 10px 15px 0;">
                                                <label for="printed_note">Receipt note</label>
                                            </td>
                                            <td style="border-bottom: none; border-left: 1px solid #ccc; padding: 10px 15px 0;">
                                                <label for="internal_note">Internal note</label>
                                                <a href="javascript:;" style="float: right" class="add-time-button">
                                                    Add time <i class="fa fa-clock"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 10px 15px 15px;">
                                                <textarea name="printed_note" id="printed_note" rows="4" style="width: 100%; padding: 6px;" disabled><?= $quote->printed_note ?></textarea>
                                            </td>
                                            <td style="border-left: 1px solid #ccc; padding: 0 15px;">
                                                <textarea name="internal_note" id="internal_note" rows="4" style="width: 100%; padding: 6px;" placeholder="Customers won't be able to see this note"><?= $quote->internal_note ?></textarea>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>


                                <div class="functions">
                                    <button type="submit" name="save" class="btn btn-success" style="float: right">Save</button>
                                    <button type="button" class="btn btn-info-dark" style="float: right">
                                        <a href="<?= HOST_NAME.'admin/quote_job_complete/'.$quote->id ?>" >Complete Job</a>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>

            </div>
        </div>
    </div>


    <link rel="stylesheet" href="<?= CSS_DIR ?>quotes.css">
    <style>
        .btn a, .btn a:hover {color: #FFF !important;text-decoration: none}
    </style>
<?php endif; ?>