<link rel="stylesheet" href="<?= htmlspecialchars(rtrim(dirname(BASE_URL), '/\\') . '/css/create_album.css') ?>">

<div class="page-wrapper">
    <h1 class="page-title">Editar álbum</h1>

    <form class="album-form album-form--expanded" action="<?= htmlspecialchars(BASE_URL . '/artist/edit-album?id=' . (int)$album['id_album']) ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="album_id" value="<?= (int)$album['id_album'] ?>">

        <div class="album-cover">
            <label class="album-cover__label" for="portada">Portada</label>
            <label class="album-cover__drop" for="portada">
                <input id="portada" type="file" name="portada" accept=".jpg,.jpeg,.png,.webp">

                <?php if(!empty($album['portada_album'])): ?>
                    <img src="/LASK/<?= htmlspecialchars($album['portada_album']) ?>" class="album-cover__preview" style="display:block;" alt="Portada actual">
                <?php else: ?>
                    <div class="album-cover__placeholder">
                        <span class="album-cover__icon" aria-hidden="true"></span>
                        <span class="album-cover__hint">Sin portada</span>
                    </div>
                <?php endif; ?>
            </label>
            <button type="button" class="album-cover__btn-insert" onclick="document.getElementById('portada').click()">Cambiar portada</button>
        </div>

        <div class="album-fields">
            <div class="form-group">
                <label class="form-label" for="albumNombre">Nombre del álbum</label>
                <input id="albumNombre" class="form-input" type="text" name="nombre" value="<?= htmlspecialchars($album['nombre_album']) ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="albumDescripcion">Descripción</label>
                <textarea id="albumDescripcion" class="form-textarea" name="descripcion"><?= htmlspecialchars($album['descripcion_album'] ?? '') ?></textarea>
            </div>

            <?php if(!empty($availableSongs)): ?>
                <div class="form-group">
                    <label class="form-label" for="existingSongId">Agregar canción existente</label>
                    <select id="existingSongId" class="form-input" name="existing_song_id">
                        <option value="">No mover ninguna canción</option>
                        <?php foreach($availableSongs as $song): ?>
                            <option value="<?= (int)$song['id_cancion'] ?>">
                                <?= htmlspecialchars($song['nombre_cancion']) ?>
                                <?= !empty($song['id_album']) ? '(transferir)' : '(sin álbum)' ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php else: ?>
                <p class="songs-panel__empty">No tienes canciones disponibles para mover.</p>
            <?php endif; ?>

            <div class="form-actions">
                <a class="btn-insert-songs" href="<?= htmlspecialchars(BASE_URL . '/album/add-songs?id=' . (int)$album['id_album']) ?>">Agregar canciones</a>
                <button class="btn-publish" type="submit">Guardar cambios</button>
            </div>
        </div>

    </form>

    <a class="link-back" href="<?= htmlspecialchars(BASE_URL . '/album?id=' . (int)$album['id_album']) ?>">Volver al álbum</a>
</div>