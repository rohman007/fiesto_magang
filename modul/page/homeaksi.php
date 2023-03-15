<?php
if ($jenis=='single') {
	$sql = "SELECT isi FROM page WHERE id='$isi'";
	$result = $mysql->query($sql);
	while (list($isihalaman) = $mysql->fetch_row($result)) {
		$home .= "<div class=\"isihalaman\">$isihalaman</div>\r\n";
	}
}
?>