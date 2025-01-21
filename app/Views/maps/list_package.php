<?= $this->extend('maps/main'); ?>

<?= $this->section('content') ?>


<script>
    currentUrl = "api";
</script>

<section class="section">
    <div class="row">

        <div class="col-md-4 col-12">
            <div class="row">
                <!--popular-->
                <div class="col-12" id="list-object-col">
                    <div class="card">
                        <div class="card-header" style="background-color: #435ebe; border-radius:0px;">
                            <h5 class="card-title text-center" style=" color: white; ">List Package</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive overflow-auto" id="table-user" style="max-height: 500px !important;">

                                <table class="table table-hover mb-0 table-lg">
                                    <thead>

                                    </thead>

                                    <tbody id="table-data">
                                        <?php foreach ($datapackage as $package) : ?>
                                            <tr onclick="window.location='<?= base_url('api/package/detail/') . $package['data']['id']; ?>';" style="cursor: pointer;">
                                                <td colspan="2" style="padding: 1rem;">
                                                    <div class="d-flex align-items-center">
                                                        <img src="<?= base_url('media/photos/package/' . esc($package['data']['gallery'][0])); ?>" alt="<?= $package['title']; ?>" style="width: 70px; height: 70px; object-fit: cover; margin-right: 20px;">
                                                        <div>
                                                            <p class="card-text btn-success btn-sm" style="margin: 0; margin-top:15px; display: inline-block;"><?= $package['type_name']; ?></p>
                                                            <h6><?= $package['title']; ?></h6>
                                                            <h6>Price:<?= 'Rp ' . number_format(esc($package['price']), 0, ',', '.'); ?></h6>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>

                                            </tr>


                                        <?php endforeach; ?>
                                    </tbody>



                                </table>
                            </div>

                        </div>

                    </div>
                </div>
                <!-- Nearby section -->
            </div>
        </div>
        <!-- Direction section -->
</section>



<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<?= $this->endSection() ?>