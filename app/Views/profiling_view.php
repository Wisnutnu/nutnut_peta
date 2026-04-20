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

<?= $this->include('layout/user/navbar') ?>
<?= $this->include('layout/sidebar/sidebar_user') ?>
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
    height: 650px;
    width: 100%;
    border-radius: 10px;
    border: 3px solid #141566de;
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

/* untuk popup */
.popup-infra h4 {
    margin: 0;
    font-size: 16px;
}

.popup-infra table {
    font-size: 13px;
    width: 100%;
}

.popup-infra td {
    padding: 2px 4px;
    vertical-align: top;
}

/* warna judul di dalam popup */
.badge {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    color: #fff;
    margin-bottom: 6px;
    text-transform: uppercase;
}

/* Infrastruktur */
.badge-sppg        { background: #2ecc71; }  /* hijau */
.badge-rph         { background: #e74c3c; }  /* merah */
.badge-puskeswan   { background: #3498db; }  /* biru */
.badge-klinikhewan { background: #9b59b6; }  /* ungu */

.badge-labkeswan   { background: #f39c12; }  /* orange */
.badge-labkesmavet { background: #1abc9c; }  /* tosca */
.badge-labbibit    { background: #34495e; }  /* abu gelap */
.badge-pasarternak { background: #d35400; }  /* coklat orange */

.badge-koperasipkh { background: #7f8c8d; }  /* abu */
.badge-uph         { background: #16a085; }  /* hijau tua */

/* fallback kalau jenis tidak dikenal */
.badge-default {
    background: #555;
}


</style>

</head>
<!-- MAIN CONTENT AREA -->
<div class="main-container">
    <div class="map-chart-wrapper">
        <h5>User</h5>
<!-- MAP -->
<div id="map"></div>
        <select id="provinsiDropdown" class="form-select mb-2">
            <option value="">-- Pilih Provinsi --</option>
            <?php foreach($provinsi as $prov): ?>
                <option value="<?= $prov['provinsi'] ?>"><?= $prov['provinsi'] ?></option>
            <?php endforeach; ?>
        </select>

        <select id="kabupatenDropdown" class="form-select mb-3">
            <option value="">-- Pilih Kabupaten --</option>
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
        <h6>Grafik Produksi </h6>
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
</div>
    </div>


<div id="ai_recommendation"></div>
<div id="ai_prediction"></div>

<!-- ============================================ -->
<!-- utk kirim data dari php ke js -->
<script>
    let allLokasi = <?= json_encode($lokasi) ?>;
    const allKabupaten = <?= json_encode($kabupaten); ?>;

//<!-- untuk nampilin console per jenis data -->
function summarizeInfraByJenis(filterKab) {
    const summary = {};

    allLokasi.forEach(row => {
        if (!row.lat || !row.lng) return;
        if (norm(row.kab_kota) !== norm(filterKab)) return;

        const jenis = row.jenis || 'unknown';
        summary[jenis] = (summary[jenis] || 0) + 1;
    });

    console.group(`📍 Data yg tersedia — ${filterKab}`);
    Object.entries(summary).forEach(([jenis, total]) => {
        console.log(`${jenis}:`, total);
    });
    console.groupEnd();
}
// misal ada data taging yg invalid itu karena apa
function showInvalidSample(filterKab) {
    let sample = [];

    allLokasi.forEach(row => {
        if (norm(row.kab_kota) !== norm(filterKab)) return;
        if (!row.lat || !row.lng) {
            sample.push(row);
        }
    });

    console.table(sample.slice(0, 20)); // tampilkan 10 contoh saja
}

// warna taging
function getInfraIcon(jenis) {

    const colors = {
        sppg: '#2ecc71',
        rph: '#e74c3c',
        puskeswan: '#3498db',
        klinikhewan: '#9b59b6',
        labkeswan: '#f39c12',
        labkesmavet: '#1abc9c',
        labbibit: '#34495e',
        pasarternak: '#d35400',
        koperasipkh: '#7f8c8d',
        uph: '#16a085',
        default: '#555'
    };

    const color = colors[jenis] || colors.default;

    return L.divIcon({
        className: 'infra-marker',
        html: `<div style="
            background:${color};
            width:14px;
            height:14px;
            border-radius:50%;
            border:2px solid white;
            box-shadow:0 0 3px rgba(0,0,0,.5);
        "></div>`
    });
}

</script>

 
<!-- peta -->
 <script>
const defaultCenter = [-2.5, 118];
const defaultZoom = 5;


let map = L.map('map').setView(defaultCenter, defaultZoom);


L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18
}).addTo(map);

let layerKabupaten;
let layerProvinsi;
let klikDiPolygon = false;
let infraLayer = L.layerGroup().addTo(map);

//dropdown provinsi
        const dropdownProv = document.getElementById('provinsiDropdown');
        dropdownProv.addEventListener('change', function () {

            const prov = this.value;

            // reset dropdown kabupaten
            dropdownKab.innerHTML = '<option value="">-- Pilih Kabupaten --</option>';

            // reset map & layer
            resetMap();
            infraLayer.clearLayers();

            if (!prov) return;

            // filter kabupaten sesuai provinsi
            const kabFiltered = allKabupaten.filter(k => k.provinsi === prov);

            // ambil kabupaten unik
            const uniqueKab = [...new Set(kabFiltered.map(k => k.kab_kota))];

            uniqueKab.forEach(kab => {
                const opt = document.createElement('option');
                opt.value = kab;
                opt.textContent = kab;
                dropdownKab.appendChild(opt);
            });

            console.log(`📍 Kabupaten di ${prov}:`, uniqueKab.length);
        });
//dropdowen kabupaten
const dropdownKab  = document.getElementById('kabupatenDropdown');
dropdownKab.addEventListener('change', function(){
    const kab = this.value;
    if (!kab) return;

    renderInfrastruktur(kab);
});

console.log('dropdown:', dropdownKab);

        //nambahin popup polygon kabupaten dipilih
                function countJenisPopulasi(kab, prov) {
            const set = new Set();
            allPopulasi.forEach(d => {
                if (norm(d.kab_kota) === norm(kab) && norm(d.provinsi) === norm(prov)) {
                    set.add(d.jenis_ternak);
                }
            });
            return set.size;
        }

        function countJenisProduksi(kab, prov) {
            const set = new Set();
            allProduksi.forEach(d => {
                if (norm(d.kab_kota) === norm(kab) && norm(d.provinsi) === norm(prov)) {
                    set.add(d.jenis_produksi);
                }
            });
            return set.size;
        }

function getHargaSummary(kab, prov, kategoriDipilih = 'Harga Konsumen') {

    // filter wilayah + kategori
    const dataWilayah = allHarga.filter(r =>
        r.kab_kota === kab &&
        r.provinsi === prov &&
        r.kategori === kategoriDipilih
    );

    if (dataWilayah.length === 0) {
        return { jenis: 0, tahun: "-" };
    }

    // cari tahun terbaru
    const tahunTerbaru = Math.max(...dataWilayah.map(r => Number(r.tahun)));

    // data hanya tahun terbaru
    const dataTerbaru = dataWilayah.filter(r =>
        Number(r.tahun) === tahunTerbaru
    );

    // hitung jenis ternak unik
    const jenisSet = new Set(
        dataTerbaru.map(r => r.jenis_ternak)
    );

    return {
        jenis: jenisSet.size,
        tahun: tahunTerbaru
    };
}

//fungsi hitung infrastruktur per kabupaten
        function getInfraCountKab(kab) {

            const result = {};
            let total = 0;

            allLokasi.forEach(row => {
                if (!row.lat || !row.lng) return;
                if (norm(row.kab_kota) !== norm(kab)) return;

                const jenis = row.jenis || 'lainnya';
                result[jenis] = (result[jenis] || 0) + 1;
                total++;
            });

            return { total, detail: result };
        }

//provinsi
fetch('http://localhost:8080/titik_koordinat/provinsi_new_kecil.json')
.then(res => res.json())
.then(data => {

});

//highlight provinsi biar kelihatan
function styleProvinsiDefault() {
    return { color:'#444', weight:1, fillOpacity:0 };
}

function styleProvinsiActive() {
    return { color:'#000', weight:3, fillOpacity:0 };
}

//fungsi ambil nilai dari database
function getDataKab(kab, prov) {

    let pop = allPopulasi.find(d =>
        d.kab_kota === kab && d.provinsi === prov
    );

    let prod = allProduksi.find(d =>
        d.kab_kota === kab && d.provinsi === prov
    );

    let harga = allHarga.find(d =>
        d.kab_kota === kab && d.provinsi === prov
    );

    return {
        populasi: pop ? pop.jumlah_populasi : 0,
        produksi: prod ? prod.jumlah : 0,
        harga: harga ? harga.harga : 0
    };
}

//warna berdasarkan populasi
function getColor(val) {
    return val > 100000 ? '#800026' :
           val > 50000  ? '#BD0026' :
           val > 20000  ? '#E31A1C' :
           val > 10000  ? '#FC4E2A' :
           val > 5000   ? '#FD8D3C' :
           val > 1000   ? '#FEB24C' :
                          '#FFEDA0';
}


//gjson kabupaten
fetch('http://localhost:8080/titik_koordinat/kabupaten_coba_master.json')
.then(res => res.json())
.then(geo => {

    layerKabupaten = L.geoJSON(geo, {
        style: styleKabupaten,

onEachFeature: (feature, layer) => {

    let p = feature.properties;

    // 1️⃣ data populasi / produksi / harga
        let jPop  = countJenisPopulasi(p.KAB_KOTA, p.PROVINSI);
        let jProd = countJenisProduksi(p.KAB_KOTA, p.PROVINSI);
        const hargaInfo = getHargaSummary(
    p.KAB_KOTA,
    p.PROVINSI,
    'Harga Konsumen'
);


    // 2️⃣ HITUNG INFRASTRUKTUR (INI YANG KURANG)
    let infra = getInfraCountKab(p.KAB_KOTA);

    // 3️⃣ baru dipakai di popup
        layer.bindPopup(`
            <b>${p.KAB_KOTA}</b><br>
            Prov: ${p.PROVINSI}<br>
           

            <hr>

            <b>Ketersediaan Data</b><br>
            Jenis Populasi Ternak : <b>${jPop}</b><br>
            Jenis Produksi       : <b>${jProd}</b><br>
            Jenis Komoditas Harga (konsumen) (${hargaInfo.tahun}) : ${hargaInfo.jenis}<br>

            <hr>

            <b>Infrastruktur PKH</b><br>
            Total Titik: <b>${infra.total}</b><br>

            ${Object.entries(infra.detail).map(([jenis, total]) => `
                <span class="badge badge-${jenis}">
                    ${jenis.toUpperCase()} : ${total}
                </span>
            `).join('<br>')}
        `);

//set dropdown
layer.on('click', function(e){

    L.DomEvent.stopPropagation(e);

    // 👉 set dropdown ikut berubah
dropdownKab.value = p.KAB_KOTA;
dropdownKab.dispatchEvent(new Event('change')); // 🔥 paksa trigger grafik

    focusKabupaten(layer);
});

        }

    }).addTo(map);

});

//saat dropdowen berubah-fokus ke peta
dropdownKab.addEventListener('change', function(){

    const kab = this.value;

    layerKabupaten.eachLayer(layer => {

        if (layer.feature.properties.KAB_KOTA === kab) {
            focusKabupaten(layer);
        }
    });
    renderInfrastruktur(kab); 

});

//stylekabupaten
function styleKabupaten(feature) {
    let p = feature.properties;
    let data = getDataKab(p.KAB_KOTA, p.PROVINSI);

    return {
        weight: 1,
        color: '#666',
        fillColor: getColor(data.populasi),
        fillOpacity: 0.6
    };
}

//fungsi fokus ke kab
function focusKabupaten(activeLayer) {

    layerKabupaten.eachLayer(layer => {

        if (layer === activeLayer) {
            // kab yang dipilih
            layer.setStyle({
                weight: 3,
                color: '#000',
                fillOpacity: 0.9
            });
            layer.bringToFront();
            map.fitBounds(layer.getBounds(), { padding: [20,20] });
            layer.openPopup();

        } else {
            // kab lain diredupkan
            layer.setStyle({
                weight: 1,
                color: '#aaa',
                fillOpacity: 0.2
            });
        }

    });
}

//fungsi highlight kab
function highlightKabupaten(namaKab) {

    layerKabupaten.eachLayer(layer => {

        const kab = layer.feature.properties.KAB_KOTA;

        if (kab === namaKab) {
            // yang dipilih → menonjol
            layer.setStyle({
                weight: 3,
                color: '#000',
                fillOpacity: 0.9
            });
            layer.bringToFront();
            map.fitBounds(layer.getBounds());
            layer.openPopup();

        } else {
            // yang lain → diredupkan
            layer.setStyle({
                weight: 1,
                color: '#aaa',
                fillOpacity: 0.2
            });
        }
    });
}

//
const resetCatcher = L.rectangle(
    [[-11, 94], [6, 141]],   // kira-kira seluruh Indonesia
    { color: 'transparent', weight: 0, fillOpacity: 0 }
).addTo(map);

resetCatcher.on('click', function(e) {
    L.DomEvent.stopPropagation(e);
    resetMap();
});

//reset map
    function resetMap() {

        if (!layerKabupaten) return;
    dropdownKab.value = "";

    infraLayer.clearLayers();

        layerKabupaten.eachLayer(l => {
            layerKabupaten.resetStyle(l);
        });

        map.closePopup();

        map.flyTo(defaultCenter, defaultZoom, { animate: true });

        const dd = document.getElementById('kabupatenDropdown');
        if (dd) dd.value = "";

map.on('click', function(e) {
    if (e.originalEvent.target.closest('.leaflet-marker-icon')) return;
    resetMap();
    updateCharts(null);
});

    }

console.log('dropdown options:', [...dropdownKab.options].map(o=>o.value));

// fungsi render marker
function renderInfrastruktur(filterKab) {

    // ⛔ kalau belum pilih kabupaten → jangan render apapun
    if (!filterKab) {
        infraLayer.clearLayers();
        console.log("INFRA: belum pilih kabupaten, marker tidak dirender");
        return;
    }

    infraLayer.clearLayers();

    let valid = 0;
    let invalid = 0;

    allLokasi.forEach(row => {

        if (!row.lat || !row.lng) {
            invalid++;
            return;
        }

        // filter kabupaten pakai normalisasi
        if (norm(row.kab_kota) !== norm(filterKab)) return;

        valid++;

//         let marker = L.marker([row.lat, row.lng])
// //data popup taging
// .bindPopup(`
//     <div class="popup-infra">
//         <h4>${row.nama}</h4>

//         <div class="badge badge-${row.jenis}">
//             ${row.jenis.toUpperCase()}
//         </div>

//         <hr>

//         <table>
//             <tr>
//                 <td><b>Alamat</b></td>
//                 <td>${row.alamat}</td>
//             </tr>
//             <tr>
//                 <td><b>Kab/Kota</b></td>
//                 <td>${row.kab_kota}</td>
//             </tr>
//             <tr>
//                 <td><b>Provinsi</b></td>
//                 <td>${row.provinsi}</td>
//             </tr>
//         </table>
//     </div>
// `);

// misalkan mau tema taging kecil2 aktifkan ini sampai =
let marker = L.marker([row.lat, row.lng], {
    icon: getInfraIcon(row.jenis),
    interactive: true,
    bubblingMouseEvents: false
})
.bindPopup(`
    <div class="popup-infra">
        <h4>${row.nama}</h4>

        <div class="badge badge-${row.jenis}">
            ${row.jenis.toUpperCase()}
        </div>

        <table>
            <tr>
                <td><b>Alamat</b></td>
                <td>${row.alamat}</td>
            </tr>
            <tr>
                <td><b>Kab/Kota</b></td>
                <td>${row.kab_kota}</td>
            </tr>
            <tr>
                <td><b>Provinsi</b></td>
                <td>${row.provinsi}</td>
            </tr>
        </table>
    </div>
`);
// =========================
        infraLayer.addLayer(marker);
    });

    console.log("data yg tersedia:", {
        filter: filterKab,
        valid,
        invalid
    });
    summarizeInfraByJenis(filterKab);
    showInvalidSample(filterKab);

}


</script>

<!-- ----------------------------------------------- -->
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


<!-- grafik chart -->
<script>
function norm(str) {
    if (!str) return "";

    return str.toLowerCase()
        .replace(/kabupaten|kab\.?|kota|kota\.?/g, '') // buang kata kab/kota
        .replace(/[\.\,]/g, '')                        // buang titik koma
        .replace(/\s+/g, '')                           // hapus semua spasi
        .trim();
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

    echarts.dispose(document.getElementById('chart-populasi-auto'));
    echarts.dispose(document.getElementById('chart-produksi-auto'));
    echarts.dispose(document.getElementById('chart-harga-auto'));

    let chartPopContainer = document.getElementById('chart-populasi-auto');
    let chartProdContainer = document.getElementById('chart-produksi-auto');
    let chartHargaContainer = document.getElementById('chart-harga-auto');

    if (!kabupaten) {
        chartPopContainer.innerHTML = "<p style='text-align:center;color:#888;'>Data belum diinputkan</p>";
        chartProdContainer.innerHTML = "<p style='text-align:center;color:#888;'>Data belum diinputkan</p>";
        chartHargaContainer.innerHTML = "<p style='text-align:center;color:#888;'>Data belum diinputkan</p>";
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


    // =======================
    // helper formatter
    // =======================
        function formatID(val) {
            if (val === null || val === undefined || val === '-') return '0';
            return Math.round(Number(val)).toLocaleString('id-ID');
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
            return (val === "-" || val == null) ? 0 : Math.round(Number(val));
        })
    }));

    let chartPop = echarts.init(chartPopContainer);
    chartPop.setOption({
        title: { text: `Populasi Hewan - ${kabupaten}`, left: "center" },
        tooltip: {
                    trigger: 'axis',
                    valueFormatter: function (value) {
                        return formatID(value);
                    }
                },

        legend: { type: 'scroll', bottom: 10 },
        xAxis: { type: 'category', data: years },
        yAxis: {
                type: 'value',
                name: "Populasi",
                axisLabel: {
                formatter: function (value) {
                return formatID(value);
                        }
                    }
                },

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
        tooltip: {
                    trigger: 'axis',
                    valueFormatter: value => formatID(value)
                 },

        legend: { type: 'scroll', bottom: 10 },
        xAxis: { type: 'category', data: yearsProd },
        yAxis: {
    type: 'value',
    name: "Produksi",
    axisLabel: {
        formatter: value => formatID(value)
    }
},

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

    layerKabupaten.eachLayer(layer => {
        layer.setStyle(styleKabupaten(layer.feature));
    });
});

// ========================================
// Search Lokasi
// ========================================
document.getElementById('kabupatenDropdown').addEventListener('change', function() {
    let kab = this.value;

    updateCharts(kab);
    highlightKabupaten(kab);
});


function zoomKabupaten(namaKab) {
    layerKabupaten.eachLayer(l => {
        if (l.feature.properties.KAB_KOTA === namaKab) {
            map.fitBounds(l.getBounds());
            l.openPopup();
        }
    });
}

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