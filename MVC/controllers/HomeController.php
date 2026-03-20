<?php

require_once __DIR__ . "/Controller.php";
require_once __DIR__ . "/../models/Song.php";
require_once __DIR__ . "/../models/Album.php";
require_once __DIR__ . "/../models/Artist.php";
require_once __DIR__ . "/../models/Tag.php";
require_once __DIR__ . "/../models/Follow.php";
require_once __DIR__ . "/../models/Block.php";

class HomeController extends Controller {

    private $song;
    private $album;
    private $artist;
    private $tag;
    private $follow;
    private $block;

    public function __construct(){
        $db = $this->connectDatabase();
        $this->song = new Song($db);
        $this->album = new Album($db);
        $this->artist = new Artist($db);
        $this->tag = new Tag($db);
        $this->follow = new Follow($db);
        $this->block = new Block($db);
    }

    public function home(){

        $songs = $this->song->getLatestSongs();
        $albums = $this->album->getLatestAlbums();
        $artists = $this->artist->getLatestArtists();
        $tags = $this->tag->getRandomTags();

        $viewerId = $this->sessionInt('user_id');
        $isLoggedIn = $viewerId !== null;

        if($isLoggedIn){
            $songs = array_values(array_filter($songs, function($song) use ($viewerId){
                return !$this->block->isBlockedBy($viewerId, (int)$song['id_artista']);
            }));

            $albums = array_values(array_filter($albums, function($album) use ($viewerId){
                return !$this->block->isBlockedBy($viewerId, (int)$album['id_artista']);
            }));

            $artists = array_values(array_filter($artists, function($artist) use ($viewerId){
                return !$this->block->isBlockedBy($viewerId, (int)$artist['id_usuario']);
            }));
        }

        $currentUsername = $this->sessionString('username');
        $currentRole = $this->sessionInt('role');
        $currentRoleLabel = $currentRole === 2 ? 'Artista' : ($currentRole === 3 ? 'Listener' : null);
        $profileUrl = $viewerId ? $this->routeUrl('profile?id=' . $viewerId) : null;
        $tagDescription = $isLoggedIn
            ? 'Explora tags para descubrir canciones con una vibe parecida.'
            : 'Explora algunos tags populares para descubrir la vibe de la plataforma.';

        foreach($songs as &$song){
            $song['cover_path'] = !empty($song['portada_cancion']) ? $song['portada_cancion'] : 'Photos/banner_default.png';
            $song['artist_name'] = $song['nombre_artistico'] ?? 'Artista';
            $song['detail_url'] = $isLoggedIn ? $this->routeUrl('song?id=' . (int)$song['id_cancion']) : null;
        }
        unset($song);

        foreach($albums as &$album){
            $album['cover_path'] = !empty($album['portada_album']) ? $album['portada_album'] : 'Photos/banner_default.png';
            $album['display_name'] = $album['nombre_album'] ?? 'Álbum';
            $album['detail_url'] = $isLoggedIn ? $this->routeUrl('album?id=' . (int)$album['id_album']) : null;
        }
        unset($album);

        foreach($artists as &$artist){
            $artist['display_name'] = $artist['nombre_artistico'] ?? 'Artista';
            $artist['profile_url'] = null;
            if($isLoggedIn){
                $artist['profile_url'] = ((int)$viewerId === (int)$artist['id_usuario'])
                    ? $this->routeUrl('profile?id=' . (int)$artist['id_usuario'])
                    : $this->routeUrl('artist?id=' . (int)$artist['id_usuario']);
            }
        }
        unset($artist);

        $this->render('home.php', [
            'songs' => $songs,
            'albums' => $albums,
            'artists' => $artists,
            'tags' => $tags,
            'isLoggedIn' => $isLoggedIn,
            'currentUsername' => $currentUsername,
            'currentRoleLabel' => $currentRoleLabel,
            'profileUrl' => $profileUrl,
            'tagDescription' => $tagDescription,
        ]);
    }
}
