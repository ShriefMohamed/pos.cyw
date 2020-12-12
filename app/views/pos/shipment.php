<?php if (isset($item) && $item !== false) : ?>
<div id="view" style="display: block;">
    <div class="view">
        <form method="post">
            <input type="hidden" name="customer-id" value="<?= $item->customer_id ?>">
            <input type="hidden" name="customer-user-id" value="<?= $item->customer_user_id ?>">
            <div class="functions">
                <button title="Save Changes" id="saveButton" class="save" name="save" type="submit">Save Changes</button>
            </div>
            <div class="main">
                <article class="view_tab_details tab loaded">
                    <div class="content">
                        <div class="view-columns">
                            <table class="view-layout set_auto_focus">
                                <tbody>
                                <tr>
                                    <td>
                                        <table class="view-column ">
                                            <tbody>
                                            <tr class="view_field_box">
                                                <td colspan="2" class="view_field_box " id="details_2">
                                                    <h3>Details</h3>
                                                    <table>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <table class="tab_columns">
                                                                    <tbody>
                                                                    <tr id="view_f_3">
                                                                        <td class="label">
                                                                            <label for="view_shipped">Shipped</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <input type="checkbox" name="shipped" id="view_shipped" class="view_view boolean data_control shipment_shipped" tabindex="200" data-id="<?= $item->id ?>" <?= $item->shipped ? 'checked' : '' ?>>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_4">
                                                                        <td class="label">
                                                                            <label for="view_transaction_id">Sale</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <span class="view_field_relation"><?= $item->sale_id ?> <a href="<?= HOST_NAME.'pos/sale/'.$item->sale_id ?>"><i class="fa fa-pencil-alt "></i></a></span>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>

                                            <tr class="view_field_box">
                                                <td colspan="2" class="view_field_box " id="details_6">
                                                    <h3>Customer</h3>
                                                    <table>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <table class="tab_columns">
                                                                    <tbody>
                                                                    <tr id="view_f_7">
                                                                        <td class="label">
                                                                            <label for="view_customer_id_function__getFullName">Customer</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <span style="width: auto; display: inline;" class="view_field_relation"><?= $item->firstName.' '.$item->lastName ?></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_10">
                                                                        <td class="label">
                                                                            <label for="view_customer_id_contact_id_mobile">Mobile</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <span style="width: auto; display: inline;" class="view_field_relation"><?= $item->phone ?></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_8">
                                                                        <td class="label">
                                                                            <label for="view_customer_id_contact_id_phone_home">Phone</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <span style="width: auto; display: inline;" class="view_field_relation"><?= $item->phone2 ?></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_11">
                                                                        <td class="label">
                                                                            <label for="view_customer_id_contact_id_email">Email</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <span style="width: auto; display: inline;" class="view_field_relation"><?= $item->email ?></span>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                            <td>
                                                                <table class="tab_columns">
                                                                    <tbody>
                                                                    <tr id="view_f_13">
                                                                        <td class="view_functions " colspan="2">
                                                                            <span class="function">
                                                                                <a href="<?= HOST_NAME.'customers/customer_update/'.$item->customer_id ?>"><button id="editCustomerButton" title="Edit" class="gui-def-button"><i class="fa fa-pencil-alt"></i> Edit Customer</button></a>
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>

                                            <tr class="view_field_box">
                                                <td colspan="2" class="view_field_box " id="details_15">
                                                    <h3>Address</h3>
                                                    <table>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <table class="tab_columns">
                                                                    <tbody>
                                                                    <tr id="view_f_16">
                                                                        <td class="label">
                                                                            <label for="view_f_name">First Name</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <input type="text" autocomplete="off" value="<?= $item->firstName ?>" size="30" maxlength="255" id="view_f_name" name="f_name" class="view_view string data_control" tabindex="205">
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_17">
                                                                        <td class="label">
                                                                            <label for="view_l_name">Last Name</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <input type="text" autocomplete="off" value="<?= $item->lastName ?>" size="30" maxlength="255" id="view_l_name" name="l_name" class="view_view string data_control" tabindex="206">
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_18">
                                                                        <td class="label">
                                                                            <label for="view_company">Company</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <input type="text" autocomplete="off" value="<?= $item->companyName ?>" size="30" maxlength="255" id="view_company" name="company" class="view_view string data_control" placeholder="Company" tabindex="207">
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_19">
                                                                        <td class="form_field_holder " colspan="2">
                                                                            <div class="cirrus">
                                                                                <table class="table-address">
                                                                                    <tbody>
                                                                                    <tr>
                                                                                        <td class="table-address__label label"><label for="contact_id.address1">Address</label></td>
                                                                                        <td class="table-address__value"><input id="contact_id.address1" name="address1" class="view_view" tabindex="208" placeholder="Address" type="text" autocomplete="off" size="30" maxlength="255" value="<?= $item->address ?>" style="height: 32px; line-height: 32px;"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td class="table-address__label label"><label for="contact_id.address2">Address 2</label></td>
                                                                                        <td class="table-address__value"><input id="contact_id.address2" name="address2" class="view_view" tabindex="208" placeholder="Address 2" type="text" autocomplete="off" size="30" maxlength="255" value="<?= $item->address2 ?>" style="height: 32px; line-height: 32px;"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td class="table-address__label label"><label for="contact_id.city">City</label></td>
                                                                                        <td class="table-address__value"><input id="contact_id.city" name="city" class="view_view" tabindex="208" placeholder="City" type="text" autocomplete="off" size="30" maxlength="255" value="<?= $item->city ?>" style="height: 32px; line-height: 32px;"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td class="table-address__label label"><label for="contact_id.state">Suburb</label></td>
                                                                                        <td class="table-address__value"><input type="text" name="suburb" autocomplete="off" size="30" maxlength="255" id="contact_id.state" tabindex="208" placeholder="Suburb" class="view_view" value="<?= $item->suburb ?>" style="height: 32px; line-height: 32px;"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td class="table-address__label label"><label for="contact_id.zip">Postcode</label></td>
                                                                                        <td class="table-address__value"><input id="contact_id.zip" name="zip" class="view_view" tabindex="208" placeholder="Postcode" type="text" autocomplete="off" size="30" maxlength="255" value="<?= $item->zip ?>" style="height: 32px; line-height: 32px;"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td class="table-address__label label"><label for="">Contact Phone</label></td>
                                                                                        <td class="table-address__value"><input id="" name="phone" class="view_view" tabindex="208" placeholder="Contact Phone" type="text" autocomplete="off" size="30" maxlength="255" value="<?= $item->phone ?>" style="height: 32px; line-height: 32px;"></td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td>
                                        <table class="view-column ">
                                            <tbody>
                                            <tr class="view_field_box">
                                                <td colspan="2" class="view_field_box " id="details_30">
                                                    <h3>Notes</h3>
                                                    <table>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <table class="tab_columns">
                                                                    <tbody>
                                                                    <tr id="view_f_31">
                                                                        <td colspan="2">
                                                                            <table>
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td class="form_field_holder">
                                                                                        <textarea rows="15" cols="50" id="view_ship_note" name="ship_note" class="view_view textarea data_control" tabindex="217"><?= $item->shipping_instructions ?></textarea>
                                                                                    </td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div>
                                            <table class="tab_columns">
                                                <tbody>
                                                <tr id="view_f_34">
                                                    <td class="view_listing" colspan="2">
                                                        <h2 class="child-listing-title" id="ship_to_listings_transaction_lines_view_title">Lines</h2>
                                                        <div id="ship_to_listings_transaction_lines_view" class="is_pannel">
                                                            <div class="listing">
                                                                <div id="work_ship_to_listings_transaction_lines_view">
                                                                    <div id="ship_to_listings_transaction_lines_view_single">
                                                                        <div class="container" style="max-width: 100%;padding-top: 10px">
                                                                            <table cellpadding="0" cellspacing="0" border="0" class="g-datatable datatable table table-striped table-bordered">
                                                                                <thead>
                                                                                <tr>
                                                                                    <th class="">ITEM</th>
                                                                                    <th class="th-number">QTY</th>
                                                                                    <th class="th-money">RETAIL</th>
                                                                                    <th class="th-money">SUBTOTAL</th>
                                                                                    <th class="th-money">DISCOUNT</th>
                                                                                    <th class="">TAX</th>
                                                                                    <th class="th-money">TOTAL</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                <?php if (isset($sale_items) && $sale_items !== false) : ?>
                                                                                    <?php foreach ($sale_items as $sale_item) : ?>
                                                                                        <tr style="" class="">
                                                                                            <td class="string ">
                                                                                                <a title="Edit Record" href="<?= HOST_NAME.'pos/item/'.$sale_item->item_id ?>"><span style="padding-right: 5px;"></span><?= $sale_item->item ?></a>
                                                                                            </td>
                                                                                            <td class="string "><?= $sale_item->quantity ?></td>
                                                                                            <td class="prettymoney ">$<?= number_format($sale_item->original_price, 2) ?></td>
                                                                                            <td class="prettymoney ">$<?= number_format($sale_item->original_price * $sale_item->quantity, 2) ?></td>
                                                                                            <td class="prettymoney ">$<?= number_format($sale_item->discount * $sale_item->quantity, 2) ?></td>
                                                                                            <td class="">
                                                                                                <?php $tax = $sale_item->rate ? $sale_item->total * $sale_item->rate / 100 : 0; ?>
                                                                                                <span><?= $sale_item->class.' ('.$sale_item->rate.'%) ~ $'.number_format($tax, 2) ?></span>
                                                                                            </td>
                                                                                            <td class="prettymoney ">$<?= number_format($sale_item->total + $tax, 2) ?></td>
                                                                                        </tr>
                                                                                    <?php endforeach; ?>
                                                                                <?php endif; ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table class="tab_columns">
                                        </table>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </article>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>