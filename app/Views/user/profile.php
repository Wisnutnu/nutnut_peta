<?= $this->include('layout/user/header') ?>
<?= $this->include('layout/user/navbar') ?>
<?= $this->include('layout/sidebar/sidebar_user') ?>

<div class="main-container p-4">

<div class="row">

    <!-- ================= LEFT PANEL ================= -->
    <div class="col-md-4">

        <div class="card text-center p-4 shadow-sm border-0">

            <!-- AVATAR -->
            <div class="mb-3">
                <div class="avatar-circle mx-auto">
                    <?= strtoupper(substr(session()->get('nama'),0,1)) ?>
                </div>
            </div>

            <!-- NAMA -->
            <h5 class="fw-bold mb-1">
                <?= session()->get('nama') ?>
            </h5>

            <!-- USERNAME -->
            <p class="text-muted small">
                @<?= session()->get('username') ?>
            </p>

            <!-- ROLE -->
            <span class="badge bg-primary mb-2">
                <?= session()->get('role') ?>
            </span>

            <!-- LEVEL -->
            <span class="badge bg-info text-dark mb-2">
                <?= session()->get('level_user') ?>
            </span>

            <!-- STATUS -->
            <div class="mt-2">
                <span class="badge bg-success">
                    Aktif
                </span>
            </div>

            <hr>

            <!-- TANGGAL -->
            <p class="small text-muted mb-0">
                Bergabung sejak <br>
                <?= !empty($user['created_at']) 
                ? date('d F Y', strtotime($user['created_at'])) 
                : '-' ?>
            </p>

        </div>

    </div>


    <!-- ================= RIGHT PANEL ================= -->
    <div class="col-md-8">

        <!-- INFO WILAYAH -->
        <div class="card mb-3 p-3 shadow-sm border-0">
            <h6 class="fw-bold mb-2">Wilayah Kerja</h6>

            <p class="mb-1">
                <strong>Provinsi:</strong> <?= $user['nama_provinsi'] ?? '-' ?>
            </p>

            <p class="mb-1">
                <strong>Kabupaten:</strong> <?= $user['nama_kabupaten'] ?? '-' ?>
            </p>

            <p class="mb-0">
                <strong>Kecamatan:</strong> <?= $user['nama_kecamatan'] ?? '-' ?>
            </p>
        </div>


        <!-- FORM EDIT -->
        <div class="card p-4 shadow-sm border-0">

            <h6 class="fw-bold mb-3">Edit Profil</h6>

            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('user/profile/update') ?>" method="post">

                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control"
                           value="<?= session()->get('nama') ?>">
                </div>

                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control"
                           value="<?= session()->get('username') ?>">
                </div>

                <button class="btn btn-primary">
                    Simpan Perubahan
                </button>

            </form>

        </div>

    </div>

</div>

</div>

<?= $this->include('layout/user/footer') ?>