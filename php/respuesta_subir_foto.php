<?php  
	session_start(); 
	include("../conect/conexion.php");
	$titulo="";
	$descripcion="";
	$pais="NULL"; //NULL
	$fichero="";
	$album="";
	$fecha="NULL"; //NULL

	$error=false;
	$msg="";


	//Validar en titulo
	if(isset($_POST["titulo"])){
		$titulo=$_POST["titulo"];
	}else{
		$error=true;
		$msg="?er=noTitulo";
	}
	filter_var($titulo, FILTER_SANITIZE_STRING);


	//Validar descripcion
	if(isset($_POST["descripcion"])){
		$descripcion=$_POST["descripcion"];
	}else{
		$error=true;
		$msg="?er=noDesc";
	}
	filter_var($descripcion, FILTER_SANITIZE_STRING);


	//Validar pais  (NULL)
	if(isset($_POST["pais"])){
		$sentencia='SELECT IdPais FROM Paises WHERE IdPais='.$_POST["pais"].'';
		if($resultado = mysqli_query($conexion, $sentencia)){
			if($fila=mysqli_fetch_assoc($resultado)){
				$pais=$_POST["pais"];
			}
		}	
	}

	//Validar foto
	if(isset($_FILES["foto"])){
		if($_FILES["foto"]["error"]==0){
		 	$nuevo=pathinfo($_FILES["foto"]["name"]);
		 	if($nuevo["extension"]=="png" || $nuevo["extension"]=="jpg"){
		 		if(ceil($_FILES["foto"]["size"] / 1024)<1024){
		 		}else{
		 			$error=true;
		 			$msg="/?=sizeMal";
		 		}
		 	}else{
		 		$error=true;
		 		$msg="/?=extMal";
		 	}
		}else{
			$error=true;
			$msg="/?=errFiles".$_FILES['foto']['error']."";
		}
	}else{
		$error=true;
		$msg="/?=noFoto";
	}
	$fichero = "";





	//Validar album
	if(isset($_POST["album"])){
		$sentencia='SELECT IdAlbum FROM albumes, usuarios WHERE IdAlbum="'.$_POST["album"].'" AND IdUsuario=Usuario AND Usuario="' .$_SESSION["id"].'"';
		if($resultado=mysqli_query($conexion, $sentencia)){
			if($fila=mysqli_fetch_assoc($resultado)){
				$album = $_POST["album"];
			}
		}else{
			$error=true;
			$msg="?er=noAlbum1";
		}
	}else{
			$error=true;
			$msg="?er=noAlbum";
		}
	//Validar fecha (NULL)
	if(isset($_POST["fecha"]) && $_POST["fecha"]!=""){
		$fecha = "'" .$_POST['fecha']. "'";
	}
	$fac= date("Y-m-d H:i:s");
	if(!$error){
		$fac= date("Y-m-d H:i:s");
		$sentencia="INSERT INTO `fotos` (`IdFoto`, `Titulo`, `Descripcion`, `Fecha`, `Pais`, `Album`, `Fichero`, `FRegistro`) VALUES (NULL, '".$titulo."', '".$descripcion."', ".$fecha.", '".$pais."', '".$album."', '".$fichero."', '".$fac."')";
		echo $sentencia;
		if(mysqli_query($conexion, $sentencia)){
			$insertado=mysqli_insert_id($conexion);
	 		$nuevo="".$insertado."image".$_SESSION['id'].".".$nuevo['extension']."";
			if(move_uploaded_file($_FILES["foto"]["tmp_name"], "../usuarios/".$_SESSION['id']."/" .$nuevo)){
				$sentencia="UPDATE fotos SET Fichero='".$_SESSION['id']."/".$nuevo."' WHERE fotos.IdFoto=".$insertado."";
				mysqli_query($conexion, $sentencia);
			} else{
				//ERROR INTERNO
			}
			$host = $_SERVER['HTTP_HOST'];
			$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
			$pag = '../detalle.php?id=';
			header("Location: http://$host$uri/$pag$insertado");
		}else{
			$error=true;
			$msg="?er=conErr";
		}
	}
	if($error){
		$host = $_SERVER['HTTP_HOST'];
			$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
			$pag = '../subir_foto.php';
			header("Location: http://$host$uri/$pag$msg");
			echo $msg;
	}
?>