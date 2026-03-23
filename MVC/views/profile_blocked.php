<link rel="stylesheet" href="<?= htmlspecialchars(rtrim(dirname(BASE_URL), '/\\') . '/css/profile_blocked.css') ?>">

<div class="blocked-profile-page">
    <div class="blocked-card">
        <h1><?= htmlspecialchars($user['nombre_usuario']) ?></h1>
        <p class="status-private">Cuenta privada</p>

        <?php if(isset($flashMessage) && $flashMessage): ?>
            <div class="flash-success">
                <?= htmlspecialchars($flashMessage) ?>
            </div>
        <?php endif; ?>

        <?php if(isset($canReport) && $canReport): ?>
            <?php if(isset($showReportedMessage) && $showReportedMessage): ?>
                <p class="report-info">Ya has enviado una denuncia para este usuario. Espera a que sea revisada.</p>
            <?php else: ?>
                <button class="btn" type="button" data-toggle-target="reportForm" onclick="var f=document.getElementById('reportForm'); if(f){f.style.display='block';}">Denunciar</button>

                <form id="reportForm" class="report-form" method="POST" action="<?= htmlspecialchars(BASE_URL . '/report') ?>" data-sql-guard="off" style="display:none;">
                    <?= $csrfField ?>
                    <input type="hidden" name="denunciado_id" value="<?= (int)$user['id_usuario'] ?>">

                    <label>Tipo de denuncia:</label>
                    <select name="motivo_denuncia" required>
                        <option value="">Selecciona un motivo</option>
                        <option value="Contenido inapropiado">Contenido inapropiado</option>
                        <option value="Acoso">Acoso</option>
                        <option value="Spam">Spam</option>
                        <option value="Suplantación de identidad">Suplantación de identidad</option>
                        <option value="Otro">Otro</option>
                    </select>

                    <label>Descripcion de la denuncia:</label>
                    <textarea name="descripcion_denuncia" rows="4" cols="45" placeholder="Describe el motivo de tu denuncia" required></textarea>

                    <div class="form-actions">
                        <button class="btn" type="submit">Enviar denuncia</button>
                        <button class="btn btn-muted" type="button" data-hide-target="reportForm" onclick="var f=document.getElementById('reportForm'); if(f){f.style.display='none';}">Cancelar</button>
                    </div>
                </form>
            <?php endif; ?>
        <?php endif; ?>

        <?php if(isset($canUnblock) && $canUnblock): ?>

            <form method="POST" action="<?= htmlspecialchars(BASE_URL . '/unblock') ?>">
                <?= $csrfField ?>
                <input type="hidden" name="user_id" value="<?= (int)$user['id_usuario'] ?>">
                <button type="submit" class="btn btn-danger">Desbloquear</button>
            </form>

        <?php endif; ?>

        <p class="back-home-wrap">
            <a class="back-home" href="<?= htmlspecialchars(BASE_URL) ?>">← Volver al inicio</a>
        </p>
    </div>
</div>