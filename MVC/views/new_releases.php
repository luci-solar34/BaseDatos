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
            <a href="<?= BASE_URL ?>/song?id=<?= (int)$song['id_cancion'] ?>">
                <?= htmlspecialchars($song['nombre_cancion']) ?>
            </a>
            - <?= htmlspecialchars($song['nombre_artistico']) ?>
            <?php if($song['nombre_album']): ?>
                (álbum: <?= htmlspecialchars($song['nombre_album']) ?>)
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
            <a href="<?= BASE_URL ?>/album?id=<?= (int)$album['id_album'] ?>">
                <?= htmlspecialchars($album['nombre_album']) ?>
            </a>
            - <?= htmlspecialchars($album['nombre_artistico'] ?? 'Artista desconocido') ?>
            (<?= htmlspecialchars($album['fecha_lanzamiento']) ?>)
        </li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>

<br>
<a href="/LASK/public">← Volver al inicio</a>