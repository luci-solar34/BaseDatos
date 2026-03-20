<h1 class="playlist-title">Mi Playlist</h1>
<link rel="stylesheet" href="/LASK/public/css/playlist-view.css">

<div class="playlist-container">

    <!-- IZQUIERDA: LISTA -->
    <div class="playlist-left">

        <div class="playlist-actions">
            <a href="<?= BASE_URL ?>/playlist/add-song?playlist=<?= (int)$playlist_id ?>">
                <button class="btn">Agregar canciones</button>
            </a>

            <a href="<?= BASE_URL ?>/playlist/edit?id=<?= (int)$playlist_id ?>">
                <button class="btn">Editar Playlist</button>
            </a>
        </div>

        <div class="songs-list">
            <?php foreach($songs as $song): ?>
                <div class="song-card">
                    <span class="song-name"><?= htmlspecialchars($song['nombre_cancion']) ?></span>

                    <audio controls class="js-song-player"
                        data-title="<?= htmlspecialchars($song['nombre_cancion']) ?>"
                        data-cover="<?= htmlspecialchars($song['portada_cancion'] ?? 'Photos/banner_default.png') ?>">
                        <source src="/LASK/<?= htmlspecialchars($song['path_link']) ?>" type="audio/mpeg">
                    </audio>
                </div>
            <?php endforeach; ?>
        </div>

        <a href="<?= htmlspecialchars($finalizeUrl) ?>">
            <button class="btn finalize">Finalizar</button>
        </a>

    </div>

    <!-- DERECHA: NOW PLAYING -->
    <div class="playlist-right" id="nowPlaying" style="display:none;">
        <img id="nowPlayingCover" src="/LASK/Photos/banner_default.png">
        <h3 id="nowPlayingTitle"></h3>
    </div>

</div>

<script src="<?= substr(BASE_URL, 0, strrpos(BASE_URL, '/')) ?>/js/playlist.js"></script>