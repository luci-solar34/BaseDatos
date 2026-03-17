<?php

class Song {

    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    public function getAll(){

        $query = "SELECT C.*, A.nombre_artistico, AL.nombre_album
                  FROM Canciones C
                  INNER JOIN Artista A ON C.id_artista = A.id_usuario
                  LEFT JOIN Albumes AL ON C.id_album = AL.id_album";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByArtist($artist){

        $query = "SELECT C.*, AL.nombre_album
                  FROM Canciones C
                  LEFT JOIN Albumes AL ON C.id_album = AL.id_album
                  WHERE C.id_artista = :artist";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":artist",$artist);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLatestSongs(){

        $query = "SELECT C.id_cancion,
                         C.nombre_cancion,
                         C.portada_cancion,
                         C.path_link,
                         A.nombre_artistico
                  FROM Canciones C
                  INNER JOIN Artista A ON C.id_artista = A.id_usuario
                  ORDER BY C.id_cancion DESC
                  LIMIT 6";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getUserLikedSongs($user){

    $query = "SELECT C.id_cancion,
                     C.nombre_cancion,
                     C.portada_cancion,
                     C.path_link,
                     A.nombre_artistico
              FROM Likes L
              INNER JOIN Canciones C ON L.id_cancion = C.id_cancion
              INNER JOIN Artista A ON C.id_artista = A.id_usuario
              WHERE L.id_usuario = :user
              ORDER BY L.fecha_like DESC
              LIMIT 5";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":user",$user);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function search($term){

        $query = "

        SELECT C.nombre_cancion AS resultado,'cancion' AS tipo
        FROM Canciones C
        WHERE C.nombre_cancion LIKE :term

        UNION

        SELECT A.nombre_artistico,'artista'
        FROM Artista A
        WHERE A.nombre_artistico LIKE :term

        UNION

        SELECT AL.nombre_album,'album'
        FROM Albumes AL
        WHERE AL.nombre_album LIKE :term

        UNION

        SELECT T.nombre_tag,'tag'
        FROM Tags T
        WHERE T.nombre_tag LIKE :term
        ";

        $stmt = $this->conn->prepare($query);

        $term = "%".$term."%";

        $stmt->bindParam(":term",$term);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data){

        $columns = ['nombre_cancion', 'numero_pista', 'path_link', 'id_album', 'id_artista', 'portada_cancion'];
        $placeholders = [':nombre', ':pista', ':path', ':album', ':artista', ':portada'];

        $query = "INSERT INTO Canciones (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nombre",$data['nombre']);
        $stmt->bindParam(":pista",$data['numero_pista']);
        $stmt->bindParam(":path",$data['path']);
        $stmt->bindParam(":album",$data['album']);
        $stmt->bindParam(":artista",$data['artista']);
        $stmt->bindParam(":portada",$data['portada']);

        if($stmt->execute()){
            $songId = $this->conn->lastInsertId();
            if(!empty($data['tags'])){
                $this->addTags($songId, $data['tags']);
            }
            return $songId;
        }

        return false;
    }

    private function tableExists($table){
        try {
            $query = "SHOW TABLES LIKE :table";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':table', $table);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    public function addTags($songId, $tags){
        if(!$this->tableExists('Cancion_Tags')){
            return false;
        }

        $query = "INSERT INTO Cancion_Tags (id_cancion, id_tag) VALUES (:song, :tag)";
        $stmt = $this->conn->prepare($query);

        foreach($tags as $tag){
            $stmt->bindValue(':song', $songId);
            $stmt->bindValue(':tag', $tag);
            $stmt->execute();
        }

        return true;
    }
    public function getById($id){

    $query = "SELECT C.*, A.nombre_artistico
              FROM Canciones C
              INNER JOIN Artista A ON C.id_artista = A.id_usuario
              WHERE C.id_cancion = :id";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":id",$id);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
public function getByTag($tag){

    $query = "SELECT C.*
              FROM Canciones C
              INNER JOIN Cancion_Tags CT ON C.id_cancion = CT.id_cancion
              WHERE CT.id_tag = :tag";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":tag",$tag);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getRecentSongs(){

    $query = "SELECT C.id_cancion,
                     C.nombre_cancion,
                     C.portada_cancion,
                     C.path_link,
                     A.nombre_artistico
              FROM Canciones C
              INNER JOIN Artista A ON C.id_artista = A.id_usuario
              ORDER BY C.id_cancion DESC
              LIMIT 20";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

