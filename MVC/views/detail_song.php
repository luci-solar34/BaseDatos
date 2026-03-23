<?php
$pageTitle = $song['nombre_cancion'] . ' - LASK';
?>
<link rel="stylesheet" href="<?= htmlspecialchars(rtrim(dirname(BASE_URL), '/\\') . '/css/detail_song.css') ?>">
 
<div class="song-page">
 
    <!-- CONTENEDOR PRINCIPAL -->
    <div class="song-container">
 
        <!-- HEADER: PORTADA + INFO -->
        <div class="song-header">
            <img src="/LASK/<?= htmlspecialchars($songCover) ?>" alt="Portada de la canción" class="song-cover">
 
            <div class="song-info">
                <h1 class="song-title"><?= htmlspecialchars($song['nombre_cancion']) ?></h1>
 
                <p class="song-meta">
                    <strong>Artista:</strong>
                    <a href="<?= BASE_URL ?>/artist?id=<?= (int)$song['id_artista'] ?>">
                        <?= htmlspecialchars($song['nombre_artistico']) ?>
                    </a>
                </p>
 
                <?php if($hasAlbum): ?>
                    <p class="song-meta">
                        <strong>Álbum:</strong>
                        <a href="<?= BASE_URL ?>/album?id=<?= (int)$song['id_album'] ?>">
                            <?= htmlspecialchars($song['nombre_album']) ?>
                        </a>
                    </p>
                <?php endif; ?>
 
                <?php if(!empty($tags)): ?>
                    <p class="song-meta">
                        <strong>Tags:</strong>
                        <?php foreach($tags as $index => $tag): ?>
                            <?= $index > 0 ? ', ' : '' ?>
                            <a href="<?= BASE_URL ?>/tag?id=<?= (int)$tag['id_tag'] ?>"><?= htmlspecialchars($tag['nombre_tag']) ?></a>
                        <?php endforeach; ?>
                    </p>
                <?php endif; ?>
 
                <!-- BOTONES DE ACCIÓN -->
                <div class="song-actions">
 
                    <form id="songLikeForm" action="<?= BASE_URL ?>/like" method="POST">
                        <?= $csrfField ?>
                        <input type="hidden" name="song_id" value="<?= (int)$song['id_cancion'] ?>">
                        <button type="submit" class="btn" id="songLikeButton">
                            <span id="songLikeLabel"><?= htmlspecialchars($songLikeLabel) ?></span>
                            (<span id="songLikeCount"><?= (int)$songLikeCount ?></span>)
                        </button>
                    </form>
 
                    <?php if($canEditSong): ?>
                        <a href="<?= BASE_URL ?>/artist/edit-song?id=<?= (int)$song['id_cancion'] ?>" class="btn">Editar Canción</a>
                    <?php endif; ?>
 
                </div>
 
                <!-- REPRODUCTOR -->
                <audio id="songAudio" controls>
                    <source src="/LASK/<?= htmlspecialchars($song['path_link']) ?>" type="audio/mpeg">
                </audio>
 
            </div>
        </div>
 
        <!-- LETRA Y FONÉTICO -->
        <div class="lyrics-grid">
 
            <div class="lyrics-box">
                <h3>Letra</h3>
                <pre><?= htmlspecialchars($lyricsText) ?></pre>
            </div>
 
            <div class="lyrics-box">
                <h3>Texto fonético</h3>
                <pre><?= htmlspecialchars($phoneticText) ?></pre>
            </div>
 
        </div>
 
        <!-- VOLVER -->
        <div class="back-link">
            <a href="<?= htmlspecialchars(rtrim(dirname(BASE_URL), '/\\')) ?>">← Volver al inicio</a>
        </div>
 
    </div>
 
</div>
 
<script src="<?= htmlspecialchars(rtrim(dirname(BASE_URL), '/\\') . '/js/detail_song.js') ?>"></script>