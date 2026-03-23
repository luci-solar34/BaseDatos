<?php

require_once __DIR__ . "/Controller.php";
require_once __DIR__ . "/../models/Tag.php";
require_once __DIR__ . "/../models/User.php";
require_once __DIR__ . "/../models/Block.php";

class TagController extends Controller {

    private $tag;
    private $user;
    private $block;

    public function __construct(){
        $db = $this->connectDatabase();
        $this->tag = new Tag($db);
        $this->user = new User($db);
        $this->block = new Block($db);
    }

    private function ensureAdmin(){
        $this->requireAdmin('Acceso denegado: solo administradores pueden crear tags.');
    }

    public function showCreate(){
        $this->ensureAdmin();

        $flash = $this->consumeFlash();
        $tags = $this->tag->getAllTags();
        $profileUrl = $this->routeUrl('profile?id=' . (int)$this->sessionInt('user_id'));

        $this->render('create_tag.php', [
            'flashMessage' => $flash['message'],
            'tags' => $tags,
            'profileUrl' => $profileUrl,
        ]);
    }

    public function create(){
        $this->verifyCsrfToken();
        $this->ensureAdmin();

        $nombre = $this->postString('nombre_tag');
        $descripcion = $this->postString('descripcion_tag');

        if($nombre === ''){
            $this->setFlash('El nombre del tag es obligatorio.');
            $this->redirectToRoute('tag/create');
        }

        if(!$this->esEntradaSegura($nombre) || !$this->esEntradaSegura($descripcion)){
            $this->setFlash('Los datos del tag contienen caracteres no permitidos.');
            $this->redirectToRoute('tag/create');
        }

        try {
            $result = $this->tag->createTag($nombre, $descripcion);

            if($result){
                $this->setFlash('Tag creado correctamente.', 'success');
                $this->redirectToRoute('tag/create');
            }

            $this->setFlash('No se pudo crear el tag. Verifica los datos.');
            $this->redirectToRoute('tag/create');

        } catch (PDOException $e) {
            if((int)$e->getCode() === 23000){
                $this->setFlash('Ya existe un tag con ese nombre.');
            } else {
                $this->setFlash('Error al crear tag.');
            }

            $this->redirectToRoute('tag/create');
        }
    }

    public function show($id){

        $tagId = (int)$id;
        $tag = $this->tag->getById($tagId);

        if(!$tag){
            $this->abort('Tag no encontrado', 404);
        }

        $songs = $this->tag->getSongsByTag($tagId);
        $viewerId = $this->sessionInt('user_id');

        if($viewerId !== null){
            $songs = array_values(array_filter($songs, function($song) use ($viewerId){
                return !$this->block->isBlockedBy($viewerId, (int)$song['id_artista']);
            }));
        }

        $this->render('detail_tag.php', [
            'tag' => $tag,
            'songs' => $songs,
        ]);
    }

    public function index(){

        $query = $this->getString('q');
        $tags = $query !== '' ? $this->tag->searchTags($query) : $this->tag->getAllTags();
        $canCreateTag = $this->sessionInt('role') === 1;
        $flash = $this->consumeFlash();

        $this->render('tags.php', [
            'query' => $query,
            'tags' => $tags,
            'canCreateTag' => $canCreateTag,
            'flashMessage' => $flash['message'],
        ]);
    }
}
