<?php
function show_me_the_list() {
	global $cfg_thumb_url, $photo;

	$catcontent .= "<ul class=\"testilist\">";
	while (list($id, $filename, $nama, $judul, $publish, $keterangan) = mysql_fetch_row($photo)) {
		$titleurl = array();
		$titleurl["pid"] = $photo_title;
								
		$catcontent .= "<li class=\"namatesti\"><a href=\"?p=testi&action=showtesti&pid=$id\">$judul</a> ($nama)</li>\r\n";
	}
	$catcontent .= "</ul>\r\n";
	return $catcontent;
}
?>