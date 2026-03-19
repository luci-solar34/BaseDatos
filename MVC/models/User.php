<?php

class User {

    private $conn;
    private $table = "Usuarios";

    public function __construct($db){
        $this->conn = $db;
    }

    public function login($username,$password){

        $query = "SELECT * FROM Usuarios
                  WHERE nombre_usuario = :username
                  AND password = :password
                  AND estado_usuario = TRUE";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":username",$username);
        $stmt->bindParam(":password",$password);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function register($data){

        $query = "INSERT INTO Usuarios
        (email,nombre_usuario,password,id_pais,id_rol)
        VALUES (:email,:username,:password,:pais,:rol)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":email",$data['email']);
        $stmt->bindParam(":username",$data['username']);
        $stmt->bindParam(":password",$data['password']);
        $stmt->bindParam(":pais",$data['pais']);
        $stmt->bindParam(":rol",$data['rol']);

        $stmt->execute();

        return $this->conn->lastInsertId();
    }

    public function emailExists(string $email): bool {
        $query = "SELECT 1 FROM Usuarios WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        return $stmt->fetchColumn() !== false;
    }

    public function usernameExists(string $username): bool {
        $query = "SELECT 1 FROM Usuarios WHERE nombre_usuario = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        return $stmt->fetchColumn() !== false;
    }

    public function getById($id){

        $query = "SELECT U.*, P.nombre_pais, R.nombre_rol
                  FROM Usuarios U
                  INNER JOIN Paises P ON U.id_pais = P.id_pais
                  INNER JOIN Roles R ON U.id_rol = R.id_rol
                  WHERE U.id_usuario = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id",$id);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllUsers(){
        $query = "SELECT U.id_usuario, U.email, U.nombre_usuario, U.id_rol, R.nombre_rol, U.estado_usuario, U.fecha_creacion
                  FROM Usuarios U
                  INNER JOIN Roles R ON U.id_rol = R.id_rol
                  WHERE U.id_rol != 1
                  ORDER BY U.id_usuario ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateBio($id,$bio){

    $query = "UPDATE Usuarios
              SET bio = :bio
              WHERE id_usuario = :id";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(":bio",$bio);
    $stmt->bindParam(":id",$id);

    return $stmt->execute();
    }

    public function createArtist($user_id, $nombre_artistico){

        $query = "INSERT INTO Artista
                  (id_usuario, nombre_artistico)
                  VALUES (:id, :nombre)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $user_id);
        $stmt->bindParam(":nombre", $nombre_artistico);

        return $stmt->execute();
    }

    public function updatePfp($id,$pfp){

    $query = "UPDATE Usuarios
              SET pfp = :pfp
              WHERE id_usuario = :id";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(":pfp",$pfp);
    $stmt->bindParam(":id",$id);

    return $stmt->execute();
    }

    public function getDenuncias(){
        $query = "SELECT D.*,
                         U1.nombre_usuario AS denunciante,
                         U2.nombre_usuario AS denunciado,
                         E.nombre_estado_denuncia AS estado
                  FROM Denuncias D
                  LEFT JOIN Usuarios U1 ON D.denunciante_id = U1.id_usuario
                  LEFT JOIN Usuarios U2 ON D.denunciado_id = U2.id_usuario
                  LEFT JOIN EstadosDenuncia E ON D.id_estado_denuncia = E.id_estado_denuncia
                  ORDER BY D.fecha_denuncia DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMotivoDenunciaIdByName($nombre){
        $query = "SELECT id_motivo_denuncia FROM motivos_denuncia WHERE nombre_motivo = :nombre LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public function createMotivoDenuncia($nombre){
        $query = "INSERT INTO motivos_denuncia (nombre_motivo) VALUES (:nombre)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nombre", $nombre);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return null;
    }

    public function getOrCreateMotivoDenuncia($nombre){
        $id = $this->getMotivoDenunciaIdByName($nombre);
        if($id){
            return $id;
        }

        return $this->createMotivoDenuncia($nombre);
    }

    public function createDenuncia($denunciante_id, $denunciado_id, $motivo, $descripcion){
        $motivoId = $this->getOrCreateMotivoDenuncia($motivo);
        if(!$motivoId){
            return false;
        }

        $query = "INSERT INTO Denuncias
                  (id_motivo_denuncia, descripcion_denuncia, denunciante_id, denunciado_id, id_estado_denuncia, fecha_denuncia)
                  VALUES (:motivo, :descripcion, :denunciante, :denunciado, :estado, NOW())";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":motivo", $motivoId);
        $stmt->bindParam(":descripcion", $descripcion);
        $stmt->bindParam(":denunciante", $denunciante_id);
        $stmt->bindParam(":denunciado", $denunciado_id);
        $estado = 1; // pendiente
        $stmt->bindParam(":estado", $estado, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function hasDenuncia($denunciante_id, $denunciado_id){
        $query = "SELECT 1 FROM Denuncias WHERE denunciante_id = :denunciante AND denunciado_id = :denunciado LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":denunciante", $denunciante_id);
        $stmt->bindParam(":denunciado", $denunciado_id);
        $stmt->execute();
        return $stmt->fetchColumn() !== false;
    }

    public function acceptDenuncia($id){
        $query = "UPDATE Denuncias SET id_estado_denuncia = 2 WHERE id_denuncia = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function rejectDenuncia($id){
        $query = "UPDATE Denuncias SET id_estado_denuncia = 3 WHERE id_denuncia = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function changeState($user,$estado){
        $query = "UPDATE Usuarios SET estado_usuario = :estado WHERE id_usuario = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":estado", $estado, PDO::PARAM_BOOL);
        $stmt->bindParam(":id", $user);
        return $stmt->execute();
    }

}
