<div class="page-section">
    <section class="card card-fluid">
        <div class="listing">
            <header style="margin-bottom: 2rem;">
                <header class="card-header">
                    Items

                    <a href="<?= HOST_NAME ?>pos/item_add" class="btn btn-success" style="float: right">Add New Item</a>
                </header>
                <form class="search no-print" id="listing_search">
                    <div class="advanced has_checkboxes" id="listing_advanced_search" style="">
                        <ul class="search-fields search-checkboxes">
                            <li>
                                <label><input type="checkbox" name="qoh_positive" id="listing_qoh_positive" class=" listing_search boolean data_control"> Items w/ Inventory</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="qoh_zero" id="listing_qoh_zero" class=" listing_search boolean data_control"> Items w/o Inventory</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="archived" id="listing_archived" class=" listing_search boolean data_control"> Archived</label>
                            </li>
                        </ul>
                        <ul class="search-fields search-refinements">
                            <li>
                                <label for="listing_category_id">Category</label>
                                <select name="category_id" id="listing_category_id" class=" listing_search data_control" tabindex="auto_tabindex">
                                    <option value="-1">All Categories</option>
                                    <?php if (isset($categories) && $categories !== false) : ?>
                                        <?php foreach ($categories as $category) : ?>
                                            <option value="<?= $category->id ?>"><?= $category->category ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </li>
                            <li>
                                <label for="listing_manufacturer_id">Brand</label>
                                <select name="manufacturer_id" id="listing_manufacturer_id" class=" listing_search data_control" tabindex="auto_tabindex">
                                    <option value="-1">All Brands</option>
                                    <?php if (isset($brands) && $brands !== false) : ?>
                                        <?php foreach ($brands as $brand) : ?>
                                            <option value="<?= $brand->id ?>"><?= $brand->brand ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </li>
                            <li>
                                <label for="listing_vendor_id">Vendor</label>
                                <select name="vendor_id" id="listing_vendor_id" class=" listing_search data_control" tabindex="auto_tabindex">
                                    <option value="-1">All Default Vendors</option>
                                    <?php if (isset($vendors) && $vendors !== false) : ?>
                                        <?php foreach ($vendors as $vendor) : ?>
                                            <option value="<?= $vendor->id ?>"><?= $vendor->name ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </li>
                            <li>
                                <label for="listing_department_id">Department</label>
                                <select name="department_id" id="listing_department_id" class=" listing_search data_control" tabindex="auto_tabindex">
                                    <option value="-1">All Departments</option>
                                    <?php if (isset($departments) && $departments !== false) : ?>
                                        <?php foreach ($departments as $department) : ?>
                                            <option value="<?= $department ?>"><?= $department ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </li>
                            <li>
                                <label for="listing_item_type">Item Type</label>
                                <select name="item_type" id="listing_item_type" class=" listing_search data_control" tabindex="auto_tabindex">
                                    <option value="-1">All Item Types</option>
                                    <optgroup label="Inventory">
                                        <option value="single">Single</option>
                                        <option value="box">Box</option>
                                        <option value="assembly">Assembly</option>
                                    </optgroup>
                                    <option value="non_inventory">Non-Inventory</option>
                                </select>
                            </li>
                            <li>
                                <label for="listing_serialized">Serialized</label>
                                <select name="serialized" id="listing_serialized" class=" listing_search data_control" tabindex="auto_tabindex">
                                    <option value="-1">Include Serialized</option>
                                    <option value="1">Serialized Only</option>
                                    <option value="0">Exclude Serialized</option>
                                </select>
                            </li>
                            <li>
                                <label for="listing_search_class_id">Tax Class</label>
                                <select name="search_class_id" id="listing_search_class_id" class=" listing_search data_control" tabindex="auto_tabindex">
                                    <option value="-1">All Tax Classes</option>
                                    <?php if (isset($tax_classes) && $tax_classes !== false) : ?>
                                        <?php foreach ($tax_classes as $tax_class) : ?>
                                            <option value="<?= $tax_class->id ?>"><?= $tax_class->class.' ('.$tax_class->rate.'%)' ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </li>
                        </ul>
                    </div>
                </form>
            </header>
            <div id="work_listing">
                <div id="listing_loc_matches">
                    <div class="container" style="max-width: 100%">
                        <?php if (isset($data) && $data !== false) : ?>
                        <table cellpadding="0" cellspacing="0" border="0" class="default-datatable datatable table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th width="15%">Item UID</th>
                                <th>Item</th>
                                <th>Type</th>
                                <th>Shop SKU</th>
                                <th>Department</th>
                                <th>Brand</th>
                                <th>Category</th>
                                <th>Tax</th>
                                <th style="width: 5%">Available QTY</th>
                                <th style="width: 5%;">AVG Retail Price</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (isset($data->data) && $data->data !== false) : ?>
                                <?php foreach ($data->data as $item) : ?>
                                    <tr class="gradeX">
                                        <td><a href="<?= HOST_NAME . 'pos/item/' . $item->id ?>">#<?= $item->uid ?></a></td>
                                        <td><a href="<?= HOST_NAME . 'pos/item/' . $item->id ?>"><?= $item->item ?></a></td>
                                        <td><?= $item->item_type ?></td>

                                        <td><?= $item->shop_sku ?></td>
                                        <td><?= $item->department ?></td>
                                        <td><?= $item->brand_name ?></td>
                                        <td><?= $item->category_name ?></td>
                                        <td><?= $item->rate ? $item->class.' ('.$item->rate.'%)' : 'None' ?></td>
                                        <td style="width: 5%"><?= $item->available_stock ?></td>
                                        <td>$<?= $item->items_avg_price ? number_format(substr($item->items_avg_price, 0, 5), 2) : 0 ?></td>

                                        <td class="center">
                                            <a href="#" title="Archive" class="archive-btn" data-id="<?= $item->id ?>" data-function="item"><i class="fa fa-trash"></i></a>
                                            <a href="#" title="Print Label"><i class="fa fa-print"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>

                        <?= $data->pagination ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .page-inner {padding-right: 0;padding-left: 0;}
    .btn-success {color: #589141;background: #dbebd6;border-radius: 0;border: solid 1px #CCC}
    .btn-success:hover {background: #bddab4;color: #436e31;border-color: #6f6f6f;}
</style>