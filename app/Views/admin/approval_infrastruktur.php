<?= $this->include('layout/admin/header') ?>
<?= $this->include('layout/admin/navbar') ?>
<?= $this->include('layout/sidebar/sidebar_admin') ?>

<!-- Tambahkan Inline Style untuk Fix Sidebar -->
<style>
    .main-content-wrapper {
        margin-left: 260px; /* Sesuaikan dengan lebar sidebar Anda */
        transition: all 0.3s;
        min-height: 100vh;
    }

    /* Responsif: Kalau layar HP, margin hilang */
    @media (max-width: 768px) {
        .main-content-wrapper {
            margin-left: 0;
            padding: 15px !important;
        }
    }

    .bg-success-subtle { background-color: #e6fcf5 !important; }
    .table thead th { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; }
    .table tbody tr:hover { background-color: #f8fff9 !important; }
</style>

<div class="main-content-wrapper p-4 bg-light">
    <!-- Header Section -->
    <div class="d-flex align-items-center justify-content-between mb-4 mt-2">
        <div>
            <h4 class="fw-bold text-dark mb-1">
                <i class="fas fa-check-circle text-success me-2"></i> Infrastruktur Terverifikasi
            </h4>
            <p class="text-muted small mb-0">Arsip data yang sudah disetujui oleh sistem.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-white shadow-sm border btn-sm px-3 rounded-pill">
                <i class="fas fa-print me-1"></i> Cetak
            </button>
            <button class="btn btn-success shadow-sm btn-sm px-3 rounded-pill fw-bold">
                <i class="fas fa-file-excel me-1"></i> Export Excel
            </button>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-dark text-white"> <!-- Pakai Dark agar kontras dengan data Hijau -->
                    <tr>
                        <th class="ps-4 py-3 border-0">Kategori</th>
                        <th class="py-3 border-0">Nama Lokasi</th>
                        <th class="py-3 border-0">Alamat</th>
                        <th class="py-3 border-0 text-center">Waktu Verifikasi</th>
                        <th class="py-3 border-0 text-center pe-4">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white text-nowrap">
                    <?php if (!empty($infrastruktur)): ?>
                        <?php foreach ($infrastruktur as $row): ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 fw-medium">
                                        <?= esc($row['jenis_infrastruktur']) ?>
                                    </span>
                                </td>
                                <td><span class="fw-bold text-dark"><?= esc($row['nama_tempat']) ?></span></td>
                                <td>
                                    <div class="text-muted small" title="<?= esc($row['alamat']) ?>">
                                        <i class="fas fa-map-pin me-1 text-danger"></i> 
                                        <?= (strlen(esc($row['alamat'])) > 40) ? substr(esc($row['alamat']), 0, 40) . '...' : esc($row['alamat']) ?>
                                    </div>
                                </td>
                                <td class="text-center text-secondary small">
                                    <i class="far fa-calendar-check me-1 text-primary"></i>
                                    <?= date('d/m/Y H:i', strtotime($row['verified_at'])) ?>
                                </td>
                                <td class="text-center pe-4">
                                    <span class="text-success small fw-bold">
                                        <i class="fas fa-check-double me-1"></i> Verified
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fas fa-inbox d-block fs-1 opacity-25 mb-3"></i>
                                <span class="text-muted">Belum ada data infrastruktur yang disetujui.</span>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->include('layout/admin/footer') ?>
