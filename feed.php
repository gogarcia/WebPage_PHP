	<?php
	$datos=parse_ini_file('conect/conexion.ini'); 
	$conexion= new mysqli($datos['Server'],$datos['User'],$datos['Password'],$datos['Database']);
	$conexion -> set_charset("utf8");
if(isset($_GET["type"])){
	if(strtoupper($_GET["type"])=="RSS"){

		header('Content-Type: text/xml;charset=utf-8',true);
		$xml=new DOMDocument("2.0","UTF-8");

		
		$rss = $xml->createElement("RSS");
		$rss_n = $xml->appendChild($rss);
		$rss_n->setAttribute("version","2.0");
		$rss_n->setAttribute("xmlns:atom","http://www.w3.org/2005/Atom");
		
		

		$canal = $xml -> createElement("channel");
		$canal_n = $rss_n -> appendChild($canal);
		
		

		//Variables de la pagina
		//fecha con el formato RFC 2822
		$fecha=date("D, d M Y H:i:s T", time());
		$fechaBuild= gmdate(DATE_RFC2822, strtotime($fecha));

		
		
		//los atributos del meta
		$canal_n -> appendChild($xml -> createElement("title","Universal Images - feed"));
		$canal_n -> appendChild($xml -> createElement("description","Sube tus fotos y compártelas con tus amigos, por todo el mundo."));
		$canal_n -> appendChild($xml -> createElement("link","http://localhost"));
		$canal_n -> appendChild($xml -> createElement("language","es-es"));
		$canal_n -> appendChild($xml -> createElement("lastBuildDate",$fechaBuild));
		$canal_n -> appendChild($xml -> createElement("generator", "PHP DOMDocument"));
		
		//Link a atom
		$canal_atom = $xml->createElement("atom:link");
		$canal_atom->setAttribute("href","http://localhost/feed.php?type=atom"); //url al feed
		$canal_atom->setAttribute("rel","self");
		$canal_atom->setAttribute("type","application/rss+xml");
		$canal_n->appendChild($canal_atom);

		
		//Imagen del feed, usamos el logo
		$icon = $canal_n->appendChild($xml->createElement("image"));
		$title_n = $icon->appendChild($xml->createElement("title", "Universal Images - Icon"));
		$url_n = $icon->appendChild($xml->createElement("url", "http://localhost/img/logo.png"));
		$link_n = $icon->appendChild($xml->createElement("link", "http://localhost"));
		
		//Conexion y fotos
		$respuesta = $conexion-> query("SELECT * FROM fotos ORDER BY fRegistro DESC LIMIT 5");
		if ($conexion->connect_error) {
			die('Error : ('. $conexion->connect_errno .') '. $conexion->connect_error);
		}
		if($respuesta){
			while($fila = $respuesta->fetch_assoc()){
				$item_n = $canal_n->appendChild($xml->createElement("item")); //crear nuevo objeto
				$titulo_n = $item_n->appendChild($xml->createElement("title", $fila["Titulo"])); //Darle un titulo
				$link_n = $item_n->appendChild($xml->createElement("link", "http://localhost/detalle.php?id=".$fila["IdFoto"])); //link de la foto
				
				//Identificador unico para el item (GUID)
				$guid_link = $xml->createElement("guid", "http://localhost/detalle.php?id=".$fila["IdFoto"]);  
				$guid_link->setAttribute("isPermaLink","false");
				$guid_n = $item_n->appendChild($guid_link);
				 
				//Descripcion para el item
				$descripcion_n = $item_n->appendChild($xml->createElement("description"));  
				 
				//usaremos el contenido CDATA para rellenar con la descripcion de la propia foto
				$descripcion_contenido = $xml->createCDATASection(htmlentities($fila["Descripcion"]));  
				$descripcion_n->appendChild($descripcion_contenido);
			   
				//Published date
				$fechaRFC = gmdate(DATE_RFC2822, strtotime($fila["FRegistro"]));
				$fechaPub = $xml->createElement("pubDate", $fechaRFC);  
				$fechaPub_n = $item_n->appendChild($fechaPub);
			}
		}

		
		$xml->saveXML();
		echo $xml;
	}elseif(strtolower($_GET["type"])=="atom"){
			header('Content-Type: text/xml;charset=utf-8',true);
			$xml = new DOMDocument("1.0","UTF-8");
			$atom = $xml -> createElement("feed");
			$atom_n = $xml -> appendChild($atom);
			$atom_n -> setAttribute("xmlsn","http://www.w3.org/2005/Atom");

			//Variables de la pagina
			//fecha con el formato RFC 2822
			$fecha=date("D, d M Y H:i:s T", time());
			$fechaBuild= gmdate(DATE_RFC3339, strtotime($fecha));
			
			//los atributos del meta
			$atom_n -> appendChild($xml -> createElement("title","Universal Images - feed"));
			$atom_n -> appendChild($xml -> createElement("subtitle","Sube tus fotos y compártelas con tus amigos, por todo el mundo."));
			$atom_n->appendChild($xml->createElement("id", uniqid("urn:uuid:")));
			$atom_n->appendChild($xml->createElement("updated",$fechaBuild));

			$link_n = $atom_n ->appendChild($xml -> createElement("link"));
			$link_n -> setAttribute("href","http://localhost/feed.php?type=atom");
			$link_n -> setAttribute("ref","self");
			$link2_n = $atom_n ->appendChild($xml -> createElement("link"));
			$link2_n -> setAttribute("href","http://localhost");
			
			$respuesta = $conexion->query("SELECT * FROM fotos ORDER BY FRegistro DESC LIMIT 5");
			if ($conexion->connect_error) {
				die('Error : ('. $conexion->connect_errno .') '. $conexion->connect_error);
			}
			if($respuesta){
				while($fila = $respuesta->fetch_assoc()){
					$titulo = $fila["Titulo"];
					$item_n = $atom_n->appendChild($xml->createElement("entry")); //crear nuevo objeto entrada
					$titulo_n = $item_n->appendChild($xml->createElement("title", $titulo)); //Darle un titulo
					
					//link a la foto
					$link_n = $item_n->appendChild($xml->createElement("link"));
					$link_n -> setAttribute("href", "http://localhost/detalle.php?id=".$fila["IdFoto"]);
					
					//nombre alternativo por si no hay foto
					$link2_n = $item_n->appendChild($xml->createElement("link"));
					$link2_n -> setAttribute("ref", "alternate");
					$link2_n -> setAttribute("type", "text/html");
					$link2_n -> setAttribute("href", "http://localhost/detalle.php?id=".$fila["IdFoto"]);
					
					//Identificador unico para el item (ID)
					$id = $xml->createElement("id", uniqid('urn:uuid:'));  
					$id_n = $item_n -> appendChild($id);
					 
					//Descripcion para el item
					$descripcion_n = $item_n->appendChild($xml->createElement("summary"));  
					 
					//usaremos el contenido CDATA para rellenar con la descripcion de la propia foto
					$descripcion_contenido = $xml->createCDATASection(htmlentities($fila["Descripcion"]));  
					$descripcion_n->appendChild($descripcion_contenido);
				   
					//Published date
					$fechaRFC = gmdate(DATE_RFC3339, strtotime($fila["FRegistro"]));
					$fechaPub = $xml->createElement("updated", $fechaRFC);  
					$fechaPub_n = $item_n->appendChild($fechaPub);
				}
			}
			$xml->saveXML();
	}else{
		die("No es RSS ni atom. ERROR Type of XML");
	}
}else{
	die("No se ha encontrado el tipo");
}?>