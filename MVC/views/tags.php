<h1 class="page-title">Explorar Tags</h1>
<link rel="stylesheet" href="/LASK/public/css/explorar-tags.css">
<link href="https://fonts.googleapis.com/css2?family=VT323&display=swap" rel="stylesheet">

<?php if($canCreateTag): ?>
    <p class="create-tag">
        <a href="<?= BASE_URL ?>/tag/create">Crear nuevo tag</a>
    </p>
<?php endif; ?>

<?php if($flashMessage): ?>
    <div class="flash-message">
        <?= htmlspecialchars($flashMessage) ?>
    </div>
<?php endif; ?>

<form action="<?= htmlspecialchars(BASE_URL . '/tags') ?>" method="GET" class="search-form">
    <input type="text" name="q" value="<?= htmlspecialchars($query ?? '') ?>" placeholder="Buscar tags">
    <button type="submit">Buscar</button>
</form>

<?php if(empty($tags)): ?>
    <p class="no-tags">No se encontraron tags con esa búsqueda.</p>
<?php else: ?>
    <ul class="tag-list">
        <?php foreach($tags as $tag): ?>
            <li class="tag-item">
                <a href="<?= BASE_URL ?>/tag?id=<?= (int)$tag['id_tag'] ?>" class="tag-link">
                    <?= htmlspecialchars($tag['nombre_tag']) ?>
                </a>
                <?php if(!empty($tag['descripcion_tag'])): ?>
                    <div class="tag-desc"><?= htmlspecialchars($tag['descripcion_tag']) ?></div>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<a href="<?= htmlspecialchars(BASE_URL) ?>" class="back-home">Volver al home</a>