<?= $this->extend('web/layouts/main'); ?>

<?= $this->section('content') ?>

<section class="section">
    <div class=" row">
        <div class="col-md-12 col-12">
            <div class="row">
                <!-- List Object -->
                <div class="col-12" id="list-rg-col">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title text-center">Package</h5>
                            <div class="col-auto">
                                <?php if (logged_in()) : ?>
                                    <?php if (in_groups(['admin']) || in_groups(['master'])) : ?>
                                        <button type="button" class="btn btn-primary float-right" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Custom New Package From Scratch" disabled><i class="fa-solid fa-plus me-3"></i>Custom New Package</button>
                                    <?php else : ?>
                                        <form class="form form-vertical" id="customForm" action="<?= base_url('/web/detailreservation/addcustom'); ?>" method="post" onsubmit="checkRequired(event)" enctype="multipart/form-data">
                                            <?= csrf_field(); ?>
                                            <button type="submit" class="btn btn-primary float-right" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Custom New Package From Scratch"><i class="fa-solid fa-plus me-3"></i>Custom New Package</button>
                                            <br>
                                        </form>
                                    <?php endif; ?>
                                <?php else : ?>
                                    <button type="button" class="btn btn-primary float-right" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Custom New Package From Scratch" onclick="redirectToLogin()"><i class="fa-solid fa-plus me-3"></i>Custom New Package</button>
                                    <script>
                                        function redirectToLogin() {
                                            Swal.fire({
                                                icon: 'warning',
                                                title: 'You are not logged in',
                                                text: 'Please log in to proceed.',
                                                confirmButtonText: 'OK',
                                            }).then(() => {
                                                // Optionally, redirect to the login page
                                                window.location.href = '<?= base_url('/login'); ?>';
                                            });
                                        }
                                    </script>
                                <?php endif; ?>

                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php if (isset($data) && count($data) > 0) : ?>
                                    <?php $count = 0; ?>
                                    <?php foreach ($data as $item) : ?>
                                        <?php if ($count % 2 === 0) : ?>
                            </div>
                            <div class="row">
                            <?php endif; ?>

                            <div class="col-md-6 mb-2">
                                <div class="card" onclick="window.location='<?= base_url('web/package/') . $item['id']; ?>';" style="cursor: pointer;">
                                    <div class="row g-0">
                                        <div class="col-md-4">
                                            <img src="<?= base_url('media/photos/package/' . esc($item['gallery'])); ?>" class="img-fluid rounded-start" alt="Gallery Image" style="object-fit: cover; width: 100%; height: 250px;border-top-right-radius: 0.25rem !important;border-bottom-right-radius: 0.25rem !important;">
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <h5 class="card-title"><?= esc($item['name']); ?></h5>
                                                <p class="card-text btn-success btn-sm" style="margin: 0; display: inline-block;"><?= esc($item['type_name']); ?></p>
                                                <p class="card-text" style="margin-top: 10px;"><i class="fa-regular fa-clock"></i><?= esc($item['days']); ?> D &nbsp;<i class="fa-solid fa-user-group"></i>Min. <?= esc($item['min_capacity']); ?> people</p>
                                                <p class="card-text" style="margin: 0;">Start from</p>
                                                <!-- Memberi warna oren pada harga tiket -->
                                                <p class="card-text" style="margin: 0; color: orange; font-weight:bold;">
                                                    <?= 'Rp ' . number_format(esc($item['price']), 0, ',', '.'); ?>
                                                </p>

                                                <div class="d-flex">
                                                    <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="More Info" class="btn icon btn-outline-primary me-2" href="<?= base_url('web/package/') . $item['id']; ?>">
                                                        <i class="fa-solid fa-circle-info"></i>
                                                    </a>
                                                    <!-- ... (tombol Extend lainnya) -->
                                                    <?php if (logged_in()) : ?>
                                                        <?php if (in_groups(['admin']) || in_groups(['master'])) : ?>
                                                            <button type="submit" class="btn icon btn-outline-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Extend Package" disabled><i class="fa-solid fa-plus-square"></i> Extend</button>&nbsp;&nbsp;
                                                            <button type="submit" class="btn icon btn-outline-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Custom Package" disabled><i class="fa-solid fa-puzzle-piece"></i> Custom</button>
                                                        <?php else : ?>
                                                            <form class="form form-vertical" id="customForm" action="<?= base_url('/web/detailreservation/addextend'); ?>/<?= esc($item['id']); ?>" method="post" onsubmit="checkRequired(event)" enctype="multipart/form-data">
                                                                <?= csrf_field(); ?>
                                                                <button type="submit" class="btn icon btn-outline-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Extend Package"><i class="fa-solid fa-plus-square"></i> Extend</button>                                                                
                                                                <br>
                                                            </form>&nbsp;&nbsp;
                                                            <form class="form form-vertical" id="customizeForm" action="<?= base_url('/web/detailreservation/addcustompackage'); ?>/<?= esc($item['id']); ?>" method="post" onsubmit="checkRequired(event)" enctype="multipart/form-data">
                                                                <?= csrf_field(); ?>
                                                                <button type="submit" class="btn icon btn-outline-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Custom Package"><i class="fa-solid fa-puzzle-piece"></i> Custom</button>
                                                                <br>
                                                            </form>
                                                        <?php endif; ?>
                                                    <?php else : ?>
                                                        <button type="button" class="btn icon btn-outline-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Extend Package" onclick="redirectToLogin()"><i class="fa-solid fa-plus-square"></i> Extend</button>&nbsp;&nbsp;
                                                        <button type="button" class="btn icon btn-outline-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Custom Package" onclick="redirectToLogin()"><i class="fa-solid fa-puzzle-piece"></i> Custom</button>
                                                        <script>
                                                            function redirectToLogin() {
                                                                Swal.fire({
                                                                    icon: 'warning',
                                                                    title: 'You are not logged in',
                                                                    text: 'Please log in to proceed.',
                                                                    confirmButtonText: 'OK',
                                                                }).then(() => {
                                                                    // Optionally, redirect to the login page
                                                                    window.location.href = '<?= base_url('/login'); ?>';
                                                                });
                                                            }
                                                        </script>
                                                    <?php endif; ?>
                                                    <!-- End extend -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <?php $count++; ?>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p>No Package available.</p>
                    <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<?= $this->endSection() ?>