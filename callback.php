<?php
session_start();
require 'includes/db.php';

if (!isset($_GET['code'])) {
    die("Error: No se recibió el código de autorización.");
}

$code = $_GET['code'];

// Configuración de credenciales
$client_id = ''; // Completa el cliente id
$client_secret = ''; // Completa el cliente secreto
$redirect_uri = 'https://dominio.com/callback.php';

$token_url = 'https://oauth2.googleapis.com/token';
$post_data = [
    'code' => $code,
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'redirect_uri' => $redirect_uri,
    'grant_type' => 'authorization_code',
];

$options = [
    'http' => [
        'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query($post_data),
    ],
];

$context = stream_context_create($options);
$response = file_get_contents($token_url, false, $context);

if ($response === FALSE) {
    die("Error: No se pudo obtener el token de acceso.");
}

$data = json_decode($response, true);
$access_token = $data['access_token'];

$user_info_url = 'https://www.googleapis.com/oauth2/v3/userinfo';
$options = [
    'http' => [
        'header' => "Authorization: Bearer $access_token\r\n",
        'method' => 'GET',
    ],
];

$context = stream_context_create($options);
$user_info_response = file_get_contents($user_info_url, false, $context);

if ($user_info_response === FALSE) {
    die("Error: No se pudo obtener la información del usuario.");
}

$user_info = json_decode($user_info_response, true);
$user_email = $user_info['email'];
$user_name = $user_info['name'];

$_SESSION['user_email'] = $user_email;
$_SESSION['user_name'] = $user_name;

$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$user_email]);
$user = $stmt->fetch();

if (!$user) {
    $stmt = $pdo->prepare("INSERT INTO users (email, name) VALUES (?, ?)");
    $stmt->execute([$user_email, $user_name]);
}

header("Location: ./");
exit;
?>