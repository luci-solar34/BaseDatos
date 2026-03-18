<a href="/LASK/public/index.php" style="display: inline-block; margin-bottom: 10px;">← Volver al inicio</a>

<h1>Mensajes</h1>

<h2>Conversaciones</h2>

<?php if(empty($conversations)): ?>
    <p>No tienes conversaciones</p>
<?php else: ?>

    <?php foreach($conversations as $conv): ?>
        
        <div style="margin-bottom: 10px;">
            <a href="/LASK/public/index.php/chat?user=<?= $conv['id_usuario'] ?>">
                <strong><?= htmlspecialchars($conv['username'] ?? $conv['nombre_usuario'] ?? '') ?></strong><br>
                <small><?= htmlspecialchars($conv['ultimo_mensaje'] ?? 'Sin mensajes') ?></small>
            </a>
        </div>

    <?php endforeach; ?>

<?php endif; ?>

<hr>

<h2>...</h2>

<?php if(empty($mutuals)): ?>
    <p>...</p>
<?php else: ?>

    <?php foreach($mutuals as $m): ?>
        
        <div style="margin-bottom: 10px;">
            <a href="/LASK/public/index.php/chat?user=<?= $m['id_usuario'] ?>">
                <strong><?= htmlspecialchars($m['nombre_usuario']) ?></strong><br>
                <small style="color: green;">Iniciar conversación</small>
            </a>
        </div>

    <?php endforeach; ?>

<?php endif; ?>