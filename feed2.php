<?php

include("conect/conexion.php");

function uuid($prefix){
	$chars = md5(uniqid(mt_rand(), true));
	$uuid  = substr($chars,0,8)."-";
	$uuid .= substr($chars,8,4)."-";
	$uuid .= substr($chars,12,4)."-";
	$uuid .= substr($chars,16,4)."-";
	$uuid .= substr($chars,20,12);
	return $prefix.$uuid;
}
if(isset($_GET["type"])){
	if(strtolower($_GET["type"])=="atom"){
		header('Content-Type: text/xml; charset=utf-8', true);
		$xml = new DOMDocument("1.0", "UTF-8");
		$rss = $xml->createElement("feed");
		$rss_node = $xml->appendChild($rss);
		$rss_node->setAttribute("xmlns","http://www.w3.org/2005/Atom");
		$rss_node->appendChild($xml->createElement("title", "ZUBBO"));
		$rss_node->appendChild($xml->createElement("subtitle", "Tus imagenes en la nube."));
		$link_node = $rss_node->appendChild($xml->createElement("link"));
		$link_node->setAttribute("href","http://127.0.0.1/feed.php?type=atom");
		$link_node->setAttribute("rel","self");
		$link2_node = $rss_node->appendChild($xml->createElement("link"));
		$link2_node->setAttribute("href","http://127.0.0.1");
		$rss_node->appendChild($xml->createElement("id", uuid("urn:uuid:")));
		$rss_node->appendChild($xml->createElement("updated", gmdate(DATE_RFC3339, strtotime(date("D, d M Y H:i:s T", time())))));
		$res = mysqli_query($db," *, (SELECT email from usuario WHERE id=(SELECT idUsuario FROM albumes WHERE id=fotos.idAlbum)) as email, (SELECT nombre from usuario WHERE id=(SELECT idUsuario FROM albumes WHERE id=fotos.idAlbum)) as nombre FROM fotos ORDER BY fecha DESC LIMIT 5");
		if ($res){
			while ($row = mysqli_fetch_assoc( $res)){
				$item_node = $rss_node->appendChild($xml->createElement("entry")); //create a new node called "item"
				$title_node = $item_node->appendChild($xml->createElement("title", $row["titulo"])); //Add Title under "item"
				$link_node = $item_node->appendChild($xml->createElement("link"));
				$link_node->setAttribute("href","http://127.0.0.1/detalle.php?id=".$row["id"]);
				$link2_node = $item_node->appendChild($xml->createElement("link"));
				$link2_node->setAttribute("rel","alternate");
				$link2_node->setAttribute("type","text/html");
				$link2_node->setAttribute("href","http://127.0.0.1/detalle.php?id=".$row["id"]);
				//Unique identifier for the item (ID)
				$id_link = $xml->createElement("id", uuid("urn:uuid:"));
				$id_node = $item_node->appendChild($id_link);
				//Published date
				$date_rfc = gmdate(DATE_RFC3339, strtotime($row["Fecha"]));
				$pub_date = $xml->createElement("updated", $date_rfc);
				$pub_date_node = $item_node->appendChild($pub_date);
				//create "description" node under "item"
				$summary_node = $item_node->appendChild($xml->createElement("summary"));
				//fill description node with CDATA content
				$summary_contents = $xml->createCDATASection(htmlentities($row["descripcion"]));
				$summary_node->appendChild($summary_contents);
				//$content_node = $item_node->appendChild($xml->createElement("content"));
				$author_node = $item_node->appendChild($xml->createElement("author"));
				$name_node = $author_node->appendChild($xml->createElement("name", $row["nombre"]));
				$email_node = $author_node->appendChild($xml->createElement("email", $row["email"]));
				//ToDo: content, author
			}
		}
		echo $xml->saveXML();
	} else if (strtoupper($_GET["type"])=="RSS"){
		header('Content-Type: text/xml; charset=utf-8', true);
		$xml = new DOMDocument("1.0", "UTF-8");
		$rss = $xml->createElement("rss");
		$rss_node = $xml->appendChild($rss);
		$rss_node->setAttribute("version","2.0");
		$rss_node->setAttribute("xmlns:atom","http://www.w3.org/2005/Atom");
		$channel = $xml->createElement("channel");
		$channel_node = $rss_node->appendChild($channel);
		$channel_node->appendChild($xml->createElement("title", "ZUBBO"));
		$channel_node->appendChild($xml->createElement("description", "Tus imagenes en la nube."));
		$channel_node->appendChild($xml->createElement("link", "http://127.0.0.1"));
		$channel_node->appendChild($xml->createElement("language", "es-es"));
		$channel_node->appendChild($xml->createElement("lastBuildDate", gmdate(DATE_RFC2822, strtotime(date("D, d M Y H:i:s T", time())))));
		$channel_node->appendChild($xml->createElement("generator", "PHP DOMDocument"));
		$channel_atom_link = $xml->createElement("atom:link");
		$channel_atom_link->setAttribute("href","http://127.0.0.1/feed.php?type=atom"); //url of the feed
		$channel_atom_link->setAttribute("rel","self");
		$channel_atom_link->setAttribute("type","application/rss+xml");
		$channel_node->appendChild($channel_atom_link);
		$image_node = $channel_node->appendChild($xml->createElement("image"));
		$title_node = $image_node->appendChild($xml->createElement("title", "ZUBBO"));
		$url_node = $image_node->appendChild($xml->createElement("url", "http://127.0.0.1/imagenes/logo.jpg"));
		$lnk_node = $image_node->appendChild($xml->createElement("link", "http://127.0.0.1"));
		$res = mysqli_query($db,"SELECT * FROM fotos ORDER BY fecha DESC LIMIT 5");
		if ($res){
			while ($row = mysqli_fetch_assoc($res)){
				$item_node = $channel_node->appendChild($xml->createElement("item")); //create a new node called "item"
			    $title_node = $item_node->appendChild($xml->createElement("title", $row["titulo"])); //Add Title under "item"
			    $link_node = $item_node->appendChild($xml->createElement("link", "http://127.0.0.1/detalle.php?id=".$row["id"])); //add link node under "item"
				//Unique identifier for the item (GUID)
			    $guid_link = $xml->createElement("guid", "http://127.0.0.1/detalle.php?id=".$row["id"]);
			    $guid_link->setAttribute("isPermaLink","false");
			    $guid_node = $item_node->appendChild($guid_link);
			    //create "description" node under "item"
			    $description_node = $item_node->appendChild($xml->createElement("description"));
			    //fill description node with CDATA content
			    $description_contents = $xml->createCDATASection(htmlentities($row["descripcion"]));
			    $description_node->appendChild($description_contents);
			    //Published date
			    $date_rfc = gmdate(DATE_RFC2822, strtotime($row["fecha"]));
			    $pub_date = $xml->createElement("pubDate", $date_rfc);
			    $pub_date_node = $item_node->appendChild($pub_date);
			}
		}
		echo $xml->saveXML();
	} else die("ERROR");
} else die ("ERROR");
mysqli_close($db);
?>
