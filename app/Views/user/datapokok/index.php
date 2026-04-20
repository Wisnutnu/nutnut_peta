<?= $this->include('layout/user/header') ?>
<?= $this->include('layout/user/navbar') ?>
<?= $this->include('layout/sidebar/sidebar_user') ?>

<div class="main-container">
<div class="container-fluid py-4">

<div class="content-wrapper">
        <h4 class="mb-3">Input Data Pokok</h4>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
    </div>
    <?php endif; ?>
<form method="post" action="<?= base_url('user/datapokok/store') ?>">

<div class="row mb-3">
    <div class="col-md-6">
        <label>Provinsi</label>
        <input type="text" class="form-control" 
           value="<?= session()->get('provinsi') ?>" readonly>
    </div>

    <div class="col-md-6">
        <label>Kabupaten / Kota</label>
        <input type="text" class="form-control" 
           value="<?= session()->get('kab_kota') ?>" readonly>
    </div>
</div>

<!-- pilihan jenis ternak -->
<div class="mb-3">
    <label>Jenis Ternak</label>
    <select name="jenis_ternak_id" id="jenis_ternak" class="form-control" required>

        <option value="">-- pilih --</option>
        <?php foreach ($jenisTernak as $j): ?>
            <option 
                value="<?= $j['id'] ?>" 
                data-kategori="<?= $j['kategori_input'] ?>"
            >
            <?= $j['nama_jenis'] ?></option>
        <?php endforeach; ?>

    </select>
</div>

<!-- pilihan input -->
 <div class="mb-3">
    <label>Mode Input</label>
    <select name="mode_inputan" class="form-control" required>
        <option value="">-- pilih mode --</option>
        <option value="parameter">Parameter</option>
        <option value="manual">Manual</option>
    </select>
</div>



<div class="mb-3">
    <label>Tahun</label>
    <input type="number" name="tahun" class="form-control" required>
</div>



<div id="groupPemotongan" style="display:none">

    <h5>Pemotongan</h5>

    <div class="mb-3">
        <label>Pemotongan di RPH/TPH</label>
        <input type="text" name="pemotongan_rph" class="form-control angka">
    </div>

    <div class="mb-3">
        <label>Pemotongan diluar RPH/TPH</label>
        <input type="text" name="pemotongan_luar_rph" class="form-control angka">
    </div>

    <div class="mb-3">
        <label>Pemotongan tidak tercatat</label>
        <input type="text" name="pemotongan_tidak_tercatat" class="form-control angka">
    </div>

</div>

<!-- B -->
            <div id="groupPopulasi" style="display:none">

    <h5>Populasi</h5>

    <div class="mb-3">
        <label>Populasi Ternak</label>
        <input type="text" name="populasi" class="form-control">
    </div>

</div>

<!-- form parameter -->
<div id="groupParameter" style="display:none; margin-top:20px;">
    <div class="card border-warning">
        <div class="card-header bg-warning">
        <strong>⚙️ Parameter Perhitungan Ternak</strong>
        <br>
        <small>Isi parameter ini jika menggunakan mode perhitungan otomatis</small>
        </div>
            <div class="card-body bg-light">
            <div id="paramPotong" style="display:none">
            <div class="row">

                <div class="col-md-6 mb-3">
                <label class="fw-bold">Berat Hidup (Kg/Ekor)</label>
                <input type="text" name="berat_hidup" class="form-control angka">
                </div>

                <div class="col-md-6 mb-3">
                <label class="fw-bold">Berat Karkas (Kg/Ekor)</label>
                <input type="text" name="berat_karkas" class="form-control angka">
                </div>

                <div class="col-md-6 mb-3">
                <label class="fw-bold">Berat Daging Murni (Kg/Ekor)</label>
                <input type="text" name="berat_daging_murni" class="form-control angka">
                </div>

                <div class="col-md-6 mb-3">
                <label class="fw-bold">Berat Jeroan (Kg/Ekor)</label>
                <input type="text" name="berat_jeroan" class="form-control angka">
                </div>

                <div class="col-md-6 mb-3">
                <label class="fw-bold">Berat Daging Variasi (Kg/Ekor)</label>
                <input type="text" name="berat_daging_variasi" class="form-control angka">
                </div>

                <div class="col-md-6 mb-3">
                <label class="fw-bold">Persentase_Berat Daging Variasi (%)</label>
                <input type="text" name="persentase_berat_daging_variasi" class="form-control angka">
                </div>

                <div class="col-md-6 mb-3">
                <label class="fw-bold">Pemotongan Tidak Tercatat (%)</label>
                <input type="text" name="pemotongan_tidak_tercatat_persen" class="form-control angka">
                </div>

            </div>
            </div>
            </div>
            </div>
    </div>
</div>


    <div id="paramSusu" style="display:none">
        <div class="mb-3">
        <label>Betina Laktasi terhadap Populasi</label>
        <input type="number" step="0.01" name="betina_laktasi_terhadap_populasi" class="form-control">
        </div>

        <div class="mb-3">
        <label>Produktivitas Susu (Liter/Ekor/Hari)</label>
        <input type="number" step="0.01" name="produktivitas_susu" class="form-control">
        </div>

    </div>


    <div id="paramTelur" style="display:none">

        <div class="mb-3">
        <label>Produktivitas Telur (Butir/Ekor/Hari)</label>
        <input type="number" step="0.01" name="produktivitas_telur" class="form-control">
        </div>

    </div>


    <div id="paramPedaging" style="display:none">

        <div class="mb-3">
        <label>Konversi Livebird ke Karkas</label>
        <input type="number" step="0.01" name="konversi_livebird_ke_karkas" class="form-control">
        </div>

    </div>


    <!-- produksi -->
    <div id="groupProduksiManual" style="display:none">
        
        <h5>Produksi Manual</h5>
        
        <div class="mb-3">
            <label>Produksi Daging (Kg)</label>
            <input type="text" name="produksi_daging" class="form-control">
        </div>
        
        <div class="mb-3">
            <label>Produksi Susu (Kg)</label>
            <input type="text" name="produksi_susu" class="form-control">
        </div>
        
        <div class="mb-3">
            <label>Produksi Telur (Kg)</label>
            <input type="text" name="produksi_telur" class="form-control">
        </div>
        
    </div>


<hr>

    <button class="btn btn-primary">
        Simpan Data Pokok
    </button>
</div>

</form>

<!-- format angka -->
 <script>

function formatRibuan(angka) {
    angka = angka.replace(/[^,\d]/g, '').toString();
    let split = angka.split(',');
    let sisa = split[0].length % 3;
    let rupiah = split[0].substr(0, sisa);
    let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        let separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    return split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
}

document.querySelectorAll('.angka').forEach(function(input) {

    input.addEventListener('keyup', function(e) {
        this.value = formatRibuan(this.value);
    });

});

</script>

<hr>

<!-- data yg diupload -->
<!-- <table class="table table-bordered">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Tahun</th>
            <th>Populasi</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($riwayat as $row): ?>
            <tr>
                <td><?= date('d-m-Y', strtotime($row['created_at'])) ?></td>
                <td><?= $row['tahun'] ?></td>
                <td><?= number_format($row['populasi']) ?></td>

                <td>
                    <?php if ($row['status'] == 'pending'): ?>
                        <span class="badge bg-warning">Pending</span>
                    <?php elseif ($row['status'] == 'approved'): ?>
                        <span class="badge bg-success">Approved</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Rejected</span>
                    <?php endif ?>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table> -->


</div>
</div>
</div>

<script>

document.addEventListener('DOMContentLoaded', function () {

const jenisSelect = document.getElementById('jenis_ternak');
const modeSelect = document.querySelector('[name="mode_inputan"]');

const groupPemotongan = document.getElementById('groupPemotongan');
const groupPopulasi = document.getElementById('groupPopulasi');
const groupManual = document.getElementById('groupProduksiManual');

function updateForm(){

    const kategori = jenisSelect.options[jenisSelect.selectedIndex]?.dataset.kategori;
    const mode = modeSelect.value;

    // reset semua
    groupPemotongan.style.display = 'none';
    groupPopulasi.style.display = 'none';
    groupManual.style.display = 'none';

    if(!kategori) return;

    // populasi selalu muncul
    groupPopulasi.style.display = 'block';

    // sapi potong
    if(kategori === 'besar_potong'){
        groupPemotongan.style.display = 'block';
    }

    // produksi manual
    if(mode === 'manual'){
        groupManual.style.display = 'block';
    }

}

jenisSelect.addEventListener('change', updateForm);
modeSelect.addEventListener('change', updateForm);

});

</script>

<!--  -->
<script>
const modeSelect = document.querySelector('[name="mode_inputan"]');
const groupManual = document.getElementById('groupProduksiManual');

modeSelect.addEventListener('change', function() {

    const mode = this.value;

    groupManual.style.display = 'none';

    if (mode === 'manual') {
        groupManual.style.display = 'block';
    }

});
</script>

<!-- parameter -->
 <script>

document.addEventListener('DOMContentLoaded', function(){

const jenisSelect = document.getElementById('jenis_ternak');
const modeSelect = document.querySelector('[name="mode_inputan"]');

const groupParameter = document.getElementById('groupParameter');

const paramPotong = document.getElementById('paramPotong');
const paramSusu = document.getElementById('paramSusu');
const paramTelur = document.getElementById('paramTelur');
const paramPedaging = document.getElementById('paramPedaging');

function updateParameter(){

const kategori = jenisSelect.options[jenisSelect.selectedIndex]?.dataset.kategori;
const susu = jenisSelect.options[jenisSelect.selectedIndex]?.dataset.susu;
const telur = jenisSelect.options[jenisSelect.selectedIndex]?.dataset.telur;

const mode = modeSelect.value;

groupParameter.style.display = 'none';

paramPotong.style.display = 'none';
paramSusu.style.display = 'none';
paramTelur.style.display = 'none';
paramPedaging.style.display = 'none';

if(mode !== 'parameter') return;

groupParameter.style.display = 'block';

if(kategori === 'besar_potong'){
paramPotong.style.display = 'block';
}

if(susu == "1"){
paramSusu.style.display = 'block';
}

if(telur == "1"){
paramTelur.style.display = 'block';
}

if(kategori === 'unggas_pedaging'){
paramPedaging.style.display = 'block';
}

}

jenisSelect.addEventListener('change', updateParameter);
modeSelect.addEventListener('change', updateParameter);

});

</script>

<?= $this->include('layout/user/footer') ?>
