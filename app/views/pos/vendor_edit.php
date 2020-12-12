<?php if (isset($vendor) && $vendor !== false) : ?>
<div id="view" data-test="view-wrapper" style="display: block;">
    <div class="view">
        <form method="post">
            <div class="functions">
                <button title="Save Changes" class="save" type="submit" name="submit">Save Vendor</button>
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
                                                <tr data-automation="printLabelsRow" class="view_field_box">
                                                    <td colspan="2" class="view_field_box " id="details_2">
                                                        <h3>Setup</h3>
                                                        <table>
                                                            <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table class="tab_columns">
                                                                        <tbody>
                                                                        <tr id="view_f_3">
                                                                            <td class="label">
                                                                                <label for="view_name">Vendor Name</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <input type="text" autocomplete="off" maxlength="255" id="view_name" name="name" class="view_view string data_control" placeholder="Vendor Name" tabindex="200" value="<?= $vendor->name ?>">
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_4">
                                                                            <td class="label">
                                                                                <label for="view_account_number">Account Number</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <input type="text" autocomplete="off" maxlength="255" id="view_account_number" name="account_number" class="view_view string data_control" placeholder="Account Number" tabindex="201" value="<?= $vendor->account_number ?>">
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
                                                    <td colspan="2" class="view_field_box " id="details_11">
                                                        <h3>Sales Rep</h3>
                                                        <table>
                                                            <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table class="tab_columns">
                                                                        <tbody>
                                                                        <tr id="view_f_12">
                                                                            <td class="label">
                                                                                <label for="view_contact_f_name">First Name</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <input type="text" autocomplete="off" maxlength="255" id="view_contact_f_name" name="contact_f_name" class="view_view string data_control" placeholder="First Name" tabindex="202" value="<?= $vendor->contact_f_name ?>">
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
                                                                                <label for="view_contact_l_name">Last Name</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <input type="text" autocomplete="off" maxlength="255" id="view_contact_l_name" name="contact_l_name" class="view_view string data_control" placeholder="Last Name" tabindex="203" value="<?= $vendor->contact_l_name ?>">
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
                                                    <td colspan="2" class="view_field_box " id="details_16">
                                                        <h3>Phones</h3>
                                                        <table>
                                                            <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table class="tab_columns">
                                                                        <tbody>
                                                                        <tr id="view_f_17">
                                                                            <td class="label">
                                                                                <label for="view_contact_id_phone_work">Phone</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <input type="text" autocomplete="off" maxlength="255" id="view_contact_id_phone_work" name="contact_phone" class="view_view string data_control" placeholder="Contact Phone" tabindex="204" value="<?= $vendor->contact_phone ?>">
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_20">
                                                                            <td class="label">
                                                                                <label for="view_contact_id_mobile">
                                                                                    Mobile
                                                                                </label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <input type="text" autocomplete="off" maxlength="255" id="view_contact_id_mobile" name="contact_mobile" class="view_view string data_control" placeholder="Contact Mobile" tabindex="205" value="<?= $vendor->contact_mobile ?>">
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

                                                <tr data-automation="printLabelsRow" class="view_field_box">
                                                    <td colspan="2" class="view_field_box " id="details_22">
                                                        <h3>Address</h3>
                                                        <table>
                                                            <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table class="tab_columns">
                                                                        <tbody>
                                                                        <tr id="view_f_23">
                                                                            <td class="form_field_holder " colspan="2">
                                                                                <table class="table-address">
                                                                                    <tbody>
                                                                                    <tr>
                                                                                        <td class="table-address__label label"><label for="contact_id.address1">Address</label></td>
                                                                                        <td class="table-address__value"><input id="contact_id.address1" name="address1" class="view_view" tabindex="210" placeholder="Address" type="text" autocomplete="off" size="30" maxlength="255" style="height: 32px; line-height: 32px;" value="<?= $vendor->address ?>"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td class="table-address__label label"><label for="contact_id.address2">Address 2</label></td>
                                                                                        <td class="table-address__value"><input id="contact_id.address2" name="address2" class="view_view" tabindex="210" placeholder="Address 2" type="text" autocomplete="off" size="30" maxlength="255" style="height: 32px; line-height: 32px;" value="<?= $vendor->address2 ?>"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td class="table-address__label label"><label for="contact_id.city">City</label></td>
                                                                                        <td class="table-address__value"><input id="contact_id.city" name="city" class="view_view" tabindex="210" placeholder="City" type="text" autocomplete="off" size="30" maxlength="255" style="height: 32px; line-height: 32px;" value="<?= $vendor->city ?>"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td class="table-address__label label"><label for="contact_id.state">Suburb</label></td>
                                                                                        <td class="table-address__value">
                                                                                            <div class="cirrus"><input type="text" name="suburb" autocomplete="off" size="30" maxlength="255" id="contact_id.state" tabindex="210" placeholder="Suburb" class="view_view" style="height: 32px; line-height: 32px;" value="<?= $vendor->suburb ?>"></div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td class="table-address__label label"><label for="contact_id.zip">Postcode</label></td>
                                                                                        <td class="table-address__value"><input id="contact_id.zip" name="zip" class="view_view" tabindex="210" placeholder="Postcode" type="text" autocomplete="off" size="30" maxlength="255" style="height: 32px; line-height: 32px;" value="<?= $vendor->zip ?>"></td>
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
                                                <tr data-automation="printLabelsRow" class="view_field_box">
                                                    <td colspan="2" class="view_field_box " id="details_33">
                                                        <h3>Other</h3>
                                                        <table>
                                                            <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table class="tab_columns">
                                                                        <tbody>
                                                                        <tr id="view_f_34">
                                                                            <td class="label">
                                                                                <label for="view_contact_id_website">Website</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <input type="text" autocomplete="off" size="30" maxlength="255" id="view_contact_id_website" name="website" class="view_view string data_control" placeholder="Website" tabindex="218" value="<?= $vendor->website ?>">
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="view_f_35">
                                                                            <td class="label">
                                                                                <label for="view_contact_id_email">Email</label>
                                                                            </td>
                                                                            <td class="form_field_holder ">
                                                                                <input type="email" autocomplete="off" size="30" maxlength="255" id="view_contact_id_email" name="email" class="view_view email data_control" placeholder="Email Address" tabindex="219"  value="<?= $vendor->email ?>">
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
                                                    <tr data-automation="printLabelsRow" class="view_field_box">
                                                        <td colspan="2" class="view_field_box " id="details_40">
                                                            <h3>Notes</h3>
                                                            <table>
                                                                <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <table class="tab_columns">
                                                                            <tbody>
                                                                            <tr id="view_f_41">
                                                                                <td colspan="2">
                                                                                    <table>
                                                                                        <tbody>
                                                                                        <tr>
                                                                                            <td class="form_field_holder">
                                                                                                <textarea rows="10" cols="80" id="view_note_id_note_text" name="notes" class="view_view textarea data_control" tabindex="222"><?= $vendor->notes ?></textarea>
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
<?php endif; ?>
