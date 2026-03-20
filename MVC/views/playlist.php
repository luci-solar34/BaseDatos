<h1>Mi Playlist</h1>

<div id="nowPlaying" style="display:none; margin: 12px 0 20px; border:1px solid #ddd; border-radius:10px; padding:12px; max-width:420px;">
    <p style="margin:0 0 8px;"><strong>Reproduciendo ahora</strong></p>
    <img id="nowPlayingCover" src="/LASK/Photos/banner_default.png" alt="Portada actual" width="220" style="border-radius:8px; display:block; margin-bottom:8px; object-fit:cover;">
    <div id="nowPlayingTitle" style="font-weight:bold;"></div>
</div>

<a href="<?= BASE_URL ?>/playlist/add-song?playlist=<?= (int)$playlist_id ?>">
    <button>Agregar canciones</button>
</a>

<a href="<?= BASE_URL ?>/playlist/edit?id=<?= (int)$playlist_id ?>">
    <button>Editar Playlist</button>
</a>

<h2>Canciones</h2>

<?php foreach($songs as $song): ?>

<div>
    <?= htmlspecialchars($song['nombre_cancion']) ?>
    <audio controls class="js-song-player"
           data-title="<?= htmlspecialchars($song['nombre_cancion']) ?>"
           data-cover="<?= htmlspecialchars($song['portada_cancion'] ?? 'Photos/banner_default.png') ?>">
        <source src="/LASK/<?= htmlspecialchars($song['path_link']) ?>" type="audio/mpeg">
    </audio>
</div>

<?php endforeach; ?>

<a href="<?= htmlspecialchars($finalizeUrl) ?>">
    <button>Finalizar</button>
</a>
<script src="<?= substr(BASE_URL, 0, strrpos(BASE_URL, '/')) ?>/js/playlist.js"></script>