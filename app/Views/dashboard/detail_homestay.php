<?= $this->extend('dashboard/layouts/main'); ?>

<?= $this->section('content') ?>
<style>
    .rating {
        display: inline-block;
        font-size: 25px;
    }
    
    .rating {
        color: orange;
    }

    .rating2 {
        display: inline-block;
        font-size: 15;
    }
    
    .rating2 {
        color: grey;
    }
</style>
<section class="section">
    <div class="row">
        <script>
            currentUrl = '<?= current_url(); ?>';
        </script>

        <!-- Object Detail Information -->
        <div class="col-md-7 col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title text-center">Homestay Information</h4>
                        </div>
                        <div class="col-auto">
                            <a href="<?= base_url('dashboard/homestay'); ?>/<?= esc($data['id']); ?>/edit" class="btn btn-primary float-end"><i class="fa-solid fa-pencil me-3"></i>Edit</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                <tr>
                                        <td class="fw-bold">Name</td>
                                        <td><?= esc($data['name']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Address</td>
                                        <td><?= esc($data['address']); ?></td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="fw-bold">Contact Person</td>
                                        <td><?= esc($data['contact_person']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Open</td>
                                        <td><?= esc($data['open']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Close</td>
                                        <td><?= esc($data['close']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Price</td>
                                        <td>
                                            <?php
                                                $price = isset($data['price']) && $data['price'] !== null ? $data['price'] : 0;
                                                echo 'Rp ' . number_format(esc($price), 0, ',', '.');
                                            ?>
                                        </td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p class="fw-bold">Description</p>
                            <p><?= esc($data['description']);
                                ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p class="fw-bold">Facility</p>
                            <p>
                                <?php if (isset($facilityhome)) : ?>                      
                                    <?php foreach ($facilityhome as $dt_fc) : ?>
                                        <li>
                                            <?= esc($dt_fc['name']); ?> (<?= esc($dt_fc['description']); ?>)
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <!-- Start Review Section -->
                    <div class="mt-4">
                        <div class="card-header">
                            <h4 class="card-title text-center">Reviews</h4>
                        </div>
                        <div class="card-body">
                            <?php if (isset($comment) && count($comment) > 0) : ?>
                                <?php foreach ($comment as $review) : ?>
                                    <div class="mb-4 border-bottom pb-2">
                                        <strong>@<?= esc($review['fullname']) ?></strong>
                                        <span class="text-muted small ms-2">(<?= esc(date('d M Y H:i', strtotime($review['created_at']))) ?>)</span>
                                        <div class="rating2 mb-1">
                                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                                                <?php if ($i <= $review['rating']) : ?>
                                                    <i name="rating2" class="fas fa-star"></i>
                                                <?php else : ?>
                                                    <i name="rating2" class="far fa-star"></i>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </div>
                                        <div><?= esc($review['review_text']) ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <p class="text-center">No reviews yet.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- End Review Section -->

                </div>
            </div>
        </div>

        <div class="col-md-5 col-12">
            <!-- Object Location on Map -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Google Maps</h5>
                </div>

                <?= $this->include('web/layouts/map-body'); ?>
                <script>
                    initMap(<?= esc($data['lat']); ?>, <?= esc($data['lng']); ?>)
                </script>
                <script>
                    objectMarker("<?= esc($data['id']); ?>", <?= esc($data['lat']); ?>, <?= esc($data['lng']); ?>);
                </script>
            </div>

            <!-- Object Media -->
            <?= $this->include('web/layouts/our_gallery'); ?>

        </div>
    </div>
</section>

<?= $this->endSection() ?>