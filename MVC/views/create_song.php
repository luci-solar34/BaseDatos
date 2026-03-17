<h1>Crear Canción</h1>

<form action="/LASK/public/index.php/artist/create-song?id=<?= $_GET['id'] ?>" method="POST" enctype="multipart/form-data">
    <label>Nombre de la Canción:</label>
    <input type="text" name="nombre" required><br>

    <label>Archivo de Música (mp3):</label>
    <input type="file" name="archivo" accept=".mp3" required><br>

    <button type="submit">Crear Canción</button>
</form>

<a href="/LASK/public/index.php/artist?id=<?= $_GET['id'] ?>">Volver</a></content>
<parameter name="filePath">c:\laragon\www\lask\MVC\views\create_song.php