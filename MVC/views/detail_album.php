<?php
$pageTitle = $album['nombre_album'] . ' - LASK';
?>

<link rel="stylesheet" href="<?= htmlspecialchars(rtrim(dirname(BASE_URL), '/\\') . '/css/detail_album.css') ?>">

<h1><?= htmlspecialchars($album['nombre_album']) ?></h1>

<?php if(!empty($album['nombre_artistico'])): ?>
    <p>
        <strong>Artista:</strong>
        <a href="<?= htmlspecialchars($albumArtistUrl) ?>">
            <?= htmlspecialchars($album['nombre_artistico']) ?>
        </a>
    </p>
<?php endif; ?>

<?php if(!empty($album['descripcion_album'])): ?>
    <p><strong>Descripción:</strong> <?= htmlspecialchars($album['descripcion_album']) ?></p>
<?php endif; ?>

<p><strong>Fecha de lanzamiento:</strong> <?= htmlspecialchars($album['fecha_lanzamiento']) ?></p>

<?php if($canEditAlbum): ?>
    <a href="<?= htmlspecialchars(BASE_URL . '/artist/edit-album?id=' . (int)$album['id_album']) ?>" class="btn btn-edit">
        Editar Álbum
    </a>
<?php endif; ?>

<div id="albumPageData" data-album-queue="<?= htmlspecialchars($albumQueueJson, ENT_QUOTES, 'UTF-8') ?>"></div>

<!-- Panel de reproducción (oculto hasta que se da play) -->
<div id="nowPlaying" style="display:none; margin:16px 0; border:1px solid #ddd; border-radius:10px; padding:14px; max-width:520px;">
    <p style="margin:0 0 6px;"><strong>▶ Reproduciendo: <span id="nowPlayingTitle"></span></strong></p>
    <img id="nowPlayingCover" src="/LASK/<?= htmlspecialchars($albumCover) ?>" alt="Portada del álbum actual" width="220" style="display:block; margin:0 0 10px; border-radius:8px; object-fit:cover;">
    <audio id="albumAudio" controls style="width:100%; margin-bottom:8px;"></audio>
    <form id="nowPlayingLikeForm" action="<?= htmlspecialchars(BASE_URL . '/like') ?>" method="POST" style="margin:0 0 12px; display:none;">
        <input type="hidden" name="song_id" id="nowPlayingSongId" value="">
        <input type="hidden" name="album_id" value="<?= (int)$album['id_album'] ?>">
        <button type="submit" id="nowPlayingLikeButton">♥ Dar like</button>
    </form>
    <div style="display:flex; gap:10px; margin-bottom:14px;">
        <button type="button" data-album-action="prev">⏮ Anterior</button>
        <button type="button" data-album-action="next">Siguiente ⏭</button>
    </div>
    <div id="lyricsSection">
        <h4 style="margin:0 0 4px;">Letra</h4>
        <pre id="lyricText" style="white-space:pre-wrap; background:#f8f8f8; padding:8px; border-radius:6px; max-height:200px; overflow-y:auto;"></pre>
        <h4 style="margin:8px 0 4px;">Texto fonético</h4>
        <pre id="lyricPhonetic" style="white-space:pre-wrap; background:#f8f8f8; padding:8px; border-radius:6px; max-height:200px; overflow-y:auto;"></pre>
    </div>
</div>

<h2>Canciones</h2>

<?php if(empty($songs)): ?>
    <p>Este álbum no tiene canciones</p>
<?php else: ?>
    <ul>
    <?php foreach($songs as $song): ?>
        <li>
            <a href="<?= htmlspecialchars(BASE_URL . '/song?id=' . (int)$song['id_cancion']) ?>">
                <?= (int)$song['numero_pista'] ?>. <?= htmlspecialchars($song['nombre_cancion']) ?>
            </a>
            <?php if($song['can_play']): ?>
                <button type="button" data-album-play-index="<?= (int)$song['playable_index'] ?>">▶ Reproducir</button>
            <?php endif; ?>
            <span style="margin-left:8px;" data-song-like-count="<?= (int)$song['id_cancion'] ?>">♥ <?= (int)$song['likes_total'] ?></span>
            <?php if($song['can_edit']): ?>
                <a href="<?= htmlspecialchars(BASE_URL . '/artist/edit-song?id=' . (int)$song['id_cancion']) ?>">Editar</a>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>

<br>
<a href="<?= htmlspecialchars(BASE_URL) ?>">← Volver al inicio</a>

<script src="<?= htmlspecialchars(BASE_URL . '/../js/detail_album.js') ?>"></script>