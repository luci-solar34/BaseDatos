<?php
$pageTitle = $album['nombre_album'] . ' - LASK';
?>

<h1><?= $album['nombre_album'] ?></h1>

<?php if(!empty($album['nombre_artistico'])): ?>
    <p>
        <strong>Artista:</strong> 
        <a href="/LASK/public/index.php/artist?id=<?= $album['id_artista'] ?? '' ?>">
            <?= $album['nombre_artistico'] ?>
        </a>
    </p>
<?php endif; ?>

<?php if($album['descripcion_album']): ?>
    <p><strong>Descripción:</strong> <?= $album['descripcion_album'] ?></p>
<?php endif; ?>

<p><strong>Fecha de lanzamiento:</strong> <?= $album['fecha_lanzamiento'] ?></p>

<?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $album['id_artista']): ?>
    <a href="/LASK/public/index.php/artist/edit-album?id=<?= $album['id_album'] ?>">
        <button>Editar Álbum</button>
    </a>
<?php endif; ?>

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
            <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $album['id_artista']): ?>
                <a href="/LASK/public/index.php/artist/edit-song?id=<?= $song['id_cancion'] ?>">Editar canción</a>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>

<br>
<a href="/LASK/public">← Volver al inicio</a>