<?php
if ($jenis=='newest') {
	list($widgetcatid,$widgetmaxnews)=explode(';',$isi);
	if (!ctype_digit($widgetmaxnews)) $widgetmaxnews=5;
	$syarat = ($widgetcatid==0) ? "1=1" : "cat_id='$widgetcatid'";
	$sql = "SELECT id, tglberita, judulberita FROM newsdata WHERE $syarat AND publish='1' ";
	if($config_site_ismembership and !isset($_SESSION['member_uid']))
	{	$sql .= "AND memberonly='0' ";
	}
	$sql .= " ORDER BY tglberita DESC LIMIT $widgetmaxnews";
	
	$result = $mysql->query($sql);
	if ($result and $mysql->num_rows($result) > 0) {
		$result = $mysql->query($sql);		
		// $widget .= "<ul class=\"listnews\">\r\n";
		
		while (list($id, $tglberita, $judulberita) = $mysql->fetch_row($result)) {
			$titleurl = array();
			$titleurl["pid"] = $judulberita;
			$widget .= "<div class=\"media\">\r\n";
			$widget .= "<a class=\"media-left\" href=\"#\">\r\n";
			$widget .= "<img data-holder-rendered=\"true\" src=\"data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iNjQiIGhlaWdodD0iNjQiIHZpZXdCb3g9IjAgMCA2NCA2NCIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+PGRlZnMvPjxyZWN0IHdpZHRoPSI2NCIgaGVpZ2h0PSI2NCIgZmlsbD0iI0VFRUVFRSIvPjxnPjx0ZXh0IHg9IjEyLjUiIHk9IjMyIiBzdHlsZT0iZmlsbDojQUFBQUFBO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1mYW1pbHk6QXJpYWwsIEhlbHZldGljYSwgT3BlbiBTYW5zLCBzYW5zLXNlcmlmLCBtb25vc3BhY2U7Zm9udC1zaXplOjEwcHQ7ZG9taW5hbnQtYmFzZWxpbmU6Y2VudHJhbCI+NjR4NjQ8L3RleHQ+PC9nPjwvc3ZnPg==\" style=\"width: 64px; height: 64px;\" data-src=\"holder.js/64x64\" alt=\"64x64\">\r\n";
			$widget .= "</a>\r\n";
			if ($isdateshown) 
			// $widget .= "<li><span class=\"widgetnewsdate\">".tglformat($tglberita)."</span><br />\r\n";
			// $widget .= "<a href=\"".$urlfunc->makePretty("?p=news&action=shownews&pid=$id", $titleurl)."\"><span class=\"widgetnewstitle\">$judulberita<span></a></li>\r\n";
			$widget .= "<div class=\"media-body\">\r\n";
			$widget .= "<a href=\"".$urlfunc->makePretty("?p=news&action=shownews&pid=$id", $titleurl)."\"><h4 class=\"media-heading\">$judulberita</h4></a>\r\n";
			$widget .= "<div class=\"date_news_widget\">".tglformat($tglberita)."</div> \r\n";
			$widget .= "</div>\r\n";
			$widget .= "</div>\r\n";
			
		}
		// $widget .= "</ul>\r\n";
	} else {
		$widget .= "<p>"._NONEWS."</p>\r\n";
	}
}

if ($jenis=='catlist') {
	$sql = "SELECT id, nama FROM newscat ORDER BY urutan";
	$result = $mysql->query($sql);
	if ($mysql->num_rows($result) > 0) {
		$result = $mysql->query($sql);		
		$widget .= "<ul class=\"catlist list-unstyled\">\r\n";
		while (list($id, $nama) = $mysql->fetch_row($result)) {
			$titleurl = array();
			$titleurl["cat_id"] = $nama;
			
			$widget .= "<li><a href=\"".$urlfunc->makePretty("?p=news&action=latest&cat_id=$id", $titleurl)."\">$nama</a></li>\r\n";
		}
		$widget .= "</ul>\r\n";
	} else {
		$widget .= "<p>"._NONEWS."</p>\r\n";
	}
}

if ($jenis=='rss') {
	$sql = "SELECT id, nama FROM newscat ORDER BY urutan";
	$result = $mysql->query($sql);
	$widget .= "<ul class='newsrss list-unstyled'>\r\n";
	$widget .= "<li><a href=\"$cfg_app_url/newsfeed.php\">"._ALLCAT."</a></li>\r\n";
	while (list($id, $nama) = $mysql->fetch_row($result)) {
		$widget .= "<li><a href=\"$cfg_app_url/newsfeed.php?cat_id=$id\">$nama</a></li>\r\n";
	}
	$widget .= "<ul>\r\n";
}

if ($jenis=='archive') {
	$sql = "SELECT tglberita FROM newsdata";
	if ($isi>0) $sql .= " WHERE cat_id='$isi'";
	$sql .= " ORDER BY tglberita DESC";
	$result = $mysql->query($sql);
	
	$widget .= "<ul class=\"archive list-unstyled\">\r\n";
	while(list($tglberita) = $mysql->fetch_row($result)) {	
		if (($timestamp = strtotime($tglberita)) !== -1) {
			$i=getdate($timestamp);
			$angkabulan=$i[mon]-1;
			if ($namabulan[$angkabulan] != $bulanini) {
				$tahun = $i[year];
				if ($isi>0) {
					$sqlcat = "SELECT id, nama FROM newscat WHERE id='$isi' ";
					$resultcat = $mysql->query($sqlcat);
					list($id, $nama) = $mysql->fetch_row($resultcat);
	
					$titleurl = array();
					$titleurl["cat_id"] = $nama;
					
					$widget .= "<li><a href=\"".$urlfunc->makePretty("?p=news&action=archive&cat_id=$isi&tahun=$tahun&bulan=$i[mon]", $titleurl)."\">$namabulan[$angkabulan] $tahun</a>\r\n";
				} else {
					$widget .= "<li><a href=\"".$urlfunc->makePretty("?p=news&action=archive&tahun=$tahun&bulan=$i[mon]")."\">$namabulan[$angkabulan] $tahun</a>\r\n";
				}
				$bulanini = $namabulan[$angkabulan];
			}
		}
	}
	$widget .= "</ul>\r\n";
}

if ($jenis=='carousel') {
	list($widgetcatid,$widgetmaxnews)=explode(';',$isi);
	if (!ctype_digit($widgetmaxnews)) $widgetmaxnews=5;
	$syarat = ($widgetcatid==0) ? "1=1" : "cat_id='$widgetcatid'";
	$sql = "SELECT id, tglberita, judulberita, summary FROM newsdata WHERE $syarat AND publish='1' ";
	if($config_site_ismembership and !isset($_SESSION['member_uid']))
	{	$sql .= "AND memberonly='0' ";
	}
	$sql .= "ORDER BY tglberita DESC LIMIT $widgetmaxnews";
	$result = $mysql->query($sql);
	if ($mysql->num_rows($result) > 0) {
		$result = $mysql->query($sql);
		$widget .= "<script type=\"text/javascript\" src=\"js/jcarousellite/jcarousellite_1.0.1.pack.js\"></script>\r\n";
		$widget .= "<link rel=\"stylesheet\" href=\"js/jcarousellite/newscarousel.css\" type=\"text/css\" media=\"screen\" />\r\n";
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
		$widget .= "<div id=\"slider1_container\" style=\"position: relative; top: 0px; left: 0px; width:600px; max-width: 100%;height: 300px;\">\r\n";
		$widget .= "<div u=\"slides\" style=\"cursor: move; position: absolute; left: 0px; top: 0px; width:600px; max-width: 100%; height: 300px;overflow: hidden;\">\r\n";
		// $widget .= "<div class=\"newsticker-jcarousellite\">\r\n";
		while (list($id, $tglberita, $judulberita, $summary) = $mysql->fetch_row($result)) {
			if ($isdateshown) $widget .= "<div style=\"margin-bottom:20px;\">\r\n";
			$widget .= "<span class=\"widgetnewsdate\">".tglformat($tglberita)."</span><br />\r\n";
			$widget .= "<div><a href=\"$cfg_app_url/index.php?p=news&action=shownews&pid=$id\"><span class=\"widgetnewstitle\"><strong>$judulberita</strong></span></a></div>\r\n";
			$widget .= "$summary</li>\r\n";
			$widget .= "</div>\r\n";
		}
		$widget .= "</div>\r\n";
		$widget .= "</div>\r\n";
	} else {
		$widget .= "<p>"._NONEWS."</p>\r\n";
	}
}


if($jenis=='search'){
	// $widget .= "<div id=\"searchnews\" align=\"center\">\r\n";
	// $widget .= "<form method=\"POST\" action=\"".$urlfunc->makePretty("?p=news&action=search")."\">\r\n";
	// $widget .= "<input type=\"text\" name=\"keyword\" id=\"keyword\" class=\"searchtext\" /> <input type=\"submit\" value=\""._SEARCH."\" class=\"searchubmit\" />\r\n";
	// $widget .= "</form>\r\n";
	// $widget .= "</div>\r\n";
	
	$widget .= "<div class=\"input-group search\">\r\n";
	$widget .= "<form action=\"".$urlfunc->makePretty("?p=news&action=search")."\" method=\"POST\">\r\n";
	$widget .= "<input type=\"text\" class=\"form-control\" name=\"keyword\">\r\n";
	$widget .= "<span class=\"input-group-btn\">\r\n";
	$widget .= "<button type=\"submit\" value=\""._SEARCH."\" class=\"btn btn-default\">\r\n";
	$widget .= "<span class=\"glyphicon glyphicon-search\"></span>\r\n";
	$widget .= "</button>\r\n";
	$widget .= "</span>\r\n";
	$widget .= "</form>\r\n";
	$widget .= "</div>\r\n";
}
?>