<?php
	$changefrequency = "never";
	$prioritas = 0;
	
	$sql = "SELECT judulfrontend FROM module WHERE nama='contact' ";
	$result = $mysql->query($sql);
	list($judulfrontend) = $mysql->fetch_array($result);
	$url = $urlfunc->makePretty("?p=contact");
	$html_others .= "<li><a href=\"$url\">$judulfrontend</a></li>\r\n";
	$xml_others .= "
			<url>
				<loc>".xmlurl($url)."</loc>
				<changefreq>$changefrequency</changefreq>
				<priority>$prioritas</priority>
			</url>\r\n";
	
?>