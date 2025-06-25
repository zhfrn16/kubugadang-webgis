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
                <div class="d-flex justify-content-center avatar avatar-xl me-3" id="avatar-sidebar">
                    <img src="<?= base_url('media/photos/logo_kuga.jpg'); ?>" alt="" srcset="">
                </div>

                <?php if (logged_in()) : ?>
                    <div class="p-2 text-center">
                        <?php if (!empty(user()->fullname)) : ?>
                            Hello, <span class="fw-bold"><?= user()->fullname; ?></span> <br> <span class="fw-bold">@<?= user()->username; ?></span>
                        <?php else : ?>
                            Hello, <span class="fw-bold">@<?= user()->username; ?></span>
                        <?php endif; ?>
                    </div>
                <?php else : ?>
                    <div class="p-2 d-flex justify-content-center">Hello, Visitor</div>
                <?php endif; ?>

                <ul class="menu">
                    <li class="sidebar-item">
                        <a href="<?= base_url('web'); ?>" class="sidebar-link">
                            <i class="fa-solid fa-house"></i><span> Home</span>
                        </a>
                    </li>



                    <!-- <?php if (in_groups(['admin']) || in_groups(['master'])) :
                            ?>
                        <li class="sidebar-item <?= ($uri1 == 'gtp') ? 'active' : ''
                                                ?>">
                            <a href="<?= base_url('dashboard/gtp');
                                        ?>" class="sidebar-link">
                                <i class="fa-brands fa-pagelines"></i><span>Manage GTP</span>
                            </a>
                        </li>
                    <?php endif;
                    ?> -->

                    <?php if (in_groups(['admin']) || in_groups(['master'])) :
                    ?>
                        <li class="sidebar-item has-sub">
                            <a href="" class="sidebar-link">
                                <i class="fa-brands fa-pagelines"></i><span>Manage Village</span>
                            </a>
                            <ul class="submenu <?= ($uri1 == 'sumpu' || $uri1 == 'announcement') ? 'active' : '' ?>">
                                <!-- List Package -->
                                <li class="submenu-item <?= ($uri1 == 'sumpu') ? 'active' : '' ?>" id="pa-list">
                                    <a href="<?= base_url('dashboard/sumpu'); ?>"><i class="fa-brands fa-pagelines"></i> Data Village</a>
                                </li>
                                <!-- List Package type-->
                                <li class="submenu-item <?= ($uri1 == 'announcement') ? 'active' : '' ?>" id="pa-list">
                                    <a href="<?= base_url('dashboard/announcement'); ?>"><i class="fa-solid fa-scroll"></i> Announcement</a>
                                </li>
                            </ul>
                        </li>
                    <?php endif;
                    ?>


                    <!-- <?php if (in_groups(['admin']) || in_groups(['master'])) :
                            ?>
                        <li class="sidebar-item <?= ($uri1 == 'attraction') ? 'active' : ''
                                                ?>">
                            <a href="<?= base_url('dashboard/attraction');
                                        ?>" class="sidebar-link">
                                <i class="fa-solid fa-star"></i><span>Manage Attraction</span>
                            </a>
                        </li>
                    <?php endif;
                    ?> -->

                    <!-- <?php if (in_groups(['admin']) || in_groups(['master'])) : ?>
                        <li class="sidebar-item <?= ($uri1 == 'event') ? 'active' : '' ?>">
                            <a href="<?= base_url('dashboard/event'); ?>" class="sidebar-link">
                                <i class="fa-solid fa-bullhorn"></i><span>Manage Event</span>
                            </a>
                        </li>
                    <?php endif; ?> -->



                    <?php if (in_groups(['admin']) || in_groups(['master'])) :
                    ?>
                        <li class="sidebar-item has-sub">
                            <a href="" class="sidebar-link">
                                <i class="fa-solid fa-square-poll-horizontal"></i><span>Manage Package</span>
                            </a>
                            <ul class="submenu <?= ($uri1 == 'package' || $uri1 == 'packagetype' || $uri1 == 'servicepackage') ? 'active' : '' ?>">
                                <!-- List Package -->
                                <li class="submenu-item <?= ($uri1 == 'package') ? 'active' : '' ?>" id="pa-list">
                                    <a href="<?= base_url('dashboard/package'); ?>"><i class="fa-solid fa-square-poll-horizontal"></i> Data Package</a>
                                </li>
                                <!-- List Package type-->
                                <li class="submenu-item <?= ($uri1 == 'packagetype') ? 'active' : '' ?>" id="pa-list">
                                    <a href="<?= base_url('dashboard/packagetype'); ?>"><i class="fa-solid fa-puzzle-piece"></i> Package Type</a>
                                </li>
                                <!-- List Service Package -->
                                <li class="submenu-item <?= ($uri1 == 'servicepackage') ? 'active' : '' ?>" id="pa-list">
                                    <a href="<?= base_url('dashboard/servicepackage'); ?>"><i class="fa-solid fa-puzzle-piece"></i> Service Package</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item <?= ($uri1 == 'homestay') ? 'active' : '' ?>">
                            <a href="<?= base_url('dashboard/homestay'); ?>" class="sidebar-link">
                                <i class="fa-solid fa-bed"></i><span>Manage Homestay</span>
                            </a>
                        </li>
                        <li class="sidebar-item <?= ($uri1 == 'event') ? 'active' : '' ?>">
                            <a href="<?= base_url('dashboard/event'); ?>" class="sidebar-link">
                                <i class="fa-solid fa-bed"></i><span>Manage Event</span>
                            </a>
                        </li>
                        <li class="sidebar-item <?= ($uri1 == 'worshipPlace') ? 'active' : '' ?>">
                            <a href="<?= base_url('dashboard/worshipPlace'); ?>" class="sidebar-link">
                                <i class="fa-solid fa-bed"></i><span>Manage Worship</span>
                            </a>
                        </li>
                        <li class="sidebar-item <?= ($uri1 == 'souvenirPlace') ? 'active' : '' ?>">
                            <a href="<?= base_url('dashboard/souvenirPlace'); ?>" class="sidebar-link">
                                <i class="fa-solid fa-bed"></i><span>Manage Souvenir</span>
                            </a>
                        </li>
                        <li class="sidebar-item <?= ($uri1 == 'culinaryPlace') ? 'active' : '' ?>">
                            <a href="<?= base_url('dashboard/culinaryPlace'); ?>" class="sidebar-link">
                                <i class="fa-solid fa-bed"></i><span>Manage Culinary</span>
                            </a>
                        </li>
                        <li class="sidebar-item <?= ($uri1 == 'attraction') ? 'active' : '' ?>">
                            <a href="<?= base_url('dashboard/attraction'); ?>" class="sidebar-link">
                                <i class="fa-solid fa-bed"></i><span>Manage Attraction</span>
                            </a>
                        </li>

                    <?php endif;
                    ?>


                    <?php if (in_groups(['admin']) || in_groups(['master'])) : ?>

                    <?php endif; ?>

                    <?php if (in_groups(['master'])) :
                    ?>
                        <li class="sidebar-item <?= ($uri1 == 'users') ? 'active' : ''
                                                ?>">
                            <a href="<?= base_url('dashboard/users');
                                        ?>" class="sidebar-link">
                                <i class="fa fa-users"></i><span>Manage Users</span>
                            </a>
                        </li>
                    <?php endif;
                    ?>

                </ul>
            </div>

        </div>
    </div>
</div>