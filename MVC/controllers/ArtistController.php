<?php

require_once "../config/database.php";
require_once "../MVC/models/Song.php";
require_once "../MVC/models/Album.php";
require_once "../MVC/models/Comment.php";
require_once "../MVC/models/Tag.php";

class ArtistController {

    private $song;
    private $album;
    private $comment;
    private $tag;

    public function __construct(){

        $database = new Database();
        $db = $database->connect();

        $this->song = new Song($db);
        $this->album = new Album($db);
        $this->comment = new Comment($db);
        $this->tag = new Tag($db);
    }

    public function profile($id){
        // En caso de que esta ruta se use, redirigimos a la vista de artista estándar.
        require_once "../MVC/controllers/DetailController.php";

        $controller = new DetailController();
        $controller->artist($id);
    }

    public function uploadSong(){

        $data = [
            "nombre" => $_POST['nombre'],
            "numero_pista" => $_POST['pista'],
            "path" => $_POST['path'],
            "album" => $_POST['album'],
            "artista" => $_SESSION['user_id']
        ];

        $this->song->create($data);
    }

    public function showCreateAlbum($artist_id){

        require "../MVC/views/create_album.php";
    }

    public function createAlbum(){

        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $artista = $_SESSION['user_id'];

        // Subir portada
        $portada = '';
        if(isset($_FILES['portada']) && $_FILES['portada']['error'] == 0){

            $ext = pathinfo($_FILES['portada']['name'], PATHINFO_EXTENSION);
            if(in_array($ext, ['jpg', 'png'])){

                $portada = 'Photos/' . uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['portada']['tmp_name'], '../' . $portada);
            }
        }

        $data = [
            "nombre" => $nombre,
            "descripcion" => $descripcion,
            "portada" => $portada,
            "artista" => $artista
        ];

        $album_id = $this->album->create($data);

        header("Location: /LASK/public/index.php/album/add-songs?id=" . $album_id);
        exit;
    }

    public function showCreateSong($artist_id){

        $tags = $this->tag->getAllTags();

        require "../MVC/views/create_song.php";
    }

    public function showEditAlbum($album_id){

        $album = $this->album->getById($album_id);
        $artistId = $_SESSION['user_id'] ?? null;

        if(!$album || !$artistId || (int)$album['id_artista'] !== (int)$artistId){
            die("Álbum no encontrado o sin permisos");
        }

        $availableSongs = $this->song->getByArtistOutsideAlbum($artistId, $album_id);

        require "../MVC/views/edit_album.php";
    }

    public function editAlbum(){

        $albumId = $_POST['album_id'] ?? null;
        $artistId = $_SESSION['user_id'] ?? null;
        $album = $this->album->getById($albumId);

        if(!$album || !$artistId || (int)$album['id_artista'] !== (int)$artistId){
            die("Álbum no encontrado o sin permisos");
        }

        $portada = $album['portada_album'];
        if(isset($_FILES['portada']) && $_FILES['portada']['error'] == 0){

            $ext = pathinfo($_FILES['portada']['name'], PATHINFO_EXTENSION);
            if(in_array($ext, ['jpg', 'png'])){
                $portada = 'Photos/' . uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['portada']['tmp_name'], '../' . $portada);
            }
        }

        $data = [
            "album" => $albumId,
            "artista" => $artistId,
            "nombre" => $_POST['nombre'],
            "descripcion" => $_POST['descripcion'],
            "portada" => $portada
        ];

        $this->album->update($data);

        if(!empty($_POST['existing_song_id'])){
            $this->song->moveToAlbum($_POST['existing_song_id'], $albumId, $artistId);
        }

        header("Location: /LASK/public/index.php/album?id=" . $albumId);
        exit;
    }

    public function createSong(){

        $nombre = $_POST['nombre'];
        $artista = $_SESSION['user_id'];
        $letra = $_POST['letra_cancion'] ?? '';
        $fonetica = $_POST['texto_fonetico'] ?? '';

        // Subir archivo
        $path = '';
        if(isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0){

            $ext = strtolower(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION));
            if($ext == 'mp3'){

                $path = 'music/' . uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['archivo']['tmp_name'], '../' . $path);
            }
        }

        // Subir portada de canción
        $portada = null;
        if(isset($_FILES['portada']) && $_FILES['portada']['error'] == 0){
            $extPortada = strtolower(pathinfo($_FILES['portada']['name'], PATHINFO_EXTENSION));
            if(in_array($extPortada, ['jpg', 'jpeg', 'png', 'webp'])){
                $portada = 'Photos/' . uniqid() . '.' . $extPortada;
                move_uploaded_file($_FILES['portada']['tmp_name'], '../' . $portada);
            }
        }

        $data = [
            "nombre" => $nombre,
            "numero_pista" => null,
            "album" => null,
            "path" => $path,
            "portada" => $portada,
            "artista" => $artista
        ];

        $created = $this->song->create($data);

        if($created){
            $songId = (int) $this->song->getConnection()->lastInsertId();
            $tagIds = $_POST['tags'] ?? [];
            $this->tag->syncSongTags($songId, $tagIds);
            $this->song->saveLyrics($songId, $letra, $fonetica);
        }

        header("Location: /LASK/public/index.php/artist?id=" . $artista);
        exit;
    }

    public function showEditSong($song_id){

        $song = $this->song->getById($song_id);
        $artistId = $_SESSION['user_id'] ?? null;

        if(!$song || !$artistId || (int)$song['id_artista'] !== (int)$artistId){
            die("Canción no encontrada o sin permisos");
        }

        $albums = $this->album->getByArtist($artistId);
        $tags = $this->tag->getAllTags();
        $selectedTagIds = $this->tag->getSongTagIds($song_id);

        require "../MVC/views/edit_song.php";
    }

    public function editSong(){

        $songId = $_POST['song_id'] ?? null;
        $artistId = $_SESSION['user_id'] ?? null;
        $song = $this->song->getById($songId);

        if(!$song || !$artistId || (int)$song['id_artista'] !== (int)$artistId){
            die("Canción no encontrada o sin permisos");
        }

        $albumId = $_POST['album_id'] !== '' ? $_POST['album_id'] : null;

        if($albumId){
            $targetAlbum = $this->album->getById($albumId);

            if(!$targetAlbum || (int)$targetAlbum['id_artista'] !== (int)$artistId){
                die("Álbum inválido para esta canción");
            }
        }

        $portada = $song['portada_cancion'] ?? null;
        if(isset($_FILES['portada']) && $_FILES['portada']['error'] == 0){
            $extPortada = strtolower(pathinfo($_FILES['portada']['name'], PATHINFO_EXTENSION));
            if(in_array($extPortada, ['jpg', 'jpeg', 'png', 'webp'])){
                $portada = 'Photos/' . uniqid() . '.' . $extPortada;
                move_uploaded_file($_FILES['portada']['tmp_name'], '../' . $portada);
            }
        }

        $data = [
            "song" => $songId,
            "artista" => $artistId,
            "nombre" => $_POST['nombre'],
            "album" => $albumId,
            "portada" => $portada
        ];

        $this->song->update($data);

        $tagIds = $_POST['tags'] ?? [];
        $this->tag->syncSongTags($songId, $tagIds);

        header("Location: /LASK/public/index.php/song?id=" . $songId);
        exit;
    }

    public function addComment(){

        $artista = $_POST['artist_id'];
        $comentario = $_POST['comment'];
        $usuario = $_SESSION['user_id'];

        $data = [
            "usuario" => $usuario,
            "artista" => $artista,
            "comentario" => $comentario
        ];

        $this->comment->create($data);

        header("Location: /LASK/public/index.php/artist?id=" . $artista);
        exit;
    }

}