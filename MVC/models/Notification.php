<?php

class Notification {

    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    public function newFollowers($user){

        $query = "

        SELECT U.nombre_usuario,S.fecha_follow
        FROM Siguen S
        INNER JOIN Usuarios U
        ON S.id_seguidor = U.id_usuario
        WHERE S.id_seguido = :user
        ORDER BY S.fecha_follow DESC
        LIMIT 5
        ";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":user",$user);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function newMessages($user){

        $query = "

        SELECT M.*,U.nombre_usuario
        FROM Mensajes M
        INNER JOIN Usuarios U
        ON M.id_emisor = U.id_usuario
        WHERE M.id_receptor = :user
        ORDER BY M.fecha_mensaje DESC
        LIMIT 5
        ";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":user",$user);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}