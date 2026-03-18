<h1>Resultados de búsqueda</h1>

<?php if(empty($results)): ?>

<p>No se encontraron resultados para "<?= htmlspecialchars($_GET['q']) ?>"</p>

<?php else: ?>

<ul>

<?php foreach($results as $result): ?>

    <li>

        <?php if($result['tipo'] == 'cancion'): ?>

            <a href="/LASK/public/index.php/song?id=<?= $result['id'] ?>">

                <?= htmlspecialchars($result['resultado']) ?> (Canción)

            </a>

        <?php elseif($result['tipo'] == 'artista'): ?>

            <a href="/LASK/public/index.php/artist?id=<?= $result['id'] ?>">

                <?= htmlspecialchars($result['resultado']) ?> (Artista)

            </a>

        <?php elseif($result['tipo'] == 'album'): ?>

            <a href="/LASK/public/index.php/album?id=<?= $result['id'] ?>">

                <?= htmlspecialchars($result['resultado']) ?> (Álbum)

            </a>

        <?php elseif($result['tipo'] == 'usuario'): ?>

            <a href="/LASK/public/index.php/profile?id=<?= $result['id'] ?>">

                <?= htmlspecialchars($result['resultado']) ?> (Usuario)

            </a>

        <?php elseif($result['tipo'] == 'tag'): ?>

            <a href="/LASK/public/index.php/tag?id=<?= $result['id'] ?>">

                <?= htmlspecialchars($result['resultado']) ?> (Tag)

            </a>

        <?php endif; ?>

    </li>

<?php endforeach; ?>

</ul>

<?php endif; ?>

<a href="/LASK/public">Volver al Home</a></content>
<parameter name="filePath">c:\laragon\www\lask\MVC\views\search.php