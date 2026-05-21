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

    /* Load More */
    .load-more-btn {
        align-self: center;
        background: rgba(212,175,55,0.1);
        border: 1px solid rgba(212,175,55,0.2);
        color: #d4af37;
        padding: 8px 20px;
        border-radius: 20px;
        cursor: pointer;
        font-size: 0.75rem;
        letter-spacing: 1px;
        text-transform: uppercase;
        transition: 0.3s;
    }
    .load-more-btn:hover { background: rgba(212,175,55,0.2); }

    .message-bubble {
        display: flex;
        gap: 15px;
        max-width: 85%;
        opacity: 0;
        transform: translateY(20px);
        animation: slideUp 0.4s ease forwards;
        position: relative;
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

    .message-img {
        max-width: 280px;
        max-height: 280px;
        border-radius: 10px;
        margin-top: 8px;
        cursor: pointer;
        transition: 0.3s;
        display: block;
    }
    .message-img:hover { opacity: 0.8; }

    /* Delete button */
    .btn-delete-msg {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(255,50,50,0.2);
        border: none;
        color: #ff5555;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 0.6rem;
        display: none;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
    }
    .message-bubble.mine:hover .btn-delete-msg { display: flex; }
    .btn-delete-msg:hover { background: rgba(255,50,50,0.5); }

    /* Input Area */
    .chat-input-area {
        padding: 15px 20px;
        background: rgba(0,0,0,0.2);
        border-top: 1px solid var(--glass-border);
        display: flex;
        gap: 10px;
        align-items: flex-end;
    }
    :root[data-theme="light"] .chat-input-area {
        background: rgba(255,255,255,0.4);
    }

    .chat-input-wrap {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 5px;
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
        resize: none;
        max-height: 100px;
        min-height: 20px;
    }
    .chat-input::placeholder { color: var(--text-secondary); }
    .char-counter {
        font-size: 0.65rem;
        color: var(--text-secondary);
        text-align: right;
        font-family: monospace;
    }
    .char-counter.warn { color: #ff5555; }

    /* Image preview */
    .img-preview-wrap {
        display: none;
        position: relative;
        padding: 5px 0;
    }
    .img-preview-wrap img {
        max-height: 80px;
        border-radius: 8px;
        border: 1px solid rgba(212,175,55,0.3);
    }
    .img-preview-close {
        position: absolute;
        top: 0;
        right: 0;
        background: rgba(255,50,50,0.8);
        border: none;
        color: #fff;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 0.7rem;
    }

    /* Action buttons */
    .chat-actions {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    .btn-chat-action {
        background: transparent;
        border: none;
        color: var(--text-secondary);
        font-size: 1.2rem;
        cursor: pointer;
        padding: 8px;
        border-radius: 8px;
        transition: 0.2s;
        position: relative;
    }
    .btn-chat-action:hover { color: #d4af37; background: rgba(212,175,55,0.1); }

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
        flex-shrink: 0;
    }
    .btn-send:hover {
        background: #d4af37;
        color: #000;
        transform: scale(1.05) rotate(-10deg);
    }

    /* Emoji Picker */
    .emoji-picker {
        display: none;
        position: absolute;
        bottom: 50px;
        left: 0;
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 12px;
        padding: 12px;
        width: 280px;
        max-height: 250px;
        overflow-y: auto;
        z-index: 100;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }
    .emoji-picker.show { display: block; }
    .emoji-category {
        font-size: 0.65rem;
        color: #d4af37;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin: 8px 0 4px;
        font-weight: bold;
    }
    .emoji-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
    }
    .emoji-btn {
        font-size: 1.4rem;
        background: none;
        border: none;
        cursor: pointer;
        padding: 4px;
        border-radius: 6px;
        transition: 0.15s;
        line-height: 1;
    }
    .emoji-btn:hover { background: rgba(212,175,55,0.2); transform: scale(1.2); }

    /* Fullscreen image modal */
    .img-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.9);
        z-index: 9999;
        justify-content: center;
        align-items: center;
        cursor: pointer;
    }
    .img-modal.show { display: flex; }
    .img-modal img { max-width: 90vw; max-height: 90vh; border-radius: 10px; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="lounge-wrapper">
    <div class="chat-header">
        <h2 style="margin: 0; font-family: 'Playfair Display', serif; font-size: 1.5rem; color: #d4af37;">
            <?= $receiver ? esc($receiver['nama_panggilan'] ?: $receiver['nama_lengkap']) : 'The Lounge' ?>
        </h2>
        <div style="font-size: 0.8rem; color: var(--text-secondary); letter-spacing: 2px;">
            <?= $receiver ? 'OBROLAN EKSKLUSIF' : 'RUANG DISKUSI ANGKATAN' ?>
        </div>
        <a href="/chat" style="position:absolute; left:10px; top:5px; color:var(--text-secondary); text-decoration:none; font-size:0.8rem;"><i class="fa-solid fa-arrow-left"></i> Inbox</a>
        <a href="/profil" style="position:absolute; right:10px; top:5px; color:var(--text-secondary); text-decoration:none;"><i class="fa-solid fa-times"></i></a>
    </div>

    <div class="chat-container">
        <div class="chat-messages" id="chatMessages">
            <!-- Load More Button -->
            <?php if(count($messages) >= 50): ?>
            <button class="load-more-btn" id="btnLoadMore">Muat Pesan Sebelumnya</button>
            <?php endif; ?>

            <?php foreach($messages as $msg): ?>
                <?php $isMine = ($msg['sender_id'] == $user_id); ?>
                <div class="message-bubble <?= $isMine ? 'mine' : '' ?>" data-msg-id="<?= $msg['id'] ?>">
                    <?php if(!$isMine): ?>
                        <img src="<?= !empty($msg['sender_avatar']) ? '/uploads/profiles/'.$msg['sender_avatar'] : 'https://ui-avatars.com/api/?name='.urlencode($msg['sender_name']).'&background=d4af37&color=000' ?>" class="message-avatar">
                    <?php endif; ?>
                    <div class="message-content">
                        <?php if($isMine): ?>
                            <button class="btn-delete-msg" onclick="deleteMsg(<?= $msg['id'] ?>)" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                        <?php endif; ?>
                        <?php if(!$isMine): ?>
                            <span class="message-sender"><?= esc($msg['sender_name']) ?></span>
                        <?php endif; ?>
                        <?php if(!empty($msg['message'])): ?>
                            <?= nl2br(esc($msg['message'])) ?>
                        <?php endif; ?>
                        <?php if(!empty($msg['image_path'])): ?>
                            <img src="/uploads/chat/<?= esc($msg['image_path']) ?>" class="message-img" onclick="openImgModal(this.src)" alt="Gambar">
                        <?php endif; ?>
                        <span class="message-time"><?= date('H:i', strtotime($msg['created_at'])) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if(empty($messages)): ?>
                <div style="text-align:center; color:var(--text-secondary); font-style:italic; margin:auto;" id="emptyMsg">
                    Belum ada diskusi. Jadilah yang pertama.
                </div>
            <?php endif; ?>
        </div>
        
        <div class="chat-input-area">
            <div class="chat-actions">
                <button class="btn-chat-action" id="btnEmoji" title="Emoji"><i class="fa-solid fa-face-smile"></i>
                    <div class="emoji-picker" id="emojiPicker"></div>
                </button>
                <button class="btn-chat-action" id="btnAttachImg" title="Kirim Gambar"><i class="fa-solid fa-image"></i></button>
                <input type="file" id="chatImageInput" accept="image/*" style="display:none;">
            </div>
            <div class="chat-input-wrap">
                <div class="img-preview-wrap" id="imgPreviewWrap">
                    <img id="imgPreview" src="" alt="Preview">
                    <button class="img-preview-close" id="imgPreviewClose">&times;</button>
                </div>
                <input type="text" id="chatInput" class="chat-input" placeholder="Tulis pesan..." autocomplete="off" maxlength="1000">
                <div class="char-counter" id="charCounter">0 / 1000</div>
            </div>
            <button id="btnSendChat" class="btn-send"><i class="fa-solid fa-paper-plane"></i></button>
        </div>
    </div>
</div>

<!-- Fullscreen Image Modal -->
<div class="img-modal" id="imgModal" onclick="this.classList.remove('show')">
    <img id="imgModalSrc" src="" alt="Full Image">
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
    const charCounter = document.getElementById('charCounter');
    const emojiPicker = document.getElementById('emojiPicker');
    const chatImageInput = document.getElementById('chatImageInput');
    const imgPreviewWrap = document.getElementById('imgPreviewWrap');
    const imgPreview = document.getElementById('imgPreview');
    let selectedImageFile = null;

    // ============ EMOJI PICKER ============
    const emojiData = {
        'Wajah': ['😀','😂','🤣','😍','😎','🥰','😢','😭','🤔','😱','🥺','😤','🤝','🙏','💪','👍','👎','❤️','🔥','✨','💯','🎉','🎊'],
        'Islami': ['☪️','🕌','📿','🤲','🌙','⭐','🕋','📖','🌹','🫶'],
        'Aktivitas': ['🎓','📚','⚽','🏀','🎯','🏆','💼','🎵','🎤','📸'],
        'Lainnya': ['👀','💬','📌','🚀','💎','🌍','☕','🍕','👑','⚡','🌈','💡']
    };
    
    let pickerHTML = '';
    for (const [cat, emojis] of Object.entries(emojiData)) {
        pickerHTML += `<div class="emoji-category">${cat}</div><div class="emoji-grid">`;
        emojis.forEach(e => { pickerHTML += `<button class="emoji-btn" type="button">${e}</button>`; });
        pickerHTML += '</div>';
    }
    emojiPicker.innerHTML = pickerHTML;

    document.getElementById('btnEmoji').addEventListener('click', (e) => {
        e.stopPropagation();
        emojiPicker.classList.toggle('show');
    });
    emojiPicker.addEventListener('click', (e) => {
        if (e.target.classList.contains('emoji-btn')) {
            input.value += e.target.textContent;
            input.focus();
            updateCharCounter();
            emojiPicker.classList.remove('show');
        }
        e.stopPropagation();
    });
    document.addEventListener('click', () => emojiPicker.classList.remove('show'));

    // ============ IMAGE UPLOAD ============
    document.getElementById('btnAttachImg').addEventListener('click', () => chatImageInput.click());
    chatImageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;
        if (file.size > 3 * 1024 * 1024) { alert('Ukuran gambar maks 3MB.'); this.value = ''; return; }
        selectedImageFile = file;
        const reader = new FileReader();
        reader.onload = (e) => { imgPreview.src = e.target.result; imgPreviewWrap.style.display = 'block'; };
        reader.readAsDataURL(file);
    });
    document.getElementById('imgPreviewClose').addEventListener('click', () => {
        selectedImageFile = null;
        chatImageInput.value = '';
        imgPreviewWrap.style.display = 'none';
    });

    // ============ CHAR COUNTER ============
    function updateCharCounter() {
        const len = input.value.length;
        charCounter.textContent = `${len} / 1000`;
        charCounter.classList.toggle('warn', len > 900);
    }
    input.addEventListener('input', updateCharCounter);

    // ============ IMAGE MODAL ============
    function openImgModal(src) {
        document.getElementById('imgModalSrc').src = src;
        document.getElementById('imgModal').classList.add('show');
    }

    // ============ SCROLL ============
    function scrollToBottom() { chatContainer.scrollTop = chatContainer.scrollHeight; }
    scrollToBottom();

    // ============ PUSHER ============
    const chatChannel = pusher.subscribe('chat-channel');
    chatChannel.bind('new-message', function(data) {
        if (currentReceiverId === null) {
            if (data.receiver_id !== null) return;
        } else {
            const isRelevant = (data.sender_id == currentReceiverId && data.receiver_id == myId) ||
                               (data.sender_id == myId && data.receiver_id == currentReceiverId);
            if (!isRelevant) return;
            if (data.sender_id == currentReceiverId) markRead(data.sender_id);
        }

        if(emptyMsg) emptyMsg.style.display = 'none';

        const isMine = data.sender_id == myId;
        let avatarHtml = '', senderHtml = '', deleteHtml = '';
        if(!isMine) {
            const avatarUrl = data.sender_avatar && data.sender_avatar !== 'default.webp' 
                ? '/uploads/profiles/' + data.sender_avatar 
                : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(data.sender_name) + '&background=d4af37&color=000';
            avatarHtml = `<img src="${avatarUrl}" class="message-avatar">`;
            senderHtml = `<span class="message-sender">${data.sender_name}</span>`;
        } else {
            deleteHtml = `<button class="btn-delete-msg" onclick="deleteMsg(${data.id})" title="Hapus"><i class="fa-solid fa-trash"></i></button>`;
        }

        const timeString = new Date(data.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        let imageHtml = '';
        if (data.image_path) {
            imageHtml = `<img src="/uploads/chat/${data.image_path}" class="message-img" onclick="openImgModal(this.src)" alt="Gambar">`;
        }
        const msgText = data.message ? data.message.replace(/\n/g, '<br>') : '';

        const html = `
            <div class="message-bubble ${isMine ? 'mine' : ''}" data-msg-id="${data.id}">
                ${avatarHtml}
                <div class="message-content">
                    ${deleteHtml}
                    ${senderHtml}
                    ${msgText}
                    ${imageHtml}
                    <span class="message-time">${timeString}</span>
                </div>
            </div>
        `;
        
        chatContainer.insertAdjacentHTML('beforeend', html);
        scrollToBottom();
        if(!isMine && navigator.vibrate) navigator.vibrate([30]);
    });

    // ============ SEND ============
    async function sendChat() {
        const message = input.value;
        const csrfHash = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        if(!message.trim() && !selectedImageFile) return;

        const formData = new FormData();
        formData.append('message', message);
        formData.append('receiver_id', currentReceiverId || '');
        formData.append('csrf_test_name', csrfHash);
        if (selectedImageFile) {
            formData.append('chat_image', selectedImageFile);
        }

        input.value = ''; 
        input.focus();
        updateCharCounter();
        selectedImageFile = null;
        chatImageInput.value = '';
        imgPreviewWrap.style.display = 'none';

        try {
            const resp = await fetch('/chat/send', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfHash },
                body: formData
            });
            const result = await resp.json();
            if(result.csrf_hash) document.querySelector('meta[name="csrf-token"]').setAttribute('content', result.csrf_hash);
            if(result.status === 'error' && result.message) alert(result.message);
        } catch(e) { console.error('Failed to send message', e); }
    }

    // ============ DELETE MESSAGE ============
    async function deleteMsg(msgId) {
        if (!confirm('Hapus pesan ini?')) return;
        const csrfHash = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        try {
            const resp = await fetch('/chat/delete/' + msgId, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfHash }
            });
            const result = await resp.json();
            if(result.csrf_hash) document.querySelector('meta[name="csrf-token"]').setAttribute('content', result.csrf_hash);
            if(result.status === 'success') {
                const el = document.querySelector(`[data-msg-id="${msgId}"]`);
                if(el) { el.style.transition = '0.3s'; el.style.opacity = '0'; el.style.transform = 'scale(0.8)'; setTimeout(() => el.remove(), 300); }
            }
        } catch(e) {}
    }

    // ============ LOAD MORE ============
    const btnLoadMore = document.getElementById('btnLoadMore');
    if (btnLoadMore) {
        btnLoadMore.addEventListener('click', async () => {
            const firstMsg = chatContainer.querySelector('.message-bubble');
            if (!firstMsg) return;
            const beforeId = firstMsg.dataset.msgId;
            btnLoadMore.textContent = 'Memuat...';
            btnLoadMore.disabled = true;
            try {
                const resp = await fetch(`/chat/load-more?before_id=${beforeId}&receiver_id=${currentReceiverId}`);
                const result = await resp.json();
                if (result.status === 'success' && result.data.length > 0) {
                    const scrollBefore = chatContainer.scrollHeight;
                    result.data.forEach(msg => {
                        const isMine = msg.sender_id == myId;
                        let avatarHtml = '', senderHtml = '', deleteHtml = '';
                        if (!isMine) {
                            const av = msg.sender_avatar && msg.sender_avatar !== 'default.webp'
                                ? '/uploads/profiles/' + msg.sender_avatar
                                : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(msg.sender_name) + '&background=d4af37&color=000';
                            avatarHtml = `<img src="${av}" class="message-avatar">`;
                            senderHtml = `<span class="message-sender">${msg.sender_name}</span>`;
                        } else {
                            deleteHtml = `<button class="btn-delete-msg" onclick="deleteMsg(${msg.id})" title="Hapus"><i class="fa-solid fa-trash"></i></button>`;
                        }
                        let imgHtml = msg.image_path ? `<img src="/uploads/chat/${msg.image_path}" class="message-img" onclick="openImgModal(this.src)">` : '';
                        const time = new Date(msg.created_at).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});
                        const msgText = msg.message ? msg.message.replace(/\n/g, '<br>') : '';
                        const html = `<div class="message-bubble ${isMine?'mine':''}" data-msg-id="${msg.id}">${avatarHtml}<div class="message-content">${deleteHtml}${senderHtml}${msgText}${imgHtml}<span class="message-time">${time}</span></div></div>`;
                        btnLoadMore.insertAdjacentHTML('afterend', html);
                    });
                    chatContainer.scrollTop = chatContainer.scrollHeight - scrollBefore;
                    if (result.data.length < 20) btnLoadMore.remove();
                    else { btnLoadMore.textContent = 'Muat Pesan Sebelumnya'; btnLoadMore.disabled = false; }
                } else {
                    btnLoadMore.remove();
                }
            } catch(e) { btnLoadMore.textContent = 'Gagal memuat'; }
        });
    }

    // ============ MARK READ ============
    async function markRead(senderId) {
        const csrfHash = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        try {
            const resp = await fetch('/chat/read/' + senderId, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfHash }
            });
            const result = await resp.json();
            if(result.csrf_hash) document.querySelector('meta[name="csrf-token"]').setAttribute('content', result.csrf_hash);
        } catch(e) {}
    }

    btnSend.addEventListener('click', sendChat);
    input.addEventListener('keypress', (e) => { if(e.key === 'Enter') sendChat(); });
</script>
<?= $this->endSection() ?>
