<?php

require_once "../config/database.php";
require_once "../MVC/models/Song.php";

class SearchController {

    private $song;

    public function __construct(){

        $database = new Database();
        $db = $database->connect();

        $this->song = new Song($db);
    }

    public function search(){

        $term = $_GET['q'];

        $results = $this->song->search($term);

        require "../MVC/views/search.php";
    }
}