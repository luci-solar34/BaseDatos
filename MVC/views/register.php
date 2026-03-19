<h1>Crear cuenta</h1>

<?php if(isset($_SESSION['flash_message'])): ?>
    <div class="alert <?= (isset($_SESSION['flash_message_type']) && $_SESSION['flash_message_type'] === 'success') ? 'alert-success' : 'alert-error' ?>">
        <?= htmlspecialchars($_SESSION['flash_message']) ?>
    </div>
    <?php unset($_SESSION['flash_message'], $_SESSION['flash_message_type']); ?>
<?php endif; ?>

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

<script>
document.addEventListener('DOMContentLoaded', function(){
    const roleSelect = document.getElementById('rol');
    const artistInput = document.getElementById('nombre_artistico');
    const warning = document.getElementById('artist-warning');

    function syncArtistField(){
        const isArtist = roleSelect.value === '2';

        artistInput.disabled = !isArtist;

        if(!isArtist){
            if(artistInput.value.trim() !== ''){
                warning.style.display = 'block';
            } else {
                warning.style.display = 'none';
            }
        } else {
            warning.style.display = 'none';
        }
    }

    roleSelect.addEventListener('change', syncArtistField);
    artistInput.addEventListener('input', function(){
        if(roleSelect.value !== '2' && artistInput.value.trim() !== ''){
            warning.style.display = 'block';
        } else {
            warning.style.display = 'none';
        }
    });

    syncArtistField();
});
</script>

<a href="/LASK/public/index.php/login">Iniciar sesión</a>