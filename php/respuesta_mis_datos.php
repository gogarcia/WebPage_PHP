<?php
	session_start(); 
	include("../conect/conexion.php");




	if(isset($_POST["sub_dat"])){
		$error=false;
		$msgError="";


		$nombre="";
		$correo="";
		$fecha="";
		$pais="";
		$foto="";
		$sexo="";



		
		



		//Comprobación del nombre de usuario
		if(isset($_POST["nomUser"]) && $_POST["nomUser"]!=""){
			$expregNom="/^([0-9]|[a-z]|[A-Z]){3,15}$/";
			if(!preg_match($expregNom,$_POST["nomUser"])){
				$error=true;
				$msgError.="?error=nomUserMal";
			}else{

				$sentenciau= 'SELECT IdUsuario FROM usuarios WHERE NomUsuario="' .$_POST["nomUser"]. '"';
				$filau=mysqli_fetch_assoc(mysqli_query($conexion, $sentenciau));
	            if($filau && $_SESSION["id"]!=$filau["IdUsuario"]){
	            	$error=true;
	            	$msgError='?error=userNoVal';
	            }else{
					$nombre ="NomUsuario='".$_POST["nomUser"]."', ";
				}
			}
		}

		//Comprobar correo
		if(isset($_POST["correo"]) && $_POST["correo"]!=""){
			$expregEmail="/^([\w-\.]+)@((?:[\w]+\.)+)([a-zA-Z]{2,4})$/";
			if(!preg_match($expregEmail,$_POST["correo"])){
				$error=true;
				$msgError="?error=corrMal";
			}else{
				$sentenciau= 'SELECT IdUsuario FROM usuarios WHERE Email="' .$_POST["correo"]. '"';
				$filau=mysqli_fetch_assoc(mysqli_query($conexion, $sentenciau));
	            if($filau && $_SESSION["id"]!=$filau["IdUsuario"]){
	            	$error=true;
	            	$msgError="?error=corrNoVal";
	            }else{
					$correo = "Email='".$_POST["correo"]."', ";
				}
			}
		}


		//Validar pais  (NULL)
		if(isset($_POST["pais"])){
			$sentencia='SELECT IdPais FROM Paises WHERE IdPais='.$_POST["pais"].'';
			if($resultado = mysqli_query($conexion, $sentencia)){
				if($fila=mysqli_fetch_assoc($resultado)){
					$pais="Pais='" .$_POST["pais"]. "', ";
				}
			}	
		}



		//Comprobar fehca
		if(isset($_POST["fecha"]) && $_POST["fecha"]!=""){
			$fecha = "FNacimiento = '" .$_POST["fecha"]. "', ";
		}


		//Sanear el tipo ciudad
		if(isset($_POST["ciudad"])){
			$ciudad = $_POST["ciudad"];
			filter_var($ciudad,FILTER_SANITIZE_STRING);		
		}else{
			$ciudad="";
		}

		//Validar el sexo
		if(isset($_POST["sexo"]) && ($_POST["sexo"]==0 || $_POST["sexo"]==1)){
			$sexo = "Sexo='".$_POST["sexo"]."'";
		}else{
			$sexo = "Sexo='0'";
		}



		//Asignación de la foto
		if(isset($_FILES["foto"]) && $_FILES["foto"]["error"]==0){
		 	$nuevo=pathinfo($_FILES["foto"]["name"]);
		 	if($nuevo["extension"]=="png" || $nuevo["extension"]=="jpg"){
		 		$nuevo="profile".$_SESSION['id'].".".$nuevo['extension']."";
		 		if(ceil($_FILES["foto"]["size"] / 1024)<1024){
					if(move_uploaded_file($_FILES["foto"]["tmp_name"], 
				              "../usuarios/".$_SESSION['id']."/" .$nuevo)){
						//Exito
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






		if(!$error){
			$sentencia= "UPDATE usuarios SET ".$nombre." ".$correo." ".$ciudad." ".$fecha." ".$pais." ".$foto." ".$sexo." WHERE IdUsuario='".$_SESSION['id']."'";
		
			if(!mysqli_query($conexion, $sentencia)){
			$error=true;
			$msgError="?error=conErr";
			}
		}
		
		//Conexión con la base de datos
		if($error){
			$host = $_SERVER['HTTP_HOST'];
			$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
			$pag = '../mis_datos.php';
			header("Location: http://$host$uri/$pag$msgError");
		}else{
			if(isset($_POST['nomUser']) && $_POST['nomUser']!=""){
				$_SESSION["nombre"]=$_POST['nomUser'];
			}
			$host = $_SERVER['HTTP_HOST'];
			$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
			$pag = '../perfil.php';
			header("Location: http://$host$uri/$pag");
		}
	}



	if(isset($_POST["sub_cla"])){
		if(isset($_POST["clave"]) && isset($_POST["clavenueva"]) && isset($_POST["repclavenueva"])){
			$sentencia = "SELECT Clave, IdUsuario FROM usuarios WHERE usuarios.IdUsuario = ".$_SESSION['id']."";
			if($resultado = mysqli_query($conexion, $sentencia)){
				if($fila=mysqli_fetch_assoc($resultado)){
					if(isset($_POST["clave"]) && $_POST["clave"]==$fila["Clave"]){
						if($_POST["clavenueva"]==$_POST["repclavenueva"]){
							$expregPass="/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])([0-9]|[a-z]|[A-Z]|_){6,15}$/";
							if(!preg_match($expregPass,$_POST["clavenueva"])){
								$host = $_SERVER['HTTP_HOST'];
								$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
								$pag = '../mis_datos.php?er=clavMal';
								header("Location: http://$host$uri/$pag");
							}else{
								$sentencia="UPDATE usuarios SET Clave = '".$_POST["clavenueva"]."' WHERE IdUsuario=".$_SESSION['id']."";
								if(!mysqli_query($conexion, $sentencia)){
									$host = $_SERVER['HTTP_HOST'];
									$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
									$pag = '../mis_datos.php?er=conErr';
									header("Location: http://$host$uri/$pag");
								}else{
									$host = $_SERVER['HTTP_HOST'];
									$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
									$pag = '../mis_datos.php?er=cambiada';
									header("Location: http://$host$uri/$pag");
								}
							}
						}else{
							$host = $_SERVER['HTTP_HOST'];
							$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
							$pag = '../mis_datos.php?er=noCoinci';
							header("Location: http://$host$uri/$pag");
						}
					}else{
						$host = $_SERVER['HTTP_HOST'];
						$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
						$pag = '../mis_datos.php?er=clavInc';
						header("Location: http://$host$uri/$pag");
					}
				}
			}
		}else{
			$host = $_SERVER['HTTP_HOST'];
			$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
			$pag = '../mis_datos.php?er=noClaves';
			header("Location: http://$host$uri/$pag");
		}
	}
?>