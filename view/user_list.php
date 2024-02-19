<?php
require '../php/conexion.php';

session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../');
    exit();
}

$sql = "SELECT * FROM login";
$result = $conn->query($sql);

// Cerrar la conexión a la base de datos
$conn->close();
?>

<?php if (isset($result) && $result->num_rows > 0) : ?>
    <h2 style="text-align: center;">Lista de usuarios</h2>
    <table class="table table-bordered" style="margin-top: 10px;">
        <thead>
            <tr style="text-align: center; text-transform: capitalize; font-size: 14px;">
                <th scope="col">user</th>
                <th scope="col">pass</th>
                <th scope="col">nombre</th>
                <th scope="col">cargo</th>
                <th scope="col">estado</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($fila = $result->fetch_assoc()) : ?>
                <tr scope="row" style="text-align: center; text-transform: capitalize; font-size: 14px;">
                    <td><?php echo $fila['user']; ?></a></td>
                    <td><?php echo $fila['pass']; ?></td>
                    <td><?php echo $fila['nombre']; ?></td>
                    <td><?php echo $fila['cargo']; ?></td>
                    <td>
                        <label class="switch">
                            <input type="checkbox" <?php echo ($fila['estado'] == 1) ? 'checked' : ''; ?>>
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
                    </td>
                    <td>
                        <button class="button_editar">
                            <a href="#" style="color: white; text-decoration: none;" data-form="user_edit" data-id="<?php echo $fila['id'];?>" class="link-dark rounded"">
                                <svg class=" svg-icon" fill="none" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                    <g stroke="#a649da" stroke-linecap="round" stroke-width="2">
                                        <path d="m20 20h-16"></path>
                                        <path clip-rule="evenodd" d="m14.5858 4.41422c.781-.78105 2.0474-.78105 2.8284 0 .7811.78105.7811 2.04738 0 2.82843l-8.28322 8.28325-3.03046.202.20203-3.0304z" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                                <span class="lable_editar">Edit</span>
                            </a>
                        </button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else : ?>
    <p style=" text-align: center;">No se encontraron registros de los datos</p>
<?php endif; ?>

<script>
    $(document).ready(function() {
        // Manejar el clic en los enlaces
        $('.link-dark').on('click', function(e) {
            e.preventDefault();

            // Obtener el valor del atributo 'data-form'
            var formName = $(this).data('form');

            // Obtener el valor del atributo 'data-id'
            var idValue = $(this).data('id');
            
            var formUrl = '../view/' + formName + '.php?id=' + encodeURIComponent(idValue);

            // Realizar una petición AJAX para cargar el formulario correspondiente
            $.ajax({
                url: formUrl,
                type: 'GET',
                success: function(response) {
                    // Colocar el contenido del formulario en el contenedor
                    $('#contenido').html(response);
                },
                error: function(error) {
                    console.log('Error al cargar el formulario:', error);
                }
            });
        });
    });
</script>