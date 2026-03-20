<h1>Perfil</h1>

<?php if($isOwner): ?>
<a href="<?= htmlspecialchars(BASE_URL . '/logout') ?>">Cerrar sesión</a>
<p>Haz click en la imagen para cambiar tu foto</p>

<form action="<?= htmlspecialchars(BASE_URL . '/update_pfp') ?>" method="POST" enctype="multipart/form-data" id="pfpForm">

<label for="pfp" style="cursor:pointer;">

<img id="pfpPreviewImg" src="/LASK/<?= htmlspecialchars($user['pfp']) ?>" alt="Tu foto de perfil" width="120" style="border-radius:50%;">

</label>

<input type="file" name="pfp" id="pfp" style="display:none"
    data-auto-submit-target="pfpForm"
    data-preview-img="pfpPreviewImg"
    onchange="this.form.submit()">

</form>

<?php else: ?>
<img src="/LASK/<?= htmlspecialchars($user['pfp']) ?>" alt="Foto de perfil" width="120" style="border-radius:50%;">
<?php endif; ?>

<p>Usuario: <?= htmlspecialchars($user['nombre_usuario']) ?></p>

<p>Email: <?= htmlspecialchars($user['email']) ?></p>

<?php if(isset($flashMessage) && $flashMessage): ?>
    <div style="padding:10px; margin:10px 0; border:1px solid green; background:#e6ffe6;">
        <?= htmlspecialchars($flashMessage) ?>
    </div>
<?php endif; ?>

<?php if($canInteract): ?>
    <form method="POST" action="<?= htmlspecialchars(BASE_URL) ?>">
    <input type="hidden" name="route" value="<?= htmlspecialchars($isFollowing ? 'unfollow' : 'follow') ?>">
    <input type="hidden" name="user_id" value="<?= (int)$user['id_usuario'] ?>">
    <input type="hidden" name="redirect_id" value="<?= (int)$user['id_usuario'] ?>">
    <button type="submit" class="btn">
        <?= htmlspecialchars($isFollowing ? 'Dejar de seguir' : 'Seguir') ?>
    </button>
</form>

    <?php if(isset($hasBlocked) && $hasBlocked): ?>
        <form method="POST" action="<?= htmlspecialchars(BASE_URL . '/unblock') ?>" style="margin-top:10px;">
            <input type="hidden" name="user_id" value="<?= (int)$user['id_usuario'] ?>">
            <button type="submit" class="btn" style="background:green;color:white;">Desbloquear</button>
        </form>
    <?php else: ?>
        <form method="POST" action="<?= htmlspecialchars(BASE_URL . '/block') ?>" style="margin-top:10px;">
            <input type="hidden" name="user_id" value="<?= (int)$user['id_usuario'] ?>">
            <button type="submit" class="btn btn-danger" style="background:red;color:white;">Bloquear</button>
        </form>
    <?php endif; ?>
<?php endif; ?>

<?php if(isset($canReport) && $canReport): ?>
    <?php if(isset($hasReported) && $hasReported): ?>
        <p style="color: #a00;">Ya has enviado una denuncia para este usuario. Espera a que sea revisada.</p>
    <?php else: ?>
        <button type="button" data-toggle-target="reportForm" onclick="var f=document.getElementById('reportForm'); if(f){f.style.display='block';}">Denunciar</button>

        <form id="reportForm" method="POST" action="<?= htmlspecialchars(BASE_URL . '/report') ?>" data-sql-guard="off" style="display:none; margin-top:15px; border:1px solid #ccc; padding: 12px;">
            <input type="hidden" name="denunciado_id" value="<?= (int)$user['id_usuario'] ?>">

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
<?php elseif($reportUnavailableMessage): ?>
    <p style="color: #a00;"><?= htmlspecialchars($reportUnavailableMessage) ?></p>
<?php endif; ?>

<a href="<?= BASE_URL ?>/profile/followers?id=<?= (int)$user['id_usuario'] ?>"><?= (int)$followers ?> Seguidores</a>
<a href="<?= BASE_URL ?>/profile/following?id=<?= (int)$user['id_usuario'] ?>"><?= (int)$following ?> Siguiendo</a>


<h3>Bio</h3>

<p><?= htmlspecialchars($bioText) ?></p>

<?php if($isOwner): ?>

<button type="button" data-toggle-target="editBio" onclick="var f=document.getElementById('editBio'); if(f){f.style.display='block';}">
Editar bio
</button>

<form id="editBio" action="<?= htmlspecialchars(BASE_URL . '/update_bio') ?>" method="POST" data-sql-guard="off" style="display:none; margin-top:10px;">

<textarea name="bio" rows="4" cols="50" placeholder="Escribe tu bio"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>

<br><br>

<button type="submit">Guardar</button>

</form>

<a href="<?= htmlspecialchars(BASE_URL . '/playlist/create') ?>" class="btn">Crear Playlist</a>

<?php if($showAdminActions): ?>
    <p>
        <a href="<?= htmlspecialchars(BASE_URL . '/tag/create') ?>">Crear nuevo tag</a>
    </p>
    <p>
        <a href="<?= htmlspecialchars(BASE_URL . '/admin/users') ?>">Ver todos los usuarios</a>
    </p>
<?php endif; ?>

<?php endif; ?>

<h2><?= $isOwner ? 'Mis playlists' : 'Playlists públicas' ?></h2>

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

<a href="<?= htmlspecialchars(BASE_URL) ?>">Volver al Home</a>