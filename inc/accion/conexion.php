<?php
    $host = "localhost";
    #local
    $user = "root";
    $clave = "";
    $bd = "dbmuebleria";
    #para el servidor
    #$user = "id17012782_user_dbmuebleria";
    #$clave = "Ga>lU3ovqV&sj$FU";
    #$bd = "id17012782_dbmuebleria";

    $conexion = mysqli_connect($host,$user,$clave,$bd);
    if (mysqli_connect_errno()){
        echo "No se pudo conectar a la base de datos";
        //header("Location: pagemantenimiento.php");
        exit();
    }

    mysqli_select_db($conexion,$bd) or die("No se encuentra la base de datos");

    mysqli_set_charset($conexion,"utf8");


?>
