<?php session_start(); include("conect/conexion.php");?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8"/>
    <title>Crear Album</title>
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
            if(isset($_GET['er'])){
            $datos=parse_ini_file("inc/alerts.ini");

            echo '<p style="color:Red;">'.$datos[$_GET['er']].'</p><br>';
        } 
        ?>
    <form action="php/respuesta_crear_album.php" method="POST" oninput="range_control_value.value = range_control.valueAsNumber" class="album-form">
        
        <fieldset>
        <legend>Crear un album</legend>

        <label class="labelForm" for="titleInput">Título de album:</label><input id="titulo" class="formInput" type="text" name="titulo" required />
        <br>

        <label class="labelForm" for="textInput">Descripción:</label><input id="descripcion" class="formInput" type="text" name="descripcion"/>
        <br>

        <label class="labelForm" for="albumInput">Pais:</label>
        <select id="pais" class="formInput" name="pais">
           <?php
                $sentencia= 'SELECT * FROM paises ORDER BY NomPais';
                $resultado = mysqli_query($conexion, $sentencia);
                echo "<option value = 0> País </option> ";
                while($fila=mysqli_fetch_assoc($resultado)){
                    echo "<option value=".$fila['IdPais'].">".$fila['NomPais']."</option>";
                }
                mysqli_free_result($resultado);
            ?>
        </select>
        <br>

        <label class="labelForm" for="fecha">Fecha</label>
        <input class="formInput" type="date" name="fecha" id="fecha">
        <br>

        </fieldset>
        <label for="subInput"></label><input id="subInput" class="formSubmit" type="submit" name="submit_control" value="Crear!"/>
        
    </form>
	</main>

	<footer>
		<a href="">Contacto</a>
		<a href="">Ayuda</a>
		<a href="">Idioma</a>
	</footer>

</body>
</html>