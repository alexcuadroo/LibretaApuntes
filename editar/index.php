<?php
session_start();
session_set_cookie_params(10080);
require '../includes/db.php';
require 'auth.php';

redirectIfNotLoggedIn();

$user_email = $_SESSION['user_email'];

$note_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    $stmt = $pdo->prepare("SELECT id FROM notes WHERE id = ? AND user_id = (SELECT id FROM users WHERE email = ?)");
    $stmt->execute([$note_id, $user_email]);
    $note = $stmt->fetch();

    if ($note) {
        $stmt = $pdo->prepare("UPDATE notes SET title = ?, content = ? WHERE id = ?");
        $stmt->execute([$title, $content, $note_id]);

        echo "<script> window.location='../';</script>";
    } else {
        echo "<p>Error: No tienes permiso para editar esta nota o la nota no existe.</p>";
    }
}

$stmt = $pdo->prepare("SELECT * FROM notes WHERE id = ? AND user_id = (SELECT id FROM users WHERE email = ?)");
$stmt->execute([$note_id, $user_email]);
$note = $stmt->fetch();

if (!$note) {
    echo "<p>Error: No se encontró la nota o no tienes permiso para editarla.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="4caf50">
    <title>Editar Nota</title>
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

        form {
            background-color: white;
            padding: 2rem;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            width: 90%;
            max-width: 500px;
            margin: 2rem 0;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: var(--color-dark-2);
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: var(--border-radius);
            font-family: var(--font-family);
            font-size: 1rem;
            color: var(--dark-color);
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
            min-height: 150px;
        }

        button[type="submit"] {
            background-color: var(--primary-color);
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: var(--border-radius);
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            opacity: 0.7;
        }

        a {
            display: inline-block;
            margin-top: 1rem;
            color: var(--primary-color);
            text-decoration: none;
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        a:hover {
            color: darken(var(--primary-color), 10%);
        }

        h1 {
            color: var(--color-dark-1);
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .dark h1 {
            color: white;
        }
    </style>
</head>

<body>
    <a id="theme-toggle"></a>
    <h1>Editar Nota</h1>
    <form method="POST">
        <label for="title">Título:</label>
        <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($note['title']); ?>" required>

        <label for="content">Contenido:</label>
        <textarea name="content" id="content" required><?php echo htmlspecialchars($note['content']); ?></textarea>

        <button type="submit">Actualizar</button>
    </form>
    <a href="../">Cancelar</a>
    <script src="../js/theme.js"></script>
</body>
</body>

</html>