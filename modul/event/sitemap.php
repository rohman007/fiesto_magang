<?php
	$changefrequency = "monthly";
	$prioritas = 0.5;
	
	$sql = "SELECT judulfrontend FROM module WHERE nama='event' ";
	$result = $mysql->query($sql);
	list($judulfrontend) = $mysql->fetch_array($result);
	$url = $urlfunc->makePretty("?p=event");
	$html_others .= "<li><a href=\"$url\">$judulfrontend</a></li>\r\n";
	$xml_others .= "
			<url>
				<loc>".xmlurl($url)."</loc>
				<lastmod>$tanggal</lastmod>
				<changefreq>$changefrequency</changefreq>
				<priority>$prioritas</priority>
			</url>\r\n";
?>