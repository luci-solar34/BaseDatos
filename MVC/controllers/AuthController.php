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
            $_SESSION['flash_message'] = "Datos inválidos";
            $_SESSION['flash_message_type'] = "error";
            header("Location: /LASK/public/index.php/login");
            exit;
        }

        $result = $this->user->login($username,$password);

        if($result){
            $_SESSION['user_id'] = $result['id_usuario'];
            $_SESSION['username'] = $result['nombre_usuario'];
            $_SESSION['role'] = $result['id_rol'];
            header("Location: /LASK/public");
            exit;
        } else {
            $_SESSION['flash_message'] = "Credenciales incorrectas";
            $_SESSION['flash_message_type'] = "error";
            header("Location: /LASK/public/index.php/login");
            exit;
        }
    }

    public function register(){

        // validar términos
        if(!isset($_POST['terms'])){
            $_SESSION['flash_message'] = "Debes aceptar los términos y condiciones";
            $_SESSION['flash_message_type'] = "error";
            header("Location: /LASK/public/index.php/register");
            exit;
        }

        $data = [
            "email" => $_POST['email'] ?? null,
            "username" => $_POST['username'] ?? null,
            "password" => $_POST['password'] ?? null,
            "pais" => $_POST['pais'] ?? null,
            "rol" => $_POST['rol'] ?? null
        ];

        if(!$data['email'] || !$data['username'] || !$data['password'] || !$data['pais'] || !$data['rol']){
            $_SESSION['flash_message'] = "Datos incompletos";
            $_SESSION['flash_message_type'] = "error";
            header("Location: /LASK/public/index.php/register");
            exit;
        }

        $nombre_artistico = trim($_POST['nombre_artistico'] ?? '');

        if((int)$data['rol'] !== 2 && $nombre_artistico !== ''){
            $_SESSION['flash_message'] = "Solo los artistas pueden tener nombre artístico. Si quieres ser artista, cambia el rol.";
            $_SESSION['flash_message_type'] = "error";
            header("Location: /LASK/public/index.php/register");
            exit;
        }

        // validar unicidad (email y nombre de usuario)
        if($this->user->emailExists($data['email'])){
            $_SESSION['flash_message'] = "El email ya está registrado";
            $_SESSION['flash_message_type'] = "error";
            header("Location: /LASK/public/index.php/register");
            exit;
        }

        if($this->user->usernameExists($data['username'])){
            $_SESSION['flash_message'] = "El nombre de usuario ya está en uso";
            $_SESSION['flash_message_type'] = "error";
            header("Location: /LASK/public/index.php/register");
            exit;
        }

        // crear usuario
        try {
            $user_id = $this->user->register($data);
        } catch(PDOException $e) {
            // En caso de race condition / duplicado en la DB, mostramos mensaje genérico o específico.
            if($e->getCode() == 23000){
                if(strpos($e->getMessage(), 'email') !== false){
                    $_SESSION['flash_message'] = "El email ya está registrado";
                } elseif(strpos($e->getMessage(), 'nombre_usuario') !== false){
                    $_SESSION['flash_message'] = "El nombre de usuario ya está en uso";
                } else {
                    $_SESSION['flash_message'] = "El email o nombre de usuario ya está en uso";
                }
            } else {
                $_SESSION['flash_message'] = "Ocurrió un error al crear la cuenta. Intenta de nuevo.";
            }
            $_SESSION['flash_message_type'] = "error";
            header("Location: /LASK/public/index.php/register");
            exit;
        }

        // si es artista, crear registro en tabla Artista
        if($data['rol'] == 2){

            if($nombre_artistico === ''){
                $_SESSION['flash_message'] = "Debes ingresar nombre artístico";
                $_SESSION['flash_message_type'] = "error";
                header("Location: /LASK/public/index.php/register");
                exit;
            }

            $this->user->createArtist($user_id, $nombre_artistico);
        }

        $_SESSION['flash_message'] = "Cuenta creada correctamente. Por favor inicia sesión.";
        $_SESSION['flash_message_type'] = "success";
        header("Location: /LASK/public/index.php/login");
        exit;
    }

    public function logout(){

    session_destroy();

    header("Location: /LASK/public");
    exit;
}
}
