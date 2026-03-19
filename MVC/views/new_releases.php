<?php
$pageTitle = 'Nuevos Lanzamientos - LASK';
?>

<h1>Todos los lanzamientos</h1>

<h2>Canciones de nuestros artistas</h2>

<?php if(empty($songs)): ?>
    <p>No hay nuevas canciones</p>
<?php else: ?>
    <ul>
    <?php foreach($songs as $song): ?>
        <li>
            <a href="/LASK/public/index.php/song?id=<?= $song['id_cancion'] ?>">
                <?= $song['nombre_cancion'] ?>
            </a>
            - <?= $song['nombre_artistico'] ?>
            <?php if($song['nombre_album']): ?>
                (álbum: <?= $song['nombre_album'] ?>)
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>

<h2>Albumes de nuestros artistas</h2>

<?php if(empty($albums)): ?>
    <p>No hay nuevos álbumes</p>
<?php else: ?>
    <ul>
    <?php foreach($albums as $album): ?>
        <li>
            <a href="/LASK/public/index.php/album?id=<?= $album['id_album'] ?>">
                <?= $album['nombre_album'] ?>
            </a>
            - <?= $album['nombre_artistico'] ?? 'Artista desconocido' ?>
            (<?= $album['fecha_lanzamiento'] ?>)
        </li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>

<br>
<a href="/LASK/public">← Volver al inicio</a>