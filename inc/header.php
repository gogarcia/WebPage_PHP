<header>
		<div class="li-logo">
			<div id="logo-U"></div>
			<img class="logo" alt="logo" src="img/logo.png">
		</div>
        <ol>
			<li class="li2">
				<a href="index.php" class="boton-menu2" >Inicio</a>
			</li>
			<li class="li2">
				<a href="subir_foto.php" class="boton-menu2">Subir foto</a>
			</li>
			<li class="li2">
				<a href="mis_albumes.php" class="boton-menu2">Mis álbumes</a>
			</li >
			<li class="li2">
				<a href="crear_album.php" class="boton-menu2">Crear álbum</a>
			</li>
			<li class="li2">
				<a href="solicitar_album.php" class="boton-menu2">Solicitar Álbum</a>
			</li>
			<li class="li2">
				<a href="perfil.php" class="boton-menu2">Ir a perfil</a>
			</li>
			<li class="li2">
				<a href="index.php?cerrar=true" class="boton-menu2">Cerrar sesión</a>
			</li>
		</ol>
		<h3> ¡Hola <?php echo $_SESSION['nombre'];?>!</h3>

	</header>