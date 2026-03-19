<h1>Seguidos de <?= htmlspecialchars($user['nombre_usuario']) ?></h1>

<a href="/LASK/public/index.php/profile?id=<?= $user['id_usuario'] ?>">← Volver al perfil</a>

<?php if(empty($following)): ?>
    <p>Este usuario no sigue a nadie aún .</p>
<?php else: ?>
    <p><?= count($following) ?> seguido<?= count($following) != 1 ? 's' : '' ?></p>
    <?php foreach($following as $followed): ?>
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
            <img src="/LASK/<?= htmlspecialchars($followed['pfp']) ?>" width="48" height="48" style="border-radius:50%; object-fit:cover;">
            <div>
                <strong><?= htmlspecialchars($followed['nombre_usuario']) ?></strong><br>
                <a href="/LASK/public/index.php/profile?id=<?= $followed['id_usuario'] ?>">Ver perfil</a>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
