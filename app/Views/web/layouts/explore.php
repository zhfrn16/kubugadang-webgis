<!-- Check nearby -->

<div class="col-12" id="check-explore-col">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title text-center">Object Around</h5>
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-md-6 col-12" style="padding: 0px;">
                    <div class="form-check">
                        <div class="checkbox">
                            <input type="checkbox" id="check-ho" class="form-check-input" checked>
                            <label for="check-ho">Homestay</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12" style="padding: 0px;">

                    <div class="form-check">
                        <div class="checkbox">
                            <input type="checkbox" id="check-cp" class="form-check-input">
                            <label for="check-cp">Culinary Place</label>
                        </div>
                    </div>
                    <div class="form-check">
                        <div class="checkbox">
                            <input type="checkbox" id="check-sp" class="form-check-input">
                            <label for="check-sp">Souvenir Place</label>
                        </div>
                    </div>
                    <div class="form-check">
                        <div class="checkbox">
                            <input type="checkbox" id="check-wp" class="form-check-input">
                            <label for="check-wp">Worship Place</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <label for="inputRadiusNearby" class="form-label">Radius: </label>
                <label id="radiusValueNearby" class="form-label">0 m</label>
                <input type="range" class="form-range" min="0" max="20" value="0" id="inputRadiusNearby" name="inputRadius" onchange="updateRadius('Nearby');">
            </div>
            <div class="mt-3">
                <a title="Close Nearby" class="btn icon btn-outline-primary mx-1" onclick="closeExplore()">
                    <i class="fa-solid fa-circle-xmark"></i> Close
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Search result nearby -->
<div class="col-12" id="result-explore-col">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title text-center">Result Object</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive overflow-auto" id="table-result-nearby">
                <table class="table table-hover mb-md-5 mb-3 table-lg" id="table-lsa">
                </table>
                <table class="table table-hover mb-md-5 mb-3 table-lg" id="table-at">
                </table>
                <table class="table table-hover mb-md-5 mb-3 table-lg" id="table-th">
                </table>
                <table class="table table-hover mb-md-5 mb-3 table-lg" id="table-ho">
                </table>
                <table class="table table-hover mb-md-5 mb-3 table-lg" id="table-cp">
                </table>
                <table class="table table-hover mb-md-5 mb-3 table-lg" id="table-sp">
                </table>
                <table class="table table-hover mb-md-5 mb-3 table-lg" id="table-wp">
                </table>
            </div>
            <?php
            $currentURL = $_SERVER['REQUEST_URI'];
            if ($currentURL === '/web/explore' || $currentURL === '/web/mypackage') {
                // Jika URL adalah '/web', tampilkan tombol
                echo '
            <div class="mt-3">
                <a title="Around You" class="btn icon btn-outline-primary mx-1" onclick="closeExplore()">
                    <i class="fa-solid fa-compass me-3"></i>Explore Package?
                </a>
            </div>';
            }
            ?>
        </div>
    </div>
</div>