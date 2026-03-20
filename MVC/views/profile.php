<link rel="stylesheet" href="/LASK/public/css/profile.css">

<div class="profile-container">

    <!-- TOP BAR -->
    <div class="top-bar">
        <a href="<?= htmlspecialchars(BASE_URL) ?>">
            <img src="/LASK/public/img/logo.png" class="logo">
        </a>

        <div class="top-buttons">
            <?php if($isOwner): ?>
                <a href="<?= htmlspecialchars(BASE_URL . '/logout') ?>">
                    <button>Cerrar Sesión</button>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- HEADER PERFIL -->
    <div class="profile-header">

        <!-- FOTO + STATS -->
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
            <p class="email"><?= htmlspecialchars($user['email']) ?></p>
        </div>

        <!-- ACCIONES -->
        <div class="profile-actions">

            <?php if($canInteract): ?>
                <form method="POST" action="<?= htmlspecialchars(BASE_URL) ?>">
                    <input type="hidden" name="route" value="<?= htmlspecialchars($isFollowing ? 'unfollow' : 'follow') ?>">
                    <input type="hidden" name="user_id" value="<?= (int)$user['id_usuario'] ?>">
                    <input type="hidden" name="redirect_id" value="<?= (int)$user['id_usuario'] ?>">
                    <button>
                        <?= htmlspecialchars($isFollowing ? 'Dejar de seguir' : 'Seguir') ?>
                    </button>
                </form>

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

    <!-- FLASH MESSAGE -->
    <?php if(isset($flashMessage) && $flashMessage): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($flashMessage) ?>
        </div>
    <?php endif; ?>

    <!-- CONTENIDO -->
    <div class="profile-content">

        <!-- BIO -->
        <div class="bio-box">
            <h3>Descripción</h3>
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

            <h3><?= $isOwner ? 'Mis playlists' : 'Playlists públicas' ?></h3>

            <div class="playlist-row">
                <?php if(empty($playlists)): ?>
                    <p>No hay playlists</p>
                <?php else: ?>
                    <?php foreach($playlists as $playlist): ?>
                        <a href="<?= htmlspecialchars(BASE_URL . '/playlist?id=' . (int)$playlist['id_playlist']) ?>">
                            <div class="playlist-card">
                                <?= htmlspecialchars($playlist['nombre_playlist']) ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        </div>

    </div>

</div>