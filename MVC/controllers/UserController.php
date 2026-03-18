<?php

require_once "../config/database.php";
require_once "../MVC/models/User.php";
require_once "../MVC/models/Follow.php";
require_once "../MVC/models/Playlist.php";

class UserController {

    private $user;
    private $follow;
    private $playlist;

    public function __construct(){

        $database = new Database();
        $db = $database->connect();

        $this->user = new User($db);
        $this->follow = new Follow($db);
        $this->playlist = new Playlist($db);
    }

    public function profile($id){

        $user = $this->user->getById($id);
        if(!$user){
            die("Usuario no encontrado");
        }

        $viewer = $_SESSION['user_id'] ?? null;
        $viewerData = $viewer ? $this->user->getById($viewer) : null;

        // Si es artista, su perfil principal es detail_artist
        if($user['id_rol'] == 2){
            header("Location: /LASK/public/index.php/artist?id=" . $id);
            exit;
        }

        $followers = $this->follow->countFollowers($id);
        $following = $this->follow->countFollowing($id);

        $isFollowing = false;
        if($viewer && $viewer != $id){
            $isFollowing = $this->follow->isFollowing($viewer, $id);
        }

        $playlists = $this->playlist->getUserPlaylists($id,$viewer);

        $canReport = false;
        if($viewer && $viewer != $id && $viewerData['id_rol'] != 1 && $user['id_rol'] != 1){
            $canReport = true;
        }

        $flashMessage = $_SESSION['flash_message'] ?? null;
        unset($_SESSION['flash_message']);

        require "../MVC/views/profile.php";
    }

    public function submitReport(){
        $denunciante = $_SESSION['user_id'] ?? null;
        $denunciado = $_POST['denunciado_id'] ?? null;
        $motivo = trim($_POST['motivo_denuncia'] ?? '');
        $descripcion = trim($_POST['descripcion_denuncia'] ?? '');

        if(!$denunciante || !$denunciado || !$motivo || !$descripcion){
            $_SESSION['flash_message'] = "Por favor completa todos los campos de la denuncia.";
            header("Location: /LASK/public/index.php/profile?id=".$denunciado);
            exit;
        }

        if($denunciante == $denunciado){
            $_SESSION['flash_message'] = "No puedes denunciarte a ti mismo.";
            header("Location: /LASK/public/index.php/profile?id=".$denunciado);
            exit;
        }

        $denuncianteData = $this->user->getById($denunciante);
        $denunciadoData = $this->user->getById($denunciado);

        if(!$denuncianteData || !$denunciadoData){
            die("Usuario no encontrado");
        }

        if($denuncianteData['id_rol'] == 1 || $denunciadoData['id_rol'] == 1){
            $_SESSION['flash_message'] = "No se puede realizar denuncias contra/desde administradores.";
            header("Location: /LASK/public/index.php/profile?id=".$denunciado);
            exit;
        }

        // Evita denuncias duplicadas por clave única
        if($this->user->hasDenuncia($denunciante, $denunciado)){
            $_SESSION['flash_message'] = "Ya has denunciado a este usuario. Espera la resolución.";
            header("Location: /LASK/public/index.php/profile?id=".$denunciado);
            exit;
        }

        $result = $this->user->createDenuncia($denunciante, $denunciado, $motivo, $descripcion);

        if($result){
            $_SESSION['flash_message'] = "Gracias por denunciar esta cuenta, tu solicitud está en progreso.";
        } else {
            $_SESSION['flash_message'] = "Ocurrió un error al enviar la denuncia. Intenta de nuevo.";
        }

        header("Location: /LASK/public/index.php/profile?id=".$denunciado);
        exit;
    }

    public function followers($id){
        $user = $this->user->getById($id);
        if(!$user){
            die("Usuario no encontrado");
        }
        $followers = $this->follow->getFollowers($id);
        require "../MVC/views/followers.php";
    }

    public function following($id){
        $user = $this->user->getById($id);
        if(!$user){
            die("Usuario no encontrado");
        }
        $following = $this->follow->getFollowing($id);
        require "../MVC/views/following.php";
    }

    public function updateBio(){

    $bio = $_POST['bio'] ?? null;
    $id = $_SESSION['user_id'] ?? null;

    if(!$id){
        die("Datos inválidos");
    }

    $this->user->updateBio($id,$bio);

    header("Location: /LASK/public/index.php/profile?id=".$id);
    exit;
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

    // 👇 REDIRECCIÓN CORRECTA
    header("Location: /LASK/public/index.php/profile?id=" . $seguido);
    exit;
}
    public function unfollow(){

    $seguidor = $_SESSION['user_id'] ?? null;
    $seguido = $_POST['user_id'] ?? null;

    if(!$seguidor || !$seguido){
        die("Datos inválidos");
    }

    $this->follow->unfollow($seguidor,$seguido);

    // 👇 REDIRECCIÓN CORRECTA
    header("Location: /LASK/public/index.php/profile?id=" . $seguido);
    exit;
}

    
}