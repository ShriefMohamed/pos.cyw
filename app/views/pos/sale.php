<?php if (isset($sale) && $sale != false) : ?>
<div id="view" style="display: block;">
    <div class="view">

        <div class="functions">
            <button title="Save Changes" id="saveButton" class="save">Save Changes</button>

            <button id="printReceiptButton" title="Print Receipt">Print Receipt</button>
        </div>
        <div class="main">
            <div class="tabs tab-content">
                <ul id="tabsMenu" class="tab-labels nav nav-tabs">
                    <li class="details nav-item"><a id="menuDetails" class="nav-link active" href="#tabs-menu-details-link" data-toggle="tab">Details</a></li>
                    <li class="payments nav-item"><a id="menuPayments" class="nav-link" href="#tabs-menu-payments-link" data-toggle="tab">Payments</a></li>
                    <li class="lines nav-item"><a id="menuLines" class="nav-link" href="#tabs-menu-lines-link" data-toggle="tab">Lines</a></li>
                    <li class="email nav-item"><a id="menuEmail" class="nav-link" href="#tabs-menu-email-link" data-toggle="tab">Email</a></li>
                    <li class="void nav-item"><a id="menuVoidSale" class="nav-link" href="#tabs-menu-void-link" data-toggle="tab">Void Sale</a></li>
                </ul>

                <article id="tabs-menu-details-link" class="view_tab_details tab tab-pane active">
                    <div class="content">
                        <div class="view-columns">
                            <table class="view-layout set_auto_focus">
                                <tbody>
                                <tr>
                                    <td>
                                        <table class="view-column ">
                                            <tbody>
                                            <tr class="view_field_box">
                                                <td colspan="2" class="view_field_box " id="details_5">
                                                    <h3>Basic</h3>
                                                    <table>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <table class="tab_columns">
                                                                    <tbody>
                                                                    <tr id="view_f_5">
                                                                        <td class="label">
                                                                            <label for="view_transaction_id">UID</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <span id="view_transaction_id" style="width: auto; display: inline;" class="view_field_relation"><?= $sale->uid ?></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_6">
                                                                        <td class="label">
                                                                            <label for="view_transaction_id">ID</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <span id="view_transaction_id" style="width: auto; display: inline;" class="view_field_relation"><?= $sale->id ?></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_7">
                                                                        <td class="label">
                                                                            <label for="view_time_stamp">Date</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <span id="view_time_stamp" style="width: auto; display: inline;" class="view_field_relation"><?= \Framework\lib\Helper::ConvertDateFormat($sale->created, true) ?></span>
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
                                                <td colspan="2" class="view_field_box " id="details_9">
                                                    <h3>Status</h3>
                                                    <table>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <table class="tab_columns">
                                                                    <tbody>
                                                                    <tr id="view_f_9">
                                                                        <td class="label">
                                                                            <label for="view_completed">Complete</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <span id="view_completed" style="width: auto; display: inline;" class="view_field_relation"><?= $sale->sale_type == 'sale' ? 'Yes' : 'No' ?></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_10">
                                                                        <td class="label">
                                                                            <label for="view_completed">Quote</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <span id="view_completed" style="width: auto; display: inline;" class="view_field_relation"><?= $sale->sale_type == 'quote' ? 'Yes' : 'No' ?></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_11">
                                                                        <td class="label">
                                                                            <label for="view_cancelled">Cancelled</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <span id="view_cancelled" style="width: auto; display: inline;" class="view_field_relation"><?= $sale->sale_type == 'canceled' ? 'Yes' : 'No' ?></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_12">
                                                                        <td class="label">
                                                                            <label for="view_voided">Voided</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <span id="view_voided" style="width: auto; display: inline;" class="view_field_relation"><?= $sale->sale_type == 'voided' ? 'Yes' : 'No' ?></span>
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


                                            <?php if ($sale->customer_id) : ?>
                                            <tr class="view_field_box">
                                                <td colspan="2" class="view_field_box " id="details_18">
                                                    <h3>Customer</h3>
                                                    <table>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <table class="tab_columns">
                                                                    <tbody>
                                                                    <tr id="view_f_19">
                                                                        <td class="label">
                                                                            <label for="view_customer_id_function__getFullName">Customer</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <span id="view_customer_id_function__getFullName" style="width: auto; display: inline;" class="view_field_relation"><?= $sale->customer_name ?></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_20">
                                                                        <td class="label">
                                                                            <label for="view_customer_id_contact_id_phone_home">Phone</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <span id="view_customer_id_contact_id_phone_home" style="width: auto; display: inline;" class="view_field_relation"><?= $sale->customer_phone ?></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_22">
                                                                        <td class="label">
                                                                            <label for="view_customer_id_contact_id_mobile">Mobile</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <span id="view_customer_id_contact_id_mobile" style="width: auto; display: inline;" class="view_field_relation"><?= $sale->customer_mobile ?></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_23">
                                                                        <td class="label">
                                                                            <label for="view_customer_id_contact_id_email">Email</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <span id="view_customer_id_contact_id_email" style="width: auto; display: inline;" class="view_field_relation"><?= $sale->customer_email ?></span>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>

                                                            <td>
                                                                <table class="tab_columns">
                                                                    <tbody>
                                                                    <tr id="view_f_25">
                                                                        <td class="view_functions " colspan="2">
                                                                            <span class="function">
                                                                                <a href="<?= HOST_NAME.'customers/customer/'.$sale->customer_id ?>"><button style="padding: 7px 10px;" id="editCustomerButton" title="Edit" class="gui-def-button"><i class="fa fa-edit"></i> Edit Customer</button></a>
                                                                                <a href="<?= HOST_NAME.'ajax/sale_remove_customer/'.$sale->id.'?returnURL=pos/sale/'.$sale->id ?>"><button style="margin-top: 1rem;padding: 7px 10px;" id="removeCustomerButton" title="Remove" class="gui-def-button"><i class="fa fa-times "></i> Remove Customer</button></a>
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
                                            <?php else : ?>
                                            <tr class="view_field_box">
                                                <td colspan="2" class="view_field_box " id="details_18">
                                                    <h3>Find Existing Customer</h3>
                                                    <table>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <table class="tab_columns">
                                                                    <tbody>
                                                                    <tr id="view_f_19">
                                                                        <td class="label">
                                                                            <label for="view_add_customer_search">Search</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <input type="text" autocomplete="off" value="" size="14" maxlength="255" id="find_customer_text" name="add_customer_search" class="view_view email data_control" placeholder="Search" tabindex="201">
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                            <td>
                                                                <table class="tab_columns">
                                                                    <tbody>
                                                                    <tr id="view_f_21">
                                                                        <td class="view_functions " colspan="2">
                                                                          <span class="function">
                                                                            <button title="Find" id="searchCustomerButton" class="custom_function" data-customer-action="sale" data-sale-id="<?= $sale->id ?>">Find</button>
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
                                            <?php endif; ?>


                                            <tr class="view_field_box">
                                                <td colspan="2" class="view_field_box " id="details_27">
                                                    <h3>Location</h3>
                                                    <table>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <table class="tab_columns">
                                                                    <tbody>
                                                                    <tr id="view_f_28">
                                                                        <td class="label">
                                                                            <label for="view_employee_id">Employee</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <span id="view_employee_id" style="width: auto; display: inline;" class="view_field_relation"><?= $sale->admin_name ?></span>
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
                                                <td colspan="2" class="view_field_box " id="totalsContainer">
                                                    <h3>Totals</h3>
                                                    <table>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <table class="tab_columns">
                                                                    <tbody>
                                                                    <tr id="view_f_36">
                                                                        <td class="label">
                                                                            <label for="view_function__getTotal">Total</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <span id="view_function__getTotal" style="width: auto; display: inline;" class="view_field_relation">$<?= number_format($sale->total, 2) ?></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_37">
                                                                        <td class="label">
                                                                            <label for="view_function__getPayment">Payments</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                     <span id="view_function__getPayment" style="width: auto; display: inline;" class="view_field_relation">$<?= number_format($sale->total_paid, 2) ?></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_38">
                                                                        <td class="label">
                                                                            <label for="view_function__getBalance">Balance</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <span id="view_function__getBalance" style="width: auto; display: inline;" class="view_field_relation">$<?= number_format($sale->total - $sale->total_paid, 2) ?></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_39">
                                                                        <td>&nbsp;</td>
                                                                        <td>&nbsp;</td>
                                                                    </tr>
                                                                    <tr id="view_f_40">
                                                                        <td class="label">
                                                                            <label for="view_function__getSubtotalNoDiscounts">Subtotal</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <span id="view_function__getSubtotalNoDiscounts" style="width: auto; display: inline;" class="view_field_relation">$<?= number_format($sale->subtotal, 2) ?></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_41">
                                                                        <td class="label">
                                                                            <label for="view_function__getTotalDiscounts">Discount</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <span id="view_function__getTotalDiscounts" style="width: auto; display: inline;" class="view_field_relation">$-<?= number_format($sale->discount, 2) ?></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_42">
                                                                        <td>&nbsp;</td>
                                                                        <td>&nbsp;</td>
                                                                    </tr>
                                                                    <tr id="view_f_46">
                                                                        <td class="label">
                                                                            <label for="view_function__getTotalTax">Total Tax</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <span id="view_function__getTotalTax" style="width: auto; display: inline;" class="view_field_relation">$<?= number_format($sale->tax, 2) ?></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_47">
                                                                        <td>&nbsp;</td>
                                                                        <td>&nbsp;</td>
                                                                    </tr>
                                                                    <tr id="view_f_48">
                                                                        <td class="label">
                                                                            <label for="view_function__getTotalAvgCost">Cost</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <span id="view_function__getTotalAvgCost" style="width: auto; display: inline;" class="view_field_relation">$<?= number_format($sale->cost, 2) ?></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_49">
                                                                        <td class="label">
                                                                            <label for="view_function__getProfit">Profit</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <span id="view_function__getProfit" style="width: auto; display: inline;" class="view_field_relation">$<?= $sale->total > 0 ? number_format($sale->total - $sale->cost, 2) : 0.00 ?></span>
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
                                                <td colspan="2" class="view_field_box " id="details_50">
                                                    <h3>Notes</h3>
                                                    <table>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <table class="tab_columns">
                                                                    <tbody>
                                                                    <tr id="view_f_51">
                                                                        <td colspan="2">
                                                                            <table>
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td class="textarea_label">
                                                                                        <label for="view_printed_note">Receipt Note</label>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="form_field_holder">
                                                                                        <textarea rows="3" cols="25" id="view_printed_note" name="printed_note" class="view_view textarea data_control" tabindex="207" placeholder="Customers will be able to see this note on their receipt"><?= $sale->printed_note ?></textarea>
                                                                                    </td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_52">
                                                                        <td colspan="2">
                                                                            <table>
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td class="textarea_label">
                                                                                        <label for="internal_note">Internal Note</label>
                                                                                        <a href="javascript:" style="float: right" class="add-time-button">Add time <i class="fa fa-clock"></i></a>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="form_field_holder">
                                                                                        <textarea rows="3" cols="25" id="internal_note" name="internal_note" class="view_view textarea data_control" tabindex="208" placeholder="Customers won't be able to see this note"><?= $sale->internal_note ?></textarea>
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
                                </tbody>
                            </table>
                        </div>
                    </div>
                </article>

                <article id="tabs-menu-payments-link" class="view_tab_payments tab tab-pane">
                    <div class="content">
                        <div class="view-columns">
                            <table class="view-layout set_auto_focus">
                                <tbody>
                                <tr>
                                    <td>
                                        <table class="view-column ">
                                            <tbody>
                                            <tr id="view_f_5">
                                                <td class="view_listing" colspan="2">
                                                    <h2 class="child-listing-title" id="admin_utilities_payments_view_title">Payments</h2>
                                                    <div id="admin_utilities_payments_view" class="is_pannel">
                                                        <div class="listing">
                                                            <div id="work_admin_utilities_payments_view">
                                                                <div id="admin_utilities_payments_view_single">
                                                                    <div class="container" style="max-width: 100%">
                                                                        <table cellpadding="0" cellspacing="0" border="0" class="g-datatable datatable table table-striped table-bordered">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>Date</th>
                                                                                <th>Type</th>
                                                                                <th>Amount</th>
                                                                                <th>Refund</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <?php if (isset($sale_payments) && $sale_payments !== false) : ?>
                                                                            <?php foreach ($sale_payments as $sale_payment) : ?>
                                                                            <tr>
                                                                                <td><time datetime="<?= $sale_payment->created ?>"><?= \Framework\lib\Helper::ConvertDateFormat($sale_payment->created, true) ?></time></td>
                                                                                <td><?= $sale_payment->method_name ?: ucfirst($sale_payment->payment_method) ?></td>
                                                                                <td class="money ">$<?= number_format($sale_payment->amount, 2) ?></td>
                                                                                <td class="boolean "><?= $sale_payment->amount < 0 ? 'Yes' : 'No' ?></td>
                                                                            </tr>
                                                                            <?php endforeach; ?>
                                                                            <?php endif; ?>
                                                                            </tbody>
                                                                        </table>
                                                                        <div class="info">
                                                                            <div class="form info_pannel">
                                                                                <table cellspacing="2">
                                                                                    <tbody>
                                                                                    <tr valign="top">
                                                                                        <td class="info_pannel_column">
                                                                                            <table>
                                                                                                <tbody>
                                                                                                <tr valign="top" class=" odd">
                                                                                                    <td class="text label" colspan="2">Totals</td>
                                                                                                </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </td>
                                                                                        <td class="info_pannel_column">
                                                                                            <table>
                                                                                                <tbody>
                                                                                                <?php if (isset($sale_payments_totals) && !empty($sale_payments_totals)) : ?>
                                                                                                <?php foreach ($sale_payments_totals as $sale_payments_total) : ?>
                                                                                                <tr valign="top">
                                                                                                    <td class="label"><?= ucfirst($sale_payments_total['method']) ?></td>
                                                                                                    <td class="info_field money">$<?= number_format($sale_payments_total['total'], 2) ?></td>
                                                                                                </tr>
                                                                                                <?php endforeach; ?>
                                                                                                <?php endif; ?>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </article>

                <article id="tabs-menu-lines-link" class="view_tab_lines tab tab-pane">
                    <div class="content">
                        <div class="view-columns">
                            <table class="view-layout set_auto_focus">
                                <tbody>
                                <tr>
                                    <td>
                                        <table class="view-column ">
                                            <tbody>
                                            <tr id="view_f_5">
                                                <td class="view_listing" colspan="2">
                                                    <h2 class="child-listing-title" id="transaction_listings_transaction_lines_view_title">Sale Lines</h2>
                                                    <div id="transaction_listings_transaction_lines_view" class="is_pannel">
                                                        <div class="listing">
                                                            <div id="work_transaction_listings_transaction_lines_view">
                                                                <div id="transaction_listings_transaction_lines_view_single">
                                                                    <div class="container" style="max-width: 100%">
                                                                        <table cellpadding="0" cellspacing="0" border="0" class="g-datatable datatable table table-striped table-bordered">
                                                                            <thead>
                                                                            <tr>
                                                                                <th class="th-string">Item</a></th>
                                                                                <th class="th-flat_select">Tax Class</a></th>
                                                                                <th class="th-number">Qty.</a></th>
                                                                                <th class="th-money">Price</a></th>
                                                                                <th class="th-money">Total Discounts</a></th>
                                                                                <th class="th-money">Total</a></th>
                                                                                <th class="th-money">Cost</a></th>
                                                                                <th class="th-money">Profit</a></th>
                                                                                <th class="th-percent">Margin</a></th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <?php if (isset($sale_items) && $sale_items !== false) : ?>
                                                                            <?php foreach ($sale_items as $sale_item) : ?>
                                                                            <tr id="transaction_listings_transaction_lines_view_r_0">
                                                                                <td class="string ">
                                                                                    <a title="Edit Record" href="<?= HOST_NAME.'pos/item/'.$sale_item->item_id ?>"><span><?= $sale_item->item ?></span></a>
                                                                                </td>
                                                                                <td><?= $sale_item->class && $sale_item->rate ? $sale_item->class.' ('.$sale_item->rate.'%)' : 'None' ?></td>
                                                                                <td class="number "><?= $sale_item->quantity ?></td>
                                                                                <td class="money ">$<?= number_format($sale_item->original_price, 2) ?></td>
                                                                                <td class="money ">$<?= number_format($sale_item->discount, 2) ?></td>
                                                                                <td class="money ">$<?= number_format($sale_item->total, 2) ?></td>

                                                                                <td class="money ">$<?= number_format($sale_item->buy_price * str_replace('-', '', $sale_item->quantity), 2) ?></td>
                                                                                <td class="money ">$<?= $sale_item->total > 0 ? number_format($sale_item->total - ($sale_item->buy_price * $sale_item->quantity), 2) : 0 ?></td>
                                                                                <td class="percent "><?= $sale_item->total > 0 ? substr((($sale_item->total - ($sale_item->buy_price * $sale_item->quantity)) / ($sale_item->buy_price * $sale_item->quantity)) * 100, 0, 5).'%' : 0 ?></td>
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
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </article>

                <article id="tabs-menu-email-link" class="view_tab_email tab tab-pane">
                    <?php $this->RenderPart('_sale_receipt_email') ?>
                </article>

                <article id="tabs-menu-void-link" class="view_tab_void tab tab-pane">
                    <div class="content">
                        <div class="view-columns">
                            <table class="view-layout set_auto_focus">
                                <tbody>
                                <tr>
                                    <td>
                                        <table class="view-column ">
                                            <tbody>
                                            <tr class="view_field_box">
                                                <td colspan="2" class="view_field_box " id="void_5">
                                                    <h3>Void Sale</h3>
                                                    <table>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <table class="tab_columns">
                                                                    <tbody>
                                                                    <tr id="view_f_7">
                                                                        <td class="form_field_holder " colspan="2">
                                                                            <div>
                                                                                <div style="width: 100%; padding:10px;">
                                                                                    Voiding a sale will:
                                                                                    <ul>
                                                                                        <li>Delete the sale and its payments from Reports and Customer Records</li>
                                                                                        <li>Add the Sale to the Voids Report</li>
                                                                                        <li>Return the Sale Items back to Inventory</li>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_8">
                                                                        <td class="form_field_holder " colspan="2">
                                                                            <div>
                                                                                <div style="width: 300px;" class="alert warning">Voiding a sale is permanent and can not be undone. Please use caution when using void!</div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_10">
                                                                        <td class="view_functions " colspan="2">
                                                                          <span class="function">
                                                                            <button title="Void Sale" id="voidSaleButton" class="custom_function btn" data-target="#void-sale-modal" data-toggle="modal"><i class="fa fa-exclamation-triangle "></i> Void Sale</button>
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
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </div>
</div>

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

<div class="modal fade" id="void-sale-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger-pos">
                <h4 class="modal-title">Void Sale #<?= $sale->id ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body clearfix" style="padding: 20px;">
                <div class="modal-container">
                    <p><strong>Warning!</strong> This action is permanent and can't be undone.</p>
                    <p>Voiding this sale:</p>
                    <ul id="void-sales-warning-list">
                        <li>Removes all records of this transaction from the sales history</li>
                        <li>Restores all relevant inventory levels</li>
                        <li>Removes all payments attached to this transaction</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <a href="<?= HOST_NAME.'ajax/sale_void/'.$sale->id ?>"><button type="submit" name="submit" class="btn btn-danger-pos">Void Sale</button></a>
                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="receipt-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <iframe id="receipt_preview_iframe" name="receipt_preview_iframe" src="" height="400px" style="border: 1px solid rgb(136, 136, 136);padding: 10px"></iframe>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#printReceiptButton').on('click', function (e) {
            $("#receipt-modal").modal();
            document.getElementById("receipt_preview_iframe").src = "<?= HOST_NAME.'pos/sale_receipt/'.$sale->id ?>";
            window.frames["receipt_preview_iframe"].focus();
            setTimeout(function () {
                window.frames["receipt_preview_iframe"].print()
            }, 1000);
        });
    });
</script>
<?php endif; ?>