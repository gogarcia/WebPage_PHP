<?php 
session_start(); 
	include("../conect/conexion.php");
	$titulo="";
	$descripcion="";
	$pais="NULL"; //NULL
	$fecha="NULL"; //NULL

	$error=false;
	$msg="";


	//Validar en titulo
	if(isset($_POST["titulo"])){
		$titulo=$_POST["titulo"];
	}
	filter_var($titulo, FILTER_SANITIZE_STRING);


	//Validar descripcion
	if(isset($_POST["descripcion"])){
		$descripcion=$_POST["descripcion"];
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
	
	//Validar fecha (NULL)
	if(isset($_POST["fecha"]) && $_POST["fecha"]!=""){
		$fecha = "'" .$_POST['fecha']. "'";
	}

	if(!$error){
		$fac= date("Y-m-d H:i:s");
		$sentencia="INSERT INTO `albumes` (`IdAlbum`, `Titulo`, `Descripcion`, `Fecha`, `Pais`, `Usuario`) VALUES (NULL, '".$titulo."', '".$descripcion."', ".$fecha.", '".$pais."', '".$_SESSION['id']."')";
		if(mysqli_query($conexion, $sentencia)){
			$insertado=mysqli_insert_id($conexion);
			$host = $_SERVER['HTTP_HOST'];
			$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
			$pag = '../album.php?id=';
			header("Location: http://$host$uri/$pag$insertado");
		}else{
			$error=true;
			$msg="?er=conErr";
		}
	}
	if($error){
		$host = $_SERVER['HTTP_HOST'];
			$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
			$pag = '../crear_album.php';
			header("Location: http://$host$uri/$pag$msg");
	}
?>
