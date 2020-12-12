<div class="page-section">
    <section class="card card-fluid">
        <div class="card-body" style="padding: 0;">
            <form method="post" id="license-form" autocomplete="off">
                <div class="customer-info-container">
                    <div class="customer-form-container" id="register">
                        <div class="register-customer">
                            <input type="hidden" name="user-id" id="user-id-holder" value="">
                            <input type="hidden" name="customer-id" id="customer-id-holder" value="">

                            <div id="customerInfo" class="block placeholder">No Customer Selected</div>
                            <button id="customerRemoveButton" tabindex="9" type="button" class="gui-def-button" style="display: none;"><i class="fa fa-trash"></i> Remove</button>

                            <div class="search" >
                                <input tabindex="6" type="text" id="find_customer_text" autocomplete="off" placeholder="Search Customers" class="group group-start small">
                                <button id="searchCustomerButton" type="button" class="group group-end gui-def-button">
                                    <i class="fa fa-search"></i> Search
                                </button>
                            </div>
                            <a href="<?= HOST_NAME ?>customers/customer_add" target="_blank"><button type="button" class="gui-def-button"><i class="fa fa-plus"></i> New</button></a>
                        </div>


                        <div class="row" style="padding: 20px 15px;">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group first-name-group">
                                            <label class="custom-form-label">First Name</label>
                                            <input type="text" class="form-control form-round first_name_input" placeholder="First Name" name="f_name" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="custom-form-label">Last Name</label>
                                            <input type="text" class="form-control form-round last_name_input" placeholder="Last Name" name="l_name" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="custom-form-label">Email</label>
                                            <input type="email" name="email" class="form-control form-round email_input" placeholder="Email" required autocomplete="chrome-off">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="custom-form-label">Phone Number</label>
                                            <input type="text" name="phone" class="form-control form-round phone_number_input" placeholder="Phone Number" required autocomplete="chrome-off">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="custom-form-label">Address</label>
                                            <input type="text" name="address" class="form-control form-round address_input" placeholder="Address" autocomplete="chrome-off">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="custom-form-label">Suburb</label>
                                            <input type="text" name="suburb" class="form-control form-round suburb_input" placeholder="Suburb" autocomplete="chrome-off">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="custom-form-label">Postcode</label>
                                            <input type="text" name="zip" class="form-control form-round zip_input" placeholder="Postcode" autocomplete="chrome-off">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="functions">
                        <button type="button" title="Continue" class="btn btn-success continue-to-license" style="padding: .375rem 1.75rem;float: right">Continue</button>
                    </div>
                </div>

                <div class="license-container hidden">
                    <div class="functions">
                        <button type="button" class="btn btn-secondary back-to-customer-info">Back</button>
                        <button type="submit" name="preview" class="btn btn-info-dark">Preview Email</button>
                        <button type="submit" name="save" class="btn btn-success">Save & Send</button>
                    </div>

                    <div class="license-form-container" style="padding: 20px 12px">
                        <div class="row">
                            <div class="col-md-12">
                                <?php $is_pre_selected_license = isset($pre_selected_license) && !empty($pre_selected_license) && $pre_selected_license['license'] && $pre_selected_license['templates']; ?>

                                <div class="form-group">
                                    <label>Select Product</label>
                                    <select name="product" class="form-control form-round product-select" required>
                                        <option selected disabled>Select Product</option>
                                        <?php if (isset($products) && $products) : ?>
                                            <?php foreach ($products as $product) : ?>
                                                <option <?= $is_pre_selected_license && $pre_selected_license['license']->item_id == $product->item_id ? 'selected' : '' ?> value="<?= $product->item_id ?>"><?= $product->item ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>License</label>
                                    <input type="text" name="license" class="form-control form-round license-input" required readonly value="<?= $is_pre_selected_license ? $pre_selected_license['license']->license : '' ?>">
                                    <input type="hidden" name="license-id" class="license-id-input" value="<?= $is_pre_selected_license ? $pre_selected_license['license']->id : '' ?>">
                                </div>

                                <div class="form-group">
                                    <label>Select Email Template</label>
                                    <select name="email-template" class="form-control form-round email-template-select" required>
                                        <?php if ($is_pre_selected_license) : ?>
                                        <?php foreach ($pre_selected_license['templates'] as $template) : ?>
                                            <option value="<?= $template ?>"><?= $template ?></option>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="license-expires-after hidden">License Expires After: <strong></strong></label>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="functions">
                        <button type="submit" name="save" class="btn btn-success" style="float: right">Save & Send</button>
                        <button type="submit" name="preview" class="btn btn-info-dark preview-btn" style="float: right">Preview Email</button>
                    </div>
                </div>

            </form>
        </div>
    </section>
</div>

<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="template-preview-modal" style="overflow: auto">
    <div class="modal-dialog" role="document" style="max-width: 65%">
        <div class="modal-content">
            <div class="modal-header bg-info-dark">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <iframe id="template_preview_iframe" name="template_preview_iframe" width="100%" height="400px" style="max-width: 95%%; border: 1px solid #e6e6e6; padding: 0;"></iframe>
            </div>

            <div class="modal-footer" style="margin-top: 0">
                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="search-results-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body clearfix" style="padding: 20px;">

            </div>
        </div>
    </div>
</div>

<style>
    .functions {width: 100%;overflow: hidden;z-index: 100;border-bottom: 1px solid #ccc;background-color: #eee;min-height: 54px;padding-left: 10px;}
    .functions button {float: left;margin-top: 10px;margin-bottom: 10px;margin-right: 6px;}

    .first-name-group i {font-size: 18px;margin-left: 15px;cursor: pointer;}
    .first-name-group i:hover {color: #666}
    .customers-search-results-dropdown {background-color: #fff;border: 1px solid #e4e4e4;
        box-shadow: 0 1px 25px rgba(153, 153, 153, 0.31);max-height: 200px;overflow: hidden;overflow-y: scroll;
        min-width: 40%; max-width: 97%;margin-top: 6px;position: absolute;width: 100%;z-index: 9;}
    .customers-search-results-dropdown ul {padding-left: 0; list-style-type: none}
    .customers-search-results-dropdown ul li {list-style: none}
    .customers-search-results-dropdown ul li a {font-size: 15px;color: #333;text-transform: capitalize;
        padding: 7px 14px;display: block;line-height: 16px;letter-spacing: 0.3px;font-weight: 500;
        border-bottom: 1px solid #e2e2e2 !important;background-color: #fff;margin: 0;box-shadow: none;
        cursor: pointer;text-align: left;text-decoration: none;transition: all 300ms linear;
        text-shadow: 0 1px 2px rgba(0,0,0,.6);}
    .customers-search-results-dropdown ul li a:hover {background-color: #f0f1ef;color: #333;}
    .customers-search-results-dropdown ul li:last-child > a {border-bottom: 0 !important;}
</style>

<script>
    $(document).ready(function () {
        // var param = window.location.pathname.split('/')[3];
        // if (param) {
        //     $('.product-select').val(param);
        //     $('.product-select').trigger('change');
        // }
    });
</script>