<?php if (isset($sale) && $sale) : ?>
<div id="register">
    <form method="post">

        <section class="main">
            <div class="register-customer">
                <input type="hidden" name="customer-id" id="customer-id-holder" value="<?= isset($sale->customer_id) && $sale->customer_id ? $sale->customer_id : '' ?>">

                <div id="customerInfo" class="block placeholder"><?= $sale->customer_name ?: 'No Customer Selected' ?></div>
                <button id="customerRemoveButton" tabindex="9" type="button" class="gui-def-button" style="display:<?= isset($sale->customer_id) && $sale->customer_id ? '' : 'none' ?>"><i class="fa fa-trash"></i> Remove</button>

                <div class="search" style="display:<?= isset($sale->customer_id) && $sale->customer_id ? 'none' : '' ?>">
                    <input tabindex="6" type="text" id="find_customer_text" autocomplete="off" placeholder="Search Customers" class="group group-start small">
                    <button id="searchCustomerButton" type="button" class="group group-end gui-def-button">
                        <i class="fa fa-search"></i> Search
                    </button>
                </div>

                <a href="<?= HOST_NAME ?>customers/customer_add" target="_blank" id="newCustomerButton" class="gui-def-button"><i class="fa fa-plus"></i> New</a>
            </div>

            <div class="lines">
                <div class="lines">
                    <nav class="menu menu-sale">
                        <div class="search ">
                            <div class="search">
                                <input class="data_control group group-start small itembarcode receiptbarcode giftcardbarcode customerbarcode workorderbarcode priority_auto_focus" type="search" tabindex="1" size="20" maxlength="255" id="add_search_item_text" placeholder="Item" autocomplete="off" title="Add Item Search">
                                <button id="registerItemSearch" class="group group-end gui-def-button" type="button">
                                    <i class="fa fa-search"></i> Search
                                </button>
                            </div>
                        </div>

                        <a href="<?= HOST_NAME ?>pos/item_add" target="_blank" id="newProductButton" class="gui-def-button"><i class="fa fa-plus"></i> New</a>

                        <div class="" style="float: right;margin-right: 2rem;margin-top: 10px;">
                            <label>Pricing Level:</label>
                            <select id="pricing-level" name="pricing-level" style="margin-left: 8px;padding: 6px;background: #FFF;border-radius: 3px;">
                                <?php if (isset($pricing_levels) && $pricing_levels) : ?>
                                    <option value="0">Item's RRP Price</option>
                                    <?php foreach ($pricing_levels as $pricing_level) : ?>
                                        <option <?= $sale->pricing_level == $pricing_level->id ? 'selected' : '' ?> value="<?= $pricing_level->id ?>" data-teir="<?= strtolower($pricing_level->teir) ?>" data-rate="<?= $pricing_level->rate ?>"><?= $pricing_level->teir.' ('.$pricing_level->rate.'%)' ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <button id="miscButton" tabindex="3" class="gui-def-button">Misc.</button>

                        <div style="display: none;" id="add_expander_menu_holder">
                        </div>

                        <div style="display: none" id="add_expander_holder">
                            <h2 id="chargeTitle">Add Miscellaneous Charge</h2>
                            <table id="add_expander">
                                <tbody>
                                <tr>
                                    <td rowspan="4" class="note">
                                        <textarea name="note" class="add_expander error_no_empty_notes" tabindex="2000" cols="25" rows="5" id="add_misc_description" spellcheck="false">Miscellaneous Charge</textarea>
                                    </td>
                                    <td><label>Price</label></td>
                                    <td>
                                        <div class="money-field-container money-line-edit">
                                            <input name="price" type="text" class="add_expander money" tabindex="2001" size="6" maxlength="15" value="0.00">
                                        </div>
                                    </td>
                                    <td><label>Discount</label></td>
                                    <td>
                                        <select name="discount_id" class="add_expander" tabindex="2005">
                                            <option value="0">--</option>
                                            <option value="1">
                                            </option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label>Cost</label></td>
                                    <td>
                                        <div class="money-field-container money-line-edit">
                                            <input name="cost" type="text" class="add_expander money" tabindex="2002" size="6" maxlength="15" value="0.00" step=".01">
                                        </div>
                                    </td>
                                    <td><label>Tax Class</label></td>
                                    <td>
                                        <select name="class_name" class="add_expander" tabindex="2006">
                                            <option value="Item" notranslate="">Item</option>
                                            <option value="GST" notranslate="">GST</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label>Qty</label></td>
                                    <td><input name="quantity" type="number" class="add_expander number" tabindex="2003" size="6" maxlength="15" value="1"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td colspan="3">
                                        <label class="checkbox">
                                            <input name="tax" type="checkbox" class="add_expander" tabindex="2004" checked="checked"> Tax
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="submit">
                                        <button id="saveChargeButton" type="button" tabindex="2008" class="gui-def-button">Save</button>
                                        <button id="cancelChargeButton" type="button" tabindex="2009" class="gui-def-button">Cancel</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                    </nav>
                </div>


                <table>
                    <thead>
                    <tr>
                        <th></th>
                        <th>Description</th>
                        <th class="th-money">Buy Price</th>
                        <th class="th-money">Retail Price</th>
                        <th>Qty.</th>
                        <th>Tax</th>
                        <th class="th-money">Subtotal</th>
                    </tr>
                    </thead>
                    <tbody id="register_transaction" class="register_transaction">
                    <?php if (isset($sale_items) && $sale_items !== false) : ?>
                        <?php foreach ($sale_items as $sale_item) : ?>
                            <tr class="item-row item-<?= $sale_item->item_id ?> <?= $sale_item->item_type == 'refund' ? 'refund refund-item-'.$sale_item->item_id : '' ?>" data-item-id="<?= $sale_item->item_id ?>" data-sale-item-id="<?= $sale_item->id ?>" data-item-stock="<?= $sale_item->available_stock ?>">
                                <input type="hidden" name="items[<?= $sale_item->item_id ?>][id]" value="<?= $sale_item->item_id ?>">
                                <input type="hidden" name="items[<?= $sale_item->item_id ?>][sale_item_id]" value="<?= $sale_item->id ?>">
                                <input type="hidden" name="items[<?= $sale_item->item_id ?>][type]" value="<?= $sale_item->item_type ?>">
                                <td class="row_controls">
                                    <a href="#" class="control remove-item-row-ajax"><i class="fa fa-trash"></i></a>
                                </td>
                                <td class="register-lines-control display_item_name">
                                    <a href="#"><span><?= $sale_item->item ?></span></a>
                                    <?php if ($sale_item->discount_id && $sale_item->title && $sale_item->type && $sale_item->discount_value) : ?>
                                    <div id="discountDescription" class="discount" data-discount="<?= $sale_item->type == 'fixed' ? $sale_item->discount_value : ($sale_item->rrp_price * $sale_item->discount_value) / 100 ?>">Discount:
                                        <span> <?= $sale_item->title ?> </span>
                                        <span class="item-displayed-discount">-$<?= $sale_item->discount ?></span>
                                        <input type="hidden" name="items[<?= $sale_item->item_id ?>][discount]" value="<?= $sale_item->discount_id ?>">
                                    </div>
                                    <?php endif; ?>
                                </td>
                                <td class="money"><?= number_format($sale_item->buy_price, 2) ?></td>
                                <td class="display_price money" data-price="<?= $sale_item->price ?>" data-dbp="<?= $sale_item->item_type == 'refund' ? '-'.$sale_item->buy_price : $sale_item->buy_price ?>" data-rrp-percentage="<?= $sale_item->rrp_percentage ?>">$<?= number_format($sale_item->price, 2) ?></td>
                                <td>
                                    <input type="number" name="items[<?= $sale_item->item_id ?>][qty]" class="display_quantity number xx-small" maxlength="8" tabindex="3000" value="<?= $sale_item->quantity ?>">
                                </td>
                                <td class="display_tax" data-tax="10"><?= $sale_item->class.' ('.$sale_item->rate.'%)' ?></td>
                                <td class="display_subtotal money">$<?= number_format($sale_item->rrp_price, 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>


                <footer id="sales-buttons">
                    <div class="sale-line-actions">
                        <button id="deleteAllButton" class="needs-sale-lines gui-def-button hidden" type="button">
                            Delete All
                        </button>
                    </div>
                    <div class="sale-actions">
                        <button id="discountButton" class="gui-def-button" type="button" data-toggle="modal" data-target="#discounts-modal">
                            Apply Discount
                        </button>
                        <button id="notesButton" class="small gui-def-button" type="button">
                            Show Notes
                        </button>
                    </div>
                </footer>
            </div>

            <div id="sale-note-wrapper" class="lines hidden">
                <table style="border-top: 0;">
                    <tbody>
                    <tr style="margin-top: 8px">
                        <td style="border-bottom: none; padding: 10px 15px 0;">
                            <label for="printed_note">Receipt note</label>
                        </td>
                        <td style="border-bottom: none; border-left: 1px solid #ccc; padding: 10px 15px 0;">
                            <label for="internal_note">Internal note</label>
                            <a href="javascript:;" style="float: right" class="add-time-button">
                                Add time <i class="fa fa-time"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 15px 15px;">
                            <textarea name="printed_note" id="printed_note" rows="4" style="width: 100%; padding: 6px;" placeholder="Customers will be able to see this note on their receipt"><?= $sale->printed_note ?></textarea>
                        </td>
                        <td style="border-left: 1px solid #ccc; padding: 0 15px;">
                            <textarea name="internal_note" id="internal_note" rows="4" style="width: 100%; padding: 6px;" placeholder="Customers won't be able to see this note"><?= $sale->internal_note ?></textarea>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <aside>
            <div class="cirrus transaction-sidebar">
                <div id="register_totals">
                    <div class="register-payment-block register-totals">
                        <div class="register-summary">
                            <table class="totals">
                                <tbody>
                                <tr>
                                    <th>Subtotal</th>
                                    <td id="popdisplay_subtotal" class="money">$0.00</td>
                                </tr>
                                <tr class="discount">
                                    <th>Discounts</th>
                                    <td id="discountValue" class="money">$0.00</td>
                                </tr>
                                </tbody>
                            </table>
                            <table>
                                <tbody>
                                <tr class="total">
                                    <th id="totalName">Total</th>
                                    <td id="sale_total">$0.00</td>
                                </tr>
                                <tr>
                                    <th class="totals_title" colspan="2">Tax Summary</th>
                                </tr>
                                <tr>
                                    <th id="taxName_1" class="no-border">Tax</th>
                                    <td id="taxValue_1" class="no-border">$0.00</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="register-payment-block">
                    <button id="paymentButton" tabindex="2" class="cr-button cr-button--primary cr-button--large cr-button--fill" type="submit" name="payment">
                        <span class="cr-button__content">Payment</span>
                    </button>
                </div>
                <div class="register-sidebar-buttons">
                    <button id="saveAsQuoteButton" href="#" class="cr-button cr-button--large" type="submit" name="quote">
                        <span class="cr-button__content"><i class="fa fa-clipboard-list"></i></span>
                        <span class="cr-button__content">Save as Quote</span>
                    </button>
                    <button id="cancelButton" class="cr-button cr-button--large" type="submit" name="cancel">
                        <span class="cr-button__content">Cancel Sale</span>
                    </button>
                </div>
            </div>
        </aside>



        <div class="modal fade" id="search-results-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body clearfix" style="padding: 20px;">

                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="discounts-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title"> Apply Discount </h5>
                    </div>

                    <div class="modal-body">
                        <fieldset>
                            <div class="form-group">
                                <label class="custom-form-label">Discounts</label>
                                <select class="form-control form-round" id="discount-select" name="discount-id">
                                    <option value="0">None</option>
                                    <?php if (isset($discounts) && $discounts !== false) : ?>
                                        <?php foreach ($discounts as $discount) : ?>
                                            <option value="<?= $discount->id ?>" data-title="<?=  $discount->title ?>" data-type="<?= $discount->type ?>" data-rate="<?= $discount->discount ?>"><?= $discount->title ?> <?= $discount->type == 'fixed' ? '$'.$discount->discount : $discount->discount.'%' ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </fieldset>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="apply-discount-button" data-dismiss="modal">Apply</button>
                        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        $('#pricing-level').trigger('change');
        ApplyDiscount();
        UpdateTotal();

        $(document).on('click', '.remove-item-row-ajax', function (e) {
            var $item_row = $(this).closest('.item-row');
            var $sale_item_id = $item_row.data('sale-item-id');

            $.ajax({
                type: "POST",
                url: "/ajax/sale_remove_item/" + $sale_item_id,
                dataType: 'json',
                beforeSend: function () {
                    Pace.restart();
                },
                success: function (data) {
                    $item_row.remove();
                    if ($('#register_transaction .item-row').length == 0) {
                        $('#deleteAllButton').addClass('hidden');
                    }
                    UpdateTotal();
                },
                fail: function (err) {
                    showFeedback('error', err.responseText);
                }
            });
        });
    });
</script>
<?php endif; ?>