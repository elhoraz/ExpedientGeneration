<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Manajemen Entitas
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .admin-wrapper { padding: clamp(80px, 15vh, 120px) 20px 40px; max-width: 1400px; margin: 0 auto; }
    .admin-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; margin-bottom: 30px; }
    .admin-title { font-family: 'Playfair Display', serif; font-size: clamp(1.5rem, 3vw, 2.5rem); color: #d4af37; }
    .admin-nav { display: flex; gap: 10px; flex-wrap: wrap; }
    .admin-nav a { padding: 8px 16px; border-radius: 8px; border: 1px solid var(--glass-border); color: var(--text-primary); text-decoration: none; font-size: 0.8rem; letter-spacing: 1px; text-transform: uppercase; transition: 0.3s; }
    .admin-nav a:hover, .admin-nav a.active { background: rgba(212,175,55,0.1); border-color: #d4af37; color: #d4af37; }

    .search-bar { display: flex; gap: 10px; margin-bottom: 25px; flex-wrap: wrap; }
    .search-bar input, .search-bar select { background: var(--glass-bg); border: 1px solid var(--glass-border); color: var(--text-primary); padding: 10px 15px; border-radius: 8px; font-size: 0.85rem; }
    .search-bar input { flex: 1; min-width: 200px; }
    .search-bar select { min-width: 130px; }
    .search-bar button { background: #d4af37; color: #000; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 0.8rem; letter-spacing: 1px; }

    .users-table { width: 100%; border-collapse: separate; border-spacing: 0 8px; }
    .users-table th { text-align: left; padding: 10px 15px; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 2px; color: var(--text-secondary); }
    .users-table td { padding: 12px 15px; background: var(--glass-bg); backdrop-filter: blur(10px); border: 1px solid var(--glass-border); font-size: 0.85rem; color: var(--text-primary); }
    .users-table tr td:first-child { border-radius: 10px 0 0 10px; border-right: none; }
    .users-table tr td:last-child { border-radius: 0 10px 10px 0; border-left: none; }
    .users-table tr td:not(:first-child):not(:last-child) { border-left: none; border-right: none; }

    .user-avatar { width: 35px; height: 35px; border-radius: 50%; object-fit: cover; border: 1px solid rgba(212,175,55,0.3); vertical-align: middle; margin-right: 10px; }
    .badge-role { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 0.7rem; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
    .badge-admin { background: rgba(212,175,55,0.2); color: #d4af37; }
    .badge-bendahara { background: rgba(0,200,100,0.15); color: #00c864; }
    .badge-member { background: rgba(255,255,255,0.05); color: var(--text-secondary); }
    .badge-inactive { background: rgba(255,50,50,0.15); color: #ff5555; }

    .action-form { display: inline-flex; gap: 5px; align-items: center; }
    .action-form select { background: rgba(0,0,0,0.3); border: 1px solid var(--glass-border); color: var(--text-primary); padding: 4px 8px; border-radius: 5px; font-size: 0.75rem; }
    .btn-sm { padding: 5px 12px; border: none; border-radius: 5px; cursor: pointer; font-size: 0.7rem; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; transition: 0.3s; }
    .btn-gold { background: rgba(212,175,55,0.2); color: #d4af37; border: 1px solid rgba(212,175,55,0.3); }
    .btn-gold:hover { background: rgba(212,175,55,0.4); }
    .btn-danger-sm { background: rgba(255,50,50,0.15); color: #ff5555; border: 1px solid rgba(255,50,50,0.2); }
    .btn-danger-sm:hover { background: rgba(255,50,50,0.3); }
    .btn-success-sm { background: rgba(0,200,100,0.15); color: #00c864; border: 1px solid rgba(0,200,100,0.2); }
    .btn-success-sm:hover { background: rgba(0,200,100,0.3); }

    .export-btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: rgba(0,200,100,0.1); border: 1px solid rgba(0,200,100,0.3); color: #00c864; border-radius: 8px; text-decoration: none; font-size: 0.8rem; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; transition: 0.3s; }
    .export-btn:hover { background: rgba(0,200,100,0.2); }

    @media (max-width: 900px) {
        .users-table, .users-table thead, .users-table tbody, .users-table th, .users-table td, .users-table tr { display: block; }
        .users-table thead { display: none; }
        .users-table tr { margin-bottom: 15px; background: var(--glass-bg); border: 1px solid var(--glass-border); border-radius: 12px; padding: 15px; }
        .users-table td { border: none !important; border-radius: 0 !important; padding: 5px 0; background: transparent; }
        .users-table td::before { content: attr(data-label); font-size: 0.7rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 3px; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="admin-wrapper">
    <div class="admin-header">
        <h1 class="admin-title">Manajemen Entitas</h1>
        <nav class="admin-nav">
            <a href="/admin/dashboard">Dashboard</a>
            <a href="/admin/users" class="active">Users</a>
            <a href="/admin/moderation">Moderasi</a>
            <a href="/admin/announcements">Pengumuman</a>
        </nav>
    </div>

    <div class="search-bar">
        <form action="/admin/users" method="GET" style="display:flex; gap:10px; flex-wrap:wrap; width:100%;">
            <input type="text" name="q" placeholder="Cari nama, panggilan, atau email..." value="<?= esc($search ?? '') ?>">
            <select name="role">
                <option value="">Semua Role</option>
                <option value="admin" <?= ($roleFilter ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="bendahara" <?= ($roleFilter ?? '') === 'bendahara' ? 'selected' : '' ?>>Bendahara</option>
                <option value="member" <?= ($roleFilter ?? '') === 'member' ? 'selected' : '' ?>>Member</option>
            </select>
            <button type="submit"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
        </form>
        <a href="/admin/export-csv" class="export-btn"><i class="fa-solid fa-file-csv"></i> Export CSV</a>
    </div>

    <div style="overflow-x: auto;">
    <table class="users-table">
        <thead>
            <tr>
                <th>Entitas</th>
                <th>Email</th>
                <th>Role</th>
                <th>Prestise</th>
                <th>Status</th>
                <th>Ubah Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $u): ?>
            <tr>
                <td data-label="Entitas">
                    <img src="<?= !empty($u['foto_profil']) ? '/uploads/profiles/'.$u['foto_profil'] : 'https://ui-avatars.com/api/?name='.urlencode($u['nama_panggilan'] ?: $u['nama_lengkap']).'&background=d4af37&color=000&size=35' ?>" class="user-avatar" alt="">
                    <?= esc($u['nama_panggilan'] ?: $u['nama_lengkap']) ?>
                </td>
                <td data-label="Email" style="font-family: monospace; font-size: 0.8rem;"><?= esc($u['email']) ?></td>
                <td data-label="Role">
                    <span class="badge-role badge-<?= esc($u['role'] ?? 'member') ?>"><?= esc($u['role'] ?? 'member') ?></span>
                </td>
                <td data-label="Prestise" style="color: #d4af37; font-weight: bold;"><?= number_format($u['prestise_points'] ?? 0) ?></td>
                <td data-label="Status">
                    <?php if(($u['is_active'] ?? 1) == 1): ?>
                        <span style="color: #00c864;">● Aktif</span>
                    <?php else: ?>
                        <span class="badge-inactive">● Nonaktif</span>
                    <?php endif; ?>
                </td>
                <td data-label="Ubah Role">
                    <form action="/admin/users/role/<?= $u['id'] ?>" method="POST" class="action-form">
                        <?= csrf_field() ?>
                        <select name="role">
                            <option value="member" <?= ($u['role'] ?? 'member') === 'member' ? 'selected' : '' ?>>Member</option>
                            <option value="bendahara" <?= ($u['role'] ?? '') === 'bendahara' ? 'selected' : '' ?>>Bendahara</option>
                            <option value="admin" <?= ($u['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                        </select>
                        <button type="submit" class="btn-sm btn-gold">Simpan</button>
                    </form>
                </td>
                <td data-label="Aksi">
                    <form action="/admin/users/toggle/<?= $u['id'] ?>" method="POST" style="display:inline;" onsubmit="event.preventDefault(); window.showConfirm('Konfirmasi', 'Yakin ingin mengubah status akun ini?').then(res => { if(res) this.submit(); });">
                        <?= csrf_field() ?>
                        <?php if(($u['is_active'] ?? 1) == 1): ?>
                            <button type="submit" class="btn-sm btn-danger-sm"><i class="fa-solid fa-ban"></i> Nonaktifkan</button>
                        <?php else: ?>
                            <button type="submit" class="btn-sm btn-success-sm"><i class="fa-solid fa-check"></i> Aktifkan</button>
                        <?php endif; ?>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>

    <div style="text-align:center; padding:20px; font-size:0.8rem; color:var(--text-secondary);">
        Total: <?= count($users) ?> entitas terdaftar
    </div>
</div>
<?= $this->endSection() ?>
