<?php

require_once "../config/database.php";
require_once "../MVC/models/Message.php";
require_once "../MVC/models/Follow.php";

class MessageController {

    private $message;
    private $follow;

    public function __construct(){

        $database = new Database();
        $db = $database->connect();

        $this->message = new Message($db);
        $this->follow = new Follow($db);
    }

    public function send(){

        $emisor = $_SESSION['user_id'] ?? null;
        $receptor = $_POST['user_id'] ?? null;
        $texto = $_POST['texto'] ?? null;

        if(!$emisor || !$receptor || !$texto){
            die("Datos inválidos");
        }

        if($this->follow->mutualFollow($emisor,$receptor)){

            $this->message->send($emisor,$receptor,$texto);

        }else{

            echo "Debes seguir a este usuario para enviar mensajes";
        }
    }

    public function chat($user_id){

        $user = $_SESSION['user_id'] ?? null;

        if(!$user){
            die("Usuario no autenticado");
        }

        $messages = $this->message->getChat($user,$user_id);

        require "../MVC/views/messages.php";
    }
}