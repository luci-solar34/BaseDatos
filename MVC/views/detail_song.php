<?php
$pageTitle = $song['nombre_cancion'] . ' - LASK';
?>

<h1><?= $song['nombre_cancion'] ?></h1>

<p>
    <strong>Artista:</strong> 
    <a href="/LASK/public/index.php/artist?id=<?= $song['id_artista'] ?>">
        <?= $song['nombre_artistico'] ?>
    </a>
</p>

<?php if($song['nombre_album']): ?>
    <p>
        <strong>Álbum:</strong> 
        <a href="/LASK/public/index.php/album?id=<?= $song['id_album'] ?>">
            <?= $song['nombre_album'] ?>
        </a>
    </p>
<?php endif; ?>

<audio controls>
    <source src="/LASK/<?= $song['path_link'] ?>" type="audio/mpeg">
</audio>

<form action="/LASK/public/index.php/like" method="POST">
    <input type="hidden" name="song_id" value="<?= $song['id_cancion'] ?>">
    <button type="submit">
        <?= $user_liked ? '♥ Quitar like' : '♥ Dar like' ?> (<?= $likes['total'] ?? 0 ?>)
    </button>
</form>

<?php if($song['letra_cancion']): ?>
    <h3>Letra</h3>
    <pre><?= $song['letra_cancion'] ?></pre>
<?php endif; ?>

<?php if($song['texto_fonetico']): ?>
    <h3>Pronunciación (fonético)</h3>
    <pre><?= $song['texto_fonetico'] ?></pre>
<?php endif; ?>

<br>
<a href="/LASK/public">← Volver al inicio</a>