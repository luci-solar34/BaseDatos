<?php
$pageTitle = $tag['nombre_tag'] . ' - LASK';
?>

<h1>#<?= htmlspecialchars($tag['nombre_tag']) ?></h1>

<?php if(!empty($tag['descripcion_tag'])): ?>
    <p><?= nl2br(htmlspecialchars($tag['descripcion_tag'])) ?></p>
<?php else: ?>
    <p>Este tag todavía no tiene descripción.</p>
<?php endif; ?>

<p><a href="/LASK/public/index.php/tags">Explorar más tags</a></p>

<h2>Canciones con esta vibe</h2>

<?php if(empty($songs)): ?>
    <p>Todavía no hay canciones asociadas a este tag.</p>
<?php else: ?>
    <ul>
        <?php foreach($songs as $song): ?>
            <li>
                <a href="/LASK/public/index.php/song?id=<?= $song['id_cancion'] ?>">
                    <?= htmlspecialchars($song['nombre_cancion']) ?>
                </a>
                por
                <a href="/LASK/public/index.php/artist?id=<?= $song['id_artista'] ?>">
                    <?= htmlspecialchars($song['nombre_artistico']) ?>
                </a>
                <?php if(!empty($song['nombre_album'])): ?>
                    (álbum: <a href="/LASK/public/index.php/album?id=<?= $song['id_album'] ?>"><?= htmlspecialchars($song['nombre_album']) ?></a>)
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<a href="/LASK/public/index.php/">Volver al home</a>