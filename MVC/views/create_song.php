<link rel="stylesheet" href="<?= htmlspecialchars(rtrim(dirname(BASE_URL), '/\\') . '/css/create_song.css') ?>">

<h1>Crear Canción</h1>

<form action="<?= BASE_URL ?>/artist/create-song?id=<?= (int)$artistId ?>" method="POST" enctype="multipart/form-data">
    <?= $csrfField ?>
    <label>Nombre de la Canción:</label>
    <input type="text" name="nombre" required><br>

    <label>Archivo de Música (mp3):</label>
    <input type="file" name="archivo" accept=".mp3" required><br>

    <label>Portada de la Canción (jpg/png/webp):</label>
    <input type="file" name="portada" accept=".jpg,.jpeg,.png,.webp"><br><br>

    <label>Letra:</label><br>
    <textarea name="letra_cancion" rows="8" cols="60" placeholder="Escribe la letra de la canción"></textarea><br><br>

    <label>Letra fonética:</label><br>
    <textarea name="texto_fonetico" rows="8" cols="60" placeholder="Escribe la pronunciación fonética"></textarea><br><br>

    <label>Tags (puedes elegir varios):</label>
    <select name="tags[]" multiple size="6">
        <?php foreach($tags as $tag): ?>
            <option value="<?= (int)$tag['id_tag'] ?>"><?= htmlspecialchars($tag['nombre_tag']) ?></option>
        <?php endforeach; ?>
    </select><br>
    <small>Tip: Ctrl + click para seleccionar varios.</small><br><br>

    <button type="submit">Crear Canción</button>
</form>

<a href="<?= BASE_URL ?>/artist?id=<?= (int)$artistId ?>">Volver</a>