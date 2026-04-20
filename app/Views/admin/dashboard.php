<?= $this->include('layout/admin/header') ?>
<?= $this->include('layout/admin/navbar') ?>
<?= $this->include('layout/sidebar/sidebar_admin') ?>


<div class="main-container p-4">
    <div class="content-wrapper">
    <h4 class="fw-bold mb-4">📊 Dashboard Admin</h4>

    <!-- STAT CARD -->
    <div class="row">

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm text-center p-4">
                <div class="text-muted">Total Infrastruktur</div>
                <h2 class="fw-bold"><?= $totalInfra ?></h2>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm text-center p-4">
                <div class="text-muted">Total User</div>
                <h2 class="fw-bold"><?= $totalUser ?></h2>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm text-center p-4">
                <div class="text-warning">Pending</div>
                <h2 class="fw-bold text-warning"><?= $pending ?></h2>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm text-center p-4">
                <div class="text-success">Approved</div>
                <h2 class="fw-bold text-success"><?= $approved ?></h2>
            </div>
        </div>

    </div>
        <!-- alert -->
        <div class="alert alert-warning mt-3">
        Pending Hari Ini: <b><?= $pendingToday ?></b> data
        </div>


        <!-- CHART + LIST -->
        <div class="row mt-4">

            <!-- Chart -->
            <div class="col-md-6 mb-3">
                <div class="card border-0 shadow-sm p-4">
                    <h6 class="fw-bold mb-3">Status Infrastruktur</h6>
                    <div style="max-width:300px; margin:auto;">
            <canvas id="statusChart"></canvas>
        </div>
                </div>
            </div>
            <!-- chart reject -->
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm text-center p-4">
                    <div class="text-danger">Rejected</div>
                    <h2 class="fw-bold text-danger"><?= $rejected ?></h2>
                </div>
            </div>

            <!-- Top Infrastruktur -->
            <div class="col-md-6 mb-3">
                <div class="card border-0 shadow-sm p-4">
                    <h6 class="fw-bold mb-3">Top Jenis Infrastruktur</h6>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($topJenis as $row): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><?= esc($row['jenis_infrastruktur']) ?></span>
                                <span class="badge bg-primary rounded-pill">
                                    <?= $row['total'] ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('statusChart');

new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Pending', 'Approved', 'Rejected'],
        datasets: [{
            data: <?= json_encode($chartStatus) ?>,
            borderWidth: 1
        }]
    },
    options: {
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
<?= $this->include('layout/admin/footer') ?>
