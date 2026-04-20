<?= $this->include('layout/user/header') ?>
<?= $this->include('layout/user/navbar') ?>
<?= $this->include('layout/sidebar/sidebar_user') ?>


<div class="main-container p-4">

    <h4><?= isset($populasi) ? 'Edit' : 'Tambah' ?> Data Populasi</h4>

    <?php 
    $isEdit = isset($populasi);
    $action = $isEdit 
        ? '/user/populasi/update/' . $populasi['id'] 
        : '/user/populasi/store';
    ?>

    <form action="<?= $action; ?>" method="post">

        <!-- Nama Pemilik -->
        <div class="mb-3">
            <label>Nama Pemilik Ternak</label>
            <input type="text" name="nama_pemilik_ternak" class="form-control"
                value="<?= $isEdit ? $populasi['nama_pemilik_ternak'] : old('nama_pemilik_ternak'); ?>">
        </div>

        <!-- No Telp -->
        <div class="mb-3">
            <label>No Telepon</label>
            <input type="text" name="no_telp" class="form-control"
                value="<?= $isEdit ? $populasi['no_telp'] : old('no_telp'); ?>">
        </div>

        <!-- Jenis Ternak -->
        <div class="mb-3">
            <label>Jenis Ternak</label>
            <select name="jenis_ternak_id" class="form-control">
                <option value="">-- Pilih --</option>
                <?php foreach ($jenis_ternak as $t): ?>
                    <option value="<?= $t['id']; ?>"
                        <?= ($isEdit && $t['id'] == $populasi['jenis_ternak_id']) ? 'selected' : ''; ?>>
                        <?= $t['nama_jenis']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Jumlah -->
        <div class="mb-3">
            <label>Jumlah Ternak</label>
            <input type="number" name="jumlah" class="form-control"
                value="<?= $isEdit ? $populasi['jumlah'] : old('jumlah'); ?>">
        </div>

        <!-- Tahun -->
        <div class="mb-3">
            <label>Tahun</label>
            <input type="number" name="tahun" class="form-control"
                value="<?= $isEdit ? $populasi['tahun'] : old('tahun'); ?>">
        </div>

        <!-- Latitude -->
        <div class="mb-3">
            <label>Latitude</label>
            <input type="text" id="latitude" name="latitude" class="form-control"
                value="<?= $isEdit ? $populasi['latitude'] : old('latitude'); ?>">
        </div>

        <!-- Longitude -->
        <div class="mb-3">
            <label>Longitude</label>
            <input type="text" id="longitude" name="longitude" class="form-control"
                value="<?= $isEdit ? $populasi['longitude'] : old('longitude'); ?>">
        </div>

        <!-- Button to get location -->
        <div class="mb-3">
            <button type="button" class="btn btn-info" onclick="getLocation()">
                📍 Ambil Lokasi Saya
            </button>
        </div>

        <!-- BUTTON -->
        <button class="btn btn-success">
            <?= $isEdit ? 'Update' : 'Kirim' ?>
        </button>

        <a href="/user/populasi" class="btn btn-secondary">Kembali</a>

    </form>

</div>

<!-- script ambil lokasi langsung -->
 <script>
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                document.getElementById("latitude").value = position.coords.latitude;
                document.getElementById("longitude").value = position.coords.longitude;
            },
            function(error) {
                alert("Gagal mengambil lokasi. Pastikan izin lokasi diaktifkan.");
            }
        );
    } else {
        alert("Browser tidak mendukung geolocation");
    }
}
</script>

<?= $this->include('layout/user/footer') ?>