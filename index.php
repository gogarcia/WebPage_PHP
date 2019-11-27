<?php session_start(); include('conect/conexion.php'); clearstatcache()?>
<!DOCTYPE html>
<html lang="es">

<head>
	<link rel="icon" type="image/x-icon" href="/favicon.ico" />
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
   <title>Universal Images - Inicio</title>
	<meta charset="UTF-8"/>

<meta http-equiv="Expires" content="0">
<meta http-equiv="Last-Modified" content="0">
<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
<meta http-equiv="Pragma" content="no-cache">



	<link rel="stylesheet" type="text/css" href="css/index.css" title="Versión normal">
	<link rel="alternate stylesheet" type="text/css" href="css/acc.css" title="Estilo accesible">
	<link rel="alternate stylesheet" type="text/css" href="css/imprimir.css" media="screen" title="Estilo de impresión"/>
</head>

<body>
	<?php
		if(!isset($_SESSION["log"])){
			$_SESSION["log"]=false;
		}
		if(isset($_GET["cerrar"])){
			if ($_GET["cerrar"]=="true") {
				$_SESSION["log"]=false;
				$_SESSION["nombre"]="";
				$_SESSION["id"]="";
			}
		}
		if($_SESSION['log']==true){
			include("inc/header.php");
		}else{
			include("inc/header_no.php");
			if(isset($_GET["popen"])){
				if($_GET["popen"]=="si"){
					echo '<div> Usuario y/o contraseña incorrectos </div>';
				}
			}
		}
		
	?>
	<main>
		<?php include("inc/buscador.inc");?>
		<section class="seleccionadas">
        	<?php include("php/fotoSeleccionada.php");?>
    	</section>

    	<section class="ultimasfotos">
        	<?php include("inc/ultimasfotos.php");?>
        </section>


        <section>
        	<?php 
	        	include("php/graficos.php");
				$src = creaImagen();
				echo "<h3>Fotos subidas esta semana</h3>";
				echo "<img class='grafico' src='".$src."' alt='Gráficos del Nº de fotos subidas la última semana' />";
        	?>
        </section>
	</main>

	<?php include("inc/footer.inc"); mysqli_close($conexion);?>

</body>
</html>