<?php

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

    case "/releases":

    $controller = new HomeController();
    $controller->releases();

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

    case "/artist/album/create":

        $controller = new ArtistController();
        $controller->createAlbum();

    break;

    case "/artist/song/create":

        $controller = new ArtistController();
        $controller->createSong();

    break;


    case "/playlist/create":

    if($_SERVER['REQUEST_METHOD'] === "POST"){

        $controller = new PlaylistController();
        $controller->create();

    } else {

        require "../MVC/views/playlist_create.php";

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

        require_once "../MVC/models/Song.php";

        $database = new Database();
        $db = $database->connect();

        $songModel = new Song($db);

        $songs = $songModel->getAll();
        $playlist = $_GET['playlist'];

        require "../MVC/views/add_song.php";
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