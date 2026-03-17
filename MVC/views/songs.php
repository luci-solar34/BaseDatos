<h1><?= $song['nombre_cancion'] ?></h1>

<p><?= $song['nombre_artistico'] ?></p>

<img src="/LASK/<?= $song['portada_cancion'] ?>" width="200">

<br><br>

<audio controls>
    <source src="/LASK/<?= $song['path_link'] ?>" type="audio/mpeg">
</audio>