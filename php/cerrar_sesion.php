<?php
session_start();

// Destruir la sesión actual
session_destroy();

// Redirigir al usuario a la página de inicio de sesión o a cualquier otra página deseada
header('Location: ../'); // Cambia "index.php" al archivo al que quieras redirigir al usuario
exit();
?>
