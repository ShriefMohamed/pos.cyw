<div class="page-section">
    <div class="row">
        <div class="col-md-8">
            <section class="card">
                <div class="card-body">
                    <form method="post">
                        <fieldset>
                            <legend>Add new User</legend>
                            <hr class="my-3">

                            <div class="form-group">
                                <label class="custom-form-label">Role</label>
                                <select class="custom-select user-role-select" name="role">
                                    <option value="admin">Admin</option>
                                    <option value="customer">Customer</option>
                                    <option value="technician">Technician</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="custom-form-label">First Name*</label>
                                <input type="text" class="form-control form-round" name="firstName" placeholder="First Name" required>
                            </div>
                            <div class="form-group">
                                <label class="custom-form-label">Last Name*</label>
                                <input type="text" class="form-control form-round" name="lastName" placeholder="Last Name" required>
                            </div>
                            <div class="form-group">
                                <label class="custom-form-label">Username*</label>
                                <input type="text" class="form-control form-round" name="username" placeholder="Username" required>
                            </div>
                            <div class="form-group">
                                <label class="custom-form-label">Email*</label>
                                <input type="email" name="email" class="form-control form-round" placeholder="Email" required>
                            </div>
                            <div class="form-group">
                                <label>Password*</label>
                                <input type="password" name="password" class="form-control form-round" placeholder="Password" required>
                            </div>
                            <div class="form-group">
                                <label>Phone Number*</label>
                                <input type="number" name="phone" class="form-control form-round" placeholder="Phone" required>
                            </div>
                            <div class="form-group">
                                <label>Alternate Phone Number</label>
                                <input type="number" name="phone-2" class="form-control form-round" placeholder="Alternate Phone Number">
                            </div>

                            <div class="customer-only" style="display: none">
                                <div class="form-group">
                                    <label>Company Name</label>
                                    <input type="text" name="company" class="form-control form-round" placeholder="Company Name">
                                </div>

                                <div class="form-group">
                                    <label class="custom-form-label">Default Automatic Updates Method</label>
                                    <div class="custom-control custom-control-inline custom-checkbox">
                                        <input type="checkbox" name="automatic-updates-email" class="custom-control-input" id="chby" value="1">
                                        <label class="custom-control-label" for="chby">Email</label>
                                    </div>
                                    <div class="custom-control custom-control-inline custom-checkbox">
                                        <input type="checkbox" name="automatic-updates-sms" class="custom-control-input" id="chbn" value="1">
                                        <label class="custom-control-label" for="chbn">SMS</label>
                                    </div>
                                </div>
                            </div>

                            <div class="technician-only" style="display: none">
                                <div class="form-group">
                                    <label class="custom-form-label control-label" for="technician-tags">Technician Tags*</label>
                                    <select name="technician-tags[]" id="technician-tags" multiple="multiple"></select>
                                </div>
                            </div>


                            <div class="form-group">
                                <button type="submit" name="submit" class="btn btn-primary">Add New User</button>
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
    });
</script>
<style>
    .select2 {width: 100% !important;}
    .select2-selection__rendered {padding-left: 15px !important; padding-top: 3px !important;}
    .select2-selection__rendered li {margin-right: 12px !important;}
</style>