<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title><?= $title ?? 'A D M I N' ?></title>


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

/* untuk tampilan staging tambah ini */
.content-wrapper {
    width: 100%;
    display: block; /* PENTING */
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
<body>
