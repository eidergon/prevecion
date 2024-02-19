<?php
$servername = "74.48.56.221";
$username = "simpplee";
$password = "Stn1990.";
$database = "oc_medellin";

// Crear la conexión
$conn_crm = mysqli_connect($servername, $username, $password, $database);

// Verificar la conexión
if (!$conn_crm) {
  die("Error de conexión: " . mysqli_connect_error());
}

// Establecer la codificación de caracteres
mysqli_set_charset($conn_crm, "utf8");

?>
