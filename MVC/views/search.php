<?php
$pageTitle = "Resultados de búsqueda - LASK";
?>

<div class="search-page">

    <h1 class="search-title">Resultados de búsqueda</h1>

    <?php if(empty($results)): ?>
        <p class="no-results">No se encontraron resultados para "<?= htmlspecialchars($query ?? '') ?>"</p>
    <?php else: ?>
        <ul class="results-list">
            <?php foreach($results as $result): ?>
                <li class="result-item">
                    <?php if($result['tipo'] == 'cancion'): ?>
                        <a class="result-link" href="<?= BASE_URL ?>/song?id=<?= (int)$result['id'] ?>">
                            <?= htmlspecialchars($result['resultado']) ?> (Canción)
                        </a>
                    <?php elseif($result['tipo'] == 'artista'): ?>
                        <a class="result-link" href="<?= BASE_URL ?>/artist?id=<?= (int)$result['id'] ?>">
                            <?= htmlspecialchars($result['resultado']) ?> (Artista)
                        </a>
                    <?php elseif($result['tipo'] == 'album'): ?>
                        <a class="result-link" href="<?= BASE_URL ?>/album?id=<?= (int)$result['id'] ?>">
                            <?= htmlspecialchars($result['resultado']) ?> (Álbum)
                        </a>
                    <?php elseif($result['tipo'] == 'usuario'): ?>
                        <a class="result-link" href="<?= BASE_URL ?>/profile?id=<?= (int)$result['id'] ?>">
                            <?= htmlspecialchars($result['resultado']) ?> (Usuario)
                        </a>
                    <?php elseif($result['tipo'] == 'tag'): ?>
                        <a class="result-link" href="<?= BASE_URL ?>/tag?id=<?= (int)$result['id'] ?>">
                            <?= htmlspecialchars($result['resultado']) ?> (Tag)
                        </a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <a class="back-home" href="/LASK/public">Volver al Home</a>

</div>

<!-- Enlace al CSS -->
<link rel="stylesheet" href="/LASK/public/css/search.css">
<link href="https://fonts.googleapis.com/css2?family=VT323&display=swap" rel="stylesheet">