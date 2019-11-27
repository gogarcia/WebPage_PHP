<?php 

    if(($fichero=file("inc/fotosSeleccion.ini"))==false){
        echo "<div class='alert'> No se puede acceder al fichero de fotos seleccionadas</div>";
    }else{
        $numLineas = count($fichero);
        $linea=rand(0,$numLineas-1);
        $contenido=explode("#",$fichero[$linea]);
        $sentencia="SELECT * from fotos where idFoto='".$contenido[1]."'";
        $resultado = mysqli_query($conexion, $sentencia);
        if($fila=mysqli_fetch_assoc($resultado)){
            echo "<article>
                    <figure>
                        <a href='detalle.php?id=" .$fila["IdFoto"]. "'>
                            <img atl=" .$fila["Titulo"]. " src='usuarios/" .$fila["Fichero"]."'/> 
                        </a>
                    </figure>
                    <p>
                        <b>Seleccionada por: " .$contenido[0]. " </b>
                    </p>
                    <p>
                        <i>''" .$contenido[2]. "''</i>
                    </p>
                </article>";
        }
    }
    mysqli_free_result($resultado);
?>