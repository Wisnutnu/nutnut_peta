<?= $this->include('layout/admin/header') ?>
<?= $this->include('layout/admin/navbar') ?>
<?= $this->include('layout/sidebar/sidebar_admin') ?>

<!-- MAIN CONTENT -->
<div class="main-container p-4">

    <!-- ALERT -->
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

    <div class="content-wrapper">
    <div class="card mb-3 shadow-sm">
        <div class="card-body bg-light-subtle">
    <!-- Nav Tabs -->
    <ul class="nav nav-pills mb-4 gap-2" id="approvalTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold px-4 shadow-sm" id="infrastruktur-tab" data-bs-toggle="tab" data-bs-target="#infrastruktur" type="button" role="tab">
                <i class="fas fa-building me-2"></i> Infrastruktur
            </button>
        </li>
    </ul>

    <div class="tab-content" id="approvalTabContent">
        <div class="tab-pane fade show active" id="infrastruktur" role="tabpanel">

            <!-- FILTER CARD -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <form method="get" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted">Cari Kabupaten</label>
                            <div class="input-group shadow-sm border rounded">
                                <span class="input-group-text bg-white border-0"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" name="kabupaten" value="<?= $_GET['kabupaten'] ?? '' ?>" class="form-control border-0 ps-0" placeholder="Contoh: Malang">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold text-muted">Status Approval</label>
                            <select name="status" class="form-select shadow-sm border rounded">
                                <option value="all">Semua Status</option>
                                <option value="pending" <?= ($_GET['status'] ?? '') == 'pending' ? 'selected' : '' ?>>🟡 Pending</option>
                                <option value="approved" <?= ($_GET['status'] ?? '') == 'approved' ? 'selected' : '' ?>>🟢 Approved</option>
                                <option value="rejected" <?= ($_GET['status'] ?? '') == 'rejected' ? 'selected' : '' ?>>🔴 Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold text-muted">Tanggal Input</label>
                            <input type="date" name="tanggal" value="<?= $_GET['tanggal'] ?? '' ?>" class="form-control shadow-sm border rounded">
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4 shadow-sm flex-grow-1 fw-bold">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="<?= current_url() ?>" class="btn btn-outline-secondary shadow-sm" title="Reset">
                                <i class="fas fa-sync-alt"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- TABLE CARD -->
            <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="ps-4 py-3 border-0">Wilayah</th>
                                <th class="py-3 border-0">Jenis</th>
                                <th class="py-3 border-0">Nama Tempat</th>
                                <th class="py-3 border-0">Alamat</th>
                                <th class="py-3 border-0 text-center">Status</th>
                                <th class="py-3 border-0 text-center" width="180">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                        <?php if (!empty($infrastruktur)): ?>
                            <?php foreach ($infrastruktur as $row): ?>
                                <tr>
                                    <!-- Kolom Kabupaten Baru -->
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle p-2 me-2 text-primary">
                                                <i class="fas fa-map-marked-alt small"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">
                                                    <?= esc($row['kab_kota'] ?? 'N/A') ?>
                                                </div>
                                                <small class="text-muted">
                                                    <?= esc($row['provinsi'] ?? '') ?>
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <!--  -->
                                    
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 fw-medium">
                                            <?= esc($row['jenis_infrastruktur']) ?>
                                        </span>
                                    </td>
                                    <td><span class="text-dark fw-semibold"><?= esc($row['nama_tempat']) ?></span></td>
                                    <td>
                                        <div class="text-muted small text-truncate" style="max-width: 200px;" title="<?= esc($row['alamat']) ?>">
                                            <i class="fas fa-location-arrow text-danger me-1 small"></i> 
                                            <?= esc($row['alamat']) ?>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($row['status'] == 'pending'): ?>
                                            <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning px-3 py-2">Pending</span>
                                        <?php elseif ($row['status'] == 'approved'): ?>
                                            <span class="badge rounded-pill bg-success-subtle text-success border border-success px-3 py-2">Approved</span>
                                        <?php else: ?>
                                            <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger px-3 py-2">Rejected</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center pe-4">
                                        <div class="btn-group shadow-sm border rounded bg-white">
                                            <form action="<?= base_url('admin/staging/infrastruktur/approve/'.$row['id']) ?>" 
                                                method="post" 
                                                style="display:inline;">
                                                <?= csrf_field() ?>
                                                <button class="btn btn-white btn-sm text-success fw-bold border-end"
                                                        onclick="return confirm('Setujui data ini?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <button class="btn btn-white btn-sm text-danger fw-bold btn-reject"
                                                    data-id="<?= $row['id'] ?>">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-folder-open d-block fs-2 mb-2 opacity-25"></i>
                                    Data kabupaten yang Anda cari tidak ditemukan.
                                </td>
                            </tr>
                        <?php endif ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-primary-subtle { background-color: #eef5ff !important; }
    .bg-warning-subtle { background-color: #fffaf0 !important; }
    .bg-success-subtle { background-color: #f0fff4 !important; }
    .bg-danger-subtle { background-color: #fff5f5 !important; }
    .table thead th { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; }
    .btn-group .btn:hover { background-color: #f8f9fa; }
    .text-truncate { cursor: help; }
</style>


            <!-- MODAL REJECT -->
                    <div class="modal fade" id="modalReject" tabindex="-1">
                        <div class="modal-dialog">
                            <form method="post" id="formReject">
                            <?= csrf_field() ?>

                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title">Alasan Penolakan</h5>
                                        <button type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <textarea name="keterangan"
                                                class="form-control"
                                                placeholder="Masukkan alasan penolakan..."
                                                required></textarea>
                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-danger">
                                            Kirim
                                        </button>
                                        <button type="button"
                                                class="btn btn-secondary"
                                                data-bs-dismiss="modal">
                                            Batal
                                        </button>
                                    </div>

                                </div>

                            </form>
                        </div>
                    </div>
            <!-- ===================== -->
                </div>
        </div>
</div>
</div>
</div>

<!-- ini scrip buat pengaturan jalur profiling -->
<script>
        let currentPreviewId = null;

    // preview staging
    function openPreview(id) {

        currentPreviewId = id; // simpan id aktif

        fetch(`/admin/staging/preview/${id}`)
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

        fetch(`/admin/staging/infrastruktur/approve/${currentPreviewId}`, {
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

        fetch(`/admin/staging/infrastruktur/reject/${currentPreviewId}`, {
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

<!-- script utk Infrastruktur -->
 <!-- MODAL REJECT -->
    <div class="modal fade" id="modalReject" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" id="formReject">
            <?= csrf_field() ?>
        <div class="modal-content">

            <div class="modal-header">
            <h5 class="modal-title">Alasan Penolakan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
            <textarea name="keterangan"
                        class="form-control"
                        placeholder="Masukkan alasan penolakan..."
                        required></textarea>
            </div>

            <div class="modal-footer">
            <button class="btn btn-danger">Kirim</button>
            <button type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">
                Batal
            </button>
            </div>

        </div>
        </form>
    </div>
    </div>




<?= view('layout/master/footer') ?>