<h1>Publicar una canción</h1>

<a href="/LASK/public/index.php/profile?id=<?= $_SESSION['user_id'] ?>">← Volver al perfil</a>

<form action="/LASK/public/index.php/artist/song/create" method="POST" enctype="multipart/form-data" style="margin-top: 16px;">
    <div>
        <label for="nombre">Nombre de la canción</label><br>
        <input type="text" name="nombre" id="nombre" required>
    </div>

    <div style="margin-top: 10px;">
        <label for="pista">Número de pista</label><br>
        <input type="number" name="pista" id="pista" min="1">
    </div>

    <div style="margin-top: 10px;">
        <label for="path">Enlace / ruta de la canción</label><br>
        <input type="text" name="path" id="path" placeholder="URL o ruta" required>
    </div>

    <div style="margin-top: 10px;">
        <label for="album">Álbum (opcional)</label><br>
        <select name="album" id="album">
            <option value="">-- Sin álbum --</option>
            <?php foreach($albums as $album): ?>
                <option value="<?= $album['id_album'] ?>" <?= isset($selectedAlbum) && $selectedAlbum == $album['id_album'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($album['nombre_album']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div style="margin-top: 10px;">
        <label for="tags">Tags (género/tipo)</label><br>
        <select name="tags[]" id="tags" multiple style="min-height: 100px; width: 100%;">
            <?php foreach($tags as $tag): ?>
                <option value="<?= $tag['id_tag'] ?>"><?= htmlspecialchars($tag['nombre_tag']) ?></option>
            <?php endforeach; ?>
        </select>
        <small>Ctrl/Cmd + click para seleccionar varios.</small>
    </div>

    <div style="margin-top: 10px;">
        <label for="portada">Portada de la canción (opcional)</label><br>
        <input type="file" name="portada" id="portada" accept="image/*">
    </div>

    <div style="margin-top: 16px;">
        <button type="submit">Publicar canción</button>
    </div>
</form>
