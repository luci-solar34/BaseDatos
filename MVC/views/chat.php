<h1>Chat</h1>

<?php foreach($messages as $msg): ?>

<div>

    <strong>
        <?= $msg['id_emisor'] == $_SESSION['user_id'] ? 'Yo' : 'Ellos' ?>:
    </strong>

    <?= $msg['texto'] ?>

</div>

<?php endforeach; ?>

<hr>

<form method="POST" action="/LASK/public/index.php/message/send">

    <input type="hidden" name="user_id" value="<?= $_GET['user'] ?>">

    <input type="text" name="texto" placeholder="Escribe mensaje">

    <button>Enviar</button>

</form>