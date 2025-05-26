<?php

session_start();

include 'conexion.php';

$usuario=$_POST['usuario'];
$contraseña=$_POST['contraseña'];
$contraseña= hash('sha512', $contraseña);  // encripatación de la contraseña

$validar_login = mysqli_query($conexion, "SELECT * FROM usuario WHERE usuario='$usuario' and contraseña='$contraseña' ");

if(mysqli_num_rows($validar_login) > 0){
    $_SESSION['usuario'] = $usuario;   // variable de sesion 
    header("location: ../preuba_inicio.php");
    exit;
}else{
    echo'
        <script>
            alert("El usario no existe, por favor verifique los datos ingresados");
            window.location = "../login y registro.php";
        </script>
    ';
    exit;
}



?>