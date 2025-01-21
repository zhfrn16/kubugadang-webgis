<?= $this->extend('dashboard/layouts/main'); ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Website Analytics</h3>
            <p><?= esc($price['total_price']); ?></p>
        </div>
        <div class="card-body">
        </div>
    </div>
</section>
<?= $this->endSection() ?>