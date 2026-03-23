<?php

class Block {

    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    
    public function block($bloqueador, $bloqueado){

   
        $query = "INSERT INTO Bloquea (id_bloqueador, id_bloqueado)
                  VALUES (:b1, :b2)
                  ON DUPLICATE KEY UPDATE fecha_bloqueo = NOW()";

        $stmt = $this->conn->prepare($query);

        if(!$stmt){
            error_log('[Block] prepare() failed on block(): ' . implode(' | ', $this->conn->errorInfo()));
            return false;
        }

        $stmt->bindParam(":b1", $bloqueador, PDO::PARAM_INT);
        $stmt->bindParam(":b2", $bloqueado, PDO::PARAM_INT);

        $ok = $stmt->execute();

        if(!$ok){
            error_log('[Block] execute() failed on block(): ' . implode(' | ', $stmt->errorInfo()));
            return false;
        }

        return $this->hasBlocked($bloqueador, $bloqueado);
    }

    public function unblock($bloqueador, $bloqueado){

        $query = "DELETE FROM Bloquea
                  WHERE id_bloqueador = :b1
                  AND id_bloqueado = :b2";

        $stmt = $this->conn->prepare($query);

        if(!$stmt){
            error_log('[Block] prepare() failed on unblock(): ' . implode(' | ', $this->conn->errorInfo()));
            return false;
        }

        $stmt->bindParam(":b1", $bloqueador, PDO::PARAM_INT);
        $stmt->bindParam(":b2", $bloqueado, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Verifica bloqueo bidireccional entre dos usuarios
    public function isBlocked($user1, $user2){

        $query = "SELECT 1 FROM Bloquea
                  WHERE (id_bloqueador = :u1 AND id_bloqueado = :u2)
                     OR (id_bloqueador = :u2 AND id_bloqueado = :u1)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":u1", $user1, PDO::PARAM_INT);
        $stmt->bindParam(":u2", $user2, PDO::PARAM_INT);

        $stmt->execute();

        return (bool)$stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Verifica si el usuario A bloqueo al usuario B
    public function hasBlocked($bloqueador, $bloqueado){

        $query = "SELECT 1 FROM Bloquea
                  WHERE id_bloqueador = :b1
                  AND id_bloqueado = :b2";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":b1", $bloqueador, PDO::PARAM_INT);
        $stmt->bindParam(":b2", $bloqueado, PDO::PARAM_INT);

        $stmt->execute();

        return (bool)$stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Verifica si el usuario fue bloqueado por otro
    public function isBlockedBy($user, $blockedBy){
        return $this->hasBlocked($blockedBy, $user);
    }
}