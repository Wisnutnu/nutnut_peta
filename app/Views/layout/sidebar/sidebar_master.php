
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

<!-- SIDEBAR MASTER -->
<div class="sidebar d-flex flex-column">

    <img src="<?= base_url('images/logo.png') ?>" 
         class="img-fluid mb-3" 
         style="max-height:120px; max-width:120px; display:block; margin:0 auto;">

    <h5 class="fw-bold text-white text-center mb-3">LATSAR MASTER</h5>

    <ul class="nav flex-column flex-grow-1 sidebar-menu">

        <!-- MONITORING NASIONAL -->
        <div class="sidebar-section">MONITORING NASIONAL</div>

<li class="nav-item">
    <a class="nav-link <?= service('uri')->getSegment(1) == 'master' && service('uri')->getSegment(2) == null ? 'active-menu' : '' ?>" 
    href="/master">
    <i class="fas fa-chart-line me-2"></i> Dashboard Nasional
    </a>
</li>

<li class="nav-item">
    <a class="nav-link <?= service('uri')->getSegment(2) == 'statistik' ? 'active-menu' : '' ?>" 
    href="/master/statistik">
    <i class="fas fa-chart-pie me-2"></i> Statistik Upload
    </a>
</li>

<li class="nav-item">
    <a class="nav-link <?= service('uri')->getSegment(2) == 'log' ? 'active-menu' : '' ?>" 
    href="/master/log">
    <i class="fas fa-file-alt me-2"></i> Log Aktivitas
    </a>
</li>

        <!-- SISTEM CONTROL -->
        <div class="sidebar-section mt-3">SISTEM CONTROL</div>

<li class="nav-item">
    <a class="nav-link <?= service('uri')->getSegment(2) == 'users' ? 'active-menu' : '' ?>" 
    href="/master/users">
    <i class="fas fa-users me-2"></i> Manajemen User
    </a>
</li>

<li class="nav-item">
    <a class="nav-link <?= service('uri')->getSegment(2) == 'roles' ? 'active-menu' : '' ?>" 
    href="/master/roles">
    <i class="fas fa-file-alt me-2"></i> Roles & hak akses
    </a>
</li>

        <li class="nav-item">
                <a class="nav-link <?= service('uri')->getSegment(2) == 'security' ? 'active-menu' : '' ?>" 
                href="/master/security">
                <i class="fas fa-lock me-2"></i> Keamanan Sistem
            </a>
        </li>

        <!-- TAMPILAN SISTEM -->
        <div class="sidebar-section mt-3">TAMPILAN SISTEM</div>

        <li class="nav-item">
                <a class="nav-link <?= service('uri')->getSegment(2) == 'theme' ? 'active-menu' : '' ?>" 
                href="/master/theme">
                <i class="fas fa-palette me-2"></i> Tema & Warna
            </a>
        </li>

        <li class="nav-item">
                <a class="nav-link <?= service('uri')->getSegment(2) == 'branding' ? 'active-menu' : '' ?>" 
                href="/master/branding">
                <i class="fas fa-image me-2"></i> Branding
            </a>
        </li>

        <li class="nav-item">
                <a class="nav-link <?= service('uri')->getSegment(2) == 'layout' ? 'active-menu' : '' ?>" 
                href="/master/layout">
                <i class="fas fa-layer-group me-2"></i> Layout & Header
            </a>
        </li>

        <!-- DATA CONTROL -->
        <div class="sidebar-section mt-3">DATA CONTROL</div>

        <li class="nav-item">
            <a class="nav-link <?= service('uri')->getSegment(2) == 'kategori' ? 'active-menu' : '' ?>" 
            href="/master/kategori">
                <i class="fas fa-tags me-2"></i> Master Kategori
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= service('uri')->getSegment(2) == 'backupdanrestore' ? 'active-menu' : '' ?>" 
            href="/master/backupdanrestore">
                <i class="fas fa-database me-2"></i> Backup & Restore
            </a>
        </li>

    </ul>

</div>
