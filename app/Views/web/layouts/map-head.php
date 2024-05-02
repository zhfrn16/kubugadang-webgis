<div class="col-md-auto">
    <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Current Location" class="btn icon btn-primary mx-1" id="current-position" onclick="currentPosition();">
        <span class="material-symbols-outlined">my_location</span>
    </a>
    <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Set Manual Location" class="btn icon btn-primary mx-1" id="manual-position" onclick="manualPosition();">
        <span class="material-symbols-outlined">pin_drop</span>
    </a>
    <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Toggle Legend" class="btn icon btn-primary mx-1" id="legend-map" onclick="viewLegend();">
        <span class="material-symbols-outlined">visibility</span>
    </a>

    <?php
    $currentURL = $_SERVER['REQUEST_URI'];
    if ($currentURL === '/web' || $currentURL === '/web/sumpu') {
        // Jika URL adalah '/web', tampilkan tombol
        echo '<a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Go to Object" class="btn icon btn-primary mx-1" id="go-to" onclick="zoomToSumpuMarkers()">
        <i style="height:1.72rem;width:1.5rem"  class="fa-solid fa-location-arrow"></i>
        </a>';
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