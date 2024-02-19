<?php
require 'conexion.php';

if (isset($_GET['cun']) && isset($_GET['documento']) && isset($_GET['telefono'])) {
    // Escapar y asignar valores
    $cun = mysqli_real_escape_string($conn, $_GET['cun']);
    $documento = mysqli_real_escape_string($conn, $_GET['documento']);
    $telefono = mysqli_real_escape_string($conn, $_GET['telefono']);
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Realizar la consulta
    $sql_datos2 = "SELECT * FROM datos WHERE id = '$id'";
    $result_datos2 = $conn->query($sql_datos2);

    // Verificar si la consulta se ejecutó correctamente
    if ($result_datos2 === false) {
        die("Error en la consulta: " . $conn->error);
    }

    // Obtener los datos y asignar la observación
    $row = $result_datos2->fetch_assoc();
    $observacion = $row['observacion'];

    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    // Si alguna de las claves no está definida, mostrar un mensaje de error
    die("Parámetros incorrectos en la URL");
}
?>

<?php echo $observacion; ?>
