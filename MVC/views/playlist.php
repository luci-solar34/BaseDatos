<h1>Mi Playlist</h1>

<a href="/LASK/public/index.php/playlist/add-song?playlist=<?= $playlist_id ?>">
    <button>Agregar canciones</button>
</a>

<a href="/LASK/public/index.php/playlist/edit?id=<?= $playlist_id ?>">
    <button>Editar Playlist</button>
</a>

<h2>Canciones</h2>

<?php foreach($songs as $song): ?>

<div>
    <?= $song['nombre_cancion'] ?>
    <audio controls>
        <source src="/LASK/<?= $song['path_link'] ?>" type="audio/mpeg">
    </audio>
</div>

<?php endforeach; ?>

<a href="/LASK/public/index.php/profile?id=<?= $_SESSION['user_id'] ?>">
    <button>Finalizar</button>
</a>