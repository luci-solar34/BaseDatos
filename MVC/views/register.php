<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear cuenta</title>

    <!-- CSS -->
    <link rel="stylesheet" href="/LASK/public/css/styles.css">

    <!-- Mensaje flash de PHP (rama de tu compañera) -->
    <?php if(isset($_SESSION['flash_message'])): ?>
        <div class="alert <?= (isset($_SESSION['flash_message_type']) && $_SESSION['flash_message_type'] === 'success') ? 'alert-success' : 'alert-error' ?>">
            <?= htmlspecialchars($_SESSION['flash_message']) ?>
        </div>
        <?php unset($_SESSION['flash_message'], $_SESSION['flash_message_type']); ?>
    <?php endif; ?>

    <!-- FUENTE PIXEL -->
    <link href="https://fonts.googleapis.com/css2?family=VT323&display=swap" rel="stylesheet">
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo">
        <img src="/LASK/public/img/logo.png" alt="Logo">
    </div>

    <div class="nav-buttons">
        <a href="/LASK/public/index.php/login" class="btn">Iniciar sesión</a>
    </div>
</div>

<!-- CONTENEDOR -->
<div class="contenedor">
    <div class="register-box">

        <h1 class="titulo-pixel">Crear cuenta</h1>

        <form action="/LASK/public/index.php/register" method="POST">

            <div class="form-grid">

                <!-- IZQUIERDA -->
                <div>
                    <label>Email</label>
                    <input type="email" name="email" required>

                    <label>Nombre usuario</label>
                    <input type="text" name="username" required>

                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>

                <!-- DERECHA -->
                <div>
                    <label>País</label>
                    <select name="pais" required>
                        <option value="">Selecciona un país</option>
                        <option value="Bolivia" selected>Bolivia</option>
                        <option value="Argentina">Argentina</option>
                        <option value="Brasil">Brasil</option>
                        <option value="Chile">Chile</option>
                        <option value="Colombia">Colombia</option>
                        <option value="Perú">Perú</option>
                        <option value="México">México</option>
                        <option value="España">España</option>
                        <option value="Estados Unidos">Estados Unidos</option>
                    </select>

                    <!-- Selección de rol -->
                    <label>Rol</label>
                    <select name="rol" id="rol" required>
                        <option value="3">Listener</option>
                        <option value="2">Artista</option>
                    </select>

                    <!-- Nombre artístico (solo artista) -->
                    <label>Nombre artístico (solo artista)</label>
                    <input type="text" name="nombre_artistico" id="nombre_artistico">

                    <p id="artist-warning" class="alert alert-error" style="display:none;">
                        Solo los artistas pueden tener nombre artístico. Si quieres ser artista, cambia el rol.
                    </p>

                    <!-- Botones de rol estilo visual -->
                    <div class="rol-buttons">
                        <label>
                            <input type="radio" name="rol" value="3" checked hidden onclick="mostrarArtista(false)">
                            <div class="rol-btn">Listener</div>
                        </label>

                        <label>
                            <input type="radio" name="rol" value="2" hidden onclick="mostrarArtista(true)">
                            <div class="rol-btn">Artista</div>
                        </label>
                    </div>

                    <div id="campo-artista" class="campo-artista">
                        <label>Nombre artístico</label>
                        <input type="text" name="nombre_artistico">
                    </div>
                </div>

            </div>

            <!-- TERMINOS -->
            <label class="terms">
                <input type="checkbox" name="terms" required>
                Acepto los 
                <a href="#" onclick="abrirModal(event)">Términos y condiciones</a>
            </label>

            <!-- BOTON -->
            <button type="submit" class="btn-submit">Crear cuenta</button>

        </form>

    </div>
</div>

<!-- MODAL -->
<div id="modal-terminos" class="modal">
    <span class="cerrar" onclick="cerrarModal()">&times;</span>
    <img src="/LASK/public/img/terminos.png" class="modal-contenido">
</div>

<!-- SCRIPT -->
<script>
function mostrarArtista(mostrar) {
    const campo = document.getElementById("campo-artista");
    if (mostrar) {
        campo.classList.add("activo");
    } else {
        campo.classList.remove("activo");
    }
}

function abrirModal(event) {
    event.preventDefault();
    document.getElementById("modal-terminos").style.display = "block";
}

function cerrarModal() {
    document.getElementById("modal-terminos").style.display = "none";
}

window.onclick = function(event) {
    let modal = document.getElementById("modal-terminos");
    if (event.target === modal) {
        modal.style.display = "none";
    }
}

// Validación y sincronización campo artista
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

</body>
</html>