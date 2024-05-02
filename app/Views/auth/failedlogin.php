<?= $this->extend($config->viewLayout) ?>
<?= $this->extend('auth/index'); ?>

<?= $this->section('content'); ?>
<div class="row justify-content-center align-items-center h-100" style="background-color: #2d499d">
    <div class="col-xl-4 col-lg-5 col-10">
        <div class="card">
            <div class="card-content">
                <div id="auth-left">

                    <!-- <div class="auth-logo">
                        <a href="<? //= base_url(); 
                                    ?>"><img src="<? //= base_url('media/icon/logo.svg'); 
                                                    ?>" alt="Logo" /></a>
                    </div> -->

                    <h1 class="auth-title text-center">Failed to Login</h1>
                    <p class="auth-subtitle mb-4 text-center">
                        This email need password to login to this account. Try other options.
                    </p>

                    <?= view('Myth\Auth\Views\_message_block') ?>




                    <a class="btn btn-primary btn-block shadow mt-3" href="<?= site_url('/login') ?>">
                        <?= lang('Auth.loginAction') ?> With Account
                    </a>

                    <a class="btn btn-danger btn-block shadow mt-3" href="<?= $link ?>" target="blank">
                        Login With Google
                    </a>



                    <!-- <<<<<<< HEAD -->
                    <!-- <?php //if ($config->allowRegistration) : 
                            ?>
                        <div class="text-center mt-3 text-lg">
                            <p class="text-gray-600">
                                <a href="<? //= url_to('register') 
                                            ?>" class="font-bold"><? //= lang('Auth.needAnAccount') 
                                                                    ?></a> <br>
                            </p>
                        </div>
                    <?php //endif; 
                    ?> -->

                    <?php if ($config->allowRegistration) : ?>
                        <div class="text-center mt-3 text-lg">
                            <p class="text-gray-600">
                                <a href="<?= url_to('register') ?>" class="font-bold"><?= lang('Auth.needAnAccount') ?></a> <br>
                            </p>
                        </div>

                        <h6 class="auth-subtitle mb-4 text-center" style="margin-bottom: 0 !important;color:black"> OR </h6>
                        <a class="btn btn-danger btn-block shadow mt-3" href="<?= $link ?>" target="blank">
                            Register With Google
                        </a>
                    <?php endif; ?>



                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>