function hapticNav() { if (navigator.vibrate) navigator.vibrate(20); }
window.hapticNav = hapticNav;

window.showToast = function(title, message, isError = false) {
    const aegisToastSys = document.getElementById('aegisToast');
    const radarNameSys = document.getElementById('radarName');
    if(aegisToastSys && radarNameSys) {
        document.querySelector('.aegis-title').innerText = title;
        radarNameSys.innerText = message;
        if(isError) {
            aegisToastSys.style.borderLeft = "4px solid #8b0000";
            document.querySelector('.aegis-title').style.color = "#8b0000";
            document.querySelector('.aegis-icon').style.color = "#8b0000";
        } else {
            aegisToastSys.style.borderLeft = "4px solid #d4af37";
            document.querySelector('.aegis-title').style.color = "var(--text-secondary)";
            document.querySelector('.aegis-icon').style.color = "#d4af37";
        }
        aegisToastSys.classList.add('show');
        if (navigator.vibrate) navigator.vibrate([50, 50, 50]);
        setTimeout(() => aegisToastSys.classList.remove('show'), 6000);
    }
};

window.showConfirm = function(title, text) {
    return new Promise((resolve) => {
        if (navigator.vibrate) navigator.vibrate(50);
        const overlay = document.createElement('div');
        overlay.style.position = 'fixed';
        overlay.style.inset = '0';
        overlay.style.background = 'rgba(0,0,0,0.85)';
        overlay.style.backdropFilter = 'blur(10px)';
        overlay.style.zIndex = '100000';
        overlay.style.display = 'flex';
        overlay.style.alignItems = 'center';
        overlay.style.justifyContent = 'center';
        overlay.style.opacity = '0';
        overlay.style.transition = 'opacity 0.3s ease';

        const box = document.createElement('div');
        box.style.background = 'rgba(15, 18, 16, 0.9)';
        box.style.border = '1px solid rgba(212, 175, 55, 0.4)';
        box.style.borderRadius = '20px';
        box.style.padding = '35px 30px';
        box.style.maxWidth = '400px';
        box.style.width = '90%';
        box.style.textAlign = 'center';
        box.style.transform = 'translateY(30px)';
        box.style.transition = 'transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.2)';
        box.style.boxShadow = '0 20px 50px rgba(0,0,0,0.8)';
        box.style.fontFamily = "'Inter', sans-serif";

        const iconEl = document.createElement('div');
        iconEl.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i>';
        iconEl.style.fontSize = '3rem';
        iconEl.style.color = '#d4af37';
        iconEl.style.marginBottom = '15px';

        const titleEl = document.createElement('h3');
        titleEl.textContent = title;
        titleEl.style.color = '#d4af37';
        titleEl.style.fontFamily = "'Playfair Display', serif";
        titleEl.style.marginTop = '0';
        titleEl.style.marginBottom = '15px';
        titleEl.style.fontSize = '1.5rem';

        const textEl = document.createElement('p');
        textEl.textContent = text;
        textEl.style.color = '#ccc';
        textEl.style.fontSize = '0.95rem';
        textEl.style.marginBottom = '30px';
        textEl.style.lineHeight = '1.5';

        const btnContainer = document.createElement('div');
        btnContainer.style.display = 'flex';
        btnContainer.style.gap = '15px';
        btnContainer.style.justifyContent = 'center';

        const btnCancel = document.createElement('button');
        btnCancel.textContent = 'BATAL';
        btnCancel.style.flex = '1';
        btnCancel.style.padding = '12px 15px';
        btnCancel.style.border = '1px solid rgba(255, 255, 255, 0.2)';
        btnCancel.style.background = 'rgba(255, 255, 255, 0.05)';
        btnCancel.style.color = '#fff';
        btnCancel.style.borderRadius = '50px';
        btnCancel.style.cursor = 'pointer';
        btnCancel.style.fontWeight = 'bold';
        btnCancel.style.letterSpacing = '1px';
        btnCancel.style.transition = '0.3s';
        btnCancel.onmouseover = () => btnCancel.style.background = 'rgba(255,255,255,0.1)';
        btnCancel.onmouseout = () => btnCancel.style.background = 'rgba(255,255,255,0.05)';

        const btnOk = document.createElement('button');
        btnOk.textContent = 'YA, LANJUTKAN';
        btnOk.style.flex = '1';
        btnOk.style.padding = '12px 15px';
        btnOk.style.border = 'none';
        btnOk.style.background = 'linear-gradient(135deg, #d4af37, #aa8529)';
        btnOk.style.color = '#000';
        btnOk.style.borderRadius = '50px';
        btnOk.style.cursor = 'pointer';
        btnOk.style.fontWeight = 'bold';
        btnOk.style.letterSpacing = '1px';
        btnOk.style.transition = '0.3s';
        btnOk.style.boxShadow = '0 5px 15px rgba(212, 175, 55, 0.3)';
        btnOk.onmouseover = () => btnOk.style.transform = 'translateY(-2px)';
        btnOk.onmouseout = () => btnOk.style.transform = 'translateY(0)';

        btnContainer.appendChild(btnCancel);
        btnContainer.appendChild(btnOk);

        box.appendChild(iconEl);
        box.appendChild(titleEl);
        box.appendChild(textEl);
        box.appendChild(btnContainer);
        overlay.appendChild(box);
        document.body.appendChild(overlay);

        setTimeout(() => {
            overlay.style.opacity = '1';
            box.style.transform = 'translateY(0)';
        }, 10);

        function close(result) {
            overlay.style.opacity = '0';
            box.style.transform = 'translateY(30px)';
            setTimeout(() => {
                overlay.remove();
                resolve(result);
            }, 300);
        }

        btnCancel.onclick = () => { if (navigator.vibrate) navigator.vibrate(20); close(false); };
        btnOk.onclick = () => { if (navigator.vibrate) navigator.vibrate(20); close(true); };
    });
};

document.addEventListener('DOMContentLoaded', () => {
    // SIDEBAR TOGGLE LOGIC
    const btnMenuOpen = document.getElementById('btnMenuOpen');
    const btnMenuClose = document.getElementById('btnMenuClose');
    
    if (btnMenuOpen && btnMenuClose) {
        btnMenuOpen.addEventListener('click', () => {
            document.body.classList.remove('sidebar-closed');
            hapticNav();
        });
        
        btnMenuClose.addEventListener('click', () => {
            document.body.classList.add('sidebar-closed');
            hapticNav();
        });

        // Auto-close sidebar on mobile after clicking a link
        if (window.innerWidth <= 768) {
            document.querySelectorAll('.sidebar .nav-item[href^="/"]').forEach(link => {
                link.addEventListener('click', () => {
                    document.body.classList.add('sidebar-closed');
                });
            });
        }
    } else {
        // Guest mode: no sidebar, so adjust main-wrapper left position
        document.body.classList.add('sidebar-closed');
    }

    // CURSOR LOGIC
    const cursorDot = document.getElementById('cursorDot');
    const cursorRing = document.getElementById('cursorRing');
    if (window.matchMedia("(pointer: fine)").matches && cursorDot && cursorRing) {
        let mouseX = 0, mouseY = 0, ringX = 0, ringY = 0;
        window.addEventListener('mousemove', (e) => {
            mouseX = e.clientX; mouseY = e.clientY;
            cursorDot.style.left = `${mouseX}px`; cursorDot.style.top = `${mouseY}px`;
        });
        function animateCursor() {
            ringX += (mouseX - ringX) * 0.15; ringY += (mouseY - ringY) * 0.15;
            cursorRing.style.left = `${ringX}px`; cursorRing.style.top = `${ringY}px`;
            requestAnimationFrame(animateCursor);
        }
        animateCursor();
        const addHover = () => document.body.classList.add('cursor-hovering');
        const removeHover = () => document.body.classList.remove('cursor-hovering');
        // Event Delegation — no more setInterval DOM scan
        document.body.addEventListener('mouseover', (e) => {
            const target = e.target.closest('a, button, .hover-trigger, .shard-wrapper');
            if (target) addHover();
        });
        document.body.addEventListener('mouseout', (e) => {
            const target = e.target.closest('a, button, .hover-trigger, .shard-wrapper');
            if (target) removeHover();
        });
        document.body.classList.add('custom-cursor-ready');
    }

    // THEME SETUP
    const currentTheme = document.documentElement.getAttribute('data-theme');
    const themeText = document.getElementById('themeText');
    const toggleIcon = document.getElementById('toggleIcon');
    if (currentTheme === 'light' && themeText && toggleIcon) {
        themeText.innerText = 'Siang';
        toggleIcon.className = 'fa-solid fa-sun';
    }

    const btnTheme = document.getElementById('btnTheme');
    if (btnTheme) {
        btnTheme.addEventListener('click', () => {
            hapticNav();
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            const newTheme = isDark ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('expedient_theme', newTheme);
            
            if (window.particlesArray && window.Particle) {
                for(let i=0; i<20; i++) window.particlesArray.push(new window.Particle(window.innerWidth - 50, 50, Math.random()*3+1, -(Math.random()*4+2)));
            }
            
            if (newTheme === 'light') { 
                themeText.innerText = 'Siang'; 
                toggleIcon.className = 'fa-solid fa-sun'; 
            } else { 
                themeText.innerText = 'Malam'; 
                toggleIcon.className = 'fa-solid fa-moon'; 
            }
        });
    }
});

// Event-driven loader — dismiss as soon as page is ready (min 300ms for animation)
const loaderStart = Date.now();
window.addEventListener('load', () => {
    const elapsed = Date.now() - loaderStart;
    const remaining = Math.max(300 - elapsed, 0);
    setTimeout(() => {
        const loader = document.getElementById('loadingScreen');
        if (loader) {
            loader.style.opacity = '0';
            setTimeout(() => loader.style.visibility = 'hidden', 800);
        }
    }, remaining);

    // SW Registration
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js').then(registration => {
            console.log('SW registered: ', registration);
            
            // Tanya izin Push Notification & Subscribe
            if (window.ExpedientConfig && window.ExpedientConfig.userId !== null) {
                Notification.requestPermission().then(permission => {
                    if (permission === 'granted') {
                        subscribeUserToPush(registration);
                    }
                });
            }
        }).catch(registrationError => {
            console.log('SW registration failed: ', registrationError);
        });
    }
});

// Fungsi untuk konversi VAPID public key
function urlB64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);
    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}

function subscribeUserToPush(registration) {
    // Ambil VAPID key dari backend
    fetch('/push/public-key')
        .then(res => res.json())
        .then(data => {
            const applicationServerKey = urlB64ToUint8Array(data.publicKey);
            return registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: applicationServerKey
            });
        })
        .then(subscription => {
            // Kirim ke backend
            return fetch('/push/subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(subscription)
            });
        })
        .then(response => response.json())
        .then(result => console.log('[Web Push] Subscribed:', result))
        .catch(err => console.error('[Web Push] Failed to subscribe:', err));
}
