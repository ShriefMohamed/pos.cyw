        <div id="loginform">
            <div class="p-b-20 border-bottom border-secondary">
                <span class="db">
                    <span class="logo-text" style="font-size: 15px; font-weight: 800; color: #444">Two-factor authentication required</span>
                </span>
            </div>

            <div class="row">
                <div class="col-12">
                    <p class="p-t-20" style="color: #555;">For security reasons, A 6-digit login code is required when anyone tries to access your account from a new device or browser.</p>
                </div>
            </div>
            <!-- Form -->
            <form class="form-horizontal m-t-20" id="loginform" method="post">
                <div class="row p-b-30">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="label-default control-label">Enter the 6-digit code sent to your registered phone.</label>
                            <div class="row">
                                <div class="col-sm-9">
                                    <input type="number" name="code" class="form-control form-control-lg" aria-label="code" placeholder="Login Code" required>
                                </div>
                            </div>
                        </div>
                        <span>Didn't receive your code?</span><a href="<?= HOST_NAME ?>login/checkpoint/resend"> Resend</a>
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
