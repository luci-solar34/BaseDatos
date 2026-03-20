<h1>Iniciar sesión</h1>

<?php if($flashMessage): ?>
    <div class="alert <?= $flashMessageType === 'success' ? 'alert-success' : 'alert-error' ?>">
        <?= htmlspecialchars($flashMessage) ?>
    </div>
<?php endif; ?>

<form action="<?= htmlspecialchars(BASE_URL . '/login') ?>" method="POST">

<label>Nombre de usuario</label>
<input type="text" name="username" required>

<label>Contraseña</label>
<input type="password" name="password" required>

<button type="submit">Iniciar sesión</button>

</form>

<a href="<?= htmlspecialchars(BASE_URL . '/register') ?>">Crear cuenta</a>