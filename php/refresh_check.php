<?php
require 'conexion.php';

if (isset($_GET['cun']) && isset($_GET['documento']) && isset($_GET['telefono'])) {
    // Escapar y asignar valores
    $cun = mysqli_real_escape_string($conn, $_GET['cun']);
    $documento = mysqli_real_escape_string($conn, $_GET['documento']);
    $telefono = mysqli_real_escape_string($conn, $_GET['telefono']);
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Realizar la consulta
    $sql_datos4 = "SELECT * FROM datos WHERE id = '$id'";
    $result_datos4 = $conn->query($sql_datos4);

    // Verificar si la consulta se ejecutó correctamente
    if ($result_datos4 === false) {
        die("Error en la consulta: " . $conn->error);
    }
} else {
    // Si alguna de las claves no está definida, mostrar un mensaje de error
    die("Parámetros incorrectos en la URL");
}
// Cerrar la conexión a la base de datos
$conn->close();
?>

<?php if (isset($result_datos4) && $result_datos4->num_rows > 0) : ?>
    <table class="table table-bordered container_datos4" style="margin-top: 10px;">
        <thead>
            <tr style="text-align: center; text-transform: capitalize; font-size: 14px;">
                <th scope="col">habeas Data</th>
                <th scope="col">Ley 2300</th>
                <th scope="col">Mala Gestion</th>
                <th scope="col">Suplantacion De Identidad</th>
                <th scope="col">Lectura De Contrato</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($fila = $result_datos4->fetch_assoc()) : ?>
                <tr scope="row" style="text-align: center; text-transform: capitalize; font-size: 14px;">
                    <td><?php echo ($fila['habeas_data'] == 1) ? '✔' : '✘'; ?></td>
                    <td><?php echo ($fila['ley_2300'] == 1) ? '✔' : '✘'; ?></td>
                    <td><?php echo ($fila['mala_gestion'] == 1) ? '✔' : '✘'; ?></td>
                    <td><?php echo ($fila['suplantacion'] == 1) ? '✔' : '✘'; ?></td>
                    <td><?php echo ($fila['lectura_contrato'] == 1) ? '✔' : '✘'; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else : ?>
<?php endif; ?>