<?php session_start(); include("conect/conexion.php")?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8"/>
    <title>Universal Images - detalle</title>
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
		if(null!=$_GET){
			$id=$_GET["id"];
            $sentencia= "SELECT NomPais, NomUsuario, IdUsuario, fotos.Titulo fTitulo, albumes.Titulo aTitulo, fotos.Fecha, NomPais, Fichero, IdAlbum FROM fotos, paises, usuarios, albumes WHERE usuarios.IdUsuario=albumes.usuario and fotos.album=albumes.IdAlbum and paises.IdPais=fotos.pais AND IdFoto=" .$id."";
            $resultado = mysqli_query($conexion, $sentencia);
            if($resultado){
	            $fila=mysqli_fetch_assoc($resultado);
                echo "<article class='detalle'>
				<h3>".$fila["fTitulo"]."</h3>
				<figure>
					<img alt='404' src='usuarios/".$fila["Fichero"]."'/>
				</figure>

				<p>
					<b>Pais: ".$fila["NomPais"]." </b>
				</p>
				<p>
					<b>Fecha: ".$fila["Fecha"]."</b>
				</p>
				<p>
					<a href='album.php?id=".$fila["IdAlbum"]." '>Album: ".$fila["aTitulo"]."</a>
				</p>
				<p>
					<a href='perfil.php?id=".$fila["IdUsuario"]."'>Usuario: ".$fila["NomUsuario"]."</a>
				</p>
				</article>";
	            mysqli_free_result($resultado);
        	}else{echo "No se han encontrado fotos";}		
		}
		?>
	</main>
	<?php include("inc/footer.inc");?>
</body>
</html>