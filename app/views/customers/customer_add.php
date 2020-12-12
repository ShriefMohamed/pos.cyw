<div id="view" data-test="view-wrapper" style="display: block;">
    <div class="view">
        <form method="post">
            <div class="functions">
                <button title="Save Customer" class="save" type="submit" name="submit">Save Customer</button>
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
                                                                <div class="new-item-spacer-text">Create New Customer</div>
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
                                                                        <tr id="view_f_9">
                                                                            <td class="label">
                                                                                <label for="view_f_name">First Name</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <input type="text" autocomplete="off" size="14" maxlength="255" id="view_f_name" name="f_name" class="view_view string data_control" placeholder="First Name" tabindex="203">
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_10">
                                                                            <td class="label">
                                                                                <label for="view_l_name">Last Name</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <input type="text" autocomplete="off" size="14" maxlength="255" id="view_l_name" name="l_name" class="view_view string data_control" placeholder="Last Name" tabindex="204">
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_12">
                                                                            <td class="label">
                                                                                <label for="view_company">Company</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <input type="text" autocomplete="off" size="14" maxlength="255" id="view_company" name="company" class="view_view string data_control" placeholder="Company" tabindex="206">
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_21">
                                                                            <td class="label">
                                                                                <label for="view_contact_id_mobile">Mobile</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <input type="text" autocomplete="off" size="14" maxlength="255" id="view_contact_id_mobile" name="mobile" class="view_view string data_control" placeholder="Mobile" tabindex="207">
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_22">
                                                                            <td class="label">
                                                                                <label for="view_contact_id_pager">Phone</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <input type="text" autocomplete="off" size="14" maxlength="255" id="view_contact_id_pager" name="phone" class="view_view string data_control" placeholder="Phone" tabindex="208">
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
                                                                                            <option value="<?= $discount->id ?>"><?= $discount->title ?> (<?= $discount->type == 'fixed' ? '$'.$discount->discount : $discount->discount.'%' ?>)</option>
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
                                                                                <input type="checkbox" name="emailNotification" id="view_contact_id_no_email" class="view_view boolean data_control" tabindex="228">
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_50">
                                                                            <td class="label">
                                                                                <label for="view_contact_id_no_phone">SMS</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <input type="checkbox" name="smsNotification" id="view_contact_id_no_phone" class="view_view boolean data_control" tabindex="230">
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
                                                                                            <td class="table-address__value"><input id="address1" name="address1" class="view_view" tabindex="215" placeholder="Address" type="text" autocomplete="off" size="30" maxlength="255" style="height: 32px; line-height: 32px;"></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td class="table-address__label label"><label for="contact_id.address2">Address 2</label></td>
                                                                                            <td class="table-address__value"><input id="contact_id.address2" name="address2" class="view_view" tabindex="215" placeholder="Address 2" type="text" autocomplete="off" size="30" maxlength="255" style="height: 32px; line-height: 32px;"></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td class="table-address__label label"><label for="contact_id.city">City</label></td>
                                                                                            <td class="table-address__value"><input id="contact_id.city" name="city" class="view_view" tabindex="215" placeholder="City" type="text" autocomplete="off" size="30" maxlength="255" style="height: 32px; line-height: 32px;"></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td class="table-address__label label"><label for="contact_id.state">Suburb</label></td>
                                                                                            <td class="table-address__value"><input type="text" name="suburb" autocomplete="off" size="30" maxlength="255" id="contact_id.state" tabindex="215" placeholder="Suburb" class="view_view" style="height: 32px; line-height: 32px;"></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td class="table-address__label label"><label for="contact_id.zip">Postcode</label></td>
                                                                                            <td class="table-address__value"><input id="contact_id.zip" name="zip" class="view_view" tabindex="215" placeholder="Postcode" type="text" autocomplete="off" size="30" maxlength="255" style="height: 32px; line-height: 32px;"></td>
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
                                                                                <input type="text" autocomplete="off" size="30" maxlength="255" id="view_contact_id_website" name="website" class="view_view string data_control" placeholder="Website" tabindex="223">
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_38">
                                                                            <td class="label">
                                                                                <label for="view_contact_id_email">Email</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <input type="text" autocomplete="off" size="30" maxlength="255" id="view_contact_id_email" name="email" class="view_view email data_control" placeholder="Email 1" tabindex="224">
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
                                                                                                <textarea rows="10" cols="70" id="view_note_id_note_text" name="notes" class="view_view textarea data_control" tabindex="233"></textarea>
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
                </div>
            </div>
        </form>
    </div>
</div>
<style>
    .page-inner {padding-right: 0; padding-left: 0}
</style>