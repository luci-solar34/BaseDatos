<h1>Crear Playlist</h1>

<form action="/LASK/public/index.php/playlist/create" method="POST">

<label>Nombre de la playlist</label><br>
<input type="text" name="name" required><br><br>

<label>Privacidad</label><br>
<select name="privacy">
    <option value="1">Pública</option>
    <option value="0">Privada</option>
</select><br><br>

<button type="submit">Crear</button>

</form>

<br>

<a href="/LASK/public">Volver</a>