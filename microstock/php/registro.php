<?php

    include 'conexion.php';

    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];
    $contraseña = hash('sha512', $contraseña);  //encriptación de contraseña //


    
    $datos = "INSERT INTO usuario(nombre,correo,usuario,contraseña) VALUES('$nombre','$correo','$usuario','$contraseña')";



    /*no repetir el correo en la base de datos*/
$verificar_correo = mysqli_query($conexion, "SELECT * FROM usuario WHERE correo='$correo' ");
if(mysqli_num_rows($verificar_correo) > 0 ){
    echo'
    <script>
        alert("El correo ya esta registrado, ingrese otro diferente");
        window.location = "../login y registro.php";
    </script>
    ';
    exit(); // no ejecuta lo de debajo si se cumple la condición //
}

    /*no repetir el usuario en la base de datos*/
    $verificar_usuario= mysqli_query($conexion, "SELECT * FROM usuario WHERE usuario='$usuario' ");
    if(mysqli_num_rows($verificar_usuario) > 0 ){
        echo'
        <script>
            alert("Este usuario no esta disponible, ingrese otro diferente");
            window.location = "../login y registro.php";
        </script>
        ';
        exit(); // no ejecuta lo de debajo si se cumple la condición //
    }


    $ejecutar = mysqli_query($conexion, $datos);

    if ($ejecutar) {
        echo '
        <script>
            alert("El usuario fue registrado con éxito");
            window.location = "../login y registro.php";
        </script>
        ';
    } else {
        die("Error al registrar usuario: " . mysqli_error($conexion));
    }   

    mysqli_close($conexion);

?>
