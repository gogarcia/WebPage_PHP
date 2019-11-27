<?php
	$sentencia= 'SELECT * FROM fotos f ORDER BY FRegistro DESC limit 5';
	$resultado = mysqli_query($conexion, $sentencia);
	while($fila=mysqli_fetch_assoc($resultado)){
		$pais="";
		$sentencia2='SELECT NomPais FROM paises Where IdPais='.$fila["Pais"].'';
		if($resultado2 = mysqli_query($conexion, $sentencia2)){
			if($fila2=mysqli_fetch_assoc($resultado2)){
				$pais=$fila2["NomPais"];
				mysqli_free_result($resultado2);
			}
		}


		echo "<article>
				<figure>
					<a href='detalle.php?id=" .$fila["IdFoto"]. "'>
						<img atl=" .$fila["Titulo"]. " src='usuarios/" .$fila["Fichero"]."'/> 
					</a>
				</figure>
				<p>
					<b>Título: " .$fila["Titulo"]. " </b>
				</p>
				<p>
					<b>País: " .$pais. " </b>
				</p>
				<p>
					<b>Fecha: " .$fila["Fecha"]. " </b>
				</p>
			</article>";
	}
	mysqli_free_result($resultado);
	
?>