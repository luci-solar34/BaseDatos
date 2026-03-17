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

    public function autocomplete(){

        $term = $_GET['q'] ?? '';

        if(strlen($term) < 2){
            echo json_encode([]);
            return;
        }

        $results = $this->song->search($term);

        header('Content-Type: application/json');
        echo json_encode($results);
    }
}