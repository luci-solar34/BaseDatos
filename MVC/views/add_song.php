<h1>Agregar canciones</h1>

<?php foreach($songs as $song): ?>

<div>

    <?= htmlspecialchars($song['nombre_cancion']) ?>

    <form method="POST" action="<?= htmlspecialchars(BASE_URL . '/playlist/add-song') ?>">

        <input type="hidden" name="playlist" value="<?= (int)$playlist ?>">
        <input type="hidden" name="song" value="<?= (int)$song['id_cancion'] ?>">

        <button>Agregar</button>

    </form>

</div>

<?php endforeach; ?>