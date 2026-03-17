<h1>Publicar un álbum</h1>

<a href="/LASK/public/index.php/profile?id=<?= $_SESSION['user_id'] ?>">← Volver al perfil</a>

<form action="/LASK/public/index.php/artist/album/create" method="POST" enctype="multipart/form-data" style="margin-top: 16px;">
    <div>
        <label for="nombre">Nombre del álbum</label><br>
        <input type="text" name="nombre" id="nombre" required>
    </div>

    <div style="margin-top: 10px;">
        <label for="descripcion">Descripción (opcional)</label><br>
        <textarea name="descripcion" id="descripcion" rows="4" cols="40" placeholder="Descripción del álbum"></textarea>
    </div>

    <div style="margin-top: 10px;">
        <label for="portada">Portada del álbum</label><br>
        <input type="file" name="portada" id="portada" accept="image/*">
    </div>

    <div style="margin-top: 16px;">
        <button type="submit">Publicar álbum</button>
    </div>
</form>
