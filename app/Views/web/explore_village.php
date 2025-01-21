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
                                    // objectMarker("SUM01", -0.52210813, 100.49432448);
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
                                                        <img src="<?= base_url('media/photos/package/' . esc($package['data']['gallery'][0])); ?>" alt="<?= $package['title']; ?>" style="width: 50px; height: 50px; object-fit:cover; margin-right: 20px;">
                                                        <div>
                                                            <h6><?= $package['title']; ?></h6>
                                                            <h6><?= 'Rp' . number_format(esc($package['price']), 0, ',', '.') ; ?></h6>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2">
                                                    <div class="btn-group">
                                                        <?php foreach ($package['day'] as $day) : ?>
                                                            <!-- <button type="button" class="btn btn-primary btn-sm" aria-expanded="false" onclick="add<?= $day['day'], $package['data']['id']; ?>();">Day <?= $day['day']; ?></button> -->
                                                            <button id="btn-day-<?= $day['day'], $package['data']['id']; ?>" type="button" class="btn btn-primary btn-sm day-route-btn" aria-expanded="false" onclick="add<?= $day['day'], $package['data']['id']; ?>(); addStartingPoint();">Day <?= $day['day']; ?></button>
                                                            <button id="btn-day-dropdown-<?= $day['day'], $package['data']['id']; ?>" type="button" class="btn btn-primary dropdown-toggle day-route-btn dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false" data-bs-reference="parent">
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
                                                                    echo '<li><button type="button" onclick="routeBetweenObjects(-0.52210813, 100.49432448, ' . esc($activity1['lat']) . ', ' . esc($activity1['lng']) . '); addOnly' . esc($day['day']) . ''  . esc($package['data']['id']) . '()" class="btn btn-outline-primary"><i class="fa fa-road"></i> Titik 0 ke 1</button></li>';
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
                                                                        <li><button type="button" onclick="routeBetweenObjects(<?= $currentActivity['lat'] ?>, <?= $currentActivity['lng'] ?>, <?= $nextActivity['lat'] ?>, <?= $nextActivity['lng'] ?>); addOnly<?= $day['day'], $package['data']['id']; ?>()" class="btn btn-outline-primary"><i class="fa fa-road"></i> Activity <?= esc($currentActivity['activity']); ?> ke <?= esc($nextActivity['activity']); ?></button></li>
                                                                <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </ul>

                                                            

                                                            <script>

                                                                routeArray = [];
                                                                markerArray = [];

                                                                objectMarker("SUM01", -0.52210813, 100.49432448);


                                                                // Menambahkan titik 0 dan rute dari titik 0 ke aktivitas 1
                                                                function addStartingPoint() {
                                                                    // Tambahkan marker untuk titik 0 dengan gambar dari folder Anda
                                                                    var image = {
                                                                        url: baseUrl + "/media/icon/marker_sumpu.png", // Ganti dengan URL gambar Anda
                                                                        scaledSize: new google.maps.Size(60, 60) // Sesuaikan dengan ukuran gambar Anda
                                                                    };

                                                                    var marker = new google.maps.Marker({
                                                                        position: {
                                                                            lat: -0.52210813,
                                                                            lng: 100.49432448
                                                                        },
                                                                        map: map,
                                                                        icon: image,
                                                                        title: 'Village Gate' // Judul marker
                                                                    });

                                                                    // // Tambahkan infowindow
                                                                    // var infowindow = new google.maps.InfoWindow({
                                                                    //     content: '<div style="line-height:1.35;font-weight:bold;overflow:hidden;white-space:nowrap;">Gerbang Desa</div>'
                                                                    // });

                                                                    var startLat = -0.52210813;
                                                                    var startLng = 100.49432448;

                                                                    // // Tambahkan infowindow untuk titik awal
                                                                    // let infowindow = new google.maps.InfoWindow({
                                                                    //     // content: '<div style="line-height:1.35;font-weight:bold;overflow:hidden;white-space:nowrap;">Gerbang Desa</div>'
                                                                    //     content : '<div style="max-width:200px;max-height:300px;" class="text-center"> <p class="fw-bold fs-6">Village Gate</div>',
                                                                    //     contentButton : '<br><div class="text-center"><a title="Route" class="btn icon btn-outline-primary mx-1" id="routeInfoWindow" onclick="routeTo(' +startLat +", " +startLng +')"><i class="fa-solid fa-road"></i></a><a title="Info" class="btn icon btn-outline-primary mx-1" target="_blank" id="infoInfoWindow"</div>'
                                                                    // });

                                                                    let id = "marker_starting";
                                                                    // Tambahkan infowindow untuk titik awal
                                                                    let infowindow = new google.maps.InfoWindow();

                                                                    // Gabungkan konten utama dan tombol dalam satu variabel
                                                                    let content = `<div style="max-width:200px;max-height:300px;" class="text-center">
                                                                                        <p class="fw-bold fs-6">Village Gate</p>
                                                                                        <div class="text-center">
                                                                                            <a title="Route" class="btn icon btn-outline-primary mx-1" id="routeInfoWindow" onclick="routeTo(${startLat}, ${startLng})">
                                                                                                <i class="fa-solid fa-road"></i>
                                                                                            </a>            
                                                                                        </div>
                                                                                    </div>
                                                                                `;

                                                                    // Tampilkan infowindow saat marker diklik
                                                                    marker.addListener('click', function() {
                                                                        infowindow.setContent(content);
                                                                        infowindow.open(map, marker);
                                                                    });
                                                                    markerArray[id] = marker;


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
                                                                    // Reset all buttons to their default color                                      
                                                                    let buttons = document.querySelectorAll('.day-route-btn');
                                                                    let dayDetails = document.querySelectorAll('.div-day-detail');
                                                                    let allActivityRows = document.querySelectorAll('[id^="activity-row-"]');

                                                                    clearRadius();
                                                                    clearRoute();
                                                                    clearMarker();
                                                                    // var routeArray = [];

                                                                    buttons.forEach(function(button) {
                                                                        button.style.backgroundColor = ''; // reset to default background color
                                                                        button.style.color = ''; // reset to default text color
                                                                    });

                                                                    dayDetails.forEach(function(detailDiv) {
                                                                        detailDiv.style.border = ''; // reset div border
                                                                    });

                                                                    allActivityRows.forEach(function(activityRow) {
                                                                        activityRow.style.visibility = 'hidden'; // Sembunyikan semua activity row
                                                                        activityRow.style.display = 'none'; // Pastikan elemen tidak terlihat
                                                                    });


                                                                    // Change the color of the clicked button
                                                                    let currentButton0 = document.getElementById('activity-row-<?= $package['data']['id']; ?>');
                                                                    currentButton0.style.visibility = 'visible';
                                                                    currentButton0.style.display = 'block';
                                                                    let currentButton = document.getElementById('btn-day-<?= $day['day'], $package['data']['id']; ?>');
                                                                    currentButton.style.fontWeight = 'bold';
                                                                    currentButton.style.backgroundColor = 'white';
                                                                    currentButton.style.color = '#435ebe';
                                                                    let currentButton2 = document.getElementById('btn-day-dropdown-<?= $day['day'], $package['data']['id']; ?>');
                                                                    currentButton2.style.backgroundColor = 'white';
                                                                    currentButton2.style.color = '#435ebe';
                                                                    let currentButton3 = document.getElementById('div-day-detail-<?= $day['day'], $package['data']['id']; ?>');
                                                                    currentButton3.style.border = '1px solid #435ebe';
                                                                    currentButton3.style.borderRadius = '5px';

                                                                    <?php $loop = 0; ?>
                                                                    // initMap();
                                                                    clearRadius();
                                                                    clearRoute();
                                                                    clearMarker();

                                                                    map.setZoom(15);

                                                                    // Inisialisasi koordinat titik awal (gerbang desa)
                                                                    let startLat = -0.52210813;
                                                                    let startLng = 100.49432448;

                                                                    // Tambahkan marker untuk titik awal dengan gambar dari folder Anda
                                                                    let image = {
                                                                        url: baseUrl + "/media/icon/marker_sumpu.png", // Ganti dengan URL gambar Anda
                                                                        scaledSize: new google.maps.Size(60, 60) // Sesuaikan dengan ukuran gambar Anda
                                                                    };

                                                                    let marker = new google.maps.Marker({
                                                                        position: {
                                                                            lat: startLat,
                                                                            lng: startLng
                                                                        },
                                                                        map: map,
                                                                        icon: image,
                                                                        title: 'Village Gate' // Judul marker
                                                                    });

                                                                    let id = "marker_starting";

                                                                    // Tambahkan infowindow untuk titik awal
                                                                    let infowindow = new google.maps.InfoWindow();

                                                                    // Gabungkan konten utama dan tombol dalam satu variabel
                                                                    let content = `<div style="max-width:200px;max-height:300px;" class="text-center">
                                                                                        <p class="fw-bold fs-6">Village Gate</p>
                                                                                        <div class="text-center">
                                                                                            <a title="Route" class="btn icon btn-outline-primary mx-1" id="routeInfoWindow" onclick="routeTo(${startLat}, ${startLng})">
                                                                                                <i class="fa-solid fa-road"></i>
                                                                                            </a>            
                                                                                        </div>
                                                                                    </div>
                                                                                `;

                                                                    // Tampilkan infowindow saat marker diklik
                                                                    marker.addListener('click', function() {
                                                                        infowindow.setContent(content);
                                                                        infowindow.open(map, marker);
                                                                    });
                                                                    markerArray[id] = marker;



                                                                    <?php foreach ($activitiesForDay as $object) {
                                                                        $loop++;

                                                                        $lat_now = isset($object['lat']) ? esc($object['lat']) : '';
                                                                        $lng_now = isset($object['lng']) ? esc($object['lng']) : '';
                                                                        $objectid = isset($object['object_id']) ? esc($object['object_id']) : '';
                                                                    ?>
                                                                        objectMarkerRoute("<?= $objectid; ?>", <?= $lat_now; ?>, <?= $lng_now; ?>, true, <?= $loop; ?>);

                                                                        <?php if ($loop === 1) { ?>

                                                                            // Tambahkan rute dari titik awal ke aktivitas pertama
                                                                            let directionsService = new google.maps.DirectionsService();
                                                                            let directionsDisplay = new google.maps.DirectionsRenderer({
                                                                                suppressMarkers: true,
                                                                                map: map
                                                                            });
                                                                            let directionsRenderer;

                                                                            


                                                                            let start = new google.maps.LatLng(startLat, startLng);
                                                                            let end = new google.maps.LatLng(<?= $lat_now; ?>, <?= $lng_now; ?>);

                                                                            let request = {
                                                                                origin: start,
                                                                                destination: end,
                                                                                travelMode: google.maps.TravelMode.DRIVING
                                                                            };

                                                                            directionsService.route(request, function(response, status) {
                                                                                if (status == google.maps.DirectionsStatus.OK) {
                                                                                    directionsDisplay.setDirections(response);
                                                                                    directionsDisplay.setMap(map);
                                                                                    routeArray.push(directionsDisplay); // Simpan reference ke routeArray
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

                                                                            let request<?= $loop; ?> = {
                                                                                origin: pointA<?= $loop; ?>,
                                                                                destination: pointB<?= $loop; ?>,
                                                                                travelMode: google.maps.TravelMode.DRIVING
                                                                            };

                                                                            directionsService<?= $loop; ?>.route(request<?= $loop; ?>, function(response, status) {
                                                                                if (status == google.maps.DirectionsStatus.OK) {
                                                                                    directionsDisplay<?= $loop; ?>.setDirections(response);
                                                                                    directionsDisplay<?= $loop; ?>.setMap(map);
                                                                                    routeArray.push(directionsDisplay<?= $loop; ?>); // Simpan reference ke routeArray
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

                                                                function addOnly<?= $day['day'], $package['data']['id']; ?>() {
                                                                    // Reset all buttons to their default color                                      
                                                                    let buttons = document.querySelectorAll('.day-route-btn');
                                                                    let dayDetails = document.querySelectorAll('.div-day-detail');
                                                                    let allActivityRows = document.querySelectorAll('[id^="activity-row-"]');

                                                                    buttons.forEach(function(button) {
                                                                        button.style.backgroundColor = ''; // reset to default background color
                                                                        button.style.color = ''; // reset to default text color
                                                                    });

                                                                    dayDetails.forEach(function(detailDiv) {
                                                                        detailDiv.style.border = ''; // reset div border
                                                                    });

                                                                    allActivityRows.forEach(function(activityRow) {
                                                                        activityRow.style.visibility = 'hidden'; // Sembunyikan semua activity row
                                                                        activityRow.style.display = 'none'; // Pastikan elemen tidak terlihat
                                                                    });


                                                                    // Change the color of the clicked button
                                                                    let currentButton0 = document.getElementById('activity-row-<?= $package['data']['id']; ?>');
                                                                    currentButton0.style.visibility = 'visible';
                                                                    currentButton0.style.display = 'block';
                                                                    let currentButton = document.getElementById('btn-day-<?= $day['day'], $package['data']['id']; ?>');
                                                                    currentButton.style.fontWeight = 'bold';
                                                                    currentButton.style.backgroundColor = 'white';
                                                                    currentButton.style.color = '#435ebe';
                                                                    let currentButton2 = document.getElementById('btn-day-dropdown-<?= $day['day'], $package['data']['id']; ?>');
                                                                    currentButton2.style.backgroundColor = 'white';
                                                                    currentButton2.style.color = '#435ebe';
                                                                    let currentButton3 = document.getElementById('div-day-detail-<?= $day['day'], $package['data']['id']; ?>');
                                                                    currentButton3.style.border = '1px solid #435ebe';
                                                                    currentButton3.style.borderRadius = '5px';


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

        <?php foreach ($datapackage as $package) : ?>
            <div class="row" id="activity-row-<?= $package['data']['id']; ?>" style="visibility: hidden;display:none;">
                <div class="col-md-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title text-center">Activity</h5>
                        </div>
                        <div class="card-body" style="padding-bottom: 0.5rem !important;">
                            <div class="row">
                                <div class="col">
                                    <?php foreach ($package['day'] as $day) : ?>
                                        <div id="div-day-detail-<?= $day['day'], $package['data']['id']; ?>" class="div-day-detail" style="padding: 5px;">
                                            <b>Day <?= esc($day['day']); ?></b>
                                            <ol>
                                                <?php foreach ($package['activity'] as $ac) : ?>
                                                    <?php if ($day['day'] == $ac['day']) : ?>
                                                        <li><?= esc($ac['name']); ?> : <?= esc($ac['description']); ?></li>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </ol>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        <?php endforeach; ?>


        <!-- Direction section -->
        <?= $this->include('web/layouts/direction'); ?>


</section>



<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script>
    // $('#activity-row-<?= $package['data']['id']; ?>').hide();
    $('#direction-row').hide();
    $('#check-explore-col').hide();
    $('#result-explore-col').hide();
    $('#result-exploreall-col').hide();
</script>

<?= $this->endSection() ?>