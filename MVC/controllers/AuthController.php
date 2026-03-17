<?php

require_once "../config/database.php";
require_once "../MVC/models/User.php";

class AuthController {

    private $user;

    public function __construct(){

        $database = new Database();
        $db = $database->connect();

        $this->user = new User($db);
    }

    public function login(){

        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;

        if(!$username || !$password){
            echo "Datos inválidos";
            return;
        }

        $result = $this->user->login($username,$password);

        if($result){

        $_SESSION['user_id'] = $result['id_usuario'];
        $_SESSION['username'] = $result['nombre_usuario'];
        $_SESSION['role'] = $result['id_rol'];

        header("Location: /LASK/public");
        exit;

        }else{

            echo "Credenciales incorrectas";
        }
    }

    public function register(){

        // validar términos
        if(!isset($_POST['terms'])){
            echo "Debes aceptar los términos y condiciones";
            return;
        }

        $data = [
            "email" => $_POST['email'] ?? null,
            "username" => $_POST['username'] ?? null,
            "password" => $_POST['password'] ?? null,
            "pais" => $_POST['pais'] ?? null,
            "rol" => $_POST['rol'] ?? null
        ];

        if(!$data['email'] || !$data['username'] || !$data['password'] || !$data['pais'] || !$data['rol']){
            echo "Datos incompletos";
            return;
        }

        // crear usuario
        $user_id = $this->user->register($data);

        // si es artista, crear registro en tabla Artista
        if($data['rol'] == 2){

            $nombre_artistico = $_POST['nombre_artistico'] ?? null;

            if(!$nombre_artistico){
                echo "Debes ingresar nombre artístico";
                return;
            }

            $this->user->createArtist($user_id, $nombre_artistico);
        }

        echo "Cuenta creada correctamente.<br>";
        echo '<a href="/LASK/public/index.php/login">Ir a iniciar sesión</a>';
    }

    public function logout(){

    session_destroy();

    header("Location: /LASK/public");
    exit;
}
}
