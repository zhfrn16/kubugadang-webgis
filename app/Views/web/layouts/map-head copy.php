<div class="col">
    <div class="btn-group" >
        <a data-bs-toggle="tooltip" style="border-radius: 0.2rem;" data-bs-placement="bottom" title="Current Location" class="btn icon btn-primary mx-1" id="current-position" onclick="currentPosition();">
            <span class="material-symbols-outlined">my_location</span>
        </a>
        <a data-bs-toggle="tooltip" style="border-radius: 0.2rem;" data-bs-placement="bottom" title="Set Manual Location" class="btn icon btn-primary mx-1" id="manual-position" onclick="manualPosition();">
            <span class="material-symbols-outlined">pin_drop</span>
        </a>
        <a data-bs-toggle="tooltip" style="border-radius: 0.2rem;" data-bs-placement="bottom" title="Toggle Legend" class="btn icon btn-primary mx-1" id="legend-map" onclick="viewLegend();">
            <span class="material-symbols-outlined">visibility</span>
        </a>
        <?php 
        $currentURL = $_SERVER['REQUEST_URI'];
        if ($currentURL === '/web' || $currentURL === '/web/sumpu') {
            // Jika URL adalah '/web', tampilkan tombol "Go to Object" dan dropdown
            echo '<div class="dropdown">
                <button class="btn icon btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    Go to Object
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="padding:0.5rem">
                    <li><input checked onclick="checkDigitNegara()" type="checkbox" id="checkDigitNegara" name="checkDigitNegara" value="Negara"><label for="checkDigitNegara">Negara</label></li>
                    <li><input checked type="checkbox" id="checkDigitProvinsi" name="checkDigitProvinsi" value="Provinsi"><label for="checkDigitProvinsi">Provinsi</label></li>
                    <li><input checked type="checkbox" id="checkDigitKabKota" name="checkDigitKabKota" value="Kab/Kota"><label for="checkDigitKabKota">Kab/Kota</label></li>
                    <!-- Tambahkan checkbox lain sesuai kebutuhan -->
                </ul>
            </div>';
        }
        ?>
    </div>
</div>
