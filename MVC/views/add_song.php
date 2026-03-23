<style>
    .add-song-page {
        max-width: 900px;
        margin: 2rem auto;
        padding: 1rem;
    }

    .add-song-title {
        margin-bottom: 1rem;
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
    }

    .song-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 1rem;
    }

    .song-card {
        background: linear-gradient(145deg, #ffffff 0%, #f6f9ff 100%);
        border: 1px solid #dbe4f0;
        border-radius: 14px;
        padding: 1rem;
        box-shadow: 0 8px 20px rgba(17, 24, 39, 0.08);
    }

    .song-name {
        margin: 0 0 0.9rem 0;
        color: #111827;
        font-weight: 600;
        line-height: 1.35;
    }

    .add-song-btn {
        width: 100%;
        border: none;
        border-radius: 10px;
        padding: 0.65rem 0.9rem;
        background: #0f766e;
        color: #ffffff;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
    }

    .add-song-btn:hover {
        background: #0d9488;
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(15, 118, 110, 0.28);
    }

    .add-song-btn:active {
        transform: translateY(0);
    }
</style>

<section class="add-song-page">
    <h1 class="add-song-title">Agregar canciones</h1>

    <div class="song-grid">
        <?php foreach($songs as $song): ?>
            <article class="song-card">
                <p class="song-name"><?= htmlspecialchars($song['nombre_cancion']) ?></p>

                <form method="POST" action="<?= htmlspecialchars(BASE_URL . '/playlist/add-song') ?>">
                    <?= $csrfField ?>
                    <input type="hidden" name="playlist" value="<?= (int)$playlist ?>">
                    <input type="hidden" name="song" value="<?= (int)$song['id_cancion'] ?>">

                    <button class="add-song-btn">Agregar</button>
                </form>
            </article>
        <?php endforeach; ?>
    </div>
</section>