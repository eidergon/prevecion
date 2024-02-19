<?php
require 'conexion.php';

if (isset($_GET['id']) && isset($_GET['documento']) && isset($_GET['telefono'])) {
    // Escapar y asignar valores
    $cun = mysqli_real_escape_string($conn, $_GET['cun']);
    $documento = mysqli_real_escape_string($conn, $_GET['documento']);
    $telefono = mysqli_real_escape_string($conn, $_GET['telefono']);
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Realizar la consulta
    $sql_historial = "SELECT * FROM historial WHERE id_original = '$id' ORDER BY id DESC";
    $result_historial = $conn->query($sql_historial);

    // Verificar si la consulta se ejecutó correctamente
    if ($result_historial === false) {
        die("Error en la consulta: " . $conn->error);
    }
} else {
    // Si alguna de las claves no está definida, mostrar un mensaje de error
    die("Parámetros incorrectos en la URL");
}

// Cerrar la conexión a la base de datos
$conn->close();
?>

<?php if (isset($result_historial) && $result_historial->num_rows > 0) : ?>
    <table class="container_historial table table-bordered" style="max-height: 400px; overflow-y: auto;">
        <thead>
            <tr style="text-align: center; text-transform: capitalize; font-size: 14px;">
                <th scope="col">estado</th>
                <th scope="col">motivo</th>
                <th scope="col">analista</th>
                <th scope="col">Responsable</th>
                <th scope="col">Observación</th>
                <th scope="col">Fecha Respuesta</th>
                <th scope="col">Usuario modificacion</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($fila = $result_historial->fetch_assoc()) : ?>
                <tr scope="row" style="text-align: center; text-transform: capitalize; font-size: 14px;">
                    <td><?php echo $fila['estado']; ?></td>
                    <td><?php echo $fila['motivo']; ?></td>
                    <td><?php echo $fila['analista']; ?></td>
                    <td><?php echo $fila['responsable']; ?></td>
                    <td>
                        <span class="observacion-preview" data-toggle="modal" data-target="#observacionModal<?php echo $fila['id']; ?>">
                            <?php echo substr($fila['observacion'], 0, 10); ?>
                        </span>
                    </td>
                    <td><?php echo $fila['fecha_modificacion']; ?></td>
                    <td><?php echo $fila['usuario_modificacion']; ?></td>

                </tr>

                <!-- Modal -->
                <div class="modal fade" id="observacionModal<?php echo $fila['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="observacionModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content" style="background: white;">
                            <div class="modal-header">
                                <h5 class="modal-title" id="observacionModalLabel">Observación Completa</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <?php echo $fila['observacion']; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else : ?>
    <table class="container_historial table table-bordered" style="max-height: 400px; overflow-y: auto;">
        <thead>
            <tr style="text-align: center; text-transform: capitalize; font-size: 14px;">
                <th scope="col">estado</th>
                <th scope="col">motivo</th>
                <th scope="col">analista</th>
                <th scope="col">Responsable</th>
                <th scope="col">Observación</th>
                <th scope="col">Fecha Respuesta</th>
                <th scope="col">Usuario modificacion</th>
            </tr>
        </thead>
        <tbody>
            <tr scope="row" style="text-align: center; text-transform: capitalize; font-size: 14px;">
                <td colspan="7">Sin Modificaciones</td>
            </tr>
        </tbody>
    </table>
<?php endif; ?>