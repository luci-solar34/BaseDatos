<h1><?= htmlspecialchars($user['nombre_usuario']) ?></h1>

<p style="color:red;">Cuenta privada</p>

<?php if(isset($flashMessage) && $flashMessage): ?>
    <div style="padding:10px; margin:10px 0; border:1px solid green; background:#e6ffe6;">
        <?= htmlspecialchars($flashMessage) ?>
    </div>
<?php endif; ?>

<?php if(isset($canReport) && $canReport): ?>
    <?php if(isset($showReportedMessage) && $showReportedMessage): ?>
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
<?php endif; ?>

<?php if(isset($canUnblock) && $canUnblock): ?>

    <form method="POST" action="<?= htmlspecialchars(BASE_URL . '/unblock') ?>">
        <input type="hidden" name="user_id" value="<?= (int)$user['id_usuario'] ?>">
        <button type="submit" class="btn">Desbloquear</button>
    </form>

<?php endif; ?>

<p style="margin-top: 15px;">
    <a href="<?= htmlspecialchars(BASE_URL) ?>" style="color:#1DB954; text-decoration:none;">← Volver al inicio</a>
</p>