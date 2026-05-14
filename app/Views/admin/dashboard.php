<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Dashboard Analitik Eksekutif
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .admin-wrapper {
        padding: clamp(80px, 15vh, 120px) 20px 40px;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .admin-header {
        text-align: center;
        margin-bottom: 40px;
    }
    .admin-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2rem, 4vw, 3rem);
        color: #d4af37;
        text-transform: uppercase;
        letter-spacing: 3px;
        margin-bottom: 5px;
    }
    .admin-subtitle {
        color: var(--text-secondary);
        font-size: 0.9rem;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .metric-card {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        padding: 30px;
        text-align: center;
        transition: 0.3s;
    }
    .metric-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(212,175,55,0.1);
        border-color: rgba(212,175,55,0.4);
    }
    .metric-value {
        font-size: 3rem;
        font-family: 'Inter', sans-serif;
        font-weight: 900;
        color: #d4af37;
        margin-bottom: 10px;
    }
    .metric-label {
        font-size: 0.85rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .charts-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
    }
    @media (max-width: 900px) {
        .charts-grid {
            grid-template-columns: 1fr;
        }
    }

    .chart-panel {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        padding: 30px;
    }

    .panel-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        color: var(--text-primary);
        margin-bottom: 20px;
        border-bottom: 1px solid var(--glass-border);
        padding-bottom: 15px;
    }

    .top-page-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    .top-page-item:last-child {
        border-bottom: none;
    }
    .page-url {
        color: var(--text-primary);
        font-family: monospace;
        font-size: 0.9rem;
    }
    .page-visits {
        color: #d4af37;
        font-weight: bold;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="admin-wrapper">
    <div class="admin-header reveal-up">
        <h1 class="admin-title">Command Center</h1>
        <p class="admin-subtitle">Otoritas Analitik & Pantauan Sentral</p>
    </div>

    <div class="metrics-grid">
        <div class="metric-card reveal-up">
            <div class="metric-value"><?= number_format($totalUsers) ?></div>
            <div class="metric-label">Total Entitas Terdaftar</div>
        </div>
        <div class="metric-card reveal-up" style="transition-delay: 0.1s;">
            <div class="metric-value"><?= number_format($activeUsers) ?></div>
            <div class="metric-label">Entitas Aktif Berprestise</div>
        </div>
        <div class="metric-card reveal-up" style="transition-delay: 0.2s;">
            <div class="metric-value"><?= number_format(array_sum(json_decode($chartVisits))) ?></div>
            <div class="metric-label">Aktivitas 7 Hari Terakhir</div>
        </div>
    </div>

    <div class="charts-grid">
        <div class="chart-panel reveal-up" style="transition-delay: 0.3s;">
            <h2 class="panel-title">Laju Akses Portal (7 Hari)</h2>
            <canvas id="trafficChart" height="120"></canvas>
        </div>

        <div class="chart-panel reveal-up" style="transition-delay: 0.4s;">
            <h2 class="panel-title">Destinasi Favorit</h2>
            <?php foreach($topPages as $p): ?>
                <div class="top-page-item">
                    <span class="page-url"><?= esc($p['page_url']) ?></span>
                    <span class="page-visits"><?= number_format($p['total_visits']) ?> <i class="fa-solid fa-eye" style="font-size: 0.8rem; margin-left: 5px;"></i></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('trafficChart').getContext('2d');
    
    // Gradient fill for luxury feel
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(212, 175, 55, 0.5)'); // Gold transparent
    gradient.addColorStop(1, 'rgba(212, 175, 55, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= $chartDates ?>,
            datasets: [{
                label: 'Interaksi Jaringan',
                data: <?= $chartVisits ?>,
                borderColor: '#d4af37',
                borderWidth: 2,
                backgroundColor: gradient,
                fill: true,
                tension: 0.4, // Smooth curve
                pointBackgroundColor: '#030504',
                pointBorderColor: '#d4af37',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleColor: '#d4af37',
                    bodyColor: '#fff',
                    borderColor: '#d4af37',
                    borderWidth: 1,
                    displayColors: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                    ticks: { color: '#888', font: { family: 'Courier New' } }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#888', font: { family: 'Inter' } }
                }
            }
        }
    });
});
</script>
<?= $this->endSection() ?>
