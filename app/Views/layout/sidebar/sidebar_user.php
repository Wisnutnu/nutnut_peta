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

<!-- SIDEBAR USER -->
<div class="sidebar d-flex flex-column">
    <?php $level = session()->get('level_user'); ?>

    <!-- logo -->
        <img src="<?= base_url('images/logo.png') ?>" 
            class="img-fluid mb-3" 
            style="max-height:120px; max-width:120px; display:block; margin:0 auto;">
        <h5 class="fw-bold text-white text-center mb-3">LATSAR USER</h5>
        <ul class="nav flex-column flex-grow-1 sidebar-menu">

    <!-- DASHBOARD -->
        <div class="sidebar-section">DASHBOARD</div>
            <li class="nav-item">
                <a class="nav-link <?= service('uri')->getSegment(1) == 'User' && service('uri')->getSegment(2) == 'dashboard' ? 'active-menu' : '' ?>" 
                href="/user/dashboard">
                <i class="fas fa-chart-line me-2"></i> Dashboard
                </a>
            </li>

            
            <!-- peta -->
            <li class="nav-item">
                        <a class="nav-link" href="/profiling">
                            <i class="fas fa-address-card me-2"></i> Profiling
                        </a>
                    </li>
        
    <!-- Provinsi -->
    <?php if ($level == 'provinsi'): ?>
    <div class="sidebar-section mt-3">PROVINSI</div>

    <li class="nav-item">
        <a class="nav-link" href="/user/provinsi/populasi">
            <i class="fas fa-chart-bar me-2"></i> Rekap Populasi
        </a>
    </li>
<?php endif; ?>

    <!-- DATA INPUT -->
        <?php if ($level == 'kabupaten'): ?>
            <div class="sidebar-section mt-3">DATA INPUT</div>

            <li class="nav-item">
                <a class="nav-link <?= service('uri')->getSegment(2) == 'statistik' ? 'active-menu' : '' ?>" 
                href="/user/upload">
                <i class="fas fa-chart-pie me-2"></i> Upload Data
                </a>
            </li>
            <!-- <li class="nav-item">
                <a class="nav-link" href="/user/datapokok">
                    <i class="fas fa-database me-2"></i> Data Pokok
                </a>
            </li> -->

            <!-- DATA INPUT -->
            <div class="sidebar-section mt-3">DATA TERSIMPAN</div>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('user/data-profilingdatatersimpan') ?>">
                    <i class="fas fa-save me-2"></i> profiling tersimpan
                    
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('user/data-infrastrukturtersimpan') ?>">
                    <i class="fas fa-save me-2"></i> Infrastruktur
                    
                </a>
            </li>
        <?php endif; ?>

    <!-- GIS -->
        <!-- <div class="sidebar-section mt-3">GIS</div>

        <li class="nav-item">
            <a class="nav-link" href="/infrastruktur">
                <i class="fas fa-map me-2"></i> -------------
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/profiling">
                <i class="fas fa-address-card me-2"></i> Profiling peta
            </a>
        </li> -->

        <!-- untuk KECAMATAN -->
        <!-- PENYULUH PERTANIAN -->
        <?php if ($level == 'kecamatan'): ?>

            <div class="sidebar-section mt-3">PENYULUH PERTANIAN</div>

            <li class="nav-item">
                <a class="nav-link" href="/user/pemotongan">
                    <i class="fas fa-cut me-2"></i> Input Pemotongan
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/user/populasi">
                    <i class="fas fa-database me-2"></i> Input Populasi
                </a>
            </li>

        <?php endif; ?>


        <!-- AKUN -->
        <?php if ($level == 'kabupaten'): ?>
        <div class="sidebar-section mt-3">Validasi penyuluh</div>
                <li class="nav-item">
                    <a class="nav-link" href="/user/kabupaten/populasi">
                        <i class="fas fa-check-circle me-2"></i> Validasi Populasi
                    </a>
                </li>

        <?php endif; ?>

    <!-- AKUN -->
        <div class="sidebar-section mt-3">AKUN</div>

        <li class="nav-item">

            <!-- PARENT -->
            <a class="nav-link d-flex justify-content-between align-items-center"
            data-bs-toggle="collapse"
            href="#menuUser"
            role="button">

                <span>
                    <i class="fas fa-user me-2"></i> Profil Saya
                </span>

                <i class="fas fa-chevron-down small"></i>
            </a>

            <!-- CHILD -->
            <div id="menuUser" class="collapse ms-3">

                <a class="nav-link py-2"
                href="<?= base_url('user/profile') ?>">
                    <i class="fas fa-id-card me-2"></i> Profil
                </a>

                <a class="nav-link py-2"
                href="<?= base_url('user/ganti-password') ?>">
                    <i class="fas fa-key me-2"></i> Ganti Password
                </a>

            </div>

        </li>

        <li class="nav-item">
            <a class="nav-link" href="/logout">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </li>

    </ul>

</div>
