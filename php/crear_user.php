<?php
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica la conexión a la base de datos
    if (!$conn) {
        die("Conexión fallida: " . mysqli_connect_error());
    }

    // Verifica la existencia de las variables POST
    if (isset($_POST["nombre"]) && isset($_POST["username"]) && isset($_POST["password"])) {
        // Escapa y obtiene los datos del formulario
        $nombre = mysqli_real_escape_string($conn, $_POST["nombre"]);
        $username = mysqli_real_escape_string($conn, $_POST["username"]);
        $password = mysqli_real_escape_string($conn, $_POST["password"]);

        $cargo = "";
        if (isset($_POST["supervisor"])) {
            $cargo .= "supervisor";
        }
        if (isset($_POST["analista_pf"])) {
            $cargo .= "analista_pf";
        }
        if (isset($_POST["legal"])) {
            $cargo .= "legal";
        }
        $cargo = rtrim($cargo);

        // Añade el estado predeterminado (1 para activo) al insertar un nuevo usuario
        $estado = 1;

        // Prepara la consulta SQL con parámetros para evitar la inyección de SQL
        $sql = "INSERT INTO login (user, pass, nombre, cargo, estado) VALUES (?, ?, ?, ?, ?)";

        // Prepara la sentencia SQL
        $stmt = mysqli_prepare($conn, $sql);

        // Vincula los parámetros
        mysqli_stmt_bind_param($stmt, "ssssi", $username, $password, $nombre, $cargo, $estado);

        // Ejecuta la consulta
        $result = mysqli_stmt_execute($stmt);

        // Verifica el resultado
        if ($result) {
            // Usuario agregado con éxito
            echo json_encode(array("status" => "success", "message" => "Usuario agregado con éxito"));
        } else {
            // Error al agregar usuario
            echo json_encode(array("status" => "error", "message" => "Error al agregar usuario"));
        }
        

        // Cierra la sentencia
        mysqli_stmt_close($stmt);
    } else {
        // Variables POST no establecidas
        echo "<script>alert('Por favor, complete todos los campos');</script>";
    }

    // Cierra la conexión a la base de datos
    mysqli_close($conn);
}
?>
