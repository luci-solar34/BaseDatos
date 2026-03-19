<?php

require_once "../config/database.php";
require_once "../MVC/models/Tag.php";
require_once "../MVC/models/User.php";

class TagController {

    private $tag;
    private $user;

    public function __construct(){

        $database = new Database();
        $db = $database->connect();

        $this->tag = new Tag($db);
        $this->user = new User($db);
    }

    private function ensureAdmin(){
        if(empty($_SESSION['user_id']) || empty($_SESSION['role']) || $_SESSION['role'] != 1){
            $_SESSION['flash_message'] = "Acceso denegado: solo administradores pueden crear tags.";
            header("Location: /LASK/public/index.php");
            exit;
        }
    }

    public function showCreate(){
        $this->ensureAdmin();

        $flashMessage = $_SESSION['flash_message'] ?? null;
        unset($_SESSION['flash_message']);

        // Mostrar todos los tags para el admin en la misma vista de creación
        $tags = $this->tag->getAllTags();

        require "../MVC/views/create_tag.php";
    }

    public function create(){
        $this->ensureAdmin();

        $nombre = trim($_POST['nombre_tag'] ?? '');
        $descripcion = trim($_POST['descripcion_tag'] ?? '');

        if($nombre === ''){
            $_SESSION['flash_message'] = "El nombre del tag es obligatorio.";
            header("Location: /LASK/public/index.php/tag/create");
            exit;
        }

        try {
            $result = $this->tag->createTag($nombre, $descripcion);

            if($result){
                $_SESSION['flash_message'] = "Tag creado correctamente.";
                header("Location: /LASK/public/index.php/tag/create");
                exit;
            }

            $_SESSION['flash_message'] = "No se pudo crear el tag. Verifica los datos.";
            header("Location: /LASK/public/index.php/tag/create");
            exit;

        } catch (PDOException $e) {
            if($e->getCode() == 23000){
                $_SESSION['flash_message'] = "Ya existe un tag con ese nombre.";
            } else {
                $_SESSION['flash_message'] = "Error al crear tag: " . $e->getMessage();
            }
            header("Location: /LASK/public/index.php/tag/create");
            exit;
        }
    }

    public function show($id){

        $tag = $this->tag->getById($id);

        if(!$tag){
            die("Tag no encontrado");
        }

        $songs = $this->tag->getSongsByTag($id);

        require "../MVC/views/detail_tag.php";
    }

    public function index(){

        $query = trim($_GET['q'] ?? '');
        $tags = $query !== '' ? $this->tag->searchTags($query) : $this->tag->getAllTags();

        require "../MVC/views/tags.php";
    }
}
