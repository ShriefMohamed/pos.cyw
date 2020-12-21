<?php if (isset($data) && $data !== false) : ?>
<div class="page-section">
    <section class="card card-fluid">
        <div class="card-body" style="padding: 0;">
            <form method="post" id="license-form" autocomplete="off">
                <input type="hidden" name="user-id" id="user-id-holder" value="<?= $data->user_id ?>">
                <input type="hidden" name="customer-id" id="customer-id-holder" value="<?= $data->customer_id ?>">

                <div class="functions">
                    <button type="submit" name="preview" class="btn btn-info-dark">Preview Email</button>
                    <button type="submit" name="save" class="btn btn-success">Save & Send</button>
                </div>

                <div class="customer-form-container" style="padding: 20px 12px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group first-name-group">
                                        <label class="custom-form-label">Full Name</label>
                                        <input type="text" class="form-control form-round " name="f_name" value="<?= $data->firstName.' '.$data->lastName ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="custom-form-label">Email</label>
                                        <input type="text" class="form-control form-round " name="l_name" value="<?= $data->email ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="custom-form-label">Phone Number</label>
                                        <input type="text" class="form-control form-round " name="email" value="<?= $data->phone ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="custom-form-label">Address</label>
                                        <input type="text" class="form-control form-round " value="<?= $data->address.', '.$data->suburb.' '.$data->zip ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr style="margin: 0">
                </div>

                <div class="license-form-container" style="padding: 20px 12px">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Product</label>
                                <input type="text" value="<?= $data->item ?>" class="form-control form-round" readonly>
                                <input type="hidden" name="product" value="<?= $data->product_id ?>">
                            </div>

                            <div class="form-group">
                                <label>License</label>
                                <input type="text" name="license" class="form-control form-round license-input" required readonly value="<?= isset($license) && $license != false ? $license->license : '' ?>">
                                <input type="hidden" name="license-id" class="license-id-input" value="<?= isset($license) && $license != false ? $license->id : '' ?>">
                            </div>

                            <div class="form-group">
                                <label>Email Template</label>
                                <select name="email-template" class="form-control form-round email-template-select" required>
                                    <?php if (isset($templates) && $templates != false) : ?>
                                        <?php foreach ($templates as $template) : ?>
                                            <option value="<?= $template ?>"><?= $template ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="functions">
                    <button type="submit" name="save" class="btn btn-success" style="float: right">Save & Send</button>
                    <button type="submit" name="preview" class="btn btn-info-dark preview-btn" style="float: right">Preview Email</button>
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

<?php endif; ?>