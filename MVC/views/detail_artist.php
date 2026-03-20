<?php
$pageTitle = $artist['nombre_artistico'] . ' - LASK';
?>

<h1><?= htmlspecialchars($artist['nombre_artistico']) ?></h1>
<p>@<?= htmlspecialchars($artist['nombre_usuario']) ?></p>

<?php if(isset($flashMessage) && $flashMessage): ?>
    <div style="padding:10px; margin:10px 0; border:1px solid green; background:#e6ffe6;">
        <?= htmlspecialchars($flashMessage) ?>
    </div>
<?php endif; ?>

<?php if($showArtistImageUpload): ?>
    <p>Haz click en la imagen para cambiar tu foto</p>
    <form action="<?= htmlspecialchars(BASE_URL . '/update_pfp') ?>" method="POST" enctype="multipart/form-data" id="pfpUploadForm">
        <label for="pfp">
            <img src="/LASK/<?= htmlspecialchars($artist['pfp']) ?>" alt="Tu foto de perfil" width="120" style="cursor:pointer;border-radius:50%;">
        </label>
        <input type="file" name="pfp" id="pfp" style="display:none" data-auto-submit-target="pfpUploadForm">
    </form>
<?php elseif($showArtistImage): ?>
    <img src="/LASK/<?= htmlspecialchars($artist['pfp']) ?>" alt="Foto de perfil del artista" width="120" style="border-radius: 50%;">
<?php endif; ?>

<p>
    <strong>Seguidores:</strong>
    <a href="<?= htmlspecialchars(BASE_URL . '/profile/followers?id=' . (int)$artist['id_usuario']) ?>"><?= (int)$followers ?></a>
</p>

<p>
    <strong>Siguiendo:</strong>
    <a href="<?= htmlspecialchars(BASE_URL . '/profile/following?id=' . (int)$artist['id_usuario']) ?>"><?= (int)$following ?></a>
</p>

<?php if($canInteract): ?>
    <form method="POST" action="<?= htmlspecialchars(BASE_URL) ?>">
    <input type="hidden" name="route" value="<?= htmlspecialchars($isFollowing ? 'unfollow' : 'follow') ?>">
    <input type="hidden" name="user_id" value="<?= (int)$artist['id_usuario'] ?>">
    <input type="hidden" name="redirect_id" value="<?= (int)$artist['id_usuario'] ?>">
    <button type="submit" class="btn">
        <?= htmlspecialchars($isFollowing ? 'Dejar de seguir' : 'Seguir') ?>
    </button>
</form>

    <form method="POST" action="<?= htmlspecialchars(BASE_URL . '/block') ?>" style="margin-top:10px;">
        <input type="hidden" name="user_id" value="<?= (int)$artist['id_usuario'] ?>">
        <button type="submit" class="btn btn-danger" style="background:red;color:white;">Bloquear</button>
    </form>

    <?php if(isset($canReport) && $canReport): ?>
        <button type="button" data-toggle-target="reportForm">Denunciar</button>

        <form id="reportForm" method="POST" action="<?= htmlspecialchars(BASE_URL . '/report') ?>" style="display:none; margin-top:15px; border:1px solid #ccc; padding: 12px;">
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
            <button type="button" data-hide-target="reportForm">Cancelar</button>
        </form>
    <?php endif; ?>
<?php endif; ?>

<?php if($hasBio): ?>
    <h3>Biografía</h3>
    <p><?= nl2br(htmlspecialchars($bioText)) ?></p>
<?php endif; ?>

<?php if($isOwner): ?>
    <button type="button" data-toggle-target="editBio">
        Editar bio
    </button>

    <form id="editBio" action="<?= htmlspecialchars(BASE_URL . '/update_bio') ?>" method="POST" style="display:none; margin-top:10px;">
        <textarea name="bio" rows="4" cols="50" placeholder="Escribe tu bio"><?= htmlspecialchars($bioText) ?></textarea>

        <br><br>

        <button type="submit">Guardar</button>
    </form>
<?php endif; ?>

<?php if($canSeeEmail && $hasEmail): ?>
    <p><strong>Email:</strong> <?= htmlspecialchars($artist['email']) ?></p>
<?php endif; ?>

<?php if($hasCountry): ?>
    <p><strong>País:</strong> <?= htmlspecialchars($artist['nombre_pais']) ?></p>
<?php endif; ?>

<?php if(!empty($albums)): ?>
    <h2>Álbumes</h2>
    <ul>
    <?php foreach($albums as $album): ?>
        <li>
            <a href="<?= htmlspecialchars(BASE_URL . '/album?id=' . (int)$album['id_album']) ?>">
                <?= htmlspecialchars($album['nombre_album']) ?>
            </a>
        </li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>

<h2>Canciones</h2>

<?php if(empty($songs)): ?>
    <p>Este artista no tiene canciones</p>
<?php else: ?>
    <ul>
    <?php foreach($songs as $song): ?>
        <li>
            <a href="<?= htmlspecialchars(BASE_URL . '/song?id=' . (int)$song['id_cancion']) ?>">
                <?= htmlspecialchars($song['nombre_cancion']) ?>
            </a>
            <?php if($song['nombre_album']): ?>
                (álbum: <?= htmlspecialchars($song['nombre_album']) ?>)
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php if($isOwner): ?>

<a href="<?= htmlspecialchars(BASE_URL . '/playlist/create') ?>" class="btn">
    Crear Playlist
</a>

<a href="<?= htmlspecialchars(BASE_URL . '/artist/create-album?id=' . (int)$artist['id_usuario']) ?>" class="btn">
    Crear Álbum
</a>

<a href="<?= htmlspecialchars(BASE_URL . '/artist/create-song?id=' . (int)$artist['id_usuario']) ?>" class="btn">
    Crear Canción
</a>

<?php endif; ?>

<h2>Playlists públicas</h2>

<?php if(empty($playlists)): ?>
    <p>No hay playlists</p>
<?php else: ?>
    <?php foreach($playlists as $playlist): ?>
        <div>
            <a href="<?= htmlspecialchars(BASE_URL . '/playlist?id=' . (int)$playlist['id_playlist']) ?>">
                <?= htmlspecialchars($playlist['nombre_playlist']) ?>
            </a>

            <?php if($playlist['privacy_label']): ?>
                <?= htmlspecialchars($playlist['privacy_label']) ?>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<h2>Comentarios</h2>

<?php if($canComment): ?>
<form action="<?= htmlspecialchars(BASE_URL . '/artist/add-comment') ?>" method="POST">
    <input type="hidden" name="artist_id" value="<?= (int)$artist['id_usuario'] ?>">
    <textarea name="comment" placeholder="Escribe un comentario..." required></textarea>
    <button type="submit">Comentar</button>
</form>
<?php endif; ?>

<?php if(!empty($comments)): ?>
<ul>
<?php foreach($comments as $comment): ?>
    <li>
        <strong><?= htmlspecialchars($comment['nombre_usuario']) ?>:</strong> <?= nl2br(htmlspecialchars($comment['comentario'])) ?>
        <small>(<?= htmlspecialchars($comment['fecha_comentario']) ?>)</small>
    </li>
<?php endforeach; ?>
</ul>
<?php else: ?>
<p>No hay comentarios aún.</p>
<?php endif; ?>

<br>
<a href="<?= htmlspecialchars(BASE_URL) ?>">← Volver al inicio</a>