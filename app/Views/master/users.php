<?= $this->include('layout/master/header') ?>
<?= $this->include('layout/master/navbar') ?>
<?= $this->include('layout/sidebar/sidebar_master') ?>

<div class="main-container">
    <?= $this->renderSection('content') ?>
</div>

<div class="main-container p-4">

    <!-- ISI HALAMAN -->
    <h3>users</h3>
    <p>Manajemen user sistem.</p>

</div>

<?= $this->include('layout/master/footer') ?>
