<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>

    <!-- FUENTE PIXEL -->
    <link href="https://fonts.googleapis.com/css2?family=VT323&display=swap" rel="stylesheet">

    <!-- Mensaje flash de PHP (de la rama de tu compañera) -->
    <?php if(isset($_SESSION['flash_message'])): ?>
        <div class="alert <?= (isset($_SESSION['flash_message_type']) && $_SESSION['flash_message_type'] === 'success') ? 'alert-success' : 'alert-error' ?>">
            <?= htmlspecialchars($_SESSION['flash_message']) ?>
        </div>
        <?php unset($_SESSION['flash_message'], $_SESSION['flash_message_type']); ?>
    <?php endif; ?>

    <!-- CSS CORRECTOS -->
    <link rel="stylesheet" href="/LASK/public/css/styles.css">
    
</head>

<body>

<!-- Navbar -->
<div class="navbar">
    <div class="logo">
        <img src="/LASK/public/img/logo.png" alt="Logo">
    </div>

    <div class="nav-buttons">
        <a href="/LASK/public/index.php/register" class="btn">Regístrate</a>
    </div>
</div>

<!-- Login -->
<div class="contenedor">
    <div class="login-box">

        <h1 class="titulo-pixel">Iniciar sesión</h1>

        <!-- Formulario combinado -->
        <form action="/LASK/public/index.php/login" method="POST">

            <label>Nombre de usuario</label>
            <input type="text" name="username" required>

            <label>Contraseña</label>
            <input type="password" name="password" required>

            <p>¿No tienes una cuenta? 
                <a href="/LASK/public/index.php/register" class="registro-link">Regístrate.</a>
            </p>

            <button type="submit">Iniciar sesión</button>

        </form>

    </div>
</div>

</body>
</html>