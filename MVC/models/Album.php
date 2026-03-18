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
public function getConnection(){
    return $this->conn;
}

public function create($data){

    $query = "INSERT INTO Albumes (nombre_album, descripcion_album, portada_album, id_artista)
              VALUES (:nombre, :descripcion, :portada, :artista)";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(":nombre", $data['nombre']);
    $stmt->bindParam(":descripcion", $data['descripcion']);
    $stmt->bindParam(":portada", $data['portada']);
    $stmt->bindParam(":artista", $data['artista']);

    $stmt->execute();

    return $this->conn->lastInsertId();
}
}