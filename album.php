<?php session_start(); include("conect/conexion.php");?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8"/>
    <title>Album</title>
    <link rel="stylesheet" type="text/css" href="css/index.css" title="Versión normal">
    <link rel="alternate stylesheet" type="text/css" href="css/acc.css" title="Estilo accesible">
    <link rel="alternate stylesheet" type="text/css" href="css/imprimir.css" media="screen" title="Estilo de impresión">
</head>

<body>
	<?php if(!isset($_SESSION["log"])){$_SESSION["log"]=false;}
		if($_SESSION['log']==true){include("inc/header.php");
		}else{include("inc/header_no.php");}?>
	<main>
	<?php
    $id=$_GET["id"];
    $sentencia= 'SELECT f.IdFoto id, f.Titulo titulo, f.Fichero fichero, f.Pais pais, f.Fecha fecha FROM fotos f, albumes a WHERE a.IdAlbum=f.album and a.IdAlbum= ' .$id. ' ORDER BY FRegistro';
    $resultado = mysqli_query($conexion, $sentencia);
    while($fila=mysqli_fetch_assoc($resultado)){

        $sentencia2='SELECT NomPais FROM paises WHERE IdPais='.$fila["pais"]. '';
        $nompais="";
        if($resultado2 = mysqli_query($conexion, $sentencia2)){
            if($fila2 = mysqli_fetch_assoc($resultado2)){
                $nompais=$fila2["NomPais"];
            }
        }







        echo "<article>
                <figure>
                    <a href='detalle.php?id=" .$fila["id"]. "'>
                        <img atl=" .$fila["titulo"]. " src='usuarios/" .$fila["fichero"]."'/> 
                    </a>
                </figure>
                <p>
                    <b>Título: " .$fila["titulo"]. " </b>
                </p>
                <p>
                    <b>País: " .$nompais. " </b>
                </p>
                <p>
                    <b>Fecha: " .$fila["fecha"]. " </b>
                </p>
            </article>";
    }
    mysqli_free_result($resultado);
?>
</main>
	<footer>
		<a href="">Contacto</a>
		<a href="">Ayuda</a>
		<a href="">Idioma</a>
	</footer>

</body>
</html>