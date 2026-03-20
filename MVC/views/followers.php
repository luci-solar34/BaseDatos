<?php
$pageTitle = 'Seguidores de ' . $user['nombre_usuario'] . ' — LASK';
?>
<link rel="stylesheet" href="/LASK/public/css/followers.css">
 
<div class="win-container">
  <div class="win-outer">
 
    <!-- TITLE BAR -->
    <div class="win-titlebar">
      <span class="win-titlebar-icon">👥</span>
      <div class="win-titlebar-text">LASK — Seguidores de <?= htmlspecialchars($user['nombre_usuario']) ?></div>
      <a href="<?= htmlspecialchars($profileUrl) ?>" class="win-btn">_</a>
      <span class="win-btn">□</span>
      <a href="<?= htmlspecialchars($profileUrl) ?>" class="win-btn">✕</a>
    </div>
 
    <!-- MENU BAR -->
    <div class="win-menubar">
      <a href="<?= htmlspecialchars($profileUrl) ?>" class="menu-item">← Volver al perfil</a>
    </div>
 
    <!-- BODY -->
    <div class="win-body">
 
      <div class="win-section">
        <div class="win-section-title">
          👥 Seguidores
          <?php if(!empty($followers)): ?>
            <span class="section-count">(<?= (int)$followersCount ?> <?= htmlspecialchars($followersLabel) ?>)</span>
          <?php endif; ?>
        </div>
 
        <div class="win-section-body">
 
          <?php if(empty($followers)): ?>
            <div class="empty-msg">[ Este usuario no tiene seguidores aún ]</div>
          <?php else: ?>
            <?php foreach($followers as $follower): ?>
              <div class="follower-item">
 
                <!-- AVATAR -->
                <div class="follower-avatar">
                  <img src="/LASK/<?= htmlspecialchars($follower['pfp']) ?>" alt="<?= htmlspecialchars($follower['nombre_usuario']) ?>">
                </div>
 
                <!-- INFO -->
                <div class="follower-info">
                  <div class="follower-name"><?= htmlspecialchars($follower['nombre_usuario']) ?></div>
                  <div class="follower-sub">Usuario de LASK</div>
                </div>
 
                <!-- BOTON -->
                <a class="profile-btn" href="<?= BASE_URL ?>/profile?id=<?= (int)$follower['id_usuario'] ?>">► Ver perfil</a>
 
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
 
        </div>
      </div>
 
    </div>
 
    <!-- STATUS BAR -->
    <div class="win-statusbar">
      <div class="statusbar-cell">LASK v1.0</div>
      <div class="statusbar-cell"><?= (int)$followersCount ?> seguidor(es)</div>
    </div>
 
  </div>
</div>