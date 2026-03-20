<?php

require_once __DIR__ . "/Controller.php";
require_once __DIR__ . "/../models/Song.php";
require_once __DIR__ . "/../models/Album.php";
require_once __DIR__ . "/../models/Comment.php";
require_once __DIR__ . "/../models/Tag.php";

class ArtistController extends Controller {

    private $song;
    private $album;
    private $comment;
    private $tag;

    public function __construct(){
        $db = $this->connectDatabase();
        $this->song = new Song($db);
        $this->album = new Album($db);
        $this->comment = new Comment($db);
        $this->tag = new Tag($db);
    }

    private function requireArtistUserId($expectedArtistId = null){
        $artistId = $this->requireAuthenticatedUser('login');
        if($this->sessionInt('role') !== 2){
            $this->abort('Solo los artistas pueden realizar esta acción', 403);
        }

        if($expectedArtistId !== null && (int)$expectedArtistId !== $artistId){
            $this->abort('Sin permisos para este artista', 403);
        }

        return $artistId;
    }

    private function loadOwnedAlbum($albumId, $artistId){
        $album = $this->album->getById($albumId);
        if(!$album || (int)$album['id_artista'] !== (int)$artistId){
            $this->abort('Álbum no encontrado o sin permisos', 403);
        }

        return $album;
    }

    private function loadOwnedSong($songId, $artistId){
        $song = $this->song->getById($songId);
        if(!$song || (int)$song['id_artista'] !== (int)$artistId){
            $this->abort('Canción no encontrada o sin permisos', 403);
        }

        return $song;
    }

    public function showCreateAlbum($artist_id){

        $artistId = $this->requireArtistUserId((int)$artist_id);

        $this->render('create_album.php', compact('artistId'));
    }

    public function createAlbum(){

        $nombre = $this->postString('nombre');
        $descripcion = $this->postString('descripcion');
        $artista = $this->requireArtistUserId();

        if(!$this->esEntradaSegura($nombre) || !$this->esEntradaSegura($descripcion)){
            $this->abort('Los datos contienen caracteres no permitidos.', 422);
        }

        $upload = $this->storeUploadedFile('portada', ['jpg', 'jpeg', 'png', 'webp'], 'Photos', '');
        if($upload['error']){
            $this->abort($upload['error'], 422);
        }

        $data = [
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'portada' => $upload['path'] ?? '',
            'artista' => $artista,
        ];

        $album_id = $this->album->create($data);

        $this->redirectToRoute('album/add-songs?id=' . (int)$album_id);
    }

    public function showCreateSong($artist_id){

        $artistId = $this->requireArtistUserId((int)$artist_id);
        $tags = $this->tag->getAllTags();

        $this->render('create_song.php', compact('artistId', 'tags'));
    }

    public function showEditAlbum($album_id){

        $artistId = $this->requireArtistUserId();
        $album = $this->loadOwnedAlbum((int)$album_id, $artistId);

        $availableSongs = $this->song->getByArtistOutsideAlbum($artistId, $album_id);

        $this->render('edit_album.php', compact('album', 'artistId', 'availableSongs'));
    }

    public function editAlbum(){

        $albumId = $this->postInt('album_id', 0);
        $artistId = $this->requireArtistUserId();
        $album = $this->loadOwnedAlbum($albumId, $artistId);
        $upload = $this->storeUploadedFile('portada', ['jpg', 'jpeg', 'png', 'webp'], 'Photos', $album['portada_album']);
        if($upload['error']){
            $this->abort($upload['error'], 422);
        }

        $nombre = $this->postString('nombre');
        $descripcion = $this->postString('descripcion');

        if(!$this->esEntradaSegura($nombre) || !$this->esEntradaSegura($descripcion)){
            $this->abort('Los datos del álbum contienen caracteres no permitidos.', 422);
        }

        $data = [
            'album' => $albumId,
            'artista' => $artistId,
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'portada' => $upload['path'],
        ];

        $this->album->update($data);

        $existingSongId = $this->postInt('existing_song_id', 0);
        if($existingSongId > 0){
            $this->song->moveToAlbum($existingSongId, $albumId, $artistId);
        }

        $this->redirectToRoute('album?id=' . $albumId);
    }

    public function createSong(){

        $nombre = $this->postString('nombre');
        $artista = $this->requireArtistUserId();
        $letra = $this->postString('letra_cancion');
        $fonetica = $this->postString('texto_fonetico');

        if(!$this->esEntradaSegura($nombre)){
            $this->abort('El nombre de la canción contiene caracteres no permitidos.', 422);
        }

        $audioUpload = $this->storeUploadedFile('archivo', ['mp3'], 'music', '');
        $coverUpload = $this->storeUploadedFile('portada', ['jpg', 'jpeg', 'png', 'webp'], 'Photos', null);

        if($audioUpload['error']){
            $this->abort($audioUpload['error'], 422);
        }

        if($coverUpload['error']){
            $this->abort($coverUpload['error'], 422);
        }

        $data = [
            'nombre' => $nombre,
            'numero_pista' => null,
            'album' => null,
            'path' => $audioUpload['path'] ?? '',
            'portada' => $coverUpload['path'] ?? null,
            'artista' => $artista,
        ];

        $songId = $this->song->create($data);

        if($songId){
            $songId = (int)$songId;
            $tagIds = $this->normalizeIdArray($this->postArray('tags'));
            $this->tag->syncSongTags($songId, $tagIds);
            $this->song->saveLyrics($songId, $letra, $fonetica);
        }

        $this->redirectToRoute('artist?id=' . $artista);
    }

    public function showEditSong($song_id){

        $artistId = $this->requireArtistUserId();
        $songId = (int)$song_id;
        $song = $this->loadOwnedSong($songId, $artistId);

        $albums = $this->album->getByArtist($artistId);
        $tags = $this->tag->getAllTags();
        $selectedTagIds = $this->tag->getSongTagIds($songId);
        $lyrics = $this->song->getLyrics($songId);

        $this->render('edit_song.php', compact('song', 'albums', 'tags', 'selectedTagIds', 'lyrics'));
    }

    public function editSong(){

        $songId = $this->postInt('song_id', 0);
        $artistId = $this->requireArtistUserId();
        $song = $this->loadOwnedSong($songId, $artistId);
        $albumId = $this->postString('album_id', '') !== '' ? $this->postInt('album_id', 0) : null;

        if($albumId){
            $targetAlbum = $this->album->getById($albumId);

            if(!$targetAlbum || (int)$targetAlbum['id_artista'] !== (int)$artistId){
                $this->abort('Álbum inválido para esta canción', 422);
            }
        }

        $coverUpload = $this->storeUploadedFile('portada', ['jpg', 'jpeg', 'png', 'webp'], 'Photos', $song['portada_cancion'] ?? null);
        if($coverUpload['error']){
            $this->abort($coverUpload['error'], 422);
        }

        $nombre = $this->postString('nombre');
        $letra = $this->postString('letra_cancion');
        $fonetica = $this->postString('texto_fonetico');

        if(!$this->esEntradaSegura($nombre) || !$this->esEntradaSegura($letra) || !$this->esEntradaSegura($fonetica)){
            $this->abort('Los datos de la canción contienen caracteres no permitidos.', 422);
        }

        $data = [
            'song' => $songId,
            'artista' => $artistId,
            'nombre' => $nombre,
            'album' => $albumId,
            'portada' => $coverUpload['path'],
        ];

        $this->song->update($data);

        $tagIds = $this->normalizeIdArray($this->postArray('tags'));
        $this->tag->syncSongTags($songId, $tagIds);

        $this->song->saveLyrics($songId, $letra, $fonetica);

        $this->redirectToRoute('song?id=' . $songId);
    }

    public function addComment(){

        $artista = $this->postInt('artist_id', 0);
        $comentario = $this->postString('comment');
        $usuario = $this->requireAuthenticatedUser('login');

        if(!$artista || $comentario === ''){
            $this->abort('Datos inválidos', 422);
        }

        if(!$this->esEntradaSegura($comentario)){
            $this->abort('El comentario contiene caracteres no permitidos.', 422);
        }

        $data = [
            'usuario' => $usuario,
            'artista' => $artista,
            'comentario' => $comentario,
        ];

        $this->comment->create($data);

        $this->redirectToRoute('artist?id=' . $artista);
    }

}