<?php
$uri = service('uri')->getSegments();
$edit = in_array('edit', $uri);
?>

<?= $this->extend('dashboard/layouts/main'); ?>

<?= $this->section('styles') ?>
<link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/filepond-plugin-media-preview@1.0.11/dist/filepond-plugin-media-preview.min.css">
<link rel="stylesheet" href="<?= base_url('assets/css/pages/form-element-select.css'); ?>">

<style>
    .filepond--root {
        width: 100%;
    }

    .table-wrapper {
        width: 100%;
    }

    .table-body-scroll {
        display: block;
        max-height: 400px;
        /* Set tinggi maksimum scroll */
        overflow-y: auto;
        /* Aktifkan scroll hanya pada y-axis */
        overflow-x: hidden;
        /* Hilangkan scroll horizontal jika tidak diperlukan */
    }

    .table-body-scroll tr {
        display: table;
        width: 100%;
        table-layout: fixed;
        /* Pastikan layout kolom tetap */
    }

    thead,
    tbody tr {
        display: table;
        width: 100%;
        table-layout: fixed;
    }

    thead th {
        position: sticky;
        top: 0;
        background: #f8f9fa;
        z-index: 2;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="row">
        <script>
            currentUrl = '<?= current_url(); ?>';
        </script>

        <!-- ADD DATA Service -->
        <div class="modal fade" id="servicesPackageModal" tabindex="-1" aria-labelledby="servicesPackageModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="servicesPackageModalLabel">Data Services</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="row g-3" action="<?= base_url('dashboard/servicepackage/create'); ?>" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="card-header">
                                <?php @csrf_field(); ?>
                                <div class="row g-4">
                                    <div class="col-md-12">
                                        <div class="form-group mb-4">
                                            <label for="name" class="mb-2">Name</label>
                                            <input type="text" id="name" class="form-control" name="name" placeholder="Name" required>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label for="category" class="mb-2">Category</label>
                                            <select id="category" class="form-control" name="category" required>
                                                <option value="1">Group</option>
                                                <option value="2">Individu</option>
                                            </select>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label for="min_capacity" class="mb-2">Min Capacity</label>
                                            <!-- <input type="number" id="min_capacity" class="form-control" name="min_capacity" placeholder="Name" value="<?= ($edit) ? $data['min_capacity'] : old('min_capacity'); ?>" required> -->
                                            <input type="number" id="min_capacity" class="form-control" name="min_capacity" placeholder="Min Capacity" required>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label for="price" class="mb-2">Price</label>
                                            <input type="number" id="price" class="form-control" name="price" placeholder="Price" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                                <button type="submit" class="btn btn-outline-primary me-1 mb-1"><i class="fa-solid fa-add"></i></button>
                                <button type="reset" class="btn btn-outline-danger me-1 mb-1"><i class="fa-solid fa-trash-can"></i> </button>
                            </div>
                            <script>
                                // Ambil elemen dropdown
                                var categoryDropdown = document.getElementById("category");

                                // Ambil elemen input min capacity
                                var minCapacityInput = document.getElementById("min_capacity");

                                // Tambahkan event listener untuk mengubah nilai dan keadaan readonly input saat dropdown berubah
                                categoryDropdown.addEventListener("change", function() {
                                    if (this.value == 1) {
                                        // Jika kategori dipilih adalah Group, aktifkan input dan atur nilai min capacity sesuai kebutuhan
                                        minCapacityInput.removeAttribute("readonly");
                                        // Di sini Anda bisa mengatur nilai min capacity sesuai kebutuhan
                                    } else if (this.value == 2) {
                                        // Jika kategori dipilih adalah Individu, nonaktifkan input dan atur nilai min capacity ke 0
                                        minCapacityInput.setAttribute("readonly", "readonly");
                                        minCapacityInput.value = 1;
                                    }
                                });
                            </script>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- end menambahkan data package -->


        <!-- Menambahkan Service -->
        <!-- <button type="button" class="btn btn-info add-new"><i class="fa fa-plus"></i> Activity</button> -->
        <div class="modal fade" id="detailServicesPackageModal" tabindex="-1" aria-labelledby="detailServicesPackageModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="detailServicesPackageModalLabel">Service Package</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form class="row g-3" action="<?= base_url('dashboard/servicepackage/createservicepackage/') . $id; ?>" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="card-header">
                                <?php @csrf_field(); ?>
                                <div class="row g-4">
                                    <div class="col-md-12">
                                        <div class="row g-4">
                                            <div class="col-md-12">
                                                <!-- <label for="id_service">Package Min Capacity </label> -->
                                                <input hidden type="text" id="package_min_capacity" class="form-control" name="package_min_capacity" placeholder="Package Name" value="<?= ($edit) ? $data['min_capacity'] : old('min_capacity'); ?>" required autocomplete="off">
                                                <!-- <br> -->
                                                <label for="id_service">Service </label>
                                                <select class="form-select" name="id_service" id="id_service" required onchange="serviceOptions()">
                                                    <option value="">Select the service</option>
                                                    <?php foreach ($servicelist as $item) : ?>
                                                        <option value="<?= esc($item['id']); ?>"> <?= esc($item['name']); ?> - <?= ($item['category'] == 1) ? 'Group' : 'Individu'; ?> - <?= esc($item['price']); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div><br>

                                    </div>

                                </div>
                                <br>
                                <div class="row g-4">
                                    <div class="col-md-12">
                                        <label>
                                            <input required type="radio" name="status_service" value="1">
                                            Service
                                        </label>
                                        <label>
                                            <input required type="radio" name="status_service" value="0">
                                            Non-service
                                        </label>
                                    </div>
                                </div><br>
                                <div class="col-md-12">
                                    <p>*Only service fees will be included in the package price. Non-service is not.</p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                                <button type="submit" class="btn btn-outline-primary me-1 mb-1"><i class="fa-solid fa-add"></i></button>
                                <button type="reset" class="btn btn-outline-danger me-1 mb-1"><i class="fa-solid fa-trash-can"></i> </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <!-- end Menambahkan Service -->

        <!-- Object Detail Information -->
        <div class="col-md-6 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title text-center"><?= $title; ?></h4>
                </div>
                <div class="card-body">
                    <form id="packageForm" class="form form-vertical" action="<?= ($data['custom'] == 1) ? base_url('dashboard/package/updatecustom/') . $data['id'] : (($edit) ? base_url('dashboard/package/update/') . $data['id'] : base_url('dashboard/package')); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-body">
                            <div class="form-group mb-4">
                                <label for="name" class="mb-2">Package Name</label>
                                <input type="text" id="name" class="form-control" name="name" placeholder="Package Name" value="<?= ($edit) ? $data['name'] : old('name'); ?>" required autocomplete="off">
                            </div>
                            <fieldset class="form-group mb-4">
                                <label for="type" class="mb-2">Package Type</label>
                                <select class="form-select" id="type" name="type">
                                    <?php foreach ($type as $t) : ?>
                                        <?php if ($edit) : ?>
                                            <option value="<?= esc($t['id']); ?>" <?= (esc($data['type_id']) == esc($t['id'])) ? 'selected' : ''; ?>><?= esc($t['type_name']); ?></option>
                                        <?php else : ?>
                                            <option value="<?= esc($t['id']); ?>"><?= esc($t['type_name']); ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </fieldset>
                            <div class="form-group mb-4">
                                <label for="price" class="mb-2">Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp </span>
                                    <input type="text" id="price" class="form-control" name="price" placeholder="Price" aria-label="Price" aria-describedby="price" value="<?= ($edit) ? $data['price'] : old('price'); ?>">
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label for="min_capacity" class="mb-2">Minimal Capacity</label>
                                <input type="number" min="1" id="min_capacity" class="form-control" name="min_capacity" placeholder="Minimal Capacity" value="<?= ($edit) ? $data['min_capacity'] : old('min_capacity'); ?>" autocomplete="off" required>
                            </div>
                            <div class="form-group mb-4">
                                <label for="contact_person" class="mb-2">Contact Person</label>
                                <input type="tel" id="contact_person" class="form-control" name="contact_person" placeholder="Contact Person" value="<?= ($edit) ? $data['contact_person'] : old('contact_person'); ?>" autocomplete="off">
                            </div>
                            <div class="form-group mb-4">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4"><?= ($edit) ? $data['description'] : old('description'); ?></textarea>
                            </div>
                            <div class="form-group mb-4">
                                <label for="gallery" class="form-label">Gallery</label>
                                <input class="form-control" accept="image/*" type="file" name="gallery[]" id="gallery" multiple>
                            </div>
                            <div class="form-group mb-4">
                                <label for="video" class="form-label">Video</label>
                                <input class="form-control" accept="video/*, .mkv" type="file" name="video" id="video">
                            </div>
                            <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                            <?php if (($edit)) : ?>
                                <button type="submit" class="btn btn-primary me-1 mb-1">Save Change</button>
                            <?php else : ?>
                                <button type="submit" class="btn btn-primary me-1 mb-1">Save</button>
                            <?php endif; ?>
                        </div>
                    </form>

                    <br />
                </div>
            </div>
        </div>

        <div class="col-md-6 col-12">
            <?php if (($edit)) : ?>

                <!-- Google Maps -->
                <div class="col-md-12 col-12">
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
                        <!-- End direction section -->

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

                <!-- Detail Package -->
                <div class="col-md-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title text-center">Detail Package</h4>
                            <div class="row align-items-center">
                                <div class="form-group mb-4">
                                    <div class="col-auto ">
                                        <br>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-primary " data-bs-toggle="modal" data-bs-target="#dayModal" onclick="dayOptions()" data-bs-whatever="@getbootstrap"><i class="fa fa-plus"></i> Day</button>
                                            <button type="button" class="btn btn-outline-info " data-bs-toggle="modal" data-bs-target="#activityModal" data-bs-whatever="@getbootstrap"><i class="fa fa-plus"></i> Activity</button>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="modal fade" id="dayModal" tabindex="-1" aria-labelledby="dayModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="dayModalLabel">Package Day</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form class="row g-3" action="<?= base_url('dashboard/package/packageday/createday') . '/' . $data['id']; ?>" method="post" enctype="multipart/form-data">
                                                    <div class="modal-body">
                                                        <div class="card-header">
                                                            <?php @csrf_field(); ?>
                                                            <h5 class="card-title"><?= esc($data['name']) ?></h5>
                                                            <div class="row g-4">
                                                                <div class="col-md-7">
                                                                    <div class="form-group">
                                                                        <label for="package">Package</label>
                                                                        <input type="text" class="form-control" id="package" name="package" placeholder="Pxxxxx" disabled value="<?= esc($data['id']) ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <div class="form-group">
                                                                        <label for="day">Day</label>
                                                                        <input type="number" min="1" class="form-control" id="day" name="day" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row g-4">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="description">Description</label>
                                                                        <input type="text" class="form-control" id="description" name="description" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                                                        <button type="submit" class="btn btn-outline-primary me-1 mb-1"><i class="fa-solid fa-add"></i></button>
                                                        <button type="reset" class="btn btn-outline-danger me-1 mb-1"><i class="fa-solid fa-trash-can"></i> </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="editdayModal" tabindex="-1" aria-labelledby="dayModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="dayModalLabel">Edit Package Day</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form class="row g-3" action="<?= base_url('dashboard/package/packageday/createday') . '/' . $data['id']; ?>" method="post" enctype="multipart/form-data">
                                                    <div class="modal-body">
                                                        <div class="card-header">
                                                            <?php @csrf_field(); ?>
                                                            <h5 class="card-title"><?= esc($data['name']) ?></h5>
                                                            <div class="row g-4">
                                                                <div class="col-md-7">
                                                                    <div class="form-group">
                                                                        <label for="package">Package</label>
                                                                        <input type="text" class="form-control" id="package" name="package" placeholder="Pxxxxx" disabled value="<?= esc($data['id']) ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <div class="form-group">
                                                                        <label for="day">Day</label>
                                                                        <input type="number" min="1" class="form-control" id="day" name="day" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row g-4">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="description">Description</label>
                                                                        <input type="text" class="form-control" id="description" name="description" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                                                        <button type="submit" class="btn btn-outline-primary me-1 mb-1"><i class="fa-solid fa-add"></i></button>
                                                        <button type="reset" class="btn btn-outline-danger me-1 mb-1"><i class="fa-solid fa-trash-can"></i> </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end menambahkan hari paket -->

                                    <!-- Menambahkan Aktivitas -->
                                    <div class="col-sm-2 float-end">
                                        <!-- <button type="button" class="btn btn-info add-new"><i class="fa fa-plus"></i> Activity</button> -->
                                        <div class="modal fade" id="activityModal" tabindex="-1" aria-labelledby="activityModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="activityModalLabel">Activity Package Day </h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <form class="row g-3" action="<?= base_url('dashboard/package/packageday/createactivity') . '/' . $data['id']; ?>" method="post">
                                                        <div class="modal-body">
                                                            <div class="card-header">
                                                                <?php @csrf_field(); ?>
                                                                <div class="row g-4">
                                                                    <div class="col-md-12">
                                                                        <input hidden type="text" class="form-control" id="package" name="package" placeholder="Pxxxxx" disabled value="<?= esc($data['id']) ?>">
                                                                        <label for="day">Activity Day</label>
                                                                        <select class="form-select" name="dayselect" id="dayselect" required onchange="activityOptions()">
                                                                            <option value="" selected>Select the day</option>
                                                                            <?php foreach ($day as $item => $keyy) : ?>
                                                                                <option value="<?= esc($keyy['day']); ?>">Activity Day <?= esc($keyy['day']); ?></option>
                                                                            <?php endforeach; ?>
                                                                        </select>
                                                                    </div>
                                                                </div><br>
                                                                <div class="row g-4">
                                                                    <div class="col-md-3">
                                                                        <label for="activity">Activity</label>
                                                                        <input type="number" min='1' required class="form-control" id="activity" name="activity">
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label for="activity_type">Activity Type</label>
                                                                        <select class="form-select" name="activity_type" id="activity_type" required onchange="objectOptions()">
                                                                            <option value="" selected>Select Type</option>
                                                                            <option value="A">Attraction</option>
                                                                            <option value="TH">Traditional House</option>
                                                                            <!-- <option value="HO">Homestay</option> -->
                                                                            <!-- <option value="EV">Event</option> -->
                                                                            <option value="CP">Culinary Place</option>
                                                                            <option value="SP">Souvenir Place</option>
                                                                            <option value="WO">Worship Place</option>
                                                                            <option value="FC">Facility</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-5">
                                                                        <label for="object1">Object</label>
                                                                        <script>
                                                                            objectOptions();
                                                                        </script>
                                                                        <fieldset class="form-group">
                                                                            <select class="form-select" name="object" id="object" required>
                                                                            </select>
                                                                        </fieldset>
                                                                    </div>
                                                                </div><br>
                                                                <div class="row g-4">
                                                                    <div class="col-md-12">
                                                                        <label for="description_activity">Description</label>
                                                                        <input type="text" class="form-control" id="description_activity" placeholder="Type the description here..." name="description_activity" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                                                            <button type="submit" class="btn btn-outline-primary me-1 mb-1"><i class="fa-solid fa-add"></i></button>
                                                            <button type="reset" class="btn btn-outline-danger me-1 mb-1"><i class="fa-solid fa-trash-can"></i> </button>
                                                        </div>
                                                    </form>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end Menambahkan Aktivitas -->


                                    <?php if (session()->getFlashdata('pesan')) : ?>
                                        <div class="alert alert-success col-sm-10 mx-auto" role="alert">
                                            <?= session()->getFlashdata('pesan'); ?>
                                        </div>
                                    <?php endif;  ?>

                                    <?php if (isset($day)) : ?>
                                        <?php foreach ($day as $item => $key) : ?>
                                            <div class="table-responsive day-table">
                                                <div class="table-title">
                                                    <div class="row">
                                                        <div class="col-sm-10">
                                                            <p>Day <?= esc($key['day']); ?><br>
                                                                <?= esc($key['description']); ?></p>
                                                        </div>
                                                        <div class="col-sm-2 ">
                                                            <div class="btn-group float-end" role="group" aria-label="Basic example">
                                                                <form action="packageday/deleteday/<?= $key['package_id']; ?>" method="post" class="d-inline">
                                                                    <?= csrf_field(); ?>
                                                                    <input type="hidden" name="package_id" value="<?= esc($key['package_id']); ?>">
                                                                    <input type="hidden" name="day" value="<?= esc($key['day']); ?>">
                                                                    <input type="hidden" name="description" value="<?= esc($key['description']); ?>">
                                                                    <input type="hidden" name="_method" value="DELETE">
                                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this day?');"><i class="fa fa-trash"></i></button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="table-wrapper">
                                                    <input type="hidden" name="dayday" value="<?= esc($key['day']); ?>"> <!-- Hidden input untuk hari -->
                                                    <table class="table table-sm activity-table" data-day="<?= esc($key['day']); ?>">
                                                        <thead>
                                                            <tr>
                                                                <th style="padding: 3px; width:30px;">No</th>
                                                                <th style="padding: 3px; width:45px;">Act Type</th>
                                                                <th>Object</th>
                                                                <th>Price</th>
                                                                <th>Description</th>
                                                                <th style="padding: 3px; width:65px;">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="table-body-scroll">
                                                            <?php if (isset($data_package)) : ?>
                                                                <?php foreach ($data_package as $item => $value) : ?>
                                                                    <?php if ($value['day'] == $key['day']) : ?>
                                                                        <tr>
                                                                            <td style="padding: 3px; width:30px;""><?= esc($value['activity']); ?></td>
                                                                            <td style=" padding: 3px; width:45px;"><?= esc($value['activity_type']); ?></td>
                                                                            <td><?= esc($value['name']); ?></td>
                                                                            <?php if (isset($value['attraction_price'])) : ?>
                                                                                <td><?= esc($value['attraction_price']); ?></td>
                                                                            <?php elseif (isset($value['traditional_house_price'])) : ?>
                                                                                <td><?= esc($value['traditional_house_price']); ?></td>
                                                                            <?php else : ?>
                                                                                <td> 0 </td>
                                                                            <?php endif; ?>
                                                                            <td><?= esc($value['description']); ?></td>
                                                                            <td style="padding: 3px; width:65px;">
                                                                                <!-- <a class="add" title="Add" data-toggle="tooltip"><i class="material-icons">&#xE03B;</i></a> -->
                                                                                <!-- <a class="edit" title="Edit" data-toggle="tooltip"><i class="material-icons">&#xE254;</i></a> -->

                                                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                                                    <form action="packageday/delete/<?= $value['package_id']; ?>" method="post" class="d-inline">
                                                                                        <?= csrf_field(); ?>
                                                                                        <input type="hidden" name="package_id" value="<?= esc($value['package_id']); ?>">
                                                                                        <input type="hidden" name="day" value="<?= esc($value['day']); ?>">
                                                                                        <input type="hidden" name="activity" value="<?= esc($value['activity']); ?>">
                                                                                        <input type="hidden" name="description" value="<?= esc($value['description']); ?>">
                                                                                        <input type="hidden" name="_method" value="DELETE">
                                                                                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this activity?');"><i class="fa fa-times"></i></button>
                                                                                    </form>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    <?php endif; ?>

                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <br>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Services -->
                <div class="col-md-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title text-center">Service Package</h4><br>
                            <div class="row align-items-center">
                                <div class="form-group mb-4">
                                    <div class="col-auto ">
                                        <div class="btn-group float-right" role="group">
                                            <button type="button" class="btn btn-outline-primary " data-bs-toggle="modal" data-bs-target="#servicesPackageModal" data-bs-whatever="@getbootstrap"><i class="fa fa-plus"></i> New Services</button>
                                            <button type="button" class="btn btn-outline-info " data-bs-toggle="modal" data-bs-target="#detailServicesPackageModal" data-bs-whatever="@getbootstrap"><i class="fa fa-plus"></i> Add Services Package</button>
                                        </div>
                                    </div>
                                    <br>
                                    <?php if (session()->has('success')) : ?>
                                        <script>
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Success!',
                                                text: '<?= session('success') ?>',
                                            });
                                        </script>
                                    <?php endif; ?>

                                    <?php if (session()->has('failed')) : ?>
                                        <script>
                                            Swal.fire({
                                                icon: 'warning',
                                                title: 'Failed!',
                                                text: '<?= session('failed') ?>',
                                            });
                                        </script>
                                    <?php endif; ?>

                                    <label for="facility" class="mb-2">Services</label>
                                    <div class="table-wrapper">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th style="width: 40px;">No</th>
                                                    <th style="width: 130px;">Name</th>
                                                    <th>Price</th>
                                                    <th>Category</th>
                                                    <th style="width: 80px;">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-body-scroll"> <?php if (isset($detailservice)) : ?>
                                                    <?php $i = 1; ?>
                                                    <?php foreach ($detailservice as $item => $value) : ?>
                                                        <?php if ($value['status'] == "1") : ?>
                                                            <tr>
                                                                <td style="width: 40px;"><?= esc($i++); ?></td>
                                                                <td style="width: 150px;"><?= esc($value['name']); ?></td>
                                                                <td><?= esc($value['price']); ?></td>
                                                                <?php if ($value['category'] == 1) : ?> <td>Group</td>
                                                                <?php elseif ($value['category'] == 2) : ?>
                                                                    <td>Individu</td>
                                                                <?php endif; ?>
                                                                <td style="width: 60px;">
                                                                    <div class="btn-group" role="group" aria-label="Basic example">
                                                                        <form action="<?= base_url('dashboard/servicepackage/delete/') . $value['package_id']; ?>" method="post" class="d-inline">
                                                                            <?= csrf_field(); ?>
                                                                            <input type="hidden" name="package_id" value="<?= esc($value['package_id']); ?>">
                                                                            <input type="hidden" name="service_package_id" value="<?= esc($value['service_package_id']); ?>">
                                                                            <input type="hidden" name="name" value="<?= esc($value['name']); ?>">
                                                                            <input type="hidden" name="status" value="<?= esc($value['status']); ?>">
                                                                            <input type="hidden" name="_method" value="DELETE">
                                                                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this service?');"><i class="fa fa-times"></i></button>
                                                                        </form>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>


                                    <label for="facility" class="mb-2">Non-Services</label>
                                    <div class="table-wrapper">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th style="width: 40px;">No</th>
                                                    <th style="width: 130px;">Name</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-body-scroll"> <?php if (isset($detailservice)) : ?>
                                                    <?php $i = 1; ?>
                                                    <?php foreach ($detailservice as $item => $value) : ?>
                                                        <?php if ($value['status'] == "0") : ?>
                                                            <tr>
                                                                <td style="width: 40px;"><?= esc($i++); ?></td>
                                                                <td style="width: 240px;"><?= esc($value['name']); ?></td>
                                                                <td>
                                                                    <div class="btn-group" role="group" aria-label="Basic example">
                                                                        <form action="<?= base_url('dashboard/servicepackage/delete/') . $value['package_id']; ?>" method="post" class="d-inline">
                                                                            <?= csrf_field(); ?>
                                                                            <input type="hidden" name="package_id" value="<?= esc($value['package_id']); ?>">
                                                                            <input type="hidden" name="service_package_id" value="<?= esc($value['service_package_id']); ?>">
                                                                            <input type="hidden" name="name" value="<?= esc($value['name']); ?>">
                                                                            <input type="hidden" name="status" value="<?= esc($value['status']); ?>">
                                                                            <input type="hidden" name="_method" value="DELETE">
                                                                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this service?');"><i class="fa fa-times"></i></button>
                                                                        </form>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endif; ?>
        </div>

    </div>

    <script>
        function activityOptions() {
            const selectedDay = document.getElementById('dayselect').value; // Ambil nilai Day
            const tables = document.querySelectorAll('.activity-table'); // Ambil semua tabel

            const existingActivities = []; // Array untuk menyimpan semua nomor activity yang ada

            tables.forEach((table) => {
                if (table.getAttribute('data-day') === selectedDay) { // Cek tabel berdasarkan Day
                    const rows = table.querySelectorAll('tbody tr'); // Ambil semua baris tabel
                    rows.forEach((row) => {
                        const activityCell = row.children[0]; // Ambil kolom pertama (activity)
                        const activityValue = parseInt(activityCell.textContent.trim(), 10);
                        if (!isNaN(activityValue)) {
                            existingActivities.push(activityValue); // Tambahkan ke array jika valid
                        }
                    });
                }
            });

            // Cari nomor terkecil yang kosong
            existingActivities.sort((a, b) => a - b); // Urutkan nomor activity
            let nextActivity = 1; // Mulai dari 1
            for (const activity of existingActivities) {
                if (activity === nextActivity) {
                    nextActivity++; // Loncat ke nomor berikutnya jika ditemukan
                } else {
                    break; // Berhenti jika menemukan celah
                }
            }

            // Perbarui input activity
            const activityInput = document.getElementById('activity');
            if (activityInput) {
                activityInput.value = nextActivity; // Isi nomor terkecil yang kosong
            }

            console.log("Selected Day: ", selectedDay);
            console.log("Tabel ditemukan: ", tables);
            console.log("Existing Activities: ", existingActivities);
            console.log("Next Activity: ", nextActivity);
        }

        function dayOptions() {
            const tables = document.querySelectorAll('.day-table'); // Ambil semua elemen dengan kelas .day-table

            const existingDays = []; // Array untuk menyimpan semua nomor day yang ada

            tables.forEach((table) => {
                const dayElement = table.querySelector('.table-title p'); // Cari elemen <h2> di .table-title
                if (dayElement) {
                    const match = dayElement.textContent.match(/Day (\d+)/); // Ekstrak angka setelah "Day"
                    if (match) {
                        const dayValue = parseInt(match[1], 10); // Ambil nilai day
                        if (!isNaN(dayValue)) {
                            existingDays.push(dayValue); // Tambahkan ke array jika valid
                        }
                    }
                }
            });

            // Cari nomor day terkecil yang kosong
            existingDays.sort((a, b) => a - b); // Urutkan nomor day secara numerik
            let nextDay = 1; // Mulai dari 1
            for (const day of existingDays) {
                if (day === nextDay) {
                    nextDay++; // Loncat ke nomor berikutnya jika ditemukan
                } else {
                    break; // Berhenti jika menemukan celah
                }
            }

            // Perbarui input day
            const dayInput = document.getElementById('day');
            if (dayInput) {
                dayInput.value = nextDay; // Isi nomor terkecil yang kosong
            }

            console.log("Day ditemukan: ", existingDays);
            console.log("Next Day: ", nextDay);
        }
    </script>

</section>


<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-resize/dist/filepond-plugin-image-resize.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script src="https://cdn.jsdelivr.net/npm/filepond-plugin-media-preview@1.0.11/dist/filepond-plugin-media-preview.min.js"></script>
<script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
<script src="<?= base_url('assets/js/extensions/form-element-select.js'); ?>"></script>

<script>
    function checkRequired(event) {
        if (!$('#geo-json').val()) {
            event.preventDefault();
            Swal.fire('Please select location for the New Package');
        }
    }
</script>

<script>
    FilePond.registerPlugin(
        FilePondPluginFileValidateType,
        FilePondPluginImageExifOrientation,
        FilePondPluginImagePreview,
        FilePondPluginImageResize,
        FilePondPluginMediaPreview,
    );

    // Get a reference to the file input element
    const photo = document.querySelector('input[id="gallery"]');
    const video = document.querySelector('input[id="video"]');

    // Create a FilePond instance
    const pond = FilePond.create(photo, {
        imageResizeTargetHeight: 720,
        imageResizeUpscale: false,
        credits: false,
    });
    const vidPond = FilePond.create(video, {
        credits: false,
    })

    <?php if ($edit && count($data['gallery']) > 0) : ?>
        pond.addFiles(
            <?php foreach ($data['gallery'] as $g) : ?> `<?= base_url('media/photos/package/' . $g); ?>`,
            <?php endforeach; ?>
        );
    <?php endif; ?>
    pond.setOptions({
        server: '/upload/photo'
    });

    <?php if ($edit && $data['video_url'] != null) : ?>
        vidPond.addFile(`<?= base_url('media/videos/' . $data['video_url']); ?>`)
    <?php endif; ?>
    vidPond.setOptions({
        server: '/upload/video'
    });
</script>

<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
        var actions = $("table td:last-child").html();
        // Append table with add row form on add new button click
        $(".add-new").click(function() {
            $(this).attr("disabled", "disabled");
            var index = $("table tbody tr:last-child").index();
            var row = '<tr>' +
                '<td><input type="text" class="form-control" name="activity" id="activity"></td>' +
                '<td><input type="text" class="form-control" name="activity_type" id="activity_type"></td>' +
                '<td><input type="text" class="form-control" name="object" id="object"></td>' +
                '<td><input type="text" class="form-control" name="description" id="description"></td>' +
                '<td>' + actions + '</td>' +
                '</tr>';
            $("table").append(row);
            $("table tbody tr").eq(index + 1).find(".add, .edit").toggle();
            $('[data-toggle="tooltip"]').tooltip();
        });
        // Add row on add button click
        $(document).on("click", ".add", function() {
            var empty = false;
            var input = $(this).parents("tr").find('input[type="text"]');
            input.each(function() {
                if (!$(this).val()) {
                    $(this).addClass("error");
                    empty = true;
                } else {
                    $(this).removeClass("error");
                }
            });
            $(this).parents("tr").find(".error").first().focus();
            if (!empty) {
                input.each(function() {
                    $(this).parent("td").html($(this).val());
                });
                $(this).parents("tr").find(".add, .edit").toggle();
                $(".add-new").removeAttr("disabled");
            }
        });
        // Edit row on edit button click
        $(document).on("click", ".edit", function() {
            $(this).parents("tr").find("td:not(:last-child)").each(function() {
                $(this).html('<input type="text" class="form-control" value="' + $(this).text() + '">');
            });
            $(this).parents("tr").find(".add, .edit").toggle();
            $(".add-new").attr("disabled", "disabled");
        });
        // Delete row on delete button click
        $(document).on("click", ".delete", function() {
            $(this).parents("tr").remove();
            $(".add-new").removeAttr("disabled");
        });
    });
</script>
<script>
    $('#direction-row').hide();
    $('#check-explore-col').hide();
    $('#result-explore-col').hide();
</script>
<?= $this->endSection() ?>