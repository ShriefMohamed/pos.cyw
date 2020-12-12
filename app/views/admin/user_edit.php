<?php if (isset($item) && $item) : ?>
<div class="page-section">
    <div class="row">
        <div class="col-md-8">
            <section class="card">
                <div class="card-body">
                    <form method="post">
                        <fieldset>
                            <legend>Update User <strong><?= $item->username ?></strong></legend>
                            <hr class="my-3">

                            <div class="form-group">
                                <label class="custom-form-label">Role</label>
                                <select class="custom-select user-role-select" name="role">
                                    <option value="admin" <?php echo ($item->role == 'admin') ? 'selected' : '' ?>>Admin</option>
                                    <option value="customer" <?php echo ($item->role == 'customer') ? 'selected' : '' ?>>Customer</option>
                                    <option value="technician" <?php echo ($item->role == 'technician') ? 'selected' : '' ?>>Technician</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="custom-form-label">First Name</label>
                                <input type="text" class="form-control form-round" name="firstName" value="<?= $item->firstName ?>">
                            </div>
                            <div class="form-group">
                                <label class="custom-form-label">Last Name</label>
                                <input type="text" class="form-control form-round" name="lastName" value="<?= $item->lastName ?>">
                            </div>
                            <div class="form-group">
                                <label class="custom-form-label">Username</label>
                                <input type="text" class="form-control form-round" name="username" value="<?= $item->username ?>">
                            </div>
                            <div class="form-group">
                                <label class="custom-form-label">Email</label>
                                <input type="email" name="email" class="form-control form-round" value="<?= $item->email ?>">
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control form-round">
                            </div>
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="number" name="phone" class="form-control form-round" value="<?= $item->phone ?>">
                            </div>
                            <div class="form-group">
                                <label>Alternate Phone Number</label>
                                <input type="number" name="phone-2" class="form-control form-round" value="<?= $item->phone2 ?>">
                            </div>

                            <div class="customer-only" style="display: <?php echo ($item->role == 'customer') ? 'block' : 'none' ?>">
                                <?php if ($item->customer_id) : ?>
                                <input type="hidden" value="<?= $item->customer_id ?>" name="customer_id">
                                <?php endif; ?>

                                <div class="form-group">
                                    <label>Company Name</label>
                                    <input type="text" name="company" class="form-control form-round" value="<?= $item->companyName ?>">
                                </div>

                                <div class="form-group">
                                    <label class="custom-form-label">Default Automatic Updates Method</label>
                                    <div class="custom-control custom-control-inline custom-checkbox">
                                        <input type="checkbox" name="automatic-updates-email" class="custom-control-input" id="chby" value="1" <?php echo $item->emailNotifications == 1 ? 'checked' : '' ?>>
                                        <label class="custom-control-label" for="chby">Email</label>
                                    </div>
                                    <div class="custom-control custom-control-inline custom-checkbox">
                                        <input type="checkbox" name="automatic-updates-sms" class="custom-control-input" id="chbn" value="1" <?php echo $item->smsNotifications == 1 ? 'checked' : '' ?>>
                                        <label class="custom-control-label" for="chbn">SMS</label>
                                    </div>
                                </div>
                            </div>

                            <div class="technician-only" style="display: none">
                                <?php if ($item->technician_id) : ?>
                                <input type="hidden" value="<?= $item->technician_id ?>" name="technician_id">
                                <?php endif; ?>

                                <div class="form-group">
                                    <label class="custom-form-label control-label" for="technician-tags">Technician Tags*</label>
                                    <select name="technician-tags[]" id="technician-tags" multiple="multiple">
                                        <?php $tags = $item->tags ? explode('|', $item->tags) : false; ?>
                                        <?php if ($tags) : ?>
                                        <?php foreach ($tags as $tag) : ?>
                                            <option selected="selected" value="<?= $tag ?>"><?= $tag ?></option>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" name="submit" class="btn btn-primary">Update User</button>
                            </div>
                        </fieldset>
                    </form>
                </div>

            </section>
        </div>
    </div>
</div>

<script src="<?= VENDOR_DIR ?>select2/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        if ($('#technician-tags').length) {
            var data = ['Computer/Desktop', 'Laptop/Notebook', 'Mobile Phones', 'Tablet', 'Server'];
            $('#technician-tags').select2({
                tags: data,
                tokenSeparators: [',', ' ']
            });
        }

        $(document).on('change', '.user-role-select', function (e) {
            let index = $('.user-role-select option:selected').val();
            if (index == 'customer') {
                $('.customer-only').css({'display': 'block'});
                $('.technician-only').css({'display': 'none'});
            } else if (index == 'technician') {
                $('.customer-only').css({'display': 'none'});
                $('.technician-only').css({'display': 'block'});
            } else {
                $('.customer-only').css({'display': 'none'});
                $('.technician-only').css({'display': 'none'});
            }
        });

        $('.user-role-select').trigger('change');
    });
</script>
<style>
    .select2 {width: 100% !important;}
    .select2-selection__rendered {padding-left: 15px !important; padding-top: 3px !important;}
    .select2-selection__rendered li {margin-right: 12px !important;}
</style>
<?php endif; ?>