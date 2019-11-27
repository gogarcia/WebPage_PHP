<?php 
	function delTree($dir) { 
   		$files = array_diff(scandir($dir), array('.','..')); 
    foreach ($files as $file) { 
      if(is_dir("$dir/$file")){
      	delTree("$dir/$file");
      }else{
      	unlink("$dir/$file");
      } 
    }
    return rmdir($dir); 
  } 
?>


<?php




	session_start(); 
	include("../conect/conexion.php");

	$error=true;

	if(isset($_POST["clave"])){
		$sentencia = "SELECT Clave, IdUsuario FROM usuarios WHERE usuarios.IdUsuario = ".$_SESSION['id']."";
		if($resultado = mysqli_query($conexion, $sentencia)){
			if($fila=mysqli_fetch_assoc($resultado)){
				if(strcmp($_POST["clave"], $fila["Clave"])==0){
					
					$sfotos= "DELETE f FROM usuarios u, albumes a, fotos f WHERE f.Album=a.IdAlbum AND a.Usuario = u.IdUsuario AND u.IdUsuario='".$_SESSION['id']."'";
					$sSolicitudes= "DELETE s FROM usuarios u, albumes a, solicitudes s WHERE s.Album=a.IdAlbum AND a.Usuario=u.IdUsuario AND u.IdUsuario='".$_SESSION['id']."'";
					$sAlbumes= "DELETE a FROM usuarios u, albumes a WHERE a.Usuario=u.IdUsuario AND u.IdUsuario='".$_SESSION['id']."'";
					$sUsuario= "DELETE u FROM usuarios u WHERE u.IdUsuario='".$_SESSION['id']. "'";

					// $sentencia= "DELETE FROM usuarios WHERE usuarios.IdUsuario = ".$_SESSION['id']. "";
					if(mysqli_query($conexion, $sfotos) && mysqli_query($conexion, $sSolicitudes) && mysqli_query($conexion, $sAlbumes) && mysqli_query($conexion, $sUsuario)){
						delTree("../usuarios/" .$_SESSION['id']. "/");
						$error=false;
						setcookie("nombre","",time()-100);
						setcookie("clave","",time()-100);
						setcookie("fecha","",time()-100);
						setcookie("hora","",time()-100);
						$_SESSION["log"]=false;
						$_SESSION["nombre"]="";
						$_SESSION["id"]="";
						$host = $_SERVER['HTTP_HOST'];
						$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
						$pag = '../index.php';
						header("Location: http://$host$uri/$pag");
					}
				}else{
					$error=false;
					$host = $_SERVER['HTTP_HOST'];
					$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
					$pag = '../mis_datos.php?er=clavInc';
					header("Location: http://$host$uri/$pag");
				}
			}
		}
	}else{
		$error=false;
		$host = $_SERVER['HTTP_HOST'];
		$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$pag = '../mis_datos.php?er=clavInc';
		header("Location: http://$host$uri/$pag");
	}

	if($error){
		$host = $_SERVER['HTTP_HOST'];
		$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$pag = '../mis_datos.php?er=conEr';
		header("Location: http://$host$uri/$pag");
	}



?>