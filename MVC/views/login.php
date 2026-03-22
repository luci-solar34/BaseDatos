
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>


    <!-- FUENTE PIXEL -->
    <link href="https://fonts.googleapis.com/css2?family=VT323&display=swap" rel="stylesheet">


    <!-- CSS CORRECTOS -->
    <link rel="stylesheet" href="/LASK/public/css/styles.css?v=login-register-fix-1">
   
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

