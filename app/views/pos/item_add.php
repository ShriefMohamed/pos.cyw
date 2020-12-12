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

    .select2-container--default .select2-selection--multiple .select2-selection__choice {margin: 0 10px 0 -5px !important;
        background-color: #e9eaeb !important;border-color: #e4e4e4 !important;}
</style>

<div id="view" data-test="view-wrapper" style="display: block;">
    <div class="view">
        <form method="post">
            <div class="functions">
                <button title="Save Item" class="save" type="submit" name="submit">Save Item</button>
            </div>
            <div class="main">
                <div class="tabs no_tab_selector">
                    <article class="view_tab_details tab loaded">
                        <div class="content">
                            <div class="view-columns">
                                <table class="view-layout set_auto_focus">
                                    <tbody>
                                    <tr>
                                        <td colspan="3">
                                            <div>
                                                <table class="tab_columns">
                                                    <tbody>
                                                    <tr id="view_f_11">
                                                        <td class="form_field_holder ">
                                                            <div class="new-item-spacer-div">
                                                                <div class="new-item-spacer-text">Create New Item</div>
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

                                    <tr>
                                        <td colspan="3">
                                            <div class="view_group">
                                                <input type="text" autocomplete="off" size="40" maxlength="255" id="view_item" name="item" class="view_view string data_control" placeholder="Item" tabindex="1">
                                                <input type="text" autocomplete="off" size="40" maxlength="255" id="view_description" name="description" class="view_view string data_control" placeholder="Description" tabindex="2">

                                                <label class="field_label flat-field-input" for="view_function__item_type">Type</label>
                                                <select name="item_type" id="view_function__item_type" class="view_view data_control" tabindex="3">
                                                    <option value="single" selected="selected">Single</option>
                                                    <option value="box">Box</option>
                                                    <option value="assembly">Assembly</option>
                                                    <option value="non_inventory">Non-Inventory</option>
                                                </select>

                                                <label class="field_label flat-field-input" for="view_function__serialized">Serialized</label>
                                                <input type="checkbox" name="serialized" id="view_function__serialized" class="view_view boolean data_control" tabindex="4">
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <table class="tab_columns">
                                                <tbody>
                                                <tr class="view_field_box">
                                                    <td colspan="2" class="view_field_box " id="details_18">
                                                        <h3>Add Inventory</h3>
                                                        <table>
                                                            <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table class="tab_columns">
                                                                        <tbody>
                                                                        <tr id="view_f_19">
                                                                            <td class="label">
                                                                                <label for="view_add_quantity">Add Qty.</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <input type="text" autocomplete="off" value="0" size="6" maxlength="15" id="view_add_quantity" name="quantity" class="view_view number priority_auto_focus data_control" placeholder="Add Qty." tabindex="5" required>
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
                                                        <h3>IDs</h3>
                                                        <table>
                                                            <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table class="tab_columns">
                                                                        <tbody>
                                                                        <tr id="view_f_26">
                                                                            <td class="label">
                                                                                <label for="view_shop_sku">Custom SKU</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <input type="text" autocomplete="off" size="14" maxlength="255" id="view_shop_sku" name="shop_sku" class="view_view string scanner-no-submit data_control" placeholder="Custom SKU" tabindex="7">
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_27">
                                                                            <td class="label">
                                                                                <label for="view_man_sku">Manufact. SKU</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <input type="text" autocomplete="off" size="14" maxlength="255" id="view_man_sku" name="man_sku" class="view_view string scanner-no-submit data_control" placeholder="Manufact. SKU" tabindex="8">
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
                                                    <td colspan="2" class="view_field_box " id="details_29">
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
                                                                                <select id="select2-departments" class="form-control" name="department">
                                                                                    <option value="0">None</option>
                                                                                </select>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_32">
                                                                            <td class="label">
                                                                                <label for="select2-departments">Department Code</label>
                                                                            </td>
                                                                            <td class="form_field_holder " style="padding-bottom: 10px">
                                                                                <input type="text" autocomplete="off" size="14" maxlength="255" name="department_code" class="view_view string scanner-no-submit data_control" placeholder="Department Code" tabindex="8">
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_31">
                                                                            <td class="label">
                                                                                <label for="view_account_id">Xero Account</label>
                                                                            </td>
                                                                            <td class="form_field_holder form-group">
                                                                                <select name="xero_account" id="view_account_id" class="view_view data_control form-control form-round" tabindex="9" style="width: 80%">
                                                                                    <option value="0">None</option>
                                                                                    <?php if (isset($xero_accounts) && $xero_accounts !== false) : ?>
                                                                                        <?php foreach ($xero_accounts as $xero_account) : ?>
                                                                                            <option value="<?= $xero_account->id.'|||'.$xero_account->Code ?>"><?= $xero_account->Name.'  ('.$xero_account->Code.')' ?></option>
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
                                                                                        <option value="<?= $category->id ?>"><?= $category->category ?></option>
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
                                                                                            <option value="<?= $brand->id ?>"><?= $brand->brand ?></option>
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

                                        <td style="width: 40%">
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
                                                                                    <input type="text" autocomplete="off" value="0.00" size="" maxlength="15" id="view_default_cost" name="buy_price" class="view_view money data_control" placeholder="Dealer Buy Price Inc. GST" tabindex="40" required>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_41">
                                                                            <td class="label">
                                                                                <label for="view_default_percentage">RRP Percentage</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <div class="percent-field-container">
                                                                                    <input type="text" autocomplete="off" value="30" maxlength="15" id="view_default_percentage" name="rrp_percentage" class="view_view money data_control" placeholder="RRP Percentage" tabindex="41" required>
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
                                                                                            <option value="<?= $vendor->id ?>"><?= $vendor->name ?></option>
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
                                                            <tr class="pricing-level pricing-level-<?= $pricing_level->id ?> <?= strtolower($pricing_level->teir) == ('teir 2')? "teir2" : '' ?>" data-rate="<?= $pricing_level->rate ?>">
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
                                                                    <input tabindex="44" id="view_price_rrp" type="text" class="money pricelevel" value="0.00" name="rrp_price" required>
                                                                </td>
                                                                <td class="margin"></td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                        <div class="price-settings">
                                                            <div class="price-setting">
                                                                <label class="checkbox">
                                                                    <input type="checkbox" id="discountable_checkbox" name="discountable" class="boolean" checked> Discounts Allowed
                                                                </label>
                                                            </div>
                                                            <div class="price-setting">
                                                                <label for="class_id" class="select">Tax Class</label>
                                                                <select name="tax_class" id="tax_class_dropdown" tabindex="45">
                                                                    <option value="0">None</option>
                                                                    <?php if (isset($tax_classes) && $tax_classes !== false) : ?>
                                                                        <?php foreach ($tax_classes as $tax_class) : ?>
                                                                            <option value="<?= $tax_class->id ?>"><?= $tax_class->class.' ('.$tax_class->rate.'%)' ?></option>
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
                                                                                <input type="checkbox" id="view_function__reorder_btn" name="auto_reorder" class="view_view number data_control" checked>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_45">
                                                                            <td class="label">
                                                                                <label for="view_function__reorder_pnt">Reorder Point</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <input type="text" autocomplete="off" value="0" size="6" maxlength="15" id="view_function__reorder_pnt" name="reorder_point" class="view_view number data_control" placeholder="Reorder Point" tabindex="42">
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_46">
                                                                            <td class="label">
                                                                                <label for="view_function__reorder_lvl">Desired Inventory Level</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <input type="text" autocomplete="off" value="0" size="6" maxlength="15" id="view_function__reorder_lvl" name="reorder_level" class="view_view number data_control" tabindex="43">
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
        </form>
    </div>
</div>


<script>
    $(document).ready(function () {
        var tags = [
            <?php if (isset($tags) && $tags !== false) : ?>
            <?php foreach ($tags as $tag) : ?>
            "<?= $tag ?>",
            <?php endforeach; ?>
            <?php endif; ?>
        ];
        var departments = [
            <?php if (isset($departments) && $departments !== false) : ?>
            <?php foreach ($departments as $department) : ?>
            "<?= $department ?>",
            <?php endforeach; ?>
            <?php endif; ?>
        ];
        $('#select2-tags').select2({
            tags: tags,
            tokenSeparators: [',', ' ']
        });
        $('#select2-departments').select2({
            tags: departments,
            tokenSeparators: [',', ' ']
        });
    });
</script>