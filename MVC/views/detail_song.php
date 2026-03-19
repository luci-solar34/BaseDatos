<?php
$pageTitle = $song['nombre_cancion'] . ' - LASK';
?>

<div class="song-page">
    <div class="overlay"></div> <!-- Fondo con opacidad -->
    <div class="song-card">
        <div class="cover">
            <img src="/LASK/<?= htmlspecialchars($song['portada_cancion'] ?? 'Photos/banner_default.png') ?>" alt="Portada" />
        </div>
        <div class="info">
            <h1><?= htmlspecialchars($song['nombre_cancion']) ?></h1>

            <p class="artist">
                <strong>Artista:</strong> 
                <a href="/LASK/public/index.php/artist?id=<?= $song['id_artista'] ?>">
                    <?= htmlspecialchars($song['nombre_artistico']) ?>
                </a>
            </p>

            <?php if($song['nombre_album']): ?>
            <p class="album">
                <strong>Álbum:</strong> 
                <a href="/LASK/public/index.php/album?id=<?= $song['id_album'] ?>">
                    <?= htmlspecialchars($song['nombre_album']) ?>
                </a>
            </p>
            <?php endif; ?>

            <?php if(!empty($tags)): ?>
            <p class="tags">
                <strong>Tags:</strong>
                <?php foreach($tags as $index => $tag): ?>
                    <?= $index > 0 ? ', ' : '' ?><a href="/LASK/public/index.php/tag?id=<?= $tag['id_tag'] ?>"><?= htmlspecialchars($tag['nombre_tag']) ?></a>
                <?php endforeach; ?>
            </p>
            <?php endif; ?>

            <div class="player">
                <audio controls>
                    <source src="/LASK/<?= $song['path_link'] ?>" type="audio/mpeg">
                </audio>
            </div>

            <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $song['id_artista']): ?>
            <p>
                <a href="/LASK/public/index.php/artist/edit-song?id=<?= $song['id_cancion'] ?>">
                    <button class="edit-btn">Editar Canción</button>
                </a>
            </p>
            <?php endif; ?>

            <form action="/LASK/public/index.php/like" method="POST">
                <input type="hidden" name="song_id" value="<?= $song['id_cancion'] ?>">
                <button type="submit" class="like-btn">
                    <?= $user_liked ? '♥ Quitar like' : '♥ Dar like' ?> (<?= $likes['total'] ?? 0 ?>)
                </button>
            </form>

            <h3>Letra</h3>
            <pre class="lyrics"><?= $song['letra_cancion'] ? htmlspecialchars($song['letra_cancion']) : 'Texto no disponible' ?></pre>

            <h3>Texto fonético</h3>
            <pre class="phonetic"><?= $song['texto_fonetico'] ? htmlspecialchars($song['texto_fonetico']) : 'Texto no disponible' ?></pre>

            <br>
            <a href="/LASK/public" class="back-link">← Volver al inicio</a>
        </div>
    </div>
</div>