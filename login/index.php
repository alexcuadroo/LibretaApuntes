<?php
$client_id = ''; // ID de cliente de la API de Google
$redirect_uri = 'https://dominio.com/callback.php';
$scope = 'email profile'; // Permisos solicitados
$auth_url = "https://accounts.google.com/o/oauth2/auth?response_type=code&client_id=$client_id&redirect_uri=$redirect_uri&scope=$scope&access_type=online";
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>La Libreta de los Genios | EduAlex</title>
  <style>
    body {
      font-family: system-ui, -apple-system, Ubuntu, Cantarell, "Open Sans",
        "Helvetica Neue", sans-serif, sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      justify-content: center;
      max-width: 1500px;
      margin: auto;
    }

    main {
      flex: 1;
      padding: 80px;
    }

    .content {
      display: flex;
      flex-direction: row;
      gap: 20px;
      margin-top: 20vh;
    }

    .content header {
      text-align: center;
      font-family: Verdana, sans-serif;
    }

    .column {
      flex: 1;
      border-radius: 8px;
    }

    header h1 {
      font-size: 4.5rem;
      margin-bottom: 20px;
      margin-top: 0;
    }

    footer {
      text-align: center;
      padding: 10px;
      margin-top: auto;
      font-size: 0.8rem;

      & a {
        color: #007bff;
        text-decoration: none;

        &:hover {
          text-decoration: underline;
        }
      }
    }

    .column a {
      display: flex;
      flex-direction: column;
      width: 100%;
      max-width: 270px;
      text-align: center;
      border: 1px solid #007bff;
      padding: 20px;
      margin: 9vh auto;
      border-radius: 8px;
      text-decoration: none;
      color: #007bff;
      font-weight: bold;
      transition: all 0.3s;
      font-size: 1.3rem;

      &:hover {
        background-color: #007bff84;
        color: #ffffff;
        border-color: #007bff84;
      }
    }

    @media (prefers-color-scheme: light) {
      body {
        background-color: #ffffff;
        color: #000000;
      }

      .column .change {
        color: #53008a;
      }

      .column a {
        color: #53008a;
        border: 1px solid #53008a;

        &:hover {
          background-color: #53008a;
          color: #ffffff;
          border-color: #53008a;
        }
      }

      footer a {
        color: #53008a;
      }
    }

    @media (prefers-color-scheme: dark) {
      body {
        background-color: #121212;
        color: #ffffff;
      }

      .column .change {
        color: #007bff;
      }
    }

    @media (max-width: 768px) {
      .content {
        flex-direction: column;
        gap: 20px;
        margin-top: 5vh;
      }

      header h1 {
        font-size: 4.6rem;
        margin-bottom: 5vh;
      }

      header p {
        font-size: 1.2rem;
      }

      main {
        padding: 0;
      }

      .column a {
        &:hover {
          background-color: #ffffff;
          color: #007bff;
        }
      }

      .cookie-modal {
        padding: 12px 0;
      }
    }
  </style>
</head>

<body>
  <main>
    <section class="content">
      <header class="column">
        <h1>La Libreta de los <span class="change">Genios</span></h1>
        <p>
          Toma apuntes, ideas o guarda archivos de forma rápida y sencilla
        </p>
      </header>
      <div class="column">
        <a href="<?php echo $auth_url; ?>">Iniciar sesión con Google</a>
      </div>
    </section>
  </main>
  <footer>
    <p>
      &copy; 2025 La Libreta de los Genios |
      <a href="https://edualex.uy" target="_blank">EduAlex</a>
    </p>
  </footer>
</body>

</html>