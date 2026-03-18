<?php

class Block {

    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    public function block($bloqueador, $bloqueado){

        // Inserta el bloqueo (IGNORAR si ya existe) y retorna si el bloqueo está activo.
        $query = "INSERT IGNORE INTO Bloquea (id_bloqueador,id_bloqueado)
                  VALUES (:b1,:b2)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":b1",$bloqueador);
        $stmt->bindParam(":b2",$bloqueado);
        $stmt->execute();

        return $this->hasBlocked($bloqueador, $bloqueado);
    }

    public function unblock($bloqueador, $bloqueado){

        $query = "DELETE FROM Bloquea
                  WHERE id_bloqueador = :b1
                  AND id_bloqueado = :b2";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":b1",$bloqueador);
        $stmt->bindParam(":b2",$bloqueado);

        return $stmt->execute();
    }

    // 🔥 LA MÁS IMPORTANTE
    public function isBlocked($user1, $user2){

        $query = "SELECT 1 FROM Bloquea
                  WHERE (id_bloqueador = :u1 AND id_bloqueado = :u2)
                     OR (id_bloqueador = :u2 AND id_bloqueado = :u1)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":u1",$user1);
        $stmt->bindParam(":u2",$user2);
        $stmt->execute();

        // rowCount() puede ser impreciso en algunos drivers. Usar fetch() es más confiable.
        return (bool)$stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function hasBlocked($bloqueador, $bloqueado){

        $query = "SELECT 1 FROM Bloquea
                  WHERE id_bloqueador = :b1
                  AND id_bloqueado = :b2";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":b1",$bloqueador);
        $stmt->bindParam(":b2",$bloqueado);
        $stmt->execute();

        return (bool)$stmt->fetch(PDO::FETCH_ASSOC);
    }
}