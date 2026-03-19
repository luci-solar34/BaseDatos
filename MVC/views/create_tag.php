<h1>Crear Tag</h1>

<?php if(isset($flashMessage) && $flashMessage): ?>
    <div style="padding:10px; margin:10px 0; border:1px solid orange; background:#fff8e6;">
        <?= htmlspecialchars($flashMessage) ?>
    </div>
<?php endif; ?>

<form action="/LASK/public/index.php/tag/create" method="POST">
    <label for="nombre_tag">Nombre del tag:</label><br>
    <input type="text" id="nombre_tag" name="nombre_tag" required maxlength="100"><br><br>

    <label for="descripcion_tag">Descripción (opcional):</label><br>
    <textarea id="descripcion_tag" name="descripcion_tag" rows="4" cols="50" maxlength="500"></textarea><br><br>

    <button type="submit">Crear tag</button>
</form>

<?php if(!empty($tags)): ?>
    <h2>Tags existentes</h2>
    <table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse; margin-bottom:20px;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($tags as $tag): ?>
                <tr>
                    <td><?= htmlspecialchars($tag['id_tag']) ?></td>
                    <td><?= htmlspecialchars($tag['nombre_tag']) ?></td>
                    <td><?= htmlspecialchars($tag['descripcion_tag']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No hay tags creados aún.</p>
<?php endif; ?>

<?php
$profileUrl = '/LASK/public/index.php/profile?id=' . ($_SESSION['user_id'] ?? '');
?>
<p><a href="<?= htmlspecialchars($profileUrl) ?>">Volver al perfil</a></p>
