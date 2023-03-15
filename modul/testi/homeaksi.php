<?php
if ($jenis=='newest') {
	
	$limit = ($isi != '') ? "LIMIT $isi" : "" ;
	$sql = "SELECT id, filename, nama, judul, summary, filename FROM testi WHERE publish='1' ORDER BY ishot DESC, judul, nama";
	$sql .= " $limit";
	$result = mysql_query($sql);
	$home .= "<div class=\"col-lg-12\">\r\n";
	$home .= "<div class=\"row testimonials\">\r\n";
	if (mysql_num_rows($result) > 0) {
		while (list($id, $filename, $nama, $judul, $summary, $filename) = mysql_fetch_row($result)) {
			$titleurl = array();
			$titleurl['pid'] = $judul;
			// ".(($judul != '') ? "<div class=\"titletesti\">$judul</div>" : "")."
			
			$thumbnail = (file_exists("$cfg_thumb_path/$filename") && $filename != '') ? "$cfg_thumb_url/$filename" : "./images/gravatar.jpg";
			$home .= "
				<div class=\"col-sm-4\">
					<blockquote>
						<p class=\"clients-words\">$summary</p>
						<a class=\"btn more\" href=\"".$urlfunc->makePretty("?p=testi&action=view&pid=$id", $titleurl)."\">"._READMORE."</a>
						<span class=\"clients-name\">&mdash; $nama</span>
						<img src=\"$thumbnail\" class=\"img-circle img-thumbnail\">
					</blockquote>
				</div>			
			";
		}
	$home .= "</div>\r\n";
	$home .= "</div>\r\n";
	} else {
		$home .= _NOTESTI;
	}
}
?>