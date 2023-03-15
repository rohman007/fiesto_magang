<?php
$keyword = fiestolaundry($_POST['keyword'],500);
$screen = fiestolaundry($_GET['screen'],11);
// $pid = fiestolaundry($_GET['pid'],11);
// $cat_id = fiestolaundry($_GET['cat_id'],11);

if ($_SEG[1] != 'archive') {
	$tempid = explode('_',$_SEG[2]);
	$pid = $tempid[0];
	$cat_id = $tempid[0];
}
// $tahun = fiestolaundry($_GET['tahun'],4);
// $bulan = fiestolaundry($_GET['bulan'],2);

$sql = "SELECT judulfrontend FROM module WHERE nama='news'";

$result = $mysql->query($sql);
list($judulmodul) = $result->fetch_row();
$title = $judulmodul;

if ($cat_id=='') $cat_id=0;

require_once 'aplikasi/schema.php';

if ($action=='' or $action=='latest') {
	$maxlistnews = 6;
	$sql = "SELECT id, cat_id, tglmuat, judulberita, summary, isiberita, tglberita, ishot,thumb FROM newsdata WHERE publish='1'";
	if ($cat_id>0) $sql .= " AND cat_id='$cat_id'";
	$sql .= " ORDER BY tglberita DESC ";
	if ($maxlistnews>0) $sql .= " LIMIT $maxlistnews ";
	if ($cat_id>0) {
		$sql1 = "SELECT nama FROM newscat WHERE id='$cat_id'";
		$result1 = $mysql->query($sql1);
		list($catname) = $mysql->fetch_row($result1);
		$title = "$judulmodul: $catname";
		
		//overriding meta tags by jun 5:54 17/01/2012
		$config_site_titletag = "$catname - $config_site_titletag";		
	} else {
		$title = "$judulmodul";
	}

	$result = $mysql->query($sql);
	if ($mysql->num_rows($result)>0) {
		$content .= '<div class="col-lg-12" id="results"></div>';
		$content .= "<div id=\"results\" class=\"row block-list-agenda\">";
		// $content .= "<ul class=\"listnews\" itemscope itemtype=\"http://schema.org/NewsArticle\">\r\n";
		// while (list($id, $cat_id, $tglmuat, $judulberita, $summary, $isiberita, $tglberita, $ishot, $thumb) = $mysql->fetch_row($result)) {
			// $titleurl = array();
			// $titleurl["pid"] = $judulberita;
			// $content .= "					
					// <div class=\"col-sm-4\" itemscope itemtype=\"http://schema.org/NewsArticle\">
						// <div class=\"panel-body news-colx\">";
							// if ($thumb != '' && file_exists("$cfg_thumb_path/$thumb")) {
								// $content .= "
								// <div class=\"img-news\">
									// <img class=\"img-responsive\" alt=\"$judulberita\" src=\"$cfg_thumb_url/$thumb\">
								// </div>	<!-- /.img-news -->\r\n";
							// }
							// $content .= "
								// <div class=\"news-content caption-news-thumb\">
									// <h1 class=\"newstitle\" itemprop=\"name\"><a href=\"".$urlfunc->makePretty("?p=news&action=shownews&pid=$id", $titleurl)."\">$judulberita</a></h1>";
							// if ($isdateshown) $content .= "<span class=\"newsdate\"><meta itemprop=\"datePublished\" content=\"$tglberita\"/>".tglformat($tglberita)."</span><br/>";
							// if ($issummary && $summary!='') $content .= "<div class=\"newsshortdesc\" temprop=\"description\">$summary</div>";
							// $content .= "<a itemprop=\"url\" href=\"".$urlfunc->makePretty("?p=news&action=shownews&pid=$id", $titleurl)."\" class=\"btn btn-default more\">"._LEARNMORE."</a>
								// </div>	<!-- /.news-content -->\r\n
							// ";
							// $content .= "
						// </div>	<!-- /.panel-body -->\r\n
					// </div>	<!-- /.col-sm-6 col-md-6 -->";			
			// // $content .= "<li><span class=\"judulberita\" itemprop=\"name\"><a itemprop=\"url\" href=\"".$urlfunc->makePretty("?p=news&action=shownews&pid=$id", $titleurl)."\">$judulberita</a></span>\r\n";
			// // if ($isdateshown) $content .= "<br /><span class=\"tglmuatberita\"><meta itemprop=\"datePublished\" content=\"$tglberita\">".tglformat($tglberita)."</span>\r\n";
			// // if ($issummary && $summary!='') $content .= "<br /><div class=\"ringkasanberita\" itemprop=\"description\">$summary</div>\r\n";
			// // $content .= "</li>";
		// }
		// // $content .= "</ul>\r\n";
		// $content .= "</div>";
		if ($cat_id>0) {
			$sqlcat = "SELECT nama FROM newscat WHERE id='$cat_id'";
			$resultcat = $mysql->query($sqlcat);
			list($catname) = $resultcat->fetch_row();
			
			$titleurl = array();
			$titleurl["cat_id"] = $catname;
			
			// $content .= "<p><a class=\"btn btn-default more\" href=\"".$urlfunc->makePretty("?p=news&action=archive&cat_id=$cat_id", $titleurl)."\">"._ARCHIVE."</a></p>";
		} else {
			// $content .= "<p><a class=\"btn btn-default more\" href=\"".$urlfunc->makePretty("?p=news&action=archive")."\">"._ARCHIVE."</a></p>";
			
			// $content .= "<p id=""><a class=\"btn btn-default more\" href=\"javascript:void(0)\">"._LOADMOREDATA."</a></p>";
			$content .= '
			<div id="loader_image"><img src=images/loader.gif" alt="" width="24" height="24"> Loading...please wait</div>
			<div class="margin10"></div>
			<div id="loader_message"></div>';
		}
	} else {
		$content .= "<p>"._NONEWS."</p>";
	}
}

if ($action=='archive') {
	$tahun = $_SEG[2];
	$bulan = $_SEG[3];
	if ($tahun=='' || $bulan=='') {
		if ($cat_id>0) {
			$sql = "SELECT nama FROM newscat WHERE id='$cat_id'";
			$result = $mysql->query($sql);
			list($catname) = $mysql->fetch_row($result);
			$title = $judulmodul.": "._ARCHIVE." - $catname";
			$sql = "SELECT tglberita FROM newsdata WHERE cat_id='$cat_id' ORDER BY tglberita DESC";
		} else {
			$title = $judulmodul.": "._ARCHIVE;
			$sql = "SELECT tglberita FROM newsdata ORDER BY tglberita DESC";
		}

		$result = $mysql->query($sql);
		if($mysql->num_rows($result)>0)
		{	
			$content = "<ul class=\"list-arsip\">\r\n";
			while(list($tglberita) = $mysql->fetch_row($result)) {
				if (($timestamp = strtotime($tglberita)) !== -1) {
					$i=getdate($timestamp);
					$angkabulan=$i[mon]-1;
					if ($namabulan[$angkabulan] != $bulanini) {
						$tahun = $i[year];
						if ($cat_id==0) {
							$content .= "<li><a href=\"".$urlfunc->makePretty("?p=news&action=archive&tahun=$tahun&bulan=$i[mon]")."\">$namabulan[$angkabulan] $tahun</a>\r\n";
						} else {
							$titleurl = array();
							$titleurl["cat_id"] = $catname;
						
							$content .= "<li><a href=\"".$urlfunc->makePretty("?p=news&action=archive&cat_id=$cat_id&tahun=$tahun&bulan=$i[mon]", $titleurl)."\">$namabulan[$angkabulan] $tahun</a>\r\n";
						}
						$bulanini = $namabulan[$angkabulan];
					}
				}
			}
			$content .= "</ul>\r\n";
		}
		else
		{	$content = "<p>"._NOARCHIVE."</p>\r\n";
			$content .= "<p><a href=\"javascript:history.back()\" class=\"btn btn-default more\">"._BACK."</a></p>\r\n";
		}
		
	} else {
		$angkabulan=$bulan-1;
		// $temp = explode('_', $tahun);
		$tahun = ($cat_id > 0) ? $_SEG[3] : $_SEG[2];
		$bulan = ($cat_id > 0) ? $_SEG[4] : $_SEG[3];
		if ($cat_id>0) {
			$sql = "SELECT nama FROM newscat WHERE id='$cat_id'";
			$result = $mysql->query($sql);
			list($catname) = $mysql->fetch_row($result);
			$title = $judulmodul.": "._ARCHIVE." - $catname - $namabulan[$angkabulan] $tahun";
		} else {
			$title = $judulmodul.": "._ARCHIVE." - $namabulan[$angkabulan] $tahun";
		}
		$content = archive($tahun,$bulan,$cat_id);
	}
}


if ($action=='shownews') {
	if ($pid=='') {
		$title = _ERROR;
		$content = _NONEWS." <a href=\"javascript:history.back()\" class=\"btn btn-default more\">"._BACK."</a>.";
	} else {
		$sql = "UPDATE newsdata SET clickctr=clickctr+1 WHERE id='$pid' AND publish='1'";
		$mysql->query($sql);
		$sql = "SELECT id,tglmuat,summary,judulberita,isiberita,tglberita, memberonly, thumb FROM newsdata WHERE id='$pid' AND publish='1' ";		
		$result = $mysql->query($sql);
		
		$schema = new Schema();
		$content .= $schema->SingleNews($sql);
		if ($mysql->num_rows($result) > 0) 
		{	
			list($id, $tglmuat, $summary, $judulberita, $isiberita, $tglberita, $memberonly, $thumb) = $result->fetch_row();
			// $title = $judulberita;
			$content .= "
				<div class=\"title_news_detail title_detail\"><h4>$judulberita</h4></div>
				<div class=\"date_news\"><span class=\"glyphicon glyphicon-time\"></span> ". _POSTEDON . " " .tglformat($tglberita). "</div>";
				// <hr>";
			if ($thumb!='') {
				$_filename = explode(':', $thumb);
				if (count($_filename) > 1) {
					$content .= "<div id=\"myCarousel\" class=\"carousel page slide\" data-ride=\"carousel\">";
					$content .= "<!-- Indicators -->
									<ol class=\"carousel-indicators\">";
										for($i = 0; $i < count($_filename); $i++) {
											$active = ($i == 0) ? "active" : "";
											$content .= "<li data-target=\"#myCarousel\" data-slide-to=\"$i\" class=\"$active\"></li>";
										}
					$content .= "</ol>";
					$i = 0;
					$content .= "
								<div class=\"carousel-inner\">";
									foreach($_filename as $file) {
										$active = ($i == 0) ? "active" : "";
										$content .= "<div class=\"item $active\">";
										$content .= "<img class=\"img-responsive\" alt=\"$judulberita\" src=\"$cfg_fullsizepics_url/$file\">";
										$content .= "</div>";
										$i++;
									}
					$content .= "</div>";
					$content .= "
								<a class=\"left carousel-control\" href=\"#myCarousel\" data-slide=\"prev\"><span class=\"glyphicon glyphicon-chevron-left\"></span></a>
								<a class=\"right carousel-control\" href=\"#myCarousel\" data-slide=\"next\"><span class=\"glyphicon glyphicon-chevron-right\"></span></a>";
					$content .= "</div><hr>";
				} else {
					$content .= "
						<img class=\"img-responsive\" src=\"$cfg_fullsizepics_url/$thumb\" alt=\"$judulberita\">
						<hr>";
				}
			}
			// $content .= "<meta itemprop=\"description\" content=\"$isiberita\">".$isiberita;
			$content .= "<div class=\"isiberita\"  itemscope itemtype=\"http://schema.org/NewsArticle\">
			<meta itemprop=\"name\" content=\"$judulberita\">
			<span itemprop=\"description\">".$isiberita."</span>";

			//overriding meta tags by jun 5:54 17/01/2012
			$config_site_titletag = "$judulberita - $config_site_titletag";
			if ($summary != '') $config_site_metadescription = strip_tags($summary);
			
			// if ($isdateshown) $content .= "<p class=\"newsdatesource\"><meta itemprop=\"datePublished\" content=\"$tglberita\">".tglformat($tglberita)."</p>\r\n";
			$content .= "<p><a href=\"$cfg_app_url/newsprint.php?pid=$pid\" target=\"_blank\"><img src=\"$cfg_app_url/images/printer.gif\" border=\"0\" width=\"23\" height=\"24\" alt=\""._PRINT."\" /></a></p>\r\n";
			$content .= "</div>";
		} 
		else 
		{	$title = _ERROR;
			$content = "<p>"._NONEWS."</p>\r\n";
			$content .= "<p><a href=\"javascript:history.back()\" class=\"btn btn-default more\">"._BACK."</a></p>\r\n";
		}
	}
}

if ($action=='search') {
	if (strlen($keyword)>0) {
		$keyword=strip_tags($keyword);
		$r_keyword=preg_split("/[\s,]+/",$keyword);
		$sql = "SELECT id,tglmuat, judulberita, isiberita, tglberita FROM newsdata WHERE publish='1' AND (";
				
		foreach ($r_keyword as $j=>$splitkeyword) {
			$sql .= ($j==0) ? "" : " AND ";
			$sql .= "judulberita LIKE '%$splitkeyword%'";
		}
		$sql .= ")";
		
		foreach ($searchedcf as $fieldygdisearch) {
			$sql .= " OR (";
			foreach ($r_keyword as $j=>$splitkeyword) {
				$sql .= ($j==0) ? "" : " AND ";
				$sql .= "$fieldygdisearch LIKE '%$splitkeyword%'";
			}
			$sql .= ")";
		}
		
		$result = $mysql->query($sql);
		$total_records = $mysql->num_rows($result);
		$pages = ceil($total_records/$max_page_list);
		// $title .= $judulmodul.": "._SEARCHRESULT;
		$title .= ": "._SEARCHRESULT;

		if ($mysql->num_rows($result) == "0") {
			$content = "<p>"._NOSEARCHRESULT."</p>\r\n";
			$content .= "<p><a href=\"javascript:history.back()\" class=\"btn btn-default more\">"._BACK."</a></p>\r\n";
		} else {
			//menampilkan record di halaman yang sesuai
			$start = $screen * $max_page_list;
			$sql .= " LIMIT $start, $max_page_list";
			
			$result = $mysql->query($sql);
			$content .="<ul itemscope itemtype=\"http://schema.org/NewsArticle\">";
			if ($pages>1) $newspage = ($keyword!='') ? aksipagination($namamodul,$screen,"keyword=$keyword") : aksipagination($namamodul,$screen);
			while (list($id, $tglmuat, $titleberita, $contentberita, $tglberita) = $mysql->fetch_row($result)) {
				$titleurl = array();
				$titleurl["pid"] = $titleberita;
			
				$content .= "<li class=\"newslist\" itemprop=\"name\"><a itemprop=\"url\" href=\"".$urlfunc->makePretty("?p=news&action=shownews&pid=$id", $titleurl)."\" class=\"newslist\">$titleberita</a>";
				if ($isdateshown) $content .= "<br />".tglformat($tglberita);
				$content .= "</li>";
			}
			$content .="</ul>";
		}
	} else {
		$title = _ERROR;
		$content = "<p>"._NONEWS."</p>\r\n";
		$content .= "<p><a href=\"javascript:history.back()\" class=\"btn btn-default more\">"._BACK."</a></p>\r\n";
	}
}

// if ($action == 'newstree') {
	// $sql = "SELECT id, cat_id, judulberita, tglberita FROM newsdata GROUP BY YEAR(tglberita) ORDER BY tglberita DESC";
	// $result = $mysql->query($sql);
	// if($mysql->num_rows($result)>0)
	// {	
		// $content = "<ul>\r\n";
		// while($row_news = $mysql->fetch_assoc($result)) {
			// $titleurl["cat_id"] = $row_news['judulberita'];
			// $url = "";	
			// if (($timestamp = strtotime($row_news['tglberita'])) !== -1) {
				// $i=getdate($timestamp);
				// $tahun = $i[year];
				// if ($row_news['cat_id']==0) {
					// $content .= "<li><a href=\"".$urlfunc->makePretty("?p=news&action=archive&tahun=$tahun&bulan=$i[mon]")."\">$tahun</a>\r\n";
				// } else {
					// $titleurl = array();
					// $titleurl["cat_id"] = $row_news['judulberita'];
					// $sql_news = "SELECT id,tglmuat,judulberita,summary,tglberita FROM newsdata WHERE YEAR(tglberita) = '$tahun'";
					// $res_news = $mysql->query($sql_news);

					// $content .= "<li>$tahun\r\n";
					// $content .= "<ul>";
					// while(list($id,$tglmuat,$judulberita,$summary,$tglberita) = $mysql->fetch_assoc($res_news)) {
						// $content .= "<li><a href=\"".$urlfunc->makePretty("?p=news&action=shownews&pid=$id", $titleurl)."\">$judulberita</a></li>";
					// }
					// $content .= "</ul>";
					// $content .= "</li>";
				// }
			// }
		// }
		// $content .= "</ul>\r\n";
	// }
// }
?>