<header class="app-header">
    <!-- .top-bar -->
    <div class="top-bar">
        <!-- .top-bar-brand -->
        <div class="top-bar-brand">
            <?php if ($this->controller == 'admin' || $this->controller == 'pos') : ?>
            <!-- toggle menu -->
            <button class="hamburger hamburger-squeeze mr-2" type="button" data-toggle="aside" aria-label="Menu" aria-controls="navigation">
              <span class="hamburger-box">
                <span class="hamburger-inner"></span>
              </span>
            </button>
            <!-- /toggle menu -->
            <?php endif; ?>

            <a href="<?= HOST_NAME.\Framework\Lib\Session::Get('loggedin')->role ?>">
                <img src="<?= IMAGES_DIR ?>brand.png" height="32" width="100%" alt="">
            </a>
        </div>
        <!-- /.top-bar-brand -->
        <!-- .top-bar-list -->
        <div class="top-bar-list">
            <!-- .top-bar-item -->
            <div class="top-bar-item px-2 d-md-none d-lg-none d-xl-none">
                <!-- toggle menu -->
                <button class="hamburger hamburger-squeeze" type="button" data-toggle="aside" aria-label="Menu" aria-controls="navigation">
                <span class="hamburger-box">
                  <span class="hamburger-inner"></span>
                </span>
                </button>
                <!-- /toggle menu -->
            </div>
            <!-- /.top-bar-item -->
            <!-- .top-bar-item -->
            <div class="top-bar-item top-bar-item-full">
                <!-- .top-bar-search -->
                <div class="top-bar-search">
                    <form method="post" action="<?= HOST_NAME ?>ajax/search">
                        <div class="input-group input-group-search">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><span class="oi oi-magnifying-glass"></span></span>
                            </div>
                            <input type="text" class="form-control" aria-label="Search" placeholder="Search" name="key">
                        </div>
                    </form>
                </div>
                <!-- /.top-bar-search -->
            </div>
            <!-- /.top-bar-item -->
            <!-- .top-bar-item -->
            <div class="top-bar-item top-bar-item-right px-0 d-none d-sm-flex">
                <!-- .btn-account -->
                <div class="dropdown">
                    <button class="btn-account d-none d-md-flex" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="user-avatar"><img src="<?= IMAGES_DIR . 'users/' . \Framework\Lib\Session::Get('loggedin')->image ?>" alt=""></span>
                        <span class="account-summary pr-lg-4 d-none d-lg-block">
                            <span class="account-name"><?= \Framework\Lib\Session::Get('loggedin')->firstName.' '.\Framework\Lib\Session::Get('loggedin')->lastName ?></span>
                        </span>
                    </button>
                    <div class="dropdown-arrow dropdown-arrow-left"></div>
                    <!-- .dropdown-menu -->
                    <div class="dropdown-menu">
                        <h6 class="dropdown-header d-none d-md-block d-lg-none"> <?= \Framework\Lib\Session::Get('loggedin')->firstName.' '.\Framework\Lib\Session::Get('loggedin')->lastName ?> </h6>
                        <a class="dropdown-item" href="<?= HOST_NAME . \Framework\Lib\Session::Get('loggedin')->role ?>/profile">
                            <span class="dropdown-icon oi oi-person"></span> Profile</a>
                        <a class="dropdown-item" href="<?= HOST_NAME . \Framework\Lib\Session::Get('loggedin')->role ?>/signout">
                            <span class="dropdown-icon oi oi-account-logout"></span> Logout</a>
                        <div class="dropdown-divider"></div>
                    </div>
                    <!-- /.dropdown-menu -->
                </div>
                <!-- /.btn-account -->
            </div>
            <!-- /.top-bar-item -->
        </div>
        <!-- /.top-bar-list -->
    </div>
    <!-- /.top-bar -->
</header>