<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Desa Wisata Kubu Gadang</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="" name="keywords" />
    <meta content="" name="description" />

    <!-- Favicon -->
    <link href="<?= base_url('media/icon/favicon.svg'); ?>" rel="icon" />

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500&family=Quicksand:wght@600;700&display=swap" rel="stylesheet" />

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />

    <!-- Calendar -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@event-calendar/build@4.4.0/dist/event-calendar.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@event-calendar/build@4.4.0/dist/event-calendar.min.js"></script>


    <!-- Libraries Stylesheet -->
    <link href="<?= base_url('assets/lib/animate/animate.min.css'); ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/lib/lightbox/css/lightbox.min.css'); ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/lib/owlcarousel/assets/owl.carousel.min.css'); ?>" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="<?= base_url('css/landing-page/bootstrap.min.css'); ?>" rel="stylesheet" />

    <!-- Template Stylesheet -->
    <link href="<?= base_url('css/landing-page/style.css'); ?>" rel="stylesheet" />
    <link rel="stylesheet" href="<?= base_url('css/web.css'); ?>">

    <!-- Third Party CSS and JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/de7d18ea4d.js" crossorigin="anonymous"></script>

    <!-- Google Maps API and Custom JS -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB8B04MTIk7abJDVESr6SUF6f3Hgt1DPAY&libraries=drawing"></script>
    <script src="<?= base_url('js/web.js'); ?>"></script>
</head>

<body>

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
            <img class="img-fluid me-3" src="<?= base_url('media/icon/logo.svg'); ?>" alt="Icon" />
            <h1 class="m-0 text-primary">Tourism Village</h1>
        </a>
        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse py-4 py-lg-0" id="navbarCollapse">
            <div class="navbar-nav ms-auto">
                <a href="#home" class="nav-item nav-link active">Home</a>
                <a href="#about" class="nav-item nav-link">About</a>
                <a href="/web/package" class="nav-item nav-link">Package</a>
                <a href="#award" class="nav-item nav-link">Award</a>
            </div>
            <?php if (!logged_in()) : ?>
                <a href="<?= base_url('login'); ?>" class="btn btn-primary">Login</a>
            <?php else : ?>
                <div class="col-1">
                    <?php if (logged_in()) : ?>
                        <div class="btn-group mb-1">
                            <div class="dropdown">
                                <a class="" role="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <div class="avatar avatar-md">
                                        <?php
                                        $userImage = user()->user_image;
                                        $imageSrc = (strpos($userImage, 'http') === 0) ? $userImage : base_url('media/photos/user') . '/' . $userImage;
                                        ?>
                                        <img style="width:30px;" src="<?= $imageSrc; ?>" alt="Face 1" />
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
            <?php endif; ?>
        </div>
    </nav>
    <!-- Navbar End -->

    <!-- Header Start -->
    <div class="container-fluid bg-dark p-0 mb-5" id="home">
        <div class="row g-0 flex-column-reverse flex-lg-row">
            <div class="col-lg-6 p-0 wow fadeIn" data-wow-delay="0.1s">
                <div class="header-bg h-100 d-flex flex-column justify-content-center p-5">
                    <h2 class="display-6 text-light mb-2">
                        Welcome to
                    </h2>
                    <h1 class="display-4 text-light mb-5">
                        Desa Wisata<br>Kubu Gadang<br>
                    </h1>
                    <h3 class="display-9 text-light mb-1"> -Your Hometown In West Sumatera-</h3>
                    <div class="d-flex align-items-center pt-4 animated slideInDown">
                        <a href="/web" class="btn btn-primary py-sm-3 px-3 px-sm-5 me-5">Explore</a>
                        <!-- <a href="/web" class="btn btn-primary py-sm-3 px-3 px-sm-5 me-5"
                        >Explore Ulakan</a
                        > -->
                        <button type="button" class="btn-play" data-bs-toggle="modal" data-src="<?= base_url('media/videos/landing_page.mp4'); ?>" data-bs-target="#videoModal">
                            <span></span>
                        </button>
                        <h6 class="text-white m-0 ms-4 d-none d-sm-block">Watch Video</h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                <div class="owl-carousel header-carousel">
                    <div class="owl-carousel-item">
                        <img class="img-fluid" src="<?= base_url('media/photos/landing-page/desakuga1.jpg'); ?>" alt="" />
                    </div>
                    <div class="owl-carousel-item">
                        <img class="img-fluid" src="<?= base_url('media/photos/landing-page/sileklanyah.jpg'); ?>" alt="" />
                    </div>
                    <div class="owl-carousel-item">
                        <img class="img-fluid" src="<?= base_url('media/photos/landing-page/desakuga2.jpg'); ?>" alt="" />
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


    <!-- About Start -->
    <div class="container-xxl py-5" id="about">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <p><span class="text-primary me-2">#</span>Welcome To Desa Wisata Desa Wisata Kubu Gadang</p>
                    <h1 class="display-5 mb-4">
                        Why You Should Visit
                        Desa Wisata <br><span class="text-primary">Kubu Gadang</span>
                    </h1>
                    <p class="mb-4">
                        Kubu Gadang Tourism Village is one of the Community Based Tourism in West Sumatra Province located on Jalan Haji Miskin, Ekor Lubuk Village, Padang Panjang City. Kubu Gadang was pioneered as a Tourism Village since 2014. Kubu Gadang tourist village is a refreshing and romantic place to evoke nostalgia for beautiful memories in your hometown. Kubu Gadang Tourism Village has a variety of natural and cultural potentials that are packaged in various educational programs with activities that provide learning and experience for tourists. Kubu Gadang Tourism Village has been visited by domestic and foreign tourists and has won various provincial and national awards.</p>
                    <h5 class="mb-3">
                        <a href="#map" class="text-reset" onclick="showMap('aLSA');">
                            <i class="far fa-check-circle text-primary me-3"></i>Cultural Heritage
                        </a>
                    </h5>
                    <h5 class="mb-3">
                        <a href="#map" class="text-reset" onclick="showMap('aNT');">
                            <i class="far fa-check-circle text-primary me-3"></i>Local Empowerment
                        </a>
                    </h5>
                    <h5 class="mb-3">
                        <a href="#map" class="text-reset" onclick="showMap('aCT');">
                            <i class="far fa-check-circle text-primary me-3"></i>Sustainable Tourism Village Certification
                        </a>
                    </h5>
                    <h5 class="mb-3">
                        <a href="#map" class="text-reset" onclick="showMap('aET');">
                            <i class="far fa-check-circle text-primary me-3"></i>Experiental Learning
                        </a>
                    </h5>
                    <h5 class="mb-3">
                        <a href="#map" class="text-reset" onclick="showMap('cp');">
                            <i class="far fa-check-circle text-primary me-3"></i>Natural Tourism, Cultural Tourism, Artificial Tourism
                        </a>
                    </h5>
                    <a class="btn btn-primary py-3 px-5 mt-3" href="/web">Explore</a>
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="img-border">
                        <img class="img-fluid right" src="<?= base_url('media/photos/landing-page/kuga22.jpg'); ?>" alt="" />
                    </div>
                </div>
            </div>

            <div class="row p-5" id="map">
                <div class="mb-3">
                    <a class="btn btn-outline-danger float-end" onclick="closeMap();"><i class="fa-solid fa-xmark"></i></a>
                </div>
                <div class="col-lg-6 wow fadeInUp googlemaps" data-wow-delay="0.5s" id="googlemaps">
                    <script>
                        initMap9();
                    </script>
                    <div id="legend"></div>
                    <script>
                        $('#legend').hide();
                        getLegend();
                    </script>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->

    <!-- About Geopark Start -->
    <div class="container-xxl py-5" id="about-lake">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <p><span class="text-primary me-2">#</span>Unique Tourism</p>
                    <h1 class="display-5 mb-4">
                        <span class="text-primary">Silek Lanyah</span>
                    </h1>
                    <p class="mb-4">
                        When visiting Kubu Gadang Village, visitors will be able to enjoy the beauty of nature and culture. Silek Lanyah Kubu Gadang is a unique traditional Minangkabau martial art performed on muddy fields, usually in rice fields after harvest. This tour is unique because it is not only a sport or performing art but also acts as a means of moral and social education. Silek Lanyah is performed by three generations, namely children, teenagers and adults and is the original community of Kubu Gadang.
                    </p>

                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="img-border">
                        <img class="img-fluid right" src="<?= base_url('media/photos/landing-page/sileklanyahbg.jpg'); ?>" alt="" />
                    </div>
                </div>
            </div>


        </div>
    </div>
    <!-- About Geopark End -->

    <!-- Carousel Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-5" id="ec">
            </div>
        </div>
    </div>
    <!-- Carousel End -->

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

    <!-- Footer Start -->
    <div class="container-fluid footer bg-dark text-light footer mt-5 pt-5 wow fadeIn" data-wow-delay="0.1s">
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
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a class="border-bottom" href="#">M. Hadi Zahfran</a>, All
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
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="<?= base_url('assets/lib/wow/wow.min.js'); ?>"></script>
    <script src="<?= base_url('assets/lib/easing/easing.min.js'); ?>"></script>
    <script src="<?= base_url('assets/lib/waypoints/waypoints.min.js'); ?>"></script>
    <script src="<?= base_url('assets/lib/counterup/counterup.min.js'); ?>"></script>
    <script src="<?= base_url('assets/lib/owlcarousel/owl.carousel.min.js'); ?>"></script>
    <script src="<?= base_url('assets/lib/lightbox/js/lightbox.min.js'); ?>"></script>

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
            } else if (category == 'hp') {
                URI = URI + '/historicalPlace'
            } else if (category == 'ev') {
                URI = URI + '/event'
            } else if (category == 'aLSA') {
                URI = URI + '/attractionLSA'
            } else if (category == 'aNT') {
                URI = URI + '/attractionNT'
            } else if (category == 'aCT') {
                URI = URI + '/attractionCT'
            } else if (category == 'aET') {
                URI = URI + '/attractionET'
            } else if (category == 'cp') {
                URI = URI + '/culinaryPlace'
            } else if (category == 'wp') {
                URI = URI + '/worshipPlace'
            } else if (category == 'sp') {
                URI = URI + '/souvenirPlace'
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
                        objectMarker(item.id, item.lat, item.lng);
                    }
                    boundToObject();

                }
            })
        }
        const ec = EventCalendar.create(document.getElementById('ec'), {
            view: 'dayGridMonth',
            headerToolbar: {
                start: 'prev,next today',
                center: 'title',
            },
            scrollTime: '09:00:00',
            events: createEvents(),
            dayMaxEvents: true,
            nowIndicator: true,
            selectable: true
        });

        function createEvents() {
            let days = [];
            // for (let i = 0; i < 7; ++i) {
            //     let day = new Date();
            //     let diff = i - day.getDay();
            //     day.setDate(day.getDate() + diff);
            //     days[i] = day.getFullYear() + "-" + _pad(day.getMonth()+1) + "-" + _pad(day.getDate());
            // }

            let events = [];
            $.ajax({
                url: '<?= base_url('/api/event') ?>',
                dataType: 'json',
                async: false,
                success: function(response) {
                    if (response && response.data) {
                        console.log(response.data);
                        events = response.data.map(function(item) {
                            return {
                                start: item.event_start,
                                end: item.event_end,
                                title: item.name,
                                color: item.color || "#779ECB"
                            };
                        });
                    }
                }
            });
            return events;

            // return [
            //     {start: days[0] + " 00:00", end: days[0] + " 09:00", resourceId: 1, display: "background"},
            //     {start: days[1] + " 12:00", end: days[1] + " 14:00", resourceId: 2, display: "background"},
            //     {start: days[2] + " 17:00", end: days[2] + " 24:00", resourceId: 1, display: "background"},
            //     {start: days[0] + " 10:00", end: days[0] + " 14:00", resourceId: 1, title: "The calendar can display background and regular events", color: "#FE6B64"},
            //     {start: days[1] + " 16:00", end: days[2] + " 08:00", resourceId: 2, title: "An event may span to another day", color: "#B29DD9"},
            //     {start: days[2] + " 09:00", end: days[2] + " 13:00", resourceId: 2, title: "Events can be assigned to resources and the calendar has the resources view built-in", color: "#779ECB"},
            //     {start: days[3] + " 14:00", end: days[3] + " 20:00", resourceId: 1, title: "", color: "#FE6B64"},
            //     {start: days[3] + " 15:00", end: days[3] + " 18:00", resourceId: 1, title: "Overlapping events are positioned properly", color: "#779ECB"},
            //     {start: days[5] + " 10:00", end: days[5] + " 16:00", resourceId: 2, title: {html: "You have complete control over the <i><b>display</b></i> of events…"}, color: "#779ECB"},
            //     {start: days[5] + " 14:00", end: days[5] + " 19:00", resourceId: 2, title: "…and you can drag and drop the events!", color: "#FE6B64"},
            //     {start: days[5] + " 18:00", end: days[5] + " 21:00", resourceId: 2, title: "", color: "#B29DD9"},
            //     {start: days[1], end: days[3], resourceId: 1, title: "All-day events can be displayed at the top", color: "#B29DD9", allDay: true}
            // ];
        }

        function _pad(num) {
            let norm = Math.floor(Math.abs(num));
            return (norm < 10 ? '0' : '') + norm;
        }
    </script>

</body>

</html>