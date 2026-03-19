<?php $currentPage = 'home'; // Indica que estamos en home ?>

<link rel="stylesheet" href="/LASK/public/css/styles.css">

<?php
// Solo carga song.css si estamos en la vista de canción
if (isset($currentPage) && $currentPage === 'song') {
    echo '<link rel="stylesheet" href="/LASK/public/css/song.css">';
}
?>

<div class="app">

    <!-- NAVBAR -->
    <div class="navbar">

        <div class="logo">
            <img src="/LASK/public/img/logo.png" alt="logo">
        </div>

        <div class="nav-right">
        <?php if(!isset($_SESSION['user_id'])): ?>

            <a href="/LASK/public/index.php/register">Registrarse</a>
            <a href="/LASK/public/index.php/login">Iniciar sesión</a>

        <?php else: ?>

            <a href="/LASK/public/index.php/profile?id=<?= $_SESSION['user_id'] ?>">
                <img class="pfp" src="/LASK/photos_pfp/pfp_default.png">
            </a>

            <a href="/LASK/public/index.php/messages">Mensajes</a>
            <a href="/LASK/public/index.php/logout">Cerrar sesión</a>

        <?php endif; ?>
        </div>

    </div>


    <!-- BIENVENIDA -->
    <div class="welcome-container">

        <?php if(isset($_SESSION['user_id'])): ?>
            <h1>Bienvenid@ <?= $_SESSION['username'] ?></h1>

            <?php if($_SESSION['role'] == 2): ?>
                <p>Artista</p>
            <?php elseif($_SESSION['role'] == 3): ?>
                <p>Listener</p>
            <?php endif; ?>

        <?php else: ?>
            <h1>Bienvenid@</h1>
        <?php endif; ?>

    </div>


    <!-- BUSCADOR -->
    <div class="search-container">

    <?php if(isset($_SESSION['user_id'])): ?>

        <form action="/LASK/public/index.php/search" method="GET">
            <input type="text" id="search-input" name="q" placeholder="Busca tu estilo de música">
            <button type="submit">🔍</button>
        </form>

        <div id="suggestions"></div>

    <?php else: ?>

        <form onsubmit="alert('Debes iniciar sesión para buscar'); return false;">
            <input type="text" placeholder="Busca tu estilo de música">
            <button type="submit">🔍</button>
        </form>

    <?php endif; ?>

    </div>


    <!-- LAYOUT -->
    <div class="layout">

        <!-- SIDEBAR -->
        <div class="sidebar">

            <h3>Encuentra tus favoritos</h3>

            <?php foreach($tags as $tag): ?>
                <div class="tag">
                    <a href="/LASK/public/index.php/tag?id=<?= $tag['id_tag'] ?>">
                        <?= htmlspecialchars($tag['nombre_tag']) ?>
                    </a>
                </div>
            <?php endforeach; ?>

        </div>


        <!-- MAIN -->
        <div class="main">

            <!-- NUEVOS LANZAMIENTOS -->
            <div class="section">

                <div class="section-header">
                    <h2>Nuevos Lanzamientos</h2>
                    <a href="/LASK/public/index.php/new-releases">Ver todos →</a>
                </div>

                <h3>Canciones recientes</h3>

                <div class="scroll">
                <?php foreach($songs as $song): ?>
                    <div class="card">

                        <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="/LASK/public/index.php/song?id=<?= $song['id_cancion'] ?>">
                        <?php else: ?>
                        <div onclick="alert('Debes iniciar sesión');">
                        <?php endif; ?>

                            <img src="/LASK/<?= $song['portada_cancion'] ?>">
                            <p><?= $song['nombre_cancion'] ?></p>

                        <?php if(isset($_SESSION['user_id'])): ?>
                        </a>
                        <?php else: ?>
                        </div>
                        <?php endif; ?>

                    </div>
                <?php endforeach; ?>
                </div>

            </div>


            <!-- ÁLBUMES -->
            <div class="section">
                <h3>Álbumes recientes</h3>

                <div class="scroll">
                <?php foreach($albums as $album): ?>
                    <div class="card">

                        <?php if(isset($_SESSION['user_id'])): ?>
                            <a href="/LASK/public/index.php/album?id=<?= $album['id_album'] ?>">
                        <?php else: ?>
                            <a href="#" onclick="alert('Debes iniciar sesión'); return false;">
                        <?php endif; ?>

                                <img src="/LASK/<?= $album['portada_album'] ?>">
                                <p><?= htmlspecialchars($album['nombre_album']) ?></p>

                            </a>

                    </div>
                <?php endforeach; ?>
                </div>
            </div>


           <!-- ARTISTAS -->
            <div class="section">
                <h2>Nuevos Artistas</h2>

                <div class="scroll">
                <?php foreach($artists as $artist): ?>

                    <?php if(isset($_SESSION['user_id'])): ?>
                        <?php 
                        $profileUrl = ($_SESSION['user_id'] == $artist['id_usuario']) 
                            ? "/LASK/public/index.php/profile?id={$artist['id_usuario']}" 
                            : "/LASK/public/index.php/artist?id={$artist['id_usuario']}";
                        ?>

                        <a href="<?= $profileUrl ?>" class="artist">
                            <img src="/LASK/<?= $artist['pfp'] ?>">
                            <p><?= htmlspecialchars($artist['nombre_artistico']) ?></p>
                        </a>

                    <?php else: ?>

                        <a href="#" class="artist" onclick="alert('Debes iniciar sesión'); return false;">
                            <img src="/LASK/<?= $artist['pfp'] ?>">
                            <p><?= htmlspecialchars($artist['nombre_artistico']) ?></p>
                        </a>

                    <?php endif; ?>

                <?php endforeach; ?>
                </div>
            </div>

        </div>

    </div>

</div>