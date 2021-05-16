<?php if (isset($order) && $order !== false) : ?>
<div id="view" class="is_pannel" style="display: block;">
    <div class="view">
        <form method="post">
            <div class="functions">
                <?php if ($order->status !== 'finished') : ?>
                <button title="Save Changes" id="saveButton" class="save" name="save" type="submit">Save Changes</button>
                <?php endif; ?>

                <?php if ($order->status !== 'checkin' && $order->status !== 'finished') : ?>
                <button type="button" onclick="window.location.href='<?= HOST_NAME.'pos/purchase_order_check_in/'.$order->id ?>'" title="Check In" class="nextaction custom_function">Check In</button>
                <?php endif; ?>

                <?php if (isset($order_items_completed) && $order_items_completed !== false && $order->status !== 'finished') : ?>
                <button type="button" onclick="window.location.href='<?= HOST_NAME.'pos/purchase_order_finish/'.$order->id ?>'" title="Finished" class="nextaction custom_function">Finished</button>
                <?php endif; ?>

                <button title="Print Order" type="button">Print Order</button>

                <?php if ($order->status !== 'archived') : ?>
                <button type="button" onclick="window.location.href='<?= HOST_NAME.'ajax/archive/'.$order->id.'?target=\purchase_orders&returnURL=pos/purchase_orders' ?>'" title="Archive" class="archive supplementary">Archive</button>
                <?php endif; ?>
            </div>
            <div class="main">
                <article class="view_tab_main tab loaded">
                    <div class="content">
                        <div class="view-columns">
                            <table class="view-layout set_auto_focus">
                                <tbody>
                                <tr>
                                    <td>
                                        <table class="view-column ">
                                            <tbody>
                                            <tr class="view_field_box">
                                                <td colspan="2" class="view_field_box " id="main_12">
                                                    <h3>Status</h3>
                                                    <table>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <table class="tab_columns">
                                                                    <tbody>
                                                                    <tr id="view_f_13">
                                                                        <td class="form_field_holder ">
                                                                            <span><?= ucfirst($order->status) ?></span>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                            <td>
                                                                <table class="tab_columns">
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>

                                            <tr class="view_field_box">
                                                <td colspan="2" class="view_field_box " id="main_16">
                                                    <h3>Details</h3>
                                                    <table>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <table class="tab_columns">
                                                                    <tbody>
                                                                    <tr id="view_f_17">
                                                                        <td class="label">
                                                                            <label for="view_vendor_id">Vendor</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <select name="vendor" id="view_vendor_id" class="view_view data_control" tabindex="201">
                                                                                <option value="0">None</option>
                                                                                <?php if (isset($vendors) && $vendors !== false) : ?>
                                                                                    <?php foreach ($vendors as $vendor) : ?>
                                                                                        <option <?= $order->vendor_id == $vendor->id ? 'selected' : '' ?> value="<?= $vendor->id ?>"><?= $vendor->name ?></option>
                                                                                    <?php endforeach; ?>
                                                                                <?php endif; ?>
                                                                            </select>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_18">
                                                                        <td class="label">
                                                                            <label for="view_created_by">Created By</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <span class="view_view string data_control view_field_relation"><?= $order->admin_name ?></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_19">
                                                                        <td class="label">
                                                                            <label for="view_ref_num">Reference #</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <input type="text" autocomplete="off" size="14" maxlength="255" id="view_ref_num" name="ref_num" class="view_view string data_control" tabindex="202" value="<?= $order->reference ?>">
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_20">
                                                                        <td class="label">
                                                                            <label for="view_ordered">Ordered Date</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <div class="date-wrapper"><input type="text" id="view_ordered" name="ordered" size="10" autocomplete="off" maxlength="10" class="view_view date savedx data_control" placeholder="Order Date" value="<?= $order->ordered ?>" tabindex="203"></div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_21">
                                                                        <td class="label">
                                                                            <label for="view_arrival_date">Expected</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <div class="date-wrapper"><input type="text" id="view_arrival_date" name="arrival_date" size="10" autocomplete="off" maxlength="10" class="view_view date savedx data_control" placeholder="Expected Date" value="<?= $order->expected ?>" tabindex="204"></div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_22">
                                                                        <td class="label">
                                                                            <label for="view_ship_instructions">Shipping Note</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <input type="text" autocomplete="off" size="30" maxlength="255" id="view_ship_instructions" name="ship_instructions" class="view_view string data_control" placeholder="Shipping Note" tabindex="205" value="<?= $order->shipping_notes ?>">
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
                                                <td colspan="2" class="view_field_box " id="main_24">
                                                    <h3> General Notes</h3>
                                                    <table>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <table class="tab_columns">
                                                                    <tbody>
                                                                    <tr id="view_f_25">
                                                                        <td colspan="2">
                                                                            <table>
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td class="form_field_holder">
                                                                                        <textarea rows="5" cols="40" id="view_note_id_note_text" name="general_notes" class="view_view textarea data_control" tabindex="206"><?= $order->general_notes ?></textarea>
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
                                    <td>
                                        <table class="view-column ">
                                            <tbody>
                                            <tr class="view_field_box">
                                                <td colspan="2" class="view_field_box " id="main_27">
                                                    <h3>Check In Summary</h3>
                                                    <table>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <table class="tab_columns">
                                                                    <tbody>
                                                                    <tr id="view_f_28">
                                                                        <td class="form_field_holder " colspan="2">
                                                                            <div></div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_29">
                                                                        <td class="form_field_holder " colspan="2">
                                                                            <div class="static-text-field">Received</div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_30">
                                                                        <td class="form_field_holder " colspan="2">
                                                                            <div class="static-text-field">Ordered</div>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                            <td>
                                                                <table class="tab_columns">
                                                                    <tbody>
                                                                    <tr id="view_f_32">
                                                                        <td class="form_field_holder " colspan="2">
                                                                            <div class="static-text-field right">Quantity</div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_33">
                                                                        <td class="form_field_holder " colspan="2">
                                                                            <div class="static-text-field right"><?= $order->total_received ?></div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_34">
                                                                        <td class="form_field_holder " colspan="2">
                                                                            <div class="static-text-field right"><?= $order->total_order ?></div>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                            <td>
                                                                <table class="tab_columns">
                                                                    <tbody>
                                                                    <tr id="view_f_36">
                                                                        <td class="form_field_holder " colspan="2">
                                                                            <div class="static-text-field right">Total</div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_37">
                                                                        <td class="form_field_holder ">
                                                                            <div class="static-text-field right">$<?= number_format($order->total_received_cost, 2) ?></div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_38">
                                                                        <td class="form_field_holder ">
                                                                            <div class="static-text-field right">$<?= number_format($order->total_order_cost, 2) ?></div>
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
                                                <td colspan="2" class="view_field_box " id="main_40">
                                                    <h3>Costs</h3>
                                                    <table>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <table class="tab_columns">
                                                                    <tbody>
                                                                    <tr id="view_f_41">
                                                                        <td class="form_field_holder " colspan="2">
                                                                            <div class="static-text-field">Subtotal</div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_42">
                                                                        <td class="form_field_holder " colspan="2">
                                                                            <div class="static-text-field">Shipping</div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_43">
                                                                        <td class="form_field_holder " colspan="2">
                                                                            <div class="static-text-field">Other (+/-)</div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_44">
                                                                        <td class="form_field_holder " colspan="2">
                                                                            <div class="static-text-field">Discount</div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_45">
                                                                        <td class="form_field_holder " colspan="2">
                                                                            <div class="static-text-field">Order Total</div>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                            <td>
                                                                <table class="tab_columns">
                                                                    <tbody>
                                                                    <tr id="view_f_47">
                                                                        <td class="form_field_holder " colspan="2">
                                                                            <div></div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_48">
                                                                        <td class="form_field_holder " colspan="2">
                                                                            <div></div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_49">
                                                                        <td class="form_field_holder " colspan="2">
                                                                            <div></div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_50">
                                                                        <td class="form_field_holder ">
                                                                            <div class="percent-field-container right">
                                                                                <input type="text" autocomplete="off" value="<?= $order->discount ?>" size="6" maxlength="15" id="view_discount" name="discount" class="view_view percent data_control" tabindex="207">
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_51">
                                                                        <td class="form_field_holder " colspan="2">
                                                                            <div></div>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                            <td>
                                                                <table class="tab_columns">
                                                                    <tbody>
                                                                    <tr id="view_f_53">
                                                                        <td class="form_field_holder ">
                                                                            <div class="static-text-field right" id="subtotal_holder" data-subtotal="<?= $order->order_subtotal ?>">$<?= number_format($order->order_subtotal, 2) ?></div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_54">
                                                                        <td class="form_field_holder ">
                                                                            <div class="money-field-container right"><input type="text" autocomplete="off" maxlength="15" id="view_ship_cost" name="ship_cost" class="view_view money data_control" tabindex="208" value="<?= number_format($order->shipping, 2) ?>"></div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_55">
                                                                        <td class="form_field_holder ">
                                                                            <div class="money-field-container right"><input type="text" autocomplete="off" maxlength="15" id="view_other_cost" name="other_cost" class="view_view money data_control" tabindex="209" value="<?= number_format($order->other, 2) ?>"></div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_56">
                                                                        <td class="form_field_holder ">
                                                                            <div class="static-text-field right" id="discount_holder">$<?= number_format($order->discount_amount, 2) ?></div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_57">
                                                                        <td class="form_field_holder ">
                                                                            <div class="static-text-field right" id="total_holder">$<?= number_format($order->order_total, 2) ?></div>
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
                                    <td colspan="3">
                                        <div>
                                            <table class="tab_columns">
                                            </table>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <table class="tab_columns">
                                            <tbody>
                                            <tr class="view_field_box">
                                                <td colspan="2" class="view_field_box " id="main_61">
                                                    <h3>Add Items</h3>
                                                    <table style="margin-top: 5px">
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <table class="tab_columns">
                                                                    <tbody>
                                                                    <tr id="view_f_62">
                                                                        <td class="form_field_holder view-padded-cell">
                                                                            <input type="text" autocomplete="off" value="" size="14" maxlength="255" id="add_search_item_text" class="view_view string itembarcode po-group-padded-input priority_auto_focus data_control" placeholder="Item Search" tabindex="210">
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                            <td>
                                                                <table class="tab_columns">
                                                                    <tbody>
                                                                    <tr id="view_f_64">
                                                                        <td class="view_functions  attached-search-button po-input-action-button " colspan="2">
                                                                            <span class="function">
                                                                                <button type="button" title="Add Item" id="registerItemSearch" data-item-action="purchase-order" class="attached-search-button po-input-action-button custom_function">Add Item</button>
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

                                    <?php if ($order->status == 'checkin') : ?>
                                    <td>
                                        <table class="view-column ">
                                            <tbody>
                                            <tr class="view_field_box">
                                                <td colspan="2" class="view_field_box " id="main_71">
                                                    <table>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <table class="tab_columns">
                                                                    <tbody>
                                                                    <tr id="view_f_72">
                                                                        <td class="view_functions  po-checkin-button po-recieve-all-button " colspan="2">
                                                                          <span class="function">
                                                                            <a href="<?= HOST_NAME.'pos/purchase_order_receive_items/'.$order->id ?>" title="Receive All Items" id="receive_all_po" class="btn btn-primary custom_function">Receive All Items</a>
                                                                          </span>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                            <?php if ((isset($order_items_completed) && $order_items_completed !== true) || $order->status == 'finished') : ?>
                                                            <td>
                                                                <table class="tab_columns">
                                                                    <tbody>
                                                                    <tr id="view_f_74">
                                                                        <td class="view_functions  po-checkin-button po-checkin-received-button " colspan="2">
                                                                          <span class="function">
                                                                              <a href="<?= HOST_NAME.'pos/purchase_order_received_to_inventory/'.$order->id ?>" title="Add Received To Inventory" id="checkin_received_po" class="btn btn-warning po-checkin-received-button custom_function">Add Received To Inventory</a>
                                                                          </span>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                            <?php endif; ?>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <?php endif; ?>
                                </tr>

                                <tr>
                                    <td colspan="3">
                                        <div>
                                            <table class="tab_columns">
                                                <tbody>
                                                <tr id="view_f_76">
                                                    <td class="view_listing" colspan="2">
                                                        <h2 class="child-listing-title" id="purchase_listings_items_view_title">Items</h2>
                                                        <div id="purchase_listings_items_view" class="is_pannel">
                                                            <div class="listing">
                                                                <div id="work_purchase_listings_items_view">
                                                                    <div id="purchase_listings_items_view_single">
                                                                        <div class="container" style="max-width:100%; padding-top: 15px ">
                                                                            <table cellpadding="0" cellspacing="0" border="0" class="datatable table table-striped table-bordered  table-status-colors">
                                                                                <thead>
                                                                                <tr>
                                                                                    <th>&nbsp;</th>
                                                                                    <th>#</th>
                                                                                    <th class="text-left">ITEM</th>

                                                                                    <th>RECEIVED</th>

                                                                                    <th>STATUS</th>
                                                                                    <th>BUY PRICE</th>
                                                                                    <th>RETAIL PRICE</th>
                                                                                    <th>QTY. ON HAND</th>
                                                                                    <th>ORDER QTY.</th>
                                                                                    <th>TOTAL</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody id="register_transaction" class="register_transaction">
                                                                                <?php if (isset($order_items) && $order_items !== false) : ?>
                                                                                <?php foreach ($order_items as $order_item) : ?>

                                                                                    <?php $order_item_status = 'status-grey'; ?>
                                                                                    <?php if ($order_item->status == 'received') : $order_item_status = 'status-orange'; ?>
                                                                                    <?php elseif ($order_item->status == 'completed') : $order_item_status = 'status-green'; ?>
                                                                                    <?php endif; ?>
                                                                                    <tr class="status purchase-item purchase-item-<?= $order_item->item_id ?> <?= $order_item_status ?>">
                                                                                    <td class="lf">
                                                                                        <?php if ($order_item->status !== 'completed') : ?>
                                                                                        <button type="button" title="Delete" class="delete purchaseOrderDeleteButton supplementary" data-order_item="<?= $order_item->id ?>"><i class="fa fa-trash "></i> </button>
                                                                                        <?php endif; ?>
                                                                                    </td>
                                                                                    <td class="lf" style="line-height: 1.8;">#<?= $order_item->id ?></td>
                                                                                    <td class="group text-left">
                                                                                        <a title="Edit Record" href="<?= HOST_NAME.'pos/item/'.$order_item->item_id ?>"><span><?= $order_item->item ?></span></a>
                                                                                    </td>


                                                                                    <?php if ($order->status == 'checkin') : ?>
                                                                                    <td class="string ">
                                                                                    <?php if ($order_item->status !== 'completed') : ?>
                                                                                        <input type="number" autocomplete="off" value="<?= $order_item->quantity_received ?>" size="4" maxlength="15" name="po-items[<?= $order_item->id ?>][quantity_received]" class="number data_control ">
                                                                                    <?php else : ?>
                                                                                        <input type="number" autocomplete="off" value="<?= $order_item->quantity_received ?>" size="4" maxlength="15" class="number data_control " disabled>
                                                                                    <?php endif; ?>
                                                                                    </td>
                                                                                    <?php else : ?>
                                                                                    <td class="number"><?= $order_item->quantity_received ?></td>
                                                                                    <?php endif; ?>

                                                                                    <td class="string  nowrap ">
                                                                                        <span class="status-label"><?= ucfirst($order_item->status) ?></span>
                                                                                    </td>
                                                                                    <td class="money ">
                                                                                        <div class="money-field-container">
                                                                                        <?php if ($order_item->status !== 'completed') : ?>
                                                                                            <input type="text" autocomplete="off" value="<?= $order_item->buy_price ?>" maxlength="15" name="po-items[<?= $order_item->id ?>][buy_price]" class="display_buy_price money data_control">
                                                                                        <?php else : ?>
                                                                                            <input type="text" autocomplete="off" value="<?= $order_item->buy_price ?>" maxlength="15" class="display_buy_price money data_control" disabled>
                                                                                        <?php endif; ?>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td class="money ">
                                                                                        <div class="money-field-container">
                                                                                        <?php if ($order_item->status !== 'completed') : ?>
                                                                                            <input type="text" autocomplete="off" value="<?= $order_item->price ?>" maxlength="15" name="po-items[<?= $order_item->id ?>][price]" class="display_price money data_control">
                                                                                        <?php else : ?>
                                                                                            <input type="text" autocomplete="off" value="<?= $order_item->price ?>" maxlength="15" class="display_price money data_control" disabled>
                                                                                        <?php endif; ?>
                                                                                        </div>
                                                                                    </td>

                                                                                    <td class="number"><?= $order_item->available_stock ?></td>

                                                                                    <td class="string ">
                                                                                    <?php if ($order_item->status !== 'completed') : ?>
                                                                                        <input type="number" autocomplete="off" value="<?= $order_item->quantity ?>" size="4" maxlength="15" name="po-items[<?= $order_item->id ?>][quantity]" class="number data_control display_quantity">
                                                                                    <?php else : ?>
                                                                                        <input type="number" autocomplete="off" value="<?= $order_item->quantity ?>" size="4" maxlength="15" class="number data_control display_quantity" disabled>
                                                                                    <?php endif; ?>
                                                                                    </td>

                                                                                    <?php if ($order_item->status !== 'completed') : ?>
                                                                                    <input type="hidden" name="po-items[<?= $order_item->id ?>][item_id]" value="<?= $order_item->item_id ?>">
                                                                                    <input type="hidden" name="po-items[<?= $order_item->id ?>][id]" value="<?= $order_item->id ?>">
                                                                                    <?php endif; ?>

                                                                                    <td class="money display_subtotal">$<?= $order_item->total ?></td>
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
    </div>
</div>

<style>
    .dataTables_wrapper .row {padding: 0}
    .listing table thead th {text-align: center}
    .listing table .money,
    .listing table .number,
    .listing table .string {text-align: center}
    .listing table thead th:last-child,
    .listing table tbody td:last-child {text-align: right !important;}

    .view_field_box td.view_functions {background: none}
</style>
<script>
    $(document).ready(function () {
        $('.date').flatpickr({format: 'd-m-Y'});

        $('#view_discount, #view_ship_cost, #view_other_cost').on('input', function (e) {
            var $subtotal = $('#subtotal_holder').data('subtotal');
            var $discount_amount = $('#discount_holder');
            var $total = $('#total_holder');

            var $shipping = $('#view_ship_cost').val();
            var $other = $('#view_other_cost').val();
            var $discount = $('#view_discount').val() ? $('#view_discount').val() : 0;

            var total = parseFloat($subtotal) + parseFloat($shipping);
            total = total + parseFloat($other);

            var discount_val = $discount ? total * parseInt($discount) / 100 : 0;
            total = total - discount_val;

            $discount_amount.html('$'+ formatMoney(discount_val));
            $total.html('$'+ formatMoney(total));
        });
    });
</script>
<?php endif; ?>