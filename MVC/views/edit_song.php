<h1>Editar Canción</h1>

<form action="/LASK/public/index.php/artist/edit-song?id=<?= $song['id_cancion'] ?>" method="POST">
    <input type="hidden" name="song_id" value="<?= $song['id_cancion'] ?>">

    <label>Nombre de la Canción:</label>
    <input type="text" name="nombre" value="<?= htmlspecialchars($song['nombre_cancion']) ?>" required><br>

    <label>Álbum:</label>
    <select name="album_id">
        <option value="">Sin álbum</option>
        <?php foreach($albums as $album): ?>
            <option value="<?= $album['id_album'] ?>" <?= (int) $song['id_album'] === (int) $album['id_album'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($album['nombre_album']) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <button type="submit">Guardar Cambios</button>
</form>

<p>Al mover la canción de álbum, se conserva el mismo ID de canción y no se pierden los likes.</p>

<a href="/LASK/public/index.php/song?id=<?= $song['id_cancion'] ?>">Volver a la canción</a>