<a href="<?= BASE_URL ?>" style="display: inline-block; margin-bottom: 10px;">← Volver al inicio</a>

<h1>Mensajes</h1>

<h2>Conversaciones</h2>

<?php if(empty($conversations)): ?>
    <p>No tienes conversaciones</p>
<?php else: ?>

    <?php foreach($conversations as $conv): ?>
        
        <div style="margin-bottom: 10px;">
            <a href="<?= BASE_URL ?>/chat?user=<?= (int)$conv['id_usuario'] ?>">
                <strong><?= htmlspecialchars($conv['username'] ?? $conv['nombre_usuario'] ?? '') ?></strong><br>
                <small><?= htmlspecialchars($conv['ultimo_mensaje'] ?? 'Sin mensajes') ?></small>
            </a>
        </div>

    <?php endforeach; ?>

<?php endif; ?>

<hr>

<h2>Recomendados</h2>
<p style="color: gray; font-size: 0.9em;">Amigos — se siguen mutuamente</p>

<?php if(empty($mutuals)): ?>
    <p>No tienes amigos mutuos por ahora.</p>
<?php else: ?>

    <?php foreach($mutuals as $m): ?>
        
        <div style="margin-bottom: 10px;">
            <a href="<?= BASE_URL ?>/chat?user=<?= (int)$m['id_usuario'] ?>">
                <strong><?= htmlspecialchars($m['nombre_usuario']) ?></strong><br>
                <small style="color: green;">Iniciar conversación</small>
            </a>
        </div>

    <?php endforeach; ?>

<?php endif; ?>