<?php $currentPage = 'home'; ?>

<link rel="stylesheet" href="/LASK/public/css/home.css">

<div class="app">

    <!-- NAVBAR -->
    <div class="navbar">

        <div class="logo">
            <a href="<?= htmlspecialchars(BASE_URL) ?>">
                <img src="/LASK/public/img/logo.png" alt="logo">
            </a>
        </div>

        <div class="nav-right">
            <?php if(!$isLoggedIn): ?>
                <a href="<?= BASE_URL ?>/register">Registrarse</a>
                <a href="<?= BASE_URL ?>/login">Iniciar sesión</a>
            <?php else: ?>
                <a href="<?= htmlspecialchars($profileUrl) ?>">
                    <img class="pfp" src="/LASK/photos_pfp/pfp_default.png">
                </a>

                <a href="<?= BASE_URL ?>/messages">Mensajes</a>
                <a href="<?= BASE_URL ?>/logout">Cerrar sesión</a>
            <?php endif; ?>
        </div>

    </div>

    <!-- BIENVENIDA -->
    <div class="welcome-container">

        <?php if($isLoggedIn): ?>
            <h1>Bienvenid@ <?= htmlspecialchars($currentUsername) ?></h1>

            <?php if($currentRoleLabel): ?>
                <p><?= htmlspecialchars($currentRoleLabel) ?></p>
            <?php endif; ?>

        <?php else: ?>
            <h1>Bienvenid@ a LASK</h1>
        <?php endif; ?>

    </div>

    <!-- BUSCADOR -->
    <div class="search-container">

        <?php if($isLoggedIn): ?>
            <form action="<?= BASE_URL ?>/search" method="GET">
                <input type="text" id="search-input" name="q" placeholder="Buscar canciones, artistas, álbumes o tags">
                <button type="submit">🔍</button>
            </form>

            <div id="suggestions" class="search-suggestions"></div>

        <?php else: ?>
            <form data-auth-required-message="Debes iniciar sesión para buscar">
                <input type="text" placeholder="Buscar canciones..." disabled>
                <button disabled>🔍</button>
            </form>
        <?php endif; ?>

    </div>

    <!-- LAYOUT -->
    <div class="layout">

        <!-- SIDEBAR (TAGS) -->
        <div class="sidebar">

            <h3>Explora Tags</h3>
            <p><?= htmlspecialchars($tagDescription) ?></p>

            <?php foreach($tags as $tag): ?>
                <div class="tag">
                    <a href="<?= BASE_URL ?>/tag?id=<?= (int)$tag['id_tag'] ?>">
                        <?= htmlspecialchars($tag['nombre_tag']) ?>
                    </a>
                </div>
            <?php endforeach; ?>

            <a href="<?= BASE_URL ?>/tags">Ver todos</a>

        </div>

        <!-- MAIN -->
        <div class="main">

            <!-- CANCIONES -->
            <div class="section">

                <div class="section-header">
                    <h2>Canciones recientes</h2>
                    <a href="<?= BASE_URL ?>/new-releases">Ver todos →</a>
                </div>

                <div class="scroll">
                    <?php if(!empty($songs)): ?>
                        <?php foreach($songs as $song): ?>
                            <div class="card">

                                <?php if($song['detail_url']): ?>
                                    <a href="<?= htmlspecialchars($song['detail_url']) ?>">
                                <?php else: ?>
                                    <div class="locked" data-auth-required-message="Debes iniciar sesión">
                                <?php endif; ?>

                                    <img src="/LASK/<?= htmlspecialchars($song['cover_path']) ?>">
                                    <p><?= htmlspecialchars($song['nombre_cancion']) ?></p>
                                    <span><?= htmlspecialchars($song['artist_name']) ?></span>

                                <?php if($song['detail_url']): ?>
                                    </a>
                                <?php else: ?>
                                    </div>
                                <?php endif; ?>

                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No hay canciones recientes</p>
                    <?php endif; ?>
                </div>

            </div>

            <!-- ÁLBUMES -->
            <div class="section">
                <h3>Álbumes recientes</h3>

                <div class="scroll">
                    <?php foreach($albums as $album): ?>
                        <div class="card">

                            <?php if($album['detail_url']): ?>
                                <a href="<?= htmlspecialchars($album['detail_url']) ?>">
                            <?php else: ?>
                                <div class="locked" data-auth-required-message="Debes iniciar sesión">
                            <?php endif; ?>

                                <img src="/LASK/<?= htmlspecialchars($album['cover_path']) ?>">
                                <p><?= htmlspecialchars($album['display_name']) ?></p>

                            <?php if($album['detail_url']): ?>
                                </a>
                            <?php else: ?>
                                </div>
                            <?php endif; ?>

                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ARTISTAS -->
            <div class="section">
                <h2>Nuevos Artistas</h2>

                <div class="scroll">
                    <?php foreach($artists as $artist): ?>
                        <div class="card">

                            <?php if($artist['profile_url']): ?>
                                <a href="<?= htmlspecialchars($artist['profile_url']) ?>">
                            <?php else: ?>
                                <div class="locked" data-auth-required-message="Debes iniciar sesión">
                            <?php endif; ?>

                                <img src="/LASK/<?= htmlspecialchars($artist['pfp']) ?>">
                                <p><?= htmlspecialchars($artist['display_name']) ?></p>

                            <?php if($artist['profile_url']): ?>
                                </a>
                            <?php else: ?>
                                </div>
                            <?php endif; ?>

                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>

    </div>

</div>

<script src="<?= substr(BASE_URL, 0, strrpos(BASE_URL, '/')) ?>/js/home.js"></script>