<h1>Editar Álbum</h1>

<form action="/LASK/public/index.php/artist/edit-album?id=<?= $album['id_album'] ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="album_id" value="<?= $album['id_album'] ?>">

    <label>Nombre del Álbum:</label>
    <input type="text" name="nombre" value="<?= htmlspecialchars($album['nombre_album']) ?>" required><br>

    <label>Descripción:</label>
    <textarea name="descripcion"><?= htmlspecialchars($album['descripcion_album'] ?? '') ?></textarea><br>

    <?php if(!empty($album['portada_album'])): ?>
        <img src="/LASK/<?= $album['portada_album'] ?>" width="180"><br>
    <?php endif; ?>

    <label>Nueva portada (opcional):</label>
    <input type="file" name="portada" accept=".jpg,.png"><br><br>

    <?php if(!empty($availableSongs)): ?>
        <label>Mover canción existente a este álbum:</label>
        <select name="existing_song_id">
            <option value="">No mover ninguna canción</option>
            <?php foreach($availableSongs as $song): ?>
                <option value="<?= $song['id_cancion'] ?>">
                    <?= htmlspecialchars($song['nombre_cancion']) ?>
                    <?= !empty($song['id_album']) ? '(transferir)' : '(sin álbum)' ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>
    <?php else: ?>
        <p>No tienes canciones disponibles para mover. Puedes subir una nueva abajo.</p>
    <?php endif; ?>

    <button type="submit">Guardar Cambios</button>
</form>

<p>
    <a href="/LASK/public/index.php/album/add-songs?id=<?= $album['id_album'] ?>">
        <button>Subir Nueva Canción al Álbum</button>
    </a>
</p>

<a href="/LASK/public/index.php/album?id=<?= $album['id_album'] ?>">Volver al álbum</a>