<h1>Mi Playlist</h1>

<a href="/LASK/public/index.php/playlist/add-song?playlist=<?= $playlist_id ?>">
    <button>Agregar canciones</button>
</a>

<h2>Canciones</h2>

<?php foreach($songs as $song): ?>

<div>
    <?= $song['nombre_cancion'] ?>
</div>

<a href="/LASK/public/index.php/profile?id=<?= $_SESSION['user_id'] ?>">
    <button>Finalizar</button>
</a>

<?php endforeach; ?>