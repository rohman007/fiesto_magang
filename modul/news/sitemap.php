<?php
	$changefrequency = "daily";
	$prioritas = 1;

	$sql = "SELECT judulfrontend FROM module WHERE nama='news' ";
	$result = $mysql->query($sql);
	list($judulfrontend) = $mysql->fetch_assoc($result);

	$html_standalone .= "<h2 class=\"sitemap-title\">$judulfrontend</h2>\r\n";
	$sql = 'SELECT id, nama FROM newscat ORDER BY urutan';
    $result = $mysql->query($sql);
	$html_standalone .= "<ul class=\"sitemap\">\r\n";
	$titleurl = array();
	// $idmenu = getCompanyID();
    while (list($cat_id,$nama) = $mysql->fetch_row($result)) {
		$titleurl['cat_id']=$nama;
		$url = $urlfunc->makePretty("?p=news&amp;action=latest&amp;cat_id=$cat_id&amp;idmenu=$idmenu", $titleurl);
		$archiveurl = $urlfunc->makePretty("?p=news&amp;action=archive&amp;cat_id=$cat_id&amp;idmenu=$idmenu", $titleurl);
		$html_standalone .= "<li><a href=\"$url\">$nama</a> (<a href=\"$archiveurl\">"._ARCHIVE."</a>)</li>\r\n";
		$xml_standalone .= "
				<url>
					<loc>".xmlurl($url)."</loc>
					<changefreq>$changefrequency</changefreq>
					<priority>$prioritas</priority>
				</url>\r\n";
		$xml_standalone .= "
				<url>
					<loc>".xmlurl($archiveurl)."</loc>
					<changefreq>$changefrequency</changefreq>
					<priority>$prioritas</priority>
				</url>\r\n";
    }
    $html_standalone .= "</ul>\r\n";
?>