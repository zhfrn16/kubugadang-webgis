<?php
$uri = service('uri')->getSegments();
$users = in_array('users', $uri);
?>
<?= $this->extend('web/layouts/main'); ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="card">
        <div class="card-header mb-2">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="card-title">My Cart</h3>
                </div>
                <div class="col">
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover dt-head-center" id="table-manage">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ID</th>
                            <th>Package</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <?php if (isset($data)) : ?>
                            <?php $i = 1; ?>
                            <?php foreach ($data as $item) : ?>
                                <tr>
                                    <td><?= esc($i); ?></td>
                                    <td><?= esc($item['package_id']); ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <!-- Tambahkan gambar paket di sini -->
                                            <img src="<?= base_url('media/photos/package/' . esc($item['gallery'])); ?>" alt="<?= esc($item['package_name']); ?>" style="max-width: 50px; max-height: 50px; object-fit: cover;" class="me-2">

                                            <div>
                                                <?= esc($item['package_name']); ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="More Info" class="btn icon btn-outline-primary mx-1" href="<?= base_url() . 'web/package' . '/' . esc($item['package_id']); ?>">
                                            More Info
                                        </a>
                                        <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Book Now" class="btn btn-success" href="<?= base_url() . 'web/reservation/custombooking/' . esc($item['package_id']); ?>">
                                            Book Now
                                        </a>
                                        <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete" class="btn icon btn-outline-danger mx-1" onclick="deleteCart('<?= esc($item['package_id']); ?>', '<?= esc($item['package_name']); ?>', '<?= esc($item['id']); ?>', <?= ($users) ? 'true' : 'false'; ?>)">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                    <?php $i++; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script>
    $(document).ready(function() {
        $('#table-manage').DataTable({
            columnDefs: [{
                targets: ['_all'],
                className: 'dt-head-center'
            }],
            lengthMenu: [5, 10, 20, 50, 100]
        });
    });
</script>
<?= $this->endSection() ?>