<?php

class Tag {

    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    public function getAllTags(){

        $query = "SELECT id_tag, nombre_tag, descripcion_tag
                  FROM Tags
                  ORDER BY nombre_tag ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRandomTags($limit = 8){

        $limit = max(1, (int)$limit);
        $query = "SELECT id_tag, nombre_tag, descripcion_tag
                  FROM Tags
                  ORDER BY RAND()
                  LIMIT :limit";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id){

        $query = "SELECT id_tag, nombre_tag, descripcion_tag
                  FROM Tags
                  WHERE id_tag = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function searchTags($term){

        $query = "SELECT id_tag, nombre_tag, descripcion_tag
                  FROM Tags
                  WHERE nombre_tag LIKE :term
                  ORDER BY nombre_tag ASC";

        $stmt = $this->conn->prepare($query);
        $term = "%" . $term . "%";
        $stmt->bindParam(":term", $term);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createTag($nombre_tag, $descripcion_tag){
        $query = "INSERT INTO Tags (nombre_tag, descripcion_tag)
                  VALUES (:nombre, :descripcion)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nombre", $nombre_tag);
        $stmt->bindParam(":descripcion", $descripcion_tag);

        if($stmt->execute()){
            return $this->conn->lastInsertId();
        }

        return false;
    }

    public function getSongsByTag($tagId){

        $query = "SELECT C.id_cancion,
                         C.nombre_cancion,
                         C.path_link,
                         C.portada_cancion,
                         C.numero_pista,
                         A.id_usuario AS id_artista,
                         A.nombre_artistico,
                         AL.id_album,
                         AL.nombre_album
                  FROM cancion_tags CT
                  INNER JOIN Canciones C ON CT.id_cancion = C.id_cancion
                  INNER JOIN Artista A ON C.id_artista = A.id_usuario
                  LEFT JOIN Albumes AL ON C.id_album = AL.id_album
                  WHERE CT.id_tag = :tag
                  ORDER BY C.id_cancion DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":tag", $tagId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSongTags($songId){

        $query = "SELECT T.id_tag, T.nombre_tag
                  FROM cancion_tags CT
                  INNER JOIN Tags T ON CT.id_tag = T.id_tag
                  WHERE CT.id_cancion = :song
                  ORDER BY T.nombre_tag ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":song", $songId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSongTagIds($songId){

        $query = "SELECT id_tag
                  FROM cancion_tags
                  WHERE id_cancion = :song";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":song", $songId);
        $stmt->execute();

        return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
    }

    public function syncSongTags($songId, $tagIds){

        $songId = (int) $songId;
        $cleanIds = $this->normalizeTagIds($tagIds);

        $this->conn->beginTransaction();

        try {
            $deleteQuery = "DELETE FROM cancion_tags WHERE id_cancion = :song";
            $deleteStmt = $this->conn->prepare($deleteQuery);
            $deleteStmt->bindParam(":song", $songId);
            $deleteStmt->execute();

            if(!empty($cleanIds)){

                $validIds = $this->fetchExistingTagIds($cleanIds);

                if(!empty($validIds)){
                    $insertQuery = "INSERT INTO cancion_tags (id_cancion, id_tag)
                                    VALUES (:song, :tag)";
                    $insertStmt = $this->conn->prepare($insertQuery);

                    foreach($validIds as $tagId){
                        $insertStmt->bindParam(":song", $songId);
                        $insertStmt->bindParam(":tag", $tagId);
                        $insertStmt->execute();
                    }
                }
            }

            $this->conn->commit();
            return true;
        } catch(Throwable $e){
            if($this->conn->inTransaction()){
                $this->conn->rollBack();
            }
            throw $e;
        }
    }

    private function normalizeTagIds(array $tagIds){
        return array_values(array_unique(array_filter(array_map('intval', $tagIds), function($id){
            return $id > 0;
        })));
    }

    private function fetchExistingTagIds(array $tagIds){
        if(empty($tagIds)){
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($tagIds), '?'));
        $validQuery = "SELECT id_tag FROM Tags WHERE id_tag IN ($placeholders)";
        $validStmt = $this->conn->prepare($validQuery);

        foreach($tagIds as $i => $tagId){
            $validStmt->bindValue($i + 1, $tagId, PDO::PARAM_INT);
        }

        $validStmt->execute();

        return array_map('intval', $validStmt->fetchAll(PDO::FETCH_COLUMN));
    }

}