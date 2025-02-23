<?php
session_start();

require 'includes/auth.php';
require 'includes/db.php';

redirectIfNotLoggedIn();

$user_email = $_SESSION['user_email'];

$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$user_email]);
$user = $stmt->fetch();

if (!$user) {
    http_response_code(403);
    exit('Acceso no autorizado.');
}

$user_id = $user['id'];
$rootDirectory = '../../notas';
$userDirectory = $rootDirectory . '/' . $user_id;

if (isset($_GET['file'])) {
    $file = basename($_GET['file']);
    $filePath = $userDirectory . '/' . $file;

    if (file_exists($filePath)) {

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $filePath);
        finfo_close($finfo);

        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: inline; filename="' . $file . '"');
        header('Content-Length: ' . filesize($filePath));

        readfile($filePath);
        exit();
    } else {
        http_response_code(404);
        exit('El archivo solicitado no existe.');
    }
} else {
    http_response_code(400);
    exit('Solicitud inválida.');
}
?>