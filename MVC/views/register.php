<h1>Crear cuenta</h1>

<form action="/LASK/public/index.php/register" method="POST">

<label>Email</label>
<input type="email" name="email" required>

<label>Nombre usuario</label>
<input type="text" name="username" required>

<label>Password</label>
<input type="password" name="password" required>

<label>País</label>
<select name="pais" required>
<option value="1">Bolivia</option>
</select>

<label>Rol</label>
<select name="rol" required>
<option value="3">Listener</option>
<option value="2">Artista</option>
</select>

<label>Nombre artístico (solo artista)</label>
<input type="text" name="nombre_artistico">

<label>
<input type="checkbox" name="terms" required>
Acepto los Términos y condiciones
</label>

<button type="submit">Crear cuenta</button>

</form>

<a href="/LASK/public/index.php/login">Iniciar sesión</a>