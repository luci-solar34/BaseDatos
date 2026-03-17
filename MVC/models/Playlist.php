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

        return $stmt->execute();
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

        $query = "SELECT C.*
                  FROM Playlist_Canciones PC
                  INNER JOIN Canciones C ON PC.id_cancion = C.id_cancion
                  WHERE PC.id_playlist = :playlist";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":playlist",$playlist);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function changePrivacy($playlist,$privacy){

    $privacy = $privacy ? 1 : 0;

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

}