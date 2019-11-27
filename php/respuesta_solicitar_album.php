<?php session_start();
include("../conect/conexion.php");

$error=false;
$msg="";

$album="";
$nombre="";
$titulo="";
$descripcion="";
$email="";
$direccion="";
$color="";
$copias="";
$resolucion="";
$fecha="";
$icolor="";
$factual="";
$coste="150";



//Validar el album
if(isset($_POST["album"])){
	$sentencia="SELECT IdAlbum FROM albumes WHERE usuario='" .$_SESSION['id']. "' AND IdAlbum='" .$_POST['album']. "'";
	if($resultado=mysqli_query($conexion, $sentencia)){
		$album=$_POST["album"];
	}else{
		$error=true;
		$msg="?er=albMal";
	}
	$msg="?er=noAlbum";
}

//Validar el nombre
	if(isset($_POST["nombre"])){
		$nombre=$_POST["nombre"];
		filter_var($nombre, FILTER_SANITIZE_STRING);
	}else{
		$error=true;
		$msg="?er=noNombre";
	}

//Validar el titulo
	if(isset($_POST["titulo"])){
		$titulo=$_POST["titulo"];
		filter_var($titulo, FILTER_SANITIZE_STRING);
	}

//Validar el descripcion
if(isset($_POST["descricpion"])){
	$descricpcion=$_POST["descricpicion"];
	filter_var($descricpion, FILTER_SANITIZE_STRING);
}

//Validar el email
if(isset($_POST["email"])){
		$expregEmail="/^([\w-\.]+)@((?:[\w]+\.)+)([a-zA-Z]{2,4})$/";
		if(!preg_match($expregEmail,$_POST["email"])){
			$error=true;
			$msg="?error=corrMal";
		}else{
			$email = $_POST["email"];
		}
	}else{
		$error=true;
		$msg="?error=noCorr";
	}


//Validar la direccion
if(isset($_POST["direccion"])){
	$direccion=$_POST["direccion"];
	filter_var($direccion, FILTER_SANITIZE_STRING);
}else{
	//$error=true;
	//$msg="?er=noDir";
}


//Validar el color
if(isset($_POST["color"])){
		$expreg="/^#[0-9A-Fa-f]{3,6}$/";
		if(!preg_match($expreg,$_POST["color"])){
			$error=true;
			$msg="?er=corrMal";
		}else{
			$color = $_POST["color"];
		}
	}else{
		$error=true;
		$msg="?er=noCorr";
	}

//Validar las copias
if(isset($_POST["copias"])){
	$int_options = array("options" => array("min_range" => 1, "max_range" => 256)); 
 	if($copias=filter_var($_POST["copias"], FILTER_VALIDATE_INT, $int_options)){

 	}else{
 		$error=true;
 		$msg="?er=copiasMal";
 	}
}else{
	$error=true;
	$msg="?er=noCopias";
}

//Validar la resolucion
if(isset($_POST["resolucion"])){
	$int_options = array("options" => array("min_range" => 150, "max_range" => 900)); 
 	if($resolucion=filter_var($_POST["resolucion"], FILTER_VALIDATE_INT, $int_options)){

 	}else{
 		$error=true;
 		$msg="?er=resMas";
 	}
}else{
	$error=true;
	$msg="?er=noRes";
}

//Validar la fecha
if(isset($_POST["fecha"])){
		$expreg="/^([0-9]{4})(\/|-)([0-9]{2})(\/|-)([0-9]{2})$/";
		if(!preg_match($expreg,$_POST["fecha"])){
			$error=true;
			$msg="?er=feMal";
		}else{
			$fecha = $_POST["fecha"];
		}
	}else{
		$error=true;
		$msg="?er=noFe";
	}


	if(isset($_POST["icolor"]) && ($_POST["icolor"]==1 || $_POST["icolor"]==0)){
		$icolor=$_POST["icolor"];
	}else{
		$error=true;
		$msg="?er=noIcolor";
	}

if(!$error){
		$factual= date("Y-m-d H:i:s");
		$sentencia="INSERT INTO `solicitudes` (`IdSolictud`, `Album`, `Nombre`, `Titulo`, `Descripcion`, `Email`, `Direccion`, `Color`, `Copias`, `Resolucion`, `Fecha`, `IColor`, `FRegistro`, `Coste`) VALUES (NULL, '".$album."', '".$nombre."', '".$titulo."', '".$descripcion."', '".$email."', '".$direccion."', '".$color."', '".$copias."', '".$resolucion."', '".$fecha."', '".$icolor."', '".$factual."', '".$coste."');";
		
		if(mysqli_query($conexion, $sentencia)){
			$host = $_SERVER['HTTP_HOST'];
			$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
			$pag = '../solicitar_album.php?er=exito';
			header("Location: http://$host$uri/$pag");
		}else{
			$error=true;
			$msg="?er=conErr";
		}
		
	}
	if($error){
		$host = $_SERVER['HTTP_HOST'];
		$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$pag = '../solicitar_album.php';
		header("Location: http://$host$uri/$pag$msg");
			
	}

?>