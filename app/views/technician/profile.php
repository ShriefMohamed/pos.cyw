<?php if (isset($user) && $user !== false): ?>
<main class="app-main">
    <div class="wrapper">
        <div class="page">

            <header class="page-cover" style="background-color: transparent; padding-top: 0; padding-bottom: 0">
                <div class="text-center">
                    <a href="<?= HOST_NAME . $user->role.'/profile' ?>" class="user-avatar user-avatar-xl">
                        <img src="<?= IMAGES_DIR . 'users/' . $user->image ?>">
                    </a>
                    <h2 class="h4 mt-3 mb-0"><?= $user->firstName . ' ' . $user->lastName ?></h2>
                    <p class="text-muted"><?= ucfirst($user->role) ?></p>
                </div>
            </header>
            <div class="page-inner">
                <div class="page-section">
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- .card -->
                            <div class="card card-fluid">
                                <h6 class="card-header"> Account </h6>
                                <!-- .card-body -->
                                <div class="card-body">
                                    <!-- form -->
                                    <form method="post">
                                        <!-- form row -->
                                        <div class="form-row">
                                            <!-- form column -->
                                            <div class="col-md-6 mb-3">
                                                <label for="input01">First Name</label>
                                                <input type="text" class="form-control" id="input01" name="firstName" value="<?= $user->firstName ?>">
                                            </div>
                                            <!-- /form column -->
                                            <!-- form column -->
                                            <div class="col-md-6 mb-3">
                                                <label for="input02">Last Name</label>
                                                <input type="text" class="form-control" id="input02" name="lastName" value="<?= $user->lastName ?>">
                                            </div>
                                            <!-- /form column -->
                                        </div>
                                        <!-- /form row -->
                                        <div class="form-row">
                                            <div class="col-md-6 mb-3">
                                                <label for="input04">Username</label>
                                                <input type="text" class="form-control" id="input04" name="username" value="<?= $user->username ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="input03">Email</label>
                                                <input type="email" class="form-control" id="input03" name="email" value="<?= $user->email ?>">
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                            <label for="input05">New Password</label>
                                            <input type="password" class="form-control" id="input05" name="password">
                                        </div>
                                        <hr>
                                        <div class="form-row">
                                            <!-- form column -->
                                            <div class="col-md-6 mb-3">
                                                <label for="input07">Phone Number</label>
                                                <input type="text" class="form-control" id="input07" name="phone" value="<?= $user->phone ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="input08">Alternative Phone Number</label>
                                                <input type="text" class="form-control" id="input08" name="phone2" value="<?= $user->phone2 ?>">
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-row">
                                            <div class="col-md-6 mb-3">
                                                <label for="input09">Address</label>
                                                <input type="text" class="form-control" id="input09" name="address" value="<?= $user->address ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Company Name</label>
                                                <input type="text" name="company" class="form-control form-round" value="<?= $user->companyName ?>">
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-row">
                                            <div class="col-md-4 mb-3">
                                                <div class="list-group-item d-flex justify-content-between align-items-center" style="padding-left: 0">
                                                    <span>Two Factor Authentication</span>
                                                    <label class="switcher-control">
                                                        <input type="checkbox" class="switcher-input" <?php echo $user->twoFA == 1 ? 'checked' : '' ?> name="2fa">
                                                        <span class="switcher-indicator"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-primary" name="submit">Update Account</button>
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
</main>
<?php endif; ?>