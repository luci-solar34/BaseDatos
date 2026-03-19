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

<?php if(isset($flashMessage) && $flashMessage): ?>
    <div style="padding:10px; margin:10px 0; border:1px solid green; background:#e6ffe6;">
        <?= $flashMessage ?>
    </div>
<?php endif; ?>

<?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != $user['id_usuario']): ?>
    <form method="POST" action="/LASK/public/index.php">
    <input type="hidden" name="route" value="<?= $isFollowing ? 'unfollow' : 'follow' ?>">
    <input type="hidden" name="user_id" value="<?= $user['id_usuario'] ?>">
    <input type="hidden" name="redirect_id" value="<?= $user['id_usuario'] ?>">
    <button type="submit">
        <?= $isFollowing ? 'Dejar de seguir' : 'Seguir' ?>
    </button>
</form>

    <?php if(isset($hasBlocked) && $hasBlocked): ?>
        <form method="POST" action="/LASK/public/index.php/unblock" style="margin-top:10px;">
            <input type="hidden" name="user_id" value="<?= $user['id_usuario'] ?>">
            <button style="background:green;color:white;">Desbloquear</button>
        </form>
    <?php else: ?>
        <form method="POST" action="/LASK/public/index.php/block" style="margin-top:10px;">
            <input type="hidden" name="user_id" value="<?= $user['id_usuario'] ?>">
            <button style="background:red;color:white;">Bloquear</button>
        </form>
    <?php endif; ?>
<?php endif; ?>

<?php if(isset($canReport) && $canReport): ?>
    <?php if(isset($hasReported) && $hasReported): ?>
        <p style="color: #a00;">Ya has enviado una denuncia para este usuario. Espera a que sea revisada.</p>
    <?php else: ?>
        <button onclick="document.getElementById('reportForm').style.display='block'">Denunciar</button>

        <form id="reportForm" method="POST" action="/LASK/public/index.php/report" style="display:none; margin-top:15px; border:1px solid #ccc; padding: 12px;">
            <input type="hidden" name="denunciado_id" value="<?= $user['id_usuario'] ?>">

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
    <?php endif; ?>
<?php elseif(isset($_SESSION['user_id']) && $_SESSION['user_id'] != $user['id_usuario']): ?>
    <p style="color: #a00;">No puedes denunciar a administradores ni usar esta función mientras estás en modo administrador.</p>
<?php endif; ?>

<a href="/LASK/public/index.php/profile/followers?id=<?= $user['id_usuario'] ?>"><?= $followers ?> Seguidores</a>
<a href="/LASK/public/index.php/profile/following?id=<?= $user['id_usuario'] ?>"><?= $following ?> Siguiendo</a>


<h3>Bio</h3>

<p><?= $user['bio'] ?? 'Sin bio' ?></p>

<?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user['id_usuario']): ?>

<button onclick="document.getElementById('editBio').style.display='block'">
Editar bio
</button>

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

    <?php if($playlist['privacidad_playlist'] == 1): ?>
        (Privada)
    <?php endif; ?>

</div>

<?php endforeach; ?>

<?php endif; ?>

<?php endif; ?>

<a href="/LASK/public">Volver al Home</a>