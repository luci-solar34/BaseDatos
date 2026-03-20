<?php

require_once __DIR__ . "/Controller.php";
require_once __DIR__ . "/../models/Song.php";
require_once __DIR__ . "/../models/Album.php";
require_once __DIR__ . "/../models/Artist.php";
require_once __DIR__ . "/../models/Like.php";
require_once __DIR__ . "/../models/Follow.php";
require_once __DIR__ . "/../models/Tag.php";
require_once __DIR__ . "/../models/Playlist.php";
require_once __DIR__ . "/../models/Block.php";
require_once __DIR__ . "/../models/Comment.php";
require_once __DIR__ . "/../models/User.php";

class DetailController extends Controller {

    private $song;
    private $album;
    private $artist;
    private $user;
    private $like;
    private $follow;
    private $tag;
    private $playlist;
    private $block;
    private $comment;

    public function __construct(){
        $db = $this->connectDatabase();
        $this->song = new Song($db);
        $this->album = new Album($db);
        $this->artist = new Artist($db);
        $this->user = new User($db);
        $this->like = new Like($db);
        $this->follow = new Follow($db);
        $this->tag = new Tag($db);
        $this->playlist = new Playlist($db);
        $this->block = new Block($db);
        $this->comment = new Comment($db);
    }

    private function isAjaxRequest(){
        return strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest'
            || strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false;
    }

    private function formatPlaylistLabels(array $playlists){
        foreach($playlists as &$playlist){
            $playlist['privacy_label'] = ((int)$playlist['privacidad_playlist'] === 0) ? '(Privada)' : '';
        }
        unset($playlist);

        return $playlists;
    }

    private function buildAlbumQueue(array $songs, $albumCover){
        $playableSongs = array_values(array_filter($songs, function($song){
            return !empty($song['path_link']);
        }));
        $playableIndex = [];

        foreach($playableSongs as $index => $playableSong){
            $playableIndex[$playableSong['id_cancion']] = $index;
        }

        foreach($songs as &$song){
            $song['playable_index'] = $playableIndex[$song['id_cancion']] ?? null;
        }
        unset($song);

        $albumQueue = array_map(function($song) use ($albumCover){
            return [
                'id' => (int)$song['id_cancion'],
                'src' => '/LASK/' . $song['path_link'],
                'title' => $song['nombre_cancion'],
                'cover' => '/LASK/' . (!empty($song['portada_cancion']) ? $song['portada_cancion'] : $albumCover),
                'letra' => $song['letra_cancion'] ?? null,
                'fonetico' => $song['texto_fonetico'] ?? null,
                'userLiked' => !empty($song['user_liked']),
                'likesTotal' => (int)($song['likes_total'] ?? 0),
            ];
        }, $playableSongs);

        return [
            'songs' => $songs,
            'albumQueueJson' => json_encode($albumQueue),
        ];
    }

    public function song($id){

        $viewerId = $this->requireAuthenticatedUser('login');

        $songId = (int)$id;
        $song = $this->song->getDetailById($songId);

        if(!$song){
            $this->abort('Canción no encontrada', 404);
        }

        if($this->block->isBlockedBy($viewerId, (int)$song['id_artista'])){
            $this->abort('Contenido no disponible', 403);
        }

        $likes = $this->like->countLikes($songId);
        $tags = $this->tag->getSongTags($songId);
        $user_liked = $this->like->isLiked($viewerId, $songId);
        $canEditSong = $viewerId === (int)$song['id_artista'];
        $songCover = !empty($song['portada_cancion']) ? $song['portada_cancion'] : 'Photos/banner_default.png';
        $songLikeCount = (int)($likes['total'] ?? 0);
        $songLikeLabel = $user_liked ? '♥ Quitar like' : '♥ Dar like';
        $lyricsText = !empty($song['letra_cancion']) ? $song['letra_cancion'] : 'Texto no disponible';
        $phoneticText = !empty($song['texto_fonetico']) ? $song['texto_fonetico'] : 'Texto no disponible';
        $hasAlbum = !empty($song['nombre_album']);

        $this->render('detail_song.php', compact(
            'song',
            'tags',
            'canEditSong',
            'songCover',
            'songLikeCount',
            'songLikeLabel',
            'lyricsText',
            'phoneticText',
            'hasAlbum'
        ));
    }

    public function album($id){

        $viewerId = $this->requireAuthenticatedUser('login');

        $albumId = (int)$id;
        $album = $this->album->getDetailByIdWithArtist($albumId);

        if(!$album){
            $this->abort('Álbum no encontrado', 404);
        }

        if($this->block->isBlockedBy($viewerId, (int)$album['id_artista'])){
            $this->abort('Contenido no disponible', 403);
        }

        $songs = $this->song->getByAlbumWithLyrics($albumId);

        foreach($songs as &$song){
            $likeData = $this->like->countLikes($song['id_cancion']);
            $song['likes_total'] = (int)($likeData['total'] ?? 0);
            $song['user_liked'] = $this->like->isLiked($viewerId, $song['id_cancion']);
            $song['can_play'] = !empty($song['path_link']);
            $song['can_edit'] = (int)$viewerId === (int)$album['id_artista'];
        }
        unset($song);

        $canEditAlbum = (int)$viewerId === (int)$album['id_artista'];
        $albumCover = !empty($album['portada_album']) ? $album['portada_album'] : 'Photos/banner_default.png';
        $albumArtistUrl = $this->routeUrl('artist?id=' . (int)$album['id_artista']);
        $queueData = $this->buildAlbumQueue($songs, $albumCover);
        $songs = $queueData['songs'];
        $albumQueueJson = $queueData['albumQueueJson'];

        $this->render('detail_album.php', compact(
            'album',
            'songs',
            'canEditAlbum',
            'albumCover',
            'albumArtistUrl',
            'albumQueueJson'
        ));
    }

    public function artist($id){

        $artistId = (int)$id;
        $artist = $this->artist->getById($artistId);

        if(!$artist){
            $this->abort('Artista no encontrado', 404);
        }

        $viewerId = $this->sessionInt('user_id');

        if($viewerId && $this->block->isBlockedBy($viewerId, $artistId)){
            $flash = $this->consumeFlash();
            $this->render('profile_blocked.php', [
                'user' => $artist,
                'canUnblock' => $this->block->hasBlocked($viewerId, $artistId),
                'flashMessage' => $flash['message'],
                'canReport' => false,
                'showReportedMessage' => false,
            ]);
            return;
        }

        $isFollowing = $viewerId && $viewerId !== $artistId
            ? $this->follow->isFollowing($viewerId, $artistId)
            : false;
        $songs = $this->song->getByArtistWithAlbum($artistId);
        $albums = $this->album->getByArtist($artistId);
        $followers = $this->follow->countFollowers($artistId);
        $following = $this->follow->countFollowing($artistId);
        $playlists = $this->formatPlaylistLabels($this->playlist->getUserPlaylists($artistId, $viewerId));
        $viewer = $viewerId ? $this->user->getById($viewerId) : null;
        $canReport = $viewerId && $viewerId !== $artistId && $viewer && (int)$viewer['id_rol'] !== 1 && (int)$artist['id_rol'] !== 1;
        $flash = $this->consumeFlash();
        $flashMessage = $flash['message'];
        $comments = $this->comment->getByArtist($artistId);

        $isOwner = $viewerId && (int)$viewerId === (int)$artist['id_usuario'];
        $canInteract = $viewerId && (int)$viewerId !== (int)$artist['id_usuario'];
        $showArtistImageUpload = $isOwner;
        $showArtistImage = !empty($artist['pfp']);
        $hasBio = !empty($artist['bio']);
        $bioText = $artist['bio'] ?? '';
        $hasEmail = !empty($artist['email']);
        $canSeeEmail = $isOwner;
        $hasCountry = !empty($artist['nombre_pais']);
        $canComment = !empty($viewerId);

        $this->render('detail_artist.php', compact(
            'artist',
            'isFollowing',
            'songs',
            'albums',
            'followers',
            'following',
            'playlists',
            'canReport',
            'flashMessage',
            'comments',
            'isOwner',
            'canInteract',
            'showArtistImageUpload',
            'showArtistImage',
            'hasBio',
            'bioText',
            'hasEmail',
            'canSeeEmail',
            'hasCountry',
            'canComment'
        ));
    }

    public function like(){

        if(!$this->isPostRequest()){
            $this->abort('Método no permitido', 405);
        }

        $isAjax = $this->isAjaxRequest();

        $userId = $this->sessionInt('user_id');
        if($userId === null){
            if($isAjax){
                $this->jsonResponse(['success' => false, 'message' => 'Debes iniciar sesión'], 401);
            }

            $this->redirectToRoute('login');
        }

        $song_id = $this->postInt('song_id', 0);

        if($song_id <= 0){
            if($isAjax){
                $this->jsonResponse(['success' => false, 'message' => 'Canción inválida'], 422);
            }

            $this->redirectToPath($this->publicUrl());
        }

        $userLiked = false;
        if($this->like->isLiked($userId, $song_id)){
            $this->like->unlikeSong($userId, $song_id);
            $userLiked = false;
        } else {
            $this->like->likeSong($userId, $song_id);
            $userLiked = true;
        }

        $likeData = $this->like->countLikes($song_id);
        $likesTotal = (int)($likeData['total'] ?? 0);

        if($isAjax){
            $this->jsonResponse([
                'success' => true,
                'songId' => $song_id,
                'userLiked' => $userLiked,
                'likesTotal' => $likesTotal,
            ]);
        }

        $albumId = $this->postInt('album_id', 0);
        if($albumId > 0){
            $this->redirectToRoute('album?id=' . $albumId);
        } else {
            $this->redirectToRoute('song?id=' . $song_id);
        }
    }

    public function newReleases(){

        $songs = $this->song->getLatestDetailed(20);

        $viewerId = $this->sessionInt('user_id');

        if($viewerId){
            $songs = array_filter($songs, function($s) use ($viewerId){
                return !$this->block->isBlockedBy($viewerId, (int)$s['id_artista']);
            });
        }

        $albums = $this->album->getLatestDetailed(20);

        $this->render('new_releases.php', [
            'songs' => $songs,
            'albums' => $albums,
        ]);
    }

    public function addSongToAlbum(){

        if($this->isPostRequest()){
            $album_id = $this->postInt('album_id', 0);
            $artista = $this->requireAuthenticatedUser('login');
            $album = $this->album->getById($album_id);
            $existingSongId = $this->postInt('existing_song_id', 0);
            $nombre = $this->postString('nombre');

            if(!$album_id || !$album || (int)$album['id_artista'] !== (int)$artista){
                $this->abort('Datos incompletos', 422);
            }

            if($existingSongId > 0){
                $moved = $this->song->moveToAlbum($existingSongId, $album_id, $artista);
                if(!$moved){
                    $this->abort('No se pudo agregar la canción seleccionada al álbum', 422);
                }

                $this->redirectToRoute('album?id=' . $album_id);
            }

            if(!$nombre || empty($_FILES['archivo']['name'] ?? null)){
                $this->abort('Selecciona una canción existente o sube una nueva canción', 422);
            }

            $upload = $this->storeUploadedFile('archivo', ['mp3'], 'music');
            if($upload['error']){
                $this->abort($upload['error'], 422);
            }

            $data = [
                'nombre' => $nombre,
                'numero_pista' => null,
                'path' => $upload['path'] ?? '',
                'album' => $album_id,
                'artista' => $artista,
            ];

            $this->song->create($data);
            $this->redirectToRoute('album?id=' . $album_id);
        }

        $album_id = $this->getInt('id', 0);
        if(!$album_id){
            $this->abort('Álbum no especificado', 422);
        }

        $album = $this->album->getById($album_id);
        if(!$album){
            $this->abort('Álbum no encontrado', 404);
        }

        $viewerId = $this->requireAuthenticatedUser('login');
        if((int)$album['id_artista'] !== (int)$viewerId){
            $this->abort('Sin permisos para editar este álbum', 403);
        }

        $availableSongs = $this->song->getByArtistOutsideAlbum($viewerId, $album_id);
        $artistProfileUrl = $this->routeUrl('artist?id=' . (int)$viewerId);
        $this->render('add_songs_to_album.php', compact('album_id', 'album', 'artistProfileUrl', 'availableSongs'));
    }
}