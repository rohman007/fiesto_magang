<?php
if ($jenis=='category') {
	list($homecatid,$homemaxnews)=explode(';',$isi);
	if ($homemaxnews=='') $homemaxnews=5;
	$sql = "SELECT id, cat_id, tglmuat, judulberita, summary, isiberita, tglberita, ishot FROM newsdata WHERE publish='1'";
	if ($homecatid>0) $sql .= " AND cat_id='$homecatid'";
	$sql .= " ORDER BY tglberita DESC LIMIT $homemaxnews";
	$result = $mysql->query($sql);
	if ($result and $mysql->num_rows($result)>0) {
		$x = 1;
		$home .= "<ul class=\"newshome\"  itemscope itemtype=\"http://schema.org/NewsArticle\">\r\n";
		while (list($id, $cat_id, $tglmuat, $judulberita, $summary, $isiberita, $tglberita, $ishot) = $mysql->fetch_row($result)) {
			$titleurl = array();
			$titleurl["pid"] = $judulberita;
			
			$home .= "<li><span class=\"newstitle\" itemprop=\"name\"><a itemprop=\"url\" href=\"".$urlfunc->makePretty("?p=news&action=shownews&pid=$id", $titleurl)."\">$judulberita</a></span>\r\n";
			if ($isdateshown) $home .= "<br /><span class=\"newsdate\"><meta itemprop=\"datePublished\" content=\"$tglberita\">".tglformat($tglberita)."</span>\r\n";
			if ($issummary && $summary!='') $home .= "<br /><div class=\"newsshortdesc\" temprop=\"description\">$summary</div>\r\n";
			$home .= "</li>\r\n";
			
		}
		$home .= "</ul>\r\n";
	} else {
		$home .= "<p>"._NONEWS."</p>";
	}
}
?>
