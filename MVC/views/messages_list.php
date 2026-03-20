<?php
// Vista: messages_list.php
// Variables: $conversations, $mutuals
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mensajes — LASK</title>
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
    /* fallback si no carga la imagen */
    background-color: #2a2a2a;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 16px;
  }

  body::before {
    content: '';
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.60);
    z-index: 0;
  }

  /* ---- VENTANA ---- */
  .win-container {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 900px;
    min-height: 75vh;
  }

  .win-outer {
    border: 2px solid;
    border-color: #d4d0c8 #404040 #404040 #d4d0c8;
    background: #808080;
    box-shadow: 4px 4px 0 #000, inset 0 0 0 1px #909090;
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
  }
  .menu-item {
    font-size: 13px;
    padding: 2px 10px;
    color: #111;
    cursor: pointer;
    text-decoration: none;
    border: 1px solid transparent;
  }
  .menu-item:hover {
    background: #3a3a3a;
    color: #f0f0f0;
    border-color: #d4d0c8 #404040 #404040 #d4d0c8;
  }

  /* ---- BODY ---- */
  .win-body {
    padding: 8px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    background: #808080;
  }

  /* ---- SECTION ---- */
  .win-section {
    border: 2px solid;
    border-color: #404040 #d4d0c8 #d4d0c8 #404040;
  }

  .win-section-title {
    background: linear-gradient(to right, #3a3a3a, #555555);
    color: #f0f0f0;
    font-family: 'VT323', monospace;
    font-size: 24px;
    padding: 3px 10px;
    letter-spacing: 1px;
    border-bottom: 1px solid #404040;
  }

  .win-section-body {
    padding: 8px;
    display: flex;
    flex-direction: column;
    gap: 5px;
    background: #2a2a2a;
  }

  .empty-msg {
    font-family: 'VT323', monospace;
    font-size: 20px;
    color: #666;
    padding: 8px 4px;
  }

  /* ---- CONVERSATION ITEM ---- */
  .conv-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 10px;
    border: 1px solid #3a3a3a;
    background: #1e1e1e;
    text-decoration: none;
    color: #c8c8c8;
    transition: all 0.1s;
  }
  .conv-item:hover {
    background: #3a3a3a;
    border-color: #d4d0c8;
    color: #ffffff;
  }
  .conv-item:hover .conv-preview { color: #aaa; }
  .conv-item:hover .conv-arrow { color: #fff; }

  .conv-avatar {
    width: 38px; height: 38px;
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
  .conv-avatar img { width: 100%; height: 100%; object-fit: cover; }

  .conv-info { flex: 1; min-width: 0; }
  .conv-name {
    font-family: 'VT323', monospace;
    font-size: 22px;
    line-height: 1.1;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
  }
  .conv-preview {
    font-size: 12px;
    color: #666;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    margin-top: 1px;
  }
  .conv-arrow {
    font-family: 'VT323', monospace;
    font-size: 20px;
    color: #555;
  }

  /* ---- MUTUAL ITEM ---- */
  .mutual-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 10px;
    border: 1px solid #3a3a3a;
    background: #1e1e1e;
  }
  .mutual-info { flex: 1; min-width: 0; }
  .mutual-name {
    font-family: 'VT323', monospace;
    font-size: 22px;
    color: #c8c8c8;
    line-height: 1.1;
  }
  .mutual-sub { font-size: 12px; color: #555; margin-top: 1px; }

  .start-btn {
    background: #333;
    border: 2px solid;
    border-color: #d4d0c8 #404040 #404040 #d4d0c8;
    font-family: 'VT323', monospace;
    font-size: 18px;
    padding: 3px 14px;
    cursor: pointer;
    color: #c8c8c8;
    text-decoration: none;
    white-space: nowrap;
    display: inline-block;
    transition: all 0.1s;
  }
  .start-btn:hover {
    background: #555;
    color: #fff;
    border-color: #f0f0f0 #606060 #606060 #f0f0f0;
  }
  .start-btn:active { transform: translate(1px,1px); border-color: #404040 #d4d0c8 #d4d0c8 #404040; }

  /* ---- STATUS BAR ---- */
  .win-statusbar {
    background: #808080;
    border-top: 1px solid #606060;
    padding: 3px 8px;
    display: flex;
    gap: 6px;
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

<div class="win-container">
  <div class="win-outer">

    <!-- TITLE BAR -->
    <div class="win-titlebar">
      <span class="win-titlebar-icon">✉</span>
      <div class="win-titlebar-text">LASK Messenger — Bandeja de entrada</div>
      <a href="<?= BASE_URL ?>/" class="win-btn">_</a>
      <span class="win-btn">□</span>
      <a href="<?= BASE_URL ?>/" class="win-btn">✕</a>
    </div>

    <!-- MENU BAR -->
    <div class="win-menubar">
      <a href="<?= BASE_URL ?>/" class="menu-item">← Volver al inicio</a>
      <span class="menu-item">Ver</span>
      <span class="menu-item">Ayuda</span>
    </div>

    <!-- BODY -->
    <div class="win-body">

      <!-- CONVERSACIONES -->
      <div class="win-section">
        <div class="win-section-title">📁 Conversaciones</div>
        <div class="win-section-body">
          <?php if (empty($conversations)): ?>
            <div class="empty-msg">[ No tienes conversaciones aún ]</div>
          <?php else: ?>
            <?php foreach ($conversations as $conv): ?>
              <a class="conv-item" href="<?= BASE_URL ?>/chat?user=<?= (int)$conv['id_usuario'] ?>">
                <div class="conv-avatar">
                  <?php if (!empty($conv['pfp'])): ?>
                    <img src="/LASK/<?= htmlspecialchars($conv['pfp']) ?>" alt="">
                  <?php else: ?>
                    <?= strtoupper(mb_substr($conv['username'] ?? $conv['nombre_usuario'] ?? '?', 0, 1)) ?>
                  <?php endif; ?>
                </div>
                <div class="conv-info">
                  <div class="conv-name"><?= htmlspecialchars($conv['username'] ?? $conv['nombre_usuario'] ?? '') ?></div>
                  <div class="conv-preview"><?= htmlspecialchars($conv['ultimo_mensaje'] ?? 'Sin mensajes') ?></div>
                </div>
                <div class="conv-arrow">►</div>
              </a>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

      <!-- RECOMENDADOS -->
      <?php if (!empty($mutuals)): ?>
      <div class="win-section">
        <div class="win-section-title">👥 Recomendados</div>
        <div class="win-section-body">
          <?php foreach ($mutuals as $m): ?>
            <div class="mutual-item">
              <div class="conv-avatar">
                <?= strtoupper(mb_substr($m['username'] ?? $m['nombre_usuario'] ?? '?', 0, 1)) ?>
              </div>
              <div class="mutual-info">
                <div class="mutual-name"><?= htmlspecialchars($m['username'] ?? $m['nombre_usuario'] ?? '') ?></div>
              </div>
              <a class="start-btn" href="<?= BASE_URL ?>/chat?user=<?= (int)$m['id_usuario'] ?>">► Iniciar conversación</a>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

    </div>

    <!-- STATUS BAR -->
    <div class="win-statusbar">
      <div class="statusbar-cell">LASK v1.0</div>
      <div class="statusbar-cell"><?= count($conversations ?? []) ?> conversación(es)</div>
    </div>

  </div>
</div>



</body>
</html>