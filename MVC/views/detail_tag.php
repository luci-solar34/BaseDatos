<?php

$pageTitle = $tag['nombre_tag'] . ' - LASK';
?>
<link rel="stylesheet" href="/LASK/public/css/tags.css?v=<?= time() ?>">

<div class="tag-page">

    <h1 class="tag-title">#<?= htmlspecialchars($tag['nombre_tag']) ?></h1>

    <p class="tag-description">
        <?php if(!empty($tag['descripcion_tag'])): ?>
            <?= nl2br(htmlspecialchars($tag['descripcion_tag'])) ?>
        <?php else: ?>
            Este tag todavía no tiene descripción.
        <?php endif; ?>
    </p>

    <p class="explore-tags"><a href="<?= htmlspecialchars(BASE_URL . '/tags') ?>">Explorar más tags</a></p>

    <h2 class="songs-title">Canciones con esta vibe</h2>

    <?php if(empty($songs)): ?>
        <p class="no-songs">Todavía no hay canciones asociadas a este tag.</p>
    <?php else: ?>
        <div class="songs-grid">
            <?php foreach($songs as $song): ?>
                <a class="media-card" href="<?= htmlspecialchars(BASE_URL . '/song?id=' . (int)$song['id_cancion']) ?>">
                    <img src="/LASK/<?= htmlspecialchars($song['portada_cancion'] ?? 'Photos/banner_default.png') ?>" alt="">
                    <span><?= htmlspecialchars($song['nombre_cancion']) ?></span>
                    <small><?= htmlspecialchars($song['nombre_artistico']) ?></small>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <a class="back-home btn" href="<?= htmlspecialchars(BASE_URL) ?>">← Volver al home</a>

</div>