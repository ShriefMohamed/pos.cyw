<?php if (isset($item) && $item) : ?>
<?php $cipher = new \Framework\lib\Cipher(); ?>

<main class="app-main">
    <div class="wrapper">
        <div class="page">
            <div class="page-inner">
                <div class="page-section">
                    <div class="row">
                        <div class="col-md-12">
                            <section class="card">
                                <div class="card-body">
                                    <form method="post" action="<?= HOST_NAME ?>customer/job_edit/<?= $item->id ?>">
                                        <fieldset>
                                            <legend>Manage Job <strong><?= $item->job_id ?></strong></legend>
                                            <hr class="my-3">

                                            <?php if (isset($customer) && $customer) : ?>
                                                <fieldset>
                                                    <div style="margin-bottom: 20px">
                                                        <legend>Client Information</legend>
                                                        <a href="<?= HOST_NAME ?>customer/profile" class="">Edit Your Information</a>
                                                    </div>

                                                    <div class="row">
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
                                                            <label class="custom-form-label">Device's Password</label>
                                                            <input type="password" class="form-control form-round" name="device-password" id="d-password" aria-label="Password" <?php echo $item->devicePassword ? "value='".$cipher->Decrypt($item->devicePassword)."'" : "placeholder='None'" ?>>
                                                            <i class="fa fa-eye toggle-password" toggle="#d-password"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="custom-form-label">Device's Alternative Password</label>
                                                            <input type="password" class="form-control form-round" name="device-alt-password" id="d-alt-password" aria-label="Password" <?php echo $item->devicePassword ? "value='".$cipher->Decrypt($item->deviceAltPassword)."'" : "placeholder='None'" ?>>
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
                                                                        <input type="checkbox" class="custom-control-input" value="<?= $one_item ?>" id="<?= $one_item ?>" checked disabled>
                                                                        <label class="custom-control-label" for="<?= $one_item ?>"><?= $one_item ?></label>
                                                                    </div>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>

                                                    <?php if ($item->is_insurance == 1) : ?>
                                                    <div class="col-md-12">
                                                        <legend>Insurance Claim Number</legend>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="custom-form-label">Insurance Company</label>
                                                            <input type="text" class="form-control form-round" value="<?= $item->insuranceCompany ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="custom-form-label">Insurance Claim Number</label>
                                                            <input type="text" class="form-control form-round" value="<?= $item->insuranceNumber ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>
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
                                                    <?php for ($i = 0; $i < sizeof($quotes); $i++) : ?>
                                                        <?php $quote_status = \Framework\models\jobs\Repair_quotes_actionsModel::getAll("WHERE repair_quote_id = ".$quotes[$i]->id, true); ?>
                                                        <?php if ($quote_status && $quote_status->action == 1) : ?>
                                                        <label class="badge badge-success" style="">Approved on <?= \Framework\lib\Helper::ConvertDateFormat($quote_status->created, true) ?></label>
                                                        <?php endif; ?>

                                                        <div class="row quote-row quote-row-<?= $quotes[$i]->id ?>">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="custom-form-label">Quote of Repair</label>
                                                                    <input type="text" name="quote[quote-repair-<?= $i + 1 ?>]" id="quote-repair-<?= $i + 1 ?>" class="form-control form-round" value="$<?= $quotes[$i]->quote ?>" oninput="calcTotal()" disabled>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="custom-form-label">Item Details</label>
                                                                    <input type="text" name="quote[item-details-<?= $i + 1 ?>]" id="item-details-<?= $i + 1 ?>" class="form-control form-round" value="<?= $quotes[$i]->item ?>" disabled>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="custom-form-label">Item Cost</label>
                                                                    <input type="text" name="cost[item-cost-<?= $i + 1 ?>]" id="item-cost-<?= $i + 1 ?>" class="form-control form-round" value="$<?= $quotes[$i]->cost ?>" oninput="calcTotal()" disabled>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endfor; ?>

                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="custom-form-label">Total</label>
                                                                <input type="text" name="total" id="quote-total" class="form-control form-round" value="" disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </fieldset>

                                            <fieldset style="margin-top: 10px">
                                                <?php if (isset($attachments) && $attachments) : ?>
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
                                                                            <?php foreach ($attachments as $attachment) : ?>
                                                                                <li class="list-group-item list-group-item-primary d-flex justify-content-between align-items-center attachment-<?= $attachment->id ?>">
                                                                                    <a target="_blank" href="<?= ATTACHMENTS_DIR . $attachment->name_on_server ?>"><?= $attachment->name ?></a>
                                                                                </li>
                                                                            <?php endforeach; ?>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </section>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                                <hr>

                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <label class="custom-form-label">Current Status Type</label>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control form-round" value="<?= $item->stage ?>" disabled>
                                                        </div>
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
                                                            <div class="row" id="approve-quote-row">
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
                                                                <div class="row" id="<?php echo strtolower($job_stage->stage) == 'awaiting approval on quote' && $job_stage->action !== 1 ? 'approval-quote-row' : ''; ?>">
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

                                            <fieldset style="margin-top: 20px">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <button class="btn btn btn-success add-note" type="button" data-content="reported">Add Reported Issue Notes</button>
                                                        <button class="btn btn btn-success add-note" type="button" data-content="client">Add Client Information Notes</button>
                                                        <button class="btn btn btn-success add-file" type="button" data-toggle="modal" data-target="#add-files-modal">Attach File</button>
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
                                        <form method="post" action="<?= HOST_NAME ?>customer/approve_quotes/<?= $item->id ?>">
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
                                        <form method="post" action="<?= HOST_NAME ?>customer/job_note_add/<?= $item->id ?>">
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

                            <!-- Add Attachment Modal -->
                            <div class="modal fade" id="add-files-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form method="post" action="<?= HOST_NAME ?>customer/job_attachment_add/<?= $item->id ?>" enctype="multipart/form-data">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="<?= VENDOR_DIR ?>stacked-menu/stacked-menu.min.js"></script>
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

        $(document).on('click', '.add-note', function (e) {
            $('#add-notes-modal .modal-title').html(e.target.innerText);
            $('#add-note-type').val(e.target.dataset.content);
            $('#add-notes-modal').modal();
        });

        //$(document).on('click', '#approve-quote-btn', function (e) {
        //    $.ajax({
        //        url: "<?//= HOST_NAME ?>//customer/approve_quote/<?//= $item->id ?>//",
        //        type: "POST",
        //        dataType: "json"
        //    }).done(function (data) {
        //        if (data == 1) {
        //            $('#approve-quote-row').remove();
        //            $('#approval-quote-row .col-md-8').append("<label class=\"badge badge-success\">Approved</label>");
        //        }
        //    });
        //});
    });
</script>
<style>
    fieldset legend {margin-bottom: 20px; color: #444444}
    #approve-quotes-modal .row {border-bottom: solid 1px #e0e0e0; margin-bottom: 15px}
    .quote-approve-modal-row div {font-weight: 600; color: #444444; font-size: 13px}
    #approve-quotes-modal .form-group {margin-bottom: 0}
    #approve-quotes-modal fieldset {width: 95%;margin: auto;}
</style>
<?php endif; ?>

