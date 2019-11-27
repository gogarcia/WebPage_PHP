<?php session_start(); include("conect/conexion.php"); ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8"/>
    <title>Universal Images - Resultados de búsqueda</title>
    <link rel="stylesheet" type="text/css" href="css/index.css" title="Versión normal">
    <link rel="alternate stylesheet" type="text/css" href="css/acc.css" title="Estilo accesible">
    <link rel="alternate stylesheet" type="text/css" href="css/imprimir.css" media="screen" title="Estilo de impresión">
</head>

<body>
	<?php if(!isset($_SESSION["log"])){$_SESSION["log"]=false;}
		if($_SESSION['log']==true){include("inc/header.php");
		}else{include("inc/header_no.php");}?>
	
	
	<main>
		<?php include("inc/buscador.inc"); ?>
				<h4>Resultado de la búsqueda</h4>
		<?php 
		$titulo="";
		$fini="";
		$ffin="";
		$pais="";
			if(isset($_POST["buscar"])){
				//Busqueda Normal
				if($_POST["buscar"]!=""){
					echo "Titulo: $_POST[buscar]";
				}
				$buscar =  " Titulo LIKE ('%$_POST[buscar]%') and ";
					$sentencia= 'SELECT NomPais, Titulo, Fecha, IdFoto, Fichero FROM paises p,fotos f WHERE ' .$buscar. ' p.IdPais=f.pais LIMIT 100';					
					$resultado = mysqli_query($conexion, $sentencia);
					while($fila=mysqli_fetch_assoc($resultado)){
						echo "<article>
								<figure>
									<a href=detalle.php?id=" .$fila["IdFoto"]. ">
										<img atl=" .$fila["Titulo"]. " src='img/" .$fila["Fichero"]. "'/> 
									</a>
								</figure>
								<p>
									<b>Título: " .$fila["Titulo"]. " </b>
								</p>
								<p>
									<b>País: " .$fila["NomPais"]. " </b>
								</p>
								<p>
									<b>Fecha: " .$fila["Fecha"]. " </b>
								</p>
							</article>";
					}
					mysqli_free_result($resultado);
			}else{
				//Búsqueda avanzada
				if(isset($_POST["Titulo"]) && $_POST["Titulo"]!=""){
					echo "Título: $_POST[Titulo] <br>";
					$titulo = " Titulo LIKE ('%$_POST[Titulo]%') and ";
				}
				if(isset($_POST["Fecha_inicio"]) && $_POST["Fecha_inicio"]!=""){
					echo "Posterior a: $_POST[Fecha_inicio] <br>";
					$fini = " Fecha >= '$_POST[Fecha_inicio]' and ";
				}
				if(isset($_POST["Fecha_final"])&& $_POST["Fecha_final"]!=""){
					echo "Anterior a: $_POST[Fecha_final] <br>";
					$ffin = " Fecha <= '$_POST[Fecha_final]' and ";
				}
				if(isset($_POST["Pais"]) && $_POST["Pais"]!=""){
					$fila = mysqli_fetch_assoc(mysqli_query($conexion, 'SELECT NomPais FROM paises p WHERE  p.IdPais=' .$_POST["Pais"]. ''));
					echo "País: $fila[NomPais] <br>";
					$pais = " IdPais = $_POST[Pais] and ";
				}				
				$sentencia= 'SELECT NomPais, Titulo, Fecha, IdFoto, Fichero, IdPais FROM paises p,fotos f WHERE ' .$titulo. '' .$fini. '' .$ffin. '' .$pais.' p.IdPais=f.pais LIMIT 100';
				
				$resultado = mysqli_query($conexion, $sentencia);

				while($fila=mysqli_fetch_assoc($resultado)){
					echo "<article>
							<figure>
								<a href=detalle.php?id=" .$fila["IdFoto"]. ">
									<img atl=" .$fila["Titulo"]. " src='img/" .$fila["Fichero"]."'/> 
								</a>
							</figure>
							<p>
								<b>Título: " .$fila["Titulo"]. " </b>
							</p>
							<p>
								<b>País: " .$fila["NomPais"]. " </b>
							</p>
							<p>
								<b>Fecha: " .$fila["Fecha"]. " </b>
							</p>
						</article>";
				}
				mysqli_free_result($resultado);
			}
		echo "</div>";
		?>
		<br>
	</main>

	<?php include("inc/footer.inc"); ?>

</body>
</html>