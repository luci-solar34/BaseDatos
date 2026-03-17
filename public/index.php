<?php

// Enable error reporting for development (remove/disable in production)
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();

require_once "../config/database.php";

require_once "../MVC/controllers/AuthController.php";
require_once "../MVC/controllers/UserController.php";
require_once "../MVC/controllers/ArtistController.php";
require_once "../MVC/controllers/PlaylistController.php";
require_once "../MVC/controllers/MessageController.php";
require_once "../MVC/controllers/AdminController.php";
require_once "../MVC/controllers/HomeController.php";
require_once "../MVC/controllers/SearchController.php";

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

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

        require_once "../MVC/controllers/DetailController.php";

        $controller = new DetailController();
        $controller->song($_GET['id']);

    }

break;

case "/album":

    if(isset($_GET['id'])){

        require_once "../MVC/controllers/DetailController.php";

        $controller = new DetailController();
        $controller->album($_GET['id']);

    }

break;

case "/artist":

    if(isset($_GET['id'])){

        require_once "../MVC/controllers/DetailController.php";

        $controller = new DetailController();
        $controller->artist($_GET['id']);

    }

break;

case "/new-releases":

    require_once "../MVC/controllers/DetailController.php";

    $controller = new DetailController();
    $controller->newReleases();

break;

case "/like":

    if($_SERVER['REQUEST_METHOD'] === "POST"){

        require_once "../MVC/controllers/DetailController.php";

        $controller = new DetailController();
        $controller->like();

    }

break;

    case "/login":

        if ($_SERVER['REQUEST_METHOD'] === "POST") {

            $controller = new AuthController();
            $controller->login();

        } else {

            require "../MVC/views/login.php";

        }

    break;


    case "/register":

        if ($_SERVER['REQUEST_METHOD'] === "POST") {

            $controller = new AuthController();
            $controller->register();

        } else {

            require "../MVC/views/register.php";

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


    case "/profile":

        if (isset($_GET['id'])) {

            $controller = new UserController();
            $controller->profile($_GET['id']);

        } else {

            echo "Usuario no especificado";

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


    case "/artist":

        if (isset($_GET['id'])) {

            $controller = new ArtistController();
            $controller->profile($_GET['id']);

        } else {

            echo "Artista no especificado";

        }

    break;


    case "/playlist/create":

    if($_SERVER['REQUEST_METHOD'] === "POST"){

        $controller = new PlaylistController();
        $controller->create();

    } else {

        require "../MVC/views/playlist_create.php";

    }

    break;

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

        require_once "../MVC/models/Song.php";

        $database = new Database();
        $db = $database->connect();

        $songModel = new Song($db);

        $songs = $songModel->getAll();
        $playlist = $_GET['playlist'];

        require "../MVC/views/add_song.php";
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