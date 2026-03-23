<h1 class="edit-title">Editar Playlist</h1>
<link rel="stylesheet" href="/LASK/public/css/edit-playlist.css">
<div class="edit-container">

    <!-- FORM PRIVACIDAD -->
    <div class="edit-card">
        <form action="<?= htmlspecialchars(BASE_URL . '/playlist/change-privacy') ?>" method="POST">
            <?= $csrfField ?>
            <input type="hidden" name="playlist_id" value="<?= (int)$playlist_id ?>">
            
            <h3>Privacidad de la Playlist</h3>

            <label class="radio-option">
                <input type="radio" name="privacy" value="1" <?= (int)$playlist['privacidad_playlist'] === 1 ? 'checked' : '' ?>>
                Pública
            </label>

            <label class="radio-option">
                <input type="radio" name="privacy" value="0" <?= (int)$playlist['privacidad_playlist'] === 0 ? 'checked' : '' ?>>
                Privada
            </label>

            <button type="submit" class="btn">Guardar Cambios</button>
        </form>
    </div>

    <!-- LISTA DE CANCIONES -->
    <div class="edit-card">
        <h2>Canciones en la Playlist</h2>

        <?php foreach($songs as $song): ?>
            <div class="song-item">
                <span><?= htmlspecialchars($song['nombre_cancion']) ?></span>

                <form action="<?= htmlspecialchars(BASE_URL . '/playlist/remove-song') ?>" method="POST">
                    <?= $csrfField ?>
                    <input type="hidden" name="playlist_id" value="<?= (int)$playlist_id ?>">
                    <input type="hidden" name="song_id" value="<?= (int)$song['id_cancion'] ?>">
                    <button type="submit" class="btn delete">Eliminar</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- BOTON VOLVER -->
    <a href="<?= htmlspecialchars(BASE_URL . '/playlist?id=' . (int)$playlist_id) ?>" class="btn back">
        Volver a la Playlist
    </a>

</div>