<h1><?= htmlspecialchars($song['nombre_cancion']) ?></h1>

<p><?= htmlspecialchars($song['nombre_artistico']) ?></p>

<img src="/LASK/<?= htmlspecialchars($song['portada_cancion']) ?>" width="200">

<br><br>

<audio controls>
    <source src="/LASK/<?= htmlspecialchars($song['path_link']) ?>" type="audio/mpeg">
</audio>