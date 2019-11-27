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
       <?php 
            $sentencia= 'SELECT Titulo, Descripcion, IdAlbum FROM albumes a, usuarios u WHERE u.IdUsuario=a.usuario and u.IdUsuario=' .$_SESSION["id"]. '';
            
            $resultado = mysqli_query($conexion, $sentencia);
            while($fila=mysqli_fetch_assoc($resultado)){
                $sentencia2='SELECT Fichero FROM fotos f WHERE f.Album=' .$fila["IdAlbum"]. ' LIMIT 1';
                $resultado2 = mysqli_query($conexion, $sentencia2);
                $fila2 = mysqli_fetch_assoc($resultado2);
 
                 if($fila2["Fichero"]!=""){
                     $marco = imagecreatefromjpeg("img/marco.jpg");
                     $porciones =explode("/", $fila2["Fichero"]);
                     if(substr($fila2["Fichero"], -3, 3)=="jpg"){
                        $foto = imagecreatefromjpeg("usuarios/" .$fila2["Fichero"]."");
                        imagecopyresampled($marco, $foto, 150, 152, 0, 0, (854 - 150 + 1), (709 - 152 + 1), imagesx($foto), imagesy($foto));
                        imagejpeg($marco, "tmp/".$porciones[1]."");
                     }elseif(substr($fila2["Fichero"], -3, 3)=="png"){
                        $foto = imagecreatefrompng("usuarios/" .$fila2["Fichero"]."");
                        imagecopyresampled($marco, $foto, 150, 152, 0, 0, (854 - 150 + 1), (709 - 152 + 1), imagesx($foto), imagesy($foto));
                        imagepng($marco, "tmp/".$porciones[1]."");                    
                    }
                     echo '<article>
                                <figure>
                                    <a href="album.php?id=' .$fila['IdAlbum']. '">
                                        <img atl=' .$fila["Titulo"]. ' src="tmp/'.$porciones[1].'"/> 
                                     </a>
                                    <p><b>Album: ' .$fila["Titulo"].'</b></p>
                                    <p> Descripcion:'.$fila["Descripcion"].'<p>
                                </figure>
                            </article>';
                }else{
                    echo '<article>
                            <figure>
                                <a href="album.php?id=' .$fila['IdAlbum']. '">
                                    <img atl=' .$fila["Titulo"]. ' src="img/marco.jpg"/> 
                                 </a>
                                <p><b>Album: ' .$fila["Titulo"].'</b></p>
                                <p> Descripcion:'.$fila["Descripcion"].'<p>
                            </figure>
                        </article>';
                }
                
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