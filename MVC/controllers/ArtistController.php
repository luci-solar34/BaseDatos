<?php

require_once "../config/database.php";
require_once "../MVC/models/Song.php";
require_once "../MVC/models/Album.php";
require_once "../MVC/models/Artist.php";
require_once "../MVC/models/Tag.php";

class ArtistController {

    private $song;
    private $album;
    private $artist;
    private $tag;

    public function __construct(){

        $database = new Database();
        $db = $database->connect();

        $this->song = new Song($db);
        $this->album = new Album($db);
        $this->artist = new Artist($db);
        $this->tag = new Tag($db);
    }

    public function profile($id){

        $songs = $this->song->getByArtist($id);

        require "../MVC/views/artist.php";
    }

    public function createAlbum(){
        $user = $_SESSION['user_id'] ?? null;

        if(!$user || !$this->artist->exists($user)){
            die("Acceso denegado");
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $file = $_FILES['portada'] ?? null;
            $path = null;

            if($file && $file['tmp_name']){
                if(!is_dir(__DIR__ . '/../../photos_albums')){
                    mkdir(__DIR__ . '/../../photos_albums', 0755, true);
                }

                $filename = time() . "_" . basename($file['name']);
                $destination = __DIR__ . '/../../photos_albums/' . $filename;
                move_uploaded_file($file['tmp_name'], $destination);
                $path = "photos_albums/" . $filename;
            }

            $data = [
                "nombre" => $_POST['nombre'],
                "portada" => $path,
                "artista" => $user,
                "descripcion" => $_POST['descripcion'] ?? null
            ];

            $albumId = $this->album->create($data);

            // si se creó el álbum, redirigimos para agregar canciones al álbum recién creado
            if($albumId){
                header("Location: /LASK/public/index.php/artist/song/create?album_id=" . urlencode($albumId));
            } else {
                header("Location: /LASK/public/index.php/profile?id=".$user);
            }
            exit;
        }

        require "../MVC/views/artist_album_create.php";
    }

    public function createSong(){
        $user = $_SESSION['user_id'] ?? null;

        if(!$user || !$this->artist->exists($user)){
            die("Acceso denegado");
        }

        $albums = $this->album->getByArtist($user);
        $tags = $this->tag->getAllTags();
        $selectedAlbum = $_GET['album_id'] ?? null;

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $file = $_FILES['portada'] ?? null;
            $path = null;

            if($file && $file['tmp_name']){
                if(!is_dir(__DIR__ . '/../../photos_songs')){
                    mkdir(__DIR__ . '/../../photos_songs', 0755, true);
                }

                $filename = time() . "_" . basename($file['name']);
                $destination = __DIR__ . '/../../photos_songs/' . $filename;
                move_uploaded_file($file['tmp_name'], $destination);
                $path = "photos_songs/" . $filename;
            }

            $data = [
                "nombre" => $_POST['nombre'],
                "numero_pista" => $_POST['pista'],
                "path" => $_POST['path'],
                "album" => $_POST['album'] ?: null,
                "artista" => $user,
                "portada" => $path,
                "tags" => $_POST['tags'] ?? []
            ];

            $this->song->create($data);

            header("Location: /LASK/public/index.php/profile?id=".$user);
            exit;
        }

        require "../MVC/views/artist_song_create.php";
    }
}