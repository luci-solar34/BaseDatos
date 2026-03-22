<link rel="stylesheet" href="<?= htmlspecialchars(rtrim(dirname(BASE_URL), '/\\') . '/css/artist.css?v=' . time()) ?>">

<div class="profile-container">
    <div class="profile-header">

        <div class="profile-left">
            <?php if($showArtistImageUpload): ?>
                <form action="<?= htmlspecialchars(BASE_URL . '/update_pfp') ?>" method="POST" enctype="multipart/form-data" id="pfpUploadForm">
                    <label for="pfp">
                        <img src="/LASK/<?= htmlspecialchars($artist['pfp']) ?>" alt="Tu foto de perfil">
                    </label>
                    <input type="file" name="pfp" id="pfp" style="display:none" onchange="this.form.submit()">
                </form>
            <?php elseif($showArtistImage): ?>
                <img src="/LASK/<?= htmlspecialchars($artist['pfp']) ?>" alt="Foto de perfil del artista">
            <?php endif; ?>
        </div>

        <div class="profile-info">
            <h1><?= htmlspecialchars($artist['nombre_artistico']) ?></h1>
            <p class="username">@<?= htmlspecialchars($artist['nombre_usuario']) ?></p>

            <div class="stats">
                    <a href="<?= htmlspecialchars(BASE_URL . '/profile/followers?id=' . (int)$artist['id_usuario']) ?>">
                    <strong><?= (int)$followers ?></strong> Seguidores
                    </a>

                    <a href="<?= htmlspecialchars(BASE_URL . '/profile/following?id=' . (int)$artist['id_usuario']) ?>">
                    <strong><?= (int)$following ?></strong> Siguiendo
                    </a>
            </div>

            <div class="actions">
                <?php if($isOwner): ?>
                    <a href="<?= htmlspecialchars(BASE_URL . '/logout') ?>" class="btn">Cerrar sesión</a>
                <?php endif; ?>

                <?php if($canInteract): ?>
                    <form method="POST" action="<?= htmlspecialchars(BASE_URL) ?>" style="display:inline;">
                        <input type="hidden" name="route" value="<?= htmlspecialchars($isFollowing ? 'unfollow' : 'follow') ?>">
                        <input type="hidden" name="user_id" value="<?= (int)$artist['id_usuario'] ?>">
                        <input type="hidden" name="redirect_id" value="<?= (int)$artist['id_usuario'] ?>">
                        <button type="submit" class="btn">
                            <?= htmlspecialchars($isFollowing ? 'Dejar de seguir' : 'Seguir') ?>
                        </button>
                    </form>

                    <?php if(isset($hasBlocked) && $hasBlocked): ?>
                        <form method="POST" action="<?= htmlspecialchars(BASE_URL . '/unblock') ?>" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?= (int)$artist['id_usuario'] ?>">
                            <button type="submit" class="btn">Desbloquear</button>
                        </form>
                    <?php else: ?>
                        <form method="POST" action="<?= htmlspecialchars(BASE_URL . '/block') ?>" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?= (int)$artist['id_usuario'] ?>">
                            <button type="submit" class="btn btn-danger">Bloquear</button>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <?php if(isset($canReport) && $canReport): ?>
        <?php if(isset($hasReported) && $hasReported): ?>
            <p style="color: #a00;">Ya has enviado una denuncia para este usuario. Espera a que sea revisada.</p>
        <?php else: ?>
            <button type="button" data-toggle-target="reportForm" onclick="var f=document.getElementById('reportForm'); if(f){f.style.display='block';}">Denunciar</button>

            <form id="reportForm" method="POST" action="<?= htmlspecialchars(BASE_URL . '/report') ?>" data-sql-guard="off" style="display:none; margin-top:15px; border:1px solid #ccc; padding: 12px;">
                <input type="hidden" name="denunciado_id" value="<?= (int)$artist['id_usuario'] ?>">

                <label>Tipo de denuncia:</label><br>
                <select name="motivo_denuncia" required>
                    <option value="">Selecciona un motivo</option>
                    <option value="Contenido inapropiado">Contenido inapropiado</option>
                    <option value="Acoso">Acoso</option>
                    <option value="Spam">Spam</option>
                    <option value="Suplantación de identidad">Suplantación de identidad</option>
                    <option value="Otro">Otro</option>
                </select>

                <br><br>

                <label>Descripción de la denuncia:</label><br>
                <textarea name="descripcion_denuncia" rows="4" cols="45" placeholder="Describe el motivo de tu denuncia" required></textarea>

                <br><br>

                <button type="submit">Enviar denuncia</button>
                <button type="button" data-hide-target="reportForm" onclick="var f=document.getElementById('reportForm'); if(f){f.style.display='none';}">Cancelar</button>
            </form>
        <?php endif; ?>
    <?php elseif(isset($reportUnavailableMessage) && $reportUnavailableMessage): ?>
        <p style="color:red;"><?= htmlspecialchars($reportUnavailableMessage) ?></p>
    <?php endif; ?>

    <?php if(isset($flashMessage) && $flashMessage): ?>
        <div class="flash-message">
            <?= htmlspecialchars($flashMessage) ?>
        </div>
    <?php endif; ?>

    <?php if($hasBio): ?>
        <div class="bio-box">
            <h3>Biografía</h3>
            <p><?= nl2br(htmlspecialchars($bioText)) ?></p>
        </div>
    <?php endif; ?>

    <?php if($isOwner): ?>
        <button onclick="document.getElementById('editBio').style.display='block'" class="btn">
            Editar bio
        </button>

        <form id="editBio" action="<?= htmlspecialchars(BASE_URL . '/update_bio') ?>" method="POST" style="display:none; margin-top:10px;">
            <textarea name="bio"><?= htmlspecialchars($bioText) ?></textarea>
            <br><br>
            <button type="submit" class="btn">Guardar</button>
        </form>
    <?php endif; ?>

    <h2>Álbumes</h2>
    <?php if(empty($albums)): ?>
        <p>Este artista no tiene álbumes</p>
    <?php else: ?>
        <div class="songs-grid albums-grid">
        <?php foreach($albums as $album): ?>
            <a class="media-card" href="<?= htmlspecialchars(BASE_URL . '/album?id=' . (int)$album['id_album']) ?>">
                <img src="/LASK/<?= htmlspecialchars($album['portada_album'] ?? 'Photos/banner_default.png') ?>" alt="">
                <span><?= htmlspecialchars($album['nombre_album']) ?></span>
            </a>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="songs-section">
        <h2>Canciones publicadas</h2>

        <?php if(empty($songs)): ?>
            <p>Este artista no tiene canciones</p>
        <?php else: ?>
            <div class="songs-grid">
                <?php foreach($songs as $song): ?>
                    <a class="media-card" href="<?= htmlspecialchars(BASE_URL . '/song?id=' . (int)$song['id_cancion']) ?>">
                        <img src="/LASK/<?= htmlspecialchars($song['portada_cancion'] ?? 'Photos/banner_default.png') ?>" alt="">
                        <span><?= htmlspecialchars($song['nombre_cancion']) ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php if($isOwner): ?>
        <div class="actions" style="margin-top:20px;">
            <a href="<?= htmlspecialchars(BASE_URL . '/playlist/create') ?>" class="btn">Crear Playlist</a>
            <a href="<?= htmlspecialchars(BASE_URL . '/artist/create-album?id=' . (int)$artist['id_usuario']) ?>" class="btn">Crear Álbum</a>
            <a href="<?= htmlspecialchars(BASE_URL . '/artist/create-song?id=' . (int)$artist['id_usuario']) ?>" class="btn">Crear Canción</a>
        </div>
    <?php endif; ?>

    <h2>Playlists públicas</h2>

    <?php if(empty($playlists)): ?>
        <p>No hay playlists</p>
    <?php else: ?>
        <div class="songs-grid">
        <?php foreach($playlists as $playlist): ?>
            <a class="media-card" href="<?= htmlspecialchars(BASE_URL . '/playlist?id=' . (int)$playlist['id_playlist']) ?>">
                <img src="/LASK/Photos/banner_default.png" alt="">
                <span><?= htmlspecialchars($playlist['nombre_playlist']) ?></span>
            </a>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <h2>Comentarios</h2>

    <?php if($canComment): ?>
        <form action="<?= htmlspecialchars(BASE_URL . '/artist/add-comment') ?>" method="POST">
            <input type="hidden" name="artist_id" value="<?= (int)$artist['id_usuario'] ?>">
            <textarea name="comment" placeholder="Escribe un comentario..." required></textarea>
            <button type="submit" class="btn">Comentar</button>
        </form>
    <?php endif; ?>

    <?php if(!empty($comments)): ?>
        <ul>
        <?php foreach($comments as $comment): ?>
            <li>
                <strong><?= htmlspecialchars($comment['nombre_usuario']) ?>:</strong>
                <?= nl2br(htmlspecialchars($comment['comentario'])) ?>
                <small>(<?= htmlspecialchars($comment['fecha_comentario']) ?>)</small>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No hay comentarios aún.</p>
    <?php endif; ?>

    <br>
    <a href="<?= htmlspecialchars(BASE_URL) ?>">← Volver al inicio</a>

</div>