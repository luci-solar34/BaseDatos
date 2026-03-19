<h1>Usuarios registrados</h1>

<?php if(isset($_SESSION['flash_message'])): ?>
    <div style="padding:10px; margin-bottom:12px; border:1px solid #ccc; background:#f9f9f9;">
        <?= htmlspecialchars($_SESSION['flash_message']) ?>
    </div>
    <?php unset($_SESSION['flash_message']); ?>
<?php endif; ?>

<?php if(!empty($users)): ?>
    <table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse; width:100%;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Nombre</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Fecha creación</th>
                <th>Contraseña</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id_usuario']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['nombre_usuario']) ?></td>
                    <td><?= htmlspecialchars($user['nombre_rol']) ?></td>
                    <td><?= $user['estado_usuario'] ? 'Activo' : 'Inactivo' ?></td>
                    <td><?= htmlspecialchars($user['fecha_creacion']) ?></td>
                    <td>*****</td>
                    <td>
                        <form action="/LASK/public/index.php/admin/user/change-state" method="POST" style="margin:0;">
                            <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['id_usuario']) ?>">
                            <input type="hidden" name="estado" value="<?= $user['estado_usuario'] ? '0' : '1' ?>">
                            <button type="submit"><?= $user['estado_usuario'] ? 'Desactivar' : 'Activar' ?></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No hay usuarios registrados.</p>
<?php endif; ?>

<p><a href="/LASK/public/index.php/profile?id=<?= $_SESSION['user_id'] ?? '' ?>">Volver al perfil</a></p>
