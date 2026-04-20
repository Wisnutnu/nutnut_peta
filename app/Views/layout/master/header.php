<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title><?= $title ?? 'THE M A S T E R' ?></title>

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
    background: #053b21ff;
    color: #fff;
    height: 56px;
    display: flex;
    align-items: center;
    padding: 0 20px;
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
}
</style>

</head>
<body>
