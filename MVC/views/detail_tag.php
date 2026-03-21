<?php

$pageTitle = $tag['nombre_tag'] . ' - LASK';
?>
<link rel="stylesheet" href="/LASK/public/css/tags.css">
<link href="https://fonts.googleapis.com/css2?family=VT323&display=swap" rel="stylesheet">

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
        <ul class="songs-list">
            <?php foreach($songs as $song): ?>
                <li class="song-item">
                    <a class="song-link" href="<?= htmlspecialchars(BASE_URL . '/song?id=' . (int)$song['id_cancion']) ?>">
                        <?= htmlspecialchars($song['nombre_cancion']) ?>
                    </a>
                    por
                    <a class="artist-link" href="<?= htmlspecialchars(BASE_URL . '/artist?id=' . (int)$song['id_artista']) ?>">
                        <?= htmlspecialchars($song['nombre_artistico']) ?>
                    </a>
                    <?php if(!empty($song['nombre_album'])): ?>
                        (álbum: <a class="album-link" href="<?= htmlspecialchars(BASE_URL . '/album?id=' . (int)$song['id_album']) ?>">
                            <?= htmlspecialchars($song['nombre_album']) ?>
                        </a>)
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <a class="back-home" href="<?= htmlspecialchars(BASE_URL) ?>">Volver al home</a>

</div>