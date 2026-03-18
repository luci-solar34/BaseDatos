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
    public function getFollowers($user_id){
        $query = "SELECT u.id_usuario, u.nombre_usuario, u.pfp
                  FROM Siguen s
                  INNER JOIN Usuarios u ON s.id_seguidor = u.id_usuario
                  WHERE s.id_seguido = :id
                  ORDER BY u.nombre_usuario ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $user_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFollowingIds($user_id){
        $query = "SELECT id_seguido
                  FROM Siguen
                  WHERE id_seguidor = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $user_id);
        $stmt->execute();

        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'id_seguido');
    }
    public function getMutuals($user_id){

    $query = "
        SELECT u.id_usuario, u.nombre_usuario, u.pfp
        FROM Usuarios u
        WHERE u.id_usuario IN (
            SELECT s1.id_seguido
            FROM Siguen s1
            INNER JOIN Siguen s2
                ON s1.id_seguido = s2.id_seguidor
            WHERE s1.id_seguidor = :user
              AND s2.id_seguido = :user
        )
    ";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":user", $user_id);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}