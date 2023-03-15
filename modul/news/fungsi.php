<?php

function archive($tahun,$bulan,$cat_id) {
	global $mysql;
	global $namabulan, $isdateshown, $issourceshown, $issummary, $lang, $urlfunc, $idmenu, $cfg_thumb_path, $cfg_thumb_url, $cfg_app_url;
	$bulandepan=$bulan+1;

	if ($cat_id=='') {
		$sql = "SELECT id,tglmuat,judulberita,summary,tglberita,thumb FROM newsdata WHERE tglberita >= '$tahun-$bulan-01 00:00:00' AND tglberita < '$tahun-$bulandepan-01 00:00:00' ORDER BY tglberita DESC";
	} else {
		$sql = "SELECT id,tglmuat,judulberita,summary,tglberita,thumb FROM newsdata WHERE tglberita >= '$tahun-$bulan-01 00:00:00' AND tglberita < '$tahun-$bulandepan-01 00:00:00' AND cat_id='$cat_id' ORDER BY tglberita DESC";
	}
	$result = $mysql->query($sql);
	if ($mysql->num_rows($result)>0) {
		$newscontent = "<ul class=\"listnews\">";
		while (list($id, $tglmuat, $judulberita, $summary, $tglberita, $thumb) = $mysql->fetch_row($result)) {
			$titleurl = array();
			$titleurl["pid"] = $judulberita;
			
			$newscontent .= "					
					<div class=\"col-sm-6 col-md-6\" itemscope itemtype=\"http://schema.org/NewsArticle\">
						<div class=\"panel panel-default news-col\">
							<div class=\"panel-body\">";
								$default_img = ($thumb != '' && file_exists("$cfg_thumb_path/$thumb")) ? "$cfg_thumb_url/$thumb" : "$cfg_app_url/images/default-image.jpg";
								$newscontent .= "
										<div class=\"img-news\">
											<img class=\"img-responsive\" alt=\"$judulberita\" src=\"$default_img\">
										</div>	<!-- /.img-news -->\r\n";
										
								$newscontent .= "
									<div class=\"news-content\">
										<div class=\"title-news-thumbnail\" itemprop=\"name\"><h4>$judulberita</h4></div>";
								if ($isdateshown) $newscontent .= "<div class=\"date_news\"><meta itemprop=\"datePublished\" content=\"$tglberita\"/>".tglformat($tglberita)."</div>";
								if ($issummary && $summary!='') $newscontent .= "<div class=\"content-news-thumbnail\" temprop=\"description\">$summary</div>";
								$newscontent .= "<a itemprop=\"url\" href=\"".$urlfunc->makePretty("?p=news&action=shownews&pid=$id", $titleurl)."\" class=\"btn btn-default more\">"._LEARNMORE."</a>
									</div>	<!-- /.news-content -->\r\n
								";
								$newscontent .= "
							</div>	<!-- /.panel-body -->\r\n
						</div>	<!-- /.news-col -->\r\n 
					</div>	<!-- /.col-sm-6 col-md-6 -->";				
			// $newscontent .= "<li><span class=\"newstitle\"><a href=\"".$urlfunc->makePretty("?p=news&amp;action=shownews&amp;pid=$id&amp;idmenu=$idmenu", $titleurl)."\">$judulberita</a></span>\r\n";
			// if ($isdateshown) $newscontent .= "<br /><span class=\"newsdate\">".tglformat($tglberita)."</span>\r\n";
			// if ($issummary && $summary!='') $newscontent .= "<br /><div class=\"newsshortdesc\">$summary</span>\r\n";
			// $newscontent .= "</li>";
		}
		$newscontent .= "</ul>";
	} else {
		$newscontent = _NONEWS;
	}
	return $newscontent;
}

function catstructure($sql)
{
	global $mysql;
	global $urlfunc;
	$cats = new categories();
	$mycats = array();
	$result = $mysql->query($sql);
	while ($row = $mysql->fetch_assoc($result)) {
	$mycats[] = array('id' => $row['id'], 'nama' => $row['nama'],'parent' => 0,'level' => 0);
	}
	$cats->get_cats($mycats);
	ob_start();
	echo "<ol class=\"sortable ui-sortable\">";
	 for ($i = 0; $i < count($cats->cats); $i++) 
	 {
		if($cats->cats[$i]['parent']==0)
		{
			$cat_id=$cats->cats[$i]['id'];
			echo "<li id='list_$cat_id' data-catid='$cat_id'>";
			echo "<div class=\"kat_item ui-state-default ui-draggable\"><div class=\"kat_nama\">".$cats->cats[$i]['nama']."</div>";
			   echo "<div class=\"kat_action\"><a href=\"?p=news&action=catedit&cat_id=".$cats->cats[$i]['id']."\"><img alt=\"Edit\" border=\"0\" src=\"../images/modify.gif\"></a> <a href=\"?p=news&action=catdel&cat_id=" . $cats->cats[$i]['id'] . "\"><img alt=\"Hapus\" border=\"0\" src=\"../images/delete.gif\"></a>
				</div></div>";
			echo "</li>";
		}
	 }
	echo "</ol>";

	return ob_get_clean();
}
?>
