<?= $this->include('layout/admin/header') ?>
<?= $this->include('layout/admin/navbar') ?>
<?= $this->include('layout/sidebar/sidebar_admin') ?>

<div class="main-container p-4">
    <div class="container-fluid">

        <!-- FLASH MESSAGE -->
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Manajemen User</h4>

            <a href="<?= base_url('admin/managementuser/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah User
            </a>
        </div>

        <!-- TABLE -->
        <div class="card shadow-sm">
            <div class="card-body p-0">

                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th width="50">No</th>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Level</th>
                                <th>Provinsi</th>
                                <th>Kabupaten</th>
                                <th>Kecamatan</th>
                                <th>Status</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if(!empty($users)): ?>
                                <?php $no = 1; foreach($users as $u): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= esc($u['nama']) ?></td>
                                        <td><?= esc($u['username']) ?></td>
                                        <td><?= esc($u['role']) ?></td>
                                        <td><?= esc($u['level_user']) ?></td>
                                        <td><?= esc($u['nama_provinsi'] ?? '-') ?></td>
                                        <td><?= esc($u['nama_kabupaten'] ?? '-') ?></td>
                                        <td><?= esc($u['nama_kecamatan'] ?? '-') ?></td>
                                        <td>
                                            <?php if($u['is_active']): ?>
                                                <span class="badge bg-success">Aktif</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Nonaktif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="#" 
                                                class="btn btn-sm btn-warning btn-edit"
                                                data-id="<?= $u['id'] ?>"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalEdit">
                                                Edit
                                            </a>
                                            <a href="<?= base_url('admin/managementuser/delete/' . $u['id']) ?>" class="btn btn-sm btn-danger"
                                               onclick="return confirm('Hapus user ini?')">
                                                Hapus
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="10" class="text-center text-muted">
                                        Tidak ada data user
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>

                    <!--  -->
                    <div class="modal fade" id="modalEdit">
  <div class="modal-dialog">
    <div class="modal-content">

      <form id="formEdit">

        <div class="modal-header">
          <h5>Edit User</h5>
        </div>

        <div class="modal-body">

            <input type="hidden" name="id" id="edit_id">

            <div class="mb-2">
                <label>Nama</label>
                <input type="text" name="nama" id="edit_nama" class="form-control">
            </div>

            <div class="mb-2">
                <label>Username</label>
                <input type="text" name="username" id="edit_username" class="form-control">
            </div>

        </div>

        <div class="modal-footer">
            <button class="btn btn-primary">Update</button>
        </div>

      </form>

    </div>
  </div>
</div>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- ajak utk popup edit -->
<script>
$('.btn-edit').on('click', function() {

    let id = $(this).data('id');

    $.get("<?= base_url('admin/managementuser/edit/') ?>" + id, function(res) {

        $('#edit_id').val(res.id);
        $('#edit_nama').val(res.nama);
        $('#edit_username').val(res.username);

    }, 'json');

});
</script>
<?= view('layout/admin/footer'); ?>