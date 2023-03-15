<?php
	$changefrequency = "daily";
	$prioritas = 1;

	$sql = "SELECT judulfrontend FROM module WHERE nama='catalog' ";
	$result = $mysql->query($sql);
	list($judulfrontend) = $mysql->fetch_array($result);

	$html_standalone .= "<h2>$judulfrontend</h2>\r\n";
	$sql = 'SELECT id, nama, parent FROM catalogcat ORDER BY urutan';
    $cats = new categories();
    $mycats = array();
    $result = $mysql->query($sql);
    while ($row = $mysql->fetch_array($result)) {
        $mycats[] = array('id' => $row['id'], 'nama' => $row['nama'], 'parent' => $row['parent'], 'level' => 0);
    }
    $cats->get_cats($mycats);

    $currlevel = 1;
    $html_standalone .= "<ul class=\"sitemap\">\r\n";
    for ($i = 0; $i < count($cats->cats); $i++) {

        $titleurl = array();
        $titleurl["cat_id"] = $cats->cats[$i]['nama'];

		$url = $urlfunc->makePretty("?p=catalog&action=images&cat_id=" . $cats->cats[$i]['id'], $titleurl);
        $selisihlevel = $cats->cats[$i]['level'] - $currlevel;
        if ($selisihlevel > 0) {
            $html_standalone .= "<ul>\r\n";
            $html_standalone .= "<li><a href=\"$url\">" . $cats->cats[$i]['nama'] . "</a></li>\r\n";
        }
        if ($selisihlevel == 0) {
            $html_standalone .= "<li><a href=\"$url\">" . $cats->cats[$i]['nama'] . "</a></li>\r\n";
        }
        if ($selisihlevel < 0) {
            for ($j = 0; $j < -$selisihlevel; $j++) {
                $html_standalone .= "</ul>\r\n";
            }
            $html_standalone .= "<li><a href=\"$url\">" . $cats->cats[$i]['nama'] . "</a></li>\r\n";
        }
        $currlevel = $cats->cats[$i]['level'];
		$xml_standalone .= "
				<url>
					<loc>".xmlurl($url)."</loc>
					<lastmod>$tanggal</lastmod>
					<changefreq>$changefrequency</changefreq>
					<priority>$prioritas</priority>
				</url>\r\n";		
    }
	for ($i = 1; $i < $currlevel; $i++) {
		$html_standalone .= "</ul>\r\n";
	}
    $html_standalone .= "</ul>\r\n";

	$sql = "SELECT id, cat_id, title FROM catalogdata WHERE publish='1' ORDER BY cat_id, id";
    $result = $mysql->query($sql);
    while (list($id,$cat_id, $title) = $mysql->fetch_array($result)) {
        $titleurl["pid"] = $title;	//untuk menampilkan nama produk di prettyurl
		$url = $urlfunc->makePretty("?p=catalog&action=detail&pid=$id&cat_id=$cat_id", $titleurl);
		$xml_standalone .= "
				<url>
					<loc>".xmlurl($url)."</loc>
					<lastmod>$tanggal</lastmod>
					<changefreq>$changefrequency</changefreq>
					<priority>$prioritas</priority>
				</url>\r\n";		
	}

?>