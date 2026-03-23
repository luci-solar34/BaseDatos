<?php

require_once __DIR__ . "/Controller.php";
require_once __DIR__ . "/../models/User.php";

class AuthController extends Controller {

    private $user;

    public function __construct(){
        $db = $this->connectDatabase();
        $this->user = new User($db);
    }

    public function showLoginForm(){
        $flash = $this->consumeFlash();
        $this->render('login.php', [
            'flashMessage' => $flash['message'],
            'flashMessageType' => $flash['type'],
        ]);
    }

    public function showRegisterForm(){
        $flash = $this->consumeFlash();
        $this->render('register.php', [
            'flashMessage' => $flash['message'],
            'flashMessageType' => $flash['type'],
        ]);
    }

    public function login(){

        $this->verifyCsrfToken();

        $username = $this->postString('username');
        $password = $this->postString('password');

        if(!$username || !$password){
            $this->setFlash('Datos inválidos');
            $this->redirectToRoute('login');
        }

        if(!$this->esEntradaSegura($username)){
            $this->setFlash('Los datos contienen caracteres no permitidos.');
            $this->redirectToRoute('login');
        }

        $result = $this->user->login($username, $password);

        if($result){
            if((int)$result['estado_usuario'] === 0){
                $this->setFlash('Usuario inactivo. Contacta al administrador.');
                $this->redirectToRoute('login');
            }

            // Regenerar ID de sesion para prevenir session fixation
            session_regenerate_id(true);

            $_SESSION['user_id'] = (int)$result['id_usuario'];
            $_SESSION['username'] = $result['nombre_usuario'];
            $_SESSION['role'] = (int)$result['id_rol'];

            $this->redirectToPath($this->publicUrl());
        }

        $this->setFlash('Credenciales incorrectas');
        $this->redirectToRoute('login');
    }

    public function register(){

        $this->verifyCsrfToken();

        if(!$this->postBool('terms')){
            $this->setFlash('Debes aceptar los términos y condiciones');
            $this->redirectToRoute('register');
        }

        $data = [
            'email' => filter_var($this->postString('email'), FILTER_VALIDATE_EMAIL) ?: null,
            'username' => $this->postString('username'),
            'password' => $this->postString('password'),
            'pais' => $this->postInt('pais', 0),
            'rol' => $this->postInt('rol', 0),
        ];

        if(!$data['email'] || !$data['username'] || !$data['password'] || !$data['pais'] || !$data['rol']){
            $this->setFlash('Datos incompletos');
            $this->redirectToRoute('register');
        }

        // Politica de contrasena: minimo 8 caracteres
        if(strlen($data['password']) < 8){
            $this->setFlash('La contraseña debe tener al menos 8 caracteres.');
            $this->redirectToRoute('register');
        }

        if(!$this->esEntradaSegura($data['username'])){
            $this->setFlash('El nombre de usuario contiene caracteres no permitidos.');
            $this->redirectToRoute('register');
        }

        $nombre_artistico = $this->postString('nombre_artistico');

        if((int)$data['rol'] !== 2 && $nombre_artistico !== ''){
            $this->setFlash('Solo los artistas pueden tener nombre artístico. Si quieres ser artista, cambia el rol.');
            $this->redirectToRoute('register');
        }

        if($this->user->emailExists($data['email'])){
            $this->setFlash('El email ya está registrado');
            $this->redirectToRoute('register');
        }

        if($this->user->usernameExists($data['username'])){
            $this->setFlash('El nombre de usuario ya está en uso');
            $this->redirectToRoute('register');
        }

        try {
            $user_id = $this->user->register($data);
        } catch(PDOException $e) {
            if((int)$e->getCode() === 23000){
                if(strpos($e->getMessage(), 'email') !== false){
                    $this->setFlash('El email ya está registrado');
                } elseif(strpos($e->getMessage(), 'nombre_usuario') !== false){
                    $this->setFlash('El nombre de usuario ya está en uso');
                } else {
                    $this->setFlash('El email o nombre de usuario ya está en uso');
                }
            } else {
                $this->setFlash('Ocurrió un error al crear la cuenta. Intenta de nuevo.');
            }

            $this->redirectToRoute('register');
        }

        if((int)$data['rol'] === 2){

            if($nombre_artistico === ''){
                $this->setFlash('Debes ingresar nombre artístico');
                $this->redirectToRoute('register');
            }

            $this->user->createArtist($user_id, $nombre_artistico);
        }

        $this->setFlash('Cuenta creada correctamente. Por favor inicia sesión.', 'success');
        $this->redirectToRoute('login');
    }

    public function logout(){
        session_destroy();
        $this->redirectToPath($this->publicUrl());
    }
}
