<?php

class Message {

    private PDO $conn;

    public function __construct(PDO $db){
        $this->conn = $db;
    }
    

    public function send($emisor,$receptor,$texto){

        $query = "INSERT INTO Mensajes
        (id_emisor,id_receptor,texto)
        VALUES (:emisor,:receptor,:texto)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":emisor",$emisor);
        $stmt->bindParam(":receptor",$receptor);
        $stmt->bindParam(":texto",$texto);

        return $stmt->execute();
    }
    public function getChat($user1,$user2){

    $query = "
        SELECT *
        FROM Mensajes
        WHERE (id_emisor = :u1 AND id_receptor = :u2)
           OR (id_emisor = :u2 AND id_receptor = :u1)
        ORDER BY fecha_mensaje ASC
    ";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(":u1",$user1);
    $stmt->bindParam(":u2",$user2);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function getConversations($user){

    $query = "
        SELECT u.id_usuario,
               u.nombre_usuario AS username,
               u.pfp,
               (SELECT texto
                FROM Mensajes m2
                WHERE (m2.id_emisor = :user AND m2.id_receptor = u.id_usuario)
                   OR (m2.id_emisor = u.id_usuario AND m2.id_receptor = :user)
                ORDER BY fecha_mensaje DESC
                LIMIT 1) AS ultimo_mensaje,
               (SELECT fecha_mensaje
                FROM Mensajes m2
                WHERE (m2.id_emisor = :user AND m2.id_receptor = u.id_usuario)
                   OR (m2.id_emisor = u.id_usuario AND m2.id_receptor = :user)
                ORDER BY fecha_mensaje DESC
                LIMIT 1) AS ultimo_ts
        FROM Usuarios u
        WHERE u.id_usuario IN (
            SELECT CASE WHEN id_emisor = :user THEN id_receptor ELSE id_emisor END
            FROM Mensajes
            WHERE id_emisor = :user OR id_receptor = :user
        )
        ORDER BY ultimo_ts DESC
    ";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":user",$user);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}