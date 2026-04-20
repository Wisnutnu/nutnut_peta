<h4>Approval Data Pokok</h4>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tahun</th>
            <th>Populasi</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $row): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['tahun'] ?></td>
            <td><?= number_format($row['populasi']) ?></td>
            <td><?= $row['status'] ?></td>
            <td>
                <a href="<?= base_url('admin/datapokok/approve/'.$row['id']) ?>" 
                   class="btn btn-success btn-sm">Approve</a>

                <a href="<?= base_url('admin/datapokok/reject/'.$row['id']) ?>" 
                   class="btn btn-danger btn-sm">Reject</a>
            </td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>
