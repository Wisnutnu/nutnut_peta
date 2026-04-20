<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>WebGIS</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<!-- Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>

<style>
/* ===== BASIC ===== */
body {
    margin: 0;
    padding: 0;
    background: #f8f9fa;
}




/* ===== MAIN LAYOUT (MAP + SIDE PANEL) ===== */
.main-container {
    display: flex;
    gap: 20px;
    padding: 15px;
}

/* ===== MAP AREA ===== */
.map-chart-wrapper {
    flex: 1;
}

#map {
    height: 650px;
    width: 100%;
    border-radius: 10px;
    border: 3px solid #141566de;
}



/* ===== CHARTS ===== */
/* Wrapper grafik */
.charts-wrapper {
    margin-left: 240px;          /* Hindari sidebar */
    margin-top: 10px;
    padding: 15px;

    display: flex;
    flex-wrap: wrap;             /* BIAR RESPONSIVE */
    gap: 20px;

    width: calc(100% - 240px);   /* PENTING → lebar penuh sisa layar */
    box-sizing: border-box;
}

.chart-box {
    flex: 1;
    min-width: 350px;
    background: #fff;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.chart-area {
    height: 300px;
}

/* ===== INFO POPUP (MAP) ===== */
.info-box {
    position: absolute;
    bottom: 20px;
    left: 20px;
    background: rgba(31,11,11,0.9);
    padding: 10px;
    color: #fff;
    border-radius: 5px;
    z-index: 5000;
}

</style>
</head>

<body>

<?= $this->include('layout/admin/header') ?>
<?= $this->include('layout/admin/navbar') ?>
<?= $this->include('layout/sidebar/sidebar_admin') ?>

<!-- MAIN CONTENT AREA -->
<div class="main-container">
    <div class="map-chart-wrapper">

        <!-- 🔹 JUDUL + MAP -->
        <h5 style="border-left: 4px solid #0d6efd; padding-left: 8px;">Peta Tagging</h5>
        <div id="map"></div>

    </div>


</div>


<!-- INFO popup peta -->
<div id="info-province"></div>
<div id="info-kabupaten" class="info-box"></div>



  <!-- kodingan untuk peta -->
<script>
    // ===============================================
    // 1. GLOBAL STATE DAN KONFIGURASI
    // ===============================================
    let allSppgData = [];
    let allUphData = [];
    let allpuskeswanData = [];
    let allklinikhewanData = [];
    let allkoperasiPKHData =[];
    let alllabbibitData = [];
    let alllabkesmavetData = [];
    let alllabpakanData = [];
    let allpasarternakData = [];
    let allrphData = [];
    let alllabkeswanData = [];
    let alluptData = [];
    let kabKotaLayer = L.layerGroup();
    let activeProvinceLayer = null;     
    let geoJsonLayer;   


    // ====== FUNGSI NORMALISASI NAMA KABUPATEN/KOTA ======

// 🔧 NORMALISASI KODE PROVINSI UNTUK PROVINSI & KABUPATEN
function normalizeKodeProv(kode) {
    if (!kode) return "";
    return String(kode)
        .replace(/\D/g, "")      // hilangkan selain angka
        .replace(/^0+/, "")      // hilangkan nol di depan
        .trim();
}

// ====== FUNGSI NORMALISASI NAMA KABUPATEN/KOTA ======
function normalizeName(name) {
    if (!name) return '';
    return name
        .toLowerCase()
        .replace(/provinsi|kab\.?|kabupaten|kota|daerah istimewa|di/g, '') // hilangkan kata tertentu
        .replace(/\(.*?\)/g, '')           // hapus teks dalam kurung
        .replace(/[^a-z\s]/g, '')          // hapus karakter aneh
        .replace(/\s+/g, ' ')              // rapikan spasi
        .trim();
}

// ====== FUNGSI NORMALISASI DATA ======
function normalizeAllData() {
    allSppgData.forEach(d => d.kab_kota = normalizeName(d.kab_kota));
    allUphData.forEach(d => d.kab_kota = normalizeName(d.kab_kota));
    allpuskeswanData.forEach(d => d.kab_kota = normalizeName(d.kab_kota));
    allklinikhewanData.forEach(d => d.kab_kota = normalizeName(d.kab_kota));
    allkoperasiPKHData.forEach(d => d.kab_kota = normalizeName(d.kab_kota));
    alllabbibitData.forEach(d => d.kab_kota = normalizeName(d.kab_kota));
    alllabkesmavetData.forEach(d => d.kab_kota = normalizeName(d.kab_kota));
    alllabpakanData.forEach(d => d.kab_kota =  normalizeName(d.kab_kota));
    allpasarternakData.forEach(d => d.kab_kota = normalizeName(d.kab_kota));
    allrphData.forEach(d => d.kab_kota = normalizeName(d.kab_kota));
    alllabkeswanData.forEach(d => d.kab_kota = normalizeName(d.kab_kota));
    alluptData.forEach(d => d.kab_kota = normalizeName(d.kab_kota));
}

function tampilanInfoPanel(judul,dataObj){
     const panel = document.getElementById('profil-lokasi');
    panel.innerHTML = `
        <h6>${judul}</h6>
        <ul>
            ${Object.entries(dataObj)
                .map(([key, val]) => `<li><strong>${key}:</strong> ${val}</li>`)
                .join('')}
        </ul>
    `;   
}
// Panggil setelah semua loadAndStoreData selesai
setTimeout(normalizeAllData, 500);

// --- KONFIGURASI PETA DAN LAYER ---
// =======================
// Inisialisasi Peta
// =======================
var map = L.map('map').setView([-2.5, 118], 5); // posisi Indonesia

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
}).addTo(map);

// Layer marker
var markersLayer = L.layerGroup().addTo(map);


// DEKLARASI LAYER GROUP 
    let layerSppg = L.layerGroup().addTo(map);
    let layerUph = L.layerGroup().addTo(map);
    let layerpuskeswan = L.layerGroup().addTo(map);
    let layerklinikhewan =  L.layerGroup().addTo(map);
    let layerkoperasiPKH = L.layerGroup().addTo(map);
    let layerlabbibit = L.layerGroup().addTo(map);
    let layerlabkesmavet = L.layerGroup().addTo(map);
    let layerlabpakan = L.layerGroup().addTo(map);
    let layerpasarternak = L.layerGroup().addTo(map);
    let layerrph = L.layerGroup().addTo(map);
    let layerlabkeswan = L.layerGroup().addTo(map);
    let layerupt = L.layerGroup().addTo(map);
 
    // DEFINISIKAN markerLayers SETELAH layerGroup ada
    let markerLayers = {
    SPPG: layerSppg,
    UPH: layerUph,
    puskeswan: layerpuskeswan,
    KLINIK: layerklinikhewan,
    koperasiPKH: layerkoperasiPKH,
    labbibit: layerlabbibit,
    labkesmavet: layerlabkesmavet,
    labpakan: layerlabpakan,
    pasarternak: layerpasarternak,
    rph: layerrph,
    labkeswan: layerlabkeswan,
    upt: layerupt,
};

    //
    function normalisasiNamaKab(nama) {
      return nama.toLowerCase().replace(/kab(\.|upaten)?|kota/g, "").replace(/\./g, "").trim();
    }

//---------------
        function filterAndDisplayMarkers(namaKabupaten) {
    const namaKabNormalized = normalisasiNamaKab(namaKabupaten);
    Object.values(markerLayers).forEach(layer => layer.clearLayers());

    // ⬇️ Filter data markers yang ada di kabupaten ini
    const filteredData = dataMarkers.filter(item =>
        normalisasiNamaKab(item.kab_kota) === namaKabNormalized
    );

    // 🧭 Tampilkan marker ke layer sesuai `jenis`
    filteredData.forEach(item => {
        L.marker([item.latitude, item.longitude])
        .bindPopup(item.nama_koperasi_UPH || item.nama || "Tanpa nama")
        .addTo(markerLayers[item.jenis]);
    });
    if (filteredData.some(item => item.jenis && item.jenis.toLowerCase().includes("koper"))) {
    console.log("🔍 Data koperasi ditemukan:", filteredData.filter(item => item.jenis.toLowerCase().includes("koper")).slice(0, 3));
    }

    // 🧾 Debugging info:
    console.log(`🟩 Klik kabupaten: ${namaKabupaten}`);
    console.log(`   ➤ Total data ditemukan: ${filteredData.length}`);
    console.log(`   ➤ Contoh data:`, filteredData.slice(0, 3)); // tampilkan 3 data awal
    }

function onEachFeature(feature, layer) {
  layer.on('click', function () {
    let namaKabupaten = feature.properties.NAME_2;
    console.log("🟢 Klik kabupaten:", namaKabupaten);
    // 🔍 Gunakan filtered dari dataMarkers
    const filtered = dataMarkers.filter(item => 
      normalizeName(item.kab_kota) === normalizeName(namaKabupaten)
    );

    // Buat count berdasarkan jenis
    const countByJenis = filtered.reduce((acc, item) => {
      acc[item.jenis] = (acc[item.jenis] || 0) + 1;
      return acc;
    }, {});

    // Debugging
    console.log("📊 Rekap data per jenis:", countByJenis);
    console.log("🔍 Contoh dari filter:", filtered.slice(0, 3));
  });
}
    // Pane provinsi
    map.createPane('provincePane');
    map.getPane('provincePane').style.zIndex = 300;

    map.createPane('kabupatenPane');
    map.getPane('kabupatenPane').style.zIndex = 400;
    map.getPane('kabupatenPane').style.pointerEvents = "auto";

    // buat markerPane
    if (!map.getPane('markerPane')) map.createPane('markerPane');
    map.getPane('markerPane').style.zIndex = 800; // cukup di atas vector panes, tapi di bawah popup

    // popupPane punya zIndex PALING TINGGI
    if (map.getPane('popupPane')) {
    map.getPane('popupPane').style.zIndex = 10000;
    } else {
    // biasanya popupPane sudah ada
    map.createPane('popupPane');
    map.getPane('popupPane').style.zIndex = 10000;
    }


// Layer kabupaten HARUS di dalam pane kabupaten
kabKotaLayer = L.geoJson(null, {
    pane: "kabupatenPane",
    style: {
        color: "#ffffff",
        weight: 1,
        fillColor: "#3388ff",
        fillOpacity: 0.35
    },
    pointToLayer: function(feature, latlng) {
        return L.marker(latlng, { pane: "kabupatenPane" });
    }
});
kabKotaLayer.addTo(map);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

//---------------
const markerCountsByKabupaten = {}; // contoh: { magelang: { SPPG: 12, UPH: 3, puskeswan: 0, klinikhewan: 1 } }

//overlaymaps bisa dinyalakan/dimatikan layar control
    const overlayMaps = {
        "Data SPPG (Titik Hijau)": layerSppg,
        "Data UPH (Titik Biru)": layerUph,
        "Data puskeswan (Titik Merah)": layerpuskeswan,
        "Data Klinik hewan (Titik Kuning)": layerklinikhewan,
        "Data koperasiPKH (Titik Orange)": layerkoperasiPKH,
        "Data labbibit (Titik Ungu)": layerlabbibit,
        "Data labkesmavet (Titik Coklat)": layerlabkesmavet,
        "Data labpakan (Titik Coklat Muda)": layerlabpakan,
        "Data pasarternak (Titik Abu-abu)": layerpasarternak,
        "Data rph (Titik Hitam)": layerrph,
        "Data labkeswan (Titik Abu-abu Gelap)": layerlabkeswan,
        "Data upt (Titik goldIcon)": layerupt,
    };
    L.control.layers(null, overlayMaps).addTo(map); 

// --- ICON KUSTOM ---
//taging SPPG
    const greenIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
    });
//taging UPH
    const blueIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
    });
//taging puskeswan
    const redIcon = L.icon({
    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
    iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
    });
//taging Klinik hewan
    const yellowIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-yellow.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
    });
//taging koperasiPKH
    const orangeIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-orange.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
    });
//taging labbibit
    const purpleIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-violet.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
  });
  //taging labkesmavet
        const brownIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-brown.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
        });
//taging labpakan
        const lightBrownIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-grey.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
        });
//taging pasarternak
        const greyIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-grey.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
        });
//taging rph
        const blackIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-black.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
        });
//taging labkeswan
    const darkGreyIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-darkgrey.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
    });
    //taging upt
    const goldIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-gold.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
    });



    // ===============================================
    // 2. FUNGSI UTAMA PENGOLAHAN DATA MARKER & KAB/KOTA
    // ===============================================
    
 //  // Fungsi 2.1: klo mau ganti isi data di geotaging 
function addMarkersToLayer(data, targetLayer, namaJenis, customIcon = null) { 
    data.forEach(item => {
        let lat = parseFloat(item.latitude);
        let lng = parseFloat(item.longitude);
// Tambah ke sistem hitungan kabupaten
let kab = normalizeName(item.kab_kota);
if (!markerCountsByKabupaten[kab]) markerCountsByKabupaten[kab] = { SPPG: 0, UPH: 0, puskeswan: 0, klinikhewan: 0, koperasiPKH: 0, labbibit: 0, labkesmavet: 0, labpakan: 0, pasarternak: 0, rph: 0, labkeswan: 0, upt: 0  };
markerCountsByKabupaten[kab][namaJenis]++;

        if (isNaN(lat) || isNaN(lng)) {
            console.warn(`⚠️ Koordinat invalid untuk ${namaJenis}:`, item);
            return;
        }

        let popupContent = '';
        if (namaJenis === 'SPPG') {
            popupContent = `
                <b>Jenis:</b> ${namaJenis}<hr>
                <b>Nama:</b> ${item.nama_SPPG || 'N/A'}<br>
                <b>TGL Operasional:</b> ${item.tanggal_operasional || 'N/A'}<br>
                <b>Provinsi:</b> ${item.provinsi || 'N/A'}<br>
                <b>Alamat:</b> ${item.alamat || 'N/A'}
            `;
        } else if (namaJenis === 'UPH') {
            popupContent = `
                <b>Jenis:</b> ${namaJenis}<hr>
                <b>Nama UPH:</b> ${item.nama_UPH || 'N/A'}<br>
                <b>Provinsi:</b> ${item.provinsi || '-'}<br>
                <b>Alamat:</b> ${item.alamat || '-'}
            `;
        } else if (namaJenis === 'puskeswan') {
            popupContent = `
                <b>Jenis:</b> ${namaJenis}<hr>
                <b>Nama puskeswan:</b> ${item.nama_puskeswan || 'N/A'}<br>
                <b>Wilayah Kerja:</b> ${item.wilayah_kerja || '-'}<br>
                <b>Provinsi:</b> ${item.provinsi || '-'}
            `;
        } else if (namaJenis === 'klinikhewan') {
            popupContent = `
                <b>Jenis:</b> ${namaJenis}<hr>
                <b>Nama:</b> ${item.nama_puskeswan || 'N/A'}<br>
                <b>Provinsi:</b> ${item.provinsi || 'N/A'}<br>
                <b>Kabupaten:</b> ${item.kab_kota || 'N/A'}<br>
            `;
        } else if (namaJenis === 'koperasiPKH') {
            popupContent = `
                <b>Jenis:</b> ${namaJenis}<hr>
                <b>Nama Koperasi/UPH:</b> ${item.nama_koperasi_UPH || 'N/A'}<br>
                <b>Alamat:</b> ${item.alamat || 'N/A'}<br>
                <b>Provinsi:</b> ${item.provinsi || 'N/A'}<br>
                <b>kabupaten: </b> ${item.kab_kota || 'N/A'}<br>
                <b>Jumlah Populasi Sapi:</b> ${item.jumlah_populasi_sapi || 'N/A'}
            `;
        } else if (namaJenis === 'labbibit') {
            popupContent = `
                <b>Jenis:</b> ${namaJenis}<hr>
                <b>Nama Lab/Bibit:</b> ${item.nama_laboratorium || 'N/A'}<br>
                <b>Jenis Uji:</b> ${item.jenis_uji || 'N/A'}<br>
                <b>Provinsi:</b> ${item.provinsi || 'N/A'}<br>
                <b>Kabupaten:</b> ${item.kab_kota || 'N/A'}<br>
                <b>Alamat:</b> ${item.alamat || 'N/A'}
            `;
        } else if (namaJenis === 'labkesmavet') {
            popupContent = `
                <b>Jenis:</b> ${namaJenis}<hr>
                <b>Nama Labkesmavet:</b> ${item.nama_laboratorium || 'N/A'}<br>
                <b>Jenis Uji:</b> ${item.jenis_uji || 'N/A'}<br>
                <b>Provinsi:</b> ${item.provinsi || 'N/A'}<br>
                <b>Kabupaten:</b> ${item.kab_kota || 'N/A'}<br>
            `;
        } else if (namaJenis === 'labpakan') {
            popupContent = `
                <b>Jenis:</b> ${namaJenis}<hr>
                <b>Nama Lab Pakan:</b> ${item.nama_laboratorium || 'N/A'}<br>
                <b>Jenis Uji:</b> ${item.jenis_uji || 'N/A'}<br>
                <b>Provinsi:</b> ${item.provinsi || 'N/A'}<br>
                <b>Kabupaten:</b> ${item.kab_kota || 'N/A'}<br>
                <b>Alamat:</b> ${item.alamat_laboratorium || 'N/A'}
            `;
        } else if (namaJenis === 'pasarternak') {
            popupContent = `
                <b>Jenis:</b> ${namaJenis}<hr>
                <b>Nama Pasar Ternak:</b> ${item.nama_pasar_ternak || 'N/A'}<br>
                <b>Provinsi:</b> ${item.provinsi || 'N/A'}<br>
                <b>Kabupaten:</b> ${item.kab_kota || 'N/A'}<br>
                <b>Alamat:</b> ${item.alamat || 'N/A'}
            `;
        } else if (namaJenis === 'rph') {
            popupContent = `
                <b>Jenis:</b> ${namaJenis}<hr>
                <b>Nama RPH:</b> ${item.nama_rph || 'N/A'}<br>
                <b>Provinsi:</b> ${item.provinsi || 'N/A'}<br>
                <b>Kabupaten:</b> ${item.kab_kota || 'N/A'}<br>
                <b>Alamat:</b> ${item.alamat_rph || 'N/A'}
            `;
        } else if (namaJenis === 'labkeswan') {
            popupContent = `
                <b>Jenis:</b> ${namaJenis}<hr>
                <b>Nama Labkeswan:</b> ${item.nama_laboratorium || 'N/A'}<br>
                <b>Jenis Uji:</b> ${item.jenis_uji || 'N/A'}<br>
                <b>Provinsi:</b> ${item.provinsi || 'N/A'}<br>
                <b>Kabupaten:</b> ${item.kab_kota || 'N/A'}}
            `;
        } else if (namaJenis === 'upt') {
            popupContent = `
                <b>Jenis:</b> ${namaJenis}<hr>
                <b>Nama UPT:</b> ${item.nama_unit_pelaksana_teknis || 'N/A'}<br>
                <b>Provinsi:</b> ${item.provinsi || 'N/A'}<br>
                <b>Kabupaten:</b> ${item.kab_kota || 'N/A'}<br>
                <b>Alamat:</b> ${item.alamat || 'N/A'}
            `;
        }

        else {
            popupContent = `<b>Jenis:</b> ${namaJenis}<hr><b>Data tidak tersedia.</b>`;
        }

                
// ⬇️ Tambahkan marker ke layer (ini yang hilang sebelumnya!)
        targetLayer.addLayer(
            L.marker([lat, lng], {
                icon: customIcon,
                pane: 'markerPane',
                zIndexOffset: 1000,
                dataKabupaten: item.kab_kota.toUpperCase()
            }).bindPopup(popupContent, {
                offset: [0, -20]  // ⬆️ geser popup 20px ke atas
})

        );
    });

    // Debug: cek apakah marker terisi
    console.log(`🟢 Total marker di layer ${namaJenis}:`, targetLayer.getLayers().length);
}


    // Fungsi 2.2: Mengambil dan menyimpan semua data marker (saat start)
function loadAndStoreData(urlApi, dataStorage, namaJenis) {
    // return promise supaya Promise.all bekerja benar
    return fetch(`http://localhost:8080${urlApi}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            dataStorage.length = 0; // kosongkan array dulu
            dataStorage.push(...data);
            console.log(`Berhasil memuat ${data.length} lokasi ${namaJenis} (Data Tersimpan).`);
            return data; // kembalikan data untuk chaining jika perlu
        })
        .catch(error => {
            console.error(`[ERROR API ${namaJenis}]: Gagal memuat data.`, error);
            // tetap return empty array agar Promise.all tidak reject (opsional)
            return [];
        });
}

    
    // Fungsi 2.3: Filter Data dan Tampilkan di Peta 
    
function filterAndDisplayMarkers(regionName, level = 'provinsi') {
    // 1️⃣ Hapus marker lama dari semua layer
    layerSppg.clearLayers();
    layerUph.clearLayers();
    layerpuskeswan.clearLayers();
    layerklinikhewan.clearLayers();
    layerkoperasiPKH.clearLayers();
    layerlabbibit.clearLayers();
    layerlabkesmavet.clearLayers();
    layerlabpakan.clearLayers();
    layerpasarternak.clearLayers();
    layerrph.clearLayers();
    layerlabkeswan.clearLayers();
    layerupt.clearLayers();

    // 2️⃣ Filter data sesuai region
    const filterProperty = (level === 'provinsi') ? 'provinsi' : 'kab_kota';
    
    const filteredSppg = allSppgData.filter(lokasi =>
    lokasi[filterProperty] && lokasi[filterProperty].toLowerCase().includes(regionName.toLowerCase())
    );
    const filteredUph = allUphData.filter(lokasi =>
    lokasi[filterProperty] && lokasi[filterProperty].toLowerCase().includes(regionName.toLowerCase())
    );
    const filteredpuskeswan = allpuskeswanData.filter(lokasi =>
    lokasi[filterProperty] && lokasi[filterProperty].toLowerCase().includes(regionName.toLowerCase())
    );
    const filteredklinikhewan = allklinikhewanData.filter(lokasi =>
    lokasi[filterProperty] && lokasi[filterProperty].toLowerCase().includes(regionName.toLowerCase())
    );
    const filteredkoperasiPKH = allkoperasiPKHData.filter(lokasi =>
    lokasi[filterProperty] && lokasi[filterProperty].toLowerCase().includes(regionName.toLowerCase())
    );
    const filteredlabbibit = alllabbibitData.filter(lokasi =>
    lokasi[filterProperty] && lokasi[filterProperty].toLowerCase().includes(regionName.toLowerCase())
    );
    const filteredlabkesmavet = alllabkesmavetData.filter(lokasi =>
    lokasi[filterProperty] && lokasi[filterProperty].toLowerCase().includes(regionName.toLowerCase())
    );
    const filteredlabpakan = alllabpakanData.filter(lokasi =>
    lokasi[filterProperty] && lokasi[filterProperty].toLowerCase().includes(regionName.toLowerCase())
    );
    const filteredpasarternak = allpasarternakData.filter(lokasi =>
    lokasi[filterProperty] && lokasi[filterProperty].toLowerCase().includes(regionName.toLowerCase())
    );
    const filteredrph = allrphData.filter(lokasi =>
    lokasi[filterProperty] && lokasi[filterProperty].toLowerCase().includes(regionName.toLowerCase())
    );
    const filteredlabkeswan = alllabkeswanData.filter(lokasi =>
    lokasi[filterProperty] && lokasi[filterProperty].toLowerCase().includes(regionName.toLowerCase())
    );
    const filteredupt = alluptData.filter(lokasi =>
    lokasi[filterProperty] && lokasi[filterProperty].toLowerCase().includes(regionName.toLowerCase())
    );

// 3 Tambahkan marker ke layer cluster
    addMarkersToLayer(filteredSppg, layerSppg, 'SPPG', greenIcon);
    addMarkersToLayer(filteredUph, layerUph, 'UPH', blueIcon);
    addMarkersToLayer(filteredpuskeswan, layerpuskeswan, 'puskeswan', redIcon);
    addMarkersToLayer(filteredklinikhewan, layerklinikhewan, 'klinikhewan', yellowIcon);
    addMarkersToLayer(filteredkoperasiPKH, layerkoperasiPKH, 'koperasiPKH', orangeIcon);
    addMarkersToLayer(filteredlabbibit, layerlabbibit, 'labbibit', purpleIcon);
    addMarkersToLayer(filteredlabkesmavet, layerlabkesmavet, 'labkesmavet', brownIcon);
    addMarkersToLayer(filteredlabpakan, layerlabpakan, 'labpakan', lightBrownIcon);
    addMarkersToLayer(filteredpasarternak, layerpasarternak, 'pasarternak', greyIcon);
    addMarkersToLayer(filteredrph, layerrph, 'rph', blackIcon);
    addMarkersToLayer(filteredlabkeswan, layerlabkeswan, 'labkeswan', darkGreyIcon);
    addMarkersToLayer(filteredupt, layerupt, 'upt', darkGreyIcon);

// 4 Tambahkan layer ke peta (jika belum ada)
    map.addLayer(layerSppg);
    map.addLayer(layerUph);
    map.addLayer(layerpuskeswan);
    map.addLayer(layerklinikhewan);
    map.addLayer(layerkoperasiPKH);
    map.addLayer(layerkoperasiPKH);
    map.addLayer(layerlabbibit);
    map.addLayer(layerlabkesmavet);
    map.addLayer(layerlabpakan);
    map.addLayer(layerpasarternak);
    map.addLayer(layerrph);
    map.addLayer(layerlabkeswan);
    map.addLayer(layerupt);
    
// 5 Log ke console
    console.log(`Menampilkan di ${regionName} (level: ${level}):
        ${filteredSppg.length} SPPG,
        ${filteredUph.length} UPH,
        ${filteredpuskeswan.length} puskeswan,
        ${filteredklinikhewan.length} klinikhewan,
        ${filteredkoperasiPKH.length} koperasiPKH,
        ${filteredlabbibit.length} labbibit,
        ${filteredlabkesmavet.length} labkesmavet,
        ${filteredlabpakan.length} labpakan,
        ${filteredpasarternak.length} pasarternak,
        ${filteredrph.length} rph,
        ${filteredlabkeswan.length} labkeswan,
        ${filteredupt.length} upt,
    `);
    
}
    

// FUNGSI 2.4: loadKabKota menampilkan batas kab/kota
function loadKabKota(provinceCode) {
    console.log("loadKabKota:", provinceCode);

    // Pastikan layer group ada
    if (!kabKotaLayer) {
        console.warn("kabKotaLayer belum ada, membuat baru...");
        kabKotaLayer = L.layerGroup().addTo(map);   // ❗ perbaikan: TIDAK pakai let
    } else {
        kabKotaLayer.clearLayers();
    }

    const kabKotaUrl = `http://localhost:8080/titik_koordinat/kabupaten_coba_master.json`;

    fetch(kabKotaUrl)
        .then(r => {
            if (!r.ok) throw new Error("Gagal memuat kabupaten JSON: " + r.status);
            return r.json();
        })
        .then(data => {

            // Normalisasi sekali di luar filter
            const kodeProv = normalizeKodeProv(provinceCode);

            // Filter kabupaten sesuai provinsi
            const filteredFeatures = data.features.filter(f => {
                const kodeKab = normalizeKodeProv(f.properties.KODE_PROV);
                return kodeKab === kodeProv;
            });

            console.log("Jumlah fitur kabupaten setelah filter:", filteredFeatures.length);

            if (filteredFeatures.length === 0) {
                console.warn(`Tidak ada data kab untuk prov kode: ${provinceCode} (normalized: ${kodeProv})`);
                return;
            }

            // Buat layer GeoJSON
            const kabLayer = L.geoJSON(
                {
                    type: "FeatureCollection",
                    features: filteredFeatures
                },
                {
                    pane: "kabupatenPane",
                    style: {
                        color: "#ffffff",
                        weight: 1.5,
                        fillColor: "#292c31ff",
                        fillOpacity: 0.35
                    },
//milik popup kabupaten              
    onEachFeature: function(feature, layer) {
        // Tambahkan popup dengan hitungan marker
            const kabNama = normalizeName(feature.properties.KAB_KOTA);
        const counts = markerCountsByKabupaten[kabNama] || { SPPG: 0, UPH: 0, PUSKESWAN: 0, klinikhewan: 0 };
        
// Anda bisa mengatur opsi maxWidth langsung di sini jika belum ada:
layer.bindPopup(`
    <div style="text-align: center; background-color: #ff6600; color: white; padding: 8px 10px; border-radius: 5px; margin-bottom: 8px;">
        <h4 style="margin: 0; font-size: 1.3em; font-weight: 700;">${feature.properties.KAB_KOTA}</h4>
    </div>

    <div style="max-height: 150px; overflow-y: scroll; padding-right: 10px;">
        
        <table style="width: 100%; border-collapse: collapse; font-size: 0.95em;">
            <tr>
                <td style="padding: 3px 0; width: 70%;">Jumlah SPPG:</td>
                <td style="padding: 3px 0; font-weight: bold; text-align: right;">${counts.SPPG}</td>
            </tr>
            <tr>
                <td style="padding: 3px 0;">Jumlah UPH:</td>
                <td style="padding: 3px 0; font-weight: bold; text-align: right;">${counts.UPH}</td>
            </tr>
            
            <tr>
                <td style="padding: 3px 0;">Puskeswan:</td>
                <td style="padding: 3px 0; text-align: right;">${counts.puskeswan}</td>
            </tr>
            <tr>
                <td style="padding: 3px 0;">Klinik Hewan:</td>
                <td style="padding: 3px 0; text-align: right;">${counts.klinikhewan}</td>
            </tr>
            <tr>
                <td style="padding: 3px 0;">Koperasi PKH:</td>
                <td style="padding: 3px 0; text-align: right;">${counts.koperasiPKH}</td>
            </tr>
            <tr>
                <td style="padding: 3px 0;">Lab Bibit:</td>
                <td style="padding: 3px 0; text-align: right;">${counts.labbibit}</td>
            </tr>
            <tr>
                <td style="padding: 3px 0;">Lab Kesmavet:</td>
                <td style="padding: 3px 0; text-align: right;">${counts.labkesmavet}</td>
            </tr>
            <tr>
                <td style="padding: 3px 0;">Lab Pakan:</td>
                <td style="padding: 3px 0; text-align: right;">${counts.labpakan}</td>
            </tr>
            <tr>
                <td style="padding: 3px 0;">Pasar Ternak:</td>
                <td style="padding: 3px 0; text-align: right;">${counts.pasarternak}</td>
            </tr>
            <tr>
                <td style="padding: 3px 0;">RPH:</td>
                <td style="padding: 3px 0; text-align: right;">${counts.rph}</td>
            </tr>
            <tr>
                <td style="padding: 3px 0;">Lab Keswan:</td>
                <td style="padding: 3px 0; text-align: right;">${counts.labkeswan}</td>
            </tr>
            <tr>
                <td style="padding: 3px 0;">UPT:</td>
                <td style="padding: 3px 0; text-align: right;">${counts.upt}</td>
            </tr>
        </table>
    </div>
    `, { 
    // Mengatur lebar popup agar lebih tipis
    maxWidth: 220, 
    offset: [20, -30] 
});


    layer.on("click", function(e) {
    kabLayer.eachLayer(l =>
    kabLayer.resetStyle && kabLayer.resetStyle(l)
    );
    //layar timbul di kab.
    e.target.setStyle({
        color: "#ffffff",
        weight: 2.5,
        fillColor: "#899c58ff",
        fillOpacity: 0.65
        });
    //zoom ke kab.
        map.fitBounds(e.target.getBounds());
    //filter titik di kabupaten yg diklik (yg konek KAB_KOTA)
        const namaKab = feature.properties.KAB_KOTA || feature.properties.kab_kota.trim().toLowerCase();
        console.log("📍 Klik kabupaten:", namaKab);

    // Tambahkan marker yang cocok
        filterMarkersByKabupaten(namaKab);
        });
            }
                }
                    );

    // tambahan fungsi
function filterMarkersByKabupaten(namaKabupaten) {
    console.log(`🎯 Filter marker untuk kabupaten: ${namaKabupaten}`);

    Object.keys(markerLayers).forEach(layerName => {
        markerLayers[layerName].eachLayer(marker => {
            const markerKab = marker.options.dataKabupaten?.trim().toLowerCase();

            if (markerKab === namaKabupaten.toLowerCase()) {
                marker.addTo(map);
            } else {
                map.removeLayer(marker);
            }
        });
    });
}

function showAllMarkers() {
    Object.keys(markerLayers).forEach(layerName => {
        markerLayers[layerName].addTo(map);
    });
}


            // Tambahkan layer ke grup
            kabLayer.addTo(kabKotaLayer);
            kabKotaLayer.addTo(map);
            console.log("Layer kabupaten ditambahkan ke peta.");
            console.log("kabLayer layer count:", kabLayer.getLayers().length);
            console.log("kabKotaLayer count:", kabKotaLayer.getLayers().length);
            console.log("Raw kabupaten data:", data);

    try {
        JSON.stringify(data);
        console.log("GeoJSON format: VALID JSON");
    } catch (e) {
        console.error("GeoJSON format: INVALID JSON!", e);
    }


            // Fit bounds kabupaten
            try {
                map.fitBounds(kabLayer.getBounds(), { padding: [20, 20] });
            } catch (e) {
                console.warn("fitBounds gagal:", e);
            }
        })
        .catch(err => console.error("Kesalahan saat memuat kabupaten:", err));
    }

    // =========================
// Highlight & zoom ke satu kabupaten
// =========================
let highlightLayer = null;

function highlightKabupatenByName(namaKabupaten) {
    if (!namaKabupaten || !kabKotaLayer) return;
    // normalize untuk perbandingan
    const target = normalizeName(namaKabupaten);

    // hapus highlight sebelumnya
    if (highlightLayer) {
        map.removeLayer(highlightLayer);
        highlightLayer = null;
    }

    // buat geojson filter dari kabKotaLayer (yang sudah di-load)
    // kalau kabKotaLayer kosong karena kamu membuat kabLayer baru setiap loadKabKota,
    // maka gunakan kabLayer variable yang kamu buat di loadKabKota dengan scope yang lebih luas.
    const features = kabKotaLayer.toGeoJSON ? kabKotaLayer.toGeoJSON().features : [];

    const matched = features.filter(f => normalizeName(f.properties.KAB_KOTA || f.properties.NAME_2) === target);

    if (matched.length === 0) {
        console.warn("Tidak ditemukan fitur kabupaten untuk:", namaKabupaten);
        return;
    }

    highlightLayer = L.geoJSON({
        type: "FeatureCollection",
        features: matched
    }, {
        pane: "kabupatenPane",
        style: {
            color: "#ffffff",
            weight: 3,
            fillColor: "#ff6b6b",
            fillOpacity: 0.6
        },
        onEachFeature: function(feature, layer) {
            layer.on('click', function() {
                // optional: klik polygon = tampilkan popup atau update chart
                filterMarkersByKabupaten(feature.properties.KAB_KOTA || feature.properties.NAME_2);
                updateCharts(feature.properties.KAB_KOTA || feature.properties.NAME_2);
            });
        }
    }).addTo(map);

    // Zoom to highlight
    try {
        map.fitBounds(highlightLayer.getBounds(), { padding: [20,20] });
    } catch (e) {
        console.warn("fitBounds highlight gagal:", e);
    }

    // Sync markers + charts
    filterMarkersByKabupaten(namaKabupaten);
    if (typeof updateCharts === "function") updateCharts(namaKabupaten);
}

//pas klik kabupaten tittik hanya di kab yg dipilih
function filterMarkersByKabupaten(namaKabupaten) {

    // bersihkan semua layer dulu
    Object.values(markerLayers).forEach(layer => layer.clearLayers());

    const kabFilter = normalizeName(namaKabupaten);

    for (let jenis in dataAll) {
        const list = dataAll[jenis];

        list.forEach(item => {
            if (!item.kab_kota) return;

            const kabItem = normalizeName(item.kab_kota);

            if (kabItem === kabFilter) {
                const customIcon = customIcons[jenis] || null;
                addMarkersToLayer([item], markerLayers[jenis], jenis, customIcon);
            }
        });
    }

    console.log(" Marker difilter berdasarkan kabupaten:", kabFilter);
}


//pake f12 di console tampilan di sini panggil semua API awal
// 1️⃣ Setelah load semua data API, langsung tampilkan semua marker
Promise.all([
    loadAndStoreData('/api/sppg', allSppgData, 'SPPG'),
    loadAndStoreData('/api/uph', allUphData, 'UPH'),
    loadAndStoreData('/api/puskeswan', allpuskeswanData, 'puskeswan'),
    loadAndStoreData('/api/klinikhewan', allklinikhewanData, 'klinikhewan'),
    loadAndStoreData('/api/koperasiPKH', allkoperasiPKHData, 'koper asiPKH'),
    loadAndStoreData('/api/labbibit', alllabbibitData, 'labbibit'),
    loadAndStoreData('/api/labkesmavet', alllabkesmavetData, 'labkesmavet'),
    loadAndStoreData('/api/labpakan', alllabpakanData, 'labpakan'),
    loadAndStoreData('/api/pasarternak', allpasarternakData, 'pasarternak'),
    loadAndStoreData('/api/rph', allrphData, 'rph'),
    loadAndStoreData('/api/labkeswan', alllabkeswanData, 'labkeswan'),
    loadAndStoreData('/api/upt', alluptData, 'upt'),
]).then(() => {
    // Semua data sudah ter-load (karena loadAndStoreData sekarang mengembalikan promise)
    normalizeAllData(); // normalisasi aman di sini

    console.log("Semua data dimuat dan dinormalisasi.");



    // Pastikan layer-layer marker aktif
    map.addLayer(layerSppg);
    map.addLayer(layerUph);
    map.addLayer(layerpuskeswan);
    map.addLayer(layerklinikhewan);
    map.addLayer(layerkoperasiPKH);
    map.addLayer(layerlabbibit);
    map.addLayer(layerlabkesmavet);
    map.addLayer(layerlabpakan);
    map.addLayer(layerpasarternak);
    map.addLayer(layerrph);
    map.addLayer(layerlabkeswan);
    map.addLayer(layerupt);
});


// ===============================================
// 3. LOGIKA GEOJSON PROVINSI DAN INTERAKSI KLIK
// ===============================================
// Fungsi 3.1: Style Poligon Provinsi (Warna Seragam)
    function styleProvinsi(feature) {
        return {
            fillColor: '#051c38ff', // Warna Biru Seragam
            weight: 2,
            opacity: 5,
            color: 'white', 
            dashArray: '3',
            fillOpacity: 0.5
        };
    }

// Fungsi 3.2: Interaksi Klik dan Hover
function onEachFeature(feature, layer) {
    // 1️⃣ Ambil nama & kode provinsi
    const namaProvinsi = feature.properties.PROVINSI || feature.properties.WADMPR || 'Daerah Tidak Diketahui';
    let kodeProvinsi = normalizeKodeProv(feature.properties.KODE_PROV);
    kodeProvinsi = String(kodeProvinsi).toUpperCase().trim();

    // 2️⃣ Event interaksi pada layer
    layer.on({
        mouseover: function(e) {
            e.target.setStyle({
                weight: 5,
                color: '#ffdd00',
                dashArray: '',
                fillOpacity: 0.8
            });
            e.target.bringToFront();
        },
        mouseout: function(e) {
            if (e.target !== activeProvinceLayer) { 
                geoJsonLayer.resetStyle(e.target); 
            }
        },
click: function(e) {
    // ... kode yang sudah ada (reset style, set activeProvinceLayer, style, dll)

    // 4️⃣ Filter data berdasarkan provinsi (ini untuk popup dan log)
    const namaProvinsi = feature.properties.PROVINSI || 'Tidak diketahui';
    // (opsional: const namaProvinsiNorm = normalizeName(namaProvinsi);)

    const filteredSppg = allSppgData.filter(lokasi => (lokasi.provinsi || '').toLowerCase().trim() === namaProvinsi.toLowerCase().trim());
    const filteredUph = allUphData.filter(lokasi => (lokasi.provinsi || '').toLowerCase().trim() === namaProvinsi.toLowerCase().trim());
    const filteredpuskeswan = allpuskeswanData.filter(lokasi => (lokasi.provinsi || '').toLowerCase().trim() === namaProvinsi.toLowerCase().trim());
    const filteredklinikhewan = allklinikhewanData.filter(lokasi => (lokasi.provinsi || '').toLowerCase().trim() === namaProvinsi.toLowerCase().trim());
    const filteredkoperasiPKH = allkoperasiPKHData.filter(lokasi => (lokasi.provinsi || '').toLowerCase().trim() === namaProvinsi.toLowerCase().trim());
    const filteredlabbibit = alllabbibitData.filter(lokasi => (lokasi.provinsi || '').toLowerCase().trim() === namaProvinsi.toLowerCase().trim());
    const filteredlabkesmavet = alllabkesmavetData.filter(lokasi => (lokasi.provinsi || '').toLowerCase().trim() === namaProvinsi.toLowerCase().trim());
    const filteredlabpakan = alllabpakanData.filter(lokasi => (lokasi.provinsi || '').toLowerCase().trim() === namaProvinsi.toLowerCase().trim());
    const filteredpasarternak = allpasarternakData.filter(lokasi => (lokasi.provinsi || '').toLowerCase().trim() === namaProvinsi.toLowerCase().trim());
    const filteredrph = allrphData.filter(lokasi => (lokasi.provinsi || '').toLowerCase().trim() === namaProvinsi.toLowerCase().trim());
    const filteredlabkeswan = alllabkeswanData.filter(lokasi => (lokasi.provinsi || '').toLowerCase().trim() === namaProvinsi.toLowerCase().trim());
    const filteredupt = alluptData.filter(lokasi => (lokasi.provinsi || '').toLowerCase().trim() === namaProvinsi.toLowerCase().trim());

    // 6️⃣ Tampilkan layer kabupaten sesuai provinsi
    loadKabKota(kodeProvinsi);

    // 7️⃣ Zoom halus ke batas provinsi
    map.flyToBounds(e.target.getBounds(), {
        duration: 1.2,
        padding: [30, 30]
    });

    // === *PENTING* : tampilkan marker sesuai provinsi (panggil fungsi ini)
    filterAndDisplayMarkers(namaProvinsi, 'provinsi');

    // popup provinsi ketika klik profinsi
    const popupContent = `
<div style="text-align: center; background-color: #6a8b4aff; color: white; padding: 8px 10px; border-radius: 5px; margin-bottom: 8px;">
        <h4 style="margin: 0; font-size: 1.3em; font-weight: 700;">Provinsi: ${namaProvinsi}</h4>
    </div>

    <div style="max-height: 150px; overflow-y: scroll; padding-right: 10px;"> 
        
        <table style="width: 100%; border-collapse: collapse; font-size: 0.95em;">
            <tr>
                <td style="padding: 3px 0; width: 70%;">Jumlah SPPG:</td>
                <td style="padding: 3px 0; font-weight: bold; text-align: right;">${filteredSppg.length}</td>
            </tr>
            <tr>
                <td style="padding: 3px 0;">Jumlah UPH:</td>
                <td style="padding: 3px 0; font-weight: bold; text-align: right;">${filteredUph.length}</td>
            </tr>
            <tr>
                <td style="padding: 3px 0;">Jumlah Puskeswan:</td>
                <td style="padding: 3px 0; text-align: right;">${filteredpuskeswan.length}</td>
            </tr>
            <tr>
                <td style="padding: 3px 0;">Jumlah Klinik hewan:</td>
                <td style="padding: 3px 0; text-align: right;">${filteredklinikhewan.length}</td>
            </tr>
            <tr>
                <td style="padding: 3px 0;">Jumlah Koperasi PKH:</td>
                <td style="padding: 3px 0; text-align: right;">${filteredkoperasiPKH.length}</td>
            </tr>
            <tr>
                <td style="padding: 3px 0;">Jumlah Lab Bibit:</td>
                <td style="padding: 3px 0; text-align: right;">${filteredlabbibit.length}</td>
            </tr>
            <tr>
                <td style="padding: 3px 0;">Jumlah Lab Kesmavet:</td>
                <td style="padding: 3px 0; text-align: right;">${filteredlabkesmavet.length}</td>
            </tr>
            <tr>
                <td style="padding: 3px 0;">Jumlah Lab Pakan:</td>
                <td style="padding: 3px 0; text-align: right;">${filteredlabpakan.length}</td>
            </tr>
            <tr>
                <td style="padding: 3px 0;">Jumlah Lab Keswan:</td>
                <td style="padding: 3px 0; text-align: right;">${filteredlabkeswan.length}</td>
            </tr>
            <tr>
                <td style="padding: 3px 0;">Jumlah Pasar Ternak:</td>
                <td style="padding: 3px 0; text-align: right;">${filteredpasarternak.length}</td>
            </tr>
            <tr>
                <td style="padding: 3px 0;">Jumlah RPH:</td>
                <td style="padding: 3px 0; text-align: right;">${filteredrph.length}</td>
            </tr>
            <tr>
                <td style="padding: 3px 0;">Jumlah UPT:</td>
                <td style="padding: 3px 0; text-align: right;">${filteredupt.length}</td>
            </tr>
        </table>
    </div>
    `;

    // UPDATE PANEL INFORMASI PROVINSI
document.getElementById("info-province").innerHTML = `
    <b>Provinsi:</b> ${namaProvinsi}<br>
    <b>SPPG:</b> ${filteredSppg.length}<br>
    <b>UPH:</b> ${filteredUph.length}<br>
    <b>puskeswan:</b> ${filteredpuskeswan.length}<br>
    <b>Klinik Hewan:</b> ${filteredklinikhewan.length}<br>
    <b>koperasiPKH:</b> ${filteredkoperasiPKH.length}<br>
    <b>labbibit:</b> ${filteredlabbibit.length}<br>
    <b>labkesmavet:</b> ${filteredlabkesmavet.length}
    <b>labpakan:</b> ${filteredlabpakan.length}<br>
    <b>pasarternak:</b> ${filteredpasarternak.length}<br>
    <b>rph:</b> ${filteredrph.length}<br>
    <b>labkeswan:</b> ${filteredlabkeswan.length}<br>
    <b>upt:</b> ${filteredupt.length}<br>
`;

    layer.bindPopup(popupContent).openPopup();
    console.log(`🗺️ Provinsi: ${namaProvinsi}
SPPG: ${filteredSppg.length}, UPH: ${filteredUph.length},
puskeswan: ${filteredpuskeswan.length}, Klinik Hewan: ${filteredklinikhewan.length}`);
}
    });
}
   
//PROVINSI Pemuatan GeoJSON Provinsi Asinkron
    fetch('http://localhost:8080/titik_koordinat/provinsi_new_kecil.json')
        .then(response => {
            if (!response.ok) {
                throw new Error(`Gagal memuat GeoJSON Provinsi: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
        geoJsonLayer = L.geoJSON(data, {
            pane: 'provincePane',
            style: styleProvinsi,
            onEachFeature: onEachFeature
        }).addTo(map);

            
            map.fitBounds(geoJsonLayer.getBounds());
            console.log("GeoJSON Provinsi berhasil dimuat.");
        })
        .catch(error => {
            console.error("Kesalahan saat memuat GeoJSON Provinsi:", error);
        });
    
// area reset map
// ===========================
// RESET MAP SAAT KLIK AREA KOSONG
// ===========================

// Simpan posisi awal peta (ubah sesuai default kamu)
const defaultCenter = [-2.5489, 118.0149]; // Koordinat Indonesia tengah
const defaultZoom = 5;

// Fungsi reset
function resetMap() {
  map.setView(defaultCenter, defaultZoom);
  map.closePopup();
  console.log("🗺️ Peta di-reset ke tampilan awal");
// Bersihkan semua marker/layer biar kosong dulu
layerSppg.clearLayers();
layerUph.clearLayers();
layerpuskeswan.clearLayers();
layerklinikhewan.clearLayers();
layerkoperasiPKH.clearLayers();
layerlabbibit.clearLayers();
layerlabkesmavet.clearLayers();
layerlabpakan.clearLayers();
layerpasarternak.clearLayers();
layerrph.clearLayers();
layerlabkeswan.clearLayers();
layerupt.clearLayers();

// Hapus layer kabupaten
kabKotaLayer.clearLayers();
  //Hapus highlight provinsi terakhir (kalau ada)
  if (activeProvinceLayer) {
    geoJsonLayer.resetStyle(activeProvinceLayer);
    activeProvinceLayer = null}
}
// klik di peta
map.on('click', function (e) {
  // `leaflet-interactive` dipakai oleh marker, polygon, polyline, dll
  const isMarker = e.originalEvent.target.classList.contains('leaflet-interactive');
  if (!isMarker) {
    resetMap();
  }
});

</script>



</body>
</html>