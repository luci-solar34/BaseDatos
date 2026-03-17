<?php

require_once "../config/database.php";
require_once "../MVC/models/User.php";
require_once "../MVC/models/Follow.php";

class UserController {

    private $user;
    private $follow;

    public function __construct(){

        $database = new Database();
        $db = $database->connect();

        $this->user = new User($db);
        $this->follow = new Follow($db);
    }

    public function profile($id){

        $user = $this->user->getById($id);

        require "../MVC/views/profile.php";
    }

    public function updateBio(){

        $bio = $_POST['bio'] ?? null;
        $id = $_SESSION['user_id'] ?? null;

        if(!$bio || !$id){
            die("Datos inválidos");
        }

        $this->user->updateBio($id,$bio);
    }

    public function updatePfp(){

    $user_id = $_SESSION['user_id'];

    if(!isset($_FILES['pfp'])){
        die("No se subió ninguna imagen");
    }

    $file = $_FILES['pfp'];

    $filename = time() . "_" . basename($file['name']);

    $destination = "../photos_pfp/" . $filename;

    move_uploaded_file($file['tmp_name'], $destination);

    $path = "photos_pfp/" . $filename;

    $this->user->updatePfp($user_id,$path);

    header("Location: /LASK/public/index.php/profile?id=".$user_id);
    exit;
}

    public function follow(){

        $seguidor = $_SESSION['user_id'] ?? null;
        $seguido = $_POST['user_id'] ?? null;

        if(!$seguidor || !$seguido){
            die("Datos inválidos");
        }

        if(!$this->follow->isFollowing($seguidor,$seguido)){
            $this->follow->follow($seguidor,$seguido);
        }
    }

    public function unfollow(){

        $seguidor = $_SESSION['user_id'] ?? null;
        $seguido = $_POST['user_id'] ?? null;

        if(!$seguidor || !$seguido){
            die("Datos inválidos");
        }

        $this->follow->unfollow($seguidor,$seguido);
    }
}