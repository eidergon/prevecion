<?php
require 'conexion.php';

$response = array(); // Array para almacenar la respuesta

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $desicion = $_POST["desicion"];
    $id_pqr = $_POST["id"];

    // Ejecuta la consulta de actualización en otra_tabla
    $stmtUpdate = $conn->prepare("UPDATE auditoria SET desicion_legal = ?, fecha_desicion_legal = CURRENT_TIMESTAMP WHERE id = ?");
    $stmtUpdate->bind_param("ss", $desicion, $id_pqr);
    $stmtUpdate->execute();
    $stmtUpdate->close();

    try {
        // Verifica si se recibieron archivos
        if (isset($_FILES['legal']) && !empty($_FILES['legal']['name'][0])) {
            // Itera sobre cada archivo cargado
            for ($i = 0; $i < count($_FILES['legal']['name']); $i++) {
                $nombreArchivo = $_FILES['legal']['name'][$i];
                $tipoArchivo = $_FILES['legal']['type'][$i];
                $datosArchivo = file_get_contents($_FILES['legal']['tmp_name'][$i]);
                $id_pqr = $_POST["id"];

                // Inserta datos en la base de datos
                $stmt = $conn->prepare("INSERT INTO legal_auditoria (nombre_archivo, tipo_archivo, datos_archivo, id_pqr) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $nombreArchivo, $tipoArchivo, $datosArchivo, $id_pqr);
                $stmt->execute();
                $stmt->close();
            }

            // Almacena un mensaje de éxito en el array de respuesta
            $response['status'] = 'success';
            $response['message'] = 'Archivos subidos correctamente.';
        } else {
            // Almacena un mensaje de error en el array de respuesta
            $response['status'] = 'error';
            $response['message'] = 'Error: No se recibieron archivos.';
        }
    } catch (Exception $e) {
        // Almacena un mensaje de error en el array de respuesta
        $response['status'] = 'error';
        $response['message'] = 'Error: ' . $e->getMessage();
    }
} else {
    // Almacena un mensaje de error en el array de respuesta
    $response['status'] = 'error';
    $response['message'] = 'Error: La solicitud debe ser de tipo POST.';
}

// Cierra la conexión a la base de datos
$conn->close();

// Devuelve la respuesta como JSON
echo json_encode($response);
