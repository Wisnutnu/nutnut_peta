<?= $this->include('layout/admin/header') ?>
<?= $this->include('layout/admin/navbar') ?>
<?= $this->include('layout/sidebar/sidebar_admin') ?>

<div class="container-fluid p-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Edit User</h4>
        <a href="<?= base_url('admin/managementuser') ?>" class="btn btn-secondary btn-sm">
            Kembali
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="<?= base_url('admin/managementuser/update/' . $user['id']) ?>" method="post">

                <!-- NAMA -->
                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control"
                           value="<?= esc($user['nama']) ?>" required>
                </div>

                <!-- USERNAME -->
                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control"
                           value="<?= esc($user['username']) ?>" required>
                </div>

                <!-- ROLE -->
                <div class="mb-3">
                    <label>Role</label>
                    <select name="role" class="form-control">
                        <option value="user" <?= $user['role']=='user'?'selected':'' ?>>User</option>
                        <option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>Admin</option>
                        <option value="master" <?= $user['role']=='master'?'selected':'' ?>>Master</option>
                    </select>
                </div>

                <!-- PROVINSI -->
                <div class="mb-3">
                    <label>Provinsi</label>
                    <select name="provinsi_id" id="provinsi" class="form-control">
                        <option value="">Pilih Provinsi</option>
                        <?php foreach($provinsi as $p): ?>
                            <option value="<?= $p['id'] ?>"
                                <?= $user['provinsi_id']==$p['id']?'selected':'' ?>>
                                <?= $p['nama_provinsi'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- KABUPATEN -->
                <div class="mb-3">
                    <label>Kabupaten</label>
                    <select name="kabupaten_id" id="kabupaten" class="form-control">
                        <option value="">Pilih Kabupaten</option>
                        <?php foreach($kabupaten as $k): ?>
                            <option value="<?= $k['id'] ?>"
                                <?= $user['kabupaten_id']==$k['id']?'selected':'' ?>>
                                <?= $k['nama_kabupaten'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- KECAMATAN -->
                <div class="mb-3">
                    <label>Kecamatan</label>
                    <select name="kecamatan_id" id="kecamatan" class="form-control">
                        <option value="">Pilih Kecamatan</option>
                        <?php foreach($kecamatan as $k): ?>
                            <option value="<?= $k['id'] ?>"
                                <?= $user['kecamatan_id']==$k['id']?'selected':'' ?>>
                                <?= $k['nama_kecamatan'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- BUTTON -->
                <button type="submit" class="btn btn-primary">
                    Update User
                </button>

            </form>

        </div>
    </div>

</div>

<?= view('layout/admin/footer'); ?>