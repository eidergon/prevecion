<?php
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST["id"];

    $habeas_data = '';
    if (isset($_POST["habeas_data"])) {
        $habeas_data .= 1;
    } else {
        $habeas_data .= 0;
    }

    $ley_2300 = '';
    if (isset($_POST["ley_2300"])) {
        $ley_2300 .= 1;
    } else {
        $ley_2300 .= 0;
    }

    $mala_gestion = '';
    if (isset($_POST["mala_gestion"])) {
        $mala_gestion .= 1;
    } else {
        $mala_gestion .= 0;
    }

    $suplantacion = '';
    if (isset($_POST["suplantacion"])) {
        $suplantacion .= 1;
    } else {
        $suplantacion .= 0;
    }

    $lectura_contrato = '';
    if (isset($_POST["lectura_contrato"])) {
        $lectura_contrato .= 1;
    } else {
        $lectura_contrato .= 0;
    }

    $sql = "UPDATE auditoria SET habeas_data = '$habeas_data', ley_2300 = '$ley_2300', mala_gestion = '$mala_gestion', suplantacion = '$suplantacion', lectura_contrato = '$lectura_contrato' WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo json_encode(array("status" => "success", "message" => "Check list agregado"));
    } else {
        echo json_encode(array("status" => "error", "message" => "La Check list no se agregó"));
    }
}
