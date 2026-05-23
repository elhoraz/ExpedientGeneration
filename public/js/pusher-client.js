// ================= PUSHER CLOUD REAL-TIME (HANYA ANGGOTA) =================
// Requires window.ExpedientConfig to be set in template.php
if (window.ExpedientConfig && window.ExpedientConfig.userId !== null) {
    const config = window.ExpedientConfig.pusher;
    
    window.pusher = new Pusher(config.key, { 
        cluster: config.cluster, 
        forceTLS: true 
    });
    
    // Monitor koneksi Pusher
    window.pusher.connection.bind('state_change', function(states) {
        console.log('[Pusher] State:', states.previous, '→', states.current);
    });
    window.pusher.connection.bind('error', function(err) {
        console.error('[Pusher] Connection error:', err);
    });
    
    const channel = window.pusher.subscribe('expedient-channel');
    const globalChatChannel = window.pusher.subscribe('chat-channel');
    
    let aegisLink = '#';
    const aegisToastElement = document.getElementById('aegisToast');
    if (aegisToastElement) {
        aegisToastElement.addEventListener('click', () => {
            if(aegisLink && aegisLink !== '#') {
                window.location.href = aegisLink;
            }
        });
    }
    
    channel.bind('alumni-baru', function(data) {
        const aegisToast = document.getElementById('aegisToast');
        const radarName = document.getElementById('radarName');
        if(aegisToast && radarName) {
            document.querySelector('.aegis-title').innerText = "Kolega Baru Bergabung";
            radarName.innerText = data.nama; 
            aegisLink = '/direktori';
            aegisToast.classList.add('show');
            if (navigator.vibrate) navigator.vibrate([100, 100, 100]);
            setTimeout(() => aegisToast.classList.remove('show'), 6000);
            fetchUnreadNotifs(); // Refresh list jika ada
        }
    });

    // CHAT REAL-TIME BADGE
    globalChatChannel.bind('new-message', function(data) {
        // Jika kita tidak sedang di halaman chat tersebut, tambah badge
        if (window.location.pathname !== '/chat/personal/' + data.sender_id && 
            window.location.pathname !== '/chat/lounge' &&
            data.sender_id != window.ExpedientConfig.userId) {
            
            if (data.receiver_id == window.ExpedientConfig.userId || data.receiver_id === null) {
                fetchUnreadChat();
                
                // Juga munculkan toast jika pesan personal
                if (data.receiver_id !== null) {
                    const aegisToast = document.getElementById('aegisToast');
                    const radarName = document.getElementById('radarName');
                    if(aegisToast && radarName) {
                        document.querySelector('.aegis-title').innerText = "Pesan Baru dari " + data.sender_name;
                        radarName.innerText = data.message.substring(0, 50) + (data.message.length > 50 ? '...' : ''); 
                        aegisLink = '/chat/personal/' + data.sender_id;
                        aegisToast.classList.add('show');
                        if (navigator.vibrate) navigator.vibrate([100, 50]);
                        setTimeout(() => aegisToast.classList.remove('show'), 6000);
                    }
                }
            }
        }
    });

    // NOTIFIKASI UMUM EVENT (PERSONAL)
    channel.bind('new-notification', function(data) {
        if (data.user_id == window.ExpedientConfig.userId) {
            const aegisToast = document.getElementById('aegisToast');
            const radarName = document.getElementById('radarName');
            if(aegisToast && radarName) {
                document.querySelector('.aegis-title').innerText = data.title;
                radarName.innerText = data.message; 
                aegisLink = data.link || '#';
                aegisToast.classList.add('show');
                if (navigator.vibrate) navigator.vibrate([50, 50, 100]);
                setTimeout(() => aegisToast.classList.remove('show'), 6000);
                fetchUnreadNotifs();
            }
        }
    });

    // NOTIFIKASI GLOBAL (BROADCAST)
    channel.bind('broadcast-notification', function(data) {
        const aegisToast = document.getElementById('aegisToast');
        const radarName = document.getElementById('radarName');
        if(aegisToast && radarName) {
            document.querySelector('.aegis-title').innerText = data.title;
            radarName.innerText = data.message; 
            aegisLink = data.link || '#';
            aegisToast.classList.add('show');
            if (navigator.vibrate) navigator.vibrate([50, 50, 100]);
            setTimeout(() => aegisToast.classList.remove('show'), 6000);
        }
    });

    // NOTIFIKASI UI LOGIC
    window.toggleNotif = function() {
        const dropdown = document.getElementById('notifDropdown');
        if (dropdown) {
            if (dropdown.style.display === 'none' || dropdown.style.display === '') {
                dropdown.style.display = 'block';
                // Adjust position for mobile
                if (window.innerWidth <= 768) {
                    dropdown.style.left = '50%';
                    dropdown.style.transform = 'translateX(-50%) translateY(10px)';
                }
                requestAnimationFrame(() => {
                    dropdown.style.transform = window.innerWidth <= 768 ? 'translateX(-50%) translateY(0)' : 'translateY(0)';
                    dropdown.style.opacity = '1';
                });
                fetchUnreadNotifs();
            } else {
                dropdown.style.transform = window.innerWidth <= 768 ? 'translateX(-50%) translateY(10px)' : 'translateY(10px)';
                dropdown.style.opacity = '0';
                setTimeout(() => dropdown.style.display = 'none', 300);
            }
        }
    }

    async function fetchUnreadNotifs() {
        try {
            const res = await fetch('/notifications/unread');
            const data = await res.json();
            if (data.status === 'success') {
                const badge = document.getElementById('notifBadge');
                const list = document.getElementById('notifList');
                
                if (badge && list) {
                    if (data.data.length > 0) {
                        badge.style.display = 'block';
                        list.innerHTML = data.data.map(n => `
                            <div onclick="readNotif(${n.id}, '${n.link || '#'}')" style="padding:12px; border-bottom:1px solid rgba(255,255,255,0.05); cursor:pointer; transition:0.3s;" onmouseover="this.style.background='rgba(212,175,55,0.1)'" onmouseout="this.style.background='transparent'">
                                <div style="font-size:0.85rem; font-weight:600; color:var(--text-primary); margin-bottom:4px;">${n.title}</div>
                                <div style="font-size:0.75rem; color:var(--text-secondary); line-height:1.4;">${n.message}</div>
                            </div>
                        `).join('');
                    } else {
                        badge.style.display = 'none';
                        list.innerHTML = '<div style="text-align:center; padding:20px; color:var(--text-secondary); font-size:0.85rem;">Tidak ada pesan baru.</div>';
                    }
                }
            }
        } catch(e) {}
    }

    async function fetchUnreadChat() {
        try {
            const res = await fetch('/chat/unread');
            const data = await res.json();
            if (data.status === 'success') {
                const badge = document.getElementById('chatBadge');
                if(badge) {
                    if (data.count > 0) {
                        badge.style.display = 'block';
                    } else {
                        badge.style.display = 'none';
                    }
                }
            }
        } catch(e) {}
    }

    window.readNotif = async function(id, link) {
        try {
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfHash = csrfMeta ? csrfMeta.getAttribute('content') : '';
            const resp = await fetch('/notifications/read/' + id, { 
                method: 'POST', 
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfHash
                } 
            });
            const result = await resp.json().catch(() => ({}));
            if(result.csrf_hash && csrfMeta) {
                csrfMeta.setAttribute('content', result.csrf_hash);
            }
            window.location.href = link;
        } catch(e) {
            window.location.href = link;
        }
    }

    // Initial fetch
    setTimeout(() => {
        fetchUnreadNotifs();
        fetchUnreadChat();
    }, 2000);
}
