<?php
	$changefrequency = "monthly";
	$prioritas = 0.8;

	$sql = "SELECT judulfrontend FROM module WHERE nama='page' ";
	$result = $mysql->query($sql);
	list($judulfrontend) = $mysql->fetch_array($result);

	$html_standalone .= "<h2>$judulfrontend</h2>\r\n";
	$sql = 'SELECT id, judul FROM page';
    $result = $mysql->query($sql);
	$html_standalone .= "<ul class=\"sitemap\">\r\n";
	$titleurl = array();
    while (list($id,$judul) = $mysql->fetch_row($result)) {
		$titleurl['pid']=$judul;
		$url = $urlfunc->makePretty("?p=page&action=view&pid=$id", $titleurl);
		$html_standalone .= "<li><a href=\"$url\">$judul</a></li>\r\n";
		$xml_standalone .= "
				<url>
					<loc>".xmlurl($url)."</loc>
					<changefreq>$changefrequency</changefreq>
					<priority>$prioritas</priority>
				</url>\r\n";
    }
    $html_standalone .= "</ul>\r\n";
?>