<h1>LASK</h1>

<?php if(!$isLoggedIn): ?>
    <nav>
        <a href="<?= BASE_URL ?>/login">Iniciar sesión</a>
        <a href="<?= BASE_URL ?>/register">Registrarse</a>
    </nav>

<?php else: ?>
    <section class="user-welcome">
        <p>Bienvenid@ <?= htmlspecialchars($currentUsername) ?></p>
        <?php if($currentRoleLabel): ?>
            <p class="user-role"><?= htmlspecialchars($currentRoleLabel) ?></p>
        <?php endif; ?>
        <nav>
            <a href="<?= htmlspecialchars($profileUrl) ?>">Ver perfil</a>
            <a href="<?= BASE_URL ?>/messages">Mensajes</a>
            <a href="<?= BASE_URL ?>/logout">Cerrar sesión</a>
        </nav>
    </section>

<?php endif; ?>


<section class="search-section">
    <h2>Buscar</h2>

    <?php if($isLoggedIn): ?>
        <form action="<?= BASE_URL ?>/search" method="GET">
            <input type="text" id="search-input" name="q" placeholder="Buscar canciones, artistas, álbumes o tags">
            <button type="submit">Buscar</button>
        </form>
        <div id="suggestions" class="search-suggestions"></div>
    <?php else: ?>
        <form data-auth-required-message="Debes iniciar sesión para buscar">
            <input type="text" placeholder="Buscar canciones, artistas, álbumes o tags" disabled>
            <button type="submit" disabled>Buscar</button>
        </form>
    <?php endif; ?>

</section>


<section class="tags-section">
    <h2>Explora Tags</h2>
    <p><?= htmlspecialchars($tagDescription) ?></p>
    <div class="tags-grid">
        <?php foreach($tags as $tag): ?>
            <a href="<?= BASE_URL ?>/tag?id=<?= (int)$tag['id_tag'] ?>" class="tag-link">
                <?= htmlspecialchars($tag['nombre_tag']) ?>
            </a>
        <?php endforeach; ?>
    </div>
    <p><a href="<?= BASE_URL ?>/tags">Explorar todos los tags</a></p>
</section>


<section class="releases-section">
    <div class="section-header">
        <h2>Canciones recientes</h2>
        <a href="<?= BASE_URL ?>/new-releases">Ver todos →</a>
    </div>

    <?php if(!empty($songs)): ?>
        <div class="carousel">
            <?php foreach($songs as $song): ?>
                <div class="carousel-item">
                    <?php if($song['detail_url']): ?>
                        <a href="<?= htmlspecialchars($song['detail_url']) ?>" class="media-link">
                            <img src="/LASK/<?= htmlspecialchars($song['cover_path']) ?>" 
                                 width="120" height="120"
                                 alt="Portada de: <?= htmlspecialchars($song['nombre_cancion']) ?>"
                                 class="media-cover">
                            <p class="media-title"><?= htmlspecialchars($song['nombre_cancion']) ?></p>
                            <p class="media-artist"><?= htmlspecialchars($song['artist_name']) ?></p>
                        </a>
                    <?php else: ?>
                        <div class="media-item locked" data-auth-required-message="Debes iniciar sesión">
                            <img src="/LASK/<?= htmlspecialchars($song['cover_path']) ?>" 
                                 width="120" height="120"
                                 alt="Portada de: <?= htmlspecialchars($song['nombre_cancion']) ?>"
                                 class="media-cover">
                            <p class="media-title"><?= htmlspecialchars($song['nombre_cancion']) ?></p>
                            <p class="media-artist"><?= htmlspecialchars($song['artist_name']) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No hay nuevos lanzamientos disponibles</p>
    <?php endif; ?>

</section>


<section class="albums-section">
    <h2>Álbumes recientes</h2>
    <div class="carousel">
        <?php foreach($albums as $album): ?>
            <div class="carousel-item">
                <?php if($album['detail_url']): ?>
                    <a href="<?= htmlspecialchars($album['detail_url']) ?>" class="media-link">
                        <img src="/LASK/<?= htmlspecialchars($album['cover_path']) ?>" 
                             width="120" height="120"
                             alt="Portada de: <?= htmlspecialchars($album['display_name']) ?>"
                             class="media-cover">
                        <p class="media-title"><?= htmlspecialchars($album['display_name']) ?></p>
                    </a>
                <?php else: ?>
                        <div class="media-item locked" data-auth-required-message="Debes iniciar sesión">
                        <img src="/LASK/<?= htmlspecialchars($album['cover_path']) ?>" 
                             width="120" height="120"
                             alt="Portada de: <?= htmlspecialchars($album['display_name']) ?>"
                             class="media-cover">
                        <p class="media-title"><?= htmlspecialchars($album['display_name']) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>


<section class="artists-section">
    <h2>Nuevos Artistas</h2>
    <div class="carousel">
        <?php foreach($artists as $artist): ?>
            <div class="carousel-item">
                <?php if($artist['profile_url']): ?>
                    <a href="<?= htmlspecialchars($artist['profile_url']) ?>" class="media-link artist-link">
                        <img src="/LASK/<?= htmlspecialchars($artist['pfp']) ?>" 
                             width="100" height="100"
                             alt="Foto de perfil: <?= htmlspecialchars($artist['display_name']) ?>"
                             class="media-cover artist-photo">
                        <p class="media-title"><?= htmlspecialchars($artist['display_name']) ?></p>
                    </a>
                <?php else: ?>
                    <div class="media-item locked artist-item" data-auth-required-message="Debes iniciar sesión">
                        <img src="/LASK/<?= htmlspecialchars($artist['pfp']) ?>" 
                             width="100" height="100"
                             alt="Foto de perfil: <?= htmlspecialchars($artist['display_name']) ?>"
                             class="media-cover artist-photo">
                        <p class="media-title"><?= htmlspecialchars($artist['display_name']) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<script src="<?= substr(BASE_URL, 0, strrpos(BASE_URL, '/')) ?>/js/home.js"></script>

