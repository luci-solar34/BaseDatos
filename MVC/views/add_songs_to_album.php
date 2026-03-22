<link rel="stylesheet" href="<?= htmlspecialchars(rtrim(dirname(BASE_URL), '/\\') . '/css/create_album.css') ?>">

<div class="page-wrapper">
    <h1 class="page-title">Agregar canciones</h1>

    <?php if(!empty($songAdded)): ?>
        <div class="alert alert--success">Cancion agregada correctamente. Puedes seguir agregando sin salir.</div>
    <?php endif; ?>

    <form class="album-form" action="<?= htmlspecialchars(BASE_URL . '/album/add-songs?id=' . (int)$album['id_album']) ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="album_id" value="<?= (int)$album['id_album'] ?>">
        <input type="hidden" id="addModeInput" name="add_mode" value="<?= htmlspecialchars($selectedMode) ?>">

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
            <div class="mode-switch" data-locked="<?= !empty($modeLocked) ? '1' : '0' ?>">
                <h2 class="mode-switch__title">Forma de agregar canciones</h2>
                <p class="mode-switch__hint">
                    <?= !empty($modeLocked)
                        ? 'Modo bloqueado temporalmente para mantener el flujo estable.'
                        : 'Elige una forma y agrega varias canciones seguidas.' ?>
                </p>

                <div class="mode-switch__options">
                    <label class="mode-option <?= $selectedMode === 'transfer' ? 'is-active' : '' ?>">
                        <input type="radio" name="mode_choice" value="transfer" <?= $selectedMode === 'transfer' ? 'checked' : '' ?> <?= !empty($modeLocked) ? 'disabled' : '' ?>>
                        <span>Transferir existente</span>
                    </label>
                    <label class="mode-option <?= $selectedMode === 'upload' ? 'is-active' : '' ?>">
                        <input type="radio" name="mode_choice" value="upload" <?= $selectedMode === 'upload' ? 'checked' : '' ?> <?= !empty($modeLocked) ? 'disabled' : '' ?>>
                        <span>Subir nueva</span>
                    </label>
                </div>

                <?php if(!empty($modeLocked)): ?>
                    <?php $unlockMode = $selectedMode === 'transfer' ? 'upload' : 'transfer'; ?>
                    <a class="mode-switch__unlock" href="<?= htmlspecialchars(BASE_URL . '/album/add-songs?id=' . (int)$album['id_album'] . '&mode=' . $unlockMode . '&unlock=1') ?>">
                        Cambiar forma de agregado
                    </a>
                <?php endif; ?>
            </div>

            <div class="mode-panel <?= $selectedMode === 'transfer' ? 'is-active' : '' ?>" data-panel="transfer" <?= $selectedMode === 'transfer' ? '' : 'hidden' ?>>
                <div class="form-group">
                    <label class="form-label" for="existingSong">Cancion para transferir</label>
                    <select id="existingSong" class="form-input" name="existing_song_id">
                        <option value="">Selecciona una cancion</option>
                        <?php if(!empty($availableSongs)): ?>
                            <?php foreach($availableSongs as $song): ?>
                                <option value="<?= (int)$song['id_cancion'] ?>">
                                    <?= htmlspecialchars($song['nombre_cancion']) ?>
                                    <?= !empty($song['id_album']) ? '(desde otro album)' : '(single)' ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <div class="mode-panel <?= $selectedMode === 'upload' ? 'is-active' : '' ?>" data-panel="upload" <?= $selectedMode === 'upload' ? '' : 'hidden' ?>>
                <div class="form-group">
                    <label class="form-label" for="nombre">Nombre de la nueva cancion</label>
                    <input id="nombre" class="form-input" type="text" name="nombre" placeholder="Nombre de la cancion nueva">
                </div>

                <div class="form-group">
                    <label class="form-label" for="archivo">Archivo mp3</label>
                    <input id="archivo" class="form-input" type="file" name="archivo" accept=".mp3">
                </div>
            </div>

            <div class="form-actions">
                <button class="btn-insert-songs" type="submit">Agregar canción al álbum</button>
                <a class="btn-publish" href="<?= htmlspecialchars(BASE_URL . '/album?id=' . (int)$album['id_album']) ?>">Terminar y ver álbum</a>
            </div>
        </div>

        <aside class="songs-panel">
            <h2 class="songs-panel__title">Canciones agregadas (<?= (int)$songCount ?>)</h2>
            <ul class="songs-panel__list">
                <?php if(!empty($albumSongs)): ?>
                    <?php foreach($albumSongs as $index => $song): ?>
                        <li class="song-item">
                            <span class="song-item__num"><?= (int)($index + 1) ?></span>
                            <span class="song-item__name"><?= htmlspecialchars($song['nombre_cancion']) ?></span>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="songs-panel__empty">Sin canciones todavia</li>
                <?php endif; ?>
            </ul>
        </aside>

    </form>

    <a class="link-back" href="<?= htmlspecialchars($artistProfileUrl) ?>">Volver al perfil</a>
</div>

<script>
    (function() {
        var modeInput = document.getElementById('addModeInput');
        var switchBox = document.querySelector('.mode-switch');
        var modeChoices = document.querySelectorAll('input[name="mode_choice"]');
        var panels = document.querySelectorAll('.mode-panel');

        if (!modeInput || !switchBox || modeChoices.length === 0) {
            return;
        }

        var isLocked = switchBox.getAttribute('data-locked') === '1';

        function togglePanelInputs(panel, enabled) {
            var fields = panel.querySelectorAll('input, select, textarea');
            fields.forEach(function(field) {
                field.disabled = !enabled;
                if (!enabled && field.type !== 'hidden') {
                    if (field.tagName === 'SELECT') {
                        field.selectedIndex = 0;
                    } else if (field.type === 'file') {
                        field.value = '';
                    } else if (field.type !== 'radio' && field.type !== 'checkbox') {
                        field.value = '';
                    }
                }
            });
        }

        function setMode(mode) {
            if (isLocked) {
                return;
            }

            modeInput.value = mode;

            modeChoices.forEach(function(choice) {
                var option = choice.closest('.mode-option');
                var isCurrent = choice.value === mode;
                choice.checked = isCurrent;
                if (option) {
                    option.classList.toggle('is-active', isCurrent);
                }
            });

            panels.forEach(function(panel) {
                var isCurrent = panel.getAttribute('data-panel') === mode;
                panel.classList.toggle('is-active', isCurrent);
                panel.hidden = !isCurrent;
                togglePanelInputs(panel, isCurrent);
            });
        }

        modeChoices.forEach(function(choice) {
            choice.addEventListener('change', function() {
                setMode(choice.value);
            });
        });

        var initialMode = modeInput.value || 'transfer';

        if (isLocked) {
            panels.forEach(function(panel) {
                var isCurrent = panel.getAttribute('data-panel') === initialMode;
                panel.classList.toggle('is-active', isCurrent);
                panel.hidden = !isCurrent;
                togglePanelInputs(panel, isCurrent);
            });

            modeChoices.forEach(function(choice) {
                var option = choice.closest('.mode-option');
                var isCurrent = choice.value === initialMode;
                choice.checked = isCurrent;
                if (option) {
                    option.classList.toggle('is-active', isCurrent);
                }
            });

            return;
        }

        setMode(initialMode);
    })();
</script>