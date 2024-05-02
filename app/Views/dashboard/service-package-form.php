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
<style>
    select {
        appearance: auto;
        -moz-appearance: auto;
        -webkit-appearance: auto;
        background-color: #fff;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%231d1d1d' viewBox='0 0 24 24'%3e%3cpath d='M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right .7em top 50%, 0 0;
        background-size: .65em auto, 100%;
        border: 1px solid #ced4da;
        border-radius: .25rem;
        padding: .375rem .75rem;
        line-height: 1.5;
    }
</style>

<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="row">
        <script>
            currentUrl = '<?= current_url(); ?>';
        </script>

        <!-- Object Detail Information -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title text-center"><?= $title; ?></h4>
                </div>
                <div class="card-body">
                    <form class="form form-vertical" action="<?= ($edit) ? base_url('dashboard/servicepackage/update') . '/' . $data['id'] : base_url('dashboard/servicepackage'); ?>" method="post" onsubmit="checkRequired(event)" enctype="multipart/form-data">
                        <?= csrf_field();  ?>
                        <div class="form-body">
                            <div class="form-group mb-4">
                                <label for="name" class="mb-2">Name</label>
                                <input type="text" id="name" class="form-control" name="name" placeholder="Name" value="<?= ($edit) ? $data['name'] : old('name'); ?>" required>
                            </div>
                            <div class="form-group mb-4">
                                <label for="category" class="mb-2">Category</label>
                                <select id="category" class="form-control" name="category" required>
                                    <option value="1" <?= (($edit && $data['category'] == 1) || old('category') == 1) ? 'selected' : ''; ?>>Group</option>
                                    <option value="2" <?= (($edit && $data['category'] == 2) || old('category') == 2) ? 'selected' : ''; ?>>Individu</option>
                                </select>
                            </div>
                            <div class="form-group mb-4">
                                <label for="min_capacity" class="mb-2">Min Capacity</label>
                                <!-- <input type="number" id="min_capacity" class="form-control" name="min_capacity" placeholder="Name" value="<?= ($edit) ? $data['min_capacity'] : old('min_capacity'); ?>" required> -->
                                <input type="number" id="min_capacity" class="form-control" name="min_capacity" placeholder="Name" value="<?= ($edit) ? $data['min_capacity'] : old('min_capacity'); ?>" required <?= (($edit && $data['category'] == 2) || old('category') == 2) ? 'readonly' : ''; ?>>
                            </div>
                            <div class="form-group mb-4">
                                <label for="price" class="mb-2">Price</label>
                                <input type="number" id="price" class="form-control" name="price" placeholder="Service Price" value="<?= ($edit) ? $data['price'] : old('price'); ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                            <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</section>

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

<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-resize/dist/filepond-plugin-image-resize.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script src="https://cdn.jsdelivr.net/npm/filepond-plugin-media-preview@1.0.11/dist/filepond-plugin-media-preview.min.js"></script>
<script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
<script src="<?= base_url('assets/js/extensions/form-element-select.js'); ?>"></script>

<?= $this->endSection() ?>