<?php

class Follow {

    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    public function follow($seguidor,$seguido){

        $query = "INSERT INTO Siguen
        (id_seguidor,id_seguido)
        VALUES (:seguidor,:seguido)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":seguidor",$seguidor);
        $stmt->bindParam(":seguido",$seguido);

        return $stmt->execute();
    }

    public function mutualFollow($user1,$user2){

        $query = "SELECT *
                  FROM Siguen
                  WHERE id_seguidor = :u1 AND id_seguido = :u2";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":u1",$user1);
        $stmt->bindParam(":u2",$user2);

        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

}