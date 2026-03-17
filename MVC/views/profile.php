<h1>Perfil</h1>
<a href="/LASK/public/index.php/logout">Cerrar sesión</a>
<p>Haz click en la imagen para cambiar tu foto</p>

<form action="/LASK/public/index.php/update_pfp" method="POST" enctype="multipart/form-data">

<label for="pfp">

<img src="/LASK/<?= $user['pfp'] ?>" width="120" style="cursor:pointer;border-radius:50%;">

</label>

<input type="file" name="pfp" id="pfp" style="display:none" onchange="this.form.submit()">

</form>

<p>Usuario: <?= $user['nombre_usuario'] ?></p>

<p>Email: <?= $user['email'] ?></p>


<?= $followers ?> Seguidores
<?= $following ?> Siguiendo


<h3>Bio</h3>

<p><?= $user['bio'] ?? 'Sin bio' ?></p>

<?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user['id_usuario']): ?>

<button onclick="document.getElementById('editBio').style.display='block'">
Editar bio
</button>

<?php if(!empty($isArtist)): ?>
    <div style="margin-top: 10px;">
        <a href="/LASK/public/index.php/artist/album/create"><button type="button">Publicar álbum</button></a>
        <a href="/LASK/public/index.php/artist/song/create"><button type="button">Publicar canción</button></a>
    </div>
<?php endif; ?>

<form id="editBio" action="/LASK/public/index.php/update_bio" method="POST" style="display:none; margin-top:10px;">

<textarea name="bio" rows="4" cols="50" placeholder="Escribe tu bio"><?= $user['bio'] ?></textarea>

<br><br>

<button type="submit">Guardar</button>

</form>

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

    <?php if($playlist['privacidad_playlist'] == 0): ?>
        (Privada)
    <?php endif; ?>

</div>

<?php endforeach; ?>

<?php endif; ?>

<?php endif; ?>

<a href="/LASK/public">Volver al Home</a>