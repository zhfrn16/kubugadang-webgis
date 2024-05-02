<header class="mb-0">
    <a href="#" class="burger-btn d-block d-xl-none">
        <i class="bi bi-justify fs-3"></i>
    </a>
</header>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <?php if (isset($data2)) : ?>
                    <?php foreach ($data2 as $item) : ?>
                        <h4><?= esc($item['name']); ?></h4>
                    <?php endforeach; ?>
                <?php endif; ?>
                <!-- <h4>Desa Wisata Sumpu</h4> -->
                <p class="text-subtitle text-muted">Tourism Village</p>
            </div>

            <div class="col-12 col-md-6 order-md-2 order-first mb-md-0 mb-3">

                <div class="float-end">
                    <?php if (logged_in()) : ?>
                        <div class="btn-group mb-1">
                            <div class="card mb-0">
                                <div class="card-body py-3 px-4">
                                    <div class="d-flex align-items-center">

                                        <a href="<?= base_url('web/cart') ?>" class="btn btn-transparent me-3 position-relative" style="color: #000;padding: 0.7rem;margin-right: 0rem!important;">
                                            <i class="fas fa-shopping-cart"></i>

                                        </a>

                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="btn-group mb-1">
                            <div class="dropdown">
                                <a class="" role="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <div class="card mb-0">
                                        <div class="card-body py-3 px-4">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-lg me-0">
                                                    <?php
                                                    $userImage = user()->user_image;
                                                    $imageSrc = (strpos($userImage, 'http') === 0) ? $userImage : base_url('media/photos/user') . '/' . $userImage;
                                                    ?>
                                                    <img src="<?= $imageSrc; ?>" alt="Face 1" />
                                                    <!-- <img src="<?= base_url('media/photos/user'); ?>/<?= user()->user_image; ?>" alt="Face 1" /> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="<?= base_url('web/profile'); ?>">My Profile</a>
                                    <a class="dropdown-item" href="<?= base_url('logout'); ?>">Log Out</a>
                                </div>
                            </div>
                        </div>
                    <?php else : ?>
                        <a href="<?= base_url('login'); ?>" class="btn btn-primary">Login</a>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</div>
<script>
    setBaseUrl("<?= base_url(); ?>");
</script>