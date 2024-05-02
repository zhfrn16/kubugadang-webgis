<?php
$uri = service('uri')->getSegments();
$uri1 = $uri[1] ?? 'index';
$uri2 = $uri[2] ?? '';
$uri3 = $uri[3] ?? '';
?>

<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <!-- Sidebar Header -->
        <?= $this->include('web/layouts/sidebar_header'); ?>

        <!-- Sidebar -->
        <div class="sidebar-menu">
            <div class="d-flex flex-column">

                <?php if (logged_in()) : ?>
                    <div class="d-flex justify-content-center avatar avatar-xl me-3" id="avatar-sidebar">
                        <img src="<?= base_url('media/photos/pesona_sumpu.png'); ?>" alt="" srcset="">
                    </div>
                    <div class="p-2 text-center">
                        <?php if (!empty(user()->fullname)) : ?>
                            Hello, <span class="fw-bold"><?= user()->fullname; ?></span> <br> <span class="text-muted mb-0">@<?= user()->username; ?></span>
                        <?php else : ?>
                            Hello, <span class="fw-bold">@<?= user()->username; ?></span>
                        <?php endif; ?>
                    </div>
                <?php else : ?>
                    <div class="d-flex justify-content-center avatar avatar-xl me-3" id="avatar-sidebar">
                        <img src="<?= base_url('media/photos/pesona_sumpu.png'); ?>" alt="" srcset="">
                    </div>
                    <div class="p-2 d-flex justify-content-center">Hello, Visitor</div>
                <?php endif; ?>

                <ul class="menu">

                    <li class="sidebar-item <?= ($uri1 == 'index') ? 'active' : '' ?>">
                        <a href="/web" class="sidebar-link">
                            <i class="fa-solid fa-house"></i><span>Home</span>
                        </a>
                    </li>


                    <!-- <li class="sidebar-item <?= ($uri1 == 'sumpur') ? 'active' : '' ?>">
                        <a href="<?= base_url('/web/sumpur'); ?>" class="sidebar-link">
                            <i class="fa-solid fa-map"></i><span>Explore Sumpur</span>
                        </a>
                    </li> -->

                    <!-- <li class="sidebar-item <?= ($uri1 == 'explore') ? 'active' : '' ?>">
                        <a href="<?= base_url('/web/explore'); ?>" class="sidebar-link">
                            <i class="fa-solid fa-map"></i><span>Explore Village</span>
                        </a>
                    </li> -->

                    <!-- Explore Package-->
                    <li class="sidebar-item <?= ($uri1 == 'explore' or $uri1 == 'mypackage') ? 'active' : '' ?> has-sub">
                        <a href="" class="sidebar-link">
                            <i class="fa-solid fa-map"></i><span>Explore Village</span>
                        </a>

                        <ul class="submenu <?= ($uri1 == 'explore' or $uri1 == 'mypackage') ? 'active' : '' ?>">
                            <!-- List Our Package -->
                            <li class="submenu-item" id="ep-list">
                                <a href="<?= base_url('/web/explore'); ?>"><i class="fa-solid fa-list me-3"></i>With Our Package</a>
                            </li>
                            <!-- List My Package -->
                            <li class="submenu-item" id="ep-list2">
                                <a href="<?= base_url('/web/mypackage'); ?>"><i class="fa-solid fa-list me-3"></i>With My Package</a>
                            </li>

                        </ul>
                    </li>


                    <!-- Package -->
                    <li class="sidebar-item <?= ($uri1 == 'package') ? 'active' : '' ?>">
                        <a href="<?= base_url('/web/package'); ?>" class="sidebar-link">
                            <i class="fa-solid fa-square-poll-horizontal"></i><span>Tourism Package<span>
                        </a>
                    </li>



                    <?php if (logged_in() && !in_groups(['admin'])) : ?>
                        <li class="sidebar-item <?= ($uri1 == 'reservation') || ($uri1 == 'detailreservation') ? 'active' : '' ?>">
                            <a href="<?= base_url('/web/reservation'); ?>" class="sidebar-link">
                                <i class="fa-solid fa-calendar"></i><span>My Reservation</span>
                            </a>
                        </li>
                    <?php endif; ?>


                    <?php if (in_groups(['admin']) || in_groups(['master'])) : ?>
                        <li class="sidebar-item">
                            <a href="<?= base_url('dashboard/sumpu'); ?>" class="sidebar-link">
                                <i class="bi bi-grid-fill"></i><span>Dashboard</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="sidebar-item">
                        <div class="d-flex justify-content-around">
                            <a href="https://www.instagram.com/pesonasumpu/" class="sidebar-link" target="_blank">
                                <i class="fa-brands fa-instagram"></i><span>Instagram</span>
                            </a>
                            <a href="https://www.tiktok.com/@pesonasumpu2" class="sidebar-link" target="_blank">
                                <i class="fa-brands fa-tiktok"></i><span>TikTok</span>
                            </a>
                        </div>
                    </li>

                </ul>
            </div>
        </div>
        <a href="https://wa.me/+6289519771656" target="_blank" rel="noopener noreferrer">
            <img width="48" height="48" src="https://img.icons8.com/color/48/whatsapp--v1.png" alt="Chat via WhatsApp" title="Chat via WhatsApp to us" style="position: fixed; bottom: 25px; right: 25px; width: 60px; height: 60px;">
        </a>

    </div>
</div>