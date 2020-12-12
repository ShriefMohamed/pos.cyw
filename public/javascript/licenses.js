$(document).ready(function () {
    $('.continue-to-license').on('click', function (e) {
        $('.customer-info-container').addClass('hidden');
        $('.license-container').removeClass('hidden');
    });

    $('.back-to-customer-info').on('click', function (e) {
        $('.customer-info-container').removeClass('hidden');
        $('.license-container').addClass('hidden');
    });


    /* Selecting Customer */
    $('#find_customer_text').on('keypress',function(e) {
        if(e.which == 13) {
            e.preventDefault();
            $('#searchCustomerButton').trigger('click');
        }
    });

    $('#searchCustomerButton').on('click', function (e) {
        e.preventDefault();
        var $key_value = $('#find_customer_text').val();

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
                    "        <th>Mobile/Phone</th>\n" +
                    "        <th>Email</th>\n" +
                    "    </tr>\n" +
                    "    </thead>\n" +
                    "    <tbody>\n";

                if (data !== false) {
                    for (var i = 0; i < data.length; i++) {
                        $html += "       <tr class=\"gradeX\">\n" +
                            "                <td>\n";
                        $html += "<button title=\"Add\" type='button' class='btn add-customer-to-license-assign' data-customer-id='"+data[i].id+"'><i class=\"fa fa-plus \"></i> Add</button>\n";
                        $html +=
                            "                </td>\n" +
                            "                <td>\n" +
                            "                    <a title=\"Edit Record\" target=\"_blank\" href=\"/customers/customer/"+data[i].id+"\"><span class='customer-name'>"+data[i].firstName+" "+data[i].lastName+"</span></a>\n" +
                            "                </td>\n";

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

    $(document).on('click', '.add-customer-to-license-assign', function (e) {
        var customer_id = $(this).data('customer-id');
        $.ajax({
            type: "POST",
            url: "/ajax/get_customer",
            data: {id: customer_id},
            dataType: 'json',
            beforeSend: function () {
                Pace.restart();
            },
            success: function (data) {
                $('.modal').modal('hide');

                $('.register-customer #user-id-holder').val(data.id);
                $('.register-customer #customer-id-holder').val(data.customer_id);
                $('.register-customer #customerInfo').html(data.firstName+' '+data.lastName);
                $('.register-customer .search').css({'display':'none'});
                $('.register-customer #customerRemoveButton').css({'display':'inline'});

                var first_name_input = $('.first_name_input'),
                    last_name_input = $('.last_name_input'),
                    email_input = $('.email_input'),
                    phone_number_input = $('.phone_number_input'),
                    address_input = $('.address_input'),
                    suburb_input = $('.suburb_input'),
                    zip_input = $('.zip_input');
                if (data.firstName) {first_name_input.val(data.firstName);}
                if (data.lastName) {last_name_input.val(data.lastName);}
                if (data.email) {email_input.val(data.email);}
                if (data.phone) {phone_number_input.val(data.phone);}

                if (data.address) {
                    address_input.val(data.address);
                    address_input.attr('disabled', true);
                }
                if (data.suburb) {
                    suburb_input.val(data.suburb);
                    suburb_input.attr('disabled', true);
                }
                if (data.zip) {
                    zip_input.val(data.zip);
                    zip_input.attr('disabled', true);
                }
            },
            fail: function (err) {
                showFeedback('error', err.responseText);
            }
        });
    });

    $('#customerRemoveButton').on('click', function (e) {
        $('.customer-form-container input').val('');

        $('.register-customer #customerInfo').html("No Customer Selected");

        $('.register-customer .search').css({'display':'inline-block'});
        $('.register-customer #customerRemoveButton').css({'display':'none'});
    });
    /* Selecting Customer End */


    $('.product-select').on('change', function (e) {
        var $product = $(this).val();

        $.ajax({
            type: "POST",
            url: "/ajax/get_license_templates_by_product/"+$product,
            data: {},
            dataType: 'json',
            beforeSend: function () {
                Pace.restart();
            },
            complete: function (xhr) {
                if (xhr.responseJSON) {
                    var data = xhr.responseJSON;

                    if (data == false) {
                        showFeedback('error', "Couldn't find available license for selected product!");
                        return
                    }

                    if (!data.license) {
                        showFeedback('error', "Couldn't find available license for selected product!");
                        return
                    }
                    if (!data.templates) {
                        showFeedback('error', "Couldn't find templates assigned for selected product!");
                        return
                    }

                    $('.license-input').val(data.license.license);
                    $('.license-id-input').val(data.license.id);

                    var template_options = '';
                    for (var i = 0; i < data.templates.length; i++) {
                        template_options += "<option value='"+data.templates[i]+"'>"+data.templates[i]+"</option>\n";
                    }
                    $('.email-template-select').html(template_options);

                    var expiration = data.license.expiration_years > 0 ? data.license.expiration_years+' Year'+(data.license.expiration_years > 1 ? 's' : '') : '';
                    expiration += expiration != '' && data.license.expiration_months > 0 ? ' & ' : '';
                    expiration += data.license.expiration_months > 0 ? data.license.expiration_months+' Month'+(data.license.expiration_months > 1 ? 's' : '') : '';
                    $('.license-expires-after strong').html(expiration);
                    $('.license-expires-after').removeClass('hidden');
                }
            },
            fail: function (err) {
                showFeedback('error', err.responseText);
            }
        });
    });


    // form submit
    var buttonpressed = '';
    $('#license-form button[type=submit]').on('click', function (e) {
        var customer = $('input[name="customer-id"]').val(),
            email = $('input[name="email"]').val();

        if (!customer || !email) {
            $('.back-to-customer-info').trigger('click');
            showFeedback('error', "Customer info required!");
            e.preventDefault();
            return
        }
        buttonpressed = $(this).attr('name')
    });

    $('#license-form').on('submit', function (e) {
        if (buttonpressed == 'preview') {
            e.preventDefault();

            var form_data = $(this).serialize();
            $('.modal').modal('hide');

            $.ajax({
                type: "POST",
                url: "/licenses/license_assign_preview",
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
                            $('#template_preview_iframe').contents().find('html').html(data.result);
                            $('#template-preview-modal').modal();
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