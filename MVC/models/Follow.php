<?php

class Follow {

    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }
    public function isFollowing($seguidor,$seguido){

    $query = "SELECT 1
              FROM Siguen
              WHERE id_seguidor = :seguidor
              AND id_seguido = :seguido";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(":seguidor",$seguidor);
    $stmt->bindParam(":seguido",$seguido);

    $stmt->execute();

    return $stmt->rowCount() > 0;
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

    public function areMutual($user1,$user2){

    $query = "
        SELECT COUNT(*) as total
        FROM Siguen
        WHERE (id_seguidor = :u1 AND id_seguido = :u2)
           OR (id_seguidor = :u2 AND id_seguido = :u1)
    ";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":u1",$user1);
    $stmt->bindParam(":u2",$user2);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC)['total'] == 2;
}
    public function unfollow($seguidor,$seguido){

    $query = "DELETE FROM Siguen
              WHERE id_seguidor = :seguidor
              AND id_seguido = :seguido";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(":seguidor",$seguidor);
    $stmt->bindParam(":seguido",$seguido);

    return $stmt->execute();
    }


    public function countFollowers($user_id){

    $query = "SELECT COUNT(*) AS total
              FROM Siguen
              WHERE id_seguido = :id";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":id",$user_id);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

public function countFollowing($user_id){

    $query = "SELECT COUNT(*) AS total
              FROM Siguen
              WHERE id_seguidor = :id";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":id",$user_id);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

}