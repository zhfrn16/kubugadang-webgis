let baseUrl = "";
let currentUrl = "";
let currentLat = 0,
  currentLng = 0;
let userLat = 0,
  userLng = 0;
let web, map;
let infoWindow = new google.maps.InfoWindow();
let userInfoWindow = new google.maps.InfoWindow();
let directionsService, directionsRenderer;
let userMarker = new google.maps.Marker();
let destinationMarker = new google.maps.Marker();
let routeArray = [],
  circleArray = [],
  markerArray = {};
let overlays = [];
let airplaneMarkers = [];
let carMarkers = [];
let customLabels = [];
let customLabelsCountry = [];
let digitasiArray = {};
let digitNegLayers = [];
let digitProvLayers = [];
let digitKabKotaLayers = [];
let digitKecLayers = [];
let digitNagari1Layers = [];
let digitVillage1Layers = [];
let isLabelChecked = false;
let isTerrainChecked = false;

let bounds = new google.maps.LatLngBounds();
let selectedShape,
  drawingManager = new google.maps.drawing.DrawingManager();
let customStyled = [
  {
    elementType: "labels",
    stylers: [
      {
        visibility: "off",
      },
    ],
  },
  {
    featureType: "poi",
    elementType: "labels",
    stylers: [{ visibility: "off" }],
  },
  {
    featureType: "administrative.country",
    stylers: [
      {
        visibility: "off",
      },
    ],
  },
  {
    featureType: "administrative.province",
    stylers: [
      {
        visibility: "off",
      },
    ],
  },
  {
    featureType: "administrative.land_parcel",
    stylers: [
      {
        visibility: "off",
      },
    ],
  },
  {
    featureType: "administrative.neighborhood",
    stylers: [
      {
        visibility: "off",
      },
    ],
  },
  {
    featureType: "road",
    elementType: "labels",
    stylers: [
      {
        visibility: "on",
      },
    ],
  },
];

const formatter = new Intl.NumberFormat("id-ID", {
  style: "currency",
  currency: "IDR",
});
function setBaseUrl(url) {
  baseUrl = url;
}

// Initialize and add the map for landing page
function initMapLP(lat = -0.5242972, lng = 100.492333, mobile = false) {
  directionsService = new google.maps.DirectionsService();
  const center = new google.maps.LatLng(lat, lng);
  if (!mobile) {
    map = new google.maps.Map(document.getElementById("googlemaps"), {
      zoom: 18,
      center: center,
      mapTypeId: "roadmap",
    });
  } else {
    map = new google.maps.Map(document.getElementById("googlemaps"), {
      zoom: 18,
      center: center,
      mapTypeControl: false,
    });
  }
  var rendererOptions = {
    map: map,
  };
  map.set("styles", customStyled);
  directionsRenderer = new google.maps.DirectionsRenderer(rendererOptions);
  // digitVillage();
  digitVillage1();
}

// Initialize and add the map
function initMap(lat = -0.47580795, lng = 100.43229867) {
  directionsService = new google.maps.DirectionsService();
  const center = new google.maps.LatLng(lat, lng);
  map = new google.maps.Map(document.getElementById("googlemaps"), {
    zoom: 16,
    center: center,
    mapTypeId: "satellite",
  });
  var rendererOptions = {
    map: map,
  };
  map.set("styles", customStyled);
  directionsRenderer = new google.maps.DirectionsRenderer(rendererOptions);
  // digitVillage();
  // digitVillage1();
}

function addCustomLabels(map) {
  const locations = [
    { position: { lat: -6.2088, lng: 106.8456 }, name: "JAKARTA" },
    { position: { lat: -0.9446, lng: 100.3714 }, name: "PADANG" },
    { position: { lat: 1.047, lng: 104.0305 }, name: "BATAM" },
  ];

  locations.forEach((location) => {
    const label = new google.maps.OverlayView();
    label.onAdd = function () {
      const div = document.createElement("div");
      div.style.position = "absolute";
      div.style.padding = "5px 10px";
      div.style.fontFamily = "Product Sans, Arial, sans-serif"; // Alternatif mendekati Google Sans
      div.style.fontSize = "13px";
      div.style.fontWeight = "800"; // Berat font normal seperti label Maps
      div.style.color = "#fff"; // Warna teks putih
      div.style.webkitTextFillColor = "#fff"; // Stroke hitam pada teks
      div.style.webkitTextStroke = "1px #000"; // Stroke hitam pada teks
      // div.style.letterSpacing = "-0.0325em"; // Simulasi semi-condensed 87.5%
      div.style.textAlign = "center"; // Posisi teks rata tengah
      div.style.zIndex = "999";
      div.innerHTML = location.name;

      const panes = this.getPanes();
      panes.overlayLayer.appendChild(div);

      this.div = div;
    };

    label.draw = function () {
      const projection = this.getProjection();
      const position = projection.fromLatLngToDivPixel(location.position);
      if (this.div) {
        const width = this.div.offsetWidth; // Lebar elemen label
        const height = this.div.offsetHeight; // Tinggi elemen label

        this.div.style.left = `${position.x - width / 2}px`; // Pusatkan secara horizontal
        this.div.style.top = `${position.y - height / 2}px`; // Pusatkan secara vertikal
      }
    };

    label.onRemove = function () {
      if (this.div) {
        this.div.parentNode.removeChild(this.div);
        this.div = null;
      }
    };

    label.setMap(map);
    customLabels.push(label); // Simpan label ke array
  });
}
function addCustomLabelsCountry(map) {
  const locations = [
    { position: { lat: 3.440052, lng: 101.957396 }, name: "MALAYSIA" },
    { position: { lat: 1.3521, lng: 103.8198 }, name: "SINGAPORE" },
    { position: { lat: 4.9031, lng: 114.9398 }, name: "BRUNEI" },
    { position: { lat: -1.377737, lng: 113.217183 }, name: "INDONESIA" },
  ];

  locations.forEach((location) => {
    const label = new google.maps.OverlayView();
    label.onAdd = function () {
      const div = document.createElement("div");
      div.style.position = "absolute";
      div.style.padding = "5px 10px";
      div.style.fontFamily = "Product Sans, Arial, sans-serif"; // Alternatif mendekati Google Sans
      div.style.fontSize = "18px";
      div.style.fontWeight = "800"; // Berat font normal seperti label Maps
      div.style.color = "#fff"; // Warna teks putih
      div.style.webkitTextFillColor = "#fff"; // Stroke hitam pada teks
      div.style.webkitTextStroke = "1px #000"; // Stroke hitam pada teks
      // div.style.letterSpacing = "-0.0325em"; // Simulasi semi-condensed 87.5%
      div.style.textAlign = "center"; // Posisi teks rata tengah
      div.style.zIndex = "999";
      div.innerHTML = location.name;

      const panes = this.getPanes();
      panes.overlayLayer.appendChild(div);

      this.div = div;
    };

    label.draw = function () {
      const projection = this.getProjection();
      const position = projection.fromLatLngToDivPixel(location.position);
      if (this.div) {
        const width = this.div.offsetWidth; // Lebar elemen label
        const height = this.div.offsetHeight; // Tinggi elemen label

        this.div.style.left = `${position.x - width / 2}px`; // Pusatkan secara horizontal
        this.div.style.top = `${position.y - height / 2}px`; // Pusatkan secara vertikal
      }
    };

    label.onRemove = function () {
      if (this.div) {
        this.div.parentNode.removeChild(this.div);
        this.div = null;
      }
    };

    label.setMap(map);
    customLabelsCountry.push(label); // Simpan label ke array
  });
}

function initMap5(lat = -0.54145013, lng = 100.48094882) {
  directionsService = new google.maps.DirectionsService();
  const center = new google.maps.LatLng(lat, lng);
  map = new google.maps.Map(document.getElementById("googlemaps"), {
    zoom: 6,
    center: center,
    mapTypeId: "hybrid",
    // styles: ,
  });
  var rendererOptions = {
    map: map,
  };
  map.set("styles", customStyled);
  directionsRenderer = new google.maps.DirectionsRenderer(rendererOptions);

  addCustomLabels(map);
  addCustomLabelsCountry(map);

  const checkOCO = document.getElementById("check-oco");
  checkOCO.checked = true;
  const checkOPR = document.getElementById("check-opr");
  checkOPR.checked = true;
  const checkORE = document.getElementById("check-ore");
  checkORE.checked = true;
  const checkODI = document.getElementById("check-odi");
  checkODI.checked = true;
  const checkOVI = document.getElementById("check-ovi");
  checkOVI.checked = true;
  const checkOTO = document.getElementById("check-oto");
  checkOTO.checked = true;
}

function checkLabel() {
  const checkBox = document.getElementById("check-label");
  isLabelChecked = checkBox.checked; // Update status global

  const defaultStyled = [
    { elementType: "labels", stylers: [{ visibility: "on" }] },
    {
      featureType: "poi",
      elementType: "labels",
      stylers: [{ visibility: "off" }],
    },
    {
      featureType: "administrative.land_parcel",
      stylers: [{ visibility: "off" }],
    },
    {
      featureType: "administrative.neighborhood",
      stylers: [{ visibility: "off" }],
    },
    {
      featureType: "road",
      elementType: "labels",
      stylers: [{ visibility: "on" }],
    },
  ];

  const hideLabels = [
    { elementType: "labels", stylers: [{ visibility: "off" }] },
  ];

  if (isLabelChecked) {
    // Tampilkan label default
    map.setOptions({ styles: defaultStyled });
    customLabels.forEach((label) => label.setMap(null));
    customLabelsCountry.forEach((label) => label.setMap(null));
    customLabels = [];
    customLabelsCountry = [];
  } else {
    // Sembunyikan label default
    map.setOptions({ styles: hideLabels });
    addCustomLabels(map);
    addCustomLabelsCountry(map);
  }
}

function checkTerrain() {
  const checkBox = document.getElementById("check-terrain");
  isTerrainChecked = checkBox.checked; // Update status global

  if (isTerrainChecked) {
    map.setMapTypeId("terrain");
  } else {
    map.setMapTypeId("hybrid");
  }

  // Terapkan ulang gaya label jika checkbox label aktif
  if (isLabelChecked) {
    checkLabel();
  }
}

function checkRoadMap() {
  const checkBox = document.getElementById("check-roadmap");

  if (checkBox.checked) {
    map.setMapTypeId("roadmap");
  } else {
    map.setMapTypeId("satellite");
  }
}

function initMap7(lat = -0.54145013, lng = 100.48094882) {
  directionsService = new google.maps.DirectionsService();
  const center = new google.maps.LatLng(lat, lng);
  map = new google.maps.Map(document.getElementById("googlemaps"), {
    zoom: 6,
    center: center,
    mapTypeId: "satellite",
  });
  var rendererOptions = {
    map: map,
  };
  map.set("styles", customStyled);
  directionsRenderer = new google.maps.DirectionsRenderer(rendererOptions);
}

// // Display tourism village digitizing
// function digitVillage() {
//   // const village = new google.maps.Data();
//   village = new google.maps.Data();
//   $.ajax({
//     url: baseUrl + "/api/village",
//     type: "POST",
//     data: {
//       village: "V01",
//     },
//     dataType: "json",
//     success: function (response) {
//       const data = response.data;
//       village.addGeoJson(data);
//       village.setStyle({
//         fillColor: "#00b300",
//         strokeWeight: 0.5,
//         strokeColor: "#005000",
//         fillOpacity: 0.1,
//         clickable: false,
//       });
//       village.setMap(map);
//     },
//   });
// }

function initMap4(lat = -0.54145013, lng = 100.48094882) {
  directionsService = new google.maps.DirectionsService();
  const center = new google.maps.LatLng(lat, lng);
  map = new google.maps.Map(document.getElementById("googlemaps"), {
    zoom: 15,
    center: center,
    mapTypeId: "satellite",
  });
  var rendererOptions = {
    map: map,
  };
  map.set("styles", customStyled);
  directionsRenderer = new google.maps.DirectionsRenderer(rendererOptions);

  digitNagari();
}

function initMap9(lat = -0.4761815168531753, lng = 100.43223933779609) {
  directionsService = new google.maps.DirectionsService();
  const center = new google.maps.LatLng(lat, lng);
  map = new google.maps.Map(document.getElementById("googlemaps"), {
    zoom: 16,
    center: center,
    mapTypeId: "hybrid",
    // styles: ,
  });
  var rendererOptions = {
    map: map,
  };
  map.set("styles", customStyled);
  directionsRenderer = new google.maps.DirectionsRenderer(rendererOptions);

  addCustomLabels(map);
  addCustomLabelsCountry(map);
}

function initMap99(lat = -0.4761815168531753, lng = 100.43223933779609) {
  // objectMarker("SUM01", -0.4761815168531753, 100.43223933779609);

  directionsService = new google.maps.DirectionsService();
  const center = new google.maps.LatLng(lat, lng);
  map = new google.maps.Map(document.getElementById("googlemaps"), {
    zoom: 16,
    center: center,
    mapTypeId: "satellite",
  });
  var rendererOptions = {
    map: map,
  };
  map.set("styles", customStyled);
  directionsRenderer = new google.maps.DirectionsRenderer(rendererOptions);

  // digitVillage1zoom();

  // for (let d = 1; d < 2; d++) {
  //   const iddesa = d;
  //   digitNagari2(iddesa);
  // }
  // digitVillage3();
  addCustomLabels(map);
  addCustomLabelsCountry(map);
}

// Display nagari digitizing
function digitNagari() {
  const digitasi = new google.maps.Data();
  const infoWindow = new google.maps.InfoWindow();

  $.ajax({
    url: baseUrl + "/api/village",
    type: "POST",
    data: {
      digitasi: "V0001",
    },
    dataType: "json",
    success: function (response) {
      const data = response.data;
      digitasi.addGeoJson(data);
      digitasi.setStyle({
        fillColor: "#03C988",
        strokeWeight: 3,
        strokeColor: "#ffffff",
        // fillOpacity: 2,
        clickable: true, // Set clickable to true to enable click event
      });
      digitasi.setMap(map);

      // Event listener for click
      digitasi.addListener("click", function (event) {
        const nagariName = event.feature.getProperty("name");

        // Set label for the clicked feature using InfoWindow
        infoWindow.setContent("Nagari " + nagariName);
        infoWindow.setPosition(event.latLng);
        infoWindow.open(map);
      });
    },
  });
}

function digitNeg(idneg) {
  const digitasi = new google.maps.Data();
  const infoWindow = new google.maps.InfoWindow();

  function getColor(idneg) {
    const colors = {
      1: "red", // Merah untuk idneg 1
      2: "purple", // Hijau untuk idneg 2
      3: "yellow", // Biru untuk idneg 3
    };
    return colors[idneg] || "#793FDF"; // Default warna ungu jika idneg tidak ada di daftar
  }

  if (idneg < 4) {
    digitasiValue = "N0" + idneg;
  }
  $.ajax({
    url: baseUrl + "media/map/" + digitasiValue + ".geojson", // Ubah sesuai dengan path file Anda
    type: "GET",
    dataType: "json",
    success: function (response) {
      const data = response; // Jika file .geojson diakses langsung melalui URL

      digitasi.addGeoJson(data);
      digitasi.setStyle({
        // fillColor: "#793FDF",
        fillColor: getColor(idneg),
        strokeWeight: 0.5,
        strokeColor: "#ffffff",
        fillOpacity: 0.3,
        clickable: true, // Set clickable to true to enable click event
        index: 1, // Set clickable to true to enable click event
      });
      map.setZoom(6);
      digitasi.setMap(map);

      // Simpan instance layer ke array global
      digitNegLayers.push(digitasi);

      // Event listener for click
      digitasi.addListener("click", function (event) {
        const Name = event.feature.getProperty("name");
        console.log(Name);

        // Set label for the clicked feature using InfoWindow
        infoWindow.setContent("Country: " + Name);
        infoWindow.setPosition(event.latLng);
        infoWindow.open(map);
      });
    },
    error: function (error) {
      console.error("Error fetching GeoJSON file: " + error);
    },
  });
}

function getIdProvince() {
  $.ajax({
    url: baseUrl + "/api/getIdProvince",
    type: "GET",
    dataType: "json",
    success: function (response) {
      // const idprov = response.data;
      const myidprov = response.data[0].province_id; // Mengambil nilai province_id dari elemen pertama dalam array data
      const digitidprov = myidprov.substring(1);
      // digitProv(idprov);

      for (let p = 1; p < digitidprov; p++) {
        const idprov = p;
        digitProv(idprov);
      }

      for (let p = digitidprov; p < 11; p++) {
        const idprov = p;
        digitProv(idprov);
      }

      const mynameprov = response.data[0].name;
      nameprov = mynameprov.replace(/\s/g, "_"); // Menghilangkan spasi dari string
      digitKabKota(nameprov);

      for (let k = 1; k < 15; k++) {
        const idkec = k;
        digitKec(idkec);
      }

      for (let d = 1; d < 3; d++) {
        const iddesa = d;
        digitNagari1(iddesa);
      }

      for (let d = 4; d < 6; d++) {
        const iddesa = d;
        digitNagari1(iddesa);
      }
    },
    error: function (error) {
      console.error("Error fetching id province" + error);
    },
  });
}

function digitProv(idprov) {
  const digitasi = new google.maps.Data();
  const infoWindow = new google.maps.InfoWindow();

  // const digitIdProv = idprov.substring(1); // Mengambil karakter mulai dari indeks ke-2 (setelah "P0")

  if (idprov < 10) {
    digitasiValue = "0" + idprov;
  } else if (idprov >= 10) {
    digitasiValue = idprov;
  }

  $.ajax({
    url: baseUrl + "media/map/provinsi/provinsi_" + digitasiValue + ".geojson", // Ubah sesuai dengan path file Anda
    type: "GET",
    dataType: "json",
    success: function (response) {
      const data = response; // Jika file .geojson diakses langsung melalui URL

      digitasi.addGeoJson(data);
      digitasi.setStyle({
        fillColor: "#fff",
        strokeWeight: 0.5,
        strokeColor: "#ffffff",
        fillOpacity: 0,
        clickable: true, // Set clickable to true to enable click event
        index: 2, // Set clickable to true to enable click event
      });
      map.setZoom(6);
      digitasi.setMap(map);

      digitNegLayers.push(digitasi);

      // Event listener for click
      digitasi.addListener("click", function (event) {
        const Name = event.feature.getProperty("PROVINSI");
        console.log(Name);

        // Set label for the clicked feature using InfoWindow
        infoWindow.setContent("PROVINSI " + Name);
        infoWindow.setPosition(event.latLng);
        infoWindow.open(map);
      });
    },
    error: function (error) {
      console.error("Error fetching GeoJSON file: " + error);
    },
  });
}
function digitKabKota(nameprov) {
  const digitasi = new google.maps.Data();
  const infoWindow = new google.maps.InfoWindow();

  $.ajax({
    url:
      baseUrl +
      "media/map/kabkota/" +
      nameprov +
      "/output_" +
      nameprov +
      ".geojson", // Ubah sesuai dengan path file Anda
    type: "GET",
    dataType: "json",
    success: function (response) {
      const data = response; // Jika file .geojson diakses langsung melalui URL

      digitasi.addGeoJson(data);
      digitasi.setStyle({
        fillColor: "#fff",
        strokeWeight: 0.5,
        strokeColor: "#ffffff",
        fillOpacity: 0,
        clickable: true, // Set clickable to true to enable click event
        index: 3, // Set clickable to true to enable click event
      });
      map.setZoom(6);
      digitasi.setMap(map);

      digitProvLayers.push(digitasi);

      // Event listener for click
      digitasi.addListener("click", function (event) {
        const Name = event.feature.getProperty("WADMKK");
        console.log(Name);

        // Set label for the clicked feature using InfoWindow
        infoWindow.setContent(Name);
        infoWindow.setPosition(event.latLng);
        infoWindow.open(map);
      });
    },
    error: function (error) {
      console.error("Error fetching GeoJSON file: " + error);
    },
  });
}

function digitKec(idkec) {
  const digitasi = new google.maps.Data();
  const infoWindow = new google.maps.InfoWindow();

  if (idkec < 10) {
    digitasiValue = "C0" + idkec;
  } else if (idkec >= 10) {
    digitasiValue = "C" + idkec;
  }
  $.ajax({
    url: baseUrl + "media/map/output_folder/" + digitasiValue + ".geojson", // Ubah sesuai dengan path file Anda
    type: "GET",
    dataType: "json",
    success: function (response) {
      const data = response; // Jika file .geojson diakses langsung melalui URL

      digitasi.addGeoJson(data);
      digitasi.setStyle({
        fillColor: "#fff",
        strokeWeight: 1,
        strokeColor: "#ffffff",
        fillOpacity: 0,
        clickable: true, // Set clickable to true to enable click event
        index: 4, // Set clickable to true to enable click event
      });
      map.setZoom(6);
      digitasi.setMap(map);

      digitKecLayers.push(digitasi);

      // Event listener for click
      digitasi.addListener("click", function (event) {
        const Name = event.feature.getProperty("NAMOBJ");
        console.log(Name);

        // Set label for the clicked feature using InfoWindow
        infoWindow.setContent("KECAMATAN " + Name);
        infoWindow.setPosition(event.latLng);
        infoWindow.open(map);
      });
    },
    error: function (error) {
      console.error("Error fetching GeoJSON file: " + error);
    },
  });
}

function digitNagari1(iddesa) {
  const digitasi = new google.maps.Data();
  const infoWindow = new google.maps.InfoWindow();

  if (iddesa < 10) {
    digitasiValue = "V0" + iddesa;
  } else if (iddesa >= 10) {
    digitasiValue = "V" + iddesa;
  }
  $.ajax({
    url: baseUrl + "media/map/kubugadang/" + digitasiValue + ".geojson", // Ubah sesuai dengan path file Anda
    type: "GET",
    dataType: "json",
    success: function (response) {
      const data = response; // Jika file .geojson diakses langsung melalui URL

      digitasi.addGeoJson(data);
      digitasi.setStyle({
        fillColor: "#fff",
        strokeWeight: 1,
        strokeColor: "#ffffff",
        fillOpacity: 0,
        clickable: true, // Set clickable to true to enable click event
        index: 5, // Set clickable to true to enable click event
      });
      map.setZoom(6);
      digitasi.setMap(map);

      digitNagari1Layers.push(digitasi);

      // Event listener for click
      digitasi.addListener("click", function (event) {
        const Name = event.feature.getProperty("DESA");
        console.log(Name);

        // Set label for the clicked feature using InfoWindow
        infoWindow.setContent("NAGARI " + Name);
        infoWindow.setPosition(event.latLng);
        infoWindow.open(map);
      });
    },
    error: function (error) {
      console.error("Error fetching GeoJSON file: " + error);
    },
  });
}

function digitNagari2(iddesa) {
  const digitasi = new google.maps.Data();
  const infoWindow = new google.maps.InfoWindow();

  if (iddesa < 10) {
    digitasiValue = "V0" + iddesa;
  } else if (iddesa >= 10) {
    digitasiValue = "V" + iddesa;
  }
  $.ajax({
    url: baseUrl + "media/map/output_folder3/" + digitasiValue + ".geojson", // Ubah sesuai dengan path file Anda
    type: "GET",
    dataType: "json",
    success: function (response) {
      const data = response; // Jika file .geojson diakses langsung melalui URL

      digitasi.addGeoJson(data);
      digitasi.setStyle({
        fillColor: "#FFC436",
        strokeWeight: 2,
        strokeColor: "#ffffff",
        fillOpacity: 2,
        clickable: true, // Set clickable to true to enable click event
      });
      digitasi.setMap(map);

      // Event listener for click
      digitasi.addListener("click", function (event) {
        const Name = event.feature.getProperty("DESA");
        console.log(Name);

        // Set label for the clicked feature using InfoWindow
        infoWindow.setContent("NAGARI " + Name);
        infoWindow.setPosition(event.latLng);
        infoWindow.open(map);
      });
    },
    error: function (error) {
      console.error("Error fetching GeoJSON file: " + error);
    },
  });
}

// Display nagari digitizing
function digitNagari() {
  const digitasi = new google.maps.Data();
  $.ajax({
    url: baseUrl + "api/village",
    type: "POST",
    data: {
      digitasi: "V0001",
    },
    dataType: "json",
    success: function (response) {
      const data = response.data;
      digitasi.addGeoJson(data);
      digitasi.setStyle({
        fillColor: "#00b300",
        strokeWeight: 0.5,
        strokeColor: "#ffffff",
        fillOpacity: 0.1,
        clickable: false,
      });
      digitasi.setMap(map);
    },
  });
}

function digitHomestay(idhomestay) {
  if (digitasiArray[idhomestay]) {
    console.log(`Menghapus digitasi untuk ID: ${idhomestay}`);
    digitasiArray[idhomestay].setMap(null);
    delete digitasiArray[idhomestay];
  }

  const digitasi = new google.maps.Data();

  $.ajax({
    url: baseUrl + "/api/home",
    type: "POST",
    data: {
      digitasi: idhomestay,
    },
    dataType: "json",
    success: function (response) {
      const data = response.data;
      digitasi.addGeoJson(data);
      digitasi.setStyle({
        fillColor: "#9a02e6",
        strokeWeight: 0.4,
        strokeColor: "#ffffff",
        fillOpacity: 0.4,
      });
      digitasi.setMap(map);
      // Simpan digitasi ke dalam array
      digitasiArray[idhomestay] = digitasi;
      // Tambahkan ke array
      // digitasiArray.push(digitasi);
      // console.log(`Digitasi ditambahkan untuk ID: ${idhomestay}`);
    },
  });
}

// Fungsi untuk menghapus semua digitasi
function clearAllDigitasi() {
  // console.log("Menghapus semua digitasi...");
  for (const id in digitasiArray) {
    if (digitasiArray[id]) {
      digitasiArray[id].setMap(null);
      // console.log(`Digitasi dengan ID ${id} dihapus.`);
    }
  }
  Object.keys(digitasiArray).forEach((key) => delete digitasiArray[key]);
}

// Clear all airplane markers
function clearAirplaneMarkers() {
  airplaneMarkers.forEach((marker) => marker.setMap(null));
  airplaneMarkers.length = 0; // Clear the array
}

// Clear all car markers
function clearCarMarkers() {
  carMarkers.forEach((marker) => marker.setMap(null));
  carMarkers.length = 0; // Clear the array
}

function clearOverlay() {
  overlays.forEach((overlay) => {
    overlay.setMap(null); // Remove overlay from the map
  });
  overlays = []; // Clear the array
}

function digitCulinary(idculinary) {
  const digitasi = new google.maps.Data();

  $.ajax({
    url: baseUrl + "/api/culinary",
    type: "POST",
    data: {
      digitasi: idculinary,
    },
    dataType: "json",
    success: function (response) {
      const data = response.data;
      digitasi.addGeoJson(data);
      digitasi.setStyle({
        fillColor: "#ff6d4d",
        strokeWeight: 0.3,
        strokeColor: "#ffffff",
        fillOpacity: 0.3,
      });
      digitasi.setMap(map);
      digitasiArray[idculinary] = digitasi;
    },
  });
  // console.log(digitasi);
}

function digitTraditional(idtraditional) {
  const digitasi = new google.maps.Data();

  $.ajax({
    url: baseUrl + "/api/traditional",
    type: "POST",
    data: {
      digitasi: idtraditional,
    },
    dataType: "json",
    success: function (response) {
      const data = response.data;
      digitasi.addGeoJson(data);
      digitasi.setStyle({
        fillColor: "#435ebe",
        strokeWeight: 0.3,
        strokeColor: "#ffffff",
        fillOpacity: 0.3,
      });
      digitasi.setMap(map);
      digitasiArray[idtraditional] = digitasi;
    },
  });
  // console.log(digitasi);
}

function digitRumah(idrumah) {
  const digitasi = new google.maps.Data();

  $.ajax({
    url: baseUrl + "/api/rumah",
    type: "POST",
    data: {
      digitasi: idrumah,
    },
    dataType: "json",
    success: function (response) {
      const data = response.data;
      digitasi.addGeoJson(data);
      digitasi.setStyle({
        fillColor: "#ff6d4d",
        strokeWeight: 0.3,
        strokeColor: "#ffffff",
        fillOpacity: 0.3,
      });
      digitasi.setMap(map);
      digitasiArray[idrumah] = digitasi;
    },
  });
  // console.log(digitasi);
}

function digitSouvenir(idsouvenir) {
  const digitasi = new google.maps.Data();

  $.ajax({
    url: baseUrl + "/api/souvenir",
    type: "POST",
    data: {
      digitasi: idsouvenir,
    },
    dataType: "json",
    success: function (response) {
      const data = response.data;
      digitasi.addGeoJson(data);
      digitasi.setStyle({
        fillColor: "#f5670f",
        strokeWeight: 0.3,
        strokeColor: "#ffffff",
        fillOpacity: 0.4,
      });
      digitasi.setMap(map);
      digitasiArray[idsouvenir] = digitasi;
    },
  });
  // console.log(digitasi);
}

function digitWorship(idworship) {
  const digitasi = new google.maps.Data();

  $.ajax({
    url: baseUrl + "/api/worship",
    type: "POST",
    data: {
      digitasi: idworship,
    },
    dataType: "json",
    success: function (response) {
      const data = response.data;
      digitasi.addGeoJson(data);
      digitasi.setStyle({
        fillColor: "#00ad00",
        strokeWeight: 0.3,
        strokeColor: "#ffffff",
        fillOpacity: 0.3,
      });
      digitasi.setMap(map);
      digitasiArray[idworship] = digitasi;
    },
  });
  // console.log(digitasi);
}

function digitFacility(idfc) {
  const digitasi = new google.maps.Data();

  // if (idfc < 10) {
  //   digitasiValue = "FC00" + idfc;
  // } else if (idfc >= 10) {
  //   digitasiValue = "FC0" + idfc;
  // }

  $.ajax({
    url: baseUrl + "/api/facility",
    type: "POST",
    data: {
      // digitasi: digitasiValue,
      digitasi: idfc,
    },
    dataType: "json",
    success: function (response) {
      const data = response.data;
      digitasi.addGeoJson(data);
      digitasi.setStyle({
        fillColor: "#898c87",
        strokeWeight: 0.4,
        strokeColor: "#ffffff",
        fillOpacity: 0.4,
      });
      digitasi.setMap(map);
      digitasiArray[idfc] = digitasi;
    },
  });
}

//GTP
// function digitVillage1() {
//     const digitasi = new google.maps.Data();
//     $.ajax({
//         url: baseUrl + '/api/village',
//         type: 'POST',
//         data: {
//             digitasi: 'GTP01'
//         },
//         dataType: 'json',
//         success: function (response) {
//             const data = response.data;
//             digitasi.addGeoJson(data);
//             digitasi.setStyle({
//                 fillColor:'#03C988',
//                 strokeWeight:3,
//                 strokeColor:'#ffffff',
//                 fillOpacity: 2,
//                 clickable: false
//             });
//             digitasi.setMap(map);
//         }
//     });
// }

//Sumpu
// function digitVillage1() {
//   const digitasi = new google.maps.Data();
//   $.ajax({
//     url: baseUrl + "/api/village",
//     type: "POST",
//     data: {
//       digitasi: "SUM01",
//     },
//     dataType: "json",
//     success: function (response) {
//       const data = response.data;
//       digitasi.addGeoJson(data);
//       digitasi.setStyle({
//         fillColor: "#03C988",
//         strokeWeight: 3,
//         strokeColor: "#ffffff",
//         fillOpacity: 2,
//         clickable: false,
//       });
//       digitasi.setMap(map);
//     },
//   });
// }

function digitVillage1zoom(iddesa) {
  const digitasi = new google.maps.Data();
  const infoWindow = new google.maps.InfoWindow();

  digitasiValue = "map-v1r2";

  $.ajax({
    url: baseUrl + "media/map/kubugadang/" + digitasiValue + ".geojson", // Ubah sesuai dengan path file Anda
    type: "GET",
    dataType: "json",
    success: function (response) {
      const data = response; // Jika file .geojson diakses langsung melalui URL

      digitasi.addGeoJson(data);
      digitasi.setStyle({
        fillColor: "#fff",
        strokeWeight: 1,
        strokeColor: "#ffffff",
        fillOpacity: 0.1,
        clickable: true, // Set clickable to true to enable click event
        index: 6, // Set clickable to true to enable click event
      });
      map.setZoom(14);
      digitasi.setMap(map);

      digitVillage1Layers.push(digitasi);

      // Event listener for click
      digitasi.addListener("click", function (event) {
        const Name = event.feature.getProperty("DESA");
        console.log(Name);

        // Set label for the clicked feature using InfoWindow
        infoWindow.setContent("NAGARI " + Name);
        infoWindow.setPosition(event.latLng);
        infoWindow.open(map);
      });
    },
    error: function (error) {
      console.error("Error fetching GeoJSON file: " + error);
    },
  });
}

function digitVillage1(iddesa) {
  const digitasi = new google.maps.Data();
  const infoWindow = new google.maps.InfoWindow();

  digitasiValue = "map-v1r2";

  $.ajax({
    url: baseUrl + "media/map/kubugadang/" + digitasiValue + ".geojson", // Ubah sesuai dengan path file Anda
    type: "GET",
    dataType: "json",
    success: function (response) {
      const data = response; // Jika file .geojson diakses langsung melalui URL

      digitasi.addGeoJson(data);
      digitasi.setStyle({
        fillColor: "#fff",
        strokeWeight: 1,
        strokeColor: "#ffffff",
        fillOpacity: 0.1,
        clickable: true, // Set clickable to true to enable click event
        index: 6, // Set clickable to true to enable click event
      });
      map.setZoom(6);
      digitasi.setMap(map);

      digitVillage1Layers.push(digitasi);

      // Event listener for click
      digitasi.addListener("click", function (event) {
        const Name = event.feature.getProperty("DESA");
        console.log(Name);

        // Set label for the clicked feature using InfoWindow
        infoWindow.setContent("NAGARI " + Name);
        infoWindow.setPosition(event.latLng);
        infoWindow.open(map);
      });
    },
    error: function (error) {
      console.error("Error fetching GeoJSON file: " + error);
    },
  });
}
function digitVillage3(iddesa) {
  const digitasi = new google.maps.Data();
  const infoWindow = new google.maps.InfoWindow();

  digitasiValue = "map-v1r2";

  $.ajax({
    url: baseUrl + "media/map/output_folder3/" + digitasiValue + ".geojson", // Ubah sesuai dengan path file Anda
    type: "GET",
    dataType: "json",
    success: function (response) {
      const data = response; // Jika file .geojson diakses langsung melalui URL

      digitasi.addGeoJson(data);
      digitasi.setStyle({
        fillColor: "#03C988",
        strokeWeight: 1,
        strokeColor: "#ffffff",
        fillOpacity: 0.3,
        clickable: true, // Set clickable to true to enable click event
      });
      digitasi.setMap(map);

      // Event listener for click
      digitasi.addListener("click", function (event) {
        const Name = event.feature.getProperty("DESA");
        console.log(Name);

        // Set label for the clicked feature using InfoWindow
        infoWindow.setContent("NAGARI " + Name);
        infoWindow.setPosition(event.latLng);
        infoWindow.open(map);
      });
    },
    error: function (error) {
      console.error("Error fetching GeoJSON file: " + error);
    },
  });
}

// Remove user location
function clearUser() {
  userLat = 0;
  userLng = 0;
  userMarker.setMap(null);
}

// Set current location based on user location
function setUserLoc(lat, lng) {
  userLat = lat;
  userLng = lng;
  currentLat = userLat;
  currentLng = userLng;
}

// Remove any route shown
function clearRoute() {
  // console.log("Route array before clearing:", routeArray);

  for (i in routeArray) {
    routeArray[i].setMap(null);
  }
  routeArray = [];
  $("#direction-row").hide();
}

// function clearRoute() {
//   console.log("Route array before clearing:", routeArray);

//   if (routeArray.length > 0) {
//       routeArray.forEach(function(route) {
//           route.setMap(null); // Hapus rute dari peta
//       });
//       routeArray = []; // Reset array setelah semua rute dihapus
//   }
//   $("#direction-row").hide(); // Sembunyikan elemen terkait jika diperlukan
// }

// Remove any radius shown
function clearRadius() {
  for (i in circleArray) {
    circleArray[i].setMap(null);
  }
  circleArray = [];
}

// Remove any marker shown
function clearMarker() {
  for (i in markerArray) {
    markerArray[i].setMap(null);
  }
  markerArray = {};
}

// Get user's current position
function currentPosition() {
  clearRadius();
  clearRoute();
  clearAirplaneMarkers();
  clearCarMarkers();
  clearOverlay();

  google.maps.event.clearListeners(map, "click");
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      (position) => {
        const pos = {
          lat: position.coords.latitude,
          lng: position.coords.longitude,
        };

        infoWindow.close();
        clearUser();
        markerOption = {
          position: pos,
          animation: google.maps.Animation.DROP,
          map: map,
        };
        userMarker.setOptions(markerOption);
        userInfoWindow.setContent(
          "<p class='text-center'><span class='fw-bold'>You are here.</span> <br> lat: " +
            pos.lat +
            "<br>long: " +
            pos.lng +
            "</p>"
        );
        userInfoWindow.open(map, userMarker);
        map.setCenter(pos);
        setUserLoc(pos.lat, pos.lng);

        userMarker.addListener("click", () => {
          userInfoWindow.open(map, userMarker);
        });
      },
      () => {
        handleLocationError(true, userInfoWindow, map.getCenter());
      }
    );
  } else {
    // Browser doesn't support Geolocation
    handleLocationError(false, userInfoWindow, map.getCenter());
  }
}

// Error handler for geolocation
function handleLocationError(browserHasGeolocation, infoWindow, pos) {
  infoWindow.setPosition(pos);
  infoWindow.setContent(
    browserHasGeolocation
      ? "Error: The Geolocation service failed."
      : "Error: Your browser doesn't support geolocation."
  );
  infoWindow.open(map);
}

// User set position on map
function manualPosition() {
  clearRadius();
  clearRoute();
  clearDigitNeg();
  clearDigitProv();
  clearDigitKabKota();
  clearDigitKec();
  clearDigitNagari1();
  clearDigitVillage1();
  clearOverlay();
  clearAirplaneMarkers();
  clearCarMarkers();

  if (userLat == 0 && userLng == 0) {
    Swal.fire("Click on Map");
  }
  map.addListener("click", (mapsMouseEvent) => {
    infoWindow.close();
    pos = mapsMouseEvent.latLng;

    clearUser();
    markerOption = {
      position: pos,
      animation: google.maps.Animation.DROP,
      map: map,
    };
    userMarker.setOptions(markerOption);
    userInfoWindow.setContent(
      "<p class='text-center'><span class='fw-bold'>You are here.</span> <br> lat: " +
        pos.lat().toFixed(8) +
        "<br>long: " +
        pos.lng().toFixed(8) +
        "</p>"
    );
    userInfoWindow.open(map, userMarker);

    userMarker.addListener("click", () => {
      userInfoWindow.open(map, userMarker);
    });

    setUserLoc(pos.lat().toFixed(8), pos.lng().toFixed(8));
  });
}

// Render route on selected object
function routeTo(lat, lng, routeFromUser = true) {
  clearRadius();
  clearRoute();
  clearMarker();

  google.maps.event.clearListeners(map, "click");

  let start, end;
  if (routeFromUser) {
    if (userLat == 0 && userLng == 0) {
      return Swal.fire("Determine your position first!");
    }
    setUserLoc(userLat, userLng);
  }
  start = new google.maps.LatLng(currentLat, currentLng);
  end = new google.maps.LatLng(lat, lng);
  let request = {
    origin: start,
    destination: end,
    travelMode: "DRIVING",
  };
  directionsService.route(request, function (result, status) {
    if (status == "OK") {
      directionsRenderer.setDirections(result);
      showSteps(result);
      directionsRenderer.setMap(map);
      routeArray.push(directionsRenderer);
    }
  });
  boundToRoute(start, end);
}

// route between two sets of coordinates
function routeAllActivitiesInDay(activities) {
  for (let i = 0; i < activities.length - 1; i++) {
    routeBetweenObjects(
      activities[i].lat,
      activities[i].lng,
      activities[i + 1].lat,
      activities[i + 1].lng
    );
  }
}

// route between two sets of coordinates
function routeBetweenObjects(startLat, startLng, endLat, endLng) {
  clearRadius();
  clearRoute();
  clearMarker();
  // initMap();
  google.maps.event.clearListeners(map, "click");

  // Create LatLng objects for the start and end coordinates
  let start = new google.maps.LatLng(startLat, startLng);
  let end = new google.maps.LatLng(endLat, endLng);

  let request = {
    origin: start,
    destination: end,
    travelMode: "DRIVING",
  };

  directionsService.route(request, function (result, status) {
    if (status == "OK") {
      directionsRenderer.setDirections(result);
      showSteps(result);
      directionsRenderer.setMap(map);
      routeArray.push(directionsRenderer);
    }
  });

  boundToRoute(start, end);
}

// Display tourism attraction digitizing

// Display marker for loaded object
function objectMarker(id, lat, lng, status, homestay_status, anim = true) {
  google.maps.event.clearListeners(map, "click");
  let pos = new google.maps.LatLng(lat, lng);
  let marker = new google.maps.Marker();

  let icon;
  if (id.substring(0, 3) === "SUM") {
    icon = baseUrl + "/media/icon/marker_sumpu.png";
  } else if (id.substring(0, 2) === "AT") {
    icon = baseUrl + "/media/icon/attraction.png";
    const idattraction = id;
    digitAttraction(idattraction);
  } else if (id.substring(0, 2) === "EV") {
    icon = baseUrl + "/media/icon/event.png";
  } else if (id.substring(0, 1) === "P") {
    icon = baseUrl + "/media/icon/package.png";
  } else if (id.substring(0, 2) === "CP") {
    if (status === "1") {
      icon = baseUrl + "/media/icon/cpgtp.png";
    } else {
      icon = baseUrl + "/media/icon/culinary.png";
    }
    const idculinary = id;
    digitCulinary(idculinary);
  } else if (id.substring(0, 2) === "HO") {
    icon = baseUrl + "/media/icon/homestay.png";
    const idhomestay = id;
    digitHomestay(idhomestay);
  } else if (id.substring(0, 2) === "SP") {
    icon = baseUrl + "/media/icon/souvenir.png";
    const idsouvenir = id;
    digitSouvenir(idsouvenir);
  } else if (id.substring(0, 2) === "WP") {
    icon = baseUrl + "/media/icon/worship.png";
    const idworship = id;
    digitWorship(idworship);
  } else if (id.substring(0, 2) === "TH") {
    icon = baseUrl + "/media/icon/marker_rg.png";
    const idtraditional = id;
    digitTraditional(idtraditional);
  } else if (id.substring(0, 2) === "FC") {
    icon = baseUrl + "/media/icon/facility.png";
    const idfc = id;
    digitFacility(idfc);
  }

  markerOption = {
    position: pos,
    icon: icon,
    animation: google.maps.Animation.DROP,
    map: map,
  };
  marker.setOptions(markerOption);
  if (!anim) {
    marker.setAnimation(null);
  }
  marker.addListener("click", () => {
    infoWindow.close();
    objectInfoWindow(id);
    infoWindow.open(map, marker);
  });
  markerArray[id] = marker;
}

function objectMarkerRoute(id, lat, lng, anim = true) {
  google.maps.event.clearListeners(map, "click");
  let pos = new google.maps.LatLng(lat, lng);
  let marker = new google.maps.Marker();

  let icon;
  if (id.substring(0, 3) === "SUM") {
    icon = baseUrl + "/media/icon/marker_sumpu.png";
  } else if (id.substring(0, 2) === "AT") {
    if (
      id.substring(0, 5) === "AT004" ||
      id.substring(0, 5) === "AT005" ||
      id.substring(0, 5) === "AT008" ||
      id.substring(0, 5) === "AT013"
    ) {
      icon = baseUrl + "/media/icon/water.png";
    } else {
      icon = baseUrl + "/media/icon/attraction.png";
    }
    const idattraction = id;
    digitAttraction(idattraction);
  } else if (id.substring(0, 2) === "EV") {
    icon = baseUrl + "/media/icon/event.png";
  } else if (id.substring(0, 1) === "P") {
    icon = baseUrl + "/media/icon/package.png";
  } else if (id.substring(0, 2) === "HO") {
    icon = baseUrl + "/media/icon/homestay.png";
    const idhomestay = id;
    digitHomestay(idhomestay);
  } else if (id.substring(0, 2) === "FC") {
    icon = baseUrl + "/media/icon/facility.png";
    const idfacility = id;
    digitFacility(idfacility);
  } else if (id.substring(0, 2) === "CP") {
    icon = baseUrl + "/media/icon/culinary.png";
    const idculinary = id;
    digitCulinary(idculinary);
  } else if (id.substring(0, 2) === "TH") {
    icon = baseUrl + "/media/icon/marker_rg.png";
    const idtraditional = id;
    digitTraditional(idtraditional);
  } else if (id.substring(0, 2) === "HO") {
    icon = baseUrl + "/media/icon/homestay.png";
    const idrumah = id;
    digitRumah(idrumah);
  } else if (id.substring(0, 2) === "SP") {
    icon = baseUrl + "/media/icon/souvenir.png";
    const idsouvenir = id;
    digitSouvenir(idsouvenir);
  } else if (id.substring(0, 2) === "WP") {
    icon = baseUrl + "/media/icon/worship.png";
    const idworship = id;
    digitWorship(idworship);
  }

  markerOption = {
    position: pos,
    icon: icon,
    animation: google.maps.Animation.DROP,
    map: map,
  };
  marker.setOptions(markerOption);
  if (!anim) {
    marker.setAnimation(null);
  }
  marker.addListener("click", () => {
    infoWindow.close();
    objectInfoWindowRoute(id);
    infoWindow.open(map, marker);
  });
  markerArray[id] = marker;
}

function objectMarkerRouteNumber(id, lat, lng, anim = true, labelNumber) {
  google.maps.event.clearListeners(map, "click");
  let pos = new google.maps.LatLng(lat, lng);
  if (id.substring(0, 3) === "SUM") {
  } else if (id.substring(0, 2) === "AT") {
    const idattraction = id;
    digitAttraction(idattraction);
  } else if (id.substring(0, 2) === "EV") {
  } else if (id.substring(0, 1) === "P") {
  } else if (id.substring(0, 2) === "HO") {
    const idhomestay = id;
    digitHomestay(idhomestay);
  } else if (id.substring(0, 2) === "CP") {
    const idculinary = id;
    digitCulinary(idculinary);
  } else if (id.substring(0, 2) === "TH") {
    const idtraditional = id;
    digitTraditional(idtraditional);
  } else if (id.substring(0, 2) === "HO") {
    const idrumah = id;
    digitRumah(idrumah);
  } else if (id.substring(0, 2) === "SP") {
    const idsouvenir = id;
    digitSouvenir(idsouvenir);
  } else if (id.substring(0, 2) === "WP") {
    const idworship = id;
    digitWorship(idworship);
  }

  let marker = new google.maps.Marker({
    position: pos,
    label: {
      text: labelNumber.toString(),
      color: "white",
      fontWeight: "bold",
    },
    map: map,
  });

  // Tambahkan event listener untuk marker
  marker.addListener("click", () => {
    infoWindow.close();
    objectInfoWindowRoute(id);
    infoWindow.open(map, marker);
  });

  // Tambahkan marker ke dalam markerArray jika diperlukan
  markerArray[id] = marker;
}

function digitAttraction(idattraction) {
  const digitasi = new google.maps.Data();

  $.ajax({
    url: baseUrl + "/api/attraction",
    type: "POST",
    data: {
      digitasi: idattraction,
    },
    dataType: "json",
    success: function (response) {
      const data = response.data;
      digitasi.addGeoJson(data);
      digitasi.setStyle({
        fillColor: "#ff6d4d",
        strokeWeight: 0.3,
        strokeColor: "#ffffff",
        fillOpacity: 0.3,
      });
      digitasi.setMap(map);
      digitasiArray[idattraction] = digitasi;
    },
  });
  // console.log(digitasi);
}

function objectMarkerRouteMobile(id, lat, lng, anim = true) {
  google.maps.event.clearListeners(map, "click");
  let pos = new google.maps.LatLng(lat, lng);
  let marker = new google.maps.Marker();

  let icon;
  if (id.substring(0, 3) === "SUM") {
    icon = baseUrl + "/media/icon/marker_sumpu.png";
  } else if (id.substring(0, 2) === "AT") {
    icon = baseUrl + "/media/icon/attraction.png";
    const idattraction = id;
    digitAttraction(idattraction);
  } else if (id.substring(0, 2) === "EV") {
    icon = baseUrl + "/media/icon/event.png";
  } else if (id.substring(0, 1) === "P") {
    icon = baseUrl + "/media/icon/package.png";
  } else if (id.substring(0, 2) === "HO") {
    icon = baseUrl + "/media/icon/homestay.png";
    const idhomestay = id;
    digitHomestay(idhomestay);
  } else if (id.substring(0, 2) === "FC") {
    icon = baseUrl + "/media/icon/facility.png";
    const idfacility = id;
    digitFacility(idfacility);
  } else if (id.substring(0, 2) === "CP") {
    icon = baseUrl + "/media/icon/culinary.png";
    const idculinary = id;
    digitCulinary(idculinary);
  } else if (id.substring(0, 2) === "TH") {
    icon = baseUrl + "/media/icon/marker_rg.png";
    const idtraditional = id;
    digitTraditional(idtraditional);
  } else if (id.substring(0, 2) === "HO") {
    icon = baseUrl + "/media/icon/homestay.png";
    const idrumah = id;
    digitRumah(idrumah);
  } else if (id.substring(0, 2) === "SP") {
    icon = baseUrl + "/media/icon/souvenir.png";
    const idsouvenir = id;
    digitSouvenir(idsouvenir);
  } else if (id.substring(0, 2) === "WP") {
    icon = baseUrl + "/media/icon/worship.png";
    const idworship = id;
    digitWorship(idworship);
  }

  markerOption = {
    position: pos,
    icon: icon,
    animation: google.maps.Animation.DROP,
    map: map,
  };
  marker.setOptions(markerOption);
  if (!anim) {
    marker.setAnimation(null);
  }
  marker.addListener("click", () => {
    infoWindow.close();
    objectInfoWindowRouteMobile(id);
    infoWindow.open(map, marker);
  });
  markerArray[id] = marker;
}

function objectMarkerMobile(id, lat, lng, anim = true) {
  google.maps.event.clearListeners(map, "click");
  let pos = new google.maps.LatLng(lat, lng);
  let marker = new google.maps.Marker();

  let icon;
  if (id.substring(0, 1) === "A") {
    icon = baseUrl + "/media/icon/talao.png";
  } else if (id.substring(0, 2) === "HO") {
    icon = baseUrl + "/media/icon/homestay.png";
  } else if (id.substring(0, 2) === "TH") {
    icon = baseUrl + "/media/icon/marker_rg.png";
  }

  markerOption = {
    position: pos,
    icon: icon,
    animation: google.maps.Animation.DROP,
    map: map,
  };
  marker.setOptions(markerOption);
  if (!anim) {
    marker.setAnimation(null);
  }
  marker.addListener("click", () => {
    infoWindow.close();
    objectInfoWindowMobile(id);
    infoWindow.open(map, marker);
  });
  markerArray[id] = marker;
}

function howToReachSumpu() {
  clearAirplaneMarkers();
  clearCarMarkers();
  clearOverlay();
  clearMarker();
  clearRoute();
  clearRadius();

  objectMarker("SUM01", -0.4761815168531753, 100.43223933779609);

  // 1.192689, 103.910130

  // Coordinates
  const singapore = { lat: 1.192689, lng: 103.91013 }; // Singapore
  const malaysia = { lat: 3.1503614007038454, lng: 101.97940881384584 }; // Kuala Lumpur
  const jakarta = { lat: -6.516948, lng: 106.930035 }; // Jakarta
  const padang = { lat: -0.9478502987473912, lng: 100.3628232695202 }; // Padang
  const bandaAceh = { lat: 5.537368838813003, lng: 95.50780215398227 }; // Banda Aceh
  const nagariSumpu = { lat: -0.4761815168531753, lng: 100.43223933779609 }; // Nagari Sumpu
  const desaKubuGadang = { lat: -0.4761815168531753, lng: 100.43223933779609 }; // Nagari Sumpu

  // Animate flight
  function animateFlight(map, fromLatLng, toLatLng) {
    const airplaneIcon = {
      url: baseUrl + "/media/icon/airplane-icon.png", // Airplane icon path
      scaledSize: new google.maps.Size(60, 60), // Icon size
      anchor: new google.maps.Point(25, 25), // Center the icon
    };

    const airplaneMarker = new google.maps.Marker({
      position: fromLatLng,
      map: map,
      icon: airplaneIcon,
      title: "Flight",
    });

    airplaneMarkers.push(airplaneMarker); // Store marker for later clearing

    let step = 0;
    const totalSteps = 100; // Number of animation steps
    const interval = setInterval(() => {
      if (step <= totalSteps) {
        const lat =
          fromLatLng.lat +
          (toLatLng.lat - fromLatLng.lat) * (step / totalSteps);
        const lng =
          fromLatLng.lng +
          (toLatLng.lng - fromLatLng.lng) * (step / totalSteps);
        const newPosition = { lat, lng };
        airplaneMarker.setPosition(newPosition);
        step++;
      } else {
        clearInterval(interval); // Stop animation when complete
      }
    }, 50); // Animation speed (50ms per step)
  }

  // Animate car
  function animateCar(map, fromLatLng, toLatLng) {
    const carIcon = {
      url: baseUrl + "/media/icon/car2.png", // Airplane icon path
      scaledSize: new google.maps.Size(50, 50), // Icon size
      anchor: new google.maps.Point(20, 20), // Center the icon
    };

    const carMarker = new google.maps.Marker({
      position: fromLatLng,
      map: map,
      icon: carIcon,
      title: "Car Journey",
      zIndex: 1000,
    });
    carMarkers.push(carMarker); // Store marker for later clearing

    let step = 0;
    const totalSteps = 100;
    const interval = setInterval(() => {
      if (step <= totalSteps) {
        const lat =
          fromLatLng.lat +
          (toLatLng.lat - fromLatLng.lat) * (step / totalSteps);
        const lng =
          fromLatLng.lng +
          (toLatLng.lng - fromLatLng.lng) * (step / totalSteps);
        const newPosition = { lat, lng };
        carMarker.setPosition(newPosition);
        step++;
      } else {
        clearInterval(interval);
      }
    }, 50);
  }

  // Add text overlays
  function createTextOverlay(map, position, steps) {
    const overlay = new google.maps.OverlayView();

    overlay.onAdd = function () {
      const div = document.createElement("div");
      div.style.position = "absolute";
      div.style.fontSize = "14px";
      div.style.fontWeight = "bold";
      div.style.color = "#4a2f13";
      div.style.backgroundColor = "#ffe6cc";
      div.style.padding = "10px";
      div.style.borderRadius = "5px";
      div.style.boxShadow = "0 2px 6px rgba(0, 0, 0, 0.3)";
      div.style.zIndex = "9999";
      div.innerHTML = steps;

      const panes = this.getPanes();
      panes.overlayLayer.appendChild(div);

      this.draw = function () {
        const projection = this.getProjection();
        const positionPixel = projection.fromLatLngToDivPixel(position);
        div.style.left = `${positionPixel.x}px`;
        div.style.top = `${positionPixel.y}px`;
      };

      overlay.div = div; // Simpan referensi ke elemen DOM
    };

    overlay.onRemove = function () {
      if (overlay.div) {
        overlay.div.parentNode.removeChild(overlay.div);
        overlay.div = null;
      }
    };

    overlay.setMap(map);
    overlays.push(overlay); // Simpan overlay dalam array
    return overlay;
  }

  // Map animations
  animateFlight(map, singapore, padang);
  animateFlight(map, malaysia, padang);
  animateCar(map, bandaAceh, desaKubuGadang);
  animateFlight(map, jakarta, padang);

  setTimeout(() => {
    animateCar(map, padang, desaKubuGadang);
  }, 6000); // Delay of 6 seconds before car animation

  // Add overlays
  createTextOverlay(
    map,
    singapore,
    `
    <div style="display: flex; align-items: center;">
      
      <div>
        <b>From Singapore <img src="${baseUrl}/media/icon/sg.svg" alt="Singapore Flag" style="width: 24px; height: 16px; margin-right: 4px;">(SIN):</b><br>
        1. Take a flight from Singapore (SIN) to Padang (PDG), Indonesia.<br>
        2. Rent a car to Kubu Gadang Village.
      </div>
    </div>
  `
  );

  createTextOverlay(
    map,
    malaysia,
    `
    <div style="display: flex; align-items: center;">
      
      <div>
        <b>From Kuala Lumpur <img src="${baseUrl}/media/icon/my.svg" alt="Malaysia Flag" style="width: 24px; height: 16px; margin-right: 4px;">(KUL):</b><br>
        1. Take a flight from Kuala Lumpur (KUL) to Padang (PDG), Indonesia.<br>
        2. Rent a car to Kubu Gadang Village.
      </div>
    </div>
  `
  );

  createTextOverlay(
    map,
    jakarta,
    `
    <div style="display: flex; align-items: center;">
      
      <div>
        <b>From Jakarta <img src="${baseUrl}/media/icon/id.svg" alt="Indonesia Flag" style="width: 24px; height: 16px; margin-right: 4px;">:</b><br>
        1. Take a domestic flight to Padang (PDG), Indonesia.<br>
        2. Rent a car to Kubu Gadang Village.
      </div>
    </div>
  `
  );

  createTextOverlay(
    map,
    bandaAceh,
    `
    <div style="display: flex; align-items: center;">      
      <div>
        <b>From anywhere in Sumatra <img src="${baseUrl}/media/icon/id.svg" alt="Indonesia Flag" style="width: 24px; height: 16px; margin-right: 4px;">:</b><br>
        1. Travel by land directly to Kubu Gadang Village.<br>
        2. Alternatively, fly to Padang (PDG) and rent a car to Kubu Gadang Village.
      </div>
    </div>
  `
  );

  map.setZoom(6);
}

function zoomToSumpuMarkers() {
  // clearAirplaneMarkers();
  // clearCarMarkers();
  clearOverlay();
  console.log("zoomToSumpuMarkers triggered"); // Debug
  console.log("markerArray:", markerArray); // Debugging marker array

  for (const id in markerArray) {
    if (id.substring(0, 3) === "SUM") {
      const marker = markerArray[id];
      console.log("Found marker:", marker); // Debug marker
      map.setCenter(marker.getPosition());
      map.setZoom(16);
    }
  }
  // objectMarker("SUM01", -0.4761815168531753, 100.43223933779609);
  // boundToObject();
}

// Display info window for loaded object
function objectInfoWindow(id) {
  let content = "";
  let contentButton = "";

  if (id.substring(0, 3) === "SUM") {
    $.ajax({
      url: baseUrl + "/api/kubugadang/" + id,
      dataType: "json",
      success: function (response) {
        let data = response.data;
        let name = data.name;
        console.log(response.data);

        content =
          '<div style="max-width:200px;max-height:300px;" class="text-center">' +
          '<p class="fw-bold fs-6">' +
          name +
          "</p>" +
          '<p><i class="fa-solid fa-spa"></i> Tourism Village</p>' +
          "</div>";

        infoWindow.setContent(content);
      },
    });
  } else if (id.substring(0, 1) === "A") {
    $.ajax({
      url: baseUrl + "/api/attraction/" + id,
      dataType: "json",
      success: function (response) {
        let data = response.data;
        let aid = data.id;
        let name = data.name;
        let lat = data.lat;
        let lng = data.lng;
        let type = data.type;
        let price = data.price == 0 ? "Free" : formatter.format(data.price);

        content =
          '<div style="max-width:200px;max-height:300px;" class="text-center">' +
          '<p class="fw-bold fs-6">' +
          name +
          "</p>" +
          '<p><i class="fa-solid fa-spa"></i> ' +
          type +
          "</p>" +
          '<p><i class="fa-solid fa-money-bill me-2"></i> ' +
          price +
          "</p>" +
          "</div>";

        contentButton =
          '<div class="text-center">' +
          '<a title="Route" class="btn icon btn-outline-primary mx-1" id="routeInfoWindow" onclick="routeTo(' +
          lat +
          ", " +
          lng +
          ')"><i class="fa-solid fa-road"></i></a>' +
          '<a title="Info" class="btn icon btn-outline-primary mx-1" target="_blank" id="infoInfoWindow" href=' +
          baseUrl +
          "/web/attraction/" +
          aid +
          '><i class="fa-solid fa-info"></i></a>' +
          "</div>";

        if (currentUrl.includes(id)) {
          infoWindow.setContent(content);
          infoWindow.open(map, markerArray[aid]);
        } else {
          infoWindow.setContent(content + contentButton);
        }
      },
    });
  } else if (id.substring(0, 2) === "EV") {
    $.ajax({
      url: baseUrl + "/api/event/" + id,
      dataType: "json",
      success: function (response) {
        let data = response.data;
        let evid = data.id;
        let name = data.name;
        let type = data.type;
        // let lat = data.lat;
        // let lng = data.lng;
        let price = data.price == 0 ? "Free" : formatter.format(data.price);

        content =
          '<div style="max-width:200px;max-height:300px;" class="text-center">' +
          '<p class="fw-bold fs-6">' +
          name +
          "</p>" +
          '<p><i class="fa-solid fa-spa"></i> ' +
          type +
          "</p>" +
          '<p><i class="fa-solid fa-money-bill me-2"></i> ' +
          price +
          "</p>" +
          "</div>";
        contentButton =
          '<div class="text-center">' +
          '<a title="Info" class="btn icon btn-outline-primary mx-1" target="_blank" id="infoInfoWindow" href=' +
          baseUrl +
          "/web/event/" +
          evid +
          '><i class="fa-solid fa-info"></i></a>' +
          "</div>";

        if (currentUrl.includes(id)) {
          infoWindow.setContent(content);
          infoWindow.open(map, markerArray[evid]);
        } else {
          infoWindow.setContent(content + contentButton);
        }
      },
    });
  } else if (id.substring(0, 1) === "P") {
    $.ajax({
      url: baseUrl + "/api/package/" + id,
      dataType: "json",
      success: function (response) {
        let data = response.data;
        let paid = data.id;
        let name = data.name;
        // let lat = data.lat;
        // let lng = data.lng;
        let type_name = data.type_name;
        let price = data.price == 0 ? "Free" : formatter.format(data.price);

        content =
          '<div style="max-width:200px;max-height:300px;" class="text-center">' +
          '<p class="fw-bold fs-6">' +
          name +
          "</p>" +
          '<p><i class="fa-solid fa-spa"></i> ' +
          type_name +
          "</p>" +
          '<p><i class="fa-solid fa-money-bill me-2"></i> ' +
          price +
          "</p>" +
          "</div>";
        contentButton =
          '<div class="text-center">' +
          '<a title="Info" class="btn icon btn-outline-primary mx-1" target="_blank" id="infoInfoWindow" href=' +
          baseUrl +
          "/web/package/" +
          paid +
          '><i class="fa-solid fa-info"></i></a>' +
          "</div>";

        if (currentUrl.includes(id)) {
          infoWindow.setContent(content);
          infoWindow.open(map, markerArray[paid]);
        } else {
          infoWindow.setContent(content + contentButton);
        }
      },
    });
  } else if (id.substring(0, 2) === "FC") {
    $.ajax({
      url: baseUrl + "/api/facility/" + id,
      dataType: "json",
      success: function (response) {
        let data = response.data;
        let aid = data.id;
        let name = data.name;
        let lat = data.lat;
        let lng = data.lng;
        let price = data.price == 0 ? "Free" : formatter.format(data.price);

        content =
          '<div style="max-width:200px;max-height:300px;" class="text-center">' +
          '<p class="fw-bold fs-6">' +
          name +
          "</p>" +
          '<p><i class="fa-solid fa-spa"></i> Facility' +
          "</p>" +
          '<p><i class="fa-solid fa-money-bill me-2"></i> ' +
          price +
          "</p>" +
          "</div>";

        contentButton =
          '<div class="text-center">' +
          '<a title="Route" class="btn icon btn-outline-primary mx-1" id="routeInfoWindow" onclick="routeTo(' +
          lat +
          ", " +
          lng +
          ')"><i class="fa-solid fa-road"></i></a>' +
          '<a title="Info" class="btn icon btn-outline-primary mx-1" target="_blank" id="infoInfoWindow" href=' +
          baseUrl +
          "/web/facility/" +
          aid +
          '><i class="fa-solid fa-info"></i></a>' +
          "</div>";

        if (currentUrl.includes(id)) {
          infoWindow.setContent(content);
          infoWindow.open(map, markerArray[aid]);
        } else {
          infoWindow.setContent(content + contentButton);
        }
      },
    });
  } else if (id.substring(0, 2) === "HO") {
    $.ajax({
      url: baseUrl + "/api/homestay/" + id,
      dataType: "json",
      success: function (response) {
        let data = response.data;
        let hoid = data.id;
        let name = data.name;
        let lat = data.lat;
        let lng = data.lng;
        let contact_person =
          data.contact_person == 0 ? "-" : data.contact_person;
        let address = data.address;

        content =
          '<div style="max-width:200px;max-height:300px;" class="text-center">' +
          '<p class="fw-bold fs-6">' +
          name +
          "</p>" +
          '<p><i class="fa-solid fa-phone me-2"></i> ' +
          contact_person +
          "</p>" +
          '<p><i class="fa-solid fa-map-pin"></i> ' +
          address +
          "</p>" +
          "</div>";
        contentButton =
          '<div class="text-center">' +
          '<a title="Route" class="btn icon btn-outline-primary mx-1" id="routeInfoWindow" onclick="routeTo(' +
          lat +
          ", " +
          lng +
          ')"><i class="fa-solid fa-road"></i></a>' +
          '<a title="Info" class="btn icon btn-outline-primary mx-1" target="_blank" id="infoInfoWindow" href=' +
          baseUrl +
          "/web/homestay/" +
          hoid +
          '><i class="fa-solid fa-info"></i></a>' +
          "</div>";
        console.log(currentUrl);
        if (currentUrl.includes(id)) {
          if (currentUrl.startsWith("api")) {
            infoWindow.setContent(content + contentButton);
          } else {
            infoWindow.setContent(content);
          }
          infoWindow.open(map, markerArray[hoid]);
        } else {
          infoWindow.setContent(content + contentButton);
        }
      },
    });
  } else if (id.substring(0, 2) === "CP") {
    $.ajax({
      url: baseUrl + "/api/culinaryPlace/" + id,
      dataType: "json",
      success: function (response) {
        let data = response.data;
        let cpid = data.id;
        let name = data.name;
        let lat = data.lat;
        let lng = data.lng;
        let contact = data.contact_person;
        let address = data.address;

        content =
          '<div style="max-width:200px;max-height:300px;" class="text-center">' +
          '<p class="fw-bold fs-6">' +
          name +
          "</p>" +
          '<p><i class="fa-solid fa-address-book"></i> ' +
          contact +
          "</p>" +
          '<p><i class="fa-solid fa-map-pin"></i> ' +
          address +
          "</p>" +
          "</div>";
        contentButton =
          '<div class="text-center">' +
          '<a title="Route" class="btn icon btn-outline-primary mx-1" id="routeInfoWindow" onclick="routeTo(' +
          lat +
          ", " +
          lng +
          ')"><i class="fa-solid fa-road"></i></a>' +
          '<a title="Info" class="btn icon btn-outline-primary mx-1" target="_blank" id="infoInfoWindow" href=' +
          baseUrl +
          "/web/culinaryPlace/" +
          cpid +
          '><i class="fa-solid fa-info"></i></a>' +
          "</div>";

        if (currentUrl.includes(id)) {
          infoWindow.setContent(content);
          infoWindow.open(map, markerArray[cpid]);
        } else {
          infoWindow.setContent(content + contentButton);
        }
      },
    });
  } else if (id.substring(0, 2) === "TH") {
    $.ajax({
      url: baseUrl + "/api/traditionalHouse/" + id,
      dataType: "json",
      success: function (response) {
        let data = response.data;
        let cpid = data.id;
        let name = data.name;
        let lat = data.lat;
        let lng = data.lng;
        let contact = data.contact_person;
        let address = data.address;

        content =
          '<div style="max-width:200px;max-height:300px;" class="text-center">' +
          '<p class="fw-bold fs-6">' +
          name +
          "</p>" +
          '<p><i class="fa-solid fa-address-book"></i> ' +
          contact +
          "</p>" +
          '<p><i class="fa-solid fa-map-pin"></i> ' +
          address +
          "</p>" +
          "</div>";
        contentButton =
          '<div class="text-center">' +
          '<a title="Route" class="btn icon btn-outline-primary mx-1" id="routeInfoWindow" onclick="routeTo(' +
          lat +
          ", " +
          lng +
          ')"><i class="fa-solid fa-road"></i></a>' +
          '<a title="Info" class="btn icon btn-outline-primary mx-1" target="_blank" id="infoInfoWindow" href=' +
          baseUrl +
          "/web/traditionalHouse/" +
          cpid +
          '><i class="fa-solid fa-info"></i></a>' +
          "</div>";

        if (currentUrl.includes(id)) {
          infoWindow.setContent(content);
          infoWindow.open(map, markerArray[cpid]);
        } else {
          infoWindow.setContent(content + contentButton);
        }
      },
    });
  } else if (id.substring(0, 2) === "HO") {
    $.ajax({
      url: baseUrl + "/api/homestay/" + id,
      dataType: "json",
      success: function (response) {
        let data = response.data;
        let cpid = data.id;
        let name = data.name;
        let lat = data.lat;
        let lng = data.lng;
        let contact = data.contact_person;
        let address = data.address;

        content =
          '<div style="max-width:200px;max-height:300px;" class="text-center">' +
          '<p class="fw-bold fs-6">' +
          name +
          "</p>" +
          '<p><i class="fa-solid fa-address-book"></i> ' +
          contact +
          "</p>" +
          '<p><i class="fa-solid fa-map-pin"></i> ' +
          address +
          "</p>" +
          "</div>";
        contentButton =
          '<div class="text-center">' +
          '<a title="Route" class="btn icon btn-outline-primary mx-1" id="routeInfoWindow" onclick="routeTo(' +
          lat +
          ", " +
          lng +
          ')"><i class="fa-solid fa-road"></i></a>' +
          '<a title="Info" class="btn icon btn-outline-primary mx-1" target="_blank" id="infoInfoWindow" href=' +
          baseUrl +
          "/web/homestay/" +
          cpid +
          '><i class="fa-solid fa-info"></i></a>' +
          "</div>";

        if (currentUrl.includes(id)) {
          infoWindow.setContent(content);
          infoWindow.open(map, markerArray[cpid]);
        } else {
          infoWindow.setContent(content + contentButton);
        }
      },
    });
  } else if (id.substring(0, 2) === "SP") {
    $.ajax({
      url: baseUrl + "/api/souvenirPlace/" + id,
      dataType: "json",
      success: function (response) {
        let data = response.data;
        let spid = data.id;
        let name = data.name;
        let lat = data.lat;
        let lng = data.lng;
        let contact = data.contact_person;
        let address = data.address;

        content =
          '<div style="max-width:200px;max-height:300px;" class="text-center">' +
          '<p class="fw-bold fs-6">' +
          name +
          "</p>" +
          '<p><i class="fa-solid fa-address-book"></i> ' +
          contact +
          "</p>" +
          '<p><i class="fa-solid fa-map-pin"></i> ' +
          address +
          "</p>" +
          "</div>";
        contentButton =
          '<div class="text-center">' +
          '<a title="Route" class="btn icon btn-outline-primary mx-1" id="routeInfoWindow" onclick="routeTo(' +
          lat +
          ", " +
          lng +
          ')"><i class="fa-solid fa-road"></i></a>' +
          '<a title="Info" class="btn icon btn-outline-primary mx-1" target="_blank" id="infoInfoWindow" href=' +
          baseUrl +
          "/web/souvenirPlace/" +
          spid +
          '><i class="fa-solid fa-info"></i></a>' +
          "</div>";

        if (currentUrl.includes(id)) {
          infoWindow.setContent(content);
          infoWindow.open(map, markerArray[spid]);
        } else {
          infoWindow.setContent(content + contentButton);
        }
      },
    });
  } else if (id.substring(0, 2) === "WP") {
    $.ajax({
      url: baseUrl + "/api/worshipPlace/" + id,
      dataType: "json",
      success: function (response) {
        let data = response.data;
        let wpid = data.id;
        let name = data.name;
        let lat = data.lat;
        let lng = data.lng;
        let capacity = data.capacity;
        let address = data.address;

        content =
          '<div style="max-width:200px;max-height:300px;" class="text-center">' +
          '<p class="fw-bold fs-6">' +
          name +
          "</p>" +
          '<p><i class="fa-solid fa-person-praying"></i> ' +
          capacity +
          "</p>" +
          '<p><i class="fa-solid fa-map-pin"></i> ' +
          address +
          "</p>" +
          "</div>";
        contentButton =
          '<div class="text-center">' +
          '<a title="Route" class="btn icon btn-outline-primary mx-1" id="routeInfoWindow" onclick="routeTo(' +
          lat +
          ", " +
          lng +
          ')"><i class="fa-solid fa-road"></i></a>' +
          '<a title="Info" class="btn icon btn-outline-primary mx-1" target="_blank" id="infoInfoWindow" href=' +
          baseUrl +
          "/web/worshipPlace/" +
          wpid +
          '><i class="fa-solid fa-info"></i></a>' +
          "</div>";

        if (currentUrl.includes(id)) {
          infoWindow.setContent(content);
          infoWindow.open(map, markerArray[wpid]);
        } else {
          infoWindow.setContent(content + contentButton);
        }
      },
    });
  }
}

function objectInfoWindowRoute(id) {
  let content = "";
  let contentButton = "";

  if (id.substring(0, 3) === "GTP") {
    $.ajax({
      url: baseUrl + "/api/gtp/" + id,
      dataType: "json",
      success: function (response) {
        let data = response.data;
        let name = data.name;

        content =
          '<div style="max-width:200px;max-height:300px;" class="text-center">' +
          '<p class="fw-bold fs-6">' +
          name +
          "</p>" +
          '<p><i class="fa-solid fa-spa"></i> Tourism Village</p>' +
          "</div>";

        infoWindow.setContent(content);
      },
    });
  } else if (id.substring(0, 1) === "A") {
    $.ajax({
      url: baseUrl + "/api/attraction/" + id,
      dataType: "json",
      success: function (response) {
        let data = response.data;
        let aid = data.id;
        let name = data.name;
        let lat = data.lat;
        let lng = data.lng;
        let type = data.type;
        let price = data.price == 0 ? "Free" : formatter.format(data.price);

        content =
          '<div style="max-width:200px;max-height:300px;" class="text-center">' +
          '<p class="fw-bold fs-6">' +
          name +
          "</p>" +
          '<p><i class="fa-solid fa-spa"></i> ' +
          type +
          "</p>" +
          '<p><i class="fa-solid fa-money-bill me-2"></i> ' +
          price +
          "</p>" +
          "</div>";

        contentButton =
          '<div class="text-center">' +
          '<a title="Route" class="btn icon btn-outline-primary mx-1" id="routeInfoWindow" onclick="routeTo(' +
          lat +
          ", " +
          lng +
          ')"><i class="fa-solid fa-road"></i></a>' +
          '<a title="Info" class="btn icon btn-outline-primary mx-1" target="_blank" id="infoInfoWindow" href=' +
          baseUrl +
          "/web/attraction/" +
          aid +
          '><i class="fa-solid fa-info"></i></a>' +
          "</div>";

        if (currentUrl.includes(id)) {
          infoWindow.setContent(content);
          infoWindow.open(map, markerArray[aid]);
        } else {
          infoWindow.setContent(content + contentButton);
        }
      },
    });
  } else if (id.substring(0, 2) === "EV") {
    $.ajax({
      url: baseUrl + "/api/event/" + id,
      dataType: "json",
      success: function (response) {
        let data = response.data;
        let evid = data.id;
        let name = data.name;
        let type = data.type;
        // let lat = data.lat;
        // let lng = data.lng;
        let price = data.price == 0 ? "Free" : formatter.format(data.price);

        content =
          '<div style="max-width:200px;max-height:300px;" class="text-center">' +
          '<p class="fw-bold fs-6">' +
          name +
          "</p>" +
          '<p><i class="fa-solid fa-spa"></i> ' +
          type +
          "</p>" +
          '<p><i class="fa-solid fa-money-bill me-2"></i> ' +
          price +
          "</p>" +
          "</div>";
        contentButton =
          '<div class="text-center">' +
          '<a title="Info" class="btn icon btn-outline-primary mx-1" target="_blank" id="infoInfoWindow" href=' +
          baseUrl +
          "/web/event/" +
          evid +
          '><i class="fa-solid fa-info"></i></a>' +
          "</div>";

        if (currentUrl.includes(id)) {
          infoWindow.setContent(content);
          infoWindow.open(map, markerArray[evid]);
        } else {
          infoWindow.setContent(content + contentButton);
        }
      },
    });
  } else if (id.substring(0, 1) === "P") {
    $.ajax({
      url: baseUrl + "/api/package/" + id,
      dataType: "json",
      success: function (response) {
        let data = response.data;
        let paid = data.id;
        let name = data.name;
        // let lat = data.lat;
        // let lng = data.lng;
        let type_name = data.type_name;
        let price = data.price == 0 ? "Free" : formatter.format(data.price);

        content =
          '<div style="max-width:200px;max-height:300px;" class="text-center">' +
          '<p class="fw-bold fs-6">' +
          name +
          "</p>" +
          '<p><i class="fa-solid fa-spa"></i> ' +
          type_name +
          "</p>" +
          '<p><i class="fa-solid fa-money-bill me-2"></i> ' +
          price +
          "</p>" +
          "</div>";
        contentButton =
          '<div class="text-center">' +
          '<a title="Info" class="btn icon btn-outline-primary mx-1" target="_blank" id="infoInfoWindow" href=' +
          baseUrl +
          "/web/package/" +
          paid +
          '><i class="fa-solid fa-info"></i></a>' +
          "</div>";

        if (currentUrl.includes(id)) {
          infoWindow.setContent(content);
          infoWindow.open(map, markerArray[paid]);
        } else {
          infoWindow.setContent(content + contentButton);
        }
      },
    });
  } else if (id.substring(0, 2) === "FC") {
    $.ajax({
      url: baseUrl + "/api/facility/" + id,
      dataType: "json",
      success: function (response) {
        let data = response.data;
        let aid = data.id;
        let name = data.name;
        let lat = data.lat;
        let lng = data.lng;
        let price = data.price == 0 ? "Free" : formatter.format(data.price);

        content =
          '<div style="max-width:200px;max-height:300px;" class="text-center">' +
          '<p class="fw-bold fs-6">' +
          name +
          "</p>" +
          '<p><i class="fa-solid fa-spa"></i> Facility' +
          "</p>" +
          '<p><i class="fa-solid fa-money-bill me-2"></i> ' +
          price +
          "</p>" +
          "</div>";

        contentButton =
          '<div class="text-center">' +
          '<a title="Route" class="btn icon btn-outline-primary mx-1" id="routeInfoWindow" onclick="routeTo(' +
          lat +
          ", " +
          lng +
          ')"><i class="fa-solid fa-road"></i></a>' +
          '<a title="Info" class="btn icon btn-outline-primary mx-1" target="_blank" id="infoInfoWindow" href=' +
          baseUrl +
          "/web/facility/" +
          aid +
          '><i class="fa-solid fa-info"></i></a>' +
          "</div>";

        if (currentUrl.includes(id)) {
          infoWindow.setContent(content);
          infoWindow.open(map, markerArray[aid]);
        } else {
          infoWindow.setContent(content + contentButton);
        }
      },
    });
  } else if (id.substring(0, 2) === "HO") {
    $.ajax({
      url: baseUrl + "/api/homestay/" + id,
      dataType: "json",
      success: function (response) {
        let data = response.data;
        let hoid = data.id;
        let name = data.name;
        let lat = data.lat;
        let lng = data.lng;
        let contact_person =
          data.contact_person == 0 ? "-" : data.contact_person;
        let address = data.address;

        content =
          '<div style="max-width:200px;max-height:300px;" class="text-center">' +
          '<p class="fw-bold fs-6">' +
          name +
          "</p>" +
          '<p><i class="fa-solid fa-phone me-2"></i> ' +
          contact_person +
          "</p>" +
          '<p><i class="fa-solid fa-map-pin"></i> ' +
          address +
          "</p>" +
          "</div>";
        contentButton =
          '<div class="text-center">' +
          '<a title="Route" class="btn icon btn-outline-primary mx-1" id="routeInfoWindow" onclick="routeTo(' +
          lat +
          ", " +
          lng +
          ')"><i class="fa-solid fa-road"></i></a>' +
          '<a title="Info" class="btn icon btn-outline-primary mx-1" target="_blank" id="infoInfoWindow" href=' +
          baseUrl +
          "/web/homestay/" +
          hoid +
          '><i class="fa-solid fa-info"></i></a>' +
          "</div>";
        console.log(currentUrl);
        if (currentUrl.includes(id)) {
          if (currentUrl.startsWith("api")) {
            infoWindow.setContent(content + contentButton);
          } else {
            infoWindow.setContent(content);
          }
          infoWindow.open(map, markerArray[hoid]);
        } else {
          infoWindow.setContent(content + contentButton);
        }
      },
    });
  } else if (id.substring(0, 2) === "CP") {
    $.ajax({
      url: baseUrl + "/api/culinaryPlace/" + id,
      dataType: "json",
      success: function (response) {
        let data = response.data;
        let cpid = data.id;
        let name = data.name;
        let lat = data.lat;
        let lng = data.lng;
        let contact = data.contact_person;
        let address = data.address;

        content =
          '<div style="max-width:200px;max-height:300px;" class="text-center">' +
          '<p class="fw-bold fs-6">' +
          name +
          "</p>" +
          '<p><i class="fa-solid fa-address-book"></i> ' +
          contact +
          "</p>" +
          '<p><i class="fa-solid fa-map-pin"></i> ' +
          address +
          "</p>" +
          "</div>";
        contentButton =
          '<div class="text-center">' +
          '<a title="Route" class="btn icon btn-outline-primary mx-1" id="routeInfoWindow" onclick="routeTo(' +
          lat +
          ", " +
          lng +
          ')"><i class="fa-solid fa-road"></i></a>' +
          '<a title="Info" class="btn icon btn-outline-primary mx-1" target="_blank" id="infoInfoWindow" href=' +
          baseUrl +
          "/web/culinaryPlace/" +
          cpid +
          '><i class="fa-solid fa-info"></i></a>' +
          "</div>";

        if (currentUrl.includes(id)) {
          infoWindow.setContent(content);
          infoWindow.open(map, markerArray[cpid]);
        } else {
          infoWindow.setContent(content + contentButton);
        }
      },
    });
  } else if (id.substring(0, 2) === "TH") {
    $.ajax({
      url: baseUrl + "/api/traditionalHouse/" + id,
      dataType: "json",
      success: function (response) {
        let data = response.data;
        let cpid = data.id;
        let name = data.name;
        let lat = data.lat;
        let lng = data.lng;
        let contact = data.contact_person;
        let address = data.address;

        content =
          '<div style="max-width:200px;max-height:300px;" class="text-center">' +
          '<p class="fw-bold fs-6">' +
          name +
          "</p>" +
          '<p><i class="fa-solid fa-address-book"></i> ' +
          contact +
          "</p>" +
          '<p><i class="fa-solid fa-map-pin"></i> ' +
          address +
          "</p>" +
          "</div>";
        contentButton =
          '<div class="text-center">' +
          '<a title="Route" class="btn icon btn-outline-primary mx-1" id="routeInfoWindow" onclick="routeTo(' +
          lat +
          ", " +
          lng +
          ')"><i class="fa-solid fa-road"></i></a>' +
          '<a title="Info" class="btn icon btn-outline-primary mx-1" target="_blank" id="infoInfoWindow" href=' +
          baseUrl +
          "/web/traditionalHouse/" +
          cpid +
          '><i class="fa-solid fa-info"></i></a>' +
          "</div>";

        if (currentUrl.includes(id)) {
          infoWindow.setContent(content);
          infoWindow.open(map, markerArray[cpid]);
        } else {
          infoWindow.setContent(content + contentButton);
        }
      },
    });
  } else if (id.substring(0, 2) === "HO") {
    $.ajax({
      url: baseUrl + "/api/homestay/" + id,
      dataType: "json",
      success: function (response) {
        let data = response.data;
        let cpid = data.id;
        let name = data.name;
        let lat = data.lat;
        let lng = data.lng;
        let contact = data.contact_person;
        let address = data.address;

        content =
          '<div style="max-width:200px;max-height:300px;" class="text-center">' +
          '<p class="fw-bold fs-6">' +
          name +
          "</p>" +
          '<p><i class="fa-solid fa-address-book"></i> ' +
          contact +
          "</p>" +
          '<p><i class="fa-solid fa-map-pin"></i> ' +
          address +
          "</p>" +
          "</div>";
        contentButton =
          '<div class="text-center">' +
          '<a title="Route" class="btn icon btn-outline-primary mx-1" id="routeInfoWindow" onclick="routeTo(' +
          lat +
          ", " +
          lng +
          ')"><i class="fa-solid fa-road"></i></a>' +
          '<a title="Info" class="btn icon btn-outline-primary mx-1" target="_blank" id="infoInfoWindow" href=' +
          baseUrl +
          "/web/homestay/" +
          cpid +
          '><i class="fa-solid fa-info"></i></a>' +
          "</div>";

        if (currentUrl.includes(id)) {
          infoWindow.setContent(content);
          infoWindow.open(map, markerArray[cpid]);
        } else {
          infoWindow.setContent(content + contentButton);
        }
      },
    });
  } else if (id.substring(0, 2) === "SP") {
    $.ajax({
      url: baseUrl + "/api/souvenirPlace/" + id,
      dataType: "json",
      success: function (response) {
        let data = response.data;
        let spid = data.id;
        let name = data.name;
        let lat = data.lat;
        let lng = data.lng;
        let contact = data.contact_person;
        let address = data.address;

        content =
          '<div style="max-width:200px;max-height:300px;" class="text-center">' +
          '<p class="fw-bold fs-6">' +
          name +
          "</p>" +
          '<p><i class="fa-solid fa-address-book"></i> ' +
          contact +
          "</p>" +
          '<p><i class="fa-solid fa-map-pin"></i> ' +
          address +
          "</p>" +
          "</div>";
        contentButton =
          '<div class="text-center">' +
          '<a title="Route" class="btn icon btn-outline-primary mx-1" id="routeInfoWindow" onclick="routeTo(' +
          lat +
          ", " +
          lng +
          ')"><i class="fa-solid fa-road"></i></a>' +
          '<a title="Info" class="btn icon btn-outline-primary mx-1" target="_blank" id="infoInfoWindow" href=' +
          baseUrl +
          "/web/souvenirPlace/" +
          spid +
          '><i class="fa-solid fa-info"></i></a>' +
          "</div>";

        if (currentUrl.includes(id)) {
          infoWindow.setContent(content);
          infoWindow.open(map, markerArray[spid]);
        } else {
          infoWindow.setContent(content + contentButton);
        }
      },
    });
  } else if (id.substring(0, 2) === "WP") {
    $.ajax({
      url: baseUrl + "/api/worshipPlace/" + id,
      dataType: "json",
      success: function (response) {
        let data = response.data;
        let wpid = data.id;
        let name = data.name;
        let lat = data.lat;
        let lng = data.lng;
        let capacity = data.capacity;
        let address = data.address;

        content =
          '<div style="max-width:200px;max-height:300px;" class="text-center">' +
          '<p class="fw-bold fs-6">' +
          name +
          "</p>" +
          '<p><i class="fa-solid fa-person-praying"></i> ' +
          capacity +
          "</p>" +
          '<p><i class="fa-solid fa-map-pin"></i> ' +
          address +
          "</p>" +
          "</div>";
        contentButton =
          '<div class="text-center">' +
          '<a title="Route" class="btn icon btn-outline-primary mx-1" id="routeInfoWindow" onclick="routeTo(' +
          lat +
          ", " +
          lng +
          ')"><i class="fa-solid fa-road"></i></a>' +
          '<a title="Info" class="btn icon btn-outline-primary mx-1" target="_blank" id="infoInfoWindow" href=' +
          baseUrl +
          "/web/worshipPlace/" +
          wpid +
          '><i class="fa-solid fa-info"></i></a>' +
          "</div>";

        if (currentUrl.includes(id)) {
          infoWindow.setContent(content);
          infoWindow.open(map, markerArray[wpid]);
        } else {
          infoWindow.setContent(content + contentButton);
        }
      },
    });
  }
}

function objectInfoWindowRouteMobile(id) {
  let content = "";
  let contentButton = "";

  if (id.substring(0, 3) === "GTP") {
    $.ajax({
      url: baseUrl + "/api/gtp/" + id,
      dataType: "json",
      success: function (response) {
        let data = response.data;
        let name = data.name;

        content =
          '<div style="max-width:200px;max-height:300px;" class="text-center">' +
          '<p class="fw-bold fs-6">' +
          name +
          "</p>" +
          '<p><i class="fa-solid fa-spa"></i> Tourism Village</p>' +
          "</div>";

        infoWindow.setContent(content);
      },
    });
  } else if (id.substring(0, 1) === "A") {
    $.ajax({
      url: baseUrl + "/api/attraction/" + id,
      dataType: "json",
      success: function (response) {
        let data = response.data;
        let aid = data.id;
        let name = data.name;
        let lat = data.lat;
        let lng = data.lng;
        let type = data.type;
        let price = data.price == 0 ? "Free" : formatter.format(data.price);

        content =
          '<div style="max-width:200px;max-height:300px;" class="text-center">' +
          '<p class="fw-bold fs-6">' +
          name +
          "</p>" +
          '<p><i class="fa-solid fa-spa"></i> ' +
          type +
          "</p>" +
          '<p><i class="fa-solid fa-money-bill me-2"></i> ' +
          price +
          "</p>" +
          "</div>";

        contentButton =
          '<div class="text-center">' +
          '<a title="Route" class="btn icon btn-outline-primary mx-1" id="routeInfoWindow" onclick="routeTo(' +
          lat +
          ", " +
          lng +
          ')"><i class="fa-solid fa-road"></i></a>' +
          "</div>";

        if (currentUrl.includes(id)) {
          infoWindow.setContent(content);
          infoWindow.open(map, markerArray[aid]);
        } else {
          infoWindow.setContent(content + contentButton);
        }
      },
    });
  } else if (id.substring(0, 2) === "EV") {
    $.ajax({
      url: baseUrl + "/api/event/" + id,
      dataType: "json",
      success: function (response) {
        let data = response.data;
        let evid = data.id;
        let name = data.name;
        let type = data.type;
        // let lat = data.lat;
        // let lng = data.lng;
        let price = data.price == 0 ? "Free" : formatter.format(data.price);

        content =
          '<div style="max-width:200px;max-height:300px;" class="text-center">' +
          '<p class="fw-bold fs-6">' +
          name +
          "</p>" +
          '<p><i class="fa-solid fa-spa"></i> ' +
          type +
          "</p>" +
          '<p><i class="fa-solid fa-money-bill me-2"></i> ' +
          price +
          "</p>" +
          "</div>";
        contentButton = '<div class="text-center">' + "</div>";

        if (currentUrl.includes(id)) {
          infoWindow.setContent(content);
          infoWindow.open(map, markerArray[evid]);
        } else {
          infoWindow.setContent(content + contentButton);
        }
      },
    });
  } else if (id.substring(0, 1) === "P") {
    $.ajax({
      url: baseUrl + "/api/package/" + id,
      dataType: "json",
      success: function (response) {
        let data = response.data;
        let paid = data.id;
        let name = data.name;
        // let lat = data.lat;
        // let lng = data.lng;
        let type_name = data.type_name;
        let price = data.price == 0 ? "Free" : formatter.format(data.price);

        content =
          '<div style="max-width:200px;max-height:300px;" class="text-center">' +
          '<p class="fw-bold fs-6">' +
          name +
          "</p>" +
          '<p><i class="fa-solid fa-spa"></i> ' +
          type_name +
          "</p>" +
          '<p><i class="fa-solid fa-money-bill me-2"></i> ' +
          price +
          "</p>" +
          "</div>";
        contentButton = '<div class="text-center">' + "</div>";

        if (currentUrl.includes(id)) {
          infoWindow.setContent(content);
          infoWindow.open(map, markerArray[paid]);
        } else {
          infoWindow.setContent(content + contentButton);
        }
      },
    });
  } else if (id.substring(0, 2) === "FC") {
    $.ajax({
      url: baseUrl + "/api/facility/" + id,
      dataType: "json",
      success: function (response) {
        let data = response.data;
        let name = data.name;

        content =
          '<div style="max-width:200px;max-height:300px;" class="text-center">' +
          '<p class="fw-bold fs-6">' +
          name +
          "</p>" +
          "</div>";

        infoWindow.setContent(content);
      },
    });
  } else if (id.substring(0, 2) === "HO") {
    $.ajax({
      url: baseUrl + "/api/homestay/" + id,
      dataType: "json",
      success: function (response) {
        let data = response.data;
        let hoid = data.id;
        let name = data.name;
        let lat = data.lat;
        let lng = data.lng;
        let contact_person =
          data.contact_person == 0 ? "-" : data.contact_person;
        let address = data.address;

        content =
          '<div style="max-width:200px;max-height:300px;" class="text-center">' +
          '<p class="fw-bold fs-6">' +
          name +
          "</p>" +
          '<p><i class="fa-solid fa-phone me-2"></i> ' +
          contact_person +
          "</p>" +
          '<p><i class="fa-solid fa-map-pin"></i> ' +
          address +
          "</p>" +
          "</div>";
        contentButton =
          '<div class="text-center">' +
          '<a title="Route" class="btn icon btn-outline-primary mx-1" id="routeInfoWindow" onclick="routeTo(' +
          lat +
          ", " +
          lng +
          ')"><i class="fa-solid fa-road"></i></a>' +
          "</div>";
        console.log(currentUrl);
        if (currentUrl.includes(id)) {
          if (currentUrl.startsWith("api")) {
            infoWindow.setContent(content + contentButton);
          } else {
            infoWindow.setContent(content);
          }
          infoWindow.open(map, markerArray[hoid]);
        } else {
          infoWindow.setContent(content + contentButton);
        }
      },
    });
  } else if (id.substring(0, 2) === "CP") {
    $.ajax({
      url: baseUrl + "/api/culinaryPlace/" + id,
      dataType: "json",
      success: function (response) {
        let data = response.data;
        let cpid = data.id;
        let name = data.name;
        let lat = data.lat;
        let lng = data.lng;
        let contact = data.contact_person;
        let address = data.address;

        content =
          '<div style="max-width:200px;max-height:300px;" class="text-center">' +
          '<p class="fw-bold fs-6">' +
          name +
          "</p>" +
          '<p><i class="fa-solid fa-address-book"></i> ' +
          contact +
          "</p>" +
          '<p><i class="fa-solid fa-map-pin"></i> ' +
          address +
          "</p>" +
          "</div>";
        contentButton =
          '<div class="text-center">' +
          '<a title="Route" class="btn icon btn-outline-primary mx-1" id="routeInfoWindow" onclick="routeTo(' +
          lat +
          ", " +
          lng +
          ')"><i class="fa-solid fa-road"></i></a>' +
          "</div>";

        if (currentUrl.includes(id)) {
          infoWindow.setContent(content);
          infoWindow.open(map, markerArray[cpid]);
        } else {
          infoWindow.setContent(content + contentButton);
        }
      },
    });
  } else if (id.substring(0, 2) === "TH") {
    $.ajax({
      url: baseUrl + "/api/traditionalHouse/" + id,
      dataType: "json",
      success: function (response) {
        let data = response.data;
        let cpid = data.id;
        let name = data.name;
        let lat = data.lat;
        let lng = data.lng;
        let contact = data.contact_person;
        let address = data.address;

        content =
          '<div style="max-width:200px;max-height:300px;" class="text-center">' +
          '<p class="fw-bold fs-6">' +
          name +
          "</p>" +
          '<p><i class="fa-solid fa-address-book"></i> ' +
          contact +
          "</p>" +
          '<p><i class="fa-solid fa-map-pin"></i> ' +
          address +
          "</p>" +
          "</div>";
        contentButton =
          '<div class="text-center">' +
          '<a title="Route" class="btn icon btn-outline-primary mx-1" id="routeInfoWindow" onclick="routeTo(' +
          lat +
          ", " +
          lng +
          ')"><i class="fa-solid fa-road"></i></a>' +
          "</div>";

        if (currentUrl.includes(id)) {
          infoWindow.setContent(content);
          infoWindow.open(map, markerArray[cpid]);
        } else {
          infoWindow.setContent(content + contentButton);
        }
      },
    });
  } else if (id.substring(0, 2) === "HO") {
    $.ajax({
      url: baseUrl + "/api/homestay/" + id,
      dataType: "json",
      success: function (response) {
        let data = response.data;
        let cpid = data.id;
        let name = data.name;
        let lat = data.lat;
        let lng = data.lng;
        let contact = data.contact_person;
        let address = data.address;

        content =
          '<div style="max-width:200px;max-height:300px;" class="text-center">' +
          '<p class="fw-bold fs-6">' +
          name +
          "</p>" +
          '<p><i class="fa-solid fa-address-book"></i> ' +
          contact +
          "</p>" +
          '<p><i class="fa-solid fa-map-pin"></i> ' +
          address +
          "</p>" +
          "</div>";
        contentButton =
          '<div class="text-center">' +
          '<a title="Route" class="btn icon btn-outline-primary mx-1" id="routeInfoWindow" onclick="routeTo(' +
          lat +
          ", " +
          lng +
          ')"><i class="fa-solid fa-road"></i></a>' +
          "</div>";

        if (currentUrl.includes(id)) {
          infoWindow.setContent(content);
          infoWindow.open(map, markerArray[cpid]);
        } else {
          infoWindow.setContent(content + contentButton);
        }
      },
    });
  } else if (id.substring(0, 2) === "SP") {
    $.ajax({
      url: baseUrl + "/api/souvenirPlace/" + id,
      dataType: "json",
      success: function (response) {
        let data = response.data;
        let spid = data.id;
        let name = data.name;
        let lat = data.lat;
        let lng = data.lng;
        let contact = data.contact_person;
        let address = data.address;

        content =
          '<div style="max-width:200px;max-height:300px;" class="text-center">' +
          '<p class="fw-bold fs-6">' +
          name +
          "</p>" +
          '<p><i class="fa-solid fa-address-book"></i> ' +
          contact +
          "</p>" +
          '<p><i class="fa-solid fa-map-pin"></i> ' +
          address +
          "</p>" +
          "</div>";
        contentButton =
          '<div class="text-center">' +
          '<a title="Route" class="btn icon btn-outline-primary mx-1" id="routeInfoWindow" onclick="routeTo(' +
          lat +
          ", " +
          lng +
          ')"><i class="fa-solid fa-road"></i></a>' +
          "</div>";

        if (currentUrl.includes(id)) {
          infoWindow.setContent(content);
          infoWindow.open(map, markerArray[spid]);
        } else {
          infoWindow.setContent(content + contentButton);
        }
      },
    });
  } else if (id.substring(0, 2) === "WP") {
    $.ajax({
      url: baseUrl + "/api/worshipPlace/" + id,
      dataType: "json",
      success: function (response) {
        let data = response.data;
        let wpid = data.id;
        let name = data.name;
        let lat = data.lat;
        let lng = data.lng;
        let capacity = data.capacity;
        let address = data.address;

        content =
          '<div style="max-width:200px;max-height:300px;" class="text-center">' +
          '<p class="fw-bold fs-6">' +
          name +
          "</p>" +
          '<p><i class="fa-solid fa-person-praying"></i> ' +
          capacity +
          "</p>" +
          '<p><i class="fa-solid fa-map-pin"></i> ' +
          address +
          "</p>" +
          "</div>";
        contentButton =
          '<div class="text-center">' +
          '<a title="Route" class="btn icon btn-outline-primary mx-1" id="routeInfoWindow" onclick="routeTo(' +
          lat +
          ", " +
          lng +
          ')"><i class="fa-solid fa-road"></i></a>' +
          "</div>";

        if (currentUrl.includes(id)) {
          infoWindow.setContent(content);
          infoWindow.open(map, markerArray[wpid]);
        } else {
          infoWindow.setContent(content + contentButton);
        }
      },
    });
  }
}

function objectInfoWindowMobile(id) {
  let content = "";
  let contentButton = "";

  if (id.substring(0, 3) === "GTP") {
    $.ajax({
      url: baseUrl + "/api/gtp/" + id,
      dataType: "json",
      success: function (response) {
        let data = response.data;
        let name = data.name;

        content =
          '<div style="max-width:200px;max-height:300px;" class="text-center">' +
          '<p class="fw-bold fs-6">' +
          name +
          "</p>" +
          '<p><i class="fa-solid fa-spa"></i> Tourism Village</p>' +
          "</div>";

        infoWindow.setContent(content);
      },
    });
  } else if (id.substring(0, 1) === "A") {
    $.ajax({
      url: baseUrl + "/api/attraction/" + id,
      dataType: "json",
      success: function (response) {
        let data = response.data;
        let aid = data.id;
        let name = data.name;
        let lat = data.lat;
        let lng = data.lng;
        let type = data.type;
        let price = data.price == 0 ? "Free" : formatter.format(data.price);

        content =
          '<div style="max-width:200px;max-height:300px;" class="text-center">' +
          '<p class="fw-bold fs-6">' +
          name +
          "</p>" +
          '<p><i class="fa-solid fa-spa"></i> ' +
          type +
          "</p>" +
          '<p><i class="fa-solid fa-money-bill me-2"></i> ' +
          price +
          "</p>" +
          "</div>";

        if (
          aid == "A0001" ||
          aid == "A0004" ||
          aid == "A0008" ||
          aid == "A0009"
        ) {
          contentButton =
            '<div class="text-center">' +
            // '<a title="Nearby" class="btn icon btn-outline-primary mx-1" id="nearbyInfoWindow" onclick="openTrack(`'+ aid +'`,'+ lat +','+ lng +')"><i class="fa-solid fa-map-location-dot"></i></a>' +
            '<a title="Info" class="btn icon btn-outline-primary mx-1" target="_blank" id="infoInfoWindow" href=' +
            baseUrl +
            "/web/attraction/" +
            aid +
            '><i class="fa-solid fa-info"></i></a>' +
            "</div>";
        } else if (aid == "A0005" || aid == "A0006" || aid == "A0007") {
          contentButton =
            '<div class="text-center">' +
            '<a title="Info" class="btn icon btn-outline-primary mx-1" target="_blank" id="infoInfoWindow" href=' +
            baseUrl +
            "/web/attraction/" +
            aid +
            '><i class="fa-solid fa-info"></i></a>' +
            "</div>";
        } else {
          contentButton =
            '<div class="text-center">' +
            // '<a title="Nearby" class="btn icon btn-outline-primary mx-1" id="nearbyInfoWindow" onclick="openNearby(`'+ aid +'`,'+ lat +','+ lng +')"><i class="fa-solid fa-compass"></i></a>' +
            '<a title="Info" class="btn icon btn-outline-primary mx-1" target="_blank" id="infoInfoWindow" href=' +
            baseUrl +
            "/web/attraction/" +
            aid +
            '><i class="fa-solid fa-info"></i></a>' +
            "</div>";
        }

        if (currentUrl.includes(id)) {
          infoWindow.setContent(content + contentButton);
          infoWindow.open(map, markerArray[aid]);
        } else {
          infoWindow.setContent(content + contentButton);
        }
      },
    });
  } else if (id.substring(0, 2) === "HO") {
    $.ajax({
      url: baseUrl + "/api/homestay/" + id,
      dataType: "json",
      success: function (response) {
        let data = response.data;
        let hoid = data.id;
        let name = data.name;
        let lat = data.lat;
        let lng = data.lng;
        let contact_person =
          data.contact_person == 0 ? "-" : data.contact_person;
        let address = data.address;

        content =
          '<div style="max-width:200px;max-height:300px;" class="text-center">' +
          '<p class="fw-bold fs-6">' +
          name +
          "</p>" +
          '<p><i class="fa-solid fa-phone me-2"></i> ' +
          contact_person +
          "</p>" +
          '<p><i class="fa-solid fa-map-pin"></i> ' +
          address +
          "</p>" +
          "</div>";
        contentButton =
          '<div class="text-center">' +
          '<a title="Route" class="btn icon btn-outline-primary mx-1" id="routeInfoWindow" onclick="routeTo(' +
          lat +
          ", " +
          lng +
          ')"><i class="fa-solid fa-road"></i></a>' +
          '<a title="Info" class="btn icon btn-outline-primary mx-1" target="_blank" id="infoInfoWindow" href=' +
          baseUrl +
          "/web/homestay/" +
          hoid +
          '><i class="fa-solid fa-info"></i></a>' +
          "</div>";

        if (currentUrl.includes(id)) {
          infoWindow.setContent(content + contentButton);
          infoWindow.open(map, markerArray[hoid]);
        } else {
          infoWindow.setContent(content + contentButton);
        }
      },
    });
  }
}

function boundToObject2() {
  console.log("Markers array:", markers);

  if (markers.length === 0) {
    console.warn("No markers to bound.");
    return;
  }

  let bounds = new google.maps.LatLngBounds();
  markers.forEach((marker) => {
    bounds.extend(marker.getPosition());
  });

  map.fitBounds(bounds);
}

// Render map to contains all object marker
function boundToObject() {
  if (Object.keys(markerArray).length > 0) {
    bounds = new google.maps.LatLngBounds();
    for (i in markerArray) {
      bounds.extend(markerArray[i].getPosition());
    }
    map.fitBounds(bounds, 80);
  } else {
    let pos = new google.maps.LatLng(-0.54145013, 100.48094882);
    map.panTo(pos);
  }
}

// Render map to contains route and its markers
function boundToRoute(start, end) {
  bounds = new google.maps.LatLngBounds();
  bounds.extend(start);
  bounds.extend(end);
}

// Add user position to map bound
function boundToRadius(lat, lng, rad) {
  let userBound = new google.maps.LatLng(lat, lng);
  const radiusCircle = new google.maps.Circle({
    center: userBound,
    radius: Number(rad),
  });
  map.fitBounds(radiusCircle.getBounds());
}

// Draw radius circle
function drawRadius(position, radius) {
  const radiusCircle = new google.maps.Circle({
    center: position,
    radius: radius,
    map: map,
    strokeColor: "#FF0000",
    strokeOpacity: 0.8,
    strokeWeight: 2,
    fillColor: "#FF0000",
    fillOpacity: 0.35,
  });
  circleArray.push(radiusCircle);
  boundToRadius(currentLat, currentLng, radius);
}

// Update radiusValue on search by radius
function updateRadius(postfix) {
  document.getElementById("radiusValue" + postfix).innerHTML =
    document.getElementById("inputRadius" + postfix).value * 100 + " m";
}

// pan to selected object
function focusObject(id) {
  google.maps.event.trigger(markerArray[id], "click");
  map.panTo(markerArray[id].getPosition());
}

// display objects by feature used
function displayFoundObject(response) {
  $("#table-data").empty();
  let data = response.data;
  let counter = 1;
  for (i in data) {
    let item = data[i];
    let row;

    row =
      "<tr>" +
      "<td>" +
      counter +
      "</td>" +
      '<td class="fw-bold">' +
      item.name +
      "</td>" +
      "<td>" +
      '<a data-bs-toggle="tooltip" data-bs-placement="bottom" title="More Info" class="btn icon btn-primary mx-1" onclick="focusObject(`' +
      item.id +
      '`);">' +
      '<span class="material-symbols-outlined">info</span>' +
      "</a>" +
      "</td>" +
      "</tr>";

    $("#table-data").append(row);
    objectMarker(item.id, item.lat, item.lng, item.status);
    counter++;
  }
}

// display steps of direction to selected route
function showSteps(directionResult) {
  $("#direction-row").show();
  $("#table-direction").empty();
  let myRoute = directionResult.routes[0].legs[0];
  for (let i = 0; i < myRoute.steps.length; i++) {
    let distance = myRoute.steps[i].distance.value;
    let instruction = myRoute.steps[i].instructions;
    let row =
      "<tr>" +
      "<td>" +
      distance.toLocaleString("id-ID") +
      "</td>" +
      "<td>" +
      instruction +
      "</td>" +
      "</tr>";
    $("#table-direction").append(row);
  }
}

// close nearby search section
function closeNearby() {
  $("#direction-row").hide();
  $("#check-track-col").hide();
  $("#check-nearby-col").hide();
  $("#result-track-col").hide();
  $("#result-nearby-col").hide();
  $("#list-at-col").show();
  $("#list-ev-col").show();
}

// open nearby search section
function openTrack(id, lat, lng) {
  $("#list-at-col").hide();
  $("#check-track-col").show();

  currentLat = lat;
  currentLng = lng;
  let pos = new google.maps.LatLng(currentLat, currentLng);
  map.panTo(pos);

  document
    .getElementById("inputTrackAlong")
    .setAttribute("onclick", 'checkTrack("' + id + '")');
}

// open nearby search section
function openNearby(id, lat, lng) {
  $("#list-at-col").hide();
  $("#check-nearby-col").show();

  currentLat = lat;
  currentLng = lng;
  let pos = new google.maps.LatLng(currentLat, currentLng);
  map.panTo(pos);

  document
    .getElementById("inputRadiusNearby")
    .setAttribute(
      "onchange",
      'updateRadius("Nearby"); checkNearby("' + id + '")'
    );
}

// open nearby search section
function openExplore() {
  $("#list-object-col").hide();
  $("#list-rec-col").hide();
  $("#result-explore-col").hide();
  $("#check-explore-col").show();

  document
    .getElementById("inputRadiusNearby")
    .setAttribute("onchange", 'updateRadius("Nearby"); checkExplore()');
}

function closeExplore() {
  $("#check-explore-col").hide();
  $("#list-object-col").show();
  $("#list-rec-col").show();
  $("#result-explore-col").hide();
}

function checkExplore() {
  if (userLat == 0 && userLng == 0) {
    document.getElementById("radiusValueNearby").innerHTML = "0 m";
    document.getElementById("inputRadiusNearby").value = 0;
    return Swal.fire("Determine your position first!");
  }

  clearRadius();
  clearRoute();
  clearMarker();
  destinationMarker.setMap(null);
  google.maps.event.clearListeners(map, "click");

  $("#table-lsa").empty();
  $("#table-at").empty();
  $("#table-th").empty();
  $("#table-ho").empty();
  $("#table-cp").empty();
  $("#table-sp").empty();
  $("#table-wp").empty();

  $("#table-lsa").hide();
  $("#table-at").hide();
  $("#table-th").hide();
  $("#table-ho").hide();
  $("#table-cp").hide();
  $("#table-sp").hide();
  $("#table-wp").hide();

  let pos = new google.maps.LatLng(currentLat, currentLng);
  let radiusValue =
    parseFloat(document.getElementById("inputRadiusNearby").value) * 100;
  map.panTo(pos);

  const checkLSA = document.getElementById("check-lsa").checked;
  const checkTH = document.getElementById("check-th").checked;
  const checkHO = document.getElementById("check-ho").checked;
  const checkCP = document.getElementById("check-cp").checked;
  const checkSP = document.getElementById("check-sp").checked;
  const checkWP = document.getElementById("check-wp").checked;

  if (!checkLSA && !checkTH && !checkHO && !checkCP && !checkSP && !checkWP) {
    document.getElementById("radiusValueNearby").innerHTML = "0 m";
    document.getElementById("inputRadiusNearby").value = 0;
    return Swal.fire("Please choose one object");
  }

  if (checkLSA) {
    findExplore("lsa", radiusValue);
    $("#table-lsa").show();
  }
  if (checkHO) {
    findExplore("ho", radiusValue);
    $("#table-ho").show();
  }
  if (checkTH) {
    findExplore("th", radiusValue);
    $("#table-th").show();
  }
  if (checkCP) {
    findExplore("cp", radiusValue);
    $("#table-cp").show();
  }
  if (checkSP) {
    findExplore("sp", radiusValue);
    $("#table-sp").show();
  }
  if (checkWP) {
    findExplore("wp", radiusValue);
    $("#table-wp").show();
  }

  drawRadius(new google.maps.LatLng(currentLat, currentLng), radiusValue);
  $("#result-explore-col").show();
  $("#list-rec-col").hide();
}

function clearAllAll() {
  clearMarker();
  clearRadius();
  clearRoute();
  clearOverlay();
  clearAirplaneMarkers();
  clearCarMarkers();
  objectMarker("SUM01", -0.4761815168531753, 100.43223933779609);
}

function checkLayer() {
  // Bersihkan peta dan tabel
  clearRadius();
  clearRoute();
  clearMarker();
  // clearAllDigitasi();

  // initMap5();
  objectMarker("SUM01", -0.4761815168531753, 100.43223933779609);

  destinationMarker.setMap(null);
  google.maps.event.clearListeners(map, "click");

  // Koordinat posisi default (misal pusat peta)
  let pos = new google.maps.LatLng(currentLat, currentLng);

  // Periksa status setiap checkbox
  if (document.getElementById("check-oco").checked) {
    clearAllAll();
    clearDigitNeg();

    for (let n = 1; n < 4; n++) {
      digitNeg(n);
    }
  } else {
    clearAllAll();
    clearDigitNeg();
  }

  if (document.getElementById("check-opr").checked) {
    clearAllAll();
    clearDigitProv();
    for (let p = 1; p < 11; p++) {
      const idprov = p;
      digitProv(idprov);
    }
  } else {
    clearAllAll();
    clearDigitProv();
  }

  if (document.getElementById("check-ore").checked) {
    clearAllAll();
    clearDigitKabKota();
    nameprovv = "Sumatera_Barat";
    digitKabKota(nameprovv);
  } else {
    clearAllAll();
    clearDigitKabKota();
  }

  if (document.getElementById("check-odi").checked) {
    clearAllAll();
    clearDigitKec();
    for (let k = 1; k < 15; k++) {
      const idkec = k;
      digitKec(idkec);
    }
  } else {
    clearAllAll();
    clearDigitKec();
  }

  if (document.getElementById("check-ovi").checked) {
    clearAllAll();
    clearDigitNagari1();
    for (let d = 1; d < 5; d++) {
      const iddesa = d;
      digitNagari1(iddesa);
    }
  } else {
    clearAllAll();
    clearDigitNagari1();
  }

  if (document.getElementById("check-oto").checked) {
    clearAllAll();
    clearDigitVillage1();
    digitVillage1();
  } else {
    clearAllAll();
    clearDigitVillage1();
  }
}

function clearDigitNeg() {
  digitNegLayers.forEach((layer) => {
    layer.setMap(null);
  });
  digitNegLayers = [];
}

function clearDigitProv() {
  digitProvLayers.forEach((layer) => {
    layer.setMap(null);
  });
  digitProvLayers = [];
}

function clearDigitKabKota() {
  digitKabKotaLayers.forEach((layer) => {
    layer.setMap(null);
  });
  digitKabKotaLayers = [];
}

function clearDigitKec() {
  digitKecLayers.forEach((layer) => {
    layer.setMap(null);
  });
  digitKecLayers = [];
}

function clearDigitNagari1() {
  digitNagari1Layers.forEach((layer) => {
    layer.setMap(null);
  });
  digitNagari1Layers = [];
}

function clearDigitVillage1() {
  digitVillage1Layers.forEach((layer) => {
    layer.setMap(null);
  });
  digitVillage1Layers = [];
}

function checkObject() {
  // Bersihkan peta dan tabel
  clearRadius();
  clearRoute();
  clearMarker();
  clearAllDigitasi();
  clearAirplaneMarkers();
  clearCarMarkers();
  // initMap5();
  objectMarker("SUM01", -0.4761815168531753, 100.43223933779609);
  destinationMarker.setMap(null);
  google.maps.event.clearListeners(map, "click");

  // Sembunyikan semua tabel
  $("#table-lsa").empty().hide();
  $("#table-at").empty().hide();
  $("#table-th").empty().hide();
  $("#table-ho").empty().hide();
  $("#table-cp").empty().hide();
  $("#table-sp").empty().hide();
  $("#table-wp").empty().hide();

  // Koordinat posisi default (misal pusat peta)
  let pos = new google.maps.LatLng(currentLat, currentLng);

  // Periksa status setiap checkbox

  if (document.getElementById("check-olsa").checked) {
    clearAllAll();
    findAll("lsa");
    $("#table-lsa").show();
  }
  if (document.getElementById("check-oat").checked) {
    clearAllAll();
    findAll("at");
    $("#table-at").show();
  }
  if (document.getElementById("check-oth").checked) {
    clearAllAll();
    findAll("th");
    $("#table-th").show();
  }
  if (document.getElementById("check-oho").checked) {
    clearAllAll();
    findAll("ho");
    $("#table-ho").show();
  }
  if (document.getElementById("check-ocp").checked) {
    clearAllAll();
    findAll("cp");
    $("#table-cp").show();
  }
  if (document.getElementById("check-osp").checked) {
    clearAllAll();
    findAll("sp");
    $("#table-sp").show();
  }
  if (document.getElementById("check-owp").checked) {
    clearAllAll();
    findAll("wp");
    $("#table-wp").show();
  }

  // Atur bound ke objek yang ditemukan
  boundToObject();

  // Tampilkan kolom hasil pencarian
  $("#result-explore-col").show();
  $("#list-rec-col").hide();
}

function clickExplore() {
  clearRadius();
  clearRoute();
  clearMarker();
  clearAllDigitasi();
  clearAirplaneMarkers();
  clearCarMarkers();
  $("#list-object-col").hide();

  const checkOLSA = document.getElementById("check-olsa");
  checkOLSA.checked = true;
  const checkOAT = document.getElementById("check-oat");
  checkOAT.checked = true;
  const checkOTH = document.getElementById("check-oth");
  checkOTH.checked = true;
  const checkOHO = document.getElementById("check-oho");
  checkOHO.checked = true;
  const checkOCP = document.getElementById("check-ocp");
  checkOCP.checked = true;
  const checkOSP = document.getElementById("check-osp");
  checkOSP.checked = true;
  const checkOWP = document.getElementById("check-owp");
  checkOWP.checked = true;

  let buttons = document.querySelectorAll(".day-route-btn");
  let dayDetails = document.querySelectorAll(".div-day-detail");
  let allActivityRows = document.querySelectorAll('[id^="activity-row-"]');

  buttons.forEach(function (button) {
    button.style.backgroundColor = ""; // reset to default background color
    button.style.color = ""; // reset to default text color
  });

  dayDetails.forEach(function (detailDiv) {
    detailDiv.style.border = ""; // reset div border
  });

  allActivityRows.forEach(function (activityRow) {
    activityRow.style.visibility = "hidden"; // Sembunyikan semua activity row
    activityRow.style.display = "none"; // Pastikan elemen tidak terlihat
  });

  // initMap5();
  // objectMarker("SUM01", -0.4761815168531753, 100.43223933779609);

  destinationMarker.setMap(null);
  google.maps.event.clearListeners(map, "click");

  let pos = new google.maps.LatLng(-0.54145013, 100.48094882);
  map.panTo(pos);

  // let categories = ["lsa", "at", "th"];
  let categories = ["lsa", "at", "th", "ho", "cp", "sp", "wp"];
  let promises = categories.map((category) => findAll(category));

  digitVillage1zoom();
  // map.setZoom(16);

  Promise.all(promises).then(() => {
    boundToObject();
    $("#result-explore-col").show();
    $("#list-rec-col").hide();
    $("#check-explore-col").hide();
  });
}

function clickLayer() {
  clearRadius();
  clearRoute();
  clearMarker();
  clearAllDigitasi();
  clearAirplaneMarkers();
  clearCarMarkers();
  clearDigitNeg();
  clearDigitProv();
  clearDigitKabKota();
  clearDigitKec();
  clearDigitNagari1();
  clearDigitVillage1();
  $("#list-object-col").hide();

  const checkOCO = document.getElementById("check-oco");
  checkOCO.checked = true;
  const checkOPR = document.getElementById("check-opr");
  checkOPR.checked = true;
  const checkORE = document.getElementById("check-ore");
  checkORE.checked = true;
  const checkODI = document.getElementById("check-odi");
  checkODI.checked = true;
  const checkOVI = document.getElementById("check-ovi");
  checkOVI.checked = true;
  const checkOTO = document.getElementById("check-oto");
  checkOTO.checked = true;

  let buttons = document.querySelectorAll(".day-route-btn");
  let dayDetails = document.querySelectorAll(".div-day-detail");
  let allActivityRows = document.querySelectorAll('[id^="activity-row-"]');

  buttons.forEach(function (button) {
    button.style.backgroundColor = ""; // reset to default background color
    button.style.color = ""; // reset to default text color
  });

  dayDetails.forEach(function (detailDiv) {
    detailDiv.style.border = ""; // reset div border
  });

  allActivityRows.forEach(function (activityRow) {
    activityRow.style.visibility = "hidden"; // Sembunyikan semua activity row
    activityRow.style.display = "none"; // Pastikan elemen tidak terlihat
  });

  // initMap5();
  objectMarker("SUM01", -0.4761815168531753, 100.43223933779609);

  destinationMarker.setMap(null);
  google.maps.event.clearListeners(map, "click");

  // let pos = new google.maps.LatLng(-0.54145013, 100.48094882);
  // map.panTo(pos);

  for (let n = 1; n < 4; n++) {
    const idneg = n;
    digitNeg(idneg);
  }

  const myidprov = "P03";
  const digitidprov = myidprov.substring(1);

  for (let p = 1; p < digitidprov; p++) {
    const idprov = p;
    digitProv(idprov);
  }

  for (let p = digitidprov; p < 11; p++) {
    const idprov = p;
    digitProv(idprov);
  }

  nameprovv = "Sumatera_Barat";
  digitKabKota(nameprovv);

  for (let k = 1; k < 15; k++) {
    const idkec = k;
    digitKec(idkec);
  }

  for (let d = 1; d < 3; d++) {
    const iddesa = d;
    digitNagari1(iddesa);
  }

  for (let d = 3; d < 5; d++) {
    const iddesa = d;
    digitNagari1(iddesa);
  }

  // digitVillage1();

  digitVillage1zoom();
  // map.setZoom(16);

  Promise.all(promises).then(() => {
    boundToObject();
    $("#result-explore-col").show();
    $("#list-rec-col").hide();
  });
}

// Fetch all object
function findAll(category) {
  // let pos = new google.maps.LatLng(currentLat, currentLng);
  if (category === "lsa") {
    $.ajax({
      url: baseUrl + "/api/attractionlsa/findlsaAll",
      type: "POST",
      data: {},
      dataType: "json",
      success: function (response) {
        displayExploreResult(category, response);
        boundToObject();
      },
    });
  } else if (category === "at") {
    $.ajax({
      url: baseUrl + "/api/attraction/findAll",
      type: "POST",
      data: {},
      dataType: "json",
      success: function (response) {
        displayExploreResult(category, response);
        boundToObject();
      },
    });
  } else if (category === "th") {
    $.ajax({
      url: baseUrl + "/api/traditionalHouse/findAll",
      type: "POST",
      data: {},
      dataType: "json",
      success: function (response) {
        displayExploreResult(category, response);
        boundToObject();
      },
    });
  } else if (category === "ho") {
    $.ajax({
      url: baseUrl + "/api/homestay/findAll",
      type: "POST",
      data: {},
      dataType: "json",
      success: function (response) {
        displayExploreResult(category, response);
        boundToObject();
      },
    });
  } else if (category === "cp") {
    $.ajax({
      url: baseUrl + "/api/culinaryPlace/findAll",
      type: "POST",
      data: {},
      dataType: "json",
      success: function (response) {
        displayExploreResult(category, response);
        boundToObject();
      },
    });
  } else if (category === "sp") {
    $.ajax({
      url: baseUrl + "/api/souvenirPlace/findAll",
      type: "POST",
      data: {},
      dataType: "json",
      success: function (response) {
        displayExploreResult(category, response);
        boundToObject();
      },
    });
  } else if (category === "wp") {
    $.ajax({
      url: baseUrl + "/api/worshipPlace/findAll",
      type: "POST",
      data: {},
      dataType: "json",
      success: function (response) {
        displayExploreResult(category, response);
        boundToObject();
      },
    });
  }
}

// Fetch object nearby by category
function findExplore(category, radius) {
  // let pos = new google.maps.LatLng(currentLat, currentLng);
  if (category === "cp") {
    $.ajax({
      url: baseUrl + "/api/culinaryPlace/findByRadius",
      type: "POST",
      data: {
        lat: currentLat,
        long: currentLng,
        radius: radius,
      },
      dataType: "json",
      success: function (response) {
        displayExploreResult(category, response);
      },
    });
  } else if (category === "lsa") {
    $.ajax({
      url: baseUrl + "/api/attractionlsa/findByRadius",
      type: "POST",
      data: {
        lat: currentLat,
        long: currentLng,
        radius: radius,
      },
      dataType: "json",
      success: function (response) {
        displayExploreResult(category, response);
      },
    });
  } else if (category === "th") {
    $.ajax({
      url: baseUrl + "/api/traditionalHouse/findByRadius",
      type: "POST",
      data: {
        lat: currentLat,
        long: currentLng,
        radius: radius,
      },
      dataType: "json",
      success: function (response) {
        displayExploreResult(category, response);
      },
    });
  } else if (category === "ho") {
    $.ajax({
      url: baseUrl + "/api/homestay/findByRadius",
      type: "POST",
      data: {
        lat: currentLat,
        long: currentLng,
        radius: radius,
      },
      dataType: "json",
      success: function (response) {
        displayExploreResult(category, response);
      },
    });
  } else if (category === "sp") {
    $.ajax({
      url: baseUrl + "/api/souvenirPlace/findByRadius",
      type: "POST",
      data: {
        lat: currentLat,
        long: currentLng,
        radius: radius,
      },
      dataType: "json",
      success: function (response) {
        displayExploreResult(category, response);
      },
    });
  } else if (category === "wp") {
    $.ajax({
      url: baseUrl + "/api/worshipPlace/findByRadius",
      type: "POST",
      data: {
        lat: currentLat,
        long: currentLng,
        radius: radius,
      },
      dataType: "json",
      success: function (response) {
        displayExploreResult(category, response);
      },
    });
  }
}

// function displayExploreResult(category, response) {
//   let data = response.data;
//   let headerName;
//   if (category === "cp") {
//     headerName = "Culinary Place";
//   } else if (category === "at") {
//     headerName = "Attraction";
//   } else if (category === "th") {
//     headerName = "Traditional House";
//   } else if (category === "lsa") {
//     headerName = "Lake Singkarak Activity";
//   } else if (category === "ho") {
//     headerName = "Homestay";
//   } else if (category === "sp") {
//     headerName = "Souvenir Place";
//   } else if (category === "wp") {
//     headerName = "Worship Place";
//   }

//   let table =
//     "<thead><tr>" +
//     "<th>" +
//     headerName +
//     " Name</th>" +
//     '<th colspan="1">Action</th>' +
//     "</tr></thead>" +
//     '<tbody id="data-' +
//     category +
//     '">' +
//     "</tbody>";
//   $("#table-" + category).append(table);

//   for (i in data) {
//     let item = data[i];
//     let row =
//       "<tr>" +
//       "<td>" +
//       item.name +
//       "</td>" +
//       "<td>" +
//       '<a title="Location" class="btn-sm icon btn-primary" onclick="focusObject(`' +
//       item.id +
//       '`);"><i class="fa-solid fa-map-location-dot"></i></a>' +
//       "</td>" +
//       "</tr>";
//     $("#data-" + category).append(row);
//     objectMarkerExplore(item.id, item.lat, item.lng, item.status, item.type);
//   }
// }

function displayExploreResult(category, response) {
  let data = response.data;
  let headerName;

  if (category === "cp") {
    headerName = "Culinary Place";
  } else if (category === "at") {
    headerName = "Attraction";
  } else if (category === "th") {
    headerName = "Traditional House";
  } else if (category === "lsa") {
    headerName = "Lake Singkarak Activity";
  } else if (category === "ho") {
    headerName = "Homestay";
  } else if (category === "sp") {
    headerName = "Souvenir Place";
  } else if (category === "wp") {
    headerName = "Worship Place";
  }

  let table =
    "<thead><tr>" +
    "<th>" +
    headerName +
    " Name</th>" +
    '<th colspan="1">Action</th>' +
    "</tr></thead>" +
    '<tbody id="data-' +
    category +
    '">' +
    "</tbody>";
  $("#table-" + category).html(table); // Use html() to replace table content instead of appending

  if (data.length === 0) {
    // If no data, display the "No object in this radius" message
    let noDataRow =
      "<tr>" +
      '<td colspan="2" style="text-align: center; font-style: italic;">No object</td>' +
      "</tr>";
    $("#data-" + category).append(noDataRow);
  } else {
    // Populate table with data
    for (let i in data) {
      let item = data[i];
      let row =
        "<tr>" +
        "<td>" +
        item.name +
        "</td>" +
        "<td>" +
        '<a title="Location" class="btn-sm icon btn-primary" onclick="focusObject(`' +
        item.id +
        '`);"><i class="fa-solid fa-map-location-dot"></i></a>' +
        "</td>" +
        "</tr>";
      $("#data-" + category).append(row);
      objectMarkerExplore(item.id, item.lat, item.lng, item.status, item.type);
    }
  }
}


function objectMarkerExplore(
  id,
  lat,
  lng,
  status,
  type,
  homestay_status,
  anim = true
) {
  google.maps.event.clearListeners(map, "click");
  let pos = new google.maps.LatLng(lat, lng);
  let marker = new google.maps.Marker();

  let icon;
  // if (id.substring(0, 2) === "AT") {
  //   icon = baseUrl + "/media/icon/attraction.png";
  //   const idlsa = id;
  //   digitAttraction(idlsa);
  // }
  if (id.substring(0, 2) === "AT") {
    if (type === "Lake") {
      icon = baseUrl + "/media/icon/water.png";
    } else {
      icon = baseUrl + "/media/icon/attraction.png";
    }
    const idattraction = id;
    digitAttraction(idattraction);
  } else if (id.substring(0, 2) === "CP") {
    if (status === "1") {
      icon = baseUrl + "/media/icon/cpgtp.png";
    } else {
      icon = baseUrl + "/media/icon/culinary.png";
    }
    const idculinary = id;
    digitCulinary(idculinary);
  } else if (id.substring(0, 2) === "HO") {
    if (status === "1") {
      icon = baseUrl + "/media/icon/homestay.png";
      const idrumah = id;
      digitRumah(idrumah);
    } else if (homestay_status === "1") {
      icon = baseUrl + "/media/icon/homestay.png";
      const idhomestay = id;
      digitHomestay(idhomestay);
    } else {
      icon = baseUrl + "/media/icon/homestay.png";
      const idrumah = id;
      digitRumah(idrumah);
    }
    // const idrumah = id;
    // digitRumah(idrumah);
  } else if (id.substring(0, 2) === "SP") {
    if (status === "1") {
      icon = baseUrl + "/media/icon/souvenir.png";
    } else {
      icon = baseUrl + "/media/icon/souvenir.png";
    }
    const idsouvenir = id;
    digitSouvenir(idsouvenir);
  } else if (id.substring(0, 2) === "WP") {
    if (status === "1") {
      icon = baseUrl + "/media/icon/wpgtp.png";
    } else {
      icon = baseUrl + "/media/icon/worship.png";
    }
    const idworship = id;
    digitWorship(idworship);
  } else if (id.substring(0, 2) === "TH") {
    if (status === "1") {
      icon = baseUrl + "/media/icon/marker_rg.png";
    } else {
      icon = baseUrl + "/media/icon/marker_rg.png";
    }
    const idtraditional = id;
    digitTraditional(idtraditional);
  }

  markerOption = {
    position: pos,
    icon: icon,
    animation: google.maps.Animation.DROP,
    map: map,
  };
  marker.setOptions(markerOption);
  if (!anim) {
    marker.setAnimation(null);
  }
  marker.addListener("click", () => {
    infoWindow.close();
    objectInfoWindow(id);
    infoWindow.open(map, marker);
  });
  markerArray[id] = marker;
}

// Create compass
// function setCompass() {
//     const compass = document.createElement("div");
//     compass.setAttribute("id", "compass");
//     const compassDiv = document.createElement("div");
//     compass.appendChild(compassDiv);
//     const compassImg = document.createElement("img");
//     compassImg.src = baseUrl + '/media/icon/compass.png';
//     compassDiv.appendChild(compassImg);

//     map.controls[google.maps.ControlPosition.LEFT_BOTTOM].push(compass);
// }

function zoomToMarker(marker) {
  if (marker) {
    map.setZoom(15); // Set the zoom level to a higher value (you can adjust this)
    map.setCenter(marker.getPosition()); // Center the map to the marker's position
  }
}

// Search Result Object Around
function checkNearby(id) {
  clearRadius();
  clearRoute();
  clearMarker();
  clearUser();
  destinationMarker.setMap(null);
  google.maps.event.clearListeners(map, "click");

  objectMarker(id, currentLat, currentLng, false);

  $("#table-F0001").empty();
  $("#table-F0002").empty();
  $("#table-F0003").empty();
  $("#table-F0004").empty();
  $("#table-F0005").empty();
  $("#table-F0006").empty();
  $("#table-F0007").empty();
  $("#table-F0008").empty();
  $("#table-F0009").empty();
  $("#table-F0010").empty();

  $("#table-F0001").hide();
  $("#table-F0002").hide();
  $("#table-F0003").hide();
  $("#table-F0004").hide();
  $("#table-F0005").hide();
  $("#table-F0006").hide();
  $("#table-F0007").hide();
  $("#table-F0008").hide();
  $("#table-F0009").hide();
  $("#table-F0010").hide();

  let radiusValue =
    parseFloat(document.getElementById("inputRadiusNearby").value) * 100;

  const checkCP = document.getElementById("F0001").checked;
  const checkGA = document.getElementById("F0002").checked;
  const checkOF = document.getElementById("F0003").checked;
  const checkPA = document.getElementById("F0004").checked;
  const checkPB = document.getElementById("F0005").checked;
  const checkSA = document.getElementById("F0006").checked;
  const checkSP = document.getElementById("F0007").checked;
  const checkTH = document.getElementById("F0008").checked;
  const checkVT = document.getElementById("F0009").checked;
  const checkWP = document.getElementById("F0010").checked;

  if (
    !checkCP &&
    !checkGA &&
    !checkOF &&
    !checkPA &&
    !checkPB &&
    !checkSA &&
    !checkSP &&
    !checkTH &&
    !checkVT &&
    !checkWP
  ) {
    document.getElementById("radiusValueNearby").innerHTML = "0 m";
    document.getElementById("inputRadiusNearby").value = 0;
    return Swal.fire("Please choose one facility!");
  }

  if (checkCP) {
    findNearby("F0001", radiusValue);
    $("#table-F0001").show();
  }
  if (checkGA) {
    findNearby("F0002", radiusValue);
    $("#table-F0002").show();
  }
  if (checkOF) {
    findNearby("F0003", radiusValue);
    $("#table-F0003").show();
  }
  if (checkPA) {
    findNearby("F0004", radiusValue);
    $("#table-F0004").show();
  }
  if (checkPB) {
    findNearby("F0005", radiusValue);
    $("#table-F0005").show();
  }
  if (checkSA) {
    findNearby("F0006", radiusValue);
    $("#table-F0006").show();
  }
  if (checkSP) {
    findNearby("F0007", radiusValue);
    $("#table-F0007").show();
  }
  if (checkTH) {
    findNearby("F0008", radiusValue);
    $("#table-F0008").show();
  }
  if (checkVT) {
    findNearby("F0009", radiusValue);
    $("#table-F0009").show();
  }
  if (checkWP) {
    findNearby("F0010", radiusValue);
    $("#table-F0010").show();
  }
  drawRadius(new google.maps.LatLng(currentLat, currentLng), radiusValue);
  $("#result-nearby-col").show();
}

// Check facility along tracking
function checkTrack(id) {
  clearRadius();
  clearRoute();
  clearMarker();
  clearUser();
  destinationMarker.setMap(null);
  google.maps.event.clearListeners(map, "click");

  objectMarker(id, currentLat, currentLng, false);

  // let i = 1;
  // for(i > 0; i <= 10; i++) {
  //     $('#table-F000'+i).empty();
  //     $('#table-F000'+i).hide();
  // }

  $("#table-F0001").empty();
  $("#table-F0002").empty();
  $("#table-F0003").empty();
  $("#table-F0004").empty();
  $("#table-F0005").empty();
  $("#table-F0006").empty();
  $("#table-F0007").empty();
  $("#table-F0008").empty();
  $("#table-F0009").empty();
  $("#table-F0010").empty();

  $("#table-F0001").hide();
  $("#table-F0002").hide();
  $("#table-F0003").hide();
  $("#table-F0004").hide();
  $("#table-F0005").hide();
  $("#table-F0006").hide();
  $("#table-F0007").hide();
  $("#table-F0008").hide();
  $("#table-F0009").hide();
  $("#table-F0010").hide();

  const checkCP = document.getElementById("F0001").checked;
  const checkGA = document.getElementById("F0002").checked;
  const checkOF = document.getElementById("F0003").checked;
  const checkPA = document.getElementById("F0004").checked;
  const checkPB = document.getElementById("F0005").checked;
  const checkSA = document.getElementById("F0006").checked;
  const checkSP = document.getElementById("F0007").checked;
  const checkTH = document.getElementById("F0008").checked;
  const checkVT = document.getElementById("F0009").checked;
  const checkWP = document.getElementById("F0010").checked;

  if (
    !checkCP &&
    !checkGA &&
    !checkOF &&
    !checkPA &&
    !checkPB &&
    !checkSA &&
    !checkSP &&
    !checkTH &&
    !checkVT &&
    !checkWP
  ) {
    return Swal.fire("Please choose one facility!");
  }

  if (checkCP) {
    findTracking("F0001");
    $("#table-F0001").show();
  }
  if (checkGA) {
    findTracking("F0002");
    $("#table-F0002").show();
  }
  if (checkOF) {
    findTracking("F0003");
    $("#table-F0003").show();
  }
  if (checkPA) {
    findTracking("F0004");
    $("#table-F0004").show();
  }
  if (checkPB) {
    findTracking("F0005");
    $("#table-F0005").show();
  }
  if (checkSA) {
    findTracking("F0006");
    $("#table-F0006").show();
  }
  if (checkSP) {
    findTracking("F0007");
    $("#table-F0007").show();
  }
  if (checkTH) {
    findTracking("F0008");
    $("#table-F0008").show();
  }
  if (checkVT) {
    findTracking("F0009");
    $("#table-F0009").show();
  }
  if (checkWP) {
    findTracking("F0010");
    $("#table-F0010").show();
  }

  $("#result-track-col").show();
}

// Fetch object along tracking
function findTracking(category) {
  let pos = new google.maps.LatLng(currentLat, currentLng);
  // if (category === 'FC') {
  const ftype = new google.maps.Data();
  $.ajax({
    url: baseUrl + "/api/facility/findByTrack",
    type: "POST",
    data: {
      ftype: category,
    },
    dataType: "json",
    success: function (response) {
      displayTrackResult(category, response);
    },
  });
  // }
}

// Fetch object nearby by category
function findNearby(category, radius) {
  let pos = new google.maps.LatLng(currentLat, currentLng);
  // if (category === 'FC') {
  const ftype2 = new google.maps.Data();
  $.ajax({
    url: baseUrl + "/api/facility/findByRadius",
    type: "POST",
    data: {
      ftype2: category,
      lat: currentLat,
      long: currentLng,
      radius: radius,
    },
    dataType: "json",
    success: function (response) {
      displayNearbyResult(category, response);
    },
  });
  // }
}

// Add nearby object to corresponding table
function displayTrackResult(category, response) {
  let data = response.data;
  let headerName;
  if (category === "F0001") {
    headerName = "Culinary Place";
  } else if (category === "F0002") {
    headerName = "Gazebo";
  } else if (category === "F0003") {
    headerName = "Outbond Field";
  } else if (category === "F0004") {
    headerName = "Parking Area";
  } else if (category === "F0005") {
    headerName = "Public Bathroom";
  } else if (category === "F0006") {
    headerName = "Selfie Area";
  } else if (category === "F0007") {
    headerName = "Souvenir Place";
  } else if (category === "F0010") {
    headerName = "Worship Place";
  }

  let table =
    "<thead><tr>" +
    "<th>" +
    headerName +
    " Name</th>" +
    '<th colspan="2">Action</th>' +
    "</tr></thead>" +
    '<tbody id="data-' +
    category +
    '">' +
    "</tbody>";
  $("#table-" + category).append(table);

  for (i in data) {
    let item = data[i];
    let row =
      "<tr>" +
      "<td>" +
      item.name +
      "</td>" +
      "<td>" +
      '<a title="Location" class="btn-sm icon btn-primary" onclick="focusObject(`' +
      item.facility_id +
      '`);"><i class="fa-solid fa-map-location-dot"></i></a>' +
      "</td>" +
      "<td>" +
      '<a title="Info" class="btn-sm icon btn-primary" onclick="infoModal(`' +
      item.facility_id +
      '`)"><i class="fa-regular fa-images"></i></a>' +
      "</td>" +
      "</tr>";
    $("#data-" + category).append(row);
    objectMarkerFacility(item.facility_id, item.lat, item.long, item.type_id);
  }
}

function objectMarkerFacility(id, lat, lng, type, anim = true) {
  google.maps.event.clearListeners(map, "click");
  let pos = new google.maps.LatLng(lat, lng);
  let marker = new google.maps.Marker();

  let icon;

  if (type === "F0001") {
    icon = baseUrl + "/media/icon/culinary.png";
  } else if (type === "F0002") {
    icon = baseUrl + "/media/icon/gazebo.png";
  } else if (type === "F0003") {
    icon = baseUrl + "/media/icon/outbond.png";
  } else if (type === "F0004") {
    icon = baseUrl + "/media/icon/parking.png";
  } else if (type === "F0005") {
    icon = baseUrl + "/media/icon/bathroom.png";
  } else if (type === "F0006") {
    icon = baseUrl + "/media/icon/selfie.png";
  } else if (type === "F0007") {
    icon = baseUrl + "/media/icon/souvenir.png";
  } else if (type === "F0008") {
    icon = baseUrl + "/media/icon/treehouse.png";
  } else if (type === "F0009") {
    icon = baseUrl + "/media/icon/tower.png";
  } else if (type === "F0010") {
    icon = baseUrl + "/media/icon/worship.png";
  }

  markerOption = {
    position: pos,
    icon: icon,
    animation: google.maps.Animation.DROP,
    map: map,
  };
  marker.setOptions(markerOption);
  if (!anim) {
    marker.setAnimation(null);
  }
  marker.addListener("click", () => {
    infoWindow.close();
    objectInfoWindow(id);
    infoWindow.open(map, marker);
  });
  markerArray[id] = marker;

  for (let h = 1; h < 30; h++) {
    const idfc = h;
    digitFacility(idfc);
  }
}

// Add nearby object to corresponding table
function displayNearbyResult(category, response) {
  let data = response.data;
  let headerName;
  if (category === "F0001") {
    headerName = "Culinary Place";
  } else if (category === "F0002") {
    headerName = "Gazebo";
  } else if (category === "F0003") {
    headerName = "Outbond Field";
  } else if (category === "F0004") {
    headerName = "Parking Area";
  } else if (category === "F0005") {
    headerName = "Public Bathroom";
  } else if (category === "F0006") {
    headerName = "Selfie Area";
  } else if (category === "F0007") {
    headerName = "Souvenir Place";
  } else if (category === "F0010") {
    headerName = "Worship Place";
  }

  let table =
    "<thead><tr>" +
    "<th>" +
    headerName +
    " Name</th>" +
    '<th colspan="2">Action</th>' +
    "</tr></thead>" +
    '<tbody id="data-' +
    category +
    '">' +
    "</tbody>";
  $("#table-" + category).append(table);

  for (i in data) {
    let item = data[i];
    let row =
      "<tr>" +
      "<td>" +
      item.name +
      "</td>" +
      "<td>" +
      '<a title="Location" class="btn-sm icon btn-primary" onclick="focusObject(`' +
      item.id +
      '`);"><i class="fa-solid fa-map-location-dot"></i></a>' +
      "</td>" +
      "<td>" +
      '<a title="Info" class="btn-sm icon btn-primary" onclick="infoModal(`' +
      item.id +
      '`)"><i class="fa-regular fa-images"></i></a>' +
      "</td>" +
      "</tr>";
    $("#data-" + category).append(row);
    objectMarkerFacility(item.id, item.lat, item.lng, item.type_id);
  }
}

// Show modal for object
function infoModal(id) {
  let title;
  let content;
  // let g;
  // let content = "";

  if (id.substring(0, 2) === "FC") {
    $.ajax({
      url: baseUrl + "/api/facility/" + id,
      dataType: "json",
      success: function (response) {
        let item = response.data;
        // g = item.gallery;
        title = "<h3>" + item.name + "</h3>";
        // g.forEach( a => {
        //     content += '<div><img src="/media/photos/facility/'+a+'" alt="'+ item.name +'" class="w-50"></div>'
        // });

        content =
          '<div class="text-start">' +
          "</div>" +
          "<div>" +
          '<img src="/media/photos/facility/' +
          item.gallery[0] +
          '" alt="' +
          item.name +
          '" class="w-50">' +
          "</div>";

        Swal.fire({
          title: title,
          html: content,
          width: "50%",
          position: "top",
        });
      },
    });
  }
}
// Create legend
function getLegend() {
  const icons = {
    // ng: {
    //   name: "Country",
    //   icon: baseUrl + "/media/icon/negara.png",
    // },
    my: {
      name: "Malaysia",
      icon: baseUrl + "/media/icon/malaysia.png",
    },
    sg: {
      name: "Singapore",
      icon: baseUrl + "/media/icon/singapore.png",
    },
    br: {
      name: "Brunei",
      icon: baseUrl + "/media/icon/brunei.png",
    },
    // pr: {
    //   name: "Province",
    //   icon: baseUrl + "/media/icon/provinsinew.png",
    // },
    // kk: {
    //   name: "Regency/City",
    //   icon: baseUrl + "/media/icon/provinsinew.png",
    // },
    // kc: {
    //   name: "District",
    //   icon: baseUrl + "/media/icon/provinsinew.png",
    // },
    // na: {
    //   name: "Village",
    //   icon: baseUrl + "/media/icon/provinsinew.png",
    // },
    // dw: {
    //   name: "Tourism Village",
    //   icon: baseUrl + "/media/icon/provinsinew.png",
    // },
    attraction: {
      name: "Attraction",
      icon: baseUrl + "/media/icon/package.png",
    },
    lsa: {
      name: "Lake Singkarak Act",
      icon: baseUrl + "/media/icon/water.png",
    },
    rg: {
      name: "Traditional House",
      icon: baseUrl + "/media/icon/marker_rg.png",
    },
    ho: {
      name: "Homestay",
      icon: baseUrl + "/media/icon/homestay.png",
    },
    cp: {
      name: "Culinary Place",
      icon: baseUrl + "/media/icon/culinary.png",
    },
    sp: {
      name: "Souvenir Place",
      icon: baseUrl + "/media/icon/souvenir.png",
    },
    wp: {
      name: "Worship Place",
      icon: baseUrl + "/media/icon/worship.png",
    },
    fc: {
      name: "Facility",
      icon: baseUrl + "/media/icon/facility.png",
    },
  };

  const title = '<p class="fw-bold fs-6">Legend</p>';
  $("#legend").append(title);

  for (key in icons) {
    const type = icons[key];
    const name = type.name;
    const icon = type.icon;
    const div = '<div><img src="' + icon + '"> ' + name + "</div>";

    $("#legend").append(div);
  }
  map.controls[google.maps.ControlPosition.LEFT_BOTTOM].push(legend);
}

// // toggle legend element
// function viewLegend() {
//   if ($("#legend").is(":hidden")) {
//     $("#legend").show();
//   } else {
//     $("#legend").hide();
//   }
// }

function viewLegend() {
  console.log("Legend exists:", $("#legend").length > 0); // Periksa apakah elemen ada
  console.log("Legend visibility before toggle:", $("#legend").is(":visible"));

  if ($("#legend").is(":hidden")) {
    $("#legend").show();
  } else {
    $("#legend").hide();
  }

  console.log("Legend visibility after toggle:", $("#legend").is(":visible"));
}

function getLegendMobile() {
  const icons = {
    attraction: {
      name: "Attraction",
      icon: baseUrl + "/media/icon/package.png",
    },
    lsa: {
      name: "Lake Singkarak Act",
      icon: baseUrl + "/media/icon/water.png",
    },
    rg: {
      name: "Traditional House",
      icon: baseUrl + "/media/icon/marker_rg.png",
    },
    ho: {
      name: "Homestay",
      icon: baseUrl + "/media/icon/homestay.png",
    },
    cp: {
      name: "Culinary Place",
      icon: baseUrl + "/media/icon/culinary.png",
    },
    sp: {
      name: "Souvenir Place",
      icon: baseUrl + "/media/icon/souvenir.png",
    },
    wp: {
      name: "Worship Place",
      icon: baseUrl + "/media/icon/worship.png",
    },
    fc: {
      name: "Facility",
      icon: baseUrl + "/media/icon/facility.png",
    },
  };

  const title = '<p class="fw-bold fs-6">Legend</p>';
  $("#legend").append(title);

  for (key in icons) {
    const type = icons[key];
    const name = type.name;
    const icon = type.icon;
    const div = '<div><img src="' + icon + '"> ' + name + "</div>";

    $("#legendMobile").append(div);
  }
  map.controls[google.maps.ControlPosition.RIGHT_TOP].push(legendMobile);
}

// toggle legend element
function viewLegendMobile() {
  if ($("#legendMobile").is(":hidden")) {
    $("#legendMobile").show();
  } else {
    $("#legendMobile").hide();
  }
}

// Update preview of uploaded photo profile
function showPreview(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = function (e) {
      $("#avatar-preview").attr("src", e.target.result).width(300).height(300);
    };
    reader.readAsDataURL(input.files[0]);
  }
}

// Find object by name
function findByName(category) {
  clearRadius();
  clearRoute();
  clearMarker();
  clearUser();
  destinationMarker.setMap(null);
  google.maps.event.clearListeners(map, "click");
  closeNearby();

  let name;
  if (category === "PA") {
    name = document.getElementById("namePA").value;
    $.ajax({
      url: baseUrl + "/api/package/findByName",
      type: "POST",
      data: {
        name: name,
      },
      dataType: "json",
      success: function (response) {
        displayFoundObject(response);
        boundToObject();
      },
    });
  }
}

// Get list of package type
function getType() {
  let type;
  $("#typePASelect").empty();
  $.ajax({
    url: baseUrl + "/api/package/type",
    dataType: "json",
    success: function (response) {
      let data = response.data;
      for (i in data) {
        let item = data[i];
        type =
          '<option value="' + item.id + '">' + item.type_name + "</option>";
        $("#typePASelect").append(type);
      }
    },
  });
}

// Find object by Type
function findByType(object) {
  clearRadius();
  clearRoute();
  clearMarker();
  clearUser();
  destinationMarker.setMap(null);
  google.maps.event.clearListeners(map, "click");
  closeNearby();

  if (object === "PA") {
    let type = document.getElementById("typePASelect").value;
    $.ajax({
      url: baseUrl + "/api/package/findByType",
      type: "POST",
      data: {
        type: type,
      },
      dataType: "json",
      success: function (response) {
        displayFoundObject(response);
        boundToObject();
      },
    });
  }
}

// function showMapExplore(category = null) {
//   clearMarker();
//   clearRadius();
//   clearRoute();

//   const apiUrls = {
//     cp: baseUrl + "/api/culinaryPlace",
//     // th: baseUrl + "/api/traditionalHouse",
//     // rg: baseUrl + "/api/homestay",
//     // ho: baseUrl + "/api/homestayhomestay",
//     // sp: baseUrl + "/api/souvenirPlace",
//     // wp: baseUrl + "/api/worshipPlace",
//     // at: baseUrl + "/api/attraction",
//   };

//   // Jika kategori null, ambil semua data
//   const categoriesToFetch = category ? [category] : Object.keys(apiUrls);

//   categoriesToFetch.forEach((cat) => {
//     const URI = apiUrls[cat];
//     if (!URI) {
//       console.error("Invalid category provided:", cat);
//       return;
//     }

//     // Fetch data untuk kategori tertentu
//     $.ajax({
//       url: URI,
//       dataType: "json",
//       success: function (response) {
//         let data = response.data;
//         for (let i in data) {
//           let item = data[i];
//           objectMarker(item.id, item.lat, item.lng);
//         }
//         boundToObject();
//         // Tampilkan tabel untuk kategori yang sesuai
//         $("#table-cp").show();
//         $("#result-exploreall-col").show();
//         console.log("Data fetched for category:", cat, data);
//       },
//       error: function (xhr, status, error) {
//         console.error("Error fetching data for category:", cat, error);
//       },
//     });
//   });
//   // $("#table-cp").show();
//   // $("#result-allexplore-col").show();
// }

function showMapExplore(category = null) {
  clearMarker();
  clearRadius();
  clearRoute();

  const apiUrls = {
    cp: baseUrl + "/api/culinaryPlace",
    th: baseUrl + "/api/traditionalHouse",
    wp: baseUrl + "/api/worshipPlace",
    ho: baseUrl + "/api/homestayhomestay",
    sp: baseUrl + "/api/souvenirPlace",
    at: baseUrl + "/api/attraction",
  };

  // Tentukan kategori yang ingin diambil
  const categoriesToFetch = category ? [category] : Object.keys(apiUrls);

  categoriesToFetch.forEach((cat) => {
    const URI = apiUrls[cat];
    if (!URI) {
      console.error("Invalid category provided:", cat);
      return;
    }

    $.ajax({
      url: URI,
      dataType: "json",
      success: function (response) {
        let data = response.data;

        // Debug: pastikan data terlihat
        console.log(`Data for category ${cat}:`, data);

        if (data.length === 0) {
          console.warn(`No data available for category ${cat}`);
          $(`#table-${cat}`).html(
            "<tr><td colspan='4'>No data available</td></tr>"
          );
          return;
        }

        // Isi tabel dengan data
        let tableBody = `<thead>
                           <tr>
                             <th>No</th>
                             <th>Name</th>
                             <th>Latitude</th>
                             <th>Longitude</th>
                           </tr>
                         </thead><tbody>`;
        data.forEach((item, index) => {
          tableBody += `<tr>
                          <td>${index + 1}</td>
                          <td>${item.name || "-"}</td>
                          <td>${item.lat}</td>
                          <td>${item.lng}</td>
                        </tr>`;

          // Tambahkan marker ke peta
          objectMarker(item.id, item.lat, item.lng);
        });
        tableBody += "</tbody>";

        // Masukkan data ke tabel dengan ID sesuai kategori
        $(`#table-${cat}`).html(tableBody).show();
        boundToObject();
        console.log("Data fetched for category:", cat, data);
      },
      error: function (xhr, status, error) {
        console.error("Error fetching data for category:", cat, error);
      },
    });
  });

  // Tampilkan kolom hasil
  $("#result-explore-col").show();
  $("#list-rec-col").hide();
}

// Show All in Explore Sumpu
function showMap(id = null) {
  let URI;

  clearMarker();
  clearRadius();
  clearRoute();

  if (id == "cp") {
    URI = baseUrl + "/api/culinaryPlace";
  } else if (id == "th") {
    URI = baseUrl + "/api/traditionalHouse";
  } else if (id == "rg") {
    URI = baseUrl + "/api/homestay";
  } else if (id == "ho") {
    URI = baseUrl + "/api/homestayhomestay";
  } else if (id == "sp") {
    URI = baseUrl + "/api/souvenirPlace";
  } else if (id == "wp") {
    URI = baseUrl + "/api/worshipPlace";
  }

  // currentUrl = '';
  $.ajax({
    url: URI,
    dataType: "json",
    success: function (response) {
      let data = response.data;
      for (i in data) {
        let item = data[i];
        // currentUrl = currentUrl + item.id;
        // currentUrl = currentUrl;
        objectMarkerExplore(
          item.id,
          item.lat,
          item.lng,
          item.status,
          item.homestay_status
        );
      }
      boundToObject();
    },
  });
}

// Set map to coordinate put by user
function findCoords(object) {
  clearMarker();
  // mapMarkers.forEach(marker => marker.setMap(null));
  google.maps.event.clearListeners(map, "click");

  const lat = Number(document.getElementById("latitude").value);
  const lng = Number(document.getElementById("longitude").value);

  if (lat === 0 || lng === 0 || isNaN(lat) || isNaN(lng)) {
    return Swal.fire("Please input Lat and Long");
  }

  let pos = new google.maps.LatLng(lat, lng);
  map.panTo(pos);

  // Creating a marker and placing it on the map
  const marker = new google.maps.Marker({
    position: pos,
    map: map,
    title: "Selected Location",
  });

  // You can also add an info window to the marker if needed
  const infoWindow = new google.maps.InfoWindow({
    content: "Latitude: " + lat + "<br>Longitude: " + lng,
  });

  // Event listener to display info window when the marker is clicked
  marker.addListener("click", function () {
    infoWindow.open(map, marker);
  });
}

// Unselect shape on drawing map
function clearSelection() {
  if (selectedShape) {
    selectedShape.setEditable(false);
    selectedShape = null;
  }
}

// Make selected shape editable on maps
function setSelection(shape) {
  clearSelection();
  selectedShape = shape;
  shape.setEditable(true);
}

// Remove selected shape on maps
function deleteSelectedShape() {
  if (selectedShape) {
    document.getElementById("latitude").value = "";
    document.getElementById("longitude").value = "";
    document.getElementById("geo-json").value = "";
    clearMarker();
    selectedShape.setMap(null);
    // To show:
    drawingManager.setOptions({
      drawingMode: google.maps.drawing.OverlayType.POLYGON,
      drawingControl: true,
    });
  }
}

// Initialize drawing manager on maps
function initDrawingManager(edit = false) {
  const drawingManagerOpts = {
    drawingMode: google.maps.drawing.OverlayType.POLYGON,
    drawingControl: true,
    drawingControlOptions: {
      position: google.maps.ControlPosition.TOP_CENTER,
      drawingModes: [google.maps.drawing.OverlayType.POLYGON],
    },
    polygonOptions: {
      fillColor: "blue",
      strokeColor: "blue",
      editable: true,
    },
    map: map,
  };
  drawingManager.setOptions(drawingManagerOpts);
  let newShape;

  if (!edit) {
    google.maps.event.addListener(
      drawingManager,
      "overlaycomplete",
      function (event) {
        drawingManager.setOptions({
          drawingControl: false,
          drawingMode: null,
        });
        newShape = event.overlay;
        newShape.type = event.type;
        setSelection(newShape);
        saveSelection(newShape);

        google.maps.event.addListener(newShape, "click", function () {
          setSelection(newShape);
        });
        google.maps.event.addListener(newShape.getPath(), "insert_at", () => {
          saveSelection(newShape);
        });
        google.maps.event.addListener(newShape.getPath(), "remove_at", () => {
          saveSelection(newShape);
        });
        google.maps.event.addListener(newShape.getPath(), "set_at", () => {
          saveSelection(newShape);
        });
      }
    );
  } else {
    drawingManager.setOptions({
      drawingControl: false,
      drawingMode: null,
    });

    newShape = drawGeom();
    newShape.type = "polygon";
    setSelection(newShape);
    saveSelection(newShape);

    google.maps.event.addListener(newShape, "click", function () {
      setSelection(newShape);
    });
    google.maps.event.addListener(newShape.getPath(), "insert_at", () => {
      saveSelection(newShape);
    });
    google.maps.event.addListener(newShape.getPath(), "remove_at", () => {
      saveSelection(newShape);
    });
    google.maps.event.addListener(newShape.getPath(), "set_at", () => {
      saveSelection(newShape);
    });
  }

  google.maps.event.addListener(map, "click", clearSelection);
  google.maps.event.addDomListener(
    document.getElementById("clear-drawing"),
    "click",
    deleteSelectedShape
  );
}

// Get geoJSON of selected shape on map
function saveSelection(shape) {
  let str_input = "MULTIPOLYGON(((";
  let coord = [];
  let centroid = [0.0, 0.0];
  const paths = shape.getPath().getArray();

  for (let i = 0; i < paths.length; i++) {
    centroid[0] += paths[i].lat();
    centroid[1] += paths[i].lng();
    coord[i] = paths[i].lng() + " " + paths[i].lat();
    str_input += paths[i].lng() + " " + paths[i].lat() + ",";
  }

  str_input = str_input + "" + coord[0] + ")))";
  const totalPaths = paths.length;
  centroid[0] = centroid[0] / totalPaths;
  centroid[1] = centroid[1] / totalPaths;

  // console.log(str_input)

  let pos = new google.maps.LatLng(centroid[0], centroid[1]);
  map.panTo(pos);

  clearMarker();
  let marker = new google.maps.Marker();
  markerOption = {
    position: pos,
    animation: google.maps.Animation.DROP,
    map: map,
  };
  marker.setOptions(markerOption);
  markerArray["new"] = marker;

  document.getElementById("latitude").value = centroid[0].toFixed(8);
  document.getElementById("longitude").value = centroid[1].toFixed(8);
  document.getElementById("multipolygon").value = str_input;

  const dataLayer = new google.maps.Data();
  dataLayer.add(
    new google.maps.Data.Feature({
      geometry: new google.maps.Data.Polygon([shape.getPath().getArray()]),
    })
  );
  dataLayer.toGeoJson(function (object) {
    document.getElementById("geo-json").value = JSON.stringify(
      object.features[0].geometry
    );
  });
}

// Draw current GeoJSON on drawing manager
function drawGeom() {
  const geoJSON = $("#geo-json").val();
  if (geoJSON !== "") {
    const geoObj = JSON.parse(geoJSON);
    const coords = geoObj.coordinates[0][0];
    // console.log(coords)
    let polygonCoords = [];
    for (i in coords) {
      polygonCoords.push({ lat: coords[i][1], lng: coords[i][0] });
    }
    // console.log(polygonCoords)
    const polygon = new google.maps.Polygon({
      paths: polygonCoords,
      fillColor: "blue",
      strokeColor: "blue",
      editable: true,
    });
    polygon.setMap(map);
    return polygon;
  }
}
// Delete selected Users
function deleteUsers(id = null, username = null, csrfToken = null) {
  var csrfToken = $('input[name="<?= csrf_token() ?>"]').val();

  if (id === null) {
    return Swal.fire("ID cannot be null");
  }

  let content, apiUri;
  content = "Users";
  apiUri = "users/";

  Swal.fire({
    title: "Delete " + content + "?",
    text: "You are about to remove " + username,
    icon: "warning",
    showCancelButton: true,
    denyButtonText: "Delete",
    confirmButtonColor: "#dc3545",
    cancelButtonColor: "#343a40",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: baseUrl + "api/" + apiUri + id,
        type: "DELETE",
        dataType: "json",
        headers: {
          "X-CSRF-TOKEN": csrfToken,
        },
        success: function (response) {
          if (response.status === 200) {
            Swal.fire(
              "Deleted!",
              "Successfully remove " + username,
              "success"
            ).then((result) => {
              if (result.isConfirmed) {
                document.location.reload();
              }
            });
          } else {
            Swal.fire("Failed", "Delete " + username + " failed!", "warning");
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.error("AJAX Error:", textStatus, errorThrown);
          Swal.fire(
            "Failed",
            "An error occurred while trying to delete " + username,
            "error"
          );
        },
      });
    }
  });
}

// Delete selected object
function deleteObjectOld(id = null, name = null, user = false) {
  if (id === null) {
    return Swal.fire("ID cannot be null");
  }

  let content, apiUri;
  if (id.substring(0, 2) === "EV") {
    content = "Event";
    apiUri = "event/";
  } else if (id.substring(0, 1) === "P") {
    content = "Package";
    apiUri = "package/";
  } else if (id.substring(0, 2) === "FC") {
    content = "Facility";
    apiUri = "facility/";
  } else if (id.substring(0, 2) === "AT") {
    content = "Attraction";
    apiUri = "attraction/";
  } else if (id.substring(0, 2) === "CP") {
    content = "Culinary Place";
    apiUri = "culinaryPlace/";
  } else if (id.substring(0, 2) === "TH") {
    content = "Traditional House";
    apiUri = "traditionalHouse/";
  } else if (id.substring(0, 2) === "HO") {
    content = "Homestay";
    apiUri = "homestay/";
  } else if (id.substring(0, 2) === "WP") {
    content = "Worship Place";
    apiUri = "worshipPlace/";
  } else if (id.substring(0, 2) === "SP") {
    content = "Souvenir Place";
    apiUri = "souvenirPlace/";
  } else if (id.substring(0, 1) === "S") {
    content = "Service Package";
    apiUri = "servicepackage/";
  } else if (id.substring(0, 1) === "T") {
    content = "Package Type";
    apiUri = "packagetype/";
  } else if (id.substring(0, 2) === "HO") {
    content = "Homestay";
    apiUri = "homestay/";
  } else if (id.substring(0, 2) === "AN") {
    content = "Announcement";
    apiUri = "announcement/";
  } else if (id.substring(0, 1) === "S") {
    content = "Admin";
    apiUri = "admin/";
  } else if (id.substring(0, 7) === "R") {
    content = "Reservation";
    apiUri = "reservation/";
  } else if (user === true) {
    content = "User";
    apiUri = "user/";
  }

  Swal.fire({
    title: "Delete " + content + "?",
    text: "You are about to remove " + name,
    icon: "warning",
    showCancelButton: true,
    denyButtonText: "Delete",
    confirmButtonColor: "#dc3545",
    cancelButtonColor: "#343a40",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        // url: baseUrl + "/api/" + apiUri + id,
        // type: "DELETE",
        url: baseUrl + "dashboard/" + apiUri + "delete/" + id,
        type: "POST",
        data: {
          // Data
          id: id,
        },
        dataType: "json",
        success: function (response) {
          if (response.status === 200) {
            Swal.fire(
              "Deleted!",
              "Successfully remove " + name,
              "success"
            ).then((result) => {
              if (result.isConfirmed) {
                document.location.reload();
              }
            });
            // document.location.reload();
          } else {
            Swal.fire("Failed", "Delete " + name + " failed!", "warning");
            // document.location.reload();
          }
          //   document.location.reload();
        },
      });
      document.location.reload();
    }
  });
}

// Delete selected object
function deleteObject(id = null, name = null, user = false) {
  if (id === null) {
    return Swal.fire("ID cannot be null");
  }

  let content, apiUri;
  if (id.substring(0, 2) === "EV") {
    content = "Event";
    apiUri = "event/";
  } else if (id.substring(0, 1) === "P") {
    content = "Package";
    apiUri = "package/";
  } else if (id.substring(0, 2) === "FC") {
    content = "Facility";
    apiUri = "facility/";
  } else if (id.substring(0, 2) === "AT") {
    content = "Attraction";
    apiUri = "attraction/";
  } else if (id.substring(0, 2) === "CP") {
    content = "Culinary Place";
    apiUri = "culinaryPlace/";
  } else if (id.substring(0, 2) === "TH") {
    content = "Traditional House";
    apiUri = "traditionalHouse/";
  } else if (id.substring(0, 2) === "HO") {
    content = "Homestay";
    apiUri = "homestay/";
  } else if (id.substring(0, 2) === "WP") {
    content = "Worship Place";
    apiUri = "worshipPlace/";
  } else if (id.substring(0, 2) === "SP") {
    content = "Souvenir Place";
    apiUri = "souvenirPlace/";
  } else if (id.substring(0, 1) === "S") {
    content = "Service Package";
    apiUri = "servicepackage/";
  } else if (id.substring(0, 1) === "T") {
    content = "Package Type";
    apiUri = "packagetype/";
  } else if (id.substring(0, 2) === "HO") {
    content = "Homestay";
    apiUri = "homestay/";
  } else if (id.substring(0, 2) === "AN") {
    content = "Announcement";
    apiUri = "announcement/";
  } else if (id.substring(0, 1) === "S") {
    content = "Admin";
    apiUri = "admin/";
  } else if (id.substring(0, 7) === "R") {
    content = "Reservation";
    apiUri = "reservation/";
  } else if (user === true) {
    content = "User";
    apiUri = "users/";
  }

  Swal.fire({
    title: "Delete " + content + "?",
    text: "You are about to remove " + name,
    icon: "warning",
    showCancelButton: true,
    denyButtonText: "Delete",
    confirmButtonColor: "#dc3545",
    cancelButtonColor: "#343a40",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: baseUrl + "dashboard/" + apiUri + "deleteobject/" + id,
        type: "POST",
        data: {
          // Data
          id: id,
        },
        dataType: "json",
        success: function (response) {
          if (response.status === 200) {
            Swal.fire(
              "Deleted!",
              "Successfully remove " + name,
              "success"
            ).then((result) => {
              if (result.isConfirmed) {
                document.location.reload();
              }
            });
            // document.location.reload();
          } else {
            Swal.fire("Failed", "Delete " + name + " failed!", "warning");
            // document.location.reload();
          }
          //   document.location.reload();
        },
      });
      document.location.reload();
    }
  });
}
function deleteCart(
  package_id = null,
  name = null,
  user_id = null,
  user = false
) {
  if (package_id === null) {
    return Swal.fire("ID cannot be null");
  }

  let content, apiUri;
  if (package_id.substring(0, 1) === "P") {
    content = "Cart";
    apiUri = "cart/";
  }

  Swal.fire({
    title: "Delete " + content + "?",
    text: "You are about to remove package " + name + " from cart",
    icon: "warning",
    showCancelButton: true,
    denyButtonText: "Delete",
    confirmButtonColor: "#dc3545",
    cancelButtonColor: "#343a40",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: baseUrl + "/api/deleteCart",
        type: "POST",
        data: {
          package_id: package_id,
          user_id: user_id,
        },
        dataType: "json",
        success: function (response) {
          if (response.status === 200) {
            Swal.fire(
              "Deleted!",
              "Successfully remove " + name,
              "success"
            ).then((result) => {
              if (result.isConfirmed) {
                // Reload halaman setelah penghapusan berhasil
                location.reload();
              }
            });
          } else {
            Swal.fire("Failed", "Delete " + name + " failed!", "warning");
          }
        },
      });
    }
  });
}

// function confirmDeletereservation(formId) {
//   Swal.fire({
//     title: "Are you sure you want to cancel this reservation?",
//     text: "This reservation has not been confirmed. We will no longer process this reservation if you cancel it.",
//     icon: "warning",
//     showCancelButton: true,
//     confirmButtonColor: "#d33",
//     cancelButtonColor: "#3085d6",
//     confirmButtonText: "Yes, cancel!",
//     cancelButtonText: "Cancel",
//   }).then((result) => {
//     if (result.isConfirmed) {
//       // Jika pengguna mengonfirmasi, submit formulir
//       document.getElementById(formId).submit(); // Mengirim formulir
//     } else if (result.dismiss === Swal.DismissReason.cancel) {
//       // Jika pengguna membatalkan, tindakan apa yang ingin Anda lakukan (contoh: tampilkan pesan)
//       Swal.fire("Not canceled", "Your reservation will be processed :)", "info");
//     }
//   });
// }

// function showAlert() {
//   Swal.fire({
//     title: "Unable to cancel because this reservation has been confirmed by admin",
//     text: "You need to request a cancellation on detail page to cancel this reservation",
//     icon: "info",
//     confirmButtonColor: "#3085d6",
//     confirmButtonText: "OK",
//   });
//   return false;
// }

function confirmDeletePackageType(formId) {
  $id = document.getElementById("id").value;

  Swal.fire({
    title: "Are you sure you want to delete this package type",
    text: "You cannot restore this data!!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Yes, delete!",
    cancelButtonText: "Cancel",
  }).then((result) => {
    if (result.isConfirmed) {
      // Jika pengguna mengonfirmasi, submit formulir
      // Mengirim data ke server menggunakan AJAX
      $.ajax({
        url: baseUrl + "/dashboard/packagetype/delete/" + $id,
        type: "POST",
        data: {
          // Data
          id: formId,
        },
        dataType: "json",
        success: function (response) {},
      });
      document.getElementById(formId).submit(); // Mengirim formulir

      console.log("Berhasil menghapus reservasi");

      // document.getElementById(formId).submit(); // Mengirim formulir
    } else if (result.dismiss === Swal.DismissReason.cancel) {
      // Jika pengguna membatalkan, tindakan apa yang ingin Anda lakukan (contoh: tampilkan pesan)
      Swal.fire("Not deleted", "Your reservation is safe :)", "info");
    }
  });
}

function confirmDeletereservation(formId) {
  $id = document.getElementById("id").value;

  Swal.fire({
    title: "Are you sure you want to delete the reservation",
    text: "You cannot restore this data!!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Yes, delete!",
    cancelButtonText: "Cancel",
  }).then((result) => {
    if (result.isConfirmed) {
      // Jika pengguna mengonfirmasi, submit formulir
      // Mengirim data ke server menggunakan AJAX
      $.ajax({
        url: baseUrl + "/web/detailreservation/savedelete/" + $id,
        type: "POST",
        data: {
          // Data
          id: formId,
        },
        dataType: "json",
        success: function (response) {},
      });
      document.getElementById(formId).submit(); // Mengirim formulir

      console.log("Berhasil menghapus reservasi");

      // document.getElementById(formId).submit(); // Mengirim formulir
    } else if (result.dismiss === Swal.DismissReason.cancel) {
      // Jika pengguna membatalkan, tindakan apa yang ingin Anda lakukan (contoh: tampilkan pesan)
      Swal.fire("Not deleted", "Your reservation is safe :)", "info");
    }
  });
}

function showAlert() {
  Swal.fire({
    title: "Unable to delete reservation",
    text: "Reservations cannot be deleted because the admin has confirmed them!",
    icon: "info",
    confirmButtonColor: "#3085d6",
    confirmButtonText: "OK",
  });
  return false;
}

function confirmDelete(itemNumber) {
  Swal.fire({
    title: "Are you sure you deleted this unit homestay?",
    text: "You cannot restore this data!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Yes, delete!",
    cancelButtonText: "Cancel",
  }).then((result) => {
    if (result.isConfirmed) {
      document.getElementById("deleteForm" + itemNumber).submit(); // Mengirim formulir
    }
  });
}

function confirmDeleteFU(formId) {
  Swal.fire({
    title: "Are you sure you deleted this facility?",
    text: "You cannot restore this data!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Yes, delete!",
    cancelButtonText: "Cancel",
  }).then((result) => {
    if (result.isConfirmed) {
      document.getElementById(formId).submit(); // Mengirim formulir
    }
  });
}

function deleteActivity(
  package_id = null,
  day = null,
  activity = null,
  description = null
) {
  id = package_id;
  if (id === null) {
    return Swal.fire("ID cannot be null");
  }

  let content, apiUri;
  if (id.substring(0, 1) === "P") {
    content = "Activity Package Day";
    apiUri = "packageday/";
  }

  Swal.fire({
    title: "Delete " + content + "?",
    text: 'You are about to remove "' + description + '" on day ' + day,
    icon: "warning",
    showCancelButton: true,
    denyButtonText: "Delete",
    confirmButtonColor: "#dc3545",
    cancelButtonColor: "#343a40",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: baseUrl + "/api/" + apiUri + id,
        type: "DELETE",
        dataType: "json",
        success: function (response) {
          if (response.status === 200) {
            Swal.fire(
              "Deleted!",
              'Successfully remove "' + description + '" on day ' + day,
              "success"
            ).then((result) => {
              if (result.isConfirmed) {
                document.location.reload();
              }
            });
          } else {
            Swal.fire(
              "Failed",
              "Delete " + description + " failed!",
              "warning"
            );
          }
        },
      });
    }
  });
}

// function findUnitHomestay(id) {
//   $.ajax({
//     url: baseUrl + "/api/reservation/findhome",
//     type: "POST",
//     data: {
//       id: id,
//     },
//     dataType: "json",
//     success: function (response) {
//       displayUnitHomestay(response.data); // Access the 'data' property
//     },
//     error: function (jqXHR, textStatus, errorThrown) {
//       console.error("AJAX Error: ", textStatus, errorThrown);
//       // Optionally, you can display an error message or handle the error case here
//     },
//   });
// }

function findUnitHomestay(id) {
  // var totalPeople = $("#total_people").val();
  var checkInDate = $("#check_in").val();
  // var checkInTime = $("#time_check_in").val();
  // var checkOutDate = $("#check_out").val();
  // var checkOutTime = $("#time_check_out").val();

  $.ajax({
    url: baseUrl + "/api/reservation/findhome",
    type: "POST",
    data: {
      id: id,
      // total_people: totalPeople,
      check_in_date: checkInDate,
      // check_in_time: checkInTime,
      // check_out_date: checkOutDate,
      // check_out_time: checkOutTime,
    },
    dataType: "json",
    success: function (response) {
      displayUnitHomestay(response.data);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("AJAX Error: ", textStatus, errorThrown);
    },
  });
}

function displayUnitHomestay(units) {
  var cardHtml = '<div class="card">';
  cardHtml +=
    '<div class="card-header"><h4 class="card-title text-center">Homestay Units</h4></div>';

  cardHtml += '<div class="card-body">';
  cardHtml +=
    '<table class="table"><thead><tr><th>Name</th><th>Price</th><th>Capacity</th><th>Action</th></tr></thead><tbody>';

  units.forEach(function (unit) {
    cardHtml += "<tr>";
    cardHtml +=
      '<td class="text-left" style="text-align:center">' +
      unit.unit_name +
      "</td>";
    cardHtml +=
      '<td class="text-left" style="text-align:center">' + unit.price + "</td>";
    cardHtml +=
      '<td class="text-left" style="text-align:center">' +
      unit.capacity +
      "</td>";
    cardHtml +=
      '<td class="text-left" style="text-align:center"><button class="btn btn-primary" onclick="addToReservation(' +
      unit.id +
      ')">Add</button></td>';
    cardHtml += "</tr>";
  });

  cardHtml += "</tbody></table>";
  cardHtml += "</div></div>";

  // Append the card to the container element
  $("#unitCardContainer").html(cardHtml);
}

// Fungsi untuk menambahkan unit ke reservasi
function addToReservation(unitId) {
  // Implementasikan logika penambahan unit ke reservasi sesuai kebutuhan aplikasi Anda
  // Misalnya, tampilkan notifikasi atau lakukan operasi lainnya
  console.log("Add unit with ID " + unitId + " to reservation.");
}

// function activityOptions() {
//   var selectedPackage = document.getElementById("package").value;
//   var selectedDay = document.getElementById("day").value;

//     $("#activity").empty();
//     $.ajax({
//       url: baseUrl + "/api/activitynumber",
//       type: "GET",
//     data: {
//       package_id: selectedPackage,
//       day: selectedDay,
//     },
//       success: function (response) {
//         if (response && response.data) {
//           let data = response.data;
//           for (let i in data) {
//             let item = data[i];
//             if (item.category == "1") {
//               categoryname = "Group";
//             } else if (item.category == "2") {
//               categoryname = "Individu";
//             }
//             let activity =
//               '<option value="' +
//               item.id +
//               '">' +
//               item.name +
//               " - Rp" +
//               item.ticket_price +
//               " - " +
//               categoryname +
//               "</option>";
//             $("#activity").append(activity);
//           }
//         } else {
//           console.error("Invalid or missing data structure in AJAX response");
//         }
//       },
//       error: function (xhr, status, error) {
//         console.error("AJAX request failed:", status, error);
//       },
//     });

// }

function objectOptions() {
  var selectedActivity = document.getElementById("activity_type").value;

  let activityType;
  if (selectedActivity === "CP") {
    activityType = "culinaryPlace";
  } else if (selectedActivity === "TH") {
    activityType = "traditionalHouse";
  } else if (selectedActivity === "SP") {
    activityType = "souvenirPlace";
  } else if (selectedActivity === "WO") {
    activityType = "worshipPlace";
  } else if (selectedActivity === "FC") {
    activityType = "facility";
  } else if (selectedActivity === "A") {
    activityType = "attraction";
  }

  if (activityType == "culinaryPlace" || activityType == "souvenirPlace") {
    $("#object").empty();
    $.ajax({
      url: baseUrl + "/api/" + activityType,
      dataType: "json",
      success: function (response) {
        if (response && response.data) {
          let data = response.data;
          for (let i in data) {
            let item = data[i];
            let object =
              '<option value="' +
              item.id +
              '">' +
              item.name +
              " - Rp0 - Shopping not include</option>";
            $("#object").append(object);
          }
        } else {
          console.error("Invalid or missing data structure in AJAX response");
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX request failed:", status, error);
      },
    });
  } else if (activityType == "worshipPlace") {
    $("#object").empty();
    $.ajax({
      url: baseUrl + "/api/" + activityType,
      dataType: "json",
      success: function (response) {
        if (response && response.data) {
          let data = response.data;
          for (let i in data) {
            let item = data[i];
            let object =
              '<option value="' +
              item.id +
              '">' +
              item.name +
              " - Rp0</option>";
            $("#object").append(object);
          }
        } else {
          console.error("Invalid or missing data structure in AJAX response");
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX request failed:", status, error);
      },
    });
  } else if (activityType == "attraction" || activityType == "facility") {
    $("#object").empty();
    $.ajax({
      url: baseUrl + "/api/" + activityType,
      dataType: "json",
      success: function (response) {
        if (response && response.data) {
          let data = response.data;
          for (let i in data) {
            let item = data[i];
            if (item.category == "1") {
              categoryname = "Group";
            } else if (item.category == "2") {
              categoryname = "Individu";
            }
            let object =
              // '<option value="' + item.id + '">' + item.name + ' - ' + categoryname + ' - Rp' + item.price + '</option>';
              '<option value="' +
              item.id +
              '">' +
              item.name +
              " - Rp" +
              item.price +
              " - " +
              categoryname +
              "</option>";
            $("#object").append(object);
          }
        } else {
          console.error("Invalid or missing data structure in AJAX response");
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX request failed:", status, error);
      },
    });
  } else if (activityType == "traditionalHouse") {
    $("#object").empty();
    $.ajax({
      url: baseUrl + "/api/" + activityType,
      dataType: "json",
      success: function (response) {
        if (response && response.data) {
          let data = response.data;
          for (let i in data) {
            let item = data[i];
            if (item.category == "1") {
              categoryname = "Group";
            } else if (item.category == "2") {
              categoryname = "Individu";
            }
            let object =
              // '<option value="' + item.id + '">' + item.name + ' - ' + categoryname + ' - Rp' + item.price + '</option>';
              '<option value="' +
              item.id +
              '">' +
              item.name +
              " - Rp" +
              item.ticket_price +
              " - " +
              categoryname +
              "</option>";
            $("#object").append(object);
          }
        } else {
          console.error("Invalid or missing data structure in AJAX response");
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX request failed:", status, error);
      },
    });
  }
}

function serviceOptions() {
  var selectedService = document.getElementById("id_service").value;

  $("#service_price").empty();
  $("#service_min_capacity").empty();
  $.ajax({
    url: baseUrl + "/api/servicepackage/" + selectedService,
    dataType: "json",
    success: function (response) {
      if (response && response.data) {
        let data = response.data;

        let service_price =
          '<option value="' + data.id + '">' + data.price + "</option>";
        $("#service_price").append(service_price);
        let service_min_capacity =
          '<option value="' + data.id + '">' + data.min_capacity + "</option>";
        $("#service_min_capacity").append(service_min_capacity);
      } else {
        console.error("Invalid or missing data structure in AJAX response");
      }
    },
    error: function (xhr, status, error) {
      console.error("AJAX request failed:", status, error);
    },
  });
}

function chooseHome() {
  let checkInDate = document.getElementById("check_in").value;
  let checkOutDate = document.getElementById("check_out").value;
  let totalPeople = document.getElementById("total_people").value;
  let accomodationType1 = document.getElementById("accomodationType1").checked;
  // let accomodationType2 = document.getElementById("accomodationType2").checked;

  let url; // Declare a variable to store the URL
  let urlHome; // Declare a variable to store the URL

  // Check the status of radio buttons
  if (accomodationType1) {
    // If accomodationType1 is checked (Default)
    url = baseUrl + "/api/chooseHome";
    urlHome = "Default";
  }

  console.log(checkInDate);
  $.ajax({
    url: url,
    type: "POST",
    data: {
      checkInDate: checkInDate,
      checkOutDate: checkOutDate,
      totalPeople: totalPeople,
    },
    dataType: "json",
    success: function (response) {
      console.log(response);

      if (
        response.status === 200 &&
        response.datahome &&
        Object.keys(response.datahome).length > 0 &&
        Object.values(response.datahome).some(
          (dateData) => dateData.houses && dateData.houses.length > 0
        )
      ) {
        // If there is data, display it
        displayFoundHome(urlHome, response);
      } else {
        // If there is an error or no data, display a Sweet Alert
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "No available homestay units found for the total people and date criteria. Please try another input.",
        });
        // Disable the button
        document.getElementById("buttonStep1").disabled = true;
      }
    },
    error: function (xhr, status, error) {
      // If there is an AJAX error, display a Sweet Alert
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No available homestay units found for the total people and date criteria. Please try another input.",
      });
      // Disable the button
      document.getElementById("buttonStep1").disabled = true;
    },
  });
}

// Definisikan fungsi base_url
function base_url(url) {
  baseUrl = url;
}

function esc(text) {
  var div = document.createElement("div");
  div.textContent = text;
  return div.innerHTML;
}

// Fungsi untuk memformat angka menjadi format mata uang
function number_format(number, decimals, dec_point, thousands_sep) {
  number = (number + "").replace(/[^0-9+\-Ee.]/g, "");
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = typeof thousands_sep === "undefined" ? "," : thousands_sep,
    dec = typeof dec_point === "undefined" ? "." : dec_point,
    s = "",
    toFixedFix = function (n, prec) {
      var k = Math.pow(10, prec);
      return "" + Math.round(n * k) / k;
    };

  // Fiksasi untuk mengatasi masalah di beberapa browser
  s = (prec ? toFixedFix(n, prec) : "" + Math.round(n)).split(".");
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || "").length < prec) {
    s[1] = s[1] || "";
    s[1] += new Array(prec - s[1].length + 1).join("0");
  }

  return s.join(dec);
}

// Fungsi untuk menghindari ReferenceError pada HTML
// function esc(value) {
//   return value;
// }

function esc(value) {
  return $("<div/>").text(value).html();
}

function displayFoundHome(urlHome, response) {
  let defaultPrice = 0;
  if (urlHome == "Default") {
    var resultContainer = document.getElementById("result-container");
    resultContainer.innerHTML = "";

    var totalUnits = 0; // Initialize totalUnits counter

    Object.keys(response.datahome).forEach(function (dateKey) {
      const dateData = response.datahome[dateKey];
      const houses = dateData.houses;

      houses.forEach(function (homestay) {
        if (!homestay.units || homestay.units.length === 0) {
          return;
        }

        var unitAvailabilityContainer = document.createElement("div");
        unitAvailabilityContainer.className = "col-md-12 col-12";

        var unitHtml =
          '<div class="card" style="margin-bottom:0rem!important">' +
          '<div class="card-body">' +
          '<table class="table table-hover dt-head-center" id="table-manage">' +
          "<tbody>" +
          homestay.units
            .map(
              (item) =>
                "<tr>" +
                "<td style='vertical-align:inherit'>" +
                '<div class="form-check">' +
                '<input class="form-check-input unit-checkbox" type="checkbox" name="selected_units[]" value="' +
                esc(item.unit_type + "-" + item.unit_number) +
                '" id="unit' +
                esc(item.unit_number) +
                '" data-date="' +
                esc(dateKey) +
                '" data-homestay-id="' +
                esc(item.homestay_id) +
                '" data-unit-type="' +
                esc(item.unit_type) +
                '" data-unit-number="' +
                esc(item.unit_number) +
                '" data-price="' +
                esc(item.price) +
                '" data-unit-name="' +
                esc(item.unit_name) +
                '" data-home-name="' +
                esc(homestay.name) +
                '" data-capacity="' +
                esc(item.capacity) +
                '" checked disabled>' +
                '<label class="form-check-label" for="unit' +
                esc(item.unit_number) +
                '"></label>' +
                "</div>" +
                "</td>" +
                "<td style='width:110px; vertical-align:inherit'>" +
                esc(dateKey) +
                "</td>" +
                "<td style='width:150px; vertical-align:inherit'>" +
                '<img src="/media/photos/homestay/' +
                homestay.gallery[0] +
                '" class="img-fluid rounded-start" alt="Gallery Image" style="object-fit: cover; width: 150px; height:100px; border-top-right-radius: 0.25rem !important; border-bottom-right-radius: 0.25rem !important;">' +
                "</td>" +
                "<td style='width:150px; vertical-align:inherit'>" +
                esc(homestay.name) +
                "<br><br>Facilities:" +
                homestay.facilities
                  .map((facility) => "<li>" + esc(facility.name) + "</li>")
                  .join("") +
                "</td>" +
                "<td  style='vertical-align:inherit'>" +
                '<td style="width: 150px;vertical-align:inherit">' +
                '<img src="/media/photos/unithomestay/' +
                esc(item.url) +
                '" class="img-fluid rounded-start" alt="Gallery Image" style="object-fit: cover; width: 150px; height: 100px; border-top-right-radius: 0.25rem !important; border-bottom-right-radius: 0.25rem !important;">' +
                "</td>" +
                "<td style='width:150px;vertical-align:inherit'>" +
                esc(item.unit_name) +
                "<br><br><br>Facilities:" +
                item.facility_units
                  .map((facility_array) => {
                    return facility_array
                      .map((facility_unit) => {
                        return "<li>" + esc(facility_unit.name) + "</li>";
                      })
                      .join("");
                  })
                  .join("") +
                "</td>" +
                "<td style='vertical-align:inherit'>" +
                "Rp " +
                number_format(esc(item.price), 0, ",", ".") +
                "</td>" +
                "<td style='vertical-align:inherit'>" +
                '<i class="fa-solid fa-user"></i> ' +
                esc(item.capacity) +
                "</td>" +
                "</tr>"
            )
            .join("") +
          "</tbody>" +
          "</table>" +
          "</div>" +
          "</div>";

        unitAvailabilityContainer.innerHTML = unitHtml;
        resultContainer.appendChild(unitAvailabilityContainer);

        updateTotalPrice();

        totalUnits += homestay.units.length;
      });
    });

    unitTotal(totalUnits);
  }
}

function updateTotalPrice() {
  let totalPrice = parseInt($("#total_price").val()) || 0;
  let selectedUnitsInfo =
    '<table class="table" id="selectedUnitTable"><thead><tr><th>Date</th><th>ID</th><th>Type</th><th>Number</th><th>Homestay</th><th>Unit</th><th>Price</th><th>Cpty</th></tr></thead><tbody>'; // Untuk menyimpan informasi unit yang dipilih

  let totalUnitPrice = 0;

  // Loop through each checkbox that is checked
  $("input[name='selected_units[]']:checked").each(function () {
    const price = parseFloat($(this).data("price"));
    const date = $(this).data("date");
    const homestay_id = $(this).data("homestay-id");
    const unit_type = $(this).data("unit-type");
    const unit_number = $(this).data("unit-number");

    const unit_name = $(this).data("unit-name");
    const home_name = $(this).data("home-name");
    const capacity = $(this).data("capacity");

    // Dapatkan informasi unit yang dipilih
    // const unitInfo = $(this).val();
    // const unitInfoArray = unitInfo.split("-");
    // const unitType = unitInfoArray[0];
    // const unitNumber = unitInfoArray[1];

    // Cari homestay dan unit yang sesuai dengan tipe dan nomor unit
    // const homestay = response.datahome.houses.find(house => house.units.some(unit => unit.unit_type === unitType && unit.unit_number === unitNumber));
    // const unit = homestay.units.find(unit => unit.unit_type === unitType && unit.unit_number === unitNumber);

    // Tambahkan informasi ke tabel
    selectedUnitsInfo += "<tr>";
    selectedUnitsInfo += "<td>" + esc(date) + "</td>";
    selectedUnitsInfo += "<td>" + esc(homestay_id) + "</td>";
    selectedUnitsInfo += "<td>" + esc(unit_type) + "</td>";
    selectedUnitsInfo += "<td>" + esc(unit_number) + "</td>";

    selectedUnitsInfo += "<td>" + esc(home_name) + "</td>";
    selectedUnitsInfo += "<td>" + esc(unit_name) + "</td>";
    selectedUnitsInfo +=
      "<td>Rp " + number_format(esc(price), 0, ",", ".") + "</td>";
    selectedUnitsInfo += "<td>" + esc(capacity) + "</td>";
    selectedUnitsInfo += "</tr>";

    totalPrice += price;
    totalUnitPrice += price;
  });

  selectedUnitsInfo += "</tbody></table>";

  console.log("Total Price:", totalPrice);

  // Update the input for total price with the formatted amount
  $("#total_price_3").val("Total: Rp" + number_format(totalPrice, 0, ",", "."));
  $("#total_price_5").val("Total: Rp" + number_format(totalPrice, 0, ",", "."));
  $("#total_price_4").val(totalPrice);
  // $("#step3_total_price").val(totalPrice);
  $("#step3_total_price_homestay").val(totalUnitPrice);
  $("#step3_total_price_reservation").val(totalPrice);

  const deposit = totalPrice * 0.2;
  $("#step3_deposit").val(deposit);

  // $("#step3_total_reservation").val(totalPrice);
  $("#total_total_reservation").val(
    "Rp" + number_format(totalPrice, 0, ",", ".")
  );
  $("#total_total_homestay").val(
    "Rp" + number_format(totalUnitPrice, 0, ",", ".")
  );

  // Update informasi unit yang dipilih di formulir
  $("#selected-units-info").html(selectedUnitsInfo);
  // Tambahkan class 'table-responsive' pada tabel agar dapat discroll secara horizontal
  $("#selectedUnitTable").addClass("table-responsive");
}

function unitTotal(totalUnits) {
  var unitTotalInput = document.getElementById("unit_total");
  unitTotalInput.value = totalUnits;
}

function createReservation() {
  // Mendapatkan data dari input
  let package_id = document.getElementById("step3_package").value;
  let total_people = document.getElementById("step3_total_people").value;
  let check_in = document.getElementById("step3_check_in").value;
  let check_out = document.getElementById("step3_check_out").value;
  let time_check_in = document.getElementById("step3_time_check_in").value;
  let time_check_out = document.getElementById("step3_time_check_out").value;
  // let total_price = document.getElementById("total_total_reservation").value;
  let total_price = document.getElementById(
    "step3_total_price_reservation"
  ).value;
  let deposit = document.getElementById("step3_deposit").value;
  let note = document.getElementById("note").value;

  // let accomodationType = document.getElementById("accomodationType1").checked;
  // let accomodationTyp2e = document.getElementById("accomodationType2").checked;

  // Inisialisasi array untuk menyimpan data homestay
  let tableData = [];

  // Mengambil referensi ke tabel
  let table = document.getElementById("selectedUnitTable");

  // Loop melalui setiap baris tabel (mulai dari baris kedua karena baris pertama biasanya berisi judul kolom)
  for (let i = 1; i < table.rows.length; i++) {
    // Mendapatkan referensi ke setiap sel dalam baris
    let cells = table.rows[i].cells;

    // Mendapatkan nilai dari setiap sel menggunakan textContent
    let date = cells[0].textContent;
    let homestay_id = cells[1].textContent;
    let unit_type = cells[2].textContent;
    let unit_number = cells[3].textContent;
    let capacity = cells[7].textContent;

    // let homestay_id = cells[0].dataset.homestayId;
    // let unit_type = cells[1].dataset.unitType;
    // let unit_number = cells[2].dataset.unitNumber;

    // Membuat objek untuk setiap baris dan menambahkannya ke array
    let rowData = {
      date: date,
      homestay_id: homestay_id,
      unit_type: unit_type,
      unit_number: unit_number,
      capacity: capacity,
    };

    // Menambahkan objek rowData ke array tableData
    tableData.push(rowData);
  }

  console.log("Data Homestay:", tableData);

  // Mengirim data ke server menggunakan AJAX
  $.ajax({
    url: baseUrl + "/api/reservation/create",
    type: "POST",
    data: {
      // Data paket
      package_id: package_id,
      total_people: total_people,
      check_in: check_in,
      check_out: check_out,
      time_check_in: time_check_in,
      total_price: total_price,
      deposit: deposit,
      note: note,

      // Data homestay
      homestays: tableData,
    },
    dataType: "json",
    success: function (response) {
      console.log("Berhasil");
      $("fieldset").removeClass("current-step");
      $('fieldset[name="step4"]').addClass("current-step");
    },
  });
}

function createReservationSingle() {
  // Mendapatkan data dari input
  let package_id = document.getElementById("step3_package").value;
  let total_people = document.getElementById("step3_total_people").value;
  let check_in = document.getElementById("step3_check_in").value;
  let check_out = document.getElementById("step3_check_out").value;
  let time_check_in = document.getElementById("step3_time_check_in").value;
  let time_check_out = document.getElementById("step3_time_check_out").value;
  // let total_price = document.getElementById("total_total_reservation").value;
  let total_price = document.getElementById("step3_total_price").value;
  let deposit = document.getElementById("step3_deposit").value;
  let note = document.getElementById("note").value;

  // let accomodationType = document.getElementById("accomodationType1").checked;
  // let accomodationTyp2e = document.getElementById("accomodationType2").checked;

  // Mengirim data ke server menggunakan AJAX
  $.ajax({
    url: baseUrl + "/api/reservation/create",
    type: "POST",
    data: {
      // Data paket
      package_id: package_id,
      total_people: total_people,
      check_in: check_in,
      check_out: check_out,
      time_check_in: time_check_in,
      total_price: total_price,
      deposit: deposit,
      note: note,
    },
    dataType: "json",
    success: function (response) {
      console.log("Berhasil");
      $("fieldset").removeClass("current-step");
      $('fieldset[name="step4"]').addClass("current-step");

      // sendToEmailRequest(reservation_id, customer_email, package_id, reservation_date, reservation_time)
    },
    error: function (error) {
      console.error("Error:", error);
    },
  });
}

function addToCart(package_id) {
  // Kirim permintaan Ajax
  console.log(package_id);
  $.ajax({
    url: baseUrl + "/api/addCart",
    type: "POST",
    data: {
      package_id: package_id,
    },
    success: function (response) {
      // Tanggapan sukses, lakukan sesuatu jika diperlukan
      console.log("Item added to cart successfully.");

      // Periksa status dari respons JSON
      if (response.status === 200) {
        // Tampilkan SweetAlert sukses
        Swal.fire("Success", response.message[0], "success").then((result) => {
          if (result.isConfirmed) {
            // Redirect atau lakukan tindakan lain jika diperlukan setelah sukses
            document.location.reload();
          }
        });
      } else if (response.status === 400) {
        // Tampilkan SweetAlert sukses
        Swal.fire("Error", response.message[0], "error").then((result) => {
          if (result.isConfirmed) {
            // Redirect atau lakukan tindakan lain jika diperlukan setelah sukses
            document.location.reload();
          }
        });
      } else {
        // Tampilkan SweetAlert error
        Swal.fire("Error", response.message[0], "error");
      }
    },
    error: function () {
      // Tanggapan error, tampilkan pesan kesalahan
      Swal.fire("Package Already in Cart");
    },
  });
}

function extendPackage(id = null) {
  $.ajax({
    url: baseUrl + "/web/detailreservation/addextend/" + id,
    type: "POST",
    dataType: "json",
    success: function (response) {
      // Handle success response if needed

      // Redirect to the desired page
      window.location.href = "YOUR_REDIRECT_URL"; // Replace with the actual URL
    },
    error: function (jqXHR, textStatus, errorThrown) {
      // Handle error response if needed
      console.error("Error extending package:", textStatus, errorThrown);
    },
  });
}

function getTourismVillageInfo() {
  // Kirim permintaan Ajax
  $.ajax({
    url: baseUrl + "/api/tourismVillageInfo",
    type: "GET",
    success: function (response) {
      if (response.status === 200) {
        // Update HTML content with response data
        displayData(response.data);
      } else {
        console.error("Error: " + response.message);
      }
    },
    error: function () {
      console.log("Error occurred while fetching data.");
    },
  });
}

function displayData(data) {
  // Clear previous data
  $("#tourism-village-info").empty();
  // Append new data to HTML container
  data.forEach(function (item) {
    $("#tourism-village-info").append("<h4>" + item.name + "</h4>");
  });
}

function explorePackage() {
  $.ajax({
    url: baseUrl + "/api/explorePackage",
    type: "GET",
    success: function (response) {
      if (response.status === 200) {
        if (response.data && Array.isArray(response.data)) {
          // Panggil fungsi displayDataExplorePackage dengan response sebagai argumen
          // displayDataExplorePackage(response);
        } else {
          console.error(
            "Error: Invalid or missing data structure in response."
          );
        }
      } else {
        console.error("Error: " + response.message);
      }
    },
    error: function () {
      console.log("Error occurred while fetching data.");
    },
  });
}

function displayDataExplorePackage(response) {
  $("#table-data").empty();
  let data = response.data;
  for (let i = 0; i < data.length; i++) {
    let item = data[i];
    let days = item.day;
    let row =
      "<tr>" +
      '<td class="fw-bold">' +
      item.title + // Menggunakan kunci title
      "<br>";

    // Menambahkan tombol untuk setiap hari
    days.forEach((day) => {
      row +=
        '<div class="btn-group">' +
        '<button type="button" class="btn btn-primary btn-sm" onclick="add' +
        day.day +
        item.datapackage.id +
        '();">Day ' +
        day.day +
        "</button>" +
        '<button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false" data-bs-reference="parent">' +
        '<span class="visually-hidden">Toggle Dropdown</span>' +
        "</button>" +
        '<ul class="dropdown-menu">';

      // Menambahkan submenu untuk setiap aktivitas pada hari tersebut
      if (day.activities && day.activities.length > 1) {
        for (let i = 0; i < day.activities.length - 1; i++) {
          let currentActivity = day.activities[i];
          let nextActivity = day.activities[i + 1];
          row +=
            '<li><button type="button" onclick="routeBetweenObjects(' +
            currentActivity.lat +
            ", " +
            currentActivity.lng +
            ", " +
            nextActivity.lat +
            ", " +
            nextActivity.lng +
            ')" class="btn btn-outline-primary"><i class="fa fa-road"></i> Activity ' +
            currentActivity.activity +
            " ke " +
            nextActivity.activity +
            "</button></li>";
        }
      }

      row += "</ul>" + "</div>";
    });

    row +=
      "<br>" +
      //  '<a data-bs-toggle="tooltip" data-bs-placement="bottom" title="More Info" class="btn icon btn-primary mx-1" onclick="focusObject(`' + item.datapackage.id + '`);">' +
      //  '<span class="material-symbols-outlined">info</span>' +
      //  '</a>' +
      "</td>" +
      "</tr>";

    $("#table-data").append(row);
  }
}

function payMidtrans() {
  let res_id_deposit = document.getElementById("res_id_deposit").value;
  let res_id = document.getElementById("res_id").value;

  $.ajax({
    url: baseUrl + "/api/payMidtrans/" + res_id_deposit,
    type: "GET",
    success: function (response) {
      let myTokenDeposit = response.data;
      // Menampilkan modal
      $("#myModal").modal("show");
      $("#modalToken").text(response.data);
      $("#modalOrderId").text(res_id_deposit);
      $("#modalPackageName").text(response.package.name);
      $("#modalAmount").text(response.datareservation.deposit);

      // Menghapus tombol "bayar" sebelumnya jika ada
      $("#modalAmount").next("button").remove();

      // Membuat link pembayaran
      var paymentLink =
        "https://app.sandbox.midtrans.com/snap/v2/vtweb/" + response.data;

      // Menambahkan tombol Bayar di bawah token
      var payButton = $("<button>")
        .addClass("btn btn-outline-info")
        .text("Pay")
        .on("click", function () {
          window.open(paymentLink, "_blank"); // Buka link pembayaran dalam jendela baru
          // updateDepositClick(res_id, res_id_deposit, myTokenDeposit);
        });
      $("#modalAmount").after(payButton);
    },
    error: function () {
      // Tampilkan pesan kesalahan kepada pengguna jika permintaan gagal
      alert("Terjadi kesalahan saat memproses permintaan.");
    },
  });
}

function updateDepositClick(res_id, res_id_deposit, myTokenDeposit) {
  $.ajax({
    url: baseUrl + "/web/detailreservation/" + res_id + "/updatedepositcheck",
    type: "POST",
    data: {
      res_id: res_id,
      res_id_deposit: res_id_deposit,
      myTokenDeposit: myTokenDeposit,
    },
    dataType: "json",
    success: function (response) {
      console.log("Deposit check berhasil diperbarui");
      document.location.reload();

    },
    error: function (xhr, status, error) {
      console.error("Gagal memperbarui deposit check:", error);
      document.location.reload();

    },
  });
}

function payMidtransMyToken() {
  let res_id_deposit = document.getElementById("res_id_deposit").value;
  let res_id = document.getElementById("res_id").value;

  let reservation_package_name = document.getElementById(
    "reservation_package_name"
  ).value;
  let reservation_deposit = document.getElementById(
    "reservation_deposit"
  ).value;
  let myTokenDeposit = document.getElementById("myTokenDeposit").value;

  $("#myModal").modal("show");
  $("#modalToken").text(myTokenDeposit);
  $("#modalOrderId").text(res_id_deposit);
  $("#modalPackageName").text(reservation_package_name);
  $("#modalAmount").text(reservation_deposit);
  // Menghapus tombol "bayar" sebelumnya jika ada
  $("#modalAmount").next("button").remove();
  // Menambahkan tombol Bayar di bawah token
  var paymentLink =
    "https://app.sandbox.midtrans.com/snap/v2/vtweb/" + myTokenDeposit;
  var payButton = $("<a>")
    .attr("href", paymentLink)
    .attr("target", "_blank")
    .addClass("btn btn-outline-info")
    .text("Pay");
  payButton.on("click", function () {
    // Ketika tombol Bayar diklik, jalankan fungsi berikut
    // Misalnya, Anda dapat menambahkan logika tambahan di sini
    updateDepositClick(res_id, res_id_deposit, myTokenDeposit);
    // Anda juga dapat memanggil fungsi lain di sini, seperti updateDepositCheck()
  });
  $("#modalAmount").after(payButton);
}

function payMidtransFull() {
  let res_id_full = document.getElementById("res_id_full").value;

  $.ajax({
    url: baseUrl + "/api/payMidtransFull/" + res_id_full,
    type: "GET",
    success: function (response) {
      let myTokenFull = response.data;
      // Menampilkan modal
      $("#myModalFull").modal("show");
      $("#modalTokenFull").text(response.data);
      // $("#modalOrderId").text(response.datareservation.id);
      $("#modalOrderIdFull").text(res_id_full);
      $("#modalPackageNameFull").text(response.package.name);
      $("#modalAmountFull").text(response.amount);

      // Menghapus tombol "bayar" sebelumnya jika ada
      $("#modalAmountFull").next("button").remove();
      // Menambahkan tombol Bayar di bawah token
      // Membuat link pembayaran
      var paymentLink =
        "https://app.sandbox.midtrans.com/snap/v2/vtweb/" + response.data;

      // Menambahkan tombol Bayar di bawah token
      var payButton = $("<button>")
        .addClass("btn btn-outline-info")
        .text("Pay")
        .on("click", function () {
          window.open(paymentLink, "_blank"); // Buka link pembayaran dalam jendela baru
          // updateDepositClick(res_id, res_id_deposit, myTokenDeposit);
        });
      $("#modalAmountFull").after(payButton);
    },
    error: function () {
      // Tampilkan pesan kesalahan kepada pengguna jika permintaan gagal
      alert("Terjadi kesalahan saat memproses permintaan.");
    },
  });
}

function payMidtransMyTokenFull() {
  let res_id_full = document.getElementById("res_id_full").value;
  let res_id = document.getElementById("res_id").value;

  let reservation_package_name = document.getElementById(
    "reservation_package_name"
  ).value;
  let reservation_payment = document.getElementById(
    "reservation_payment"
  ).value;
  let myTokenFull = document.getElementById("myTokenFull").value;

  $("#myModalFull").modal("show");
  $("#modalTokenFull").text(myTokenFull);
  $("#modalOrderIdFull").text(res_id_full);
  $("#modalPackageNameFull").text(reservation_package_name);
  $("#modalAmountFull").text(reservation_payment);
  // Menghapus tombol "bayar" sebelumnya jika ada
  $("#myModalFull .modal-body").next("button").remove();
  // Membuat link pembayaran
  var paymentLink =
    "https://app.sandbox.midtrans.com/snap/v2/vtweb/" + myTokenFull;

  // Menambahkan tombol Bayar di dalam modal
  var payButton = $("<button>")
    .addClass("btn btn-outline-info")
    .text("Pay")
    .on("click", function () {
      window.open(paymentLink, "_blank"); // Buka link pembayaran dalam jendela baru
      updateFullClick(res_id, res_id_full, myTokenFull);
    });
  $("#myModalFull .modal-body").append(payButton); // Menambahkan tombol ke dalam modal
}

function updateFullClick(res_id, res_id_full, myTokenFull) {
  $.ajax({
    url: baseUrl + "/web/detailreservation/" + res_id + "/updatefullcheck",
    type: "POST",
    data: {
      res_id: res_id,
      res_id_full: res_id_full,
      myTokenFull: myTokenFull,
    },
    dataType: "json",
    success: function (response) {
      console.log("Full check berhasil diperbarui");
      document.location.reload();
    },
    error: function (xhr, status, error) {
      console.error("Gagal memperbarui full check:", error);
      document.location.reload();

    },
  });
}

function updateCapacity() {
  let id = document.getElementById("package_id_custom").value;
  let min_capacity = document.getElementById("min_capacity_custom").value;

  $.ajax({
    // url: baseUrl + "/web/package/updatecapacity/",
    url: baseUrl + "/web/package/updatecapacity",
    type: "POST",
    data: {
      id: id,
      min_capacity: min_capacity,
    },
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        Swal.fire({
          icon: "success",
          title: "Success",
          text: "Minimal capacity has been successfully updated.",
        }).then((result) => {
          if (result.isConfirmed) {
            document.location.reload();
          }
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Failed to update minimal capacity.",
        });
      }
      // document.location.reload();
    },
  });
}

function sendToEmailRequest(
  reservation_id,
  customer_email,
  package_id,
  reservation_date,
  reservation_time
) {
  // Mendapatkan alamat email pelanggan dari elemen HTML
  var customer_email = document.getElementById("customer_email").innerText;

  // Mengirim permintaan AJAX ke server dengan menggunakan library jQuery
  $.ajax({
    url: baseUrl + "/web/package/sendToEmailRequest",
    type: "POST",
    data: {
      customer_email: customer_email,
    },
    success: function (response) {
      // Handle response
      alert(response.message); // Tampilkan pesan respons dari server
    },
    error: function (xhr, status, error) {
      // Handle error
      console.error(xhr.responseText); // Log pesan kesalahan
    },
  });
}

function weatherNow() {
  const apiKey = "0ec1b86edc77ddcf8f5b6722561e564b";
  const cityName = "Kubu Gadang";

  const apiUrl = `https://api.openweathermap.org/data/2.5/weather?q=${cityName}&appid=${apiKey}&units=metric`;

  async function fetchWeather() {
    try {
      const response = await fetch(apiUrl);
      const data = await response.json();

      // Ambil data cuaca dari API
      const weatherDescription = data.weather[0].description;
      const temperature = data.main.temp;
      const humidity = data.main.humidity;
      const weatherIcon = data.weather[0].icon;
      const windSpeed = data.wind.speed;

      const capitalizeWords = (str) => {
        return str.replace(/\b\w/g, (char) => char.toUpperCase());
      };
      const capitalizedWeatherDescription = capitalizeWords(weatherDescription);

      document.getElementById("weather-info").innerHTML = `
    <span style="margin-right: 10px;">${cityName}, ID</span>
    <img src="http://openweathermap.org/img/wn/${weatherIcon}.png" alt="Weather Icon" style="margin-right: 10px; filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.5));" />
    <span style="margin-right: 10px;">${temperature}°C</span>
    <span style="margin-right: 10px;">${capitalizedWeatherDescription}</span>
    <span style="margin-right: 10px;">Humidity: ${humidity}%</span>
    <span style="margin-right: 10px;">Wind: ${windSpeed} m/s</span>
`;
    } catch (error) {
      console.error("Error fetching weather data:", error);
      document.getElementById("weather-info").innerHTML =
        "Failed to fetch weather data.";
    }
  }

  window.onload = fetchWeather;
}

function initMap6() {
    initMap();
    digitEstuaria();
}

function digitSilekLanyah() {
    const digitasi = new google.maps.Data();
    $.ajax({
        url: baseUrl + '/api/village',
        type: 'POST',
        data: {
            digitasi: 'AT001'
        },
        dataType: 'json',
        success: function (response) {
            const data = response.data;
            digitasi.addGeoJson(data);
            digitasi.setStyle({
                fillColor:'#FF0000',
                strokeWeight:0.8,
                strokeColor:'#FF0000',
                fillOpacity: 0.2,
                clickable: false
            });
            digitasi.setMap(map);
        }
    });
}