<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Baitul Maal - Constellation of Giving
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --gold-light: #fff2cd;
        --gold-main: #d4af37;
        --gold-dark: #aa771c;
        --islamic-green: #021a0f;
        --bg-dark: #010402;
    }

    body {
        background-color: var(--bg-dark);
        background-image: radial-gradient(circle at 50% 10%, rgba(212,175,55,0.1) 0%, transparent 80%);
        font-family: 'Inter', sans-serif;
        color: #fff;
        min-height: 100vh;
        overflow-x: hidden;
    }

    .maal-wrapper {
        position: relative;
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
        z-index: 10;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    /* HEADER */
    .maal-header {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 50px;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: var(--gold-main);
        text-decoration: none;
        font-size: 0.85rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        font-weight: 600;
        transition: all 0.4s;
        padding: 10px 20px;
        border-radius: 30px;
        border: 1px solid rgba(212,175,55,0.2);
        background: rgba(0,0,0,0.4);
        backdrop-filter: blur(10px);
    }
    .btn-back:hover {
        background: rgba(212,175,55,0.1);
        transform: translateX(-5px);
        box-shadow: 0 5px 15px rgba(212,175,55,0.1);
    }

    .page-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        color: var(--gold-main);
        margin: 0;
        text-align: right;
    }

    /* THE GOLDEN WELL (Visualizer) */
    .well-container {
        position: relative;
        width: 350px;
        height: 350px;
        margin: 40px 0 80px 0;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .well-outer-ring {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 2px dashed rgba(212,175,55,0.3);
        animation: spin 30s linear infinite;
    }

    .well-inner-ring {
        position: absolute;
        width: 80%;
        height: 80%;
        border-radius: 50%;
        border: 1px solid rgba(212,175,55,0.6);
        box-shadow: 0 0 50px rgba(212,175,55,0.1), inset 0 0 50px rgba(212,175,55,0.1);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background: radial-gradient(circle, rgba(2,26,15,0.8) 0%, rgba(0,0,0,0.9) 100%);
        backdrop-filter: blur(10px);
        z-index: 10;
        transition: box-shadow 0.5s;
    }

    .well-inner-ring.glow {
        box-shadow: 0 0 100px rgba(212,175,55,0.5), inset 0 0 80px rgba(212,175,55,0.4);
    }

    @keyframes spin { 100% { transform: rotate(360deg); } }

    .total-amount {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        color: #fff;
        margin: 0;
        text-shadow: 0 0 20px rgba(212,175,55,0.5);
    }
    .amount-label {
        color: var(--gold-dark);
        font-size: 0.8rem;
        letter-spacing: 3px;
        text-transform: uppercase;
        margin-top: 5px;
    }

    /* PARTICLES */
    .particle {
        position: absolute;
        width: 10px; height: 10px;
        background: var(--gold-main);
        border-radius: 50%;
        box-shadow: 0 0 15px var(--gold-light), 0 0 30px var(--gold-main);
        pointer-events: none;
        z-index: 20;
    }

    /* ACTION BUTTON */
    .btn-donate {
        background: linear-gradient(135deg, var(--gold-dark) 0%, var(--gold-main) 100%);
        color: #000;
        border: none;
        padding: 15px 40px;
        font-size: 1.1rem;
        font-weight: bold;
        letter-spacing: 2px;
        text-transform: uppercase;
        border-radius: 50px;
        cursor: pointer;
        box-shadow: 0 10px 30px rgba(212,175,55,0.3);
        transition: transform 0.3s, box-shadow 0.3s;
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 60px;
    }
    .btn-donate:hover {
        transform: translateY(-5px) scale(1.05);
        box-shadow: 0 15px 40px rgba(212,175,55,0.5);
    }
    .btn-donate i { font-size: 1.3rem; }

    /* CAMPAIGN CARDS */
    .campaign-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        width: 100%;
    }

    .campaign-card {
        background: rgba(10, 15, 12, 0.6);
        border: 1px solid rgba(212,175,55,0.15);
        border-radius: 20px;
        padding: 30px;
        position: relative;
        backdrop-filter: blur(10px);
        transition: transform 0.4s;
    }
    .campaign-card:hover {
        transform: translateY(-10px);
        border-color: rgba(212,175,55,0.4);
    }

    .camp-icon {
        width: 50px; height: 50px;
        background: rgba(212,175,55,0.1);
        border-radius: 50%;
        display: flex; justify-content: center; align-items: center;
        color: var(--gold-main);
        font-size: 1.5rem;
        margin-bottom: 20px;
        border: 1px solid rgba(212,175,55,0.3);
    }

    .camp-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.3rem;
        margin: 0 0 10px 0;
    }
    .camp-desc {
        font-size: 0.85rem;
        color: #8899a6;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .progress-track {
        width: 100%;
        height: 6px;
        background: rgba(255,255,255,0.1);
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 10px;
    }
    .progress-fill {
        height: 100%;
        background: var(--gold-main);
        box-shadow: 0 0 10px var(--gold-main);
        border-radius: 3px;
    }
    .progress-stats {
        display: flex;
        justify-content: space-between;
        font-size: 0.75rem;
        color: var(--gold-main);
        font-weight: bold;
    }

    /* PANEL TRANSAKSI */
    .transactions-panel {
        position: fixed; top: 0; right: -450px; width: 400px; height: 100vh;
        background: rgba(10, 15, 12, 0.95); backdrop-filter: blur(20px);
        border-left: 1px solid rgba(212,175,55,0.3); z-index: 1000;
        transition: right 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        padding: 40px 30px; display: flex; flex-direction: column; gap: 20px;
        overflow-y: auto; color: #fff; box-shadow: -20px 0 50px rgba(0,0,0,0.8);
    }
    .transactions-panel.open { right: 0; }
    
    .panel-title { font-family: 'Playfair Display', serif; color: var(--gold-main); font-size: 1.5rem; letter-spacing: 2px; border-bottom: 1px solid rgba(212,175,55,0.3); padding-bottom: 10px; margin-bottom: 10px; }
    .maal-form input, .maal-form select, .maal-form textarea {
        width: 100%; background: rgba(0,0,0,0.5); border: 1px solid rgba(212,175,55,0.3);
        color: #fff; padding: 12px; margin-bottom: 15px; border-radius: 5px; font-family: 'Inter', sans-serif;
    }
    .btn-submit-maal { width: 100%; background: var(--gold-main); color: #000; border: none; padding: 12px; font-weight: bold; cursor: pointer; border-radius: 5px; letter-spacing: 2px; text-transform: uppercase; }
    
    .tx-card { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); padding: 15px; border-radius: 8px; margin-bottom: 10px; display: flex; align-items: center; gap: 15px; }
    .tx-type-icon { width: 40px; height: 40px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 1.2rem; }
    .tx-in { background: rgba(0,255,136,0.1); color: #00ff88; }
    .tx-out { background: rgba(255,51,102,0.1); color: #ff3366; }

    @media (max-width: 768px) {
        .maal-header { flex-direction: column-reverse; gap: 20px; align-items: center; }
        .page-title { text-align: center; }
        .well-container { width: 280px; height: 280px; }
        .total-amount { font-size: 2rem; }
        .transactions-panel { width: 100%; right: -100%; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="maal-wrapper">
    
    <header class="maal-header">
        <a href="/fitur" class="btn-back">
            <i class="fa-solid fa-chevron-left"></i> <?= cms_text('maal_btn_back', 'Vault') ?>
        </a>
        <h1 class="page-title"><?= cms_text('maal_title', 'Baitul Maal') ?></h1>
    </header>

    <!-- Visualizer / Golden Well -->
    <div class="well-container" id="goldenWell">
        <div class="well-outer-ring"></div>
        <div class="well-inner-ring" id="wellInner">
            <h2 class="total-amount" id="totalAmount">Rp <?= number_format($saldo_akhir ?? 0, 0, ',', '.') ?></h2>
            <div class="amount-label"><?= cms_text('maal_label_saldo', 'Total Saldo Kas') ?></div>
        </div>
    </div>

    <!-- Action Button -->
    <?php if(!empty($can_manage)): ?>
    <button class="btn-donate" id="btnTogglePanel">
        <i class="fa-solid fa-hand-holding-dollar"></i> <?= cms_text('maal_btn_record', 'Catat Transaksi') ?>
    </button>
    <?php else: ?>
    <div style="background: rgba(212,175,55,0.05); border: 1px solid rgba(212,175,55,0.15); border-radius: 16px; padding: 20px 30px; margin-bottom: 60px; text-align: center; max-width: 500px;">
        <i class="fa-solid fa-lock" style="color: rgba(212,175,55,0.4); font-size: 1.5rem; margin-bottom: 10px;"></i>
        <div style="font-size: 0.85rem; color: #888;"><?= cms_html('maal_notice_role', 'Pencatatan transaksi hanya dapat dilakukan oleh <strong style="color: var(--gold-main);">Bendahara</strong> atau <strong style="color: var(--gold-main);">Admin</strong>.') ?></div>
    </div>
    <?php endif; ?>

    <!-- Specific Campaigns -->
    <div class="campaign-grid">
        <div class="campaign-card">
            <div class="camp-icon"><i class="fa-solid fa-mosque"></i></div>
            <h3 class="camp-title"><?= cms_text('maal_camp1_title', 'Wakaf Sumur & Masjid') ?></h3>
            <p class="camp-desc"><?= cms_text('maal_camp1_desc', 'Pembangunan fasilitas air bersih dan perluasan area shalat di pelosok Nusa Tenggara.') ?></p>
            <div class="progress-track"><div class="progress-fill" style="width: 75%;"></div></div>
            <div class="progress-stats"><span><?= cms_text('maal_camp1_current', 'Terkumpul: 75%') ?></span><span><?= cms_text('maal_camp1_target', 'Target: Rp 200 Jt') ?></span></div>
        </div>

        <div class="campaign-card">
            <div class="camp-icon"><i class="fa-solid fa-book-open-reader"></i></div>
            <h3 class="camp-title"><?= cms_text('maal_camp2_title', 'Beasiswa Perintis') ?></h3>
            <p class="camp-desc"><?= cms_text('maal_camp2_desc', 'Bantuan dana pendidikan penuh untuk 10 santri tahfidz berprestasi hingga sarjana.') ?></p>
            <div class="progress-track"><div class="progress-fill" style="width: 40%;"></div></div>
            <div class="progress-stats"><span><?= cms_text('maal_camp2_current', 'Terkumpul: 40%') ?></span><span><?= cms_text('maal_camp2_target', 'Target: Rp 500 Jt') ?></span></div>
        </div>

        <div class="campaign-card">
            <div class="camp-icon"><i class="fa-solid fa-heart-pulse"></i></div>
            <h3 class="camp-title"><?= cms_text('maal_camp3_title', 'Dana Darurat Ukhuwah') ?></h3>
            <p class="camp-desc"><?= cms_text('maal_camp3_desc', 'Kas siaga untuk membantu entitas angkatan atau keluarga inti yang tertimpa musibah/sakit keras.') ?></p>
            <div class="progress-track"><div class="progress-fill" style="width: 90%;"></div></div>
            <div class="progress-stats"><span><?= cms_text('maal_camp3_current', 'Terkumpul: 90%') ?></span><span><?= cms_text('maal_camp3_target', 'Target: Rp 100 Jt') ?></span></div>
        </div>
    </div>
</div>

<!-- Panel Transaksi (Slide) — Hanya untuk Bendahara/Admin -->
<?php if(!empty($can_manage)): ?>
<div class="transactions-panel" id="txPanel">
    <div class="panel-title"><?= cms_text('maal_panel_title', 'Pencatatan Ledger') ?></div>
    <form action="/baitul-maal/store" method="POST" class="maal-form">
        <?= csrf_field() ?>
        <select name="type" required>
            <option value="Pemasukan">Pemasukan (Khidmah/Infaq)</option>
            <option value="Pengeluaran">Pengeluaran (Operasional)</option>
        </select>
        <input type="text" name="amount" placeholder="<?= cms_raw('maal_ph_amount', 'Nominal (Misal: 500000)') ?>" required>
        <textarea name="description" rows="2" placeholder="<?= cms_raw('maal_ph_desc', 'Keterangan transaksi...') ?>" required></textarea>
        <label style="display:flex; align-items:center; gap:10px; font-size:0.8rem; color:#888; margin-bottom:15px; cursor:pointer;">
            <input type="checkbox" name="anonim" value="1" style="width:auto; margin:0;"> <?= cms_text('maal_anon_label', 'Hamba Allah (Anonim)') ?>
        </label>
        <button type="submit" class="btn-submit-maal"><?= cms_text('maal_btn_submit', 'Simpan Transaksi') ?></button>
    </form>

    <div class="panel-title" style="margin-top:20px;"><?= cms_text('maal_history_title', 'Riwayat Transaksi') ?></div>
    <?php if(!empty($transactions)): ?>
        <?php foreach($transactions as $tx): ?>
            <div class="tx-card">
                <div class="tx-type-icon <?= $tx['transaction_type'] == 'Pemasukan' ? 'tx-in' : 'tx-out' ?>">
                    <i class="fa-solid <?= $tx['transaction_type'] == 'Pemasukan' ? 'fa-arrow-down' : 'fa-arrow-up' ?>"></i>
                </div>
                <div style="flex:1;">
                    <div style="font-weight:bold; font-size:1.1rem;">Rp <?= number_format($tx['amount'], 0, ',', '.') ?></div>
                    <div style="font-size:0.8rem; color:var(--gold-main);"><?= $tx['user_id'] ? esc($tx['nama_panggilan']) : 'Hamba Allah' ?></div>
                    <div style="font-size:0.75rem; color:#888; margin-top:3px;"><?= esc($tx['description']) ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div style="text-align:center; padding:20px; color:#555; font-size:0.8rem;"><?= cms_text('maal_history_empty', 'Belum ada catatan transaksi.') ?></div>
    <?php endif; ?>
</div>
<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="/vendor/gsap/gsap.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    // Intro Animations
    gsap.from(".maal-header", { y: -30, opacity: 0, duration: 1, ease: "power3.out" });
    gsap.from(".well-container", { scale: 0.5, opacity: 0, duration: 1.5, ease: "back.out(1.5)" });
    gsap.from(".btn-donate", { y: 30, opacity: 0, duration: 1, delay: 0.5, ease: "power2.out" });
    gsap.from(".campaign-card", { y: 50, opacity: 0, duration: 0.8, stagger: 0.2, delay: 0.8, ease: "power2.out" });

    // Toggle Panel (hanya jika user adalah bendahara/admin)
    const btnToggle = document.getElementById('btnTogglePanel');
    const txPanel = document.getElementById('txPanel');
    
    if (btnToggle && txPanel) {
        btnToggle.addEventListener('click', () => {
            txPanel.classList.toggle('open');
            if(navigator.vibrate) navigator.vibrate(20);
        });

        // Auto-open panel on flashdata success/error
        <?php if(session()->getFlashdata('success') || session()->getFlashdata('error')): ?>
            txPanel.classList.add('open');
        <?php endif; ?>
    }
});
</script>
<?= $this->endSection() ?>
