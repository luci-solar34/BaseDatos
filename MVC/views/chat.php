<?php $pageTitle = 'Chat con ' . $chatPartnerName . ' - LASK'; ?>
<link rel="stylesheet" href="<?= htmlspecialchars(rtrim(dirname(BASE_URL), '/\\') . '/css/chat.css') ?>">

<div class="chat-page">
  <div class="win-outer">

    <div class="win-titlebar">
      <span class="win-titlebar-icon">CHAT</span>
      <div class="win-titlebar-text">LASK Messenger - Chat con <?= htmlspecialchars($chatPartnerName) ?></div>
      <a href="<?= BASE_URL ?>/messages" class="win-btn">_</a>
      <span class="win-btn">[]</span>
      <a href="<?= BASE_URL ?>/messages" class="win-btn">X</a>
    </div>

    <div class="win-menubar">
      <a href="<?= BASE_URL ?>/messages" class="menu-item"><- Volver a mensajes</a>
      <span class="menu-item">Ver</span>
      <span class="menu-item">Ayuda</span>
    </div>

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

    <div class="messages" id="messages">
      <?php if (empty($messages)): ?>
        <div class="empty-chat">[ No hay mensajes. Envia el primero. ]</div>
      <?php else: ?>
        <?php
          $lastDate = null;
          foreach ($messages as $msg):
            $fecha = date('d/m/Y', strtotime($msg['fecha_mensaje']));
            $esMio = ($msg['sender_label'] === 'Yo');
            if ($fecha !== $lastDate):
              $lastDate = $fecha;
        ?>
          <div class="date-divider">-- <?= htmlspecialchars($fecha) ?> --</div>
        <?php endif; ?>
          <div class="msg-row <?= $esMio ? 'sent' : 'received' ?>">
            <div class="msg-sender"><?= $esMio ? 'Yo' : htmlspecialchars($chatPartnerName) ?></div>
            <div class="bubble <?= $esMio ? 'sent' : 'received' ?>"><?= htmlspecialchars($msg['texto']) ?></div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <div class="input-panel">
      <span class="input-label">></span>
      <form method="POST" action="<?= htmlspecialchars(BASE_URL . '/message/send') ?>" class="message-form">
        <?= $csrfField ?>
        <input type="hidden" name="user_id" value="<?= (int)$chatRecipientId ?>">
        <input class="msg-input" type="text" name="texto" id="msgInput" placeholder="Escribe un mensaje..." autocomplete="off" required>
        <button type="submit" class="send-btn">> Enviar</button>
      </form>
    </div>

    <div class="win-statusbar">
      <div class="statusbar-cell">LASK v1.0</div>
      <div class="statusbar-cell"><?= htmlspecialchars($chatPartnerName) ?></div>
      <div class="statusbar-cell" id="clock"></div>
    </div>

  </div>
</div>

<script>
  document.getElementById('messages').scrollTop = 999999;
  function updateClock() {
    const now = new Date();
    document.getElementById('clock').textContent =
      String(now.getHours()).padStart(2, '0') + ':' +
      String(now.getMinutes()).padStart(2, '0') + ':' +
      String(now.getSeconds()).padStart(2, '0');
  }
  updateClock();
  setInterval(updateClock, 1000);
</script>
