<?php

class Album {

    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    public function getLatestAlbums(){

        $query = "SELECT id_album, nombre_album, portada_album
                  FROM Albumes
                  ORDER BY fecha_lanzamiento DESC
                  LIMIT 6";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}