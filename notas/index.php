<?php
session_start();
session_set_cookie_params(10080);
require '../includes/db.php';
require 'auth.php';

redirectIfNotLoggedIn();

$user_email = $_SESSION['user_email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$user_email]);
    $user = $stmt->fetch();

    if ($user) {
        $user_id = $user['id'];

        $stmt = $pdo->prepare("INSERT INTO notes (user_id, title, content) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $title, $content]);

        echo "<script> window.location='../';</script>";
    } else {
        echo "<p>Error: No se encontró el usuario en la base de datos.</p>";
    }
}

// Obtener notas del usuario
$stmt = $pdo->prepare("SELECT * FROM notes WHERE user_id = (SELECT id FROM users WHERE email = ?)");
$stmt->execute([$user_email]);
$notes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="4caf50">
    <title>Gestor de Apuntes</title>
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
            scrollbar-width: thin;
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

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: var(--border-radius);
            box-sizing: border-box;
            font-size: 16px;
            font-family: var(--font-family);
        }

        textarea {
            height: 150px;
            resize: vertical;
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

        h2 {
            color: var(--primary-color);
            font-size: 22px;
            font-weight: 600;
            margin-top: 40px;
        }

        .dark h2 {
            color: white;
        }

        ul {
            list-style: none;
            padding: 0;
            width: 100%;
            max-width: 500px;
        }

        li {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            font-size: 16px;
            display: flex;
            flex-direction: column;

            & p {
                overflow: hidden;
                text-overflow: ellipsis;
            }
        }

        strong {
            font-weight: 600;
            color: white;
            margin-bottom: 8px;
            text-overflow: ellipsis;
            overflow: hidden;
        }

        .dark li {
            background-color: var(--color-dark-4);
            color: white;
        }

        small {
            color: #777;
            font-size: 0.85em;
            margin-top: 10px;
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
        <h1>Agreguemos apuntes</h1>
        <nav class="header__nav">
            <a href="../" class="header__link">🏠 Inicio</a>
            <span class="separator">|</span>
            <a href="../cargar" class="header__link">⬆️ Subir archivo</a>
            <a id="theme-toggle"></a>
    </header>
    <form method="POST">
        <label for="title">Título:</label>
        <input type="text" name="title" id="title" required>

        <label for="content">Contenido:</label>
        <textarea name="content" id="content" required></textarea>

        <button type="submit">Guardar</button>
    </form>
    <h2>Apuntes guardados:</h2>
    <?php if (count($notes) > 0): ?>
        <ul>
            <?php foreach ($notes as $note): ?>
                <li>
                    <strong><?php echo htmlspecialchars($note['title']); ?></strong><br>
                    <p><?php echo nl2br(htmlspecialchars($note['content'])); ?></p><br>
                    <small>Creado el: <?php echo $note['created_at']; ?></small>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No hay apuntes guardados.</p>
    <?php endif; ?>
    <script src="../js/theme.js"></script>
</body>

</html>