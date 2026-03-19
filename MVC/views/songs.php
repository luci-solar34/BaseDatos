<div class="song-page">

    <div class="song-card">

        <!-- PORTADA -->
        <div class="cover">
            <img src="/LASK/<?= $song['portada_cancion'] ?>" alt="Portada de <?= $song['nombre_cancion'] ?>">
        </div>

        <!-- INFORMACIÓN -->
        <div class="info">
            <h1><?= $song['nombre_cancion'] ?></h1>
            <p class="artist"><?= $song['nombre_artistico'] ?></p>

            <!-- PLAYER -->
            <div class="player">
                <audio controls>
                    <source src="/LASK/<?= $song['path_link'] ?>" type="audio/mpeg">
                </audio>
            </div>

        </div>

    </div>

</div>