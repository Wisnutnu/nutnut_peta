<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title><?= $title ?? 'U S E R' ?></title>


<style>
    :root {
    --sidebar-width: 260px;
    --navbar-height: 60px;
}

body {
    margin: 0;
    padding: 0;
    background: #f8f9fa;
    overflow-x: hidden;
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
    left: 0px;
    right: 0;
    z-index: 1000;
    background: #053b21ff;
    color: #fff;
    height: 56px;
    display: flex;
    align-items: center;
    padding: 0 20px;
    overflow: visible !important;
}

/* MAIN LAYOUT */
.main-container {
    display: flex;
    flex-direction: column;
    position: absolute;
    top: 56px;
    left: 240px;
    right: 0;
    bottom: 0;
    overflow-y: auto;
    overflow-x: hidden;
    overflow: visible;
}

/* untuk tampilan staging tambah ini */
.content-wrapper {
    width: 100%;
    display: block; /* PENTING */
}

/* Avatar Circle */
.avatar-circle {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: #0d6efd;
    color: #fff;
    font-weight: bold;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
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

<style>
.pagination {
    display: flex;
    list-style: none;
    gap: 6px;
    padding: 0;
}

.pagination li a {
    padding: 6px 12px;
    border: 1px solid #ddd;
    text-decoration: none;
    border-radius: 6px;
}

.pagination li.active a {
    background-color: #0d6efd;
    color: white;
}

.pagination li a:hover {
    background-color: #eee;
}

/* tampilan popuasi */
.table-hover tbody tr:hover {
    background-color: #f8f9fa;
    transition: 0.2s;
}

.badge {
    font-size: 11px;
    padding: 6px 10px;
    border-radius: 8px;
}
</style>


</head>
<body>
