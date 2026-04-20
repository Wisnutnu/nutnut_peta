<?= $this->include('layout/user/header') ?>
<?= $this->include('layout/user/navbar') ?>
<?= $this->include('layout/sidebar/sidebar_user') ?>

<div class="main-container">
    <div class="container-fluid py-4">
        <div class="content-wrapper">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    Form Input Pemotongan Tidak Tercatat
                </div>

                <div class="card-body">

                    <?php if(session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <form action="/user/penyuluh/store" method="post">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Nama Tempat Pemotongan</label>
                                <input type="text" name="nama_tempat"
                                    class="form-control"
                                    value="<?= old('nama_tempat') ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Nama Petugas</label>
                                <input type="text" name="nama_petugas"
                                    class="form-control"
                                    value="<?= old('nama_petugas') ?>">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label>Alamat</label>
                                <textarea name="alamat" class="form-control"><?= old('alamat') ?></textarea>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Desa</label>
                                <input type="text" name="desa"
                                    class="form-control"
                                    value="<?= old('desa') ?>">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Kecamatan</label>
                                <input type="text" name="kecamatan"
                                    class="form-control"
                                    value="<?= old('kecamatan') ?>">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Kabupaten</label>
                                <input type="text" name="kabupaten"
                                    class="form-control"
                                    value="<?= old('kabupaten') ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Bulan</label>
                                <input type="month" name="bulan"
                                    class="form-control"
                                    value="<?= old('bulan') ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Tanggal</label>
                                <input type="date" name="tanggal"
                                    class="form-control"
                                    value="<?= old('tanggal') ?>" required>
                            </div>

                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label>Sapi Potong (ekor)</label>
                                <input type="number" name="sapi_potong"
                                    class="form-control"
                                    value="<?= old('sapi_potong', 0) ?>" min="0">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Sapi Perah (FH & Jersey)</label>
                                <input type="number" name="sapi_perah"
                                    class="form-control"
                                    value="<?= old('sapi_perah', 0) ?>" min="0">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Kerbau (ekor)</label>
                                <input type="number" name="kerbau"
                                    class="form-control"
                                    value="<?= old('kerbau', 0) ?>" min="0">
                            </div>
                        </div>

                        <div class="text-end">
                            <a href="/user/penyuluh" class="btn btn-secondary">
                                Kembali
                            </a>

                            <button type="submit" class="btn btn-success">
                                Simpan
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>