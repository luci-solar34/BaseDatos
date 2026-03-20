<a href="<?= BASE_URL ?>/messages" style="display: inline-block; margin-bottom: 10px;">← Volver a mensajes</a>

<h1>Chat con <?= htmlspecialchars($chatPartnerName) ?></h1>

<?php if(empty($messages)): ?>
    <p>No hay mensajes aún. Envía el primero.</p>
<?php else: ?>
    <?php foreach($messages as $msg): ?>

    <div style="margin-bottom: 10px;">
        <strong>
            <?= htmlspecialchars($msg['sender_label']) ?>:
        </strong>
        <?= htmlspecialchars($msg['texto']) ?>
        <br>
        <small style="color:#666;"><?= htmlspecialchars($msg['fecha_mensaje']) ?></small>
    </div>

    <?php endforeach; ?>
<?php endif; ?>

<hr>

<form method="POST" action="<?= htmlspecialchars(BASE_URL . '/message/send') ?>">

    <input type="hidden" name="user_id" value="<?= (int)$chatRecipientId ?>">

    <input type="text" name="texto" placeholder="Escribe mensaje" required>

    <button>Enviar</button>

</form>