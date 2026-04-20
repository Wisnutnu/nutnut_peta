<?= view('layout/admin/header'); ?>
<?= view('layout/admin/navbar'); ?>
<?= view('layout/sidebar/sidebar_admin'); ?>

<div class="main-container p-4">
    <div class="content-wrapper">
    <h3>🕒 Data Pemotongan (Pending)</h3>

    <?php if (!empty($pemotongan)) : ?>
        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Nama Penyuluh</th>
                <th>Lokasi</th>
                <th>Sapi Potong</th>
                <th>Sapi Perah</th>
                <th>Kerbau</th>
                <th>Aksi</th>
            </tr>

            <?php $no=1; foreach ($pemotongan as $row): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= esc($row['nama_petugas']) ?></td>
                <td><?= esc($row['nama_tempat']) ?></td>
                <td><?= esc($row['sapi_potong']) ?></td>
                <td><?= esc($row['sapi_perah']) ?></td>
                <td><?= esc($row['kerbau']) ?></td>
                <td>
                    <a href="<?= base_url('admin/approval/penyuluh/approve/'.$row['id']) ?>" class="btn btn-success btn-sm">Approve</a>
                    <a href="<?= base_url('admin/approval/penyuluh/reject/'.$row['id']) ?>" class="btn btn-danger btn-sm">Reject</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Tidak ada data pending.</p>
    <?php endif; ?>
</div>
</div>

<?= view('layout/admin/footer'); ?>