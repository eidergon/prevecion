<?php
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica la conexión a la base de datos
    if (!$conn) {
        die("Conexión fallida: " . mysqli_connect_error());
    }

    // Verifica la existencia de las variables POST
    if (isset($_POST["nombre_cliente"]) && isset($_POST["telefono"]) && isset($_POST["documento"]) && isset($_POST["cun"])) {
        // Escapa y obtiene los datos del formulario
        $nombre_cliente = mysqli_real_escape_string($conn, $_POST["nombre_cliente"]);
        $telefono = mysqli_real_escape_string($conn, $_POST["telefono"]);
        $documento = mysqli_real_escape_string($conn, $_POST["documento"]);
        $cun = mysqli_real_escape_string($conn, $_POST["cun"]);
        $id_venta = mysqli_real_escape_string($conn, $_POST["id_venta"]);

        $producto = "";
        if (isset($_POST["hogar"])) {
            $producto .= "Hogar";
        }
        if (isset($_POST["movil"])) {
            $producto .= "Movil";
        }
        if (isset($_POST["terminales"])) {
            $producto .= "Terminales";
        }
        $producto = rtrim($producto);

        // Prepara la consulta SQL con parámetros para evitar la inyección de SQL
        $sql = "INSERT INTO datos (nombre_cliente, telefono, documento, cun, producto, id_venta) VALUES (?, ?, ?, ?, ?, ?)";

        // Prepara la sentencia SQL
        $stmt = mysqli_prepare($conn, $sql);

        // Vincula los parámetros
        mysqli_stmt_bind_param($stmt, "ssssss", $nombre_cliente, $telefono, $documento, $cun, $producto, $id_venta);

        // Ejecuta la consulta
        $result = mysqli_stmt_execute($stmt);

        // Verifica el resultado
        if ($result) {
            // Usuario agregado con éxito
            echo json_encode(array("status" => "success", "message" => "PQR agregado con éxito"));
        } else {
            // Error al agregar usuario
            echo json_encode(array("status" => "error", "message" => "Error al agregar PQR"));
        }

        // Cierra la sentencia
        mysqli_stmt_close($stmt);
    } else {
    }

    // Cierra la conexión a la base de datos
    mysqli_close($conn);
}
