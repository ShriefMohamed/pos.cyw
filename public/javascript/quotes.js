var current_page_num = 1, per_page = 20;
function reloadProducts(page) {
    current_page_num = page;
    $('#category-select').trigger('change');
}

$(document).ready(function () {
    var $target_tr = '';

    $('.first_name_input, .last_name_input, .email_input, .phone_number_input').on('input', function() {
        clearTimeout(this.delay);
        this.delay = setTimeout(function(){
            $(this).trigger('search_customers');
        }.bind(this), 800);
    }).on('search_customers', function() {
        var $target_group = $(this).closest('.form-group');

        var first_name_input = $('.first_name_input'),
            last_name_input = $('.last_name_input'),
            email_input = $('.email_input'),
            phone_number_input = $('.phone_number_input');

        if (first_name_input || last_name_input || email_input || phone_number_input) {
            $.ajax({
                type: "POST",
                url: "/ajax/quotes_search_customers",
                data: {
                    first_name_input: first_name_input.val(),
                    last_name_input: last_name_input.val(),
                    email_input: email_input.val(),
                    phone_number_input: phone_number_input.val()
                },
                dataType: 'json',
                beforeSend: function () {
                    Pace.restart();
                },
                complete: function (xhr) {
                    console.log(xhr)
                    if (xhr.responseJSON) {
                        var data = xhr.responseJSON;
                        if (data && data.length > 0) {
                            var html = '<div class="customers-search-results-dropdown select-dropdown" style="display: block;">\n' +
                                '    <ul class="list-drop">\n';

                            for (var i = 0; i < data.length; i++) {
                                html += '<li><a href="#" data-id="'+data[i].id+'">'+data[i].firstName+' '+data[i].lastName+'</a></li>\n';
                            }

                            html += '    </ul>\n' +
                                '</div>';
                            $target_group.append(html);
                        }
                    }
                },
                fail: function (err) {
                    showFeedback('error', err.responseText);
                }
            });
        } else {
            $('.customers-search-results-dropdown').remove();
        }
    });

    $(document).on('click', '.customers-search-results-dropdown li a', function (e) {
        $('#customer_user_id').remove();
        $('#customer_id').remove();

        var id = $(this).data('id');
        if (id) {
            var first_name_input = $('.first_name_input'),
                last_name_input = $('.last_name_input'),
                email_input = $('.email_input'),
                phone_number_input = $('.phone_number_input'),
                address_input = $('.address_input'),
                suburb_input = $('.suburb_input'),
                zip_input = $('.zip_input');

            $.ajax({
                type: "POST",
                url: "/ajax/quotes_get_customer/"+id,
                data: {},
                dataType: 'json',
                beforeSend: function () {
                    Pace.restart();
                },
                complete: function (xhr) {
                    if (xhr.responseJSON) {
                        var data = xhr.responseJSON;

                        var id_inputs = '<input type="hidden" name="customer_user_id" id="customer_user_id" value="'+data.id+'">';
                        id_inputs += '<input type="hidden" name="customer_id" id="customer_id" value="'+data.customer_id+'">';
                        $('.customer-info-container').append(id_inputs);

                        $(first_name_input).attr('readonly', true);
                        $(last_name_input).attr('readonly', true);
                        $('.first-name-group i').removeClass('hidden');
                        if (data.firstName) {first_name_input.val(data.firstName);}
                        if (data.lastName) {last_name_input.val(data.lastName);}
                        if (data.email) {email_input.val(data.email);}
                        if (data.phone) {phone_number_input.val(data.phone);}
                        if (data.address) {address_input.val(data.address);}
                        if (data.suburb) {suburb_input.val(data.suburb);}
                        if (data.zip) {zip_input.val(data.zip);}
                    }
                },
                fail: function (err) {
                    showFeedback('error', err.responseText);
                }
            });

        }

        $('.customers-search-results-dropdown').remove();
    });

    $(window).click(function() {
        $('.customers-search-results-dropdown').remove();
    });

    $(document).on('click', '.remove-attached-customer', function (e) {
        $('.remove-attached-customer').addClass('hidden');
        $('#customer_user_id').remove();
        $('#customer_id').remove();
        $('.customer-form-container input').val('');
        $('.first_name_input').attr('readonly', false);
        $('.last_name_input').attr('readonly', false);
    });



    $('.continue-to-component').on('click', function (e) {
       $('.customer-info-container').addClass('hidden');
       $('.component-container').removeClass('hidden');
    });

    $('.back-to-customer-info').on('click', function (e) {
        $('.customer-info-container').removeClass('hidden');
        $('.component-container').addClass('hidden');
    });

    $('#viewbuttongrid').on('click', function (e) {
        if (!$(this).hasClass('active')) {
            $(this).addClass('active');
        }
        $('#viewbuttonlist').removeClass('active');
        $('#gridviewlist').slideUp();
        $('#gridviewgrid').fadeIn();
        // $('#gridviewgrid').css({'display':'block'});
        // $('#gridviewlist').css({'display': 'none'});
    });
    $('#viewbuttonlist').on('click', function (e) {
        if (!$(this).hasClass('active')) {
            $(this).addClass('active');
        }
        $('#viewbuttongrid').removeClass('active');
        $('#gridviewgrid').slideUp();
        $('#gridviewlist').fadeIn();
    });

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

            var button = "<td class=\"td__addComponent\" colspan=\"8\">\n" +
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
    $(document).on('click', '.ajax-remove-part-btn', function (e) {
        e.preventDefault();
        var product_tr = $(this).parent().parent();
        var quote_id = $(this).data('quote-id');
        var quote_item_id = $(this).data('id');

        $.ajax({
            type: "POST",
            url: "/ajax/quote_item_delete",
            data: {id: quote_item_id, quote_id: quote_id},
            dataType: 'json',
            beforeSend: function () {
                Pace.restart();
            },
            success: function (data) {
                if (data != false) {
                    if (product_tr.find('td.td__component').is(':empty')) {
                        product_tr.remove();
                    } else {
                        product_tr.children().not('td:first, td:nth(1)').remove();
                        var category = product_tr.data('component');
                        var component = product_tr.data('class');

                        var button = "<td class=\"td__addComponent\" colspan=\"8\">\n" +
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
                } else {
                    showFeedback('error', "Couldn't remove item!");
                }
            },
            fail: function (err) {
                showFeedback('error', err.responseText);
            }
        });
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

        $.ajax({
            type: "POST",
            url: "/ajax/get_manufacturers",
            data: {category: category, subcategory: subcategory},
            dataType: 'json',
            beforeSend: function () {
                Pace.restart();
            },
            success: function (data) {
                if (data) {
                    var $options = '<option value="">Select Manufacturer</option>';
                    for (var i = 0; i < data.length; i++) {
                        $options += "<option value='"+data[i]+"'>"+data[i]+"</option>"
                    }
                    $('#manufacturer-select').html($options);
                }
            },
            fail: function (err) {
                showFeedback('error', err.responseText);
            }
        });
    });

    $('.filters-select').on('change ', function (e) {
        search_leaderItems();
    });


    $(document).on('click', '.add-item-to-quote', function (e) {
        var $item_id = $(this).data('item-id');
        var $qty = $(this).closest('.shop-grid-item').find('.display_quantity').val();
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
                        "      <input type='hidden' name='items["+data.id+"][item_sku]' value='"+data.StockCode+"'>\n" +
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


    $(document).on('input mousewheel change', '.money input', UpdateQuoteTotal);

    $(document).on('input change', '.tr__total--labor input', UpdateQuoteTotal);

    $(document).on('input mousewheel change', '.quantity input, .display_quantity', function (e) {
        // if ($(this).parent().hasClass('quantity')) {
        //
        // }
        if ($(this).val() < 1) {
            $(this).val(1);
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
        $tr.find('.money input').attr('value', String(parseFloat(rrp) + dbp).substring(0, 7));
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

    $('.price-inputs-with-keypress').on('keypress', function (e) {
        if(e.which == 13) {
            e.preventDefault();
            search_leaderItems(true);
        }
    });



    // form submit
    var buttonpressed = '';
    $('#quote-form button[type=submit]').on('click', function (e) {
        var fname = $('input[name="f_name"]').val(),
            lname = $('input[name="l_name"]').val(),
            email = $('input[name="email"]').val();

        if (!fname || !lname || !email) {
            $('.back-to-customer-info').trigger('click');
            showFeedback('error', "Customer info required!");
            e.preventDefault();
            return
        }
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
});


var selector = document.getElementById('nouislider-selector')
function priceSlider(min, max, current) {
    if (!current) {
        current = [min, max];
    }
    if (selector.noUiSlider !== undefined) {
        selector.noUiSlider.destroy();
    }

    var input0 = document.getElementById('input-with-keypress-0');
    var input1 = document.getElementById('input-with-keypress-1');
    var inputs = [input0, input1];

    // Initializing the slider and linking the input
    noUiSlider.create(selector, {
        start: current,
        connect: true,
        direction: 'ltr',
        format: wNumb({
            decimals: 0
        }),
        tooltips: [true, wNumb({ decimals: 1 })],
        range: {
            'min': 0,
            'max': max
        }
    });

    selector.noUiSlider.on('update', function (values, handle) {
        inputs[handle].value = '$'+formatMoney(values[handle]);
    });

    selector.noUiSlider.on('end', function (values, handle) {
        search_leaderItems(true);
    });
}

function search_leaderItems(price_range = false) {
    var category = $('#category-select').val(),
        subcategory = $('#subcategory-select').val(),
        manufacturer = $('#manufacturer-select').val(),
        search = $('.search-input').val(),
        sortBy = $('#sortby').val(),
        sortByType = $('#sortbytype').prop('checked'),
        showOnlyAvailable = $('#showonlyavailable').prop('checked');

    var minPrice, maxPrice;
    minPrice = $('#input-with-keypress-0').val().replace('$', '').replace(',', '');
    minPrice = minPrice ? parseInt(minPrice) : 0;
    maxPrice = $('#input-with-keypress-1').val().replace('$', '').replace(',', '');
    maxPrice = maxPrice ? parseInt(maxPrice) : 0;

    $.ajax({
        type: "POST",
        url: "/ajax/search_leaderItems/"+current_page_num+"/"+per_page,
        data: {
            category: category,
            subcategory: subcategory,
            manufacturer: manufacturer,
            search: search,
            minPrice: minPrice,
            maxPrice: maxPrice,
            selectedPrice: price_range,
            sortBy: sortBy,
            sortByType: sortByType,
            showOnlyAvailable: showOnlyAvailable
        },
        dataType: 'json',
        beforeSend: function () {
            Pace.restart();
        },
        complete: function (xhr) {
            console.log(xhr);
            if (xhr.responseJSON) {
                var data = xhr.responseJSON['data'];
                if (data !== false) {
                    // reload noUiSlider
                    if (xhr.responseJSON['max_price'] && (parseInt(xhr.responseJSON['max_price']) > 0)) {
                        var min_price = 0, max_price = parseInt(xhr.responseJSON['max_price']);
                        if (xhr.responseJSON['current_price']) {
                            if (parseInt(xhr.responseJSON['current_price'].min) > 0) {
                                min_price = parseInt(xhr.responseJSON['current_price'].min);
                            }
                            if (parseInt(xhr.responseJSON['current_price'].max) > 0) {
                                max_price = parseInt(xhr.responseJSON['current_price'].max);
                            }
                        }
                        priceSlider(0, parseInt(xhr.responseJSON['max_price']), [min_price, max_price]);
                    }

                    var $grid_list_html = "", $grid_hml = "";
                    for (var i = 0; i < data.length; i++) {
                        $grid_list_html += "<tr class='shop-grid-item'>\n" +
                            "   <td>\n";
                        $grid_list_html += data[i].IMAGE ?
                            "       <a href=\"#\" class=\"preview-img\"><img src=\""+data[i].IMAGE+"\"></a>\n" :
                            "";
                        $grid_list_html +=
                            "   </td>\n" +
                            "   <td>\n";
                        $grid_list_html += data[i].Description ?
                            "       <a href=\"#\" class=\"preview-description\">"+(data[i].Manufacturer ? data[i].Manufacturer : '') + data[i].ProductName+"</a>\n" +
                            "       <div class=\"items-table-item_description\" style=\"display: none\">"+escape(data[i].Description)+"</div>\n" :
                            (data[i].Manufacturer ? data[i].Manufacturer : '') + data[i].ProductName;
                        $grid_list_html +=
                            "   </td>\n" +

                            "   <td>"+data[i].StockCode+"</td>\n" +
                            "   <td class=\"sorting_1\">\n" +
                            '      <div>\n';
                        var eta_continue = true;
                        if (data[i].AA == 'CALL') {
                            if (data[i].ETAA !== null) {
                                $grid_list_html += data[i].ETAA;
                                eta_continue = false;
                            } else {
                                $grid_list_html += 'CALL';
                            }
                        } else {
                            $grid_list_html += data[i].AA !== null ? data[i].AA : 'n/a';
                        }
                        if (eta_continue) {
                            $grid_list_html += ' - ';
                            if (data[i].AQ == 'CALL') {
                                $grid_list_html += data[i].ETAQ !== null ? data[i].ETAQ : 'CALL';
                            } else {
                                $grid_list_html += data[i].AQ !== null ? data[i].AQ : 'n/a';
                            }
                            $grid_list_html += ' - ';
                            if (data[i].AN == 'CALL') {
                                $grid_list_html += data[i].ETAN !== null ? data[i].ETAN : 'CALL';
                            } else {
                                $grid_list_html += data[i].AN !== null ? data[i].AN : 'n/a';
                            }
                            $grid_list_html += ' - ';
                            if (data[i].AV == 'CALL') {
                                $grid_list_html += data[i].ETAV !== null ? data[i].ETAV : 'CALL';
                            } else {
                                $grid_list_html += data[i].AV !== null ? data[i].AV : 'n/a';
                            }
                            $grid_list_html += ' - ';
                            if (data[i].AW == 'CALL') {
                                $grid_list_html += data[i].ETAW !== null ? data[i].ETAW : 'CALL';
                            } else {
                                $grid_list_html += data[i].AW !== null ? data[i].AW : 'n/a';
                            }
                        }
                        $grid_list_html +=
                            '       </div>\n'+
                            "   </td>\n" +
                            "   <td>$"+(data[i].DBP ? formatMoney(data[i].DBP) : '0' )+"</td>\n" +
                            "   <td>$"+(data[i].RRP ? formatMoney(data[i].RRP) : '0')+"</td>\n" +
                            "   <td><input type=\"number\" class=\"display_quantity number xx-small\" value=\"1\"></td>\n" +
                            "   <td><button title=\"Add\" type=\"button\" class=\"add-item-to-quote component-control\" data-item-id=\""+data[i].id+"\"><i class=\"fa fa-plus \"></i> </button>\n" +
                            "   </td>\n" +
                            "</tr>"


                        //grid
                        $grid_hml += "<div class=\"col-md-2 col-sm-3 shop-grid-item\">\n" +
                            "    <div class=\"product-slide-entry\">\n" +
                            "        <div class=\"product-image\" style=\"line-height: 180px;\">\n" +
                            "            <a href=\"#\">";
                        $grid_hml += data[i].IMAGE ?
                            "<img src=\""+data[i].IMAGE+"\">\n" :
                            "<img src=\"/images/default-quote-product-image.jpg\">\n";
                        $grid_hml += " </a>\n" +
                            "            <div class=\"bottom-line\">\n" +
                            "                <a href=\"#\" title=\"Add\" class=\"bottom-line-a add-item-to-quote \" data-item-id=\""+data[i].id+"\"><i class=\"fa fa-plus\"></i>Add to Quote</a>\n" +
                            "            </div>\n" +
                            "        </div>\n";
                        $grid_hml += data[i].Description ?
                            "<a class=\"tag3 preview-description\" href=\"#\">"+(data[i].Manufacturer ? data[i].Manufacturer : '') + data[i].ProductName+"</a>\n" +
                            "<div class=\"items-table-item_description\" style=\"display: none\">"+escape(data[i].Description)+"</div>\n" :
                            "<a class=\"tag3 preview-description\" href=\"#\">"+(data[i].Manufacturer ? data[i].Manufacturer : '') + data[i].ProductName+"</a>\n";
                        $grid_hml += "<a class=\"tag\" href=\"#\" style=\"display:table; font-size:12px;\">"+data[i].StockCode+"</a>\n" +
                            "        <div class=\"price\">\n" +
                            "            <div class=\"current\">\n" +
                            "                DBP: $"+(data[i].DBP ? formatMoney(data[i].DBP) : '0' )+"\n" +
                            "                <div class=\"dbp\">&nbsp; inc GST</div>\n" +
                            "            </div>\n" +
                            "            <div class=\"current\">\n" +
                            "                RRP: $"+(data[i].RRP ? formatMoney(data[i].RRP) : '0' )+"\n" +
                            "                <div class=\"dbp\">&nbsp; inc GST</div>\n" +
                            "            </div>\n" +
                            "            <div class=\"availability-categoriesnew\">\n" +
                            "                <a href=\"#\" class=\"state-categoriesnew\" style=\"float:left; clear:left; padding-right: 1px;\">Adelaide:</a>\n" +
                            "                <a href=\"#\" class=\"availability-numbernew\" style=\"float:left;\">";
                        if (data[i].AA == 'CALL') {
                            $grid_hml += data[i].ETAA ?
                                data[i].ETAA :
                                "<div class=\"call-imageleader\"><img style=\"height: 13px;\" src=\"/images/call.png\"></div>\n";
                        } else {
                            $grid_hml += data[i].AA !== null ? data[i].AA : 'n/a';
                        }
                        $grid_hml +=
                            "                </a>\n" +
                            "                <a href=\"#\" class=\"state-categoriesnew\" style=\"float:left; clear:left; padding-right: 1px;\">Sydney:</a>\n" +
                            "                <a href=\"#\" class=\"availability-numbernew\" style=\"float:left;\">\n";
                        if (data[i].AQ == 'CALL') {
                            $grid_hml += data[i].ETAQ !== null ?
                                data[i].ETAQ :
                                "<div class=\"call-imageleader\"><img style=\"height: 13px;\" src=\"/images/call.png\"></div>\n";
                        } else {
                            $grid_hml += data[i].AQ !== null ? data[i].AQ : 'n/a';
                        }
                        $grid_hml +=
                            "                </a>\n" +
                            "                <a href=\"#\" class=\"state-categoriesnew\" style=\"float:left; clear:left; padding-right: 1px;\">Brisbane:</a>\n" +
                            "                <a href=\"#\" class=\"availability-numbernew\" style=\"float:left;\">\n";
                        if (data[i].AN == 'CALL') {
                            $grid_hml += data[i].ETAN !== null ?
                                data[i].ETAN :
                                "<div class=\"call-imageleader\"><img style=\"height: 13px;\" src=\"/images/call.png\"></div>\n";
                        } else {
                            $grid_hml += data[i].AN !== null ? data[i].AN : 'n/a';
                        }
                        $grid_hml +=
                            "                </a>\n" +
                            "                <a href=\"#\" class=\"state-categoriesnew\" style=\"float:left; clear:left; padding-right: 1px;\">Melbourne:</a>\n" +
                            "                <a href=\"#\" class=\"availability-numbernew\" style=\"float:left;\">\n";
                        if (data[i].AV == 'CALL') {
                            $grid_hml += data[i].ETAV !== null ?
                                data[i].ETAV :
                                "<div class=\"call-imageleader\"><img style=\"height: 13px;\" src=\"/images/call.png\"></div>\n";
                        } else {
                            $grid_hml += data[i].AV !== null ? data[i].AV : 'n/a';
                        }
                        $grid_hml +=
                            "                </a>\n" +
                            "                <a href=\"#\" class=\"state-categoriesnew\" style=\"float:left; clear:left; padding-right: 1px;\">Perth:</a>\n" +
                            "                <a href=\"#\" class=\"availability-numbernew\" style=\"float:left;\">";
                        if (data[i].AW == 'CALL') {
                            $grid_hml += data[i].ETAW !== null ?
                                data[i].ETAW :
                                "<div class=\"call-imageleader\"><img style=\"height: 13px;\" src=\"/images/call.png\"></div>\n";;
                        } else {
                            $grid_hml += data[i].AW !== null ? data[i].AW : 'n/a';
                        }
                        $grid_hml +=
                            "                </a>\n" +
                            "                <a href=\"#\" class=\"state-categoriesnew\" style=\"float:left; clear:left; padding-right: 1px;\">Add:</a>\n" +
                            "                <input class=\"txtnoredi display_quantity\" type=\"number\" min=\"1\" max=\"100\" step=\"1\" value=\"1\" style=\"float:left;height: 18px;font-size:12px; padding:0px;display: inline;border-radius:2px; border: 1px solid #9fc5e3; width:45px; text-align: center;\">\n" +
                            "                <button title=\"Add\" type=\"button\" class=\"add-item-to-quote component-control plus\" data-item-id=\""+data[i].id+"\"><i class=\"fa fa-plus \"></i> </button>\n" +
                            "            </div>\n" +
                            "        </div>\n" +
                            "    </div>\n" +
                            "    <div class=\"clear\"></div>\n" +
                            "</div>"
                    }

                    $('#gridviewgrid').html($grid_hml);
                    $('#gridviewlist .items-table tbody').html($grid_list_html);
                    if ($.fn.dataTable.isDataTable('.items-table')) {
                        $('.items-table').dataTable().fnDestroy();
                    }
                    var table = $('.items-table').DataTable({
                        "paging": false,
                        dom: 'Bfrtip',
                        buttons: [
                            'copy', 'csv', 'excel', 'print', 'colvis'
                        ]
                    });


                    //pagination
                    var pagination =
                        "        <div class=\"dataTables_info\" id=\"items-table_info\">Showing "+((((current_page_num * per_page) - per_page) === 0) ? 1 : (current_page_num * per_page) - per_page)+" to "+(((current_page_num * per_page) > xhr.responseJSON['total_records']) ? xhr.responseJSON['total_records'] : (current_page_num * per_page))+" of "+xhr.responseJSON['total_records']+" entries</div>\n" +
                        "        <div class=\"dataTables_paginate paging_simple_numbers\" id=\"items-table_paginate\">\n" +
                        "           <a class=\"paginate_button previous\" onclick='reloadProducts(1)' tabindex=\"-1\" id=\"items-table_previous\">Previous</a>\n" +
                        "               <span>\n";
                    var loop_limit = parseInt(xhr.responseJSON['total_pages']) > 10 ? 10 : parseInt(xhr.responseJSON['total_pages']);
                    for (var j = 1; j < loop_limit; j++) {
                        pagination += "<a onclick='reloadProducts("+(j)+")' class=\"paginate_button "+(j == current_page_num ? 'current' : '')+"\">"+(j)+"</a>";
                    }
                    pagination +=
                        "                </span>\n" +
                        "                <a class=\"paginate_button next\" onclick='reloadProducts("+(current_page_num + 1)+")' tabindex=\"0\" id=\"items-table_next\">Next</a>\n" +
                        "                <a class=\"paginate_button last\" onclick=\"reloadProducts("+(xhr.responseJSON['total_pages'])+")\" tabindex=\"0\" id=\"items-table_last\">Last</a>\n" +
                        "        </div>\n" +
                        "        <div class=\"clearfix\"></div>\n";
                    $('#products-pagination').html(pagination);
                } else {
                    // $('.modal').modal('hide')
                    showFeedback('error', "No items could be found or something wrong happened!");
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

function UpdateQuoteTotal() {
    var total_dbp_el = $('.tr__total--dbp .td__price span'),
        total_dbp_in = $('.tr__total--dbp .td__price input'),

        total_labor = $('.tr__total--labor input').val() ? parseFloat($('.tr__total--labor input').val()) : 0,

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
    total = total + total_sys_total + total_labor;
    gst = total ? total / 10 : 0.00;
    subtotal = total && gst ? total - (gst + total_labor) : 0.00;

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