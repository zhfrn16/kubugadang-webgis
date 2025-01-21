<?= $this->extend('web/layouts/main'); ?>

<?= $this->section('content') ?>

<section class="section">
    <?php if ($data3 != null) : ?>
        <div class="row">
            <!-- announcement -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title text-left" style="color: #dc3545;"><i class="fa-solid fa-bullhorn"></i> Announcement</h5>
                    </div>
                    <div class="card-body">
                        <ul>
                            <?php foreach ($data3 as $item3) : ?>
                                <li class="text-left"><?= esc($item3['announcement']); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    <?php endif; ?>

    <div class="row">
        <!--map-->
        <div class="col-md-8 col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-auto">
                            <h4 class="card-title">Google Maps</h4>
                            <div class="col-12 d-flex align-items-center gap-1">
                                <!-- Checkbox 1 -->
                                <div class="form-check" style="font-size: 14px;">
                                    <input class="form-check-input" type="checkbox" id="check-label" value="check-label" onchange="checkLabel()">
                                    <label class="form-check-label" for="check-label">Labels</label>
                                </div>&nbsp;
                                <!-- Checkbox 2 -->
                                <div class="form-check" style="font-size: 14px;">
                                    <input class="form-check-input" type="checkbox" id="check-terrain" value="check-terrain" onchange="checkTerrain()">
                                    <label class="form-check-label" for="check-terrain">Terrain</label>
                                </div>
                            </div>
                        </div>

                        <!-- <script>
                            ramalancuaca();
                        </script>
                        <div class="col-md-auto" id="weather-info"></div> -->

                        <?= $this->include('web/layouts/map-head'); ?>
                    </div>
                </div>
                <?= $this->include('web/layouts/map-body-5'); ?>
            </div>
        </div>

        <!--tourism village info-->
        <div class="col-md-4 col-12">
            <div class="row">
                <!--popular-->
                <div class="col-12" id="list-rec-col">
                    <div class="card">
                        <div class="card-header">
                            <script>
                                getTourismVillageInfo();
                            </script>
                            <?php foreach ($data as $item) : endforeach; ?>
                            <h5 class="card-title text-center"><?= esc($item['name']); ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive" style="max-height: 550px;">
                                <?php $i = 0; ?>
                                <script>
                                    clearMarker();
                                    clearRadius();
                                    clearRoute();



                                    for (let n = 1; n < 4; n++) {
                                        const idneg = n;
                                        digitNeg(idneg);
                                    }


                                    const myidprov = 'P03';
                                    const digitidprov = myidprov.substring(1);


                                    for (let p = 1; p < digitidprov; p++) {
                                        const idprov = p;
                                        digitProv(idprov);
                                    }

                                    for (let p = digitidprov; p < 11; p++) {
                                        const idprov = p;
                                        digitProv(idprov);
                                    }


                                    nameprovv = "Sumatera_Barat";
                                    digitKabKota(nameprovv);

                                    for (let k = 1; k < 15; k++) {
                                        const idkec = k;
                                        digitKec(idkec);
                                    }

                                    for (let d = 1; d < 3; d++) {
                                        const iddesa = d;
                                        digitNagari1(iddesa);
                                    }

                                    for (let d = 3; d < 5; d++) {
                                        const iddesa = d;
                                        digitNagari1(iddesa);
                                    }

                                    digitVillage1();
                                </script>

                                <script>
                                    objectMarker("<?= esc($item['id']); ?>", <?= esc($item['lat']); ?>, <?= esc($item['lng']); ?>);
                                </script>
                                <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
                                    <ol class="carousel-indicators">
                                        <?php foreach ($item['gallery'] as $x) : ?>
                                            <li data-bs-target="#carouselExampleCaptions" data-bs-slide-to="<?= esc($i); ?>" class="<?= ($i == 0) ? 'active' : ''; ?>"></li>
                                            <?php $i++; ?>
                                        <?php endforeach; ?>
                                    </ol>
                                    <div class="carousel-inner">
                                        <?php $i = 0; ?>
                                        <?php foreach ($item['gallery'] as $g) : ?>
                                            <div class="carousel-item<?= ($i == 0) ? ' active' : ''; ?>">
                                                <a>
                                                    <img src="<?= base_url('media/photos/sumpu/' . esc($g)); ?>" class="d-block w-100">
                                                </a>
                                            </div>
                                        <?php $i++;
                                        endforeach; ?>
                                    </div>
                                    <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </a>
                                </div>
                                <?php if (isset($data)) : ?>
                                    <?php foreach ($data as $item) : ?>
                                        <div class="row">
                                            <div class="col table-responsive">
                                                <table class="table table-borderless">
                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-bold">Name</td>
                                                            <td><?= esc($item['name']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Type of Tourism</td>
                                                            <td><?= esc($item['type_of_tourism']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Address</td>
                                                            <td><?= esc($item['address']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Open</td>
                                                            <td><?= esc($item['open']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Close</td>
                                                            <td><?= esc($item['close']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Contact Person</td>
                                                            <td><?= esc($item['contact_person']); ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>



                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>

                            <div class="mt-3">
                                <a title="Around You" class="btn icon btn-outline-primary mx-1" onclick="openExplore()">
                                    <i class="fa-solid fa-compass me-3"></i>Search object around you?
                                </a>
                            </div>
                        </div>


                    </div>
                </div>
            </div>

            <!-- Explore section -->
            <?= $this->include('web/layouts/explore_home'); ?>
            <?= $this->include('web/layouts/explore_home_result'); ?>
        </div>
    </div>
    <!-- Explore section
    
    <!-- Direction section -->
    <?= $this->include('web/layouts/direction'); ?>
</section>

<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script>
    $('#direction-row').hide();
    $('#check-explore-col').hide();
    $('#result-explore-col').hide();
    $('#result-exploreall-col').hide();
</script>
<?= $this->endSection() ?>