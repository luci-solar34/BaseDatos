<h1>Editar Canción</h1>

<form action="/LASK/public/index.php/artist/edit-song?id=<?= $song['id_cancion'] ?>" method="POST" enctype="multipart/form-data">
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

    <label>Portada actual:</label><br>
    <img src="/LASK/<?= htmlspecialchars($song['portada_cancion'] ?? 'Photos/banner_default.png') ?>" width="160" style="border-radius:8px;"><br><br>

    <label>Nueva portada (jpg/png/webp):</label>
    <input type="file" name="portada" accept=".jpg,.jpeg,.png,.webp"><br><br>

    <label>Tags (puedes elegir varios):</label>
    <select name="tags[]" multiple size="6">
        <?php foreach($tags as $tag): ?>
            <option value="<?= $tag['id_tag'] ?>" <?= in_array((int) $tag['id_tag'], $selectedTagIds, true) ? 'selected' : '' ?>>
                <?= htmlspecialchars($tag['nombre_tag']) ?>
            </option>
        <?php endforeach; ?>
    </select><br>
    <small>Tip: Ctrl + click para seleccionar varios.</small><br><br>

    <button type="submit">Guardar Cambios</button>
</form>

<p>Al mover la canción de álbum, se conserva el mismo ID de canción y no se pierden los likes.</p>

<a href="/LASK/public/index.php/song?id=<?= $song['id_cancion'] ?>">Volver a la canción</a>