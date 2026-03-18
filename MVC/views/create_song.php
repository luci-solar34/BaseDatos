<h1>Crear Canción</h1>

<form action="/LASK/public/index.php/artist/create-song?id=<?= $_GET['id'] ?>" method="POST" enctype="multipart/form-data">
    <label>Nombre de la Canción:</label>
    <input type="text" name="nombre" required><br>

    <label>Archivo de Música (mp3):</label>
    <input type="file" name="archivo" accept=".mp3" required><br>

    <label>Tags (puedes elegir varios):</label>
    <select name="tags[]" multiple size="6">
        <?php foreach($tags as $tag): ?>
            <option value="<?= $tag['id_tag'] ?>"><?= htmlspecialchars($tag['nombre_tag']) ?></option>
        <?php endforeach; ?>
    </select><br>
    <small>Tip: Ctrl + click para seleccionar varios.</small><br><br>

    <button type="submit">Crear Canción</button>
</form>

<a href="/LASK/public/index.php/artist?id=<?= $_GET['id'] ?>">Volver</a>