<h1>Nuevos Lanzamientos</h1>

<h2>Canciones</h2>

<?php foreach($songs as $song): ?>

<a href="<?= BASE_URL ?>/song?id=<?= (int)$song['id_cancion'] ?>">
<img src="/LASK/<?= htmlspecialchars($song['portada_cancion']) ?>" width="150">
</a>

<?php endforeach; ?>

<br><br>

<h2>Álbumes</h2>

<?php foreach($albums as $album): ?>

<a href="<?= BASE_URL ?>/album?id=<?= (int)$album['id_album'] ?>">
<img src="/LASK/<?= htmlspecialchars($album['portada_album']) ?>" width="150">
</a>


<?php endforeach; ?>

<a href="javascript:history.back()">← Volver</a>


<!-- El resto de tu código actual -->