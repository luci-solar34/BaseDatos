<?php
$pageTitle = $song['nombre_cancion'] . ' - LASK';
?>

<h1><?= htmlspecialchars($song['nombre_cancion']) ?></h1>

<p>
    <strong>Artista:</strong> 
    <a href="<?= BASE_URL ?>/artist?id=<?= (int)$song['id_artista'] ?>">
        <?= htmlspecialchars($song['nombre_artistico']) ?>
    </a>
</p>

<?php if($hasAlbum): ?>
    <p>
        <strong>Álbum:</strong> 
        <a href="<?= BASE_URL ?>/album?id=<?= (int)$song['id_album'] ?>">
            <?= htmlspecialchars($song['nombre_album']) ?>
        </a>
    </p>
<?php endif; ?>

<p>
    <img src="/LASK/<?= htmlspecialchars($songCover) ?>" alt="Portada de la canción" width="220" style="border-radius:8px; object-fit:cover;">
</p>

<?php if(!empty($tags)): ?>
    <p>
        <strong>Tags:</strong>
        <?php foreach($tags as $index => $tag): ?>
            <?= $index > 0 ? ', ' : '' ?><a href="<?= BASE_URL ?>/tag?id=<?= (int)$tag['id_tag'] ?>"><?= htmlspecialchars($tag['nombre_tag']) ?></a>
        <?php endforeach; ?>
    </p>
<?php endif; ?>

<audio id="songAudio" controls>
    <source src="/LASK/<?= htmlspecialchars($song['path_link']) ?>" type="audio/mpeg">
</audio>



<?php if($canEditSong): ?>
    <p>
        <a href="<?= BASE_URL ?>/artist/edit-song?id=<?= (int)$song['id_cancion'] ?>" class="btn">Editar Canción</a>
    </p>
<?php endif; ?>

<form id="songLikeForm" action="<?= BASE_URL ?>/like" method="POST">
    <input type="hidden" name="song_id" value="<?= (int)$song['id_cancion'] ?>">
    <button type="submit" id="songLikeButton">
        <span id="songLikeLabel"><?= htmlspecialchars($songLikeLabel) ?></span>
        (<span id="songLikeCount"><?= (int)$songLikeCount ?></span>)
    </button>
</form>

<h3>Letra</h3>
<pre style="white-space:pre-wrap; background:#f8f8f8; padding:8px; border-radius:6px;"><?= htmlspecialchars($lyricsText) ?></pre>

<h3>Texto fonético</h3>
<pre style="white-space:pre-wrap; background:#f8f8f8; padding:8px; border-radius:6px;"><?= htmlspecialchars($phoneticText) ?></pre>

<br>
<a href="<?= substr(BASE_URL, 0, strrpos(BASE_URL, '/')) ?>">← Volver al inicio</a>
<script src="<?= substr(BASE_URL, 0, strrpos(BASE_URL, '/')) ?>/js/detail_song.js"></script>