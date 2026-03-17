<?php

require_once "../config/database.php";
require_once "../MVC/models/User.php";

class AdminController {

    private $user;

    public function __construct(){

        $database = new Database();
        $db = $database->connect();

        $this->user = new User($db);
    }

    public function denuncias(){

        $denuncias = $this->user->getDenuncias();

        require "../MVC/views/admin_denuncias.php";
    }

    public function aceptarDenuncia(){

        $id = $_POST['denuncia'];

        $this->user->acceptDenuncia($id);
    }

    public function rechazarDenuncia(){

        $id = $_POST['denuncia'];

        $this->user->rejectDenuncia($id);
    }

    public function cambiarEstadoUsuario(){

        $user = $_POST['user_id'];
        $estado = $_POST['estado'];

        $this->user->changeState($user,$estado);
    }
}