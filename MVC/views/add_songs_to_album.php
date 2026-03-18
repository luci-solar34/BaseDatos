<h1>Agregar Canción a Álbum: <?= $album['nombre_album'] ?></h1>

<form action="/LASK/public/index.php/album/add-songs?id=<?= $album['id_album'] ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="album_id" value="<?= $album['id_album'] ?>">

    <label>Nombre de la Canción:</label>
    <input type="text" name="nombre" required><br>

    <label>Archivo de Música (mp3):</label>
    <input type="file" name="archivo" accept=".mp3" required><br>

    <button type="submit">Agregar Canción</button>
</form>

<a href="/LASK/public/index.php/album?id=<?= $album['id_album'] ?>">Ver Álbum</a>
<a href="/LASK/public/index.php/artist?id=<?= $_SESSION['user_id'] ?>">Volver al Perfil</a></content>
<parameter name="filePath">c:\laragon\www\lask\MVC\views\add_songs_to_album.php