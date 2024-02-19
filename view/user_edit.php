<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../');
    exit();
}
require '../php/conexion.php';
$id = mysqli_real_escape_string($conn, $_GET['id']);

$sql = "SELECT * FROM login";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nombre = $row['nombre'];
    $username = $row['user'];
    $password = $row['pass'];
    $cargo = $row['cargo'];
    $estado = $row['estado'];
}

// Cerrar la conexión a la base de datos
$conn->close();
?>

<form class="form" id="crear">
    <div class="inputGroup">
        <input name="nombre" value="<?php echo $nombre; ?>" type="text" required autocomplete="off">
        <label for="name">Nombre</label>
    </div>
    <div class="inputGroup">
        <input name="username" value="<?php echo $username; ?>" type="text" required autocomplete="off">
        <label for="name">Username</label>
    </div>

    <div class="inputGroup">
        <input name="password" value="<?php echo $password; ?>" type="text" required autocomplete="off">
        <label for="name">Password</label>
    </div>
    <div class="inputGroup">
        <input name="cargo" value="<?php echo $cargo; ?>" type="text" required autocomplete="off">
        <label for="name">cargo</label>
    </div>
    <label class="switch">
        <input type="checkbox" <?php echo($estado == 1) ? 'checked' : ''; ?>>
        <div class="slider">
            <div class="circle">
                <svg class="cross" xml:space="preserve" style="enable-background:new 0 0 512 512" viewBox="0 0 365.696 365.696" y="0" x="0" height="6" width="6" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <g>
                        <path data-original="#000000" fill="currentColor" d="M243.188 182.86 356.32 69.726c12.5-12.5 12.5-32.766 0-45.247L341.238 9.398c-12.504-12.503-32.77-12.503-45.25 0L182.86 122.528 69.727 9.374c-12.5-12.5-32.766-12.5-45.247 0L9.375 24.457c-12.5 12.504-12.5 32.77 0 45.25l113.152 113.152L9.398 295.99c-12.503 12.503-12.503 32.769 0 45.25L24.48 356.32c12.5 12.5 32.766 12.5 45.247 0l113.132-113.132L295.99 356.32c12.503 12.5 32.769 12.5 45.25 0l15.081-15.082c12.5-12.504 12.5-32.77 0-45.25zm0 0"></path>
                    </g>
                </svg>
                <svg class="checkmark" xml:space="preserve" style="enable-background:new 0 0 512 512" viewBox="0 0 24 24" y="0" x="0" height="10" width="10" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <g>
                        <path class="" data-original="#000000" fill="currentColor" d="M9.707 19.121a.997.997 0 0 1-1.414 0l-5.646-5.647a1.5 1.5 0 0 1 0-2.121l.707-.707a1.5 1.5 0 0 1 2.121 0L9 14.171l9.525-9.525a1.5 1.5 0 0 1 2.121 0l.707.707a1.5 1.5 0 0 1 0 2.121z"></path>
                    </g>
                </svg>
            </div>
        </div>
    </label>

    <button type="submit" class="button">
        <div class="svg-wrapper-1">
            <div class="svg-wrapper">
                <svg class="icon" height="30" width="30" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22,15.04C22,17.23 20.24,19 18.07,19H5.93C3.76,19 2,17.23 2,15.04C2,13.07 3.43,11.44 5.31,11.14C5.28,11 5.27,10.86 5.27,10.71C5.27,9.33 6.38,8.2 7.76,8.2C8.37,8.2 8.94,8.43 9.37,8.8C10.14,7.05 11.13,5.44 13.91,5.44C17.28,5.44 18.87,8.06 18.87,10.83C18.87,10.94 18.87,11.06 18.86,11.17C20.65,11.54 22,13.13 22,15.04Z">
                    </path>
                </svg>
            </div>
        </div>
        <span>Guardar</span>
    </button>
</form>

<script>
    //Jquerry
    $(document).ready(function() {
        var formulario = $('#crear');

        // Enviar el formulario con AJAX
        formulario.submit(function(e) {
            e.preventDefault();

            var formData = $(this).serialize();
            var formulario = this;
            var botonEnvio = $(formulario).find('button[type="submit"]');

            botonEnvio.prop('disabled', true);

            $.ajax({
                url: '../php/crear_user.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Bien hecho!',
                        text: 'Usuario agregado correctamente',
                        onClose: function() {}
                    });
                },
                error: function(error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'ERROR',
                        text: 'Error al crear el PQR: ' + error.responseText
                    });
                },
            });
        });

        $('input[type="checkbox"]').change(function() {
            $('input[type="checkbox"]').not(this).prop('checked', false);
        });
    });
</script>