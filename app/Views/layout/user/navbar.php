<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm w-100">
    <div class="container-fluid">

        <a class="navbar-brand fw-bold" href="#">
            🌟 User Panel
        </a>

        <div class="d-flex ms-auto">

            <a href="<?= base_url('user/profile') ?>" class="btn btn-warning btn-sm">
    <i class="fas fa-user me-1"></i>
    <?= session()->get('nama') ?>
</a>

        </div>

    </div>
</nav>
