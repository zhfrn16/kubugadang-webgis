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