<?php
require 'conexion.php';

if (isset($_GET['cun']) && isset($_GET['documento']) && isset($_GET['telefono'])) {
    // Escapar y asignar valores
    $cun = mysqli_real_escape_string($conn, $_GET['cun']);
    $documento = mysqli_real_escape_string($conn, $_GET['documento']);
    $telefono = mysqli_real_escape_string($conn, $_GET['telefono']);
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Realizar la consulta
    $sql_datos3 = "SELECT * FROM datos WHERE id = '$id'";
    $result_datos3 = $conn->query($sql_datos3);

    // Verificar si la consulta se ejecutó correctamente
    if ($result_datos3 === false) {
        die("Error en la consulta: " . $conn->error);
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    // Si alguna de las claves no está definida, mostrar un mensaje de error
    die("Parámetros incorrectos en la URL");
}
?>

<?php if (isset($result_datos3) && $result_datos3->num_rows > 0) : ?>
    <table class="table table-bordered container_datos3" style="margin-top: 10px;">
        <thead>
            <tr style="text-align: center; text-transform: capitalize; font-size: 14px;">
                <th scope="col">retroalimentacion</th>
                <th scope="col">fecha de la retroalimentacion</th>
                <th scope="col">desicion legal</th>
                <th scope="col">fecha de la desicion</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($fila = $result_datos3->fetch_assoc()) : ?>
                <tr scope="row" style="text-align: center; text-transform: capitalize; font-size: 14px;">
                    <td><?php echo $fila['estado_retroalimentacion']; ?></td>
                    <td><?php echo $fila['fecha_estado_retroalimentacion']; ?></td>
                    <td><?php echo $fila['desicion_legal']; ?></td>
                    <td><?php echo $fila['fecha_desicion_legal']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else : ?>
    <p style="text-align: center;">No se encontraron registros de los datos</p>
<?php endif; ?>