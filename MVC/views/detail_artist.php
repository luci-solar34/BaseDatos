<?php
$pageTitle = $artist['nombre_artistico'] . ' - LASK';
?>

<h1><?= $artist['nombre_artistico'] ?></h1>
<p>@<?= $artist['nombre_usuario'] ?></p>

<p><strong>Seguidores:</strong> <?= $followers ?></p>

<?php if($artist['bio']): ?>
    <h3>Biografía</h3>
    <p><?= nl2br($artist['bio']) ?></p>
<?php endif; ?>

<?php if($artist['email']): ?>
    <p><strong>Email:</strong> <?= $artist['email'] ?></p>
<?php endif; ?>

<?php if($artist['nombre_pais']): ?>
    <p><strong>País:</strong> <?= $artist['nombre_pais'] ?></p>
<?php endif; ?>

<?php if(!empty($albums)): ?>
    <h2>Álbumes</h2>
    <ul>
    <?php foreach($albums as $album): ?>
        <li>
            <a href="/LASK/public/index.php/album?id=<?= $album['id_album'] ?>">
                <?= $album['nombre_album'] ?>
            </a>
        </li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>

<h2>Canciones</h2>

<?php if(empty($songs)): ?>
    <p>Este artista no tiene canciones</p>
<?php else: ?>
    <ul>
    <?php foreach($songs as $song): ?>
        <li>
            <a href="/LASK/public/index.php/song?id=<?= $song['id_cancion'] ?>">
                <?= $song['nombre_cancion'] ?>
            </a>
            <?php if($song['nombre_album']): ?>
                (álbum: <?= $song['nombre_album'] ?>)
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>

<br>
<a href="/LASK/public">← Volver al inicio</a>