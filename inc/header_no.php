<header>
		<a href="index.php"><img class="logo" alt="logo" src="img/logo.png"></a>

		<nav>
			<a href="index.php" class="boton-menu2" >Inicio</a>
			<div class="boton-ini" >Entrar
				<?php

				if(isset($_COOKIE["nombre"]) && $_COOKIE["clave"]){
					$text=$_COOKIE["nombre"];
					$text2=$_COOKIE["clave"];
					echo "
					<form action='php/login.php' method='POST' class='ini-form'>
					  <input type='hidden' name='nombre' value=$text/>
					  <input type='hidden' name='clave' placeholder=$text2/>
					  <fieldset class='cookie-login'>
						Hola $text, su última visita fue el $_COOKIE[fecha], a las $_COOKIE[hora]
					  </fieldset>
					  <button name='submit' value='in'>Iniciar sesión</button>
					  <button name='submit2' value='out'>Salir</button>
					</form>";

				}else{
					echo "
					<form action='php/login.php' method='POST' class='ini-form'>
					  <input type='text' name='nombre' placeholder='Nombre'/>
					  <input type='password' name='clave' placeholder='contraseña'/>
					   <label>Recordarme</label>
						<input type='checkbox' name='recordar' checked='checked'/>
					  <button name='submit' >Iniciar sesión</button>
					  <p class='mensaje'>¿No está registrado? <a href='registro.php'>¡Regístrate!</a></p>
					</form>";
				}
				?>
			</div>
		</nav>
	</header>