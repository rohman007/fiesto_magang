<?php
$sql = "SELECT id, judul, deskripsi, tglmulai, tglselesai, filename, kota, lokasi FROM event WHERE tglselesai>=CURDATE() ORDER BY tglmulai ASC";
$widgetmaxevent = $isi;
if (!ctype_digit($widgetmaxevent)) $widgetmaxevent=0;
if ($widgetmaxevent > 0) $sql .= " LIMIT $widgetmaxevent";
$result = $mysql->query($sql);
if ($mysql->num_rows($result) > 0) {
	$result = $mysql->query($sql);
	$widget .= "<ul id=\"eventwidget\" itemscope itemtype=\"http://schema.org/Event\">";
	while (list($id, $judul, $deskripsi, $tglmulai, $tglselesai, $filename, $kota, $lokasi) = $mysql->fetch_row($result)) {
		$widget .= "<li>";
		$titleurl = array();
		$titleurl["pid"] = $judul;
		$widget .= "<a itemprop=\"url\" href=\"".$urlfunc->makePretty("?p=event&action=view&pid=$id",$titleurl)."\">";
		$widget .= "<div class=\"judulevent\" itemprop=\"name\">$judul</div></a>\r\n";
		if($filename!="" && file_exists("$cfg_thumb_path/$filename"))
		{	
			// $widget .= "<div class=\"gambarevent\">\r\n";
			// $widget .= "<a itemprop=\"url\" href=\"".$urlfunc->makePretty("?p=event&action=view&pid=$id",$titleurl)."\"><img itemprop=\"image\" src=\"$cfg_thumb_url/$filename\" alt=\"$title\" border=\"0\" /></a>\r\n";
			// $widget .= "</div>\r\n";
			$widget .= "<div class=\"image-event\"><img itemprop=\"image\" class=\"img-event img-responsive\" src=\"$cfg_thumb_url/$filename\" alt=\"$judul\"></div>";
		}

		// $widget .= "<a itemprop=\"url\" href=\"".$urlfunc->makePretty("?p=event&action=view&pid=$id",$titleurl)."\">";
		// $widget .= "<div class=\"judulevent\" itemprop=\"name\">$judul</div></a>\r\n";
		if($lokasi!='' or $lokasi!='')
		{
			$widget.="<div itemprop=\"location\" itemscope itemtype=\"http://schema.org/PostalAddress\">";
		}
		if ($lokasi!='') $widget .= "<div class=\"lokasievent\" itemprop=\"streetAddress\">$lokasi</div>\r\n";
		if ($kota!='') $widget .= "<div class=\"kotaevent\" itemprop=\"addressLocality\">$kota</div>\r\n";
		
		if($lokasi!='' or $lokasi!='')
		{
			$widget.="</div>";
		}
			
		$widget .= "<div class=\"tglevent\">";
		$ttglmulai=$tglmulai;
		$ttglselesai=$tglselesai;
		$tglmulai = tglformat($tglmulai);
		$tglselesai = tglformat($tglselesai);
		$mulai = explode(" ",$tglmulai);
		$selesai = explode(" ",$tglselesai);
		if ($mulai[2] == $selesai[2]) {
			if ($mulai[1] == $selesai[1]) {
				if ($mulai[0] == $selesai[0]) {
					$widget .= "<meta itemprop='startDate' content='$ttglmulai'> $mulai[0] $mulai[1] $mulai[2]";
				} else {
					$widget .= "<meta itemprop='startDate' content='$ttglmulai'> $mulai[0]-<meta itemprop='endDate' content='$ttglselesai'>$selesai[0] $mulai[1] $mulai[2]";
				}
			} else {
				$widget .= "<meta itemprop='startDate' content='$ttglmulai'> $mulai[0] $mulai[1] - <meta itemprop='endDate' content='$ttglselesai'> $selesai[0] $selesai[1] $mulai[2]";
			}
		} else {
			$widget .= "<meta itemprop='startDate' content='$ttglmulai'> $mulai[0] $mulai[1] $mulai[2] - <meta itemprop='endDate' content='$ttglselesai'> $selesai[0] $selesai[1] $selesai[2]";
		}
		$widget .= "</div>";
		$widget .= "</li>\r\n";
	}
	$widget .= "</ul>";
} else {
	$widget .= "<p>"._NOEVENT."</p>\r\n";
}
?>
