<?php
$pageTitle = $album['nombre_album'] . ' - LASK';
?>

<h1><?= $album['nombre_album'] ?></h1>

<?php if(!empty($album['nombre_artistico'])): ?>
    <p>
        <strong>Artista:</strong> 
        <a href="/LASK/public/index.php/artist?id=<?= $album['id_artista'] ?? '' ?>">
            <?= $album['nombre_artistico'] ?>
        </a>
    </p>
<?php endif; ?>

<?php if($album['descripcion_album']): ?>
    <p><strong>Descripción:</strong> <?= $album['descripcion_album'] ?></p>
<?php endif; ?>

<p><strong>Fecha de lanzamiento:</strong> <?= $album['fecha_lanzamiento'] ?></p>

<?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $album['id_artista']): ?>
    <a href="/LASK/public/index.php/artist/edit-album?id=<?= $album['id_album'] ?>">
        <button>Editar Álbum</button>
    </a>
<?php endif; ?>

<div id="nowPlaying" style="display:none; margin: 12px 0 20px; border:1px solid #ddd; border-radius:10px; padding:12px; max-width:420px;">
    <p style="margin:0 0 8px;"><strong>Reproduciendo ahora</strong></p>
    <img id="nowPlayingCover" src="/LASK/<?= !empty($album['portada_album']) ? htmlspecialchars($album['portada_album']) : 'Photos/banner_default.png' ?>" alt="Portada actual" width="220" style="border-radius:8px; display:block; margin-bottom:8px; object-fit:cover;">
    <div id="nowPlayingTitle" style="font-weight:bold;"></div>
</div>

<h2>Canciones</h2>

<?php if(empty($songs)): ?>
    <p>Este álbum no tiene canciones</p>
<?php else: ?>
    <ul>
    <?php foreach($songs as $song): ?>
        <li>
            <a href="/LASK/public/index.php/song?id=<?= $song['id_cancion'] ?>">
                <?= $song['numero_pista'] ?>. <?= $song['nombre_cancion'] ?>
            </a>
            <?php if(!empty($song['path_link'])): ?>
                <div style="margin: 6px 0;">
                    <audio controls class="js-song-player"
                           data-title="<?= htmlspecialchars($song['nombre_cancion']) ?>"
                           data-cover="<?= htmlspecialchars($song['portada_cancion'] ?? (!empty($album['portada_album']) ? $album['portada_album'] : 'Photos/banner_default.png')) ?>">
                        <source src="/LASK/<?= htmlspecialchars($song['path_link']) ?>" type="audio/mpeg">
                    </audio>
                </div>
            <?php endif; ?>
            <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $album['id_artista']): ?>
                <a href="/LASK/public/index.php/artist/edit-song?id=<?= $song['id_cancion'] ?>">Editar canción</a>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>

<br>
<a href="/LASK/public">← Volver al inicio</a>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const nowPlaying = document.getElementById('nowPlaying');
    const cover = document.getElementById('nowPlayingCover');
    const title = document.getElementById('nowPlayingTitle');
    const players = document.querySelectorAll('.js-song-player');

    players.forEach(function(player){
        player.addEventListener('play', function(){
            const songTitle = player.dataset.title || 'Canción';
            const songCover = player.dataset.cover || 'Photos/banner_default.png';

            nowPlaying.style.display = 'block';
            title.textContent = songTitle;
            cover.src = '/LASK/' + songCover;
        });
    });
});
</script>