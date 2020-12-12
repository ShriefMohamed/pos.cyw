<?php if (isset($tracking_info) && $tracking_info) : ?>
    <?php
    $carriers = array(
        'auspost' => 'Australia Post',
        'couriersplease' => 'Couriers Please',
        'fastway' => 'FastWay',
        'apdparcel' => 'APD Parcel'
    );
    ?>
    <div class="page-section">
        <div class="row">
            <div class="col-md-8" style="margin: auto">
                <section class="card">
                    <div class="card-body">
                        <fieldset>
                            <legend>Parcel Tracking Log. Job: <strong><?= $tracking_info[0]->job_id ?></strong></legend>
                            <hr class="my-3">

                            <?php foreach ($tracking_info as $item) : ?>
                            <section class="card">
                                <div class="card-body">
                                    <fieldset>
                                        <h6 class="fieldset-title">Tracking Number: <span><?= $item->tracking_number ?></span></h6>
                                        <h6 class="fieldset-title">Carrier: <span><?= $carriers[$item->carrier] ?></span></h6>
                                        <h6 class="fieldset-title status"><?= $item->status ?></h6>

                                        <?php $logs = \Framework\models\jobs\Repair_tracking_logsModel::getAll("WHERE repair_tracking_id = '$item->id'"); ?>
                                        <?php if (isset($logs) && $logs) : ?>
                                            <section class="card card-expansion-item">
                                                <header class="card-header" id="heading<?= $item->id ?>">
                                                    <button class="btn btn-reset d-flex justify-content-between w-100 collapsed" data-toggle="collapse" data-target="#collapse<?= $item->id ?>" aria-expanded="false" aria-controls="collapse<?= $item->id ?>">
                                                        <span>Tracking History</span>
                                                        <span class="collapse-indicator"><i class="fa fa-fw fa-chevron-down"></i></span>
                                                    </button>
                                                </header>
                                                <div id="collapse<?= $item->id ?>" class="collapse" aria-labelledby="heading<?= $item->id ?>">
                                                    <div class="card-body pt-0 pb-0">
                                                        <div class="row" style="font-weight: 700; color: #4d4d54">
                                                            <div class="col-md-6">Details</div>
                                                            <div class="col-md-3">Location</div>
                                                            <div class="col-md-3">Date&Time</div>
                                                        </div>
                                                        <?php foreach ($logs as $log) : ?>
                                                        <div class="row">
                                                            <div class="col-md-6"><?= $log->details ?></div>
                                                            <div class="col-md-3"><?= $log->location ?></div>
                                                            <div class="col-md-3"><?= $log->date_time ?></div>
                                                        </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </section>
                                        <?php endif; ?>
                                    </fieldset>
                                </div>
                            </section>
                            <hr>
                            <?php endforeach; ?>
                        </fieldset>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <style>
        fieldset legend {color: #444444}
        .fieldset-title {font-size: 15px; color: #4d4d54}
        .fieldset-title span {font-size: 14px; font-weight: 400}
        .fieldset-title.status {color: rgb(0, 172, 62); font-size: 18px;font-weight: 500;letter-spacing: .8px;line-height: 24px;}
        .card-expansion-item {margin-top: 25px}
        .card-expansion-item .card-header {background-color: transparent; color: #212129;}
        .card-expansion-item .card-header button {font-weight: 700;}
        .card-expansion-item .card-header:hover {background-color: #e2e2e2}
        .card-expansion-item .card-body .row {border-bottom: 1px solid #e2e2e2;color: #4d4d54;
            justify-content: space-between;padding: 1rem; align-content: center}
        .card-expansion-item .card-body .row:last-child {border: 0}
    </style>
<?php endif; ?>

