<link rel="stylesheet" href="<?= htmlspecialchars(rtrim(dirname(BASE_URL), '/\\') . '/css/edit_song.css?v=' . time()) ?>">

<div class="edit-song-page">

    <h1>Editar Canción</h1>

    <form class="edit-song-form" action="<?= BASE_URL ?>/artist/edit-song?id=<?= (int)$song['id_cancion'] ?>" method="POST" enctype="multipart/form-data">
        <?= $csrfField ?>
        <input type="hidden" name="song_id" value="<?= (int)$song['id_cancion'] ?>">

        <label>Nombre de la Canción:</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($song['nombre_cancion']) ?>" required>

        <label>Álbum:</label>
        <select name="album_id">
            <option value="">Sin álbum</option>
            <?php foreach($albums as $album): ?>
                <option value="<?= (int)$album['id_album'] ?>" <?= (int) $song['id_album'] === (int) $album['id_album'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($album['nombre_album']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Portada actual:</label>
        <img class="current-cover" src="/LASK/<?= htmlspecialchars($song['portada_cancion'] ?? 'Photos/banner_default.png') ?>" width="160" alt="Portada actual">

        <label>Nueva portada (jpg/png/webp):</label>
        <input type="file" name="portada" accept=".jpg,.jpeg,.png,.webp">

        <label>Tags (puedes elegir varios):</label>
        <select name="tags[]" multiple size="6">
            <?php foreach($tags as $tag): ?>
                <option value="<?= (int)$tag['id_tag'] ?>" <?= in_array((int) $tag['id_tag'], $selectedTagIds, true) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($tag['nombre_tag']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <small>Tip: Ctrl + click para seleccionar varios.</small>

        <label>Letra de la canción:</label>
        <textarea name="letra_cancion" rows="8"><?= htmlspecialchars($lyrics['letra_cancion'] ?? '') ?></textarea>

        <label>Texto fonético:</label>
        <textarea name="texto_fonetico" rows="8"><?= htmlspecialchars($lyrics['texto_fonetico'] ?? '') ?></textarea>

        <button type="submit" class="btn">Guardar Cambios</button>
    </form>

    <p class="edit-song-info">Al mover la canción de álbum, se conserva el mismo ID de canción y no se pierden los likes.</p>

    <a class="link-back-song" href="<?= BASE_URL ?>/song?id=<?= (int)$song['id_cancion'] ?>">← Volver a la canción</a>

</div>