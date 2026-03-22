<?php

require_once __DIR__ . "/../../config/database.php";

abstract class Controller {

    protected function connectDatabase(){
        $database = new Database();
        return $database->connect();
    }

    protected function render($view, array $data = []){
        extract($data, EXTR_SKIP);
        $viewPath = __DIR__ . "/../views/" . $view;

        if(!$this->shouldUseAppShell($view)){
            require $viewPath;
            return;
        }

        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        $layoutStylesheets = $this->extractStylesheetLinks($content);
        $pageTitle = isset($pageTitle) && is_string($pageTitle) && trim($pageTitle) !== ''
            ? $pageTitle
            : 'LASK - Musica';

        extract($this->buildAppShellData($pageTitle, $layoutStylesheets), EXTR_SKIP);
        require __DIR__ . "/../views/layout.php";
    }

    protected function routeUrl($route = ''){
        $baseUrl = BASE_URL;
        if($route === ''){
            return $baseUrl;
        }

        return $baseUrl . '/' . ltrim($route, '/');
    }

    protected function publicUrl($path = ''){
        $publicBase = rtrim(dirname(BASE_URL), '/\\');
        if($path === ''){
            return $publicBase;
        }

        return $publicBase . '/' . ltrim($path, '/');
    }

    protected function appUrl($path = ''){
        $appBase = rtrim(dirname(dirname(BASE_URL)), '/\\');
        if($path === ''){
            return $appBase;
        }

        $normalizedPath = str_replace('\\', '/', (string)$path);

        if(strpos($normalizedPath, 'http://') === 0 || strpos($normalizedPath, 'https://') === 0){
            return $normalizedPath;
        }

        if(strpos($normalizedPath, $appBase . '/') === 0){
            return $normalizedPath;
        }

        return $appBase . '/' . ltrim($normalizedPath, '/');
    }

    protected function redirectToRoute($route = ''){
        header('Location: ' . $this->routeUrl($route));
        exit;
    }

    protected function redirectToPath($path){
        header('Location: ' . $path);
        exit;
    }

    protected function setFlash($message, $type = 'error'){
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_message_type'] = $type;
    }

    protected function consumeFlash(){
        $flash = [
            'message' => $_SESSION['flash_message'] ?? null,
            'type' => $_SESSION['flash_message_type'] ?? 'error',
        ];

        unset($_SESSION['flash_message'], $_SESSION['flash_message_type']);

        return $flash;
    }

    protected function abort($message, $statusCode = 400){
        http_response_code($statusCode);
        exit($message);
    }

    protected function jsonResponse(array $payload, $statusCode = 200){
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload);
        exit;
    }

    protected function isPostRequest(){
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
    }

    protected function postString($key, $default = ''){
        $value = filter_input(INPUT_POST, $key, FILTER_UNSAFE_RAW);
        if($value === null){
            $value = $_POST[$key] ?? $default;
        }

        return is_string($value) ? trim($value) : $default;
    }

    protected function getString($key, $default = ''){
        $value = filter_input(INPUT_GET, $key, FILTER_UNSAFE_RAW);
        if($value === null){
            $value = $_GET[$key] ?? $default;
        }

        return is_string($value) ? trim($value) : $default;
    }

    protected function postInt($key, $default = 0){
        $value = filter_input(INPUT_POST, $key, FILTER_VALIDATE_INT);
        if($value === false || $value === null){
            $fallback = $_POST[$key] ?? null;
            if(filter_var($fallback, FILTER_VALIDATE_INT) === false){
                return $default;
            }
            return (int)$fallback;
        }

        return (int)$value;
    }

    protected function getInt($key, $default = 0){
        $value = filter_input(INPUT_GET, $key, FILTER_VALIDATE_INT);
        if($value === false || $value === null){
            $fallback = $_GET[$key] ?? null;
            if(filter_var($fallback, FILTER_VALIDATE_INT) === false){
                return $default;
            }
            return (int)$fallback;
        }

        return (int)$value;
    }

    protected function postArray($key){
        $value = $_POST[$key] ?? [];
        return is_array($value) ? $value : [];
    }

    protected function postBool($key){
        return isset($_POST[$key]);
    }

    protected function sessionInt($key){
        if(!isset($_SESSION[$key]) || filter_var($_SESSION[$key], FILTER_VALIDATE_INT) === false){
            return null;
        }

        return (int)$_SESSION[$key];
    }

    protected function sessionString($key, $default = null){
        if(!isset($_SESSION[$key])){
            return $default;
        }

        return is_string($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }

    protected function requireAuthenticatedUser($redirectRoute = 'login'){
        $userId = $this->sessionInt('user_id');
        if($userId === null){
            if($redirectRoute !== null){
                $this->setFlash('Debes iniciar sesión o crear una cuenta para continuar.');
                $this->redirectToRoute($redirectRoute);
            }

            $this->abort('No autenticado', 401);
        }

        return $userId;
    }

    protected function requireAdmin($deniedMessage = 'Acceso denegado', $redirectRoute = ''){
        $userId = $this->sessionInt('user_id');
        $role = $this->sessionInt('role');

        if($userId === null || $role !== 1){
            $this->setFlash($deniedMessage);
            $this->redirectToRoute($redirectRoute);
        }

        return $userId;
    }

    protected function storeUploadedFile($field, array $allowedExtensions, $directory, $fallbackPath = null){
        if(empty($_FILES[$field]) || !isset($_FILES[$field]['error'])){
            return ['path' => $fallbackPath, 'error' => null, 'uploaded' => false];
        }

        $file = $_FILES[$field];
        if((int)$file['error'] === UPLOAD_ERR_NO_FILE){
            return ['path' => $fallbackPath, 'error' => null, 'uploaded' => false];
        }

        if((int)$file['error'] !== UPLOAD_ERR_OK){
            return ['path' => $fallbackPath, 'error' => 'No se pudo procesar el archivo subido.', 'uploaded' => false];
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if(!in_array($extension, $allowedExtensions, true)){
            return ['path' => $fallbackPath, 'error' => 'Formato de archivo no permitido.', 'uploaded' => false];
        }

        $relativeDirectory = trim($directory, '/\\');
        $absoluteDirectory = dirname(__DIR__, 2) . '/' . $relativeDirectory;

        if(!is_dir($absoluteDirectory) && !mkdir($absoluteDirectory, 0755, true) && !is_dir($absoluteDirectory)){
            return ['path' => $fallbackPath, 'error' => 'No se pudo preparar el directorio de subida.', 'uploaded' => false];
        }

        try {
            $filename = bin2hex(random_bytes(16));
        } catch (Exception $e) {
            $filename = uniqid('upload_', true);
        }

        $relativePath = $relativeDirectory . '/' . $filename . '.' . $extension;
        $absolutePath = dirname(__DIR__, 2) . '/' . $relativePath;

        if(!move_uploaded_file($file['tmp_name'], $absolutePath)){
            return ['path' => $fallbackPath, 'error' => 'No se pudo guardar el archivo subido.', 'uploaded' => false];
        }

        return ['path' => str_replace('\\', '/', $relativePath), 'error' => null, 'uploaded' => true];
    }

    private function shouldUseAppShell($view){
        if($this->sessionInt('user_id') === null){
            return false;
        }

        $viewName = basename((string)$view);

        return !in_array($viewName, ['login.php', 'register.php', 'layout.php'], true);
    }

    private function extractStylesheetLinks(&$content){
        $stylesheets = [];
        $pattern = '/<link\\b[^>]*rel=["\']stylesheet["\'][^>]*>/i';

        $content = preg_replace_callback($pattern, function($matches) use (&$stylesheets){
            $stylesheets[] = trim($matches[0]);
            return '';
        }, $content);

        return array_values(array_unique($stylesheets));
    }

    private function buildAppShellData($pageTitle, array $layoutStylesheets){
        require_once __DIR__ . '/../models/User.php';

        $userId = $this->sessionInt('user_id');
        $userModel = new User($this->connectDatabase());
        $currentUser = $userId ? $userModel->getById($userId) : null;
        $pfpPath = !empty($currentUser['pfp']) ? $currentUser['pfp'] : 'photos_pfp/pfp_default.png';

        return [
            'pageTitle' => $pageTitle,
            'layoutStylesheets' => $layoutStylesheets,
            'appShellStylesheet' => $this->publicUrl('css/app_shell.css'),
            'viewActionsScriptUrl' => $this->publicUrl('js/view-actions.js'),
            'navHomeUrl' => $this->routeUrl(),
            'navMessagesUrl' => $this->routeUrl('messages'),
            'navProfileUrl' => $this->routeUrl('profile?id=' . (int)$userId),
            'navProfileImageUrl' => $this->appUrl($pfpPath),
        ];
    }

    protected function normalizeIdArray(array $values){
        $ids = [];
        foreach($values as $value){
            if(filter_var($value, FILTER_VALIDATE_INT) !== false){
                $ids[] = (int)$value;
            }
        }

        return $ids;
    }

    /**
     * Rechaza valores que contengan SQL keywords o patrones de inyección.
     * Úsalo en controllers para validar cualquier input de texto libre.
     */
    protected function esEntradaSegura($value){
        if(!is_string($value) || trim($value) === ''){
            return true;
        }

        // SQL keywords peligrosos
        $sqlKeywords = '/\b(SELECT|UNION|INSERT|DELETE|UPDATE|DROP|ALTER|CREATE|TRUNCATE|EXEC|EXECUTE|CAST|CONVERT|DECLARE|CURSOR|SHUTDOWN|GRANT|REVOKE|MERGE|LOAD|OUTFILE|DUMPFILE)\b/i';
        if(preg_match($sqlKeywords, $value)){
            return false;
        }

        // Comentarios SQL: --, #, /* ... */
        if(preg_match('/--|#|\/\*|\*\//', $value)){
            return false;
        }

        return true;
    }
}