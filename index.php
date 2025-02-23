<?php
session_start();
session_set_cookie_params(10080);
require 'includes/db.php';
require 'includes/auth.php';

redirectIfNotLoggedIn();

$user_email = $_SESSION['user_email'];

// funcion para eliminar
function handleFileDeletion($pdo, $user_email)
{
    if (isset($_GET['delete_file'])) {
        $file_id = $_GET['delete_file'];

        $stmt = $pdo->prepare("SELECT file_path FROM files WHERE id = ? AND user_id = (SELECT id FROM users WHERE email = ?)");
        $stmt->execute([$file_id, $user_email]);
        $file = $stmt->fetch();

        if ($file) {
            $filePath = realpath($file['file_path']);

            echo "Ruta del archivo: " . $filePath;

            if ($filePath && file_exists($filePath)) {
                unlink($filePath);

                $stmt = $pdo->prepare("DELETE FROM files WHERE id = ?");
                $stmt->execute([$file_id]);

                header("Location: ./");
                exit;
            } else {
                die("Error: El archivo no existe en la ruta especificada.");
            }
        } else {
            die("Error: No se encontró el archivo en la base de datos.");
        }
    }
}

handleFileDeletion($pdo, $user_email);

if (isset($_GET['delete_note'])) {
    $note_id = $_GET['delete_note'];
    $stmt = $pdo->prepare("DELETE FROM notes WHERE id = ? AND user_id = (SELECT id FROM users WHERE email = ?)");
    $stmt->execute([$note_id, $user_email]);
    header("Location: ./");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM notes WHERE user_id = (SELECT id FROM users WHERE email = ?)");
$stmt->execute([$user_email]);
$notes = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT * FROM files WHERE user_id = (SELECT id FROM users WHERE email = ?)");
$stmt->execute([$user_email]);
$files = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="4caf50">
    <title>La Libreta de los Genios | EduAlex</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="assets/favicon-dark.webp" sizes="any" media="(prefers-color-scheme: light)" />
    <link rel="icon" href="assets/favicon-light.webp" sizes="any" media="(prefers-color-scheme: dark)" />
</head>

<body>
    <div id="overlay" class="overlay"></div>
    <div id="banner" class="banner">
        <button id="closeBanner" class="close-btn">&times;</button>
        <h2>Hola 👋 <?php echo htmlspecialchars($_SESSION['user_name']); ?></h2>
        <p>Para agregar una nota, arriba tienes <strong>➕ Nuevo Apunte </strong><br> y la opción <br><strong>⬆️ Subir
                archivo</strong>, puedes empezar por ahí. 😁</p>
    </div>
    <header class="header">
        <h1 class="header__title">📖 Apuntes de <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
        <nav class="header__nav">
            <a href="./notas" class="header__link">➕ Nuevo Apunte</a>
            <span class="separator">|</span>
            <a href="./cargar" class="header__link">⬆️ Subir archivo</a>
            <span class="separator">|</span>
            <a href="logout.php" class="header__link">👋 Cerrar sesión</a>
            <span class="separator">|</span>
            <a id="theme-toggle"><img width="25px" src="assets/soluna2.webp" alt="Modo Claro u Oscuro"></a>
        </nav>
    </header>

    <main class="main">
        <section class="notes">
            <h2 class="section__title">Tus Apuntes:</h2>
            <?php if (count($notes) > 0): ?>
                <ul>
                    <?php foreach ($notes as $note): ?>
                        <li class="note-item">
                            <div class="note-content">
                                <h3 class="note__title"><?php echo htmlspecialchars($note['title']); ?></h3>
                                <p class="note__text"><?php echo nl2br(htmlspecialchars($note['content'])); ?></p>
                                <small class="note__date">Creado el: <?php echo $note['created_at']; ?></small>
                                <form method="GET" class="note__form">
                                    <input type="hidden" name="delete_note" value="<?php echo $note['id']; ?>">
                                    <button type="submit" class="note__delete-button">Eliminar</button>
                                    <a class="note-editar-button" href="./editar?id=<?php echo $note['id']; ?>">Editar</a>
                                </form>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No hay apuntes guardados.</p>
            <?php endif; ?>
        </section>
        <section class="files">
            <h2 class="section__title">Tus archivos:</h2>
            <?php if (count($files) > 0): ?>
                <ul class="file-list">
                    <?php foreach ($files as $file): ?>
                        <li class="file-item">
                            <a href="get_file.php?file=<?php echo urlencode(basename($file['file_path'])); ?>" target="_blank"
                                class="file__link">
                                <?php echo htmlspecialchars(basename($file['file_path'])); ?>
                            </a>
                            <small class="note__date">Subido el: <?php echo $file['uploaded_at']; ?></small>
                            <form method="GET" class="file__form">
                                <input type="hidden" name="delete_file" value="<?php echo $file['id']; ?>">
                                <button type="submit">Eliminar</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No hay archivos subidos.</p>
            <?php endif; ?>
        </section>
    </main>
    <footer>&copy; 2025 | La Libreta de los Genios | <a href="https://edualex.uy">EduAlex</a></footer>
    <script src="js/theme.js"></script>
    <script src="js/banner.js"></script>
</body>

</html>