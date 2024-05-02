<?php
$uri = service('uri')->getSegments();
$edit = in_array('edit', $uri);
?>

<?= $this->extend('web/layouts/main'); ?>

<?= $this->section('styles') ?>
<link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/filepond-plugin-media-preview@1.0.11/dist/filepond-plugin-media-preview.min.css">
<link rel="stylesheet" href="<?= base_url('assets/css/pages/form-element-select.css'); ?>">
<link href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css'>

<script src='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js'>
</script>

<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'>
</script>

<style>
    * {
        margin: 0;
        padding: 0
    }

    html {
        height: 100%
    }

    h2 {
        color: #435ebe;
    }

    #form {
        text-align: left;
        position: relative;
        margin-top: 20px
    }

    #form fieldset {
        background: white;
        border: 0 none;
        border-radius: 0.5rem;
        box-sizing: border-box;
        width: 100%;
        margin: 0;
        padding-bottom: 20px;
        position: relative
    }

    .finish {
        text-align: center
    }

    #form fieldset:not(:first-of-type) {
        display: none
    }

    #form .previous-step,
    .next-step {
        width: 100px;
        font-weight: bold;
        color: white;
        border: 0 none;
        border-radius: 5px;
        cursor: pointer;
        padding: 10px 5px;
        margin: 10px 10px 10px 0px;
        float: right
    }

    .final-step {
        width: 100px;
        font-weight: bold;
        color: white;
        border: 0 none;
        border-radius: 5px;
        cursor: pointer;
        padding: 10px 5px;
        margin: 10px 10px 10px 0px;
        float: right
    }

    .form,
    .previous-step {
        background: #616161;
    }

    .form,
    .next-step {
        background: #435ebe;
    }

    .form,
    .final-step {
        background: #435ebe;
    }

    #form .previous-step:hover,
    #form .previous-step:focus {
        background-color: #000000
    }

    #form .next-step:hover,
    #form .next-step:focus {
        background-color: #435ebe
    }

    #form .final-step:hover,
    #form .final-step:focus {
        background-color: #435ebe
    }

    .text {
        color: #435ebe;
        font-weight: normal
    }

    #progressbar {
        margin-bottom: 30px;
        overflow: hidden;
        color: lightgrey
    }

    #progressbar .active {
        color: #435ebe
    }

    #progressbar li {
        list-style-type: none;
        font-size: 15px;
        width: 33%;
        float: left;
        position: relative;
        font-weight: 400
    }

    #progressbar #step1:before {
        content: "1"
    }

    #progressbar #step2:before {
        content: "2"
    }

    #progressbar #step3:before {
        content: "3"
    }

    #progressbar #step4:before {
        content: "4"
    }

    #progressbar li:before {
        width: 50px;
        height: 50px;
        line-height: 45px;
        display: block;
        font-size: 20px;
        color: #ffffff;
        background: lightgray;
        border-radius: 50%;
        margin: 0 auto 10px auto;
        padding: 2px
    }

    #progressbar li:after {
        content: '';
        width: 100%;
        height: 2px;
        background: lightgray;
        position: absolute;
        left: 0;
        top: 25px;
        z-index: -1
    }

    #progressbar li.active:before,
    #progressbar li.active:after {
        background: #435ebe
    }

    .progress {
        height: 20px
    }

    .progress-bar {
        background-color: #435ebe
    }

    /* Tambahkan style berikut di dalam tag <style> atau dalam file CSS terpisah */
    input[name="next-step"]:disabled {
        background-color: #d3d3d3;
        /* Warna abu-abu */
        cursor: not-allowed;
        /* Ganti kursor menjadi not-allowed saat tombol dinonaktifkan */
    }
</style>

<style>
    .filepond--root {
        width: 100%;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="row">
        <script>
            currentUrl = '<?= current_url(); ?>';
        </script>

        <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Reservation Guide</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <!-- start package guide -->
                    <div class="modal-body">
                        <b>Reservation of Tour Packages provided</b>
                        <li>Tourists choose a tour package from the input form (detailed package information can be seen on the tour package page)</li>
                        <li>Tourists fill out the reservation form for the desired tour package and/or homestay</li>
                        <br>
                        <b>Customized Tour Package Reservations</b>
                        <li>Tourists can select the 'custom package' button to create the desired package</li>
                        <li>Tourists are asked to fill in what activities, locations and services available in those activities</li>
                        <li>Tourists make a reservation by filling in the tour package reservation form</li>
                        <li>If tourists want to book a homestay, they can fill in the package reservation form</li>
                        <br>
                        <b>Homestay Reservation</b>
                        <li>Tourists can book accomodation or homestay on tour packages with a duration of more than 1 day or packages that include homestay.</li>
                        <li>There are two types of accommodation types that tourists can choose, namely Default and Custom.</li>
                        <li>The default accommodation type is a homestay accomodation that is free of charge because it is included in the tour package. Units are selected by the system to support equalization of reservations.</li>
                        <li>Custom accommodation type is Homestay accommodation that is subject to additional fees. Tourists can choose units as they wish.</li>
                        <br>
                        <b>Package Order</b>
                        <li>The minimum order quantity must meet the minimum capacity</li>
                        <li>If there is less than the minimum number of people then the price is calculated as 1 package</li>
                        <li>If there is more than the minimum number of 1 package, then if the additional <5 you pay plus half the package price, if>=5 you pay plus 1 package price, so for multiples of the minimum capacity</li>
                        <br>
                        <b>Reservation Payment</b>
                        <li>Tourists can choose the date and time to check in for the tour package</li>
                        <li>If you reserve a homestay, check-in and check-out of the homestay still starts at 12.00 noon </li>
                        <li>Tour package reservations can be submitted and then please wait for admin confirmation</li>
                        <li>If the admin approves, the deposit payment is 20% of the total reservation price and is paid a maximum of 2 days after the visit</li>
                        <li>Cancellations of reservations can be made up to 3 days after the visit, in this case the deposit paid will be returned</li>
                        <li>If cancellation is made after the 3rd day of the visit, the deposit will not be returned</li>
                        <li>Payment of the remainder of the deposit can be paid on the day of the tourist visit</li>
                    </div>
                    <!-- end package guide -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- reservation card -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="px-0 pt-4 pb-0 mt-3 mb-3" style="padding-top: 0!;">
                        <form id="form">

                            <ul id="progressbar" style="text-align: center;">
                                <li class="active" id="step1"><strong>Step 1</strong></li>
                                <li id="step2"><strong>Step 2</strong></li>
                                <li id="step3"><strong>Finish</strong></li>
                            </ul>
                            <br>
                            <div class="card-body">


                                <fieldset>
                                    <div class="row">
                                        <div class="row" style="margin:15px;justify-content:center;">
                                            <h4 class="card-title text-center">Package Reservation</h4>
                                            <div col=col-auto>
                                                <br>
                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#infoModal" data-bs-whatever="@getbootstrap"><i class="fa fa-info"></i><i>Read this guide</i></button>
                                                <br>
                                                <input type="checkbox" id="readCheckbox" name="readCheckbox" required>
                                                <label for="readCheckbox"><i>I have read this guide</i></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="card">

                                                <div class="card-body" style="text-align: left;">

                                                    <?= csrf_field();  ?>
                                                    <div class="form-group">
                                                        <label for="package">Package</label>
                                                        <input type="text" id="package" class="form-control" name="package" value="<?= esc($datapackage['id']); ?>" hidden>
                                                        <input type="text" id="name" class="form-control" name="name" value="<?= esc($datapackage['name']); ?>" required autocomplete="off" readonly>
                                                    </div>
                                                    <div class="row g-4">
                                                        <div class="col-md-7">
                                                            <label for="input_min_capacity">Minimal Capacity</label>
                                                            <input type="number" id="input_min_capacity" name="input_min_capacity" readonly value="<?= esc($datapackage['min_capacity']); ?>" class="form-control" required>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <label for="input_days">Day Activities</label>
                                                            <input type="number" id="days" name="days" readonly value="<?= esc($datapackage['days']); ?>" class="form-control" min="1" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="input_price">Price Package</label>
                                                        <input type="number" id="input_price" name="input_price" readonly value="<?= esc($datapackage['price']); ?>" class="form-control" required>
                                                    </div>
                                                    <div class="row g-4">
                                                        <div class="col-md-7">
                                                            <label for="total_people">Total People</label>
                                                            <input type="number" id="total_people" name="total_people" class="form-control" min="1" required>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <label for="input_item">Package Order</label>
                                                            <input type="number" id="item" name="item" class="form-control" min="1" readonly required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="total_price">Total Price Package</label>
                                                        <input type="number" id="total_price" name="total_price" class="form-control" value="<?= ($edit) ? $datapackage['total_price'] : old('total_price'); ?>" readonly>
                                                    </div>
                                                    <br>
                                                    <br>

                                                    <div class="accordion" id="accordionExample">
                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header" id="headingOne">
                                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                                                    Package Include
                                                                </button>
                                                            </h2>
                                                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                                <div class="accordion-body">
                                                                    <div class="row">
                                                                        <div class="col">
                                                                            <?php foreach ($serviceinclude as $ls) : ?>
                                                                                <li><?= esc($ls['name']); ?></li>
                                                                            <?php endforeach; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header" id="headingTwo">
                                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                                    Package Exclude
                                                                </button>
                                                            </h2>
                                                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                                                <div class="accordion-body">
                                                                    <div class="row">
                                                                        <div class="col">
                                                                            <?php foreach ($serviceexclude as $ls) : ?>
                                                                                <li><?= esc($ls['name']); ?></li>
                                                                            <?php endforeach; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header" id="headingThree">
                                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                                    Package Activity
                                                                </button>
                                                            </h2>
                                                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                                                <div class="accordion-body">
                                                                    <p>
                                                                        <?php foreach ($day as $d) : ?>
                                                                            <b>Day <?= esc($d['day']); ?></b><br>
                                                                            <?php foreach ($activity as $ac) : ?>
                                                                                <?php if ($d['day'] == $ac['day']) : ?>
                                                                                    <?= esc($ac['activity']); ?>. <?= esc($ac['name']); ?> : <?= esc($ac['description']); ?> <br>
                                                                                <?php endif; ?>
                                                                            <?php endforeach; ?>
                                                                            <br>
                                                                        <?php endforeach; ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <br>


                                                </div>
                                            </div>

                                        </div>


                                        <div class="col-md-6 col-12">
                                            <div class="card">

                                                <div class="card-body" style="text-align: left;">

                                                    <?= csrf_field();  ?>
                                                    <div class="row g-4">
                                                        <div class="col-md-7">
                                                            <label for="check_in">Check-in</label>
                                                            <input type="date" id="check_in" name="check_in" min="<?= date('Y-m-d', strtotime('+2 days')); ?>" class="form-control" required onfocus="this.min='<?= date('Y-m-d', strtotime('+2 days')); ?>'">
                                                        </div>
                                                        <div class="col-md-5">
                                                            <label for="check_in"></label>
                                                            <input type="time" id="time_check_in" name="time_check_in" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="row g-4">
                                                        <div class="col-md-7">
                                                            <label for="check_out">Check-out</label>
                                                            <input readonly type="date" id="check_out" name="check_out" class="form-control" required>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <label for="check_out"></label>
                                                            <input readonly type="time" id="time_check_out" name="time_check_out" class="form-control" required>
                                                        </div>
                                                    </div>
                                                   


                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Your Step 1 Form Fields and HTML Here -->
                                    <!-- <input type="button" name="previous-step" class="form-control" value="Previous" /> -->
                                    <input type="button" name="next-step" class="next-step" value="Next" />
                                    <input type="text" id="total_price_2" name="total_price_2" readonly class="form-control" style="font-weight: bold; color:black; background-color: transparent; border: 0px; width: unset !important; float: right; font-size: larger; text-align: right; margin-top: 12px; " min="1" required>

                                </fieldset>
                                <fieldset>

                                    <div class="row">
                                        <div class="row" style="margin:15px;justify-content:center;">
                                            <h4 class="card-title text-center">Reservation Preview</h4>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <!-- <h4 class="card-title text-center"><?= $title; ?></h4> -->
                                                </div>
                                                <div class="card-body" style="text-align: left;">
                                                    <div col=col-auto>
                                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#infoModal" data-bs-whatever="@getbootstrap"><i class="fa fa-info"></i><i>Read this guide</i></button>
                                                        <br>
                                                        <input type="checkbox" id="readCheckbox2" name="readCheckbox2" required>
                                                        <label for="readCheckbox"><i>I have read this guide</i></label>
                                                        <br> <br>
                                                    </div>
                                                    <?= csrf_field();  ?>
                                                    <div class="form-group">
                                                        <label for="step3_package">Package</label>
                                                        <input type="text" id="step3_package" class="form-control" name="step3_package" value="<?= esc($datapackage['id']); ?>" hidden>
                                                        <input type="text" id="step3_name" class="form-control" name="step3_name" value="<?= esc($datapackage['name']); ?>" required autocomplete="off" readonly>
                                                    </div>
                                                    <div class="row g-4">
                                                        <div class="col-md-7">
                                                            <label for="step3_input_min_capacity">Minimal Capacity</label>
                                                            <input type="number" id="step3_input_min_capacity" name="step3_input_min_capacity" readonly value="<?= esc($datapackage['min_capacity']); ?>" class="form-control" required>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <label for="step3_input_days">Day Activities</label>
                                                            <input type="number" id="step3_days" name="step3_days" readonly value="<?= esc($datapackage['days']); ?>" class="form-control" min="1" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="step3_input_price">Price Package</label>
                                                        <input type="number" id="step3_input_price" name="step3_input_price" readonly value="<?= esc($datapackage['price']); ?>" class="form-control" required>
                                                    </div>
                                                    <div class="row g-4">
                                                        <div class="col-md-7">
                                                            <label for="step3_total_people">Total People</label>
                                                            <input type="number" id="step3_total_people" name="step3_total_people" class="form-control" min="1" required readonly>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <label for="step3_input_item">Package Order</label>
                                                            <input type="number" id="step3_item" name="step3_item" class="form-control" min="1" readonly required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="step3_total_price">Total Price Package</label>
                                                        <input type="number" id="step3_total_price" name="step3_total_price" class="form-control" value="<?= ($edit) ? $datapackage['total_price'] : old('total_price'); ?>" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="step3_deposit">Total Deposit</label>
                                                        <input type="number" id="step3_deposit" name="step3_deposit" class="form-control" value="<?= ($edit) ? $datapackage['deposit'] : old('deposit'); ?>" readonly>
                                                    </div>
                                                    
                                                    <br>
                                                    <br>

                                                    <div class="accordion" id="accordionExample">
                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header" id="headingOne">
                                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                                                    Package Include
                                                                </button>
                                                            </h2>
                                                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                                <div class="accordion-body">
                                                                    <div class="row">
                                                                        <div class="col">
                                                                            <?php foreach ($serviceinclude as $ls) : ?>
                                                                                <li><?= esc($ls['name']); ?></li>
                                                                            <?php endforeach; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header" id="headingTwo">
                                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                                    Package Exclude
                                                                </button>
                                                            </h2>
                                                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                                                <div class="accordion-body">
                                                                    <div class="row">
                                                                        <div class="col">
                                                                            <?php foreach ($serviceexclude as $ls) : ?>
                                                                                <li><?= esc($ls['name']); ?></li>
                                                                            <?php endforeach; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header" id="headingThree">
                                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                                    Package Activity
                                                                </button>
                                                            </h2>
                                                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                                                <div class="accordion-body">
                                                                    <p>
                                                                        <?php foreach ($day as $d) : ?>
                                                                            <b>Day <?= esc($d['day']); ?></b><br>
                                                                            <?php foreach ($activity as $ac) : ?>
                                                                                <?php if ($d['day'] == $ac['day']) : ?>
                                                                                    <?= esc($ac['activity']); ?>. <?= esc($ac['name']); ?> : <?= esc($ac['description']); ?> <br>
                                                                                <?php endif; ?>
                                                                            <?php endforeach; ?>
                                                                            <br>
                                                                        <?php endforeach; ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <br>


                                                </div>
                                            </div>

                                        </div>


                                        <div class="col-md-6 col-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <!-- <h4 class="card-title text-center">Select Date</h4> -->
                                                </div>
                                                <div class="card-body" style="text-align: left;">
                                                    <div col=col-auto>
                                                        <br>
                                                        <br> <br>
                                                    </div>
                                                    <?= csrf_field();  ?>
                                                    <div class="row g-4">
                                                        <div class="col-md-7">
                                                            <label for="step3_check_in">Check-in</label>
                                                            <input type="date" id="step3_check_in" name="step3_check_in" min="<?php echo date('Y-m-d'); ?>" class="form-control" required readonly>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <label for="step3_check_in"></label>
                                                            <input type="time" id="step3_time_check_in" name="step3_time_check_in" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" class="form-control" required readonly>
                                                        </div>
                                                    </div>
                                                    <div class="row g-4">
                                                        <div class="col-md-7">
                                                            <label for="step3_check_out">Check-out</label>
                                                            <input readonly type="date" id="step3_check_out" name="step3_check_out" class="form-control" required>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <label for="step3_check_out"></label>
                                                            <input readonly type="time" id="step3_time_check_out" name="step3_time_check_out" class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group mb-2">
                                                        <label for="note" class="mb-2">Note</label>
                                                        <textarea class="form-control" id="note" name="note" placeholder="Make requests that you want to be on the reservation record, such as the proposed food menu and price range of the package" required rows="4"><?= ($edit) ? $data['note'] : old('note'); ?></textarea>
                                                    </div>

                                                   
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="card">
                                            <div class="form-group">

                                                <!-- total total -->
                                                <br><br>
                                                <div class="row g-3 align-items-center" style=" justify-content: right;">
                                                    <div class="col-auto">
                                                        <label for="total_total_package" class="col-form-label">Total Package:</label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="text" id="total_total_package" class="form-control" readonly style="text-align:right; color: black; background-color: transparent; border: none;">
                                                    </div>
                                                </div>
                                                <div class="row g-3 align-items-center" style=" justify-content: right;">
                                                    <div class="col-auto">
                                                        <label for="total_total_reservation" class="col-form-label">Total Reservation</label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <!-- <input type="number" id="total_total_reservation" class="form-control" readonly style="text-align:right; color: black; background-color: transparent; border: none;"> -->
                                                        <input type="text" id="total_total_reservation" readonly class="form-control" style="font-weight:bold; font-size: x-large; text-align:right; color:black; background-color: transparent; border: 0px;" min="1" required>
                                                    </div>
                                                </div>
                                                <!-- end total total -->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Your Step 3 Form Fields and HTML Here -->
                                    <input type="button" name="final-step" class="final-step" value="Submit" onclick="createReservationSingle()" />
                                    <input type="button" name="previous-step" class="previous-step" value="Previous" />
                                    <!-- <input type="text" id="total_price_5" name="total_price_5" readonly class="form-control" style="color:black; background-color: transparent; border: 0px; width: unset !important; float: right; font-size: larger; text-align: right; margin-top: 12px; " min="1" required> -->

                                </fieldset>
                                <fieldset>

                                    <div class="form-card" style="text-align: center;margin: 50px;">
                                        <h2 class="fs-title text-center">Success!</h2>
                                        <br><br>
                                        <div class="row justify-content-center">
                                            <div class="col-3">
                                                <img src="https://img.icons8.com/color/96/000000/ok--v2.png" class="fit-image">
                                            </div>
                                        </div>
                                        <br><br>
                                        <div class="row justify-content-center">
                                            <div class="col-7 text-center">
                                                <h5>Your Reservation Has Been Successfully Booked</h5>
                                                <a class="btn btn-primary" href="<?= base_url('/web/reservation'); ?>" class="sidebar-link">
                                                    <span>My Reservation</span>
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- Your Step 4 Form Fields and HTML Here -->
                                    <!-- <a type="button" name="next-step" class="next-step" value="Finish" href="<?= base_url('/web/reservation'); ?>"/>Finish</a> -->
                                    <!-- <input type="button" name="previous-step" class="previous-step" value="Previous" /> -->
                                </fieldset>
                                <!-- <fieldset>
                                    <div class="finish">
                                        <h2 class="text text-center">
                                            <strong>FINISHED</strong>
                                        </h2>
                                    </div>
                                    <input type="button" name="previous-step" class="previous-step" value="Previous" />
                                </fieldset> -->

                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script>
            $(document).ready(function() {
                var currentGfgStep, nextGfgStep, previousGfgStep;
                var opacity;
                var current = 1;
                var steps = $("fieldset").length;

                setProgressBar(current);

                $('html, body').animate({
                    scrollTop: 0
                }, 500);

                $(".next-step").click(function() {
                    currentGfgStep = $(this).parent();
                    nextGfgStep = $(this).parent().next();

                    $("#progressbar li").eq($("fieldset").index(nextGfgStep)).addClass("active");

                    nextGfgStep.show();
                    currentGfgStep.animate({
                        opacity: 0
                    }, {
                        step: function(now) {
                            opacity = 1 - now;

                            currentGfgStep.css({
                                'display': 'none',
                                'position': 'relative'
                            });
                            nextGfgStep.css({
                                'opacity': opacity
                            });
                        },
                        duration: 500
                    });
                    setProgressBar(++current);

                    $('html, body').animate({
                        scrollTop: 0
                    }, 500);
                });

                $(".final-step").click(function() {
                    currentGfgStep = $(this).parent();
                    nextGfgStep = $(this).parent().next();

                    $("#progressbar li").eq($("fieldset").index(nextGfgStep)).addClass("active");

                    nextGfgStep.show();
                    currentGfgStep.animate({
                        opacity: 0
                    }, {
                        step: function(now) {
                            opacity = 1 - now;

                            currentGfgStep.css({
                                'display': 'none',
                                'position': 'relative'
                            });
                            nextGfgStep.css({
                                'opacity': opacity
                            });
                        },
                        duration: 500
                    });
                    setProgressBar(++current);

                   
                });

                $(".previous-step").click(function() {
                    currentGfgStep = $(this).parent();
                    previousGfgStep = $(this).parent().prev();

                    $("#progressbar li").eq($("fieldset").index(currentGfgStep)).removeClass("active");

                    previousGfgStep.show();

                    currentGfgStep.animate({
                        opacity: 0
                    }, {
                        step: function(now) {
                            opacity = 1 - now;

                            currentGfgStep.css({
                                'display': 'none',
                                'position': 'relative'
                            });
                            previousGfgStep.css({
                                'opacity': opacity
                            });
                        },
                        duration: 500
                    });
                    setProgressBar(--current);

                    $('html, body').animate({
                        scrollTop: 0
                    }, 500);
                });

                function setProgressBar(currentStep) {
                    var percent = parseFloat(100 / steps) * current;
                    percent = percent.toFixed();
                    $(".progress-bar").css("width", percent + "%");
                }

                $(".submit").click(function() {
                    return false;
                });
            });
        </script>


        <!-- end reservation card -->

    </div>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



</section>

<script>
    // Dapatkan elemen input tanggal
    var checkInInput = document.getElementById('check_in');

    // Dapatkan tanggal sekarang
    var currentDate = new Date();

    // Tambahkan 2 hari ke tanggal sekarang
    currentDate.setDate(currentDate.getDate() + 1);

    // Konversi tanggal menjadi format 'YYYY-MM-DD'
    var minDate = currentDate.toISOString().split('T')[0];

    // Atur nilai minimum pada elemen input tanggal
    checkInInput.setAttribute('min', minDate);
</script>

<script>
    // Memantau perubahan pada readCheckbox
    document.getElementById('readCheckbox').addEventListener('change', function() {
        // Mengatur properti checked dari readCheckbox2 sesuai dengan nilai checked dari readCheckbox
        document.getElementById('readCheckbox2').checked = this.checked;
    });
    // Dapatkan elemen-elemen input yang diperlukan
    var totalPeopleInput = document.getElementById('total_people');
    var checkInInput = document.getElementById('check_in');
    var timeCheckInInput = document.getElementById('time_check_in');
    var readCheckbox = document.getElementById('readCheckbox');

    // Dapatkan elemen tombol "Next"
    var nextButton = document.querySelector('input[name="next-step"]');

    // Tambahkan event listener untuk setiap perubahan pada input fields
    totalPeopleInput.addEventListener('input', checkInputs);
    checkInInput.addEventListener('input', checkInputs);
    timeCheckInInput.addEventListener('input', checkInputs);
    readCheckbox.addEventListener('change', checkInputs);

    // Fungsi untuk memeriksa nilai input dan mengaktifkan/nonaktifkan tombol "Next"
    function checkInputs() {
        var totalPeopleValue = totalPeopleInput.value;
        var checkInValue = checkInInput.value;
        var timeCheckInValue = timeCheckInInput.value;

        // Ganti kondisi di bawah sesuai kebutuhan Anda
        if (totalPeopleValue !== '' && checkInValue !== '' && timeCheckInValue !== '' && readCheckbox.checked) {
            nextButton.removeAttribute('disabled');
        } else {
            nextButton.setAttribute('disabled', 'disabled');
        }
    }

    // Panggil fungsi untuk memastikan status tombol "Next" pada halaman awal
    checkInputs();
</script>

<script>
    $(document).ready(function() {
        var maxUnits = 2; // Set the maximum number of units

        $('input[name="selected_units[]"]').on('change', function() {
            var selectedUnits = $('input[name="selected_units[]"]:checked').length;

            if (selectedUnits >= maxUnits) {
                // Disable unchecked checkboxes
                $('input[name="selected_units[]"]:not(:checked)').prop('disabled', true);
            } else {
                // Enable all checkboxes
                $('input[name="selected_units[]"]').prop('disabled', false);
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#check_in, #time_check_in').change(function() {
            calculateCheckOut();
        });
    });

    function calculateCheckOut() {
        const checkInDate = $('#check_in').val();
        const checkInTime = $('#time_check_in').val();
        const daysTime = $('#days').val();

        // Misalnya, kita ingin menambahkan 2 hari dari check-in
        const daysToAdd = daysTime - 1; // Ganti dengan durasi yang diinginkan
        const hoursToAdd = 2;

        if (checkInDate && checkInTime) {
            const checkInDateTime = new Date(checkInDate + 'T' + checkInTime);
            const checkOutDateTime = new Date(checkInDateTime);

            checkOutDateTime.setDate(checkOutDateTime.getDate() + daysToAdd);
            checkOutDateTime.setHours(checkOutDateTime.getHours() + hoursToAdd);

            const checkOutDate = checkOutDateTime.toISOString().split('T')[0];
            const checkOutTime = checkOutDateTime.toTimeString().split(' ')[0];

            if (daysTime == '1' && checkInTime < '18:00:00') {
                $('#check_out').val(checkOutDate);
                $('#time_check_out').val('18:00:00');
                $('#step3_check_in').val(checkInDate);
                $('#step3_time_check_in').val(checkInTime);
                $('#step3_check_out').val(checkOutDate);
                $('#step3_time_check_out').val('18:00:00');
            } else if (daysTime == '1' && checkInTime > '18:00:00') {
                $('#check_out').val(checkOutDate);
                $('#time_check_out').val('12:00:00');
                $('#step3_check_in').val(checkInDate);
                $('#step3_time_check_in').val(checkInTime);
                $('#step3_check_out').val(checkOutDate);
                $('#step3_time_check_out').val('12:00:00');
            } else if (daysTime > '1') {
                $('#check_out').val(checkOutDate);
                $('#time_check_out').val('12:00:00');
                $('#step3_check_in').val(checkInDate);
                $('#step3_time_check_in').val(checkInTime);
                $('#step3_check_out').val(checkOutDate);
                $('#step3_time_check_out').val('12:00:00');
            }

        }
    }


    $(document).ready(function() {
        $('#total_people').change(function() {
            calculateTotalPrice();
        });
    });

     function calculateTotalPrice() {
        const price = parseFloat($('#input_price').val());
        const capacity = parseInt($('#input_min_capacity').val());
        const totalPeople = parseInt($('#total_people').val());
        const totalPeople2 = parseInt($('#total_price_2').val());
        const totalPeople3 = parseInt($('#total_price_3').val());
        const totalPeople5 = parseInt($('#total_price_5').val());
        const totalPeople4 = parseInt($('#total_price_4').val());

        const numberOfPackages = Math.floor(totalPeople / capacity);
        const remainder = totalPeople % capacity;

        if (numberOfPackages !== 0) {
            let add = 0;
            if (remainder !== 0 && remainder < 5) {
                add = 0.5;
            } else if (remainder >= 5) {
                add = 1;
            }

            const order = numberOfPackages + add;
            $('#item').val(order);
            $('#step3_item').val(order);
            $('#step3_total_people').val(totalPeople);

            const totalPrice = price * order;
            const formattedPrice = 'Total: Rp' + totalPrice.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            const formattedPrice2 = 'Rp' + totalPrice.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            $('#total_price').val(totalPrice);
            $('#total_total_package').val(formattedPrice2);
            $('#total_total_reservation').val(formattedPrice2);
            $('#step3_total_price').val(totalPrice);
            $('#total_price_2').val(formattedPrice);
            $('#total_price_3').val(formattedPrice);
            $('#total_price_5').val(formattedPrice);
            $('#total_price_4').val(totalPrice);

            const deposit = totalPrice * 0.2;
            // $('#deposit').val(deposit);
            $('#step3_deposit').val(deposit);
            // $('#total_total_deposit').val(deposit);
        } else {
            const order = 1;
            $('#item').val(order);
            $('#step3_item').val(order);
            $('#step3_total_people').val(totalPeople);

            const totalPrice = price * order;
            const formattedPrice = 'Total: Rp' + totalPrice.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            const formattedPrice2 = 'Rp' + totalPrice.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            $('#total_price').val(totalPrice);
            $('#total_total_package').val(formattedPrice2);
            $('#total_total_reservation').val(formattedPrice2);
            $('#step3_total_price').val(totalPrice);
            $('#total_price_2').val(formattedPrice);
            $('#total_price_3').val(formattedPrice);
            $('#total_price_5').val(formattedPrice);
            $('#total_price_4').val(totalPrice);

            const deposit = totalPrice * 0.2;
            // $('#deposit').val(deposit);
            $('#step3_deposit').val(deposit);
            // $('#total_total_deposit').val(deposit);


        }


    }
</script>

<script>
    $(document).ready(function() {
        // Saat nilai input #total_people berubah
        $("#total_people").on("input", function() {
            // Ambil nilai dari #total_people
            var totalPeopleValue = $(this).val();

            // Setel nilai dari #days dengan nilai yang sama
            $("#total_people_2").val(totalPeopleValue);
        });


    });
</script>


<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-resize/dist/filepond-plugin-image-resize.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script src="https://cdn.jsdelivr.net/npm/filepond-plugin-media-preview@1.0.11/dist/filepond-plugin-media-preview.min.js"></script>
<script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
<script src="<?= base_url('assets/js/extensions/form-element-select.js'); ?>"></script>

<?= $this->endSection() ?>