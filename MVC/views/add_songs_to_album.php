<link rel="stylesheet" href="<?= htmlspecialchars(rtrim(dirname(BASE_URL), '/\\') . '/css/create_album.css') ?>">

<div class="page-wrapper">
    <h1 class="page-title">Agregar canciones</h1>

    <form class="album-form" action="<?= htmlspecialchars(BASE_URL . '/album/add-songs?id=' . (int)$album['id_album']) ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="album_id" value="<?= (int)$album['id_album'] ?>">

        <div class="album-cover">
            <label class="album-cover__label">Álbum</label>
            <div class="album-cover__drop" style="cursor:default;">
                <?php if(!empty($album['portada_album'])): ?>
                    <img src="/LASK/<?= htmlspecialchars($album['portada_album']) ?>" class="album-cover__preview" style="display:block;" alt="Portada del álbum">
                <?php else: ?>
                    <div class="album-cover__placeholder">
                        <span class="album-cover__icon" aria-hidden="true"></span>
                        <span class="album-cover__hint">Sin portada</span>
                    </div>
                <?php endif; ?>
            </div>
            <p class="album-cover__hint" style="margin-top:8px;"><?= htmlspecialchars($album['nombre_album']) ?></p>
        </div>

        <div class="album-fields">
            <div class="form-group">
                <label class="form-label" for="existingSong">Agregar canción existente</label>
                <select id="existingSong" class="form-input" name="existing_song_id">
                    <option value="">Selecciona una canción ya subida (opcional)</option>
                    <?php if(!empty($availableSongs)): ?>
                        <?php foreach($availableSongs as $song): ?>
                            <option value="<?= (int)$song['id_cancion'] ?>">
                                <?= htmlspecialchars($song['nombre_cancion']) ?>
                                <?= !empty($song['id_album']) ? '(transferir de otro álbum)' : '(sin álbum)' ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="nombre">Nombre canción nueva</label>
                <input id="nombre" class="form-input" type="text" name="nombre" placeholder="Nombre de la canción nueva (opcional)">
            </div>

            <div class="form-group">
                <label class="form-label" for="archivo">Archivo canción nueva</label>
                <input id="archivo" class="form-input" type="file" name="archivo" accept=".mp3">
            </div>

            <div class="form-actions">
                <button class="btn-insert-songs" type="submit">Agregar canción al álbum</button>
                <a class="btn-publish" href="<?= htmlspecialchars(BASE_URL . '/album?id=' . (int)$album['id_album']) ?>">Terminar y ver álbum</a>
            </div>
        </div>

    </form>

    <a class="link-back" href="<?= htmlspecialchars($artistProfileUrl) ?>">Volver al perfil</a>
</div>