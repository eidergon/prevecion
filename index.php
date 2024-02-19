<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Prevencion</title>
    <link rel="stylesheet" href="./css/login.css">
    <link rel="shortcut icon" href="./img/logo-removebg-preview.ico" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="shortcut icon" href="./img/logo-removebg-preview.ico" type="image/x-icon">
</head>

<body>
    <div class="body">
        <div class="container">
            <div class="heading">Sign In</div>
            <form class="form" id="form" method="post">
                <input required class="input" name="username" id="user" placeholder="Username" autocomplete="off">
                <input required class="input" type="password" name="password" id="password" placeholder="Password">
                <span class="forgot-password" id="forgot-password"><a>Forgot Password ?</a></span>
                <input class="login-button" id="login-button" type="submit" value="Sign In">
            </form>
            <span class="agreement" id="agreement"><a>Desarrollo de One Contact</a></span>
        </div>
    </div>

    <script>
        function no_valido() {
            Swal.fire({
                icon: 'error',
                title: 'ERROR',
                text: 'Accion no permitida',
                allowOutsideClick: false,
            });
        }

        $(function() {
            $('#forgot-password').click(function() {
                Swal.fire({
                    text: "¿Has olvidado tu contraseña?",
                    icon: "question"
                });
            });

            $('#agreement').click(function() {
                Swal.fire({
                    text: "Desarrollado por Eider Gonzalez",
                });
            });
        });
    </script>

</body>

</html>

<?php
require 'php/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Modifica la consulta para incluir la condición de estado activo
    $consulta = "SELECT nombre, cargo, estado FROM login WHERE user = ? AND pass = ?";
    $stmt = mysqli_prepare($conn, $consulta);
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) === 1) {
        mysqli_stmt_bind_result($stmt, $nombre, $cargo, $estado);
        mysqli_stmt_fetch($stmt);

        // Verifica si el usuario está activo
        if ($estado == 1) {
            // Usuario activo, inicia la sesión
            session_start();
            $_SESSION["nombre"] = $nombre;
            $_SESSION["cargo"] = $cargo;
            $_SESSION['logged_in'] = true;
            header("Location: ./view/inicio.php");
            exit;
        } else {
            // Usuario deshabilitado, muestra un mensaje de error
            echo "<script>Swal.fire({
                    icon: 'error',
                    title: 'ERROR',
                    text: 'Usuario deshabilitado',
                    allowOutsideClick: false,
                });</script>";
        }
    } else {
        // Datos inválidos, mostrar un mensaje de error con SweetAlert2
        echo "<script>Swal.fire({
                icon: 'error',
                title: 'ERROR',
                text: 'Datos inválidos',
                allowOutsideClick: false,
            });</script>";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>