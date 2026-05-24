<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Agenda & Eksibisi Expedient
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .event-header {
        text-align: center;
        padding: clamp(60px, 10vh, 100px) 20px 40px;
        position: relative;
    }
    .event-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2.5rem, 5vw, 4rem);
        color: #d4af37;
        margin-bottom: 10px;
        letter-spacing: 2px;
        text-transform: uppercase;
    }
    .event-subtitle {
        color: var(--text-secondary);
        font-size: 1rem;
        letter-spacing: 3px;
        text-transform: uppercase;
        font-family: 'Courier New', monospace;
    }

    .event-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 0 20px 80px;
    }

    .create-card {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 50px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    }

    .luxury-input {
        width: 100%;
        background: rgba(0,0,0,0.2);
        border: 1px solid rgba(212,175,55,0.3);
        padding: 15px 20px;
        border-radius: 8px;
        color: var(--text-primary);
        font-family: 'Inter', sans-serif;
        font-size: 1rem;
        margin-bottom: 20px;
        transition: 0.3s;
    }
    :root[data-theme="light"] .luxury-input { background: rgba(255,255,255,0.5); border-color: rgba(212,175,55,0.5); }
    .luxury-input:focus {
        outline: none;
        border-color: #d4af37;
        box-shadow: 0 0 15px rgba(212,175,55,0.2);
    }

    .btn-gold {
        background: linear-gradient(135deg, #d4af37, #aa771c);
        color: #000;
        border: none;
        padding: 15px 30px;
        border-radius: 8px;
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        letter-spacing: 2px;
        cursor: pointer;
        text-transform: uppercase;
        transition: 0.3s;
        display: inline-block;
        width: 100%;
    }
    .btn-gold:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(212,175,55,0.3);
    }

    .timeline {
        position: relative;
        padding-left: 30px;
    }
    .timeline::before {
        content: '';
        position: absolute;
        top: 0; left: 0; bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, transparent, #d4af37, transparent);
    }

    .event-card {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        border: 1px solid var(--glass-border);
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 40px;
        position: relative;
        transition: 0.3s;
    }
    .event-card:hover {
        transform: translateX(10px);
        border-color: rgba(212,175,55,0.5);
    }
    .event-card::before {
        content: '';
        position: absolute;
        top: 30px; left: -34px;
        width: 10px; height: 10px;
        background: #d4af37;
        border-radius: 50%;
        box-shadow: 0 0 15px #d4af37;
    }

    .event-date {
        font-family: 'Playfair Display', serif;
        color: #d4af37;
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 10px;
    }
    .event-title-display {
        font-size: 1.8rem;
        color: var(--text-primary);
        font-weight: 700;
        margin-bottom: 15px;
    }
    .event-desc {
        color: var(--text-secondary);
        line-height: 1.6;
        margin-bottom: 20px;
    }
    .event-meta {
        display: flex;
        gap: 20px;
        font-size: 0.9rem;
        color: var(--text-secondary);
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 1px dashed rgba(212,175,55,0.2);
    }
    .event-meta i { color: #d4af37; margin-right: 8px; }

    .rsvp-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }
    .rsvp-stats {
        display: flex;
        gap: 15px;
        font-size: 0.85rem;
    }
    .stat-badge {
        background: rgba(255,255,255,0.05);
        padding: 5px 12px;
        border-radius: 20px;
        border: 1px solid var(--glass-border);
    }
    .stat-badge.Hadir { border-color: #00ff88; color: #00ff88; }
    .stat-badge.Tentatif { border-color: #d4af37; color: #d4af37; }
    .stat-badge.Tidak { border-color: #ff3366; color: #ff3366; }

    .rsvp-buttons form { display: inline-block; }
    .btn-rsvp {
        background: transparent;
        border: 1px solid var(--glass-border);
        color: var(--text-primary);
        padding: 8px 16px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.85rem;
        transition: 0.3s;
        margin-left: 5px;
    }
    .btn-rsvp:hover { background: rgba(255,255,255,0.05); }
    .btn-rsvp.active { background: rgba(212,175,55,0.15); border-color: #d4af37; color: #d4af37; font-weight: bold; }

</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="event-header">
    <h1 class="event-title"><?= cms_text('event_title', 'Agenda & Eksibisi') ?></h1>
    <div class="event-subtitle"><?= cms_text('event_subtitle', 'Pertemuan Para Pelopor Peradaban') ?></div>
</div>

<div class="event-container">

    <!-- Form Create Event -->
    <div class="create-card">
        <h3 style="color:#d4af37; font-family:'Playfair Display', serif; font-size:1.5rem; margin-bottom:20px;"><?= cms_text('event_create_title', 'Jadwalkan Pertemuan Baru') ?></h3>
        <form action="/event/store" method="POST">
            <?= csrf_field() ?>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px;">
                <div style="grid-column: 1 / -1;">
                    <input type="text" name="title" class="luxury-input" placeholder="<?= cms_raw('event_ph_title', 'Nama Agenda / Acara') ?>" required style="margin-bottom:0;">
                </div>
                <div>
                    <label style="display:block; color:var(--text-secondary); font-size:0.85rem; margin-bottom:8px;"><?= cms_text('event_label_time', 'Waktu Pelaksanaan') ?></label>
                    <input type="datetime-local" name="event_date" class="luxury-input" required style="margin-bottom:0;">
                </div>
                <div>
                    <label style="display:block; color:var(--text-secondary); font-size:0.85rem; margin-bottom:8px;"><?= cms_text('event_label_loc', 'Lokasi / Tautan Panggilan') ?></label>
                    <input type="text" name="location" class="luxury-input" placeholder="<?= cms_raw('event_ph_loc', 'Lokasi Pertemuan') ?>" style="margin-bottom:0;">
                </div>
                <div style="grid-column: 1 / -1;">
                    <textarea name="description" class="luxury-input" rows="3" placeholder="<?= cms_raw('event_ph_desc', 'Deskripsi dan tujuan agenda...') ?>" required style="margin-bottom:0;"></textarea>
                </div>
                <div style="grid-column: 1 / -1;">
                    <button type="submit" class="btn-gold"><i class="fa-solid fa-calendar-plus" style="margin-right:10px;"></i> <?= cms_text('event_btn_submit', 'Siarkan Agenda') ?></button>
                </div>
            </div>
        </form>
    </div>

    <!-- Timeline Events -->
    <?php if(empty($events)): ?>
        <div style="text-align:center; color:var(--text-secondary); padding:50px; font-style:italic;"><?= cms_text('event_empty', 'Belum ada agenda yang dijadwalkan di masa mendatang.') ?></div>
    <?php else: ?>
        <div class="timeline">
            <?php foreach($events as $event): ?>
                <div class="event-card">
                    <div class="event-date"><?= date('d F Y', strtotime($event['event_date'])) ?> <span style="font-size:1rem; color:var(--text-secondary); font-family:'Inter', sans-serif; font-weight:normal;">| <?= date('H:i', strtotime($event['event_date'])) ?> WIB</span></div>
                    <div class="event-title-display"><?= esc($event['title']) ?></div>
                    <div class="event-desc"><?= nl2br(esc($event['description'])) ?></div>
                    
                    <div class="event-meta">
                        <div><i class="fa-solid fa-location-dot"></i> <?= esc($event['location'] ?: cms_raw('event_no_loc', 'Lokasi belum ditentukan')) ?></div>
                        <div><i class="fa-solid fa-user-pen"></i> <?= cms_text('event_scheduled_by', 'Dijadwalkan oleh') ?> <?= esc($event['creator_name']) ?></div>
                    </div>

                    <div class="rsvp-section">
                        <div class="rsvp-stats">
                            <div class="stat-badge Hadir"><i class="fa-solid fa-check"></i> <?= $event['stats']['Hadir'] ?> <?= cms_text('event_stat_hadir', 'Hadir') ?></div>
                            <div class="stat-badge Tentatif"><i class="fa-solid fa-question"></i> <?= $event['stats']['Tentatif'] ?> <?= cms_text('event_stat_tentatif', 'Tentatif') ?></div>
                            <div class="stat-badge Tidak"><i class="fa-solid fa-xmark"></i> <?= $event['stats']['Tidak Hadir'] ?> <?= cms_text('event_stat_absen', 'Absen') ?></div>
                        </div>

                        <div class="rsvp-buttons">
                            <span style="font-size:0.85rem; color:var(--text-secondary); margin-right:10px;"><?= cms_text('event_label_confirm', 'Konfirmasi Anda:') ?></span>
                            
                            <form action="/event/rsvp/<?= $event['id'] ?>" method="POST">
                                <?= csrf_field() ?>
                                <input type="hidden" name="status" value="Hadir">
                                <button type="submit" class="btn-rsvp <?= $event['my_rsvp'] === 'Hadir' ? 'active' : '' ?>"><?= cms_text('event_btn_hadir', 'Hadir') ?></button>
                            </form>
                            
                            <form action="/event/rsvp/<?= $event['id'] ?>" method="POST">
                                <?= csrf_field() ?>
                                <input type="hidden" name="status" value="Tentatif">
                                <button type="submit" class="btn-rsvp <?= $event['my_rsvp'] === 'Tentatif' ? 'active' : '' ?>"><?= cms_text('event_btn_tentatif', 'Tentatif') ?></button>
                            </form>

                            <form action="/event/rsvp/<?= $event['id'] ?>" method="POST">
                                <?= csrf_field() ?>
                                <input type="hidden" name="status" value="Tidak Hadir">
                                <button type="submit" class="btn-rsvp <?= $event['my_rsvp'] === 'Tidak Hadir' ? 'active' : '' ?>"><?= cms_text('event_btn_absen', 'Absen') ?></button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<?= $this->endSection() ?>
