<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear cuenta</title>

    <!-- CSS -->
    <link rel="stylesheet" href="/LASK/public/css/styles.css?v=login-register-fix-1">

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
        <a href="<?= htmlspecialchars(BASE_URL . '/login') ?>" class="btn">Iniciar sesión</a>
    </div>
</div>

<!-- CONTENEDOR -->
<div class="contenedor">

    <div class="register-box">

        <h1 class="titulo-pixel">Crear cuenta</h1>

        <!-- FLASH MESSAGE -->
        <?php if($flashMessage): ?>
            <div class="alert <?= $flashMessageType === 'success' ? 'alert-success' : 'alert-error' ?>">
                <?= htmlspecialchars($flashMessage) ?>
            </div>
        <?php endif; ?>

        <form action="<?= htmlspecialchars(BASE_URL . '/register') ?>" method="POST">

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
                        <option value="1">Bolivia</option>
                    </select>

                    <label>Rol</label>

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

                    <!-- SOLO UNA VEZ (NO DUPLICADO) -->
                    <div id="campo-artista" class="campo-artista">
                        <label>Nombre artístico</label>
                        <input type="text" name="nombre_artistico" id="nombre_artistico">
                    </div>

                    <!-- WARNING -->
                    <p id="artist-warning" class="alert alert-error" style="display:none;">
                        Solo los artistas pueden tener nombre artístico. Si quieres ser artista, cambia el rol.
                    </p>

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
    const warning = document.getElementById("artist-warning");

    if (mostrar) {
        campo.classList.add("activo");
        warning.style.display = "none";
    } else {
        campo.classList.remove("activo");

        const input = document.getElementById("nombre_artistico");
        if (input.value.trim() !== "") {
            warning.style.display = "block";
        } else {
            warning.style.display = "none";
        }
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
</script>

</body>
</html>