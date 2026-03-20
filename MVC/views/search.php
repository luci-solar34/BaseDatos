<h1>Resultados de búsqueda</h1>

<?php if(empty($results)): ?>

<p>No se encontraron resultados para "<?= htmlspecialchars($query ?? '') ?>"</p>

<?php else: ?>

<ul>

<?php foreach($results as $result): ?>

    <li>

        <?php if($result['tipo'] == 'cancion'): ?>

            <a href="<?= BASE_URL ?>/song?id=<?= (int)$result['id'] ?>">

                <?= htmlspecialchars($result['resultado']) ?> (Canción)

            </a>

        <?php elseif($result['tipo'] == 'artista'): ?>

            <a href="<?= BASE_URL ?>/artist?id=<?= (int)$result['id'] ?>">

                <?= htmlspecialchars($result['resultado']) ?> (Artista)

            </a>

        <?php elseif($result['tipo'] == 'album'): ?>

            <a href="<?= BASE_URL ?>/album?id=<?= (int)$result['id'] ?>">

                <?= htmlspecialchars($result['resultado']) ?> (Álbum)

            </a>

        <?php elseif($result['tipo'] == 'usuario'): ?>

            <a href="<?= BASE_URL ?>/profile?id=<?= (int)$result['id'] ?>">

                <?= htmlspecialchars($result['resultado']) ?> (Usuario)

            </a>

        <?php elseif($result['tipo'] == 'tag'): ?>

            <a href="<?= BASE_URL ?>/tag?id=<?= (int)$result['id'] ?>">

                <?= htmlspecialchars($result['resultado']) ?> (Tag)

            </a>

        <?php endif; ?>

    </li>

<?php endforeach; ?>

</ul>

<?php endif; ?>

<a href="/LASK/public">Volver al Home</a>
