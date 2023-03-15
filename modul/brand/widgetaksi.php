<?php
if ($jenis=='catlist') { 
	$sql = "SELECT id, nama FROM catalogmerek ORDER BY nama";
	$result = $mysql->query($sql);
	if ($mysql->num_rows($result)>0) {
		$widget .= "<ul id=\"brandwidget\">\r\n";
		while (list($idmerek, $merek) = $mysql->fetch_row($result)) {
			$titleurl = array();
			$titleurl["merek"] = $merek;
			$widget .= "<li><a href=\"".$urlfunc->makePretty("?p=catalog&action=brand&merek=$idmerek", $titleurl)."\">$merek</a></li>\r\n";
		}
		$widget .= "</ul>\r\n";
	} else {
		$widget .= "<p>"._NOBRAND."</p>\r\n";
	}
}
?>
