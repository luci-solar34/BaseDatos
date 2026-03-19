<?php
$pageTitle = $album['nombre_album'] . ' - LASK';
$albumCover = !empty($album['portada_album']) ? $album['portada_album'] : 'Photos/banner_default.png';
// Canciones con audio reproducible
$playableSongs = array_values(array_filter($songs, fn($s) => !empty($s['path_link'])));
$playableIndex = [];
foreach($playableSongs as $pi => $ps){ $playableIndex[$ps['id_cancion']] = $pi; }
?>

<h1><?= htmlspecialchars($album['nombre_album']) ?></h1>

<?php if(!empty($album['nombre_artistico'])): ?>
    <p>
        <strong>Artista:</strong>
        <a href="/LASK/public/index.php/artist?id=<?= $album['id_artista'] ?? '' ?>">
            <?= htmlspecialchars($album['nombre_artistico']) ?>
        </a>
    </p>
<?php endif; ?>

<?php if(!empty($album['descripcion_album'])): ?>
    <p><strong>Descripción:</strong> <?= htmlspecialchars($album['descripcion_album']) ?></p>
<?php endif; ?>

<p><strong>Fecha de lanzamiento:</strong> <?= htmlspecialchars($album['fecha_lanzamiento']) ?></p>

<?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $album['id_artista']): ?>
    <a href="/LASK/public/index.php/artist/edit-album?id=<?= $album['id_album'] ?>">
        <button>Editar Álbum</button>
    </a>
<?php endif; ?>

<!-- Panel de reproducción (oculto hasta que se da play) -->
<div id="nowPlaying" style="display:none; margin:16px 0; border:1px solid #ddd; border-radius:10px; padding:14px; max-width:520px;">
    <p style="margin:0 0 6px;"><strong>▶ Reproduciendo: <span id="nowPlayingTitle"></span></strong></p>
    <img id="nowPlayingCover" src="/LASK/<?= htmlspecialchars($albumCover) ?>" alt="Portada actual" width="220" style="display:block; margin:0 0 10px; border-radius:8px; object-fit:cover;">
    <audio id="albumAudio" controls style="width:100%; margin-bottom:8px;"></audio>
    <form id="nowPlayingLikeForm" action="/LASK/public/index.php/like" method="POST" style="margin:0 0 12px; display:none;">
        <input type="hidden" name="song_id" id="nowPlayingSongId" value="">
        <input type="hidden" name="album_id" value="<?= $album['id_album'] ?>">
        <button type="submit" id="nowPlayingLikeButton">♥ Dar like</button>
    </form>
    <div style="display:flex; gap:10px; margin-bottom:14px;">
        <button onclick="albumPrev()">⏮ Anterior</button>
        <button onclick="albumNext()">Siguiente ⏭</button>
    </div>
    <div id="lyricsSection">
        <h4 style="margin:0 0 4px;">Letra</h4>
        <pre id="lyricText" style="white-space:pre-wrap; background:#f8f8f8; padding:8px; border-radius:6px; max-height:200px; overflow-y:auto;"></pre>
        <h4 style="margin:8px 0 4px;">Texto fonético</h4>
        <pre id="lyricPhonetic" style="white-space:pre-wrap; background:#f8f8f8; padding:8px; border-radius:6px; max-height:200px; overflow-y:auto;"></pre>
    </div>
</div>

<h2>Canciones</h2>

<?php if(empty($songs)): ?>
    <p>Este álbum no tiene canciones</p>
<?php else: ?>
    <ul>
    <?php foreach($songs as $song): ?>
        <li>
            <a href="/LASK/public/index.php/song?id=<?= $song['id_cancion'] ?>">
                <?= (int)$song['numero_pista'] ?>. <?= htmlspecialchars($song['nombre_cancion']) ?>
            </a>
            <?php if(!empty($song['path_link'])): ?>
                <button onclick="albumPlay(<?= $playableIndex[$song['id_cancion']] ?>)">▶ Reproducir</button>
            <?php endif; ?>
            <span style="margin-left:8px;" data-song-like-count="<?= (int)$song['id_cancion'] ?>">♥ <?= (int)($song['likes_total'] ?? 0) ?></span>
            <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $album['id_artista']): ?>
                <a href="/LASK/public/index.php/artist/edit-song?id=<?= $song['id_cancion'] ?>">Editar</a>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>

<br>
<a href="/LASK/public">← Volver al inicio</a>

<script>
var albumQueue = <?= json_encode(array_map(fn($s) => [
    'id'       => $s['id_cancion'],
    'src'      => '/LASK/' . $s['path_link'],
    'title'    => $s['nombre_cancion'],
    'cover'    => '/LASK/' . (!empty($s['portada_cancion']) ? $s['portada_cancion'] : $albumCover),
    'letra'    => $s['letra_cancion']    ?? null,
    'fonetico' => $s['texto_fonetico']   ?? null,
    'userLiked' => !empty($s['user_liked']),
    'likesTotal' => (int)($s['likes_total'] ?? 0),
], $playableSongs)) ?>;

var albumIndex = 0;

function renderNowPlayingLike(track){
    document.getElementById('nowPlayingLikeButton').textContent = (track.userLiked ? '♥ Quitar like' : '♥ Dar like') + ' (' + track.likesTotal + ')';
}

function renderSongLikeInList(songId, likesTotal){
    var el = document.querySelector('[data-song-like-count="' + songId + '"]');
    if(el) el.textContent = '♥ ' + likesTotal;
}

function albumPlay(idx){
    if(idx === null || idx === undefined || !albumQueue[idx]) return;
    albumIndex = idx;
    var t = albumQueue[idx];

    var audio = document.getElementById('albumAudio');
    audio.src = t.src;
    audio.play();

    document.getElementById('nowPlayingTitle').textContent = t.title;
    document.getElementById('nowPlayingCover').src = t.cover;
    document.getElementById('nowPlaying').style.display = 'block';
    document.getElementById('lyricText').textContent     = t.letra    || 'Texto no disponible';
    document.getElementById('lyricPhonetic').textContent = t.fonetico || 'Texto no disponible';
    document.getElementById('nowPlayingSongId').value = t.id;
    renderNowPlayingLike(t);
    document.getElementById('nowPlayingLikeForm').style.display = 'block';

}

function albumNext(){
    if(albumIndex < albumQueue.length - 1) albumPlay(albumIndex + 1);
}

function albumPrev(){
    if(albumIndex > 0) albumPlay(albumIndex - 1);
}

document.addEventListener('DOMContentLoaded', function(){
    document.getElementById('albumAudio').addEventListener('ended', albumNext);

    var likeForm = document.getElementById('nowPlayingLikeForm');
    var likeButton = document.getElementById('nowPlayingLikeButton');

    likeForm.addEventListener('submit', async function(e){
        e.preventDefault();

        if(!albumQueue[albumIndex]) return;

        likeButton.disabled = true;
        try {
            var response = await fetch(likeForm.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: new FormData(likeForm)
            });

            if(response.status === 401){
                window.location.href = '/LASK/public/index.php/login';
                return;
            }

            if(!response.ok){
                throw new Error('Error al actualizar like');
            }

            var data = await response.json();
            if(data && data.success){
                var track = albumQueue[albumIndex];
                track.userLiked = !!data.userLiked;
                track.likesTotal = parseInt(data.likesTotal || 0, 10);
                renderNowPlayingLike(track);
                renderSongLikeInList(track.id, track.likesTotal);
            }
        } catch (err) {
            // Fallback al envío tradicional si falla AJAX.
            likeForm.submit();
        } finally {
            likeButton.disabled = false;
        }
    });
});
</script>