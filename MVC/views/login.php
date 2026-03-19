<h1>Iniciar sesión</h1>

<?php if(isset($_SESSION['flash_message'])): ?>
    <div class="alert <?= (isset($_SESSION['flash_message_type']) && $_SESSION['flash_message_type'] === 'success') ? 'alert-success' : 'alert-error' ?>">
        <?= htmlspecialchars($_SESSION['flash_message']) ?>
    </div>
    <?php unset($_SESSION['flash_message'], $_SESSION['flash_message_type']); ?>
<?php endif; ?>

<form action="/LASK/public/index.php/login" method="POST">

<label>Nombre de usuario</label>
<input type="text" name="username" required>

<label>Contraseña</label>
<input type="password" name="password" required>

<button type="submit">Iniciar sesión</button>

</form>

<a href="/LASK/public/index.php/register">Crear cuenta</a>