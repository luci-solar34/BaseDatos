<?php
$pageTitle = $album['nombre_album'] . ' - LASK';
?>

<h1><?= $album['nombre_album'] ?></h1>

<?php if($album['nombre_artistico']): ?>
    <p>
        <strong>Artista:</strong> 
        <a href="/LASK/public/index.php/artist?id=<?= $album['id_usuario'] ?? '' ?>">
            <?= $album['nombre_artistico'] ?>
        </a>
    </p>
<?php endif; ?>

<?php if($album['descripcion_album']): ?>
    <p><strong>Descripción:</strong> <?= $album['descripcion_album'] ?></p>
<?php endif; ?>

<p><strong>Fecha de lanzamiento:</strong> <?= $album['fecha_lanzamiento'] ?></p>

<h2>Canciones</h2>

<?php if(empty($songs)): ?>
    <p>Este álbum no tiene canciones</p>
<?php else: ?>
    <ul>
    <?php foreach($songs as $song): ?>
        <li>
            <a href="/LASK/public/index.php/song?id=<?= $song['id_cancion'] ?>">
                <?= $song['numero_pista'] ?>. <?= $song['nombre_cancion'] ?>
            </a>
        </li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>

<br>
<a href="/LASK/public">← Volver al inicio</a>