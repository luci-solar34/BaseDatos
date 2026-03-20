<link rel="stylesheet" href="/LASK/public/css/create-playlist.css">
<link href="https://fonts.googleapis.com/css2?family=VT323&display=swap" rel="stylesheet">

<div class="playlist-container">

    <h1 class="title">Crear playlist</h1>

    <div class="card">

        <!-- PORTADA FUNCIONAL -->
        <div class="left">
            <label class="cover">
                <span>+ Insertar portada</span>
                <input type="file" name="cover" accept="image/*">
                <img id="preview" class="preview">
            </label>
        </div>

        <!-- FORM -->
        <div class="center">
            <form action="<?= BASE_URL ?>/playlist/create" method="POST" enctype="multipart/form-data">

                <label>Nombre de la playlist</label>
                <input type="text" name="name" required class="input">

                <label>Privacidad</label>
                <select name="privacy" class="input">
                    <option value="1">Pública</option>
                    <option value="0">Privada</option>
                </select>

                <button type="submit" class="btn-primary">
                    Crear playlist
                </button>

            </form>
        </div>

    </div>

    <a href="/LASK/public" class="back">Volver</a>

</div>

<!-- PREVIEW DE IMAGEN -->
<script>
    const input = document.querySelector('input[name="cover"]');
    const preview = document.getElementById('preview');

    input.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        }
    });
</script>