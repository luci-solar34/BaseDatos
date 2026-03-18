<h1>LASK</h1>

<?php if(!isset($_SESSION['user_id'])): ?>

<a href="/LASK/public/index.php/login">Iniciar sesión</a>
<a href="/LASK/public/index.php/register">Registrarse</a>

<?php else: ?>

<p>Bienvenid@ <?= $_SESSION['username'] ?></p>

<?php if($_SESSION['role'] == 2): ?>
<p>Artista</p>
<?php elseif($_SESSION['role'] == 3): ?>
<p>Listener</p>
<?php endif; ?>

<a href="/LASK/public/index.php/profile?id=<?= $_SESSION['user_id'] ?>">Ver perfil</a>
<a href="/LASK/public/index.php/messages">
    <button>Mensajes</button>
</a>
<a href="/LASK/public/index.php/logout">Cerrar sesión</a>

<?php endif; ?>


<h2>Buscar</h2>

<?php if(isset($_SESSION['user_id'])): ?>

<form action="/LASK/public/index.php/search" method="GET">
<input type="text" id="search-input" name="q" placeholder="Buscar canciones, artistas, álbumes o tags">
<button type="submit">Buscar</button>
</form>
<div id="suggestions" style="position: absolute; background: white; border: 1px solid #ccc; max-height: 200px; overflow-y: auto; display: none;"></div>

<?php else: ?>

<form onsubmit="alert('Debes iniciar sesión para buscar'); return false;">
<input type="text" placeholder="Buscar canciones, artistas, álbumes o tags">
<button type="submit">Buscar</button>
</form>

<?php endif; ?>


<h2>Explora Tags</h2>

<p>
    <?= isset($_SESSION['user_id']) ? 'Explora tags para descubrir canciones con una vibe parecida.' : 'Explora algunos tags populares para descubrir la vibe de la plataforma.' ?>
</p>

<?php foreach($tags as $tag): ?>
    <div>
        <a href="/LASK/public/index.php/tag?id=<?= $tag['id_tag'] ?>">
            <?= htmlspecialchars($tag['nombre_tag']) ?>
        </a>
    </div>
<?php endforeach; ?>

<p>
    <a href="/LASK/public/index.php/tags">Explorar tags</a>
</p>


<h2>Nuevos Lanzamientos</h2>

<div style="display: flex; justify-content: space-between; align-items: center;">
    <h3>Canciones recientes</h3>
    <a href="/LASK/public/index.php/new-releases">Ver todos los lanzamientos →</a>
</div>

<?php $songs = $songs ?? []; ?>

<?php if(isset($songs) && !empty($songs)): ?>
    <div style="display: flex; gap: 10px; overflow-x: auto;">
        <?php foreach($songs as $song): ?>
            <div style="min-width: 120px;">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="/LASK/public/index.php/song?id=<?= $song['id_cancion'] ?>">
                        <img src="/LASK/<?= $song['portada_cancion'] ?? 'Photos/banner_default.png' ?>" 
                             width="120" height="120"
                             style="border-radius: 8px;">
                        <p><?= $song['nombre_cancion'] ?></p>
                        <small><?= $song['nombre_artistico'] ?? 'Artista' ?></small>
                    </a>
                <?php else: ?>
                    <div onclick="alert('Debes iniciar sesión');">
                        <img src="/LASK/<?= $song['portada_cancion'] ?? 'Photos/banner_default.png' ?>" 
                             width="120" height="120"
                             style="border-radius: 8px;">
                        <p><?= $song['nombre_cancion'] ?></p>
                        <small><?= $song['nombre_artistico'] ?? 'Artista' ?></small>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>No hay nuevos lanzamientos disponibles</p>
<?php endif; ?>


<?php $albums = $albums ?? []; ?>

<h2>Álbumes recientes</h2>
<div style="display: flex; gap: 10px; overflow-x: auto;">
    <?php foreach($albums as $album): ?>
        <div style="min-width: 140px; text-align: center;">
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="/LASK/public/index.php/album?id=<?= $album['id_album'] ?>">
                    <img src="/LASK/<?= $album['portada_album'] ?>" width="120" style="border-radius: 8px;">
                </a>
                <p style="margin: 6px 0 0; font-size: 0.9em;"><?= htmlspecialchars($album['nombre_album'] ?? 'Álbum') ?></p>
            <?php else: ?>
                <a href="#" onclick="alert('Debes iniciar sesión'); return false;">
                    <img src="/LASK/<?= $album['portada_album'] ?>" width="120" style="border-radius: 8px;">
                </a>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>


<h2>Nuevos Artistas</h2>

<?php $artists = $artists ?? []; ?>

<div style="display: flex; gap: 10px; overflow-x: auto;">
    <?php foreach($artists as $artist): ?>
        <div style="min-width: 130px; text-align: center;">
            <?php if(isset($_SESSION['user_id'])): ?>
                <?php $profileUrl = ($_SESSION['user_id'] == $artist['id_usuario']) ? "/LASK/public/index.php/profile?id={$artist['id_usuario']}" : "/LASK/public/index.php/artist?id={$artist['id_usuario']}"; ?>
                <a href="<?= $profileUrl ?>">
                    <img src="/LASK/<?= $artist['pfp'] ?>" width="100" style="border-radius: 50%;">
                </a>
                <p style="margin: 6px 0 0; font-size: 0.9em;"><?= htmlspecialchars($artist['nombre_artistico'] ?? 'Artista') ?></p>

            <?php else: ?>
                <a href="#" onclick="alert('Debes iniciar sesión'); return false;">
                    <img src="/LASK/<?= $artist['pfp'] ?>" width="100" style="border-radius: 50%;">
                </a>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<script>
document.getElementById('search-input').addEventListener('input', function() {
    const query = this.value;
    const suggestionsDiv = document.getElementById('suggestions');
    
    if (query.length < 2) {
        suggestionsDiv.style.display = 'none';
        return;
    }
    
    fetch('/LASK/public/index.php/search/autocomplete?q=' + encodeURIComponent(query))
        .then(response => response.json())
        .then(data => {
            suggestionsDiv.innerHTML = '';
            if (data.length > 0) {
                data.forEach(item => {
                    const div = document.createElement('div');
                    div.textContent = item.resultado + ' (' + item.tipo + ')';
                    div.style.padding = '5px';
                    div.style.cursor = 'pointer';
                    div.addEventListener('click', function() {
                        // Navegar al link correspondiente
                        let url = '';
                        if (item.tipo === 'cancion') {
                            url = '/LASK/public/index.php/song?id=' + item.id;
                        } else if (item.tipo === 'artista') {
                            url = '/LASK/public/index.php/artist?id=' + item.id;
                        } else if (item.tipo === 'album') {
                            url = '/LASK/public/index.php/album?id=' + item.id;
                        } else if (item.tipo === 'usuario') {
                            url = '/LASK/public/index.php/profile?id=' + item.id;
                        } else if (item.tipo === 'tag') {
                            url = '/LASK/public/index.php/tag?id=' + item.id;
                        }
                        if (url) {
                            window.location.href = url;
                        }
                        suggestionsDiv.style.display = 'none';
                    });
                    suggestionsDiv.appendChild(div);
                });
                suggestionsDiv.style.display = 'block';
            } else {
                suggestionsDiv.style.display = 'none';
            }
        });
});
</script>

