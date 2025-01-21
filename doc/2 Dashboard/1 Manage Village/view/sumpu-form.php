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
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?= esc($title); ?></h3>
        </div>
        <div class="card-body">
            <form class="form form-vertical" action="<?= ($edit) ? base_url('dashboard/sumpu/update') . '/' . $data['id'] : base_url('dashboard/sumpu'); ?>" method="post" enctype="multipart/form-data">
                <div class="form-body">
                    <div class="row gx-md-5">
                        <div class="col-md-6 col-12 order-md-first order-last">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <label for="name" class="mb-2">Name</label>
                                        <input type="text" id="name" class="form-control" name="name" placeholder="name" value="<?= ($edit) ? $data['name'] : old('name'); ?>">
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <label for="type_of_tourism" class="mb-2">Type of Tourism</label>
                                        <input type="text" id="type_of_tourism" class="form-control" name="type_of_tourism" placeholder="type_of_tourism" value="<?= ($edit) ? $data['type_of_tourism'] : old('type_of_tourism'); ?>">
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <label for="address" class="mb-2">Address</label>
                                        <input type="text" id="address" class="form-control" name="address" placeholder="address" value="<?= ($edit) ? $data['address'] : old('address'); ?>">
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <label for="open" class="mb-2">Open</label>
                                        <input type="text" id="open" class="form-control" name="open" placeholder="open" value="<?= ($edit) ? $data['open'] : old('open'); ?>">
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <label for="close" class="mb-2">Close</label>
                                        <input type="text" id="close" class="form-control" name="close" placeholder="close" value="<?= ($edit) ? $data['close'] : old('close'); ?>">
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <label for="ticket_price" class="mb-2">Ticket Price</label>
                                        <input type="text" id="ticket_price" class="form-control" name="ticket_price" placeholder="ticket_price" value="<?= ($edit) ? $data['ticket_price'] : old('ticket_price'); ?>">
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <label for="contact_person" class="mb-2">Contact Person</label>
                                        <input type="text" id="contact_person" class="form-control" name="contact_person" placeholder="contact_person" value="<?= ($edit) ? $data['contact_person'] : old('contact_person'); ?>">
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <label for="bank_name" class="mb-2">Bank Name</label>
                                        <input type="text" id="bank_name" class="form-control" name="bank_name" placeholder="bank_name" value="<?= ($edit) ? $data['bank_name'] : old('bank_name'); ?>">
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <label for="bank_code" class="mb-2">Bank Code</label>
                                        <input type="text" id="bank_code" class="form-control" name="bank_code" placeholder="bank_code" value="<?= ($edit) ? $data['bank_code'] : old('bank_code'); ?>">
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <label for="bank_account_holder" class="mb-2">Bank Account Holder Name</label>
                                        <input type="text" id="bank_account_holder" class="form-control" name="bank_account_holder" placeholder="bank_account_holder" value="<?= ($edit) ? $data['bank_account_holder'] : old('bank_account_holder'); ?>">
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <label for="bank_account_number" class="mb-2">Bank Account Number</label>
                                        <input type="text" id="bank_account_number" class="form-control" name="bank_account_number" placeholder="bank_account_number" value="<?= ($edit) ? $data['bank_account_number'] : old('bank_account_number'); ?>">
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="qr_url" class="form-label">QR Image</label>
                                    <input class="form-control" accept="image/*" type="file" name="qr_url" id="qr_url">
                                </div>
                                <!-- <div class="col-12 d-flex justify-content-end mb-3"> -->
                                <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                <!-- </div> -->
                            </div>
                        </div>

                        <div class="col-md-6 col-12 order-md-last order-first">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <label for="gallery" class="mb-2">Gallery</label>
                                        <input class="form-control" accept="image/*" type="file" name="gallery[]" id="gallery" multiple>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
    const qr_url = document.querySelector('input[id="qr_url"]');

    // Create a FilePond instance
    const pond = FilePond.create(photo, {
        imageResizeTargetHeight: 720,
        imageResizeUpscale: false,
        credits: false,
    });

    const pond2 = FilePond.create(qr_url, {       
        imageResizeTargetHeight: 720,
        imageResizeUpscale: false,
        credits: false,
    });

    <?php if ($edit && count($data['gallery']) > 0) : ?>
        pond.addFiles(
            <?php foreach ($data['gallery'] as $g) : ?> `<?= base_url('media/photos/sumpu/' . $g); ?>`,
            <?php endforeach; ?>
        );
    <?php endif; ?>
    pond.setOptions({
        server: '/upload/photo'
    });

    <?php if ($edit && $data['qr_url'] != null) : ?>
        pond2.addFile(`<?= base_url('media/photos/sumpu/' . $data['qr_url']); ?>`)
    <?php endif; ?>
    pond2.setOptions({
        server: '/upload/qr_url'
    });
</script>

<?= $this->endSection() ?>