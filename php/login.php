
<?php
	session_start();

	include ("../conect/conexion.php");

	if(isset($_POST["submit2"])){
		setcookie("nombre","",time()-100);
		setcookie("clave","",time()-100);
		setcookie("fecha","",time()-100);
		setcookie("hora","",time()-100);
		$_SESSION["log"]=false;
		$host = $_SERVER['HTTP_HOST'];
		$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$pag = '../index.php';
		header("Location: http://$host$uri/$pag"); 
	}

	if(isset($_POST["nombre"]) && isset($_POST["clave"])){
		$nombre = $_POST["nombre"];
		$clave = $_POST["clave"];
		$existe = false;

		$sentencia = 'SELECT NomUsuario, Clave, IdUsuario FROM usuarios WHERE NomUsuario="' .$nombre. '"';
		$resultado = mysqli_query($conexion, $sentencia);
		if($fila=mysqli_fetch_assoc($resultado)){
			if(strcmp($nombre, $fila["NomUsuario"])==0 && strcmp($clave, $fila["Clave"])==0){
				if(isset($_POST["recordar"])){
					setcookie("nombre",$nombre,time()+3600*24*30);
					setcookie("clave",$clave,time()+3600*24*30);
					setcookie("fecha",date("d.m.y"),time()+3600*24*30);
					setcookie("hora",date("H:i:s"),time()+3600*24*30);
				}else{
					setcookie("nombre","",time()-100);
					setcookie("clave","",time()-100);
					setcookie("fecha","",time()-100);
					setcookie("hora","",time()-100);
				}
				$_SESSION["log"]=true;
				$_SESSION["nombre"]=$nombre;
				$_SESSION["id"]=$fila["IdUsuario"];
				$host = $_SERVER['HTTP_HOST'];
				$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
				$pag = '../index.php';
				header("Location: http://$host$uri/$pag");
			}else{
				$_SESSION["log"]=false;
				$_SESSION["nombre"]="";
				$_SESSION["id"]="";
				setcookie("nombre","",time()-100);
				setcookie("clave","",time()-100);
				setcookie("fecha","",time()-100);
				setcookie("hora","",time()-100);
				$host = $_SERVER['HTTP_HOST'];
				$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
				$pag = '../index.php?popen=si';
				header("Location: http://$host$uri/$pag");
			}
		}else{
		$_SESSION["log"]=false;
		$_SESSION["nombre"]="";
		$_SESSION["id"]="";
		$host = $_SERVER['HTTP_HOST'];
		$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$pag = '../index.php?popen=si';
		header("Location: http://$host$uri/$pag"); 
	}
	
	}else{
		$_SESSION["log"]=false;
		$_SESSION["nombre"]="";
		$_SESSION["id"]="";
		$host = $_SERVER['HTTP_HOST'];
		$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$pag = '../index.php?popen=si';
		header("Location: http://$host$uri/$pag"); 
	}
?>