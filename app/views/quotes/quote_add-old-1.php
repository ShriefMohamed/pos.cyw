<?php use Framework\lib\FilterInput; ?>
<div class="page-section">
    <section class="card card-fluid">
        <div class="card-body" style="padding: 0;">
            <form method="post" id="quote-form">
                <div class="functions">
                    <button type="button" title="Continue" class="btn btn-success" data-toggle="modal" data-target="#customer-into-modal" style="padding: .375rem 1.75rem;">Continue</button>
                </div>
                <div class="main">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered quote-table">
                        <thead>
                        <tr>
                            <th class="th__component" style="width: 15%">Component</th>
                            <th></th>
                            <th class="th__selection" colspan="3" style="width: 55%">Selection</th>
                            <th class="th__qty">Qty.</th>
                            <th class="th__base">DBP</th>
                            <th class="th__price">RRP</th>
                            <th class="th__price">Total</th>
                            <th class="th__remove" style="width: 10%"></th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php if (isset($categories) && $categories) : ?>
                            <?php foreach ($categories as $category) : ?>
                                <tr class="tr__product" id="<?= FilterInput::CleanString(str_replace(' ', '', $category)) ?>-tr" data-class="<?= FilterInput::CleanString(str_replace(' ', '', $category)) ?>-tr" data-component="<?= $category ?>">
                                    <td class="td__component">
                                        <a href="#" class="select-part-btn" data-category="<?= $category ?>"><?= $category ?></a>
                                    </td>
                                    <td class="td__component">
                                        <label>Merge
                                            <input type='checkbox' id='merge-<?= FilterInput::CleanString(str_replace(' ', '', $category)) ?>-tr' class=" merge-component-btn" data-component='<?= FilterInput::CleanString(str_replace(' ', '', $category)) ?>-tr' name='item_merge_component[<?= $category ?>]' value='<?= $category ?>' title="Merge Parts">
                                        </label>
                                    </td>
                                    <td class="td__addComponent" colspan="8">
                                        <button type="button" class="btn btn-primary select-part-btn" data-category="<?= $category ?>">
                                            <i class="fa fa-plus"></i>
                                            Choose <?= $category ?>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>



                        <tr class="tr__total tr__total--dbp">
                            <td class="td__label" colspan="8">DBP System:</td>
                            <td class="td__price" colspan="2">
                                <input type="hidden" name="system_dbp" value="0">
                                <span>$0.00</span>
                            </td>
                        </tr>
                        <tr class="tr__total tr__total--margin">
                            <td class="td__label" colspan="8">Mark up %</td>
                            <td class="td__price" colspan="2">
                                <div class="tr__total--margin-input">
                                    <input type="text" name="system_margin" value="25">
                                </div>
                            </td>
                        </tr>
                        <tr class="tr__total tr__total--profit">
                            <td class="td__label" colspan="8">Margin (Profit)</td>
                            <td class="td__price" colspan="2">$0.00</td>
                        </tr>
                        <tr class="tr__total tr__total--sys_total">
                            <td class="td__label" colspan="8">System Total:</td>
                            <td class="td__price" colspan="2">
                                <input type="text" name="system_total" value="0.00">
                            </td>
                        </tr>

                        <tr class="tr__total tr__total--subtotal">
                            <td class="td__label" colspan="8">Sub Total:</td>
                            <td class="td__price" colspan="2">
                                $0.00
                            </td>
                        </tr>
                        <tr class="tr__total tr__total--gst">
                            <td class="td__label" colspan="8">GST:</td>
                            <td class="td__price" colspan="2">$0.00</td>
                        </tr>
                        <tr class="tr__total tr__total--final">
                            <td class="td__label" colspan="8">Total:</td>
                            <td class="td__price" colspan="2">$0.00</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div id="sale-note-wrapper">
                    <table style="border-top: 0;width: 100%">
                        <tbody>
                        <tr style="margin-top: 8px">
                            <td style="border-bottom: none; padding: 10px 15px 0;">
                                <label for="printed_note">Receipt note</label>
                            </td>
                            <td style="border-bottom: none; border-left: 1px solid #ccc; padding: 10px 15px 0;">
                                <label for="internal_note">Internal note</label>
                                <a href="javascript:;" style="float: right" class="add-time-button">
                                    Add time <i class="fa fa-clock"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 15px 15px;">
                                <textarea name="printed_note" id="printed_note" rows="4" style="width: 100%; padding: 6px;" placeholder="Customers will be able to see this note on their receipt"></textarea>
                            </td>
                            <td style="border-left: 1px solid #ccc; padding: 0 15px;">
                                <textarea name="internal_note" id="internal_note" rows="4" style="width: 100%; padding: 6px;" placeholder="Customers won't be able to see this note"></textarea>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="functions">
                    <button type="button" title="Continue" class="btn btn-success" data-toggle="modal" data-target="#customer-into-modal" style="padding: .375rem 1.75rem;float: right">Continue</button>
                </div>

                <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="customer-into-modal">
                    <div class="modal-dialog" role="document" style="max-width: 80%">
                        <div class="modal-content">
                            <div class="modal-header bg-info-dark">
                                <h5 class="modal-title"> Customer Details </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="custom-form-label">First Name*</label>
                                                    <input type="text" class="form-control form-round" name="f_name" placeholder="First Name" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="custom-form-label">Last Name*</label>
                                                    <input type="text" class="form-control form-round" name="l_name" placeholder="Last Name" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="custom-form-label">Email</label>
                                                    <input type="email" name="email" class="form-control form-round" placeholder="Email" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="custom-form-label">Phone Number</label>
                                                    <input type="text" name="phone" class="form-control form-round" placeholder="Phone Number" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label class="custom-form-label">Address</label>
                                                    <input type="text" name="address" class="form-control form-round" placeholder="Address">
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label class="custom-form-label">Suburb</label>
                                                    <input type="text" name="suburb" class="form-control form-round" placeholder="Suburb">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="custom-form-label">Postcode</label>
                                                    <input type="text" name="zip" class="form-control form-round" placeholder="Postcode">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer" style="margin-top: 0">
                                <button type="submit" name="preview" class="btn btn-info-dark" id="apply-discount-button">Preview Quote</button>
                                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="quote-preview-modal" style="overflow: auto">
                    <div class="modal-dialog" role="document" style="max-width: 65%">
                        <div class="modal-content">
                            <div class="modal-header bg-info-dark">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <iframe id="quote_preview_iframe" name="receipt_preview_iframe" width="100%" height="400px" style="max-width: 95%%; border: 1px solid #e6e6e6; padding: 0;"></iframe>
                            </div>

                            <div class="modal-footer" style="margin-top: 0">
                                <button type="submit" name="save" class="btn btn-info-dark">Save & Send</button>
                                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="select-part-modal" style="overflow: auto !important;">
                <div class="modal-dialog" role="document" style="max-width: 95%;margin-top: 2rem;">
                    <div class="modal-content">
                        <div class="modal-header bg-info-dark">
                            <h5 class="modal-title"> Add a Component </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Select Category</label>
                                                <select class="form-control categories-select" id="category-select">
                                                    <option value="">Select Category</option>
                                                    <?php if (isset($categories) && $categories) : ?>
                                                        <?php foreach ($categories as $category) : ?>
                                                            <option value="<?= $category ?>"><?= $category ?></option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Select Sub-Category</label>
                                                <select class="form-control categories-select" id="subcategory-select">
                                                    <option value="">Select Sub-Category</option>
                                                    <?php if (isset($sub_categories) && $sub_categories) : ?>
                                                        <?php foreach ($sub_categories as $sub_category) : ?>
                                                            <option value="<?= $sub_category ?>"><?= $sub_category ?></option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <table cellpadding="0" cellspacing="0" border="0" class="items-table table table-striped table-bordered" id="items-table" style="width: 100% !important;">
                                                <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Product</th>
                                                    <th>Stock Code</th>
                                                    <th>Manufacturer</th>
                                                    <th>ADL-SYD-BRS-MEL-WA</th>
                                                    <th>DBP</th>
                                                    <th>RRP</th>
                                                    <th style="width: 5%">Qty.</th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div >

            <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="image-preview-modal">
                <div class="modal-dialog" role="document">
                    <div class="modal-content" style="background: transparent">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute;right: 0;padding-top: 8px;padding-right: 15px;">
                            <span aria-hidden="true">&times;</span>
                        </button>

                        <img src="" class="modal-image-preview" style="object-fit: fill">
                    </div>
                </div>
            </div>

            <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="description-preview-modal">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="description-area"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .functions {width: 100%;overflow: hidden;z-index: 100;border-bottom: 1px solid #ccc;background-color: #eee;
        min-height: 54px;padding-left: 10px;}
    .functions button {float: left;margin-top: 10px;margin-bottom: 10px;margin-right: 6px;}

    .component-control {background: #eee linear-gradient(#fff, #eee);margin: 0 5px 0 0;padding: 6px 6px 6px 8px;
        white-space: nowrap;border-radius: 3px;border: 1px solid #aaa;color: #666;
        text-align: center;text-decoration: none;display: inline-block;font-size: 13px;}
    .component-control i {font-size: 14px;display: inline-block;margin-right: 2px !important;color: #999;}
    .component-control:hover {background: #dcdcdc linear-gradient(#e6e6e6, #dcdcdc)}

    .display_quantity {padding: 3px 0;width: 3rem;font-size: 0.875rem;text-align: center;
        border: 1px solid #aaa;box-shadow: inset 1px 1px 1px #eee;min-height: 30px;}

    .table tbody tr td:last-child {border-right: 1px solid #DDDDDD !important;}

    .rrp-percentage-td {display: inline-flex}

    .tr__product .money {margin-right: 1rem}
    .tr__product .money, .tr__product .percentage {position: relative}
    .tr__product .money input,
    .tr__product .percentage input,
    .tr__total--sys_total input,
    .tr__total--margin input
    {margin: 2px 0;padding: 3px 6px;-webkit-appearance: none;
        width: 5em;font-size: 0.875rem;border: 1px solid #aaa;box-shadow: inset 1px 1px 1px #eee;min-height: 30px;}
    .tr__product .money input, .tr__total--sys_total input {text-align: right;padding-left: 1rem;}
    .tr__product .percentage input, .tr__total--margin input {text-align: left}

    .tr__product .money::before {content: "$";color: #777;font-size: 0.875rem;left: 0.375rem;
        position: absolute;top: 49%;transform: translate(0, -50%);pointer-events: none;cursor: text;}
    .tr__product .percentage::before {content: "%";color: #777;font-size: 0.875rem;right: 0.375rem;
        position: absolute;top: 49%;transform: translate(0, -50%);pointer-events: none;cursor: text;}

    .tr__total--margin-input {position: relative;display: inline-block;width: 50%;}
    .tr__total--margin-input input {width: 100%;}
    .tr__total--sys_total .td__price {position: relative}

    .tr__total--sys_total .td__price::before {content: "$";color: #777;font-size: 0.875rem;left: 1.1rem;
        position: absolute;top: 49%;transform: translate(0, -50%);pointer-events: none;cursor: text;}
    .tr__total--margin .tr__total--margin-input::before {content: "%";color: #777;font-size: 0.875rem;right: 0.375rem;
        position: absolute;top: 49%;transform: translate(0, -50%);pointer-events: none;cursor: text;}

    .tr__product .money::after,
    .tr__product .percentage::after,
    .tr__total--sys_total .td__price::after,
    .tr__total--margin .tr__total--margin-input::after {content: "";display: table;clear: both;}


    .tr__product .display_quantity {margin-top: 2px;}
    .tr__product .display_dbp, .tr__product .display_subtotal {line-height: 2.3}

    .tr__total .td__label, .tr__total .td__price {font-size: 1.25rem;}
    .tr__total .td__label {text-align: right;}
    .tr__total .td__price {font-weight: 700; text-align: left}

    .preview-img {width: 48px;height: 48px;padding: 4px;border: solid 1px #dbdbdb;display: inline-block;margin-right: 15px;}
    .preview-img img {max-width: 100%;max-height: 100%;position: relative;
        top: 50%;transform: translateY(-50%);}

    .items-table thead th:nth-child(3) {width: 30% !important;}
    /*.items-table .items-table-rrp-price::before, .items-table .items-table-dbp-price::before {content: "$";}*/

    #description-preview-modal .description-area {white-space: pre-line}
</style>

<script>
    $(document).ready(function () {
        var $target_tr = '';

        $(document).on('click', '.select-part-btn', function (e) {
            e.preventDefault();
            var category = $(this).data('category');
            $target_tr = $(this).parent().parent();

            if (category) {
                $('#category-select option[value="'+category+'"]').prop('selected', true);
            }
            $('#category-select').trigger('change');
            $('#select-part-modal').modal();
        });
        $(document).on('click', '.remove-part-btn', function (e) {
            e.preventDefault();
            var product_tr = $(this).parent().parent();
            if (product_tr.find('td.td__component').is(':empty')) {
                product_tr.remove();
            } else {
                product_tr.children().not('td:first, td:nth(1)').remove();
                var category = product_tr.data('component');
                var component = product_tr.data('class');

                var button = "<td class=\"td__addComponent\" colspan=\"7\">\n" +
                    "    <button class=\"btn btn-primary select-part-btn\" data-category='"+category+"'>\n" +
                    "        <i class=\"fa fa-plus\"></i>\n" +
                    "        Choose "+category+" \n" +
                    "    </button>\n" +
                    "</td>";

                product_tr.append(button);
                product_tr.removeClass();
                product_tr.addClass('tr__product');
            }

            UpdateQuoteTotal();
        });

        $('#category-select').on('change', function (e) {
            var category = $(this).val();

            $.ajax({
                type: "POST",
                url: "/ajax/get_subcategories",
                data: {category: category},
                dataType: 'json',
                beforeSend: function () {
                    Pace.restart();
                },
                success: function (data) {
                    if (data) {
                        var $options = '<option value="">Select Sub-Category</option>';
                        for (var i = 0; i < data.length; i++) {
                            $options += "<option value='"+data[i]+"'>"+data[i]+"</option>"
                        }
                        $('#subcategory-select').html($options);
                    }
                },
                fail: function (err) {
                    showFeedback('error', err.responseText);
                }
            });
        });

        $('.categories-select').on('change', function (e) {
            var category = $('#category-select').val(),
                subcategory = $('#subcategory-select').val();

            if ($.fn.dataTable.isDataTable('.items-table')) {
                $('.items-table').dataTable().fnDestroy();
            }
            $('.items-table').DataTable( {
                "serverSide": true,
                "processing": true,
                "searching": true,
                "ajax": {
                    "url": '/ajax/search_leaderItems',
                    "type": "POST",
                    "data": {category: category, subcategory: subcategory},
                    "complete": function (data) {
                    }
                },
                "order": [[ 4, "asc" ]],
                "columns": [{
                    "data": 0,
                    "render": function(data, type, full, meta) {
                        return full[1] ? "<a href='#' class='preview-img'><img src='"+full[1]+"'></a>" : '';
                    }}, {
                    "data": 1,
                    "render": function(data, type, full, meta) {
                        var data = full[7] ? "<a href='#' class='preview-description'>"+full[2]+"</a>" : full[2];
                        data += full[7] ? "<div class='items-table-item_description' style='display: none'>"+escape(full[7])+"</div>" : '';
                        return data;
                    }}, {
                    "data": 2,
                    "render": function (data, type, full, meta) {
                        return full[3];
                    }}, {
                    "data": 3,
                    "render": function (data, type, full, meta) {
                        return full[4];
                    }}, {
                    "data": 4,
                    "render": function (data, type, full, meta) {
                        var $returnVal = '<div>';
                        var eta_continue = true;

                        if (full[8] == 'CALL') {
                            if (full[13] !== null) {
                                $returnVal += full[13];
                                eta_continue = false;
                            } else {
                                $returnVal += 'CALL';
                            }
                        } else {
                            $returnVal += full[8] !== null ? full[8] : 'n/a';
                        }

                        if (eta_continue) {
                            $returnVal += ' - ';
                            if (full[9] == 'CALL') {
                                $returnVal += full[14] !== null ? full[14] : 'CALL';
                            } else {
                                $returnVal += full[9] !== null ? full[9] : 'n/a';
                            }
                            $returnVal += ' - ';
                            if (full[10] == 'CALL') {
                                $returnVal += full[15] !== null ? full[15] : 'CALL';
                            } else {
                                $returnVal += full[10] !== null ? full[10] : 'n/a';
                            }
                            $returnVal += ' - ';
                            if (full[11] == 'CALL') {
                                $returnVal += full[16] !== null ? full[16] : 'CALL';
                            } else {
                                $returnVal += full[11] !== null ? full[11] : 'n/a';
                            }
                            $returnVal += ' - ';
                            if (full[12] == 'CALL') {
                                $returnVal += full[17] !== null ? full[17] : 'CALL';
                            } else {
                                $returnVal += full[12] !== null ? full[12] : 'n/a';
                            }
                        }

                        $returnVal += '</div>';
                        return $returnVal;
                    }}, {
                    "data": 5,
                    "type": 'currency',
                    "render": function (data, type, full, meta) {
                        return full[5];
                    }}, {
                    "data": 6,
                    "type": 'currency',
                    "render": function (data, type, full, meta) {
                        return full[6];
                    }}, {
                    "data": 7,
                    "render": function (data, type, full, meta) {
                        return "<input type=\"number\" class=\"display_quantity number xx-small\" value=\"1\">";
                    }}, {
                    "data": 8,
                    "render": function (data, type, full, meta) {
                        return "<button title=\"Add\" type='button' class='add-item-to-quote component-control' data-item-id='"+full[0]+"'><i class=\"fa fa-plus \"></i> </button>\n"
                    }}
                ]
            } );

            jQuery.extend(jQuery.fn.dataTableExt.oSort, {
                "currency-pre": function (a) {
                    a = (a === "-") ? 0 : a.replace(/[^\d\-]/g, "");
                    return parseFloat(a);
                },
                "currency-asc": function (a, b) {
                    return a - b;
                },
                "currency-desc": function (a, b) {
                    return b - a;
                }
            });
        });

        $(document).on('click', '.add-item-to-quote', function (e) {
            var $item_id = $(this).data('item-id');
            var $qty = $(this).parent().parent().find('td .display_quantity').val();
            $('.modal').modal('hide');

            var item_exists = $('.quote-table tr.tr__product-' + $item_id);
            if (item_exists.length > 0) {
                $(item_exists).find('.display_quantity').val(function () {
                    return parseInt(this.value) + parseInt($qty);
                });
                UpdateQuoteTotal();
                return;
            }

            $.ajax({
                type: "POST",
                url: "/ajax/get_leaderItem",
                data: {id: $item_id},
                dataType: 'json',
                beforeSend: function () {
                    Pace.restart();
                },
                success: function (data) {
                    if (data !== false) {
                        var $html = '';
                        var td_compnent = $target_tr.find('.td__addComponent');
                        if (td_compnent.length == 0) {
                            $html += "<tr class=\"tr__product tr__product-row tr__product-"+$item_id+" "+$($target_tr).data('class')+"\" data-class='"+$($target_tr).data('class')+"'>\n";

                            if ($('.'+ $($target_tr).data('class') + ' .merge-component-btn').length >= 1) {
                                $html += "<td></td><td class=\"td__component\"></td>\n";
                            } else {
                                // var component_name = $('#'+ $($target_tr).data('class')).data('component');
                                // $html +=
                                //     "<td class=\"td__component\">" +
                                //     "<label>Merge <input type='checkbox' id='merge-"+$($target_tr).data('class')+"' class=\" merge-component-btn\" data-component='"+$($target_tr).data('class')+"' name='item_merge_component["+component_name+"]' value='"+component_name+"' title=\"Merge Parts\"></label>" +
                                //     "</td>\n";
                            }
                        } else {
                            $target_tr.addClass("tr__product-"+$item_id);
                            $target_tr.addClass('tr__product-row');
                            $target_tr.addClass($($target_tr).data('class'));
                        }

                        var rrp_percentage = data.DBP && data.RRP ? String(((data.RRP - data.DBP) / data.DBP) * 100).substring(0, 5) : 0;

                        $html += "<td colspan=\"3\">\n";
                        $html += data.IMAGE ?
                            "   <a href='#' class='preview-img'><img src='"+data.IMAGE+"'></a>" :
                            "";
                        $html += data.ProductName+"</td>\n";

                        $html +=
                            "      <td>\n" +
                            "            <div class=\"quantity\"><input type=\"number\" name='items["+data.id+"][item_qty]' class='display_quantity' value='"+$qty+"' autocomplete='quantity'></div>\n" +
                            "      </td>\n" +
                            "      <td class='display_dbp' data-dbp='"+data.DBP+"'>$"+formatMoney(parseFloat(data.DBP))+"</td>\n" +
                            "      <td class='rrp-percentage-td'>\n" +
                            "            <div class=\"money\"><input type=\"text\" data-rrp='"+data.RRP+"' name='items["+data.id+"][item_price]' class='' value='"+formatMoney(parseFloat(data.RRP))+"'></div>\n" +
                            "            <div class=\"percentage\"><input type=\"text\" name='items["+data.id+"][item_price_percent]' class='' value='"+rrp_percentage+"'></div>\n" +
                            "      </td>\n" +
                            "      <td>\n" +
                            "            <div class=\"display_subtotal\">$"+formatMoney(parseFloat(data.RRP) * parseInt($qty))+"</div>\n" +
                            "      </td>\n" +
                            "      <td>\n" +
                            "           <a href=\"#\" class=\"component-control select-part-btn\" data-action='add-more' data-category='"+$('#'+$target_tr.data('class')).find('a.select-part-btn').data('category') +"' title=\"Add More\"><i class=\"fa fa-plus\"></i></a>\n" +
                            "           <a href=\"#\" class=\"component-control remove-part-btn\" title=\"Remove\"><i class=\"fa fa-trash\"></i></a>\n" +
                            "       </td>\n" +
                            "      <input type='hidden' name='items["+data.id+"][component]' value='"+($target_tr).data('component')+"'>\n" +
                            "      <input type='hidden' name='items["+data.id+"][item_id]' value='"+data.id+"'>\n" +
                            "      <input type='hidden' name='items["+data.id+"][item_name]' value='"+data.ProductName+"'>\n" +
                            "      <input type='hidden' name='items["+data.id+"][item_original_price]' value='"+data.DBP+"'>\n";

                        if (td_compnent.length == 0) {
                            $html += "</tr>";
                        }

                        if (td_compnent.length == 0) {
                            $($target_tr).after($html);
                        } else {
                            td_compnent.replaceWith($html);
                        }
                        UpdateQuoteTotal();
                    } else {
                        showFeedback('error', "Sorry something wrong happened!");
                    }
                },
                fail: function (err) {
                    showFeedback('error', err.responseText);
                }
            });
        });


        $(document).on('click', '.merge-component-btn', function (e) {
            var $perc_inputs = $('.rrp-percentage-td .percentage input');
            var $total_margin = $('.tr__total--margin input').val() ? parseFloat($('.tr__total--margin input').val()) : 25;
            for (var i = 0; i < $perc_inputs.length; i++) {
                var parent_tr = $($perc_inputs[i]).parents('tr:first');
                var data_class = parent_tr.data('class');
                var $item_merge_button = $('#merge-'+data_class).length > 0 && $('#merge-'+data_class).is(':checked') ? true : false;

                if ($item_merge_button) {
                    $($perc_inputs[i]).val($total_margin);
                    parent_tr.find('.rrp-percentage-td').css({'visibility':'hidden'});
                } else {
                    parent_tr.find('.rrp-percentage-td').css({'visibility':'visible'});
                }
            }
            $('.percentage input').trigger('change');
            UpdateQuoteTotal();
        });



        $(document).on('input mousewheel change', '.money input, .quantity input', function (e) {
            if ($(this).parent().hasClass('quantity')) {
                if ($(this).val() < 1) {
                    $(this).val(1);
                }
            }

            UpdateQuoteTotal();
        });

        $(document).on('input change', '.percentage input', function (e) {
            var $tr = $(this).closest('.tr__product');
            var dbp = Number(String($tr.find('.display_dbp').data('dbp')).replace(/[^0-9.-]+/g,""));
            var rrp_perc = parseFloat($tr.find('.percentage input').val());

            // Calc RRP price
            var rrp = (rrp_perc / 100) * dbp;
            $tr.find('.money input').val(String(parseFloat(rrp) + dbp).substring(0, 7));
            $tr.find('.money input').attr('data-rrp', String(parseFloat(rrp) + dbp).substring(0, 7));

            UpdateQuoteTotal();
        });

        $(document).on('input change', '.money input', function (e) {
            var $tr = $(this).closest('.tr__product');
            var dbp = Number(String($tr.find('.display_dbp').data('dbp')).replace(/[^0-9.-]+/g,""));
            var rrp = Number(String($tr.find('.money input').val()).replace(/[^0-9.-]+/g,""))

            // Calc RRP percentage
            var rrp_percentage = String(((rrp - dbp) / dbp) * 100).substring(0, 5);
            $tr.find('.percentage input').val(rrp_percentage);

            UpdateQuoteTotal();
        });

        $(document).on('input change', '.tr__total--margin input', function (e) {
            var margin = parseFloat($(this).val()) ? parseFloat($(this).val()) : 25;
            var dbp = parseFloat($('.tr__total--dbp input').val());

            if (dbp) {
                var rrp = (dbp * margin) / 100;
                $('.tr__total--sys_total .td__price input').val(rrp + dbp);
                UpdateQuoteTotal();
            }
        });

        $(document).on('focusout', '.tr__total--sys_total input', function (e) {
            var dbp = parseFloat($('.tr__total--dbp input').val());
            var rrp = parseFloat($('.tr__total--sys_total input').val());

            var margin = String(((rrp - dbp) / dbp) * 100).substring(0, 5);
            $('.tr__total--margin .td__price input').val(margin);
            UpdateQuoteTotal();
        });



        $(document).on('click', '.preview-img', function() {
            $('.modal-image-preview').attr('src', $(this).find('img').attr('src'));
            $('#image-preview-modal').modal();
        });

        $(document).on('click', '.preview-description', function (e) {
            var desciption = $(this).next('.items-table-item_description').html();
            desciption = desciption ? unescape(desciption) : '';
            $('#description-preview-modal .description-area').html(desciption);
            $('#description-preview-modal').modal();
        });




        // form submit
        var buttonpressed = '';
        $('button[type=submit]').on('click', function () {
            buttonpressed = $(this).attr('name')
        });

        $('#quote-form').on('submit', function (e) {
            if (buttonpressed == 'preview') {
                e.preventDefault();

                var form_data = $(this).serialize();
                $('.modal').modal('hide');

                $.ajax({
                    type: "POST",
                    url: "/quotes/quote_preview",
                    data: form_data,
                    dataType: 'json',
                    beforeSend: function () {
                        Pace.restart();
                    },
                    complete: function (xhr) {
                        console.log(xhr);
                        if (xhr.responseJSON) {
                            var data = xhr.responseJSON;
                            if (data.status !== false) {
                                var iframe_obj = "<html>\n" +
                                    "<body style='margin: 0'>\n" +
                                    "    <object data='" + data.result + "' type=\"application/pdf\" style='width: 100%;height: 100%;'>\n" +
                                    "        <embed src='" + data.result + "' type=\"application/pdf\" />\n" +
                                    "    </object>\n" +
                                    "</body>\n" +
                                    "</html>"

                                $('#quote_preview_iframe').contents().find('body').html(iframe_obj);

                                // $('#quote_preview_iframe').contents().find('body').html(data.result);
                                $('#quote-preview-modal').modal();
                            } else {
                                showFeedback('error', data.msg);
                            }
                        } else {
                            showFeedback('error', "Sorry something wrong happened!");
                        }
                    },
                    fail: function (err) {
                        showFeedback('error', err.responseText);
                    }
                });
            }
        });


        function UpdateQuoteTotal() {
            var total_dbp_el = $('.tr__total--dbp .td__price span'),
                total_dbp_in = $('.tr__total--dbp .td__price input'),

                total_margin_in = $('.tr__total--margin .td__price input').val() ? parseFloat($('.tr__total--margin .td__price input').val()) : 25,
                total_profit = $('.tr__total--profit .td__price'),

                total_sys_total_el = $('.tr__total--sys_total .td__price input'),

                subtotal_el = $('.tr__total--subtotal .td__price'),
                gst_el = $('.tr__total--gst .td__price'),
                total_el = $('.tr__total--final .td__price');

            var inputs = $('.tr__product-row');

            var total_dbp = 0, total = 0, subtotal = 0, gst = 0;
            for (var i = 0; i < inputs.length; i++) {
                var data_class = $(inputs[i]).data('class');
                var $item_merge_button = $('#merge-'+data_class).length > 0 && $('#merge-'+data_class).is(':checked') ? true : false;

                var dbp = $(inputs[i]).find('.display_dbp').data('dbp') ? Number(String($(inputs[i]).find('.display_dbp').data('dbp')).replace(/[^0-9.-]+/g,"")) : 0,
                    price = $(inputs[i]).find('.money input').val() ? Number(String($(inputs[i]).find('.money input').val()).replace(/[^0-9.-]+/g,"")) : 0,
                    qty = $(inputs[i]).find('.display_quantity').val() ? parseInt($(inputs[i]).find('.display_quantity').val()) : 1;

                var item_sub_total = $(inputs[i]).find('.display_subtotal');
                item_sub_total.html('$'+ formatMoney(price * qty));

                if ($item_merge_button) {
                    total_dbp += dbp * qty;
                } else {
                    total += (price * qty);
                }
            }


            var profit = (total_dbp * total_margin_in) / 100;
            var total_sys_total = parseFloat(total_dbp) + parseFloat(profit);
            total = total + total_sys_total;
            gst = total ? total / 10 : 0.00;
            subtotal = total && gst ? total - gst : 0.00;

            if (total_dbp) {
                total_dbp_el.html('$'+ formatMoney(parseFloat(total_dbp)));
                total_dbp_in.val(total_dbp);
            } else {
                total_dbp_el.html('$0.00');
            }
            if (profit) {
                // var profit = total_sys_total && total_dbp ? '$'+formatMoney(parseFloat(total_sys_total - total_dbp)) : '$0.00';
                // total_margin_in.val(total_margin);
                total_profit.html('$'+ formatMoney(profit));
            } else {
                // total_margin_in.val(0)
                total_profit.html('$0.00');
            }

            if (total_sys_total) {
                total_sys_total_el.val(formatMoney(total_sys_total));
            } else {
                total_sys_total_el.val('0.00');
            }
            if (subtotal) {
                subtotal_el.html('$'+ formatMoney(subtotal));
            } else {
                subtotal_el.html('$0.00');
            }
            if (gst) {
                gst_el.html('$'+ formatMoney(gst));
            } else {
                gst_el.html('$0.00');
            }
            if (total) {
                total_el.html('$'+ formatMoney(total));
            } else {
                total_el.html('$0.00');
            }
        }

    });
</script>
