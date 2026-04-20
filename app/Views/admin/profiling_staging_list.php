<?= $this->include('layout/admin/header') ?>
<?= $this->include('layout/admin/navbar') ?>
<?= $this->include('layout/sidebar/sidebar_admin') ?>

<!-- MAIN CONTENT -->
<div class="main-container p-4">
    <div class="content-wrapper">
    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <ul class="nav nav-tabs mb-3" id="approvalTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active"
                            id="profiling-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#profiling"
                            type="button"
                            role="tab">
                        Profiling
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link"
                            id="infrastruktur-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#infrastruktur"
                            type="button"
                            role="tab">
                        Infrastruktur
                    </button>
                </li>
            </ul>
            <!-- Tab Pane Profiling -->
<div class="tab-pane fade show active" id="profiling" role="tabpanel">
    
    <!-- FILTER SECTION: Header & Form -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3 text-dark"><i class="fas fa-filter text-primary me-2"></i> Filter Pencarian</h5>
            <form method="get" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Kabupaten</label>
                    <div class="input-group border rounded shadow-sm">
                        <span class="input-group-text bg-white border-0"><i class="fas fa-map-marker-alt text-muted"></i></span>
                        <input type="text" name="kabupaten" value="<?= $_GET['kabupaten'] ?? '' ?>" class="form-control border-0 ps-0" placeholder="Cari Kabupaten...">
                    </div>
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">Kategori</label>
                    <select name="kategori" class="form-select border rounded shadow-sm">
                        <option value="">Semua Kategori</option>
                        <?php foreach($kategoriList as $k): ?>
                            <option value="<?= $k['kategori'] ?>" <?= ($_GET['kategori'] ?? '') == $k['kategori'] ? 'selected' : '' ?>>
                                <?= ucfirst($k['kategori']) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">Status</label>
                    <select name="status" class="form-select border rounded shadow-sm">
                        <option value="all">Semua Status</option>
                        <option value="pending" <?= ($_GET['status'] ?? '') == 'pending' ? 'selected' : '' ?>>🟡 Pending</option>
                        <option value="approved" <?= ($_GET['status'] ?? '') == 'approved' ? 'selected' : '' ?>>🟢 Approved</option>
                        <option value="rejected" <?= ($_GET['status'] ?? '') == 'rejected' ? 'selected' : '' ?>>🔴 Rejected</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">Tanggal Input</label>
                    <input type="date" name="tanggal" value="<?= $_GET['tanggal'] ?? '' ?>" class="form-control border rounded shadow-sm">
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4 shadow-sm flex-grow-1 fw-bold">
                        <i class="fas fa-search me-1"></i> Cari Data
                    </button>
                    <a href="<?= current_url() ?>" class="btn btn-outline-secondary shadow-sm">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- TABLE SECTION -->
    <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
        <div class="card-header bg-white py-3 px-4 border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">Data Yang Diupload</h5>
                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">Total: <?= count($list) ?> Data</span>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary">
                    <tr>
                        <th class="ps-4" width="80">ID</th>
                        <th>Kabupaten</th>
                        <th>Kategori</th>
                        <th>Detail Analisis</th>
                        <th class="text-center">Jumlah Baris</th>
                        <th class="text-center">Status</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <?php foreach($list as $row): ?>
                        <?php
                        $data = json_decode($row['data_json'], true);
                        $first = $data[0] ?? [];
                        $detail = '-';
                        
                        if ($row['kategori'] == 'populasi') {
                            $detail = '<span class="badge bg-info-subtle text-info p-2 rounded"><i class="fas fa-cow me-1"></i> ' . ($first[3] ?? '-') . ' (' . ($first[4] ?? '-') . ')</span>';
                        } elseif ($row['kategori'] == 'produksi') {
                            $detail = '<span class="badge bg-success-subtle text-success p-2 rounded"><i class="fas fa-drumstick-bite me-1"></i> ' . ($first[3] ?? '-') . ' (' . ($first[4] ?? '-') . ')</span>';
                        } elseif ($row['kategori'] == 'harga') {
                            $detail = '<span class="badge bg-warning-subtle text-warning p-2 rounded"><i class="fas fa-tag me-1"></i> ' . ($first[3] ?? '-') . ' - ' . ($first[4] ?? '-') . '</span>';
                        }
                        ?>
                        <tr class="<?= $row['status'] == 'pending' ? 'bg-light-warning' : '' ?>">
                            <td class="ps-4 fw-bold text-muted">#<?= $row['id'] ?></td>
                            <td>
                                <div class="fw-bold text-dark"><?= esc($row['kabupaten']) ?></div>
                            </td>
                            <td>
                                <span class="fw-semibold text-secondary"><?= ucfirst($row['kategori']) ?></span>
                            </td>
                            <td><?= $detail ?></td>
                            <td class="text-center">
                                <span class="fw-bold px-2 py-1 bg-light rounded border small"><?= $row['jumlah_row'] ?> Row</span>
                            </td>
                            <td class="text-center">
                                <?php if($row['status'] == 'pending'): ?>
                                    <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning px-3 py-2">Pending</span>
                                <?php elseif($row['status'] == 'approved'): ?>
                                    <span class="badge rounded-pill bg-success-subtle text-success border border-success px-3 py-2">Approved</span>
                                <?php else: ?>
                                    <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger px-3 py-2">Rejected</span>
                                <?php endif ?>
                            </td>
                            <td class="text-center pe-4">
                                <button class="btn btn-outline-primary btn-sm rounded-circle shadow-sm" 
                                        onclick="openPreview(<?= $row['id'] ?>)" title="Preview Data">
                                    <i class="fas fa-search"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .bg-light-warning { background-color: #fffdf5 !important; }
    .bg-info-subtle { background-color: #e7f5ff !important; color: #1098ad !important; }
    .bg-success-subtle { background-color: #ebfbee !important; color: #37b24d !important; }
    .bg-warning-subtle { background-color: #fff9db !important; color: #f08c00 !important; }
    .bg-danger-subtle { background-color: #fff5f5 !important; color: #f03e3e !important; }
    .table thead th { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
</style>


                <!-- MODAL PREVIEW -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            
            <!-- Header dengan Gradasi Lembut -->
            <div class="modal-header bg-light border-bottom-0 py-3 px-4">
                <div class="d-flex align-items-center">
                    <div class="bg-primary text-white rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fas fa-search-plus"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0">Preview & Verifikasi Data</h5>
                        <small class="text-muted">Periksa kembali data sebelum melakukan tindakan approval</small>
                    </div>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body p-4 bg-white">
                <!-- Info Summary Singkat -->
                <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-4" style="border-radius: 12px;">
                    <i class="fas fa-info-circle me-3 fs-4"></i>
                    <div>
                        <strong>Petunjuk:</strong> Pastikan seluruh format kolom sudah sesuai. Klik tombol <b>Download</b> di bawah jika ingin memeriksa file Excel aslinya secara manual.
                    </div>
                </div>

                <!-- Area Preview Content dengan Border Halus -->
                <div class="border rounded-4 overflow-hidden shadow-sm bg-light">
                    <div id="previewContent" class="p-3 min-vh-50 text-center">
                        <div class="py-5">
                            <div class="spinner-border text-primary" role="status"></div>
                            <p class="mt-2 text-muted fw-bold">Sedang memuat data...</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer dengan Area Catatan yang Menonjol -->
            <div class="modal-footer bg-light border-top-0 p-4 pt-2">
                <div class="container-fluid p-0">
                    <div class="row g-3 align-items-center">
                        <!-- Tombol Download di Kiri -->
                        <div class="col-md-3">
                            <a id="downloadBtn" class="btn btn-outline-primary w-100 fw-bold rounded-pill shadow-sm">
                                <i class="fas fa-file-download me-2"></i> File Excel Asli
                            </a>
                        </div>
                        
                        <!-- Area Catatan Admin di Tengah -->
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-pen"></i></span>
                                <textarea id="catatan_admin" 
                                    class="form-control border-start-0 shadow-none" 
                                    rows="1"
                                    placeholder="Tulis alasan penolakan atau catatan tambahan..."
                                    style="resize: none;"></textarea>
                            </div>
                        </div>
                        
                        <!-- Tombol Aksi di Kanan -->
                        <div class="col-md-4">
                            <div class="d-flex gap-2">
                                <button class="btn btn-danger flex-grow-1 fw-bold rounded-pill shadow-sm py-2" onclick="rejectData()">
                                    <i class="fas fa-times-circle me-1"></i> Reject
                                </button>
                                <button class="btn btn-success flex-grow-1 fw-bold rounded-pill shadow-sm py-2" onclick="approveData()">
                                    <i class="fas fa-check-circle me-1"></i> Approve
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<style>
    /* Bikin preview table di dalam modal jadi cantik */
    #previewContent table {
        background-color: white;
        font-size: 0.9rem;
    }
    #previewContent table thead th {
        background-color: #f8f9fa;
        color: #333;
        font-weight: 700;
        border-bottom: 2px solid #dee2e6;
    }
    .modal-xl {
        max-width: 90% !important; /* Biar lebih lega pas liat data Excel */
    }
</style>

                    <!--  -->
                </div>
        </div>
</div>
</div>
</div>

<!-- ini scrip buat pengaturan jalur profiling -->
<script>
    let currentPreviewId = null;

// filter status
function filterStatus(status) {
    document.querySelectorAll('tbody tr').forEach(row => {
        if (!status || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
// preview staging
function openPreview(id) {

    currentPreviewId = id; // simpan id aktif

    fetch(`/admin/profiling/staging/preview/${id}`)
        .then(res => res.json())
        .then(res => {

            if (!res.status) {
                alert(res.message);
                return;
            }

            document.getElementById('downloadBtn').href =
                `/admin/staging/download/${id}`;

            let html = `
                <p><b>Kategori:</b> ${res.kategori}</p>
                <p><b>Jumlah Row:</b> ${res.jumlah_row}</p>
                <hr>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Provinsi</th>
                            <th>Kab/Kota</th>
                            <th>Data 1</th>
                            <th>Data 2</th>
                            <th>Data 3</th>
                            <th>Data 4</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            res.data.forEach(row => {
                html += `
                    <tr>
                        <td>${row[1]}</td>
                        <td>${row[2]}</td>
                        <td>${row[3]}</td>
                        <td>${row[4]}</td>
                        <td>${row[5]}</td>
                        <td>${row[6]}</td>
                    </tr>
                `;
            });

            html += '</tbody></table>';

            document.getElementById('previewContent').innerHTML = html;
                
            // ================= CATATAN ADMIN =================
                if (res.catatan_admin) {
                    const noteHtml = `
                        <div class="alert alert-info mt-3">
                            <b>📝 Catatan Admin (${res.status_data}):</b><br>
                            ${res.catatan_admin}
                        </div>
                    `;
                    document.getElementById('previewContent').innerHTML += noteHtml;
                }

            let modal = new bootstrap.Modal(
                document.getElementById('previewModal')
            );
            modal.show();

        });
}


// catatan_admin pada approve/reject
function approveData() {

    if (!currentPreviewId) return;

    fetch(`/admin/profiling/staging/approve/${currentPreviewId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken
        }
    })
    .then(res => res.json())
    .then(res => {
        alert(res.message);
        location.reload();
    });
}

function rejectData() {

    if (!currentPreviewId) return;

    const catatan = document.getElementById('catatan_admin').value;

    if (!catatan.trim()) {
        alert('Catatan wajib diisi jika Reject!');
        return;
    }

    fetch(`/admin/profiling/staging/reject/${currentPreviewId}`, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': window.csrfToken
    },
    body: JSON.stringify({
        catatan_admin: catatan
    })
})
    .then(res => res.json())
    .then(res => {
        alert(res.message);
        location.reload();
    });
}


</script>





<?= view('layout/master/footer') ?>