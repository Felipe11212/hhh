<?php
session_start();
session_destroy(); // Destruir la sesión
header("Location: ../login y registro.php"); // Redirigir a la página de inicio de sesión
exit();
?>