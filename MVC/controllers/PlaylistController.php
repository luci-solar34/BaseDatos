<?php

require_once __DIR__ . "/Controller.php";
require_once __DIR__ . "/../models/Playlist.php";
require_once __DIR__ . "/../models/Song.php";

class PlaylistController extends Controller {

    private $playlist;
    private $song;

    public function __construct(){
        $db = $this->connectDatabase();
        $this->playlist = new Playlist($db);
        $this->song = new Song($db);
    }

    private function loadOwnedPlaylist($playlistId, $userId){
        $playlist = $this->playlist->getPlaylistByOwner($playlistId, $userId);
        if(!$playlist){
            $this->abort('Playlist no encontrada o sin permisos', 403);
        }

        return $playlist;
    }

    public function showCreateForm(){
        $this->requireAuthenticatedUser('login');
        $this->render('playlist_create.php');
    }

    public function create(){

        $name = $this->postString('name');
        $privacy = $this->postInt('privacy', 0);
        $user = $this->requireAuthenticatedUser('login');

        if(!$name || !$user){
            $this->abort('Datos inválidos', 422);
        }

        $playlist_id = $this->playlist->create($name, $privacy, $user);

        $this->redirectToRoute('playlist?id=' . (int)$playlist_id);
    }

    public function show($id){
        $userId = $this->requireAuthenticatedUser('login');
        $playlistId = (int)$id;
        if($playlistId <= 0){
            $this->abort('Playlist inválida', 422);
        }

        $songs = $this->playlist->getPlaylistSongs($playlistId);
        $playlist_id = $playlistId;
        $finalizeUrl = $this->routeUrl('profile?id=' . (int)$userId);

        $this->render('playlist.php', compact('songs', 'playlist_id', 'finalizeUrl'));
    }

    public function showAddSongForm(){

        $userId = $this->requireAuthenticatedUser('login');
        $playlistId = $this->getInt('playlist', 0);

        if(!$playlistId){
            $this->abort('Playlist no especificada', 422);
        }

        $this->loadOwnedPlaylist($playlistId, $userId);

        $songs = $this->song->getAll();
        $playlist = $playlistId;

        $this->render('add_song.php', compact('songs', 'playlist'));
    }

    public function edit($id){
        $userId = $this->requireAuthenticatedUser('login');
        $playlistId = (int)$id;
        $playlist = $this->loadOwnedPlaylist($playlistId, $userId);
        $songs = $this->playlist->getPlaylistSongs($playlistId);
        $playlist_id = $playlistId;

        $this->render('edit_playlist.php', compact('playlist', 'songs', 'playlist_id'));
    }

    public function addSong(){

        $userId = $this->requireAuthenticatedUser('login');
        $playlist = $this->postInt('playlist', 0);
        $song = $this->postInt('song', 0);

        if(!$playlist || !$song){
            $this->abort('Datos inválidos', 422);
        }

        $this->loadOwnedPlaylist($playlist, $userId);

        if(!$this->playlist->hasSong($playlist, $song)){
            $this->playlist->addSong($playlist, $song);
        }

        $this->redirectToRoute('playlist?id=' . $playlist);
    }

    public function removeSong(){

        $userId = $this->requireAuthenticatedUser('login');
        $playlist = $this->postInt('playlist_id', 0);
        $song = $this->postInt('song_id', 0);

        if(!$playlist || !$song){
            $this->abort('Datos inválidos', 422);
        }

        $this->loadOwnedPlaylist($playlist, $userId);

        $this->playlist->removeSong($playlist, $song);

        $this->redirectToRoute('profile?id=' . $userId);
    }

    public function changePrivacy(){

        $userId = $this->requireAuthenticatedUser('login');
        $playlist = $this->postInt('playlist_id', 0);
        $privacy = $this->postInt('privacy', 0);

        if(!$playlist){
            $this->abort('Datos inválidos', 422);
        }

        $this->loadOwnedPlaylist($playlist, $userId);

        $this->playlist->changePrivacy($playlist, $privacy === 1 ? 1 : 0);

        $this->redirectToRoute('profile?id=' . $userId);
    }
}