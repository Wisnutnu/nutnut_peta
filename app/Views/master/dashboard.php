<?= $this->include('layout/master/header') ?>
<?= $this->include('layout/master/navbar') ?>
<?= $this->include('layout/sidebar/sidebar_master') ?>

<div class="main-container">
    <?= $this->renderSection('content') ?>
</div>


<div class="main-container p-4">

    <h3 class="mb-4">👑 Dashboard Nasional</h3>
<p> Grafik</p>

<?= $this->include('layout/master/footer') ?>