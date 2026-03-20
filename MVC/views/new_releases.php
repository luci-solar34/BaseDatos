<?php
$pageTitle = 'Nuevos Lanzamientos - LASK';
?>

<link rel="stylesheet" href="<?= htmlspecialchars(rtrim(dirname(BASE_URL), '/\\') . '/css/new_releases.css') ?>">

<div class="releases-page">
    <header class="releases-header">
        <h1>Todos los lanzamientos</h1>
        <p>Lo mas reciente de canciones y albumes en LASK.</p>
    </header>

    <section class="releases-section">
        <div class="section-head">
            <h2>Canciones</h2>
        </div>

        <?php if(empty($songs)): ?>
            <p class="empty-state">No hay nuevas canciones</p>
        <?php else: ?>
            <ul class="release-grid" aria-label="Lista de canciones recientes">
                <?php foreach($songs as $song): ?>
                    <?php
                        $songCoverRaw = !empty($song['portada_cancion']) ? $song['portada_cancion'] : 'Photos/banner_default.png';
                        $songCover = '/LASK/' . ltrim($songCoverRaw, '/');
                    ?>
                    <li class="release-item">
                        <a class="release-card" href="<?= BASE_URL ?>/song?id=<?= (int)$song['id_cancion'] ?>">
                            <span class="release-cover-wrap">
                                <img class="release-cover" src="<?= htmlspecialchars($songCover) ?>" alt="Portada de <?= htmlspecialchars($song['nombre_cancion']) ?>">
                            </span>
                            <span class="release-meta">
                                <span class="release-title"><?= htmlspecialchars($song['nombre_cancion']) ?></span>
                                <span class="release-subtitle"><?= htmlspecialchars($song['nombre_artistico']) ?></span>
                                <?php if(!empty($song['nombre_album'])): ?>
                                    <span class="release-extra">Album: <?= htmlspecialchars($song['nombre_album']) ?></span>
                                <?php else: ?>
                                    <span class="release-extra">Single</span>
                                <?php endif; ?>
                            </span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>

    <section class="releases-section">
        <div class="section-head">
            <h2>Albumes</h2>
        </div>

        <?php if(empty($albums)): ?>
            <p class="empty-state">No hay nuevos albumes</p>
        <?php else: ?>
            <ul class="release-grid" aria-label="Lista de albumes recientes">
                <?php foreach($albums as $album): ?>
                    <?php
                        $albumCoverRaw = !empty($album['portada_album']) ? $album['portada_album'] : 'Photos/banner_default.png';
                        $albumCover = '/LASK/' . ltrim($albumCoverRaw, '/');
                    ?>
                    <li class="release-item">
                        <a class="release-card" href="<?= BASE_URL ?>/album?id=<?= (int)$album['id_album'] ?>">
                            <span class="release-cover-wrap">
                                <img class="release-cover" src="<?= htmlspecialchars($albumCover) ?>" alt="Portada de <?= htmlspecialchars($album['nombre_album']) ?>">
                            </span>
                            <span class="release-meta">
                                <span class="release-title"><?= htmlspecialchars($album['nombre_album']) ?></span>
                                <span class="release-subtitle"><?= htmlspecialchars($album['nombre_artistico'] ?? 'Artista desconocido') ?></span>
                                <span class="release-extra"><?= htmlspecialchars($album['fecha_lanzamiento']) ?></span>
                            </span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>

    <a class="back-home" href="/LASK/public">Volver al inicio</a>
</div>