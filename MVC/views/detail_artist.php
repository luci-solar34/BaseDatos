<?php
$pageTitle = $artist['nombre_artistico'] . ' - LASK';
?>

<h1><?= $artist['nombre_artistico'] ?></h1>
<p>@<?= $artist['nombre_usuario'] ?></p>

<?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $artist['id_usuario']): ?>
    <p>Haz click en la imagen para cambiar tu foto</p>
    <form action="/LASK/public/index.php/update_pfp" method="POST" enctype="multipart/form-data">
        <label for="pfp">
            <img src="/LASK/<?= $artist['pfp'] ?>" width="120" style="cursor:pointer;border-radius:50%;">
        </label>
        <input type="file" name="pfp" id="pfp" style="display:none" onchange="this.form.submit()">
    </form>
<?php elseif(!empty($artist['pfp'])): ?>
    <img src="/LASK/<?= $artist['pfp'] ?>" width="120" style="border-radius: 50%;">
<?php endif; ?>

<p><strong>Seguidores:</strong> <a href="/LASK/public/index.php/profile/followers?id=<?= $artist['id_usuario'] ?>"><?= $followers ?></a></p>
<p><strong>Siguiendo:</strong> <a href="/LASK/public/index.php/profile/following?id=<?= $artist['id_usuario'] ?>"><?= $following ?></a></p>

<?php if(isset($flashMessage) && $flashMessage): ?>
    <div style="padding:10px; margin:10px 0; border:1px solid green; background:#e6ffe6;">
        <?= $flashMessage ?>
    </div>
<?php endif; ?>

<?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != $artist['id_usuario']): ?>
    <form method="POST" action="/LASK/public/index.php">
    <input type="hidden" name="route" value="<?= $isFollowing ? 'unfollow' : 'follow' ?>">
    <input type="hidden" name="user_id" value="<?= $artist['id_usuario'] ?>">
    <button type="submit">
        <?= $isFollowing ? 'Dejar de seguir' : 'Seguir' ?>
    </button>
</form>
<?php endif; ?>

<?php if(isset($canReport) && $canReport): ?>
    <button onclick="document.getElementById('reportForm').style.display='block'">Denunciar</button>
    <form id="reportForm" method="POST" action="/LASK/public/index.php/report" style="display:none; margin-top:15px; border:1px solid #ccc; padding:12px;">
        <input type="hidden" name="denunciado_id" value="<?= $artist['id_usuario'] ?>">
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
        <button type="button" onclick="document.getElementById('reportForm').style.display='none'">Cancelar</button>
    </form>
<?php elseif(isset($_SESSION['user_id']) && $_SESSION['user_id'] != $artist['id_usuario']): ?>
    <p style="color: #a00;">No puedes denunciar a un administrador o el sistema ha bloqueado tu acción.</p>
<?php endif; ?>

<h3>Bio</h3>
<p><?= $artist['bio'] ?? 'Sin bio' ?></p>

<?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $artist['id_usuario']): ?>
    <button onclick="document.getElementById('editBio').style.display='block'">Editar bio</button>
    <form id="editBio" action="/LASK/public/index.php/update_bio" method="POST" style="display:none; margin-top:10px;">
        <textarea name="bio" rows="4" cols="50" placeholder="Escribe tu bio"><?= $artist['bio'] ?></textarea>
        <br><br>
        <button type="submit">Guardar</button>
    </form>
<?php endif; ?>

<?php if($artist['email']): ?>
    <p><strong>Email:</strong> <?= $artist['email'] ?></p>
<?php endif; ?>

<?php if(!empty($artist['nombre_pais'])): ?>
    <p><strong>País:</strong> <?= $artist['nombre_pais'] ?></p>
<?php endif; ?>

<?php if(!empty($albums)): ?>
    <h2>Álbumes</h2>
    <ul>
    <?php foreach($albums as $album): ?>
        <li>
            <a href="/LASK/public/index.php/album?id=<?= $album['id_album'] ?>">
                <?= $album['nombre_album'] ?>
            </a>
            <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $artist['id_usuario']): ?>
                <a href="/LASK/public/index.php/artist/edit-album?id=<?= $album['id_album'] ?>">Editar</a>
            <?php endif; ?>
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
            <a href="/LASK/public/index.php/song?id=<?= $song['id_cancion'] ?>">
                <?= $song['nombre_cancion'] ?>
            </a>
            <?php if($song['nombre_album']): ?>
                (álbum: <?= $song['nombre_album'] ?>)
            <?php endif; ?>
            <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $artist['id_usuario']): ?>
                <a href="/LASK/public/index.php/artist/edit-song?id=<?= $song['id_cancion'] ?>">Editar</a>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $artist['id_usuario']): ?>

<a href="/LASK/public/index.php/playlist/create">
    <button>Crear Playlist</button>
</a>

<h2>Playlists públicas</h2>

<?php if(empty($playlists)): ?>

<p>No hay playlists</p>

<?php else: ?>

<?php foreach($playlists as $playlist): ?>

<div>
    <a href="/LASK/public/index.php/playlist?id=<?= $playlist['id_playlist'] ?>">
        <?= $playlist['nombre_playlist'] ?>
    </a>

    <?php if($playlist['privacidad_playlist'] == 1): ?>
        (Privada)
    <?php endif; ?>

</div>

<?php endforeach; ?>

<?php endif; ?>

<a href="/LASK/public/index.php/artist/create-album?id=<?= $artist['id_usuario'] ?>">
    <button>Crear Álbum</button>
</a>

<a href="/LASK/public/index.php/artist/create-song?id=<?= $artist['id_usuario'] ?>">
    <button>Crear Canción</button>
</a>

<?php endif; ?>

<h2>Comentarios</h2>

<?php if(isset($_SESSION['user_id'])): ?>
<form action="/LASK/public/index.php/artist/add-comment" method="POST">
    <input type="hidden" name="artist_id" value="<?= $artist['id_usuario'] ?>">
    <textarea name="comment" placeholder="Escribe un comentario..." required></textarea>
    <button type="submit">Comentar</button>
</form>
<?php endif; ?>

<?php if(!empty($comments)): ?>
<ul>
<?php foreach($comments as $comment): ?>
    <li>
        <strong><?= $comment['nombre_usuario'] ?>:</strong> <?= nl2br($comment['comentario']) ?>
        <small>(<?= $comment['fecha_comentario'] ?>)</small>
    </li>
<?php endforeach; ?>
</ul>
<?php else: ?>
<p>No hay comentarios aún.</p>
<?php endif; ?>

<br>
<a href="/LASK/public">← Volver al inicio</a>