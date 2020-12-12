        <div id="loginform">
            <div class="p-b-20 border-bottom border-secondary">
                <span class="db">
                    <span class="logo-text" style="font-size: 15px; font-weight: 800; color: #444">Reset your password</span>
                </span>
            </div>

            <!-- Form -->
            <form class="form-horizontal m-t-20" id="loginform" method="post">
                <div class="row p-b-30">
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <label class="label-default control-label">Enter your new password.</label>
                            <div class="row">
                                <div class="col-sm-9">
                                    <input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="Password" aria-label="Password" required>
                                    <i class="fa fa-eye toggle-password" toggle="#password"></i>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-default control-label">Confirm your new password.</label>
                            <div class="row">
                                <div class="col-sm-9">
                                    <input type="password" id="password-confirm" name="password-confirm" class="form-control form-control-lg" placeholder="confirm Password" aria-label="Confirm Password" required>
                                    <i class="fa fa-eye toggle-password" toggle="#password-confirm"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row border-top border-secondary">
                    <div class="col-12">
                        <div class="form-group">
                            <div class="p-t-20">
                                <button class="btn btn-success float-right" name="submit" type="submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>