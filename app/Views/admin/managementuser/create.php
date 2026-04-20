<?= $this->include('layout/admin/header') ?>
<?= $this->include('layout/admin/navbar') ?>
<?= $this->include('layout/sidebar/sidebar_admin') ?>

<div class="main-container p-4">
    <div class="container-fluid">
        <h4>Tambah User</h4>

        <form action="<?= base_url('admin/managementuser/store') ?>" method="post">

            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control">
            </div>

            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control">
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
            </div>

            <div class="mb-3">
                <label>Role</label>
                <select name="role" class="form-control">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                    <option value="master">Master</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Level User</label>
                <select name="level_user" class="form-control">
                    <option value="provinsi">Provinsi</option>
                    <option value="kabupaten">Kabupaten</option>
                    <option value="kecamatan">Kecamatan</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Provinsi</label>
                <select id="provinsi" name="provinsi_id" class="form-control">
                    <option value="">Pilih Provinsi</option>
                    <?php foreach($provinsi as $p): ?>
                        <option value="<?= $p['id'] ?>">
                            <?= $p['nama_provinsi'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <select name="kabupaten_id" id="kabupaten" class="form-control">
                    <option value="">Pilih Kabupaten</option>
                </select>
                <select name="kecamatan_id" id="kecamatan" class="form-control">
                    <option value="">Pilih Kecamatan</option>
                </select>
            </div>
            

            <button class="btn btn-primary">Simpan</button>

        </form>

    </div>
</div>

<!-- ajak utk wilayah -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$('#provinsi').on('change', function() {
    let provinsi_id = $(this).val();

    $('#kabupaten').html('<option>Loading...</option>');
    $('#kecamatan').html('<option value="">Pilih Kecamatan</option>');

    if (provinsi_id) {
        $.get("<?= base_url('admin/wilayah/kabupaten/') ?>" + provinsi_id, function(data) {
            $('#kabupaten').html(data);
        });
    }
});

$('#kabupaten').on('change', function() {
    let kabupaten_id = $(this).val();

    $('#kecamatan').html('<option>Loading...</option>');

    if (kabupaten_id) {
        $.get("<?= base_url('admin/wilayah/kecamatan/') ?>" + kabupaten_id, function(data) {
            $('#kecamatan').html(data);
        });
    }
});
</script>

<?= view('layout/admin/footer'); ?>