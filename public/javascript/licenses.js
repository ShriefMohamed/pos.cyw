$(document).ready(function () {
    $('.continue-to-license').on('click', function (e) {
        $('.customer-info-container').addClass('hidden');
        $('.license-container').removeClass('hidden');
    });

    $('.back-to-customer-info').on('click', function (e) {
        $('.customer-info-container').removeClass('hidden');
        $('.license-container').addClass('hidden');
    });


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