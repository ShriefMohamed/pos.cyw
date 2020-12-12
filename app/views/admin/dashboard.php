<div class="page-section">
    <section class="card">
        <header class="card-header">Overall Stats</header>
        <div class="card-body">

            <?php if (isset($repair_stages) && $repair_stages) : ?>
            <div class="row dashboard">
            <?php foreach ($repair_stages as $repair_stage) : ?>

                <?php if (strtolower($repair_stage->stage) == "awaiting diagnosis") : ?>
                <div class="col-md-3">
                    <div class="item">
                        <div class="head">
                            <i class="fa fa-users"></i>
                            <span><?= $repair_stage->count ?></span>
                        </div>
                        <a href="<?= HOST_NAME . 'admin/jobs?status=new' ?>">
                            <div class="footer">
                                <span>Jobs Awaiting Diagnostics</span>
                                <i class="fa fa-arrow-circle-right"></i>
                            </div>
                        </a>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (strtolower($repair_stage->stage) == "technician diagnosing") : ?>
                <div class="col-md-3">
                    <div class="item">
                        <div class="head">
                            <i class="fa fa-users"></i>
                            <span><?= $repair_stage->count ?></span>
                        </div>
                        <a href="<?= HOST_NAME . 'admin/jobs?status=diagnosing' ?>">
                            <div class="footer">
                                <span>Jobs In Diagnostics</span>
                                <i class="fa fa-arrow-circle-right"></i>
                            </div>
                        </a>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (strtolower($repair_stage->stage) == "repairing") : ?>
                <div class="col-md-3">
                    <div class="item">
                        <div class="head">
                            <i class="fa fa-users"></i>
                            <span><?= $repair_stage->count ?></span>
                        </div>
                        <a href="<?= HOST_NAME . 'admin/jobs?status=repairing' ?>">
                            <div class="footer">
                                <span>Jobs Being Repaired</span>
                                <i class="fa fa-arrow-circle-right"></i>
                            </div>
                        </a>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (strtolower($repair_stage->stage) == "awaiting parts") : ?>
                <div class="col-md-3">
                    <div class="item">
                        <div class="head">
                            <i class="fa fa-users"></i>
                            <span><?= $repair_stage->count ?></span>
                        </div>
                        <a href="<?= HOST_NAME . 'admin/jobs?status=parts' ?>">
                            <div class="footer">
                                <span>Jobs Awaiting Parts</span>
                                <i class="fa fa-arrow-circle-right"></i>
                            </div>
                        </a>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (strtolower($repair_stage->stage) == "called customer, awaiting collection, completed") : ?>
                <div class="col-md-3">
                    <div class="item">
                        <div class="head">
                            <i class="fa fa-users"></i>
                            <span><?= $repair_stage->count ?></span>
                        </div>
                        <a href="<?= HOST_NAME . 'admin/jobs?status=completed' ?>">
                            <div class="footer">
                                <span>Jobs Awaiting Collection</span>
                                <i class="fa fa-arrow-circle-right"></i>
                            </div>
                        </a>
                    </div>
                </div>
                <?php endif; ?>

            <?php endforeach; ?>
            </div>
            <hr>
            <?php endif; ?>

            
            <div class="row avg-time-row" style="margin-bottom: 3rem">
                <?php if (isset($diagnostics_avg)) : ?>
                <div class="col-md-6">
                    <h3 class="section-title"> Awaiting diagnostics: <strong><?= round($diagnostics_avg, 2) ?></strong> Hours.</h3>
                    <div class="progress mb-3">
                        <div class="progress-bar progress-bar-striped bg-warning" role="progressbar" style="width: <?= round($diagnostics_avg) ?>%" aria-valuenow="<?= round($diagnostics_avg) ?>" aria-valuemin="0" aria-valuemax="<?= ceil($diagnostics_avg / 100) * 100 ?>"></div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (isset($completion_avg)) : ?>
                <div class="col-md-6">
                    <h3 class="section-title"> Avg Job Completion time: <strong><?= round($completion_avg, 2) ?></strong> Hours.</h3>
                    <div class="progress mb-3">
                        <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: <?= round($completion_avg) ?>%" aria-valuenow="<?= round($completion_avg) ?>" aria-valuemin="0" aria-valuemax="<?= ceil($completion_avg / 100) * 100 ?>"></div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <hr>

            <div class="row dashboard">
                <?php if (isset($completed_today) && $completed_today) : ?>
                    <div class="col-md-4">
                        <div class="item">
                            <div class="head">
                                <i class="oi oi-dashboard"></i>
                                <span><?= $completed_today->count ?></span>
                            </div>
                            <a href="<?= HOST_NAME . 'admin/jobs?status=completed' ?>">
                                <div class="footer">
                                    <span>Jobs Completed Today</span>
                                    <i class="fa fa-arrow-circle-right"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (isset($completed_week) && $completed_week) : ?>
                    <div class="col-md-4">
                        <div class="item">
                            <div class="head">
                                <i class="oi oi-dashboard"></i>
                                <span><?= $completed_week->count ?></span>
                            </div>
                            <a href="<?= HOST_NAME . 'admin/jobs?status=completed' ?>">
                                <div class="footer">
                                    <span>Jobs Completed This Week</span>
                                    <i class="fa fa-arrow-circle-right"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (isset($completed_month) && $completed_month) : ?>
                    <div class="col-md-4">
                        <div class="item">
                            <div class="head">
                                <i class="oi oi-dashboard"></i>
                                <span><?= $completed_month->count ?></span>
                            </div>
                            <a href="<?= HOST_NAME . 'admin/jobs?status=completed' ?>">
                                <div class="footer">
                                    <span>Jobs Completed This Month</span>
                                    <i class="fa fa-arrow-circle-right"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<style>
    .dashboard {margin-bottom: 3rem}
    .dashboard .item {background: linear-gradient(to bottom right, #20a8d8, #20a8d8b0);border-radius: 6px;
        vertical-align: top; box-shadow: 0 0 16px #00000045; cursor: pointer; width: 100%; margin-bottom: 1rem}
    .dashboard .item:hover {opacity: 0.8}
    .dashboard .item .head {display: flex;vertical-align: top;overflow: hidden;
        border-top-left-radius: 6px; border-top-right-radius: 6px;
        background-color: #337ab7;border-color: #337ab7;padding: 20px 15px;border-bottom: 1px solid transparent;}
    .dashboard .item .head span {color: #FFF;font-size: 32px;font-weight: 800;position: absolute;right: 5%;}
    .dashboard .item .head i {color: #fff;font-size: 45px;}
    .dashboard .item a {text-decoration: none}
    .dashboard .item .footer {padding: 10px 15px;background-color: #f5f5f5;border-top: 1px solid #ddd;
        border-bottom-right-radius: 6px;border-bottom-left-radius: 6px;display: flex;}
    .dashboard .item .footer i {position: absolute;right: 0;margin: 4px 7%;}

    .avg-time-row .section-title strong {color: #a94442}
</style>
