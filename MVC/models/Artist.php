<?php

class Artist {

    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    public function getLatestArtists(){

        $query = "SELECT U.id_usuario, U.pfp, A.nombre_artistico
                  FROM Artista A
                  JOIN Usuarios U ON A.id_usuario = U.id_usuario
                  ORDER BY U.fecha_creacion DESC
                  LIMIT 6";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id){

        $query = "SELECT U.*, A.nombre_artistico
                  FROM Artista A
                  JOIN Usuarios U ON A.id_usuario = U.id_usuario
                  WHERE A.id_usuario = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id",$id);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}