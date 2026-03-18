<?php

class Comment {

    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    public function create($data){

        $query = "INSERT INTO Comentarios_Artista (id_usuario, id_artista, texto, fecha_comentario)
                  VALUES (:usuario, :artista, :texto, NOW())";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":usuario", $data['usuario']);
        $stmt->bindParam(":artista", $data['artista']);
        $stmt->bindParam(":texto", $data['comentario']);

        return $stmt->execute();
    }
}