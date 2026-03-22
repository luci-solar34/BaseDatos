<h1 class="playlist-title">Mi Playlist</h1>
<link rel="stylesheet" href="/LASK/public/css/playlist-view.css?v=<?= time() ?>">

<div class="playlist-container">

    <!-- IZQUIERDA: LISTA -->
    <div class="playlist-left">

        <div class="playlist-actions">
            <?php if($isOwner): ?>
            <a href="<?= BASE_URL ?>/playlist/add-song?playlist=<?= (int)$playlist_id ?>">
                <button class="btn">Agregar canciones</button>
            </a>

            <a href="<?= BASE_URL ?>/playlist/edit?id=<?= (int)$playlist_id ?>">
                <button class="btn">Editar Playlist</button>
            </a>
            <?php endif; ?>
        </div>

        <div class="songs-list">
            <?php foreach($songs as $song): ?>
                <div class="song-card">
                    <div class="song-main">
                        <?php
                            $coverPath = !empty($song['portada_cancion'])
                                ? $song['portada_cancion']
                                : (!empty($song['portada_album']) ? $song['portada_album'] : 'Photos/banner_default.png');
                        ?>
                        <img class="song-cover" src="/LASK/<?= htmlspecialchars($coverPath) ?>" alt="Portada de <?= htmlspecialchars($song['nombre_cancion']) ?>">

                        <div class="song-meta">
                            <span class="song-name\"><?= htmlspecialchars($song['nombre_cancion']) ?></span>
                            <a class="song-artist" href="<?= htmlspecialchars(BASE_URL . '/artist?id=' . (int)$song['id_artista']) ?>">
                                <?= htmlspecialchars($song['nombre_artistico']) ?>
                            </a>
                        </div>
                    </div>

                    <audio controls class="js-song-player"
                        data-title="<?= htmlspecialchars($song['nombre_cancion']) ?>"
                        data-cover="<?= htmlspecialchars($coverPath) ?>">
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