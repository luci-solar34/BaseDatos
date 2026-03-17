<?php

class Tag {

    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    public function getAllTags(){

        $query = "SELECT id_tag, nombre_tag FROM Tags";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}