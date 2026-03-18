<h1><?= htmlspecialchars($user['nombre_usuario']) ?></h1>

<p style="color:red;">Cuenta privada</p>

<?php if(isset($canUnblock) && $canUnblock): ?>

    <form method="POST" action="/LASK/public/index.php/unblock">
        <input type="hidden" name="user_id" value="<?= $user['id_usuario'] ?>">
        <button>Desbloquear</button>
    </form>

<?php endif; ?>