<?php

class Playlist {

    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    public function create($name,$privacy,$user){

    $query = "INSERT INTO Playlists
    (nombre_playlist,privacidad_playlist,id_usuario)
    VALUES (:name,:privacy,:user)";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(":name",$name);
    $stmt->bindParam(":privacy",$privacy);
    $stmt->bindParam(":user",$user);

    $stmt->execute();

    return $this->conn->lastInsertId(); 
    }

    public function addSong($playlist,$song){

        $query = "INSERT INTO Playlist_Canciones
        (id_playlist,id_cancion)
        VALUES (:playlist,:song)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":playlist",$playlist);
        $stmt->bindParam(":song",$song);

        return $stmt->execute();
    }

    public function getPlaylistSongs($playlist){

        $query = "SELECT C.*,
                         AR.id_usuario AS id_artista,
                         AR.nombre_artistico,
                         AL.portada_album
                  FROM Playlist_Canciones PC
                  INNER JOIN Canciones C ON PC.id_cancion = C.id_cancion
                  INNER JOIN Artista AR ON C.id_artista = AR.id_usuario
                  LEFT JOIN Albumes AL ON C.id_album = AL.id_album
                  WHERE PC.id_playlist = :playlist";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":playlist",$playlist);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function changePrivacy($playlist,$privacy){

    $query = "UPDATE Playlists
              SET privacidad_playlist = :privacy
              WHERE id_playlist = :playlist";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(":playlist",$playlist);
    $stmt->bindParam(":privacy",$privacy, PDO::PARAM_INT);

    return $stmt->execute();
}
public function removeSong($playlist,$song){

    $query = "DELETE FROM Playlist_Canciones
              WHERE id_playlist = :playlist
              AND id_cancion = :song";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(":playlist",$playlist);
    $stmt->bindParam(":song",$song);

    return $stmt->execute();
}

public function getUserPlaylists($user,$viewer){

    if($user == $viewer){

        
        $query = "SELECT *
                  FROM Playlists
                  WHERE id_usuario = :user
                  ORDER BY fecha_playlist DESC";

    } else {

        
        $query = "SELECT *
                  FROM Playlists
                  WHERE id_usuario = :user
                  AND privacidad_playlist = 1
                  ORDER BY fecha_playlist DESC";
    }

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":user",$user);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getPlaylist($id){

    $query = "SELECT * FROM Playlists WHERE id_playlist = :id";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":id", $id);

    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function getPlaylistByOwner($playlistId, $userId){

    $query = "SELECT *
              FROM Playlists
              WHERE id_playlist = :playlist
              AND id_usuario = :user
              LIMIT 1";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":playlist", $playlistId, PDO::PARAM_INT);
    $stmt->bindParam(":user", $userId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function hasSong($playlistId, $songId){

    $query = "SELECT 1
              FROM Playlist_Canciones
              WHERE id_playlist = :playlist
              AND id_cancion = :song
              LIMIT 1";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":playlist", $playlistId, PDO::PARAM_INT);
    $stmt->bindParam(":song", $songId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchColumn() !== false;
}

}