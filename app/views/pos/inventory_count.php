<?php if (isset($inventory_count) && $inventory_count !== false) : ?>
<div id="view" style="display: block;">
    <div class="view inventory-count-view">
        <form method="post" id="inventory-count-form">
            <div class="functions">
                <?php if ($inventory_count->status !== 'finished' && $inventory_count->status !== 'archived') : ?>
                <button type="submit" name="save" value="save" title="Save Changes" id="saveButton" class="save">Save Changes</button>
                <?php endif; ?>

                <?php if ($inventory_count->status == 'finished' || $inventory_count->status == 'archived') : ?>
                <button type="submit" name="save" value="save" title="Re-open Count" class="save">Re-open Count</button>
                <?php endif; ?>

                <?php if ($inventory_count->status !== 'finished') : ?>
                <button type="submit" name="finished" value="finished" title="Finished" class="nextaction custom_function">Finished</button>
                <?php endif; ?>

                <?php if ($inventory_count->status !== 'archived') : ?>
                <button type="submit" name="archived" value="archived" title="Archive" class="archive supplementary" >Archive</button>
                <?php endif; ?>
            </div>
            <div class="main">
                <div class="tabs tab-content">
                    <ul id="tabsMenu" class="tab-labels nav nav-tabs">
                        <li class="count nav-item"><a id="menuCount" href="#count" class="nav-link active" data-toggle="tab">Count</a></li>
                        <li class="totals nav-item"><a id="menuTotals" href="#totals" class="nav-link " data-toggle="tab">Totals</a></li>
                        <li class="missed nav-item"><a id="menuMissed" href="#missed" class="nav-link " data-toggle="tab">Missed</a></li>
                        <li class="reconcile nav-item"><a id="menuReconcile" href="#reconcile" class="nav-link " data-toggle="tab">Reconcile</a></li>
                        <li class="printout nav-item"><a id="menuPrintItemList" href="#printout" class="nav-link" data-toggle="tab">Print Item List</a></li>
                    </ul>

                    <article id="count" class="view_tab_count tab tab-pane active">
                        <div class="content">
                            <div class="view-columns">
                                <table class="view-layout set_auto_focus">
                                    <tbody>
                                    <tr>
                                        <td>
                                            <table class="view-column ">
                                                <tbody>
                                                <tr class="view_field_box">
                                                    <td colspan="2" class="view_field_box " id="count_3">
                                                        <h3>Details</h3>
                                                        <table>
                                                            <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table class="tab_columns">
                                                                        <tbody>
                                                                        <tr id="view_f_4">
                                                                            <td class="label">
                                                                                <label for="view_name">Name</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <input type="text" autocomplete="off" value="<?= $inventory_count->name ?>" size="30" maxlength="255" id="view_name" name="name" class="view_view string data_control" placeholder="Name" tabindex="200">
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_5">
                                                                            <td class="label">
                                                                                <label for="view_name">Created By</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <span><?= $inventory_count->admin_name ?></span>
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
                                                    <td colspan="2" class="view_field_box " id="count_6">
                                                        <h3>Count Items</h3>
                                                        <table>
                                                            <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table class="tab_columns">
                                                                        <tbody>
                                                                        <tr id="view_f_7">
                                                                            <td class="label">
                                                                                <label for="view_add_item_search">Search</label>
                                                                            </td>
                                                                            <td class="form_field_holder search">
                                                                                <input type="text" autocomplete="off" value="" size="14" maxlength="255" id="add_search_item_text" class="view_view string itembarcode priority_auto_focus data_control" placeholder="Item Scan / Search" tabindex="201">
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_8">
                                                                            <td class="view_functions " colspan="2">
                                                                                <span class="function">
                                                                                    <button title="Count Item" class="custom_function" id="registerItemSearch" type="button" data-item-action="inventory-count">
                                                                                        <i class="icon-plus "></i> Search
                                                                                    </button>
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
                                    <tr>
                                        <td colspan="2">
                                            <div>
                                                <table class="tab_columns">
                                                    <tbody>
                                                    <tr id="view_f_11">
                                                        <td class="view_listing" colspan="2">
                                                            <h2 class="child-listing-title" id="inventory_count_listings_inventory_count_items_view_title">
                                                                Counted Items
                                                            </h2>
                                                            <div id="inventory_count_listings_inventory_count_items_view" class="is_pannel">
                                                                <div class="listing">
                                                                    <div id="work_inventory_count_listings_inventory_count_items_view">
                                                                        <div id="inventory_count_listings_inventory_count_items_view_single">
                                                                            <div class="container" style="max-width:100%;padding-top: 15px;">
                                                                                <table cellpadding="0" cellspacing="0" border="0" class="datatable table table-striped table-bordered">
                                                                                    <thead>
                                                                                    <tr>
                                                                                        <th>&nbsp;</th>
                                                                                        <th>Item</th>
                                                                                        <th class="th-number">Should Have</th>
                                                                                        <th class="th-number">Counted</th>
                                                                                        <th>Who</th>
                                                                                        <th>When</th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                    <tbody id="register_transaction" class="register_transaction">

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

                    <article id="totals" class="view_tab_totals tab tab-pane">
                        <div class="content">
                            <div class="view-columns">
                                <table class="view-layout set_auto_focus">
                                    <tbody>
                                    <tr>
                                        <td>
                                            <table class="view-column ">
                                                <tbody>
                                                <tr id="view_f_3">
                                                    <td class="view_listing" colspan="2">
                                                        <h2 class="child-listing-title" id="inventory_count_listings_item_totals_view_title">
                                                            Item Totals
                                                        </h2>
                                                        <div id="inventory_count_listings_item_totals_view" class="is_pannel">
                                                            <div class="listing">
                                                                <div id="work_inventory_count_listings_item_totals_view">
                                                                    <div id="inventory_count_listings_item_totals_view_single">
                                                                        <div class="container" style="max-width:100%;padding-top: 15px;">
                                                                            <table cellpadding="0" cellspacing="0" border="0" class="datatable table table-striped table-bordered">
                                                                                <thead>
                                                                                <tr>
                                                                                    <th>&nbsp;</th>
                                                                                    <th>Item</th>
                                                                                    <th class="th-number">Should Have</th>
                                                                                    <th class="th-number">Counted</th>
                                                                                    <th class="th-number">Change</th>
                                                                                    <th class="td-shortdatetime">Modified</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody id="register_transaction" class="register_transaction">
                                                                                <?php if (isset($inventory_count_items) && $inventory_count_items !== false) : ?>
                                                                                    <?php foreach ($inventory_count_items as $inventory_count_item) : ?>
                                                                                        <tr class="row-status inventory-count-item inventory-count-item-<?= $inventory_count_item->item_id ?>" data-inv-count-item_id="<?= $inventory_count_item->item_id ?>" data-inv-count-item-id="<?= $inventory_count_item->id ?>">
                                                                                            <td class="lf ">
                                                                                                <button title="Delete" class="deleteInventoryCountItemButtonAjax supplementary"><i class="fa fa-trash "></i> </button>
                                                                                            </td>
                                                                                            <td class="string ">
                                                                                                <a title="Edit Record" target="_blank" href="<?= HOST_NAME.'pos/item/'.$inventory_count_item->item_id ?>"><span><?= $inventory_count_item->item ?></span></a>
                                                                                            </td>
                                                                                            <td class="number ">
                                                                                                <span><?= $inventory_count_item->available_stock ?></span>
                                                                                                <input type='hidden' name='invCount-items[<?= $inventory_count_item->item_id ?>][should_have]' value='<?= $inventory_count_item->available_stock ?>'>
                                                                                            </td>
                                                                                            <td class="number">
                                                                                                <input type="number" autocomplete="off" value="<?= $inventory_count_item->counted ?>" maxlength="15" name="invCount-items[<?= $inventory_count_item->item_id ?>][quantity]" class=" number data_control display_quantity">
                                                                                            </td>
                                                                                            <td class="number "><?= $inventory_count_item->counted - $inventory_count_item->available_stock ?></td>
                                                                                            <td class="shortdatetime ">
                                                                                                <span><?= date('h:i A', strtotime($inventory_count_item->updated)) ?></span>
                                                                                            </td>
                                                                                            <input type="hidden" name="invCount-items[<?= $inventory_count_item->item_id ?>][id]" value="<?= $inventory_count_item->id ?>">
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

                    <article id="missed" class="view_tab_missed tab tab-pane">
                        <div class="content">
                            <div class="view-columns">
                                <table class="view-layout set_auto_focus">
                                    <tbody>
                                    <tr>
                                        <td>
                                            <table class="view-column ">
                                                <tbody>
                                                <tr class="view_field_box">
                                                    <td colspan="2" class="view_field_box " id="missed_3">
                                                        <h3>Missed Item Help</h3>
                                                        <table>
                                                            <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table class="tab_columns">
                                                                        <tbody>
                                                                        <tr id="view_f_4">
                                                                            <td class="form_field_holder " colspan="2">
                                                                                <div>
                                                                                    <ul>
                                                                                        <li>Missed items are items the system thinks you should have in stock but you have not yet counted.</li>
                                                                                        <li>You should check this list and count any items you have that you missed.</li>
                                                                                        <li>Zero all items that you are sure you no longer have in stock. This will remove the inventory when you reconcile.</li>
                                                                                    </ul>
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
                                                <tr id="view_f_6">
                                                    <td class="view_listing" colspan="2">
                                                        <h2 class="child-listing-title" id="inventory_count_listings_inventory_missed_items_view_title">
                                                            Missed Items
                                                        </h2>
                                                        <div class="is_pannel">
                                                            <div class="listing">
                                                                <div id="work_inventory_count_listings_inventory_missed_items_view">
                                                                    <div id="inventory_count_listings_inventory_missed_items_view_single">
                                                                        <div class="container" style="max-width:100%;padding-top: 15px;">
                                                                            <table cellpadding="0" cellspacing="0" border="0" class="datatable table table-striped table-bordered">
                                                                                <thead>
                                                                                <tr>
                                                                                    <th>&nbsp;</th>
                                                                                    <th class="th-string">Item</th>
                                                                                    <th class="th-number">Should Have</th>
                                                                                    <th class="th-string">Count</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                <?php if (isset($inventory_count_missed_items) && $inventory_count_missed_items !== false) : ?>
                                                                                    <?php foreach ($inventory_count_missed_items as $inventory_count_missed_item) : ?>
                                                                                    <tr class="row-status inventory-count-item inventory-count-item-<?= $inventory_count_missed_item->item_id ?>">
                                                                                        <td class="lf ">
                                                                                            <button title="Zero" type="button" class="custom_function" onclick="window.location.href='<?= HOST_NAME.'ajax/inventory_count_zero_item_inventory/'.$inventory_count->id.'/'.$inventory_count_missed_item->item_id ?>'"><i class="fa fa-check "></i> Zero</button>
                                                                                        </td>

                                                                                        <td class="string ">
                                                                                            <a title="Edit Record" target="_blank" href="<?= HOST_NAME.'pos/item/'.$inventory_count_missed_item->item_id ?>"><span><?= $inventory_count_missed_item->item ?></span></a>
                                                                                        </td>
                                                                                        <td class="number ">
                                                                                            <span><?= $inventory_count_missed_item->available_stock ?></span>
                                                                                            <input type='hidden' name='missed-items[<?= $inventory_count_missed_item->item_id ?>][should_have]' value='<?= $inventory_count_missed_item->available_stock ?>'>
                                                                                        </td>
                                                                                        <td class="string">
                                                                                            <input type="number" autocomplete="off" value="" maxlength="15" name="missed-items[<?= $inventory_count_missed_item->item_id ?>][quantity]" class=" number data_control display_quantity">
                                                                                        </td>
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

                    <article id="reconcile" class="view_tab_reconcile tab tab-pane">
                        <div class="content">
                            <div class="view-columns">
                                <table class="view-layout set_auto_focus">
                                    <tbody>
                                    <tr>
                                        <td>
                                            <table class="view-column ">
                                                <tbody>
                                                <tr class="view_field_box">
                                                    <td colspan="2" class="view_field_box " id="reconcile_3">
                                                        <h3>Reconcile Inventory with Count</h3>
                                                        <table>
                                                            <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table class="tab_columns">
                                                                        <tbody>
                                                                        <tr id="view_f_4">
                                                                            <td class="form_field_holder " colspan="2">
                                                                                <div>
                                                                                    <ul>
                                                                                        <li>Reconcile will change inventory records to match the physical counts you have made.</li>
                                                                                        <li>Check your Missed items before you reconcile.</li>
                                                                                        <li>Look over the Adjustment numbers below to see if they look correct/reasonable.</li>
                                                                                        <li><strong>This action can not be undone.</strong></li>
                                                                                    </ul>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_5">
                                                                            <td class="view_functions " colspan="2">
                                                                              <span class="function">
                                                                                <button title="Reconcile Inventory" type="button" class="custom_function" onclick="window.location.href='<?= HOST_NAME.'ajax/inventory_count_reconcile/'.$inventory_count->id ?>'"><i class="fa fa-exclamation-triangle"></i> Reconcile Inventory</button>
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
                                        <td>
                                            <table class="view-column ">
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <div>
                                                <table class="tab_columns">
                                                    <tbody>
                                                    <tr id="view_f_8">
                                                        <td class="view_listing" colspan="2">
                                                            <h2 class="child-listing-title" id="inventory_count_listings_reconcile_preview_view_title">
                                                                Item Totals
                                                            </h2>
                                                            <div class="is_pannel">
                                                                <div class="listing">
                                                                    <div id="work_inventory_count_listings_reconcile_preview_view">
                                                                        <div id="inventory_count_listings_reconcile_preview_view_single">
                                                                            <div class="container" style="max-width:100%;padding-top: 15px;">
                                                                                <table cellpadding="0" cellspacing="0" border="0" class="datatable table table-striped table-bordered">
                                                                                    <thead>
                                                                                    <tr>
                                                                                        <th class="th-string">Item</th>
                                                                                        <th class="th-number">Adjustment</th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                    <?php if (isset($inventory_count_items) && $inventory_count_items) : ?>
                                                                                    <?php foreach ($inventory_count_items as $inventory_count_item) : ?>
                                                                                        <?php if ($inventory_count_item->should_have !== $inventory_count_item->counted) : ?>
                                                                                        <tr class="row-status">
                                                                                            <td class="string ">
                                                                                                <a title="Edit Record" target="_blank" href="<?= HOST_NAME.'pos/item/'.$inventory_count_item->item_id ?>"><span><?= $inventory_count_item->item ?></span></a>
                                                                                            </td>
                                                                                            <td class="number "><?= $inventory_count_item->counted - $inventory_count_item->available_stock ?></td>
                                                                                        </tr>
                                                                                        <?php endif; ?>
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

                    <article id="printout" class="view_tab_printout tab tab-pane">
                        <div class="content">
                            <div class="view-columns">
                                <table class="view-layout set_auto_focus">
                                    <tbody>
                                    <tr>
                                        <td>
                                            <table class="view-column ">
                                                <tbody>
                                                <tr class="view_field_box">
                                                    <td colspan="2" class="view_field_box " id="printout_3">
                                                        <h3>Print Item List</h3>
                                                        <table>
                                                            <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table class="tab_columns">
                                                                        <tbody>
                                                                        <tr id="view_f_4">
                                                                            <td class="form_field_holder " colspan="2">
                                                                                <div>
                                                                                    <div style="width: 150px;">
                                                                                        1) Print your item list below.<br>
                                                                                        2) Complete your count using the print out.<br>
                                                                                        3) Enter your counts into the system using the form to the right.
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_5">
                                                                            <td>&nbsp;</td>
                                                                            <td>&nbsp;</td>
                                                                        </tr>
                                                                        <tr class="view_field_box">
                                                                            <td colspan="2" class="view_field_box ">
                                                                                <h3>Sort By Item ID</h3>
                                                                                <table>
                                                                                    <tbody>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <table class="tab_columns">
                                                                                                <tbody>
                                                                                                <tr id="view_f_7">
                                                                                                    <td class="view_functions print_function  " colspan="2">
                                                                                                        <span class="function">
                                                                                                            <a target="_blank" href="<?= HOST_NAME.'pos/inventory_count_printout/'.$inventory_count->id ?>" class=" print_function " title="No Grouping">No Grouping</a>
                                                                                                        </span>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr id="view_f_8">
                                                                                                    <td class="view_functions   print_function  " colspan="2">
                                                                                                         <span class="function">
                                                                                                            <a target="_blank" href="<?= HOST_NAME.'pos/inventory_count_printout/'.$inventory_count->id.'?order-by=category' ?>" class=" print_function " title="By Category">By Category</a>
                                                                                                         </span>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr id="view_f_9">
                                                                                                    <td class="view_functions   print_function  " colspan="2">
                                                                                                         <span class="function">
                                                                                                            <a target="_blank" href="<?= HOST_NAME.'pos/inventory_count_printout/'.$inventory_count->id.'?order-by=brand' ?>" class=" print_function " title="By Brand">By Brand</a>
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
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td>
                                            <table class="view-column ">
                                                <tbody>
                                                <tr class="view_field_box">
                                                    <td colspan="2" class="view_field_box " id="printout_17">
                                                        <h3>Count By ID From Printed List</h3>
                                                        <table>
                                                            <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table class="tab_columns">
                                                                        <tbody>
                                                                        <tr id="view_f_18">
                                                                            <td class="label">
                                                                                <label for="view_count_by_id_id">
                                                                                    ID
                                                                                </label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <input type="text" autocomplete="off" size="6" maxlength="15" id="view_count_by_id_id" name="count_by_id_id" class="view_view number priority_auto_focus data_control" placeholder="ID" tabindex="200">
                                                                            </td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                                <td>
                                                                    <table class="tab_columns">
                                                                        <tbody>
                                                                        <tr id="view_f_20">
                                                                            <td class="label">
                                                                                <label for="view_count_by_id_count">
                                                                                    Count
                                                                                </label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <input type="text" autocomplete="off" value="" size="6" maxlength="15" id="view_count_by_id_count" name="count_by_id_count" class="view_view number priority_auto_focus data_control" placeholder="Count" tabindex="201">
                                                                            </td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                                <td>
                                                                    <table class="tab_columns">
                                                                        <tbody>
                                                                        <tr id="view_f_22">
                                                                            <td class="view_functions " colspan="2">
                                                                                 <span class="function">
                                                                                    <button id="view_count_by_id_button" type="button" title="Enter Count" class="custom_function" style="padding: 6px 10px"><i class="fa fa-plus "></i> Enter Count</button>
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
                                                <tr id="view_f_24">
                                                    <td class="view_listing" colspan="2">
                                                        <h2 class="child-listing-title" id="inventory_count_listings_item_totals_for_print_view_title">
                                                            Item Totals
                                                        </h2>
                                                        <div class="is_pannel">
                                                            <div class="listing">
                                                                <div id="work_inventory_count_listings_item_totals_for_print_view">
                                                                    <div id="inventory_count_listings_item_totals_for_print_view_single">
                                                                        <div class="container" style="max-width:100%;padding-top: 15px;">
                                                                            <table cellpadding="0" cellspacing="0" border="0" class="datatable table table-striped table-bordered">
                                                                                <thead>
                                                                                <tr>
                                                                                    <th></th>
                                                                                    <th class="th-string">Item</th>
                                                                                    <th class="th-number">Should Have</th>
                                                                                    <th class="th-number">Counted</th>
                                                                                    <th class="th-number">Change</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody id="register_transaction-printout_count_byID">

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
        </form>
    </div>
</div>
<?php endif; ?>