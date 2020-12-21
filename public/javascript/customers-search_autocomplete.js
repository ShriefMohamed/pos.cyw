$(document).ready(function () {
    // search
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
                        $html += "<button title=\"Add\" type='button' class='btn add-customer-to-form-assign' data-customer-id='"+data[i].id+"'><i class=\"fa fa-plus \"></i> Add</button>\n";
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

    $(document).on('click', '.add-customer-to-form-assign', function (e) {
        var customer_id = $(this).data('customer-id');
        $.ajax({
            type: "POST",
            url: "/ajax/get_customer",
            data: {id: customer_id},
            dataType: 'json',
            beforeSend: function () {
                Pace.restart();
            },
            complete: function (xhr) {
                if (xhr.responseJSON) {
                    $('.modal').modal('hide');

                    AddCustomerToForm(xhr.responseJSON);
                } else {
                    showFeedback('error', "Failed to get customer!");
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


    // Autocomplete
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
        var id = $(this).data('id');
        if (id) {
            $.ajax({
                type: "POST",
                url: "/ajax/get_customer",
                data: {id: id},
                dataType: 'json',
                beforeSend: function () {
                    Pace.restart();
                },
                complete: function (xhr) {
                    if (xhr.responseJSON) {
                        AddCustomerToForm(xhr.responseJSON);
                    } else {
                        showFeedback('error', "Failed to get customer!");
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

    function AddCustomerToForm(data) {
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

        if (data.firstName) {
            first_name_input.val(data.firstName);
            $(first_name_input).attr('readonly', true);
        }
        if (data.lastName) {
            last_name_input.val(data.lastName);
            $(last_name_input).attr('readonly', true);
        }

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
    }
});