<?php if (isset($customer) && $customer !== false) : ?>
<div id="view" data-test="view-wrapper" style="display: block;">
        <div class="view">
            <div class="functions">
                <button title="Save Changes" class="save" type="submit">Save Changes</button>
                <button title="Archive" class="archive supplementary" >Archive</button>
            </div>

            <div class="main">
                <form method="post" action="<?= HOST_NAME.'customers/customer_update/'.$customer->id ?>" id="customer-details-form">
                    <input type="hidden" name="customer_id" value="<?= $customer->customer_id ?>">

                    <div class="tabs tab-content">
                        <ul id="tabsMenu" class="tab-labels nav nav-tabs">
                            <li class="details nav-item"><a id="menuDetails" class="nav-link active" data-form="customer-details" href="#tabs-menu-details-link" data-toggle="tab">Details</a></li>

                            <li class="sales nav-item"><a id="menuSales" class="nav-link" href="#tabs-menu-sales-link" data-toggle="tab">Sales</a></li>
                            <li class="layaways nav-item "><a id="menuLayaways" class="nav-link" href="#tabs-menu-layaways-link" data-toggle="tab">Layaways</a></li>
                            <li class="special_orders nav-item"><a id="menuSpecialOrders" class="nav-link" href="#tabs-menu-special_orders-link" data-toggle="tab">Special Orders</a></li>
                            <li class="quotes nav-item"><a id="menuQuotes" class="nav-link" href="#tabs-menu-quotes-link" data-toggle="tab">Quotes</a></li>
                            <li class="payments nav-item"><a id="menuPayments" class="nav-link" href="#tabs-menu-payments-link" data-toggle="tab">Payments</a></li>
                            <li class="account nav-item"><a id="menuAccount" class="nav-link" data-form="customer-account" href="#tabs-menu-account-link" data-toggle="tab">Account</a></li>
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
                                                            <td colspan="2" class="view_field_box " id="details_8">
                                                                <h3>Details</h3>
                                                                <table>
                                                                    <tbody>
                                                                    <tr>
                                                                        <td>
                                                                            <table class="tab_columns">
                                                                                <tbody>
                                                                                <tr id="view_f_8">
                                                                                    <td class="label">
                                                                                        <label for="view_create_time">Created</label>
                                                                                    </td>
                                                                                    <td class="form_field_holder ">
                                                                                        <span id="view_create_time" style="width: auto; display: inline;" class="view_field_relation" ><?= \Framework\lib\Helper::ConvertDateFormat($customer->created, true) ?></span>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr id="view_f_9">
                                                                                    <td class="label">
                                                                                        <label for="view_f_name">First Name</label>
                                                                                    </td>
                                                                                    <td class="form_field_holder ">
                                                                                        <input type="text" autocomplete="off" size="14" maxlength="255" id="view_f_name" name="f_name" class="view_view string data_control" placeholder="First Name" tabindex="203" value="<?= $customer->firstName ?>">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr id="view_f_10">
                                                                                    <td class="label">
                                                                                        <label for="view_l_name">Last Name</label>
                                                                                    </td>
                                                                                    <td class="form_field_holder ">
                                                                                        <input type="text" autocomplete="off" size="14" maxlength="255" id="view_l_name" name="l_name" class="view_view string data_control" placeholder="Last Name" tabindex="204" value="<?= $customer->lastName ?>">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr id="view_f_12">
                                                                                    <td class="label">
                                                                                        <label for="view_company">Company</label>
                                                                                    </td>
                                                                                    <td class="form_field_holder ">
                                                                                        <input type="text" autocomplete="off" size="14" maxlength="255" id="view_company" name="company" class="view_view string data_control" placeholder="Company" tabindex="206" value="<?= $customer->companyName ?>">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr id="view_f_21">
                                                                                    <td class="label">
                                                                                        <label for="view_contact_id_mobile">Mobile</label>
                                                                                    </td>
                                                                                    <td class="form_field_holder ">
                                                                                        <input type="text" autocomplete="off" size="14" maxlength="255" id="view_contact_id_mobile" name="mobile" class="view_view string data_control" placeholder="Mobile" tabindex="207" value="<?= $customer->phone ?>">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr id="view_f_22">
                                                                                    <td class="label">
                                                                                        <label for="view_contact_id_pager">Phone</label>
                                                                                    </td>
                                                                                    <td class="form_field_holder ">
                                                                                        <input type="text" autocomplete="off" size="14" maxlength="255" id="view_contact_id_pager" name="phone" class="view_view string data_control" placeholder="Phone" tabindex="208" value="<?= $customer->phone2 ?>">
                                                                                    </td>
                                                                                </tr>

                                                                                <tr id="view_f_5">
                                                                                    <td class="label">
                                                                                        <label for="view_discount_id">Discount</label>
                                                                                    </td>
                                                                                    <td class="form_field_holder ">
                                                                                        <select name="discount_id" id="view_discount_id" class="view_view data_control" tabindex="209">
                                                                                            <option value="0" selected="selected">Default/None</option>
                                                                                            <?php if (isset($discounts) && $discounts) : ?>
                                                                                                <?php foreach ($discounts as $discount) : ?>
                                                                                                    <option <?= $customer->discount_id == $discount->id ? 'selected' : '' ?> value="<?= $discount->id ?>"><?= $discount->title ?> (<?= $discount->type == 'fixed' ? '$'.$discount->discount : $discount->discount.'%' ?>)</option>
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
                                                            <td colspan="2" class="view_field_box " id="details_45" style="max-width: 200px;">
                                                                <h3>Contact</h3>
                                                                <table>
                                                                    <tbody>
                                                                    <tr>
                                                                        <td>
                                                                            <div class="cirrus">
                                                                                <div class="cr-pt-1 cr-pb-2">
                                                                                    <p class="cr-text-base cr-text--dimmed cr-text--content">To select your customer’s preferred contact method, you need their explicit consent.</p>
                                                                                    <div class="cr-mb-2"></div>
                                                                                </div>
                                                                            </div>
                                                                            <table class="tab_columns">
                                                                                <tbody>
                                                                                <tr id="view_f_48">
                                                                                    <td class="label">
                                                                                        <label for="view_contact_id_no_email">Email</label>
                                                                                    </td>
                                                                                    <td class="form_field_holder ">
                                                                                        <input type="checkbox" name="emailNotification" id="view_contact_id_no_email" class="view_view boolean data_control" tabindex="228" <?= $customer->emailNotifications == 1 ? 'checked' : '' ?>>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr id="view_f_50">
                                                                                    <td class="label">
                                                                                        <label for="view_contact_id_no_phone">SMS</label>
                                                                                    </td>
                                                                                    <td class="form_field_holder ">
                                                                                        <input type="checkbox" name="smsNotification" id="view_contact_id_no_phone" class="view_view boolean data_control" tabindex="230" <?= $customer->smsNotifications == 1 ? 'checked' : '' ?>>
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
                                                            <td colspan="2" class="view_field_box " id="details_25">
                                                                <h3>Address</h3>
                                                                <table>
                                                                    <tbody>
                                                                    <tr>
                                                                        <td>
                                                                            <table class="tab_columns">
                                                                                <tbody>
                                                                                <tr id="view_f_26">
                                                                                    <td class="form_field_holder " colspan="2">
                                                                                        <div class="cirrus">
                                                                                            <table class="table-address">
                                                                                                <tbody>
                                                                                                <tr>
                                                                                                    <td class="table-address__label label"><label for="address1">Address</label></td>
                                                                                                    <td class="table-address__value"><input id="address1" name="address1" class="view_view" tabindex="215" placeholder="Address" type="text" autocomplete="off" size="30" maxlength="255" style="height: 32px; line-height: 32px;" value="<?= $customer->address ?>"></td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td class="table-address__label label"><label for="contact_id.address2">Address 2</label></td>
                                                                                                    <td class="table-address__value"><input id="contact_id.address2" name="address2" class="view_view" tabindex="215" placeholder="Address 2" type="text" autocomplete="off" size="30" maxlength="255" style="height: 32px; line-height: 32px;" value="<?= $customer->address2 ?>"></td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td class="table-address__label label"><label for="contact_id.city">City</label></td>
                                                                                                    <td class="table-address__value"><input id="contact_id.city" name="city" class="view_view" tabindex="215" placeholder="City" type="text" autocomplete="off" size="30" maxlength="255" style="height: 32px; line-height: 32px;" value="<?= $customer->city ?>"></td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td class="table-address__label label"><label for="contact_id.state">Suburb</label></td>
                                                                                                    <td class="table-address__value"><input type="text" name="suburb" autocomplete="off" size="30" maxlength="255" id="contact_id.state" tabindex="215" placeholder="Suburb" class="view_view" style="height: 32px; line-height: 32px;" value="<?= $customer->suburb ?>"></td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td class="table-address__label label"><label for="contact_id.zip">Postcode</label></td>
                                                                                                    <td class="table-address__value"><input id="contact_id.zip" name="zip" class="view_view" tabindex="215" placeholder="Postcode" type="text" autocomplete="off" size="30" maxlength="255" style="height: 32px; line-height: 32px;" value="<?= $customer->zip ?>"></td>
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

                                                        <tr class="view_field_box">
                                                            <td colspan="2" class="view_field_box " id="details_36">
                                                                <h3>Other</h3>
                                                                <table>
                                                                    <tbody>
                                                                    <tr>
                                                                        <td>
                                                                            <table class="tab_columns">
                                                                                <tbody>
                                                                                <tr id="view_f_37">
                                                                                    <td class="label">
                                                                                        <label for="view_contact_id_website">Website</label>
                                                                                    </td>
                                                                                    <td class="form_field_holder ">
                                                                                        <input type="text" autocomplete="off" size="30" maxlength="255" id="view_contact_id_website" name="website" class="view_view string data_control" placeholder="Website" tabindex="223" value="<?= $customer->website ?>">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr id="view_f_38">
                                                                                    <td class="label">
                                                                                        <label for="view_contact_id_email">Email</label>
                                                                                    </td>
                                                                                    <td class="form_field_holder ">
                                                                                        <input type="text" autocomplete="off" size="30" maxlength="255" id="view_contact_id_email" name="email" class="view_view email data_control" placeholder="Email 1" tabindex="224" value="<?= $customer->email ?>">
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
                                                <td>
                                                    <table class="tab_columns">
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">
                                                    <div>
                                                        <table class="tab_columns">
                                                            <tbody>
                                                            <tr class="view_field_box">
                                                                <td colspan="2" class="view_field_box " id="details_59">
                                                                    <h3>Notes</h3>
                                                                    <table>
                                                                        <tbody>
                                                                        <tr>
                                                                            <td>
                                                                                <table class="tab_columns">
                                                                                    <tbody>
                                                                                    <tr id="view_f_60">
                                                                                        <td colspan="2">
                                                                                            <table>
                                                                                                <tbody>
                                                                                                <tr>
                                                                                                    <td class="form_field_holder">
                                                                                                        <textarea rows="10" cols="70" id="view_note_id_note_text" name="notes" class="view_view textarea data_control" tabindex="233"><?= $customer->notes ?></textarea>
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
                                                                                        <th>UID - ID</th>
                                                                                        <th>DATE</th>
                                                                                        <th>QTY</th>
                                                                                        <th>SUBTOTAL</th>
                                                                                        <th>DISCOUNT</th>
                                                                                        <th>TAX</th>
                                                                                        <th>TOTAL</th>
                                                                                        <th>PAID</th>
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
                                                                                                <td class="date ">
                                                                                                    <time datetime="<?= $sale->created ?>" class=""><?= \Framework\lib\Helper::ConvertDateFormat($sale->created) ?></time>
                                                                                                </td>
                                                                                                <td class="string "><?= $sale->quantity ?></td>
                                                                                                <td>$<?= number_format($sale->subtotal, 2) ?></td>
                                                                                                <td>$<?= number_format($sale->discount, 2) ?></td>
                                                                                                <td>$<?= number_format($sale->tax, 2) ?></td>
                                                                                                <td>$<?= number_format($sale->total, 2) ?></td>
                                                                                                <td>$<?= number_format($sale->total_paid, 2) ?></td>
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

                        <article id="tabs-menu-layaways-link" class="view_tab_layaways tab tab-pane">
                            <div class="content">
                                <i class="icon-refresh icon-spin"></i> Loading...
                            </div>
                        </article>
                        <article id="tabs-menu-special_orders-link" class="view_tab_special_orders tab tab-pane">
                            <div class="content">
                                <i class="icon-refresh icon-spin"></i> Loading...
                            </div>
                        </article>

                        <article id="tabs-menu-quotes-link" class="view_tab_quotes tab tab-pane">
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
                                                            <h2 class="child-listing-title" id="reports_sales_listings_transaction_line_sales_view_title">Quotes</h2>
                                                            <div id="reports_sales_listings_transaction_line_sales_view" class="is_pannel">
                                                                <div class="listing">
                                                                    <div id="work_reports_sales_listings_transaction_line_sales_view">
                                                                        <div id="reports_sales_listings_transaction_line_sales_view_single">
                                                                            <div class="container" style="max-width: 100%;padding-top: 10px">
                                                                                <table cellpadding="0" cellspacing="0" border="0" class="g-datatable datatable table table-striped table-bordered">
                                                                                    <thead>
                                                                                    <tr>
                                                                                        <th>UID - ID</th>
                                                                                        <th>DATE</th>
                                                                                        <th>QTY</th>
                                                                                        <th>SUBTOTAL</th>
                                                                                        <th>DISCOUNT</th>
                                                                                        <th>TAX</th>
                                                                                        <th>TOTAL</th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                    <?php if (isset($sales) && $sales !== false) : ?>
                                                                                        <?php foreach ($sales as $sale) : ?>
                                                                                            <?php if ($sale->sale_type == 'quote') : ?>
                                                                                            <tr style="" class="" id="reports_sales_listings_transaction_line_sales_view_r_<?= $sale->id ?>">
                                                                                                <td class="string ">
                                                                                                    <a title="Edit Record" href="<?= HOST_NAME.'pos/sale/'.$sale->id ?>"><span style="padding-right: 5px;"><i class="fa fa-search "></i></span><?= $sale->uid.' - #'.$sale->id ?></a>
                                                                                                </td>
                                                                                                <td class="date ">
                                                                                                    <time datetime="<?= $sale->created ?>" class=""><?= \Framework\lib\Helper::ConvertDateFormat($sale->created) ?></time>
                                                                                                </td>
                                                                                                <td class="string "><?= $sale->quantity ?></td>
                                                                                                <td>$<?= number_format($sale->subtotal, 2) ?></td>
                                                                                                <td>$<?= number_format($sale->discount, 2) ?></td>
                                                                                                <td>$<?= number_format($sale->tax, 2) ?></td>
                                                                                                <td>$<?= number_format($sale->total, 2) ?></td>
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
                                                                                        <th>Sale</th>
                                                                                        <th>Date</th>
                                                                                        <th>Type</th>
                                                                                        <th>Amount</th>
                                                                                        <th>Refund</th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                    <?php if (isset($sales_payments) && $sales_payments !== false) : ?>
                                                                                        <?php foreach ($sales_payments as $sale_payment) : ?>
                                                                                            <tr>
                                                                                                <td class="string">
                                                                                                    <a title="Edit Record" href="<?= HOST_NAME.'pos/sale/'.$sale_payment->sale_id ?>"><span style="padding-right: 5px;"><i class="fa fa-search "></i></span><?= $sale_payment->uid.' - #'.$sale_payment->sale_id ?></a>
                                                                                                </td>
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

                        <article id="tabs-menu-account-link" class="view_tab_account tab tab-pane">
                            <div class="content">
                                <div class="view-columns">
                                    <table class="view-layout set_auto_focus">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <table class="view-column ">
                                                    <tbody>
                                                    <tr data-automation="printLabelsRow" class="view_field_box">
                                                        <td colspan="2" class="view_field_box " id="account_6">
                                                            <h3>Credit Account Details</h3>
                                                            <table>
                                                                <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <table class="tab_columns">
                                                                            <tbody><tr id="view_f_7">
                                                                                <td class="label">
                                                                                    <label for="view_credit_account_id_credit_limit">Credit Limit</label>
                                                                                </td>
                                                                                <td class="form_field_holder ">
                                                                                    <div class="money-field-container"><input type="text" autocomplete="off" value="<?= $customer->credit_limit ?>" size="" maxlength="15" id="view_credit_account_id_credit_limit" name="credit_limit" class="view_view money data_control" tabindex="200"></div>
                                                                                </td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                    <td>
                                                                        <table class="tab_columns">
                                                                            <tbody>
                                                                            <?php if ($customer->owed) : ?>
                                                                            <tr id="view_f_9">
                                                                                <td class="label">
                                                                                    <label for="view_credit_account_id_function__creditBalanceOwed">Balance Owed</label>
                                                                                </td>
                                                                                <td class="form_field_holder ">
                                                                                    <span id="view_credit_account_id_function__creditBalanceOwed" style="width: auto; display: inline;" class="view_field_relation">$<?= $customer->owed ?></span>
                                                                                </td>
                                                                            </tr>
                                                                            <?php endif; ?>
                                                                            <?php if ($customer->credit_limit && $customer->owed) : ?>
                                                                            <tr id="view_f_10">
                                                                                <td class="label">
                                                                                    <label for="view_credit_account_id_function__getCreditLeft">Available</label>
                                                                                </td>
                                                                                <td class="form_field_holder ">
                                                                                    <span id="view_credit_account_id_function__getCreditLeft" style="width: auto; display: inline;" class="view_field_relation">$<?= $customer->credit_limit - $customer->owed ?></span>
                                                                                </td>
                                                                            </tr>
                                                                            <?php endif; ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr data-automation="printLabelsRow" class="view_field_box">
                                                        <td colspan="2" class="view_field_box " id="account_11">
                                                            <h3>Print Account Statement</h3>
                                                            <table>
                                                                <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <table class="tab_columns">
                                                                            <tbody>
                                                                            <tr id="view_f_12">
                                                                                <td class="label">
                                                                                    <label for="view_custom_account_statement_start">From</label>
                                                                                </td>
                                                                                <td class="form_field_holder ">
                                                                                    <div class="date-wrapper"><input type="text" id="view_custom_account_statement_start" name="custom_account_statement_start" size="10" autocomplete="off" maxlength="10" class="view_view date data_control" value="2020-05-20" tabindex="201"></div>
                                                                                </td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                    <td>
                                                                        <table class="tab_columns">
                                                                            <tbody>
                                                                            <tr id="view_f_14">
                                                                                <td class="label">
                                                                                    <label for="view_custom_account_statement_end">
                                                                                        To
                                                                                    </label>
                                                                                </td>
                                                                                <td class="form_field_holder ">
                                                                                    <div class="date-wrapper"><input type="text" id="view_custom_account_statement_end" name="custom_account_statement_end" size="10" autocomplete="off" maxlength="10" class="view_view date data_control" onchange=" parseDateField(this);  $('#view_custom_account_statement_end')[0].dx = true;" value="2020-06-20" tabindex="202" pikaday="true"></div>
                                                                                </td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                    <td>
                                                                        <table class="tab_columns">
                                                                            <tbody>
                                                                            <tr id="view_f_16">
                                                                                <td class="view_functions  griffin-no-display " colspan="2">
                                                          <span class="function">
                                                          <button title="Print" id="printAccountStatementButton" class="griffin-no-display custom_function" onclick="merchantos.controls.customFunctionOnClick(this,{name: 'print_current_statement_fnc',pannel_id: 'view',context: 'view',confirm_msg: '',prevent_repeat: false,save_on_changes: false,extra: null}); return false;"><i class="icon-print "></i> Print</button>
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
                                                    <tr data-automation="printLabelsRow" class="view_field_box">
                                                        <td colspan="2" class="view_field_box " id="account_18">
                                                            <h3>Billing Address</h3>
                                                            <table>
                                                                <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <table class="tab_columns">
                                                                            <tbody>
                                                                            <tr id="view_f_19">
                                                                                <td class="form_field_holder " colspan="2">
                                                                                    <div>
                                                                                        <div style="width: 300px;">Billing address will be the same as the customer's main address. To use a different address click 'New Address' below.</div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr id="view_f_20">
                                                                                <td class="view_functions " colspan="2">
                                                                                  <span class="function">
                                                                                      <button title="New Address" class="custom_function"><i class="fa fa-plus "></i> New Address</button>
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
                </form>
            </div>
        </div>
</div>
<style>
    .page-inner {padding-right: 0; padding-left: 0}
    .nav-item {width: 100%;}
    .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {color: #333;background-color: #eee;font-weight: 600;}
    .nav-tabs .nav-link:focus {border: 0; outline: none}
    .nav-tabs .nav-link {border: 0; color: #2a6fb1}
</style>
<script>
    $(document).ready(function () {
        $('.nav-link').on('click', function (e) {
            if ($(this).attr('href') !== "#tabs-menu-details-link" && $(this).attr('href') !== "#tabs-menu-account-link") {
                $('button.save').attr('disabled', true);
            } else {
                $('button.save').attr('disabled', false);
            }
        });

        $('button.save').on('click', function (e) {
            // var form = $('.nav-link.active').data('form');
            // $('#'+form+'-form').submit();

            $('#customer-details-form').submit();
        });
    });
</script>
<?php endif; ?>
