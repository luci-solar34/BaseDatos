<h1>Explorar Tags</h1>

<?php if($canCreateTag): ?>
    <p>
        <a href="<?= BASE_URL ?>/tag/create">Crear nuevo tag</a>
    </p>
<?php endif; ?>

<?php if($flashMessage): ?>
    <div style="padding:10px; margin:10px 0; border:1px solid green; background:#e6ffe6;">
        <?= htmlspecialchars($flashMessage) ?>
    </div>
<?php endif; ?>

<form action="<?= htmlspecialchars(BASE_URL . '/tags') ?>" method="GET">
    <input type="text" name="q" value="<?= htmlspecialchars($query ?? '') ?>" placeholder="Buscar tags">
    <button type="submit">Buscar</button>
</form>

<?php if(empty($tags)): ?>
    <p>No se encontraron tags con esa búsqueda.</p>
<?php else: ?>
    <ul>
        <?php foreach($tags as $tag): ?>
            <li>
                <a href="<?= BASE_URL ?>/tag?id=<?= (int)$tag['id_tag'] ?>">
                    <?= htmlspecialchars($tag['nombre_tag']) ?>
                </a>
                <?php if(!empty($tag['descripcion_tag'])): ?>
                    <div><?= htmlspecialchars($tag['descripcion_tag']) ?></div>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<a href="<?= htmlspecialchars(BASE_URL) ?>">Volver al home</a>