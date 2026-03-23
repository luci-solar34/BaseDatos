<?php
$pageTitle = 'Seguidos de ' . $user['nombre_usuario'] . ' — LASK';
?>
<link rel="stylesheet" href="/LASK/public/css/followers.css">
 
<div class="win-container">
  <div class="win-outer">
 
    <!-- TITLE BAR -->
    <div class="win-titlebar">
      <span class="win-titlebar-icon">USR</span>
      <div class="win-titlebar-text">LASK — Seguidos de <?= htmlspecialchars($user['nombre_usuario']) ?></div>
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
          Seguidos
          <?php if(!empty($following)): ?>
            <span class="section-count">(<?= (int)$followingCount ?> <?= htmlspecialchars($followingLabel) ?>)</span>
          <?php endif; ?>
        </div>
 
        <div class="win-section-body">
 
          <?php if(empty($following)): ?>
            <div class="empty-msg">[ Este usuario no sigue a nadie aún ]</div>
          <?php else: ?>
            <?php foreach($following as $followed): ?>
              <div class="follower-item">
 
                <!-- AVATAR -->
                <div class="follower-avatar">
                  <img src="/LASK/<?= htmlspecialchars($followed['pfp']) ?>" alt="<?= htmlspecialchars($followed['nombre_usuario']) ?>">
                </div>
 
                <!-- INFO -->
                <div class="follower-info">
                  <div class="follower-name"><?= htmlspecialchars($followed['nombre_usuario']) ?></div>
                  <div class="follower-sub">Usuario de LASK</div>
                </div>
 
                <!-- BOTON -->
                <a class="profile-btn" href="<?= BASE_URL ?>/profile?id=<?= (int)$followed['id_usuario'] ?>">► Ver perfil</a>
 
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
 
        </div>
      </div>
 
    </div>
 
    <!-- STATUS BAR -->
    <div class="win-statusbar">
      <div class="statusbar-cell">LASK v1.0</div>
      <div class="statusbar-cell"><?= (int)$followingCount ?> seguido(s)</div>
    </div>
 
  </div>
</div>