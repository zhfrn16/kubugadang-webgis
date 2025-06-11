<!DOCTYPE html>
<?php $uri = service('uri')->getSegments(); ?>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= esc($title); ?> - Desa Wisata Desa Wisata Kubu Gadang</title>

    <!-- Favicon -->
    <link href="media/icon/favicon.svg" rel="icon" />

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500&family=Quicksand:wght@600;700&display=swap" rel="stylesheet" />

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />

    <!-- Libraries Stylesheet -->
    <link href="<?= base_url('assets/lib/animate/animate.min.css'); ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/lib/lightbox/css/lightbox.min.css'); ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/lib/owlcarousel/assets/owl.carousel.min.css'); ?>" rel="stylesheet" />
    <script src="<?= base_url('assets/js/extensions/sweetalert2.js'); ?>"></script>


    <!-- Customized Bootstrap Stylesheet -->
    <link href="<?= base_url('css/landing-page/bootstrap.min.css'); ?>" rel="stylesheet" />

    <!-- Template Stylesheet -->
    <link href="<?= base_url('css/landing-page/style.css'); ?>" rel="stylesheet" />
    <link rel="stylesheet" href="<?= base_url('assets/css/main/app.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/pages/auth.css'); ?>">
</head>

<body>
    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top py-lg-0 px-4 px-lg-5 wow fadeIn" data-wow-delay="0.1s">
        <a href="/" class="navbar-brand p-0">
            <img class="img-fluid me-3" src="<?= base_url('media/icon/logo.svg'); ?>" alt="Icon" />
            <h2 class="m-0 text-primary">Tourism Village</h2>
        </a>
        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse py-4 py-lg-0" id="navbarCollapse">
            <div class="navbar-nav ms-auto">
                <a href="<?= base_url('web'); ?>" class="nav-item nav-link">Explore</a>
                <a href="<?= base_url(); ?>" class="nav-item nav-link">About</a>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->

    <!-- Auth Content -->
    <div id="auth">
        <?= $this->renderSection('content'); ?>
    </div>
    <!-- End Auth Content -->

    <div class="row justify-content-center align-items-center m-0" style="background-color: #2d499d">
        <div class="col">
            <p class="text-center text-white"><?= date('Y');
                                                ?> &copy; Rivonny Wulandari</p>
        </div>
    </div>
  
</body>

</html>