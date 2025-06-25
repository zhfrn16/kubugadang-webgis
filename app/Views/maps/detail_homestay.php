<?= $this->extend('maps/main'); ?>

<?= $this->section('content') ?>
<style>
    .rating {
        display: inline-block;
        font-size: 25px;
    }
    
    .rating {
        color: orange;
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
                    <h4 class="card-title text-center">Homestay Information</h4>
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
                </div>

            </div>

        </div>

        <div class="col-md-5 col-12">
            <!-- Object Media -->
            <?= $this->include('web/layouts/our_gallery'); ?>

        </div>
    </div>
</section>

<?= $this->endSection() ?>