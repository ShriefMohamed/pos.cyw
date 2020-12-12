<div id="view" class="is_pannel" data-test="view-wrapper" style="display: block;">
    <div class="view">
        <form method="post">
            <div class="functions">
                <button title="Save Changes" id="saveButton" class="save" name="save" type="submit">Save Changes</button>
                <button title="Check In" class="nextaction custom_function" name="check-in" type="submit">Check In</button>
                <button title="Archive" class="archive supplementary" name="archive" type="submit">Archive</button>
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
                                                                                        <option value="<?= $vendor->id ?>"><?= $vendor->name ?></option>
                                                                                    <?php endforeach; ?>
                                                                                <?php endif; ?>
                                                                            </select>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_19">
                                                                        <td class="label">
                                                                            <label for="view_ref_num">Reference #</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <input type="text" autocomplete="off" size="14" maxlength="255" id="view_ref_num" name="ref_num" class="view_view string data_control" tabindex="202">
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_20">
                                                                        <td class="label">
                                                                            <label for="view_ordered">Ordered Date</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <div class="date-wrapper"><input type="text" id="view_ordered" name="ordered" size="10" autocomplete="off" maxlength="10" class="view_view date savedx data_control" placeholder="Order Date" tabindex="203"></div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_21">
                                                                        <td class="label">
                                                                            <label for="view_arrival_date">Expected</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <div class="date-wrapper"><input type="text" id="view_arrival_date" name="arrival_date" size="10" autocomplete="off" maxlength="10" class="view_view date savedx data_control" placeholder="Expected Date" tabindex="204"></div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_22">
                                                                        <td class="label">
                                                                            <label for="view_ship_instructions">Shipping Note</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <input type="text" autocomplete="off" value="" size="30" maxlength="255" id="view_ship_instructions" name="ship_instructions" class="view_view string data_control" placeholder="Shipping Note" tabindex="205">
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
                                                                                        <textarea rows="5" cols="40" id="view_note_id_note_text" name="general_notes" class="view_view textarea data_control" tabindex="206"></textarea>
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
                                                    <table>
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
                                                                        <div class="container" style="max-width:100%;padding-top: 15px;">
                                                                            <table cellpadding="0" cellspacing="0" border="0" class="datatable table table-striped table-bordered">
                                                                                <thead>
                                                                                <tr>
                                                                                    <th>&nbsp;</th>
                                                                                    <th>&nbsp;</th>
                                                                                    <th>ITEM</th>
                                                                                    <th>BUY PRICE</th>
                                                                                    <th>RETAIL PRICE</th>
                                                                                    <th>QTY. ON HAND</th>
                                                                                    <th>ORDER QTY.</th>
                                                                                    <th>TOTAL</th>
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
    .listing table .string,
    .listing table .group {text-align: center}
    .listing table thead th:last-child,
    .listing table tbody td:last-child {text-align: right !important;}
</style>
<script>
    $(document).ready(function () {
        $('.date').flatpickr({format: 'd-m-Y'});
    });
</script>
