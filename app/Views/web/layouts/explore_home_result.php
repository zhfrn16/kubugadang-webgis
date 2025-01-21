
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
            if ($currentURL === '/web' || $currentURL === '/web?i=1') {
                // Jika URL adalah '/web', tampilkan tombol
                echo '
            <div class="mt-3">
                                <a title="Around You" class="btn icon btn-outline-primary mx-1" onclick="openExplore()">
                                    <i class="fa-solid fa-compass me-3"></i>Search object around you?
                                </a>
                            </div>';
            }
            ?>
        </div>
    </div>
</div>