<?php

class Like {

    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    public function likeSong($user,$song){

        $query = "INSERT INTO Likes
        (id_usuario,id_cancion)
        VALUES (:user,:song)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":user",$user);
        $stmt->bindParam(":song",$song);

        return $stmt->execute();
    }

    public function countLikes($song){

        $query = "SELECT COUNT(*) AS total
                  FROM Likes
                  WHERE id_cancion = :song";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":song",$song);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}