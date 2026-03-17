<h1>Editar Playlist</h1>

<form action="/LASK/public/index.php/playlist/change-privacy" method="POST">
    <input type="hidden" name="playlist_id" value="<?= $playlist_id ?>">
    
    <h3>Privacidad de la Playlist</h3>
    <label>
        <input type="radio" name="privacy" value="0" <?= !$playlist['privacidad_playlist'] ? 'checked' : '' ?>>
        Pública
    </label>
    <br>
    <label>
        <input type="radio" name="privacy" value="1" <?= $playlist['privacidad_playlist'] ? 'checked' : '' ?>>
        Privada
    </label>
    <br><br>
    <button type="submit">Guardar Cambios</button>
</form>

<h2>Canciones en la Playlist</h2>

<?php foreach($songs as $song): ?>
<div>
    <p><?= $song['nombre_cancion'] ?></p>
    <form action="/LASK/public/index.php/playlist/remove-song" method="POST" style="display:inline;">
        <input type="hidden" name="playlist_id" value="<?= $playlist_id ?>">
        <input type="hidden" name="song_id" value="<?= $song['id_cancion'] ?>">
        <button type="submit">Eliminar</button>
    </form>
</div>
<?php endforeach; ?>

<a href="/LASK/public/index.php/playlist?id=<?= $playlist_id ?>">
    <button>Volver a la Playlist</button>
</a></content>
<parameter name="filePath">c:\laragon\www\lask\MVC\views\edit_playlist.php