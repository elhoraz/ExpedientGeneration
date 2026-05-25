<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Baitul Maal - Constellation of Giving
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --gold-light: #f9f5e8;
        --gold-main: #d4af37;
        --gold-dark: #aa771c;
        --islamic-green: #021a0f;
        --bg-dark: #050505;
        --card-bg: rgba(15, 15, 18, 0.85);
        --text-muted: #8b9ba8;
    }

    body {
        background-color: var(--bg-dark);
        background-image: radial-gradient(circle at 50% 0%, rgba(212,175,55,0.1) 0%, transparent 70%);
        font-family: 'Inter', sans-serif;
        color: #fff;
        min-height: 100vh;
        overflow-x: hidden;
    }

    .maal-wrapper {
        position: relative;
        width: 100%;
        max-width: 1000px;
        margin: 0 auto;
        padding: 60px 20px;
        z-index: 10;
        display: flex;
        flex-direction: column;
    }

    /* HEADER */
    .maal-header {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: var(--gold-main);
        text-decoration: none;
        font-size: 0.9rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        font-weight: 500;
        transition: all 0.4s;
    }
    .btn-back:hover {
        color: #fff;
        transform: translateX(-5px);
    }

    .page-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.2rem;
        color: var(--gold-main);
        margin: 0;
        text-align: right;
        letter-spacing: 2px;
    }

    /* FINANCIAL DASHBOARD (3-Pillar) */
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 50px;
    }

    .stat-card {
        background: var(--card-bg);
        border: 1px solid rgba(212,175,55,0.2);
        border-radius: 12px;
        padding: 30px 20px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        backdrop-filter: blur(10px);
        position: relative;
        overflow: hidden;
    }
    .stat-card.primary {
        background: radial-gradient(circle at top, rgba(212,175,55,0.15) 0%, rgba(10,10,12,0.9) 100%);
        border-color: var(--gold-main);
        grid-column: span 3;
        padding: 50px 20px;
    }
    .stat-card.primary .stat-value {
        font-size: 3.5rem;
        text-shadow: 0 0 30px rgba(212,175,55,0.5);
        margin-bottom: 10px;
    }

    .stat-label {
        font-size: 0.85rem;
        color: var(--text-muted);
        letter-spacing: 3px;
        text-transform: uppercase;
        margin-bottom: 15px;
        font-weight: 600;
    }
    .stat-value {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        color: #fff;
        margin: 0;
    }
    
    .stat-icon {
        position: absolute;
        bottom: -20px;
        right: -20px;
        font-size: 6rem;
        opacity: 0.05;
        color: var(--gold-main);
    }

    .text-in { color: #00ff88 !important; }
    .text-out { color: #ff5555 !important; }

    /* ACTION BUTTON */
    .btn-donate {
        background: var(--gold-main);
        color: #000;
        border: none;
        padding: 15px 40px;
        font-size: 1rem;
        font-weight: bold;
        letter-spacing: 2px;
        text-transform: uppercase;
        border-radius: 50px;
        cursor: pointer;
        box-shadow: 0 10px 30px rgba(212,175,55,0.3);
        transition: transform 0.3s, box-shadow 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 15px;
        margin: 0 auto 50px auto;
    }
    .btn-donate:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(212,175,55,0.5);
    }

    /* THE OPEN LEDGER */
    .ledger-section {
        background: var(--card-bg);
        border: 1px solid rgba(255,255,255,0.05);
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.4);
    }
    
    .ledger-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 1px dashed rgba(255,255,255,0.1);
    }
    .ledger-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        color: var(--gold-light);
        margin: 0;
    }
    .ledger-subtitle {
        font-size: 0.8rem;
        color: var(--text-muted);
        letter-spacing: 1px;
    }

    .tx-item { 
        padding: 20px 0; 
        border-bottom: 1px solid rgba(255,255,255,0.05); 
        display: flex; 
        align-items: center; 
        justify-content: space-between;
        gap: 20px; 
        transition: background 0.3s;
    }
    .tx-item:last-child { border-bottom: none; }
    .tx-item:hover { background: rgba(255,255,255,0.02); }

    .tx-left {
        display: flex;
        align-items: center;
        gap: 20px;
        flex: 1;
    }

    .tx-type-icon { 
        width: 50px; height: 50px; 
        border-radius: 50%; 
        display: flex; justify-content: center; align-items: center; 
        font-size: 1.2rem; 
        flex-shrink: 0;
    }
    .tx-in-bg { background: rgba(0,255,136,0.1); color: #00ff88; border: 1px solid rgba(0,255,136,0.2); }
    .tx-out-bg { background: rgba(255,85,85,0.1); color: #ff5555; border: 1px solid rgba(255,85,85,0.2); }

    .tx-details { flex: 1; }
    .tx-title { font-family: 'Inter', sans-serif; font-size: 1.1rem; font-weight: 600; color: #fff; margin-bottom: 8px; line-height: 1.4; }
    .tx-meta { display: flex; gap: 15px; font-size: 0.8rem; color: var(--text-muted); }
    
    .tx-amount { font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: bold; text-align: right; white-space: nowrap; }
    .tx-amount.in { color: #00ff88; text-shadow: 0 0 10px rgba(0,255,136,0.3); }
    .tx-amount.out { color: #ff5555; text-shadow: 0 0 10px rgba(255,85,85,0.3); }

    .donor-name { color: var(--gold-main); font-weight: 500; font-style: italic; }

    /* PANEL TRANSAKSI (Form Admin Saja) */
    .transactions-panel {
        position: fixed; top: 0; right: -450px; width: 400px; height: 100vh;
        background: rgba(10, 10, 12, 0.98); backdrop-filter: blur(20px);
        border-left: 1px solid rgba(212,175,55,0.3); z-index: 1000;
        transition: right 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        padding: 40px 30px; display: flex; flex-direction: column; gap: 20px;
        box-shadow: -20px 0 50px rgba(0,0,0,0.8);
    }
    .transactions-panel.open { right: 0; }
    
    .panel-title { font-family: 'Playfair Display', serif; color: var(--gold-main); font-size: 1.5rem; letter-spacing: 2px; border-bottom: 1px solid rgba(212,175,55,0.3); padding-bottom: 10px; margin-bottom: 20px; }
    
    .maal-form label { display: block; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 5px; letter-spacing: 1px; }
    .maal-form input, .maal-form select, .maal-form textarea {
        width: 100%; background: rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.1);
        color: #fff; padding: 12px; margin-bottom: 20px; border-radius: 6px; font-family: 'Inter', sans-serif;
        transition: border-color 0.3s;
    }
    .maal-form input:focus, .maal-form select:focus, .maal-form textarea:focus { border-color: var(--gold-main); outline: none; }
    
    .btn-submit-maal { width: 100%; background: var(--gold-main); color: #000; border: none; padding: 15px; font-weight: bold; cursor: pointer; border-radius: 6px; letter-spacing: 2px; text-transform: uppercase; transition: background 0.3s; }
    .btn-submit-maal:hover { background: var(--gold-light); }
    
    .btn-close-panel { position: absolute; top: 20px; right: 20px; background: transparent; border: none; color: #fff; font-size: 1.5rem; cursor: pointer; opacity: 0.5; transition: opacity 0.3s; }
    .btn-close-panel:hover { opacity: 1; }

    @media (max-width: 768px) {
        .maal-header { flex-direction: column-reverse; gap: 20px; align-items: center; }
        .page-title { text-align: center; }
        .dashboard-grid { grid-template-columns: 1fr; gap: 15px; }
        .stat-card.primary { grid-column: span 1; padding: 30px 20px; }
        .stat-card.primary .stat-value { font-size: 2.5rem; }
        .transactions-panel { width: 100%; right: -100%; }
        .tx-item { flex-direction: column; align-items: flex-start; gap: 15px; }
        .tx-left { width: 100%; }
        .tx-meta { flex-direction: column; gap: 5px; }
        .tx-amount { text-align: left; font-size: 1.3rem; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="maal-wrapper">
    
    <header class="maal-header">
        <a href="/fitur" class="btn-back">
            <i class="fa-solid fa-arrow-left-long"></i> <?= cms_text('maal_btn_back', 'Kembali ke Vault') ?>
        </a>
        <h1 class="page-title"><?= cms_text('maal_title', 'Baitul Maal') ?></h1>
    </header>

    <!-- FINANCIAL DASHBOARD -->
    <div class="dashboard-grid" id="financeDashboard">
        <div class="stat-card primary">
            <i class="fa-solid fa-scale-balanced stat-icon"></i>
            <div class="stat-label"><?= cms_text('maal_label_saldo', 'Total Saldo Kas Terkini') ?></div>
            <h2 class="stat-value">Rp <?= number_format($saldo_akhir ?? 0, 0, ',', '.') ?></h2>
        </div>
        
        <div class="stat-card">
            <i class="fa-solid fa-arrow-turn-down stat-icon" style="color:#00ff88;"></i>
            <div class="stat-label"><?= cms_text('maal_label_in', 'Total Pemasukan') ?></div>
            <h2 class="stat-value text-in">Rp <?= number_format($total_pemasukan ?? 0, 0, ',', '.') ?></h2>
        </div>
        
        <div class="stat-card">
            <i class="fa-solid fa-arrow-turn-up stat-icon" style="color:#ff5555;"></i>
            <div class="stat-label"><?= cms_text('maal_label_out', 'Total Pengeluaran') ?></div>
            <h2 class="stat-value text-out">Rp <?= number_format($total_pengeluaran ?? 0, 0, ',', '.') ?></h2>
        </div>
    </div>

    <!-- Action Button (Admin Only) -->
    <?php if(!empty($can_manage)): ?>
    <div style="text-align: center;">
        <button class="btn-donate" id="btnTogglePanel">
            <i class="fa-solid fa-file-invoice-dollar"></i> <?= cms_text('maal_btn_record', 'Catat Transaksi Baru') ?>
        </button>
    </div>
    <?php endif; ?>

    <!-- THE OPEN LEDGER (Public Transparency) -->
    <div class="ledger-section" id="openLedger">
        <div class="ledger-header">
            <div>
                <h2 class="ledger-title"><?= cms_text('maal_history_title', 'Buku Besar Kas') ?></h2>
                <div class="ledger-subtitle"><?= cms_text('maal_history_subtitle', 'Laporan Transparansi Arus Keuangan') ?></div>
            </div>
            <div><i class="fa-solid fa-book-open" style="color:var(--gold-main); font-size:2rem; opacity:0.5;"></i></div>
        </div>
        
        <div class="ledger-list">
            <?php if(!empty($transactions)): ?>
                <?php foreach($transactions as $tx): ?>
                    <div class="tx-item">
                        <div class="tx-left">
                            <div class="tx-type-icon <?= $tx['transaction_type'] == 'Pemasukan' ? 'tx-in-bg' : 'tx-out-bg' ?>">
                                <i class="fa-solid <?= $tx['transaction_type'] == 'Pemasukan' ? 'fa-arrow-down' : 'fa-arrow-up' ?>"></i>
                            </div>
                            <div class="tx-details">
                                <div class="tx-title"><?= esc($tx['description']) ?></div>
                                <div class="tx-meta">
                                    <div><i class="fa-regular fa-user"></i> <span class="donor-name"><?= $tx['user_id'] ? esc($tx['nama_panggilan']) : 'Hamba Allah' ?></span></div>
                                    <div><i class="fa-regular fa-calendar"></i> <?= date('d M Y, H:i', strtotime($tx['created_at'])) ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="tx-amount <?= $tx['transaction_type'] == 'Pemasukan' ? 'in' : 'out' ?>">
                            <?= $tx['transaction_type'] == 'Pemasukan' ? '+' : '-' ?> Rp <?= number_format($tx['amount'], 0, ',', '.') ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align:center; padding:50px 20px; color:var(--text-muted);">
                    <i class="fa-solid fa-folder-open" style="font-size:3rem; margin-bottom:15px; opacity:0.3;"></i><br>
                    <?= cms_text('maal_history_empty', 'Belum ada catatan transaksi di dalam buku besar ini.') ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<!-- Panel Transaksi (Form Slide) — Hanya untuk Bendahara/Admin -->
<?php if(!empty($can_manage)): ?>
<div class="transactions-panel" id="txPanel">
    <button class="btn-close-panel" id="btnClosePanel"><i class="fa-solid fa-xmark"></i></button>
    <div class="panel-title"><?= cms_text('maal_panel_title', 'Pencatatan Entri') ?></div>
    
    <div style="font-size:0.85rem; color:var(--text-muted); margin-bottom:20px; line-height:1.5;">
        Anda login sebagai otoritas pengelola kas. Harap pastikan data dimasukkan dengan teliti untuk menjaga integritas Buku Besar.
    </div>

    <form action="/baitul-maal/store" method="POST" class="maal-form">
        <?= csrf_field() ?>
        
        <label>JENIS TRANSAKSI</label>
        <select name="type" required>
            <option value="Pemasukan"><?= cms_text('maal_opt_in', 'Pemasukan (Khidmah/Infaq)') ?></option>
            <option value="Pengeluaran"><?= cms_text('maal_opt_out', 'Pengeluaran (Operasional)') ?></option>
        </select>
        
        <label>NOMINAL (RUPIAH)</label>
        <input type="number" name="amount" placeholder="<?= cms_raw('maal_ph_amount', 'Contoh: 500000') ?>" required min="1">
        
        <label>KETERANGAN/TUJUAN</label>
        <textarea name="description" rows="3" placeholder="<?= cms_raw('maal_ph_desc', 'Deskripsi detail transaksi...') ?>" required></textarea>
        
        <label style="display:flex; align-items:center; gap:10px; flex-direction:row; cursor:pointer; margin-bottom: 25px;">
            <input type="checkbox" name="anonim" value="1" style="width:auto; margin:0;"> 
            <span style="color:#fff; font-size:0.9rem;"><?= cms_text('maal_anon_label', 'Catat Sebagai Hamba Allah (Anonim)') ?></span>
        </label>
        
        <button type="submit" class="btn-submit-maal"><i class="fa-solid fa-file-signature"></i> <?= cms_text('maal_btn_submit', 'Otorisasi Entri') ?></button>
    </form>
</div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="/vendor/gsap/gsap.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    // Intro Animations
    gsap.from(".maal-header", { y: -30, opacity: 0, duration: 1, ease: "power3.out" });
    gsap.from(".stat-card", { y: 40, opacity: 0, duration: 0.8, stagger: 0.15, ease: "back.out(1.2)" });
    gsap.from(".ledger-section", { y: 50, opacity: 0, duration: 1, delay: 0.4, ease: "power2.out" });

    // Toggle Panel (hanya jika user adalah bendahara/admin)
    const btnToggle = document.getElementById('btnTogglePanel');
    const btnClose = document.getElementById('btnClosePanel');
    const txPanel = document.getElementById('txPanel');
    
    if (txPanel) {
        if (btnToggle) {
            btnToggle.addEventListener('click', () => {
                txPanel.classList.add('open');
                if(navigator.vibrate) navigator.vibrate(20);
            });
        }
        if (btnClose) {
            btnClose.addEventListener('click', () => {
                txPanel.classList.remove('open');
            });
        }

        // Auto-open panel on flashdata success/error
        <?php if(session()->getFlashdata('success') || session()->getFlashdata('error')): ?>
            // txPanel.classList.add('open'); // Kita matikan auto-open agar user bisa melihat toast notification
        <?php endif; ?>
    }
});
</script>
<?= $this->endSection() ?>
