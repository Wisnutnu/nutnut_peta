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
            <li class="nav-item" role="presentation">
                <button class="nav-link"
                        id="datapokok-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#datapokok"
                        type="button"
                        role="tab">
                    Data Pokok
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link"
                        id="penyuluh-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#penyuluh"
                        type="button"
                        role="tab">
                    Penyuluh
                </button>
            </li>
            </ul>
            <!--Tapilan Staging List -->
            <div class="tab-content mt-3">
            <!-- TAB PROFILING -->
            <div class="tab-pane fade show active" id="profiling" role="tabpanel">
                    <form method="get" class="row g-2">

                        <div class="col-md-3">
                            <input type="text" name="kabupaten" 
                                value="<?= $_GET['kabupaten'] ?? '' ?>"
                                class="form-control"
                                placeholder="Cari Kabupaten">
                        </div>

                        <div class="col-md-3">
                            <select name="kategori" class="form-select">
                                <option value="">Semua Kategori</option>
                                <?php foreach($kategoriList as $k): ?>
                                    <option value="<?= $k['kategori'] ?>"
                                        <?= ($_GET['kategori'] ?? '') == $k['kategori'] ? 'selected' : '' ?>>
                                        <?= $k['kategori'] ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="all">Semua Status</option>
                                <option value="pending" <?= ($_GET['status'] ?? '') == 'pending' ? 'selected' : '' ?>>
                                    🟡 Pending
                                </option>
                                <option value="approved" <?= ($_GET['status'] ?? '') == 'approved' ? 'selected' : '' ?>>
                                    🟢 Approved
                                </option>
                                <option value="rejected" <?= ($_GET['status'] ?? '') == 'rejected' ? 'selected' : '' ?>>
                                    🔴 Rejected
                                </option>

                            </select>
                        </div>

                        <div class="col-md-2">
                            <input type="date" name="tanggal" 
                                value="<?= $_GET['tanggal'] ?? '' ?>"
                                class="form-control">
                        </div>

                        <div class="col-md-2 d-grid">
                            <button class="btn btn-primary">🔍 Filter</button>
                        </div>

                    </form>

                
                        <!-- TABLE -->
                        <div class="card shadow-sm">
                            <div class="card-body p-2">
                                <h5 class="mb-3">Data Yg diupload</h5>
                                
                                <table class="table table-bordered table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="60">ID</th>
                                        <th>Kategori</th>
                                        <th>Detail Data</th>
                                        <th width="120">Jumlah Row</th>
                                        <th width="140">Status</th>
                                        <th width="120">Preview</th>
                                        <th width="120">Kabupaten</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($list as $row): ?>
                                        <?php
                                    $data = json_decode($row['data_json'], true);
                                    $first = $data[0] ?? [];

                                    $detail = '-';
                                    
                                    if ($row['kategori'] == 'populasi') {
                                        $detail = '🐄 ' . ($first[3] ?? '-') . ' (' . ($first[4] ?? '-') . ')';
                                        } elseif ($row['kategori'] == 'produksi') {
                                            $detail = '🥩 ' . ($first[3] ?? '-') . ' (' . ($first[4] ?? '-') . ')';
                                    } elseif ($row['kategori'] == 'harga') {
                                        $detail = '💲 ' . ($first[3] ?? '-') . ' - ' . ($first[4] ?? '-');
                                        }
                                        ?>
                                <tr class="<?= $row['status'] == 'pending' ? 'table-warning' : '' ?>">
                                    
                                    <td><?= $row['id'] ?></td>
                                    
                                    <td><?= ucfirst($row['kategori']) ?></td>
                                    
                                    <td><?= esc($detail) ?></td>
                                    
                                    <td class="text-center"><?= $row['jumlah_row'] ?></td>
                                    
                                    <td>
                                        <?php if($row['status'] == 'pending'): ?>
                                            <span class="badge bg-warning text-dark">🟡 Pending</span>
                                            <?php elseif($row['status'] == 'approved'): ?>
                                                <span class="badge bg-success">🟢 Approved</span>
                                                <?php else: ?>
                                            <span class="badge bg-danger">🔴 Rejected</span>
                                        <?php endif ?>
                                    </td>
                                    
                                    <td>
                                        <button class="btn btn-sm btn-primary"
                                        onclick="openPreview(<?= $row['id'] ?>)">
                                        🔍
                                    </button>
                                </td>
                                
                                <td><?= esc($row['kabupaten']) ?></td>
                                </tr>
                                <?php endforeach ?>
                            </tbody>
                            
                        </table>
                        
                        </div>
                    </div>

            
                    <!-- MODAL PREVIEW -->
                    <div class="modal fade" id="previewModal" tabindex="-1">
                        <div class="modal-dialog modal-xl modal-dialog-scrollable">
                            <div class="modal-content">
                                
                                <div class="modal-header">
                                    <h5 class="modal-title">🔍 Preview Upload</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                
                                <div class="modal-body">
                                    <div id="previewContent">Loading...</div>
                                </div>
                                
                                <div class="modal-footer">
                                    
                                    <a id="downloadBtn" class="btn btn-outline-primary me-auto">
                                        ⬇ Download Excel Asli
                                    </a>
                                    
                                    <textarea id="catatan_admin" 
                                    class="form-control mb-2" 
                                        rows="2"
                                        placeholder="Isi catatan (wajib jika reject)"></textarea>
                                        
                                        <button class="btn btn-success" onclick="approveData()">
                                            ✅ Approve
                                    </button>
                                    
                                    <button class="btn btn-danger" onclick="rejectData()">
                                        ❌ Reject
                                    </button>
                                    
                                </div>
                                
                                
                            </div>
                            
                        </div>
                    </div>
                </div>

        <!-- tab infrastruktur -->
                <div class="tab-pane fade" id="infrastruktur" role="tabpanel">

                <!-- FILTER -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <form method="get" class="row g-2">

                                <div class="col-md-3">
                                    <input type="text" name="kabupaten"
                                        value="<?= $_GET['kabupaten'] ?? '' ?>"
                                        class="form-control"
                                        placeholder="Cari Kabupaten">
                                </div>

                                <div class="col-md-2">
                                    <select name="status" class="form-select">
                                        <option value="all">Semua Status</option>
                                        <option value="pending" <?= ($_GET['status'] ?? '') == 'pending' ? 'selected' : '' ?>>
                                            🟡 Pending
                                        </option>
                                        <option value="approved" <?= ($_GET['status'] ?? '') == 'approved' ? 'selected' : '' ?>>
                                            🟢 Approved
                                        </option>
                                        <option value="rejected" <?= ($_GET['status'] ?? '') == 'rejected' ? 'selected' : '' ?>>
                                            🔴 Rejected
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <input type="date" name="tanggal"
                                        value="<?= $_GET['tanggal'] ?? '' ?>"
                                        class="form-control">
                                </div>

                                <div class="col-md-2 d-grid">
                                    <button class="btn btn-primary">
                                        🔍 Filter
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>

                <!-- TABEL -->
                    <div class="card">
                        <div class="card-body table-responsive">

                            <table class="table table-bordered table-striped align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Jenis Infrastruktur</th>
                                        <th>Nama Tempat</th>
                                        <th>Alamat</th>
                                        <th>Status</th>
                                        <th width="150">Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                <?php if (!empty($infrastruktur)): ?>
                                    <?php foreach ($infrastruktur as $row): ?>
                                        <tr>
                                            <td><?= esc($row['jenis_infrastruktur']) ?></td>
                                            <td><?= esc($row['nama_tempat']) ?></td>
                                            <td><?= esc($row['alamat']) ?></td>
                                            <td>
                                                <?php if ($row['status'] == 'pending'): ?>
                                                    <span class="badge bg-warning">Pending</span>
                                                <?php elseif ($row['status'] == 'approved'): ?>
                                                    <span class="badge bg-success">Approved</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Rejected</span>
                                                <?php endif; ?>
                                            </td>

                                            <td>
                                                <a href="<?= base_url('admin/staging/infrastruktur/approve/'.$row['id']) ?>"
                                                class="btn btn-sm btn-success"
                                                onclick="return confirm('Setujui data ini?')">
                                                    Approve
                                                </a>

                                                <button class="btn btn-sm btn-danger btn-reject"
                                                        data-id="<?= $row['id'] ?>">
                                                    Reject
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            Belum ada data
                                        </td>
                                    </tr>
                                <?php endif ?>
                                </tbody>

                            </table>

                        </div>
                    </div>


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

                </div>
        <!-- tab data pokok -->
                <div class="tab-pane fade" id="datapokok" role="tabpanel"></div>
                    <p>Coming Soon</p>
                </div>
        <!-- tab penyuluh -->
                <div class="tab-pane fade" id="penyuluh" role="tabpanel">
            </div>"
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