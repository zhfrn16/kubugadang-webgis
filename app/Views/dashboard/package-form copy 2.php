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
        <!-- end menambahkan data Service -->


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
            <!-- Services -->
            <?php if (($edit)) : ?>
                <div class="col-md-12 col-12">
                    <div class="card">
                        <div class="card-header">
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
                                    <div class="table-responsive">
                                        <div class="table-wrapper">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Name</th>
                                                        <th>Price</th>
                                                        <th>Category</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (isset($detailservice)) : ?>
                                                        <?php $i = 1; ?>
                                                        <?php foreach ($detailservice as $item => $value) : ?>
                                                            <?php if ($value['status'] == "1") : ?>
                                                                <tr>
                                                                    <td><?= esc($i++); ?></td>
                                                                    <td><?= esc($value['name']); ?></td>
                                                                    <td><?= esc($value['price']); ?></td>
                                                                    <?php if ($value['category'] == 1) : ?> <td>Group</td>
                                                                    <?php elseif ($value['category'] == 2) : ?>
                                                                        <td>Individu</td>
                                                                    <?php endif; ?>
                                                                    <td>
                                                                        <div class="btn-group" role="group" aria-label="Basic example">
                                                                            <form action="<?= base_url('dashboard/servicepackage/delete/') . $value['package_id']; ?>" method="post" class="d-inline">
                                                                                <?= csrf_field(); ?>
                                                                                <input type="hidden" name="package_id" value="<?= esc($value['package_id']); ?>">
                                                                                <input type="hidden" name="service_package_id" value="<?= esc($value['service_package_id']); ?>">
                                                                                <input type="hidden" name="name" value="<?= esc($value['name']); ?>">
                                                                                <input type="hidden" name="status" value="<?= esc($value['status']); ?>">
                                                                                <input type="hidden" name="_method" value="DELETE">
                                                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this service?');"><i class="fa fa-times"></i></button>
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


                                    <label for="facility" class="mb-2">Non-Services</label>
                                    <div class="table-responsive">
                                        <div class="table-wrapper">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Name</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (isset($detailservice)) : ?>
                                                        <?php $i = 1; ?>
                                                        <?php foreach ($detailservice as $item => $value) : ?>
                                                            <?php if ($value['status'] == "0") : ?>
                                                                <tr>
                                                                    <td><?= esc($i++); ?></td>
                                                                    <td><?= esc($value['name']); ?></td>
                                                                    <td>
                                                                        <div class="btn-group" role="group" aria-label="Basic example">
                                                                            <form action="<?= base_url('dashboard/servicepackage/delete/') . $value['package_id']; ?>" method="post" class="d-inline">
                                                                                <?= csrf_field(); ?>
                                                                                <input type="hidden" name="package_id" value="<?= esc($value['package_id']); ?>">
                                                                                <input type="hidden" name="service_package_id" value="<?= esc($value['service_package_id']); ?>">
                                                                                <input type="hidden" name="name" value="<?= esc($value['name']); ?>">
                                                                                <input type="hidden" name="status" value="<?= esc($value['status']); ?>">
                                                                                <input type="hidden" name="_method" value="DELETE">
                                                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this service?');"><i class="fa fa-times"></i></button>
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
                            <?php if ($edit) : ?>
                                <a href="<?= base_url('dashboard/packageday/'); ?>/<?= $data['id']; ?>" class="btn btn-secondary"><i class="fa fa-plus"></i> Manage Activity Package</a>
                            <?php endif; ?>
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
            Swal.fire('Please select location for the New Package');
        }
    }
</script>

<!-- <script>
    function calculateTotalPrice() {
        const price = parseFloat($('#service_price').val());
        const capacity = parseInt($('#service_min_capacity').val());
        const totalPeople = parseInt($('#min_capacity').val());


        // const totalPriceReservation = parseFloat($('#step3_total_price_reservation').val());

        const numberOfServices = Math.floor(totalPeople / capacity);
        const remainder = totalPeople % capacity;

        if (numberOfServices !== 0) {
            let add = 0;
            if (remainder !== 0 && remainder < 5) {
                add = 0.5;
            } else if (remainder >= 5) {
                add = 1;
            }

            const order = numberOfServices + add;
            $('#service_item').val(order);


            const totalPrice = price * order;           
            $('#service_total_price').val(totalPrice);
         

        } else {
            const order = 1;
            $('#service_item').val(order);


            const totalPrice = price * order;
            $('#service_total_price').val(totalPrice);

        }


    }
</script> -->



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

<?= $this->endSection() ?>