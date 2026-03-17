<h1>Agregar canciones</h1>

<?php foreach($songs as $song): ?>

<div>

    <?= $song['nombre_cancion'] ?>

    <form method="POST" action="/LASK/public/index.php/playlist/add-song">

        <input type="hidden" name="playlist" value="<?= $playlist ?>">
        <input type="hidden" name="song" value="<?= $song['id_cancion'] ?>">

        <button>Agregar</button>

    </form>

</div>

<?php endforeach; ?>