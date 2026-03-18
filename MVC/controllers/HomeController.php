<?php

require_once "../config/database.php";
require_once "../MVC/models/Song.php";
require_once "../MVC/models/Album.php";
require_once "../MVC/models/Artist.php";
require_once "../MVC/models/Tag.php";
require_once "../MVC/models/Follow.php";

class HomeController {

    private $song;
    private $album;
    private $artist;
    private $tag;
    private $follow;

    public function __construct(){

        $database = new Database();
        $db = $database->connect();

        $this->song = new Song($db);
        $this->album = new Album($db);
        $this->artist = new Artist($db);
        $this->tag = new Tag($db);
        $this->follow = new Follow($db);
    }

    public function home(){

    $songs = $this->song->getLatestSongs();
    $albums = $this->album->getLatestAlbums();
    $artists = $this->artist->getLatestArtists();
    $tags = $this->tag->getRandomTags();

    $likedSongs = [];
    $followingArtists = [];

    if(isset($_SESSION['user_id'])){
        $likedSongs = $this->song->getUserLikedSongs($_SESSION['user_id']);
        $followingArtists = $this->follow->getFollowingIds($_SESSION['user_id']);
    }

    require "../MVC/views/home.php";
}
}
