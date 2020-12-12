<div class="content">
    <div class="view-columns">
        <form method="post" action="<?= HOST_NAME.'pos/sale_receipt_email_action/'.$this->data['sale_id'] ?>">
            <table class="view-layout set_auto_focus">
                <tbody>
                <tr>
                    <td>
                        <table class="view-column ">
                            <tbody>
                            <tr class="view_field_box">
                                <td colspan="2" class="view_field_box " id="email_5">
                                    <h3>Send Email Receipt</h3>
                                    <table>
                                        <tbody><tr>
                                            <td>
                                                <table class="tab_columns">
                                                    <tbody><tr id="view_f_6">
                                                        <td class="label">
                                                            <label for="view_email_to_email">To Email Address</label>
                                                        </td>
                                                        <td class="form_field_holder ">
                                                            <input type="text" autocomplete="off" value="" size="40" maxlength="255" id="view_email_to_email" name="to_email" class="view_view email priority_auto_focus data_control" placeholder="To Email Address" tabindex="200" required>
                                                        </td>
                                                    </tr>
                                                    <tr id="view_f_7">
                                                        <td class="label">
                                                            <label for="view_email_to_name">To Name</label>
                                                        </td>
                                                        <td class="form_field_holder ">
                                                            <input type="text" autocomplete="off" value="" size="40" maxlength="255" id="view_email_to_name" name="to_name" class="view_view string data_control" placeholder="To Name" tabindex="201">
                                                        </td>
                                                    </tr>
                                                    <tr id="view_f_8">
                                                        <td class="label">
                                                            <label for="view_email_to_subject">Subject</label>
                                                        </td>
                                                        <td class="form_field_holder ">
                                                            <input type="text" autocomplete="off" value="CYW Receipt For Sale #<?= $this->data['sale_id'] ?>" size="40" maxlength="255" id="view_email_to_subject" name="to_subject" class="view_view string data_control" placeholder="Subject" tabindex="202">
                                                        </td>
                                                    </tr>
                                                    <tr id="view_f_9">
                                                        <td colspan="2">
                                                            <table>
                                                                <tbody><tr>
                                                                    <td class="textarea_label">
                                                                        <label for="view_email_to_header">Message</label>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="form_field_holder">
                                                                        <textarea rows="5" cols="50" id="view_email_to_header" name="message" class="view_view textarea data_control" tabindex="203"></textarea>
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr id="view_f_11">
                                                        <td class="view_functions " colspan="2">
                                                            <span class="function">
                                                                <button title="Email Receipt" id="send_email_receipt" class="custom_function" type="submit" name="submit"><i class="fa fa-envelope "></i> Email Receipt</button>
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
        </form>
    </div>
</div>