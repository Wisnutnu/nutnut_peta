<div class="card border-0 shadow-sm rounded-3">
    <!-- Header: Aksen Biru-Kehijauan (Money/Economy Feel) -->
    <div class="card-header bg-primary bg-gradient text-white py-3" style="background: linear-gradient(45deg, #1a5928, #28a745);">
        <h5 class="card-title mb-0">
            <i class="bi bi-currency-dollar me-2"></i> Input Monitor Harga Komoditas
        </h5>
        <small class="opacity-75">Pemantauan Harga Produsen & Konsumen Nasional</small>
    </div>

    <div class="card-body p-4">
        <form action="<?= base_url('harga/simpan') ?>" method="post">
            <?= csrf_field() ?>
            
            <input type="hidden" name="provinsi" value="<?= $provinsi ?>">
            <input type="hidden" name="kab_kota" value="<?= $kab_kota ?>">

            <div class="row g-4">
                <!-- Baris 1: Komoditas & Kategori -->
                <div class="col-md-6">
                    <label class="form-label fw-bold text-dark">
                        <i class="bi bi-tag me-1 text-success"></i> Jenis Komoditas
                    </label>
                    <select name="jenis_ternak" class="form-select border-2" required>
                        <option value="" selected disabled>Pilih Komoditas...</option>
                        <?php foreach ($jenisharga as $jt): ?>
                            <option value="<?= $jt ?>"><?= $jt ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-dark">
                        <i class="bi bi-layers me-1 text-success"></i> Tingkat Rantai Pasok
                    </label>
                    <select name="kategori" id="kategoriHarga" class="form-select border-2" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Harga Produsen">🏢 Harga Produsen (Farmgate)</option>
                        <option value="Harga Konsumen">🛒 Harga Konsumen (Pasar)</option>
                    </select>
                </div>

                <!-- Baris 2: Tahun & Input Harga -->
                <div class="col-md-4">
                    <label class="form-label fw-bold text-dark">
                        <i class="bi bi-calendar-check me-1 text-success"></i> Tahun
                    </label>
                    <input type="number" name="tahun" class="form-control border-2 text-center fw-bold" 
                           placeholder="YYYY" value="<?= date('Y') ?>" required>
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-bold text-dark">
                        <i class="bi bi-cash-stack me-1 text-success"></i> Nominal Harga
                    </label>
                    <div class="input-group input-group-lg border-2 shadow-sm rounded">
                        <span class="input-group-text bg-light text-success fw-bold">Rp</span>
                        <input type="text" 
                               name="harga" 
                               class="form-control fw-bold text-success" 
                               placeholder="0"
                               style="font-size: 1.5rem;"
                               required 
                               oninput="this.value = formatID(this.value.replace(/\./g,''))">
                        <span class="input-group-text bg-light">/ Kg atau Satuan</span>
                    </div>
                    <div id="price-hint" class="form-text mt-2">
                        <!-- Hint dinamis akan muncul di sini via JS -->
                    </div>
                </div>

                <!-- Baris 3: Action Buttons -->
                <div class="col-12 mt-4">
                    <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded-3 border">
                        <div class="text-muted small">
                            <i class="bi bi-shield-check"></i> Data akan diverifikasi oleh sistem pusat.
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-light px-4" onclick="history.back()">Batal</button>
                            <button type="submit" class="btn btn-success btn-lg px-5 shadow-sm" onclick="this.disabled=true; this.form.submit();">
                                <i class="bi bi-save me-2"></i> Simpan Data Harga
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Script Dinamis untuk Label -->
<script>
    document.getElementById('kategoriHarga').addEventListener('change', function() {
        const hint = document.getElementById('price-hint');
        if(this.value === 'Harga Produsen') {
            hint.innerHTML = '<span class="badge bg-info-subtle text-info"><i class="bi bi-info-circle"></i> Harga di tingkat peternak (on-farm).</span>';
        } else if(this.value === 'Harga Konsumen') {
            hint.innerHTML = '<span class="badge bg-warning-subtle text-warning"><i class="bi bi-info-circle"></i> Harga rata-rata di pasar eceran.</span>';
        } else {
            hint.innerHTML = '';
        }
    });
</script>
