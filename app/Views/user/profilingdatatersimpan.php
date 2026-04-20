<?= $this->include('layout/user/header') ?>
<?= $this->include('layout/user/navbar') ?>
<?= $this->include('layout/sidebar/sidebar_user') ?>

<div class="main-container p-4">
    <h4 class="mb-4">💾 Data Tersimpan</h4>
        <ul class="nav nav-tabs" id="mainTab">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profiling">
                    Profiling
                </button>
            </li>
            <!-- <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#infrastruktur">
                    Infrastruktur
                </button>
            </li> -->
        </ul>
        
        <div class="tab-content mt-3">
    <!-- ========= -->
    <!-- 🔵 PROFILING -->
    <div class="tab-pane fade show active" id="profiling">

    <form method="get" class="mb-3 d-flex gap-2">
    <select name="tahun" class="form-select" style="max-width:200px;">
        <option value="">Semua Tahun</option>

        <?php foreach($list_tahun as $t): ?>
            <option value="<?= $t['tahun'] ?>"
                <?= ($tahun == $t['tahun']) ? 'selected' : '' ?>>
                <?= $t['tahun'] ?>
            </option>
        <?php endforeach ?>
    </select>

    <button type="submit" class="btn btn-primary">Filter</button>
</form>
        <!-- SUB TAB -->
        <ul class="nav nav-pills mb-3">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#populasi">
                    Populasi
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#produksi">
                    Produksi
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#harga">
                    Harga
                </button>
            </li>
        </ul>

        <!-- SUB CONTENT -->
        <div class="tab-content">
        <!-- 🔵 POPULASI -->
            <div class="tab-pane fade show active" id="populasi">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Provinsi</th>
                            <th>Kabupaten</th>
                            <th>Jenis Ternak</th>
                            <th>Tahun</th>
                            <th>Jumlah Populasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($populasi)): ?>
                            <?php foreach($populasi as $row): ?>

                            <?php 
                            // 🎨 warna badge random
                            $colors = ['primary','success','warning','danger','info'];
                            $color = $colors[array_rand($colors)];
                            ?>

                            <tr>
                                <!-- 📍 WILAYAH -->
                                <td class="ps-4">
                                    <div class="fw-semibold">
                                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                        <?= $row['nama_provinsi'] ?? '-' ?>
                                    </div>
                                    
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?= $row['nama_kabupaten'] ?? '-' ?>
                                    </small>
                                </td>
                                
                                <!-- 🐄 JENIS -->
                                <td>
                                    <span class="badge bg-<?= $color ?>-subtle text-<?= $color ?>">
                                        <?= $row['jenis_ternak'] ?? '-' ?>
                                    </span>
                                </td>

                                <!-- 📅 TAHUN -->
                                <td class="text-center">
                                    <?= $row['tahun'] ?? '-' ?>
                                </td>

                                <!-- 🔢 JUMLAH -->
                                <td class="text-end pe-4 fw-bold">
                                    <?= is_numeric($row['jumlah_populasi']) 
                                        ? number_format($row['jumlah_populasi'], 0, ',', '.') 
                                        : '-' ?>
                                </td>
                            </tr>

                        <?php endforeach ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    Belum ada data populasi
                                </td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>

                <!-- jumlah data dan pagination(tap 123) -->
                Menampilkan <?= count($populasi) ?> dari <?= $total_populasi ?> data
                <?php if ($pager_populasi->getPageCount('populasi') > 1): ?>
                    <div class="d-flex justify-content-center mt-3">
                        <?= $pager_populasi->links('populasi', 'default_full', ['query' => $_GET]) ?>
                    </div>
                <?php endif; ?>
            </div>

        <!-- 🟢 PRODUKSI -->
         <div class="tab-pane fade" id="produksi">
           <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary small text-uppercase">
                        <tr>
                            <th>Provinsi</th>
                            <th>Kabupaten</th>
                            <th>Jenis Produksi</th>
                            <th class="text-center">Tahun</th>
                            <th class="text-end pe-4">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($produksi)): ?>
                            <?php foreach($produksi as $row): ?>
                                <?php 
                                    $colors = ['primary','success','warning','danger','info'];
                                    $color = $colors[array_rand($colors)];
                                ?>
                                <tr>
                                    <!-- 📍 WILAYAH -->
                                    <td class="ps-4">
                                        <div class="fw-semibold">
                                            <i class="bi bi-geo-alt-fill text-danger me-1"></i> <?= $row['provinsi'] ?? '-' ?>
                                        </div>
                                    </td>
                                    <td><small class="text-muted"><?= $row['kab_kota'] ?? '-' ?></small></td>
                                    <td>
                                        <span class="badge bg-<?= $color ?>-subtle text-<?= $color ?>">
                                            <?= $row['jenis_produksi'] ?? '-' ?>
                                        </span>
                                    </td>
                                    <td class="text-center"><?= $row['tahun'] ?? '-' ?></td>
                                    <td class="text-end pe-4 fw-bold">
                                        <?= is_numeric($row['jumlah']) ? number_format($row['jumlah'], 0, ',', '.') : '-' ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center py-5 text-muted">Belum ada data produksi</td></tr>
                        <?php endif ?>
                    </tbody>
                </table>
                
                <!-- jumlah data dan pagination(tap 123) -->
                <div class="mt-3 small text-muted">
                    Menampilkan <b><?= count($produksi) ?></b> dari <b><?= $total_produksi ?? 0 ?></b> data
                </div>

                <div class="d-flex justify-content-center mt-3">
                    <?php if ($pager_produksi->getPageCount('produksi') > 1): ?>
                        <?= $pager_produksi->links('produksi', 'default_full', ['query' => $_GET]) ?>
                    <?php endif; ?>
                </div>
                
                
            </div> 
         </div>

        <!-- 🟡 HARGA -->
         <div class="tab-pane fade" id="harga">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary small text-uppercase">
                        <tr>
                            <th>Komoditas</th>
                            <th>Kategori</th>
                            <th class="text-center">Tahun</th>
                            <th class="text-end pe-4">Harga (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($harga)): ?>
                            <?php foreach($harga as $row): ?>
                                <?php 
                                    // Kategori biasanya cuma 2, kita bedakan warnanya agar lebih informatif
                                    $isProdusen = strpos(strtolower($row['kategori'] ?? ''), 'produsen') !== false;
                                    $color = $isProdusen ? 'primary' : 'warning';
                                ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-semibold">
                                            <i class="bi bi-tag-fill text-success me-1"></i> <?= $row['jenis_ternak'] ?? '-' ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $color ?>-subtle text-<?= $color ?>">
                                            <?= $row['kategori'] ?? '-' ?>
                                        </span>
                                    </td>
                                    <td class="text-center"><?= $row['tahun'] ?? '-' ?></td>
                                    <td class="text-end pe-4 fw-bold text-success">
                                        <?= is_numeric($row['harga']) ? number_format($row['harga'], 0, ',', '.') : '-' ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center py-5 text-muted">Belum ada data harga</td></tr>
                        <?php endif ?>
                    </tbody>
                </table>

                <div class="mt-3 small text-muted">
                    Menampilkan <b><?= count($harga) ?></b> dari <b><?= $total_harga ?? 0 ?></b> data
                </div>
                <div class="d-flex justify-content-center mt-3">
                    <?php if ($pager_harga->getPageCount('harga') > 1): ?>
                        <?= $pager_harga->links('harga', 'default_full', ['query' => $_GET]) ?>
                    <?php endif; ?>
                </div>
            </div>
         </div>
<!-- div akhir -->

        </div>
    </div>

                            
    <!-- ================== -->
    <!-- 🟡 INFRASTRUKTUR -->
    <!-- <div class="tab-pane fade" id="infrastruktur">
        <p>Data infrastruktur akan ditampilkan di sini</p>
    </div> -->

</div>

</div>

<?= $this->include('layout/user/footer') ?>