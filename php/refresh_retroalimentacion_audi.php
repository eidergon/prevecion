<?php
require 'conexion.php';

if (isset($_GET['id']) && isset($_GET['documento']) && isset($_GET['telefono'])) {
    // Escapar y asignar valores
    $documento = mysqli_real_escape_string($conn, $_GET['documento']);
    $telefono = mysqli_real_escape_string($conn, $_GET['telefono']);
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Realizar la consulta
    $sql_retroalimentacion = "SELECT * FROM retroalimentacion_auditoria WHERE id_pqr = '$id' ORDER BY id DESC";
    $result_retroalimentacion = $conn->query($sql_retroalimentacion);

    // Verificar si la consulta se ejecutó correctamente
    if ($result_retroalimentacion === false) {
        die("Error en la consulta: " . $conn->error);
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    // Si alguna de las claves no está definida, mostrar un mensaje de error
    die("Parámetros incorrectos en la URL");
}
?>

<?php if (isset($result_retroalimentacion) && $result_retroalimentacion->num_rows > 0) : ?>
    <table class="container_retroalimentacion table table-bordered" style="max-height: 400px; overflow-y: auto;">
        <thead>
            <tr style="text-align: center; text-transform: capitalize; font-size: 14px;">
                <th scope="col">nombre</th>
                <th scope="col">tipo</th>
                <th scope="col">fecha</th>
                <th scope="col">descargar</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($fila = $result_retroalimentacion->fetch_assoc()) : ?>
                <tr scope="row" style="text-align: center; text-transform: capitalize; font-size: 14px;">
                    <td><?php echo $fila['nombre_archivo']; ?></td>
                    <td><?php echo $fila['tipo_archivo']; ?></td>
                    <td><?php echo $fila['fecha_subida']; ?></td>
                    <td style="display: flex; justify-content: center; align-items: center;">
                        <button class="Btn_legal">
                            <a style="text-decoration: none; color: #000;" href='data:<?php echo $fila['tipo_archivo']; ?>;base64,<?php echo base64_encode($fila['datos_archivo']); ?>' download='<?php echo $fila['nombre_archivo']; ?>'>
                                <svg class="svgIcon" viewBox="0 0 384 512" height="1em" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M169.4 470.6c12.5 12.5 32.8 12.5 45.3 0l160-160c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L224 370.8 224 64c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 306.7L54.6 265.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l160 160z"></path>
                                </svg>
                            </a>
                        </button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else : ?>
    <h2 style="text-align: center;">Soporte retroalimentacion</h2>
    <table class="container_retroalimentacion table table-bordered" style="max-height: 400px; overflow-y: auto;">
        <thead>
            <tr style="text-align: center; text-transform: capitalize; font-size: 14px;">
                <th scope="col">nombre</th>
                <th scope="col">tipo</th>
                <th scope="col">fecha</th>
                <th scope="col">descargar</th>
            </tr>
        </thead>
        <tbody>
            <tr style="text-align: center; text-transform: capitalize; font-size: 14px;">
                <td colspan="4">Sin datos</td>
            </tr>
        </tbody>
    </table>
<?php endif; ?>