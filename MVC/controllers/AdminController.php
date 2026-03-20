<?php

require_once __DIR__ . "/Controller.php";
require_once __DIR__ . "/../models/User.php";

class AdminController extends Controller {

    private $user;

    public function __construct(){
        $db = $this->connectDatabase();
        $this->user = new User($db);
    }

    private function ensureAdmin(){
        $this->requireAdmin('Acceso denegado');
    }

    public function denuncias(){

        $this->ensureAdmin();

        $denuncias = $this->user->getDenuncias();
        $flash = $this->consumeFlash();

        $this->render('admin_denuncias.php', [
            'denuncias' => $denuncias,
            'flashMessage' => $flash['message'],
        ]);
    }

    public function aceptarDenuncia(){

        $this->ensureAdmin();

        $id = $this->postInt('denuncia', 0);

        if(!$id){
            $this->setFlash('Denuncia inválida');
            $this->redirectToRoute('admin/denuncias');
        }

        $this->user->acceptDenuncia($id);

        $this->setFlash('Denuncia aceptada', 'success');
        $this->redirectToRoute('admin/denuncias');
    }

    public function rechazarDenuncia(){

        $this->ensureAdmin();

        $id = $this->postInt('denuncia', 0);

        if(!$id){
            $this->setFlash('Denuncia inválida');
            $this->redirectToRoute('admin/denuncias');
        }

        $this->user->rejectDenuncia($id);

        $this->setFlash('Denuncia rechazada', 'success');
        $this->redirectToRoute('admin/denuncias');
    }
}