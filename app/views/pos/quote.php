<?php if (isset($quote) && $quote != false) : ?>
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
                        <li class="lines nav-item"><a id="menuLines" class="nav-link" href="#tabs-menu-lines-link" data-toggle="tab">Lines</a></li>
                        <?php if (isset($quote_po) && $quote_po !== false) : ?>
                            <li class="purchase-order nav-item"><a id="menuPO" class="nav-link" href="#tabs-menu-po-link" data-toggle="tab">Purchase Order</a></li>
                        <?php else : ?>
                            <li class="nav-item"><a class="nav-link" href="<?= HOST_NAME.'pos/quote_order_parts/'.$quote->id ?>" target="_blank">Purchase Order</a></li>
                        <?php endif; ?>
                        <li class="void nav-item"><a id="menuVoidSale" class="nav-link" href="#tabs-menu-void-link" data-toggle="tab">Void Quote</a></li>
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
                                                                                <span id="view_transaction_id" style="width: auto; display: inline;" class="view_field_relation"><?= $quote->uid ?></span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_6">
                                                                            <td class="label">
                                                                                <label for="view_transaction_id">ID</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <span id="view_transaction_id" style="width: auto; display: inline;" class="view_field_relation"><?= $quote->id ?></span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_7">
                                                                            <td class="label">
                                                                                <label for="view_time_stamp">Date</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <span id="view_time_stamp" style="width: auto; display: inline;" class="view_field_relation"><?= \Framework\lib\Helper::ConvertDateFormat($quote->created, true) ?></span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_7">
                                                                            <td class="label">
                                                                                <label for="view_time_stamp">Updated</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <span id="view_time_stamp" style="width: auto; display: inline;" class="view_field_relation"><?= \Framework\lib\Helper::ConvertDateFormat($quote->updated, true) ?></span>
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
                                                                        <tr id="view_f_10">
                                                                            <td class="label">
                                                                                <label for="view_completed">Approved</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <span id="view_completed" style="width: auto; display: inline;" class="view_field_relation"><?= $quote->status == 'approved' ? 'Yes' : 'No' ?></span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_10">
                                                                            <td class="label">
                                                                                <label for="view_completed">Purchase Order</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <span id="view_completed" style="width: auto; display: inline;" class="view_field_relation">
                                                                                    <?= (isset($quote_po) && $quote_po !== false) ? ucfirst($quote_po->purchase_order_status) : 'Not Generated' ?>
                                                                                </span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_10">
                                                                            <td class="label">
                                                                                <label for="view_completed">Parts Ordered</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <span id="view_completed" style="width: auto; display: inline;" class="view_field_relation"><?= (isset($quote_po) && $quote_po !== false && $quote_po->purchase_order_status == 'sent') ? 'Yes' : 'No' ?></span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_10">
                                                                            <td class="label">
                                                                                <label for="view_completed">Job Created for Technician</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <span id="view_completed" style="width: auto; display: inline;" class="view_field_relation"><?= $quote->status == 'job' ? 'Yes' : 'No' ?></span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_9">
                                                                            <td class="label">
                                                                                <label for="view_completed">Completed</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <span id="view_completed" style="width: auto; display: inline;" class="view_field_relation"><?= $quote->status == 'complete' ? 'Yes' : 'No' ?></span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_12">
                                                                            <td class="label">
                                                                                <label for="view_voided">Voided</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <span id="view_voided" style="width: auto; display: inline;" class="view_field_relation"><?= $quote->status == 'voided' ? 'Yes' : 'No' ?></span>
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


                                                <?php if ($quote->customer_id && $quote->customer_name) : ?>
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
                                                                                    <span id="view_customer_id_function__getFullName" style="width: auto; display: inline;" class="view_field_relation"><?= $quote->customer_name ?></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr id="view_f_20">
                                                                                <td class="label">
                                                                                    <label for="view_customer_id_contact_id_phone_home">Phone</label>
                                                                                </td>
                                                                                <td class="form_field_holder ">
                                                                                    <span id="view_customer_id_contact_id_phone_home" style="width: auto; display: inline;" class="view_field_relation"><?= $quote->customer_phone ?></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr id="view_f_22">
                                                                                <td class="label">
                                                                                    <label for="view_customer_id_contact_id_mobile">Mobile</label>
                                                                                </td>
                                                                                <td class="form_field_holder ">
                                                                                    <span id="view_customer_id_contact_id_mobile" style="width: auto; display: inline;" class="view_field_relation"><?= $quote->customer_mobile ?></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr id="view_f_23">
                                                                                <td class="label">
                                                                                    <label for="view_customer_id_contact_id_email">Email</label>
                                                                                </td>
                                                                                <td class="form_field_holder ">
                                                                                    <span id="view_customer_id_contact_id_email" style="width: auto; display: inline;" class="view_field_relation"><?= $quote->customer_email ?></span>
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
                                                                                        <a target="_blank" href="<?= HOST_NAME.'customers/customer/'.$quote->customer_id ?>"><button style="padding: 7px 10px;" id="editCustomerButton" title="Edit" class="gui-def-button"><i class="fa fa-edit"></i> Edit Customer</button></a>
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
                                                                                <span id="view_employee_id" style="width: auto; display: inline;" class="view_field_relation"><?= $quote->admin_name ?></span>
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
                                                                                <span id="view_function__getTotal" style="width: auto; display: inline;" class="view_field_relation">$<?= number_format($quote->total, 2) ?></span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_37">
                                                                            <td class="label">
                                                                                <label for="view_function__getPayment">DBP System</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <span id="view_function__getPayment" style="width: auto; display: inline;" class="view_field_relation">$<?= number_format($quote->DBP, 2) ?></span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_38">
                                                                            <td class="label">
                                                                                <label for="view_function__getBalance">Mark up %</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <span id="view_function__getBalance" style="width: auto; display: inline;" class="view_field_relation">$<?= number_format($quote->margin, 2) ?></span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_38">
                                                                            <td class="label">
                                                                                <label for="view_function__getBalance">System Total</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <span id="view_function__getBalance" style="width: auto; display: inline;" class="view_field_relation">$<?= number_format($quote->system_total, 2) ?></span>
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
                                                                                <span id="view_function__getSubtotalNoDiscounts" style="width: auto; display: inline;" class="view_field_relation">$<?= number_format($quote->subtotal, 2) ?></span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_41">
                                                                            <td class="label">
                                                                                <label for="view_function__getTotalDiscounts">Labor</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <span id="view_function__getTotalDiscounts" style="width: auto; display: inline;" class="view_field_relation">$-<?= number_format($quote->labor, 2) ?></span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_46">
                                                                            <td class="label">
                                                                                <label for="view_function__getTotalTax">Total Tax</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <span id="view_function__getTotalTax" style="width: auto; display: inline;" class="view_field_relation">$<?= number_format($quote->GST, 2) ?></span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_47">
                                                                            <td>&nbsp;</td>
                                                                            <td>&nbsp;</td>
                                                                        </tr>
                                                                        <tr id="view_f_48">
                                                                            <td class="label">
                                                                                <label for="view_function__getTotalAvgCost">Total Cost</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <span id="view_function__getTotalAvgCost" style="width: auto; display: inline;" class="view_field_relation">$<?= number_format($quote->cost, 2) ?></span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_49">
                                                                            <td class="label">
                                                                                <label for="view_function__getProfit">Total Profit</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <span id="view_function__getProfit" style="width: auto; display: inline;" class="view_field_relation">$<?= $quote->total > 0 ? number_format($quote->total - $quote->cost, 2) : 0.00 ?></span>
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
                                                                                            <textarea rows="3" cols="25" id="view_printed_note" name="printed_note" class="view_view textarea data_control" tabindex="207" placeholder="Customers will be able to see this note on their receipt"><?= $quote->printed_note ?></textarea>
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
                                                                                            <textarea rows="3" cols="25" id="internal_note" name="internal_note" class="view_view textarea data_control" tabindex="208" placeholder="Customers won't be able to see this note"><?= $quote->internal_note ?></textarea>
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
                                                                                    <th class="th-string">Component</a></th>
                                                                                    <th class="th-number">System</a></th>
                                                                                    <th class="th-number">Qty.</a></th>
                                                                                    <th class="th-money">DBP</a></th>
                                                                                    <th class="th-money">RRP</a></th>
                                                                                    <th class="th-money">Total</a></th>
                                                                                    <th class="th-money">Profit</a></th>
                                                                                    <th class="th-percent">Margin</a></th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                <?php if (isset($quote_items) && $quote_items !== false) : ?>
                                                                                    <?php foreach ($quote_items as $quote_item) : ?>
                                                                                        <tr id="transaction_listings_transaction_lines_view_r_0">
                                                                                            <td class="string "><span><?= $quote_item->item_name ?></span></td>
                                                                                            <td class="string"><span><?= $quote_item->component ?></span></td>
                                                                                            <td class="string"><span><?= $quote_item->merged ? 'Yes' : 'No' ?></span></td>
                                                                                            <td class="number "><?= $quote_item->quantity ?></td>
                                                                                            <td class="money ">$<?= number_format($quote_item->original_price, 2) ?></td>
                                                                                            <td class="money ">$<?= number_format($quote_item->price, 2) ?></td>
                                                                                            <td class="money ">$<?= number_format($quote_item->price * $quote_item->quantity, 2) ?></td>
                                                                                            <td class="money ">$<?= $quote_item->price > 0 ? number_format(($quote_item->price * $quote_item->quantity) - ($quote_item->original_price * $quote_item->quantity), 2) : 0 ?></td>
                                                                                            <td class="percent "><?= $quote_item->price > 0 ? substr(((($quote_item->price * $quote_item->quantity) - ($quote_item->original_price * $quote_item->quantity)) / ($quote_item->price * $quote_item->quantity)) * 100, 0, 5).'%' : 0 ?></td>
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

                    <!-- po exists & !sent: email temp & btn(edit po)-->
                    <!-- po exists & sent: order_parts temp-->
                    <!-- po doesn't exist: don't show tab-->
                    <?php if (isset($quote_po) && $quote_po !== false && $quote_po->purchase_order) : ?>
                    <article id="tabs-menu-po-link" class="view_tab_purchase-order tab tab-pane">
                        <div class="content">
                            <div class="view-columns">
                                <?php if ($quote_po->purchase_order_status == 'generated') : ?>
                                    <div class="card-header" style="margin-bottom: 1rem;padding-top: 10px;margin-top: -10px;">
                                        Purchase Order Status: <strong class="badge badge-primary" style="font-size: 14px;">Generated & Saved</strong>

                                        <a href="<?= HOST_NAME.'pos/quote_order_parts/'.$quote->id ?>" class="btn btn-warning" style="float: right;margin-top: -6px">Edit Purchase Order</a>
                                    </div>
                                    <form method="post" action="<?= HOST_NAME.'pos/quote_purchase_order_email_action/'.$quote->id ?>">
                                    <table class="view-layout set_auto_focus">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <table class="view-column ">
                                                    <tbody>
                                                    <tr class="view_field_box">
                                                        <td colspan="2" class="view_field_box " id="email_5">
                                                            <h3>Send Purchase Order Email</h3>
                                                            <table>
                                                                <tbody><tr>
                                                                    <td>
                                                                        <table class="tab_columns">
                                                                            <tbody><tr id="view_f_6">
                                                                                <td class="label">
                                                                                    <label for="view_email_to_email">To Email Address</label>
                                                                                </td>
                                                                                <td class="form_field_holder ">
                                                                                    <input type="text" autocomplete="off" value="" size="40" maxlength="255" id="view_email_to_email" name="to_email" class="view_view email priority_auto_focus data_control" placeholder="e.g. LeaderSystems Account Manager's Email Address" tabindex="200" required>
                                                                                </td>
                                                                            </tr>
                                                                            <tr id="view_f_7">
                                                                                <td class="label">
                                                                                    <label for="view_email_to_name">To Name</label>
                                                                                </td>
                                                                                <td class="form_field_holder ">
                                                                                    <input type="text" autocomplete="off" value="" size="40" maxlength="255" id="view_email_to_name" name="to_name" class="view_view string data_control" placeholder="e.g. LeaderSystem's Personal Account Manager's name " tabindex="201">
                                                                                </td>
                                                                            </tr>
                                                                            <tr id="view_f_8">
                                                                                <td class="label">
                                                                                    <label for="view_email_to_subject">Subject</label>
                                                                                </td>
                                                                                <td class="form_field_holder ">
                                                                                    <input type="text" autocomplete="off" value="Purchase Order #PO-<?= $quote->id ?> From Compute Your World" size="40" maxlength="255" id="view_email_to_subject" name="to_subject" class="view_view string data_control" placeholder="Subject" tabindex="202">
                                                                                </td>
                                                                            </tr>
                                                                            <tr id="view_f_8">
                                                                                <td class="label">
                                                                                    <label for="view_email_to_attachment">Attachment (Purchase Order PDF)</label>
                                                                                </td>
                                                                                <td class="form_field_holder " style="border-left: 1px solid #ddd;box-shadow: inset 1px 1px 1px #eee;padding: 0 6px;background: #FFF;">
                                                                                    <a href="<?= QUOTES_PO_DIR.$quote_po->purchase_order.'.pdf' ?>" target="_blank" id="view_email_to_attachment" class="view_view string data_control"><?= $quote_po->purchase_order ?></a>
                                                                                </td>
                                                                            </tr>
                                                                            <tr id="view_f_9">
                                                                                <td colspan="2">
                                                                                    <table>
                                                                                        <tbody><tr>
                                                                                            <td class="textarea_label">
                                                                                                <label for="view_email_to_header">Message</label>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td class="form_field_holder">
                                                                                                <textarea rows="5" cols="50" id="view_email_to_header" name="message" class="view_view textarea data_control" tabindex="203"></textarea>
                                                                                            </td>
                                                                                        </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                            <tr id="view_f_11">
                                                                                <td class="view_functions " colspan="2">
                                                                                    <span class="function">
                                                                                        <button title="Email Purchase Order" id="send_email_receipt" class="custom_function" type="submit" name="submit"><i class="fa fa-envelope "></i> Email Purchase Order</button>
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
                                </form>
                                <?php else: ?>
                                    <div class="card-header" style="margin-bottom: 1rem;padding-top: 10px;margin-top: -10px;">
                                        Purchase Order Status: <strong class="badge badge-primary" style="font-size: 14px;">Generated & Saved & Sent</strong>
                                        <a href="<?= HOST_NAME.'pos/quote_regenerate_purchase_order/'.$quote_po->id.'/'.$quote->id ?>" class="btn btn-danger-pos regenerate-purchase-order-btn" style="float: right;margin-top: -6px">Re-generate Purchase Order</a>
                                    </div>

                                    <table cellpadding="0" cellspacing="0" border="0" class="datatable table table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th width="20%">ITEM</th>
                                            <th width="10%">SKU</th>
                                            <th>QTY.</th>
                                            <th>DBP</th>
                                            <th>PRICE</th>
                                            <th>Availability</th>
                                            <th>Added to PO</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if (isset($quote_items) && $quote_items !== false) : ?>
                                            <?php foreach ($quote_items as $item) : ?>
                                                <tr class="gradeX">
                                                    <td>
                                                        <?php if ($item->pos_item_id) : ?>
                                                            <a target="_blank" title="Edit Item" href="<?= HOST_NAME . 'pos/item/' . $item->pos_item_id ?>"><?= $item->item_name ?></a>
                                                        <?php else : ?>
                                                            <span><?= $item->item_name ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= $item->item_sku ?></td>
                                                    <td><?= $item->quantity ?></td>
                                                    <td>$<?= number_format($item->original_price, 2) ?></td>
                                                    <td>$<?= number_format($item->price, 2) ?></td>
                                                    <td><?= $item->pos_item_id ? "Stock" : "LeaderSystems" ?></td>
                                                    <td><?= in_array($item->id, json_decode($quote_po->purchase_order_items)) ? "Yes" : "No" ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                        </tbody>
                                    </table>

                                    <div class="purchase-order-container">
                                        <iframe id="po_preview_iframe" name="po_preview_iframe" width="100%" height="400px" style="max-width: 100%; border: 1px solid rgb(136, 136, 136); padding: 10px;margin: 20px auto;display: block;"></iframe>

                                        <script>
                                            $(document).ready(function () {
                                                reloadIFrame("<?= QUOTES_PO_DIR.$quote_po->purchase_order.'.pdf' ?>");
                                            });
                                        </script>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                    <?php endif; ?>

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
                                                        <h3>Void Quote</h3>
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
                                                                                        Voiding a quote will:
                                                                                        <ul>
                                                                                            <li>Delete the quote and its payments from Reports and Customer Records</li>
                                                                                            <li>Add the Sale to the Voids Report</li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_8">
                                                                            <td class="form_field_holder " colspan="2">
                                                                                <div>
                                                                                    <div style="width: 300px;" class="alert warning">Voiding a quote is permanent and can not be undone. Please use caution when using void!</div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_10">
                                                                            <td class="view_functions " colspan="2">
                                                                          <span class="function">
                                                                            <button title="Void Sale" id="voidSaleButton" class="custom_function btn" data-target="#void-sale-modal" data-toggle="modal"><i class="fa fa-exclamation-triangle "></i> Void Quote</button>
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
                    <h4 class="modal-title">Void Quote #<?= $quote->uid ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body clearfix" style="padding: 20px;">
                    <div class="modal-container">
                        <p><strong>Warning!</strong> This action is permanent and can't be undone.</p>
                        <p>Voiding this quote:</p>
                        <ul id="void-sales-warning-list">
                            <li>Removes all records of this transaction from the quote history</li>
                            <li>Restores all relevant inventory levels</li>
                            <li>Removes all payments attached to this transaction</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="<?= HOST_NAME.'ajax/quote_void/'.$quote->id ?>"><button type="submit" name="submit" class="btn btn-danger-pos">Void Quote</button></a>
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
                document.getElementById("receipt_preview_iframe").src = "<?= HOST_NAME.'pos/quote_receipt/'.$quote->id ?>";
                window.frames["receipt_preview_iframe"].focus();
                setTimeout(function () {
                    window.frames["receipt_preview_iframe"].print()
                }, 1000);
            });
        });
    </script>
<?php endif; ?>