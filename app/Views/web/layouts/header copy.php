<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

<script>
    function loadCartTotal() {
        $.ajax({
            url: baseUrl + "/web/usercarttotal",
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                // Ubah konten pada elemen badge
                $('.cart-badge').text(response.data[0].total_cart);
            },
            error: function(error) {
                console.error('Error loading cart total:', error);
            }
        });
    }

    // Panggil fungsi saat dokumen sudah siap
    $(document).ready(function() {
        loadCartTotal();
    });
</script>

<!-- ... (bagian HTML lainnya) ... -->


<header class="mb-0">
    <a href="#" class="burger-btn d-block d-xl-none">
        <i class="bi bi-justify fs-3"></i>
    </a>
</header>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h4>Desa Wisata Sumpu</h4>
                <p class="text-subtitle text-muted">Tourism Village</p>
            </div>

            <div class="col-12 col-md-6 order-md-2 order-first mb-md-0 mb-3">
                <div class="float-end">
                    <?php if (logged_in()) : ?>
                        <div class="btn-group mb-1">
                            <div class="dropdown">
                                <a class="" role="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <div class="card mb-0">
                                        <div class="card-body py-3 px-4">
                                            <div class="d-flex align-items-center">
                                                <a href="<?= base_url('web/cart') ?>" class="btn btn-transparent me-3 position-relative" style="color: #435ebe;">
                                                    <i class="fas fa-shopping-cart"></i>
                                                    <script>
                                                        loadCartTotal();
                                                    </script>c <a href="<?= base_url('web/cart') ?>" class="btn btn-transparent me-3 position-relative" style="color: #435ebe;">
                                                    <i class="fas fa-shopping-cart"></i>
                                                    <script>
                                                        loadCartTotal();
                                                    </script>
                                                    <!-- <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge">

                                                    </span> -->
                                                </a>


                                                &nbsp;&nbsp;
                                                    <!-- <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge">

                                                    </span> -->
                                                </a>


                                                &nbsp;&nbsp;
                                                <div class="avatar avatar-lg me-0">
                                                    <img src="<?= base_url('media/photos/user'); ?>/<?= user()->user_image; ?>" alt="User Avatar" />
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="<?= base_url('web/profile'); ?>">My Profile</a>
                                    <a class="dropdown-item" href="<?= base_url('logout'); ?>">Log Out</a>
                                </div>
                            </div>
                        </div>
                    <?php else : ?>
                        <a href="<?= base_url('login'); ?>" class="btn btn-primary">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    setBaseUrl("<?= base_url(); ?>");
</script>