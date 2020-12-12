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
                                                <td colspan="2" class="view_field_box " id="main_16">
                                                    <h3>Add Licenses to Product</h3>
                                                    <table>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <table class="tab_columns">
                                                                    <tbody>
                                                                    <tr id="view_f_17">
                                                                        <td class="label">
                                                                            <label for="view_vendor_id">Product</label>
                                                                        </td>
                                                                        <td class="form_field_holder " style="padding: 10px;">
                                                                            <select name="item" id="product-select" class="view_view data_control form-control" tabindex="201" required>
                                                                                <?php if (isset($items) && $items !== false) : ?>
                                                                                    <?php foreach ($items as $item) : ?>
                                                                                        <option value="<?= $item->id ?>"><?= $item->item.' | '.$item->shop_sku. ($item->shop_sku != $item->man_sku ? ' | '.$item->man_sku : '') ?></option>
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
                                                <td colspan="2" class="view_field_box " id="main_24">
                                                    <h3> Licenses (Separated by comma or new line)</h3>
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
                                                                                        <textarea rows="5" cols="40" name="licenses" class="view_view textarea data_control" tabindex="206" required></textarea>
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

                                            <tr class="view_field_box">
                                                <td colspan="2" class="view_field_box " id="main_24">
                                                    <h3> License Expires After:</h3>
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
                                                                                        <div class="row form-group" style="padding: 1rem 8px 8px">
                                                                                            <?php $numbers = ['One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven']; ?>
                                                                                            <div class="col-md-6">
                                                                                                <label for="expiration-year">Years</label>
                                                                                                <select class="form-control form-round" name="expiration-year" id="expiration-year" required>
                                                                                                    <option value="0">0</option>
                                                                                                    <?php for ($i = 0; $i < 10; $i++) : ?>
                                                                                                        <option value="<?= ($i + 1) ?>"><?= $numbers[$i] ?> Year<?= $i >= 1 ? 's' : '' ?></option>
                                                                                                    <?php endfor; ?>
                                                                                                </select>
                                                                                            </div>
                                                                                            <div class="col-md-6">
                                                                                                <label for="expiration-month">Months</label>
                                                                                                <select class="form-control form-round" name="expiration-month" id="expiration-month" required>
                                                                                                    <option value="0">0</option>
                                                                                                    <?php for ($i = 0; $i <= 10; $i++) : ?>
                                                                                                        <option value="<?= ($i + 1) ?>"><?= $numbers[$i] ?> Month<?= $i >= 1 ? 's' : '' ?></option>
                                                                                                    <?php endfor; ?>
                                                                                                </select>
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
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>


                                            <tr class="view_field_box">
                                                <td colspan="2" class="view_field_box " id="main_24">
                                                    <h3> Email Template:</h3>
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
                                                                                    <td class="label">
                                                                                        <label for="view_vendor_id">Template:</label>
                                                                                    </td>
                                                                                    <td class="form_field_holder " style="padding: 10px;">
                                                                                        <select name="email-template" class="form-control form-round email-template-select" required>
                                                                                            <?php if (isset($templates) && $templates !== false) : ?>
                                                                                            <?php foreach ($templates as $template) : ?>
                                                                                                <option value="<?= $template->template_name ?>"><?= $template->template_name ?></option>
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
                <button title="Save Changes" id="saveButton" class="save" name="save" type="submit">Save</button>
            </div>
        </form>

    </div>
</div>
<style>
    .dataTables_wrapper .row {padding: 0}
    .listing table thead th {text-align: center}
    .listing table thead th:last-child,
    .listing table tbody td:last-child {text-align: right !important;}

    .select2 {margin-top: 5px}
    .select2-selection {padding: 0 !important;}
    .select2 .selection {padding: 0 !important;}
    .select2-container {padding: 0 !important;}
    .select2-selection__rendered {display: block !important}
    .select2-container .select2-selection--single {height: unset}
    .select2-container--default .select2-selection--single .select2-selection__rendered {padding: 4px 12px}
    .select2-container--default .select2-selection--multiple .select2-selection__rendered {padding: 0 !important;}
    .dropdown-wrapper {display: block;padding-block-end: 0;padding-block-start: 0;padding: 0 !important;}
    /*.select2-container--default .select2-selection--multiple .select2-selection__rendered li {margin: 0 !important;}*/

    .select2-container--default .select2-selection--multiple .select2-selection__choice {margin: 0 10px 0 -5px !important;
        background-color: #e9eaeb !important;border-color: #e4e4e4 !important;}
</style>


<script>
    $(document).ready(function() {
        $('#product-select').select2();
    });
</script>