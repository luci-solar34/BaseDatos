<?php

require_once __DIR__ . "/Controller.php";
require_once __DIR__ . "/../models/Message.php";
require_once __DIR__ . "/../models/Follow.php";
require_once __DIR__ . "/../models/User.php";
require_once __DIR__ . "/../models/Block.php";

class MessageController extends Controller {

    private $message;
    private $follow;
    private $userModel;
    private $block;

    public function __construct(){
        $db = $this->connectDatabase();
        $this->message = new Message($db);
        $this->block = new Block($db);
        $this->follow = new Follow($db);
        $this->userModel = new User($db);
    }

    public function index(){
        $user = $this->requireAuthenticatedUser('login');
        $conversations = $this->message->getConversations($user);
        $mutuals = $this->follow->getMutuals($user);

        $conversations = array_filter($conversations, function($conv) use ($user){
            return !$this->block->isBlocked($user, $conv['id_usuario']);
        });

        $mutuals = array_filter($mutuals, function($m) use ($user){
            return !$this->block->isBlocked($user, $m['id_usuario']);
        });

        $conversationIds = array_column($conversations, 'id_usuario');
        $mutuals = array_filter($mutuals, function($m) use ($conversationIds){
            return !in_array($m['id_usuario'], $conversationIds);
        });

        $this->render('messages_list.php', [
            'conversations' => $conversations,
            'mutuals' => $mutuals,
        ]);
    }

    public function chat($user_id){
        $user = $this->requireAuthenticatedUser('login');
        $recipientId = (int)$user_id;

        if($recipientId <= 0){
            $this->abort('Usuario no válido', 422);
        }

        if($this->block->isBlocked($user, $recipientId)){
            $this->abort('No puedes ver este chat', 403);
        }

        $messages = $this->message->getChat($user, $recipientId);
        $otherUser = $this->userModel->getById($recipientId);
        if(!$otherUser){
            $this->abort('Usuario no válido', 404);
        }

        $chatPartnerName = $otherUser['nombre_usuario'] ?? 'usuario';
        $chatPartnerPfp  = $otherUser['pfp'] ?? null;
        $chatRecipientId = $recipientId;

        foreach($messages as &$message){
            $message['sender_label'] = ((int)$message['id_emisor'] === (int)$user) ? 'Yo' : $chatPartnerName;
        }
        unset($message);

        $this->render('chat.php', compact('messages', 'chatPartnerName', 'chatRecipientId', 'chatPartnerPfp'));
    }

    public function send(){
        $this->verifyCsrfToken();
        $user = $this->requireAuthenticatedUser('login');
        $receptor = $this->postInt('user_id', 0);
        $texto = $this->postString('texto');

        if (!$receptor || $texto === '') {
            $this->redirectToRoute('chat?user=' . urlencode((string)$receptor));
        }

        if(!$this->esEntradaSegura($texto)){
            $this->abort('El mensaje contiene caracteres no permitidos.', 422);
        }

        if($this->block->isBlocked($user, $receptor)){
            $this->abort('No puedes enviar mensajes a este usuario', 403);
        }

        $otherUser = $this->userModel->getById($receptor);
        if (!$otherUser) {
            $this->abort('Usuario receptor no válido', 404);
        }

        try {
            $this->message->send($user, $receptor, $texto);
        } catch (Exception $e) {
            $this->abort('Error al enviar mensaje', 500);
        }

        $this->redirectToRoute('chat?user=' . urlencode((string)$receptor));
    }
}