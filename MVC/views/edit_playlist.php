<h1>Editar Playlist</h1>

<form action="<?= htmlspecialchars(BASE_URL . '/playlist/change-privacy') ?>" method="POST">
    <input type="hidden" name="playlist_id" value="<?= (int)$playlist_id ?>">
    
    <h3>Privacidad de la Playlist</h3>
    <label>
        <input type="radio" name="privacy" value="1" <?= (int)$playlist['privacidad_playlist'] === 1 ? 'checked' : '' ?>>
        Pública
    </label>
    <br>
    <label>
        <input type="radio" name="privacy" value="0" <?= (int)$playlist['privacidad_playlist'] === 0 ? 'checked' : '' ?>>
        Privada
    </label>
    <br><br>
    <button type="submit">Guardar Cambios</button>
</form>

<h2>Canciones en la Playlist</h2>

<?php foreach($songs as $song): ?>
<div>
    <p><?= htmlspecialchars($song['nombre_cancion']) ?></p>
    <form action="<?= htmlspecialchars(BASE_URL . '/playlist/remove-song') ?>" method="POST" style="display:inline;">
        <input type="hidden" name="playlist_id" value="<?= (int)$playlist_id ?>">
        <input type="hidden" name="song_id" value="<?= (int)$song['id_cancion'] ?>">
        <button type="submit">Eliminar</button>
    </form>
</div>
<?php endforeach; ?>

<a href="<?= htmlspecialchars(BASE_URL . '/playlist?id=' . (int)$playlist_id) ?>" class="btn">Volver a la Playlist</a>