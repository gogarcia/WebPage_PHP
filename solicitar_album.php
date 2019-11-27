<?php session_start(); include("conect/conexion.php");?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8"/>
    <title>Solicitar album</title>
    <link rel="stylesheet" type="text/css" href="css/index.css" title="Versión normal">
    <link rel="alternate stylesheet" type="text/css" href="css/acc.css" title="Estilo accesible">
    <link rel="alternate stylesheet" type="text/css" href="css/imprimir.css" media="screen" title="Estilo de impresión">
</head>

<body>
	<?php if(!isset($_SESSION["log"])){$_SESSION["log"]=false;}
        if($_SESSION['log']==true){include("inc/header.php");
        }else{include("inc/header_no.php");}?>
    
    <main>
    <p >En esta página puedes solicitar un álbum para que te lo envíen a tu dirección. Solo has de rellenar este formulario</p>

    <?php 
        if(isset($_GET['er'])){
            $datos=parse_ini_file("inc/alerts.ini");
            if($_GET['er']="exito"){
                echo '<p style="color:#06FF00;">'.$datos[$_GET['er']].'</p>';
            }else{
                echo '<p style="color:Red;">'.$datos[$_GET['er']].'</p>';
            }
        } 
    ?>

    <form action="php/respuesta_solicitar_album.php" method="POST" oninput="range_control_value.value = range_control.valueAsNumber" class="album-form">
        
        <fieldset>
        <legend>Formulario de solicitud</legend>
        <label class="labelForm" for="nameInput">Nombre:</label><input id="nombre" class="formInput" type="text" name="nombre" autofocus required />
        <br>
        <label class="labelForm" for="titleInput">Título de album:</label>
        <input id="titulo" type="text" class="formInput" name="titulo"></input>
        <br>
        <label class="labelForm" for="textInput">Texto adicional:</label><input id="descripcion" class="formInput" type="text" name="descripcion"/>
        <br>
        <label class="labelForm" for="emailInput"> E-mail:</label><input id="email" class="formInput" type="text" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" required />
        <br>
        <label class="labelForm" for="directionInput">Dirección:</label><input id="direccion" class="formInput" type="text" name="direccion"/>
        <br>
        <label class="labelForm" for="fechaInput">Fecha</label>
        <input class="formInput" type="date" name="fecha"  id="fecha">
        <br>
        <label class="labelForm" for="colorInput">Color de portada:</label><input id="color" class="formInputColor" type="color" name="color"/>
        <br>
        <label class="labelForm" for="numberInput">Nº de copias:</label><input id="copias" class="formInput" type="number" name="copias" min="1" max="100000" value="1" required/>
        <br>
        <label class="labelForm" for="resInput">Resolución:</label><input id="resolucion" class="formInput" type="number" name="resolucion" min="150" max="900" step="150" value="150" required/>
        <br>
        <label class="labelForm" for="albumInput">Album:</label>
        <select id="album" class="formInput" name="album">
           <?php
                $sentencia= 'SELECT IdAlbum, Titulo FROM albumes a, usuarios u WHERE u.IdUsuario=a.usuario and u.IdUsuario=' .$_SESSION["id"]. '';
                $resultado = mysqli_query($conexion, $sentencia);
                while($fila=mysqli_fetch_assoc($resultado)){
                    echo "<option value=".$fila['IdAlbum'].">".$fila['Titulo']."</option>";
                }
                mysqli_free_result($resultado);
            ?>
        </select>
        <br>
        <label class="labelForm" for="colorInput">A color:</label>
        <select id="albumInput" class="formInput" name="icolor">
            <option label="Color" value="1"></option>
            <option label="Blanco y negro" value="0"></option>
        </select>
        <br>
        </fieldset>
        <label for="subInput"></label><input id="subInput" class="formSubmit" type="submit" name="submit_control" value="Confirmar"/>
        
    </form>
	</main>

	<footer>
		<a href="">Contacto</a>
		<a href="">Ayuda</a>
		<a href="">Idioma</a>
	</footer>

</body>
</html>