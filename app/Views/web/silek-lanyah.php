<?= $this->extend('web/layouts/main'); ?>

<?= $this->section('content') ?>

<section class="section">
    <div class=" row">
    <!--map-->
    <div class="col-md-7 col-12">
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
            <div class="card-body">
            <div class="googlemaps" id="googlemaps"></div>
            <script>initMap6(); </script>
            <div id="legend"></div>
            <script>$('#legend').hide(); getLegend();</script>
            </div>
        </div>
    </div>

    <div class="col-md-5 col-12">
        <div class="row">
            <!--Home-->
            <div class="col-12" id="list-at-col">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title text-center">Silek Lanyah</h5>
                    </div>
                    <div class="card-body">
                        <script>
                            clearMarker();
                            clearRadius();
                            clearRoute();
                        </script>

                        <?php foreach ($data as $item) : ?>
                            <script>
                                objectMarker("<?= esc($item['id']); ?>", <?= esc($item['lat']); ?>, <?= esc($item['lng']); ?>);
                            </script>
                            <div class="row">
                                <div class="col table-responsive">
                                    <div>
                                        <?= $item['description']; ?>
                                    </div>
                                    <div>
                                        <tr>
                                            <td class="fw-bold">Price</td>
                                            <td> : <?= 'Rp ' . number_format(esc($item['price']), 0, ',', '.'); ?></td>
                                        </tr>
                                    </div>
                                    <div>
                                        <tr>
                                            <td class="fw-bold">Payment Category </td>
                                            <td>
                                                <?php if ($item['category'] == 0): ?>
                                                    : Group
                                                <?php elseif ($item['category'] == 1): ?>
                                                    : Individu
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    </div>
                                    <br>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <!-- Object Media -->
                        <?= $this->include('web/layouts/our_gallery_video'); ?>

                        <div class="d-grid gap-2 pt-2">
                            <button type="button" class="btn btn-outline-primary" onclick="focusObject(`<?= esc($item['id']); ?>`);">
                                <span class="material-icons" style="font-size: 1.5rem; vertical-align: bottom">info</span> More Info
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- Nearby section -->
        <?= $this->include('web/layouts/track'); ?>
    </div>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script>
    const myModal = document.getElementById('videoModal');
    const videoSrc = document.getElementById('video-play').getAttribute('data-src');

    myModal.addEventListener('shown.bs.modal', () => {
        console.log(videoSrc);
        document.getElementById('video').setAttribute('src', videoSrc);
    });
    myModal.addEventListener('hide.bs.modal', () => {
        document.getElementById('video').setAttribute('src', '');
    });

    $('#direction-row').hide();
    $('#check-track-col').hide();
    $('#check-nearby-col').hide();
    $('#result-track-col').hide();
    $('#result-nearby-col').hide();
</script>
<?= $this->endSection() ?>