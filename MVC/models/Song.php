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

        $query = "INSERT INTO Canciones
                  (nombre_cancion,numero_pista,path_link,id_album,id_artista)
                  VALUES (:nombre,:pista,:path,:album,:artista)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nombre",$data['nombre']);
        $stmt->bindParam(":pista",$data['numero_pista']);
        $stmt->bindParam(":path",$data['path']);
        $stmt->bindParam(":album",$data['album']);
        $stmt->bindParam(":artista",$data['artista']);

        return $stmt->execute();
    }
}

