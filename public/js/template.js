function hapticNav() { if (navigator.vibrate) navigator.vibrate(20); }
window.hapticNav = hapticNav;

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
