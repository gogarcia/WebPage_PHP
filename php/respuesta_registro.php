<?php
	session_start(); 
	include("../conect/conexion.php");
	$error=false;
	$msgError="";

	//Comprobación del nombre de usuario
	if(isset($_POST["nomUser"])){
		$expregNom="/^([0-9]|[a-z]|[A-Z]){3,15}$/";
		if(!preg_match($expregNom,$_POST["nomUser"])){
			$error=true;
			$msgError.="?error=nomUserMal";
		}else{
			$sentencia= 'SELECT IdUsuario FROM usuarios WHERE NomUsuario="' .$_POST["nomUser"]. '"';
            if(mysqli_fetch_assoc(mysqli_query($conexion, $sentencia))){
            	$error=true;
            	$msgError='?error=userNoVal';
            }else{
			$nombre = $_POST["nomUser"];
			}
		}
	}else{
		$error=true;
		$msgError="?error=noUser";
	}


	//Comprobación de la contraseña
	if(isset($_POST["pass"])){
	$expregPass="/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])([0-9]|[a-z]|[A-Z]|_){6,15}$/";
		if(!preg_match($expregPass,$_POST["pass"])){
			$error=true;
			$msgError='?error=clavMal';
		}else{
			$pass = $_POST["pass"];
		}
	}else{
		$error=true;
		$msgError="?error=noClav";
	}
	//Comprobación de repetir contraseña
	if(isset($_POST["pass2"])){
		if($_POST["pass"] == $_POST["pass2"]){
			$pass = $_POST["pass"];
		}else{
			$pass=null;
			$error=true;
			$msgError="?error=repMal";
		}		
	}else{
		$error=true;
		$msgError="?error=noRep";
	}

	//Asignación de variables restantes
	if(isset($_POST["correo"])){
		$expregEmail="/^([\w-\.]+)@((?:[\w]+\.)+)([a-zA-Z]{2,4})$/";
		if(!preg_match($expregEmail,$_POST["correo"])){
			$error=true;
			$msgError="?error=corrMal";
		}else{
			$correo = $_POST["correo"];
		}
	}else{
		$error=true;
		$msgError="?error=noCorr";
	}

	$email = $_POST["correo"];
	$sexo = $_POST["sexo"];
	$pais = $_POST["paisRegis"];
	$fecha = $_POST["fecha"];

	//Sanear el tipo ciudad
	if(isset($_POST["ciudad"])){
		$ciudad = $_POST["ciudad"];
		filter_var($ciudad,FILTER_SANITIZE_STRING);		
	}else{
		$ciudad=null;
	}

	//Fecha de registro actual
	$fregistro= date("Y-m-d H:i:s");



	if(!$error){
		$sentencia= "INSERT INTO usuarios VALUES (null,'".$nombre."','".$pass."','".$email."','".$sexo."','".$fecha."','".$ciudad."','".$pais."','','".$fregistro."')";
	
		if(!mysqli_query($conexion, $sentencia)){
		$error=true;
		$msgError="?error=conErr";
		}

		//Creacion de una carpeta
		$id=mysqli_insert_id($conexion);
		$estructura = "../usuarios/" .$id. "/";
		mkdir($estructura, 0700);
		
		//Asignación de la foto
		if($_FILES["foto"]["error"]==0){
		 	$nuevo=pathinfo($_FILES["foto"]["name"]);
		 	if($nuevo["extension"]=="png" || $nuevo["extension"]=="jpg"){
		 		$nuevo="profile".$id.".".$nuevo['extension']."";
		 		if(ceil($_FILES["foto"]["size"] / 1024)<1024){
					if(move_uploaded_file($_FILES["foto"]["tmp_name"], 
				              "../usuarios/".$id."/" .$nuevo)){
						$sentencia="UPDATE usuarios SET Foto='".$id."/" .$nuevo."' WHERE usuarios.IdUsuario=".$id."";
						mysqli_query($conexion, $sentencia);
					} else{
						//ERROR INTERNO
					}
		 		}else{
		 			//ERROR TAMAÑO
		 		}
		 	}else{
		 		//ERROR EXTENSION
		 	}
		}
	}
	
	//Conexión con la base de datos
	if($error){
		$host = $_SERVER['HTTP_HOST'];
		$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$pag = '../registro.php';
		header("Location: http://$host$uri/$pag$msgError");
	}else{
		$sentencia="SELECT * from usuarios where '".$nombre."' = usuarios.NomUsuario LIMIT 1";
		$resultado = mysqli_query($conexion, $sentencia);
		$fila=mysqli_fetch_assoc($resultado);
		$_SESSION["log"]=true;
		$_SESSION["nombre"]=$fila['NomUsuario'];
		$_SESSION["id"]=$fila["IdUsuario"];
		$host = $_SERVER['HTTP_HOST'];
		$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$pag = '../perfil.php';
		header("Location: http://$host$uri/$pag");
	}
?>
