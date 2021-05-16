<div class="container">
    <div class="header clearfix">
        <nav>
            <ul class="nav nav-pills pull-right">
                <li role="presentation" class="active"><a href="<?= HOST_NAME ?>">Home</a></li>
                <li role="presentation"><a href="<?= HOST_NAME ?>login">Login</a></li>
            </ul>
        </nav>
        <img height="26" src="<?= IMAGES_DIR ?>brand-dark.png">
    </div>

    <div class="jumbotron" id="check-repair-slide">
        <h1>Do You Know Your</h1>
        <div class="pull-center">
            <h1><label>REPAIR CODE ?</label></h1>
        </div>

        <form id="repair-code-form">
            <input type="text" placeholder="Please enter your reparation code." id="code" name="uid" class="form-control"><br>
            <button class="btn btn-primary" id="submit">Submit</button>
        </form>

        <hr>
        <p><a href="#" class="add_reparation">Book Reparation</a></p>
    </div>


    <div class="row justify-content-center mt-0" id="book-repair-slide" style="display: none">
        <div class="col-12 col-sm-12 col-md-7 col-lg-12 text-center p-0 mt-3 mb-2">
            <div class="card px-0 pt-4 pb-0 mt-3 mb-3">
                <p class="check_reparation-p"><a href="#" class="check_reparation">Back</a></p>
                <h2 style="padding-top: 15px;"><strong>Check In Your Repair</strong></h2>
                <div class="row">
                    <div class="col-md-12 mx-0">
                        <form id="msform" method="post">
                            <!-- progressbar -->
                            <ul id="progressbar">
                                <li class="active" id="device"><strong>Device</strong></li>
                                <li id="customer"><strong>Customer Details</strong></li>
                                <li id="information"><strong>Information</strong></li>
                                <li id="finalize"><strong>Finalize</strong></li>
                                <li id="confirm"><strong>Finish</strong></li>
                            </ul>
                            <div class="error-messages" style="display: none">
                                <div class="alert-danger alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <div id="error-messages-content"></div>
                                </div>
                            </div>

                            <fieldset id="step-1">
                                <div class="form-card">
                                    <h2 class="fs-title">Select Device</h2>
                                    <div class="row">
                                        <div class="paymentWrap">
                                            <div class="btn-group paymentBtnGroup btn-group-justified" data-toggle="buttons">
                                                <label class="btn paymentMethod active">
                                                    <div class="method computer"></div>
                                                    <input type="radio" name="devices" value="computer" checked>
                                                    <span>Computer/Desktop</span>
                                                </label>
                                                <label class="btn paymentMethod">
                                                    <div class="method laptop"></div>
                                                    <input type="radio" name="devices" value="laptop">
                                                    <span>Laptop/Notebook</span>
                                                </label>
                                                <label class="btn paymentMethod">
                                                    <div class="method phones"></div>
                                                    <input type="radio" name="devices" value="phone">
                                                    <span>Mobile Phone</span>
                                                </label>
                                                <label class="btn paymentMethod">
                                                    <div class="method tablet"></div>
                                                    <input type="radio" name="devices" value="tablet">
                                                    <span>Tablet</span>
                                                </label>
                                                <label class="btn paymentMethod">
                                                    <div class="method server"></div>
                                                    <input type="radio" name="devices" value="server">
                                                    <span>Server</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="button" name="next" class="next action-button" value="Next Step" />
                            </fieldset>

                            <fieldset id="step-2">
                                <div class="form-card" id="customer-information-yes-no">
                                    <h2 class="fs-title">Are you an existing Customer</h2>
                                    <label class="btn btn-primary btn-lg">
                                        <input type="radio" class="customer-exists" name="customer-information-yes-no" value="y">Yes
                                    </label>
                                    <label class="btn btn-primary btn-lg">
                                        <input type="radio" class="customer-exists" name="customer-information-yes-no" value="n">No
                                    </label>
                                </div>

                                <div class="form-card" id="customer-information-phone" style="display: none">
                                    <p class="check_reparation-p"><a href="#" class="customer-information-yes-no-back">Back</a></p>
                                    <h2 class="fs-title">Enter Mobile number or Last Name</h2>
                                    <input type="text" name="phone-lastname" id="phone-lastname" placeholder="Mobile number or Last Name">
                                    <button type="button" class="btn btn-success" id="check-existing-customer">Check</button>
                                </div>

                                <div class="form-card" id="customer-information-found" style="display: none">
                                    <p class="check_reparation-p">
                                        <a href="#" class="customer-information-phone-back">Not You? Back</a>
                                    </p>
                                    <h2 class="fs-title">Personal Information</h2>
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input type="text" name="first-name-1" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input type="text" name="last-name-1" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Company Name</label>
                                        <input type="text" name="company-name-1" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Address</label>
                                        <input type="text" name="address-1" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Email Address</label>
                                        <input type="email" name="email-1" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        <input type="text" name="phone-1" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Alternate Phone Number</label>
                                        <input type="text" name="phone-2-1" placeholder="Alternate Phone Number">
                                    </div>
                                    <input type="hidden" name="found-customer-id" value="">
                                </div>

                                <div class="form-card" id="customer-information-form" style="display: none">
                                    <p class="check_reparation-p"><a href="#" class="customer-information-yes-no-back">Back</a></p>
                                    <h2 class="fs-title">Personal Information</h2>
                                    <input type="text" name="first-name" placeholder="First Name*" />
                                    <input type="text" name="last-name" placeholder="Last Name*" />
                                    <input type="text" name="username" placeholder="Username*" />
                                    <input type="password" name="password" placeholder="Password*">
                                    <input type="text" name="company-name" placeholder="Company Name" />
                                    <input type="text" name="address" placeholder="Address" />
                                    <input type="email" name="email" placeholder="Email Address*" />
                                    <input type="text" name="phone" placeholder="Mobile Phone Number*">
                                    <input type="text" name="phone-2" placeholder="Alternate Phone Number" />
                                </div>

                                <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                                <input type="button" name="next" class="next action-button disabled" value="Next Step" id="next-step-button" disabled />
                            </fieldset>

                            <fieldset id="step-3">
                                <div class="form-card">
                                    <h2 class="fs-title">Device Information</h2>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="custom-form-label">Please select Make of Device</label>
                                                <select class="form-control" id="device-make" name="device-make">
                                                    <option value="" selected disabled>Select Make of Device</option>
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
                                    </div>
                                    <hr>
                                    <div class="row" id="">
                                        <div class="col-md-8">
                                            <div class="form-group insurance-assessment-cont">
                                                <label class="insurance-assessment-label custom-from-label">
                                                    <input type="checkbox" class="" id="insurance-assessment" name="insurance-assessment">
                                                    Is it an insurance assessment?
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div id="insurance-assessment-form" style="display: none">
                                                <div class="form-group">
                                                    <label class="custom-form-label">Insurance Company Name</label>
                                                    <select class="form-control" name="insurance-company">
                                                        <option value="none" selected>None</option>
                                                        <option value="Insurance Australia Group">Insurance Australia Group</option>
                                                        <option value="Suncorp">Suncorp</option>
                                                        <option value="QBE">QBE</option>
                                                        <option value="Youi">Youi</option>
                                                        <option value="Allianz">Allianz</option>
                                                        <option value="SGIO">SGIO</option>
                                                        <option value="SGIC">SGIC</option>
                                                        <option value="RAA">RAA</option>
                                                        <option value="AAMI">AAMI</option>
                                                        <option value="GIO">GIO</option>
                                                        <option value="APIA">APIA</option>
                                                        <option value="Vero">Vero</option>
                                                        <option value="CGU">CGU</option>
                                                        <option value="CIL">CIL</option>
                                                        <option value="BUPA">BUPA</option>
                                                        <option value="Budget">Budget</option>
                                                        <option value="Real Insurance">Real Insurance</option>
                                                        <option value="Guardian">Guardian</option>
                                                        <option value="Australia Post">Australia Post</option>
                                                        <option value="Virgin">Virgin</option>
                                                        <option value="1Cover Direct">1Cover Direct</option>
                                                        <option value="ANZ">ANZ</option>
                                                        <option value="AON">AON</option>
                                                        <option value="Aussie">Aussie</option>
                                                        <option value="Australian Seniors Insurance Agency">Australian Seniors Insurance Agency</option>
                                                        <option value="Australian Unity">Australian Unity</option>
                                                        <option value="Bank of Melbourne">Bank of Melbourne</option>
                                                        <option value="Bank SA">Bank SA</option>
                                                        <option value="Bank West">Bank West</option>
                                                        <option value="Bendigo Bank">Bendigo Bank</option>
                                                        <option value="Bank of Queensland">Bank of Queensland</option>
                                                        <option value="Catholic Church Insurance">Catholic Church Insurance</option>
                                                        <option value="Chubb">Chubb</option>
                                                        <option value="Citi">Citi</option>
                                                        <option value="Coles Insurance">Coles Insurance</option>
                                                        <option value="Comminsure">Comminsure</option>
                                                        <option value="CUA">CUA</option>
                                                        <option value="Dodo">Dodo</option>
                                                        <option value="Guid Insurance">Guid Insurance</option>
                                                        <option value="HBF">HBF</option>
                                                        <option value="HSBC">HSBC</option>
                                                        <option value="Hume Bank">Hume Bank</option>
                                                        <option value="IMB Bank">IMB Bank</option>
                                                        <option value="ING Direct">ING Direct</option>
                                                        <option value="Kogan">Kogan</option>
                                                        <option value="Mortgage Choice">Mortgage Choice</option>
                                                        <option value="NAB">NAB</option>
                                                        <option value="National Seniors Insurance Australia">National Seniors Insurance Australia</option>
                                                        <option value="NRMA">NRMA</option>
                                                        <option value="NRMA QLD">NRMA QLD</option>
                                                        <option value="Ozicare">Ozicare</option>
                                                        <option value="Peoples Choice Credit Union">Peoples Choice Credit Union</option>
                                                        <option value="RAC">RAC</option>
                                                        <option value="RACQ">RACQ</option>
                                                        <option value="RACV">RACV</option>
                                                        <option value="Shannons Insurance">Shannons Insurance</option>
                                                        <option value="St George Bank">St George Bank</option>
                                                        <option value="Westpac">Westpac</option>
                                                        <option value="Woolworths">Woolworths</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="insurance-claim-number" placeholder="Insurance Claim Number*">
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="insurance-email-address" placeholder="Insurance Email Address*">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row device-password-row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="custom-form-label">What is the password to get into your device? if none leave empty.</label>
                                                <input type="text" class="form-control" name="device-password" placeholder="Device's Password">
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="custom-form-label">Alternative password to get into your device? if none leave empty.</label>
                                                <input type="text" class="form-control" name="device-password-alt" placeholder="Device's Alternative Password">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row issue-description-row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="custom-form-label">Please describe the issue you are having</label>
                                                <textarea rows="3" name="issue-description" placeholder="Description of the issue you having"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                                <input type="button" name="next" class="next action-button" value="Next Step" id="next-step-button" />
                            </fieldset>

                            <fieldset id="step-4">
                                <div class="form-card">
                                    <h2 class="fs-title">Finalize Check In</h2>
                                    <div class="row" style="margin-bottom: 3rem">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="custom-form-label">How did you hear about us? (please select only one option)</label>

                                                <div class="custom-control custom-control-inline custom-radio">
                                                    <input type="radio" name="heared-about-us" class="custom-control-input" id="ckb1" value="Existing Customer">
                                                    <label class="custom-control-label" for="ckb1">Existing Customer</label>
                                                </div>
                                                <div class="custom-control custom-control-inline custom-radio">
                                                    <input type="radio" name="heared-about-us" class="custom-control-input" id="ckb2" value="Road Sign">
                                                    <label class="custom-control-label" for="ckb2">Road Sign</label>
                                                </div>
                                                <div class="custom-control custom-control-inline custom-radio">
                                                    <input type="radio" name="heared-about-us" class="custom-control-input" id="ckb3" value="Google">
                                                    <label class="custom-control-label" for="ckb3">Google</label>
                                                </div>
                                                <div class="custom-control custom-control-inline custom-radio">
                                                    <input type="radio" name="heared-about-us" class="custom-control-input" id="ckb4" value="Yellow Pages">
                                                    <label class="custom-control-label" for="ckb4">Yellow Pages</label>
                                                </div>
                                                <div class="custom-control custom-control-inline custom-radio">
                                                    <input type="radio" name="heared-about-us" class="custom-control-input" id="ckb5" value="True Local">
                                                    <label class="custom-control-label" for="ckb5">True Local</label>
                                                </div>
                                                <div class="custom-control custom-control-inline custom-radio">
                                                    <input type="radio" name="heared-about-us" class="custom-control-input" id="ckb6" value="Friends">
                                                    <label class="custom-control-label" for="ckb6">Friends</label>
                                                </div>
                                                <div class="custom-control custom-control-inline custom-radio">
                                                    <input type="radio" name="heared-about-us" class="custom-control-input" id="ckb7" value="Family">
                                                    <label class="custom-control-label" for="ckb7">Family</label>
                                                </div>
                                                <div class="custom-control custom-control-inline custom-radio">
                                                    <input type="radio" name="heared-about-us" class="custom-control-input" id="ckb8" value="Trading Post">
                                                    <label class="custom-control-label" for="ckb8">Trading Post</label>
                                                </div>
                                                <div class="custom-control custom-control-inline custom-radio">
                                                    <input type="radio" name="heared-about-us" class="custom-control-input" id="ckb9" value="Other">
                                                    <label class="custom-control-label" for="ckb9">Other</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-bottom: 3rem">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="custom-form-label">Select items being left with us. (more than one item maybe selected)</label>

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

                                                <p>(We would only like the necessary items left with us that are Broken or required to run the device).</p>
                                                <p>Please take any other items home with you, eg Laptop Bags, Mouse, Keyboards etc.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-bottom: 3rem">
                                        <div class="col-md-8">
                                            <label class="custom-form-label">We have the ability to send you automatic update during the process of your job. Please select 1 or more options to be notified of updates.</label>

                                            <div class="form-group">
                                                <div class="custom-control custom-control-inline custom-checkbox">
                                                    <input type="checkbox" name="automatic-updates-email" class="custom-control-input" id="chby" value="1" checked>
                                                    <label class="custom-control-label" for="chby">Email</label>
                                                </div>
                                                <div class="custom-control custom-control-inline custom-checkbox">
                                                    <input type="checkbox" name="automatic-updates-sms" class="custom-control-input" id="chbn" value="1">
                                                    <label class="custom-control-label" for="chbn">SMS</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 authorised-rep">
                                            <div class="panel-group" id="accordion">
                                                <div class="panel panel-primary">
                                                    <div class="panel-heading" style="padding: 5px 15px !important;">
                                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" class="panel-title collapsed" style="text-decoration: none">
                                                            <h4>Terms and conditions.</h4>
                                                        </a>
                                                    </div>
                                                    <div id="collapseOne" class="panel-collapse collapse show">
                                                        <div class="panel-body">
                                                            <p>I (the customer) hereby agree for the following work to be carried out by CYW (Compute Your World), the estimate is subject to change but not without prior communication with the client via phone, all phone calls are recorded. I also recognize that it is my responsibility to inform CYW of any other faults or defects within the warranty period of the work.</p>
                                                            <p>Customers are required to Backup ALL data, while every effort is made to backup Customers data, CYW takes no responsibility of loss of data.</p>
                                                            <p>Compute Your World guarantees the labour of the work for a period of 60 Days and all New Hardware for a Period of 12 months from the invoiced date.  Payment is required in full on the day.</p>
                                                            <p>Compute Your World will make every effort once the device has been repaired to inform you your device is ready for collection.  If your device has been left in our store for more than 30 days after repair, we will give you 60 Days notice either via, phone, email, sms stating that if you do not pay and collect your device with in the 60 Day Period that under the Unclaimed Goods Act we sell the device to recover the costs of repair.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="custom-control custom-control-inline custom-checkbox">
                                                    <input type="checkbox" name="terms-conditions" class="custom-control-input" id="terms-conditions">
                                                    <label class="custom-control-label" for="terms-conditions">I have read and understand the the terms and conditions</label>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label>Please Sign Here:</label>
                                            </div>
                                            <div id="signatureparent">
                                                <div id="signature"></div>
                                            </div>
                                            <input type="button" value="Reset" class="sign-reset-button btn btn-danger">
                                        </div>

                                    </div>
                                </div>

                                <?= isset($script) ? $script : '' ?>
                                <?= isset($widget) ? $widget : '' ?>
                                <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                                <input type="submit" name="submit-checkin" class="next action-button" value="Confirm" />
                            </fieldset>


                            <input type="hidden" name="signature" value="">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php if (\Framework\Lib\Session::Exists('job-checkin')) : ?>
    <script>
        $(document).ready(function () {
            $('#job-checkin').modal();
        });
    </script>
    <div class="modal modal-alert" role="dialog" id="job-checkin">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <p style="padding: 15px 0"><?= \Framework\Lib\Session::Get('job-checkin') ?></p>
                </div>
                <div class="modal-footer" style="padding: 10px 15px">
                    <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <?php \Framework\Lib\Session::Remove('job-checkin'); ?>
    <?php endif; ?>
</div>

<script src="<?= VENDOR_DIR ?>jSignature/src/jSignature.js"></script>
<script src="<?= VENDOR_DIR ?>jSignature/src/plugins/jSignature.CompressorSVG.js"></script>
<script src="<?= VENDOR_DIR ?>jSignature/src/plugins/jSignature.UndoButton.js"></script>
<script>
    $(document).ready(function() {
        $('#exampleModalAlert').modal();

        var $sigdiv = $("#signatureparent").jSignature({
            height: 90
        });

        $('.sign-reset-button').bind('click', function(e){
            $sigdiv.jSignature('reset');
        });

        $('.add_reparation').on("click", function() {
            $("#check-repair-slide").slideUp();
            $("#book-repair-slide").fadeIn();
        });
        $('.check_reparation').on('click', function () {
            $('#book-repair-slide').slideUp();
            $('#check-repair-slide').fadeIn();
        });

        $('.customer-information-yes-no-back').on('click', function (e) {
            $('#customer-information-phone').slideUp();
            $('#customer-information-form').slideUp();
            $('#customer-information-yes-no').fadeIn();
        });
        $('.customer-information-phone-back').on('click', function (e) {
            $('#customer-information-form').slideUp();
            $('#customer-information-found').slideUp();
            $('#customer-information-phone').fadeIn();
        });
        $('.customer-exists').on('click', function (e) {
            $('#customer-information-yes-no').slideUp();
            if (e.target.value == 'y') {
                $('#customer-information-phone').fadeIn();
            } else {
                $('#customer-information-form').fadeIn();
                $('#next-step-button').removeClass('disabled');
                $('#next-step-button').attr('disabled', false);
            }
        });

        $('#insurance-assessment').on('click', function (e) {
            if (e.target.checked) {
                $('#insurance-assessment-form').fadeIn();
            } else {
                $('#insurance-assessment-form').slideUp();
            }
        });


        $('#check-existing-customer').on('click', function (e) {
            let phone = $('#phone-lastname').val();
            $.ajax({
                url: "<?= HOST_NAME ?>index/check_customer/"+JSON.stringify(phone),
                type: "POST",
                dataType: "json",
                success: function (data) {
                   if (data) {
                       $('#phone-lastname').val('');

                       $('input[name="found-customer-id"').val(data.id);
                       $('input[name="first-name-1"]').val(data.firstName);
                       $('input[name="last-name-1"]').val(data.lastName);
                       $('input[name="company-name-1"]').val(data.companyName);
                       $('input[name="address-1"]').val(data.address);
                       $('input[name="email-1"]').val(data.email);
                       $('input[name="phone-1"]').val(data.phone);
                       $('input[name="phone-2-1"]').val(data.phone2);

                       $('#customer-information-phone').slideUp();
                       $('#customer-information-found').fadeIn();

                       $('#next-step-button').removeClass('disabled');
                       $('#next-step-button').attr('disabled', false);
                   } else {
                       $('#error-messages-content').html("<p>Sorry we couldn't find any customer associated with <strong>"+phone+"</strong></p>");
                       $('.error-messages').show();
                   }
                }
            })
        });

        $(window).keydown(function(event){
            if(event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });

        var current_fs, current_step, next_fs, previous_fs;
        var opacity;
        var errors;

        $(".next").click(function(e){
            current_fs = $(this).parent();
            current_step = current_fs[0].id;
            next_fs = $(this).parent().next();
            errors = [];

            if (current_step == 'step-1') {
                $('.paymentBtnGroup').each(function () {
                    if ($(this).find('input[type="radio"]:checked').length == 0) {
                        errors.push("Please Select Your Device!");
                    }
                });
            } else if (current_step == 'step-2') {
                let customer_exists_selected_option = document.querySelector('input[name="customer-information-yes-no"]:checked').value;
                if (customer_exists_selected_option !== 'y') {
                    if ($('input[name="first-name"]').val() == '') { errors.push("First Name is required."); }
                    if ($('input[name="last-name"]').val() == '') { errors.push("Last Name is required."); }
                    if ($('input[name="email"]').val() == '') { errors.push("Email Address is required."); }
                    if ($('input[name="username"]').val() == '') { errors.push("Username is required."); }
                    if ($('input[name="password"]').val() == '') { errors.push("Password is required."); }
                    if ($('input[name="phone"]').val() == '') { errors.push("Phone Number is required."); }
                } else {
                    if ($('input[name="found-customer-id"]').val() == '') {
                        errors.push("You must either fill in your information, or search for your existing account.");
                    }
                }
            } else if (current_step == 'step-3') {
                if ($('#device-make').val() == null) { errors.push("Please select Make of Device"); }
                if ($('textarea[name="issue-description"]').val() == '') { errors.push("Description of the device's issue is required."); }
            } else if (current_step == 'step-4') {
                if (!$('#chbn')[0].checked && !$('#chby')[0].checked) { errors.push("Please select at least 1 option to be notified of updates during the process of your job."); }
                if (!$('#terms-conditions')[0].checked) { errors.push("Please read and agree to our terms & conditions."); }

                if ($sigdiv.jSignature('getData', 'native').length == 0) {
                    errors.push("Sorry, Signature is required.");
                } else {
                    $('input[name="signature"]').val($sigdiv.jSignature('getData', 'image'));
                }
            }

            if (errors.length > 0) {
                let errors_p = '';
                for (let i = 0; i < errors.length; i++) {
                    errors_p += "<p>" + errors[i] + "</p><br>";
                }
                $('#error-messages-content').html(errors_p);
                $('.error-messages').show();
                e.preventDefault();
                return;
            }

            $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
            next_fs.show();

            current_fs.animate({opacity: 0}, {
                step: function(now) {
                    opacity = 1 - now;

                    current_fs.css({
                        'display': 'none',
                        'position': 'relative'
                    });
                    next_fs.css({'opacity': opacity});
                },
                duration: 600
            });
        });

        $(".previous").click(function(){

            current_fs = $(this).parent();
            previous_fs = $(this).parent().prev();

//Remove class active
            $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

//show the previous fieldset
            previous_fs.show();

//hide the current fieldset with style
            current_fs.animate({opacity: 0}, {
                step: function(now) {
// for making fielset appear animation
                    opacity = 1 - now;

                    current_fs.css({
                        'display': 'none',
                        'position': 'relative'
                    });
                    previous_fs.css({'opacity': opacity});
                },
                duration: 600
            });
        });

        $('.radio-group .radio').click(function(){
            $(this).parent().find('.radio').removeClass('selected');
            $(this).addClass('selected');
        });

        $(document).on('submit', '#repair-code-form', function (e) {
            e.preventDefault();
            var code = $('#code').val();
            if (code) {
                window.location = "<?= HOST_NAME ?>index/job/" + code;
            }
        });
    });
</script>

<style>
    .pace .pace-progress {top: 8.5rem !important;}
    .disabled {background-color: #d3d3d3 !important;}
    hr {border-color: #d8d8d8}
    .form-card select {font-size: 15px}
    .btn {border: 0 !important; padding: 8px 15px; font-size: 14px;}
    .modal-backdrop {background-color: #0000008a !important;}

    .error-messages {width: 94%; margin: 25px auto}
    .error-messages .alert {font-size: 1.3rem; font-weight: 800; padding: 10px !important; padding-bottom: 0 !important;}

    #signatureparent {background-color:#eee;padding:5px; width: 50%; border: solid 1px #ccc; border-radius: 3px}
    .jSignature {width: 100%; height: 90px !important;}
    .sign-reset-button {margin-top: 12px}

    #book-repair-slide .check_reparation-p {float: left}
    #book-repair-slide .check_reparation-p a {font-size: 18px;margin: 16px;position: absolute;top: -8px;}
    #msform .check_reparation-p a {margin: 16px 0}
    #customer-information-yes-no label, #check-existing-customer {font-weight: 800; border: 0;
        padding: 12px 22px; border-radius: 0; font-size: 16px}
    #customer-information-yes-no label {background-color: #87ceeb}
    #customer-information-yes-no label input[type='radio'] {display: none}
    #check-existing-customer {background-color: #00a65a}
    #check-existing-customer:hover {box-shadow: 0 0 0 2px white, 0 0 0 3px #00a157;}
    #customer-information-yes-no button:nth-child(3) {margin-left: 4rem}
    #customer-information-yes-no button:hover {box-shadow: 0 0 0 2px white, 0 0 0 3px #87ceeb;}
    #customer-information-phone input {margin-top: 2rem}

    /*.insurance-assessment-cont {margin: 30px 0}*/
    #insurance-assessment-form {margin-top: 2rem}
    .insurance-assessment-label {display: inline-flex;float: left;line-height: 1.5;}
    .insurance-assessment-label input {margin-right: 12px}
    .device-password-row label, .issue-description-row label {margin-bottom: 20px}


    .paymentWrap {padding: 50px 0;}
    .paymentWrap .paymentBtnGroup {max-width: 800px;margin: auto;}
    .paymentWrap .paymentBtnGroup .paymentMethod {padding: 40px;box-shadow: none;position: relative;}
    .paymentWrap .paymentBtnGroup .paymentMethod.active {outline: none !important;}
    .paymentWrap .paymentBtnGroup .paymentMethod.active .method {border-color: #87ceeb;outline: none !important;box-shadow: 0px 3px 22px 0px #7b7b7b;}
    .paymentWrap .paymentBtnGroup .paymentMethod .method {position: absolute;right: 3px;top: 3px;bottom: 3px;left: 3px;background-size: contain;
        background-position: center;background-repeat: no-repeat;border: 2px solid transparent;transition: all 0.5s;}
    .paymentWrap .paymentBtnGroup .paymentMethod span {position: absolute;font-size: 14px;color: #555;font-weight: 800;
        top: 85px;left: 0; right: 0}
    .paymentWrap .paymentBtnGroup .paymentMethod .method.computer {
        background-image: url("<?= IMAGES_DIR ?>index/computer.png");
    }
    .paymentWrap .paymentBtnGroup .paymentMethod .method.laptop {
        background-image: url("<?= IMAGES_DIR ?>index/laptop.png");
    }
    .paymentWrap .paymentBtnGroup .paymentMethod .method.phones {
        background-image: url("<?= IMAGES_DIR ?>index/phones.png");
    }
    .paymentWrap .paymentBtnGroup .paymentMethod .method.tablet {
        background-image: url("<?= IMAGES_DIR ?>index/tablet.png");
    }
    .paymentWrap .paymentBtnGroup .paymentMethod .method.server {
        background-image: url("<?= IMAGES_DIR ?>index/server.png");
    }
    .paymentWrap .paymentBtnGroup .paymentMethod .method:hover {border-color: #87ceeb;outline: none !important;}


    #msform {text-align: center;position: relative;margin-top: 20px}
    #msform fieldset .form-card {background: white;border: 0 none;border-radius: 0px;box-shadow: 0 2px 2px 2px rgba(0, 0, 0, 0.2);
        padding: 20px 40px 30px 40px;box-sizing: border-box;width: 94%;margin: 0 3% 20px 3%;position: relative}
    #msform fieldset {background: white;border: 0 none;border-radius: 0.5rem;box-sizing: border-box;
        width: 100%;margin: 0;padding-bottom: 20px;position: relative}
    #msform fieldset:not(:first-of-type) {display: none}
    #msform fieldset .form-card {text-align: left;color: #888888}
    #msform input[type='text'],
    #msform input[type='email'],
    #msform input[type='password'],
    #msform textarea {padding: 0px 8px 4px 8px;border: none;border-bottom: 1px solid #ccc;border-radius: 0px;margin-bottom: 25px;
        margin-top: 2px;width: 100%;box-sizing: border-box;font-family: montserrat;color: #2C3E50;font-size: 16px;letter-spacing: 1px}
    #msform input[type='text'], #msform input[type='email'], #msform input[type='password'] {box-shadow: none !important;}
    #msform input:focus,
    #msform textarea:focus {-moz-box-shadow: none !important;-webkit-box-shadow: none !important;
        box-shadow: none !important;border: none;font-weight: bold;border-bottom: 2px solid skyblue;outline-width: 0}
    #msform .action-button {width: 100px;background: skyblue;font-weight: bold;color: white;border: 0 none;border-radius: 0px;cursor: pointer;padding: 10px 5px;margin: 10px 5px}
    #msform .action-button:hover,
    #msform .action-button:focus {box-shadow: 0 0 0 2px white, 0 0 0 3px skyblue}
    #msform .action-button-previous {width: 100px;background: #616161;font-weight: bold;color: white;border: 0 none;border-radius: 0px;cursor: pointer;padding: 10px 5px;margin: 10px 5px}
    #msform .action-button-previous:hover,
    #msform .action-button-previous:focus {box-shadow: 0 0 0 2px white, 0 0 0 3px #616161}
    select.list-dt {border: none;outline: 0;border-bottom: 1px solid #ccc;padding: 2px 5px 3px 5px;margin: 2px}
    select.list-dt:focus {border-bottom: 2px solid skyblue}
    .card {z-index: 0;border: none;border-radius: 0.5rem;position: relative}
    .fs-title {font-size: 25px;color: #2C3E50;margin-bottom: 25px;font-weight: bold;text-align: left}
    #progressbar {margin-bottom: 30px;overflow: hidden;color: lightgrey}
    #progressbar .active {color: #000000}
    #progressbar li {list-style-type: none;font-size: 12px;width: 19%;float: left;position: relative}
    #progressbar #device:before {font-family: "Font Awesome 5 Free";font-weight: 900; content: "\f109";}
    #progressbar #customer:before {font-family: "Font Awesome 5 Free";font-weight: 900;;content: "\f2c2"}
    #progressbar #information:before {font-family: "Font Awesome 5 Free";font-weight: 900;content: "\f059"}
    #progressbar #finalize:before {font-family: "Font Awesome 5 Free";font-weight: 900;content: "\f00c"}
    #progressbar #confirm:before {font-family: "Font Awesome 5 Free";font-weight: 900;content: "\f00c"}
    #progressbar li:before {width: 50px;height: 50px;line-height: 45px;display: block;font-size: 18px;color: #ffffff;background: lightgray;border-radius: 50%;margin: 0 auto 10px auto;padding: 2px}
    #progressbar li:after {content: '';width: 100%;height: 2px;background: lightgray;position: absolute;left: 0;top: 25px;z-index: -1}
    #progressbar li.active:before, #progressbar li.active:after {background: skyblue}


    .custom-control {position: relative;display: block;min-height: 1.5rem;padding-left: 1.5rem}
    .custom-control-inline {display: -webkit-inline-box;display: -ms-inline-flexbox;display: inline-flex;margin-right: 2rem}
    .custom-control-input {position: absolute;z-index: -1;opacity: 0}
    .custom-control-input:checked~.custom-control-label:before {color: #fff;background-color: #346cb0}
    .custom-control-input:focus~.custom-control-label:before {-webkit-box-shadow: 0 0 0 1px #346cb0;box-shadow: 0 0 0 1px #346cb0}
    .custom-control-input:active~.custom-control-label:before {color: #fff;background-color: #afc9e7}
    .custom-control-input:disabled~.custom-control-label {color: #686f76}
    .custom-control-input:disabled~.custom-control-label:before {background-color: #f5f5f5}
    .custom-control-label {position: relative;margin-bottom: 0}
    .custom-control-label:before {pointer-events: none;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;background-color: #fff}
    .custom-control-label:after,.custom-control-label:before {position: absolute;top: .25rem;left: -2rem;display: block;width: 1.3rem;height: 1.3rem;content: ""}
    .custom-control-label:after {background-repeat: no-repeat;background-position: 50%;background-size: 65% 65%}
    .custom-checkbox .custom-control-label:before {border-radius: .25rem}
    .custom-checkbox .custom-control-input:checked~.custom-control-label:before {background-color: #346cb0}
    .custom-checkbox .custom-control-input:checked~.custom-control-label:after {background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%230179A8' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath d='M6.41 0l-.69.72-2.78 2.78-.81-.78-.72-.72-1.41 1.41.72.72 1.5 1.5.69.72.72-.72 3.5-3.5.72-.72-1.44-1.41z' transform='translate(0 1)' /%3e%3c/svg%3e")}
    .custom-checkbox .custom-control-input:indeterminate~.custom-control-label:before {background-color: #346cb0}
    .custom-checkbox .custom-control-input:indeterminate~.custom-control-label:after {background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%230179A8' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath d='M0 0v2h8v-2h-8z' transform='translate(0 3)' /%3e%3c/svg%3e")}
    .custom-checkbox .custom-control-input:disabled:checked~.custom-control-label:before {background-color: rgba(52,108,176,.5)}
    .custom-checkbox .custom-control-input:disabled:indeterminate~.custom-control-label:before {background-color: rgba(52,108,176,.5)}
    .custom-radio .custom-control-label:before {border-radius: 50%}
    .custom-radio .custom-control-input:checked~.custom-control-label:before {background-color: #346cb0}
    .custom-radio .custom-control-input:checked~.custom-control-label:after {background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%230179A8' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath d='M3 0c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z' transform='translate(1 1)' /%3e%3c/svg%3e")}
    .custom-radio .custom-control-input:disabled:checked~.custom-control-label:before {background-color: rgba(52,108,176,.5)}

    .custom-form-label {padding-bottom: 1rem;}
    .g-recaptcha>div {margin: auto}
</style>
