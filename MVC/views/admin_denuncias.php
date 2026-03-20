<?php
$pageTitle = 'Gestión de Denuncias - LASK';
?>

<h1>Gestión de Denuncias</h1>

<?php if($flashMessage): ?>
    <p style="padding:8px; border-radius:6px; background:#f4f4f4;">
        <?= htmlspecialchars($flashMessage) ?>
    </p>
<?php endif; ?>

<?php if(empty($denuncias)): ?>
    <p>No hay denuncias registradas.</p>
<?php else: ?>
    <table border="1" cellpadding="8" cellspacing="0" style="border-collapse:collapse; width:100%;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Denunciante</th>
                <th>Denunciado</th>
                <th>Motivo</th>
                <th>Descripcion</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($denuncias as $d): ?>
                <tr>
                    <td><?= (int)($d['id_denuncia'] ?? 0) ?></td>
                    <td><?= htmlspecialchars($d['denunciante'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($d['denunciado'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($d['id_motivo_denuncia'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($d['descripcion_denuncia'] ?? '') ?></td>
                    <td><?= htmlspecialchars($d['estado'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($d['fecha_denuncia'] ?? '') ?></td>
                    <td>
                        <form action="<?= BASE_URL ?>/admin/denuncias/accept" method="POST" style="display:inline;">
                            <input type="hidden" name="denuncia" value="<?= (int)($d['id_denuncia'] ?? 0) ?>">
                            <button type="submit">Aceptar</button>
                        </form>
                        <form action="<?= BASE_URL ?>/admin/denuncias/reject" method="POST" style="display:inline; margin-left:6px;">
                            <input type="hidden" name="denuncia" value="<?= (int)($d['id_denuncia'] ?? 0) ?>">
                            <button type="submit">Rechazar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<br>
<a href="<?= BASE_URL ?>/admin/users">Ir a gestión de usuarios</a>
