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

    public function getById($id){

        $query = "SELECT *
                  FROM Canciones
                  WHERE id_cancion = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getDetailById($id){

        $query = "SELECT C.*, A.nombre_artistico, AL.nombre_album, AL.portada_album,
                         L.letra_cancion, L.texto_fonetico
                  FROM Canciones C
                  INNER JOIN Artista A ON C.id_artista = A.id_usuario
                  LEFT JOIN Albumes AL ON C.id_album = AL.id_album
                  LEFT JOIN Letras L ON C.id_cancion = L.id_cancion
                  WHERE C.id_cancion = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByAlbumWithLyrics($albumId){

        $query = "SELECT C.*, A.nombre_artistico, L.letra_cancion, L.texto_fonetico
                  FROM Canciones C
                  INNER JOIN Artista A ON C.id_artista = A.id_usuario
                  LEFT JOIN Letras L ON C.id_cancion = L.id_cancion
                  WHERE C.id_album = :id
                  ORDER BY C.numero_pista";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $albumId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByArtistWithAlbum($artistId){

        $query = "SELECT C.*, AL.nombre_album
                  FROM Canciones C
                  LEFT JOIN Albumes AL ON C.id_album = AL.id_album
                  WHERE C.id_artista = :id
                  ORDER BY C.id_cancion DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $artistId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLatestDetailed($limit = 20){

        $limit = max(1, (int)$limit);

        $query = "SELECT C.*, A.nombre_artistico, AL.nombre_album
                  FROM Canciones C
                  INNER JOIN Artista A ON C.id_artista = A.id_usuario
                  LEFT JOIN Albumes AL ON C.id_album = AL.id_album
                  ORDER BY C.id_cancion DESC
                  LIMIT :limit";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByArtistOutsideAlbum($artist, $albumId){

        $query = "SELECT *
                  FROM Canciones
                  WHERE id_artista = :artist
                  AND (id_album IS NULL OR id_album != :album)
                  ORDER BY id_cancion DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":artist", $artist);
        $stmt->bindParam(":album", $albumId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLatestSongs(){

        $query = "SELECT C.id_cancion,
                         C.id_artista,
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

        SELECT C.id_cancion AS id, C.nombre_cancion AS resultado,'cancion' AS tipo
        FROM Canciones C
        WHERE C.nombre_cancion LIKE :term

        UNION

        SELECT A.id_usuario AS id, A.nombre_artistico AS resultado,'artista' AS tipo
        FROM Artista A
        WHERE A.nombre_artistico LIKE :term

        UNION

        SELECT AL.id_album AS id, AL.nombre_album AS resultado,'album' AS tipo
        FROM Albumes AL
        WHERE AL.nombre_album LIKE :term

        UNION

        SELECT T.id_tag AS id, T.nombre_tag AS resultado,'tag' AS tipo
        FROM Tags T
        WHERE T.nombre_tag LIKE :term

        UNION

        SELECT U.id_usuario AS id, U.nombre_usuario AS resultado,'usuario' AS tipo
        FROM Usuarios U
        WHERE U.nombre_usuario LIKE :term
        ";

        $stmt = $this->conn->prepare($query);

        $term = "%".$term."%";

        $stmt->bindParam(":term",$term);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data){

        $album = $data['album'] ?? null;
        $track = $data['numero_pista'] ?? null;

        if($album){
            $track = $track ?? $this->getNextTrackNumber($album);
        } else {
            $track = null;
        }

        $query = "INSERT INTO Canciones
              (nombre_cancion,numero_pista,path_link,portada_cancion,id_album,id_artista)
              VALUES (:nombre,:pista,:path,:portada,:album,:artista)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nombre",$data['nombre']);
        $stmt->bindParam(":pista",$track);
        $stmt->bindParam(":path",$data['path']);
        $stmt->bindParam(":portada",$data['portada']);
        $stmt->bindParam(":album",$album);
        $stmt->bindParam(":artista",$data['artista']);

        if(!$stmt->execute()){
            return false;
        }

        return $this->conn->lastInsertId();
    }

    public function getLyrics($songId){
        $query = "SELECT letra_cancion, texto_fonetico FROM Letras WHERE id_cancion = :song";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":song", $songId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: ['letra_cancion' => '', 'texto_fonetico' => ''];
    }

    public function saveLyrics($songId, $lyrics, $phonetic){

        $lyrics = trim((string) $lyrics);
        $phonetic = trim((string) $phonetic);

        if($lyrics === '' && $phonetic === ''){
            $query = "DELETE FROM Letras WHERE id_cancion = :song";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":song", $songId);
            return $stmt->execute();
        }

        $query = "INSERT INTO Letras (id_cancion, letra_cancion, texto_fonetico)
                  VALUES (:song, :lyrics, :phonetic)
                  ON DUPLICATE KEY UPDATE
                      letra_cancion = VALUES(letra_cancion),
                      texto_fonetico = VALUES(texto_fonetico)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":song", $songId);
        $stmt->bindParam(":lyrics", $lyrics);
        $stmt->bindParam(":phonetic", $phonetic);

        return $stmt->execute();
    }

    public function update($data){

        $current = $this->getById($data['song']);

        if(!$current || (int)$current['id_artista'] !== (int)$data['artista']){
            return false;
        }

        $album = $data['album'];
        $previousAlbum = $current['id_album'];
        $track = $current['numero_pista'];

        if(empty($album)){
            $album = null;
            $track = null;
        } elseif((int)$current['id_album'] !== (int)$album){
            $track = $this->getNextTrackNumber($album);
        }

        $query = "UPDATE Canciones
                  SET nombre_cancion = :nombre,
                      portada_cancion = :portada,
                      id_album = :album,
                      numero_pista = :pista
                  WHERE id_cancion = :song
                  AND id_artista = :artista";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nombre", $data['nombre']);
        $stmt->bindParam(":portada", $data['portada']);
        $stmt->bindParam(":album", $album);
        $stmt->bindParam(":pista", $track);
        $stmt->bindParam(":song", $data['song']);
        $stmt->bindParam(":artista", $data['artista']);

        $updated = $stmt->execute();

        if($updated && $previousAlbum && (int)$previousAlbum !== (int)$album){
            $this->normalizeAlbumTracks($previousAlbum);
        }

        return $updated;
    }

    public function moveToAlbum($songId, $albumId, $artistId){

        $song = $this->getById($songId);

        if(!$song || (int)$song['id_artista'] !== (int)$artistId){
            return false;
        }

        $previousAlbum = $song['id_album'];
        $track = $albumId ? $this->getNextTrackNumber($albumId) : null;

        $query = "UPDATE Canciones
                  SET id_album = :album,
                      numero_pista = :pista
                  WHERE id_cancion = :song
                  AND id_artista = :artista";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":album", $albumId);
        $stmt->bindParam(":pista", $track);
        $stmt->bindParam(":song", $songId);
        $stmt->bindParam(":artista", $artistId);

        $moved = $stmt->execute();

        if($moved && $previousAlbum && (int)$previousAlbum !== (int)$albumId){
            $this->normalizeAlbumTracks($previousAlbum);
        }

        return $moved;
    }

    private function getNextTrackNumber($albumId){

        $query = "SELECT COALESCE(MAX(numero_pista), 0) + 1 AS next_track
                  FROM Canciones
                  WHERE id_album = :album";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":album", $albumId);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) ($result['next_track'] ?? 1);
    }

    private function normalizeAlbumTracks($albumId){

        $query = "SELECT id_cancion
                  FROM Canciones
                  WHERE id_album = :album
                  ORDER BY numero_pista ASC,
                           id_cancion ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":album", $albumId);
        $stmt->execute();

        $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $tempTrack = 1000;
        $tempQuery = "UPDATE Canciones
                      SET numero_pista = :track
                      WHERE id_cancion = :song";
        $tempStmt = $this->conn->prepare($tempQuery);

        foreach($songs as $song){
            $tempStmt->bindValue(":track", $tempTrack, PDO::PARAM_INT);
            $tempStmt->bindValue(":song", $song['id_cancion'], PDO::PARAM_INT);
            $tempStmt->execute();
            $tempTrack++;
        }

        $finalTrack = 1;
        $finalStmt = $this->conn->prepare($tempQuery);

        foreach($songs as $song){
            $finalStmt->bindValue(":track", $finalTrack, PDO::PARAM_INT);
            $finalStmt->bindValue(":song", $song['id_cancion'], PDO::PARAM_INT);
            $finalStmt->execute();
            $finalTrack++;
        }
    }
}

