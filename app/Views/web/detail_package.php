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
        <?php if (session()->has('warning')) : ?>
            <script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Wait!',
                    text: '<?= session('warning') ?>',
                });
            </script>
        <?php endif; ?>
        <!-- Object Detail Information -->
        <div class="col-md-7 col-12">
            <div class="card">
                <div class="row">
                    <!-- Carousel Section -->
                    <div class="col-md-6 col-12">
                        <div class="card-header">
                            <div id="productCarousel" class="carousel slide">
                                <div class="carousel-inner">
                                    <?php if (empty($data['gallery'])) : ?>
                                        <!-- Jika galeri kosong, tampilkan foto default -->
                                        <div class="carousel-item active">
                                            <a>
                                                <img src="<?= base_url('media/photos/package/default.jpg'); ?>" class="d-block w-100" style="height: 300px; object-fit: cover;">
                                            </a>
                                        </div>
                                    <?php else : ?>
                                        <?php $i = 0; ?>
                                        <?php foreach ($data['gallery'] as $item) : ?>
                                            <div class="carousel-item<?= ($i == 0) ? ' active' : ''; ?>">
                                                <a>
                                                    <img src="<?= base_url('media/photos/package/' . esc($item)); ?>" class="d-block w-100" style="height: 300px; object-fit: cover;">
                                                </a>
                                            </div>
                                            <?php $i++; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon bg-primary" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon bg-primary" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- End of Carousel Section -->

                    <!-- Informasi Produk Section -->
                    <div class="col-md-6 col-12" style="padding-left: 2rem;">
                        <br>
                        <div class="row" style="margin-top: 0.5rem;">
                            <div class="col-12">
                                <h5 style="text-align: left;"><?= esc($data['name']); ?></h5>
                            </div>
                        </div>
                        <div class="rating text-center">
                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                                <?php if ($i <= $rating['rating']) : ?>
                                    <i name="rating" class="fas fa-star"></i>
                                <?php else : ?>
                                    <i name="rating" class="far fa-star"></i>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <br>
                                <h6 style="margin-top: 0px;">Start from</h6>

                                <h5 style="margin-top: 0px;"> <?= 'Rp ' . number_format(esc($data['price']), 0, ',', '.'); ?></h5>
                            </div>
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td style="padding-bottom: 0rem !important;"><?= esc($data['type_name']); ?> Package</td>
                                    </tr>
                                    <tr>
                                        <td>Min. <?= esc($data['min_capacity']); ?> People</td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- Tombol Add to Cart dan Book Now -->
                            <div class="col-12 mb-3">
                                <?php if (logged_in()) : ?>
                                    <?php if (in_groups(['admin']) || in_groups(['master'])) : ?>
                                        <button class="btn icon btn-outline-primary me-2" disabled>
                                            <i class="fa fa-cart-plus"></i> Add to Cart
                                        </button>
                                        <button class="btn btn-success" disabled>
                                            Book Now
                                        </button>
                                    <?php else : ?>
                                        <a class="btn icon btn-outline-primary me-2" onclick="addToCart('<?= esc($data['id']); ?>');">
                                            <i class="fa fa-cart-plus"></i> Add to Cart
                                        </a>
                                        <a href="<?= base_url('web/reservation/custombooking/') . $data['id']; ?>" class="btn btn-success">Book Now</a>
                                    <?php endif; ?>
                                <?php else : ?>
                                    <a class="btn icon btn-outline-primary me-2" onclick="redirectToLogin()">
                                        <i class="fa fa-cart-plus"></i> Add to Cart
                                    </a>
                                    <a class="btn btn-success" onclick="redirectToLogin()">Book Now</a>
                                    <script>
                                        function redirectToLogin() {
                                            Swal.fire({
                                                icon: 'warning',
                                                title: 'You are not logged in',
                                                text: 'Please log in to proceed.',
                                                confirmButtonText: 'OK',
                                            }).then(() => {
                                                // Optionally, redirect to the login page
                                                window.location.href = '<?= base_url('/login'); ?>';
                                            });
                                        }
                                    </script>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>

                    <!-- End of Informasi Produk Section -->
                </div>
            </div>


            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-7">
                            <h4 class="card-title text-end">Package Information</h4>
                        </div>
                        <div class="col-5">
                            <div class="text-end">
                                <?php if (logged_in()) : ?>
                                    <?php if (in_groups(['admin']) || in_groups(['master'])) : ?>
                                        <button type="submit" class="btn icon btn-outline-primary" title="Extend Package" disabled><i class="fa-solid fa-plus-square"></i> Extend</button>&nbsp;&nbsp;
                                        <button type="submit" class="btn icon btn-outline-primary" title="Custom Package" disabled><i class="fa-solid fa-puzzle-piece"></i> Custom</button>
                                    <?php else : ?>
                                        <div class="d-flex justify-content-end align-items-center gap-2">
                                            <!-- Form Extend -->
                                            <form id="customForm" action="<?= base_url('/web/detailreservation/addextend'); ?>/<?= esc($data['id']); ?>" method="post" onsubmit="checkRequired(event)" enctype="multipart/form-data">
                                                <?= csrf_field(); ?>
                                                <button type="submit" class="btn icon btn-outline-primary" title="Extend Package">
                                                    <i class="fa-solid fa-plus-square"></i> Extend
                                                </button>
                                            </form>

                                            <!-- Form Custom -->
                                            <form id="customizeForm" action="<?= base_url('/web/detailreservation/addcustompackage'); ?>/<?= esc($data['id']); ?>" method="post" onsubmit="checkRequired(event)" enctype="multipart/form-data">
                                                <?= csrf_field(); ?>
                                                <button type="submit" class="btn icon btn-outline-primary" title="Custom Package">
                                                    <i class="fa-solid fa-puzzle-piece"></i> Custom
                                                </button>
                                            </form>
                                        </div>
                                    <?php endif; ?>

                                <?php else : ?>
                                    <div class="d-flex justify-content-end align-items-center gap-2">
                                        <!-- Button Extend -->
                                        <button type="button" class="btn icon btn-outline-primary" title="Extend Package" onclick="redirectToLogin()">
                                            <i class="fa-solid fa-plus-square"></i> Extend
                                        </button>

                                        <!-- Button Custom -->
                                        <button type="button" class="btn icon btn-outline-primary" title="Custom Package" onclick="redirectToLogin()">
                                            <i class="fa-solid fa-puzzle-piece"></i> Custom
                                        </button>
                                    </div>
                                    <script>
                                        function redirectToLogin() {
                                            Swal.fire({
                                                icon: 'warning',
                                                title: 'You are not logged in',
                                                text: 'Please log in to proceed.',
                                                confirmButtonText: 'OK',
                                            }).then(() => {
                                                window.location.href = '<?= base_url('/login'); ?>';
                                            });
                                        }
                                    </script>
                                <?php endif; ?>
                            </div>
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
                                        <td class="fw-bold">Package Type</td>
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
                            <p class="fw-bold">Description</p>
                            <p><?= esc($data['description']);
                                ?></p>
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

            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="card-title">Package Review</h4>
                        </div>
                        <div class="col-6 text-end">
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addReviewModal">
                                <i class="fa fa-plus"></i> Add Review
                            </button>
                        </div>
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
                                            <form action="<?= base_url('web/package/updateComment/' . $review['id']) ?>" method="post">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editReviewModalLabel<?= esc($review['id']) ?>">Edit Review</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="id" value="<?= esc($data['id']) ?>">
                                                    <div class="mb-3">
                                                        <label for="edit_review_text<?= esc($review['id']) ?>" class="form-label">Review</label>
                                                        <textarea class="form-control" id="edit_review_text<?= esc($review['id']) ?>" name="review" rows="3" required><?= esc($review['review_text']) ?></textarea>
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
                                                        document.addEventListener('DOMContentLoaded', function() {
                                                            const stars = document.querySelectorAll('#edit-star-rating<?= esc($review['id']) ?> .fa-star');
                                                            const radios = document.querySelectorAll('#edit-star-rating<?= esc($review['id']) ?> input[type="radio"]');
                                                            let selected = <?= (int) $review['rating'] ?>;
                                                            stars.forEach((star, idx) => {
                                                                star.addEventListener('mouseenter', function() {
                                                                    for (let i = 0; i <= idx; i++) stars[i].classList.add('hovered');
                                                                });
                                                                star.addEventListener('mouseleave', function() {
                                                                    for (let i = 0; i < stars.length; i++) stars[i].classList.remove('hovered');
                                                                });
                                                                star.addEventListener('click', function() {
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
                                            <form action="<?= base_url('web/package/deleteComment/' . $review['id']) ?>" method="post">
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
                        <p class="text-center"><i>There are no reviews yet</i></p>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Add Review Modal -->
            <div class="modal fade" id="addReviewModal" tabindex="-1" aria-labelledby="addReviewModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="addReviewForm" action="<?= base_url('web/package/createComment') ?>" method="post" onsubmit="return validateReviewForm('addReviewForm')">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addReviewModalLabel">Add Review</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" value="<?= esc($data['id']) ?>">
                                <div class="mb-3">
                                    <label for="review_text" class="form-label">Review</label>
                                    <textarea class="form-control" id="review_text" name="review" rows="3" required></textarea>
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
                                <div id="review-error" class="text-danger" style="display:none;"></div>
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
                                    function validateReviewForm(formId) {
                                        var form = document.getElementById(formId);
                                        var ratingChecked = form.querySelector('input[name="rating"]:checked');
                                        var reviewText = form.querySelector('textarea[name="review"]').value.trim();
                                        var errorDiv = form.querySelector('#review-error');
                                        if (!ratingChecked || reviewText === '') {
                                            errorDiv.style.display = 'block';
                                            errorDiv.textContent = 'Please provide both a rating and review text.';
                                            return false;
                                        }
                                        errorDiv.style.display = 'none';
                                        return true;
                                    }
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const stars = document.querySelectorAll('#star-rating .fa-star');
                                        const radios = document.querySelectorAll('#star-rating input[type="radio"]');
                                        let selected = 0;
                                        stars.forEach((star, idx) => {
                                            star.addEventListener('mouseenter', function() {
                                                for (let i = 0; i <= idx; i++) stars[i].classList.add('hovered');
                                            });
                                            star.addEventListener('mouseleave', function() {
                                                for (let i = 0; i < stars.length; i++) stars[i].classList.remove('hovered');
                                            });
                                            star.addEventListener('click', function() {
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
</section>

<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script>
    const myModal = document.getElementById('videoModal');
    const videoSrc = document.getElementById('video-play').getAttribute('data-src');

    myModal.addEventListener('shown.bs.modal', () => {
        console.log(videoSrc);
        document.getElementById('video').setAttribute('src', videoSrc);
    });
    myModal.addEventListener('hide.bs.modal', () => {
        document.getElementById('video').setAttribute('src', '');
    });
</script>
<script>
    $('#direction-row').hide();
    $('#check-explore-col').hide();
    $('#result-explore-col').hide();
</script>
<?= $this->endSection() ?>