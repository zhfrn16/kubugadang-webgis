<?= $this->extend('dashboard/layouts/main'); ?>

<?= $this->section('content') ?>

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
                            <h4 class="card-title text-center">Package Information</h4>
                        </div>
                        <div class="col-auto">
                            <a href="<?= base_url('dashboard/package/edit'); ?>/<?= esc($data['id']); ?>" class="btn btn-primary float-end"><i class="fa-solid fa-pencil me-3"></i>Edit</a>
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
                                        <td class="fw-bold">Type</td>
                                        <td><?= esc($data['type_name']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Contact Person</td>
                                        <td><?= esc($data['contact_person']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Minimal Capacity </td>
                                        <td><?= esc($data['min_capacity']); ?> people</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Price</td>
                                        <td><?= 'Rp ' . number_format(esc($data['price']), 0, ',', '.'); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p class="fw-bold">Description </p>
                            <p><?= esc($data['description']); ?></p>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p class="fw-bold">Service Include <br>
                            <ul>
                                <?php foreach ($serviceinclude as $ls) : ?>
                                    <li><?= esc($ls['name']); ?></li>
                                <?php endforeach; ?>
                            </ul>
                            </p>
                            <p class="fw-bold">Service exclude <br>
                            <ul>
                                <?php foreach ($serviceexclude as $ls) : ?>
                                    <li><?= esc($ls['name']); ?> (<?= 'Rp ' . number_format(esc($ls['price']), 0, ',', '.'); ?>)</li>
                                <?php endforeach; ?>
                            </ul>
                            </p>
                        </div>
                    </div>

                    <button type="button" id="video-play" class="btn-play btn btn-outline-primary" data-bs-toggle="modal" data-src="<?= base_url('media/videos/' . esc($data['video_url']) . ''); ?>" data-bs-target="#videoModal" <?= ($data['video_url'] == '') ? 'disabled' : ''; ?>>
                        <span class="material-icons" style="font-size: 1.5rem; vertical-align: bottom">play_circle</span> Play Video
                    </button>

                    <div class="modal fade text-left" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel17">Video</h4>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                        <i data-feather="x"></i>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="ratio ratio-16x9">
                                        <video src="" class="embed-responsive-item" id="video" controls>Sorry, your browser doesn't support embedded videos</video>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                        <i class="bx bx-x d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block">Close</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Object Media -->
            <?= $this->include('web/layouts/our_gallery'); ?>

        </div>

        <div class="col-md-5 col-12">
            <!-- Object Location on Map -->
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-auto">
                            <h5 class="card-title">Google Maps</h5>
                        </div>
                        <?= $this->include('web/layouts/map-head'); ?>
                    </div>
                </div>
                <?= $this->include('web/layouts/map-body'); ?>
                <div class="card-body">
                    <div class="col-auto">
                        <br>
                        <div class="btn-group float-right" role="group">
                            <?php foreach ($day as $d) : ?>
                                <?php $loop = 0; ?>

                                <script>
                                    function add<?= $d['day'], $d['package_id']; ?>() {
                                        // Reset all buttons to their default color                                      
                                        let buttons = document.querySelectorAll('.day-route-btn');
                                        let dayDetails = document.querySelectorAll('.div-day-detail');

                                        clearRadius();
                                        clearRoute();
                                        clearMarker();

                                        buttons.forEach(function(button) {
                                            button.style.backgroundColor = ''; // reset to default background color
                                            button.style.color = ''; // reset to default text color
                                        });

                                        dayDetails.forEach(function(detailDiv) {
                                            detailDiv.style.border = ''; // reset div border
                                        });

                                        // Change the color of the clicked button
                                        let currentButton = document.getElementById('btn-day-<?= $d['day'], $d['package_id']; ?>');
                                        currentButton.style.fontWeight = 'bold';
                                        currentButton.style.backgroundColor = 'white';
                                        currentButton.style.color = '#435ebe';
                                        let currentButton2 = document.getElementById('btn-day-dropdown-<?= $d['day'], $d['package_id']; ?>');
                                        currentButton2.style.backgroundColor = 'white';
                                        currentButton2.style.color = '#435ebe';
                                        let currentButton3 = document.getElementById('div-day-detail-<?= $d['day'], $d['package_id']; ?>');
                                        currentButton3.style.border = '1px solid #435ebe';
                                        currentButton3.style.borderRadius = '5px';

                                        // Call initMap and other logic here
                                        initMap();
                                        map.setZoom(15);

                                        // Inisialisasi koordinat titik awal (gerbang desa)
                                        var startLat = -0.52210813;
                                        var startLng = 100.49432448;

                                        // // Tambahkan marker untuk titik awal dengan gambar dari folder Anda
                                        // var image = {
                                        //     url: baseUrl + "/media/icon/marker_sumpu.png", // Ganti dengan URL gambar Anda
                                        //     scaledSize: new google.maps.Size(50, 50) // Sesuaikan dengan ukuran gambar Anda
                                        // };

                                        var marker = new google.maps.Marker({
                                            position: {
                                                lat: startLat,
                                                lng: startLng
                                            },
                                            map: map,
                                            // icon: image,
                                            label: {
                                                text: '0',
                                                color: 'white',
                                                fontSize: '14px',
                                                fontWeight: 'bold'
                                            },
                                            title: 'Village Gate'
                                        });

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


                                        <?php
                                        $activitiesForDay = array_filter($activity, function ($activity) use ($d) {
                                            return $activity['day'] === $d['day'];
                                        });
                                        foreach ($activitiesForDay as $object) {
                                            $loop++;

                                            $lat_now = isset($object['lat']) ? esc($object['lat']) : '';
                                            $lng_now = isset($object['lng']) ? esc($object['lng']) : '';
                                            $objectid = isset($object['object_id']) ? esc($object['object_id']) : '';
                                        ?>
                                            objectMarkerRouteNumber("<?= $objectid; ?>", <?= $lat_now; ?>, <?= $lng_now; ?>, true, <?= $loop; ?>);


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
                                                        directionsDisplay.setMap(map);
                                                        routeArray.push(directionsDisplay);
                                                    } else {
                                                        window.alert('Directions request failed due to ' + status);
                                                    }
                                                });
                                            <?php } else if (1 < $loop) { ?>

                                                pointA<?= $loop; ?> = new google.maps.LatLng(<?= $lat_bef; ?>, <?= $lng_bef; ?>);
                                                pointB<?= $loop; ?> = new google.maps.LatLng(<?= $lat_now; ?>, <?= $lng_now; ?>);
                                                directionsService<?= $loop; ?> = new google.maps.DirectionsService;
                                                directionsDisplay<?= $loop; ?> = new google.maps.DirectionsRenderer({
                                                    suppressMarkers: true,
                                                    map: map
                                                });
                                                directionsService<?= $loop; ?>.route({
                                                    origin: pointA<?= $loop; ?>,
                                                    destination: pointB<?= $loop; ?>,
                                                    avoidTolls: true,
                                                    avoidHighways: false,
                                                    travelMode: google.maps.TravelMode.DRIVING
                                                }, function(response, status) {
                                                    if (status == google.maps.DirectionsStatus.OK) {
                                                        directionsDisplay<?= $loop; ?>.setDirections(response);
                                                        directionsDisplay<?= $loop; ?>.setMap(map);
                                                        routeArray.push(directionsDisplay<?= $loop; ?>);
                                                    } else {
                                                        window.alert('Directions request failed due to ' + status);
                                                    }
                                                });

                                            <?php
                                            }
                                            ?>
                                            <?php
                                            $lat_bef = $lat_now;
                                            $lng_bef = $lng_now;
                                            ?>
                                        <?php
                                        }
                                        ?>
                                    }

                                    function addOnly<?= $d['day'], $d['package_id']; ?>() {
                                        // Reset all buttons to their default color                                      
                                        let buttons = document.querySelectorAll('.day-route-btn');
                                        let dayDetails = document.querySelectorAll('.div-day-detail');

                                        buttons.forEach(function(button) {
                                            button.style.backgroundColor = ''; // reset to default background color
                                            button.style.color = ''; // reset to default text color
                                        });

                                        dayDetails.forEach(function(detailDiv) {
                                            detailDiv.style.border = ''; // reset div border
                                        });

                                        // Change the color of the clicked button
                                        let currentButton = document.getElementById('btn-day-<?= $d['day'], $d['package_id']; ?>');
                                        currentButton.style.fontWeight = 'bold';
                                        currentButton.style.backgroundColor = 'white';
                                        currentButton.style.color = '#435ebe';
                                        let currentButton2 = document.getElementById('btn-day-dropdown-<?= $d['day'], $d['package_id']; ?>');
                                        currentButton2.style.backgroundColor = 'white';
                                        currentButton2.style.color = '#435ebe';
                                        let currentButton3 = document.getElementById('div-day-detail-<?= $d['day'], $d['package_id']; ?>');
                                        currentButton3.style.border = '1px solid #435ebe';
                                        currentButton3.style.borderRadius = '5px';


                                    }
                                </script>

                                <div class="btn-group">
                                    <button id="btn-day-<?= $d['day'], $d['package_id']; ?>" type="button" class="btn btn-primary btn-sm day-route-btn" type="button" aria-expanded="false" onclick="add<?= $d['day'], $d['package_id']; ?>();">Day <?= $d['day']; ?> Route</button>
                                    <button id="btn-day-dropdown-<?= $d['day'], $d['package_id']; ?>" type="button" class="btn btn-primary day-route-btn dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false" data-bs-reference="parent">
                                        <span class="visually-hidden">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <?php if (!empty($activitiesForDay)) : ?>
                                            <?php
                                            // Hitung jumlah aktivitas dalam hari ini
                                            $activityCount = count($activitiesForDay);

                                            // Ambil aktivitas pertama
                                            $firstActivity = reset($activitiesForDay);

                                            // Tambahkan tombol untuk Titik 0 ke Titik 1
                                            if ($firstActivity) : ?>
                                                <li>
                                                    <button
                                                        type="button"
                                                        onclick="routeBetweenObjects(-0.52210813,100.49432448,<?= esc($firstActivity['lat']); ?>,<?= esc($firstActivity['lng']); ?>); addOnly<?= esc($d['day']), esc($d['package_id']); ?>();" class="btn btn-outline-primary">
                                                        <i class="fa fa-road"></i> Titik 0 ke 1
                                                    </button>
                                                </li>
                                            <?php endif; ?>

                                            <?php
                                            // Jika ada lebih dari 1 aktivitas, tambahkan dropdown antar aktivitas
                                            if ($activityCount > 1) :
                                                foreach ($activitiesForDay as $index => $currentActivity) :
                                                    if (isset($activitiesForDay[$index + 1])) :
                                                        $nextActivity = $activitiesForDay[$index + 1];
                                            ?>
                                                        <li>
                                                            <button type="button" onclick="routeBetweenObjects( <?= esc($currentActivity['lat']); ?>, <?= esc($currentActivity['lng']); ?>, <?= esc($nextActivity['lat']); ?>, <?= esc($nextActivity['lng']); ?>); addOnly<?= esc($d['day']), esc($d['package_id']); ?>();" class="btn btn-outline-primary"> <i class="fa fa-road"></i> <?= esc($currentActivity['activity']); ?> ke <?= esc($nextActivity['activity']); ?> </button>
                                                        </li>
                                            <?php
                                                    endif;
                                                endforeach;
                                            endif;
                                            ?>
                                        <?php else : ?>
                                            <li><span class="dropdown-item text-muted">Tidak ada aktivitas untuk hari ini</span></li>
                                        <?php endif; ?>
                                    </ul>
                                    </ul>
                                    </ul>
                                </div>
                            <?php endforeach; ?>
                        </div>

                    </div>
                </div>
                <script>
                    initMap(-0.54145013, 100.48094882);

                    window.onload = function() {
                        try {
                            // Cek apakah data Day 1 ada
                            <?php if (isset($day[0])) : ?>
                                add<?= $day[0]['day'], $day[0]['package_id']; ?>();
                            <?php else : ?>
                                console.log("Tidak ada data untuk Day 1");
                            <?php endif; ?>
                        } catch (error) {
                            // Menangani error jika terjadi
                            console.error("Terjadi error saat memanggil fungsi Day 1: ", error);
                        }
                    };
                </script>
                <?php foreach ($day as $d) : ?>
                    <?php foreach ($activity as $ac) : ?>
                        <script>
                        </script>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                <div class="card-body" style="padding-bottom: 0.5rem !important;">
                    <div class="row">
                        <div class="col">
                            <?php foreach ($day as $d) : ?>
                                <div id="div-day-detail-<?= $d['day'], $d['package_id']; ?>" class="div-day-detail" style="padding: 5px;>">
                                    <b>Day <?= esc($d['day']); ?></b>
                                    <ol>
                                        <?php foreach ($activity as $ac) : ?>
                                            <?php if ($d['day'] == $ac['day']) : ?>
                                                <li><?= esc($ac['name']); ?> : <?= esc($ac['description']); ?></li>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </ol>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <!-- Direction section -->
                <?= $this->include('web/layouts/direction'); ?>

                <div class="card-body">
                    <div class="mt-3">
                        <a title="Around You" class="btn icon btn-outline-primary mx-1" onclick="openExplore()">
                            <i class="fa-solid fa-compass me-3"></i>Search object around you?
                        </a>
                    </div>
                    <!-- Nearby section -->
                    <?= $this->include('web/layouts/explore'); ?>
                </div>


            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script>
    const myModal = document.getElementById('videoModal');
    const videoSrc = document.getElementById('video-play').getAttribute('data-src');

    myModal.addEventListener('shown.bs.modal', () => {
        // console.log(videoSrc);
        document.getElementById('video').setAttribute('src', videoSrc);
    });
    myModal.addEventListener('hide.bs.modal', () => {
        document.getElementById('video').setAttribute('src', '');
    });

    $('#direction-row').hide();
</script>
<script>
    $('#direction-row').hide();
    $('#check-explore-col').hide();
    $('#result-explore-col').hide();
</script>
<?= $this->endSection() ?>