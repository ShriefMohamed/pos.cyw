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
                        <a href="<?= HOST_NAME ?>admin" class="menu-link">
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





                    <!-- .menu-item -->
                    <li class="menu-item">
                        <a href="<?= HOST_NAME ?>admin/jobs" class="menu-link">
                            <span class="menu-icon oi oi-browser"></span>
                            <span class="menu-text">Jobs</span>
                            <?php $count = \Framework\models\jobs\RepairsModel::Count("WHERE status != 'trash' "); ?>
                            <span class="badge badge-success"><?= $count ?></span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= HOST_NAME ?>pos/sales" class="menu-link">
                            <span class="menu-icon oi oi-browser"></span>
                            <span class="menu-text">Sales</span>
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


                    <!-- Quotes -->
                    <li class="menu-header">Quotes Management </li>
                    <li class="menu-item has-child">
                        <a href="#" class="menu-link">
                            <span class="menu-icon oi oi-people"></span>
                            <span class="menu-text">Quotes</span>
                        </a>
                        <!-- child menu -->
                        <ul class="menu">
                            <li class="menu-item">
                                <a href="<?= HOST_NAME ?>quotes/quotes" class="menu-link">Quotes</a>
                            </li>
                            <li class="menu-item">
                                <a href="<?= HOST_NAME ?>quotes/quotes?ex=1" class="menu-link">Expired Quotes</a>
                            </li>
                            <li class="menu-item">
                                <a href="<?= HOST_NAME ?>quotes/quote_add" class="menu-link">Add Quote</a>
                            </li>
                            <li class="menu-item">
                                <a href="<?= HOST_NAME ?>quotes/quote_template" class="menu-link">Quote Template</a>
                            </li>
                        </ul>
                    </li>


                    <!-- POS -->
                    <li class="menu-header">POS Management </li>

                    <li class="menu-item has-child">
                        <a href="#" class="menu-link">
                            <span class="menu-icon oi oi-people"></span>
                            <span class="menu-text">Sales</span>
                        </a>
                        <!-- child menu -->
                        <ul class="menu">
                            <li class="menu-item">
                                <a href="<?= HOST_NAME ?>pos/sales" class="menu-link">Sales Dashboard</a>
                            </li>
                            <li class="menu-item">
                                <a href="<?= HOST_NAME ?>pos/sales_list" class="menu-link">Sales History</a>
                            </li>
                            <li class="menu-item">
                                <a href="<?= HOST_NAME ?>pos/sale_add" class="menu-link">New Sale</a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-item has-child">
                        <a href="#" class="menu-link">
                            <span class="menu-icon oi oi-people"></span>
                            <span class="menu-text">Inventory</span>
                        </a>
                        <!-- child menu -->
                        <ul class="menu">
                            <li class="menu-item">
                                <a href="<?= HOST_NAME ?>pos/inventory" class="menu-link">Inventory Dashboard</a>
                            </li>
                            <li class="menu-item">
                                <a href="<?= HOST_NAME ?>pos/items" class="menu-link">All Items</a>
                            </li>
                            <li class="menu-item">
                                <a href="<?= HOST_NAME ?>pos/item_add" class="menu-link">New Item</a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-item has-child">
                        <a href="#" class="menu-link">
                            <span class="menu-icon oi oi-people"></span>
                            <span class="menu-text">Customers</span>
                        </a>
                        <!-- child menu -->
                        <ul class="menu">
                            <li class="menu-item">
                                <a href="<?= HOST_NAME ?>pos/customers" class="menu-link">Customers Dashboard</a>
                            </li>
                            <li class="menu-item">
                                <a href="<?= HOST_NAME ?>pos/customers_list" class="menu-link">All Customers</a>
                            </li>
                            <li class="menu-item">
                                <a href="<?= HOST_NAME ?>pos/customer_add" class="menu-link">New Customer</a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-item has-child">
                        <a href="#" class="menu-link">
                            <span class="menu-icon oi oi-globe"></span>
                            <span class="menu-text">Settings</span>
                        </a>
                        <!-- child menu -->
                        <ul class="menu">
                            <li class="menu-item">
                                <a href="<?= HOST_NAME ?>pos/settings" class="menu-link">Settings Dashboard</a>
                            </li>
                        </ul>
                    </li>



                    <!-- Xero -->
                    <li class="menu-header">Xero Operations</li>
                    <li class="menu-item has-child">
                        <a href="#" class="menu-link">
                            <span class="menu-icon oi oi-people"></span>
                            <span class="menu-text">Xero Sync</span>
                        </a>
                        <!-- child menu -->
                        <ul class="menu">
                            <li class="menu-item">
                                <a href="<?= HOST_NAME ?>xero/sync?type=products" class="menu-link">Sync Products</a>
                            </li>
                            <li class="menu-item">
                                <a href="<?= HOST_NAME ?>xero/sync?type=customers" class="menu-link">Sync Customers</a>
                            </li>
                            <li class="menu-item">
                                <a href="<?= HOST_NAME ?>xero/sync?type=accounts" class="menu-link">Sync Accounts</a>
                            </li>
                            <li class="menu-item">
                                <a href="<?= HOST_NAME ?>xero/sync?type=invoices" class="menu-link">Sync Invoices</a>
                            </li>
                        </ul>
                    </li>



                    <!-- Jobs -->
                    <li class="menu-header">Jobs Management</li>

                    <li class="menu-item has-child">
                        <a href="#" class="menu-link">
                            <span class="menu-icon oi oi-people"></span>
                            <span class="menu-text">Repair Jobs</span>
                        </a>
                        <!-- child menu -->
                        <ul class="menu">
                            <li class="menu-item">
                                <a href="<?= HOST_NAME ?>admin/jobs" class="menu-link">All Jobs</a>
                            </li>
                            <li class="menu-item">
                                <a href="<?= HOST_NAME ?>admin/jobs?status=trash" class="menu-link">Trash Jobs</a>
                            </li>
                            <li class="menu-item">
                                <a href="<?= HOST_NAME ?>admin/job_add" class="menu-link">Add Job</a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-item has-child">
                        <a href="#" class="menu-link">
                            <span class="menu-icon oi oi-people"></span>
                            <span class="menu-text">Quotes Jobs</span>
                        </a>
                        <!-- child menu -->
                        <ul class="menu">
                            <li class="menu-item">
                                <a href="<?= HOST_NAME ?>admin/quote_jobs" class="menu-link">All Jobs</a>
                            </li>
                            <li class="menu-item">
                                <a href="<?= HOST_NAME ?>admin/quote_jobs?status=complete" class="menu-link">Completed Jobs</a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-item">
                        <a href="<?= HOST_NAME ?>admin/stages" class="menu-link">
                            <span class="menu-icon oi oi-grid-two-up"></span>
                            <span class="menu-text">Repair Stages</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= HOST_NAME ?>admin/insurance_reports" class="menu-link">
                            <span class="menu-icon oi oi-bar-chart"></span>
                            <span class="menu-text">Insurance Reports</span>
                        </a>
                    </li>

                    <li class="menu-item has-child">
                        <a href="#" class="menu-link">
                            <span class="menu-icon oi oi-people"></span>
                            <span class="menu-text">Users</span>
                        </a>
                        <!-- child menu -->
                        <ul class="menu">
                            <li class="menu-item">
                                <a href="<?= HOST_NAME ?>admin/users" class="menu-link">All Users</a>
                            </li>
                            <li class="menu-item">
                                <a href="<?= HOST_NAME ?>admin/users?type=admin" class="menu-link">Admins</a>
                            </li>
                            <li class="menu-item">
                                <a href="<?= HOST_NAME ?>admin/users?type=technician" class="menu-link">Technicians</a>
                            </li>
                            <li class="menu-item">
                                <a href="<?= HOST_NAME ?>admin/users?type=customer" class="menu-link">Customers</a>
                            </li>
                            <li class="menu-item">
                                <a href="<?= HOST_NAME ?>admin/user_add" class="menu-link">Add User</a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-item has-child">
                        <a href="#" class="menu-link">
                            <span class="menu-icon oi oi-people"></span>
                            <span class="menu-text">Insurance Companies</span>
                        </a>
                        <!-- child menu -->
                        <ul class="menu">
                            <li class="menu-item">
                                <a href="<?= HOST_NAME ?>admin/insurance" class="menu-link">All Companies</a>
                            </li>
                            <li class="menu-item">
                                <a href="<?= HOST_NAME ?>admin/insurance_add" class="menu-link">Add Company</a>
                            </li>
                        </ul>
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
                    <?php if (\Framework\Lib\Session::Get('messages')[0] !== 'error') : ?>
                        <div class="alert alert-info" style="margin: 4px 8px 6px;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <strong style="line-height: 1.5;" class="badge badge-info">Success!</strong>
                            <?= \Framework\Lib\Session::Get('messages')[1] ?>
                        </div>
                    <?php else : ?>
                        <div class="alert alert-danger" style="margin: 4px 8px 6px;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <strong style="line-height: 1.5;" class="badge badge-danger">Oh snap!</strong> <?= \Framework\Lib\Session::Get('messages')[1] ?>
                        </div>
                    <?php endif; ?>
                    <?php \Framework\Lib\Session::Remove('messages'); ?>
                <?php endif; ?>



                <!--feedback modal-->
                <div class="modal fade" id="feedback-modal">
                    <div class="modal-dialog modal-content" style="background-color: transparent;border: 0;">
                        <div class="alert feedback-body alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <span class="badge">Success!</span>
                            <div id="feedback-msg" style="display: inline"></div>
                        </div>
                    </div>
                </div>

                <?php if (\Framework\Lib\Session::Exists('messages')) : ?>
                    <script>
                        $(document).ready(function () {
                            showFeedback("<?= \Framework\Lib\Session::Get('messages')[0] ?>", "<?= \Framework\Lib\Session::Get('messages')[1] ?>");
                        });
                    </script>

                    <?php \Framework\Lib\Session::Remove('messages'); ?>
                <?php endif; ?>
