<?php

class Comment {

    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    public function create($data){

        $query = "INSERT INTO Comentarios (id_usuario, id_artista, comentario, fecha_comentario)
                  VALUES (:usuario, :artista, :comentario, NOW())";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":usuario", $data['usuario']);
        $stmt->bindParam(":artista", $data['artista']);
        $stmt->bindParam(":comentario", $data['comentario']);

        return $stmt->execute();
    }
}