<div class="col-md-auto" style="padding-right: 0px !important; padding-left: 0px !important;">
    <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Current Location" class="btn icon btn-primary mx-1" id="current-position" onclick="currentPosition();">
        <span class="material-symbols-outlined">my_location</span>
    </a>
    <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Set Manual Location" class="btn icon btn-primary mx-1" id="manual-position" onclick="manualPosition();">
        <span class="material-symbols-outlined">pin_drop</span>
    </a>
    <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="View Legend" class="btn icon btn-primary mx-1" id="legend-map" onclick="viewLegend();">
        <span class="material-symbols-outlined">visibility</span>
    </a>

    <?php
    $currentURL = $_SERVER['REQUEST_URI'];
    if ($currentURL === '/web' || $currentURL === '/web/explore' || $currentURL === '/web/mypackage') {
        // Jika URL adalah '/web', tampilkan tombol
        echo '<a data-bs-toggle="tooltip" data-bs-placement="bottom" title="How to Reach Sumpu" class="btn icon btn-primary mx-1" id="go-to" onclick="howToReachSumpu()">
        <i style="height:1.72rem;width:1.5rem" class="fa-solid fa-person-walking-luggage"></i>
    </a>';
    }
    ?>

    <?php
    $currentURL = $_SERVER['REQUEST_URI'];
    if ($currentURL === '/web' || $currentURL === '/web/explore' || $currentURL === '/web/mypackage') {
        // Jika URL adalah '/web', tampilkan tombol
        echo '<a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Zoom to Sumpu Village" class="btn icon btn-primary mx-1" id="go-to" onclick="zoomToSumpuMarkers()">
        <i style="height:1.72rem;width:1.5rem"  class="fa-solid fa-location-arrow"></i>
        </a>';
    }
    ?>


    <!-- <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="All Object" class="btn icon btn-primary mx-1" id="go-to" onclick="clickExplore()">
        <i style="height:1.72rem;width:1.5rem" class="fa-solid fa-layer-group"></i>
    </a> -->

    <?php
    $currentURL = $_SERVER['REQUEST_URI'];
    if ($currentURL === '/web' || $currentURL === '/web/explore' || $currentURL === '/web/mypackage') {
        // Jika URL adalah '/web', tampilkan tombol
        echo '<div class="btn-group mx-1">
        <button style="height:2.8rem"  class="btn btn-primary" type="button"  data-bs-toggle="tooltip" data-bs-placement="bottom" title="View All Layers" onclick="clickLayer()"><i style="height:1.72rem;width:1.5rem" class="fa-solid fa-layer-group"></i></button>
        <button style="height:2.8rem" class="btn btn-primary dropdown-toggle dropdown-toggle-split" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="visually-hidden">Toggle Dropdown</span>       
        </button>
        <ul class="dropdown-menu p-2">            
            <li>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="check-oco" value="co" onchange="checkLayer()">
                    <label class="form-check-label" for="check-oco">Country</label>
                </div>
            </li>
            <li>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="check-opr" value="pr" onchange="checkLayer()">
                    <label class="form-check-label" for="check-opr">Province</label>
                </div>
            </li>
            <li>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="check-ore" value="re" onchange="checkLayer()">
                    <label class="form-check-label" for="check-ore">Regency/City</label>
                </div>
            </li>
            <li>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="check-odi" value="di" onchange="checkLayer()">
                    <label class="form-check-label" for="check-odi">District</label>
                </div>
            </li>
            <li>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="check-ovi" value="vi" onchange="checkLayer()">
                    <label class="form-check-label" for="check-ovi">Village</label>
                </div>
            </li>
            <li>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="check-oto" value="to" onchange="checkLayer()">
                    <label class="form-check-label" for="check-oto">Tourism Village</label>
                </div>
            </li>
        </ul>

    </div>';
    }
    ?>

    <!-- <li>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="click-explore" onchange="clickExplore()">
            <label class="form-check-label" for="check-oco">All Tourism Objects</label>
        </div>
    </li> -->

    <!-- <button style="height:2.8rem" class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" onchange="clickExplore()">
        Object
    </button> -->


    <?php
    $currentURL = $_SERVER['REQUEST_URI'];
    if ($currentURL === '/web' || $currentURL === '/web/explore' || $currentURL === '/web/mypackage') {
        // Jika URL adalah '/web', tampilkan tombol
        echo '<div class="btn-group mx-1">        
        <button style="height:2.8rem"  class="btn btn-primary" type="button" data-bs-toggle="tooltip" data-bs-placement="bottom" title="View All Objects" onclick="clickExplore()">Object</button>
        <button style="height:2.8rem" class="btn btn-primary dropdown-toggle dropdown-toggle-split" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="visually-hidden">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu p-2">        
            <li>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="check-oat" value="at" onchange="checkObject()">
                    <label class="form-check-label" for="check-oat">Attraction</label>
                </div>
            </li>
            <li>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="check-oho" value="ho" onchange="checkObject()">
                    <label class="form-check-label" for="check-oho">Homestay</label>
                </div>
            </li>
            <li>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="check-ocp" value="cp" onchange="checkObject()">
                    <label class="form-check-label" for="check-ocp">Culinary Places</label>
                </div>
            </li>
            <li>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="check-osp" value="sp" onchange="checkObject()">
                    <label class="form-check-label" for="check-osp">Souvenir Places</label>
                </div>
            </li>
            <li>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="check-owp" value="wp" onchange="checkObject()">
                    <label class="form-check-label" for="check-owp">Worship Places</label>
                </div>
            </li>
        </ul>

    </div>';
    }
    ?>




</div>
<?php
$currentURL = $_SERVER['REQUEST_URI'];
if ($currentURL === '/web' || $currentURL === '/web/sumpu' || $currentURL === '/web/explore' || $currentURL === '/web/mypackage') {
    echo ' <script>
    weatherNow();
</script>
<div  class="col-md-auto" id="weather-info"></div>';
}
?>