<?php

require_once "../config/database.php";
require_once "../MVC/models/Song.php";
require_once "../MVC/models/Album.php";
require_once "../MVC/models/Comment.php";

class ArtistController {

    private $song;
    private $album;
    private $comment;

    public function __construct(){

        $database = new Database();
        $db = $database->connect();

        $this->song = new Song($db);
        $this->album = new Album($db);
        $this->comment = new Comment($db);
    }

    public function profile($id){

        $songs = $this->song->getByArtist($id);

        require "../MVC/views/artist.php";
    }

    public function uploadSong(){

        session_start();

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

        session_start();

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

        require "../MVC/views/create_song.php";
    }

    public function createSong(){

        session_start();

        $nombre = $_POST['nombre'];
        $artista = $_SESSION['user_id'];

        // Subir archivo
        $path = '';
        if(isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0){

            $ext = pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION);
            if($ext == 'mp3'){

                $path = 'music/' . uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['archivo']['tmp_name'], '../' . $path);
            }
        }

        $data = [
            "nombre" => $nombre,
            "path" => $path,
            "artista" => $artista
        ];

        $this->song->create($data);

        header("Location: /LASK/public/index.php/artist?id=" . $artista);
        exit;
    }

    public function addComment(){

        session_start();

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