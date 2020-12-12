<?php if (isset($template) && $template) : ?>
<style>
    .dataTables_wrapper .row {padding: 0}
    .listing table thead th {text-align: center}
    .listing table thead th:last-child,
    .listing table tbody td:last-child {text-align: right !important;}
</style>

<link rel="stylesheet" href="<?= VENDOR_DIR ?>summernote/summernote-bs4.css">
<script src="<?= VENDOR_DIR ?>summernote/summernote-bs4.min.js"></script>



<div id="view" class="is_pannel" data-test="view-wrapper" style="display: block;">
    <div class="view">
        <form method="post">
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
                                                <td colspan="2" class="view_field_box " id="main_24">
                                                    <h3> Template </h3>
                                                    <table>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <table class="tab_columns">
                                                                    <tbody>
                                                                    <tr id="view_f_6">
                                                                        <td class="label">
                                                                            <label for="view_vendor_id">Template Name</label>
                                                                        </td>
                                                                        <td class="form_field_holder ">
                                                                            <input type="text" class="view_view data_control" value="<?= $template->template_name ?>" disabled>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_7">
                                                                        <td class="form_field_holder " colspan="2">
                                                                            <div>
                                                                                <div style="width: 100%; padding:10px;">
                                                                                    <p>Please surround keywords with { } in order for it to get detected.</p>
                                                                                    <p>example: Hello {first_name}, your code is {license_code}</p>
                                                                                    Allowed Keywords
                                                                                    <ul>
                                                                                        <li>first_name</li>
                                                                                        <li>last_name</li>
                                                                                        <li>product_name</li>
                                                                                        <li>license_code</li>
                                                                                        <li>expiration_period (i.e. 3 years and 6 months)</li>
                                                                                        <li>expiration_date (i.e. 25-03-2020)</li>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="view_f_25">
                                                                        <td colspan="2">
                                                                            <table>
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td class="form_field_holder">
                                                                                        <textarea rows="5" cols="40" name="template" class="view_view textarea data_control" tabindex="206" required><?= html_entity_decode($template->template) ?></textarea>
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
                        </div>
                    </div>
                </article>
            </div>

            <div class="functions functions-pull-right">
                <button title="Save Changes" id="saveButton" class="save" name="save" type="submit">Save Template</button>
            </div>
        </form>

    </div>
</div>


<script>
    $(document).ready(function() {
        $('.textarea').summernote({
            placeholder: 'Write template here...',
            height: 200
        });
    });
</script>
<?php endif; ?>