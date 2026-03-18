<h1>Seguidores de <?= htmlspecialchars($user['nombre_usuario']) ?></h1>

<a href="/LASK/public/index.php/profile?id=<?= $user['id_usuario'] ?>">← Volver al perfil</a>

<?php if(empty($followers)): ?>
    <p>Este usuario no tiene seguidores aún.</p>
<?php else: ?>
    <p><?= count($followers) ?> seguidor<?= count($followers) != 1 ? 'es' : '' ?></p>
    <?php foreach($followers as $follower): ?>
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
            <img src="/LASK/<?= htmlspecialchars($follower['pfp']) ?>" width="48" height="48" style="border-radius:50%; object-fit:cover;">
            <div>
                <strong><?= htmlspecialchars($follower['nombre_usuario']) ?></strong><br>
                <a href="/LASK/public/index.php/profile?id=<?= $follower['id_usuario'] ?>">Ver perfil</a>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
