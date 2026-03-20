<?php

require_once __DIR__ . "/Controller.php";
require_once __DIR__ . "/../models/User.php";
require_once __DIR__ . "/../models/Follow.php";
require_once __DIR__ . "/../models/Playlist.php";
require_once __DIR__ . "/../models/Block.php";

class UserController extends Controller {

    private $user;
    private $follow;
    private $playlist;
    private $block;

    public function __construct(){
        $db = $this->connectDatabase();
        $this->user = new User($db);
        $this->follow = new Follow($db);
        $this->playlist = new Playlist($db);
        $this->block = new Block($db);
    }

    private function redirectToProfile($userId){
        $this->redirectToRoute('profile?id=' . (int)$userId);
    }

    private function hasDangerousSqlPayload($value){
        if(!is_string($value) || trim($value) === ''){
            return false;
        }

        $pattern = '/(--|\/\*|\*\/|;\s*(SELECT|UNION|INSERT|DELETE|UPDATE|DROP|ALTER|TRUNCATE|EXEC|EXECUTE)\b|\bUNION\b\s+\bALL\b\s+\bSELECT\b|\bINTO\b\s+\bOUTFILE\b|\bLOAD_FILE\s*\(|\b(OR|AND)\b\s+\d+\s*=\s*\d+)/i';

        return preg_match($pattern, $value) === 1;
    }

    private function formatPlaylists(array $playlists){
        foreach($playlists as &$playlist){
            $playlist['privacy_label'] = ((int)$playlist['privacidad_playlist'] === 0) ? '(Privada)' : '';
        }
        unset($playlist);

        return $playlists;
    }

    public function profile($id){
        $profileUserId = (int)$id;
        $user = $this->user->getById($profileUserId);
        if(!$user){
            $this->abort('Usuario no encontrado', 404);
        }

        $viewerId = $this->sessionInt('user_id');
        $viewerData = $viewerId ? $this->user->getById($viewerId) : null;
        $isBlocked = $viewerId ? $this->block->isBlocked($viewerId, $profileUserId) : false;
        $hasBlocked = $viewerId ? $this->block->hasBlocked($viewerId, $profileUserId) : false;
        $canReport = $viewerId
            && $viewerId !== $profileUserId
            && $viewerData
            && (int)$viewerData['id_rol'] !== 1
            && (int)$user['id_rol'] !== 1;
        $hasReported = $canReport ? $this->user->hasDenuncia($viewerId, $profileUserId) : false;
        $isOwner = $viewerId !== null && $viewerId === (int)$user['id_usuario'];
        $canInteract = $viewerId !== null && $viewerId !== (int)$user['id_usuario'];
        $showAdminActions = $this->sessionInt('role') === 1 && $isOwner;
        $reportUnavailableMessage = $canInteract && !$canReport
            ? 'No puedes denunciar a administradores ni usar esta función mientras estás en modo administrador.'
            : null;
        $bioText = !empty($user['bio']) ? $user['bio'] : 'Sin bio';

        if($isBlocked){
            $flash = $this->consumeFlash();
            $this->render('profile_blocked.php', [
                'user' => $user,
                'flashMessage' => $flash['message'],
                'canReport' => $canReport,
                'showReportedMessage' => $canReport && $hasReported,
                'canUnblock' => $hasBlocked,
            ]);
            return;
        }

        if((int)$user['id_rol'] === 2){
            $this->redirectToRoute('artist?id=' . $profileUserId);
        }

        $followers = $this->follow->countFollowers($profileUserId);
        $following = $this->follow->countFollowing($profileUserId);
        $isFollowing = $viewerId && $viewerId !== $profileUserId
            ? $this->follow->isFollowing($viewerId, $profileUserId)
            : false;
        $playlists = $this->formatPlaylists($this->playlist->getUserPlaylists($profileUserId, $viewerId));
        $flash = $this->consumeFlash();

        $this->render('profile.php', [
            'user' => $user,
            'followers' => $followers,
            'following' => $following,
            'isFollowing' => $isFollowing,
            'playlists' => $playlists,
            'flashMessage' => $flash['message'],
            'canInteract' => $canInteract,
            'hasBlocked' => $hasBlocked,
            'canReport' => $canReport,
            'hasReported' => $hasReported,
            'reportUnavailableMessage' => $reportUnavailableMessage,
            'bioText' => $bioText,
            'isOwner' => $isOwner,
            'showAdminActions' => $showAdminActions,
        ]);
    }

    public function submitReport(){
        $denunciante = $this->requireAuthenticatedUser('login');
        $denunciado = $this->postInt('denunciado_id', 0);
        $motivo = $this->postString('motivo_denuncia');
        $descripcion = $this->postString('descripcion_denuncia');

        if(!$denunciante || !$denunciado || !$motivo || !$descripcion){
            $this->setFlash('Por favor completa todos los campos de la denuncia.');
            $this->redirectToProfile($denunciado);
        }

        if($this->hasDangerousSqlPayload($motivo) || $this->hasDangerousSqlPayload($descripcion)){
            $this->setFlash('La denuncia contiene caracteres no permitidos.');
            $this->redirectToProfile($denunciado);
        }

        if($denunciante === $denunciado){
            $this->setFlash('No puedes denunciarte a ti mismo.');
            $this->redirectToProfile($denunciado);
        }

        $denuncianteData = $this->user->getById($denunciante);
        $denunciadoData = $this->user->getById($denunciado);

        if(!$denuncianteData || !$denunciadoData){
            $this->abort('Usuario no encontrado', 404);
        }

        if((int)$denuncianteData['id_rol'] === 1 || (int)$denunciadoData['id_rol'] === 1){
            $this->setFlash('No se puede realizar denuncias contra/desde administradores.');
            $this->redirectToProfile($denunciado);
        }

        if($this->user->hasDenuncia($denunciante, $denunciado)){
            $this->setFlash('Ya has denunciado a este usuario. Espera la resolución.');
            $this->redirectToProfile($denunciado);
        }

        $result = $this->user->createDenuncia($denunciante, $denunciado, $motivo, $descripcion);

        if($result){
            $this->setFlash('Gracias por denunciar esta cuenta, tu solicitud está en progreso.', 'success');
        } else {
            $this->setFlash('Ocurrió un error al enviar la denuncia. Intenta de nuevo.');
        }

        $this->redirectToProfile($denunciado);
    }

    public function followers($id){
        $user = $this->user->getById($id);
        if(!$user){
            $this->abort('Usuario no encontrado', 404);
        }
        $followers = $this->follow->getFollowers($id);
        $followersCount = count($followers);
        $followersLabel = $followersCount === 1 ? 'seguidor' : 'seguidores';
        $profileUrl = $this->routeUrl('profile?id=' . (int)$user['id_usuario']);
        $this->render('followers.php', compact('user', 'followers', 'followersCount', 'followersLabel', 'profileUrl'));
    }

    public function following($id){
        $user = $this->user->getById($id);
        if(!$user){
            $this->abort('Usuario no encontrado', 404);
        }
        $following = $this->follow->getFollowing($id);
        $followingCount = count($following);
        $followingLabel = $followingCount === 1 ? 'seguido' : 'seguidos';
        $profileUrl = $this->routeUrl('profile?id=' . (int)$user['id_usuario']);
        $this->render('following.php', compact('user', 'following', 'followingCount', 'followingLabel', 'profileUrl'));
    }

    public function updateBio(){
        $id = $this->requireAuthenticatedUser('login');
        $bio = $this->postString('bio');

        if($this->hasDangerousSqlPayload($bio)){
            $this->setFlash('La biografía contiene caracteres no permitidos.');
            $this->redirectToProfile($id);
        }

        $this->user->updateBio($id, $bio);
        $this->redirectToProfile($id);
    }


    public function updatePfp(){
        $userId = $this->requireAuthenticatedUser('login');
        $upload = $this->storeUploadedFile('pfp', ['jpg', 'jpeg', 'png', 'webp'], 'photos_pfp');

        if($upload['error']){
            $this->setFlash($upload['error']);
            $this->redirectToProfile($userId);
        }

        if(empty($upload['uploaded'])){
            $this->setFlash('No se subió ninguna imagen');
            $this->redirectToProfile($userId);
        }

        if($this->user->updatePfp($userId, $upload['path'])){
            $this->setFlash('Foto de perfil actualizada correctamente.', 'success');
        } else {
            $this->setFlash('No se pudo actualizar la foto de perfil. Intenta de nuevo.');
        }

        $this->redirectToProfile($userId);
    }

    public function listUsers(){
        $adminId = $this->requireAdmin('Acceso denegado: solo administradores pueden ver todos los usuarios.');

        $users = $this->user->getAllUsers();
        foreach($users as &$userRow){
            $userRow['status_label'] = $userRow['estado_usuario'] ? 'Activo' : 'Inactivo';
            $userRow['next_state'] = $userRow['estado_usuario'] ? '0' : '1';
            $userRow['action_label'] = $userRow['estado_usuario'] ? 'Desactivar' : 'Activar';
        }
        unset($userRow);
        $flash = $this->consumeFlash();
        $profileUrl = $this->routeUrl('profile?id=' . (int)$adminId);
        $this->render('users_list.php', [
            'users' => $users,
            'flashMessage' => $flash['message'],
            'profileUrl' => $profileUrl,
        ]);
    }

    public function changeUserState(){
        $adminId = $this->requireAdmin('Acceso denegado: solo administradores pueden cambiar estado de usuarios.');

        $userId = $this->postInt('user_id', 0);
        $estado = $this->postString('estado', '');
        $estadoValue = ($estado == '1') ? 1 : (($estado == '0') ? 0 : null);

        if(!$userId || !in_array($estadoValue, [0, 1], true)){
            $this->setFlash('Datos inválidos para cambiar el estado.');
            $this->redirectToRoute('admin/users');
        }

        if($userId === $adminId){
            $this->setFlash('No se puede cambiar el estado de tu propia cuenta desde aquí.');
            $this->redirectToRoute('admin/users');
        }

        $updated = $this->user->changeState($userId, $estadoValue);

        if($updated){
            $this->setFlash($estadoValue ? 'Usuario activado correctamente.' : 'Usuario desactivado correctamente.', 'success');
        } else {
            $this->setFlash('No se pudo actualizar el estado del usuario.');
        }

        $this->redirectToRoute('admin/users');
    }

    public function follow(){
        $seguidor = $this->requireAuthenticatedUser('login');
        $seguido = $this->postInt('user_id', 0);

        if(!$seguido){
            $this->abort('Datos inválidos', 422);
        }

        if($this->block->isBlocked($seguidor, $seguido)){
            $this->abort('No puedes seguir a este usuario', 403);
        }

        if(!$this->follow->isFollowing($seguidor, $seguido)){
            $this->follow->follow($seguidor, $seguido);
        }

        $this->redirectToProfile($seguido);
    }

    public function unfollow(){
        $seguidor = $this->requireAuthenticatedUser('login');
        $seguido = $this->postInt('user_id', 0);

        if(!$seguido){
            $this->abort('Datos inválidos', 422);
        }

        $this->follow->unfollow($seguidor, $seguido);
        $this->redirectToProfile($seguido);
    }

    public function block(){
        $bloqueador = $this->requireAuthenticatedUser('login');
        $bloqueado = $this->postInt('user_id', 0);

        if(!$bloqueado){
            $this->abort('Datos inválidos', 422);
        }

        $this->block->block($bloqueador, $bloqueado);
        $this->redirectToProfile($bloqueado);
    }

    public function unblock(){
        $bloqueador = $this->requireAuthenticatedUser('login');
        $bloqueado = $this->postInt('user_id', 0);

        if(!$bloqueado){
            $this->abort('Datos inválidos', 422);
        }

        $this->block->unblock($bloqueador, $bloqueado);
        $this->redirectToProfile($bloqueado);
    }
}