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

/* ===== SIDEBAR ===== */
.sidebar {
    --sidebar-bg: #155f3bff;
    width: 240px;
    min-height: 100vh;
    background-color: var(--sidebar-bg);
    color: #fff;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1030;
    padding: 15px;
    box-shadow: 5px 0 10px rgba(0,0,0,0.1);
}
.sidebar .nav-link { color: #cdd6ff; }
.sidebar .nav-link:hover,
.sidebar .active-menu { color: #fff; }

/* ===== NAVBAR ===== */
.navbar {
    position: fixed;
    top: 0;
    left: 240px;
    right: 0;
    z-index: 1000;
}

/* ===== MAIN LAYOUT (MAP + SIDE PANEL) ===== */
.main-container {
    display: flex;
    gap: 20px;
    margin-left: 240px;    /* Hindari sidebar kiri */
    margin-top: 56px;      /* Hindari navbar */
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

/* ===== SIDE PANEL ===== */
.side-panel {
    width: 260px;
    background: #fff;
    padding: 15px;
    border-radius: 10px;
    box-shadow: -2px 0 6px rgba(0,0,0,0.1);
    height: fit-content;
}

.side-panel h5 {
    border-left: 4px solid #0d6efd;
    padding-left: 8px;
    margin-bottom: 15px;
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

<!-- SIDEBAR kiri -->
<div class="sidebar d-flex flex-column">
<img src="<?= base_url('images/logo.png') ?>" class="img-fluid" 
style="max-height:150px; max-width:150px; display:block; margin:0 auto;">
 
    <h4 class="fw-bold text-white mb-4">Latsar</h4>
<ul class="nav flex-column flex-grow-1">
    <li class="nav-item">
        <a class="nav-link active-menu" href="/">
            <i class="fas fa-tachometer-alt me-3"></i> Dashboard Utama
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/upload">
            <i class="fas fa-upload me-3"></i> Upload Data
        </a>
    </li>
        <li class="nav-item">
        <a class="nav-link" href="/profiling">
            <i class="fas fa-address-card me-3"></i> Profiling
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/carapenggunaan">
            <i class="fas fa-project-diagram me-3"></i> Cara penggunaan
        </a>
    </li>

</ul>

</div>


<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">🌍 WebGIS</a>

        <div class="ms-auto">
            <button class="btn btn-outline-light me-2"><i class="fas fa-bell"></i></button>
            <button class="btn btn-primary"><i class="fas fa-user-circle me-1"></i> Admin </button>
        </div>
    </div>
</nav>


<!-- MAIN CONTENT AREA -->
<div class="main-container">
    <div class="map-chart-wrapper">

        <!-- 🔹 JUDUL + MAP -->
        <h5 style="border-left: 4px solid #0d6efd; padding-left: 8px;">Peta Tagging</h5>
        <div id="map"></div>

    </div>

    <!-- 📊 PANEL KANAN -->
    <div class="side-panel">
        <h5>Pencarian Data Peta</h5>
        <input type="text" id="searchBox" class="form-control mb-3" placeholder="Cari lokasi...">

        <h5>Filter Lokasi Grafik Profiling</h5>

        <select id="provinsiDropdown" class="form-select mb-2">
            <option value="">-- Pilih Provinsi --</option>
            <?php foreach($provinsi as $prov): ?>
                <option value="<?= $prov['provinsi'] ?>"><?= $prov['provinsi'] ?></option>
            <?php endforeach; ?>
        </select>

        <select id="kabupatenDropdown" class="form-select mb-3">
            <option value="">-- Pilih Kabupaten --</option>
            <?php foreach($kabupaten as $kab): ?>
                <option value="<?= $kab['kab_kota'] ?>" data-prov="<?= $kab['provinsi'] ?>">
                    <?= $kab['kab_kota'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

    <!-- 🔹 CHART DIBAWAH MAP -->
    <div class="charts-wrapper">
        <div class="chart-box">
            <h5>Grafik Populasi (Ekor)</h5>
            <div id="chart-populasi-auto" class="chart-area"></div>
        </div>

        <div class="chart-box">
            <h5>Grafik Produksi (Ekor)</h5>
            <div id="chart-produksi-auto" class="chart-area"></div>
        </div>

        <div class="chart-box">
            <h5>Harga Ternak (Rp)</h5>
            <div id="chart-harga-auto" class="chart-area"></div>
        </div>
    </div>

<!-- INFO popup peta -->
<div id="info-province"></div>
<div id="info-kabupaten" class="info-box"></div>


<!-- chart grafik-->
<script>
function norm(str) {
    return str ? str.toLowerCase().trim() : "";
}

// =======================
// Data dari PHP ke JS
// =======================
let allMarkers = <?= json_encode($lokasi) ?>;
let allPopulasi = <?= json_encode($populasi) ?>;
let allProduksi = <?= json_encode($produksi) ?>;
let allHarga = <?= json_encode($harga) ?>;

// =======================
// Fungsi tambah marker ke map
// =======================
function addMarkers(filteredData) {
    markersLayer.clearLayers();
    filteredData.forEach(item => {
        let lat = parseFloat(item.latitude);
        let lng = parseFloat(item.longitude);
        if (!isNaN(lat) && !isNaN(lng)) {
            let marker = L.marker([lat, lng]).addTo(markersLayer);
            marker.bindPopup(`<b>${item.nama}</b><br>${item.alamat}<br>${item.kab_kota}, ${item.provinsi}`);
        }
    });
}

// =======================
// Fungsi update chart
// =======================
function updateCharts(kabupaten) {

    let chartPopContainer = document.getElementById('chart-populasi-auto');
    let chartProdContainer = document.getElementById('chart-produksi-auto');
    let chartHargaContainer = document.getElementById('chart-harga-auto');

    if (!kabupaten) {
        chartPopContainer.innerHTML = "<p style='text-align:center;color:#888;'>Pilih Kabupaten</p>";
        chartProdContainer.innerHTML = "<p style='text-align:center;color:#888;'>Pilih Kabupaten</p>";
        chartHargaContainer.innerHTML = "<p style='text-align:center;color:#888;'>Pilih Kabupaten</p>";
        return;
    }

    // ==========================
    // POPULASI — versi bagus
    // ==========================
    let rawPop = allPopulasi.filter(r => norm(r.kab_kota) === norm(kabupaten));

    const tahunSet = new Set();
    const jenisSet = new Set();

    rawPop.forEach(r => {
        tahunSet.add(r.tahun);
        jenisSet.add(r.jenis_ternak);
    });

    const years = [...tahunSet];
    const jenis = [...jenisSet];

    const seriesPop = jenis.map(jenisHewan => ({
        name: jenisHewan,
        type: 'line',
        smooth: true,
        data: years.map(th => {
            let row = rawPop.find(r => r.tahun == th && r.jenis_ternak == jenisHewan);
            let val = row ? row.jumlah_populasi : 0;
            return (val === "-" || val == null) ? 0 : Number(val);
        })
    }));

    let chartPop = echarts.init(chartPopContainer);
    chartPop.setOption({
        title: { text: `Populasi Hewan - ${kabupaten}`, left: "center" },
        tooltip: { trigger: 'axis' },
        legend: { type: 'scroll', bottom: 10 },
        xAxis: { type: 'category', data: years },
        yAxis: { type: 'value', name: "Populasi" },
        series: seriesPop
    });

    // ==========================
    // PRODUKSI — versi bagus
    // ==========================
    let rawProd = allProduksi.filter(r => norm(r.kab_kota) === norm(kabupaten));

    const tahunProdSet = new Set();
    const jenisProdSet = new Set();

    rawProd.forEach(r => {
        tahunProdSet.add(r.tahun);
        jenisProdSet.add(r.jenis_produksi);
    });

    const yearsProd = [...tahunProdSet];
    const jenisProd = [...jenisProdSet];

    const seriesProd = jenisProd.map(jenisP => ({
        name: jenisP,
        type: 'line',
        smooth: true,
        data: yearsProd.map(th => {
            let row = rawProd.find(r => r.tahun == th && r.jenis_produksi == jenisP);
            let val = row ? row.jumlah : 0;
            return (val === "-" || val == null) ? 0 : Number(val);
        })
    }));

    let chartProd = echarts.init(chartProdContainer);
    chartProd.setOption({
        title: { text: `Produksi - ${kabupaten}`, left: "center" },
        tooltip: { trigger: 'axis' },
        legend: { type: 'scroll', bottom: 10 },
        xAxis: { type: 'category', data: yearsProd },
        yAxis: { type: 'value', name: "Produksi" },
        series: seriesProd
    });

    // ==========================
    // HARGA — versi bagus
    // ==========================
    let rawHarga = allHarga.filter(r => norm(r.kab_kota) === norm(kabupaten));

    const tahunHargaSet = new Set();
    const jenisHargaSet = new Set();

    rawHarga.forEach(r => {
        tahunHargaSet.add(r.tahun);
        jenisHargaSet.add(r.jenis_ternak + " (" + r.kategori + ")");
    });

    const yearsHarga = [...tahunHargaSet];
    const jenisHarga = [...jenisHargaSet];

    const seriesHarga = jenisHarga.map(j => ({
        name: j,
        type: 'line',
        smooth: true,
        data: yearsHarga.map(th => {
            let row = rawHarga.find(r => (r.jenis_ternak + " (" + r.kategori + ")") === j && r.tahun == th);
            let val = row ? row.harga : 0;
            return (val === "-" || val == null) ? 0 : Number(val);
        })
    }));

    let chartHarga = echarts.init(chartHargaContainer);
    chartHarga.setOption({
        title: { text: `Harga Ternak - ${kabupaten}`, left: "center" },
        tooltip: { trigger: 'axis' },
        legend: { type: 'scroll', bottom: 10 },
        xAxis: { type: 'category', data: yearsHarga },
        yAxis: { type: 'value', name: "Harga (Rp)" },
        series: seriesHarga
    });

}
// ========================================
// Dropdown Provinsi
// ========================================
document.getElementById('provinsiDropdown').addEventListener('change', function() {
    let prov = this.value;

    let filteredMarkers = prov ? allMarkers.filter(m => m.provinsi === prov) : allMarkers;
    addMarkers(filteredMarkers);

    let kabSelect = document.getElementById('kabupatenDropdown');
    Array.from(kabSelect.options).forEach(opt => {
        opt.style.display = (prov === "" || opt.getAttribute('data-prov') === prov) ? '' : 'none';
    });
    kabSelect.value = "";

});

// ========================================
// Dropdown Kabupaten
// ========================================
document.getElementById('kabupatenDropdown').addEventListener('change', function() {
    let kab = this.value;

    let filtered = kab ? allMarkers.filter(m => norm(m.kab_kota) === norm(kab)) : allMarkers;
    addMarkers(filtered);

    updateCharts(kab);  // PENTING!!
});

// ========================================
// Search Lokasi
// ========================================

//Debug
document.getElementById('kabupatenDropdown').addEventListener('change', function() {
    let kab = this.value;

    console.log("KAB TERPILIH:", kabTerpilih);
console.log("HASIL FILTER POPULASI:", filteredPopulasi.map(a => a.kab_kota));
console.log("HASIL FILTER PRODUKSI:", filteredProduksi.map(a => a.kab_kota));
console.log("HASIL FILTER HARGA:", filteredHarga.map(a => a.kab_kota));


    let filteredMarkers = kab ? allMarkers.filter(m => m.kab_kota === kab) : allMarkers;
    addMarkers(filteredMarkers);

    updateCharts(kab);
});

// ========================================
// Inisialisasi awal
// ========================================
addMarkers(allMarkers);
updateCharts("");
</script>


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
    

// 🔍 FITUR PENCARIAN MARKER (SPPG & UPH)
searchBox.addEventListener('keyup', function(e) {
    if (e.key === 'Enter') {
        const keyword = this.value.trim().toLowerCase(); // Keyword pencarian
        if (!keyword) return;

        const semuaData = [...allSppgData, ...allUphData];

        const hasilFilter = semuaData.filter(item => {
            
            // Mengambil Nilai Data dan memastikan mereka bersih (trim) dan lowercase
            const namaSppg = (item.Nama_SPPG || '').trim().toLowerCase();
            const namaUph = (item['Nama Koperasi/UPH'] || '').trim().toLowerCase();
            const prov = (item.provinsi || '').trim().toLowerCase();
            const kabKota = (item.Kab_Kota || '').trim().toLowerCase();
            const alamat = (item.Alamat || item.Alamat_Lokasi || '').trim().toLowerCase();

            // --- LOGIKA PENCARIAN FLEKSIBEL ---
            // Menggunakan .includes() sudah benar untuk flekibilitas (misal: "semarang" akan match "kota semarang")

            return (
                // Pencarian Nama Lokasi/UPH
                namaSppg.includes(keyword) || 
                (item.Nama && item.Nama.toLowerCase().includes(keyword)) ||
                namaUph.includes(keyword) ||
                
                // Pencarian Daerah
                prov.includes(keyword) || 
                kabKota.includes(keyword) ||
                
                // Pencarian Alamat
                alamat.includes(keyword)
            );
        });

        // ... [Lanjutan logika if (hasilFilter.length > 0) Anda] ...
        // ... (addMarkers(hasilFilter) dan map.fitBounds(bounds)) ...
        
        if (hasilFilter.length > 0) {
            // Logika untuk menampilkan marker dan zoom peta (map.fitBounds)
            addMarkers(hasilFilter); 

            const coords = hasilFilter.map(item => {
                // ... (Ambil koordinat item) ...
                const lat = parseFloat(item.Lat || item.latitude || item.Latitude);
                const lng = parseFloat(item.Long || item.longitude || item.Longitude);
                return [lat, lng];
            }).filter(c => !isNaN(c[0]) && !isNaN(c[1]));

            if (coords.length > 0) {
                const bounds = L.latLngBounds(coords);
                map.fitBounds(bounds, { padding: [50, 50] }); 
            } else {
                 alert('Koordinat tidak valid untuk lokasi ini.');
            }
        } else {
             alert('Lokasi atau Daerah tidak ditemukan.');
        }
    }
});

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

<!-- profiling -->
 <!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Profiling PKH</title>

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
body {
    margin: 0;
    padding: 0;
    background: #f8f9fa;
    overflow: hidden;
}

/* Sidebar kiri */
.sidebar {
    --sidebar-bg: #058146;
    width: 240px;
    min-height: 100vh;
    background-color: var(--sidebar-bg);
    color: #fff;
    position: fixed;
    left: 0;
    top: 0;
    z-index: 1030;
    padding: 15px;
    box-shadow: 5px 0 10px rgba(0, 0, 0, 0.1);
}
.sidebar .nav-link { color: #cdd6ff; }
.sidebar .nav-link:hover,
.sidebar .active-menu { color: #fff; }

/* Navbar */
.navbar {
    position: fixed;
    top: 0;
    left: 240px;
    right: 0;
    z-index: 1000;
    
    background: #053b21ff;     /* warna navbar */
    color: #fff;             /* warna teks */
    height: 56px;            /* tinggi fix */
    display: flex;
    align-items: center;     /* biar teks/logo center vertical */
    padding: 0 20px;
}


/* MAIN LAYOUT */
.main-container {
    display: flex;
    position: absolute;
    top: 56px;
    left: 240px;
    right: 0;
    bottom: 0;
    overflow-y: auto;    /* ⬅ scroll bebas */
    overflow-x: hidden;
    align-items: flex-start; /* penting */
}

/* MAP + CHART */
.map-chart-wrapper {
    flex: 7;
    display: flex;
    flex-direction: column;
    gap: 20px;
    padding: 15px;
    overflow: hidden;
    width: 100%;
}

/* MAP */
#map {
    height: 450px;
    border-radius: 10px;
    border: 3px solid #141566de;
    margin: 10px;
}

.chart-row {
    display: flex;
    flex-wrap: nowrap; /* tetap jejer */
    gap: 20px;
    margin-top: 20px;
}

.chart-box {
    flex: 1;
    min-width: 300px; 
    background: #fff;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.chart-area {
    height: 300px; /* atur tinggi grafik */
}


.side-panel h5 {
    border-bottom: 2px solid #0d6efd;
    margin-bottom: 12px;
    padding-bottom: 6px;
}


.analysis-box {
    padding: 10px;
    background: #f7f7f7;
    border-radius: 8px;
    border: 1px solid #ddd;
}
/* Info popup */
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

#analysisPanel {
    text-align: center;
}

.ai-box {
    background: #ffffff;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 15px;
    box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    line-height: 1.5;
}

</style>

</head>

<body>

<!-- SIDEBAR kiri-->
<div class="sidebar d-flex flex-column">
<img src="<?= base_url('images/logo.png') ?>" class="img-fluid" style="max-height:150px; max-width:150px; display:block; margin:0 auto;">
    
<h4 class="fw-bold text-white mb-4">Latsar</h4>
    <ul class="nav flex-column flex-grow-1">
        <li class="nav-item">
            <a class="nav-link" href="/">
                <i class="fas fa-tachometer-alt me-3"></i> Dashboard Utama
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/upload">
                <i class="fas fa-upload me-3"></i> Upload Data
            </a>
        </li>
                <li class="nav-item">
            <a class="nav-link   active-menu" href="/profiling">
                <i class="fas fa-address-card me-3"></i> Profiling
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-project-diagram me-3"></i> Core Value
            </a>
        </li>

    </ul>
</div>


<!-- NAVBAR- atas -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">🌍 P R O F I L I N G </a>

        <div class="ms-auto">
            <button class="btn btn-outline-light me-2"><i class="fas fa-bell"></i></button>
<button class="btn" 
        style="background:#058146; color:white; border:1px solid #058146;">
    <i class="fas fa-user-circle me-1"></i> Admin
</button>

        </div>
    </div>
</nav>


<!-- MAIN CONTENT AREA -->
<div class="main-container">
    <div class="map-chart-wrapper">

        <select id="provinsiDropdown" class="form-select mb-2">
            <option value="">-- Pilih Provinsi --</option>
            <?php foreach($provinsi as $prov): ?>
                <option value="<?= $prov['provinsi'] ?>"><?= $prov['provinsi'] ?></option>
            <?php endforeach; ?>
        </select>

        <select id="kabupatenDropdown" class="form-select mb-3">
            <option value="">-- Pilih Kabupaten --</option>
            <?php foreach($kabupaten as $kab): ?>
                <option value="<?= $kab['kab_kota'] ?>" data-prov="<?= $kab['provinsi'] ?>">
                    <?= $kab['kab_kota'] ?>
                </option>
            <?php endforeach; ?>
        </select>

<!-- pilihan tahun grafik -->
 <div class="d-flex align-items-center gap-3 my-3">
    <div>
        <label class="fw-bold mb-1">Tahun Awal</label>
        <select id="tahunAwal" class="form-select">
            <option value="">-- Pilih --</option>
            <?php for ($t=2020; $t<=2024; $t++): ?>
                <option value="<?= $t ?>"><?= $t ?></option>
            <?php endfor; ?>
        </select>
    </div>

    <div>
        <label class="fw-bold mb-1">Tahun Akhir</label>
        <select id="tahunAkhir" class="form-select">
            <option value="">-- Pilih --</option>
            <?php for ($t=2020; $t<=2024; $t++): ?>
                <option value="<?= $t ?>"><?= $t ?></option>
            <?php endfor; ?>
        </select>
    </div>

</div>

<!--  -->
<button id="runAnalysis" class="btn btn-sm btn-outline-primary">AI Analysis</button>

<div id="aiAnalysisCard" style="margin-top:12px; display:none;">
    <div class="card" style="padding:12px;">
        <h6>Ringkasan Analisis</h6>
        <div id="aiSummary"></div>
        <hr>
        <div id="aiDetails" style="font-size:13px; color:#333"></div>
    </div>
</div>

<!-- CARD HASIL CAGR -->
<div id="analysisPanel">

    <div class="card p-3 mt-3" style="border:1px solid #ddd; border-radius:8px;">
        <h4><b>CAGR (Rata-rata Pertumbuhan Tahunan)</b></h4>
        <table class="table">
            <tr>
                <th>Populasi</th>
            <td id="cagr_pop">-</td>
        </tr>
        <tr>
            <th>Produksi</th>
            <td id="cagr_prod">-</td>
        </tr>
        <tr>
            <th>Harga</th>
            <td id="cagr_harga">-</td>
        </tr>
    </table>
    </div>
</div> 

<!--  -->
<div style="display:flex; gap:20px; margin-bottom:25px;">
    <div id="gaugePop" style="width:150px; height:150px;"></div>
    <div id="gaugeProd" style="width:150px; height:150px;"></div>
    <div id="gaugeHarga" style="width:150px; height:150px;"></div>
</div>
<!--  -->
<div style="margin-top: 10px;">
    <div><b>Growth Populasi:</b> <span id="growthPop">0%</span></div>
    <div><b>Growth Produksi:</b> <span id="growthPrd">0%</span></div>
    <div><b>Growth Harga:</b> <span id="growthHrg">0%</span></div>
</div>
<!--  -->
<div class="row">
    <div class="chart-box">
        <h6>Grafik Populasi (Ekor)</h6>
        <div id="chart-populasi-auto" class="chart-area"></div>
    </div>
                
    <div class="chart-box">
        <h6>Grafik Produksi (Ekor)</h6>
        <div id="chart-produksi-auto" class="chart-area"></div>
    </div>
        
    <div class="chart-box">
        <h6>Harga Ternak (Rp)</h6>
        <div id="chart-harga-auto" class="chart-area"></div>
    </div>
</div>

<!-- BLOK AI DIPINDAHKAN KE SINI -->
<div class="card p-3 mt-4" style="border:1px solid #ddd; border-radius:8px;">
    <h3>Analisis AI</h3>
    <div id="ai-analysis" class="ai-box"></div>

    <h3 class="mt-3">Rekomendasi AI</h3>
    <div id="ai-recommendation" class="ai-box"></div>

    <h3 class="mt-3">Prediksi Tahun Depan</h3>
    <div id="ai-prediction" class="ai-box"></div>
</div>


<div id="ai_recommendation"></div>
<div id="ai_prediction"></div>



<!-- feach runanalysis-->
<script>
document.getElementById('runAnalysis').addEventListener('click', () => {
    const prov = document.getElementById('provinsiDropdown').value;
    const kab  = document.getElementById('kabupatenDropdown').value;
    const tA   = document.getElementById('tahunAwal').value;
    const tB   = document.getElementById('tahunAkhir').value;

    if (!prov || !kab || !tA || !tB) {
        alert('Lengkapi filter (prov/kab/tahun).');
        return;
    }

    // Reset UI / loading
    document.getElementById("ai-analysis").innerHTML = "Memuat...";
    document.getElementById("ai-recommendation").innerHTML = "Memuat...";
    document.getElementById("ai-prediction").innerHTML = "Memuat...";

    fetch(`/profiling/getAnalysis?provinsi=${encodeURIComponent(prov)}&kabupaten=${encodeURIComponent(kab)}&tahun_awal=${encodeURIComponent(tA)}&tahun_akhir=${encodeURIComponent(tB)}`)
        .then(res => {
            if (!res.ok) throw new Error('Network response was not ok: ' + res.status);
            return res.json();
        })
        .then(data => {
            console.log("DATA ANALISIS:", data);

            if (!data || data.error) {
                const msg = data?.error || "Tidak ada data untuk filter ini.";
                document.getElementById("ai-analysis").innerText = msg;
                document.getElementById("ai-recommendation").innerText = "-";
                document.getElementById("ai-prediction").innerText = "-";
                return;
            }

            // Ambil CAGR dari response
            const cagr = data.cagr || { populasi: '-', produksi: '-', harga: '-' };

            // === UPDATE CARD CAGR DI SINI ===
            document.getElementById("cagr_pop").innerText = cagr.populasi || "-";
            document.getElementById("cagr_prod").innerText = cagr.produksi || "-";
            document.getElementById("cagr_harga").innerText = cagr.harga || "-";

            // AI analysis, recommendation, prediction
            document.getElementById("ai-analysis").innerHTML = data.analysis || "-";
            document.getElementById("ai-recommendation").innerHTML = data.recommendation || "-";
            document.getElementById("ai-prediction").innerHTML = data.prediction || "-";
        })
        .catch(err => {
            console.error("ERROR:", err);
            document.getElementById("ai-analysis").innerText = "Gagal mengambil analisis.";
            document.getElementById("ai-recommendation").innerText = "-";
            document.getElementById("ai-prediction").innerText = "-";
            alert("Gagal mengambil analisis: " + err.message);
        });

});
</script>


<!-- grafik -->
<script>
function norm(str) {
    return str ? str.toLowerCase().trim() : "";
}

// =======================
// Data dari PHP ke JS
// =======================
let allPopulasi = <?= json_encode($populasi) ?>;
let allProduksi = <?= json_encode($produksi) ?>;
let allHarga = <?= json_encode($harga) ?>;



// =======================
// Fungsi update chart
// =======================
function updateCharts(kabupaten) {

    let chartPopContainer = document.getElementById('chart-populasi-auto');
    let chartProdContainer = document.getElementById('chart-produksi-auto');
    let chartHargaContainer = document.getElementById('chart-harga-auto');

    if (!kabupaten) {
        chartPopContainer.innerHTML = "<p style='text-align:center;color:#888;'>Pilih Kabupaten</p>";
        chartProdContainer.innerHTML = "<p style='text-align:center;color:#888;'>Pilih Kabupaten</p>";
        chartHargaContainer.innerHTML = "<p style='text-align:center;color:#888;'>Pilih Kabupaten</p>";
        return;
    }

    // =====================
// G A U G E  UPDATE
// =====================
let dataPop = allPopulasi.filter(r => norm(r.kab_kota) === norm(kabupaten));
let dataProd = allProduksi.filter(r => norm(r.kab_kota) === norm(kabupaten));
let dataHarga = allHarga.filter(r => norm(r.kab_kota) === norm(kabupaten));

let growthPop = hitungGrowth(dataPop, "jumlah_populasi");
let growthProd = hitungGrowth(dataProd, "jumlah");
let growthHarga = hitungGrowth(dataHarga, "harga");

// Render Gauge
createGauge("gaugePop", growthPop, "Populasi", "#0d6efd");
createGauge("gaugeProd", growthProd, "Produksi", "#198754");
createGauge("gaugeHarga", growthHarga, "Harga", "#ffc107");

    // ==========================
    // POPULASI — versi bagus
    // ==========================
    let rawPop = allPopulasi.filter(r => norm(r.kab_kota) === norm(kabupaten));

    const tahunSet = new Set();
    const jenisSet = new Set();

    rawPop.forEach(r => {
        tahunSet.add(r.tahun);
        jenisSet.add(r.jenis_ternak);
    });

    const years = [...tahunSet];
    const jenis = [...jenisSet];

    const seriesPop = jenis.map(jenisHewan => ({
        name: jenisHewan,
        type: 'line',
        smooth: true,
        data: years.map(th => {
            let row = rawPop.find(r => r.tahun == th && r.jenis_ternak == jenisHewan);
            let val = row ? row.jumlah_populasi : 0;
            return (val === "-" || val == null) ? 0 : Number(val);
        })
    }));

    let chartPop = echarts.init(chartPopContainer);
    chartPop.setOption({
        title: { text: `Populasi Hewan - ${kabupaten}`, left: "center" },
        tooltip: { trigger: 'axis' },
        legend: { type: 'scroll', bottom: 10 },
        xAxis: { type: 'category', data: years },
        yAxis: { type: 'value', name: "Populasi" },
        series: seriesPop
    });

    // ==========================
    // PRODUKSI — versi bagus
    // ==========================
    let rawProd = allProduksi.filter(r => norm(r.kab_kota) === norm(kabupaten));

    const tahunProdSet = new Set();
    const jenisProdSet = new Set();

    rawProd.forEach(r => {
        tahunProdSet.add(r.tahun);
        jenisProdSet.add(r.jenis_produksi);
    });

    const yearsProd = [...tahunProdSet];
    const jenisProd = [...jenisProdSet];

    const seriesProd = jenisProd.map(jenisP => ({
        name: jenisP,
        type: 'line',
        smooth: true,
        data: yearsProd.map(th => {
            let row = rawProd.find(r => r.tahun == th && r.jenis_produksi == jenisP);
            let val = row ? row.jumlah : 0;
            return (val === "-" || val == null) ? 0 : Number(val);
        })
    }));

    let chartProd = echarts.init(chartProdContainer);
    chartProd.setOption({
        title: { text: `Produksi - ${kabupaten}`, left: "center" },
        tooltip: { trigger: 'axis' },
        legend: { type: 'scroll', bottom: 10 },
        xAxis: { type: 'category', data: yearsProd },
        yAxis: { type: 'value', name: "Produksi" },
        series: seriesProd
    });

    // ==========================
    // HARGA — versi bagus
    // ==========================
    let rawHarga = allHarga.filter(r => norm(r.kab_kota) === norm(kabupaten));

    const tahunHargaSet = new Set();
    const jenisHargaSet = new Set();

    rawHarga.forEach(r => {
        tahunHargaSet.add(r.tahun);
        jenisHargaSet.add(r.jenis_ternak + " (" + r.kategori + ")");
    });

    const yearsHarga = [...tahunHargaSet];
    const jenisHarga = [...jenisHargaSet];

    const seriesHarga = jenisHarga.map(j => ({
        name: j,
        type: 'line',
        smooth: true,
        data: yearsHarga.map(th => {
            let row = rawHarga.find(r => (r.jenis_ternak + " (" + r.kategori + ")") === j && r.tahun == th);
            let val = row ? row.harga : 0;
            return (val === "-" || val == null) ? 0 : Number(val);
        })
    }));

    let chartHarga = echarts.init(chartHargaContainer);
    chartHarga.setOption({
        title: { text: `Harga Ternak - ${kabupaten}`, left: "center" },
        tooltip: { trigger: 'axis' },
        legend: { type: 'scroll', bottom: 10 },
        xAxis: { type: 'category', data: yearsHarga },
        yAxis: { type: 'value', name: "Harga (Rp)" },
        series: seriesHarga
    });

}
// ========================================
// Dropdown Provinsi
// ========================================
document.getElementById('provinsiDropdown').addEventListener('change', function() {
    let prov = this.value;
    let kabSelect = document.getElementById('kabupatenDropdown');
    Array.from(kabSelect.options).forEach(opt => {
        opt.style.display = (prov === "" || opt.getAttribute('data-prov') === prov) ? '' : 'none';
    });
    kabSelect.value = "";

});

// ========================================
// Search Lokasi
// ========================================
document.getElementById('kabupatenDropdown').addEventListener('change', function() {
    let kab = this.value;

    console.log("Kabupaten dipilih:", kab);

    updateCharts(kab);
});


//=====untuk grafik persentasi lingkaran

function createGauge(elementId, value, label, color) {
    let chart = echarts.init(document.getElementById(elementId));
    chart.setOption({
        series: [{
            type: 'gauge',
            startAngle: 90,
            endAngle: -270,
            progress: {
                show: true,
                width: 10,
                itemStyle: { color: color }
            },
            axisLine: {
                lineStyle: { width: 10 }
            },
            pointer: { show: false },
            axisTick: { show: false },
            splitLine: { show: false },
            axisLabel: { show: false },
            data: [{ value: value }],
            detail: {
                valueAnimation: true,
                formatter: value + '%\n' + label,
                color: '#000',
                fontSize: 16,
                offsetCenter: [0, 20]
            }
        }]
    });
}

createGauge("gaugePop", 100, "Populasi", "#0d6efd");
createGauge("gaugeProd", 100, "Produksi", "#198754");
createGauge("gaugeHarga", 100, "Harga", "#ffc107");

function hitungGrowth(data, field) {
    if (data.length === 0) return 0;

    // Ambil tahun awal & akhir
    let tahunList = [...new Set(data.map(d => d.tahun))].sort();

    let thAwal = tahunList[0];
    let thAkhir = tahunList[tahunList.length - 1];

    let awal = data.find(d => d.tahun == thAwal);
    let akhir = data.find(d => d.tahun == thAkhir);

    let vAwal = awal ? Number(awal[field]) : 0;
    let vAkhir = akhir ? Number(akhir[field]) : 0;

    if (vAwal <= 0) return 0;


    return Math.round(((vAkhir - vAwal) / vAwal) * 100);
}


</script>

</body>
</html>

<!-- upload -->
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Profiling PKH</title>

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
body {
    margin: 0;
    padding: 0;
    background: #f8f9fa;
    overflow: hidden;
}

/* Sidebar kiri */
.sidebar {
    --sidebar-bg: #058146;
    width: 240px;
    min-height: 100vh;
    background-color: var(--sidebar-bg);
    color: #fff;
    position: fixed;
    left: 0;
    top: 0;
    z-index: 1030;
    padding: 15px;
    box-shadow: 5px 0 10px rgba(0, 0, 0, 0.1);
}
.sidebar .nav-link { color: #cdd6ff; }
.sidebar .nav-link:hover,
.sidebar .active-menu { color: #fff; }

/* Navbar */
.navbar {
    position: fixed;
    top: 0;
    left: 240px;
    right: 0;
    z-index: 1000;
    
    background: #053b21ff;     /* warna navbar */
    color: #fff;             /* warna teks */
    height: 56px;            /* tinggi fix */
    display: flex;
    align-items: center;     /* biar teks/logo center vertical */
    padding: 0 20px;
}


/* MAIN LAYOUT */
.main-container {
    display: flex;
    position: absolute;
    top: 56px;
    left: 240px;
    right: 0;
    bottom: 0;
    overflow-y: auto;    /* ⬅ scroll bebas */
    overflow-x: hidden;
    align-items: flex-start; /* penting */
}


</style>

</head>

<body>

<!-- SIDEBAR kiri-->
<div class="sidebar d-flex flex-column">
<img src="<?= base_url('images/logo.png') ?>" class="img-fluid" style="max-height:150px; max-width:150px; display:block; margin:0 auto;">
    
<h4 class="fw-bold text-white mb-4">Latsar</h4>

<div class="sidebar d-flex flex-column">
<img src="<?= base_url('images/logo.png') ?>" class="img-fluid" 
style="max-height:150px; max-width:150px; display:block; margin:0 auto;">
 
    <h4 class="fw-bold text-white mb-4">Latsar</h4>
<ul class="nav flex-column flex-grow-1">
    <li class="nav-item">
        <a class="nav-link" href="/">
            <i class="fas fa-tachometer-alt me-3"></i> Dashboard Utama
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link " href="/upload">
            <i class="fas fa-upload me-3"></i> Upload Data
        </a>
    </li>
        <li class="nav-item">
        <a class="nav-link" href="/profiling">
            <i class="fas fa-address-card me-3"></i> Profiling
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link active-menu" href="#">
            <i class="fas fa-project-diagram me-3"></i> Core value
        </a>
    </li>

</ul>

</div>
</div>


<!-- NAVBAR- atas -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">🌍 Upload Data </a>

        <div class="ms-auto">
            <button class="btn btn-outline-light me-2"><i class="fas fa-bell"></i></button>
            <button class="btn" 
                    style="background:#058146; color:white; border:1px solid #058146;">
                <i class="fas fa-user-circle me-1"></i> Admin
            </button>

        </div>
    </div>
</nav>

<!-- database view -->
<div class="main-container">
    <div class="container-fluid py-4">

    <!-- Card Upload -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-upload me-1"></i> Upload Data Excel</h5>
            </div>
        <div class="card-body">

<!-- Dropdown Kategori -->
<form action="<?= base_url('/upload') ?>" method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="kategori" class="form-label">Kategori Data</label>
        <select class="form-select" name="kategori" id="kategori" required>
            <option value="" selected disabled>Pilih Kategori</option>
            <option value="populasi">Populasi</option>
            <option value="produksi">Produksi</option>
            <option value="harga">Harga</option>
        </select>
    </div>

    <!-- Form Upload -->
                    <div class="mb-3">
                        <input type="file" class="form-control" name="excel_file" accept=".xls,.xlsx" required>
                    </div>
                    <button class="btn btn-success"><i class="fas fa-upload me-1"></i> Upload</button>
                </form>

    <!-- Feedback -->
                <?php if(session()->getFlashdata('success')): ?>
                    <div class="alert alert-success mt-2"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>
                <?php if(session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger mt-2"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

            </div>
        </div>


<!-- ======================================= -->
    <!-- tombol dowenload populasi-->
    <a href="<?= base_url('/download-data') ?>" class="btn btn-success me-2">
        <i class="fas fa-download me-1"></i> Download Populasi
    </a>

    <!-- tabel populasi-->
        <div class="card shadow-sm">
            <div class="card-header text-white" style="background-color: #058146;">
            <h5 class="mb-0"><i class="fas fa-table me-1"></i> Data Populasi Saat Ini</h5>
            </div>

            <div class="card-body table-responsive" style="max-height:400px; overflow-y:auto;">
                <table class="table table-bordered table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Provinsi</th>
                            <th>Kab/Kota</th>
                            <th>Jenis Ternak</th>
                            <th>Tahun</th>
                            <th>Jumlah Populasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($populasi as $row): ?>
                        <tr>
                            <td><?= $row['provinsi'] ?></td>
                            <td><?= $row['kab_kota'] ?></td>
                            <td><?= $row['jenis_ternak'] ?></td>
                            <td><?= $row['tahun'] ?></td>
                            <td><?= $row['jumlah_populasi'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

  <!-- tombol dowenlod produksi -->
<div class="mt-4">
    <a href="<?= base_url('/download-produksi') ?>" class="btn btn-warning">
        <i class="fas fa-download me-1"></i> Download Produksi
    </a>
</div>

<!-- Card Produksi -->
    <div class="card shadow-sm mt-4">
    <div class="card-header bg-warning text-dark">
        <h5 class="mb-0"><i class="fas fa-industry me-1"></i> Data Produksi Saat Ini</h5>
    </div>
    <div class="card-body table-responsive" style="max-height:400px; overflow-y:auto;">
        <table class="table table-bordered table-striped mb-0">
            <thead>
                <tr>
                    <th>Provinsi</th>
                    <th>Kab/Kota</th>
                    <th>Jenis Produksi</th>
                    <th>Tahun</th>
                    <th>Jumlah Produksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($produksi as $row): ?>
                <tr>
                    <td><?= $row['provinsi'] ?></td>
                    <td><?= $row['kab_kota'] ?></td>
                    <td><?= $row['jenis_produksi'] ?></td>
                    <td><?= $row['tahun'] ?></td>
                    <td><?= $row['jumlah'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- tombol dowenlod harga -->
 <div class="mt-4">
    <a href="<?= base_url('/download-harga') ?>" class="btn btn-danger mt-4">
        <i class="fas fa-download me-1"></i> Download Data Harga
        </a>
</div>

<!-- Card Harga -->
<div class="card shadow-sm mt-4">
    <div class="card-header bg-danger text-white">
        <h5 class="mb-0"><i class="fas fa-dollar-sign me-1"></i> Data Harga Saat Ini</h5>
    </div>
    <div class="card-body table-responsive" style="max-height:400px; overflow-y:auto;">
        <table class="table table-bordered table-striped mb-0">
            <thead>
                <tr>
                    <th>Provinsi</th>
                    <th>Kab/Kota</th>
                    <th>Jenis Ternak</th>
                    <th>Kategori</th>
                    <th>Tahun</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($harga as $row): ?>
                <tr>
                    <td><?= $row['provinsi'] ?></td>
                    <td><?= $row['kab_kota'] ?></td>
                    <td><?= $row['jenis_ternak'] ?></td>
                    <td><?= $row['kategori'] ?></td>
                    <td><?= $row['tahun'] ?></td>
                    <td><?= $row['harga'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</div>
</div>

</body>
</html>
