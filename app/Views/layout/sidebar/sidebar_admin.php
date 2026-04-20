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


/* ===== SIDEBAR SPECIFIC ===== */
    .sidebar-section {
        font-size: 12px;
        letter-spacing: 1px;
        color: rgba(255,255,255,0.6);
        margin-top: 10px;
        margin-bottom: 5px;
        padding-left: 5px;
    }
    
    .sidebar-menu {
        overflow-y: auto;
}

.sidebar-menu::-webkit-scrollbar {
    width: 4px;
}
.sidebar-menu::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.2);
}
</style>
<style>
.circle-card {
    width: 180px;
    height: 180px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    color: white;
    font-weight: bold;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    margin: auto;
}

.circle-green { background: linear-gradient(135deg, #28a745, #20c997); }
.circle-blue { background: linear-gradient(135deg, #007bff, #17a2b8); }
.circle-orange { background: linear-gradient(135deg, #fd7e14, #ffc107); }

.circle-card h3 {
    font-size: 32px;
    margin: 0;
}

.circle-card span {
    font-size: 14px;
}
</style>
<!-- SIDEBAR kiri-->
<div class="sidebar d-flex flex-column">

    <img src="<?= base_url('images/logo.png') ?>" 
         class="img-fluid mb-3" 
         style="max-height:120px; max-width:120px; display:block; margin:0 auto;">

    <h5 class="fw-bold text-white text-center mb-3">LATSAR ADMIN</h5>

    <ul class="nav flex-column flex-grow-1 sidebar-menu">

        <!-- MONITORING -->
        <div class="sidebar-section">MONITORING</div>

        <li class="nav-item">
            <a class="nav-link <?= service('uri')->getSegment(1) == 'admin' && service('uri')->getSegment(2) == null ? 'active-menu' : '' ?>" 
            href="<?= base_url('admin') ?>">
            <i class="fas fa-chart-line me-2"></i> Dashboard
            </a>
        </li>

        <!-- MANAJEMEN USER -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#menuUser">
                <i class="fas fa-users me-2"></i> Manajemen User
            </a>

            <div id="menuUser" class="collapse">
                <a class="nav-link ms-4" href="<?= base_url('admin/managementuser') ?>">
                    <i class="fas fa-list me-2"></i> List User
                </a>

                <a class="nav-link ms-4" href="<?= base_url('admin/managementuser/create') ?>">
                    <i class="fas fa-plus me-2"></i> Tambah User
                </a>
            </div>
        </li>

        <!-- DATA -->
        <div class="sidebar-section mt-3">DATA</div>

        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('admin/upload') ?>">
                <i class="fas fa-upload me-2"></i> Upload Data
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('admin/profiling/staging') ?>">
                <i class="fas fa-check-circle me-2"></i> Staging Profiling
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('admin/infrastruktur/staging') ?>">
                <i class="fas fa-check-circle me-2"></i> Staging Infrastruktur
            </a>
        </li>
        <!-- <li class="nav-item">
            <a class="nav-link" href="<?= base_url('admin/staging/penyuluh') ?>">
                <i class="fas fa-check-circle me-2"></i> Staging Pemotongan
            </a>
        </li> -->

        <!-- APPROVE DATA -->
        <div class="sidebar-section mt-3">APPROVE DATA</div>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('admin/populasi') ?>">
                <i class="fas fa-database"></i> Data Populasi Penyuluh
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('admin/data_approved') ?>">
                <i class="fas fa-check-circle"></i> Approval Profiling
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('admin/infrastruktur/approved') ?>">
                <i class="fas fa-check-circle"></i> Approval Infrastruktur
            </a>
        </li>

        <!--  -->
        <div class="sidebar-section mt-3">PENYULUH PERTANIAN</div>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('admin/approval/penyuluh') ?>">
                <i class="fas fa-check-circle me-2"></i> Approval pemotongan
            </a>
        </li>
        <!-- GIS -->
        <div class="sidebar-section mt-3">GIS / PROFILING</div>

        <!-- <li class="nav-item">
            <a class="nav-link" href="<?= base_url('/infrastruktur') ?>">
                <i class="fas fa-address-card me-2"></i> -------------
            </a>
        </li> -->

        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('admin/profiling') ?>">
                <i class="fas fa-map me-2"></i> Peta Profiling view
            </a>
        </li>

        
        <!-- SISTEM -->
        <div class="sidebar-section mt-3">SISTEM</div>

        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('admin/carapenggunaan') ?>">
                <i class="fas fa-book me-2"></i> Cara Penggunaan
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('logout') ?>">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </li>

    </ul>
</div>
