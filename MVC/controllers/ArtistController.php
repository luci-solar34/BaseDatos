<?php

require_once "../config/database.php";
require_once "../MVC/models/Song.php";

class ArtistController {

    private $song;

    public function __construct(){

        $database = new Database();
        $db = $database->connect();

        $this->song = new Song($db);
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

}