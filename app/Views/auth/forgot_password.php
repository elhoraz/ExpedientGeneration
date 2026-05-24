<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Reset Sandi - Expedient Generation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root{--bg-base:#010302;--bg-radial:#030d08;--glow-1:rgba(0,255,136,0.15);--glow-2:rgba(0,162,255,0.12);--glow-3:rgba(255,215,0,0.08);--glass-surface:rgba(4,10,7,0.6);--glass-border:rgba(0,255,136,0.15);--glass-shadow:0 40px 80px rgba(0,0,0,0.9);--text-main:#f0f5f2;--text-muted:#5e7a6b;--gold-liquid:linear-gradient(135deg,#d4af37 0%,#fff2cd 40%,#aa771c 60%,#d4af37 100%);--emerald-liquid:linear-gradient(135deg,#00ff88,#008844);--input-bg:rgba(0,0,0,0.5);--input-focus:rgba(0,255,136,0.1)}
        [data-theme="light"]{--bg-base:#f0f5f3;--bg-radial:#fff;--glow-1:rgba(0,255,136,0.15);--glow-2:rgba(0,162,255,0.1);--glow-3:rgba(255,215,0,0.15);--glass-surface:rgba(255,255,255,0.7);--glass-border:rgba(255,255,255,1);--glass-shadow:0 30px 60px rgba(0,20,10,0.08);--text-main:#041008;--text-muted:#6a8275;--gold-liquid:linear-gradient(135deg,#aa771c 0%,#d4af37 40%,#e6c27a 60%,#aa771c 100%);--input-bg:rgba(255,255,255,0.8);--input-focus:rgba(0,255,136,0.05)}
        *{box-sizing:border-box;margin:0;padding:0;font-family:'Inter',sans-serif;-webkit-tap-highlight-color:transparent}
        body{background-color:var(--bg-base);background-image:radial-gradient(circle at 50% 0%,var(--bg-radial),transparent 80%);color:var(--text-main);width:100vw;height:100vh;display:flex;justify-content:center;align-items:center;overflow:hidden;perspective:2000px;transition:background 1.2s;touch-action:none}
        .ambient-field{position:absolute;inset:-20%;z-index:0;pointer-events:none;filter:blur(50px)}
        .core-orb{position:absolute;border-radius:50%;opacity:0.8;mix-blend-mode:screen}
        [data-theme="light"] .core-orb{mix-blend-mode:multiply;opacity:0.6}
        .orb-1{width:50vmax;height:50vmax;background:var(--glow-1);top:-10%;left:0;animation:breathe 15s alternate infinite ease-in-out}
        .orb-2{width:60vmax;height:60vmax;background:var(--glow-2);bottom:-10%;right:-10%;animation:breathe 20s alternate-reverse infinite ease-in-out}
        .orb-3{width:40vmax;height:40vmax;background:var(--glow-3);top:30%;left:30%;animation:breathe 25s alternate infinite ease-in-out}
        @keyframes breathe{0%{transform:scale(1)}100%{transform:scale(1.3) translate(5vw,-5vh)}}
        .scene-wrapper{position:relative;z-index:10;width:100%;max-width:clamp(320px,90vw,440px);padding:clamp(10px,2vh,20px);display:flex;justify-content:center;align-items:center}
        .auth-prism{width:100%;background:var(--glass-surface);backdrop-filter:blur(40px);-webkit-backdrop-filter:blur(40px);border-radius:clamp(20px,4vh,36px);padding:clamp(20px,5vh,45px) clamp(20px,6vw,40px);box-shadow:var(--glass-shadow);border-top:1px solid var(--glass-border);border-left:1px solid var(--glass-border);border-bottom:1px solid rgba(0,0,0,0.8);border-right:1px solid rgba(0,0,0,0.8);opacity:0;transform:translateY(5vh);animation:prismEnter 1.5s cubic-bezier(0.175,0.885,0.32,1.1) forwards}
        @keyframes prismEnter{to{opacity:1;transform:translateY(0)}}
        .prism-header{text-align:center;margin-bottom:clamp(15px,4vh,35px)}
        .icon-shield{width:clamp(60px,10vh,80px);height:clamp(60px,10vh,80px);margin:0 auto clamp(10px,2vh,20px);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:clamp(1.5rem,4vh,2.2rem);animation:pulseGlow 3s ease-in-out infinite}
        .step-email .icon-shield{background:rgba(0,255,136,0.1);border:2px solid rgba(0,255,136,0.3);color:#00ff88}
        .step-verify .icon-shield{background:rgba(255,215,0,0.1);border:2px solid rgba(255,215,0,0.3);color:#ffd700}
        .step-reset .icon-shield{background:rgba(0,162,255,0.1);border:2px solid rgba(0,162,255,0.3);color:#00a2ff}
        @keyframes pulseGlow{0%,100%{box-shadow:0 0 20px rgba(0,255,136,0.2)}50%{box-shadow:0 0 40px rgba(0,255,136,0.4)}}
        .title-holo{font-family:'Playfair Display',serif;font-size:clamp(1.4rem,3.5vh,1.8rem);font-weight:700;background:var(--gold-liquid);background-size:200% auto;-webkit-background-clip:text;-webkit-text-fill-color:transparent;letter-spacing:1px;margin-bottom:4px;animation:shimmerHolo 8s linear infinite}
        @keyframes shimmerHolo{to{background-position:200% center}}
        .subtitle-spec{font-size:clamp(0.55rem,1.2vh,0.7rem);color:var(--text-muted);letter-spacing:4px;text-transform:uppercase;font-weight:600}
        .step-indicator{display:flex;justify-content:center;gap:8px;margin-bottom:clamp(15px,3vh,25px)}
        .step-dot{width:clamp(28px,5vw,36px);height:4px;border-radius:2px;background:rgba(255,255,255,0.1);transition:0.5s}
        .step-dot.active{background:var(--emerald-liquid);box-shadow:0 0 10px rgba(0,255,136,0.4)}
        .step-dot.done{background:rgba(0,255,136,0.3)}
        .input-group{position:relative;margin-bottom:clamp(15px,3vh,25px)}
        .input-control{width:100%;padding:clamp(10px,2vh,12px) 0;background:var(--input-bg);border:none;border-bottom:1px solid rgba(255,255,255,0.05);color:var(--text-main);font-size:clamp(0.9rem,2vh,1.05rem);outline:none;transition:0.4s;border-radius:6px 6px 0 0;padding-left:12px}
        .input-control::placeholder{color:transparent}
        .input-neon-line{position:absolute;bottom:0;left:50%;width:0;height:2px;background:var(--emerald-liquid);transition:0.5s cubic-bezier(0.25,1,0.5,1);transform:translateX(-50%)}
        .input-label{position:absolute;top:clamp(10px,2vh,12px);left:12px;color:var(--text-muted);font-size:clamp(0.85rem,1.8vh,0.95rem);pointer-events:none;transition:0.4s cubic-bezier(0.16,1,0.3,1);letter-spacing:0.5px}
        .input-control:focus,.input-control:not(:placeholder-shown){background:var(--input-focus)}
        .input-control:focus~.input-label,.input-control:not(:placeholder-shown)~.input-label{top:-14px;left:0;font-size:clamp(0.6rem,1.2vh,0.7rem);color:var(--text-muted);letter-spacing:1px;text-transform:uppercase;font-weight:700}
        .input-control:focus~.input-neon-line{width:100%;box-shadow:0 -2px 10px rgba(0,255,136,0.4)}
        .input-control:focus~.input-label{color:#00ff88}
        .icon-eye{position:absolute;right:10px;top:clamp(10px,2vh,12px);color:var(--text-muted);cursor:pointer;transition:0.3s;font-size:1.1rem}
        .icon-eye:hover{color:var(--text-main)}
        .otp-group{display:flex;gap:8px;justify-content:center;margin-bottom:clamp(15px,3vh,25px)}
        .otp-input{width:clamp(40px,10vw,50px);height:clamp(48px,8vh,56px);text-align:center;font-size:clamp(1.2rem,3vh,1.6rem);font-weight:700;background:var(--input-bg);border:1px solid rgba(0,255,136,0.15);border-radius:12px;color:var(--text-main);outline:none;transition:0.3s;letter-spacing:2px}
        .otp-input:focus{border-color:#00ff88;box-shadow:0 0 15px rgba(0,255,136,0.3);background:var(--input-focus)}
        .btn-prime{position:relative;width:100%;padding:clamp(12px,2.5vh,18px);border-radius:14px;border:none;background:var(--gold-liquid);background-size:200% auto;color:#05090a;font-weight:800;font-size:clamp(0.85rem,1.8vh,0.95rem);text-transform:uppercase;letter-spacing:2px;cursor:pointer;display:flex;justify-content:center;align-items:center;gap:10px;box-shadow:0 10px 20px rgba(0,0,0,0.5),inset 0 2px 0 rgba(255,255,255,0.4);transition:transform 0.2s cubic-bezier(0.175,0.885,0.32,1.27)}
        .btn-prime:hover{animation:shimmerHolo 3s linear infinite;box-shadow:0 15px 30px rgba(212,175,55,0.3)}
        .back-link{text-align:center;margin-top:clamp(15px,3vh,25px);color:var(--text-muted);font-size:clamp(0.75rem,1.5vh,0.85rem)}
        .back-link a{color:var(--text-main);text-decoration:none;font-weight:600;border-bottom:1px solid transparent;transition:0.3s}
        .back-link a:hover{border-color:#d4af37;color:#d4af37}
        .quantum-toast{position:fixed;top:3vh;right:-500px;padding:clamp(15px,2vh,20px) clamp(20px,3vw,25px);border-radius:16px;background:var(--glass-surface);backdrop-filter:blur(40px);border:1px solid var(--glass-border);color:var(--text-main);display:flex;align-items:center;gap:15px;box-shadow:var(--glass-shadow);transition:right 0.8s cubic-bezier(0.34,1.56,0.64,1.2);z-index:9999}
        .quantum-toast.show{right:4vw}
        .toast-icon{font-size:clamp(1.5rem,3vh,1.8rem);filter:drop-shadow(0 5px 5px rgba(0,0,0,0.5))}
        .toast-success{border-bottom:3px solid #00ff88}.toast-success i{color:#00ff88}
        .toast-error{border-bottom:3px solid #ff3366}.toast-error i{color:#ff3366}
        .resend-timer{text-align:center;margin-top:10px;font-size:clamp(0.7rem,1.3vh,0.8rem);color:var(--text-muted)}
        .resend-timer a{color:#d4af37;text-decoration:none;font-weight:600}
        .step-form{display:none;animation:fadeSlide 0.6s ease forwards}
        .step-form.active{display:block}
        @keyframes fadeSlide{from{opacity:0;transform:translateY(15px)}to{opacity:1;transform:translateY(0)}}
        .toggle-widget{position:fixed;top:3vh;right:4vw;z-index:100;background:var(--glass-surface);backdrop-filter:blur(40px);border:1px solid var(--glass-border);border-radius:30px;padding:5px 12px 5px 5px;cursor:pointer;display:flex;align-items:center;gap:8px;transition:0.4s;box-shadow:var(--glass-shadow)}
        .toggle-widget:hover{transform:translateY(-2px) scale(1.05)}
        .icon-orb{width:clamp(25px,4vh,32px);height:clamp(25px,4vh,32px);border-radius:50%;display:flex;justify-content:center;align-items:center;color:var(--text-main);box-shadow:inset 0 2px 5px rgba(0,0,0,0.5);transition:0.4s;font-size:clamp(0.8rem,1.5vh,1rem)}
        .widget-text{font-size:clamp(0.6rem,1.2vh,0.7rem);font-weight:700;letter-spacing:1px;text-transform:uppercase}
        @media(max-width:600px){.quantum-toast.show{right:20px;left:20px}.widget-text{display:none}}
    </style>
</head>
<body data-theme="dark">

    <div class="ambient-field">
        <div class="core-orb orb-1"></div>
        <div class="core-orb orb-2"></div>
        <div class="core-orb orb-3"></div>
    </div>

    <button class="toggle-widget" id="btnTheme" title="Ganti Mode">
        <div class="icon-orb"><i class="fa-solid fa-moon" id="toggleIcon"></i></div>
        <span class="widget-text" id="themeText">Malam</span>
    </button>

    <?php
        $step = session()->getFlashdata('step') ?? 'email';
    ?>

    <?php if(session()->getFlashdata('success')): ?>
        <div id="toastAlert" class="quantum-toast toast-success show">
            <div class="toast-icon"><i class="fa-solid fa-check-double"></i></div>
            <div>
                <strong style="font-family:'Playfair Display',serif;font-size:1rem;">Berhasil</strong><br>
                <span style="font-size:0.8rem;"><?= session()->getFlashdata('success') ?></span>
            </div>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
        <div id="toastAlert" class="quantum-toast toast-error show">
            <div class="toast-icon"><i class="fa-solid fa-shield-virus"></i></div>
            <div>
                <strong style="font-family:'Playfair Display',serif;font-size:1rem;">Gagal</strong><br>
                <span style="font-size:0.8rem;"><?= session()->getFlashdata('error') ?></span>
            </div>
        </div>
    <?php endif; ?>

    <div class="scene-wrapper">
        <div class="auth-prism">

            <!-- ====== STEP 1: EMAIL ====== -->
            <div class="step-form step-email <?= $step === 'email' ? 'active' : '' ?>" id="stepEmail">
                <div class="prism-header">
                    <div class="icon-shield"><i class="fa-solid fa-envelope-open-text"></i></div>
                    <div class="subtitle-spec"><?= cms_text('forgot_step1_subtitle', 'Pemulihan Akses') ?></div>
                    <h1 class="title-holo"><?= cms_text('forgot_step1_title', 'Lupa Kata Sandi') ?></h1>
                </div>
                <div class="step-indicator">
                    <div class="step-dot active"></div><div class="step-dot"></div><div class="step-dot"></div>
                </div>
                <p style="text-align:center;color:var(--text-muted);font-size:clamp(0.75rem,1.5vh,0.85rem);margin-bottom:clamp(15px,3vh,20px);"><?= cms_text('forgot_step1_desc', 'Masukkan email yang terdaftar untuk menerima kode verifikasi.') ?></p>
                <form action="/auth/forgot-password/send" method="POST">
                    <?= csrf_field() ?>
                    <div class="input-group">
                        <input type="email" name="email" id="emailInput" class="input-control" required placeholder=" ">
                        <label for="emailInput" class="input-label"><?= cms_text('forgot_label_email', 'Surel Terdaftar') ?></label>
                        <div class="input-neon-line"></div>
                    </div>
                    <button type="submit" class="btn-prime" id="btnSend">
                        <?= cms_text('forgot_btn_send', 'Kirim Kode') ?> <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </form>
                <div class="back-link">
                    <i class="fa-solid fa-arrow-left" style="font-size:0.7rem;margin-right:4px;"></i>
                    <a href="/login"><?= cms_text('forgot_back_login', 'Kembali ke Login') ?></a>
                </div>
            </div>

            <!-- ====== STEP 2: VERIFY CODE ====== -->
            <div class="step-form step-verify <?= $step === 'verify' ? 'active' : '' ?>" id="stepVerify">
                <div class="prism-header">
                    <div class="icon-shield"><i class="fa-solid fa-shield-halved"></i></div>
                    <div class="subtitle-spec"><?= cms_text('forgot_step2_subtitle', 'Konfirmasi Identitas') ?></div>
                    <h1 class="title-holo"><?= cms_text('forgot_step2_title', 'Kode Verifikasi') ?></h1>
                </div>
                <div class="step-indicator">
                    <div class="step-dot done"></div><div class="step-dot active"></div><div class="step-dot"></div>
                </div>
                <p style="text-align:center;color:var(--text-muted);font-size:clamp(0.75rem,1.5vh,0.85rem);margin-bottom:clamp(15px,3vh,20px);"><?= cms_text('forgot_step2_desc', 'Masukkan 6 digit kode yang telah dikirim ke email Anda.') ?></p>
                <form action="/auth/forgot-password/verify" method="POST" id="otpForm">
                    <?= csrf_field() ?>
                    <input type="hidden" name="code" id="otpHidden">
                    <div class="otp-group">
                        <input type="text" maxlength="1" class="otp-input" data-otp="0" inputmode="numeric" autocomplete="off">
                        <input type="text" maxlength="1" class="otp-input" data-otp="1" inputmode="numeric" autocomplete="off">
                        <input type="text" maxlength="1" class="otp-input" data-otp="2" inputmode="numeric" autocomplete="off">
                        <input type="text" maxlength="1" class="otp-input" data-otp="3" inputmode="numeric" autocomplete="off">
                        <input type="text" maxlength="1" class="otp-input" data-otp="4" inputmode="numeric" autocomplete="off">
                        <input type="text" maxlength="1" class="otp-input" data-otp="5" inputmode="numeric" autocomplete="off">
                    </div>
                    <button type="submit" class="btn-prime" id="btnVerify">
                        <?= cms_text('forgot_btn_verify', 'Verifikasi Kode') ?> <i class="fa-solid fa-check-double"></i>
                    </button>
                </form>
                <div class="resend-timer" id="resendArea">
                    <span id="timerText"><?= cms_text('forgot_resend_wait', 'Kirim ulang dalam') ?> <strong id="countdown">60</strong>s</span>
                </div>
                <div class="back-link">
                    <i class="fa-solid fa-arrow-left" style="font-size:0.7rem;margin-right:4px;"></i>
                    <a href="/auth/forgot-password"><?= cms_text('forgot_restart', 'Ulangi dari awal') ?></a>
                </div>
            </div>

            <!-- ====== STEP 3: RESET PASSWORD ====== -->
            <div class="step-form step-reset <?= $step === 'reset' ? 'active' : '' ?>" id="stepReset">
                <div class="prism-header">
                    <div class="icon-shield"><i class="fa-solid fa-lock-open"></i></div>
                    <div class="subtitle-spec"><?= cms_text('forgot_step3_subtitle', 'Tahap Akhir') ?></div>
                    <h1 class="title-holo"><?= cms_text('forgot_step3_title', 'Sandi Baru') ?></h1>
                </div>
                <div class="step-indicator">
                    <div class="step-dot done"></div><div class="step-dot done"></div><div class="step-dot active"></div>
                </div>
                <p style="text-align:center;color:var(--text-muted);font-size:clamp(0.75rem,1.5vh,0.85rem);margin-bottom:clamp(15px,3vh,20px);"><?= cms_text('forgot_step3_desc', 'Buat kata sandi baru minimal 8 karakter.') ?></p>
                <form action="/auth/forgot-password/reset" method="POST">
                    <?= csrf_field() ?>
                    <div class="input-group">
                        <input type="password" name="password" id="newPw" class="input-control" required placeholder=" " minlength="8">
                        <label for="newPw" class="input-label"><?= cms_text('forgot_label_newpw', 'Kata Sandi Baru') ?></label>
                        <div class="input-neon-line"></div>
                        <i class="fa-solid fa-eye icon-eye" onclick="togglePass('newPw',this)"></i>
                    </div>
                    <div class="input-group">
                        <input type="password" name="password_confirm" id="confirmPw" class="input-control" required placeholder=" " minlength="8">
                        <label for="confirmPw" class="input-label"><?= cms_text('forgot_label_confirmpw', 'Konfirmasi Sandi') ?></label>
                        <div class="input-neon-line"></div>
                        <i class="fa-solid fa-eye icon-eye" onclick="togglePass('confirmPw',this)"></i>
                    </div>
                    <button type="submit" class="btn-prime">
                        <?= cms_text('forgot_btn_save', 'Simpan Sandi Baru') ?> <i class="fa-solid fa-arrow-right-long"></i>
                    </button>
                </form>
                <div class="back-link">
                    <i class="fa-solid fa-arrow-left" style="font-size:0.7rem;margin-right:4px;"></i>
                    <a href="/login"><?= cms_text('forgot_back_login', 'Kembali ke Login') ?></a>
                </div>
            </div>

        </div>
    </div>

    <script>
        // OTP Input Logic
        const otpInputs = document.querySelectorAll('.otp-input');
        otpInputs.forEach((input, i) => {
            input.addEventListener('input', (e) => {
                const v = e.target.value.replace(/\D/g, '');
                e.target.value = v;
                if (v && i < otpInputs.length - 1) otpInputs[i + 1].focus();
            });
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && i > 0) otpInputs[i - 1].focus();
            });
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const data = (e.clipboardData.getData('text')).replace(/\D/g, '').slice(0, 6);
                data.split('').forEach((c, j) => { if (otpInputs[j]) otpInputs[j].value = c; });
                if (otpInputs[data.length - 1]) otpInputs[data.length - 1].focus();
            });
        });

        // OTP Form Submit
        document.getElementById('otpForm').addEventListener('submit', (e) => {
            let code = '';
            otpInputs.forEach(inp => code += inp.value);
            if (code.length < 6) { e.preventDefault(); otpInputs[0].focus(); return; }
            document.getElementById('otpHidden').value = code;
        });

        // Countdown Timer
        <?php if($step === 'verify'): ?>
        let sec = 60;
        const cd = document.getElementById('countdown');
        const ra = document.getElementById('resendArea');
        const ti = setInterval(() => {
            sec--;
            cd.textContent = sec;
            if (sec <= 0) {
                clearInterval(ti);
                ra.innerHTML = '<a href="/auth/forgot-password">Kirim ulang kode</a>';
            }
        }, 1000);
        <?php endif; ?>

        // Toggle Password
        function togglePass(id, el) {
            const f = document.getElementById(id);
            f.type = f.type === 'password' ? 'text' : 'password';
            el.classList.toggle('fa-eye'); el.classList.toggle('fa-eye-slash');
        }

        // Theme Toggle
        document.getElementById('btnTheme').addEventListener('click', () => {
            const isDark = document.body.getAttribute('data-theme') === 'dark';
            document.body.setAttribute('data-theme', isDark ? 'light' : 'dark');
            document.getElementById('themeText').innerText = isDark ? 'Siang' : 'Malam';
            document.getElementById('toggleIcon').className = isDark ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
        });

        // Toast Auto-hide
        const t = document.getElementById('toastAlert');
        if (t) setTimeout(() => t.classList.remove('show'), 6000);
    </script>
</body>
</html>
