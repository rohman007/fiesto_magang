<?php
if ($jenis=='carousel') {
	$sql = "SELECT id, nama, komentar FROM testimonial WHERE status='1' ";
	$result = $mysql->query($sql);
	if ($mysql->num_rows($result) > 0) {
		$result = $mysql->query($sql);
		$widget .= "<script type=\"text/javascript\" src=\"$cfg_app_url/js/jcarousellite/jcarousellite_1.0.1.pack.js\"></script>\r\n";
		$widget .= "<link rel=\"stylesheet\" href=\"$cfg_app_url/js/jcarousellite/newscarousel.css\" type=\"text/css\" media=\"screen\" />\r\n";
		$widget .= '<script>$(document).ready(function() {
			$(".newsticker-jcarousellite").jCarouselLite({
				vertical: true,
				hoverPause:true,
				visible: 1,
				auto:2000,
				speed:2000
			});
		});</script>
		';
		$widget .= "<div class=\"newsticker-jcarousellite\">\r\n";
		$widget .= "<ul>\r\n";
		while (list($id, $nama, $judul) = $mysql->fetch_row($result)) {
			// if($filename!='')
				// $testifoto = "<img src=\"$cfg_thumb_url/$filename\" border=\"0\"><br />";
			// else
				// $testifoto = "";
			$widget .= "
				<li>
					<span class=\"pengirimtesti\"><strong>$nama</strong></span><br /><br />
					$judul
				</li>";
		}
		$widget .= "</ul>\r\n";
		$widget .= "</div>\r\n";
		
		$widget .= "<div class='showalltesti'><a href=\"$cfg_app_url/index.php?p=testimonial\">"._SHOWMORETESTI."</a> </div>\r\n";
	} else {
		$widget .= "<p>"._NOTESTI."</p>\r\n";
	}
}
?>
