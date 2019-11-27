<?php session_start(); include("conect/conexion.php");?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8"/>
    <title>Universal Images - Registro</title>
    <link rel="stylesheet" type="text/css" href="css/index.css" title="Versión normal">
    <link rel="alternate stylesheet" type="text/css" href="css/acc.css" title="Estilo accesible">
    <link rel="alternate stylesheet" type="text/css" href="css/imprimir.css" media="screen" title="Estilo de impresión">
</head>

<body>
	<?php if(!isset($_SESSION["log"])){$_SESSION["log"]=false;}
		if($_SESSION['log']==true){$_SESSION['log']=false; include("inc/header_no.php");
		}else{include("inc/header_no.php");}?>
	
	<?php 
    if(isset($_GET['error'])){
        $datos=parse_ini_file('inc/alerts.ini');
        echo '<p style="color:Red;">'.$datos[$_GET['error']].'</p>';
    }
    ?>

	<main>
		<form class="album-form" action="php/respuesta_registro.php" method="POST" enctype="multipart/form-data">
                <fieldset>
                <legend>Formulario de registro</legend>
                <label class="labelForm" for="nomReg">Usuario</label>
				<input class="formInput" type="text" name="nomUser" id="nomUser" required>
                <br>
                <label class="labelForm" for="passReg">Contraseña</label>
				<input class="formInput" type="password" name="pass" id="pass" required>
                <br>
                <label class="labelForm" for="passReg2">Repetir contraseña</label>
				<input class="formInput" type="password" name="pass2" id="pass2" required>
                <br>
                <label class="labelForm" for="emailReg">Correo electrónico</label>
				<input class="formInput" type="email" name="correo" id="correo" required>
                <br>
                <label class="labelForm" for="sexReg">Sexo</label>
                    <input type="radio" name="sexo" value="1" id="sexReg" checked> Hombre
                    <br>
                    <input type="radio" name="sexo" value="0"> Mujer
                    <br>
                <br>
                <label class="labelForm" for="naciReg">Fecha de nacimiento</label>
				<input class="formInput" type="date" name="fecha"  id="fecha" required>
				<label class="labelForm" for="paisReg">País</label>
                <select class="formInput" name="paisRegis" id="paisRegis">
				    <?php
                        $sentencia= 'SELECT * FROM paises ORDER BY NomPais';
                        $resultado = mysqli_query($conexion, $sentencia);

                        while($fila=mysqli_fetch_assoc($resultado)){
                            echo "<option value=".$fila['IdPais'].">".$fila['NomPais']."</option>";
                        }
                        mysqli_free_result($resultado);
                    ?>
                </select>
                <br>
                <label class="labelForm" for="ciuReg">Ciudad</label>
                <input class="formInput" type="text" name="ciudad" id="ciudad">
                <br>
                <label class="labelForm" for="fotoReg">Foto de perfil</label>
				<input class="formFile" type="file" name="foto" id="fotoReg">
                </fieldset>
			 	<label for="subReg"></label>
				<input class="formSubmit" type="submit" name="submit_reg" id="subReg" value="Registrarse" />
                
		</form>
	</main>

	<?php include("inc/footer.inc"); mysqli_close($conexion);?>
    
</body>
</html>