<?php session_start(); include("conect/conexion.php");?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8"/>
    <title>Pefil</title>
    <link rel="stylesheet" type="text/css" href="css/index.css" title="Versión normal">
    <link rel="alternate stylesheet" type="text/css" href="css/acc.css" title="Estilo accesible">
    <link rel="alternate stylesheet" type="text/css" href="css/imprimir.css" media="screen" title="Estilo de impresión">
</head>

<body>
	<?php if(!isset($_SESSION["log"])){$_SESSION["log"]=false;}
		if($_SESSION['log']==true){include("inc/header.php");
		}else{include("inc/header_no.php");}?>
	

    <main class="perfil">
    <a href="mis_datos.php">Editar perfil </a>
    <?php      
        $id=$_SESSION["id"];
        $sentencia= "SELECT * FROM usuarios, paises WHERE usuarios.pais=paises.IdPais and usuarios.IdUsuario=" .$id."";
        $resultado = mysqli_query($conexion, $sentencia);
        if($resultado){
            $fila=mysqli_fetch_assoc($resultado); 
            echo '
            <form class="album-form">
            <legend>Datos personales:</legend>
            <picture><img hspace="40" src="usuarios/'.$fila["Foto"].'"></picture>
            <script>console.log('.$fila["Foto"].');</script>
            <p>
            <b>Nombre:</b> '.$fila["NomUsuario"].'
            </p>
            <p>
            <b>Correo electrónico:</b> '.$fila["Email"].'
            </p>
            <p>
            <b>Pais:</b> '.$fila["NomPais"].'
            </p>
            <p>
            <b>Ciudad:</b> '.$fila["Ciudad"].'
            </p>
            <p>
            <b>Sexo:</b> '; 

            if($fila["Sexo"]==1){echo 'Hombre';}else{echo 'Mujer';}

            echo'
            </p>
            <p>
            <b>Nacimiento:</b> '.$fila["FNacimiento"].'
            </p>
            <p>
            <b>Edad: </b>';

            list($Y,$m,$d) = explode("-",$fila["FNacimiento"]);
            echo ( date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y );

            echo '
            </p>
            </form>
            ';
        }
    ?>


	
        <form class="album-form">
            <legend>Últimas fotos:</legend>
                <?php  
                    $sentencia = "SELECT IdFoto, Fichero FROM fotos, albumes WHERE albumes.IdAlbum=fotos.Album and albumes.usuario='" .$_SESSION["id"]. "' ORDER BY FRegistro DESC limit 4";
                    if($resultado = mysqli_query($conexion, $sentencia)){
                        while($fila=mysqli_fetch_assoc($resultado)){
                            echo "<a href='detalle.php?id=".$fila['IdFoto']."'><img hspace='40' src='usuarios/".$fila["Fichero"]."' style='width:160px;height:90px;'><a>";
                        }
                    }
                ?>
            </form>    
            <form class="album-form">
            <legend>Álbumes:</legend>
                <?php  
                    $sentencia = "SELECT IdAlbum FROM albumes WHERE albumes.usuario='" .$_SESSION["id"]. "'";
                    if($resultado = mysqli_query($conexion, $sentencia)){
                        while($fila=mysqli_fetch_assoc($resultado)){
                            $sentencia2 = "SELECT IdFoto, Fichero FROM fotos WHERE ".$fila["IdAlbum"]."=fotos.Album ORDER BY FRegistro DESC limit 1";
                            if($resultado2 = mysqli_query($conexion, $sentencia2)){
                                if($fila2=mysqli_fetch_assoc($resultado2)){
                                    echo "<a href='album.php?id=".$fila['IdAlbum']."'><img hspace='40' src='usuarios/".$fila2["Fichero"]."' style='width:160px;height:90px;'><a>";
                                }else{
                                    echo "<a href='album.php?id=".$fila['IdAlbum']."'><img hspace='40' src='img/model.png' style='width:160px;height:90px;'><a>";
                                }
                            }                           
                        }
                    }
                ?>
        </form>
	</main>

	<?php include("inc/footer.inc"); ?>

</body>
</html>