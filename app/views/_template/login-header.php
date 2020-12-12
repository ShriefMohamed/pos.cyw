<main class="auth">
    <header id="auth-header" class="auth-header" style="background-image: url('<?= IMAGES_DIR ?>illustration/img-1.png');">
        <h1>
            <img src="<?= IMAGES_DIR ?>brand-inverse.png" alt="" height="72">
            <span class="sr-only">Sign In</span>
        </h1>
        <p> Don't have a account?
            <a href="<?= HOST_NAME ?>register">Create One</a>
        </p>
    </header>

    <?php if (\Framework\Lib\Session::Exists('messages')) : ?>
        <?php if (\Framework\Lib\Session::Get('messages')[0] !== 'error') : ?>
            <div class="alert alert-success" style="position: absolute;top: 40px;width: 40%;text-align: center;font-size: 1.3rem">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <span class="icon icon-ok-sign"></span> <strong class="badge badge-success" style="float: left">Success!</strong> <?= \Framework\Lib\Session::Get('messages')[1] ?>
            </div>
        <?php else : ?>
            <div class="alert alert-danger" style="position: absolute;top: 40px;width: 40%;text-align: center;font-size: 1.3rem">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <span class="icon icon-remove-sign"></span> <strong class="badge badge-danger" style="float: left">Oh snap!</strong> <?= \Framework\Lib\Session::Get('messages')[1] ?>
            </div>
        <?php endif; ?>

        <?php \Framework\Lib\Session::Remove('messages'); ?>
    <?php endif; ?>

