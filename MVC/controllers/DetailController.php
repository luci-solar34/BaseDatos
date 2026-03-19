<?php

require_once "../config/database.php";
require_once "../MVC/models/Song.php";
require_once "../MVC/models/Album.php";
require_once "../MVC/models/Artist.php";
require_once "../MVC/models/Like.php";
require_once "../MVC/models/Follow.php";
require_once "../MVC/models/Tag.php";
require_once "../MVC/models/Playlist.php";
require_once "../MVC/models/Block.php";

class DetailController {

    private $song;
    private $album;
    private $artist;
    private $user;
    private $like;
    private $follow;
    private $tag;
    private $playlist;
    private $block;

    public function __construct(){

        $database = new Database();
        $db = $database->connect();

        $this->song = new Song($db);
        $this->album = new Album($db);
        $this->artist = new Artist($db);
        require_once "../MVC/models/User.php";
        $this->user = new User($db);
        $this->like = new Like($db);
        $this->follow = new Follow($db);
        $this->tag = new Tag($db);
        $this->playlist = new Playlist($db);
        $this->block = new Block($db);
    }

    public function song($id){

        // Obtener información de la canción
        $query = "SELECT C.*, A.nombre_artistico, AL.nombre_album, AL.portada_album,
                         L.letra_cancion, L.texto_fonetico
                  FROM Canciones C
                  INNER JOIN Artista A ON C.id_artista = A.id_usuario
                  LEFT JOIN Albumes AL ON C.id_album = AL.id_album
                  LEFT JOIN Letras L ON C.id_cancion = L.id_cancion
                  WHERE C.id_cancion = :id";

        $stmt = $this->song->getConnection()->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        $song = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$song){
        die("Canción no encontrada");
        }

        $viewerId = $_SESSION['user_id'] ?? null;

        if($viewerId && $this->block->isBlocked($viewerId, $song['id_artista'])){
        die("Contenido no disponible");
        }

        // Contar likes
        $likes = $this->like->countLikes($id);
        $tags = $this->tag->getSongTags($id);

        // Verificar si el usuario actual le dio like
        $user_liked = false;
        if(isset($_SESSION['user_id'])){
            $query = "SELECT 1 FROM Likes WHERE id_usuario = :user AND id_cancion = :song";
            $stmt = $this->song->getConnection()->prepare($query);
            $stmt->bindParam(":user", $_SESSION['user_id']);
            $stmt->bindParam(":song", $id);
            $stmt->execute();
            $user_liked = $stmt->rowCount() > 0;
        }

        require "../MVC/views/detail_song.php";
    }

    public function album($id){

        // Obtener información del álbum
        $query = "SELECT A.*, AR.id_usuario AS id_artista, AR.nombre_artistico, U.nombre_usuario
              FROM Albumes A
              INNER JOIN Artista AR ON A.id_artista = AR.id_usuario
              INNER JOIN Usuarios U ON AR.id_usuario = U.id_usuario
              WHERE A.id_album = :id";

        $stmt = $this->album->getConnection()->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        $album = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$album){
            die("Álbum no encontrado");
        }

        $viewerId = $_SESSION['user_id'] ?? null;

        if($viewerId && $this->block->isBlocked($viewerId, $album['id_artista'])){
        die("Contenido no disponible");
        }

        // Obtener canciones del álbum con letras
        $query = "SELECT C.*, A.nombre_artistico, L.letra_cancion, L.texto_fonetico
                  FROM Canciones C
                  INNER JOIN Artista A ON C.id_artista = A.id_usuario
                  LEFT JOIN Letras L ON C.id_cancion = L.id_cancion
                  WHERE C.id_album = :id
                  ORDER BY C.numero_pista";

        $stmt = $this->album->getConnection()->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $viewerId = $_SESSION['user_id'] ?? null;
        foreach($songs as &$song){
            $likeData = $this->like->countLikes($song['id_cancion']);
            $song['likes_total'] = (int)($likeData['total'] ?? 0);
            $song['user_liked'] = $viewerId ? $this->like->isLiked($viewerId, $song['id_cancion']) : false;
        }
        unset($song);

        require "../MVC/views/detail_album.php";
    }

    public function artist($id){

        // Obtener información del artista
        $artist = $this->artist->getById($id);

        if(!$artist){
            die("Artista no encontrado");
        }

        $viewerId = $_SESSION['user_id'] ?? null;

        if($viewerId && $this->block->isBlocked($viewerId, $id)){
            // Mostrar la misma vista de "Cuenta privada" para quien bloquea o es bloqueado.
            $user = $artist; // reutiliza la vista que espera $user
            $canUnblock = $this->block->hasBlocked($viewerId, $id);
            $flashMessage = $_SESSION['flash_message'] ?? null;
            unset($_SESSION['flash_message']);
            require "../MVC/views/profile_blocked.php";
            return;
        }

        // Verificar si el usuario actual sigue a este artista
        $viewerId = $_SESSION['user_id'] ?? null;
        $isFollowing = false;
        if($viewerId && $viewerId != $id){
            $isFollowing = $this->follow->isFollowing($viewerId, $id);
        }

        // Obtener canciones del artista
        $query = "SELECT C.*, AL.nombre_album
                  FROM Canciones C
                  LEFT JOIN Albumes AL ON C.id_album = AL.id_album
                  WHERE C.id_artista = :id
                  ORDER BY C.id_cancion DESC";

        $stmt = $this->artist->getConnection()->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Obtener álbumes del artista
        $query = "SELECT AL.*
              FROM Albumes AL
              WHERE AL.id_artista = :id
                  ORDER BY AL.fecha_lanzamiento DESC";

        $stmt = $this->artist->getConnection()->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        $albums = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Contar seguidores
        $followers = $this->follow->countFollowers($id);
        $following = $this->follow->countFollowing($id);
        $playlists = $this->playlist->getUserPlaylists($id, $viewerId);

        $viewerId = $_SESSION['user_id'] ?? null;
        $viewer = $viewerId ? $this->user->getById($viewerId) : null;
        $canReport = false;
        if($viewerId && $viewerId != $id && $viewer && $viewer['id_rol'] != 1 && $artist['id_rol'] != 1){
            $canReport = true;
        }

        $flashMessage = $_SESSION['flash_message'] ?? null;
        unset($_SESSION['flash_message']);

        // Obtener comentarios
        $query = "SELECT C.texto AS comentario, C.fecha_comentario, U.nombre_usuario
                  FROM Comentarios_Artista C
                  INNER JOIN Usuarios U ON C.id_usuario = U.id_usuario
                  WHERE C.id_artista = :id
                  ORDER BY C.fecha_comentario DESC";

        $stmt = $this->artist->getConnection()->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require "../MVC/views/detail_artist.php";
    }

    public function like(){

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])){
            
            $song_id = $_POST['song_id'] ?? null;
            $user_id = $_SESSION['user_id'];

            if($song_id){
                // Chequear si ya existe el like
                if($this->like->isLiked($user_id, $song_id)){
                    // Si existe, borrar
                    $this->like->unlikeSong($user_id, $song_id);
                } else {
                    // Si no existe, crear
                    $this->like->likeSong($user_id, $song_id);
                }
            }

            if(!empty($_POST['album_id'])){
                header("Location: /LASK/public/index.php/album?id=" . (int)$_POST['album_id']);
            } else {
                header("Location: /LASK/public/index.php/song?id=" . $song_id);
            }
            exit;
        }
    }

    public function newReleases(){

        // Obtener nuevas canciones (últimas 20)
        $query = "SELECT C.*, A.nombre_artistico, AL.nombre_album
                  FROM Canciones C
                  INNER JOIN Artista A ON C.id_artista = A.id_usuario
                  LEFT JOIN Albumes AL ON C.id_album = AL.id_album
                  ORDER BY C.id_cancion DESC
                  LIMIT 20";

        $stmt = $this->song->getConnection()->prepare($query);
        $stmt->execute();
        $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $viewerId = $_SESSION['user_id'] ?? null;

        if($viewerId){
        $songs = array_filter($songs, function($s) use ($viewerId){
        return !$this->block->isBlocked($viewerId, $s['id_artista']);
        });
        }

        // Obtener nuevos álbumes (últimos 20) - VERSIÓN CORREGIDA
        $query = "SELECT A.*, 
                         (SELECT AR.nombre_artistico 
                          FROM Canciones C2 
                          INNER JOIN Artista AR ON C2.id_artista = AR.id_usuario 
                          WHERE C2.id_album = A.id_album 
                          LIMIT 1) as nombre_artistico,
                         (SELECT U.nombre_usuario 
                          FROM Canciones C2 
                          INNER JOIN Artista AR ON C2.id_artista = AR.id_usuario 
                          INNER JOIN Usuarios U ON AR.id_usuario = U.id_usuario 
                          WHERE C2.id_album = A.id_album 
                          LIMIT 1) as nombre_usuario
                  FROM Albumes A
                  ORDER BY A.fecha_lanzamiento DESC
                  LIMIT 20";

        $stmt = $this->album->getConnection()->prepare($query);
        $stmt->execute();
        $albums = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require "../MVC/views/new_releases.php";
    }

    public function addSongToAlbum(){

        if($_SERVER['REQUEST_METHOD'] === "POST"){

            $album_id = $_POST['album_id'] ?? null;
            $nombre = $_POST['nombre'] ?? null;
            $artista = $_SESSION['user_id'] ?? null;

            $album = $this->album->getById($album_id);

            if(!$album_id || !$nombre || !$artista || !$album || (int)$album['id_artista'] !== (int)$artista){
                die("Datos incompletos");
            }

            // Subir archivo
            $path = '';
            if(isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0){

                $ext = pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION);
                if($ext == 'mp3'){

                    $path = 'music/' . uniqid() . '.' . $ext;
                    move_uploaded_file($_FILES['archivo']['tmp_name'], '../' . $path);
                }
            }

            $data = [
                "nombre" => $nombre,
                "numero_pista" => null,
                "path" => $path,
                "album" => $album_id,
                "artista" => $artista
            ];

            $this->song->create($data);

            header("Location: /LASK/public/index.php/album?id=" . $album_id);
            exit;

        } else {

            if(isset($_GET['id'])){

                $album_id = $_GET['id'];

                $album = $this->album->getById($album_id);

                if(!$album){
                    die("Álbum no encontrado");
                }

                $viewerId = $_SESSION['user_id'] ?? null;
                if(!$viewerId || (int)$album['id_artista'] !== (int)$viewerId){
                    die("Sin permisos para editar este álbum");
                }

                require "../MVC/views/add_songs_to_album.php";

            } else {

                echo "Álbum no especificado";
            }
        }
    }
}
?>