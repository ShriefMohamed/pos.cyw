var $current_controller = window.location.pathname.split('/')[1];
var $current_action = window.location.pathname.split('/')[2];
var $current_param = window.location.pathname.split('/')[3];

/*Sale*/
$(document).ready(function () {
    $('#add_search_item_text').on('keypress',function(e) {
        if(e.which == 13) {
            e.preventDefault();
            $('#registerItemSearch').trigger('click');
        }
    });
    $('#find_customer_text').on('keypress',function(e) {
        if(e.which == 13) {
            e.preventDefault();
            $('#searchCustomerButton').trigger('click');
        }
    });

    $('#searchCustomerButton').on('click', function (e) {
        e.preventDefault();
        var $key_value = $('#find_customer_text').val();
        var $customer_adding_action = $(this).data('customer-action') ? $(this).data('customer-action') : 'sale_add';
        var $sale_id = $customer_adding_action == 'sale' ? $(this).data('sale-id') : 0;

        $.ajax({
            type: "POST",
            url: "/ajax/search_customers/" + $key_value,
            data: {key: $key_value},
            dataType: 'json',
            beforeSend: function () {
                Pace.restart();
            },
            success: function (data) {
                var $html = "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"g-datatable datatable table table-striped table-bordered customers-datatables\">\n" +
                    "    <thead>\n" +
                    "    <tr>\n" +
                    "        <th></th>\n" +
                    "        <th>Name</th>\n" +
                    "        <th>Company</th>\n" +
                    "        <th>Mobile/Phone</th>\n" +
                    "        <th>Email</th>\n" +
                    "    </tr>\n" +
                    "    </thead>\n" +
                    "    <tbody>\n";

                if (data !== false) {
                    for (var i = 0; i < data.length; i++) {
                        $html += "       <tr class=\"gradeX\">\n" +
                            "                <td>\n";
                        $html += $customer_adding_action == 'sale_add' ?
                            "                    <button title=\"Add\" type='button' class='btn' id='add-customer-to-sale' data-user-id='"+data[i].id+"' data-customer-id='"+data[i].customer_id+"' data-customer-name='"+data[i].firstName+" "+data[i].lastName+"'><i class=\"fa fa-plus \"></i> Add</button>\n" :
                            "                    <a href='/ajax/sale_add_customer/"+$sale_id+'/'+data[i].id+"?returnURL=pos/sale/"+$sale_id+"'><button title=\"Add\" type='button' class='btn'><i class=\"fa fa-plus \"></i> Add</button></a>\n";
                        $html +=
                            "                </td>\n" +
                            "                <td>\n" +
                            "                    <a title=\"Edit Record\" target=\"_blank\" href=\"/customers/customer/"+data[i].id+"\"><span class='customer-name'>"+data[i].firstName+" "+data[i].lastName+"</span></a>\n" +
                            "                </td>\n" +
                            "                <td>"+data[i].companyName+"</td>\n";

                        $html += data[i].phone2 !== null ? "<td>"+data[i].phone+" / "+data[i].phone2+"</td>\n" : "<td>"+data[i].phone+"</td>\n";
                        $html +=
                            "                <td>"+data[i].email+"</td>\n" +
                            "            </tr>\n";
                    }
                } else {
                    $html += "<tr>" +
                        "<td colspan='6' class='text-center'>Sorry no customers match your input ("+$key_value+")</td>" +
                        "<td class='hidden'></td>" +
                        "<td class='hidden'></td>" +
                        "<td class='hidden'></td>" +
                        "<td class='hidden'></td>" +
                        "<td class='hidden'></td>" +
                        "</tr> \n";
                }
                $html += "</tbody></table>";

                $('#find_customer_text').val('');
                $('#search-results-modal .modal-title').html('Customers containing "' + $key_value + '"');
                $('#search-results-modal .modal-body').html($html);
                InitDatatable('customers-datatables');
                $('#search-results-modal').modal();
            },
            fail: function (err) {
                showFeedback('error', err.responseText);
            }
        });
    });
    $('#registerItemSearch').on('click', function (e) {
        e.preventDefault();
        var $key_value = $('#add_search_item_text').val();
        var $item_adding_action = $(this).data('item-action') ? $(this).data('item-action') : 'sale_add';
        var $options_vendor_id;

        if ($current_action == 'vendor_return' || $current_action == 'vendor_return_add') {
            if ($('#vendor_id').val()) {
                $options_vendor_id = $('#vendor_id').val();
            }
        }

        $.ajax({
            type: "POST",
            url: "/ajax/search_items",
            data: {key: $key_value, vendor_id: $options_vendor_id},
            dataType: 'json',
            beforeSend: function () {
                Pace.restart();
            },
            complete: function (xhr) {
                var $html = "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"g-datatable datatable table table-striped table-bordered items-datatable\">\n" +
                    "    <thead>\n" +
                    "    <tr>\n" +
                    "        <th></th>\n" +
                    "        <th>Item</th>\n" +
                    "        <th>Qty.</th>\n" +
                    "        <th>Price</th>\n" +
                    "        <th>Tax</th>\n" +
                    "        <th>Category</th>\n" +
                    "    </tr>\n" +
                    "    </thead>\n" +
                    "    <tbody>\n";

                if (xhr.responseJSON) {
                    var data = xhr.responseJSON;
                    for (var i = 0; i < data.length; i++) {
                        $html += "       <tr class=\"gradeX\">\n";

                        $html += $item_adding_action == 'sale_add' ?
                            "                <td>\n" +
                            "                    <button title=\"Add\" type='button' class='btn' id='add-item-to-sale' data-item-id='"+data[i].item_o_id+"'><i class=\"fa fa-plus \"></i> Add</button>\n" +
                            "                    <button title=\"Refund\" type='button' class='btn' id='add-item-to-sale' data-item-id='"+data[i].item_o_id+"' data-refund='1'><i class=\"fa fa-minus \"></i> Refund</button>\n" +
                            "                </td>\n" :
                            "                <td>\n" +
                            "                    <button title=\"Add\" type='button' class='btn add-item-to-"+$item_adding_action+"' data-item-id='"+data[i].item_o_id+"'><i class=\"fa fa-plus \"></i> Add</button>\n" +
                            "                </td>\n";

                        $html +=
                            "                <td>\n" +
                            "                    <a title=\"Edit Record\" target=\"_blank\" href=\"/pos/item/"+data[i].item_o_id+"\"><span>"+data[i].item+"</span></a>\n" +
                            "                </td>\n";
                        $html +=
                            "                <td>"+(data[i].available_stock ? data[i].available_stock : 0)+"</td>\n"
                        $html +=
                            "                <td>$"+data[i].rrp_price+"</td>\n";

                        if (data[i].tax_class == '0') {
                            $html += "   <td>None</td>\n";
                        } else {
                            $html += "   <td>"+data[i].class+' ('+data[i].rate+'%)'+"</td>\n";
                        }

                        $html += data[i].category_name ?
                            "            <td><span>"+data[i].category_name+"</span></td></tr>\n" :
                            "            <td><span>None</span></td></tr>\n";
                    }
                } else {
                    $html += "<tr>" +
                        "<td colspan='6' class='text-center'>Sorry no items match your input ("+$key_value+")</td>" +
                        "<td class='hidden'></td>" +
                        "<td class='hidden'></td>" +
                        "<td class='hidden'></td>" +
                        "<td class='hidden'></td>" +
                        "<td class='hidden'></td>" +
                        "</tr> \n";
                }
                $html += "</tbody></table>";

                $('#add_search_item_text').val('');
                $('#search-results-modal .modal-title').html('Items containing "' + $key_value + '"');
                $('#search-results-modal .modal-body').html($html);
                InitDatatable('items-datatable');
                $('#search-results-modal').modal();
            },
            fail: function (err) {
                showFeedback('error', err.responseText);
            }
        });
    });


    $(document).on('click', '#add-item-to-sale', function (e) {
        var $item_id = $(this).data('item-id');
        var refund = !!$(this).data('refund');
        $('.modal').modal('hide');
        $('#deleteAllButton').removeClass('hidden');

        var item_exists = refund ? $('#register_transaction tr.refund-item-' + $item_id) : $('#register_transaction tr.sale-item-' + $item_id);
        if (item_exists.length > 0) {
            if (refund) {
                $(item_exists).find('.display_quantity').val(function () {
                    return parseInt(this.value) - 1
                });
            } else {
                $(item_exists).find('.display_quantity').val(function () {
                    return parseInt(this.value) + 1
                });
            }
            UpdateTotal();
            return;
        }
        $.ajax({
            type: "POST",
            url: "/ajax/get_item",
            data: {id: $item_id},
            dataType: 'json',
            beforeSend: function () {
                Pace.restart();
            },
            success: function (data) {
                if (data !== false) {
                    if (refund == false) {
                        if (data.available_stock == null || parseInt(data.available_stock) <=0) {
                            showFeedback('error', "Sorry selected item has no stock!");
                            return;
                        }
                    }
                    AddItemToSale(data, 1, refund);
                } else {
                    showFeedback('error', "Sorry something wrong happened!");
                }
            },
            fail: function (err) {
                showFeedback('error', err.responseText);
            }
        });
    });
    $(document).on('click', '#add-customer-to-sale', function (e) {
        var user_id = $(this).data('user-id'),
            customer_id = $(this).data('customer-id'),
            customer_name = $(this).data('customer-name');

        $('.modal').modal('hide');
        $('.register-customer #customer-id-holder').val(user_id);
        $('.register-customer #user-customer-id-holder').val(customer_id);
        $('.register-customer #customerInfo').html(customer_name);
        $('.register-customer .search').css({'display':'none'});
        $('.register-customer #customerRemoveButton').css({'display':'inline'});
        $('.register-customer #customerShipButton').css({'display':'inline'});
    });
    $('#customerRemoveButton').on('click', function (e) {
        $('.register-customer #customer-id-holder').val('');
        $('.register-customer #customerInfo').html("No Customer Selected");

        $('.register-customer .search').css({'display':'inline-block'});
        $('.register-customer #customerRemoveButton').css({'display':'none'});

        $('.register-customer #customerShipButton').css({'display':'none'});
        $('.table-address-wrapper').empty();
        if (!$('#sale-ship-wrapper').hasClass('hidden')) {
            $('#sale-ship-wrapper').addClass('hidden');
        }
    });
    $('#customerShipButton, #customerShipSaveButton').on('click', function (e) {
        if ($('#sale-ship-wrapper').hasClass('hidden')) {
            if ($('.table-address-wrapper').is(':empty')) {
                if ($('.register-customer #customer-id-holder').val()) {
                    var customer_id = $('.register-customer #customer-id-holder').val();
                    $.ajax({
                        type: "POST",
                        url: "/ajax/get_customer",
                        data: {id: customer_id},
                        dataType: 'json',
                        beforeSend: function () {
                            Pace.restart();
                        },
                        success: function (data) {
                            var $html = "<table class=\"table-address\">\n" +
                                "    <input type=\"hidden\" name=\"register-customer-shipping\" value=\"1\">\n" +
                                "    <tbody>\n" +
                                "    <tr>\n" +
                                "        <td class=\"table-address__label label\"><label for=\"f_name\">First Name</label></td>\n" +
                                "        <td class=\"table-address__value\">\n" +
                                "            <input id=\"edit_ship_first_name\" name=\"f_name\" class=\"ship_to_data\" tabindex=\"60\" placeholder=\"First Name\" type=\"text\" autocomplete=\"off\" size=\"30\" maxlength=\"255\" value='"+data.firstName+"' style=\"height: 32px; line-height: 32px;\">\n" +
                                "        </td>\n" +
                                "    </tr>\n" +
                                "    <tr>\n" +
                                "        <td class=\"table-address__label label\"><label for=\"l_name\">Last Name</label></td>\n" +
                                "        <td class=\"table-address__value\">\n" +
                                "            <input id=\"l_name\" name=\"l_name\" class=\"ship_to_data\" tabindex=\"61\" placeholder=\"Last Name\" type=\"text\" autocomplete=\"off\" size=\"30\" maxlength=\"255\" value='"+data.lastName+"' style=\"height: 32px; line-height: 32px;\">\n" +
                                "        </td>\n" +
                                "    </tr>\n" +
                                "    <tr>\n" +
                                "        <td class=\"table-address__label label\"><label for=\"company\">Company</label></td>\n" +
                                "        <td class=\"table-address__value\">\n" +
                                "            <input id=\"company\" name=\"company\" class=\"ship_to_data\" tabindex=\"62\" placeholder=\"Company\" type=\"text\" autocomplete=\"off\" size=\"30\" maxlength=\"255\" value='"+(data.companyName ? data.companyName : '')+"' style=\"height: 32px; line-height: 32px;\">\n" +
                                "        </td>\n" +
                                "    </tr>\n" +
                                "    <tr>\n" +
                                "        <td class=\"table-address__label label\"><label for=\"address1\">Address</label></td>\n" +
                                "        <td class=\"table-address__value\">\n" +
                                "            <input id=\"address1\" name=\"address\" class=\"ship_to_data\" tabindex=\"64\" placeholder=\"Address\" type=\"text\" autocomplete=\"off\" size=\"30\" maxlength=\"255\" value='"+(data.address ? data.address : '')+"' style=\"height: 32px; line-height: 32px;\">\n" +
                                "        </td>\n" +
                                "    </tr>\n" +
                                "    <tr>\n" +
                                "        <td class=\"table-address__label label\"><label for=\"address2\">Address 2</label></td>\n" +
                                "        <td class=\"table-address__value\">\n" +
                                "            <input id=\"address2\" name=\"address2\" class=\"ship_to_data\" tabindex=\"65\" placeholder=\"Address 2\" type=\"text\" autocomplete=\"off\" size=\"30\" maxlength=\"255\" value='"+(data.address2 ? data.address2 : '')+"' style=\"height: 32px; line-height: 32px;\">\n" +
                                "        </td>\n" +
                                "    </tr>\n" +
                                "    <tr>\n" +
                                "        <td class=\"table-address__label label\"><label for=\"city\">City</label></td>\n" +
                                "        <td class=\"table-address__value\">\n" +
                                "            <input id=\"city\" name=\"city\" class=\"ship_to_data\" tabindex=\"66\" placeholder=\"City\" type=\"text\" autocomplete=\"off\" size=\"30\" maxlength=\"255\" value='"+(data.city ? data.city : '')+"' style=\"height: 32px; line-height: 32px;\">\n" +
                                "        </td>\n" +
                                "    </tr>\n" +
                                "    <tr>\n" +
                                "        <td class=\"table-address__label label\"><label for=\"state\">Suburb</label></td>\n" +
                                "        <td class=\"table-address__value\">\n" +
                                "            <input type=\"text\" name=\"suburb\" autocomplete=\"off\" size=\"30\" maxlength=\"255\" id=\"state\" tabindex=\"67\" placeholder=\"Suburb\" class=\"ship_to_data\" value='"+(data.suburb ? data.suburb : '')+"' style=\"height: 32px; line-height: 32px;\">\n" +
                                "        </td>\n" +
                                "    </tr>\n" +
                                "    <tr>\n" +
                                "        <td class=\"table-address__label label\"><label for=\"zip\">Postcode</label></td>\n" +
                                "        <td class=\"table-address__value\">\n" +
                                "            <input id=\"zip\" name=\"zip\" class=\"ship_to_data\" tabindex=\"68\" placeholder=\"Postcode\" type=\"text\" autocomplete=\"off\" size=\"30\" maxlength=\"255\" value='"+(data.zip ? data.zip : '')+"' style=\"height: 32px; line-height: 32px;\">\n" +
                                "        </td>\n" +
                                "    </tr>\n" +
                                "    <tr>\n" +
                                "        <td class=\"table-address__label label\"><label for=\"phone_home\">Contact Phone</label></td>\n" +
                                "        <td class=\"table-address__value\">\n" +
                                "            <input id=\"phone_home\" name=\"phone\" class=\"ship_to_data\" tabindex=\"69\" placeholder=\"Contact Phone\" type=\"text\" autocomplete=\"off\" size=\"30\" maxlength=\"255\" value='"+(data.phone ? data.phone : '')+"' style=\"height: 32px; line-height: 32px;\">\n" +
                                "        </td>\n" +
                                "    </tr>\n" +
                                "    <tr>\n" +
                                "        <td class=\"table-address__label label\"><label for=\"mobile_home\">Mobile Number</label></td>\n" +
                                "        <td class=\"table-address__value\">\n" +
                                "            <input id=\"mobile_home\" name=\"mobile\" class=\"ship_to_data\" tabindex=\"69\" placeholder=\"Mobile Number\" type=\"text\" autocomplete=\"off\" size=\"30\" maxlength=\"255\" value='"+(data.phone2 ? data.phone2 : '')+"' style=\"height: 32px; line-height: 32px;\">\n" +
                                "        </td>\n" +
                                "    </tr>\n" +
                                "    </tbody>\n" +
                                "</table>";

                            $('.table-address-wrapper').html($html);
                            $('#sale-ship-wrapper').removeClass('hidden');
                        },
                        fail: function (err) {
                            showFeedback('error', err.responseText);
                        }
                    });
                }
            } else {
                $('#sale-ship-wrapper').removeClass('hidden');
            }
        } else {
            $('#sale-ship-wrapper').addClass('hidden');
        }
    });
    $('#customerShipCancelButton').on('click', function (e) {
        $('.table-address-wrapper').empty();
        if (!$('#sale-ship-wrapper').hasClass('hidden')) {
            $('#sale-ship-wrapper').addClass('hidden');
        }
    });

    $('#newCustomerForm').on('submit', function (e) {
        e.preventDefault();
        $('.modal').modal('hide');

        var formData = $(this).serialize();
        $.ajax({
            type: "POST",
            url: "/ajax/customer_add",
            data: formData,
            dataType: 'json',
            beforeSend: function () {
                Pace.restart();
            },
            success: function (data) {
                if (data !== false) {
                    $('.register-customer #customer-id-holder').val(data.id);
                    $('.register-customer #customerInfo').html(data.firstName+' '+data.lastName);
                    $('.register-customer .search').css({'display':'none'});
                    $('.register-customer #customerRemoveButton').css({'display':'inline'});
                } else {
                    showFeedback('error', "Failed to create new customer!");
                }
            },
            fail: function (err) {
                showFeedback('error', err.responseText);
            }
        });
    });

    $(document).on('input change mousewheel', '.item-row .display_quantity', function (e) {
        if ($(this).closest('tr').hasClass('refund')) {
            if ($(this).val() > -1) {
                $(this).val(-1);
            }
        } else {
            var $stock = $(this).closest('tr').data('item-stock');
            $stock = $stock ? parseInt($stock) : 0;
            if ($(this).val() > $stock) {
                $(this).val($stock);
                showFeedback('error', "Selected item's stock is <strong>"+$stock+"</strong>.");
            }

            if ($(this).val() < 1) {
                $(this).val(1);
            }
        }
        UpdateTotal();
    });


    $(document).on('click', '#apply-discount-button', function (e) {
        // var discount = $(e.target).parent().parent().find('.modal-body select').val();
        ApplyDiscount();
    });

    $(document).on('change', '#pricing-level', function (e) {
        var price_level_id = $('#pricing-level').val();
        if (price_level_id !== '0') {
            var selected = $('#pricing-level').find('option:selected');
            var price_level = selected.data('rate');
            var price_tier = selected.data('teir');
        }

        var items = $('#register_transaction .item-row');
        for (var i = 0; i < items.length; i++) {
            var price_item = $(items[i]).find('.display_price');

            var dbp = parseFloat($(items[i]).find('.display_price').data('dbp'));
            var rrp_percentage = parseInt($(items[i]).find('.display_price').data('rrp-percentage'));

            var level_rate = 0;
            if (price_level_id == '0') {
                level_rate = rrp_percentage;
            } else {
                level_rate = price_tier == 'teir 2' ? rrp_percentage / 2 : price_level;
            }

            var item_price = parseFloat(String((level_rate / 100) * dbp).substring(0, 5)) + parseFloat(dbp);
            price_item.html('$'+ item_price);
            price_item.data('price', item_price);
        }

        ApplyDiscount();
        UpdateTotal();
    });

    $(document).on('click', '.remove-item-row', function (e) {
        $(this).closest('.item-row').remove();

        if ($('#register_transaction .item-row').length == 0) {
            $('#deleteAllButton').addClass('hidden');
        }

        UpdateTotal();
    });


    $('#deleteAllButton').on('click', function (e) {
        if (confirm("Are you sure you want to delete all lines from this sale?")) {
            $('#register_transaction').html('');
            $('#deleteAllButton').addClass('hidden');
        }
    });

    $('#saveAsQuoteButton').on('click', function (e) {
        if ($('#customer-id-holder').val() == "") {
            e.preventDefault();
            showFeedback('error', "You need a customer to save this quote. Please find or create a customer and then press 'Save as Quote' again.\n");
        }
    });

    $('#paymentButton').on('click', function (e) {
        if ($('#register_transaction .item-row').length == 0) {
            e.preventDefault();
            showFeedback('error', "No items were selected! Please find or create items and then press 'Payment' again.\n");
        }
    });



    $('#notesButton').on('click', function (e) {
        if ($('#sale-note-wrapper').hasClass('hidden')) {
            $('#notesButton').html('Hide Notes');
            $('#sale-note-wrapper').removeClass('hidden');
        } else {
            $('#notesButton').html('Show Notes');
            $('#sale-note-wrapper').addClass('hidden');
        }
    });

    $('.add-time-button').on('click', function (e) {
        $('#internal_note').val(function () {
            return this.value + '(' + new Date().toLocaleString('au') + ')';
        });
    });

    $('#miscButton').on('click', function (e) {
        $.ajax({
            type: "POST",
            url: "/ajax/get_taxClasses",
            dataType: 'json',
            beforeSend: function () {
                Pace.restart();
            },
            success: function (data) {
                var $html =
                    "<h2 id=\"chargeTitle\">Add Miscellaneous Charge</h2>\n" +
                    "<table id=\"add_expander\">\n" +
                    "    <tbody>\n" +
                    "    <tr>\n" +
                    "        <td rowspan=\"4\" class=\"note\">\n" +
                    "            <textarea class=\"add_expander error_no_empty_notes\" tabindex=\"2000\" cols=\"25\" rows=\"5\" id=\"add_misc_description\">Miscellaneous Charge</textarea>\n" +
                    "        </td>\n" +
                    "        <td><label>Price</label></td>\n" +
                    "        <td>\n" +
                    "            <div class=\"money-field-container money-line-edit\">\n" +
                    "                <input type=\"text\" id='add_misc_price' class=\"add_expander money\" tabindex=\"2001\" size=\"6\" maxlength=\"15\" value=\"0.00\">\n" +
                    "            </div>\n" +
                    "        </td>\n" +
                    "    </tr>\n" +
                    "    <tr>\n" +
                    "        <td><label>Cost</label></td>\n" +
                    "        <td>\n" +
                    "            <div class=\"money-field-container money-line-edit\">\n" +
                    "                <input id='add_misc_cost' type=\"text\" class=\"add_expander money\" tabindex=\"2002\" size=\"6\" maxlength=\"15\" value=\"0.00\" step=\".01\">\n" +
                    "            </div>\n" +
                    "        </td>\n" +
                    "        <td><label>Tax Class</label></td>\n" +
                    "        <td>\n" +
                    "            <select id='add_misc_class' class=\"add_expander\" tabindex=\"2006\">\n";
                if (data.taxClasses) {
                    for (var i = 0; i < data.taxClasses.length; i++) {
                        $html += "                <option value='"+data.taxClasses[i].id+"'>"+data.taxClasses[i].class+" ("+data.taxClasses[i].rate+"%)"+"</option>\n";
                    }
                }
                $html +=
                    "            </select>\n" +
                    "        </td>\n" +
                    "    </tr>\n" +
                    "    <tr>\n" +
                    "        <td><label>Qty</label></td>\n" +
                    "        <td><input id='add_misc_qty' type=\"number\" class=\"add_expander number\" tabindex=\"2003\" size=\"6\" maxlength=\"15\" value=\"1\"></td>\n" +
                    "    </tr>\n" +
                    "    <tr>\n" +
                    "        <td></td>\n" +
                    "        <td colspan=\"3\"></td>\n" +
                    "    </tr>\n" +
                    "    <tr>\n" +
                    "        <td colspan=\"4\" class=\"submit\">\n" +
                    "            <button id=\"saveChargeButton\" type=\"button\" tabindex=\"2008\" class=\"gui-def-button\">Save</button>\n" +
                    "            <button id=\"cancelChargeButton\" type=\"button\" tabindex=\"2009\" class=\"gui-def-button\">Cancel</button>\n" +
                    "        </td>\n" +
                    "    </tr>\n" +
                    "    </tbody>\n" +
                    "</table>"

                $('#add_expander_holder').html($html);
            },
            fail: function (err) {
                showFeedback('error', err.responseText);
            }
        });
    });

    $(document).on('click', '#cancelChargeButton', function (e) {
        $('#add_expander_holder').html('');
    });

    $(document).on('click', '#saveChargeButton', function (e) {
        var $misc_holder = $('#add_expander_holder');
        var item = $misc_holder.find('#add_misc_description').val(),
            tax_class = $misc_holder.find('#add_misc_class').val(),
            price = $misc_holder.find('#add_misc_price').val(),
            cost = $misc_holder.find('#add_misc_cost').val(),
            qty = $misc_holder.find('#add_misc_qty').val();

        $.ajax({
            type: "POST",
            url: "/ajax/item_misc_add",
            data: {item: item, tax_class: tax_class, price: price, cost: cost},
            dataType: 'json',
            beforeSend: function () {
                Pace.restart();
            },
            success: function (data) {
                if (data !== false) {
                    AddItemToSale(data, qty);
                } else {
                    showFeedback('error', "Failed to create misc item. Unknown error!");
                }
            },
            fail: function (err) {
                showFeedback('error', err.responseText);
            }
        });

        $('#add_expander_holder').html('');
    });
});


function ApplyDiscount() {
    var discount_id = $('#discount-select').val();
    var title = $('#discount-select').find('option:selected').data('title');
    var type = $('#discount-select').find('option:selected').data('type');
    var rate = $('#discount-select').find('option:selected').data('rate');
    if (type !== undefined && rate !== undefined) {
        // var sub_total = parseFloat($('#popdisplay_subtotal').html().replace('$', ''));
        // var sale_total = $('#sale_total');
        // var discount = type == 'fixed' ? rate : (sub_total * rate) / 100;
        // $('#discountValue').html('-$'+ discount);
        // sale_total.html('$'+ (sub_total - discount));


        var items = $('#register_transaction .item-row');
        for (var i = 0; i < items.length; i++) {
            var item_id = $(items[i]).data('item-id');
            var price_item = parseFloat($(items[i]).find('.display_price').html().replace('$', ''));
            var item_qty = parseInt($(items[i]).find('.display_quantity').val());

            var item_discount = $(items[i]).find('.display_item_name #discountDescription');
            var discount = type == 'fixed' ? rate : (price_item * rate) / 100;
            if (!item_discount.length) {
                var html = "<div id=\"discountDescription\" class=\"discount\" data-discount='"+discount+"'> Discount:" +
                    "   <span> "+title+" </span>" +
                    "   <span class='item-displayed-discount'>-$"+ (discount * item_qty) +"</span>" +
                    "   <input type='hidden' name='items["+item_id+"][discount]' value='"+discount_id+"'>"
                "</div>";
                $(items[i]).find('.display_item_name').append(html);
            } else {
                item_discount.data('discount', discount);
                item_discount.find('.item-displayed-discount').html("-$"+ discount);
            }
        }
        UpdateTotal();
    }
}

function UpdateTotal() {
    var items = $('#register_transaction .item-row'),
        sub_total = $('#popdisplay_subtotal'),
        discount = $('#discountValue'),
        total = $('#sale_total'),
        tax = $('#taxValue_1');

    var tmp_tax = parseFloat('0.00'), tmp_sub_total = parseFloat('0.00'), tmp_total = parseFloat('0.00'), tmp_discount = parseFloat('0.00');
    for (var i = 0; i < items.length; i++) {
        var price = parseFloat($(items[i]).find('.display_price').data('price')),
            qty = parseInt($(items[i]).find('.display_quantity').val().replace('-', '')),
            tax_rate = parseFloat($(items[i]).find('.display_tax').data('tax')),
            item_sub_total = $(items[i]).find('.display_subtotal');

        var discounted_price = price;
        var item_discount_el = $(items[i]).find('.display_item_name #discountDescription');
        if (item_discount_el.length) {
            var item_discount = parseFloat(item_discount_el.data('discount'));
            discounted_price = price - item_discount;
            tmp_discount += item_discount * qty;

            item_discount_el.find('.item-displayed-discount').html("-$"+ item_discount * qty);
        }

        tmp_sub_total = parseInt(tmp_sub_total) + (price * qty);
        tmp_total += discounted_price * qty;
        tmp_tax += (discounted_price * qty) * tax_rate / 100;
        item_sub_total.html('$' + (discounted_price * qty));
    }

    sub_total.html('$' + tmp_sub_total);
    discount.html('-$' + tmp_discount);
    total.html('$' + tmp_total);
    tax.html('$' + parseFloat(String(tmp_tax).substring(0, 5)));
}

function AddItemToSale(data, qty, refund = 0) {
    var item_price = data.rrp_price;

    var price_level_id = $('#pricing-level').val();
    if (price_level_id !== '0') {
        var selected = $('#pricing-level').find('option:selected');
        var price_level = selected.data('rate');
        var price_tier = selected.data('teir');

        var level_rate = price_tier == 'teir 2' ? data.rrp_percentage / 2 : price_level;

        item_price = parseFloat(String((level_rate / 100) * data.buy_price).substring(0, 5)) + parseFloat(data.buy_price);
    }

    var inputs_name_add = refund ? 'r' : 's';
    var $item_row = refund ?
        "<tr class=\"item-row item-"+data.item_o_id+" refund-item-"+data.item_o_id+" refund \" data-item-id='"+data.item_o_id+"'>\n" :
        "<tr class=\"item-row item-"+data.item_o_id+" sale-item-"+data.item_o_id+"\" data-item-id='"+data.item_o_id+"' data-item-stock='"+data.available_stock+"'>\n";

    $item_row +=
        "    <input type=\"hidden\" name=\"items["+inputs_name_add+data.item_o_id+"][id]\" value='"+data.item_o_id+"'>\n";
    $item_row += refund ?
        "    <input type=\"hidden\" name=\"items["+inputs_name_add+data.item_o_id+"][type]\" value='refund'>\n" :
        "    <input type=\"hidden\" name=\"items["+inputs_name_add+data.item_o_id+"][type]\" value='sale'>\n";
    $item_row +=
        "    <td class=\"row_controls\">\n" +
        "        <a href=\"#\" class=\"control remove-item-row\"><i class=\"fa fa-trash\"></i></a>\n" +
        "    </td>\n" +
        "    <td class=\"register-lines-control display_item_name\">\n" +
        "        <a href=\"/pos/item/"+data.item_o_id+"\"><span>"+data.item+"</span></a>\n" +
        "    </td>\n";
    $item_row += "    <td class=\"money\">$"+data.buy_price+"</td>\n";
    $item_row += refund ?
        "    <td class=\"display_price money\" data-price='-"+item_price+"' data-dbp='"+data.buy_price+"' data-rrp-percentage='"+data.rrp_percentage+"'>-$"+item_price+"</td>\n" :
        "    <td class=\"display_price money\" data-price='"+item_price+"' data-dbp='"+data.buy_price+"' data-rrp-percentage='"+data.rrp_percentage+"'>$"+item_price+"</td>\n";
    $item_row += refund ?
        "    <td>\n" +
        "        <input type=\"number\" name='items["+inputs_name_add+data.item_o_id+"][qty]' class=\"display_quantity number xx-small\" maxlength=\"8\" tabindex=\"3000\" value=\"-"+qty+"\">\n" +
        "    </td>\n" :
        "    <td>\n" +
        "        <input type=\"number\" name='items["+inputs_name_add+data.item_o_id+"][qty]' class=\"display_quantity number xx-small\" maxlength=\"8\" tabindex=\"3000\" value=\""+qty+"\">\n" +
        "    </td>\n";

    if (data.tax_class == '0') {
        $item_row +=
            "<td>None</td>\n";
    } else {
        $item_row +=
            "<td class='display_tax' data-tax='"+data.rate+"'>"+data.class+' ('+data.rate+'%)'+"</td>\n";
    }

    $item_row +=
        "    <td class=\"display_subtotal money\">-$"+parseFloat(item_price) * parseInt(qty)+"</td>\n" +
        "</tr>";

    $('#register_transaction').append($item_row);
    UpdateTotal();
}
/*End Sale*/


/*Quotes*/
function generatePO(quote_id) {
    var checkedVals = $('.order-component-btn:checked').map(function() {
        return this.value;
    }).get();

    $.ajax({
        type: "POST",
        url: "/pos/quote_purchase_order_generate/"+quote_id,
        data: {items: checkedVals.join(",")},
        dataType: 'json',
        beforeSend: function () {
            Pace.restart();
        },
        complete: function (xhr) {
            console.log(xhr);
            if (xhr.responseJSON) {
                var data = xhr.responseJSON;
                if (data.status !== false) {
                    $('#purchase-order-form').append("<input type='hidden' name='purchase-order' value='"+data.document+"'>");
                    reloadIFrame(data.result);
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

function reloadIFrame(pdf) {
    var iframe_obj = "<html>\n" +
        "<body style='margin: 0'>\n" +
        "    <object data='" + pdf + "' type=\"application/pdf\" style='width: 100%;height: 100%;'>\n" +
        "        <embed src='" + pdf + "' type=\"application/pdf\" />\n" +
        "    </object>\n" +
        "</body>\n" +
        "</html>";

    $('#po_preview_iframe').contents().find('body').html(iframe_obj);
}

$(document).ready(function () {
    $('.regenerate-purchase-order-btn').on('click', function (e) {
        e.preventDefault();
        if (confirm("Careful! using this function means that a purchase order was already sent! \nMake sure you clear any previous orders with vendor first to avoid duplicate orders.")) {
            window.location = $(this).attr('href');
        }
    });
});
/*End Quotes*/

/*Item Add*/
$(document).ready(function () {
    // On DBP change
    $('#view_default_cost').on('input', function (e) {
        if (parseInt($('#view_default_cost').val())) {
            var dbp = parseFloat($('#view_default_cost').val());
            var rrp_perc = parseFloat($('#view_default_percentage').val());

            // Calc default RRP price & margin
            var rrp = String((rrp_perc / 100) * dbp).substring(0, 6);
            $('.pricing-level-rrp .price input').val(parseFloat(rrp) + dbp);
            $('.pricing-level-rrp .margin').html('$'+rrp);

            // Loop through the other pricing levels and also calc the price & margin.
            var $levels = $('.pricing-level');
            if ($levels) {
                for (var i = 0; i < $levels.length; i++) {
                    if ($($levels[i]).hasClass('teir2')) {
                        var level_rate = rrp_perc / 2;
                    } else {
                        var level_rate = $($levels[i]).data('rate');
                    }
                    var level_price = String((level_rate / 100) * dbp).substring(0, 6);

                    $($levels[i]).find('.price input').val(String(parseFloat(level_price) + dbp).substring(0, 6));
                    $($levels[i]).find('.margin').html('$'+level_price);
                }
            }
        }
    });

    // On DBP Percentage change
    $('#view_default_percentage').on('input', function (e) {
        if (parseInt($('#view_default_cost').val())) {
            var dbp = parseFloat($('#view_default_cost').val());
            var rrp_perc = parseFloat($('#view_default_percentage').val());

            // Calc RRP price & margin
            var rrp = String((rrp_perc / 100) * dbp).substring(0, 6);
            $('.pricing-level-rrp .price input').val(parseFloat(rrp) + dbp);
            $('.pricing-level-rrp .margin').html('$'+rrp);

            // Calc Teir 2 price & margin
            var teir2 = $('.pricing-level.teir2');
            if (teir2) {
                var level_price = String(((rrp_perc / 2) / 100) * dbp).substring(0, 6);

                teir2.find('.price input').val(parseFloat(level_price) + dbp);
                teir2.find('.margin').html('$'+level_price);
            }
        }
    });

    // On RRP change
    $('.pricing-level-rrp .price input').on('input', function (e) {
        if (parseInt($('#view_default_cost').val())) {
            var dbp = parseFloat($('#view_default_cost').val());
            var rrp = parseFloat($('.pricing-level-rrp .price input').val());

            // Calc RRP percentage & margin
            var rrp_percentage = String(((rrp - dbp) / dbp) * 100).substring(0, 3);
            $('#view_default_percentage').val(rrp_percentage);

            // Calc Teir 2 price & margin
            var teir2 = $('.pricing-level.teir2');
            if (teir2) {
                var level_price = String(((rrp_percentage / 2) / 100) * dbp).substring(0, 6);

                teir2.find('.price input').val(parseFloat(level_price) + dbp);
                teir2.find('.margin').html('$'+level_price);
            }
        }
    });

    // Auto re-order checkbox
    $('#view_function__reorder_btn').on('change', function (e) {
        if ($(this).is(":checked")) {
            $('#view_function__reorder_pnt').attr('disabled', false);
            $('#view_function__reorder_lvl').attr('disabled', false);
        } else {
            $('#view_function__reorder_pnt').attr('disabled', true);
            $('#view_function__reorder_lvl').attr('disabled', true);
        }
    });
});
/*End Item Add*/



/*Purchase Order*/
$(document).ready(function () {
    $(document).on('click', '.add-item-to-purchase-order', function (e) {
        var $item_id = $(this).data('item-id');
        $('.modal').modal('hide');

        var item_exists = $('#register_transaction tr.purchase-item-' + $item_id);
        if (item_exists.length > 0) {
            $(item_exists).find('.display_quantity').val(function () {
                return parseInt(this.value) + 1
            });
            UpdatePurchaseTotals();
            return;
        }

        $.ajax({
            type: "POST",
            url: "/ajax/get_item",
            data: {id: $item_id},
            dataType: 'json',
            beforeSend: function () {
                Pace.restart();
            },
            complete: function (xhr) {
                if (xhr.responseJSON) {
                    var data = xhr.responseJSON;
                    var $html = "<tr class='status purchase-item purchase-item-"+data.item_o_id+"'>\n" +
                        "    <td class='lf'>\n" +
                        "        <button type='button' title='Delete' class='deleteButton' class='delete supplementary'><i class='fa fa-trash '></i> </button>\n" +
                        "    </td>\n" +
                        "    <td class='lf'>#"+($('#register_transaction tr.purchase-item').length + 1)+"</td>\n" +
                        "    <td class='group '>\n" +
                        "        <a title='Edit Record' target='_blank' href='/pos/item/"+data.item_o_id+"'><span>"+data.item+"</span></a>\n" +
                        "    </td>\n";
                    $html += $current_action == 'purchase_order' ? "<td></td><td></td>" : "";
                    $html +=
                        "    <td class='money '>\n" +
                        "        <div class='money-field-container'>\n" +
                        "            <input type='text' autocomplete='off' value='"+formatMoney(parseFloat(data.buy_price))+"' maxlength='15' name='items["+data.item_o_id+"][buy_price]' class='display_buy_price money data_control'>\n" +
                        "        </div>\n" +
                        "    </td>\n" +
                        "    <td class='string '>\n" +
                        "        <div class='money-field-container'>\n" +
                        "            <input type='text' autocomplete='off' value='"+formatMoney(parseFloat(data.rrp_price))+"' maxlength='15' name='items["+data.item_o_id+"][price]' class='display_price money data_control'>\n" +
                        "        </div>\n" +
                        "    </td>\n" +
                        "    <td class='number '>"+data.available_stock+"</td>\n" +
                        "    <td class='string '>\n" +
                        "        <input type='number' autocomplete='off' value='1' size='4' maxlength='15' name='items["+data.item_o_id+"][quantity]' class='number data_control display_quantity'>\n" +
                        "    </td>\n" +
                        "    <input type=\"hidden\" name=\"items["+data.item_o_id+"][id]\" value='"+data.item_o_id+"'>\n" +
                        "    <td class='money display_subtotal'>$"+data.buy_price+"</td>\n" +
                        "</tr>";
                    if ($('#register_transaction tr.purchase-item').length > 0) {
                        $('#register_transaction').append($html);
                    } else {
                        $('#register_transaction').html($html);
                    }

                } else {
                    showFeedback('error', "Sorry something wrong happened!");
                }
            },
            fail: function (err) {
                showFeedback('error', err.responseText);
            }
        });
    });

    $(document).on('click', '.deleteButton', function (e) {
        $(this).closest('.purchase-item').remove();
    });

    $(document).on('click', '.purchaseOrderDeleteButton', function (e) {
        var item_tr = $(this).closest('.purchase-item');
        var order_item_id = $(this).data('order_item');

        $.ajax({
            type: "POST",
            url: "/ajax/purchase_order_remove_item/"+order_item_id,
            dataType: 'json',
            beforeSend: function () {
                Pace.restart();
            },
            success: function (data) {
                if (data.status == '1') {
                    item_tr.remove();
                } else {
                    showFeedback('error', data.msg);
                }
            },
            fail: function (err) {
                showFeedback('error', err.responseText);
            }
        });
    });

    $(document).on('input change mousewheel', '.purchase-item .display_quantity, .purchase-item .display_buy_price', function (e) {
        if ($(e.target).hasClass('display_quantity')) {
            if ($(this).val() < 1) {
                $(this).val(1);
            }
        }

        UpdatePurchaseTotals();
    });

    function UpdatePurchaseTotals() {
        var items = $('#register_transaction .purchase-item');
        for (var i = 0; i < items.length; i++) {
            var price = parseFloat($(items[i]).find('.display_buy_price').val()),
                qty = parseInt($(items[i]).find('.display_quantity').val()),
                item_sub_total = $(items[i]).find('.display_subtotal');

            item_sub_total.html('$' + (price * qty));
        }
    }
});
/*End Purchase Order*/


/*Vendor Return*/
$(document).ready(function () {
    $(document).on('click', '#add-vendor-modal .select-vendor-btn', function (e) {
        $('.modal').modal('hide');

        var vendor_id = $('.vendor-select option:selected').val();
        var vendor_name = $('.vendor-select option:selected').text();
        if (vendor_id && vendor_name) {
            $('#vendor_id').val(vendor_id);
            $('#vendor_name').html(vendor_name);
        }
    });

    $(document).on('click', '.add-item-to-vendor-return', function (e) {
        var $item_id = $(this).data('item-id');
        $('.modal').modal('hide');

        var item_exists = $('#register_transaction tr.vendor-return-item-' + $item_id);
        if (item_exists.length > 0) {
            $(item_exists).find('.display_quantity').val(function () {
                return parseInt(this.value) + 1
            });
            UpdateVendorReturnTotals();
            return;
        }

        $.ajax({
            type: "POST",
            url: "/ajax/getItem_vendorReturn",
            data: {id: $item_id},
            dataType: 'json',
            beforeSend: function () {
                Pace.restart();
                $('#register_transaction .cr-table__body-row--loading .cr-table__loading').removeClass('cr-table__loading--hidden');
            },
            complete: function (xhr) {
                if (xhr.responseJSON && xhr.responseJSON.item && xhr.responseJSON.return_reasons) {
                    var data = xhr.responseJSON;
                    var $tr_class = data.item.available_quantity > 0 ? "" : "cr-table__body-row--error";
                    var $html = "<tr class=\"cr-table__body-row vendor-return-item vendor-return-item-"+data.item.item_o_id+" "+$tr_class+"\">\n" +
                        "    <td class=\"cr-table__body-cell cr-text-left cr-table__body-cell--first cr-table__body-cell--normal lf\" style=\"width: 3rem; vertical-align: middle;\">\n" +
                        "        <button type=\"button\" title=\"Delete\" class=\"deleteVendorItemButton\" class=\"delete supplementary\"><i class=\"fa fa-trash \"></i> </button>\n" +
                        "    </td>\n" +
                        "    <td class=\"cr-table__body-cell cr-text-left cr-table__body-cell--normal\" style=\"width: 3rem; vertical-align: middle;\">#"+($('#register_transaction tr.vendor-return-item').length + 1)+"</td>\n" +
                        "    <td class=\"cr-table__body-cell cr-text-left notranslate cr-table__body-cell--first cr-table__body-cell--normal\" style=\"vertical-align: middle; padding-top: 0.375rem; padding-bottom: 0.375rem; line-height: 0.75rem; text-overflow: ellipsis; overflow: hidden;\">\n" +
                        "        <span class=\"cr-text-base cr-text--body-small cr-text--body-color cr-text--bold notranslate\">\n" +
                        "            <a href='/pos/item/"+data.item.item_o_id+"' target='_blank' title='Edit Record' class=\"cr-text-link\">"+data.item.item+"</a>\n" +
                        "        </span>\n" +
                        "    </td>\n" +
                        "   <td class=\"cr-table__body-cell cr-text-left cr-table__body-cell--normal display_purchase_order\" style=\"width: 8rem; vertical-align: middle; padding-top: 0.375rem; padding-bottom: 0.375rem;\">\n" +
                        "      <button class=\"css-t86oos-ExtendedBaseButton ExtendedBaseButton associate-purchase-order-btn\" type=\"button\" title=\"Select a purchase order to associate with this line.\" data-item-id='"+data.item.item_o_id+"'>\n" +
                        "          <span class=\"css-kw31c7-ButtonContent e1eugw0d3\">Select PO</span>\n" +
                        "      </button>\n" +
                        "    </td>\n" +
                        "    <td class=\"cr-table__body-cell cr-text-left cr-table__body-cell--first cr-table__body-cell--normal\" style=\"width: 8rem; vertical-align: middle; padding-top: 0.375rem; padding-bottom: 0.375rem;\">\n" +
                        "        <select class=\"cr-select\" name='items["+data.item.item_o_id+"][return_reason]'>\n" +
                        "            <option value=\"1\">None</option>\n";

                    if (data.return_reasons) {
                        for (var i = 0; i < data.return_reasons.length; i++) {
                            $html += "<option value='"+data.return_reasons[i].id+"'>"+data.return_reasons[i].reason+"</option>\n";
                        }
                    }

                    $html +=
                        "        </select>\n" +
                        "    </td>\n" +
                        "    <td class=\"cr-table__body-cell cr-text-center cr-text-center cr-table__body-cell--first cr-table__body-cell--normal\" style=\"width: 6.5rem; vertical-align: middle;\">\n" +
                        "        <span class='available_quantity'>"+data.item.available_stock+"</span>\n" +
                        "    </td>\n" +
                        "    <td class=\"cr-table__body-cell cr-text-center cr-table__body-cell--first cr-table__body-cell--normal\" style=\"width: 7.5rem; vertical-align: middle; padding-top: 0.375rem; padding-bottom: 0.375rem;\">\n" +
                        "               <div class=\"cr-input__container\">\n" +
                        "                  <label class=\"cr-input__wrapper\">\n" +
                        "                     <input type=\"number\" class=\"cr-input display_quantity\" value=\"1\" name='items["+data.item.item_o_id+"][quantity]' style=\"text-align: center;\">\n" +
                        "                     <div class=\"cr-input__backdrop\"></div>\n" +
                        "                  </label>\n" +
                        "               </div>\n" +
                        "    </td>\n" +
                        "    <td class=\"cr-table__body-cell cr-text-right cr-table__body-cell--first cr-table__body-cell--normal\" style=\"width: 7rem; vertical-align: middle; padding-top: 0.375rem; padding-bottom: 0.375rem;\">\n" +
                        "        <div class=\"cr-input__container\">\n" +
                        "            <label class=\"cr-input__wrapper\">\n" +
                        "                <div class=\"cr-input__prefix\"><span class=\"notranslate\">$</span></div>\n" +
                        "                <input type=\"text\" class=\"cr-input cr-input--has-prefix display_buy_price\" value='"+formatMoney(parseFloat(data.item.buy_price))+"' maxlength='15' name='items["+data.item.item_o_id+"][cost]' style=\"text-align: right;\">\n" +
                        "                <div class=\"cr-input__backdrop\"></div>\n" +
                        "            </label>\n" +
                        "        </div>\n" +
                        "    </td>\n" +
                        "    <td class=\"cr-table__body-cell cr-text-right cr-text-right notranslate cr-table__body-cell--first cr-table__body-cell--normal\" style=\"width: 8rem; vertical-align: middle;\">\n" +
                        "        <span class=\"notranslate display_line_subtotal\">$"+formatMoney(parseFloat(data.item.buy_price))+"</span>\n" +
                        "    </td>\n" +
                        "</tr>";

                    $($html).insertBefore('#register_transaction .cr-table__body-row--loading');
                    UpdateVendorReturnTotals();
                } else {
                    showFeedback('error', "Sorry something wrong happened!");
                }
                $('#register_transaction .cr-table__body-row--loading .cr-table__loading').addClass('cr-table__loading--hidden');
            },
            fail: function (err) {
                showFeedback('error', err.responseText);
            }
        });
    });

    $(document).on('click', '.deleteVendorItemButton', function (e) {
        e.preventDefault();
        $(this).closest('.vendor-return-item').remove();
        UpdateVendorReturnTotals();
    });

    $(document).on('click', '.associate-purchase-order-btn', function (e) {
        e.preventDefault();
        var $item_id = $(this).data('item-id');
        $.ajax({
            type: "POST",
            url: "/ajax/get_items_purchase_orders",
            data: {id: $item_id},
            dataType: 'json',
            beforeSend: function () {
                Pace.restart();
            },
            complete: function (xhr) {
                if (xhr.responseJSON !== false) {

                    var $html = "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"g-datatable datatable table table-striped table-bordered items-datatable\">\n" +
                        "    <thead>\n" +
                        "    <tr>\n" +
                        "        <th></th>\n" +
                        "        <th title='Purchase Order ID'>ID</th>\n" +
                        "        <th>Status</th>\n" +
                        "        <th>Item</th>\n" +
                        "        <th>Vendor</th>\n" +
                        "        <th>Qty.</th>\n" +
                        "        <th>Received</th>\n" +
                        "        <th>Buy Price</th>\n" +
                        "        <th>Total</th>\n" +
                        "    </tr>\n" +
                        "    </thead>\n" +
                        "    <tbody>\n";

                    if (xhr.responseJSON) {
                        var data = xhr.responseJSON;
                        for (var i = 0; i < data.length; i++) {
                            $html += "       <tr class=\"gradeX\">\n" +
                                "                <td>\n" +
                                "                    <button title=\"Add\" type='button' class='btn add-purchase-order-vendor_return' data-order-id='"+data[i].order_id+"' data-po-item-id='"+data[i].id+"' data-item-id='"+$item_id+"'><i class=\"fa fa-plus \"></i> Select</button>\n" +
                                "                </td>\n" +
                                "                <td>\n" +
                                "                    <a title=\"Edit Purchase Order\" target=\"_blank\" href=\"/pos/purchase_order/"+data[i].order_id+"\"><span>#"+data[i].order_id+"</span></a>\n" +
                                "                </td>\n" +
                                "                <td style='text-transform:capitalize;'>"+data[i].status+"</td>\n" +
                                "                <td>\n" +
                                "                    <a title=\"Edit Item\" target=\"_blank\" href=\"/pos/item/"+data[i].item_id+"\"><span>"+data[i].item+"</span></a>\n" +
                                "                </td>\n" +
                                "                <td>"+data[i].vendor_name+"</td>\n" +
                                "                <td>"+data[i].quantity+"</td>\n" +
                                "                <td>"+data[i].quantity_received+"</td>\n" +
                                "                <td>$"+data[i].buy_price+"</td>\n" +
                                "                <td>$"+data[i].total+"</td>\n";
                        }
                    } else {
                        $html += "<tr>" +
                            "<td colspan='6' class='text-center'>Sorry no PO have item ("+$item_id+")</td>" +
                            "<td class='hidden'></td>" +
                            "<td class='hidden'></td>" +
                            "<td class='hidden'></td>" +
                            "<td class='hidden'></td>" +
                            "<td class='hidden'></td>" +
                            "</tr> \n";
                    }
                    $html += "</tbody></table>";

                    $('#search-results-modal .modal-title').html('Purchase orders contains item #'+$item_id);
                    $('#search-results-modal .modal-body').html($html);
                    InitDatatable('items-datatable');
                    $('#search-results-modal').modal();
                } else {
                    showFeedback('error', "Can't find purchase orders that includes selected item!");
                }
            },
            fail: function (err) {
                showFeedback('error', err.responseText);
            }
        });
    });

    $(document).on('click', '.add-purchase-order-vendor_return', function (e) {
        e.preventDefault();
        var $order_id = $(this).data('order-id');
        var $po_item_id = $(this).data('po-item-id');
        var $item_id = $(this).data('item-id');
        var $html = "<div class='dropright'>\n" +
            " <div role=\"button\" class=\"css-153ki3p ertzig70 dropdown-toggle\" id='dropdown-btn-"+$item_id+"' data-toggle=\"dropdown\" aria-haspopup=\"true\">" +
            ""+$order_id+
            "<input type='hidden' name='items["+$item_id+"][purchase_order_item_id]' value='"+$po_item_id+"'>" +
            "</div>\n" +
            " <div class=\"dropdown-menu\" aria-labelledby=\"dropdown-btn-"+$item_id+"\">\n" +
            "    <a class=\"dropdown-item\" target='_blank' href=\"/pos/purchase_order/"+$order_id+"\"><i class='fa fa-edit'></i> View PO</a>\n" +
            "    <a class=\"dropdown-item associate-purchase-order-btn\" href=\"#\" data-item-id='"+$item_id+"'><i class='fa redo-alt'></i> Select other PO</a>\n" +
            "    <a class=\"dropdown-item disassociate-purchase-order-btn\" href=\"#\" data-item-id='"+$item_id+"'><i class='fa fa-trash'></i> Remove PO</a>\n" +
            "  </div>\n" +
            "  </div>";
        $('.vendor-return-item-'+$item_id+' td.display_purchase_order').html($html);
        $('.modal').modal('hide');
    });

    $(document).on('click', '.disassociate-purchase-order-btn', function (e) {
        e.preventDefault();
        var $item_id = $(this).data('item-id');
        var $html = "      <button class=\"css-t86oos-ExtendedBaseButton ExtendedBaseButton associate-purchase-order-btn\" type=\"button\" title=\"Select a purchase order to associate with this line.\" data-item-id='"+$item_id+"'>\n" +
            "          <span class=\"css-kw31c7-ButtonContent e1eugw0d3\">Select PO</span>\n" +
            "      </button>\n";
        $(this).parents('tr.vendor-return-item').find('.display_purchase_order').html($html);
    });

    $(document).on('input change mousewheel', '.vendor-return-item .display_quantity, .vendor-return-item .display_buy_price, #shipCost, #otherCost', function (e) {
        if ($(e.target).hasClass('display_quantity')) {
            if ($(this).val() < 1) {
                $(this).val(1);
            }

            var stock = $(this).parents('tr.vendor-return-item').find('.available_quantity').html();
            if ($(this).val() > parseInt(stock)) {
                $(this).val(parseInt(stock));
            }
        }

        UpdateVendorReturnTotals();
    });

    function UpdateVendorReturnTotals() {
        var items = $('#register_transaction .vendor-return-item');
        var lines_subtotal = 0,
            subtotal = $('#returnValue'),
            total = $('#totalReturnValue'),
            shipping = parseFloat($('#shipCost').val()),
            other = parseFloat($('#otherCost').val());

        for (var i = 0; i < items.length; i++) {
            var price = parseFloat($(items[i]).find('.display_buy_price').val()),
                qty = parseInt($(items[i]).find('.display_quantity').val()),
                item_sub_total = $(items[i]).find('.display_line_subtotal');

            var line_subtotal = price * qty;
            item_sub_total.html('$' + formatMoney(line_subtotal));
            lines_subtotal += line_subtotal;
        }

        subtotal.html('$'+ formatMoney(lines_subtotal));
        total.html('$'+ formatMoney(lines_subtotal + shipping + other));
    }
});
/*End Vendor Return*/


/* Shipping */
$(document).ready(function () {
    $(document).on('click', '.shipment_shipped', function (e) {
        var $this = $(this);
        var $id = $this.data('id');
        var $checked = $this.is(':checked');

        $.ajax({
            type: "POST",
            url: "/ajax/shipment_update",
            data: {id: $id, shipped: $checked},
            dataType: 'json',
            beforeSend: function () {
                Pace.restart();
            },
            complete: function (xhr) {
                if (xhr.responseJSON !== false) {
                    if (xhr.responseJSON.status === 0) {
                        if ($checked == true) {
                            $this.prop('checked', false);
                        } else {
                            $this.prop('checked', !$checked);
                        }
                    }
                } else {
                    showFeedback('error', "Something wrong happened!");
                }
            },
            fail: function (err) {
                showFeedback('error', err.responseText);
            }
        });
    });
});
/* End Shipping */


/* Inventory Count */
$(document).ready(function () {
    $(document).on('click', '.add-item-to-inventory-count', function (e) {
        var $item_id = $(this).data('item-id');-
        $('.modal').modal('hide');

        var item_exists = $('#register_transaction tr.inventory-count-item-' + $item_id);
        if (item_exists.length > 0) {
            $(item_exists).find('.display_quantity').val(function () {
                return parseInt(this.value) + 1
            });
            return;
        }

        $.ajax({
            type: "POST",
            url: "/ajax/get_item_simple",
            data: {id: $item_id},
            dataType: 'json',
            beforeSend: function () {
                Pace.restart();
            },
            complete: function (xhr) {
                if (xhr.responseJSON) {
                    var data = xhr.responseJSON;
                    var time = (new Date).toLocaleTimeString('en');
                    var $html = "<tr class=\"row-status inventory-count-item inventory-count-item-"+data.id+"\">\n" +
                      "    <td class=\"lf\">\n" +
                      "        <button title=\"Delete\" class=\"deleteInventoryCountItemButton supplementary\"><i class=\"fa fa-trash \"></i> </button>\n" +
                      "    </td>\n" +
                      "    <td class=\"string \">\n" +
                      "        <a title='Edit Record' target='_blank' href='/pos/item/"+data.id+"'><span>"+data.item+"</span></a>\n" +
                      "    </td>\n" +
                      "    <td class=\"number \">\n" +
                      "         <span>"+data.available_stock+"</span>\n" +
                      "         <input type='hidden' name='items["+data.id+"][should_have]' value='"+data.available_stock+"'>\n" +
                      "     </td>\n" +
                      "    <td class=\"number\">\n" +
                      "        <input type=\"number\" autocomplete=\"off\" value=\"1\" maxlength=\"15\" name=\"items["+data.id+"][quantity]\" class=\" number data_control display_quantity\">\n" +
                      "    </td>\n";
                    if ($current_action == 'inventory_count') {
                        $html +=
                            "    <td></td>\n";
                    }
                      $html +=
                      "    <td class=\"shortdatetime \">\n" +
                      "        <span>"+time+"</span>\n" +
                      "    </td>\n" +
                      "</tr>";

                    $('#register_transaction').append($html);
                } else {
                    showFeedback('error', "Sorry something wrong happened!");
                }
            },
            fail: function (err) {
                showFeedback('error', err.responseText);
            }
        });
    });

    $(document).on('click', '.deleteInventoryCountItemButton', function (e) {
        e.preventDefault();
        $(this).closest('.inventory-count-item').remove();
    });

    $(document).on('click', '.deleteInventoryCountItemButtonAjax', function (e) {
        e.preventDefault();
        var $parent_tr = $(this).closest('.inventory-count-item');

        $.ajax({
            type: "POST",
            url: "/ajax/inventory_count_remove_item/"+$parent_tr.data('inv-count-item-id'),
            dataType: 'json',
            beforeSend: function () {
                Pace.restart();
            },
            success: function (data) {
                if (data.status == '1') {
                    $('.inventory-count-item-'+$parent_tr.data('inv-count-item_id')).remove();
                } else {
                    showFeedback('error', data.msg);
                }
            },
            fail: function (err) {
                showFeedback('error', err.responseText);
            }
        });
    });

    $(document).on('input change mousewheel', '.inventory-count-item .display_quantity', function (e) {
        if ($(e.target).hasClass('display_quantity')) {
            if ($(this).val() < 0) {
                $(this).val(0);
            }
        }
    });

    $('#view_count_by_id_count, #view_count_by_id_id').on('keypress',function(e) {
        if(e.which == 13) {
            e.preventDefault();
            $('#view_count_by_id_button').trigger('click');
        }
    });

    $(document).on('click', '#view_count_by_id_button', function (e) {
        var $count_by_id_id = $('#view_count_by_id_id').val();
        var $count_by_id_count = $('#view_count_by_id_count').val() ? $('#view_count_by_id_count').val() : 0;

        if ($count_by_id_id) {

            var item_exists = $('#register_transaction-printout_count_byID tr.printout-item-' + $count_by_id_id);
            if (item_exists.length > 0) {
                $(item_exists).find('.display_quantity').val(function () {
                    return parseInt($count_by_id_count)
                });
                return;
            }

            $.ajax({
                type: "POST",
                url: "/ajax/get_inventory_count_printout_item",
                data: {id: $current_param, count_by_id_id: $count_by_id_id},
                dataType: 'json',
                beforeSend: function () {
                    Pace.restart();
                },
                complete: function (xhr) {
                    if (xhr.responseJSON) {
                        var data = xhr.responseJSON;
                        var $html = "<tr class=\"row-status inventory-count-item printout-item printout-item-"+$count_by_id_id+"\">\n" +
                            "    <td class=\"lf\">\n" +
                            "        <button title=\"Delete\" class=\"deleteInventoryCountItemButton supplementary\"><i class=\"fa fa-trash \"></i> </button>\n" +
                            "    </td>\n" +
                            "    <td class=\"string \">\n" +
                            "        <a title=\"Edit Record\" target=\"_blank\" href=\"/pos/item/"+data.item_id+"\"><span>"+data.item+"</span></a>\n" +
                            "    </td>\n" +
                            "    <td class=\"number \">"+data.available_stock+"</td>\n" +
                            "       <input type='hidden' name='printout-items["+data.item_id+"][should_have]' value='"+data.available_stock+"'>\n" +
                            "    <td class=\"number \">\n" +
                            "        <input type=\"number\" autocomplete=\"off\" value=\""+($count_by_id_count ? $count_by_id_count : 0)+"\" maxlength=\"15\" name=\"printout-items["+data.item_id+"][quantity]\" class=\" number data_control display_quantity\">\n" +
                            "    </td>\n" +
                            "    <td class=\"number \">"+($count_by_id_count - data.available_stock)+"</td>\n" +
                            "    <input type='hidden' name='printout-items["+data.item_id+"][id]' value='"+data.id+"'>\n"+
                            "</tr>";
                        $('#register_transaction-printout_count_byID').append($html);
                        $('#view_count_by_id_id').val('');
                        $('#view_count_by_id_count').val('')
                    } else {
                        showFeedback('error', "Sorry couldn't find item that match ID <strong>"+$count_by_id_id+"</strong>. Please re-check the printout!");
                    }
                },
                fail: function (err) {
                    showFeedback('error', err.responseText);
                }
            });
        } else {
            showFeedback('error', "ID Required to Insert Count From Printed List!");
        }
    });


    $('.inventory-count-view .nav-item .nav-link').on('click', function (e) {
        e.preventDefault();
        window.location.hash = this.hash;
        $('#saveButton').trigger('click');
    });

    $('#inventory-count-form').on('submit', function (e) {
        var hash = window.location.hash;
        if (hash == '#printout') {
            $('.inventory-count-view #printout').append("<input type='hidden' name='printout-tab' value='1'>");
        }
        // $(this).unbind('submit').submit();
        return true;
    });
});
/* End Inventory Count */


// register count
function recalc_total() {
    var t = document.getElementById('cash_count_total');
    if (!t)
        return;
    var denoms = new Array();

    denoms.push([document.getElementById('cash_count_1'),100]);
    denoms.push([document.getElementById('cash_count_2'),50]);
    denoms.push([document.getElementById('cash_count_3'),20]);
    denoms.push([document.getElementById('cash_count_4'),10]);
    denoms.push([document.getElementById('cash_count_5'),5]);
    denoms.push([document.getElementById('cash_count_6'),1]);

    denoms.push([document.getElementById('cash_count_7'),0.50]);
    denoms.push([document.getElementById('cash_count_8'),0.20]);
    denoms.push([document.getElementById('cash_count_9'),0.10]);
    denoms.push([document.getElementById('cash_count_10'),0.05]);


    var sum = 0.00;

    var denom_ele = false;
    while (denom_ele = denoms.pop())
    {
        if (denom_ele[0] && denom_ele[0].value && denom_ele[1])
        {
            sum += Number(denom_ele[0].value * denom_ele[1]);
        }
    }

    var extra = document.getElementById('cash_count_extra');

    if (extra.value)
    {
        sign_index = extra.value.indexOf("$",0);
        if (sign_index > -1)
        {
            if (sign_index != 0 || sign_index >= extra.value.length - 1)
            {
                sum += Number(0);
            }
            else
            {
                sum += Number(extra.value.substr(sign_index + 1, extra.value.length));
            }
        }
        else
        {
            sum += Number(extra.value);
        }
    }
    t.value = (Math.round(sum*100)/100).toFixed(2);
}

$(document).ready(function () {
    // if viewing an item, Trigger DBP to calc pricings on page load
    if ($current_controller == 'pos' && $current_action == 'item') {
        $('#view_default_cost').trigger('input');
    }
});