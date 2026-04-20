<div class="card border-0 shadow-sm rounded-3">
    <!-- Header dengan nuansa Ditjen PKH -->
    <div class="card-header bg-success bg-gradient text-white py-3">
        <h5 class="card-title mb-0">
            <i class="bi bi-clipboard-data me-2"></i> Input Data Produksi Peternakan
        </h5>
        <small class="opacity-75">Sistem Pelaporan Produksi Nasional</small>
    </div>

    <div class="card-body p-4">
        <form action="<?= base_url('produksi/simpan') ?>" method="post">
            <?= csrf_field() ?>
            
            <input type="hidden" name="provinsi" value="<?= $provinsi ?>">
            <input type="hidden" name="kab_kota" value="<?= $kab_kota ?>">

            <div class="row g-4">
                <!-- Baris 1: Komoditas & Tahun -->
                <div class="col-md-8">
                    <label class="form-label fw-bold text-secondary">
                        <i class="bi bi-box-seam me-1"></i> Jenis Produksi / Komoditas
                    </label>
                    <select name="jenis_produksi" id="jenisProduksi" class="form-select form-select-lg border-success-subtle" required>
                        <option value="" selected disabled>Pilih Komoditas...</option>
                        <?php foreach ($jenisProduksi as $jp): ?>
                            <option value="<?= $jp ?>"><?= $jp ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold text-secondary">
                        <i class="bi bi-calendar-event me-1"></i> Tahun Anggaran
                    </label>
                    <input type="number" name="tahun" class="form-control form-control-lg border-success-subtle" 
                           placeholder="YYYY" min="2000" max="<?= date('Y') ?>" required>
                </div>

                <!-- Baris 2: Volume Produksi -->
                <div class="col-12">
                    <div class="p-3 bg-light rounded-3 border-start border-success border-4">
                        <label class="form-label fw-bold text-success" id="label-jumlah">
                            Volume Produksi (<span id="satuan-text" class="fst-italic text-decoration-underline">-</span>)
                        </label>
                        <div class="input-group input-group-lg">
                            <input type="text" 
                                   name="jumlah" 
                                   class="form-control border-success-subtle bg-white" 
                                   placeholder="0"
                                   required 
                                   oninput="this.value = formatID(this.value.replace(/\./g,''))">
                            <span class="input-group-text bg-success text-white border-success" id="badge-satuan">Kg/Ekor</span>
                        </div>
                        <div class="form-text mt-2 text-muted">
                            <i class="bi bi-info-circle me-1"></i> Pastikan angka sesuai dengan laporan statistik daerah.
                        </div>
                    </div>
                </div>

                <!-- Baris 3: Action -->
                <div class="col-12 pt-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success btn-lg px-5 shadow" onclick="this.disabled=true; this.form.submit();">
                            <i class="bi bi-cloud-arrow-up me-2"></i> Sinkronkan Data
                        </button>
                        <button type="reset" class="btn btn-outline-secondary btn-lg px-4">
                            Reset
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Script Tambahan untuk efek 'Keren' di Satuan -->
<script>
    document.getElementById('jenisProduksi').addEventListener('change', function() {
        const value = this.value.toLowerCase();
        let satuan = '-';
        
        if(value.includes('susu')) {
            satuan = 'Liter';
        } else if(value.includes('telur')) {
            satuan = 'Butir/Kg';
        } else if(value.includes('daging')) {
            satuan = 'Ton/Kg';
        } else {
            satuan = 'Kg';
        }
        
        document.getElementById('satuan-text').innerText = satuan;
        document.getElementById('badge-satuan').innerText = satuan;
    });
</script>
