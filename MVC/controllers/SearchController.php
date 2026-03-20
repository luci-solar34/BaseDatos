<?php

require_once __DIR__ . "/Controller.php";
require_once __DIR__ . "/../models/Song.php";

class SearchController extends Controller {

    private $song;

    public function __construct(){
        $db = $this->connectDatabase();
        $this->song = new Song($db);
    }

    public function search(){

        $term = $this->getString('q');
        $query = $term;

        if(!$this->esEntradaSegura($term)){
            $this->render('search.php', ['query' => $query, 'results' => []]);
            return;
        }

        $results = $this->song->search($term);

        $this->render('search.php', [
            'query' => $query,
            'results' => $results,
        ]);
    }

    public function autocomplete(){

        $this->requireAuthenticatedUser('login');

        $term = $this->getString('q');

        if(strlen($term) < 2 || !$this->esEntradaSegura($term)){
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([]);
            return;
        }

        $results = $this->song->search($term);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($results);
    }
}