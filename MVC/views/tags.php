<h1>Explorar Tags</h1>

<form action="/LASK/public/index.php/tags" method="GET">
    <input type="text" name="q" value="<?= htmlspecialchars($query ?? '') ?>" placeholder="Buscar tags">
    <button type="submit">Buscar</button>
</form>

<?php if(empty($tags)): ?>
    <p>No se encontraron tags con esa búsqueda.</p>
<?php else: ?>
    <ul>
        <?php foreach($tags as $tag): ?>
            <li>
                <a href="/LASK/public/index.php/tag?id=<?= $tag['id_tag'] ?>">
                    <?= htmlspecialchars($tag['nombre_tag']) ?>
                </a>
                <?php if(!empty($tag['descripcion_tag'])): ?>
                    <div><?= htmlspecialchars($tag['descripcion_tag']) ?></div>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<a href="/LASK/public/index.php/">Volver al home</a>