<?php

class Album {

    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    public function getLatestAlbums(){

        $query = "SELECT id_album, id_artista, nombre_album, portada_album
                  FROM Albumes
                  ORDER BY fecha_lanzamiento DESC
                  LIMIT 6";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id){

        $query = "SELECT *
                  FROM Albumes
                  WHERE id_album = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getDetailByIdWithArtist($id){

        $query = "SELECT A.*, AR.id_usuario AS id_artista, AR.nombre_artistico, U.nombre_usuario
                  FROM Albumes A
                  INNER JOIN Artista AR ON A.id_artista = AR.id_usuario
                  INNER JOIN Usuarios U ON AR.id_usuario = U.id_usuario
                  WHERE A.id_album = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByArtist($artistId){

        $query = "SELECT *
                  FROM Albumes
                  WHERE id_artista = :artist
                  ORDER BY fecha_lanzamiento DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":artist", $artistId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLatestDetailed($limit = 20){

        $limit = max(1, (int)$limit);

        $query = "SELECT A.*,
                         (SELECT AR.nombre_artistico
                          FROM Canciones C2
                          INNER JOIN Artista AR ON C2.id_artista = AR.id_usuario
                          WHERE C2.id_album = A.id_album
                          LIMIT 1) as nombre_artistico,
                         (SELECT U.nombre_usuario
                          FROM Canciones C2
                          INNER JOIN Artista AR ON C2.id_artista = AR.id_usuario
                          INNER JOIN Usuarios U ON AR.id_usuario = U.id_usuario
                          WHERE C2.id_album = A.id_album
                          LIMIT 1) as nombre_usuario
                  FROM Albumes A
                  ORDER BY A.fecha_lanzamiento DESC
                      LIMIT :limit";

        $stmt = $this->conn->prepare($query);
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    public function update($data){

        $query = "UPDATE Albumes
                  SET nombre_album = :nombre,
                      descripcion_album = :descripcion,
                      portada_album = :portada
                  WHERE id_album = :album
                  AND id_artista = :artista";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nombre", $data['nombre']);
        $stmt->bindParam(":descripcion", $data['descripcion']);
        $stmt->bindParam(":portada", $data['portada']);
        $stmt->bindParam(":album", $data['album']);
        $stmt->bindParam(":artista", $data['artista']);

        return $stmt->execute();
    }
}