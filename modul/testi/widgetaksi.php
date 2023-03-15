<?php
if ($jenis=='carousel') {
	$sql = "SELECT id, filename, judul, summary, nama FROM testi WHERE publish='1' ";
	$result = mysql_query($sql);
	if (mysql_num_rows($result) > 0) {
		$result = mysql_query($sql);
		// $widget .= "<script type=\"text/javascript\" src=\"$cfg_app_url/js/jcarousellite/jcarousellite_1.0.1.pack.js\"></script>\r\n";
		// $widget .= "<link rel=\"stylesheet\" property=\"stylesheet\" href=\"$cfg_app_url/js/jcarousellite/newscarousel.css\" type=\"text/css\" media=\"screen\" />\r\n";
		// $widget .= '<script>$(document).ready(function() {
			// $(".newsticker-jcarousellite").jCarouselLite({
				// vertical: true,
				// hoverPause:true,
				// visible: 1,
				// auto:2000,
				// speed:2000
			// });
		// });</script>
		// ';
		$widget .= "<div class=\"newsticker-jcarousellite\">\r\n";
		$widget .= "<ul>\r\n";
		while (list($id, $filename, $judul, $summary, $nama) = mysql_fetch_row($result)) {
			$titleurl = array();
			$titleurl['pid'] = $judul;
			$testifoto = ($filename!='' && file_exists("$cfg_thumb_path/$filename")) ? "<a class=\"media-left\" href=\"".$urlfunc->makePretty("?p=testi&action=view&pid=$id", $titleurl)."\"><img src=\"$cfg_thumb_url/$filename\" style=\"width:64px;height:64px\"></a>" : "";
			$widget .= "
				<li>
					<div class=\"media\">$testifoto 
					<div class=\"media-body\">
						<div class=\"clients-name\"><a href=\"".$urlfunc->makePretty("?p=testi&action=view&pid=$id", $titleurl)."\">$nama</a></div>
						<div class=\"clints-info\">".(($judul != '') ? " - $judul" : "")."</div>
						$summary
					</div>
					</div>
				</li>";
		}
		$widget .= "</ul>\r\n";
		$widget .= "</div>\r\n";
	} else {
		$widget .= "<p>"._NOTESTI."</p>\r\n";
	}
}
?>
