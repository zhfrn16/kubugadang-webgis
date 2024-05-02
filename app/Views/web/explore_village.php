<?= $this->extend('web/layouts/main'); ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="row">
        <!--map-->
        <div class="col-md-8 col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-auto">
                            <h5 class="card-title">Google Maps with Location</h5>
                        </div>
                        <?= $this->include('web/layouts/map-head'); ?>
                    </div>
                </div>
                <?= $this->include('web/layouts/map-body-4'); ?>
            </div>
        </div>


        <div class="col-md-4 col-12">
            <div class="row">
                <!--popular-->
                <div class="col-12" id="list-object-col">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title text-center">Explore Village With Our Package</h5>
                            <!-- <hr class="hr" /> -->
                        </div>
                        <div class="card-body">
                            <div class="table-responsive overflow-auto" id="table-user" style="max-height: 450px !important;">
                                <script>
                                    // clearMarker();
                                    // clearRadius();
                                    // clearRoute();
                                    // explorePackage();
                                </script>
                                <table class="table table-hover mb-0 table-lg">
                                    <thead>
                                        <tr>
                                            <th>Package Name</th>
                                            <!-- <th>Action</th> -->
                                        </tr>
                                    </thead>

                                    <tbody id="table-data">
                                        <?php foreach ($datapackage as $package) : ?>
                                            <tr onclick="window.location='<?= base_url('web/package/') . $package['data']['id']; ?>';" style="cursor: pointer;">
                                                <td colspan="2" style="padding: 1rem;">
                                                    <div class="d-flex align-items-center">
                                                        <img src="<?= base_url('media/photos/package/' . esc($package['data']['gallery'][0])); ?>" alt="<?= $package['title']; ?>" style="width: 50px; height: 50px; object-fit: cover; margin-right: 20px;">
                                                        <div>
                                                            <h6><?= $package['title']; ?></h6>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2">
                                                    <div class="btn-group">
                                                        <?php foreach ($package['day'] as $day) : ?>
                                                            <!-- <button type="button" class="btn btn-primary btn-sm" aria-expanded="false" onclick="add<?= $day['day'], $package['data']['id']; ?>();">Day <?= $day['day']; ?></button> -->
                                                            <button type="button" class="btn btn-primary btn-sm" aria-expanded="false" onclick="add<?= $day['day'], $package['data']['id']; ?>(); addStartingPoint();">Day <?= $day['day']; ?></button>

                                                            <!-- <button type="button" class="btn btn-primary btn-sm" aria-expanded="false" onclick="addDay<?= $day['day']; ?>()">Day <?= $day['day']; ?></button> -->


                                                            <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false" data-bs-reference="parent">
                                                                <span class="visually-hidden">Toggle Dropdown</span>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <?php
                                                                // Tambahkan tombol untuk rute dari Activity 0 ke Activity 1 untuk setiap hari
                                                                $activity1 = null;
                                                                foreach ($package['activity'] as $activity) {
                                                                    if ($activity['day'] == $day['day'] && $activity['activity'] == 1) {
                                                                        $activity1 = $activity;
                                                                        break;
                                                                    }
                                                                }

                                                                if ($activity1) {
                                                                    echo '<li><button type="button" onclick="routeBetweenObjects(-0.52210813, 100.49432448, ' . esc($activity1['lat']) . ', ' . esc($activity1['lng']) . ')" class="btn btn-outline-primary"><i class="fa fa-road"></i> Titik 0 ke 1</button></li>';
                                                                }
                                                                ?>
                                                                <?php
                                                                // Tampilkan dropdown untuk rute antar aktivitas
                                                                $activitiesForDay = array_filter($package['activity'], function ($activity) use ($day) {
                                                                    return $activity['day'] === $day['day'];
                                                                });
                                                                foreach ($activitiesForDay as $index => $currentActivity) {
                                                                    if (isset($activitiesForDay[$index + 1])) {
                                                                        $nextActivity = $activitiesForDay[$index + 1];
                                                                ?>
                                                                        <li><button type="button" onclick="routeBetweenObjects(<?= $currentActivity['lat'] ?>, <?= $currentActivity['lng'] ?>, <?= $nextActivity['lat'] ?>, <?= $nextActivity['lng'] ?>)" class="btn btn-outline-primary"><i class="fa fa-road"></i> Activity <?= esc($currentActivity['activity']); ?> ke <?= esc($nextActivity['activity']); ?></button></li>
                                                                <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </ul>

                                                            <script>
                                                                // Menambahkan titik 0 dan rute dari titik 0 ke aktivitas 1
                                                                function addStartingPoint() {
                                                                    // Tambahkan marker untuk titik 0 dengan gambar dari folder Anda
                                                                    var image = {
                                                                        url: baseUrl + "/media/icon/marker_sumpu.png", // Ganti dengan URL gambar Anda
                                                                        scaledSize: new google.maps.Size(50, 50) // Sesuaikan dengan ukuran gambar Anda
                                                                    };

                                                                    var marker = new google.maps.Marker({
                                                                        position: {
                                                                            lat: -0.52210813,
                                                                            lng: 100.49432448
                                                                        },
                                                                        map: map,
                                                                        icon: image,
                                                                        title: 'Gerbang Desa' // Judul marker
                                                                    });

                                                                    // Tambahkan infowindow
                                                                    var infowindow = new google.maps.InfoWindow({
                                                                        content: '<div style="line-height:1.35;font-weight:bold;overflow:hidden;white-space:nowrap;">Gerbang Desa</div>'
                                                                    });

                                                                    // Tampilkan infowindow saat marker diklik
                                                                    marker.addListener('click', function() {
                                                                        infowindow.open(map, marker);
                                                                    });

                                                                    // Temukan aktivitas 1
                                                                    <?php foreach ($package['activity'] as $activity) : ?>
                                                                        <?php if ($activity['activity'] === 1) : ?>
                                                                            var lat1 = <?= esc($activity['lat']); ?>;
                                                                            var lng1 = <?= esc($activity['lng']); ?>;
                                                                        <?php endif; ?>
                                                                    <?php endforeach; ?>

                                                                    // Tambahkan rute dari titik 0 ke aktivitas 1
                                                                    routeBetweenObjects(-0.52210813, 100.49432448, lat1, lng1);
                                                                }
                                                            </script>


                                                            <script>
                                                                function add<?= $day['day'], $package['data']['id']; ?>() {
                                                                    <?php $loop = 0; ?>
                                                                    initMap();
                                                                    map.setZoom(15);

                                                                    // Inisialisasi koordinat titik awal (gerbang desa)
                                                                    var startLat = -0.52210813;
                                                                    var startLng = 100.49432448;

                                                                    // Tambahkan marker untuk titik awal dengan gambar dari folder Anda
                                                                    var image = {
                                                                        url: baseUrl + "/media/icon/marker_sumpu.png", // Ganti dengan URL gambar Anda
                                                                        scaledSize: new google.maps.Size(50, 50) // Sesuaikan dengan ukuran gambar Anda
                                                                    };

                                                                    var marker = new google.maps.Marker({
                                                                        position: {
                                                                            lat: startLat,
                                                                            lng: startLng
                                                                        },
                                                                        map: map,
                                                                        icon: image,
                                                                        title: 'Gerbang Desa' // Judul marker
                                                                    });

                                                                    // Tambahkan infowindow untuk titik awal
                                                                    var infowindow = new google.maps.InfoWindow({
                                                                        content: '<div style="line-height:1.35;font-weight:bold;overflow:hidden;white-space:nowrap;">Gerbang Desa</div>'
                                                                    });

                                                                    // Tampilkan infowindow saat marker diklik
                                                                    marker.addListener('click', function() {
                                                                        infowindow.open(map, marker);
                                                                    });

                                                                    <?php foreach ($activitiesForDay as $object) {
                                                                        $loop++;

                                                                        $lat_now = isset($object['lat']) ? esc($object['lat']) : '';
                                                                        $lng_now = isset($object['lng']) ? esc($object['lng']) : '';
                                                                        $objectid = isset($object['object_id']) ? esc($object['object_id']) : '';
                                                                    ?>
                                                                        objectMarkerRoute("<?= $objectid; ?>", <?= $lat_now; ?>, <?= $lng_now; ?>, true, <?= $loop; ?>);

                                                                        <?php if ($loop === 1) { ?>
                                                                            // Tambahkan rute dari titik awal ke aktivitas pertama
                                                                            var directionsService = new google.maps.DirectionsService();
                                                                            var directionsDisplay = new google.maps.DirectionsRenderer({
                                                                                suppressMarkers: true,
                                                                                map: map
                                                                            });

                                                                            var start = new google.maps.LatLng(startLat, startLng);
                                                                            var end = new google.maps.LatLng(<?= $lat_now; ?>, <?= $lng_now; ?>);

                                                                            var request = {
                                                                                origin: start,
                                                                                destination: end,
                                                                                travelMode: google.maps.TravelMode.DRIVING
                                                                            };

                                                                            directionsService.route(request, function(response, status) {
                                                                                if (status == google.maps.DirectionsStatus.OK) {
                                                                                    directionsDisplay.setDirections(response);
                                                                                } else {
                                                                                    window.alert('Directions request failed due to ' + status);
                                                                                }
                                                                            });
                                                                        <?php } else if (1 < $loop) { ?>
                                                                            // Tambahkan rute antara aktivitas
                                                                            pointA<?= $loop; ?> = new google.maps.LatLng(<?= $lat_bef; ?>, <?= $lng_bef; ?>);
                                                                            pointB<?= $loop; ?> = new google.maps.LatLng(<?= $lat_now; ?>, <?= $lng_now; ?>);
                                                                            directionsService<?= $loop; ?> = new google.maps.DirectionsService();
                                                                            directionsDisplay<?= $loop; ?> = new google.maps.DirectionsRenderer({
                                                                                suppressMarkers: true,
                                                                                map: map
                                                                            });

                                                                            var request<?= $loop; ?> = {
                                                                                origin: pointA<?= $loop; ?>,
                                                                                destination: pointB<?= $loop; ?>,
                                                                                travelMode: google.maps.TravelMode.DRIVING
                                                                            };

                                                                            directionsService<?= $loop; ?>.route(request<?= $loop; ?>, function(response, status) {
                                                                                if (status == google.maps.DirectionsStatus.OK) {
                                                                                    directionsDisplay<?= $loop; ?>.setDirections(response);
                                                                                } else {
                                                                                    window.alert('Directions request failed due to ' + status);
                                                                                }
                                                                            });
                                                                        <?php } ?>

                                                                        <?php
                                                                        $lat_bef = $lat_now;
                                                                        $lng_bef = $lng_now;
                                                                        ?>
                                                                    <?php } ?>
                                                                }
                                                            </script>

                                                        <?php endforeach; ?>
                                                    </div>
                                                </td>
                                            </tr>


                                        <?php endforeach; ?>
                                    </tbody>



                                </table>
                            </div>
                            <div class="mt-3">
                                <a title="Around You" class="btn icon btn-outline-primary mx-1" onclick="openExplore()">
                                    <i class="fa-solid fa-compass me-3"></i>Search object around you?
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- Nearby section -->
                <?= $this->include('web/layouts/explore'); ?>
            </div>
        </div>
        <!-- Direction section -->
        <?= $this->include('web/layouts/direction'); ?>
</section>



<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script>
    $('#direction-row').hide();
    $('#check-explore-col').hide();
    $('#result-explore-col').hide();
</script>
<?= $this->endSection() ?>