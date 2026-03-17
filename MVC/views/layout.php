<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'LASK - Música' ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            color: #333;
        }

        .navbar {
            background: #1DB954;
            padding: 15px 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-right: 20px;
            font-weight: bold;
        }

        .navbar a:hover {
            text-decoration: underline;
        }

        .navbar .user-info {
            float: right;
            color: white;
        }

        .main-content {
            min-height: calc(100vh - 60px);
            padding: 20px;
        }

        .footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }

        .btn {
            display: inline-block;
            padding: 8px 16px;
            background: #1DB954;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            background: #169c46;
        }

        .btn-secondary {
            background: #666;
        }

        .btn-secondary:hover {
            background: #555;
        }

        .alert {
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="/LASK/public">Inicio</a>
        <a href="/LASK/public/index.php/new-releases">Nuevos Lanzamientos</a>
        
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="/LASK/public/index.php/profile?id=<?= $_SESSION['user_id'] ?>">Mi Perfil</a>
            <a href="/LASK/public/index.php/messages">Mensajes</a>
            <?php if($_SESSION['role'] == 1): ?>
                <a href="/LASK/public/index.php/admin/denuncias">Admin</a>
            <?php endif; ?>
            <div class="user-info">
                <span>👤 <?= $_SESSION['username'] ?></span>
                <a href="/LASK/public/index.php/logout" style="margin-left: 15px;">Cerrar sesión</a>
            </div>
        <?php else: ?>
            <div class="user-info">
                <a href="/LASK/public/index.php/login">Iniciar sesión</a>
                <a href="/LASK/public/index.php/register" style="margin-left: 15px;">Registrarse</a>
            </div>
        <?php endif; ?>
    </nav>

    <div class="main-content">
        <?= $content ?>
    </div>

    <footer class="footer">
        <p>&copy; 2024 LASK - Tu plataforma de música</p>
    </footer>
</body>
</html>