<aside class="app-aside">
    <!-- .aside-content -->
    <div class="aside-content">
        <!-- .aside-header -->
        <header class="aside-header">
            <!-- toggle menu -->
            <button class="hamburger hamburger-squeeze" type="button" data-toggle="aside" aria-label="Menu" aria-controls="navigation">
              <span class="hamburger-box">
                <span class="hamburger-inner"></span>
              </span>
            </button>
            <!-- /toggle menu -->
            <!-- .btn-account -->
            <button class="btn-account d-flex d-md-none" type="button" data-toggle="collapse" data-target="#dropdown-aside">
                <span class="user-avatar user-avatar-lg">
                    <img src="<?= IMAGES_DIR . 'users/' . \Framework\Lib\Session::Get('loggedin')->image ?>" alt="">
                </span>
                <span class="account-icon">
                    <span class="fa fa-caret-down fa-lg"></span>
                </span>
                <span class="account-summary">
                <span class="account-name"><?= \Framework\Lib\Session::Get('loggedin')->firstName . ' ' . \Framework\Lib\Session::Get('loggedin')->lastName ?></span>
              </span>
            </button>
            <!-- /.btn-account -->
            <!-- .dropdown-aside -->
            <div id="dropdown-aside" class="dropdown-aside collapse">
                <!-- dropdown-items -->
                <div class="pb-3">
                    <a class="dropdown-item" href="<?= HOST_NAME ?>admin/profile">
                        <span class="dropdown-icon oi oi-person"></span> Profile</a>
                    <a class="dropdown-item" href="<?= HOST_NAME ?>admin/signout">
                        <span class="dropdown-icon oi oi-account-logout"></span> Logout</a>
                    <div class="dropdown-divider"></div>
                </div>
                <!-- /dropdown-items -->
            </div>
            <!-- /.dropdown-aside -->
        </header>
        <!-- /.aside-header -->
        <!-- .aside-menu -->
        <section class="aside-menu has-scrollable">
            <!-- .stacked-menu -->
            <nav id="stacked-menu" class="stacked-menu">
                <!-- .menu -->
                <ul class="menu">
                    <!-- .menu-item -->
                    <li class="menu-item">
                        <a href="<?= HOST_NAME ?>admin/dashboard" class="menu-link">
                            <span class="menu-icon oi oi-dashboard"></span>
                            <span class="menu-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= HOST_NAME ?>pos/sale_add" class="menu-link">
                            <span class="menu-icon oi oi-dashboard"></span>
                            <span class="menu-text">POS</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= HOST_NAME ?>quotes/quote_add" class="menu-link">
                            <span class="menu-icon oi oi-browser"></span>
                            <span class="menu-text">Generate Quote</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= HOST_NAME ?>licenses/license_assign" class="menu-link">
                            <span class="menu-icon oi oi-browser"></span>
                            <span class="menu-text">Assign Digital License</span>
                        </a>
                    </li>

                    <li class="menu-header">SETTINGS </li>
                    <li class="menu-item">
                        <a href="<?= HOST_NAME ?>pos" class="menu-link">
                            <span class="menu-icon oi oi-browser"></span>
                            <span class="menu-text">POS</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= HOST_NAME ?>quotes" class="menu-link">
                            <span class="menu-icon oi oi-browser"></span>
                            <span class="menu-text">Quotes</span>
                            <?php $menu_quotes_count = \Framework\models\quotes\QuotesModel::Count("WHERE status = 'sent' && expired != '1'"); ?>
                            <span class="badge badge-success"><?= $menu_quotes_count ?></span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= HOST_NAME ?>admin" class="menu-link">
                            <span class="menu-icon oi oi-browser"></span>
                            <span class="menu-text">Jobs</span>
                            <?php $menu_jobs_count = \Framework\models\jobs\RepairsModel::Count("WHERE status != 'trash' "); ?>
                            <span class="badge badge-success"><?= $menu_jobs_count ?></span>
                        </a>
                    </li
                    <li class="menu-item">
                        <a href="<?= HOST_NAME ?>insurance" class="menu-link">
                            <span class="menu-icon oi oi-browser"></span>
                            <span class="menu-text">Insurance</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= HOST_NAME ?>licenses" class="menu-link">
                            <span class="menu-icon oi oi-browser"></span>
                            <span class="menu-text">Digital Licenses</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= HOST_NAME ?>customers" class="menu-link">
                            <span class="menu-icon oi oi-browser"></span>
                            <span class="menu-text">Customers</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= HOST_NAME ?>xero" class="menu-link">
                            <span class="menu-icon oi oi-browser"></span>
                            <span class="menu-text">Xero</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= HOST_NAME ?>admin/logs" class="menu-link">
                            <span class="menu-icon fa fa-paper-plane"></span>
                            <span class="menu-text">Logs</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </section>
    </div>
    <!-- /.aside-content -->
</aside>


<main class="app-main">
    <!-- .wrapper -->
    <div class="wrapper">

        <div class="page">
            <div class="page-inner">

                <?php if (\Framework\Lib\Session::Exists('messages')) : ?>
                    <?php foreach (\Framework\Lib\Session::Get('messages') as $message) : ?>
                        <?php if ($message['type'] !== 'error') : ?>
                            <div class="alert alert-info" style="margin: 4px 8px 6px;">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <strong style="line-height: 1.5;" class="badge badge-info">Success!</strong>
                                <?= $message['message'] ?>
                            </div>
                        <?php else : ?>
                            <div class="alert alert-danger" style="margin: 4px 8px 6px;">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <strong style="line-height: 1.5;" class="badge badge-danger">Oh snap!</strong> <?= $message['message'] ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php \Framework\Lib\Session::Remove('messages'); ?>
                <?php endif; ?>



                <!--feedback modal. for ajax feedback-->
                <div class="modal fade" id="feedback-modal" style="z-index: 9999">
                    <div class="modal-dialog modal-content" style="background-color: transparent;border: 0;">
                        <div class="alert feedback-body alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <span class="badge">Success!</span>
                            <div id="feedback-msg" style="display: inline"></div>
                        </div>
                    </div>
                </div>
