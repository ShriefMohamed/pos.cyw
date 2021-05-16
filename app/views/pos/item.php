<?php if (isset($item) && $item != false) : ?>
<div id="view" style="display: block;">
    <div class="view item-view_update-view">
        <div class="functions">
            <button title="Save Changes" id="saveButton" class="save">Save Changes</button>
            <button title="Add Label" id="addLabelButton" class="custom_function">Add Label</button>
            <button title="Print Label" id="printLabelButton" class="griffin-no-display print-label">Print Label</button>
            <button title="Archive" class="archive supplementary" >Archive</button>
        </div>

        <div class="main">
            <div class="tabs tab-content">
                <ul id="tabsMenu" class="tab-labels nav nav-tabs">
                    <li class="details nav-item"><a id="menuDetails" class="nav-link active" href="#tabs-menu-details-link" data-toggle="tab">Details</a></li>
                    <?php if ($item->is_tracked_as_inventory == 1) : ?>
                    <li class="inventory nav-item"><a id="menuInventory" class="nav-link" href="#tabs-menu-inventory-link" data-toggle="tab">Inventory</a></li>
                    <?php endif; ?>
                    <li class="sales nav-item"><a id="menuSales" class="nav-link" href="#tabs-menu-sales-link" data-toggle="tab">Sales</a></li>
                    <li class="customers nav-item"><a id="menuCustomers" class="nav-link" href="#tabs-menu-customers-link" data-toggle="tab">Customers</a></li>
                    <li class="purchases nav-item"><a id="menuPurchaseOrders" class="nav-link" href="#tabs-menu-purchases-link" data-toggle="tab">Purchase Orders</a></li>
                    <li class="returned nav-item"><a id="menuVendorReturns" class="nav-link" href="#tabs-menu-returned-link" data-toggle="tab">Vendor Returns</a></li>
                    <li class="history nav-item"><a id="menuHistory" class="nav-link" href="#tabs-menu-history-link" data-toggle="tab">History</a></li>
                </ul>

                <article id="tabs-menu-details-link" class="view_tab_details tab tab-pane active">
                    <div class="content">
                        <div class="view-columns">
                            <form method="post" class="update-item-form" action="<?= HOST_NAME.'pos/item_update/'.$item->id ?>">
                                <input type="hidden" name="xero_accounts_id" value="<?= $item->items_xero_accounts_id ?>">
                                <input type="hidden" name="auto_reorder_id" value="<?= $item->items_auto_reorder_id ?>">

                                <table class="view-layout set_auto_focus">
                                    <tbody>
                                    <tr>
                                        <td>
                                            <table class="view-column ">
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <div class="view_group">
                                                <div class="row" style="padding: 0">
                                                    <div class="col-md-7">
                                                        <div class="row" style="padding: 0">
                                                            <div class="col-md-6">
                                                                <input type="text" autocomplete="off" size="40" maxlength="255" id="view_item" name="item" class="form-control" placeholder="Item" tabindex="1" value="<?= htmlentities($item->item) ?>">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="text" autocomplete="off" size="40" maxlength="255" id="view_description" name="description" class="form-control" placeholder="Description" tabindex="2" value="<?= htmlentities($item->description) ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label class="field_label flat-field-input" for="view_function__IsTrackedAsInventory1" data-toggle="tooltip" data-placement="top" title="This treats your item as a tracked inventory asset. System will record the quantity on hand and prevent you selling below a quantity of zero.">
                                                            <input type="checkbox" name="is_tracked_as_inventory" id="view_function__IsTrackedAsInventory1" class="view_view boolean data_control" tabindex="2" <?= $item->is_tracked_as_inventory == 1 ? 'checked' : '' ?>>
                                                            Tracked As Inventory
                                                        </label>

                                                        <label class="field_label flat-field-input" for="view_function__item_type">Type</label>
                                                        <select name="item_type" id="view_function__item_type" class="view_view data_control" tabindex="3">
                                                            <option value="single" <?= $item->item_type == 'single' ? 'selected' : '' ?>>Single</option>
                                                            <option value="box" <?= $item->item_type == 'box' ? 'selected' : '' ?>>Box</option>
                                                            <option value="assembly" <?= $item->item_type == 'assembly' ? 'selected' : '' ?>>Assembly</option>
                                                        </select>

                                                        <label class="field_label flat-field-input" for="view_function__serialized">
                                                            <input type="checkbox" name="serialized" id="view_function__serialized" class="view_view boolean data_control" tabindex="4" <?= $item->serialized == 1 ? 'checked' : '' ?>>
                                                            Serialized
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table class="tab_columns">
                                                <tbody>
                                                <tr class="view_field_box">
                                                    <td colspan="2" class="view_field_box " id="details_15">
                                                        <h3>IDs</h3>
                                                        <table>
                                                            <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table class="tab_columns">
                                                                        <tbody>
                                                                        <tr id="view_f_16">
                                                                            <td class="label">
                                                                                <label for="view_function__getBikeSoftID">System UID</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <span id="view_function__getBikeSoftID" style="width: auto; display: inline;" class="view_field_relation"><?= $item->uid ?></span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_17">
                                                                            <td class="label">
                                                                                <label for="view_upc">UPC</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <input type="text" autocomplete="off" value="<?= $item->upc ?>" size="14" maxlength="255" id="view_upc" name="upc" class="view_view string itembarcode scanner-no-submit data_control" tabindex="5">
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_18">
                                                                            <td class="label">
                                                                                <label for="view_shop_sku">Custom SKU</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <input type="text" autocomplete="off" size="14" maxlength="255" id="view_shop_sku" name="shop_sku" class="view_view string scanner-no-submit data_control" tabindex="6" value="<?= $item->shop_sku ?>">
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_19">
                                                                            <td class="label">
                                                                                <label for="view_man_sku">Manufact. SKU</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <input type="text" autocomplete="off" size="14" maxlength="255" id="view_man_sku" name="man_sku" class="view_view string scanner-no-submit data_control" tabindex="7" value="<?= $item->man_sku ?>">
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
                                                    <td colspan="2" class="view_field_box " id="details_22">
                                                        <h3>Organize</h3>
                                                        <table>
                                                            <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table class="tab_columns">
                                                                        <tbody>
                                                                        <tr id="view_f_32">
                                                                            <td class="label">
                                                                                <label for="select2-departments">Department</label>
                                                                            </td>
                                                                            <td class="form_field_holder " style="padding-bottom: 10px">
                                                                                <select id="select2-departments" class="form-control" name="department" tabindex="8">
                                                                                    <option value="0">None</option>
                                                                                    <?php if (isset($departments) && $departments !== false) : ?>
                                                                                        <?php foreach ($departments as $department) : ?>
                                                                                            <option <?= $department == $item->department ? 'selected' : '' ?> value="<?= $department ?>"><?= $department ?></option>
                                                                                        <?php endforeach; ?>
                                                                                    <?php endif; ?>
                                                                                </select>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_31">
                                                                            <td class="label">
                                                                                <label for="view_account_id">Xero Inventory Asset Account</label>
                                                                            </td>
                                                                            <td class="form_field_holder form-group">
                                                                                <select name="xero_ia_account" id="view_account_id" class="view_view data_control form-control form-round" tabindex="9" style="width: 80%">
                                                                                    <option value="0">None</option>
                                                                                    <?php if (isset($xero_accounts) && $xero_accounts !== false) : ?>
                                                                                        <?php foreach ($xero_accounts as $xero_account) : ?>
                                                                                            <option <?= $xero_account->id == $item->inventory_asset_xero_account_id ? 'selected' : '' ?> value="<?= $xero_account->id.'|||'.$xero_account->Code ?>"><?= $xero_account->Name.'  ('.$xero_account->Code.')' ?></option>
                                                                                        <?php endforeach; ?>
                                                                                    <?php endif; ?>
                                                                                </select>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_31">
                                                                            <td class="label">
                                                                                <label for="view_account_id">Xero Purchase Account</label>
                                                                            </td>
                                                                            <td class="form_field_holder form-group">
                                                                                <select name="xero_p_account" id="view_account_id" class="view_view data_control form-control form-round" tabindex="9" style="width: 80%">
                                                                                    <option value="0">None</option>
                                                                                    <?php if (isset($xero_accounts) && $xero_accounts !== false) : ?>
                                                                                        <?php foreach ($xero_accounts as $xero_account) : ?>
                                                                                            <option <?= $xero_account->id == $item->purchase_xero_account_id ? 'selected' : '' ?> value="<?= $xero_account->id.'|||'.$xero_account->Code ?>"><?= $xero_account->Name.'  ('.$xero_account->Code.')' ?></option>
                                                                                        <?php endforeach; ?>
                                                                                    <?php endif; ?>
                                                                                </select>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_31">
                                                                            <td class="label">
                                                                                <label for="view_account_id">Xero Sales Account</label>
                                                                            </td>
                                                                            <td class="form_field_holder form-group">
                                                                                <select name="xero_s_account" id="view_account_id" class="view_view data_control form-control form-round" tabindex="9" style="width: 80%">
                                                                                    <option value="0">None</option>
                                                                                    <?php if (isset($xero_accounts) && $xero_accounts !== false) : ?>
                                                                                        <?php foreach ($xero_accounts as $xero_account) : ?>
                                                                                            <option <?= $xero_account->id == $item->sales_xero_account_id ? 'selected' : '' ?> value="<?= $xero_account->id.'|||'.$xero_account->Code ?>"><?= $xero_account->Name.'  ('.$xero_account->Code.')' ?></option>
                                                                                        <?php endforeach; ?>
                                                                                    <?php endif; ?>
                                                                                </select>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_30">
                                                                            <td class="label">
                                                                                <label for="view_category_id">Category</label>
                                                                            </td>
                                                                            <td class="form_field_holder form-group">
                                                                                <select name="category" id="view_category_id" class="view_view data_control form-control form-round" tabindex="10" style="width: 80%">
                                                                                    <option value="0">None</option>
                                                                                    <?php if (isset($categories) && $categories !== false) : ?>
                                                                                        <?php foreach ($categories as $category) : ?>
                                                                                            <option <?= $category->id == $item->category ? 'selected' : '' ?> value="<?= $category->id ?>"><?= $category->category ?></option>
                                                                                        <?php endforeach; ?>
                                                                                    <?php endif; ?>
                                                                                </select>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_31">
                                                                            <td class="label">
                                                                                <label for="view_manufacturer_id">Brand</label>
                                                                            </td>
                                                                            <td class="form_field_holder form-group">
                                                                                <select name="brand" id="view_brand_id" class="view_view data_control form-control form-round" tabindex="11" style="width: 80%">
                                                                                    <option value="0">None</option>
                                                                                    <?php if (isset($brands) && $brands !== false) : ?>
                                                                                        <?php foreach ($brands as $brand) : ?>
                                                                                            <option <?= $brand->id == $item->brand ? 'selected' : '' ?> value="<?= $brand->id ?>"><?= $brand->brand ?></option>
                                                                                        <?php endforeach; ?>
                                                                                    <?php endif; ?>
                                                                                </select>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_32">
                                                                            <td class="label">
                                                                                <label for="select2-tags">Tags</label>
                                                                            </td>
                                                                            <td class="form_field_holder " style="padding-bottom: 10px">
                                                                                <select id="select2-tags" class="form-control" multiple="multiple" name="tags[]">
                                                                                    <?php if (isset($tags) && $tags != false) : ?>
                                                                                        <?php foreach ($tags as $tag) : ?>
                                                                                            <?php $o_tags = explode(',', $item->tags); ?>
                                                                                            <option <?= in_array($tag, $o_tags) ? 'selected' : '' ?> value="<?= $tag ?>"><?= $tag ?></option>
                                                                                        <?php endforeach; ?>
                                                                                    <?php endif; ?>
                                                                                </select>
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
                                                    <td colspan="2" class="view_field_box " id="details_40">
                                                        <h3>Inventory Buy Price Inc. GST</h3>
                                                        <table>
                                                            <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table class="tab_columns">
                                                                        <tbody>
                                                                        <tr id="view_f_41">
                                                                            <td class="label">
                                                                                <label for="view_default_cost">Dealer Buy Price Inc. GST</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <div class="money-field-container">
                                                                                    <input type="text" autocomplete="off" maxlength="15" id="view_default_cost" name="buy_price" class="view_view money data_control" placeholder="Dealer Buy Price Inc. GST" tabindex="40" required value="<?= $item->buy_price ?>">
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_41">
                                                                            <td class="label">
                                                                                <label for="view_default_percentage">RRP Percentage</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <div class="percent-field-container">
                                                                                    <input type="text" autocomplete="off" maxlength="15" id="view_default_percentage" name="rrp_percentage" class="view_view money data_control" placeholder="RRP Percentage" tabindex="41" required value="<?= $item->rrp_percentage ?>">
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_42">
                                                                            <td class="label">
                                                                                <label for="view_vendor_id">Vendor</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <select name="vendor" id="view_vendor_id" class="view_view data_control" tabindex="42">
                                                                                    <option value="0">None</option>
                                                                                    <?php if (isset($vendors) && $vendors !== false) : ?>
                                                                                        <?php foreach ($vendors as $vendor) : ?>
                                                                                            <option <?= $vendor->id == $item->vendor_id ? 'selected' : '' ?> value="<?= $vendor->id ?>"><?= $vendor->name ?></option>
                                                                                        <?php endforeach; ?>
                                                                                    <?php endif; ?>
                                                                                </select>
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
                                                    <td colspan="2" class="view_field_box " id="item-pricing">
                                                        <h3>Pricing</h3>
                                                        <table class="product-prices">
                                                            <thead>
                                                            <tr>
                                                                <th>Name</th>
                                                                <th>Price</th>
                                                                <th class="markup">Margin</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php if (isset($pricing_levels) && $pricing_levels !== false) : ?>
                                                                <?php foreach ($pricing_levels as $pricing_level) : ?>
                                                                    <tr class="pricing-level pricing-level-<?= $pricing_level->id ?> <?= $pricing_level->teir == 'TEIR 2'? "teir2" : '' ?>" data-rate="<?= $pricing_level->rate ?>">
                                                                        <th><?= $pricing_level->teir ?></th>
                                                                        <td class="price">
                                                                            <input tabindex="43" id="view_price_default" type="text" class="money pricelevel" value="0.00" name="prices[]" disabled>
                                                                        </td>
                                                                        <td class="margin"></td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>

                                                            <tr class="pricing-level-rrp">
                                                                <th>RRP</th>
                                                                <td class="price default-linked">
                                                                    <input tabindex="44" id="view_price_rrp" type="text" class="money pricelevel" name="rrp_price" required value="<?= $item->rrp_price ?>">
                                                                </td>
                                                                <td class="margin"></td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                        <div class="price-settings">
                                                            <div class="price-setting">
                                                                <label class="checkbox">
                                                                    <input type="checkbox" id="discountable_checkbox" name="discountable" class="boolean" <?= $item->discountable == 1 ? 'checked' : '' ?>> Discounts Allowed
                                                                </label>
                                                            </div>
                                                            <div class="price-setting">
                                                                <label for="class_id" class="select">Tax Class</label>
                                                                <select name="tax_class" id="tax_class_dropdown" tabindex="45">
                                                                    <option value="0">None</option>
                                                                    <?php if (isset($tax_classes) && $tax_classes !== false) : ?>
                                                                        <?php foreach ($tax_classes as $tax_class) : ?>
                                                                            <option <?= $tax_class->id == $item->tax_class ? 'selected' : '' ?> value="<?= $tax_class->id ?>"><?= $tax_class->class.' ('.$tax_class->rate.'%)' ?></option>
                                                                        <?php endforeach; ?>
                                                                    <?php endif; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr class="view_field_box">
                                                    <td colspan="2" class="view_field_box " id="details_44">
                                                        <h3>Automatic Re-Ordering</h3>
                                                        <table>
                                                            <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table class="tab_columns">
                                                                        <tbody>
                                                                        <tr id="view_f_45">
                                                                            <td class="label">
                                                                                <label for="view_function__reorder_btn">Automatic Re-Ordering</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <input type="checkbox" id="view_function__reorder_btn" name="auto_reorder" class="view_view number data_control" <?= $item->auto_reorder == 1 ? 'checked' : '' ?>>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_45">
                                                                            <td class="label">
                                                                                <label for="view_function__reorder_pnt">Reorder Point</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <input type="text" autocomplete="off" size="6" maxlength="15" id="view_function__reorder_pnt" name="reorder_point" class="view_view number data_control" placeholder="Reorder Point" tabindex="42" value="<?= $item->reorder_point ? $item->reorder_point : 0 ?>">
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_46">
                                                                            <td class="label">
                                                                                <label for="view_function__reorder_lvl">Desired Inventory Level</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <input type="text" autocomplete="off" size="6" maxlength="15" id="view_function__reorder_lvl" name="reorder_level" class="view_view number data_control" tabindex="43" value="<?= $item->reorder_level ? $item->reorder_level : 0 ?>">
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
                                                    <td colspan="2" class="view_field_box " id="details_42">
                                                        <h3>Stock</h3>
                                                        <table>
                                                            <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table class="tab_columns">
                                                                        <tbody>
                                                                        <tr id="view_f_43">
                                                                            <td class="label">
                                                                                <label for="view_function__getInStock_details">In Stock</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <span id="view_function__getInStock_details" style="width: auto; display: inline;" class="view_field_relation" ><?= $item->available_stock ?></span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_44">
                                                                            <td class="label">
                                                                                <label for="function__getAvgCost_details">Avg. Cost</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <span id="function__getAvgCost_details" style="width: auto; display: inline;" class="view_field_relation">$<?= isset($item_avg_price->avg_buy_price) && $item_avg_price->avg_buy_price ? number_format(substr($item_avg_price->avg_buy_price, 0, 5), 2) : 0 ?></span>
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
                                                    <td colspan="2" class="view_field_box " id="details_46">
                                                        <h3>Orders</h3>
                                                        <table>
                                                            <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table class="tab_columns">
                                                                        <tbody>
                                                                        <tr id="view_f_47">
                                                                            <td class="label">
                                                                                <label for="view_function__getLayaway">Layaway</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <span id="view_function__getLayaway" style="width: auto; display: inline;" class="view_field_relation">0</span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_48">
                                                                            <td class="label">
                                                                                <label for="view_function__getSpecialOrder">
                                                                                    Special Order
                                                                                </label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                     <span id="view_function__getSpecialOrder" style="width: auto; display: inline;" class="view_field_relation">
                                                                     0</span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_49">
                                                                            <td class="label">
                                                                                <label for="view_function__getBackOrder">
                                                                                    On Order
                                                                                </label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                     <span id="view_function__getBackOrder" style="width: auto; display: inline;" class="view_field_relation">
                                                                     0</span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_50">
                                                                            <td class="label">
                                                                                <label for="view_function__getSentVendorReturnQty">
                                                                                    Pending Return
                                                                                </label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                     <span id="view_function__getSentVendorReturnQty" style="width: auto; display: inline;" class="view_field_relation">
                                                                     0</span>
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
                                                    <td colspan="2" class="view_field_box " id="details_52">
                                                        <h3>Sales History</h3>
                                                        <table>
                                                            <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table class="tab_columns">
                                                                        <tbody>
                                                                        <tr id="view_f_53">
                                                                            <td class="label">
                                                                                <label for="view_function__getItemSalesDay">
                                                                                    Day
                                                                                </label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                     <span id="view_function__getItemSalesDay" style="width: auto; display: inline;" class="view_field_relation">
                                                                     0</span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_54">
                                                                            <td class="label">
                                                                                <label for="view_function__getItemSalesWeek">
                                                                                    Week
                                                                                </label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                     <span id="view_function__getItemSalesWeek" style="width: auto; display: inline;" class="view_field_relation">
                                                                     0</span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_55">
                                                                            <td class="label">
                                                                                <label for="view_function__getItemSalesMonth">
                                                                                    Month
                                                                                </label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                     <span id="view_function__getItemSalesMonth" style="width: auto; display: inline;" class="view_field_relation">
                                                                     0</span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_56">
                                                                            <td class="label">
                                                                                <label for="view_function__getItemSalesYear">
                                                                                    Year
                                                                                </label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                     <span id="view_function__getItemSalesYear" style="width: auto; display: inline;" class="view_field_relation">
                                                                     0</span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_57">
                                                                            <td class="label">
                                                                                <label for="view_function__getItemSalesAll">
                                                                                    All
                                                                                </label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                     <span id="view_function__getItemSalesAll" style="width: auto; display: inline;" class="view_field_relation">
                                                                     0</span>
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
                        </div>
                    </div>
                </article>

                <?php if ($item->is_tracked_as_inventory == 1) : ?>
                <article id="tabs-menu-inventory-link" class="view_tab_inventory tab tab-pane">
                    <div class="content">
                        <div class="view-columns">
                            <table class="view-layout set_auto_focus">
                                <tbody>
                                <tr>
                                    <td>
                                        <table class="view-column ">
                                            <tbody>
                                            <tr class="view_field_box">
                                                <td colspan="2" class="view_field_box " id="inventory_9">
                                                    <h3>Stock</h3>
                                                    <table>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <table class="tab_columns">
                                                                    <tbody>
                                                                    <tr id="view_f_10">
                                                                        <td class="label">
                                                                            <label for="view_function__getInStock_inventory">All Stock</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <span id="view_function__getInStock_inventory" style="width: auto; display: inline;" class="view_field_relation"><?= $item->available_stock - (isset($item_inventory) && $item_inventory !== false && $item_inventory->awaiting_dispatch ? $item_inventory->awaiting_dispatch : 0) ?></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="label">
                                                                            <label for="view_function__getInStock_inventory">Physically Available</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <span id="view_function__getInStock_inventory" style="width: auto; display: inline;" class="view_field_relation"><?= $item->available_stock  ?></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="label">
                                                                            <label for="view_function__getInStock_inventory">Awaiting Dispatch</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <span id="view_function__getInStock_inventory" style="width: auto; display: inline;" class="view_field_relation"><?= isset($item_inventory) && $item_inventory !== false && $item_inventory->awaiting_dispatch ? $item_inventory->awaiting_dispatch : 0 ?></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_11">
                                                                        <td class="label">
                                                                            <label for="function__getAvgCost_inventory">Avg. Cost</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <span id="function__getAvgCost_inventory" style="width: auto; display: inline;" class="view_field_relation">$<?= isset($item_avg_price->avg_buy_price) && $item_avg_price->avg_buy_price ? number_format(substr($item_avg_price->avg_buy_price, 0, 5), 2) : 0 ?></span>
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
                                                <td colspan="2" class="view_field_box " id="inventory_13">
                                                    <h3>Orders</h3>
                                                    <table>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <table class="tab_columns">
                                                                    <tbody>
                                                                    <tr id="view_f_14">
                                                                        <td class="label">
                                                                            <label for="view_function__getLayaway">
                                                                                Layaway
                                                                            </label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                      <span id="view_function__getLayaway" style="width: auto; display: inline;" class="view_field_relation">
                                                      0</span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_15">
                                                                        <td class="label">
                                                                            <label for="view_function__getSpecialOrder">
                                                                                Special Order
                                                                            </label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                      <span id="view_function__getSpecialOrder" style="width: auto; display: inline;" class="view_field_relation">
                                                      0</span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_16">
                                                                        <td class="label">
                                                                            <label for="view_function__getBackOrder">
                                                                                On Order
                                                                            </label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                      <span id="view_function__getBackOrder" style="width: auto; display: inline;" class="view_field_relation">
                                                      0</span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_17">
                                                                        <td class="label">
                                                                            <label for="view_function__getSentVendorReturnQty">
                                                                                Pending Return
                                                                            </label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                      <span id="view_function__getSentVendorReturnQty" style="width: auto; display: inline;" class="view_field_relation">
                                                      0</span>
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
                                                <td colspan="2" class="view_field_box " id="inventory_19">
                                                    <h3>Add Inventory</h3>
                                                    <table>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <form method="post" action="<?= HOST_NAME.'pos/inventory_add/'.$item->item_o_id ?>">
                                                                    <table class="tab_columns">
                                                                    <tbody>
                                                                    <tr id="view_f_20">
                                                                        <td class="label">
                                                                            <label for="view_add_inventory1_quantity">Quantity</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <input type="text" autocomplete="off" size="6" maxlength="15" id="view_add_inventory1_quantity" name="add_inventory1_quantity" class="view_view number priority_auto_focus data_control" placeholder="Quantity" tabindex="200">
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_21">
                                                                        <td class="label">
                                                                            <label for="view_add_inventory1_cost">Cost</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <div class="money-field-container">
                                                                                <input type="text" autocomplete="off" maxlength="15" id="view_add_inventory1_cost" name="add_inventory1_cost" class="view_view money data_control" placeholder="Cost" tabindex="201" value="<?= $item->buy_price ?>">
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_22">
                                                                        <td class="label">
                                                                            <label for="view_add_inventory1_vendor">Vendor</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <select name="add_inventory1_vendor" id="view_add_inventory1_vendor" class="view_view data_control" tabindex="202">
                                                                                <option value="0">None</option>
                                                                                <?php if (isset($vendors) && $vendors !== false) : ?>
                                                                                    <?php foreach ($vendors as $vendor) : ?>
                                                                                        <option <?= $vendor->id == $item->vendor_id ? 'selected' : '' ?> value="<?= $vendor->id ?>"><?= $vendor->name ?></option>
                                                                                    <?php endforeach; ?>
                                                                                <?php endif; ?>
                                                                            </select>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_23">
                                                                        <td class="view_functions " colspan="2">
                                                                          <span class="function"><button type="submit" name="submit" title="Add Inventory" id="addInventoryButton" class="custom_function"><i class="fa fa-plus"></i> Add Inventory</button></span>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                                </form>
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
                                                <tbody>
                                                <tr id="view_f_26">
                                                    <td class="view_listing" colspan="2">
                                                        <h2 class="child-listing-title" id="item_listings_inventory_edit_view_title">Inventory Details</h2>
                                                        <div id="item_listings_inventory_edit_view" class="is_pannel">
                                                            <div class="listing">
                                                                <div id="work_item_listings_inventory_edit_view">
                                                                    <div id="item_listings_inventory_edit_view_single">
                                                                        <div class="container" style="max-width: 100%">
                                                                            <table class="">
                                                                                <thead>
                                                                                <tr>
                                                                                    <th class="th-number"><span title="Quantity">Quantity</span></th>
                                                                                    <th class="th-number"><span title="Remaining">Remaining</span></th>
                                                                                    <th class="th-string"><span title="Vendor">Vendor</span></th>
                                                                                    <th class="th-money"><span title="Unit Cost">Unit Cost</span></th>
                                                                                    <th class="th-money"><span title="Unit RRP">Unit RRP</span></th>
                                                                                    <th class="sorted th-date"><span title="Date">Date</span></th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                <?php $total_remaining_qty = $total_cost = $total_sale = 0; ?>
                                                                                <?php if (isset($inventory) && $inventory !== false) : ?>
                                                                                <?php foreach ($inventory as $inventory_record) : ?>
                                                                                    <?php $total_remaining_qty += $inventory_record->qoh ?>
                                                                                    <?php $total_cost += $inventory_record->qoh * $inventory_record->buy_price; ?>
                                                                                    <?php $total_sale += $inventory_record->qoh * $inventory_record->rrp_price; ?>
                                                                                        <tr class="inventory-line">
                                                                                            <td class="number"><?= $inventory_record->quantity ?></td>
                                                                                            <td class="number"><?= $inventory_record->qoh ?></td>
                                                                                            <td class="string "><?= $inventory_record->vendor_name ?: 'None' ?></td>
                                                                                            <td class="money ">$<?= number_format($inventory_record->buy_price, 2) ?></td>
                                                                                            <td class="money ">$<?= number_format($inventory_record->rrp_price, 2) ?></td>
                                                                                            <td class="date ">
                                                                                                <time datetime="<?= $inventory_record->created ?>"><?= \Framework\lib\Helper::ConvertDateFormat($inventory_record->created, true) ?></time>
                                                                                            </td>
                                                                                        </tr>
                                                                                <?php endforeach; ?>
                                                                                <?php endif; ?>
                                                                                </tbody>
                                                                            </table>
                                                                            <div class="info">
                                                                                <div class="form info_pannel">
                                                                                    <table cellspacing="2">
                                                                                        <tbody>
                                                                                        <tr>
                                                                                            <td class="info_pannel_column">
                                                                                                <table>
                                                                                                    <tbody>
                                                                                                    <tr class="odd">
                                                                                                        <td class="label">Total Remaining</td>
                                                                                                        <td class="info_field number"><?= $total_remaining_qty ?></td>
                                                                                                    </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                            <td class="info_pannel_column">
                                                                                                <table>
                                                                                                    <tbody>
                                                                                                    <tr class="odd">
                                                                                                        <td class="label">Cost</td>
                                                                                                        <td class="info_field money">$<?= number_format($total_cost, 2) ?></td>
                                                                                                    </tr>
                                                                                                    <tr class="even">
                                                                                                        <td class="label">Sale Value</td>
                                                                                                        <td class="info_field money">$<?= $total_sale ?></td>
                                                                                                    </tr>
                                                                                                    <tr class="odd">
                                                                                                        <td class="label">Margin</td>
                                                                                                        <td class="info_field percent">$<?= $total_sale && $total_cost ? number_format($total_sale - $total_cost, 2) : 0.00 ?> (<?= $total_sale && $total_cost ? substr((($total_sale - $total_cost) / $total_cost) * 100, 0, 5) : 0 ?>%)</td>
                                                                                                    </tr>
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
                <?php endif; ?>

                <article id="tabs-menu-sales-link" class="view_tab_sales tab tab-pane">
                    <div class="content">
                        <div class="view-columns">
                            <table class="view-layout set_auto_focus">
                                <tbody>
                                <tr>
                                    <td>
                                        <table class="view-column ">
                                            <tbody>
                                            <tr id="view_f_9">
                                                <td class="view_listing" colspan="2">
                                                    <h2 class="child-listing-title" id="reports_sales_listings_transaction_line_sales_view_title">Sales</h2>
                                                    <div id="reports_sales_listings_transaction_line_sales_view" class="is_pannel">
                                                        <div class="listing">
                                                            <div id="work_reports_sales_listings_transaction_line_sales_view">
                                                                <div id="reports_sales_listings_transaction_line_sales_view_single">
                                                                    <div class="container" style="max-width: 100%;padding-top: 10px">
                                                                        <table cellpadding="0" cellspacing="0" border="0" class="g-datatable datatable table table-striped table-bordered">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>ID</th>
                                                                                <th>DATE</th>
                                                                                <th>QTY</th>
                                                                                <th>RETAIL</th>
                                                                                <th>SUBTOTAL</th>
                                                                                <th>DISCOUNT</th>
                                                                                <th>TAX</th>
                                                                                <th>TOTAL</th>
                                                                                <th>CUSTOMER</th>
                                                                                <th>STATUS</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <?php if (isset($sales) && $sales !== false) : ?>
                                                                            <?php foreach ($sales as $sale) : ?>
                                                                            <tr style="" class="" id="reports_sales_listings_transaction_line_sales_view_r_<?= $sale->id ?>">
                                                                                <td class="string ">
                                                                                    <a title="Edit Record" href="<?= HOST_NAME.'pos/sale/'.$sale->id ?>"><span style="padding-right: 5px;"><i class="fa fa-search "></i></span><?= $sale->uid.' - #'.$sale->id ?></a>
                                                                                </td>
                                                                                <td id="cellSalesLinesDate_0" class="date ">
                                                                                    <time datetime="<?= $sale->created ?>" class=""><?= \Framework\lib\Helper::ConvertDateFormat($sale->created) ?></time>
                                                                                </td>
                                                                                <td id="cellSalesLinesQty_0" class="string "><?= $sale->quantity ?></td>
                                                                                <td id="cellSalesLinesRetail_0" class="prettymoney ">$<?= number_format($sale->original_price, 2) ?></td>
                                                                                <td id="cellSalesLinesSubtotal_0" class="prettymoney ">$<?= number_format($sale->original_price * $sale->quantity, 2) ?></td>
                                                                                <td id="cellSalesLinesDiscount_0" class="prettymoney ">$<?= number_format($sale->item_discount * $sale->quantity, 2) ?></td>
                                                                                <td id="cellSalesLinesTax_0" class="percent ">
                                                                                    <span><?= $sale->class.' ('.$sale->rate.'%)' ?></span>
                                                                                </td>
                                                                                <td id="cellSalesLinesTotal_0" class="prettymoney ">$<?= number_format($sale->item_total, 2) ?></td>
                                                                                <td id="cellSalesLinesCustomer_0" class="string ">
                                                                                    <a title="Edit Record" href="<?= $sale->customer_id ? HOST_NAME.'customers/customer/'.$sale->customer_id : '#' ?>"><span><?= $sale->customer_name ?: '' ?></span></a>
                                                                                </td>
                                                                                <td><?= $sale->sale_status ?></td>
                                                                            </tr>
                                                                            <?php endforeach; ?>
                                                                            <?php endif; ?>
                                                                            </tbody>
                                                                        </table>

                                                                        <?php if (isset($sales_totals) && !empty($sales_totals)) : ?>
                                                                        <div class="info">
                                                                            <div class="form info_pannel">
                                                                                <table cellspacing="2">
                                                                                    <tbody>
                                                                                    <tr valign="top">

                                                                                        <td class="info_pannel_column">
                                                                                            <table>
                                                                                                <tbody>
                                                                                                <tr class="odd" valign="top">
                                                                                                    <td class="label">Subtotal</td>
                                                                                                    <td id="infoPannelTotalsSubtotalValue" class="info_field prettymoney">$<?= number_format($sales_totals->original_sub_total, 2) ?></td>
                                                                                                </tr>
                                                                                                <tr class="even" valign="top">
                                                                                                    <td class="label">Discounts</td>
                                                                                                    <td id="infoPannelTotalsDiscountsValue" class="info_field prettymoney">$<?= number_format($sales_totals->discounts, 2) ?></td>
                                                                                                </tr>
                                                                                                <tr class="odd" valign="top">
                                                                                                    <td class="label">Subtotal w/ Discounts</td>
                                                                                                    <td id="infoPannelTotalsSubtotalWithDiscountValue" class="info_field prettymoney">$<?= number_format($sales_totals->sub_total, 2) ?></td>
                                                                                                </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </td>
                                                                                        <td class="info_pannel_column">
                                                                                            <table>
                                                                                                <tbody>
                                                                                                <tr class="odd" valign="top">
                                                                                                    <td class="label">Cost</td>
                                                                                                    <td id="infoPannelTotalsCostValue" class="info_field prettymoney">$<?= number_format($sales_totals->cost, 2) ?></td>
                                                                                                </tr>
                                                                                                <tr class="even" valign="top">
                                                                                                    <td class="label">Profit</td>
                                                                                                    <td id="infoPannelTotalsProfitValue" class="info_field prettymoney">$<?= number_format($sales_totals->profit, 2) ?></td>
                                                                                                </tr>
                                                                                                <tr class="odd" valign="top">
                                                                                                    <td class="label">Margin</td>
                                                                                                    <td id="infoPannelTotalsMarginValue" class="info_field prettymoney"><?= number_format($sales_totals->margin, 2) ?>%</td>
                                                                                                </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                        <?php endif; ?>
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

               <!-- <article id="tabs-menu-layaways-link" class="view_tab_layaways tab tab-pane">
                    <div class="content">
                        <i class="icon-refresh icon-spin"></i> Loading...
                    </div>
                </article>
                <article id="tabs-menu-workorders-link" class="view_tab_workorders tab tab-pane">
                    <div class="content">
                        <i class="icon-refresh icon-spin"></i> Loading...
                    </div>
                </article>
-->
                <article id="tabs-menu-customers-link" class="view_tab_customers tab tab-pane">
                    <div class="content">
                        <div class="view-columns">
                            <table class="view-layout set_auto_focus">
                                <tbody>
                                <tr>
                                    <td>
                                        <table class="view-column ">
                                            <tbody>
                                            <tr id="view_f_9">
                                                <td class="view_listing" colspan="2">
                                                    <h2 class="child-listing-title" id="reports_sales_listings_sales_customer_item_view_title">Customer Items</h2>
                                                    <div id="reports_sales_listings_sales_customer_item_view" class="is_pannel">
                                                        <div class="listing">
                                                            <div id="work_reports_sales_listings_sales_customer_item_view">
                                                                <div id="reports_sales_listings_sales_customer_item_view_single">
                                                                    <div class="container" style="max-width: 100%;padding-top: 10px;padding-bottom: 10px">
                                                                        <table cellpadding="0" cellspacing="0" border="0" class="g-datatable datatable table table-striped table-bordered">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>Customer</th>
                                                                                <th>Qty</th>
                                                                                <th>Subtotal</th>
                                                                                <th>Discounts</th>
                                                                                <th>Total</th>
                                                                                <th>Cost</th>
                                                                                <th>Profit</th>
                                                                                <th>Margin</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <?php if (isset($customers_items) && $customers_items != false) : ?>
                                                                            <?php foreach ($customers_items as $customer_item) : ?>
                                                                            <tr id="reports_sales_listings_sales_customer_item_view_r_0">
                                                                                <td id="cellCustomerSalesCustomer_0" class="string ">
                                                                                    <a title="Edit Record" href="<?= $customer_item['customer_id'] ? HOST_NAME.'customers/customer/'.$customer_item['customer_id'] : '#' ?>"><span><?= $customer_item['name'] ?: '' ?></span></a>
                                                                                </td>
                                                                                <td id="cellCustomerSalesQty_0" class="number "><?= $customer_item['qty'] ?></td>
                                                                                <td id="cellCustomerSalesSubtotal_0" class="money ">$<?= number_format($customer_item['original_subtotal'], 2) ?></td>
                                                                                <td id="cellCustomerSalesDiscounts_0" class="money ">$<?= number_format($customer_item['discounts'], 2) ?></td>
                                                                                <td id="cellCustomerSalesTotal_0" class="money ">$<?= number_format($customer_item['subtotal'], 2) ?></td>
                                                                                <td id="cellCustomerSalesCost_0" class="money ">$<?= number_format($customer_item['cost'], 2) ?></td></td>
                                                                                <td id="cellCustomerSalesProfit_0" class="money ">$<?= number_format($customer_item['subtotal'] - $customer_item['cost'], 2) ?></td>
                                                                                <td id="cellCustomerSalesMargin_0" class="percent "><?= substr((($customer_item['subtotal'] - $customer_item['cost']) / $customer_item['cost']) * 100, 0, 5) ?>%</td>
                                                                            </tr>
                                                                            <?php endforeach; ?>
                                                                            <?php endif; ?>
                                                                            </tbody>
                                                                        </table>

                                                                        <?php if (isset($customers_totals) && !empty($customers_totals)) : ?>
                                                                            <div class="info">
                                                                                <div class="form info_pannel">
                                                                                    <table cellspacing="2">
                                                                                        <tbody>
                                                                                        <tr valign="top">
                                                                                            <td class="info_pannel_column">
                                                                                                <table>
                                                                                                    <tbody>
                                                                                                    <tr class="odd" valign="top">
                                                                                                        <td class="label">Subtotal</td>
                                                                                                        <td id="infoPannelTotalsSubtotalValue" class="info_field prettymoney">$<?= number_format($customers_totals->original_sub_total, 2) ?></td>
                                                                                                    </tr>
                                                                                                    <tr class="even" valign="top">
                                                                                                        <td class="label">Discounts</td>
                                                                                                        <td id="infoPannelTotalsDiscountsValue" class="info_field prettymoney">$<?= number_format($customers_totals->discounts, 2) ?></td>
                                                                                                    </tr>
                                                                                                    <tr class="odd" valign="top">
                                                                                                        <td class="label">Subtotal w/ Discounts</td>
                                                                                                        <td id="infoPannelTotalsSubtotalWithDiscountValue" class="info_field prettymoney">$<?= number_format($customers_totals->sub_total, 2) ?></td>
                                                                                                    </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                            <td class="info_pannel_column">
                                                                                                <table>
                                                                                                    <tbody>
                                                                                                    <tr class="odd" valign="top">
                                                                                                        <td class="label">Cost</td>
                                                                                                        <td id="infoPannelTotalsCostValue" class="info_field prettymoney">$<?= number_format($customers_totals->cost, 2) ?></td>
                                                                                                    </tr>
                                                                                                    <tr class="even" valign="top">
                                                                                                        <td class="label">Profit</td>
                                                                                                        <td id="infoPannelTotalsProfitValue" class="info_field prettymoney">$<?= number_format($customers_totals->profit, 2) ?></td>
                                                                                                    </tr>
                                                                                                    <tr class="odd" valign="top">
                                                                                                        <td class="label">Margin</td>
                                                                                                        <td id="infoPannelTotalsMarginValue" class="info_field percent"><?= number_format($customers_totals->margin, 2) ?>%</td>
                                                                                                    </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                        </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        <?php endif; ?>
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

                <article id="tabs-menu-purchases-link" class="view_tab_purchases tab tab-pane">
                    <div class="content">
                        <div class="view-columns">
                            <table class="view-layout set_auto_focus">
                                <tbody>
                                <tr>
                                    <td>
                                        <table class="view-column ">
                                            <tbody>
                                            <tr id="view_f_9">
                                                <td class="view_listing" colspan="2">
                                                    <h2 class="child-listing-title" id="reports_inventory_listings_inventory_purchases_view_title">
                                                        Inventory Purchase Orders
                                                    </h2>
                                                    <div id="reports_inventory_listings_inventory_purchases_view" class="is_pannel">
                                                        <div class="listing">
                                                            <div id="work_reports_inventory_listings_inventory_purchases_view">
                                                                <div id="reports_inventory_listings_inventory_purchases_view_single">
                                                                    <div class="container" style="max-width: 100%;padding-top: 10px">
                                                                        <table cellpadding="0" cellspacing="0" border="0" class="g-datatable datatable table table-striped table-bordered table-status-colors">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>ID</th>
                                                                                <th>STATUS</th>
                                                                                <th>VENDOR</th>
                                                                                <th>NOTE</th>
                                                                                <th>CREATED</th>
                                                                                <th>ORDERED</th>
                                                                                <th>EXPECTED</th>
                                                                                <th># ORDERED</th>
                                                                                <th># RECEIVED</th>
                                                                                <th>TOTAL</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <?php if (isset($purchase_orders) && $purchase_orders !== false) : ?>
                                                                                <?php foreach ($purchase_orders as $purchase_order) : ?>
                                                                                    <?php $order_item_status = 'status-grey'; ?>
                                                                                    <?php if ($purchase_order->status == 'finished') : $order_item_status = 'status-orange'; ?>
                                                                                    <?php elseif ($purchase_order->status == 'checkin') : $order_item_status = 'status-green'; ?>
                                                                                    <?php elseif ($purchase_order->status == 'archived') : $order_item_status = 'status-red'; ?>
                                                                                    <?php endif; ?>

                                                                                    <tr class="gradeX <?= $order_item_status ?>">
                                                                                        <td><a href="<?= HOST_NAME . 'pos/purchase_order/' . $purchase_order->id ?>" target="_blank"><?= $purchase_order->id ?></a></td>

                                                                                        <td class="string  nowrap">
                                                                                            <span class="status-label"><a href="<?= HOST_NAME . 'pos/purchase_order/' . $purchase_order->id ?>"><?= strtoupper($purchase_order->status) ?></a></span>
                                                                                        </td>

                                                                                        <td><?= $purchase_order->vendor_name ?></td>
                                                                                        <td><?= substr($purchase_order->general_notes, 0, 80) ?>..</td>
                                                                                        <td class="date ">
                                                                                            <time datetime="<?= $purchase_order->created ?>"><?= \Framework\lib\Helper::ConvertDateFormat($purchase_order->created, true) ?></time>
                                                                                        </td>
                                                                                        <td class="date ">
                                                                                            <time datetime="<?= $purchase_order->ordered ?>"><?= \Framework\lib\Helper::ConvertDateFormat($purchase_order->ordered) ?></time>
                                                                                        </td>
                                                                                        <td class="date ">
                                                                                            <time datetime="<?= $purchase_order->expected ?>"><?= \Framework\lib\Helper::ConvertDateFormat($purchase_order->expected) ?></time>
                                                                                        </td>
                                                                                        <td><?= $purchase_order->total_ordered ?: 0 ?></td>
                                                                                        <td><?= $purchase_order->total_received ?: 0 ?></td>
                                                                                        <td>$<?= number_format($purchase_order->order_total, 2) ?></td>
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

                <article id="tabs-menu-returned-link" class="view_tab_returned tab tab-pane">
                    <div class="content">
                        <div class="view-columns">
                            <table class="view-layout set_auto_focus">
                                <tbody>
                                <tr>
                                    <td>
                                        <table class="view-column ">
                                            <tbody>
                                            <tr id="view_f_9">
                                                <td class="view_listing" colspan="2">
                                                    <h2 class="child-listing-title" id="reports_inventory_listings_inventory_returned_view_title">
                                                        Inventory Vendor Returns
                                                    </h2>
                                                    <div id="reports_inventory_listings_inventory_returned_view" class="is_pannel">
                                                        <div class="listing">
                                                            <div id="work_reports_inventory_listings_inventory_returned_view">
                                                                <div id="reports_inventory_listings_inventory_returned_view_single">
                                                                    <div class="container" style="max-width: 100%;padding-top: 10px">
                                                                        <table cellpadding="0" cellspacing="0" border="0" class="g-datatable datatable table table-striped table-bordered table-status-colors">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>ID</th>
                                                                                <th>REF #</th>
                                                                                <th>STATUS</th>
                                                                                <th>VENDOR</th>
                                                                                <th>QTY.</th>
                                                                                <th>SENT</th>
                                                                                <th>TOTAL COST</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <?php $total_returned = $total_returned_cost = 0; ?>
                                                                            <?php if (isset($vendor_returns) && $vendor_returns !== false) : ?>
                                                                                <?php foreach ($vendor_returns as $vendor_return) : ?>
                                                                                    <?php $total_returned += $vendor_return->total_returned; ?>
                                                                                    <?php $total_returned_cost += $vendor_return->total; ?>

                                                                                    <?php $order_item_status = 'status-grey'; ?>
                                                                                    <?php if ($vendor_return->status == 'sent') : $order_item_status = 'status-orange'; ?>
                                                                                    <?php elseif ($vendor_return->status == 'closed') : $order_item_status = 'status-green'; ?>
                                                                                    <?php elseif ($vendor_return->status == 'archived') : $order_item_status = 'status-red'; ?>
                                                                                    <?php endif; ?>

                                                                                    <tr class="gradeX <?= $order_item_status ?>">
                                                                                        <td><a target="_blank" href="<?= HOST_NAME.'pos/vendor_return/'.$vendor_return->id ?>"><?= $vendor_return->id ?></a></td>
                                                                                        <td class="string  nowrap">#<?= $vendor_return->reference ?></td>
                                                                                        <td class="string  nowrap">
                                                                                            <span class="status-label"><a href="<?= HOST_NAME.'pos/vendor_return/'.$vendor_return->id ?>"><?= strtoupper($vendor_return->status) ?></a></span>
                                                                                        </td>
                                                                                        <td><?= $vendor_return->vendor_name ?></td>
                                                                                        <td class="string  nowrap"><?= $vendor_return->total_returned ?></td>
                                                                                        <td class="date ">
                                                                                            <time datetime="<?= $vendor_return->sending_date ?>"><?= \Framework\lib\Helper::ConvertDateFormat($vendor_return->sending_date, true) ?></time>
                                                                                        </td>
                                                                                        <td>$<?= number_format($vendor_return->total, 2) ?></td>
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
                                                                                                <tr class="odd">
                                                                                                    <td class="label">Cost</td>
                                                                                                    <td class="info_field money">$<?= number_format($total_returned_cost, 2) ?></td>
                                                                                                </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </td>
                                                                                        <td class="info_pannel_column">
                                                                                            <table>
                                                                                                <tbody>
                                                                                                <tr class="odd">
                                                                                                    <td class="label">Returned</td>
                                                                                                    <td class="info_field string"><?= $total_returned ?></td>
                                                                                                </tr>
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

                <article id="tabs-menu-history-link" class="view_tab_history tab tab-pane">
                    <div class="content">
                        <div class="view-columns">
                            <table class="view-layout set_auto_focus">
                                <tbody>
                                <tr>
                                    <td>
                                        <table class="view-column ">
                                            <tbody>
                                            <tr id="view_f_9">
                                                <td class="view_listing" colspan="2">
                                                    <h2 class="child-listing-title" id="reports_inventory_listings_inventory_logs_view_title">
                                                        Inventory Logs
                                                    </h2>
                                                    <div id="reports_inventory_listings_inventory_logs_view" class="is_pannel">
                                                        <div class="listing">
                                                            <div id="work_reports_inventory_listings_inventory_logs_view">
                                                                <div id="reports_inventory_listings_inventory_logs_view_single">
                                                                    <div class="container" style="max-width: 100%">
                                                                        <table cellpadding="0" cellspacing="0" border="0" class="g-datatable datatable table table-striped table-bordered">
                                                                            <thead>
                                                                            <tr>
                                                                                <th class="th-number">#</th>
                                                                                <th class="th-number">Change in Qty</th>
                                                                                <th class="th-string">Reason</th>
                                                                                <th class="th-string">Source</th>
                                                                                <th class="th-datetime">Date/Time</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <?php if (isset($inventory_movements) && $inventory_movements !== false) : ?>
                                                                            <?php foreach ($inventory_movements as $inventory_movement) : ?>
                                                                                <tr>
                                                                                    <td class="number">#<?= $inventory_movement->id ?></td>
                                                                                    <td class="number"><?= $inventory_movement->quantity ?></td>
                                                                                    <td class="string"><?= ucwords($inventory_movement->type) ?></td>
                                                                                    <td class="string"><a target="_blank" href="<?= $inventory_movement->source ? HOST_NAME.$inventory_movement->source : '#' ?>"><?= ucwords($inventory_movement->type) ?></a></td>
                                                                                    <td class="date"><?= \Framework\lib\Helper::ConvertDateFormat($inventory_movement->created, true) ?></td>
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


                                            <tr id="view_f_10">
                                                <td class="view_listing" colspan="2">
                                                    <h2 class="child-listing-title" id="reports_inventory_listings_all_inventory_view_title">
                                                        Inventory Lots
                                                    </h2>
                                                    <div id="reports_inventory_listings_all_inventory_view" class="is_pannel">
                                                        <div class="listing">
                                                            <div id="work_reports_inventory_listings_all_inventory_view">
                                                                <div id="reports_inventory_listings_all_inventory_view_single">
                                                                    <div class="container" style="max-width: 100%">
                                                                        <table cellpadding="0" cellspacing="0" border="0" class="g-datatable datatable table table-striped table-bordered">
                                                                            <thead>
                                                                            <tr>
                                                                                <th class="th-string">Inventory ID</th>
                                                                                <th class="th-number">Quantity</th>
                                                                                <th class="th-number">Remaining</th>
                                                                                <th class="th-money">Cost/Unit</th>
                                                                                <th class="th-money">Retail/Unit</th>
                                                                                <th class="th-shortdatetime">Created</th>
                                                                                <th class="th-string">Vendor</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <?php if (isset($inventory) && $inventory !== false) : ?>
                                                                                <?php foreach ($inventory as $inventory_lot) : ?>
                                                                                    <tr>
                                                                                        <td class="string">#<?= $inventory_lot->id ?></td>
                                                                                        <td class="number"><?= $inventory_lot->quantity ?></td>
                                                                                        <td class="number"><?= $inventory_lot->qoh ?></td>
                                                                                        <td class="money">$<?= number_format($inventory_lot->buy_price, 2) ?></td>
                                                                                        <td class="money">$<?= number_format($inventory_lot->rrp_price, 2) ?></td>
                                                                                        <td class="date"><?= \Framework\lib\Helper::ConvertDateFormat($inventory_lot->created, true) ?></td>
                                                                                        <td class="string"><?= $inventory_lot->vendor_name ?></td>
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
            </div>
        </div>
    </div>
</div>
<style>
    .select2 {margin-top: 5px}
    .select2-selection {padding: 0 !important;}
    .select2 .selection {padding: 0 !important;}
    .select2-container {padding: 0 !important; width: 80% !important;}
    .select2-selection__rendered {display: block !important}
    .select2-container .select2-selection--single {height: unset}
    .select2-container--default .select2-selection--single .select2-selection__rendered {padding: 4px 12px}
    .select2-container--default .select2-selection--multiple .select2-selection__rendered {padding: 0 !important;}
    .dropdown-wrapper {display: block;padding-block-end: 0;padding-block-start: 0;padding: 0 !important;}
    /*.select2-container--default .select2-selection--multiple .select2-selection__rendered li {margin: 0 !important;}*/

    .select2-container--default .select2-selection--multiple .select2-selection__choice {margin: 2px 10px 0 -5px !important;
        background-color: #e9eaeb !important;border-color: #e4e4e4 !important;}

    .dataTables_wrapper .row {padding: 0}
</style>
<script>
    $(document).ready(function () {
        $('#select2-tags').select2({
            tags: true,
            tokenSeparators: [',', ' ']
        });
        $('#select2-departments').select2();
    });
</script>
<?php endif; ?>