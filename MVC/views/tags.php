<h1>Explorar Tags</h1>

<?php if(isset($_SESSION['role']) && $_SESSION['role'] == 1): ?>
    <p>
        <a href="/LASK/public/index.php/tag/create">Crear nuevo tag</a>
    </p>
<?php endif; ?>

<?php if(isset($_SESSION['flash_message']) && $_SESSION['flash_message']): ?>
    <div style="padding:10px; margin:10px 0; border:1px solid green; background:#e6ffe6;">
        <?= $_SESSION['flash_message'] ?>
    </div>
    <?php unset($_SESSION['flash_message']); ?>
<?php endif; ?>

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