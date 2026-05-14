<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
The Lounge - Expedient
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .lounge-wrapper {
        padding: clamp(80px, 15vh, 120px) 20px 40px;
        max-width: 900px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        height: 100vh;
    }
    
    .lounge-header {
        text-align: center;
        margin-bottom: 20px;
    }
    .chat-header {
        text-align: center;
        margin-bottom: 20px;
        position: relative;
    }
    .lounge-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2rem, 4vw, 3rem);
        color: #d4af37;
        text-transform: uppercase;
        letter-spacing: 3px;
        margin-bottom: 5px;
    }
    .lounge-subtitle {
        color: var(--text-secondary);
        font-size: 0.9rem;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .chat-container {
        flex: 1;
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    }
    :root[data-theme="light"] .chat-container {
        box-shadow: 0 20px 40px rgba(0,0,0,0.05);
    }

    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 30px;
        display: flex;
        flex-direction: column;
        gap: 20px;
        scroll-behavior: smooth;
    }

    .message-bubble {
        display: flex;
        gap: 15px;
        max-width: 85%;
        opacity: 0;
        transform: translateY(20px);
        animation: slideUp 0.4s ease forwards;
    }
    @keyframes slideUp { to { opacity: 1; transform: translateY(0); } }

    .message-bubble.mine {
        align-self: flex-end;
        flex-direction: row-reverse;
    }

    .message-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 1px solid rgba(212,175,55,0.4);
        object-fit: cover;
        flex-shrink: 0;
    }

    .message-content {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.05);
        padding: 15px 20px;
        border-radius: 0 15px 15px 15px;
        font-size: 0.95rem;
        line-height: 1.5;
        color: var(--text-primary);
        position: relative;
    }
    :root[data-theme="light"] .message-content {
        background: rgba(0,0,0,0.03);
        border-color: rgba(0,0,0,0.05);
    }
    .message-bubble.mine .message-content {
        background: rgba(212,175,55,0.1);
        border-color: rgba(212,175,55,0.2);
        border-radius: 15px 0 15px 15px;
        color: #fff;
    }
    :root[data-theme="light"] .message-bubble.mine .message-content {
        color: #000;
    }

    .message-sender {
        font-family: 'Playfair Display', serif;
        font-size: 0.85rem;
        color: #d4af37;
        margin-bottom: 5px;
        display: block;
        font-weight: 700;
    }
    .message-bubble.mine .message-sender { display: none; }

    .message-time {
        font-size: 0.65rem;
        color: var(--text-secondary);
        margin-top: 5px;
        text-align: right;
        display: block;
        font-family: 'Courier New', monospace;
    }

    .chat-input-area {
        padding: 20px;
        background: rgba(0,0,0,0.2);
        border-top: 1px solid var(--glass-border);
        display: flex;
        gap: 15px;
        align-items: center;
    }
    :root[data-theme="light"] .chat-input-area {
        background: rgba(255,255,255,0.4);
    }

    .chat-input {
        flex: 1;
        background: transparent;
        border: none;
        outline: none;
        color: var(--text-primary);
        font-size: 1rem;
        font-family: 'Inter', sans-serif;
        padding: 10px;
    }
    .chat-input::placeholder {
        color: var(--text-secondary);
    }

    .btn-send {
        background: transparent;
        color: #d4af37;
        border: 1px solid #d4af37;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        transition: 0.3s;
        font-size: 1.1rem;
    }
    .btn-send:hover {
        background: #d4af37;
        color: #000;
        transform: scale(1.05) rotate(-10deg);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="lounge-wrapper">
    <div class="chat-header">
        <h2 class="chat-title" style="margin: 0; font-family: 'Playfair Display', serif; font-size: 1.5rem; color: #d4af37;">
            <?= $receiver ? esc($receiver['nama_panggilan'] ?: $receiver['nama_lengkap']) : 'The Lounge' ?>
        </h2>
        <div style="font-size: 0.8rem; color: var(--text-secondary); letter-spacing: 2px;">
            <?= $receiver ? 'OBROLAN EKSKLUSIF' : 'RUANG DISKUSI ANGKATAN' ?>
        </div>
        <a href="/profil" style="position:absolute; right:30px; top:30px; color:var(--text-secondary); text-decoration:none;"><i class="fa-solid fa-times"></i></a>
    </div>

    <div class="chat-container">
        <div class="chat-messages" id="chatMessages">
            <?php foreach($messages as $msg): ?>
                <?php $isMine = ($msg['sender_id'] == $user_id); ?>
                <div class="message-bubble <?= $isMine ? 'mine' : '' ?>">
                    <?php if(!$isMine): ?>
                        <img src="<?= !empty($msg['sender_avatar']) ? '/uploads/profiles/'.$msg['sender_avatar'] : 'https://ui-avatars.com/api/?name='.urlencode($msg['sender_name']).'&background=d4af37&color=000' ?>" class="message-avatar">
                    <?php endif; ?>
                    <div class="message-content">
                        <?php if(!$isMine): ?>
                            <span class="message-sender"><?= esc($msg['sender_name']) ?></span>
                        <?php endif; ?>
                        <?= nl2br(esc($msg['message'])) ?>
                        <span class="message-time"><?= date('H:i', strtotime($msg['created_at'])) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if(empty($messages)): ?>
                <div style="text-align:center; color:var(--text-secondary); font-style:italic; margin:auto;" id="emptyMsg">
                    Belum ada diskusi di The Lounge. Jadilah yang pertama.
                </div>
            <?php endif; ?>
        </div>
        
        <div class="chat-input-area">
            <input type="text" id="chatInput" class="chat-input" placeholder="Tulis pesan eksklusif Anda..." autocomplete="off">
            <button id="btnSendChat" class="btn-send"><i class="fa-solid fa-paper-plane"></i></button>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const myId = <?= $user_id ?>;
    const currentReceiverId = <?= $receiver ? $receiver['id'] : 'null' ?>;
    const chatContainer = document.getElementById('chatMessages');
    const input = document.getElementById('chatInput');
    const btnSend = document.getElementById('btnSendChat');
    const emptyMsg = document.getElementById('emptyMsg');

    function scrollToBottom() {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
    
    // Scroll ke bawah saat pertama kali diload
    scrollToBottom();

    // Bind Pusher Khusus Chat
    const chatChannel = pusher.subscribe('chat-channel');
    chatChannel.bind('new-message', function(data) {
        // Filter logic
        if (currentReceiverId === null) {
            // Kita sedang di Lounge, abaikan pesan personal
            if (data.receiver_id !== null) return;
        } else {
            // Kita sedang di Personal Chat
            // Tampilkan jika pesan ini dari target ke kita, atau dari kita ke target
            const isRelevant = (data.sender_id == currentReceiverId && data.receiver_id == myId) ||
                               (data.sender_id == myId && data.receiver_id == currentReceiverId);
            if (!isRelevant) return;

            // Jika ini pesan dari lawan bicara ke kita, tandai sudah dibaca di server
            if (data.sender_id == currentReceiverId) {
                markRead(data.sender_id);
            }
        }

        if(emptyMsg) emptyMsg.style.display = 'none';

        const isMine = data.sender_id == myId;
        
        let avatarHtml = '';
        let senderHtml = '';
        if(!isMine) {
            const avatarUrl = data.sender_avatar && data.sender_avatar !== 'default.webp' 
                ? '/uploads/profiles/' + data.sender_avatar 
                : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(data.sender_name) + '&background=d4af37&color=000';
            avatarHtml = `<img src="${avatarUrl}" class="message-avatar">`;
            senderHtml = `<span class="message-sender">${data.sender_name}</span>`;
        }

        const timeString = new Date(data.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});

        const html = `
            <div class="message-bubble ${isMine ? 'mine' : ''}">
                ${avatarHtml}
                <div class="message-content">
                    ${senderHtml}
                    ${data.message.replace(/\n/g, '<br>')}
                    <span class="message-time">${timeString}</span>
                </div>
            </div>
        `;
        
        chatContainer.insertAdjacentHTML('beforeend', html);
        scrollToBottom();
        
        if(!isMine && navigator.vibrate) navigator.vibrate([30]);
    });

    async function sendChat() {
        const message = input.value;
        const receiverId = <?= $receiver ? $receiver['id'] : 'null' ?>;
        const csrfHash = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        if(!message.trim()) return;

        input.value = ''; 
        input.focus();

        try {
            const resp = await fetch('/chat/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfHash
                },
                body: new URLSearchParams({ 
                    message: message,
                    receiver_id: receiverId || ''
                })
            });
            const result = await resp.json();
            // Update CSRF hash for next request (CI4 regenerates token)
            if(result.csrf_hash) {
                document.querySelector('meta[name="csrf-token"]').setAttribute('content', result.csrf_hash);
            }
        } catch(e) {
            console.error('Failed to send message', e);
        }
    }

    async function markRead(senderId) {
        const csrfHash = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        try {
            const resp = await fetch('/chat/read/' + senderId, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfHash
                }
            });
            const result = await resp.json();
            if(result.csrf_hash) {
                document.querySelector('meta[name="csrf-token"]').setAttribute('content', result.csrf_hash);
            }
        } catch(e) {}
    }

    btnSend.addEventListener('click', sendChat);
    input.addEventListener('keypress', (e) => {
        if(e.key === 'Enter') sendChat();
    });
</script>
<?= $this->endSection() ?>
