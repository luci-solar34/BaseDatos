<link rel="stylesheet" href="/LASK/public/css/profile.css?v=<?= time() ?>">

<div class="profile-container">

    <!-- HEADER -->
    <div class="profile-header">

        <!-- FOTO -->
        <div class="pfp-section">

            <?php if($isOwner): ?>
                <form action="<?= htmlspecialchars(BASE_URL . '/update_pfp') ?>" method="POST" enctype="multipart/form-data" id="pfpForm">
                    <label for="pfp">
                        <img id="pfpPreviewImg" src="/LASK/<?= htmlspecialchars($user['pfp']) ?>" class="pfp">
                    </label>
                    <input type="file" name="pfp" id="pfp" hidden onchange="this.form.submit()">
                </form>
            <?php else: ?>
                <img src="/LASK/<?= htmlspecialchars($user['pfp']) ?>" class="pfp">
            <?php endif; ?>

            <!-- STATS -->
            <div class="stats">
                <a href="<?= BASE_URL ?>/profile/following?id=<?= (int)$user['id_usuario'] ?>" class="stat-box">
                    <span class="stat-number"><?= (int)$following ?></span>
                    <span class="stat-label">Siguiendo</span>
                </a>

                <a href="<?= BASE_URL ?>/profile/followers?id=<?= (int)$user['id_usuario'] ?>" class="stat-box">
                    <span class="stat-number"><?= (int)$followers ?></span>
                    <span class="stat-label">Seguidores</span>
                </a>
            </div>
        </div>

        <!-- INFO -->
        <div class="profile-info">
            <h2><?= htmlspecialchars($user['nombre_usuario']) ?></h2>
            <p><?= htmlspecialchars($user['email']) ?></p>
        </div>

        <!-- ACCIONES -->
        <div class="profile-actions">

            <?php if($isOwner): ?>
                <a href="<?= htmlspecialchars(BASE_URL . '/logout') ?>">
                    <button>Cerrar sesión</button>
                </a>
            <?php endif; ?>

            <?php if($canInteract): ?>

                <!-- FOLLOW -->
                <form method="POST" action="<?= htmlspecialchars(BASE_URL . '/' . ($isFollowing ? 'unfollow' : 'follow')) ?>">
                    <input type="hidden" name="user_id" value="<?= (int)$user['id_usuario'] ?>">
                    <input type="hidden" name="redirect_id" value="<?= (int)$user['id_usuario'] ?>">
                    <button>
                        <?= htmlspecialchars($isFollowing ? 'Dejar de seguir' : 'Seguir') ?>
                    </button>
                </form>

                <!-- BLOCK -->
                <?php if(isset($hasBlocked) && $hasBlocked): ?>
                    <form method="POST" action="<?= htmlspecialchars(BASE_URL . '/unblock') ?>">
                        <input type="hidden" name="user_id" value="<?= (int)$user['id_usuario'] ?>">
                        <button style="background:green;color:white;">Desbloquear</button>
                    </form>
                <?php else: ?>
                    <form method="POST" action="<?= htmlspecialchars(BASE_URL . '/block') ?>">
                        <input type="hidden" name="user_id" value="<?= (int)$user['id_usuario'] ?>">
                        <button style="background:red;color:white;">Bloquear</button>
                    </form>
                <?php endif; ?>

            <?php endif; ?>

        </div>

    </div>

    <!-- FLASH -->
    <?php if(isset($flashMessage) && $flashMessage): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($flashMessage) ?>
        </div>
    <?php endif; ?>

    <!-- REPORTES (AQUÍ ESTABA LO QUE FALTABA 🔥) -->
    <div class="report-section">

        <?php if(isset($canReport) && $canReport): ?>

            <?php if(isset($hasReported) && $hasReported): ?>
                <p style="color: red;">
                    Ya has enviado una denuncia para este usuario.
                </p>
            <?php else: ?>

                <button onclick="document.getElementById('reportForm').style.display='block'">
                    Denunciar
                </button>

                <form id="reportForm"
                      method="POST"
                      action="<?= htmlspecialchars(BASE_URL . '/report') ?>"
                      style="display:none; margin-top:10px;">

                    <input type="hidden" name="denunciado_id" value="<?= (int)$user['id_usuario'] ?>">

                    <label>Motivo</label>
                    <select name="motivo_denuncia" required>
                        <option value="">Selecciona</option>
                        <option value="Contenido inapropiado">Contenido inapropiado</option>
                        <option value="Acoso">Acoso</option>
                        <option value="Spam">Spam</option>
                        <option value="Suplantación de identidad">Suplantación</option>
                        <option value="Otro">Otro</option>
                    </select>

                    <textarea name="descripcion_denuncia" required></textarea>

                    <button type="submit">Enviar</button>
                    <button type="button" onclick="this.parentElement.style.display='none'">Cancelar</button>

                </form>

            <?php endif; ?>

        <?php elseif($reportUnavailableMessage): ?>
            <p style="color:red;"><?= htmlspecialchars($reportUnavailableMessage) ?></p>
        <?php endif; ?>

    </div>

    <!-- CONTENIDO -->
    <div class="profile-content">

        <!-- BIO -->
        <div class="bio-box">
            <h3>Bio</h3>
            <p><?= htmlspecialchars($bioText) ?></p>

            <?php if($isOwner): ?>
                <button onclick="document.getElementById('editBio').style.display='block'">Editar</button>

                <form id="editBio" action="<?= htmlspecialchars(BASE_URL . '/update_bio') ?>" method="POST" style="display:none;">
                    <textarea name="bio"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                    <button type="submit">Guardar</button>
                </form>

                <a href="<?= htmlspecialchars(BASE_URL . '/playlist/create') ?>">
                    <button>Crear Playlist</button>
                </a>
            <?php endif; ?>
        </div>

        <!-- PLAYLISTS -->
        <div class="playlists-box">

            <h3><?= $isOwner ? 'Mis playlists' : 'Playlists' ?></h3>

            <div class="playlist-row">
                <?php if(empty($playlists)): ?>
                    <p>No hay playlists</p>
                <?php else: ?>
                    <?php foreach($playlists as $playlist): ?>
                        <a class="media-card" href="<?= htmlspecialchars(BASE_URL . '/playlist?id=' . (int)$playlist['id_playlist']) ?>">
                            <div class="playlist-folder-icon" aria-hidden="true"></div>
                            <span><?= htmlspecialchars($playlist['nombre_playlist']) ?></span>
                            <?php if($playlist['privacy_label']): ?>
                                <small><?= htmlspecialchars($playlist['privacy_label']) ?></small>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        </div>

    </div>

</div>