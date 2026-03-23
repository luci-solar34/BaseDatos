<?php

// Proteccion de cookies de sesion
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.use_strict_mode', 1);

session_start();

require_once __DIR__ . "/../config/constants.php";
require_once __DIR__ . "/../config/database.php";

require_once __DIR__ . "/../MVC/controllers/AuthController.php";
require_once __DIR__ . "/../MVC/controllers/UserController.php";
require_once __DIR__ . "/../MVC/controllers/ArtistController.php";
require_once __DIR__ . "/../MVC/controllers/PlaylistController.php";
require_once __DIR__ . "/../MVC/controllers/MessageController.php";
require_once __DIR__ . "/../MVC/controllers/AdminController.php";
require_once __DIR__ . "/../MVC/controllers/HomeController.php";
require_once __DIR__ . "/../MVC/controllers/SearchController.php";
require_once __DIR__ . "/../MVC/controllers/TagController.php";
require_once __DIR__ . "/../MVC/controllers/DetailController.php";


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['route'])){
    $uri = '/' . $_POST['route'];
}

$uri = str_replace("/LASK/public/index.php", "", $uri);
$uri = str_replace("/LASK/public", "", $uri);

if ($uri == "") {
    $uri = "/";
}

switch ($uri) {

    case "/":
    case "/index.php":

        $controller = new HomeController();
        $controller->home();

    break;

// Agrega estas rutas después de las existentes:

case "/song":

    if(isset($_GET['id'])){

        $controller = new DetailController();
        $controller->song($_GET['id']);

    }


break;

case "/tags":

    $controller = new TagController();
    $controller->index();

break;

case "/tag":

    if(isset($_GET['id'])){
        $controller = new TagController();
        $controller->show($_GET['id']);
    } else {
        echo "Tag no especificado";
    }

break;

case "/tag/create":

    $controller = new TagController();

    if($_SERVER['REQUEST_METHOD'] === "POST"){
        $controller->create();
    } else {
        $controller->showCreate();
    }

break;

case "/album/add-songs":

    if($_SERVER['REQUEST_METHOD'] === "POST"){

        $controller = new DetailController();
        $controller->addSongToAlbum();

    } else {

        if(isset($_GET['id'])){

            $controller = new DetailController();
            $controller->addSongToAlbum();

        } else {

            echo "Álbum no especificado";
        }
    }

break;

case "/album":

    if(isset($_GET['id'])){

        $controller = new DetailController();
        $controller->album($_GET['id']);

    }

break;


case "/new-releases":

    $controller = new DetailController();
    $controller->newReleases();

break;

case "/like":

    if($_SERVER['REQUEST_METHOD'] === "POST"){

        $controller = new DetailController();
        $controller->like();

    }

break;

case "/follow":

    if($_SERVER['REQUEST_METHOD'] === "POST"){

        $controller = new UserController();
        $controller->follow();

    }

break;

case "/unfollow":

    if($_SERVER['REQUEST_METHOD'] === "POST"){

        $controller = new UserController();
        $controller->unfollow();

    }

break;

case "/block":

    if($_SERVER['REQUEST_METHOD'] === "POST"){
        $controller = new UserController();
        $controller->block();
    }

break;

case "/unblock":

    if($_SERVER['REQUEST_METHOD'] === "POST"){
        $controller = new UserController();
        $controller->unblock();
    }

break;

    case "/login":

        $controller = new AuthController();

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $controller->login();

        } else {
            $controller->showLoginForm();

        }

    break;


    case "/register":

        $controller = new AuthController();

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $controller->register();

        } else {
            $controller->showRegisterForm();

        }

    break;


    case "/logout":

        $controller = new AuthController();
        $controller->logout();

    break;



    case "/search":

        $controller = new SearchController();
        $controller->search();

    break;

    case "/search/autocomplete":

        $controller = new SearchController();
        $controller->autocomplete();

    break;


    case "/profile/followers":

        if(isset($_GET['id'])){
            $controller = new UserController();
            $controller->followers($_GET['id']);
        } else {
            echo "Usuario no especificado";
        }

    break;

    case "/profile/following":

        if(isset($_GET['id'])){
            $controller = new UserController();
            $controller->following($_GET['id']);
        } else {
            echo "Usuario no especificado";
        }

    break;

    case "/profile":

        if (isset($_GET['id'])) {

            $controller = new UserController();
            $controller->profile($_GET['id']);

        } else {

            echo "Usuario no especificado";

        }

    break;

    case "/admin/users":

        $controller = new UserController();
        $controller->listUsers();

    break;

    case "/admin/user/change-state":

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $controller = new UserController();
            $controller->changeUserState();
        } else {
            echo "Método no permitido";
        }

    break;

    case "/report":

        if($_SERVER['REQUEST_METHOD'] === "POST"){
            $controller = new UserController();
            $controller->submitReport();
        } else {
            echo "Método no permitido";
        }

    break;

    case "/admin/denuncias":

        $controller = new AdminController();
        $controller->denuncias();

    break;

    case "/admin/denuncias/accept":

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $controller = new AdminController();
            $controller->aceptarDenuncia();
        } else {
            echo "Método no permitido";
        }

    break;

    case "/admin/denuncias/reject":

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $controller = new AdminController();
            $controller->rechazarDenuncia();
        } else {
            echo "Método no permitido";
        }

    break;

    case "/update_pfp":

    if($_SERVER['REQUEST_METHOD'] === "POST"){

        $controller = new UserController();
        $controller->updatePfp();

    }

    break;

    case "/update_bio":

    if($_SERVER['REQUEST_METHOD'] === "POST"){

        $controller = new UserController();
        $controller->updateBio();

    }

    break;


    case "/artist/create-album":

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $controller = new ArtistController();
            $controller->createAlbum();
        } else {
            if (isset($_GET['id'])) {
                $controller = new ArtistController();
                $controller->showCreateAlbum($_GET['id']);
            } else {
                echo "Artista no especificado";
            }
        }

    break;

    case "/artist/create-song":

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $controller = new ArtistController();
            $controller->createSong();
        } else {
            if (isset($_GET['id'])) {
                $controller = new ArtistController();
                $controller->showCreateSong($_GET['id']);
            } else {
                echo "Artista no especificado";
            }
        }

    break;

    case "/artist/edit-album":

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $controller = new ArtistController();
            $controller->editAlbum();
        } else {
            if (isset($_GET['id'])) {
                $controller = new ArtistController();
                $controller->showEditAlbum($_GET['id']);
            } else {
                echo "Álbum no especificado";
            }
        }

    break;

    case "/artist/edit-song":

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $controller = new ArtistController();
            $controller->editSong();
        } else {
            if (isset($_GET['id'])) {
                $controller = new ArtistController();
                $controller->showEditSong($_GET['id']);
            } else {
                echo "Canción no especificada";
            }
        }

    break;

    case "/artist/add-comment":

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $controller = new ArtistController();
            $controller->addComment();
        }

    break;

    case "/artist":

        if (isset($_GET['id'])) {
            $controller = new DetailController();
            $controller->artist($_GET['id']);
        } else {
            echo "Artista no especificado";
        }

    break;


    case "/playlist/create":

    $controller = new PlaylistController();

    if($_SERVER['REQUEST_METHOD'] === "POST"){
        $controller->create();

    } else {
        $controller->showCreateForm();

    }

    break;

    case "/playlist":

    if(isset($_GET['id'])){

        $controller = new PlaylistController();
        $controller->show($_GET['id']);

    } else {

        echo "Playlist no especificada";

    }

    break;

    case "/playlist/add-song":

    if($_SERVER['REQUEST_METHOD'] === "POST"){

        $controller = new PlaylistController();
        $controller->addSong();

    } else {

        $controller = new PlaylistController();
        $controller->showAddSongForm();
    }

break;

case "/playlist/edit":

    if(isset($_GET['id'])){

        $controller = new PlaylistController();
        $controller->edit($_GET['id']);

    } else {

        echo "Playlist no especificada";

    }

break;

case "/playlist/change-privacy":

    if($_SERVER['REQUEST_METHOD'] === "POST"){

        $controller = new PlaylistController();
        $controller->changePrivacy();

    }

break;

case "/playlist/remove-song":

    if($_SERVER['REQUEST_METHOD'] === "POST"){

        $controller = new PlaylistController();
        $controller->removeSong();

    }

break;

case "/messages":

    $controller = new MessageController();
    $controller->index();

break;

case "/chat":

    if(isset($_GET['user'])){
        $controller = new MessageController();
        $controller->chat($_GET['user']);
    }

break;

case "/message/send":

    if($_SERVER['REQUEST_METHOD'] === "POST"){
        $controller = new MessageController();
        $controller->send();
    }

break;




    default:

        echo "404 Página no encontrada";
}