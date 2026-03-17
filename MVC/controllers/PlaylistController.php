<?php

require_once "../config/database.php";
require_once "../MVC/models/Playlist.php";

class PlaylistController {

    private $playlist;

    public function __construct(){

        $database = new Database();
        $db = $database->connect();

        $this->playlist = new Playlist($db);
    }

    
    public function create(){

        $name = $_POST['name'] ?? null;
        $privacy = $_POST['privacy'] ?? 0;
        $user = $_SESSION['user_id'] ?? null;

        if(!$name || !$user){
            die("Datos inválidos");
        }

        $this->playlist->create($name,$privacy,$user);

        $playlist_id = $this->playlist->getLastId();

        header("Location: /LASK/public/index.php/playlist?id=".$playlist_id);
        exit;
    }

    
    public function show($id){

        $songs = $this->playlist->getPlaylistSongs($id);
        $playlist_id = $id;

        require "../MVC/views/playlist.php";
    }

   
    public function addSong(){

        $playlist = $_POST['playlist'] ?? null;
        $song = $_POST['song'] ?? null;

        if(!$playlist || !$song){
            die("Datos inválidos");
        }

        $this->playlist->addSong($playlist,$song);

        header("Location: /LASK/public/index.php/playlist?id=".$playlist);
        exit;
    }

    
    public function removeSong(){

        $playlist = $_POST['playlist'] ?? null;
        $song = $_POST['song'] ?? null;

        if(!$playlist || !$song){
            die("Datos inválidos");
        }

        $this->playlist->removeSong($playlist,$song);

        header("Location: /LASK/public/index.php/playlist?id=".$playlist);
        exit;
    }

    
    public function changePrivacy(){

        $playlist = $_POST['playlist'] ?? null;
        $privacy = $_POST['privacy'] ?? 0;

        if(!$playlist){
            die("Datos inválidos");
        }

        $this->playlist->changePrivacy($playlist,$privacy);
    }
}