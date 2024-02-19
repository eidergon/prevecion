<?php
$servername = "74.48.56.221";
$username = "simpplee";
$password = "Stn1990.";
$database = "oc_bogota";

// Crear la conexión
$conn_crm_bog = mysqli_connect($servername, $username, $password, $database);

// Verificar la conexión
if (!$conn_crm_bog) {
  die("Error de conexión: " . mysqli_connect_error());
}

// Establecer la codificación de caracteres
mysqli_set_charset($conn_crm_bog, "utf8");

?>
