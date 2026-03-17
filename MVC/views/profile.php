<h1>Perfil</h1>

<p>Haz click en la imagen para cambiar tu foto</p>

<form action="/LASK/public/index.php/update_pfp" method="POST" enctype="multipart/form-data">

<label for="pfp">

<img src="/LASK/<?= $user['pfp'] ?>" width="120" style="cursor:pointer;border-radius:50%;">

</label>

<input type="file" name="pfp" id="pfp" style="display:none" onchange="this.form.submit()">

</form>

<p>Usuario: <?= $user['nombre_usuario'] ?></p>

<p>Email: <?= $user['email'] ?></p>

<p>Bio: <?= $user['bio'] ?></p>

<a href="/LASK/public">Volver al Home</a>