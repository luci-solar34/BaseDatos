<?php


class Database {


    private $host = "localhost";
    private $db_name = "laskBD";
    private $username = "root";
    private $password = "";


    private ?PDO $conn = null;


    public function connect(): PDO {


        try {


            $this->conn = new PDO(
                "mysql:host=".$this->host.";dbname=".$this->db_name,
                $this->username,
                $this->password
            );


            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        } catch(PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }


        return $this->conn;
    }
}
