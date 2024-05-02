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
                            <h5 class="card-title text-center">Homestay</h5>
                           
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php if (isset($data) && count($data) > 0) : ?>
                                    <?php $count = 0; ?>
                                    <?php foreach ($data as $item) : ?>
                                        <!-- Bagi setiap 3 item, buat baris baru -->
                                        <?php if ($count % 2 === 0) : ?>
                            </div>
                            <div class="row">
                            <?php endif; ?>

                            <div class="col-md-6 mb-2">
                                <div class="card">
                                    <div class="row g-0">
                                        <div class="col-md-4">
                                            <img src="<?= base_url('media/photos/homestay/' . esc($item['gallery'])); ?>" class="img-fluid rounded-start" alt="Gallery Image" style="object-fit: cover; width: 100%; height: 100%;border-top-right-radius: 0.25rem !important;border-bottom-right-radius: 0.25rem !important;">
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <h5 class="card-title"><?= esc($item['name']); ?></h5>
                                                <p>Desa Wisata Sumpu</p>
                                                <!-- <p>Include Tourism Package</p> -->
                                                <p><i class="fa-solid fa-wifi"></i> Free WiFi &nbsp;<i class="fa-solid fa-utensils"></i> Breakfast</p>
                                               
                                                <div class="d-flex">
                                                    <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="More Info" class="btn icon btn-outline-primary me-2" href="<?= base_url('web/homestay/') . $item['id']; ?>">
                                                        <i class="fa-solid fa-circle-info"></i> More Info
                                                    </a>
                                                    <!-- ... (tombol Extend lainnya) -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php $count++; ?>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p>No homestay available.</p>
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