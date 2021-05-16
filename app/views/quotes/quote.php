<?php use Framework\lib\FilterInput; ?>
<?php if (isset($quote) && $quote != false) : ?>
    <div class="page-section">
        <section class="card card-fluid">
            <div class="card-body" style="padding: 0;">
                <form method="post" id="quote-form">
                    <input type="hidden" name="customer_user_id" id="customer_user_id" value="<?= $quote_customer ? $quote_customer->id : '' ?>">
                    <input type="hidden" name="customer_id" id="customer_id" value="<?= $quote_customer ? $quote_customer->customer_id : '' ?>">
                    <input type="hidden" name="quote_uid" value="<?= $quote->uid ?>">

                    <div class="customer-info-container hidden">
                        <div class="functions">
                            <button type="button" title="Continue" class="btn btn-success continue-to-component" style="padding: .375rem 1.75rem;">Continue</button>
                        </div>

                        <div class="customer-form-container" style="padding: 20px 12px;">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group first-name-group">
                                                <label class="custom-form-label">First Name* <i class="fa fa-trash remove-attached-customer "></i></label>
                                                <input type="text" class="form-control form-round first_name_input" name="f_name" placeholder="First Name" required autocomplete="chrome-off" value="<?= $quote_customer ? $quote_customer->firstName : '' ?>" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="custom-form-label">Last Name*</label>
                                                <input type="text" class="form-control form-round last_name_input" name="l_name" placeholder="Last Name" required autocomplete="chrome-off" value="<?= $quote_customer ? $quote_customer->lastName : '' ?>" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="custom-form-label">Email</label>
                                                <input type="email" name="email" class="form-control form-round email_input" placeholder="Email" required autocomplete="chrome-off" value="<?= $quote_customer ? $quote_customer->email : '' ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="custom-form-label">Phone Number</label>
                                                <input type="text" name="phone" class="form-control form-round phone_number_input" placeholder="Phone Number" required autocomplete="chrome-off" value="<?= $quote_customer ? $quote_customer->phone : '' ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label class="custom-form-label">Address</label>
                                                <input type="text" name="address" class="form-control form-round address_input" placeholder="Address" autocomplete="chrome-off" value="<?= $quote_customer ? $quote_customer->address : '' ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label class="custom-form-label">Suburb</label>
                                                <input type="text" name="suburb" class="form-control form-round suburb_input" placeholder="Suburb" autocomplete="chrome-off" value="<?= $quote_customer ? $quote_customer->suburb : '' ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="custom-form-label">Postcode</label>
                                                <input type="text" name="zip" class="form-control form-round zip_input" placeholder="Postcode" autocomplete="chrome-off">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="functions">
                            <button type="button" title="Continue" class="btn btn-success continue-to-component" style="padding: .375rem 1.75rem;float: right">Continue</button>
                        </div>
                    </div>


                    <div class="component-container ">
                        <div class="functions">
                            <button type="button" class="btn btn-secondary back-to-customer-info">Back</button>
                            <button type="submit" name="preview" class="btn btn-info-dark">Preview Quote</button>
                            <button type="submit" name="save" class="btn btn-success">Save</button>
                        </div>

                        <div class="main">
                            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered quote-table">
                                <thead>
                                <tr>
                                    <th class="th__component" style="width: 15%">Component</th>
                                    <th></th>
                                    <th class="th__selection" colspan="3" style="width: 55%">Selection</th>
                                    <th class="th__qty">Qty.</th>
                                    <th class="th__base">DBP</th>
                                    <th class="th__price">RRP</th>
                                    <th class="th__price">Total</th>
                                    <th class="th__remove" style="width: 10%"></th>
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
                                                        <a href="#" class="select-part-btn" data-category="<?= str_replace("Alternative", "", $category) ?>"><?= $category ?></a>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="td__component">
                                                        <label>Merge
                                                            <input type='checkbox' <?= $quote_item->merged == '1' ? 'checked' : '' ?> id='merge-<?= FilterInput::CleanString(str_replace(' ', '', $category)) ?>-tr' class=" merge-component-btn" data-component='<?= FilterInput::CleanString(str_replace(' ', '', $category)) ?>-tr' name='item_merge_component[<?= $category ?>]' value='<?= $category ?>' title="Merge Parts">
                                                        </label>
                                                    </td>
                                                    <td colspan="3">
                                                        <?= $quote_item->IMAGE ? "<a href=\"#\" class=\"preview-img\"><img src=\"".$quote_item->IMAGE."\"></a>" : '' ?>
                                                        <?= $quote_item->item_name ?>
                                                    </td>
                                                    <td>
                                                        <div class="quantity"><input name="items[<?= $quote_item->item_id ?>][item_qty]" type="number" class="display_quantity" value="<?= $quote_item->quantity ?>"></div>
                                                    </td>
                                                    <td class="display_dbp" data-dbp="<?= number_format($quote_item->original_price, 2) ?>">
                                                        $<?= number_format($quote_item->original_price, 2) ?>
                                                    </td>
                                                    <td class="rrp-percentage-td" style="visibility: <?= $quote_item->merged == '1' ? 'hidden' : '' ?>">
                                                        <div class="money">
                                                            <input type="text" data-rrp="<?= number_format($quote_item->price, 2) ?>" name="items[<?= $quote_item->item_id ?>][item_price]" class="" value="<?= number_format($quote_item->price, 2) ?>">
                                                        </div>
                                                        <div class="percentage">
                                                            <input type="text" name="items[<?= $quote_item->item_id ?>][item_price_percent]" class="" value="<?= ($quote_item->price - $quote_item->original_price) / $quote_item->original_price * 100 ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="display_subtotal">$<?= number_format($quote_item->price * $quote_item->quantity, 2) ?></div>
                                                    </td>
                                                    <td>
                                                        <a href="#" class="component-control select-part-btn" data-action="add-more" data-category="<?= str_replace("Alternative", "", $category) ?>" title="Add More"><i class="fa fa-plus"></i></a>
                                                        <a href="#" class="component-control ajax-remove-part-btn" data-quote-id="<?= $quote_item->quote_id ?>" data-id="<?= $quote_item->id ?>" title="Remove"><i class="fa fa-trash"></i></a>
                                                    </td>
                                                    <input type="hidden" name="items[<?= $quote_item->item_id ?>][component]" value="<?= $category ?>">
                                                    <input type="hidden" name="items[<?= $quote_item->item_id ?>][item_id]" value="<?= $quote_item->item_id ?>">
                                                    <input type="hidden" name="items[<?= $quote_item->item_id ?>][item_name]" value="<?= $quote_item->item_name ?>">
                                                    <input type="hidden" name="items[<?= $quote_item->item_id ?>][item_original_price]" value="<?= $quote_item->original_price ?>">
                                                    <input type="hidden" name="items[<?= $quote_item->item_id ?>][quote_item_id]" value="<?= $quote_item->id ?>">
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr class="tr__product" id="<?= FilterInput::CleanString(str_replace(' ', '', $category)) ?>-tr" data-class="<?= FilterInput::CleanString(str_replace(' ', '', $category)) ?>-tr" data-component="<?= $category ?>">
                                                <td class="td__component">
                                                    <a href="#" class="select-part-btn" data-category="<?= str_replace("Alternative", "", $category) ?>"><?= $category ?></a>
                                                </td>
                                                <td class="td__component">
                                                    <label>Merge
                                                        <input type='checkbox' id='merge-<?= FilterInput::CleanString(str_replace(' ', '', $category)) ?>-tr' class=" merge-component-btn" data-component='<?= FilterInput::CleanString(str_replace(' ', '', $category)) ?>-tr' name='item_merge_component[<?= $category ?>]' value='<?= $category ?>' title="Merge Parts">
                                                    </label>
                                                </td>
                                                <td class="td__addComponent" colspan="8">
                                                    <button type="button" class="btn btn-primary select-part-btn" data-category="<?= str_replace("Alternative", "", $category) ?>">
                                                        <i class="fa fa-plus"></i>
                                                        Choose <?= $category ?>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>


                                <tr class="tr__total tr__total--dbp">
                                    <td class="td__label" colspan="8">DBP System:</td>
                                    <td class="td__price" colspan="2">
                                        <input type="hidden" name="system_dbp" value="<?= $quote->DBP ?>">
                                        <span>$<?= number_format($quote->DBP, 2) ?></span>
                                    </td>
                                </tr>
                                <tr class="tr__total tr__total--margin">
                                    <td class="td__label" colspan="8">Mark up %</td>
                                    <td class="td__price" colspan="2">
                                        <div class="tr__total--margin-input">
                                            <input type="text" name="system_margin" value="<?= number_format($quote->margin, 2) ?>">
                                        </div>
                                    </td>
                                </tr>
                                <tr class="tr__total tr__total--profit">
                                    <td class="td__label" colspan="8">Margin (Profit)</td>
                                    <td class="td__price" colspan="2">$<?= number_format($quote->DBP * $quote->margin / 100, 2) ?></td>
                                </tr>
                                <tr class="tr__total tr__total--sys_total">
                                    <td class="td__label" colspan="8">System Total:</td>
                                    <td class="td__price" colspan="2">
                                        <input type="text" name="system_total" value="<?= number_format($quote->system_total, 2) ?>">
                                    </td>
                                </tr>

                                <tr class="tr__total tr__total--subtotal">
                                    <td class="td__label" colspan="8">Sub Total:</td>
                                    <td class="td__price" colspan="2">$<?= number_format($quote->subtotal, 2) ?></td>
                                </tr>
                                <tr class="tr__total tr__total--gst">
                                    <td class="td__label" colspan="8">GST:</td>
                                    <td class="td__price" colspan="2">$<?= number_format($quote->GST, 2) ?></td>
                                </tr>

                                <tr class="tr__total tr__total--labor">
                                    <td class="td__label" colspan="8">Labor:</td>
                                    <td class="td__price" colspan="2">
                                        <input type="text" name="labor" value="<?= number_format($quote->labor, 2) ?>">
                                    </td>
                                </tr>

                                <tr class="tr__total tr__total--final">
                                    <td class="td__label" colspan="8">Total:</td>
                                    <td class="td__price" colspan="2">$<?= number_format($quote->total, 2) ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

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
                                        <textarea name="printed_note" id="printed_note" rows="4" style="width: 100%; padding: 6px;" placeholder="Customers will be able to see this note on their receipt"><?= $quote->printed_note ?></textarea>
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
                            <button type="submit" name="preview" class="btn btn-info-dark" style="float: right">Preview Quote</button>
                        </div>
                    </div>

                    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="quote-preview-modal" style="overflow: auto">
                        <div class="modal-dialog" role="document" style="max-width: 65%">
                            <div class="modal-content">
                                <div class="modal-header bg-info-dark">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <iframe id="quote_preview_iframe" name="receipt_preview_iframe" width="100%" height="400px" style="max-width: 95%%; border: 1px solid #e6e6e6; padding: 0;"></iframe>
                                </div>

                                <div class="modal-footer" style="margin-top: 0">
                                    <button type="submit" name="save" class="btn btn-success">Save</button>
                                    <button type="submit" name="send" class="btn btn-info-dark">Save & Send</button>
                                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="select-part-modal" style="overflow: auto !important;">
                    <div class="modal-dialog" role="document" style="max-width: 95%;margin-top: 2rem;">
                        <div class="modal-content">
                            <div class="modal-header bg-info-dark">
                                <h5 class="modal-title"> Add a Component </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">


                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Select Category</label>
                                                    <select class="form-control filters-select categories-select" id="category-select">
                                                        <option value="">Select Category</option>
                                                        <?php if (isset($categories) && $categories) : ?>
                                                            <?php foreach ($categories as $category) : ?>
                                                                <option value="<?= str_replace("Alternative", "", $category) ?>"><?= $category ?></option>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Select Sub-Category</label>
                                                    <select class="form-control filters-select categories-select" id="subcategory-select">
                                                        <option value="">Select Sub-Category</option>
                                                        <?php if (isset($sub_categories) && $sub_categories) : ?>
                                                            <?php foreach ($sub_categories as $sub_category) : ?>
                                                                <option value="<?= $sub_category ?>"><?= $sub_category ?></option>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Select Manufacturer</label>
                                                    <select class="form-control filters-select" id="manufacturer-select">
                                                        <option value="">Select Manufacturer</option>
                                                        <?php if (isset($manufacturers) && $manufacturers) : ?>
                                                            <?php foreach ($manufacturers as $manufacturer) : ?>
                                                                <option value="<?= $manufacturer ?>"><?= $manufacturer ?></option>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-1">
                                                <div class="shop-grid-controls">
                                                    <div id="viewbuttonlist" class="view-button list "><i class="fa fa-list"></i></div>
                                                    <div id="viewbuttongrid" class="view-button grid active"><i class="fa fa-th"></i></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" style="border-bottom: solid 1px #ccc;">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Search</label>
                                                    <input type="text" class="form-control filters-select search-input" placeholder="Search">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <section class="card card-fluid">
                                                    <!-- .card-body -->
                                                    <div class="card-body">
                                                        <h3 class="card-title"> Price Range</h3>
                                                        <div class="nouislider-wrapper">
                                                            <div id="nouislider-selector"></div>
                                                        </div>
                                                        <!-- grid row -->
                                                        <div class="form-row">
                                                            <!-- grid column -->
                                                            <div class="col">
                                                                <input type="text" id="input-with-keypress-0" class="form-control price-inputs-with-keypress">
                                                            </div>
                                                            <div class="col">
                                                                <input type="text" id="input-with-keypress-1" class="form-control price-inputs-with-keypress">
                                                            </div>
                                                        </div>
                                                        <!-- /grid row -->
                                                    </div>
                                                    <!-- /.card-body -->
                                                </section>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <input id="sortbytype" class="filters-select" type="checkbox">
                                                        <label for="sortbytype" class="checkbox-entry fa" style="display: inline-block; cursor: pointer;margin-top: 25px;">
                                                            Sort Type
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="simple-drop-down form-group" style="margin-top: 8px;margin-bottom: 0;">
                                                            <select id="sortby" style="cursor: default;" class="form-control filters-select">
                                                                <option value="StockCode">Product Code</option>
                                                                <option value="ProductName">Product Name</option>
                                                                <option value="Manufacturer">Manufacturer</option>
                                                                <option value="DBP" selected>Dealer Buy Price</option>
                                                                <option value="RRP">Recommended Retail Price</option>

                                                                <option value="AvailAA">Available ADL</option>
                                                                <option value="AvailAQ">Available BRS</option>
                                                                <option value="AvailAV">Available MEL</option>
                                                                <option value="AvailAN">Available SYD</option>
                                                                <option value="AvailAW">Available WA</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="showonlyavailable" class="checkbox-entry" style="display: inline-block; cursor: pointer;margin-top: 25px;">
                                                    <input id="showonlyavailable" class="filters-select" type="checkbox" style="vertical-align: -2px;">
                                                    Show in stock items only
                                                </label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id="gridviewgrid" class="row shop-grid grid-view">
                                                </div>

                                                <div id="gridviewlist" style="display: none;">
                                                    <table cellpadding="0" cellspacing="0" border="0" class="items-table table table-striped table-bordered" id="items-table" style="width: 100% !important;">
                                                        <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th style="width: 20%">Product</th>
                                                            <th>Stock Code</th>
                                                            <th>ADL-SYD-BRS-MEL-WA</th>
                                                            <th style="width: 5%">DBP</th>
                                                            <th style="width: 5%">RRP</th>
                                                            <th style="width: 5%">Qty.</th>
                                                            <th></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>


                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 dataTables_wrapper" id="products-pagination">

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div >

                <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="image-preview-modal">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content" style="background: transparent">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute;right: 0;padding-top: 8px;padding-right: 15px;">
                                <span aria-hidden="true">&times;</span>
                            </button>

                            <img src="" class="modal-image-preview" style="object-fit: fill">
                        </div>
                    </div>
                </div>

                <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="description-preview-modal">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-info-dark">
                                <h5 class="modal-title"> Description </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div class="description-area"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>


    <script src="<?= VENDOR_DIR ?>nouislider/wNumb.js"></script>
    <script src="<?= VENDOR_DIR ?>nouislider/nouislider.min.js"></script>


    <script>
        $(document).ready(function () {
            priceSlider(0, <?= isset($max_price) && intval($max_price) > 0 ? intval($max_price) : 10000 ?>);
        });
    </script>
<?php endif; ?>