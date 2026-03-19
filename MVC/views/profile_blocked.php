<h1><?= htmlspecialchars($user['nombre_usuario']) ?></h1>

<p style="color:red;">Cuenta privada</p>

<?php if(isset($flashMessage) && $flashMessage): ?>
    <div style="padding:10px; margin:10px 0; border:1px solid green; background:#e6ffe6;">
        <?= $flashMessage ?>
    </div>
<?php endif; ?>

<?php if(isset($canReport) && $canReport): ?>
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

<?php if(isset($canUnblock) && $canUnblock): ?>

    <form method="POST" action="/LASK/public/index.php/unblock">
        <input type="hidden" name="user_id" value="<?= $user['id_usuario'] ?>">
        <button>Desbloquear</button>
    </form>

<?php endif; ?>

<p style="margin-top: 15px;">
    <a href="/LASK/public" style="color:#1DB954; text-decoration:none;">← Volver al inicio</a>
</p>