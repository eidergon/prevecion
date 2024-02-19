<?php
require 'conexion.php';

// Verificar si la solicitud es de tipo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener y escapar los valores del formulario
    $id_venta = mysqli_real_escape_string($conn, $_POST["id_venta"]);
    $nombre = mysqli_real_escape_string($conn, $_POST["nombre"]);
    $documento = mysqli_real_escape_string($conn, $_POST["documento"]);
    $supervisor = mysqli_real_escape_string($conn, $_POST["supervisor"]);
    $nombre_cliente = mysqli_real_escape_string($conn, $_POST["nombre_cliente"]);
    $telefono = mysqli_real_escape_string($conn, $_POST["telefono"]);
    $documento_cliente = mysqli_real_escape_string($conn, $_POST["documento_cliente"]);
    $campaña = mysqli_real_escape_string($conn, $_POST["campaña"]);

    // Construir la cadena de productos seleccionados
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

    // Preparar la consulta SQL para la inserción
    $sql = "INSERT INTO auditoria (id_venta, nombre, documento, supervisor, nombre_cliente, telefono, documento_cliente, producto, campaña) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    // Vincular los parámetros y ejecutar la consulta
    mysqli_stmt_bind_param($stmt, "sssssssss", $id_venta, $nombre, $documento, $supervisor, $nombre_cliente, $telefono, $documento_cliente, $producto, $campaña);
    $result = mysqli_stmt_execute($stmt);

    // Comprobar el resultado y enviar una respuesta JSON
    if ($result) {
        echo json_encode(array("status" => "success", "message" => "Auditoria agregada con éxito"));
    } else {
        echo json_encode(array("status" => "error", "message" => "Error al agregar la Auditoria"));
    }

    // Cerrar la declaración preparada y la conexión a la base de datos
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
