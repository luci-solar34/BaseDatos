<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'LASK - Musica') ?></title>
    <link rel="stylesheet" href="<?= htmlspecialchars($appShellStylesheet) ?>">
    <?php foreach($layoutStylesheets ?? [] as $stylesheetTag): ?>
        <?= $stylesheetTag . PHP_EOL ?>
    <?php endforeach; ?>
</head>
<body class="app-shell-body">
    <header class="app-header">
        <div class="app-header-frame">
            <a href="<?= htmlspecialchars($navHomeUrl) ?>" class="app-logo-link" aria-label="Ir al inicio">
                <img src="<?= htmlspecialchars(rtrim(dirname(BASE_URL), '/\\') . '/img/logo.png') ?>" alt="LASK" class="app-logo-image">
            </a>

            <div class="app-header-actions">
                <a href="<?= htmlspecialchars($navMessagesUrl) ?>" class="app-header-button">Mensajes</a>
                <a href="<?= htmlspecialchars($navProfileUrl) ?>" class="app-profile-link" aria-label="Ir a mi perfil">
                    <img src="<?= htmlspecialchars($navProfileImageUrl) ?>" alt="Mi perfil" class="app-profile-image">
                </a>
            </div>
        </div>
    </header>

    <div class="app-shell-spacer"></div>

    <main class="app-main">
        <div class="app-main-inner">
            <?= $content ?>
        </div>
    </main>

    <footer class="app-footer">
        <div class="app-footer-card">
            <div class="app-footer-pill">Necesitas ayuda? Contactanos a:</div>

            <div class="app-footer-grid">
                <div class="app-footer-contacts">
                    <p>L +591 78406569</p>
                    <p>A +591 60354578</p>
                    <p>S +591 74457552</p>
                    <p>K +591 71331522</p>
                </div>

                <div class="app-footer-signature">UPB SCZ 2026- BDR</div>
            </div>
        </div>
    </footer>

    <script src="<?= htmlspecialchars($viewActionsScriptUrl) ?>"></script>
</body>
</html>
