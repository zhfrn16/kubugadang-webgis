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
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="row">
        <script>
            currentUrl = '<?= current_url(); ?>';
        </script>

        <!-- ADD DATA NEW FACILITY -->
        <div class="modal fade" id="facilityModal" tabindex="-1" aria-labelledby="facilityModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="facilityModalLabel">Data Facility</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="row g-3" action="<?= ($edit) ? base_url('dashboard/facilityhomestay/createfacility') . '/' . $data['id'] : base_url('dashboard/facilityhomestay/createfacility') . '/' . $homestay_id; ?>" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="card-header">
                                <?php @csrf_field(); ?>
                                <div class="row g-4">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="facility_name">Facility Name</label>
                                            <input type="text" class="form-control" id="facility_name" name="facility_name" required>
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
        <!-- end menambahkan data baru fasilitas -->


        <!-- Menambahkan Facility RumGad-->
        <!-- <button type="button" class="btn btn-info add-new"><i class="fa fa-plus"></i> Activity</button> -->
        <div class="modal fade" id="facilityRumahGadangModal" tabindex="-1" aria-labelledby="facilityRumahGadangModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="facilityRumahGadangModalLabel">Facility Homestay</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form class="row g-3" action="<?= ($edit) ? base_url('dashboard/facilityhomestay/createfacilityhomestay') . '/' . $data['id'] : base_url('dashboard/facilityhomestay/createfacilityhomestay') . '/' . $homestay_id;  ?>" method="post">
                        <div class="modal-body">
                            <div class="card-header">
                                <?php @csrf_field(); ?>
                                <div class="row g-4">
                                    <div class="col-md-12">
                                        <label for="facilityRumahGadang">Facility Homestay</label>
                                        <select class="form-select" name="facility" required>
                                            <option value="" selected>Select the facility</option>
                                            <?php foreach ($facility as $item => $keyy) : ?>
                                                <option value="<?= esc($keyy['id']); ?>"><?= esc($keyy['name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div><br>
                            
                                <div class="row g-4">
                                    <div class="col-md-12">
                                        <label for="description_facility">Description</label>
                                        <textarea class="form-control" id="description_facility" required name="description_facility" cols="30" rows="10"></textarea>
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
        <!-- end Menambahkan Facility RumGad-->

        <!-- Object Detail Information -->
        <div class="col-md-6 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title text-center"><?= $title; ?></h4>
                    <?php if ($edit) : ?>
                        <a href="<?= base_url('dashboard/unithomestay/new'); ?>/<?= $data['id']; ?>" class="btn btn-primary float-end"><i class="fa fa-add"></i> Add Unit</a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <form class="form form-vertical" action="<?= ($edit) ? base_url('dashboard/homestay/update') . '/' . $data['id'] : base_url('dashboard/homestay'); ?>" method="post" onsubmit="checkRequired(event)" enctype="multipart/form-data">
                        <div class="form-body">
                            <div class="form-group mb-4">
                                <label for="geo-json" class="mb-2">GeoJSON</label>
                                <input type="text" id="geo-json" class="form-control" name="geo-json" placeholder="GeoJSON" readonly="readonly" required value='<?= ($edit) ? $data['geoJson'] : ''; ?>'>
                                <input type="hidden" class="form-control" id="multipolygon" name="multipolygon" placeholder="">
                            </div>
                            <div class="form-group mb-4">
                                <label for="name" class="mb-2">Homestay Name</label>
                                <input type="text" id="name" class="form-control" name="name" placeholder="Homestay Name" value="<?= ($edit) ? $data['name'] : old('name'); ?>" required autocomplete="off">
                            </div>
                            <div class="form-group mb-4">
                                <label for="name" class="mb-2">Address</label>
                                <input type="text" id="name" class="form-control" name="address" placeholder="Address" value="<?= ($edit) ? $data['address'] : old('address'); ?>" required autocomplete="off">
                            </div>
                            <div class="form-group mb-4">
                                <label for="contact_person" class="mb-2">Contact Person</label>
                                <input type="tel" id="contact_person" class="form-control" name="contact_person" placeholder="Contact Person" value="<?= ($edit) ? $data['contact_person'] : old('contact_person'); ?>" autocomplete="off">
                            </div>


                            <div class="form-group mb-4">
                                <label for="open" class="mb-2">Open</label>
                                <input type="time" id="open" class="form-control" name="open" value="<?= ($edit) ? $data['open'] : old('open'); ?>" required>
                            </div>
                            <div class="form-group mb-4">
                                <label for="close" class="mb-2">Close</label>
                                <input type="time" id="close" class="form-control" name="close" value="<?= ($edit) ? $data['close'] : old('close'); ?>" required>
                            </div>

                            <div class="form-group mb-4">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4"><?= ($edit) ? $data['description'] : old('description'); ?></textarea>
                            </div>
                            <div class="form-group mb-4">
                                <label for="price" class="mb-2">Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp </span>
                                    <input type="number" id="price" class="form-control" name="price" placeholder="Price" aria-label="Price" aria-describedby="price" value="<?= ($edit && isset($data['price'])) ? $data['price'] : 0; ?>">
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label for="gallery" class="form-label">Gallery</label>
                                <input class="form-control" accept="image/*" type="file" name="gallery[]" id="gallery" multiple>
                            </div>
                            <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                            <?php if ($edit) : ?>
                                <button type="submit" class="btn btn-primary me-1 mb-1">Save Change</button>
                            <?php else : ?>
                                <button type="submit" class="btn btn-primary me-1 mb-1">Save & Add Facility</button>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-12">
            <!-- Object Location on Map -->
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-12 mb-3">
                            <h5 class="card-title">Google Maps</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="latitude">Latitude</label>
                                <input type="text" class="form-control" id="latitude" name="latitude" value="<?= ($edit) ? $data['lat'] : old('lat'); ?>" placeholder="eg. -0.52435750">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="latitude">Longitude</label>
                                <input type="text" class="form-control" id="longitude" name="longitude" value="<?= ($edit) ? $data['lng'] : old('lng'); ?>" placeholder="eg. 100.49234850">
                            </div>
                        </div>
                        <div class="col-auto mx-1">
                            <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Search" class="btn icon btn-outline-primary" onclick="findCoords('EV');">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </a>
                        </div>
                        <div class="col-auto mx-1">
                            <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Clear" class="btn icon btn-outline-danger" id="clear-drawing">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        </div>
                    </div>

                </div>
                <?= $this->include('web/layouts/map-body'); ?>
                <script>
                    initDrawingManager(<?= $edit ?>);
                </script>
            </div>
            <!-- Object Media -->

            <?php if ($edit) : ?>
                <div class="card">
                    <div class="card-body">
                        <div class="form-group mb-4">
                            <label for="facility" class="mb-2">Facility</label>
                            <div class="col-auto ">
                                <div class="btn-group float-right" role="group">
                                    <button type="button" class="btn btn-outline-primary " data-bs-toggle="modal" data-bs-target="#facilityModal" data-bs-whatever="@getbootstrap"><i class="fa fa-plus"></i> New Facility</button>
                                    <button type="button" class="btn btn-outline-info " data-bs-toggle="modal" data-bs-target="#facilityRumahGadangModal" data-bs-whatever="@getbootstrap"><i class="fa fa-plus"></i> Add Facility Homestay</button>
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

                            <div class="table-responsive">
                                <div class="table-wrapper">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Facility</th>
                                                <th>Description</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (isset($facility_homestay)) : ?>
                                                <?php $i = 1; ?>
                                                <?php foreach ($facility_homestay as $item => $value) : ?>
                                                    <tr>
                                                        <td><?= esc($i++); ?></td>
                                                        <td><?= esc($value['name']); ?></td>
                                                        <td><?= esc($value['description']); ?></td>
                                                        <td>
                                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                                <form id="deleteFormFH<?= esc($value['homestay_id']) ?><?= esc($value['facility_homestay_id']) ?>" action="<?= base_url('dashboard/facilityhomestay/delete/') . $value['homestay_id']; ?>" method="post" class="d-inline">
                                                                    <?= csrf_field(); ?>
                                                                    <input type="hidden" name="homestay_id" value="<?= esc($value['homestay_id']); ?>">
                                                                    <input type="hidden" name="facility_homestay_id" value="<?= esc($value['facility_homestay_id']); ?>">
                                                                    <input type="hidden" name="description" value="<?= esc($value['description']); ?>">
                                                                    <input type="hidden" name="_method" value="DELETE">
                                                                    <!-- <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('apakah anda yakin akan menghapus?');"><i class="fa fa-times"></i></button> -->
                                                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDeleteFU('deleteFormFH<?= esc($value['homestay_id']) ?><?= esc($value['facility_homestay_id']); ?>');">
                                                                        <i class="fa fa-times"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>


    </div>
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

<!-- <script>
    $('#datepicker_start').datepicker({
        format: 'yyyy-mm-dd',
        startDate: '-3d'
    });
    $('#datepicker_end').datepicker({
        format: 'yyyy-mm-dd',
        startDate: '-3d'
    });
</script> -->
<script>
    // const myModal = document.getElementById('videoModal');
    // const videoSrc = document.getElementById('video-play').getAttribute('data-src');

    // myModal.addEventListener('shown.bs.modal', () => {
    //     // console.log(videoSrc);
    //     document.getElementById('video').setAttribute('src', videoSrc);
    // });
    // myModal.addEventListener('hide.bs.modal', () => {
    //     document.getElementById('video').setAttribute('src', '');
    // });

    function checkRequired(event) {
        if (!$('#geo-json').val()) {
            event.preventDefault();
            Swal.fire('Please select location for the New Homestay');
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
            <?php foreach ($data['gallery'] as $g) : ?> `<?= base_url('media/photos/homestay/' . $g); ?>`,
            <?php endforeach; ?>
        );
    <?php endif; ?>
    pond.setOptions({
        server: '/upload/photo'
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

<?= $this->endSection() ?>