<?php if (isset($repair) && $repair) : ?>
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


    <div class="row">
        <form id="rpair_form" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-lg-6 col-sm-12">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa  fa-user"></i>
                                </div>
                                <input type="text" class="form-control" value="<?= $repair->firstName.' '.$repair->lastName ?>" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-sm-12">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-folder"></i>
                                </div>
                                <input id="category" type="text" class="form-control" value="<?= $repair->stage ?>" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-sm-12">
                        <div class="form-group">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fas fa-mobile"></i>
                                    </div>
                                    <input type="text" class="form-control" value="<?= $repair->device ?>" disabled>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-sm-12">
                        <div class="form-group">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-folder"></i>
                                    </div>
                                    <input class="form-control" id="reparation_manufacturer" style="width: 100%;" value="<?= $repair->deviceManufacture ?>" disabled>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-sm-12">
                        <div class="form-group">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fas fa-link"></i>
                                    </div>
                                    <input id="defect" type="text" class="form-control defect_typeahead" value="<?= $repair->issue ?>" disabled>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-sm-12">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <input type="text" value="<?= \Framework\lib\Helper::ConvertDateFormat($repair->created, true) ?>" class="form-control" disabled>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-6">
                        <?php if (isset($quotes) && $quotes) : ?>
                        <div class="control-group table-group">
                            <div class="controls table-controls">
                                <table class="table items table-striped table-bordered table-condensed table-hover">
                                    <thead>
                                    <tr>
                                        <th colspan="1">Quote of Repair</th>
                                        <th colspan="3">Item Details</th>
                                        <th colspan="1">Item Cost</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <?php $total = 0; ?>

                                    <?php foreach ($quotes as $quote) : ?>
                                    <?php $total = $total + ($quote->quote + $quote->cost); ?>
                                    <tr>
                                        <td colspan="1" class="success"><span>$<?= floatval($quote->quote) ?></span></td>
                                        <td colspan="3" class="warning"><?= $quote->item ?></td>
                                        <td colspan="1" class="info"><span>$<?= floatval($quote->cost) ?></span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td colspan="5" class="warning"><span class="pull-right">Total</span></td>
                                        <td colspan="1" class="success"><span>$<?= floatval($total) ?></span></td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div style="clear: both;"></div>

            <div class="row">
                <div class="col-lg-12">
                    <?php if (isset($notes) && $notes) : ?>
                        <div class="col-lg-6">
                            <label class="custom-form-label" style="margin-bottom: 15px">Reported Notes</label>
                            <div class="form-group">
                                <i id="add_timestamp" class="fa fa-calendar" style="padding-right: 15px"></i>
                                <?php foreach ($notes as $note) : ?>
                                    <?php if ($note->type == 'reported') : ?>
                                        <time datetime="<?= $note->created ?>">{<?= \Framework\lib\Helper::ConvertDateFormat($note->created, true) ?>}</time><span style="margin: 0 5px;color: #357ebd" title="<?= $note->firstName.' '.$note->lastName.' {'.ucfirst($note->role).'}' ?>">{<?= $note->username ?>}</span> :: <span style="margin-left: 10px"><?= $note->note ?>.</span><br>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($notes) && $notes) : ?>
                        <div class="col-lg-6">
                            <label class="custom-form-label" style="margin-bottom: 15px">Diagnostic Notes</label>
                            <div class="form-group">
                                <i id="add_timestamp" class="fa fa-calendar" style="padding-right: 15px"></i>
                                <?php foreach ($notes as $note) : ?>
                                    <?php if ($note->type == 'diagnostic') : ?>
                                        <time datetime="<?= $note->created ?>">{<?= \Framework\lib\Helper::ConvertDateFormat($note->created, true) ?>}</time><span style="margin: 0 5px;color: #357ebd" title="<?= $note->firstName.' '.$note->lastName.' {'.ucfirst($note->role).'}' ?>">{<?= $note->username ?>}</span> :: <span style="margin-left: 10px"><?= $note->note ?>.</span><br>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>

</div>

<?php endif; ?>