<script src="<?= VENDOR_DIR.'onscan.js/onscan.js' ?>"></script>
<script>
    // Enable scan events for the entire document
    onScan.attachTo(document, {
        suffixKeyCodes: [13], // enter-key expected at the end of a scan
        reactToPaste: true, // Compatibility to built-in scanners in paste-mode (as opposed to keyboard-mode)
        onScan: function(sCode, iQty) { // Alternative to document.addEventListener('scan')
            console.log('Scanned: ' + iQty + 'x ' + sCode);
        },
        onKeyDetect: function(iKeyCode){ // output all potentially relevant key events - great for debugging!
            console.log('Pressed: ' + iKeyCode);
        }
    });
</script>

<div id="register">
    <form method="post">

        <section class="main">
            <div class="register-customer">
                <input type="hidden" name="user-id" id="customer-id-holder" value="">
                <input type="hidden" name="customer-id" id="user-customer-id-holder" value="">

                <div id="customerInfo" class="block placeholder">No Customer Selected</div>
                <button id="customerRemoveButton" tabindex="9" type="button" class="gui-def-button" style="display: none;"><i class="fa fa-trash"></i> Remove</button>
                <button id="customerShipButton" tabindex="10" type="button" class="gui-def-button" style="display: none;">Ship</button>

                <div class="search" >
                    <input tabindex="6" type="text" id="find_customer_text" autocomplete="off" placeholder="Search Customers" class="group group-start small">
                    <button id="searchCustomerButton" type="button" class="group group-end gui-def-button" data-customer-action="sale_add">
                        <i class="fa fa-search"></i> Search
                    </button>
                </div>
                <button id="newCustomerButton" type="button" class="gui-def-button" data-toggle="modal" data-target="#newCustomerModal"><i class="fa fa-plus"></i> New</button>



                <div id="sale-ship-wrapper" class="register-customer-form hidden">
                    <h3>Edit Ship To</h3>
                    <div>
                        <div id="ship_to_data" style="display: block;">
                            <table cellpadding="1" cellspacing="0" border="0" class="edit-ship-to">
                                <tbody>
                                <tr>
                                    <td colspan="2">
                                        <div class="cirrus table-address-wrapper"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 120px">Shipping <br>Instructions</td>
                                    <td>
                                        <textarea class="ship_to_data" tabindex="70" cols="30" rows="3" name="ship_note"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        <label>
                                            <input class="ship_to_data" type="checkbox" tabindex="71" name="shipped"> Mark as already shipped.
                                        </label>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="submit">
                                <button id="customerShipSaveButton" type="button" tabindex="72" class="gui-def-button">Save</button>
                                <button id="customerShipCancelButton" type="button" tabindex="74" class="gui-def-button">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>

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
                                        <option value="<?= $pricing_level->id ?>" data-teir="<?= strtolower($pricing_level->teir) ?>" data-rate="<?= $pricing_level->rate ?>"><?= $pricing_level->teir.' ('.$pricing_level->rate.'%)' ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <button id="miscButton" type="button" tabindex="3" class="gui-def-button">Misc.</button>

                        <div id="add_expander_holder">

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
                                Add time <i class="fa fa-clock"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 15px 15px;">
                            <textarea name="printed_note" id="printed_note" rows="4" style="width: 100%; padding: 6px;" placeholder="Customers will be able to see this note on their receipt"></textarea>
                        </td>
                        <td style="border-left: 1px solid #ccc; padding: 0 15px;">
                            <textarea name="internal_note" id="internal_note" rows="4" style="width: 100%; padding: 6px;" placeholder="Customers won't be able to see this note"></textarea>
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

    <div class="modal fade" id="newCustomerModal">
        <div class="modal-dialog" style="max-width: 65%;">
            <div class="modal-content">
                <form method="post" id="newCustomerForm">
                    <div class="modal-header bg-info-dark">
                        <h5 class="modal-title"> Create New Customer </h5>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="custom-form-label">First Name*</label>
                                            <input type="text" class="form-control form-round" name="f_name" placeholder="First Name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="custom-form-label">Last Name*</label>
                                            <input type="text" class="form-control form-round" name="l_name" placeholder="Last Name" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="custom-form-label">Company</label>
                                            <input type="text" class="form-control form-round" name="company" placeholder="Company">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="custom-form-label">Email</label>
                                            <input type="email" name="email" class="form-control form-round" placeholder="Email">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Mobile</label>
                                            <input type="number" name="mobile" class="form-control form-round" placeholder="Mobile Number">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Phone</label>
                                            <input type="number" name="phone" class="form-control form-round" placeholder="Phone">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Notes</label>
                                            <textarea class="form-control form-round" name="notes" placeholder="Notes"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer" style="margin-top: 0">
                        <button type="submit" class="btn btn-info-dark" id="apply-discount-button">Save</button>
                        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
