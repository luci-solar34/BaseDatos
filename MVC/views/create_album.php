<link rel="stylesheet" href="<?= htmlspecialchars(rtrim(dirname(BASE_URL), '/\\') . '/css/create_album.css') ?>">

<div class="page-wrapper">
    <h1 class="page-title">Crear álbum</h1>

    <form class="album-form" action="<?= htmlspecialchars(BASE_URL . '/artist/create-album?id=' . (int)$artistId) ?>" method="POST" enctype="multipart/form-data">
        <div class="album-cover">
            <label class="album-cover__label" for="portada">Portada</label>

            <label class="album-cover__drop" for="portada">
                <input id="portada" type="file" name="portada" accept=".jpg,.jpeg,.png,.webp" required>

                <div id="coverPlaceholder" class="album-cover__placeholder">
                    <span class="album-cover__icon" aria-hidden="true"></span>
                    <span class="album-cover__hint">jpg / png</span>
                </div>

                <img id="coverPreview" class="album-cover__preview" alt="Vista previa de portada">
            </label>

            <button type="button" class="album-cover__btn-insert" onclick="document.getElementById('portada').click()">Insertar portada</button>
        </div>

        <div class="album-fields">
            <div class="form-group">
                <label class="form-label" for="albumNombre">Nombre del álbum</label>
                <input id="albumNombre" class="form-input" type="text" name="nombre" placeholder="Nombre del álbum" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="albumDescripcion">Descripción</label>
                <textarea id="albumDescripcion" class="form-textarea" name="descripcion" placeholder="Descripción del álbum..."></textarea>
            </div>

            <div class="form-actions">
                <button class="btn-insert-songs" type="submit">Insertar canciones</button>
                <button class="btn-publish" type="submit">Publicar álbum</button>
            </div>
        </div>

        <aside class="songs-panel">
            <h2 class="songs-panel__title">Canciones agregadas</h2>
            <ul class="songs-panel__list">
                <li class="songs-panel__empty">Sin canciones todavía</li>
            </ul>
        </aside>
    </form>

    <a class="link-back" href="<?= htmlspecialchars(BASE_URL . '/artist?id=' . (int)$artistId) ?>">Volver</a>
</div>

<script>
    (function() {
        var input = document.getElementById('portada');
        var preview = document.getElementById('coverPreview');
        var placeholder = document.getElementById('coverPlaceholder');

        if (!input || !preview || !placeholder) {
            return;
        }

        input.addEventListener('change', function() {
            var file = input.files && input.files[0] ? input.files[0] : null;
            if (!file) {
                preview.removeAttribute('src');
                preview.style.display = 'none';
                placeholder.style.display = 'flex';
                return;
            }

            var objectUrl = URL.createObjectURL(file);
            preview.src = objectUrl;
            preview.style.display = 'block';
            placeholder.style.display = 'none';

            preview.onload = function() {
                URL.revokeObjectURL(objectUrl);
            };
        });
    })();
</script>