<h1>Crear Álbum</h1>

<form action="<?= BASE_URL ?>/artist/create-album?id=<?= (int)$artistId ?>" method="POST" enctype="multipart/form-data">
    <label>Nombre del Álbum:</label>
    <input type="text" name="nombre" required><br>

    <label>Descripción:</label>
    <textarea name="descripcion"></textarea><br>

    <label>Portada (jpg/png):</label>
    <input type="file" name="portada" accept=".jpg,.png" required><br>

    <button type="submit">Crear Álbum</button>
</form>

<a href="<?= BASE_URL ?>/artist?id=<?= (int)$artistId ?>">Volver</a>