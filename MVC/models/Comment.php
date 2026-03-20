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

    public function getByArtist($artistId){

        $query = "SELECT C.texto AS comentario, C.fecha_comentario, U.nombre_usuario
                  FROM Comentarios_Artista C
                  INNER JOIN Usuarios U ON C.id_usuario = U.id_usuario
                  WHERE C.id_artista = :id
                  ORDER BY C.fecha_comentario DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $artistId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}