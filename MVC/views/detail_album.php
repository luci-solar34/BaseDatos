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
    <audio id="albumAudio" controls style="width:100%; margin-bottom:8px;"></audio>
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
    'src'      => '/LASK/' . $s['path_link'],
    'title'    => $s['nombre_cancion'],
    'cover'    => '/LASK/' . (!empty($s['portada_cancion']) ? $s['portada_cancion'] : $albumCover),
    'letra'    => $s['letra_cancion']    ?? null,
    'fonetico' => $s['texto_fonetico']   ?? null,
], $playableSongs)) ?>;

var albumIndex = 0;

function albumPlay(idx){
    if(idx === null || idx === undefined || !albumQueue[idx]) return;
    albumIndex = idx;
    var t = albumQueue[idx];

    var audio = document.getElementById('albumAudio');
    audio.src = t.src;
    audio.play();

    document.getElementById('nowPlayingTitle').textContent = t.title;
    document.getElementById('nowPlaying').style.display = 'block';
    document.getElementById('lyricText').textContent     = t.letra    || 'Texto no disponible';
    document.getElementById('lyricPhonetic').textContent = t.fonetico || 'Texto no disponible';

}

function albumNext(){
    if(albumIndex < albumQueue.length - 1) albumPlay(albumIndex + 1);
}

function albumPrev(){
    if(albumIndex > 0) albumPlay(albumIndex - 1);
}

document.addEventListener('DOMContentLoaded', function(){
    document.getElementById('albumAudio').addEventListener('ended', albumNext);
});
</script>