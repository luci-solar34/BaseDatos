<h1>Mi Playlist</h1>

<div id="nowPlaying" style="display:none; margin: 12px 0 20px; border:1px solid #ddd; border-radius:10px; padding:12px; max-width:420px;">
    <p style="margin:0 0 8px;"><strong>Reproduciendo ahora</strong></p>
    <img id="nowPlayingCover" src="/LASK/Photos/banner_default.png" alt="Portada actual" width="220" style="border-radius:8px; display:block; margin-bottom:8px; object-fit:cover;">
    <div id="nowPlayingTitle" style="font-weight:bold;"></div>
</div>

<a href="/LASK/public/index.php/playlist/add-song?playlist=<?= $playlist_id ?>">
    <button>Agregar canciones</button>
</a>

<a href="/LASK/public/index.php/playlist/edit?id=<?= $playlist_id ?>">
    <button>Editar Playlist</button>
</a>

<h2>Canciones</h2>

<?php foreach($songs as $song): ?>

<div>
    <?= $song['nombre_cancion'] ?>
    <audio controls class="js-song-player"
           data-title="<?= htmlspecialchars($song['nombre_cancion']) ?>"
           data-cover="<?= htmlspecialchars($song['portada_cancion'] ?? 'Photos/banner_default.png') ?>">
        <source src="/LASK/<?= $song['path_link'] ?>" type="audio/mpeg">
    </audio>
</div>

<?php endforeach; ?>

<a href="/LASK/public/index.php/profile?id=<?= $_SESSION['user_id'] ?>">
    <button>Finalizar</button>
</a>

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
            const songSrc   = player.querySelector('source').src;

            nowPlaying.style.display = 'block';
            title.textContent = songTitle;
            cover.src = '/LASK/' + songCover;


        });
    });
});
</script>