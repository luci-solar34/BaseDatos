<h1>Crear cuenta</h1>

<?php if($flashMessage): ?>
    <div class="alert <?= $flashMessageType === 'success' ? 'alert-success' : 'alert-error' ?>">
        <?= htmlspecialchars($flashMessage) ?>
    </div>
<?php endif; ?>

<form action="<?= htmlspecialchars(BASE_URL . '/register') ?>" method="POST">

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
<select name="rol" id="rol" required>
<option value="3">Listener</option>
<option value="2">Artista</option>
</select>

<label>Nombre artístico (solo artista)</label>
<input type="text" name="nombre_artistico" id="nombre_artistico">

<p id="artist-warning" class="alert alert-error" style="display:none;">
Solo los artistas pueden tener nombre artístico. Si quieres ser artista, cambia el rol.
</p>

<label>
<input type="checkbox" name="terms" required>
Acepto los Términos y condiciones
</label>

<button type="submit">Crear cuenta</button>

</form>

<script src="<?= htmlspecialchars(BASE_URL . '/../js/register.js') ?>"></script>

<a href="<?= htmlspecialchars(BASE_URL . '/login') ?>">Iniciar sesión</a>