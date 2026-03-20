<h1>Seguidos de <?= htmlspecialchars($user['nombre_usuario']) ?></h1>

<a href="<?= htmlspecialchars($profileUrl) ?>">← Volver al perfil</a>

<?php if(empty($following)): ?>
    <p>Este usuario no sigue a nadie aún .</p>
<?php else: ?>
    <p><?= (int)$followingCount ?> <?= htmlspecialchars($followingLabel) ?></p>
    <?php foreach($following as $followed): ?>
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
            <img src="/LASK/<?= htmlspecialchars($followed['pfp']) ?>" width="48" height="48" style="border-radius:50%; object-fit:cover;">
            <div>
                <strong><?= htmlspecialchars($followed['nombre_usuario']) ?></strong><br>
                <a href="<?= BASE_URL ?>/profile?id=<?= (int)$followed['id_usuario'] ?>">Ver perfil</a>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
