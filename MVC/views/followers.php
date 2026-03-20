<h1>Seguidores de <?= htmlspecialchars($user['nombre_usuario']) ?></h1>

<a href="<?= htmlspecialchars($profileUrl) ?>">← Volver al perfil</a>

<?php if(empty($followers)): ?>
    <p>Este usuario no tiene seguidores aún.</p>
<?php else: ?>
    <p><?= (int)$followersCount ?> <?= htmlspecialchars($followersLabel) ?></p>
    <?php foreach($followers as $follower): ?>
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
            <img src="/LASK/<?= htmlspecialchars($follower['pfp']) ?>" width="48" height="48" style="border-radius:50%; object-fit:cover;">
            <div>
                <strong><?= htmlspecialchars($follower['nombre_usuario']) ?></strong><br>
                <a href="<?= BASE_URL ?>/profile?id=<?= (int)$follower['id_usuario'] ?>">Ver perfil</a>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
