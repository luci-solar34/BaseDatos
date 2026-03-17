public function index(){

    $user = $_SESSION['user_id'] ?? null;

    if(!$user){
        die("No autenticado");
    }

    $conversations = $this->message->getConversations($user);

    require "../MVC/views/messages_list.php";
}