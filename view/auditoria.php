<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../');
    exit();
}
?>

<div class="body">
    <div class="container">
        <div class="heading">Crear Auditoria</div>
        <form class="form" id="crear_auditoria">
            <input required class="input" name="id_venta" id="id_venta" placeholder="ID Venta" autocomplete="off" pattern="[0-9]+" title="Ingrese solo números">
            <input required class="input" name="nombre" id="nombre" placeholder="Nombre" autocomplete="off">
            <input required class="input" name="documento" id="documento" placeholder="Documento" pattern="[0-9]+" title="Ingrese solo números">
            <input required class="input" name="supervisor" id="supervisor" placeholder="Supervisor" autocomplete="off">
            <input required class="input" name="nombre_cliente" id="nombre_cliente" placeholder="Nombre Cliente" autocomplete="off">
            <input required class="input" name="documento_cliente" id="documento_cliente" placeholder="Documento Cliente" autocomplete="off" pattern="[0-9]+" title="Ingrese solo números">
            <input required class="input" name="telefono" id="telefono" placeholder="Telefono" pattern="3[0-9]+" title="Ingrese un número de teléfono que empiece con 3">

            <label class="label">Producto</label>
            <div class="check_content">
                <label class="material-checkbox">
                    <input name="hogar" type="checkbox">
                    <span class="checkmark"></span>
                    Hogar
                </label>

                <label class="material-checkbox">
                    <input name="terminales" type="checkbox">
                    <span class="checkmark"></span>
                    Terminales
                </label>

                <label class="material-checkbox">
                    <input name="movil" type="checkbox">
                    <span class="checkmark"></span>
                    Movil
                </label>

                <label class="material-checkbox">
                    <input name="dedicadas" type="checkbox">
                    <span class="checkmark"></span>
                    Dedicadas
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
    </div>
</div>

<script>
    //limpiar el form
    function limpiar() {
        crear_auditoria.reset();
        return false;
    }

    //Jquerry
    $(document).ready(function() {
        var formulario = $('#crear_auditoria');
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
                url: '../php/crear_auditoria.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    response = JSON.parse(response);
                    if (response.status === "success") {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Bien hecho!',
                            text: response.message,
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