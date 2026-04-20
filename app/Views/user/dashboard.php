<?= $this->include('layout/user/header') ?>
<?= $this->include('layout/user/navbar') ?>
<?= $this->include('layout/sidebar/sidebar_user') ?>

<div class="main-container p-4">

    <h2 class="fw-bold"><?= $judul ?></h2>
    <p class="text-muted"><?= $desc ?></p>

    <div class="row mt-3">

        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0 text-center h-100">
                <div class="card-body">

                    <div class="fs-3">📊</div>

                    <h6>Data Disetujui</h6>

                    <h2 class="fw-bold text-success">
                        <?= $rekap ?>
                    </h2>

                    <small>total data</small>

                </div>
            </div>
        </div>

    </div>

</div>

<?= $this->include('layout/user/footer') ?>