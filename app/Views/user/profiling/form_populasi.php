<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form action="<?= base_url('controller/method') ?>" method="post">
            <?= csrf_field() ?>
            
            <input type="hidden" name="provinsi" value="<?= $provinsi ?? '' ?>">
            <input type="hidden" name="kab_kota" value="<?= $kab_kota ?? '' ?>">

            <div class="row g-3">
                <!-- Baris 1: Jenis Ternak & Tahun -->
                <div class="col-md-8">
                    <label class="form-label fw-bold">Jenis Ternak</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-piggy-bank"></i></span>
                        <select name="jenis_ternak" class="form-select" required>
                            <option value="" selected disabled>Pilih Jenis Ternak...</option>
                            <?php foreach ($jenisTernak as $jt): ?>
                                <option value="<?= $jt ?>"><?= $jt ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Tahun</label>
                    <input type="number" name="tahun" class="form-control" placeholder="Contoh: 2024" required>
                </div>

                <!-- Baris 2: Jumlah Populasi -->
                <div class="col-12">
                    <label class="form-label fw-bold">Jumlah Populasi</label>
                    <div class="input-group">
                        <input type="text" 
                               name="jumlah_populasi" 
                               class="form-control form-control-lg" 
                               placeholder="0"
                               required 
                               oninput="this.value = formatID(this.value.replace(/\./g,''))">
                        <span class="input-group-text">Ekor</span>
                    </div>
                    <div class="form-text">Masukkan angka saja, format ribuan akan otomatis muncul.</div>
                </div>

                <!-- Baris 3: Action Button -->
                <div class="col-12 mt-4 d-grid">
                    <button type="submit" class="btn btn-success btn-lg shadow-sm" onclick="this.disabled=true; this.form.submit();">
                        <i class="bi bi-check-circle me-2"></i> Simpan Data Populasi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
