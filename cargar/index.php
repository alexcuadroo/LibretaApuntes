<?php
session_start();
session_set_cookie_params(10080);
require '../includes/db.php';
require '../includes/auth.php';

redirectIfNotLoggedIn();

$user_email = $_SESSION['user_email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$user_email]);
    $user = $stmt->fetch();

    if ($user) {
        $user_id = $user['id'];
        $rootDirectory = "/home/u241718225/domains/edualex.uy/notas"; // Ruta absoluta
        $userDirectory = $rootDirectory . '/' . $user_id;

        if (!is_dir($userDirectory)) {
            mkdir($userDirectory, 0777, true);
        }

        $target_file = $userDirectory . '/' . basename($_FILES['file']['name']);
        $normalized_path = realpath($userDirectory) . '/' . basename($_FILES['file']['name']);

        if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
            $stmt = $pdo->prepare("INSERT INTO files (user_id, file_path) VALUES (?, ?)");
            $stmt->execute([$user_id, $normalized_path]);

            echo "<script>alert('Archivo cargado'); window.location='./';</script>";
        } else {
            echo "<p>Error al subir el archivo.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="4caf50">
    <title>Subir Archivos</title>
    <link rel="icon" href="../assets/favicon-dark.webp" sizes="any" media="(prefers-color-scheme: light)" />
    <link rel="icon" href="../assets/favicon-light.webp" sizes="any" media="(prefers-color-scheme: dark)" />
    <style>
        :root {
            --primary-color: #4caf50;
            --dark-color: #333;
            --light-color: #f4f7f6;
            --border-radius: 8px;
            --box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            --font-family: "Arial", sans-serif;
            --color-dark-1: #05002d;
            --color-dark-2: #202020;
            --color-dark-3: #120b4a;
            --color-dark-4: #0e045e;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--light-color);
            color: var(--dark-color);
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            height: 100vh;
            box-sizing: border-box;
            max-width: 90%;
            margin: auto;
            scrollbar-width: thin;
        }

        body.dark {
            background-color: var(--color-dark-3);
            color: white;
        }

        header {
            text-align: center;
        }

        nav {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #theme-toggle {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 0 3px;
            cursor: pointer;
            transition: ease 0.3;

            &:hover {
                transform: scale(1.18);
            }
        }

        nav .header__link {
            margin: 0;
            padding: 10px;
        }

        h1 {
            color: var(--primary-color);
            margin-top: 40px;
            font-size: 28px;
            font-weight: 600;
        }

        .dark h1 {
            color: white;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            width: 100%;
            max-width: 500px;
            margin-top: 20px;
            display: flex;
            flex-direction: column;
        }

        .dark form {
            background-color: var(--color-dark-4);
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: var(--dark-color);
        }

        .dark label {
            color: white;
        }

        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: var(--border-radius);
            box-sizing: border-box;
            font-size: 16px;
            font-family: var(--font-family);
            cursor: pointer;
        }

        button[type="submit"] {
            background-color: var(--primary-color);
            color: white;
            padding: 12px;
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 16px;
            font-family: var(--font-family);
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            display: inline-block;
            margin-top: 20px;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #45a049;
        }
    </style>
</head>

<body>
    <header class="header">
        <h1>Subir Archivos (max. 20MB)</h1>
        <nav class="header__nav">
            <a href="../" class="header__link">🏠 Inicio</a>
            <span class="separator">|</span>
            <a href="../notas" class="header__link">➕ Nuevo Apunte</a><a id="theme-toggle"></a>
        </nav>
    </header>

    <form method="POST" enctype="multipart/form-data">
        <label for="file">Selecciona un archivo:</label>
        <input type="file" name="file" id="file" required>

        <button type="submit">Subir archivo</button>
    </form>
    <script src="../js/theme.js"></script>
</body>

</html>