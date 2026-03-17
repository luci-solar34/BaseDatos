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

    public function getConversations($user){

    $query = "
        SELECT DISTINCT 
            u.id_usuario,
            u.nombre_usuario,
            u.pfp
        FROM Mensajes m
        JOIN Usuarios u 
            ON (u.id_usuario = m.id_emisor AND m.id_receptor = :user)
            OR (u.id_usuario = m.id_receptor AND m.id_emisor = :user)
    ";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":user",$user);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
}