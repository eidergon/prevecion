<?php
require 'conexion.php';

// Verificar si las claves están definidas en $_GET
if (isset($_GET['id']) && isset($_GET['documento']) && isset($_GET['telefono'])) {
    // Escapar y asignar valores
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $documento = mysqli_real_escape_string($conn, $_GET['documento']);
    $telefono = mysqli_real_escape_string($conn, $_GET['telefono']);

    // Realizar la consulta
    $sql_archivos = "SELECT * FROM archivos_auditoria WHERE id_pqr = '$id' AND tipo_archivo LIKE 'image/%' ORDER BY id DESC";
    $result_archivos = $conn->query($sql_archivos);

    // Verificar si la consulta se ejecutó correctamente
    if ($result_archivos === false) {
        die("Error en la consulta: " . $conn->error);
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    // Si alguna de las claves no está definida, mostrar un mensaje de error
    die("Parámetros incorrectos en la URL");
}
?>

<?php if ($result_archivos && $result_archivos->num_rows > 0) : ?>
    <div class="container_img">
        <?php while ($row = $result_archivos->fetch_assoc()) : ?>
            <?php
            $tipoArchivo = $row['tipo_archivo'];
            $archivoBase64 = base64_encode($row['datos_archivo']);
            $nombreArchivo = $row['nombre_archivo'];
            $fecha_subida = $row['fecha_subida'];
            ?>
            <?php if (strpos($tipoArchivo, 'image') !== false) : ?>
                <div class="card_img">
                    <p class="title_img">
                        <img src='data:<?php echo $tipoArchivo; ?>;base64,<?php echo $archivoBase64; ?>' class="card-img-top" alt='<?php echo $nombreArchivo; ?>'>
                    </p>
                    <p style="width: auto; text-align: center;">Tipo: <?php echo $tipoArchivo; ?></p>
                    <p style="width: auto; text-align: center;">Fecha de subida: <?php echo $fecha_subida; ?></p>
                    <button class="Btn_descargar">
                        <a style="text-decoration: none; color: white;" href='data:<?php echo $tipoArchivo; ?>;base64,<?php echo $archivoBase64; ?>' download='<?php echo $nombreArchivo; ?>'>
                            <svg class="saveicon" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" stroke-linejoin="round" stroke-linecap="round"></path>
                            </svg>
                            download
                        </a>
                    </button>
                </div>
            <?php elseif (strpos($tipoArchivo, 'audio') !== false) : ?>
                <div class="card_audio">
                    <p class="title_audio">
                        <audio controls>
                            <source type="<?php echo $tipoArchivo; ?>" src='data:<?php echo $tipoArchivo; ?>;base64,<?php echo $archivoBase64; ?>'>
                            Your browser does not support the audio element.
                        </audio>
                    </p>
                    <p style="text-align: center;"><?php echo $fecha_subida; ?></p>
                </div>
            <?php endif; ?>
        <?php endwhile; ?>
    </div>
<?php else : ?>
    <h2 style="text-align: center;">Evidencia del Analista</h2>
    <div class="container_img">
        <h3 style="text-align: center;">No tiene archivos Cargados.</h3>
    </div>
<?php endif; ?>