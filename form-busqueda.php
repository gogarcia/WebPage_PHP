<?php session_start(); include("conect/conexion.php");?>
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8"/>
	<title>Universal Images - búsqueda avanzada</title>
    <link rel="stylesheet" type="text/css" href="css/index.css" title="Versión normal">
    <link rel="alternate stylesheet" type="text/css" href="css/acc.css" title="Estilo accesible">
    <link rel="alternate stylesheet" type="text/css" href="css/imprimir.css" media="screen" title="Estilo de impresión">
</head>

<body>
	<?php if(!isset($_SESSION["log"])){$_SESSION["log"]=false;}
		if($_SESSION['log']==true){include("inc/header.php");
		}else{include("inc/header_no.php");}?>
	

	<main>
		<form action="result-busqueda.php" method="POST" class="bus-form-a">
			<fieldset>
			<label for="title">Título</label>
			<input type="text" name="Titulo" id="title">
			</fieldset>
			<fieldset>
			<label for="fecha_ini">Fecha entre</label>
			<input type="date" name="Fecha_inicio" id="fecha_ini" ><input type="date" name="Fecha_final" id="fecha_fin">
			</fieldset>
			<fieldset>
			<label for="country">País:</label>
			<select class="formInput" name="Pais" id="country">
			    <?php
	                $sentencia= 'SELECT * FROM paises ORDER BY NomPais';
	                $resultado = mysqli_query($conexion, $sentencia);
	                	echo "<option value=''> Pais </option>";
	                while($fila=mysqli_fetch_assoc($resultado)){
	                    echo "<option value=".$fila['IdPais'].">".$fila['NomPais']."</option>";
	                }
	                mysqli_free_result($resultado);
	            ?>
            </select>
			</fieldset>
			<button type="submit" value="Buscar">Buscar</button>
		</form>
	</main>
	<?php include("inc/footer.inc");?>
</body>
</html>