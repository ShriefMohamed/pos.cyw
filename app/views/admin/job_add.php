<div class="page-section">
    <div class="row">
        <div class="col-md-12">
            <section class="card">
                <div class="card-body">
                    <form method="post">
                        <fieldset>
                            <legend>Create New Job</legend>
                            <hr class="my-3">

                            <fieldset>
                                <div>
                                    <legend>Client Information</legend>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="customers" class="custom-form-label">Select Client</label>
                                            <select class="form-control" name="customer" id="customers">
                                                <?php if (isset($customers) && $customers) : ?>
                                                    <?php foreach ($customers as $customer) : ?>
                                                        <option value="<?= $customer->id ?>"><?= $customer->firstName.' '.$customer->lastName ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-1"></div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="custom-form-label" style="margin-bottom: 18px;">Automatic updates method</label>
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
                                </div>
                            </fieldset>

                            <hr>

                            <fieldset>
                                <legend>Device Details</legend>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="custom-form-label">Device Type</label>
                                            <select class="custom-select" name="devices">
                                                <option value="computer">Computer/Desktop</option>
                                                <option value="laptop">Laptop/Notebook</option>
                                                <option value="phone">Mobile Phone</option>
                                                <option value="tablet">Tablet</option>
                                                <option value="server">Server</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="custom-form-label">Manufacture</label>
                                            <select class="custom-select" name="device-make">
                                                <option selected disabled>Manufacture</option>
                                                <option value="Apple">Apple</option>
                                                <option value="Dell">Dell</option>
                                                <option value="HP">HP</option>
                                                <option value="Toshiba">Toshiba</option>
                                                <option value="Lenovo">Lenovo</option>
                                                <option value="Samsung">Samsung</option>
                                                <option value="LG">LG</option>
                                                <option value="Sony">Sony</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="custom-form-label">Model</label>
                                            <input type="text" class="form-control form-round" name="model">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="custom-form-label">Serial Number</label>
                                            <input type="text" class="form-control form-round" name="serial-number">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="custom-form-label">IMEI Number</label>
                                            <input type="text" class="form-control form-round" name="imei">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="custom-form-label">Description of the Device's Issue</label>
                                            <textarea class="form-control" name="issue-description" rows="3"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="custom-form-label">Device's Password</label>
                                            <input type="password" class="form-control form-round" id="d-password" aria-label="Password" name="device-password">
                                            <i class="fa fa-eye toggle-password" toggle="#d-password"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="custom-form-label">Device's Alternative Password</label>
                                            <input type="password" class="form-control form-round" id="d-alt-password" aria-label="Password" name="device-password-alt">
                                            <i class="fa fa-eye toggle-password" toggle="#d-alt-password"></i>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="custom-form-label">Select items being left. (more than one item maybe selected)</label>

                                            <div class="custom-control custom-control-inline custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="chb10" name="left-items[]" value="Computer">
                                                <label class="custom-control-label" for="chb10">Computer</label>
                                            </div>
                                            <div class="custom-control custom-control-inline custom-checkbox">
                                                <input type="checkbox"  class="custom-control-input" id="chb11" name="left-items[]" value="Laptop">
                                                <label class="custom-control-label" for="chb11">Laptop</label>
                                            </div>
                                            <div class="custom-control custom-control-inline custom-checkbox">
                                                <input type="checkbox"  class="custom-control-input" id="chb12" name="left-items[]" value="Power Adapter">
                                                <label class="custom-control-label" for="chb12">Power Adapter</label>
                                            </div>
                                            <div class="custom-control custom-control-inline custom-checkbox">
                                                <input type="checkbox"  class="custom-control-input" id="chb13" name="left-items[]" value="Portable Hard Drive">
                                                <label class="custom-control-label" for="chb13">Portable Hard Drive</label>
                                            </div>
                                            <div class="custom-control custom-control-inline custom-checkbox">
                                                <input type="checkbox"  class="custom-control-input" id="chb14" name="left-items[]" value="Printer">
                                                <label class="custom-control-label" for="chb14">Printer</label>
                                            </div>
                                            <div class="custom-control custom-control-inline custom-checkbox">
                                                <input type="checkbox"  class="custom-control-input" id="chb15" name="left-items[]" value="Monitor">
                                                <label class="custom-control-label" for="chb15">Monitor</label>
                                            </div>
                                            <div class="custom-control custom-control-inline custom-checkbox">
                                                <input type="checkbox"  class="custom-control-input" id="chb16" name="left-items[]" value="Server">
                                                <label class="custom-control-label" for="chb16">Server</label>
                                            </div>
                                            <div class="custom-control custom-control-inline custom-checkbox">
                                                <input type="checkbox"  class="custom-control-input" id="chb17" name="left-items[]" value="Keyboard">
                                                <label class="custom-control-label" for="chb17">Keyboard</label>
                                            </div>
                                            <div class="custom-control custom-control-inline custom-checkbox">
                                                <input type="checkbox"  class="custom-control-input" id="chb18" name="left-items[]" value="Mouse">
                                                <label class="custom-control-label" for="chb18">Mouse</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <hr>

                            <fieldset style="margin-top: 20px">
                                <div class="row">
                                    <div class="col-md-12">
                                        <legend>Insurance Claim Number</legend>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="custom-form-label">Insurance Company</label>
                                            <?php if (isset($insurance) && $insurance) : ?>
                                                <select class="custom-select" name="insuranceCompany">
                                                    <option selected>Select Insurance Company</option>
                                                    <?php foreach ($insurance as $insurance_company) : ?>
                                                        <option value="<?= $insurance_company->name ?>"><?= $insurance_company->name ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            <?php else : ?>
                                                <input type="text" class="form-control form-round" name="insuranceCompany">
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="custom-form-label">Insurance Claim Number</label>
                                            <input type="text" class="form-control form-round" name="insuranceNumber">
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="technicians" class="custom-form-label">Technician Assigned</label>
                                            <select class="custom-select" name="technician" id="technicians">
                                                <?php if (isset($technicians) && $technicians) : ?>
                                                    <option selected disabled>Select Technician</option>
                                                    <?php foreach ($technicians as $technician) : ?>
                                                    <?php $tags = ($technician->tags) ? str_replace('|', ', ', $technician->tags) : ''; ?>
                                                        <option value="<?= $technician->id ?>"><?= $technician->firstName.' '.$technician->lastName.' :: '.$tags ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <br><br>
                            <input type="submit" class="btn btn-primary btn-lg" name="submit" value="Create Job">
                        </fieldset>
                    </form>
                </div>
            </section>
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?= VENDOR_DIR ?>select2/css/select2.min.css"/>
<script src="<?= VENDOR_DIR ?>select2/js/select2.min.js"></script>

<style>
    .select2-container {width: 100% !important;}
    fieldset legend {margin-bottom: 20px; color: #444444}
</style>

<script>
    $(document).ready(function () {
        $("#customers").select2();
        $('#technicians').select2();
    });
</script>

