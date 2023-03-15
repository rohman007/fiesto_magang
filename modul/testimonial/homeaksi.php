<?php
$sql = "SELECT id, nama, foto FROM zoomer WHERE status='1' ORDER BY tanggal DESC LIMIT 100";
$result = $mysql->query($sql);
$home .= "<p align=\"center\"><a href=\"?p=testimonial\">"._ADDCOMMENTHUMB."</a></p>";
if ($mysql->num_rows($result) > 0) {
	$result = $mysql->query($sql);
	$home .= "<ul class=\"thumb\">\r\n";
	while (list($id, $nama, $foto) = $mysql->fetch_row($result)) {
		$home .= "<li><a href=\"zoom.php?pid=$id&width=600&height=240\" rel=\"sexylightbox\"><img src=\"$cfg_comment_url/$foto\" alt=\"$nama\" /></a></li>\r\n";
	}
	$home .= "</ul>\r\n";
	$sql = "SELECT id, nama, foto FROM zoomer WHERE status='1'";
	$result = $mysql->query($sql);
	if ($mysql->num_rows($result)>100) $home .= "<p align=\"center\"><a href=\"?p=testimonial\">Lihat semua pesan</a></p>";
}
?>
