<?= $this->extend('web/layouts/main'); ?>

<?= $this->section('content') ?>
<style>
    .rating {
        display: inline-block;
        font-size: 25px;
    }

    .rating {
        color: orange;
    }
</style>

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
                            <h4 class="card-title text-center">Homestay Information</h4>
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
                                        <td class="fw-bold">Address</td>
                                        <td><?= esc($data['address']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Contact Person</td>
                                        <td><?= esc($data['contact_person']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Open</td>
                                        <td><?= esc($data['open']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Close</td>
                                        <td><?= esc($data['close']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Price</td>
                                        <td>  
                                            <?php
                                                $price = isset($data['price']) && $data['price'] !== null ? $data['price'] : 0;
                                                echo 'Rp ' . number_format(esc($price), 0, ',', '.');
                                            ?>
                                            </td>
                                    </tr>                              
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p class="fw-bold">Description</p>
                            <p><?= esc($data['description']);
                                ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p class="fw-bold">Facility</p>
                            <p>
                                <?php if (isset($facilityhome)) : ?>
                                    <?php foreach ($facilityhome as $dt_fc) : ?>
                                        <li>
                                            <?= esc($dt_fc['name']); ?> (<?= esc($dt_fc['description']); ?>)
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Start Review Section -->
            <div>
                <div class="card-header">
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6">
                            <h4 class="card-title text-center">Reviews</h4>
                        </div>
                        <div class="col-3 text-end">
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addReviewModal">
                                <i class="fa fa-plus"></i> Add Review
                            </button>
                        </div>
                    </div>
                                    <div class="card-body">
                    <?php if (isset($comment) && count($comment) > 0) : ?>
                        <?php foreach ($comment as $review) : ?>
                            <div class="mb-4 border-bottom pb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>@<?= esc($review['fullname']) ?></strong>
                                        <span class="text-muted small ms-2">(<?= esc(date('d M Y H:i', strtotime($review['created_at']))) ?>)</span>
                                    </div>
                                    <?php if (user_id() == $review['user_id']) : ?>
                                        <div>

                                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editReviewModal<?= esc($review['id']) ?>">
                                                <i class="fa fa-edit"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteReviewModal<?= esc($review['id']) ?>">
                                                <i class="fa fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="rating2 mb-1">
                                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                                        <?php if ($i <= $review['rating']) : ?>
                                            <i name="rating2" class="fas fa-star"></i>
                                        <?php else : ?>
                                            <i name="rating2" class="far fa-star"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                                <div><?= esc($review['review_text']) ?></div>
                            </div>
                            <?php if (user_id() == $review['user_id']) : ?>
                                <!-- Edit Review Modal -->
                                <div class="modal fade" id="editReviewModal<?= esc($review['id']) ?>" tabindex="-1" aria-labelledby="editReviewModalLabel<?= esc($review['id']) ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="<?= base_url('web/homestay/updateComment/' . $review['id']) ?>" method="post">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editReviewModalLabel<?= esc($review['id']) ?>">Edit Review</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="id" value="<?= esc($data['id']) ?>">
                                                    <div class="mb-3">
                                                        <label for="edit_review_text<?= esc($review['id']) ?>" class="form-label">Review</label>
                                                        <textarea class="form-control" id="edit_review_text<?= esc($review['id']) ?>" name="comment" rows="3" required><?= esc($review['review_text']) ?></textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Rating</label>
                                                        <div id="edit-star-rating<?= esc($review['id']) ?>" class="rating">
                                                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                                                                <input type="radio" class="btn-check" name="rating" id="edit-star<?= esc($review['id']) ?>-<?= $i ?>" value="<?= $i ?>" autocomplete="off" <?= ($review['rating'] == $i) ? 'checked' : '' ?>>
                                                                <label class="fa fa-star<?= ($i <= $review['rating']) ? ' selected' : '' ?>" for="edit-star<?= esc($review['id']) ?>-<?= $i ?>"></label>
                                                            <?php endfor; ?>
                                                        </div>
                                                    </div>
                                                    <style>
                                                        #edit-star-rating<?= esc($review['id']) ?> .fa-star {
                                                            cursor: pointer;
                                                            font-size: 2rem;
                                                            color: #ccc;
                                                        }
                                                        #edit-star-rating<?= esc($review['id']) ?> .fa-star.selected,
                                                        #edit-star-rating<?= esc($review['id']) ?> .fa-star.hovered {
                                                            color: orange;
                                                        }
                                                    </style>
                                                    <script>
                                                        document.addEventListener('DOMContentLoaded', function () {
                                                            const stars = document.querySelectorAll('#edit-star-rating<?= esc($review['id']) ?> .fa-star');
                                                            const radios = document.querySelectorAll('#edit-star-rating<?= esc($review['id']) ?> input[type="radio"]');
                                                            let selected = <?= (int) $review['rating'] ?>;
                                                            stars.forEach((star, idx) => {
                                                                star.addEventListener('mouseenter', function () {
                                                                    for (let i = 0; i <= idx; i++) stars[i].classList.add('hovered');
                                                                });
                                                                star.addEventListener('mouseleave', function () {
                                                                    for (let i = 0; i < stars.length; i++) stars[i].classList.remove('hovered');
                                                                });
                                                                star.addEventListener('click', function () {
                                                                    selected = idx + 1;
                                                                    radios[idx].checked = true;
                                                                    for (let i = 0; i < stars.length; i++) {
                                                                        if (i <= idx) stars[i].classList.add('selected');
                                                                        else stars[i].classList.remove('selected');
                                                                    }
                                                                });
                                                            });
                                                        });
                                                    </script>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Update Review</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Edit Review Modal -->
                                <!-- Delete Review Modal -->
                                <div class="modal fade" id="deleteReviewModal<?= esc($review['id']) ?>" tabindex="-1" aria-labelledby="deleteReviewModalLabel<?= esc($review['id']) ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="<?= base_url('web/homestay/deleteComment/' . $review['id']) ?>" method="post">
                                                <input type="hidden" name="comment_id" value="<?= esc($review['id']) ?>">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteReviewModalLabel<?= esc($review['id']) ?>">Delete Review</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete this review?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Delete Review Modal -->
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p class="text-center">No reviews yet.</p>
                    <?php endif; ?>
                </div>
                </div>

            </div>
            <!-- Add Review Modal -->
            <div class="modal fade" id="addReviewModal" tabindex="-1" aria-labelledby="addReviewModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="<?= base_url('web/homestay/createComment') ?>" method="post">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addReviewModalLabel">Add Review</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" value="<?= esc($data['id']) ?>">
                                <div class="mb-3">
                                    <label for="review_text" class="form-label">Review</label>
                                    <textarea class="form-control" id="review_text" name="comment" rows="3" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Rating</label>
                                    <div id="star-rating" class="rating">
                                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                                            <input type="radio" class="btn-check" name="rating" id="star<?= $i ?>" value="<?= $i ?>" autocomplete="off">
                                            <label class="fa fa-star" for="star<?= $i ?>"></label>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <style>
                                    #star-rating .fa-star {
                                        cursor: pointer;
                                        font-size: 2rem;
                                        color: #ccc;
                                    }
                                    #star-rating .fa-star.selected,
                                    #star-rating .fa-star.hovered {
                                        color: orange;
                                    }
                                </style>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        const stars = document.querySelectorAll('#star-rating .fa-star');
                                        const radios = document.querySelectorAll('#star-rating input[type="radio"]');
                                        let selected = 0;
                                        stars.forEach((star, idx) => {
                                            star.addEventListener('mouseenter', function () {
                                                for (let i = 0; i <= idx; i++) stars[i].classList.add('hovered');
                                            });
                                            star.addEventListener('mouseleave', function () {
                                                for (let i = 0; i < stars.length; i++) stars[i].classList.remove('hovered');
                                            });
                                            star.addEventListener('click', function () {
                                                selected = idx + 1;
                                                radios[idx].checked = true;
                                                for (let i = 0; i < stars.length; i++) {
                                                    if (i <= idx) stars[i].classList.add('selected');
                                                    else stars[i].classList.remove('selected');
                                                }
                                            });
                                        });
                                    });
                                </script>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Submit Review</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End Review Section -->
        </div>

        <div class="col-md-5 col-12">
            <!-- Object Location on Map -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Google Maps</h5>
                </div>

                <?= $this->include('web/layouts/map-body'); ?>
                <script>
                    initMap(<?= esc($data['lat']); ?>, <?= esc($data['lng']); ?>)
                </script>
                <script>
                    objectMarker("<?= esc($data['id']); ?>", <?= esc($data['lat']); ?>, <?= esc($data['lng']); ?>);
                </script>
            </div>

            <!-- Object Media -->
            <?= $this->include('web/layouts/our_gallery'); ?>

        </div>
    </div>
</section>

<?= $this->endSection() ?>