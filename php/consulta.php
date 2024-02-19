<?php
require 'conexion.php';

$sql = "";
$whereClauses = array();

if (isset($_POST['busqueda'])) {
    $termino = $_POST['busqueda'];

    if (isset($_POST['complemento_cun'])) {
        $whereClauses[] = "cun = '$termino'";
    }

    if (isset($_POST['complemento_documento'])) {
        $whereClauses[] = "documento = '$termino'";
    }

    if (isset($_POST['complemento_telefono'])) {
        $whereClauses[] = "telefono = '$termino'";
    }

    // Construir la consulta SQL
    if (!empty($whereClauses)) {
        $sql = "SELECT * FROM datos WHERE " . implode(" OR ", $whereClauses);
        $result = $conn->query($sql);
    }
}

$conn->close();
?>

<?php if (isset($result) && $result->num_rows > 0) : ?>
    <table class="table table-bordered" style="margin-top: 10px;">
        <thead>
            <tr style="text-align: center;">
                <th scope="col">ID</th>
                <th scope="col">Fecha recepcion</th>
                <th scope="col">Nombre cliente</th>
                <th scope="col">Telefono</th>
                <th scope="col">Documento</th>
                <th scope="col">Cun</th>
                <th scope="col">Producto</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($fila = $result->fetch_assoc()) : ?>
                <tr scope="row" style="text-align: center;">
                    <td><a href="#" style="text-decoration: none; color: #0059FF; font-weight: 700;" data-form="ver_datos" data-cun="<?php echo $fila['cun'];?>" data-documento="<?php echo $fila['documento'];?>" data-telefono="<?php echo $fila['telefono'];?>" data-id="<?php echo $fila['id'];?>" data-producto="<?php echo $fila['producto'];?>" data-idventa="<?php echo $fila['id_venta'];?>" class="link-dark rounded"><?php echo $fila['id']; ?></a></td>
                    <td><?php echo $fila['fecha_recepcion']; ?></td>
                    <td><?php echo $fila['nombre_cliente']; ?></td>
                    <td><?php echo $fila['telefono']; ?></td>
                    <td><?php echo $fila['documento']; ?></td>
                    <td><?php echo $fila['cun']; ?></td>
                    <td><?php echo $fila['producto']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else : ?>
    <p style="text-align: center;">No se encontraron registros</p>
<?php endif; ?>

<script>
    $(document).ready(function() {
        // Manejar el clic en los enlaces
        $('.link-dark').on('click', function(e) {
            e.preventDefault();

            // Obtener el valor del atributo 'data-form'
            var formName = $(this).data('form');

            // Obtener el valor del atributo 'data-cun'
            var cunValue = $(this).data('cun');

            // Obtener el valor del atributo 'data-telefono'
            var telefonoValue = $(this).data('telefono');

            // Obtener el valor del atributo 'data-documento'
            var documentoValue = $(this).data('documento');

            // Obtener el valor del atributo 'data-id'
            var idValue = $(this).data('id');

            // Obtener el valor del atributo 'data-idventa'
            var idVentaValue = $(this).data('idventa');

            // Obtener el valor del atributo 'data-producto'
            var productoValue = $(this).data('producto');
            
            // Construir la ruta del formulario en la carpeta "formularios" y pasar los parámetros 'cun', 'telefono' y 'documento' en la URL
            var formUrl = '../view/' + formName + '.php?cun=' + encodeURIComponent(cunValue) + '&telefono=' + encodeURIComponent(telefonoValue) + '&documento=' + encodeURIComponent(documentoValue) + '&id=' + encodeURIComponent(idValue) + '&producto=' + encodeURIComponent(productoValue) + '&id_venta=' + encodeURIComponent(idVentaValue);

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

