<div style="max-width: 600px; margin: 0 auto; padding: 20px;">
    <h1>Nuevo Álbum Creado</h1>

    <!-- Mostrar detalles del álbum -->
    <div style="border: 1px solid #ddd; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
        <?php if(!empty($album['portada_album'])): ?>
            <img src="/LASK/<?= $album['portada_album'] ?>" style="max-width: 200px; height: auto; border-radius: 8px; margin-bottom: 20px;">
        <?php endif; ?>

        <h2><?= htmlspecialchars($album['nombre_album']) ?></h2>
        <p><strong>Descripción:</strong></p>
        <p><?= htmlspecialchars($album['descripcion_album']) ?></p>
    </div>

    <!-- Formulario para agregar canciones -->
    <h3>Agregar Canciones</h3>
    <form action="/LASK/public/index.php/album/add-songs?id=<?= $album['id_album'] ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="album_id" value="<?= $album['id_album'] ?>">

        <label for="nombre">Nombre de la Canción:</label>
        <input type="text" id="nombre" name="nombre" required><br><br>

        <label for="archivo">Archivo de Música (mp3):</label>
        <input type="file" id="archivo" name="archivo" accept=".mp3" required><br><br>

        <button type="submit">Agregar Canción</button>
    </form>

    <!-- Opciones -->
    <hr style="margin: 30px 0;">
    <div style="display: flex; gap: 10px;">
        <a href="/LASK/public/index.php/album?id=<?= $album['id_album'] ?>" style="padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 5px;">Ver Álbum Completo</a>
        <a href="/LASK/public/index.php/artist?id=<?= $_SESSION['user_id'] ?>" style="padding: 10px 20px; background: #008CBA; color: white; text-decoration: none; border-radius: 5px;">Volver al Perfil</a>
    </div>
</div>