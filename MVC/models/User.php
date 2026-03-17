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
}