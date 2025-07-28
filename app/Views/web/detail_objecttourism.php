<!-- web/detail_objecttourism.php -->
<?= $this->extend('web/layouts/main') ?>
<?= $this->section('content') ?>
<div class="container mt-4">
    <h2><?= esc($data['name']) ?></h2>
    <div class="mb-3">
        <strong>Category:</strong> <?= esc($data['category']) ?><br>
        <strong>Price:</strong> <?= esc($data['price']) ?><br>
        <strong>Open:</strong> <?= esc($data['open']) ?> - <strong>Close:</strong> <?= esc($data['close']) ?><br>
        <strong>Min Capacity:</strong> <?= esc($data['min_capacity']) ?><br>
    </div>
    <div class="mb-3">
        <strong>Description:</strong>
        <p><?= esc($data['description']) ?></p>
    </div>
    <?php if (!empty($data['video_url'])): ?>
        <div class="mb-3">
            <strong>Video:</strong><br>
            <iframe width="560" height="315" src="<?= esc($data['video_url']) ?>" frameborder="0" allowfullscreen></iframe>
        </div>
    <?php endif; ?>
    <a href="<?= base_url('/') ?>" class="btn btn-secondary">Back</a>
</div>
<?= $this->endSection() ?>