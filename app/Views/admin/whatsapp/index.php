<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>WhatsApp Broadcast — Admin<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main style="padding: 40px 20px; max-width: 1200px; margin: 0 auto;">

    <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 40px;">
        <div style="width: 50px; height: 50px; border-radius: 50%; background: rgba(37,211,102,0.15); display: flex; align-items: center; justify-content: center; font-size: 1.4rem; color: #25d366;">
            <i class="fa-brands fa-whatsapp"></i>
        </div>
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin: 0;">WhatsApp Broadcast</h1>
            <p style="color: var(--text-secondary); font-size: 0.8rem; margin: 4px 0 0 0; letter-spacing: 1px; text-transform: uppercase;">Kelola antrian notifikasi alumni</p>
        </div>
        <a href="/admin/dashboard" style="margin-left: auto; color: var(--text-secondary); font-size: 0.8rem; text-decoration: none;">← Kembali</a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div style="background: rgba(37,211,102,0.1); border: 1px solid rgba(37,211,102,0.3); border-radius: 12px; padding: 14px 20px; margin-bottom: 24px; color: #25d366; font-size: 0.85rem;">
            <i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i><?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div style="background: rgba(255,51,102,0.1); border: 1px solid rgba(255,51,102,0.3); border-radius: 12px; padding: 14px 20px; margin-bottom: 24px; color: #ff3366; font-size: 0.85rem;">
            <i class="fa-solid fa-triangle-exclamation" style="margin-right: 8px;"></i><?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- Fonnte Connection Status Panel -->
    <div id="statusPanel" style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 18px 24px; margin-bottom: 32px; display: flex; align-items: center; gap: 16px;">
        <div id="statusDot" style="width: 10px; height: 10px; border-radius: 50%; background: #888; flex-shrink: 0; transition: background 0.3s;"></div>
        <div style="flex: 1;">
            <div style="font-size: 0.8rem; font-weight: 600; color: var(--text-primary);">Status Koneksi Fonnte API</div>
            <div id="statusText" style="font-size: 0.72rem; color: var(--text-secondary); margin-top: 3px;">Memeriksa koneksi...</div>
        </div>
        <button onclick="checkFonnteStatus()" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: var(--text-secondary); padding: 6px 14px; border-radius: 8px; font-size: 0.72rem; cursor: pointer; white-space: nowrap;">
            <i class="fa-solid fa-rotate-right" style="margin-right: 5px;"></i>Cek Ulang
        </button>
    </div>

    <!-- Fonnte Setup Guide (hanya muncul jika token invalid) -->
    <div id="setupGuide" style="display: none; background: rgba(255,193,7,0.05); border: 1px solid rgba(255,193,7,0.3); border-radius: 16px; padding: 24px; margin-bottom: 32px;">
        <h3 style="color: #ffc107; font-size: 0.9rem; margin: 0 0 16px 0;"><i class="fa-solid fa-circle-exclamation" style="margin-right: 8px;"></i>Token Tidak Valid — Ikuti Langkah Setup Berikut:</h3>
        <ol style="color: var(--text-secondary); font-size: 0.8rem; line-height: 2; padding-left: 20px; margin: 0;">
            <li>Login ke <a href="https://fonnte.com" target="_blank" style="color: #25d366;">fonnte.com</a> menggunakan akun yang sudah terdaftar</li>
            <li>Buka menu <strong style="color: var(--text-primary);">Device</strong> → klik <strong style="color: var(--text-primary);">Add Device</strong></li>
            <li>Scan QR Code dengan WhatsApp yang akan dijadikan pengirim notifikasi</li>
            <li>Setelah device terhubung (<span style="color: #25d366;">Connected</span>), buka menu <strong style="color: var(--text-primary);">Profile → Token</strong></li>
            <li>Copy token yang baru, lalu update di file <code style="background: rgba(0,0,0,0.3); padding: 2px 6px; border-radius: 4px;">.env</code>:
                <br><code style="background: rgba(0,0,0,0.4); padding: 4px 10px; border-radius: 6px; display: inline-block; margin-top: 6px; color: #25d366;">FONNTE_TOKEN="token_baru_kamu"</code>
            </li>
        </ol>
    </div>

    <script>
    async function checkFonnteStatus() {
        const dot  = document.getElementById('statusDot');
        const text = document.getElementById('statusText');
        const guide = document.getElementById('setupGuide');
        dot.style.background  = '#888';
        dot.style.animation   = 'pulse 1s infinite';
        text.textContent = 'Memeriksa koneksi ke Fonnte...';
        try {
            const res  = await fetch('/admin/whatsapp/diagnose');
            const data = await res.json();
            if (data.api_ok) {
                dot.style.background  = '#25d366';
                dot.style.animation   = '';
                text.innerHTML = `<span style="color:#25d366;font-weight:600;">✅ Terhubung</span> · Device: ${data.reason || 'OK'} · Token: ${data.token_value}`;
                guide.style.display   = 'none';
            } else {
                dot.style.background  = '#ff3366';
                dot.style.animation   = '';
                const errMsg = data.reason || data.raw_response || 'Unknown error';
                text.innerHTML = `<span style="color:#ff3366;font-weight:600;">❌ Gagal</span> · ${errMsg} · Token: ${data.token_value}`;
                guide.style.display   = 'block';
            }
        } catch(e) {
            dot.style.background  = '#f39c12';
            dot.style.animation   = '';
            text.innerHTML = `<span style="color:#f39c12;font-weight:600;">⚠ Error</span> · Tidak bisa reach endpoint diagnostik`;
        }
    }
    checkFonnteStatus(); // Auto-check saat halaman load
    </script>



    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 40px;">
        <?php
        $statCards = [
            ['label' => 'Total Antrian', 'value' => $stats['total'], 'color' => '#d4af37', 'icon' => 'fa-list'],
            ['label' => 'Terkirim', 'value' => $stats['sent'], 'color' => '#25d366', 'icon' => 'fa-check-circle'],
            ['label' => 'Menunggu', 'value' => $stats['pending'], 'color' => '#f39c12', 'icon' => 'fa-clock'],
            ['label' => 'Gagal', 'value' => $stats['failed'], 'color' => '#ff3366', 'icon' => 'fa-times-circle'],
        ];
        foreach ($statCards as $card): ?>
            <div style="background: var(--glass-bg); border: 1px solid var(--glass-border); border-radius: 16px; padding: 24px 20px; text-align: center;">
                <i class="fa-solid <?= $card['icon'] ?>" style="color: <?= $card['color'] ?>; font-size: 1.5rem; margin-bottom: 12px; display: block;"></i>
                <div style="font-size: 2rem; font-weight: 800; color: <?= $card['color'] ?>; font-family: monospace;"><?= number_format($card['value']) ?></div>
                <div style="font-size: 0.7rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px; margin-top: 6px;"><?= $card['label'] ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 40px;">

        <!-- Manual Blast Form -->
        <div style="background: var(--glass-bg); border: 1px solid rgba(37,211,102,0.2); border-radius: 20px; padding: 28px;">
            <h2 style="font-size: 1rem; font-weight: 700; color: #25d366; margin: 0 0 8px 0; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-bullhorn"></i> Manual Blast
            </h2>
            <p style="font-size: 0.75rem; color: var(--text-secondary); margin: 0 0 20px 0;">
                Kirim pesan kustom ke semua alumni yang opt-in. Mendukung format WhatsApp: <strong>*bold*</strong>, <em>_italic_</em>.
            </p>
            <form action="/admin/whatsapp/blast" method="POST" id="blastForm">
                <?= csrf_field() ?>
                <textarea name="message" id="blastMessage" placeholder="Tulis pesan WhatsApp di sini...&#10;&#10;Contoh:&#10;📢 *Expedient Generation*&#10;&#10;Informasi penting untuk seluruh alumni..." style="width: 100%; min-height: 160px; background: rgba(0,0,0,0.3); border: 1px solid var(--glass-border); border-radius: 12px; color: var(--text-primary); font-size: 0.85rem; padding: 14px; resize: vertical; font-family: inherit; box-sizing: border-box; outline: none; transition: border-color 0.2s;" required maxlength="4096" oninput="document.getElementById('charCount').textContent = this.value.length"></textarea>
                <div style="display: flex; justify-content: space-between; font-size: 0.65rem; color: var(--text-secondary); margin: 6px 0 16px 0;">
                    <span>Markdown WA: *bold*, _italic_, ~coret~</span>
                    <span><span id="charCount">0</span>/4096 karakter</span>
                </div>
                <button type="submit" id="blastBtn" style="width: 100%; padding: 14px; background: rgba(37,211,102,0.15); border: 1px solid rgba(37,211,102,0.4); color: #25d366; border-radius: 12px; font-size: 0.85rem; font-weight: 600; cursor: pointer; letter-spacing: 0.5px; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <i class="fa-brands fa-whatsapp"></i> Tambahkan ke Antrian &amp; Kirim
                </button>
            </form>
            <script>
            document.getElementById('blastForm').addEventListener('submit', function(e) {
                var msg = document.getElementById('blastMessage').value.trim();
                if (!msg) { e.preventDefault(); return; }
                var btn = document.getElementById('blastBtn');
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mengirim...';
            });
            </script>
        </div>


        <!-- Retry Failed + Cron Info -->
        <div style="display: flex; flex-direction: column; gap: 16px;">
            <!-- Retry -->
            <div style="background: var(--glass-bg); border: 1px solid rgba(255,51,102,0.2); border-radius: 20px; padding: 24px;">
                <h2 style="font-size: 1rem; font-weight: 700; color: #ff3366; margin: 0 0 8px 0; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-rotate-right"></i> Retry Gagal
                </h2>
                <p style="font-size: 0.75rem; color: var(--text-secondary); margin: 0 0 16px 0;">
                    Reset pesan yang gagal agar dicoba ulang.
                </p>
                <form action="/admin/whatsapp/retry" method="POST">
                    <?= csrf_field() ?>
                    <button type="submit" style="width: 100%; padding: 10px; background: rgba(255,51,102,0.1); border: 1px solid rgba(255,51,102,0.3); color: #ff3366; border-radius: 10px; font-size: 0.8rem; font-weight: 600; cursor: pointer;">
                        <i class="fa-solid fa-rotate" style="margin-right: 6px;"></i>Reset <?= $stats['failed'] ?> Pesan Gagal
                    </button>
                </form>
            </div>

            <!-- Process Now -->
            <div style="background: var(--glass-bg); border: 1px solid rgba(212,175,55,0.2); border-radius: 20px; padding: 24px;">
                <h2 style="font-size: 1rem; font-weight: 700; color: #d4af37; margin: 0 0 8px 0; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-bolt"></i> Proses Sekarang
                </h2>
                <p style="font-size: 0.75rem; color: var(--text-secondary); margin: 0 0 16px 0;">
                    Kirim <?= $stats['pending'] ?> pesan pending sekarang tanpa menunggu cron.
                </p>
                <form action="/admin/whatsapp/process-now" method="POST">
                    <?= csrf_field() ?>
                    <button type="submit" <?= $stats['pending'] === 0 ? 'disabled' : '' ?> style="width: 100%; padding: 10px; background: rgba(212,175,55,0.1); border: 1px solid rgba(212,175,55,0.3); color: #d4af37; border-radius: 10px; font-size: 0.8rem; font-weight: 600; cursor: pointer; <?= $stats['pending'] === 0 ? 'opacity:0.4;cursor:not-allowed;' : '' ?>">
                        <i class="fa-solid fa-paper-plane" style="margin-right: 6px;"></i>Proses <?= $stats['pending'] ?> Antrian Pending
                    </button>
                </form>
            </div>

            <!-- Cron Info -->
            <div style="background: var(--glass-bg); border: 1px solid var(--glass-border); border-radius: 20px; padding: 24px;">
                <h2 style="font-size: 0.85rem; font-weight: 700; color: #d4af37; margin: 0 0 12px 0; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-terminal"></i> Cron Jobs
                </h2>
                <pre style="background: rgba(0,0,0,0.4); border-radius: 8px; padding: 12px; font-size: 0.65rem; color: #25d366; overflow-x: auto; margin: 0; line-height: 1.8;"># Proses antrian setiap menit
* * * * * php spark wa:process

# Ucapan ulang tahun (tiap hari jam 07:00)
0 7 * * * php spark wa:birthday</pre>
            </div>
        </div>
    </div>

    <!-- Recent Queue Log -->
    <div style="background: var(--glass-bg); border: 1px solid var(--glass-border); border-radius: 20px; padding: 28px; overflow: hidden;">
        <h2 style="font-size: 1rem; font-weight: 700; color: var(--text-primary); margin: 0 0 20px 0; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-list-check" style="color: #d4af37;"></i> Log Antrian Terbaru (50)
        </h2>

        <?php if (empty($recent_logs)): ?>
            <div style="text-align: center; padding: 40px; color: var(--text-secondary); font-size: 0.85rem;">
                <i class="fa-solid fa-inbox" style="font-size: 2rem; margin-bottom: 12px; display: block; opacity: 0.4;"></i>
                Belum ada antrian WhatsApp.
            </div>
        <?php else: ?>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.78rem;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--glass-border);">
                            <th style="text-align: left; padding: 10px 12px; color: var(--text-secondary); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; font-size: 0.65rem;">Status</th>
                            <th style="text-align: left; padding: 10px 12px; color: var(--text-secondary); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; font-size: 0.65rem;">Penerima</th>
                            <th style="text-align: left; padding: 10px 12px; color: var(--text-secondary); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; font-size: 0.65rem;">Pesan</th>
                            <th style="text-align: left; padding: 10px 12px; color: var(--text-secondary); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; font-size: 0.65rem;">Terkirim</th>
                            <th style="text-align: center; padding: 10px 12px; color: var(--text-secondary); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; font-size: 0.65rem;">Percobaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_logs as $log): ?>
                            <?php
                            $statusColor = match($log['status']) {
                                'sent'    => '#25d366',
                                'failed'  => '#ff3366',
                                default   => '#f39c12',
                            };
                            $statusIcon = match($log['status']) {
                                'sent'    => 'fa-check',
                                'failed'  => 'fa-times',
                                default   => 'fa-clock',
                            };
                            ?>
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.03);">
                                <td style="padding: 10px 12px;">
                                    <span style="display: inline-flex; align-items: center; gap: 5px; background: <?= $statusColor ?>22; color: <?= $statusColor ?>; font-size: 0.65rem; padding: 3px 9px; border-radius: 20px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                        <i class="fa-solid <?= $statusIcon ?>" style="font-size: 0.6rem;"></i>
                                        <?= esc($log['status']) ?>
                                    </span>
                                </td>
                                <td style="padding: 10px 12px; color: var(--text-primary);">
                                    <div style="font-weight: 600;"><?= esc($log['to_name'] ?: '—') ?></div>
                                    <div style="color: var(--text-secondary); font-size: 0.7rem;"><?= esc($log['to_number']) ?></div>
                                </td>
                                <td style="padding: 10px 12px; color: var(--text-secondary); max-width: 300px;">
                                    <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 280px;" title="<?= esc($log['message']) ?>">
                                        <?= esc(mb_substr(preg_replace('/\s+/', ' ', $log['message']), 0, 80)) ?>...
                                    </div>
                                    <?php if ($log['status'] === 'failed' && $log['error_message']): ?>
                                        <div style="color: #ff3366; font-size: 0.65rem; margin-top: 3px;">⚠ <?= esc($log['error_message']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 10px 12px; color: var(--text-secondary); white-space: nowrap; font-size: 0.75rem;">
                                    <?= $log['sent_at'] ? date('d M, H:i', strtotime($log['sent_at'])) : '—' ?>
                                </td>
                                <td style="padding: 10px 12px; text-align: center; color: var(--text-secondary);">
                                    <?= $log['attempts'] ?>/<?= $log['max_attempts'] ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

</main>
<?= $this->endSection() ?>
