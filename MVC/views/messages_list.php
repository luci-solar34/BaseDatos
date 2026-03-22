<?php $pageTitle = 'Mensajes - LASK'; ?>
<link rel="stylesheet" href="<?= htmlspecialchars(rtrim(dirname(BASE_URL), '/\\') . '/css/messages.css') ?>">

<div class="messages-page">
  <div class="win-container">
    <div class="win-outer">

      <div class="win-titlebar">
        <span class="win-titlebar-icon">MAIL</span>
        <div class="win-titlebar-text">LASK Messenger - Bandeja de entrada</div>
        <a href="<?= BASE_URL ?>/" class="win-btn">_</a>
        <span class="win-btn">[]</span>
        <a href="<?= BASE_URL ?>/" class="win-btn">X</a>
      </div>

      <div class="win-menubar">
        <a href="<?= BASE_URL ?>/" class="menu-item"><- Volver al inicio</a>
      </div>

      <div class="win-body">
        <div class="win-section">
          <div class="win-section-title">Conversaciones</div>
          <div class="win-section-body">
            <?php if (empty($conversations)): ?>
              <div class="empty-msg">[ No tienes conversaciones aun ]</div>
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
                  <div class="conv-arrow">></div>
                </a>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>

        <?php if (!empty($mutuals)): ?>
        <div class="win-section">
          <div class="win-section-title">Recomendados</div>
          <div class="win-section-body">
            <?php foreach ($mutuals as $m): ?>
              <div class="mutual-item">
                <div class="conv-avatar">
                  <?= strtoupper(mb_substr($m['username'] ?? $m['nombre_usuario'] ?? '?', 0, 1)) ?>
                </div>
                <div class="mutual-info">
                  <div class="mutual-name"><?= htmlspecialchars($m['username'] ?? $m['nombre_usuario'] ?? '') ?></div>
                </div>
                <a class="start-btn" href="<?= BASE_URL ?>/chat?user=<?= (int)$m['id_usuario'] ?>">> Iniciar conversacion</a>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
      </div>

      <div class="win-statusbar">
        <div class="statusbar-cell">LASK v1.0</div>
        <div class="statusbar-cell"><?= count($conversations ?? []) ?> conversacion(es)</div>
      </div>

    </div>
  </div>
</div>
