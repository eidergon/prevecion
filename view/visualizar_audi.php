<div class="body_consulta">
    <div class="container">
    <div class="heading">Buscar Auditoria</div>
        <form class="form" id="busquedaForm">
            <div>
                <label class="material-checkbox">
                    <input name="complemento_idventa" type="checkbox">
                    <span class="checkmark"></span>
                    ID Venta
                </label>
        
                <label class="material-checkbox">
                    <input name="complemento_documento" type="checkbox">
                    <span class="checkmark"></span>
                    Documento Asesor
                </label>
            </div>

            <div class="container_busqueda">
                <input checked="" class="checkbox_busqueda" type="checkbox">
                <div class="mainbox">
                    <div class="iconContainer">
                        <svg viewBox="0 0 512 512" height="1em" xmlns="http://www.w3.org/2000/svg" class="search_icon">
                            <path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"></path>
                        </svg>
                    </div>
                    <input name="busqueda" class="search_input" placeholder="search" type="text" autocomplete="off">
                </div>
            </div>
        </form>
    </div>
</div>


<div id="resultadoBusqueda"></div>

<script>
    $(document).ready(function() {
        // Manejar el envío del formulario con AJAX
        $('#busquedaForm').submit(function(e) {
            e.preventDefault(); // Evitar que el formulario se envíe tradicionalmente

            // Obtener datos del formulario
            var formData = $(this).serialize();

            // Realizar una petición AJAX para buscar en la base de datos
            $.ajax({
                url: '../php/consulta_audi.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Actualizar la tabla con los resultados de la búsqueda
                    $('#resultadoBusqueda').html(response);
                },
                error: function(error) {
                    console.log('Error en la búsqueda:', error);
                }
            });
        });

        $('input[type="checkbox"]').change(function() {
            $('input[type="checkbox"]').not(this).prop('checked', false);
        });
    });
</script>