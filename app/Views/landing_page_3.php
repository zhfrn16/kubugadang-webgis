<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Tourism Village</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="" name="keywords" />
    <meta content="" name="description" />

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
    <link href="assets/lib/animate/animate.min.css" rel="stylesheet" />
    <link href="assets/lib/lightbox/css/lightbox.min.css" rel="stylesheet" />
    <link href="assets/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/landing-page/bootstrap.min.css" rel="stylesheet" />

    <!-- Template Stylesheet -->
    <link href="css/landing-page/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?= base_url('css/web.css'); ?>">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

    <!-- Third Party CSS and JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggles = document.querySelectorAll('.toggle-caption-btn');

            toggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const caption = this.closest('.carousel-caption');
                    caption.classList.toggle('expanded');

                    if (caption.classList.contains('expanded')) {
                        this.classList.remove('fa-chevron-up');
                        this.classList.add('fa-chevron-down');
                    } else {
                        this.classList.remove('fa-chevron-down');
                        this.classList.add('fa-chevron-up');
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            var owl = $('.header-carousel');

            owl.owlCarousel({
                items: 1,
                loop: true,
                autoplay: true,
                autoplayTimeout: 10000, // 10 detik
                smartSpeed: 1000,
                dots: false,
                nav: false,
                autoplayHoverPause: true
            });

            // 🔥 Paksa set interval ulang jika konflik dari luar
            owl.trigger('play.owl.autoplay', [10000]);

            $('.carousel-control.prev').click(function() {
                owl.trigger('prev.owl.carousel');
            });

            $('.carousel-control.next').click(function() {
                owl.trigger('next.owl.carousel');
            });
        });
    </script>




    <script src="https://kit.fontawesome.com/de7d18ea4d.js" crossorigin="anonymous"></script>

    <!-- Google Maps API and Custom JS -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB8B04MTIk7abJDVESr6SUF6f3Hgt1DPAY&libraries=drawing"></script>
    <script src="<?= base_url('js/web.js'); ?>"></script>
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        #home {
            height: 92%;
            /* Mengatur tinggi elemen agar sesuai dengan tinggi viewport */
        }

        .card {
            height: 100%;
            /* Mengatur kartu agar memenuhi elemen parent */
            border: none;
            /* Opsional: Hilangkan border jika tidak diperlukan */
        }

        .owl-carousel-item {
            position: relative;
            width: 100%;
            /* Make sure it takes the full width */
            height: 620px;
            /* Set a fixed height or adjust as needed */
            overflow: hidden;
            /* Hide overflow when the image zooms */
        }

        .owl-carousel-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* This ensures the image covers the entire div without distortion */
        }

        .carousel-caption {
            position: absolute;
            bottom: 20%;
            left: 2%;
            right: 2%;
            bottom: 12%;
            z-index: 2;
            background: rgba(0, 0, 0, 0.6);
            padding: 15px 20px;
            border-radius: 10px;
            overflow: hidden;
            transition: 0.5s ease;
            max-height: 100px;
            /* saat collapse */
        }

        /* Saat expanded: diperbesar dan bisa discroll */
        .carousel-caption.expanded {
            max-height: 350px;
            /* lebih tinggi dari sebelumnya */
            overflow-y: auto;
            /* aktifkan scroll isi */
        }

        /* Tambahan: scroll bar agar terlihat halus */
        .carousel-caption.expanded::-webkit-scrollbar {
            width: 6px;
        }

        .carousel-caption.expanded::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.5);
            border-radius: 4px;
        }

        /* Judul */
        .carousel-caption h5 {
            font-size: 24px;
            color: white;
            margin-bottom: 8px;
        }

        /* Deskripsi */
        .carousel-caption .caption-text {
            font-size: 20px;
            color: white;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: all 0.3s ease;
        }

        /* Saat expanded: tampilkan semua */
        .carousel-caption.expanded .caption-text {
            -webkit-line-clamp: unset;
            display: block;
            overflow: visible;
        }

        .header-carousel {
            height: 100% !important;
        }

        .header-carousel .owl-stage-outer,
        .header-carousel .owl-stage {
            height: 100% !important;
        }

        .header-carousel .owl-item {
            height: 100% !important;
        }

        /* Sesuaikan tinggi owl-carousel-item dengan container parent */
        .header-carousel .owl-carousel-item {
            height: 100% !important;
            min-height: 65vh;
        }

        /* Pastikan gambar mengisi penuh container */
        .header-carousel .owl-carousel-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            min-height: 92vh;
            border: 5px solid rgb(0, 0, 0);
            /* warna bingkai (putih) */
            padding: 3px;
            /* ruang antara gambar dan bingkai */
            background-color: rgb(255, 255, 255);
            /* warna latar belakang bingkai */
        }


        /* Responsive adjustments */
        @media (max-width: 991.98px) {

            .header-carousel .owl-carousel-item,
            .header-carousel .owl-carousel-item img {
                min-height: 50vh;
            }
        }

        @media (max-width: 767.98px) {

            .header-carousel .owl-carousel-item,
            .header-carousel .owl-carousel-item img {
                min-height: 40vh;
            }
        }

        .carousel-wrapper {
            position: relative;
        }

        .carousel-control {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            background-color: rgba(0, 0, 0, 0.4);
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            font-size: 18px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .carousel-control:hover {
            background-color: rgba(0, 0, 0, 0.7);
        }

        .carousel-control.prev {
            left: 10px;
        }

        .carousel-control.next {
            right: 10px;
        }

        .owl-nav {
            display: none !important;
        }
    </style>

    </style>
</head>

<body class="text-dark">

    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top py-lg-0 px-4 px-lg-5 wow fadeIn" data-wow-delay="0.1s">
        <a href="/" class="navbar-brand p-0">
            <img class="img-fluid me-3" src="media/icon/logo.svg" alt="Icon" />
            <h1 class="m-0 text-primary">Tourism Village</h1>
        </a>
        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse py-4 py-lg-0" id="navbarCollapse">
            <div class="navbar-nav ms-auto">

                <a href="#home" class="nav-item nav-link active fw-bold" style="font-size: 20px;">Home</a>
                <?php if ($village != null) : ?>
                    <a href="/web" class="nav-item nav-link fw-bold text-dark" style="font-size: 20px;">Explore</a>
                    <a href="#about" class="nav-item nav-link fw-bold text-dark" style="font-size: 20px;">About</a>
                    <a href="#award" class="nav-item nav-link fw-bold text-dark" style="font-size: 20px;">Award</a>
                <?php endif; ?>
            </div>
            <?php if (!logged_in()) : ?>
                <a href="<?= base_url('login'); ?>" class="btn btn-primary" style="font-size: 20px;">Login</a>
            <?php endif; ?>
        </div>
    </nav>
    <!-- Navbar End -->

    <?php if ($village != null) : ?>
        <!-- Header Start -->
        <div class="container-fluid bg-dark p-0 mb-5" id="home">
            <div class="row g-0 flex-column-reverse flex-lg-row" style="height: 100%;">
                <div class="col-lg-6 p-0 wow fadeIn" data-wow-delay="0.1s">
                    <div class="card">
                        <div class="header-bg h-100 d-flex flex-column justify-content-center p-5" style="background: linear-gradient(rgba(0, 0, 0, .7), rgba(0, 0, 0, .7)), url(media/photos/desakuga3.jpg) center center no-repeat; background-size: cover;">
                            <h2 class="display-6 text-light mb-2">
                                Welcome to
                            </h2>
                            <h1 class="display-4 text-light mb-5">
                                <?= $village['name']; ?>
                            </h1>
                            <h3 class="display-9 text-light mb-1"> -Your Hometown In West Sumatera-</h3>
                            <div class="d-flex align-items-center pt-4 animated slideInDown">
                                <a href="/web" class="btn btn-primary py-sm-3 px-3 px-sm-5 me-5" style="font-size: 20px;">Explore</a>
                                <button type="button" class="btn-play" data-bs-toggle="modal" data-src="<?= base_url('media/videos/video_kuga.MOV'); ?>" data-bs-target="#videoModal">
                                    <span></span>
                                </button>
                                <h6 class="text-white m-0 ms-4 d-none d-sm-block" style="font-size: 20px;">Watch Video</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 p-0 wow fadeIn" data-wow-delay="0.5s">
                    <div class="carousel-wrapper position-relative">
                        <!-- Tombol Previous -->
                        <button class="carousel-control prev">
                            <i class="fas fa-chevron-left"></i>
                        </button>

                        <!-- Tombol Next -->
                        <button class="carousel-control next">
                            <i class="fas fa-chevron-right"></i>
                        </button>

                        <div class="owl-carousel header-carousel h-100" id="header-carousel">
                            <?php foreach ($gallery as $item) : ?>
                                <div class="owl-carousel-item">
                                    <img src="media/landing-page-img/<?= $item['url']; ?>" alt="<?= $item['title']; ?>" class="w-100 h-100 object-fit-cover" />
                                    <div class="carousel-caption collapsed">
                                        <div class="toggle-arrow text-end">
                                            <i class="fas fa-chevron-up toggle-caption-btn" style="cursor: pointer;"></i>
                                        </div>
                                        <h5><?= $item['title']; ?></h5>
                                        <p class="caption-text">
                                            <?= $item['description']; ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Header End -->

        <!-- Video Modal Start -->
        <div class="modal modal-video fade" id="videoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <h3 class="modal-title" id="exampleModalLabel">Video</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- 16:9 aspect ratio -->
                        <div class="ratio ratio-16x9">
                            <video src="" class="embed-responsive-item" id="video" controls autoplay>Sorry, your browser doesn't support embedded videos</video>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Video Modal End -->

    <!-- Award Start -->
    <div class="container-xxl bg-primary facts my-5 py-5 wow fadeInUp" data-wow-delay="0.1s" id="award">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-md-3 col-lg-3 text-center wow fadeIn" data-wow-delay="0.1s">
                    <img src="media/photos/landing-page/trophy.png" alt="" style="filter: invert(100%); max-width: 4em" class="mb-3">
                    <p class="text-white mb-0">Desa Terbaik Di Sumbar</p>
                    <p class="text-white mb-0">GIPI AWARD 2021</p>
                </div>
                <div class="col-md-3 col-lg-3 text-center wow fadeIn" data-wow-delay="0.2s">
                    <img src="media/photos/landing-page/trophy.png" alt="" style="filter: invert(100%); max-width: 4em" class="mb-3">
                    <p class="text-white mb-0">Desa Wisata</p>
                    <p class="text-white mb-0">Berkelanjutan</p>
                </div>
                <div class="col-md-3 col-lg-3 text-center wow fadeIn" data-wow-delay="0.3s">
                    <img src="media/photos/landing-page/trophy.png" alt="" style="filter: invert(100%); max-width: 4em" class="mb-3">
                    <h1 class="text-white mb-2">100</h1>
                    <p class="text-white mb-0">Besar</p>
                    <p class="text-white mb-0">ADWI</p>
                </div>
                <div class="col-md-3 col-lg-3 text-center wow fadeIn" data-wow-delay="0.3s">
                    <img src="media/photos/landing-page/trophy.png" alt="" style="filter: invert(100%); max-width: 4em" class="mb-3">
                    <p class="text-white mb-0">Top</p>
                    <h1 class="text-white mb-2">8</h1>
                    <p class="text-white mb-0">Desa Wisata Termaju</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Award End -->
    <?php else: ?>
        <div class="container-fluid bg-dark p-0 mb-5" id="home">
            <div class="card">
                <div class="card-body d-flex justify-content-center align-items-center">
                    <h3 class="text-center">The app has not been set up</h3>
                </div>
            </div>
        </div>
    <?php endif; ?>


    <!-- Footer Start -->
    <div class="container-fluid footer bg-dark text-light footer mt-5 pt-5 wow fadeIn" data-wow-delay="0.1s">
        <?php if ($village != null) : ?>
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-9 col-md-6">
                        <h5 class="text-light mb-4">Address</h5>
                        <p class="mb-2">
                            <i class="fa fa-map-marker-alt me-3"></i> Jalan Haji Miskin Kelurahan Ekor Lubuk, Kecamatan Padang Panjang Timur, Kota Padang Panjang, Provinsi Sumatera Barat
                        </p>
                        <p class="mb-2">
                            <i class="fa fa-phone-alt me-3"></i> +62 812 7515 1074
                        </p>
                        <p class="mb-2">
                            <i class="fa fa-envelope me-3"></i> desawisatakubugadang@gmail.com
                        </p>
                        <div class="d-flex pt-2">
                            <a class="btn btn-outline-light btn-social" href="https://www.instagram.com/desawisatakubugadang/"><i class="fab fa-instagram"></i></a>
                            <a class="btn btn-outline-light btn-social" href="https://www.facebook.com/desawisatakubugadang/"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-outline-light btn-social" href="https://www.youtube.com/@Desawisatakubugadang"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>


                    <div class="col-lg-3 col-md-6">
                        <h5 class="text-light mb-4">Links</h5>
                        <a class="btn btn-link" href="#home">Home</a>
                        <a class="btn btn-link" href="/web">Explore</a>
                        <a class="btn btn-link" href="#about">About</a>
                        <a class="btn btn-link" href="#award">Award</a>
                        <a class="btn btn-link" href="<?= base_url('login'); ?>">Login</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a class="border-bottom" href="#">Muhammad Hadi Zahfran</a>, All
                        Right Reserved.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="assets/lib/wow/wow.min.js"></script>
    <script src="assets/lib/easing/easing.min.js"></script>
    <script src="assets/lib/waypoints/waypoints.min.js"></script>
    <script src="assets/lib/counterup/counterup.min.js"></script>
    <script src="assets/lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="assets/lib/lightbox/js/lightbox.min.js"></script>

    <script>
        window.onload = function() {
            // Get the current title
            let title = document.title;

            // Split the title based on the ' - ' separator
            let parts = title.split(" - ");

            $.ajax({
                url: baseUrl + "/api/touristVillage/",
                dataType: "json",
                success: function(response) {
                    let data = response.data;
                    let name = data.name;

                    parts[1] = name;
                    document.title = parts.join(" - ");

                },
            });
        };
    </script>

    <!-- Template Javascript -->
    <script src="<?= base_url('js/landing-page.js'); ?>"></script>
    <script>
        $('#map').hide();

        function closeMap() {
            $('#map').hide();
        }
    </script>
    <script>
        function showMap(category = null) {
            if ($('#map').hide()) {
                $('#map').show();
            }

            let URI = "<?= base_url('api') ?>";
            clearMarker();
            clearRadius();
            clearRoute();
            if (category == 'rg') {
                URI = URI + '/rumahGadang'
            } else if (category == 'uat') {
                URI = URI + '/uAttraction'
            } else if (category == 'at') {
                URI = URI + '/attraction'
            } else if (category == 'hs') {
                URI = URI + '/homestay'
            } else if (category == 'ev') {
                URI = URI + '/event'
            } else if (category == 'cp') {
                URI = URI + '/culinaryPlace'
            } else if (category == 'wp') {
                URI = URI + '/worshipPlace'
            } else if (category == 'sp') {
                URI = URI + '/souvenirPlace'
            } else if (category == 'sv') {
                URI = URI + '/serviceProvider'
            }

            currentUrl = '';
            $.ajax({
                url: URI,
                dataType: 'json',
                success: function(response) {
                    let data = response.data
                    for (i in data) {
                        let item = data[i];
                        currentUrl = currentUrl + item.id;
                        if (item.id.substring(0, 1) === "A") {
                            objectMarker(item.id, item.lat, item.lng, true, item.attraction_category);
                        } else {
                            objectMarker(item.id, item.lat, item.lng);
                        }
                    }
                    boundToObject();
                }
            })
        }
        $('#header-carousel').trigger('play.owl.autoplay', [10000]);
    </script>
</body>

</html>