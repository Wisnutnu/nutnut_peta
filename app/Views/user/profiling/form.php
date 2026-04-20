<div class="container mt-4">

    <h4>Input Data Pokok</h4>

    <form action="/user/datapokok/store" method="post">

        <div class="mb-3">
            <label>Jenis Ternak</label>
            <select name="jenis_ternak_id" class="form-control" required>
                <option value="">-- pilih --</option>
                <?php foreach ($jenis as $j): ?>
                    <option value="<?= $j['id'] ?>">
                        <?= $j['nama_jenis'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Tahun</label>
            <input type="number" name="tahun" class="form-control" required>
        </div>

        <hr>

        <h5>Pemotongan</h5>

        <div class="mb-3">
            <label>Pemotongan RPH</label>
            <input type="number" name="pemotongan_rph" class="form-control">
        </div>

        <div class="mb-3">
            <label>Pemotongan Luar RPH</label>
            <input type="number" name="pemotongan_luar_rph" class="form-control">
        </div>

        <div class="mb-3">
            <label>Pemotongan Tidak Tercatat</label>
            <input type="number" name="pemotongan_tidak_tercatat" class="form-control">
        </div>

        <div class="mb-3">
            <label>Populasi</label>
            <input type="number" name="populasi" class="form-control">
        </div>

        <hr>

        <h5>Produksi</h5>

        <div class="mb-3">
            <label>Produksi Susu</label>
            <input type="number" step="0.01" name="produksi_susu" class="form-control">
        </div>

        <div class="mb-3">
            <label>Produksi Telur</label>
            <input type="number" step="0.01" name="produksi_telur" class="form-control">
        </div>

        <button class="btn btn-primary">Simpan</button>

    </form>

</div>
