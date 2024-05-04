<?= $this->extend($config->viewLayout) ?>
<?= $this->extend('auth/index'); ?>

<?= $this->section('content'); ?>
<div class="row justify-content-center align-items-center h-100" style="background-color: #2d499d">
    <div class="col-xl-4 col-lg-5 col-10">

        <div class="card">
            <h2 class="card-header" style="text-align: center;"><?= lang('Auth.register') ?></h2>
            <div class="card-body">

                <?= view('Myth\Auth\Views\_message_block') ?>

                <form action="<?= url_to('register') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <label for="email"><?= lang('Auth.email') ?></label>
                        <input type="email" class="form-control <?php if (session('errors.email')) : ?>is-invalid<?php endif ?>" name="email" aria-describedby="emailHelp" placeholder="<?= lang('Auth.email') ?>" value="<?= old('email') ?>" required>
                        <small id="emailHelp" class="form-text text-muted"><?= lang('Auth.weNeverShare') ?></small>
                        <div class="invalid-feedback">
                            Please enter a valid email address with Gmail domain.
                        </div>
                    </div>

                    <script>
                        document.querySelector('form').addEventListener('submit', function(event) {
                            const emailInput = document.querySelector('input[name="email"]');
                            const emailValue = emailInput.value.trim();
                            const emailRegex = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;

                            if (!emailRegex.test(emailValue)) {
                                emailInput.classList.add('is-invalid');
                                event.preventDefault();
                            }
                        });
                    </script>

                    <div class="form-group">
                        <label for="username"><?= lang('Auth.username') ?></label>
                        <input type="text" class="form-control <?php if (session('errors.username')) : ?>is-invalid<?php endif ?>" name="username" placeholder="<?= lang('Auth.username') ?>" value="<?= old('username') ?>">
                    </div>

                    <div class="form-group">
                        <label for="password"><?= lang('Auth.password') ?></label>
                        <input type="password" name="password" class="form-control <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>" placeholder="<?= lang('Auth.password') ?>" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="pass_confirm"><?= lang('Auth.repeatPassword') ?></label>
                        <input type="password" name="pass_confirm" class="form-control <?php if (session('errors.pass_confirm')) : ?>is-invalid<?php endif ?>" placeholder="<?= lang('Auth.repeatPassword') ?>" autocomplete="off">
                    </div>

                    <br>

                    <button type="submit" class="btn btn-primary btn-block"><?= lang('Auth.register') ?></button>
                </form>


                <hr>

                <p><?= lang('Auth.alreadyRegistered') ?> <a href="<?= url_to('login') ?>"><?= lang('Auth.loginAction') ?></a></p>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>