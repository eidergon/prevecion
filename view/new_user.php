<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../');
    exit();
}
?>

<form class="form" id="crear_user">
    <div class="inputGroup">
        <input name="nombre" type="text" required autocomplete="off">
        <label for="name">Nombre</label>
    </div>
    <div class="inputGroup">
        <input name="username" type="text" required autocomplete="off">
        <label for="name">Username</label>
    </div>

    <div class="inputGroup">
        <input name="password" type="text" required autocomplete="off">
        <label for="name">Password</label>
    </div>
    <div>
        <label class="material-checkbox">
            <input name="analista_pf" type="checkbox">
            <span class="checkmark"></span>
            Analista PF
        </label>

        <label class="material-checkbox">
            <input name="supervisor" type="checkbox">
            <span class="checkmark"></span>
            Supervisor
        </label>

        <label class="material-checkbox">
            <input name="legal" type="checkbox">
            <span class="checkmark"></span>
            Legal
        </label>

    </div>
    <button type="submit" class="button">
        <div class="svg-wrapper-1">
            <div class="svg-wrapper">
                <svg class="icon" height="30" width="30" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22,15.04C22,17.23 20.24,19 18.07,19H5.93C3.76,19 2,17.23 2,15.04C2,13.07 3.43,11.44 5.31,11.14C5.28,11 5.27,10.86 5.27,10.71C5.27,9.33 6.38,8.2 7.76,8.2C8.37,8.2 8.94,8.43 9.37,8.8C10.14,7.05 11.13,5.44 13.91,5.44C17.28,5.44 18.87,8.06 18.87,10.83C18.87,10.94 18.87,11.06 18.86,11.17C20.65,11.54 22,13.13 22,15.04Z">
                    </path>
                </svg>
            </div>
        </div>
        <span>Crear</span>
    </button>
</form>

<script>
    //limpiar el form
    function limpiar() {
        crear_user.reset();
        return false;
    }

    //Jquerry
    $(document).ready(function() {
        var formulario = $('#crear_user');
        var botonEnvio = formulario.find('button[type="submit"]');
        var checkboxes = formulario.find('input[type="checkbox"]');

        // Enviar el formulario con AJAX
        formulario.submit(function(e) {
            e.preventDefault();

            var formData = $(this).serialize();
            var formulario = this;
            var botonEnvio = $(formulario).find('button[type="submit"]');

            botonEnvio.prop('disabled', true); // Deshabilitar el botón de envío

            $.ajax({
                url: '../php/crear_user.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    response = JSON.parse(response);
                    if (response.status === "success") {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Bien hecho!',
                            text: response.message,
                            onClose: function() {}
                        });
                        limpiar();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'ERROR',
                            text: response.message
                        });
                    }
                },
                complete: function() {
                    botonEnvio.prop('disabled', false); // Habilitar el botón después de completar la solicitud
                }
            });
        });

        $('input[type="checkbox"]').change(function() {
            $('input[type="checkbox"]').not(this).prop('checked', false);
        });
    });
</script>