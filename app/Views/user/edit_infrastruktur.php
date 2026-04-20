<form action="<?= base_url('user/upload/updateInfrastruktur/'.$data['id']) ?>" method="post">

<div class="mb-3">
    <label>Jenis</label>
    <input type="text" name="jenis"
           value="<?= $data['jenis_infrastruktur'] ?>"
           class="form-control">
</div>

<div class="mb-3">
    <label>Nama</label>
    <input type="text" name="nama"
           value="<?= $data['nama_tempat'] ?>"
           class="form-control">
</div>

<div class="mb-3">
    <label>Alamat</label>
    <textarea name="alamat"
              class="form-control"><?= $data['alamat'] ?></textarea>
</div>

<button class="btn btn-primary">Update</button>

</form>