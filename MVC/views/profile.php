<link rel="stylesheet" href="/LASK/public/css/profile.css">

<div class="profile-container">

    <!-- TOP BAR -->
    <div class="top-bar">
        <a href="/LASK/public">
           <img src="/LASK/public/img/logo.png" class="logo">
        </a>

        <div class="top-buttons">
            <a href="/LASK/public/index.php/logout">
                <button>Cerrar Sesión</button>
            </a>
        </div>
    </div>

    <!-- HEADER PERFIL -->
    <div class="profile-header">

        <!-- FOTO + STATS -->
        <div class="pfp-section">

            <form action="/LASK/public/index.php/update_pfp" method="POST" enctype="multipart/form-data">
                <label for="pfp">
                    <img src="/LASK/<?= $user['pfp'] ?>" class="pfp">
                </label>
                <input type="file" name="pfp" id="pfp" hidden onchange="this.form.submit()">
            </form>

            <div class="stats">
                <a href="/LASK/public/index.php/profile/following?id=<?= $user['id_usuario'] ?>" class="stat-box">
                    <span class="stat-number"><?= $following ?></span>
                    <span class="stat-label">Siguiendo</span>
                </a>

                <a href="/LASK/public/index.php/profile/followers?id=<?= $user['id_usuario'] ?>" class="stat-box">
                    <span class="stat-number"><?= $followers ?></span>
                    <span class="stat-label">Seguidores</span>
                </a>
            </div>

        </div>

        <!-- INFO -->
        <div class="profile-info">
            <h2><?= $user['nombre_usuario'] ?></h2>
            <p class="email"><?= $user['email'] ?></p>
            <p class="role">Listener</p>
        </div>

        <!-- BOTONES DERECHA -->
        <div class="profile-actions">

            <!-- Botón seguir / dejar de seguir -->
            <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != $user['id_usuario']): ?>
                <form method="POST" action="/LASK/public/index.php">
                    <input type="hidden" name="route" value="<?= $isFollowing ? 'unfollow' : 'follow' ?>">
                    <input type="hidden" name="user_id" value="<?= $user['id_usuario'] ?>">
                    <input type="hidden" name="redirect_id" value="<?= $user['id_usuario'] ?>">
                    <button><?= $isFollowing ? 'Dejar de seguir' : 'Seguir' ?></button>
                </form>

                <!-- Bloquear / desbloquear -->
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

                <!-- Denunciar -->
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

            <?php endif; ?>

            <!-- Crear playlist -->
            <a href="/LASK/public/index.php/playlist/create">
                <button>Crear playlist</button>
            </a>

        </div>
    </div>

    <!-- CONTENIDO -->
    <div class="profile-content">

        <!-- BIO -->
        <div class="bio-box">
            <h3>Descripción</h3>
            <p><?= $user['bio'] ?? 'Sin bio' ?></p>

            <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user['id_usuario']): ?>
                <button onclick="document.getElementById('editBio').style.display='block'">Editar</button>

                <form id="editBio" action="/LASK/public/index.php/update_bio" method="POST" style="display:none;">
                    <textarea name="bio"><?= $user['bio'] ?></textarea>
                    <button type="submit">Guardar</button>
                </form>
            <?php endif; ?>
        </div>

        <!-- PLAYLISTS -->
        <div class="playlists-box">

            <h3>Playlists públicas</h3>

            <div class="playlist-row">
                <?php foreach($playlists as $playlist): ?>
                    <?php if($playlist['privacidad_playlist'] == 0): ?>
                        <a href="/LASK/public/index.php/playlist?id=<?= $playlist['id_playlist'] ?>">
                            <div class="playlist-card">
                                <?= $playlist['nombre_playlist'] ?>
                            </div>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <h3>Playlists privadas</h3>

            <div class="playlist-row">
                <?php foreach($playlists as $playlist): ?>
                    <?php if($playlist['privacidad_playlist'] == 1): ?>
                        <div class="playlist-card private">
                            <?= $playlist['nombre_playlist'] ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <!-- Administrador: crear nuevo tag -->
            <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 1 && $_SESSION['user_id'] == $user['id_usuario']): ?>
                <p>
                    <a href="/LASK/public/index.php/tag/create">Crear nuevo tag</a>
                </p>
            <?php endif; ?>

        </div>

    </div>

</div>