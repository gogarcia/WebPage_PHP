<?php session_start(); include("conect/conexion.php");?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8"/>
    <title>Universal Images - Mis datos</title>
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

        if(isset($_GET['error'])){
            $datos=parse_ini_file("inc/alerts.ini");

            echo '<p style="color:Red;">'.$datos[$_GET['error']].'</p>';
        }

        $id=$_SESSION["id"];
        $sentenciau = "SELECT * FROM usuarios, paises WHERE usuarios.pais=paises.IdPais and usuarios.IdUsuario=" .$id."";
        $resultadou = mysqli_query($conexion, $sentenciau);
        if($resultadou){
            $filau=mysqli_fetch_assoc($resultadou); 

            echo '
        <form class="album-form" action="php/respuesta_mis_datos.php" method="POST">
                <fieldset>
                <legend>Editar perfil</legend>
                <label class="labelForm" for="nomReg">Usuario</label>
                <input class="formInput" type="text" name="nomUser" id="nomUser" value="'.$filau["NomUsuario"].'">
                <br>
                <label class="labelForm" for="emailReg">Correo electrónico</label>
                <input class="formInput" type="email" name="correo" id="correo" value="'.$filau["Email"].'">
                <br>
                <label class="labelForm" for="sexReg">Sexo</label>
                    <input type="radio" name="sexo" value="1" id="sexo" ';if($filau["Sexo"]==1){echo 'checked';}
                    echo '> Hombre
                    <br>
                    <input type="radio" name="sexo" value="0" ';if($filau["Sexo"]==0){echo 'checked';}
                    echo '> Mujer
                    <br>
                <br>
                <label class="labelForm" for="naciReg">Fecha de nacimiento</label>
                <input class="formInput" type="date" name="fecha"  id="fecha" value="'.$filau["FNacimiento"].'">
                <label class="labelForm" for="paisReg">País</label>
                <select class="formInput" name="pais" id="pais">';
                        echo "<option value=".$filau['IdPais'].">".$filau['NomPais']."</option>";
                        $sentencia2= 'SELECT * FROM paises ORDER BY NomPais';
                        $resultado2 = mysqli_query($conexion, $sentencia2);
                        while($fila2=mysqli_fetch_assoc($resultado2)){
                            echo "<option value=".$fila2['IdPais'].">".$fila2['NomPais']."</option>";
                        }
                        mysqli_free_result($resultado2);
                echo '
                </select>
                <label class="labelForm" for="fotoReg">Foto de perfil</label>
                <input class="formFile" type="file" name="foto" id="foto">
                </fieldset>
                <label for="subReg"></label>
                <input class="formSubmit" type="submit" name="sub_dat" id="sub_dat" value="Guardar" />
                
        </form>
         ';

        }
        ?>

        <br>

        <form class="album-form" action="php/respuesta_mis_datos.php" method="POST">
            <fieldset>
            <legend>Cambiar contraseña</legend>
            <label class="labelForm" for="passReg">Contraseña</label>
            <input class="formInput" type="password" name="clavenueva" id="clavenueva" required>
            <br>
            <label class="labelForm" for="passReg2">Repetir contraseña</label>
            <input class="formInput" type="password" name="repclavenueva" id="repclavenueva" required>
            <label class="labelForm" for="passReg">Contraseña Actual</label>
            <input class="formInput" type="password" name="clave" id="clave" required>
            </fieldset>
            <label for="Cambiar!"></label>
            <input class="formSubmit" type="submit" name="sub_cla" id="sub_cla" value="Guardar" />    
        </form>



        <br>

        <form class="album-form" action="php/respuesta_baja.php" method="POST">
            <fieldset>
            <legend>Darme de baja</legend>
            <label class="labelForm" for="passReg">Contraseña</label>
            <input class="formInput" type="password" name="clave" id="clave" required>
            </fieldset>
            <label for="Dame de baja!"></label>
            <input class="formSubmit" type="submit" name="submit_reg" id="subReg" value="Bye bye!" />    
        </form>
    </main>
    <?php include("inc/footer.inc"); mysqli_close($conexion);?>
    
</body>
</html>