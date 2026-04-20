<?= $this->include('layout/user/header') ?>
<?= $this->include('layout/user/navbar') ?>
<?= $this->include('layout/sidebar/sidebar_user') ?>

<div class="main-container p-4">
    <h4 class="mb-4">💾 Infrastruktur Data Tersimpan</h4>

    <ul class="nav nav-tabs" id="tabInfra">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#sppg">SPPG</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#sppgnotaging">SPPG Notaging</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#klinik">Klinik Hewan</button>
        </li>
    </ul>
    <div class="card shadow-sm">
        <div class="card-body">

        <!-- SPPG -->
        <div class="tab-pane fade show active" id="sppg">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Alamat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($sppg as $row): ?>
                    <tr>
                        <td><?= $row['nama'] ?? '-' ?></td>
                        <td><?= $row['alamat'] ?? '-' ?></td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
        <!-- SPPG Notaging -->
           <div class="tab-pane fade" id="sppgnotaging">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Alamat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($sppgnotaging as $row): ?>
                    <tr>
                        <td><?= $row['nama_sppg'] ?? '-' ?></td>
                        <td><?= $row['alamat_sppg'] ?? '-' ?></td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div> 
        <!--  -->
        <div class="tab-pane fade" id="klinik">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Klinik</th>
                <th>Alamat</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($klinikhewan as $row): ?>
            <tr>
                <td><?= $row['nama_klinik'] ?? '-' ?></td>
                <td><?= $row['alamat_klinik'] ?? '-' ?></td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
<!--  -->
    </div>
    </div>
</div>

<?= $this->include('layout/user/footer') ?>