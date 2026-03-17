<h1>Nuevos Lanzamientos</h1>

<h2>Canciones</h2>

<?php foreach($songs as $song): ?>

<a href="/LASK/public/song?id=<?= $song['id_cancion'] ?>">
<img src="/LASK/<?= $song['portada_cancion'] ?>" width="150">
</a>

<?php endforeach; ?>

<br><br>

<h2>Álbumes</h2>

<?php foreach($albums as $album): ?>

<a href="/LASK/public/album?id=<?= $album['id_album'] ?>">
<img src="/LASK/<?= $album['portada_album'] ?>" width="150">
</a>


<?php endforeach; ?>

<a href="javascript:history.back()">← Volver</a>


<!-- El resto de tu código actual -->