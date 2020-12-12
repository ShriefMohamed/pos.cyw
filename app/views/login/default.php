<form class="auth-form" id="login-form" method="post">
    <!-- .form-group -->
    <div class="form-group">
        <div class="form-label-group">
            <input type="text" id="inputUser" class="form-control" placeholder="Username or Email" name="username" required autofocus>
            <label for="inputUser">Username or Email Address</label>
        </div>
    </div>
    <!-- /.form-group -->
    <!-- .form-group -->
    <div class="form-group">
        <div class="form-label-group">
            <input type="password" id="inputPassword" class="form-control" placeholder="Password" name="password" required>
            <label for="inputPassword">Password</label>
        </div>
    </div>
    <!-- /.form-group -->
    <!-- .form-group -->
    <div class="form-group">
        <button class="btn btn-lg btn-primary btn-block" name="login" type="submit">Sign In</button>
    </div>
    <!-- /.form-group -->
    <!-- .form-group -->
    <div class="form-group text-center">
        <div class="custom-control custom-control-inline custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="remember-me" name="">
            <label class="custom-control-label" for="remember-me">Keep me sign in</label>
        </div>
    </div>
    <!-- /.form-group -->
    <!-- recovery links -->
    <div class="text-center pt-3">
        <button class="btn btn-info" id="to-recover" type="button"><i class="fa fa-lock m-r-5"></i> Lost password?</button>
    </div>
    <!-- /recovery links -->
</form>

<form class="auth-form" id="recover-form" style="display: none" method="post">
    <div class="text-center mb-4">
        <div class="mb-4">
        <h1 class="h3"> Reset Your Password </h1>
    </div>
    </div>
    <p class="mb-4">Enter your e-mail address below and we will send you instructions how to recover a password.</p>

    <div class="form-group mb-4">
        <label class="d-block text-left" for="inputUser">Email</label>
        <input type="email" id="inputUser" class="form-control form-control-lg" required="" autofocus="">
        <p class="text-muted">
            <small>We'll send password reset link to your email.</small>
        </p>
    </div>
    <!-- /.form-group -->
    <!-- actions -->
    <div class="d-block d-md-inline-block mb-2">
        <button class="btn btn-block btn-primary" name="recover" type="submit">Reset Password</button>
    </div>
    <div class="d-block d-md-inline-block mb-2" style="float: right">
        <a class="btn btn-success" href="#" id="to-login">Return To Login</a>
    </div>
</form>

<script>
    jQuery(document).ready(function () {
        // ==============================================================
        // Login and Recover Password
        // ==============================================================
        $('#to-recover').on("click", function() {
            $("#login-form").slideUp();
            $("#recover-form").fadeIn();
        });
        $('#to-login').click(function(){
            $("#recover-form").hide();
            $("#login-form").fadeIn();
        });
    });
</script>