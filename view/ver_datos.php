<?php
    require '../php/conexion.php';
    require '../php/conexion_crm.php';
    require '../php/conexion_crm_bog.php';

    session_start();
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        header('Location: ../');
        exit();
    }
    $nombre = $_SESSION["nombre"];
    $cargo = $_SESSION["cargo"];

    $cun = mysqli_real_escape_string($conn, $_GET['cun']);
    $documento = mysqli_real_escape_string($conn, $_GET['documento']);
    $telefono = mysqli_real_escape_string($conn, $_GET['telefono']);
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $producto = mysqli_real_escape_string($conn, $_GET['producto']);
    $id_venta = mysqli_real_escape_string($conn, $_GET['id_venta']);

    $sql_archivos = "SELECT * FROM archivos WHERE id_pqr = '$id' ORDER BY id DESC";
    $result_archivos = $conn->query($sql_archivos);

    $sql_datos = "SELECT * FROM datos WHERE id = '$id'";
    $result_datos = $conn->query($sql_datos);

    $sql_datos2 = "SELECT * FROM datos WHERE id = '$id'";
    $result_datos2 = $conn->query($sql_datos2);

    $sql_datos3 = "SELECT * FROM datos WHERE id = '$id'";
    $result_datos3 = $conn->query($sql_datos3);

    $sql_historial = "SELECT * FROM historial WHERE id_original = '$id' ORDER BY id DESC";
    $result_historial = $conn->query($sql_historial);

    $sql_legal = "SELECT * FROM legal WHERE id_pqr = '$id' ORDER BY id DESC";
    $result_legal = $conn->query($sql_legal);

    $sql_retroalimentacion = "SELECT * FROM retroalimentacion WHERE id_pqr = '$id' ORDER BY id DESC";
    $result_retroalimentacion = $conn->query($sql_retroalimentacion);

    $sql_crm = "";
    if ($producto === 'Hogar') {
        $sql_crm = "SELECT nombre_asesor, cedula_asesor, Coordinador, Subcampana, Fecha_Venta, Digitador, id_venta FROM exportacion_fija_med WHERE cedula_cliente = ?";
        $stmt = $conn_crm->prepare($sql_crm);
        $stmt->bind_param("s", $documento);
        $stmt->execute();
        $result_crm = $stmt->get_result();
        $stmt->close();
    } elseif ($producto === 'Movil') {
        $sql_crm = "SELECT NOMBRE_ASESOR, CEDULA_ASESOR, SUPERVISOR, CAMPAÑA, Fecha_Venta, BACKOFFICE, Id_venta FROM exportacion_portabilidad_med WHERE LÍNEA_LLAMADA = ?";
        $stmt = $conn_crm->prepare($sql_crm);
        $stmt->bind_param("s", $telefono);
        $stmt->execute();
        $result_crm = $stmt->get_result();
        $stmt->close();
    } else {
        $sql_crm = "SELECT asesor, cedula_asesor, coordinador, subcampana, fechas_ventas, back, id_tecnologia FROM exportacion_tecnologia_bog WHERE id_tecnologia = ?";
        $stmt = $conn_crm_bog->prepare($sql_crm);
        $stmt->bind_param("s", $id_venta);
        $stmt->execute();
        $result_crm = $stmt->get_result();
        $stmt->close();
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
    $conn_crm->close();
?>

<div>
    <!-- boton volver -->
    <button class="Btn_volver">
        <div class="sign">
            <svg viewBox="0 0 512 512">
                <path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path>
            </svg>
        </div>
        <a href="#" style="color: white; text-decoration: none;" data-form="visualizar" class="link-dark rounded">
            <div class="text">Volver</div>
        </a>

    </button>
    <br>

    <!-- tabla de datos -->
    <div style="border: 1px solid #000; padding: 10px; border-radius: 20px;">
        <?php if (isset($result_datos) && $result_datos->num_rows > 0) : ?>
            <h2 style="text-align: center;"> Informacion PQR</h2>
            <table class="table table-bordered" style="margin-top: 10px;">
                <thead>
                    <tr style="text-align: center; text-transform: capitalize; font-size: 14px;">
                        <th scope="col">ID</th>
                        <th scope="col">Fecha recepcion</th>
                        <th scope="col">Nombre cliente</th>
                        <th scope="col">Telefono</th>
                        <th scope="col">Documento</th>
                        <th scope="col">Cun</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($fila = $result_datos->fetch_assoc()) : ?>
                        <tr scope="row" style="text-align: center; text-transform: capitalize; font-size: 14px;">
                            <td><?php echo $fila['id']; ?></a></td>
                            <td><?php echo $fila['fecha_recepcion']; ?></td>
                            <td><?php echo $fila['nombre_cliente']; ?></td>
                            <td><?php echo $fila['telefono']; ?></td>
                            <td><?php echo $fila['documento']; ?></td>
                            <td><?php echo $fila['cun']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else : ?>
        <?php endif; ?>

        <?php if (isset($result_datos2) && $result_datos2->num_rows > 0) : ?>
            <table class="table table-bordered container_datos2" style="margin-top: 10px;">
                <thead>
                    <tr style="text-align: center; text-transform: capitalize; font-size: 14px;">
                        <th scope="col">ESTADO</th>
                        <th scope="col">MOTIVO</th>
                        <th scope="col">ANALISTA</th>
                        <th scope="col">FECHA RESPUESTA</th>
                        <th scope="col">RESPONSABLE</th>
                        <th scope="col">OBSERVACIÓN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($fila = $result_datos2->fetch_assoc()) : ?>
                        <tr scope="row" style="text-align: center; text-transform: capitalize; font-size: 14px;">
                            <td><?php echo $fila['estado']; ?></a></td>
                            <td><?php echo $fila['motivo']; ?></td>
                            <td><?php echo $fila['analista']; ?></td>
                            <td><?php echo $fila['fecha_respuesta']; ?></td>
                            <td><?php echo $fila['responsable']; ?></td>
                            <td>
                                <span class="observacion-preview" data-toggle="modal" data-target="#observacionModal<?php echo $fila['id'] . '_' . time(); ?>">
                                    <?php echo substr($fila['observacion'], 0, 10); ?>
                                </span>
                            </td>
                        </tr>

                        <!-- Modal -->
                        <div class="modal fade" id="observacionModal<?php echo $fila['id'] . '_' . time(); ?>" tabindex="-1" role="dialog" aria-labelledby="observacionModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="observacionModalLabel">Observación Completa</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <?php echo $fila['observacion']; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p style="text-align: center;">No se encontraron registros de los datos</p>
        <?php endif; ?>
    </div>
    <script>
        $(document).ready(function() {
            // Delegación de eventos en el documento para elementos con la clase "observacion-preview"
            $(document).on('click', '.observacion-preview', function() {
                // Obtén el ID único de la ventana modal desde el atributo data-target
                var modalId = $(this).data('target');

                // Abre la ventana modal correspondiente
                $(modalId).modal('show');
            });
        });
    </script>
    <br><br><br>

    <!-- tabla de crm -->
    <div style="border: 1px solid #000; padding: 10px; border-radius: 20px;">
        <?php if (isset($result_crm) && $result_crm->num_rows > 0) : ?>
            <h2 style="text-align: center;">CRM</h2>
            <table class="table table-bordered" style="margin-top: 10px;">
                <thead>
                    <tr style="text-align: center; text-transform: capitalize; font-size: 14px;">
                        <th scope="col">nombre asesor</th>
                        <th scope="col">cedula asesor</th>
                        <th scope="col">supervisor</th>
                        <th scope="col">campaña</th>
                        <th scope="col">Fecha Venta</th>
                        <th scope="col">BaccOffice</th>
                        <th scope="col">Id venta</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($fila = $result_crm->fetch_assoc()) : ?>
                        <tr scope="row" style="text-align: center; text-transform: capitalize; font-size: 14px;">
                            <?php if ($producto === 'Hogar') : ?>
                                <td><?php echo $fila['nombre_asesor']; ?></td>
                                <td><?php echo $fila['cedula_asesor']; ?></td>
                                <td><?php echo $fila['Coordinador']; ?></td>
                                <td><?php echo $fila['Subcampana']; ?></td>
                                <td><?php echo $fila['Fecha_Venta']; ?></td>
                                <td><?php echo $fila['Digitador']; ?></td>
                                <td><?php echo $fila['id_venta']; ?></td>
                            <?php elseif ($producto === 'Movil') : ?>
                                <td><?php echo $fila['NOMBRE_ASESOR']; ?></td>
                                <td><?php echo $fila['CEDULA_ASESOR']; ?></td>
                                <td><?php echo $fila['SUPERVISOR']; ?></td>
                                <td><?php echo $fila['CAMPAÑA']; ?></td>
                                <td><?php echo $fila['Fecha_Venta']; ?></td>
                                <td><?php echo $fila['BACKOFFICE']; ?></td>
                                <td><?php echo $fila['Id_venta']; ?></td>
                            <?php elseif ($producto === 'Terminales') : ?>
                                <td><?php echo $fila['asesor']; ?></td>
                                <td><?php echo $fila['cedula_asesor']; ?></td>
                                <td><?php echo $fila['coordinador']; ?></td>
                                <td><?php echo $fila['subcampana']; ?></td>
                                <td><?php echo $fila['fechas_ventas']; ?></td>
                                <td><?php echo $fila['back']; ?></td>
                                <td><?php echo $fila['id_tecnologia']; ?></td>
                            <?php endif; ?>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else : ?>
            <h2 style="text-align: center;">No se encontraron datos de CRM</h2>
            <br><br>
        <?php endif; ?>
    </div>
    <br><br><br>

    <!-- tabla de retrialimentacion y desicion legal  -->
    <div style="border: 1px solid #000; padding: 10px; border-radius: 20px;">
        <?php if (isset($result_datos3) && $result_datos3->num_rows > 0) : ?>
            <h2 style="text-align: center;">Retroalimentacion y Desicion legal</h2>
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
    </div>
    <br><br><br>

    <!-- Archivos analista -->
    <div style="border: 1px solid #000; padding: 10px; border-radius: 20px;">
        <?php if ($result_archivos && $result_archivos->num_rows > 0) : ?>
            <h2 style="text-align: center;">Evidencia del Analista</h2>
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
    </div>
    <br><br><br>

    <!-- soporte retroalimentacion -->
    <div style="border: 1px solid #000; padding: 10px; border-radius: 20px;">
        <?php if (isset($result_retroalimentacion) && $result_retroalimentacion->num_rows > 0) : ?>
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
    </div>
    <br><br><br>

    <!-- archivos soporte legal  -->
    <div style="border: 1px solid #000; padding: 10px; border-radius: 20px;">
        <?php if (isset($result_legal) && $result_legal->num_rows > 0) : ?>
            <h2 style="text-align: center;">Soporte legal</h2>
            <table class="container_legal table table-bordered" style="max-height: 400px; overflow-y: auto;">
                <thead>
                    <tr style="text-align: center; text-transform: capitalize; font-size: 14px;">
                        <th scope="col">nombre</th>
                        <th scope="col">tipo</th>
                        <th scope="col">fecha</th>
                        <th scope="col">descargar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($fila = $result_legal->fetch_assoc()) : ?>
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
            <h2 style="text-align: center;">Soporte legal</h2>
            <table class="container_legal table table-bordered" style="max-height: 400px; overflow-y: auto;">
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
                        <td colspan="4">Sin datos Cargados.</td>
                    </tr>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <br><br><br>

    <!-- modificar y historial -->
    <div style="display: flex; align-items: flex-start;">
        <?php if ($cargo === 'admin' || $cargo === 'analista_pf') : ?>
            <!-- modificar -->
            <form class="modificar" id="modificar" enctype="multipart/form-data">
                <h2>modificar PQR</h2>
                <?php
                require '../php/conexion.php';
                $sql_datos2 = "SELECT * FROM datos WHERE id = '$id'";
                $result_datos2 = $conn->query($sql_datos2);

                if ($result_datos2 && $result_datos2->num_rows > 0) {
                    $row = $result_datos2->fetch_assoc();
                    $observacion = $row['observacion'];
                }
                ?>
                <div>
                    <label for="" style="font-weight: 500; font-size: 20px; margin-right: 10px;">Estado</label>
                    <select name="estado" id="estado" class="select">
                        <option value="no_procede">NO PROCEDE</option>
                        <option value="procede">PROCEDE</option>
                    </select>
                </div>
                <div>
                    <label for="" style="font-size: 16px; margin-right: 10px; font-weight: 500;">Motivo</label>
                    <select name="motivo" id="motivo" class="select border-red" disabled required>
                        <option value="">Selecciona motivo</option>
                        <option value="direccion_entrega_sin">Dirección entrega de sim</option>
                        <option value="campaña_errada">Campaña errada</option>
                        <option value="direccion_entrega_equipo">Dirección entrega equipo</option>
                        <option value="ciclos_de_facturacion">Ciclos de facturación</option>
                        <option value="negacion_compra">Negación compra</option>
                        <option value="obsequio">Obsequio</option>
                        <option value="doble_facturacion">Doble facturación</option>
                        <option value="cobro_domicilio">Cobro domicilio</option>
                        <option value="descuento_factura">Descuento factura</option>
                        <option value="digitacion_errada">Digitacion errada</option>
                    </select>
                </div>
                <div style="margin-top: 10px; display: none;">
                    <label for="" style="font-weight: 500; font-size: 16px; margin-right: 10px;">Analista a cargo</label>
                    <input name="analista_a_cargo" type="text" style="text-align: center; width: auto;" value="<?php echo $nombre; ?>" readonly>
                </div>
                <div>
                    <label for="" style="font-size: 20px; margin-right: 10px; font-weight: 500;">Responsable</label>
                    <select name="responsable" id="responsable" class="select border-red" disabled>
                        <option value="asesor">Asesor</option>
                        <option value="back">Backoffice</option>
                        <option value="ambos">Ambos</option>
                    </select>
                </div>
                <textarea name="observacion" id="observacion" cols="40" rows="5" class="textarea border-red" style="margin-top: 10px;" placeholder="Observacion" disabled><?php echo $observacion; ?></textarea>

                <div style="display: flex;">
                    <input class="file-input border-red" disabled type="file" id="file-input" name="archivos[]" id="archivos" accept="image/*,audio/*" multiple>
                </div>
                <button type="submit" class="btn_modificar" id="btn_modificar">Guardar</button>
            </form>
        <?php else : ?>
            <form style="display: none;" class="modificar" id="modificar" enctype="multipart/form-data">
                <h2>modificar PQR</h2>
                <?php
                require '../php/conexion.php';
                $sql_datos2 = "SELECT * FROM datos WHERE id = '$id'";
                $result_datos2 = $conn->query($sql_datos2);

                if ($result_datos2 && $result_datos2->num_rows > 0) {
                    $row = $result_datos2->fetch_assoc();
                    $observacion = $row['observacion'];
                }
                ?>
                <div>
                    <label for="" style="font-weight: 500; font-size: 20px; margin-right: 10px;">Estado</label>
                    <select name="estado" id="estado" class="select">
                        <option value="no_procede">NO PROCEDE</option>
                        <option value="procede">PROCEDE</option>
                    </select>
                </div>
                <div>
                    <label for="" style="font-size: 16px; margin-right: 10px; font-weight: 500;">Motivo</label>
                    <select name="motivo" id="motivo" class="select border-red" disabled required>
                        <option value="">Selecciona motivo</option>
                        <option value="direccion_entrega_sin">Dirección entrega de sim</option>
                        <option value="campaña_arrada">Campaña errada</option>
                        <option value="direccion_entrega_equipo">Dirección entrega equipo</option>
                        <option value="ciclos_de_facturacion">Ciclos de facturación</option>
                        <option value="negacion_compra">Negación compra</option>
                        <option value="obsequi">Obsequio</option>
                        <option value="doble_facturacion">Doble facturación</option>
                        <option value="cobro_domicilio">Cobro domicilio</option>
                        <option value="descuento_factura">Descuento factura</option>
                        <option value="digitacion_errada">Digitacion errada</option>
                    </select>
                </div>
                <div style="margin-top: 10px; display: none;">
                    <label for="" style="font-weight: 500; font-size: 16px; margin-right: 10px;">Analista a cargo</label>
                    <input name="analista_a_cargo" type="text" style="text-align: center; width: auto;" value="<?php echo $nombre; ?>" readonly>
                </div>
                <div>
                    <label for="" style="font-size: 20px; margin-right: 10px; font-weight: 500;">Responsable</label>
                    <select name="responsable" id="responsable" class="select border-red" disabled>
                        <option value="asesor">Asesor</option>
                        <option value="back">Backoffice</option>
                        <option value="ambos">Ambos</option>
                    </select>
                </div>
                <textarea name="observacion" id="observacion" cols="40" rows="5" class="textarea border-red" style="margin-top: 10px;" placeholder="Observacion" disabled><?php echo $observacion; ?></textarea>

                <div style="display: flex;">
                    <input class="file-input border-red" disabled type="file" id="file-input" name="archivos[]" id="archivos" accept="image/*,audio/*" multiple>
                </div>
                <button type="submit" class="btn_modificar" id="btn_modificar">Guardar</button>
            </form>
        <?php endif; ?>

        <!-- tabla de historial -->
        <div style="margin-left: 5px; max-height: 400px; overflow: auto;">
            <h3 style="text-align: center;">Historial de modificaciones</h3>
            <?php if (isset($result_historial) && $result_historial->num_rows > 0) : ?>
                <table class="container_historial table table-bordered" style="max-height: 400px; overflow-y: auto;">
                    <thead>
                        <tr style="text-align: center; text-transform: capitalize; font-size: 14px;">
                            <th scope="col">estado</th>
                            <th scope="col">motivo</th>
                            <th scope="col">analista</th>
                            <th scope="col">Responsable</th>
                            <th scope="col">Observación</th>
                            <th scope="col">Fecha Respuesta</th>
                            <th scope="col">Usuario modificacion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fila = $result_historial->fetch_assoc()) : ?>
                            <tr scope="row" style="text-align: center; text-transform: capitalize; font-size: 14px;">
                                <td><?php echo $fila['estado']; ?></td>
                                <td><?php echo $fila['motivo']; ?></td>
                                <td><?php echo $fila['analista']; ?></td>
                                <td><?php echo $fila['responsable']; ?></td>
                                <td>
                                    <span class="observacion-preview" data-toggle="modal" data-target="#observacionModal<?php echo $fila['id']; ?>">
                                        <?php echo substr($fila['observacion'], 0, 10); ?>
                                    </span>
                                </td>
                                <td><?php echo $fila['fecha_modificacion']; ?></td>
                                <td><?php echo $fila['usuario_modificacion']; ?></td>

                            </tr>

                            <!-- Modal -->
                            <div class="modal fade" id="observacionModal<?php echo $fila['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="observacionModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content" style="background: white;">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="observacionModalLabel">Observación Completa</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <?php echo $fila['observacion']; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <table class="container_historial table table-bordered" style="max-height: 400px; overflow-y: auto;">
                    <thead>
                        <tr style="text-align: center; text-transform: capitalize; font-size: 14px;">
                            <th scope="col">estado</th>
                            <th scope="col">motivo</th>
                            <th scope="col">analista</th>
                            <th scope="col">Responsable</th>
                            <th scope="col">Observación</th>
                            <th scope="col">Fecha Respuesta</th>
                            <th scope="col">Usuario modificacion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr scope="row" style="text-align: center; text-transform: capitalize; font-size: 14px;">
                            <td colspan="7">Sin Modificaciones</td>
                        </tr>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#estado').change(function() {
                var estadoSeleccionado = $(this).val();
                var motivoSelect = $('#motivo');
                var responsableSelect = $('#responsable');
                var textarea = $('#observacion');
                var input = $('#file-input');

                if (estadoSeleccionado === 'procede') {
                    motivoSelect.prop('disabled', false).addClass('border-green').removeClass('border-red');
                    responsableSelect.prop('disabled', false).addClass('border-green').removeClass('border-red');
                    textarea.prop('disabled', false).addClass('border-green').removeClass('border-red');
                    input.prop('disabled', false).addClass('border-green').removeClass('border-red');

                } else {
                    motivoSelect.prop('disabled', true).addClass('border-red').removeClass('border-green');
                    responsableSelect.prop('disabled', true).addClass('border-red').removeClass('border-green');
                    textarea.prop('disabled', true).addClass('border-red').removeClass('border-green');
                    input.prop('disabled', true).addClass('border-red').removeClass('border-green');
                }
            });
        });
    </script>
    <br><br><br>

    <!-- retroalimentacion y legal -->
    <div style="display: flex; align-items: flex-start;">
        <?php if ($cargo === 'admin' || $cargo === 'supervisor') : ?>
            <!-- form retroalimentacion -->
            <form class="modificar" id="form_retroalimentacion" style="margin-top: 10px ;" enctype="multipart/form-data">
                <div>
                    <label for="" style="font-weight: 500; font-size: 20px; margin-right: 10px;">Estado Retroalimentacion</label>
                    <select name="retroalimentacion" id="retroalimentacion" class="select">
                        <option value="no_aplica_legal">NO APLICA LEGAL</option>
                        <option value="aplica_legal">APLICA LEGAL</option>
                    </select>
                    <input class="file-input" type="file" id="file_input_retroalimentacion" name="retroalimentacion[]" accept="image/*,application/pdf" multiple>
                </div>
                <button type="submit" class="btn_modificar" id="btn_retroalimentacion">Guardar</button>
            </form>
        <?php else : ?>
            <!-- form retroalimentacion -->
            <form class="modificar" id="form_retroalimentacion" style="margin-top: 10px; display: none;" enctype="multipart/form-data">
                <div>
                    <label for="" style="font-weight: 500; font-size: 20px; margin-right: 10px;">Estado Retroalimentacion</label>
                    <select name="retroalimentacion" id="retroalimentacion" class="select">
                        <option value="no_aplica_legal">NO APLICA LEGAL</option>
                        <option value="aplica_legal">APLICA LEGAL</option>
                    </select>
                    <input class="file-input" type="file" id="file_input_retroalimentacion" name="retroalimentacion[]" accept="image/*,application/pdf" multiple>
                </div>
                <button type="submit" class="btn_modificar" id="btn_retroalimentacion">Guardar</button>
            </form>
        <?php endif; ?>

        <?php if ($cargo === 'admin' || $cargo === 'legal') : ?>
            <!-- form legal -->
            <form class="modificar" id="form_legal" style="margin-top: 10px; margin-left: 100px;" enctype="multipart/form-data">
                <div>
                    <label for="" style="font-weight: 500; font-size: 20px; margin-right: 10px;">Decisión legal</label>
                    <select name="desicion" id="desicion" class="select">
                        <option value="no_aplica">No Aplica</option>
                        <option value="suspension">Suspension</option>
                        <option value="llamado_de_atencion">Llamado de atencion</option>
                        <option value="terminacion_de_contrato">Terminacion de contrato</option>
                    </select>
                    <input class="file-input" type="file" id="file_input_legal" name="legal[]" accept="image/*,application/pdf" multiple>
                </div>
                <button type="submit" class="btn_modificar" id="btn_legal">Guardar</button>
            </form>
        <?php else : ?>
            <!-- form legal -->
            <form class="modificar" id="form_legal" style="margin-top: 10px; margin-left: 100px; display: none;" enctype="multipart/form-data">
                <div>
                    <label for="" style="font-weight: 500; font-size: 20px; margin-right: 10px;">Decisión legal</label>
                    <select name="desicion" id="desicion" class="select">
                        <option value="no_aplica">No Aplica</option>
                        <option value="suspension">Suspension</option>
                        <option value="llamado_de_atencion">Llamado de atencion</option>
                        <option value="terminacion_de_contrato">Terminacion de contrato</option>
                    </select>
                    <input class="file-input" type="file" id="file_input_legal" name="legal[]" accept="image/*,application/pdf" multiple>
                </div>
                <button type="submit" class="btn_modificar" id="btn_legal">Guardar</button>
            </form>
        <?php endif; ?>
    </div>
    <br><br><br>
</div>

<script>
    //actualizar tabla archivos
    function actualizarTabla() {
        // Obtener los valores de los parámetros
        var id = '<?php echo $id; ?>';
        var documento = '<?php echo $documento; ?>';
        var telefono = '<?php echo $telefono; ?>';

        // Utilizar AJAX para obtener los nuevos datos sin recargar la página
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Actualizar el contenido de la clase "container_img" con los nuevos datos
                document.querySelector('.container_img').innerHTML = this.responseText;
            }
        };

        // Construir la URL con los parámetros
        var url = "../php/refresh_img.php?id=" + encodeURIComponent(id) + "&documento=" + encodeURIComponent(documento) + "&telefono=" + encodeURIComponent(telefono);

        // Realizar una solicitud GET al script PHP con los parámetros en la URL
        xmlhttp.open("GET", url, true);
        xmlhttp.send();
    }

    //actualizar tabla retroalimentacion
    function actualizarTabla2() {
        // Obtener los valores de los parámetros
        var id = '<?php echo $id; ?>';
        var documento = '<?php echo $documento; ?>';
        var telefono = '<?php echo $telefono; ?>';

        // Utilizar AJAX para obtener los nuevos datos sin recargar la página
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.querySelector('.container_retroalimentacion').innerHTML = this.responseText;
            }
        };

        // Construir la URL con los parámetros
        var url = "../php/refresh_retroalimentacion.php?id=" + encodeURIComponent(id) + "&documento=" + encodeURIComponent(documento) + "&telefono=" + encodeURIComponent(telefono);

        // Realizar una solicitud GET al script PHP con los parámetros en la URL
        xmlhttp.open("GET", url, true);
        xmlhttp.send();
    }

    //actualizar tabla historial
    function actualizarTabla3() {
        // Obtener los valores de los parámetros
        var cun = '<?php echo $cun; ?>';
        var documento = '<?php echo $documento; ?>';
        var telefono = '<?php echo $telefono; ?>';
        var id = '<?php echo $id; ?>';

        // Utilizar AJAX para obtener los nuevos datos sin recargar la página
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Actualizar el contenido de la clase "container_audio" con los nuevos datos
                document.querySelector('.container_historial').innerHTML = this.responseText;
            }
        };

        // Construir la URL con los parámetros
        var url = "../php/refresh_historial.php?cun=" + encodeURIComponent(cun) + "&documento=" + encodeURIComponent(documento) + "&telefono=" + encodeURIComponent(telefono) + "&id=" + encodeURIComponent(id);

        // Realizar una solicitud GET al script PHP con los parámetros en la URL
        xmlhttp.open("GET", url, true);
        xmlhttp.send();
    }

    //actualizar tabla datos2
    function actualizarTabla4() {
        // Obtener los valores de los parámetros
        var cun = '<?php echo $cun; ?>';
        var documento = '<?php echo $documento; ?>';
        var telefono = '<?php echo $telefono; ?>';
        var id = '<?php echo $id; ?>';

        // Utilizar AJAX para obtener los nuevos datos sin recargar la página
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Actualizar el contenido de la clase "container_audio" con los nuevos datos
                document.querySelector('.container_datos2').innerHTML = this.responseText;
            }
        };

        // Construir la URL con los parámetros
        var url = "../php/refresh_datos.php?cun=" + encodeURIComponent(cun) + "&documento=" + encodeURIComponent(documento) + "&telefono=" + encodeURIComponent(telefono) + "&id=" + encodeURIComponent(id);

        // Realizar una solicitud GET al script PHP con los parámetros en la URL
        xmlhttp.open("GET", url, true);
        xmlhttp.send();
    }

    //actualizar textarea
    function actualizarTabla5() {
        // Obtener los valores de los parámetros
        var cun = '<?php echo $cun; ?>';
        var documento = '<?php echo $documento; ?>';
        var telefono = '<?php echo $telefono; ?>';
        var id = '<?php echo $id; ?>';

        // Utilizar AJAX para obtener los nuevos datos sin recargar la página
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Actualizar el contenido de la clase "container_audio" con los nuevos datos
                document.querySelector('.textarea').innerHTML = this.responseText;
            }
        };

        // Construir la URL con los parámetros
        var url = "../php/refresh_textarea.php?cun=" + encodeURIComponent(cun) + "&documento=" + encodeURIComponent(documento) + "&telefono=" + encodeURIComponent(telefono) + "&id=" + encodeURIComponent(id);

        // Realizar una solicitud GET al script PHP con los parámetros en la URL
        xmlhttp.open("GET", url, true);
        xmlhttp.send();
    }

    //actualizar tabla legal
    function actualizarTabla6() {
        // Obtener los valores de los parámetros
        var cun = '<?php echo $cun; ?>';
        var documento = '<?php echo $documento; ?>';
        var telefono = '<?php echo $telefono; ?>';
        var id = '<?php echo $id; ?>';

        // Utilizar AJAX para obtener los nuevos datos sin recargar la página
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Actualizar el contenido de la clase "container_audio" con los nuevos datos
                document.querySelector('.container_legal').innerHTML = this.responseText;
            }
        };

        // Construir la URL con los parámetros
        var url = "../php/refresh_legal.php?cun=" + encodeURIComponent(cun) + "&documento=" + encodeURIComponent(documento) + "&telefono=" + encodeURIComponent(telefono) + "&id=" + encodeURIComponent(id);

        // Realizar una solicitud GET al script PHP con los parámetros en la URL
        xmlhttp.open("GET", url, true);
        xmlhttp.send();
    }

    //actualizar tabla datos3
    function actualizarTabla7() {
        // Obtener los valores de los parámetros
        var cun = '<?php echo $cun; ?>';
        var documento = '<?php echo $documento; ?>';
        var telefono = '<?php echo $telefono; ?>';
        var id = '<?php echo $id; ?>';

        // Utilizar AJAX para obtener los nuevos datos sin recargar la página
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.querySelector('.container_datos3').innerHTML = this.responseText;
            }
        };

        // Construir la URL con los parámetros
        var url = "../php/refresh_datos2.php?cun=" + encodeURIComponent(cun) + "&documento=" + encodeURIComponent(documento) + "&telefono=" + encodeURIComponent(telefono) + "&id=" + encodeURIComponent(id);

        // Realizar una solicitud GET al script PHP con los parámetros en la URL
        xmlhttp.open("GET", url, true);
        xmlhttp.send();
    }

    //actualizacion multiple
    function actualizacionMultiple() {
        actualizarTabla3();
        actualizarTabla4();
        actualizarTabla5();
        actualizarTabla();
    }

    //limpiar el form modificar
    function limpiar() {
        // Limpiar campos y deshabilitar selects
        $('#estado').val('no_procede');
        $('#file-input').val('').addClass('border-red').removeClass('border-green').prop('disabled', true);
        $('#motivo, #observacion, #responsable').addClass('border-red').removeClass('border-green').prop('disabled', true);
    }

    //limpiar el form retroalimentacion
    function limpiar2() {
        $('#retroalimentacion').val('no_aplica_legal');
        $('#file_input_retroalimentacion').val('');
    }

    //limpiar el form legal
    function limpiar3() {
        $('#legal').val('no_aplica');
        $('#file_input_legal').val('');
    }

    //Jquerry
    $(document).ready(function() {
        // Manejar el clic en los enlaces
        $('.link-dark').on('click', function(e) {
            e.preventDefault();

            // Obtener el valor del atributo 'data-form'
            var formName = $(this).data('form');

            // Realizar una petición AJAX para cargar el formulario correspondiente
            $.ajax({
                url: formName + '.php', // Asumiendo que tus archivos se llaman overview.php, updates.php, reports.php, etc.
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

        // Manejar el form modificar
        $('#modificar').submit(function(e) {
            e.preventDefault(); // Evitar que el formulario se envíe tradicionalmente

            // Obtener datos del formulario
            var formData = new FormData(this);
            formData.append('id', <?php echo json_encode($_GET['id']); ?>);

            // Realizar una petición AJAX para insertar el cliente en la base de datos
            $.ajax({
                url: '../php/modificar_pqr.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Good job!',
                        text: 'PQR modificada correctamente',
                        onClose: function() {

                        }
                    });
                    limpiar();
                    actualizacionMultiple();
                },
                error: function(error) {
                    // Mostrar la alerta de error
                    Swal.fire({
                        icon: 'error',
                        title: 'ERROR',
                        text: 'Error al modificar el PQR: ' + error.responseText
                    });
                    limpiar();
                }
            });
        });

        // Manejar el form legal
        $('#form_legal').submit(function(e) {
            e.preventDefault(); // Evitar que el formulario se envíe tradicionalmente

            // Obtener datos del formulario
            var formData = new FormData(this);
            formData.append('id', <?php echo json_encode($_GET['id']); ?>);

            // Realizar una petición AJAX para insertar el cliente en la base de datos
            $.ajax({
                url: '../php/legal.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    response = JSON.parse(response);
                    if (response.status === "success") {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Bien hecho!',
                            text: response.message,
                            onClose: function() {}
                        });
                        limpiar3();
                        actualizarTabla7();
                        actualizarTabla6();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'ERROR',
                            text: response.message
                        });
                        limpiar3();
                        actualizarTabla7();
                        actualizarTabla6();
                    }
                },
            });
        });

        // Manejar el form retroalimentacion
        $('#form_retroalimentacion').submit(function(e) {
            e.preventDefault(); // Evitar que el formulario se envíe tradicionalmente

            // Obtener datos del formulario
            var formData = new FormData(this);
            formData.append('id', <?php echo json_encode($_GET['id']); ?>);

            // Realizar una petición AJAX para insertar el cliente en la base de datos
            $.ajax({
                url: '../php/retroalimentacion.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    response = JSON.parse(response);
                    if (response.status === "success") {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Bien hecho!',
                            text: response.message,
                            onClose: function() {}
                        });
                        limpiar2();
                        actualizarTabla7();
                        actualizarTabla2();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'ERROR',
                            text: response.message
                        });
                        limpiar2();
                        actualizarTabla7();
                        actualizarTabla2();
                    }
                },
            });
        });
    });
</script>