<?php
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $estado = $_POST["estado"];
    $motivo = $_POST["motivo"];
    $responsable = $_POST["responsable"];
    $observacion = $_POST["observacion"];
    $analista = $_POST["analista_a_cargo"];
    $id = $_POST["id"];

    $sql_update = "UPDATE datos SET estado = ?, motivo = ?, analista = ?, responsable = ?, observacion = ?, fecha_respuesta = CURRENT_TIMESTAMP WHERE id = ?";
    $stmt_update = mysqli_prepare($conn, $sql_update);

    mysqli_stmt_bind_param($stmt_update, "sssssi", $estado, $motivo, $analista, $responsable, $observacion, $id);
    mysqli_stmt_execute($stmt_update);

    if (mysqli_stmt_affected_rows($stmt_update) > 0) {
        // Inserta una nueva fila en la tabla de historial
        $sql_insert_historial = "INSERT INTO historial (id_original, estado, motivo, analista, responsable, observacion, fecha_respuesta, usuario_modificacion) VALUES (?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, ?)";
        $stmt_insert_historial = mysqli_prepare($conn, $sql_insert_historial);

        mysqli_stmt_bind_param($stmt_insert_historial, "issssss", $id, $estado, $motivo, $analista, $responsable, $observacion, $analista);
        mysqli_stmt_execute($stmt_insert_historial);

        // Muestra un mensaje de éxito
        echo json_encode(array("status" => "success", "message" => "Datos modificados correctamente"));
    } else {
        // Manejar el caso en que la consulta de actualización falla
        echo json_encode(array("status" => "error", "message" => "Error al modificar los datos"));
    }

    try {
        // Verifica si se recibieron archivos
        if (isset($_FILES['archivos']) && !empty($_FILES['archivos']['name'][0])) {
            // Itera sobre cada archivo cargado
            for ($i = 0; $i < count($_FILES['archivos']['name']); $i++) {
                $nombreArchivo = $_FILES['archivos']['name'][$i];
                $tipoArchivo = $_FILES['archivos']['type'][$i];
                $datosArchivo = file_get_contents($_FILES['archivos']['tmp_name'][$i]);
                $id = $_POST["id"];

                // Inserta datos en la base de datos
                $stmt = $conn->prepare("INSERT INTO archivos (nombre_archivo, tipo_archivo, datos_archivo, id_pqr) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $nombreArchivo, $tipoArchivo, $datosArchivo, $id);
                $stmt->execute();
                $stmt->close();
            }

            // Muestra un mensaje de éxito
            echo "Archivos subidos correctamente.";
        } else {
            // Muestra un mensaje de error si no se recibieron archivos
            echo "Error: No se recibieron archivos.";
        }
    } catch (Exception $e) {
        // Muestra un mensaje de error en caso de excepción
        echo "Error: " . $e->getMessage();
    }

    mysqli_stmt_close($stmt_update);
    mysqli_stmt_close($stmt_insert_historial);
    mysqli_close($conn);
}
