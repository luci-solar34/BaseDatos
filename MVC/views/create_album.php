<h1>Crear Álbum</h1>

<form action="/LASK/public/index.php/artist/create-album?id=<?= $_GET['id'] ?>" method="POST" enctype="multipart/form-data">
    <label>Nombre del Álbum:</label>
    <input type="text" name="nombre" required><br>

    <label>Descripción:</label>
    <textarea name="descripcion"></textarea><br>

    <label>Portada (jpg/png):</label>
    <input type="file" name="portada" accept=".jpg,.png" required><br>

    <button type="submit">Crear Álbum</button>
</form>

<a href="/LASK/public/index.php/artist?id=<?= $_GET['id'] ?>">Volver</a></content>
<parameter name="filePath">c:\laragon\www\lask\MVC\views\create_album.php