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
} else {
    // Si alguna de las claves no está definida, mostrar un mensaje de error
    die("Parámetros incorrectos en la URL");
}
// Cerrar la conexión a la base de datos
$conn->close();
?>

<?php if (isset($result_datos2) && $result_datos2->num_rows > 0) : ?>
    <table class="table table-bordered container_datos2" style="margin-top: 10px;">
        <thead>
            <tr style="text-align: center; text-transform: capitalize; font-size: 14px;">
                <th scope="col">ESTADO</th>
                <th scope="col">MOTIVO</th>
                <th scope="col">ANALISTA</th>
                <th scope="col">FECHA RESPUESTA</th>
                <th scope="col">RESPONSABLE</th>
                <th scope="col">OBSERVACIÓN</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($fila = $result_datos2->fetch_assoc()) : ?>
                <tr scope="row" style="text-align: center; text-transform: capitalize; font-size: 14px;">
                    <td><?php echo $fila['estado']; ?></a></td>
                    <td><?php echo $fila['motivo']; ?></td>
                    <td><?php echo $fila['analista']; ?></td>
                    <td><?php echo $fila['fecha_respuesta']; ?></td>
                    <td><?php echo $fila['responsable']; ?></td>
                    <td>
                        <span class="observacion-preview" data-toggle="modal" data-target="#observacionModal<?php echo $fila['id'] . '_' . time(); ?>">
                            <?php echo substr($fila['observacion'], 0, 10); ?>
                        </span>
                    </td>
                </tr>

                <!-- Modal -->
                <div class="modal fade" id="observacionModal<?php echo $fila['id'] . '_' . time(); ?>" tabindex="-1" role="dialog" aria-labelledby="observacionModalLabel" aria-hidden="true">
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
    <p style="text-align: center;">No se encontraron registros de los datos</p>
<?php endif; ?>