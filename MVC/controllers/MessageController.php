<?php

require_once "../config/database.php";
require_once "../MVC/models/Message.php";
require_once "../MVC/models/Follow.php";
require_once "../MVC/models/User.php";

class MessageController {

    private $message;
    private $follow;
    private $userModel;

    public function __construct(){

        $database = new Database();
        $db = $database->connect();

        $this->message = new Message($db);
        $this->follow = new Follow($db);
        $this->userModel = new User($db);
    }

    public function index(){

    $user = $_SESSION['user_id'] ?? null;

    if(!$user){
        die("No autenticado");
    }

    $conversations = $this->message->getConversations($user);
    $mutuals = $this->follow->getMutuals($user);

    // 🔥 evitar duplicados (IMPORTANTE)
    $conversationIds = array_column($conversations, 'id_usuario');

    $mutuals = array_filter($mutuals, function($m) use ($conversationIds){
        return !in_array($m['id_usuario'], $conversationIds);
    });

    require "../MVC/views/messages_list.php";
}

    public function chat($user_id){

        $user = $_SESSION['user_id'] ?? null;

        if(!$user){
            die("Usuario no autenticado");
        }

        $messages = $this->message->getChat($user,$user_id);
        $otherUser = $this->userModel->getById($user_id);

        require "../MVC/views/chat.php";
    }

    public function send(){

        $user = $_SESSION['user_id'] ?? null;

        if(!$user){
            die("Usuario no autenticado");
        }

        $receptor = $_POST['user_id'] ?? null;
        $texto = trim($_POST['texto'] ?? '');

        if (!$receptor || $texto === '') {
            header("Location: /LASK/public/index.php/chat?user=" . urlencode($receptor));
            exit;
        }

        // Validate receptor exists
        $otherUser = $this->userModel->getById($receptor);
        if (!$otherUser) {
            die("Usuario receptor no válido");
        }

        try {
            $this->message->send($user, $receptor, $texto);
        } catch (Exception $e) {
            // In development, show details. Remove or log in production.
            die("Error al enviar mensaje: " . $e->getMessage());
        }

        header("Location: /LASK/public/index.php/chat?user=" . urlencode($receptor));
        exit;
    }
}