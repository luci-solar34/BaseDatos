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

        $query = "SELECT M.*, 
                         U1.nombre_usuario AS emisor,
                         U2.nombre_usuario AS receptor
                  FROM Mensajes M
                  INNER JOIN Usuarios U1 ON M.id_emisor = U1.id_usuario
                  INNER JOIN Usuarios U2 ON M.id_receptor = U2.id_usuario
                  WHERE (M.id_emisor = :u1 AND M.id_receptor = :u2)
                     OR (M.id_emisor = :u2 AND M.id_receptor = :u1)
                  ORDER BY fecha_mensaje ASC";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":u1",$user1);
        $stmt->bindParam(":u2",$user2);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}