<?php
// Vista: chat.php
// Variables: $chatPartnerName, $chatRecipientId, $messages, $chatPartnerPfp
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Chat con <?= htmlspecialchars($chatPartnerName) ?> — LASK</title>
<link href="https://fonts.googleapis.com/css2?family=VT323&family=Exo+2:wght@400;500&display=swap" rel="stylesheet">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    font-family: 'Exo 2', sans-serif;
    font-size: 15px;
    background-image: url('/LASK/public/img/fondomensajes.png');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    background-color: #2a2a2a;
    height: 100vh;
    overflow: hidden;
    display: flex;
    flex-direction: column;
  }

  body::before {
    content: '';
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.60);
    z-index: 0;
  }

  /* ---- VENTANA ---- */
  .win-outer {
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    height: 100vh;
    border: 2px solid;
    border-color: #d4d0c8 #404040 #404040 #d4d0c8;
    background: #808080;
    box-shadow: 0 0 30px rgba(0,0,0,0.7);
  }

  /* ---- TITLE BAR ---- */
  .win-titlebar {
    background: linear-gradient(to right, #3a3a3a, #606060);
    padding: 4px 6px;
    display: flex;
    align-items: center;
    gap: 6px;
    user-select: none;
    border-bottom: 1px solid #404040;
    flex-shrink: 0;
  }
  .win-titlebar-icon { font-size: 14px; }
  .win-titlebar-text {
    flex: 1;
    color: #f0f0f0;
    font-family: 'VT323', monospace;
    font-size: 22px;
    letter-spacing: 1px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .win-btn {
    width: 20px; height: 18px;
    background: #909090;
    border: 1.5px solid;
    border-color: #d4d0c8 #404040 #404040 #d4d0c8;
    font-family: 'VT323', monospace;
    font-size: 14px;
    color: #111;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    text-decoration: none;
    flex-shrink: 0;
  }
  .win-btn:hover { background: #b0b0b0; }
  .win-btn:active { border-color: #404040 #d4d0c8 #d4d0c8 #404040; transform: translate(1px,1px); }

  /* ---- MENU BAR ---- */
  .win-menubar {
    background: #808080;
    border-bottom: 1px solid #606060;
    padding: 3px 6px;
    display: flex;
    gap: 2px;
    flex-shrink: 0;
  }
  .menu-item {
    font-size: 13px;
    padding: 2px 10px;
    color: #111;
    cursor: pointer;
    text-decoration: none;
    border: 1px solid transparent;
  }
  .menu-item:hover { background: #3a3a3a; color: #f0f0f0; border-color: #d4d0c8 #404040 #404040 #d4d0c8; }

  /* ---- CHAT HEADER ---- */
  .chat-header {
    background: #2a2a2a;
    border-bottom: 1px solid #404040;
    padding: 6px 12px;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
  }
  .header-avatar {
    width: 36px; height: 36px;
    border: 2px solid;
    border-color: #404040 #d4d0c8 #d4d0c8 #404040;
    background: #333;
    display: flex; align-items: center; justify-content: center;
    font-family: 'VT323', monospace;
    font-size: 22px;
    color: #c8c8c8;
    flex-shrink: 0;
    overflow: hidden;
  }
  .header-avatar img { width: 100%; height: 100%; object-fit: cover; }
  .header-info { flex: 1; }
  .header-name {
    font-family: 'VT323', monospace;
    font-size: 26px;
    color: #f0f0f0;
    line-height: 1;
  }

  /* ---- MESSAGES ---- */
  .messages {
    flex: 1;
    overflow-y: auto;
    padding: 12px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    background: #1a1a1a;
  }
  .messages::-webkit-scrollbar { width: 14px; }
  .messages::-webkit-scrollbar-track { background: #2a2a2a; border: 1px solid #404040; }
  .messages::-webkit-scrollbar-thumb {
    background: #555;
    border: 2px solid;
    border-color: #d4d0c8 #404040 #404040 #d4d0c8;
  }

  .empty-chat {
    font-family: 'VT323', monospace;
    font-size: 20px;
    color: #444;
    text-align: center;
    margin-top: 20px;
  }

  .date-divider {
    align-self: center;
    font-family: 'VT323', monospace;
    font-size: 16px;
    color: #444;
    border-top: 1px solid #2a2a2a;
    border-bottom: 1px solid #2a2a2a;
    padding: 1px 14px;
    margin: 4px 0;
  }

  .msg-row {
    display: flex;
    flex-direction: column;
    max-width: 75%;
    gap: 2px;
  }
  .msg-row.sent   { align-self: flex-end; align-items: flex-end; }
  .msg-row.received { align-self: flex-start; align-items: flex-start; }

  .msg-sender {
    font-family: 'VT323', monospace;
    font-size: 16px;
    padding: 0 6px;
    color: #666;
  }
  .msg-row.sent .msg-sender { color: #888; }

  .bubble {
    padding: 8px 12px;
    font-size: 15px;
    line-height: 1.5;
    word-break: break-word;
    max-width: 100%;
    border: 1px solid;
  }
  .bubble.received {
    background: #2a2a2a;
    border-color: #404040;
    color: #c8c8c8;
    border-top-left-radius: 0;
  }
  .bubble.sent {
    background: #3a3a3a;
    border-color: #606060;
    color: #f0f0f0;
    border-top-right-radius: 0;
  }

  .bubble-time {
    font-family: 'VT323', monospace;
    font-size: 14px;
    color: #444;
    padding: 0 6px;
  }

  /* ---- INPUT ---- */
  .input-panel {
    background: #2a2a2a;
    border-top: 2px solid #404040;
    padding: 8px 10px;
    display: flex;
    gap: 8px;
    align-items: center;
    flex-shrink: 0;
  }
  .input-label {
    font-family: 'VT323', monospace;
    font-size: 20px;
    color: #666;
    flex-shrink: 0;
  }
  .msg-input {
    flex: 1;
    border: 2px solid;
    border-color: #404040 #d4d0c8 #d4d0c8 #404040;
    background: #111;
    font-family: 'Exo 2', sans-serif;
    font-size: 14px;
    padding: 6px 10px;
    color: #c8c8c8;
    outline: none;
    caret-color: #aaa;
  }
  .msg-input:focus { border-color: #888 #d4d0c8 #d4d0c8 #888; }
  .msg-input::placeholder { color: #333; }

  .send-btn {
    background: #3a3a3a;
    border: 2px solid;
    border-color: #d4d0c8 #404040 #404040 #d4d0c8;
    font-family: 'VT323', monospace;
    font-size: 20px;
    padding: 5px 18px;
    cursor: pointer;
    color: #c8c8c8;
    white-space: nowrap;
    transition: all 0.1s;
  }
  .send-btn:hover { background: #555; color: #fff; }
  .send-btn:active { transform: translate(1px,1px); border-color: #404040 #d4d0c8 #d4d0c8 #404040; }

  /* ---- STATUS BAR ---- */
  .win-statusbar {
    background: #808080;
    border-top: 1px solid #606060;
    padding: 2px 8px;
    display: flex;
    gap: 6px;
    flex-shrink: 0;
  }
  .statusbar-cell {
    border: 1px solid;
    border-color: #404040 #d4d0c8 #d4d0c8 #404040;
    padding: 1px 8px;
    font-size: 13px;
    color: #111;
    background: #909090;
  }
</style>
</head>
<body>

<div class="win-outer">

  <!-- TITLE BAR -->
  <div class="win-titlebar">
    <span class="win-titlebar-icon">💬</span>
    <div class="win-titlebar-text">LASK Messenger — Chat con <?= htmlspecialchars($chatPartnerName) ?></div>
    <a href="<?= BASE_URL ?>/messages" class="win-btn">_</a>
    <span class="win-btn">□</span>
    <a href="<?= BASE_URL ?>/messages" class="win-btn">✕</a>
  </div>

  <!-- MENU BAR -->
  <div class="win-menubar">
    <a href="<?= BASE_URL ?>/messages" class="menu-item">← Volver a mensajes</a>
    <span class="menu-item">Ver</span>
    <span class="menu-item">Ayuda</span>
  </div>

  <!-- CHAT HEADER -->
  <div class="chat-header">
    <div class="header-avatar">
      <?php if (!empty($chatPartnerPfp)): ?>
        <img src="/LASK/<?= htmlspecialchars($chatPartnerPfp) ?>" alt="">
      <?php else: ?>
        <?= strtoupper(mb_substr($chatPartnerName, 0, 1)) ?>
      <?php endif; ?>
    </div>
    <div class="header-info">
      <div class="header-name"><?= htmlspecialchars($chatPartnerName) ?></div>
    </div>
  </div>

  <!-- MESSAGES -->
  <div class="messages" id="messages">
    <?php if (empty($messages)): ?>
      <div class="empty-chat">[ No hay mensajes. Envía el primero. ]</div>
    <?php else: ?>
      <?php
        $lastDate = null;
        foreach ($messages as $msg):
          $fecha = date('d/m/Y', strtotime($msg['fecha_mensaje']));
          $hora  = date('H:i', strtotime($msg['fecha_mensaje']));
          $esMio = ($msg['sender_label'] === 'Yo');
          if ($fecha !== $lastDate): $lastDate = $fecha;
      ?>
        <div class="date-divider">── <?= htmlspecialchars($fecha) ?> ──</div>
      <?php endif; ?>
        <div class="msg-row <?= $esMio ? 'sent' : 'received' ?>">
          <div class="msg-sender"><?= $esMio ? 'Yo' : htmlspecialchars($chatPartnerName) ?></div>
          <div class="bubble <?= $esMio ? 'sent' : 'received' ?>"><?= htmlspecialchars($msg['texto']) ?></div>
        </div>
      </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <!-- INPUT -->
  <div class="input-panel">
    <span class="input-label">►</span>
    <form method="POST" action="<?= htmlspecialchars(BASE_URL . '/message/send') ?>"
          style="display:flex;gap:8px;flex:1;align-items:center;">
      <input type="hidden" name="user_id" value="<?= (int)$chatRecipientId ?>">
      <input class="msg-input" type="text" name="texto" id="msgInput"
             placeholder="Escribe un mensaje..." autocomplete="off" required>
      <button type="submit" class="send-btn">► Enviar</button>
    </form>
  </div>

  <!-- STATUS BAR -->
  <div class="win-statusbar">
    <div class="statusbar-cell">LASK v1.0</div>
    <div class="statusbar-cell"><?= htmlspecialchars($chatPartnerName) ?></div>
    <div class="statusbar-cell" id="clock"></div>
  </div>

</div>

<script>
  document.getElementById('messages').scrollTop = 999999;
  function updateClock() {
    const now = new Date();
    document.getElementById('clock').textContent =
      String(now.getHours()).padStart(2,'0') + ':' +
      String(now.getMinutes()).padStart(2,'0') + ':' +
      String(now.getSeconds()).padStart(2,'0');
  }
  updateClock();
  setInterval(updateClock, 1000);
</script>

</body>
</html>