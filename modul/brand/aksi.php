<?php

$sql = "SELECT judulfrontend FROM module WHERE nama='brand'";
$result = $mysql->query($sql);
list($title) = $mysql->fetch_row($result);
$sql = "SELECT id, nama FROM catalogmerek ORDER BY nama";
$result = $mysql->query($sql);
if ($mysql->num_rows($result)>0) {
	$alfabet = '';
	while (list($idmerek, $merek) = $mysql->fetch_row($result)) {
		if ($alfabet != strtoupper(substr($merek,0,1))) {
			$alfabet = strtoupper(substr($merek,0,1));
			if ($alfabet != '') $content .= "</ul>\r\n";
			$content .= "<h2>$alfabet</h2>\r\n";
			$content .= "<ul>\r\n";
		}
		$titleurl = array();
		$titleurl["merek"] = $merek;
		$content .= "<li><a href=\"".$urlfunc->makePretty("?p=catalog&action=brand&merek=$idmerek", $titleurl)."\">$merek</a></li>\r\n";
	}
	$content .= "</ul>\r\n";
} else {
	$content .= "<p>"._NOBRAND."</p>\r\n";
}
?>
