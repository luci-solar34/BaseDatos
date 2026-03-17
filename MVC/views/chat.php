<a href="/LASK/public/index.php/messages" style="display: inline-block; margin-bottom: 10px;">← Volver a mensajes</a>

<h1>Chat con <?= htmlspecialchars($otherUser['nombre_usuario'] ?? 'usuario') ?></h1>

<?php if(empty($messages)): ?>
    <p>No hay mensajes aún. Envía el primero.</p>
<?php else: ?>
    <?php foreach($messages as $msg): ?>

    <div style="margin-bottom: 10px;">
        <strong>
            <?= $msg['id_emisor'] == $_SESSION['user_id'] ? 'Yo' : htmlspecialchars($otherUser['nombre_usuario']) ?>:
        </strong>
        <?= htmlspecialchars($msg['texto']) ?>
        <br>
        <small style="color:#666;"><?= $msg['fecha_mensaje'] ?></small>
    </div>

    <?php endforeach; ?>
<?php endif; ?>

<hr>

<form method="POST" action="/LASK/public/index.php/message/send">

    <input type="hidden" name="user_id" value="<?= htmlspecialchars($_GET['user']) ?>">

    <input type="text" name="texto" placeholder="Escribe mensaje" required>

    <button>Enviar</button>

</form>