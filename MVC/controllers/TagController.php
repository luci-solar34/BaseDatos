<?php

require_once "../config/database.php";
require_once "../MVC/models/Tag.php";

class TagController {

    private $tag;

    public function __construct(){

        $database = new Database();
        $db = $database->connect();

        $this->tag = new Tag($db);
    }

    public function show($id){

        $tag = $this->tag->getById($id);

        if(!$tag){
            die("Tag no encontrado");
        }

        $songs = $this->tag->getSongsByTag($id);

        require "../MVC/views/detail_tag.php";
    }

    public function index(){

        $query = trim($_GET['q'] ?? '');
        $tags = $query !== '' ? $this->tag->searchTags($query) : $this->tag->getAllTags();

        require "../MVC/views/tags.php";
    }
}
