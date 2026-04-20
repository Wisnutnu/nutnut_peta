
<?= $this->include('layout/user/header') ?>
<?= $this->include('layout/user/navbar') ?>
<?= $this->include('layout/sidebar/sidebar_user') ?>

<!-- bagian 1 -->
<div class="main-container">
    <div class="container-fluid py-4">
        <div class="content-wrapper">
            <!-- pemberitahuan data duplikat -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <!--  -->
                <!-- TAB ATAS -->
                <ul class="nav nav-tabs mb-3">

                    <li class="nav-item">
                        <button class="nav-link active"
                                data-bs-toggle="tab"
                                data-bs-target="#profiling">
                            Profiling
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link"
                                data-bs-toggle="tab"
                                data-bs-target="#infrastruktur">
                            Infrastruktur PKH
                        </button>
                    </li>

                </ul>


<!-- ========================== -->
<!-- Profiling -->
<!-- ========================== -->
<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="profiling">
        
        <div class="row">
            <!-- SECTION 1: FORM UPLOAD (FULL WIDTH) -->
            <div class="col-12 mb-5">
                <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
                    <div class="card-header bg-primary p-4 border-0">
                        <h5 class="text-white mb-1 fw-bold"><i class="fas fa-file-import me-2"></i> Form Pengiriman Data</h5>
                        <p class="text-white-50 small mb-0">Silakan pilih kategori dan lengkapi data profiling di bawah ini.</p>
                    </div>
                    
                    <div class="card-body p-4">
                        <form id="formProfiling" method="post" action="<?= base_url('user/upload/populasi') ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="provinsi" value="<?= $provinsi ?>">
                            <input type="hidden" name="kab_kota" value="<?= $kab_kota ?>">
                            
                            <!-- Info Wilayah (Readonly Style) -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3 border shadow-sm">
                                        <label class="d-block small text-muted fw-bold mb-1">Provinsi</label>
                                        <span class="fw-bold text-dark"><?= esc($provinsi) ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3 border shadow-sm">
                                        <label class="d-block small text-muted fw-bold mb-1">Kabupaten / Kota</label>
                                        <span class="fw-bold text-dark"><?= esc($kab_kota) ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Pemilihan Kategori (Radio Button Modern) -->
                            <label class="fw-bold mb-3 d-block text-dark text-center">Pilih Kategori Data:</label>
                            <div class="row g-3 mb-4 justify-content-center">
                                <div class="col-6 col-md-3">
                                    <input type="radio" class="btn-check" name="kategori_select" id="opt-populasi" value="populasi" autocomplete="off">
                                    <label class="btn btn-outline-primary w-100 py-3 rounded-4 shadow-sm h-100 d-flex flex-column align-items-center justify-content-center" for="opt-populasi">
                                        <i class="fas fa-chart-bar mb-2 fs-3"></i>
                                        <span class="small fw-bold">Populasi</span>
                                    </label>
                                </div>
                                <div class="col-6 col-md-3">
                                    <input type="radio" class="btn-check" name="kategori_select" id="opt-produksi" value="produksi" autocomplete="off">
                                    <label class="btn btn-outline-success w-100 py-3 rounded-4 shadow-sm h-100 d-flex flex-column align-items-center justify-content-center" for="opt-produksi">
                                        <i class="fas fa-boxes mb-2 fs-3"></i>
                                        <span class="small fw-bold">Produksi</span>
                                    </label>
                                </div>
                                <div class="col-6 col-md-3">
                                    <input type="radio" class="btn-check" name="kategori_select" id="opt-harga" value="harga" autocomplete="off">
                                    <label class="btn btn-outline-warning w-100 py-3 rounded-4 shadow-sm h-100 d-flex flex-column align-items-center justify-content-center" for="opt-harga">
                                        <i class="fas fa-tags mb-2 fs-3"></i>
                                        <span class="small fw-bold">Harga</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Container untuk Form Dinamis -->
                            <div id="formContainer" class="p-4 bg-light rounded-4 border-dashed border-2 text-center">
                                <div class="py-3 text-muted">
                                    <i class="fas fa-mouse-pointer fa-2x mb-2 opacity-25"></i>
                                    <p class="mb-0">Pilih kategori di atas untuk memuat formulir input.</p>
                                </div>
                            </div>

                            <!-- Templates (Tetap tersembunyi) -->
                            <div id="tpl-populasi" style="display:none"><?= view('user/profiling/form_populasi', ['jenisTernak' => $jenisTernak]) ?></div>
                            <div id="tpl-produksi" style="display:none"><?= view('user/profiling/form_produksi', ['jenisProduksi' => $jenisProduksi]) ?></div>
                            <div id="tpl-harga" style="display:none"><?= view('user/profiling/form_harga', ['jenisHarga' => $jenisharga]) ?></div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: RIWAYAT (DI BAWAH) -->
            <div class="col-12 mt-4">
                <div class="d-flex align-items-center justify-content-between mb-4 px-2">
                    <h5 class="fw-bold text-dark mb-0"><i class="fas fa-history me-2 text-muted"></i> Riwayat Pengiriman</h5>
                    <span class="badge bg-white text-primary border rounded-pill px-3 shadow-sm py-2 small fw-bold">Total: <?= count($riwayat) ?> Data</span>
                </div>

                <?php if (empty($riwayat)): ?>
                    <div class="card border-0 shadow-sm rounded-4 text-center py-5 bg-white mb-5">
                        <p class="text-muted mb-0">Belum ada riwayat pengiriman data.</p>
                    </div>
                <?php else: ?>
                    <div class="row g-3 mb-5">
                        <?php foreach ($riwayat as $row): ?>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card border-0 shadow-sm rounded-4 h-100 hover-up transition-3 bg-white">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="rounded-4 p-3 me-3 
                                            <?= ($row['kategori'] == 'populasi') ? 'bg-info-subtle text-info' : (($row['kategori'] == 'produksi') ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning') ?>">
                                            <i class="fas <?= ($row['kategori'] == 'populasi') ? 'fa-chart-line' : (($row['kategori'] == 'produksi') ? 'fa-box-open' : 'fa-money-bill-wave') ?> fs-5"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0 fw-bold text-dark"><?= ucfirst($row['kategori']) ?></h6>
                                            <small class="text-muted d-block" style="font-size: 0.7rem;"><?= date('d/m/Y', strtotime($row['created_at'])) ?></small>
                                        </div>
                                        <div class="text-end">
                                            <?php if ($row['status'] == 'pending'): ?>
                                                <span class="badge bg-warning text-dark px-2 py-1 small">Pending</span>
                                            <?php elseif ($row['status'] == 'approved'): ?>
                                                <span class="badge bg-success text-white px-2 py-1 small">Approved</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger text-white px-2 py-1 small">Rejected</span>
                                            <?php endif ?>
                                        </div>
                                    </div>
                                    
                                    <p class="text-muted small mb-0 border-top pt-2">
                                        <strong>Detail:</strong> <?= esc($row['detail_data']) ?>
                                    </p>

                                    <?php if(!empty($row['catatan_admin'])): ?>
                                        <div class="bg-danger-subtle text-danger p-2 rounded-3 mt-2 small border-start border-3 border-danger">
                                            <i class="fas fa-comment-dots me-1"></i> <?= esc($row['catatan_admin']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach ?>
                    </div>
                <?php endif ?>

                <!-- Pager CI4 -->
                <?php if (isset($pager)): ?>
                    <div class="mt-4 pb-5 d-flex justify-content-center">
                        <?= $pager->links() ?>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>

        <style>
            .border-dashed { border-style: dashed !important; border-color: #dee2e6 !important; }
            .transition-3 { transition: all 0.2s ease; }
            .hover-up:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important; }
            
            /* CI4-Subtle Colors */
            .bg-info-subtle { background-color: #e7f5ff !important; color: #1098ad !important; }
            .bg-success-subtle { background-color: #ebfbee !important; color: #37b24d !important; }
            .bg-warning-subtle { background-color: #fff9db !important; color: #f08c00 !important; }
            .bg-danger-subtle { background-color: #fff5f5 !important; color: #f03e3e !important; }

            /* Custom Radio Buttons */
            .btn-check:checked + .btn-outline-primary { background-color: #0d6efd; color: white; border-color: #0d6efd; box-shadow: 0 4px 10px rgba(13,110,253,0.3); }
            .btn-check:checked + .btn-outline-success { background-color: #198754; color: white; border-color: #198754; box-shadow: 0 4px 10px rgba(25,135,84,0.3); }
            .btn-check:checked + .btn-outline-warning { background-color: #ffc107; color: black; border-color: #ffc107; box-shadow: 0 4px 10px rgba(255,193,7,0.3); }
        </style>

<script>
    // JS Sederhana untuk pindah template form
    document.querySelectorAll('input[name="kategori_select"]').forEach(radio => {
        radio.addEventListener('change', function() {
            
            const template = document.getElementById('tpl-' + this.value).innerHTML;
            
            container.innerHTML = template;
            container.classList.remove('text-center', 'border-dashed');
            container.classList.add('bg-white', 'text-start');
        });
    });
</script>


<!-- JS buat handle ganti form profiling bagian atas 1-->
<script>
document.querySelectorAll('input[name="kategori_select"]').forEach(radio => {
    radio.addEventListener('change', function() {

        const container = document.getElementById('formContainer'); // ✅ BENAR
        const template = document.getElementById('tpl-' + this.value).innerHTML;

        container.innerHTML = template;

        // update action form
        const form = document.getElementById('formProfiling');

        if (this.value === 'populasi') {
            form.action = "<?= base_url('user/upload/populasi') ?>";
        } 
        else if (this.value === 'produksi') {
            form.action = "<?= base_url('user/upload/produksi') ?>";
        } 
        else if (this.value === 'harga') {
            form.action = "<?= base_url('user/upload/harga') ?>";
        }

        // biar smooth scroll (optional tapi keren 😎)
        container.scrollIntoView({ behavior: 'smooth' });
    });
});
</script>

<!-- fungsi untuk mengubah label jumlah produksi sesuai jenis produksi -->
<script>
const mapSatuan = {
    "Daging Sapi": "kg",
    "Daging Kerbau": "kg",
    "Daging Kambing": "kg",
    "Daging Domba": "kg",
    "Daging Kuda": "kg",
    "Daging Kelinci": "kg",
    "Daging Babi": "kg",
    "Daging Ayam Buras": "kg",
    "Daging Ayam Ras Pedaging": "kg",
    "Daging Ayam Ras Petelur": "kg",
    "Daging Itik": "kg",
    "Daging Itik Manila": "kg",
    "Daging Puyuh": "kg",
    "Daging Sapi Lokal": "kg",
    "Daging Sapi ex-Impor": "kg",

    "Telur Ayam Ras": "butir",
    "Telur Ayam Buras": "butir",
    "Telur Itik": "butir",
    "Telur Itik Manila": "butir",
    "Telur Puyuh": "butir",

    "Susu Sapi": "liter",
    "Susu Kambing": "liter",
    "Susu Kerbau": "liter"
};

function bindProduksiSatuan() {
    const jenisProduksi = document.getElementById('jenisProduksi');
    const labelJumlah = document.getElementById('label-jumlah');

    if (!jenisProduksi || !labelJumlah) return;

    function updateLabel() {
    const satuan = mapSatuan[jenisProduksi.value] || '-';
    document.getElementById('satuan-text').innerHTML = `<strong>${satuan}</strong>`;
}


    // 🔥 jalan saat ganti dropdown
    jenisProduksi.addEventListener('change', updateLabel);

    // 🔥 jalan saat form pertama kali muncul
    updateLabel();
}
</script>

    <script>
        function formatID(value) {
            value = value.replace(/[^\d]/g, '');
            return value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
    </script>



<!-- ======================= -->
<!-- TAB INFRASTRUKTUR -->
 <!-- ========================== -->
    <div class="tab-pane fade" id="infrastruktur">
    <div class="container-fluid px-0">
        <form action="<?= base_url('user/upload/saveInfrastruktur') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="provinsi" value="<?= $provinsi ?>">
            <input type="hidden" name="kab_kota" value="<?= $kab_kota ?>">

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <!-- Header Card dengan Gradasi Smooth -->
                <div class="card-header bg-primary py-3 border-0" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-25 rounded-3 p-2 me-3">
                            <i class="bi bi-geo-fill text-white fs-4"></i>
                        </div>
                        <div>
                            <h5 class="text-white mb-0 fw-bold">Manajemen Infrastruktur</h5>
                            <p class="text-white text-opacity-75 mb-0 small">Input data lokasi dan fasilitas PKH</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-lg-5">
                    <div class="row">
                        <!-- Sisi Kiri: Informasi Lokasi & Jenis -->
                        <div class="col-lg-6 pe-lg-4 border-end">
                            <div class="row g-3">
                                <div class="col-12 mb-3">
                                    <h6 class="fw-bold text-uppercase small text-primary mb-3">1. Informasi Wilayah</h6>
                                    <div class="p-3 bg-light rounded-3">
                                        <div class="row">
                                            <div class="col-6">
                                                <label class="small text-muted d-block">Provinsi</label>
                                                <span class="fw-bold"><?= $provinsi ?></span>
                                            </div>
                                            <div class="col-6">
                                                <label class="small text-muted d-block">Kab/Kota</label>
                                                <span class="fw-bold text-truncate d-block"><?= $kab_kota ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold text-secondary small">Jenis Infrastruktur</label>
                                    <select name="jenis_infrastruktur" class="form-select border-0 bg-light py-2 px-3 shadow-none focus-ring focus-ring-primary" required style="border-radius: 10px;">
                                        <option value="" disabled selected>Pilih jenis fasilitas...</option>
                                        <option value="klinikhewan">🏥 Klinik Hewan</option>
                                        <option value="koperasipkh">🤝 Koperasi PKH</option>
                                        <option value="labbibit">🧪 Lab Bibit</option>
                                        <option value="labkeswan">🔬 Lab Keswan</option>
                                        <option value="labkesmavet">🔍 Lab Kes Mavet</option>
                                        <option value="labpakan">🌿 Lab Pakan</option>
                                        <option value="pasarternak">🐄 Pasar Ternak</option>
                                        <option value="puskeswan">🩺 Puskeswan</option>
                                        <option value="Rumah Potong Hewan">🥩 RPH</option>
                                        <option value="sppg">🏗️ SPPG</option>
                                        <option value="uph">🏭 UPH</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold text-secondary small">Nama Tempat</label>
                                    <input type="text" name="nama" class="form-control border-0 bg-light py-2 px-3 shadow-none" placeholder="Masukkan nama tempat/fasilitas" required style="border-radius: 10px;">
                                </div>
                            </div>
                        </div>

                        <!-- Sisi Kanan: Alamat & Titik Koordinat -->
                        <div class="col-lg-6 ps-lg-4 mt-4 mt-lg-0">
                            <h6 class="fw-bold text-uppercase small text-primary mb-3">2. Detail Alamat & Koordinat</h6>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary small">Alamat Lengkap</label>
                                <textarea name="alamat" class="form-control border-0 bg-light py-2 px-3 shadow-none" rows="2" placeholder="Tuliskan alamat lengkap..." required style="border-radius: 10px;"></textarea>
                            </div>

                            <div class="p-4 bg-light rounded-4 border">
                                <div class="mb-3">
                                    <h6 class="fw-bold text-secondary text-uppercase small mb-3">
                                        <i class="bi bi-geo-alt-fill me-1"></i> Titik Koordinat (GPS)
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-floating shadow-sm">
                                                <input type="text" name="latitude" id="lat-input" class="form-control border-0 px-3" placeholder="Latitude" style="border-radius: 12px; font-weight: 600;">
                                                <label class="text-muted">Latitude</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating shadow-sm">
                                                <input type="text" name="longitude" id="long-input" class="form-control border-0 px-3" placeholder="Longitude" style="border-radius: 12px; font-weight: 600;">
                                                <label class="text-muted">Longitude</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tombol Deteksi Lokasi Diperbesar & Di Bawah -->
                                <div class="d-grid mt-3">
                                    <button type="button" class="btn btn-info btn-lg text-white shadow-sm rounded-3 py-3" onclick="getLocation()" style="transition: all 0.3s ease;">
                                        <i class="bi bi-crosshair2 fs-5 me-2"></i> 
                                        <span class="fw-bold" style="letter-spacing: 0.5px;">DETEKSI LOKASI OTOMATIS</span>
                                    </button>
                                    <div class="text-center mt-2">
                                        <small class="text-muted" style="font-size: 13px;">
                                            <i class="bi bi-info-circle"></i> Pastikan GPS HP/Laptop dalam posisi <strong>Aktif</strong> untuk akurasi data.
                                        </small>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- Footer Action -->
                <div class="card-footer bg-white p-4 border-top-0 d-flex justify-content-end">
                    <button type="reset" class="btn btn-link text-decoration-none text-muted me-3">Reset</button>
                    <button type="submit" class="btn btn-primary px-5 py-2 shadow rounded-pill fw-bold">
                        Simpan Infrastruktur <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </div>
            </form>




            <!--  bagian 2 riwayat -->
                <div class="card border-0 shadow-sm rounded-4 mt-4 overflow-hidden">
    <!-- Header Riwayat -->
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
        <h6 class="mb-0 fw-bold text-dark">
            <i class="bi bi-clock-history me-2 text-primary"></i> Riwayat Pengajuan Infrastruktur
        </h6>
        <span class="badge bg-primary-subtle text-primary rounded-pill px-3">Total: <?= count($riwayatinfrastruktur ?? []) ?> Data</span>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="min-width: 800px;">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase small fw-bold text-secondary border-0">Fasilitas</th>
                        <th class="py-3 text-uppercase small fw-bold text-secondary border-0">Alamat & Lokasi</th>
                        <th class="py-3 text-uppercase small fw-bold text-secondary text-center border-0">Status</th>
                        <th class="py-3 text-uppercase small fw-bold text-secondary text-center border-0">Aksi</th>
                        <th class="pe-4 py-3 text-uppercase small fw-bold text-secondary border-0">Catatan Admin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($riwayatinfrastruktur)): ?>
                        <?php foreach ($riwayatinfrastruktur as $row): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark"><?= esc($row['nama_tempat'] ?? '-') ?></div>
                                    <div class="badge bg-secondary-subtle text-secondary small fw-normal">
                                        <?= esc($row['jenis_infrastruktur'] ?? '-') ?>
                                    </div>
                                </td>
                                <td style="max-width: 300px;">
                                    <div class="text-truncate small text-muted mb-1"><?= esc($row['alamat'] ?? '-') ?></div>
                                    <?php if(!empty($row['latitude'])): ?>
                                        <a href="https://google.com<?= $row['latitude'] ?>,<?= $row['longitude'] ?>" target="_blank" class="badge bg-info-subtle text-info text-decoration-none small">
                                            <i class="bi bi-map me-1"></i> Lihat Map
                                        </a>
                                    <?php endif; ?>
                                </td>

                                <!-- STATUS -->
                                <td class="text-center">
                                    <?php 
                                        $status = $row['status'] ?? 'pending';
                                        if ($status == 'pending'): ?>
                                        <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle px-3">
                                            <i class="bi bi-hourglass-split me-1"></i> Menunggu
                                        </span>
                                    <?php elseif ($status == 'approved'): ?>
                                        <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3">
                                            <i class="bi bi-check-circle-fill me-1"></i> Disetujui
                                        </span>
                                    <?php else: ?>
                                        <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle px-3">
                                            <i class="bi bi-x-circle-fill me-1"></i> Ditolak
                                        </span>
                                    <?php endif ?>
                                </td>

                                <!-- AKSI -->
                                <td class="text-center px-3">
                                    <?php if (($row['status'] ?? 'pending') == 'pending'): ?>
                                        <!-- Tombol dengan Teks agar User Langsung Paham -->
                                        <div class="d-flex gap-1 justify-content-center">
                                            <button type="button" 
                                                class="btn btn-sm btn-warning btn-edit text-white fw-bold px-3"
                                                data-id="<?= $row['id'] ?>"
                                                data-jenis="<?= $row['jenis_infrastruktur'] ?>"
                                                data-nama="<?= $row['nama_tempat'] ?>"
                                                data-alamat="<?= $row['alamat'] ?>"
                                                data-lat="<?= $row['latitude'] ?>"
                                                data-lng="<?= $row['longitude'] ?>"
                                                style="border-radius: 6px;">
                                                <i class="bi bi-pencil-square me-1"></i> Edit
                                            </button>

                                            <a href="<?= base_url('user/upload/deleteInfrastruktur/'.$row['id']) ?>" 
                                            class="btn btn-sm btn-danger fw-bold px-3"
                                            onclick="return confirm('Yakin hapus data ini?')"
                                            style="border-radius: 6px;">
                                                <i class="bi bi-trash-fill me-1"></i> Hapus
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <!-- Tampilan Terkunci yang Lebih Bagus daripada sekedar strip -->
                                        <div class="text-secondary opacity-75">
                                            <i class="bi bi-lock-fill me-1"></i>
                                            <span class="small fw-bold text-uppercase" style="font-size: 10px;">Data Terkunci</span>
                                        </div>
                                    <?php endif ?>
                                </td>


                                <!-- BAGIAN CATATAN ADMIN (FIXED) -->
                                <td class="pe-4 small text-muted">
                                    <?php if (!empty($row['catatan_admin'])): ?>
                                        <div class="d-flex align-items-start">
                                            <i class="bi bi-chat-left-dots me-2 text-primary"></i>
                                            <span><?= esc($row['catatan_admin']) ?></span>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-opacity-50">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="py-5 text-center text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                                Belum ada data infrastruktur yang diajukan.
                            </td>
                        </tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <?php if (isset($pager) && $pager): ?>
        <div class="card-footer bg-white py-3 border-top-0">
            <div class="d-flex justify-content-center">
                <?= $pager->links() ?>
            </div>
        </div>
    <?php endif ?>
</div>

        </div>
    </div>
    </div>

<!-- popup edit -->
<div class="modal fade" id="modalEdit" tabindex="-1">
  <div class="modal-dialog">
    <form method="post" id="formEdit">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title">Edit Infrastruktur</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="mb-3">
            <label>Jenis</label>
            <select name="jenis_infrastruktur" id="edit_jenis" class="form-control">
                <option value="klinikhewan">Klinik Hewan</option>
                <option value="koperasipkh">Koperasi PKH</option>
                <option value="labbibit">Lab Bibit</option>
                <option value="labkeswan">Lab Keswan</option>
                <option value="labkesmavet">Lab Kes Mavet</option>
                <option value="labpakan">Lab Pakan</option>
                <option value="pasarternak">Pasar Ternak</option>
                <option value="puskeswan">Puskeswan</option>
                <option value="rph">RPH</option>
                <option value="sppg">SPPG</option>
                <option value="uph">UPH</option>
            </select>
          </div>

          <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="nama" id="edit_nama" class="form-control">
          </div>

          <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat" id="edit_alamat" class="form-control"></textarea>
          </div>

          <div class="mb-3">
            <label>Latitude</label>
            <input type="text" name="latitude" id="edit_lat" class="form-control">
          </div>

          <div class="mb-3">
            <label>Longitude</label>
            <input type="text" name="longitude" id="edit_lng" class="form-control">
          </div>

        </div>

        <div class="modal-footer">
          <button class="btn btn-primary">Update</button>
        </div>

      </div>
    </form>
  </div>
</div>

<script>
document.querySelectorAll('.btn-edit').forEach(btn => {

    btn.addEventListener('click', function() {

        let id = this.dataset.id;

        document.getElementById('edit_jenis').value = this.dataset.jenis;
        document.getElementById('edit_nama').value = this.dataset.nama;
        document.getElementById('edit_alamat').value = this.dataset.alamat;
        document.getElementById('edit_lat').value = this.dataset.lat;
        document.getElementById('edit_lng').value = this.dataset.lng;

        // set action form
        document.getElementById('formEdit').action =
            "<?= base_url('user/upload/updateInfrastruktur') ?>/" + id;

        new bootstrap.Modal(document.getElementById('modalEdit')).show();

    });

});

// pilih langsung lokasinya
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {

            document.querySelector('[name=latitude]').value =
                position.coords.latitude;

            document.querySelector('[name=longitude]').value =
                position.coords.longitude;

        }, function(error) {
            alert('Gagal ambil lokasi: ' + error.message);
        });
    } else {
        alert("Browser tidak mendukung lokasi");
    }
}
</script>

<?= $this->include('layout/user/footer') ?>
