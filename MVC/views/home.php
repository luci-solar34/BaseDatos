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


<h2>Encuentra tus favoritos</h2>

<?php $tags = $tags ?? []; ?>

<?php foreach($tags as $tag): ?>

<?php if(isset($_SESSION['user_id'])): ?>

<a href="/LASK/public/tag?id=<?= $tag['id_tag'] ?>">
<?= $tag['nombre_tag'] ?>
</a>

<?php else: ?>

<a href="#" onclick="alert('Debes iniciar sesión'); return false;">
<?= $tag['nombre_tag'] ?>
</a>

<?php endif; ?>

<br>

<?php endforeach; ?>


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

<?php $songs = $songs ?? []; ?>

<?php if(isset($songs) && !empty($songs)): ?>
    <div style="display: flex; gap: 10px; overflow-x: auto;">
        <?php foreach($songs as $song): ?>
            <div style="min-width: 120px;">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="/LASK/public/song?id=<?= $song['id_cancion'] ?>">
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

<?php foreach($albums as $album): ?>

<?php if(isset($_SESSION['user_id'])): ?>

<a href="/LASK/public/album?id=<?= $album['id_album'] ?>">
<img src="/LASK/<?= $album['portada_album'] ?>" width="120">
</a>

<?php else: ?>

<a href="#" onclick="alert('Debes iniciar sesión'); return false;">
<img src="/LASK/<?= $album['portada_album'] ?>" width="120">
</a>

<?php endif; ?>

<?php endforeach; ?>


<h2>Nuevos Artistas</h2>

<?php $artists = $artists ?? []; ?>

<?php foreach($artists as $artist): ?>

<?php if(isset($_SESSION['user_id'])): ?>

<a href="/LASK/public/artist?id=<?= $artist['id_usuario'] ?>">
<img src="/LASK/<?= $artist['pfp'] ?>" width="100">
</a>

<?php else: ?>

<a href="#" onclick="alert('Debes iniciar sesión'); return false;">
<img src="/LASK/<?= $artist['pfp'] ?>" width="100">
</a>

<?php endif; ?>

<?php endforeach; ?>

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
                        document.getElementById('search-input').value = item.resultado;
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