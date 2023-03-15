<?php 

if ($jenis=='carousel') { 
	$sql = "SELECT id, nama, filename FROM catalogmerek ORDER BY nama";
	$result = $mysql->query($sql);
	if ($mysql->num_rows($result)>0) {
		$home .= "<div id=\"brandhome\" class=\"owl-carousel carousel-brand\">";
		while (list($idmerek, $merek, $filename) = $mysql->fetch_row($result)) {
			$thumbnail = ($filename != '' && file_exists("$cfg_thumb_path/$filename")) ? "<img src=\"$cfg_thumb_url/$filename\" alt=\"$merek\">" : "";
			$titleurl = array();
			$titleurl["merek"] = $merek;
			$home .= "<div class=\"item\">$thumbnail<a href=\"".$urlfunc->makePretty("?p=catalog&action=brand&merek=$idmerek", $titleurl)."\"><span>$merek</span></a></div>\r\n";
		}
		$home .= "</div>\r\n";
	} else {
		$home .= "<p>"._NOBRAND."</p>\r\n";
	}
}