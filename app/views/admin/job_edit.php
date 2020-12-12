<?php if (isset($item) && $item) : ?>
<?php $cipher = new \Framework\lib\Cipher(); ?>
<div class="page-section">
    <div class="row">
        <div class="col-md-12">
            <section class="card">
                <div class="card-body">
                    <form method="post" action="<?= HOST_NAME ?>admin/job_edit_action/<?= $item->id ?>">
                        <fieldset>
                            <legend>Manage Job <strong><?= $item->job_id ?></strong></legend>
                            <hr class="my-3">

                            <?php if (isset($customer) && $customer) : ?>
                            <fieldset>
                                <div style="margin-bottom: 20px">
                                    <legend>Client Information</legend>
                                    <a href="<?= HOST_NAME ?>admin/user_edit/<?= $customer->id ?>" class="">Edit Client</a>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="custom-form-label">Full Name</label>
                                            <input type="text" class="form-control form-round" name="firstName" value="<?= $customer->firstName.' '.$customer->lastName ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="custom-form-label">Username</label>
                                            <input type="text" class="form-control form-round" name="username" value="<?= $customer->username ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="custom-form-label">Email</label>
                                            <input type="email" name="email" class="form-control form-round" value="<?= $customer->email ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Phone Number</label>
                                            <input type="number" name="phone" class="form-control form-round" value="<?= $customer->phone ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="custom-form-label">Alternate Phone Number</label>
                                            <input type="number" name="phone-2" class="form-control form-round" value="<?= $customer->phone2 ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="custom-form-label">Company Name</label>
                                            <input type="text" name="company" class="form-control form-round" value="<?= $customer->companyName ?>" disabled>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="custom-form-label">Automatic updates method</label>
                                            <div class="custom-control custom-control-inline custom-checkbox">
                                                <input type="checkbox" name="automatic-updates-email" class="custom-control-input" id="chby" value="1" <?php echo $item->emailUpdates == 1 ? 'checked' : '' ?>>
                                                <label class="custom-control-label" for="chby">Email</label>
                                            </div>
                                            <div class="custom-control custom-control-inline custom-checkbox">
                                                <input type="checkbox" name="automatic-updates-sms" class="custom-control-input" id="chbn" value="1" <?php echo $item->smsUpdates == 1 ? 'checked' : '' ?>>
                                                <label class="custom-control-label" for="chbn">SMS</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <?php endif; ?>

                            <hr>

                            <fieldset>
                                <legend>Device Details</legend>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="custom-form-label">Device Type</label>
                                            <select class="custom-select" disabled>
                                                <option <?php echo $item->device == 'computer' ? 'selected' : '' ?> value="computer">Computer/Desktop</option>
                                                <option <?php echo $item->device == 'laptop' ? 'selected' : '' ?> value="laptop">Laptop/Notebook</option>
                                                <option <?php echo $item->device == 'phone' ? 'selected' : '' ?> value="phone">Mobile Phone</option>
                                                <option <?php echo $item->device == 'tablet' ? 'selected' : '' ?> value="tablet">Tablet</option>
                                                <option <?php echo $item->device == 'server' ? 'selected' : '' ?> value="server">Server</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="custom-form-label">Manufacture</label>
                                            <select class="custom-select" disabled>
                                                <option disabled>Manufacture</option>
                                                <option <?php echo $item->deviceManufacture == 'Apple' ? 'selected' : '' ?> value="Apple">Apple</option>
                                                <option <?php echo $item->deviceManufacture == 'Dell' ? 'selected' : '' ?> value="Dell">Dell</option>
                                                <option <?php echo $item->deviceManufacture == 'HP' ? 'selected' : '' ?> value="HP">HP</option>
                                                <option <?php echo $item->deviceManufacture == 'Toshiba' ? 'selected' : '' ?> value="Toshiba">Toshiba</option>
                                                <option <?php echo $item->deviceManufacture == 'Lenovo' ? 'selected' : '' ?> value="Lenovo">Lenovo</option>
                                                <option <?php echo $item->deviceManufacture == 'Samsung' ? 'selected' : '' ?> value="Samsung">Samsung</option>
                                                <option <?php echo $item->deviceManufacture == 'LG' ? 'selected' : '' ?> value="LG">LG</option>
                                                <option <?php echo $item->deviceManufacture == 'Sony' ? 'selected' : '' ?> value="Sony">Sony</option>
                                                <option <?php echo $item->deviceManufacture == 'Other' ? 'selected' : '' ?> value="Other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="custom-form-label">Model</label>
                                            <input type="text" class="form-control form-round" name="model" value="<?= $item->deviceModel ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="custom-form-label">Serial Number</label>
                                            <input type="text" class="form-control form-round" name="serial-number" value="<?= $item->serialNumber ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="custom-form-label">IMEI Number</label>
                                            <input type="text" class="form-control form-round" name="imei" value="<?= $item->IMEI ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="custom-form-label">Device's Password</label>
                                            <input type="password" class="form-control form-round" id="d-password" aria-label="Password" <?php echo $item->devicePassword ? "value='".$cipher->Decrypt($item->devicePassword)."'" : "placeholder='None'" ?> disabled>
                                            <i class="fa fa-eye toggle-password" toggle="#d-password"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="custom-form-label">Device's Alternative Password</label>
                                            <input type="password" class="form-control form-round" id="d-alt-password" aria-label="Password" <?php echo $item->devicePassword ? "value='".$cipher->Decrypt($item->deviceAltPassword)."'" : "placeholder='None'" ?> disabled>
                                            <i class="fa fa-eye toggle-password" toggle="#d-alt-password"></i>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="custom-form-label">Items being left</label>

                                            <?php $all_items = array('Computer', 'Laptop', 'Power Adapter', 'Portable Hard Drive', 'Printer', 'Monitor', 'Server', 'Keyboard', 'Mouse') ?>
                                            <?php $itemsLeft = explode('|', $item->itemsLeft); ?>

                                            <?php foreach ($all_items as $one_item) : ?>
                                                <?php if (in_array($one_item, $itemsLeft)) : ?>
                                                    <div class="custom-control custom-control-inline custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" name="left-items[]" value="<?= $one_item ?>" id="<?= $one_item ?>" checked>
                                                        <label class="custom-control-label" for="<?= $one_item ?>"><?= $one_item ?></label>
                                                    </div>
                                                <?php else : ?>
                                                    <div class="custom-control custom-control-inline custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" name="left-items[]" value="<?= $one_item ?>" id="<?= $one_item ?>">
                                                        <label class="custom-control-label" for="<?= $one_item ?>"><?= $one_item ?></label>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>

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
                                                    <?php if (strtolower($item->insuranceCompany) == $insurance_company->name) : ?>
                                                    <option selected value="<?= $insurance_company->name ?>"><?= $insurance_company->name ?></option>
                                                    <?php else : ?>
                                                    <option value="<?= $insurance_company->name ?>"><?= $insurance_company->name ?></option>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </select>
                                            <?php else : ?>
                                            <input type="text" class="form-control form-round" value="<?= $item->insuranceCompany ?>" name="insuranceCompany">
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="custom-form-label">Insurance Claim Number</label>
                                            <input type="text" class="form-control form-round" value="<?= $item->insuranceNumber ?>" name="insuranceNumber">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset style="margin-top: 20px">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="custom-form-label">Date Booked in & Time</label>
                                            <input type="text" class="form-control form-round" value="<?= \Framework\lib\Helper::ConvertDateFormat($item->created, true) ?>" disabled>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="custom-form-label">Technician Assigned</label>
                                            <select class="custom-select" name="technician">
                                                <?php if (isset($technicians) && $technicians) : ?>
                                                    <option selected disabled>Select Technician</option>
                                                    <?php foreach ($technicians as $technician) : ?>
                                                        <?php if ($technician->id == $item->technician_id) : ?>
                                                            <option value="<?= $technician->id ?>" selected><?= $technician->firstName.' '.$technician->lastName ?></option>
                                                        <?php else : ?>
                                                            <option value="<?= $technician->id ?>"><?= $technician->firstName.' '.$technician->lastName ?></option>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <hr>

                            <fieldset style="margin-top: 20px" class="notes-field">
                                <legend>Job Notes</legend>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="custom-form-label">Reported Issue</label>
                                    </div>
                                    <div class="col-md-10">
                                        <time datetime="<?= $item->created ?>">{<?= \Framework\lib\Helper::ConvertDateFormat($item->created, true) ?>}</time><span style="margin: 0 5px;color: #357ebd" title="<?= $customer->firstName.' '.$customer->lastName ?>">{Client}</span> :: <span style="margin-left: 10px"><?= $item->issue ?>.</span><br>
                                        <?php if (isset($notes) && $notes) : ?>
                                        <?php foreach ($notes as $note) : ?>
                                            <?php if ($note->type == 'reported') : ?>
                                                <time datetime="<?= $note->created ?>">{<?= \Framework\lib\Helper::ConvertDateFormat($note->created, true) ?>}</time><span style="margin: 0 5px;color: #357ebd" title="<?= $note->firstName.' '.$note->lastName.' {'.ucfirst($note->role).'}' ?>">{<?= $note->username ?>}</span> :: <span style="margin-left: 10px"><?= $note->note ?>.</span><br>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="custom-form-label">Diagnostic Notes</label>
                                    </div>
                                    <div class="col-md-10">
                                        <?php if (isset($notes) && $notes) : ?>
                                            <?php foreach ($notes as $note) : ?>
                                                <?php if ($note->type == 'diagnostic') : ?>
                                                <div id="note-<?= $note->id ?>">
                                                    <time datetime="<?= $note->created ?>">{<?= \Framework\lib\Helper::ConvertDateFormat($note->created, true) ?>}</time><span style="margin: 0 5px;color: #357ebd" title="<?= $note->firstName.' '.$note->lastName.' {'.ucfirst($note->role).'}' ?>">{<?= $note->username ?>}</span> :: <span style="margin-left: 10px"><?= $note->note ?>.</span>
                                                    <i class="fa fa-pencil-alt btn btn-outline-primary edit-note" data-content="<?= $note->id ?>"></i>
                                                    <i class="far fa-trash-alt btn btn-outline-primary delete-note" data-content="<?= $note->id ?>"></i>
                                                </div>
                                                <br>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="custom-form-label">Internal Notes<br><small>(Only techs can see)</small></label>
                                    </div>
                                    <div class="col-md-10">
                                        <?php if (isset($notes) && $notes) : ?>
                                            <?php foreach ($notes as $note) : ?>
                                                <?php if ($note->type == 'internal') : ?>
                                                    <div class="note-<?= $note->id ?>">
                                                        <time datetime="<?= $note->created ?>">{<?= \Framework\lib\Helper::ConvertDateFormat($note->created, true) ?>}</time><span style="margin: 0 5px;color: #357ebd" title="<?= $note->firstName.' '.$note->lastName.' {'.ucfirst($note->role).'}' ?>">{<?= $note->username ?>}</span> :: <span style="margin-left: 10px"><?= $note->note ?>.</span>
                                                        <i class="fa fa-pencil-alt btn btn-outline-primary edit-note" data-content="<?= $note->id ?>"></i>
                                                        <i class="far fa-trash-alt btn btn-outline-primary delete-note" data-content="<?= $note->id ?>"></i>
                                                    </div>
                                                    <br>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="custom-form-label">Client Information</label>
                                    </div>
                                    <div class="col-md-10">
                                        <?php if (isset($notes) && $notes) : ?>
                                            <?php foreach ($notes as $note) : ?>
                                                <?php if ($note->type == 'client') : ?>
                                                    <div class="note-<?= $note->id ?>">
                                                        <time datetime="<?= $note->created ?>">{<?= \Framework\lib\Helper::ConvertDateFormat($note->created, true) ?>}</time><span style="margin: 0 5px;color: #357ebd" title="<?= $note->firstName.' '.$note->lastName.' {'.ucfirst($note->role).'}' ?>">{<?= $note->username ?>}</span> :: <span style="margin-left: 10px"><?= $note->note ?>.</span>
                                                        <i class="fa fa-pencil-alt btn btn-outline-primary edit-note" data-content="<?= $note->id ?>"></i>
                                                        <i class="far fa-trash-alt btn btn-outline-primary delete-note" data-content="<?= $note->id ?>"></i>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </fieldset>

                            <hr>

                            <fieldset style="margin-top: 35px">
                                <?php if (isset($quotes) && $quotes) : ?>
                                    <?php $add_quote_button = false; ?>
                                    <?php for ($i = 0; $i < sizeof($quotes); $i++) : ?>
                                        <div class="row quote-row quote-row-<?= $quotes[$i]->id ?>">
                                            <?php $quote_status = \Framework\models\jobs\Repair_quotes_actionsModel::getAll("WHERE repair_quote_id = ".$quotes[$i]->id, true); ?>
                                            <?php if ($quote_status && $quote_status->action == 1) : ?>
                                                <label class="badge badge-success" style="">Approved on <?= \Framework\lib\Helper::ConvertDateFormat($quote_status->created, true) ?></label>
                                            <?php endif; ?>

                                            <?php if ($add_quote_button !== true) : ?>
                                                <button class="btn btn-success add-quote-btn" type="button">Add New Line</button>
                                                <?php $add_quote_button = true; ?>
                                            <?php endif; ?>

                                            <input type="hidden" name="quote[quote-id-<?= $i + 1 ?>]">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="custom-form-label">Quote of Repair</label>
                                                    <input type="text" name="quote[quote-repair-<?= $i + 1 ?>]" id="quote-repair-<?= $i + 1 ?>" class="form-control form-round" value="$<?= $quotes[$i]->quote ?>" oninput="calcTotal()">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="custom-form-label">Item Details</label>
                                                    <input type="text" name="quote[item-details-<?= $i + 1 ?>]" id="item-details-<?= $i + 1 ?>" class="form-control form-round" value="<?= $quotes[$i]->item ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="custom-form-label">Item Cost</label>
                                                    <input type="text" name="cost[item-cost-<?= $i + 1 ?>]" id="item-cost-<?= $i + 1 ?>" class="form-control form-round" value="$<?= $quotes[$i]->cost ?>" oninput="calcTotal()">
                                                </div>
                                            </div>
                                            <div class="col-md-1" style="padding: 0">
                                                <button type="button" class="btn btn-danger remove-quote-btn" data-content="<?= $quotes[$i]->id ?>">Remove</button>
                                            </div>
                                        </div>
                                    <?php endfor; ?>
                                <?php else : ?>
                                <div class="row quote-row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="custom-form-label">Quote of Repair</label>
                                            <input type="text" name="quote[quote-repair-1]" id="quote-repair-1" class="form-control form-round" value="" oninput="calcTotal()">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="custom-form-label">Item Details</label>
                                            <input type="text" name="quote[item-details-1]" id="item-details-1" class="form-control form-round">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="custom-form-label">Item Cost</label>
                                            <input type="text" name="cost[item-cost-1]" id="item-cost-1" class="form-control form-round" value="" oninput="calcTotal()">
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <div class="added-quotes">
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="custom-form-label">Total</label>
                                            <input type="text" name="total" id="quote-total" class="form-control form-round" value="" disabled>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset style="margin-top: 10px">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div id="accordion" class="card-expansion">
                                            <section class="card card-expansion-item expanded">
                                                <header class="card-header border-0" id="headingOne">
                                                    <button class="btn btn-reset" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" type="button">
                                                      <span class="collapse-indicator mr-2">
                                                        <i class="fa fa-fw fa-caret-right"></i>
                                                      </span>
                                                      <span>Attachments</span>
                                                    </button>
                                                </header>
                                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                                    <div class="card-body pt-0">
                                                        <ul class="list-group-bordered mb-3" style="padding-left: 5px">
                                                            <?php if (isset($attachments) && $attachments) : ?>
                                                            <?php foreach ($attachments as $attachment) : ?>
                                                            <li class="list-group-item list-group-item-primary d-flex justify-content-between align-items-center attachment-<?= $attachment->id ?>">
                                                                <a target="_blank" href="<?= ATTACHMENTS_DIR . $attachment->name_on_server ?>"><?= $attachment->name ?></a>
                                                                <i class="btn btn-outline-primary far fa-trash-alt delete-attachment" data-content="<?= $attachment->id ?>"></i>
                                                            </li>
                                                            <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </section>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="custom-form-label">Status Type</label>
                                    </div>
                                    <div class="col-md-10">
                                        <select class="custom-select" name="stage">
                                            <?php if (isset($stages) && $stages) : ?>
                                            <?php foreach ($stages as $stage) : ?>
                                                <?php if ($stage->stage == $item->stage) : ?>
                                                    <option value="<?= $stage->id ?>" selected><?= $stage->stage ?></option>
                                                <?php else : ?>
                                                    <option value="<?= $stage->id ?>"><?= $stage->stage ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>

                                <?php if (strtolower($item->stage) == "awaiting approval on quote") : ?>
                                    <?php if (isset($quotes_actions) && $quotes_actions) : ?>
                                        <?php $break = false; ?>
                                        <?php foreach ($quotes_actions as $quotes_action) : ?>
                                            <?php if ($break == true) : ?>
                                                <?php break; ?>
                                            <?php endif; ?>

                                            <?php if ($quotes_action->action != 1) : ?>
                                                <?php $break = true; ?>
                                                <div class="row" id="approve-quote-row" style="margin-top: 25px">
                                                    <div class="col-md-2">
                                                        <label class="custom-form-label">Awaiting Approval on quote.</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <button class="btn btn-info" type="button" data-toggle="modal" data-target="#approve-quotes-modal">Approve</button>
                                                    </div>
                                                </div>
                                                <hr>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <div class="row" style="margin-top: 20px">
                                    <div class="col-md-2">
                                        <label class="custom-form-label">Status History</label>
                                    </div>
                                    <div class="col-md-10">
                                        <?php if (isset($job_stages) && $job_stages) : ?>
                                        <?php foreach ($job_stages as $job_stage) : ?>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <span style="margin-right: 15px"><?= $job_stage->stage ?>.</span>
                                                </div>
                                                <div class="col-md-8" style="margin-bottom: 10px;">
                                                    Started:: <time datetime="<?= $job_stage->created ?>">{<?= \Framework\lib\Helper::ConvertDateFormat($job_stage->created, true) ?>}</time>
                                                    <?php echo ($job_stage->status == 2 && $job_stage->ended) ? "Ended:: <time datetime=".$job_stage->ended.">{".\Framework\lib\Helper::ConvertDateFormat($job_stage->ended, true)."}</time>" : ""; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </fieldset>

                            <hr>

                            <?php if (isset($tracking_info) && $tracking_info) : ?>
                            <fieldset style="margin-top: 20px">
                                <legend>Parcel Tracking Info</legend>
                                <?php for ($i = 0; $i < sizeof($tracking_info); $i++) : ?>
                                <div class="row tracking-row tracking-row-<?= $tracking_info[$i]->id ?>">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="custom-form-label">Tracking Number</label>
                                            <input type="text" class="form-control form-round" value="<?= $tracking_info[$i]->tracking_number ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="custom-form-label">Carrier</label>
                                            <select class="custom-select" disabled>
                                                <?php
                                                if ($tracking_info[$i]->carrier == 'auspost') {
                                                    echo "<option value=\"auspost\">Australia Post</option>";
                                                } elseif ($tracking_info[$i]->carrier == 'couriersplease') {
                                                    echo "<option value=\"couriersplease\">Couriers Please</option>";
                                                } elseif ($tracking_info[$i]->carrier == 'fastway') {
                                                    echo "<option value=\"fastway\">FastWay</option>";
                                                } elseif ($tracking_info[$i]->carrier == 'apdparcel') {
                                                    echo "<option value=\"apdparcel\">APD Parcel</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="custom-form-label">Expected Delivery Date</label>
                                            <input type="text" class="form-control form-round" value="<?= $tracking_info[$i]->expected_delivery ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-1" style="padding: 0">
                                        <button type="button" class="btn btn-danger remove-tracking-btn" data-content="<?= $tracking_info[$i]->id ?>">Remove</button>
                                    </div>
                                </div>
                                <?php endfor; ?>
                            </fieldset>
                            <hr>
                            <?php endif; ?>

                            <fieldset style="margin-top: 20px">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn btn-success add-note" type="button" data-content="diagnostic">Add Diagnostic Notes</button>
                                        <button class="btn btn btn-success add-note" type="button" data-content="internal">Add Internal Notes</button>
                                        <button class="btn btn btn-success add-note" type="button" data-content="reported">Add Reported Issue Notes</button>
                                        <button class="btn btn btn-success add-note" type="button" data-content="client">Add Client Information Notes</button>
                                        <button class="btn btn btn-success add-file" type="button" data-toggle="modal" data-target="#add-files-modal">Attach File</button>
                                        <button class="btn btn btn-success add-tracking-info" type="button" data-toggle="modal" data-target="#add-tracking-info-modal">Add Tracking Info</button>
                                    </div>
                                </div>
                            </fieldset>

                            <br><br>
                            <input type="submit" class="btn btn-primary btn-lg" name="submit" value="Update Job">
                        </fieldset>
                    </form>
                </div>
            </section>

            <!-- Approve Quotes Modal -->
            <div class="modal fade" id="approve-quotes-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content" style="width: 150%; margin-left: -16%">
                        <form method="post" action="<?= HOST_NAME ?>admin/approve_quotes/<?= $item->id ?>">
                            <div class="modal-header">
                                <h5 class="modal-title" style="padding-left: 12px;">Approve the repairs</h5>
                            </div>
                            <div class="modal-body">
                                <fieldset>
                                    <div class="row quote-approve-modal-row">
                                        <div class="col-md-1">
                                            <div class="custom-control custom-control-inline custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="quote-checkbox" disabled>
                                                <label class="custom-control-label" for="quote-checkbox"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">Item Cost</div>
                                        <div class="col-md-2">Quote of Repair</div>
                                        <div class="col-md-4">Item Details</div>
                                    </div>
                                    <?php if (isset($quotes_actions) && $quotes_actions) : ?>
                                        <?php foreach ($quotes_actions as $quotes_action) : ?>
                                            <div class="row">
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <div class="custom-control custom-control-inline custom-checkbox">
                                                            <?php if ($quotes_action->action == 1) : ?>
                                                                <input type="checkbox" class="custom-control-input" id="quote-<?= $quotes_action->quote_id ?>" checked disabled>
                                                            <?php else : ?>
                                                                <input type="checkbox" class="custom-control-input" name="quote-approval[]" value="<?= $quotes_action->quote_id ?>" id="quote-<?= $quotes_action->quote_id ?>">
                                                            <?php endif; ?>
                                                            <label class="custom-control-label" for="quote-<?= $quotes_action->quote_id ?>"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <span>$<?= $quotes_action->cost ?></span>
                                                </div>
                                                <div class="col-md-2">
                                                    <span>$<?= $quotes_action->quote ?></span>
                                                </div>
                                                <div class="col-md-4">
                                                    <span><?= $quotes_action->item ?></span>
                                                </div>
                                                <?php if ($quotes_action->action == 1) : ?>
                                                    <div class="col-md-3">
                                                        <label class="badge badge-success" style="">Approved on <?= \Framework\lib\Helper::ConvertDateFormat($quotes_action->created, true) ?></label>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </fieldset>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="submit" class="btn btn-primary">Approve</button>
                                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Add Note Modal -->
            <div class="modal fade" id="add-notes-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form method="post" action="<?= HOST_NAME ?>admin/job_note_add/<?= $item->id ?>">
                            <input type="hidden" value="" name="add-note-type" id="add-note-type">
                            <div class="modal-header">
                                <h5 class="modal-title"></h5>
                            </div>
                            <div class="modal-body">
                                <fieldset>
                                    <div class="form-group">
                                        <label class="custom-form-label">Note</label>
                                        <input type="text" class="form-control form-round" name="add-note-note" autofocus>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Edit Note Modal -->
            <div class="modal fade" id="edit-note-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form method="post" action="<?= HOST_NAME ?>admin/job_note_edit/<?= $item->id ?>">
                            <input type="hidden" value="" id="edit-note-id" name="edit-note-id">
                            <div class="modal-header">
                                <h5 class="modal-title"> Edit Job Note </h5>
                            </div>
                            <div class="modal-body">
                                <fieldset>
                                    <div class="form-group">
                                        <label class="custom-form-label">Note</label>
                                        <input type="text" class="form-control form-round" id="edit-note-note" name="edit-note-note" autofocus>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Add Attachment Modal -->
            <div class="modal fade" id="add-files-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form method="post" action="<?= HOST_NAME ?>admin/job_attachment_add/<?= $item->id ?>" enctype="multipart/form-data">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Attachment</h5>
                            </div>
                            <div class="modal-body">
                                <fieldset>
                                    <div class="form-group">
                                        <label for="tf3" class="custom-form-label">File input</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="tf3" name="attachments[]" multiple>
                                            <label class="custom-file-label" for="tf3">Choose file</label>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Add Tracking Info Modal -->
            <div class="modal fade" id="add-tracking-info-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form method="post" action="<?= HOST_NAME ?>admin/job_tracking_info_add/<?= $item->id ?>">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Parcel Tracking Info</h5>
                            </div>
                            <div class="modal-body">
                                <fieldset>
                                    <div class="form-group">
                                        <label class="custom-form-label">Tracking Number</label>
                                        <input type="text" class="form-control form-round" name="tracking-number">
                                    </div>
                                    <div class="form-group">
                                        <label class="custom-form-label">Carrier</label>
                                        <select class="custom-select" name="carrier">
                                            <option value="auspost">Australia Post</option>
                                            <option value="couriersplease">Couriers Please</option>
                                            <option value="fastway">FastWay</option>
                                            <option value="apdparcel">APD Parcel</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="custom-form-label">Expected Delivery Date</label>
                                        <input type="text" class="form-control form-round expected-delivery" name="expected_delivery">
                                    </div>
                                </fieldset>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= VENDOR_DIR ?>stacked-menu/stacked-menu.min.js"></script>
<script src="<?= VENDOR_DIR ?>jquery.datetimepicker/jquery.datetimepicker.full.min.js"></script>

<script>
    function calcTotal() {
        let count = $('.quote-row').length;
        var total = 0;
        for (let i = 1; i < (count + 1); i++) {
            let repair = $('#quote-repair-' + i).val() ? $('#quote-repair-' + i).val().replace('$', '') : 0,
                cost = $('#item-cost-' + i).val() ? $('#item-cost-' + i).val().replace('$', '') : 0;

            total += parseInt(repair) + parseInt(cost);
        }
        $('#quote-total').val('$' + total);
    }

    $(document).ready(function () {
        calcTotal();
        $('.expected-delivery').datetimepicker({format: 'd-m-Y H:i'});

        $(document).on('click', '.delete-attachment', function (e) {
            let file_id = e.target.dataset.content;
            $.ajax({
                url: "<?= HOST_NAME ?>admin/job_attachment_delete/"+file_id,
                type: "POST",
                dataType: "json"
            }).done(function (data) {
                if (data == 1) {
                    alert('Attachment was deleted successfully.');
                    $('.attachment-'+file_id).remove();
                } else {
                    alert('Failed to delete attachment!');
                }
            });
        })

        $(document).on('click', '.add-note', function (e) {
            $('#add-notes-modal .modal-title').html(e.target.innerText);
            $('#add-note-type').val(e.target.dataset.content);
            $('#add-notes-modal').modal();
        });

        $(document).on('click', '.edit-note', function (e) {
            let note_id = e.target.dataset.content;
            $.ajax({
                url: "<?= HOST_NAME ?>admin/job_note_get/"+note_id,
                type: "POST",
                dataType: "json"
            }).done(function (data) {
                if (data) {
                    $('#edit-note-id').val(data.id);
                    $('#edit-note-note').val(data.note);
                    $('#edit-note-modal').modal();
                } else {
                    alert('Failed to get note!');
                }
            });
        });

        $(document).on('click', '.delete-note', function (e) {
            let note_id = e.target.dataset.content;
            $.ajax({
                url: "<?= HOST_NAME ?>admin/job_note_delete/"+note_id,
                type: "POST",
                dataType: "json"
            }).done(function (data) {
                if (data == 1) {
                    alert('Note was deleted successfully.');
                    $('.note-'+note_id).remove();
                } else {
                    alert('Failed to delete note!');
                }
            });
        });

        $(document).on('click', '.add-quote-btn', function (e) {
            let num = $('.quote-row').length;
            let row = "<div class='row quote-row'>\n" +
                "    <div class='col-md-3'>\n" +
                "        <div class='form-group'>\n" +
                "            <label class='custom-form-label'>Quote of Repair</label>\n" +
                "            <input type='text' name='quote[quote-repair-"+(num + 1)+"]' id='quote-repair-"+(num + 1)+"' class='form-control form-round' value='' oninput='calcTotal()'>\n" +
                "        </div>\n" +
                "    </div>\n" +
                "    <div class=\"col-md-4\">\n" +
                "       <div class=\"form-group\">\n" +
                "           <label class=\"custom-form-label\">Item Details</label>\n" +
                "           <input type=\"text\" name=\"quote[item-details-"+(num + 1)+"]\" id=\"item-details-"+(num + 1)+"\" class=\"form-control form-round\">\n" +
                "       </div>\n" +
                "    </div>\n" +
                "    <div class='col-md-3'>\n" +
                "        <div class='form-group'>\n" +
                "            <label class='custom-form-label'>Item Cost</label>\n" +
                "            <input type='text' name='cost[item-cost-"+(num + 1)+"]' id='item-cost-"+(num + 1)+"' class='form-control form-round' value='' oninput='calcTotal()'>\n" +
                "        </div>\n" +
                "    </div>\n" +
                "</div>"

            $('.added-quotes').append(row);
        });

        $(document).on('click', '.remove-quote-btn', function (e) {
            let quote_id = e.target.dataset.content;
            $.ajax({
                url: "<?= HOST_NAME ?>admin/job_quote_delete/"+quote_id,
                type: "POST",
                dataType: "json"
            }).done(function (data) {
                if (data == 1) {
                    $('.quote-row-'+quote_id).remove();
                    if ($('.quote-row').length == 0) {
                        $('.add-quote-btn').trigger('click');
                    }

                    calcTotal();
                }
            });
        });

        $(document).on('click', '.remove-tracking-btn', function (e) {
            let id = e.target.dataset.content;
            $.ajax({
                url: "<?= HOST_NAME ?>admin/job_tracking_info_delete/"+id,
                type: "POST",
                dataType: "json"
            }).done(function (data) {
                console.log(data);
                console.log('.tracking-row-'+id);
                if (data == 1) {
                    $('.tracking-row-'+id).remove();
                }
            });
        });
    });
</script>
<style>
    fieldset legend {margin-bottom: 20px; color: #444444}
    .add-quote-btn {margin-top: 28px;position: absolute; right: 1%}
    .remove-quote-btn, .remove-tracking-btn {margin-top: 28px}
    #approve-quotes-modal .row {border-bottom: solid 1px #e0e0e0; margin-bottom: 15px}
    .quote-approve-modal-row div {font-weight: 600; color: #444444; font-size: 13px}
    #approve-quotes-modal .form-group {margin-bottom: 0}
    #approve-quotes-modal fieldset {width: 95%;margin: auto;}
    .quote-row {margin-bottom: 15px}
    .quote-row .badge {position: absolute;margin-top: -25px;margin-left: 10px;}
</style>
<?php endif; ?>

