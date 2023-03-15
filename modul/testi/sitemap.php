<?php
	$changefrequency = "monthly";
	$prioritas = 0.5;
	
	$sql = "SELECT judulfrontend FROM module WHERE nama='testi' ";
	$result = mysql_query($sql);
	list($judulfrontend) = mysql_fetch_array($result);
	$url = $urlfunc->makePretty("?p=testi");
	$html_others .= "<li><a href=\"$url\">$judulfrontend</a></li>\r\n";
	$xml_others .= "
			<url>
				<loc>".xmlurl($url)."</loc>
				<changefreq>$changefrequency</changefreq>
				<priority>$prioritas</priority>
			</url>\r\n";
?>