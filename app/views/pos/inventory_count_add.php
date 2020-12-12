<div id="view" style="display: block;">
    <div class="view">
        <form method="post">
            <div class="functions">
                <button type="submit" name="save" title="Save Changes" id="saveButton" class="save">Save Changes</button>
            </div>
            <div class="main">
                <div class="tabs no_tab_selector">
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
                                                                                <input type="text" autocomplete="off" value="" size="30" maxlength="255" id="view_name" name="name" class="view_view string data_control" placeholder="Name" tabindex="200" required>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_5">
                                                                            <td class="label">
                                                                                <label for="view_name">Created By</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <span><?= \Framework\Lib\Session::Get('loggedin')->lastName ?></span>
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
                                                                                        <th>Should Have</th>
                                                                                        <th>Counted</th>
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
