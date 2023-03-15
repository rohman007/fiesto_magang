<?php

function print_menu($jenis,$cat_id="") {
	global $catmenu, $topcatnamecombo, $urlfunc;
	$thisfile = $_SERVER['PHP_SELF'];
	$terpilih = $cat_id;
	$sql = "SELECT id, nama FROM ".$jenis."cat WHERE parent='0' ORDER BY description";
	$cat = mysql_query($sql);
			
	if($urlfunc->is_permalink)
	{	$catmenu .= '
		<script language="JavaScript">
		<!--
		function MM_jumpMenu(targ,selObj,restore) 
		{	if (selObj.options[selObj.selectedIndex].value=="") {
				eval(targ+".location=\''.$urlfunc->makePretty("?p=catalog").'\'");
			} else {
				eval(targ+".location=\''.$urlfunc->makePretty("?p=catalog&action=images").'\"+selObj.options[selObj.selectedIndex].value+"\'");
			}
			if (restore) selObj.selectedIndex=0;
		}
		//-->
		</script>';
	}
	else
	{	
	$catmenu .= '
	<script language="JavaScript">
	<!--
	function MM_jumpMenu(targ,selObj,restore) { //v3.0
		if (selObj.options[selObj.selectedIndex].value=="") {
			eval(targ+".location=\'?p=catalog\'");
		} else {
			eval(targ+".location=\'?p=catalog&action=images&cat_id="+selObj.options[selObj.selectedIndex].value+"\'");
		}
		if (restore) selObj.selectedIndex=0;
	}
	//-->
	</script>
	';
	}

	$catmenu .= "<form><select name=\"cat_id\" onChange=\"MM_jumpMenu('parent',this,0)\">";
	$cats = new categories();
	$mycats = array();
	$sql = 'SELECT id, nama, parent FROM catalogcat';
	$result = mysql_query($sql);
	while($row = mysql_fetch_array($result)) {
		$mycats[] = array('id'=>$row['id'],'nama'=>$row['nama'],'parent'=>$row['parent'],'level'=>0);
	}
	$cats->get_cats($mycats);
	mysql_free_result($result);

	$catmenu .= "<option value=\"\">$topcatnamecombo /</option>\n";
	for ($i=0; $i<count($cats->cats); $i++) {
		$cats->cat_map($cats->cats[$i]['id'],$mycats);
		$cat_name = $cats->cats[$i]['nama'];
		$catmenu .= '<option value="'.$cats->cats[$i]['id'].'"';
		$catmenu .= ($cats->cats[$i]['id']==$cat_id) ? " selected>$topcatnamecombo" : ">$topcatnamecombo";
		for ($a=0; $a<count($cats->cat_map); $a++) {
			$cat_parent_id = $cats->cat_map[$a]['id'];
			$cat_parent_name = $cats->cat_map[$a]['nama'];
			$catmenu .= " / $cat_parent_name";
		}
		$catmenu .= " / $cat_name</option>";
	}

	$catmenu .= "</select></form>\n";
	mysql_free_result($cat);
	return $catmenu;
}

function show_me_the_images($isadmin=FALSE) {
	global $action, $cfg_per_page, $cfg_max_cols, $urlfunc;
	global $cfg_thumb_width, $sql, $cfg_thumb_path, $cfg_thumb_url,$imagefolder;
	global $screen, $pages, $photo, $cat_id, $keyword, $merek, $random;

	$x = 1;
	$catcontent .= '<table id="producttable" border="0" cellpadding="0" cellspacing="0" width="100%">';
	while (list($photo_id, $photo_cat_id, $photo_filename, $photo_title, $photo_published, $photo_ishot, $keterangansingkat, $harganormal, $diskon, $matauang, $nilai_disc, $harga_disc) = mysql_fetch_row($photo)) {
		$lebar = 100 / $cfg_max_cols;
		if (($x % $cfg_max_cols) == 1 || $cfg_max_cols==1) {
			$imgcell = "<tr>\n<td valign=\"bottom\" align=\"center\" width=\"$lebar%\">\n&nbsp;";
			$txtcell = "<tr>\n<td valign=\"top\" align=\"center\" width=\"$lebar%\">";
		} else {
			$imgcell .= "<td valign=\"bottom\" align=\"center\" width=\"$lebar%\">\n&nbsp;";
			$txtcell .= "<td valign=\"top\" align=\"center\" width=\"$lebar%\">";
		}

		//Junaidi: Bugfix agar selalu ambil dari direktori thumb jika ada thumbnail
		$tempthumb=$cfg_thumb_url;
		if (file_exists($cfg_thumb_path."/".$photo_filename)) {
			$image_stats = getimagesize($cfg_thumb_path."/".$photo_filename); 
			$new_w = $image_stats[0]; 
			$new_h = $image_stats[1]; 
			$if_thumb="yes";
		} elseif ((!file_exists($cfg_thumb_path."/".$photo_filename)) && (file_exists($cfg_fullsizepics_path."/".$photo_filename))) {
			$image_stats = getimagesize($cfg_fullsizepics_path."/".$photo_filename); 
			$imagewidth = $image_stats[0]; 
			$imageheight = $image_stats[1]; 
			$img_type = $image_stats[2]; 
			if ($imagewidth > $imageheight) {
				$new_w = $cfg_thumb_width; 
				$ratio = ($imagewidth / $cfg_thumb_width);
				$new_h = round($imageheight / $ratio);
			} else {
				$new_h = $cfg_thumb_width; 
				$ratio = ($imageheight / $cfg_thumb_width);
				$new_w = round($imagewidth / $ratio);
			}
			$cfg_thumb_url=$cfg_fullsizepics_url;				
			$if_thumb="no";
		} elseif ((!file_exists($cfg_thumb_path."/".$photo_filename)) && (!file_exists($cfg_fullsizepics_path."/".$photo_filename))) {
			$photo_filename="none.gif";
		}	// end if file exists		
		
		$titleurl = array();
		$titleurl["pid"] = $photo_title;
				
		if ($action=='search') $actiondependent = "keyword=$keyword";
		if ($action=='images') 
		{	$actiondependent = "cat_id=$cat_id";
			$sqlcatname = "SELECT nama FROM catalogcat WHERE id='$cat_id' ";
			$resultcatname = mysql_query($sqlcatname);
			list($catname) = mysql_fetch_row($resultcatname);
			$titleurl["cat_id"] = $catname;
			mysql_free_result($resultcatname);
		}
		if ($action=='brand')
		{	$actiondependent = "merek=$merek";
			$sqlmerekname = "SELECT nama FROM catalogmerek WHERE id='$merek' ";
			$resultmerekname = mysql_query($sqlmerekname);
			list($merekname) = mysql_fetch_row($resultmerekname);
			$titleurl["merek"] = $merekname;
			mysql_free_result($resultmerekname);
		}
		
		if ($isadmin) {
			$imgcell .= "<a href=\"?p=catalog&pid=$photo_id&action=edit&$actiondependent\"><img src=\"$cfg_thumb_url/$photo_filename\" border=\"0\" height=\"$new_h\" width=\"$new_w\" alt=\"$photo_title\" title=\"$photo_title\" class=\"photos\"></a>\r\n";
			$txtcell .= "<span class=\"catpublishis$photo_published\" align=\"center\"><span class=\"cathotis$photo_ishot\">$photo_title</span></span>\r\n";
		} else {
			$imgcell .= "<a href=\"".$urlfunc->makePretty("?p=catalog&action=view$action&pid=$photo_id&$actiondependent", $titleurl)."\"><img src=\"$cfg_thumb_url/$photo_filename\" border=\"0\" height=\"$new_h\" width=\"$new_w\" alt=\"$photo_title\" title=\"$photo_title\" class=\"photos\"></a>\r\n";
			$txtcell .= "<p align=\"center\" class=\"photo_title\">$photo_title</p>\r\n";
		}
		//Junaidi: Bugfix agar selalu ambil dari direktori thumb jika ada thumbnail
		$cfg_thumb_url=$tempthumb;

		// if we're in admin mode, print out the admin links to edit, delete, etc, and whether
		// or not it's a published image
		if ($isadmin) {
			$txtcell .= '<br />[<a href="'.$thisfile.'?p=catalog&pid='.$photo_id.'&action=edit&cat_id='.$cat_id.'&keyword='.$keyword.'&screen='.$screen.'">'._EDIT.'</a>] [<a href="'.$thisfile.'?p=catalog&pid='.$photo_id.'&cat_id='.$cat_id.'&screen='.$screen.'&action=delete">'._DEL.'</a>]<br />&nbsp;';
		} else {
			$txtcell .= '<br />&nbsp;';
		}
		if (($x % $cfg_max_cols) == 0) {
			$imgcell .= "</td></tr>\n";
			$txtcell .= "</td></tr>\n";
			$catcontent .= $imgcell.$txtcell;
		} else {
			$imgcell .= "</td>\n";
			$txtcell .= "</td>\n";
		}
		$x++;
	}
	if (($x-1) % $cfg_max_cols != 0) {
		while ($x % $cfg_max_cols > 0) {
			$imgcell .= "<td valign=\"bottom\" align=\"center\" width=\"$lebar%\">&nbsp;</td>\n";
			$txtcell .= "<td valign=\"bottom\" align=\"center\" width=\"$lebar%\">&nbsp;</td>\n";
			$x++;
		}
		$imgcell .= "<td valign=\"bottom\" align=\"center\" width=\"$lebar%\">&nbsp;</td></tr>\n";
		$txtcell .= "<td valign=\"bottom\" align=\"center\" width=\"$lebar%\">&nbsp;</td></tr>\n";
		$catcontent .= $imgcell.$txtcell;
	}
	$catcontent .= "</table>\n";
	return $catcontent;
}

function show_me_the_list() {
	global $action, $cfg_per_page, $cfg_max_cols, $urlfunc;
	global $cfg_thumb_width, $sql, $cfg_thumb_path, $cfg_thumb_url,$imagefolder;
	global $screen, $pages, $photo, $cat_id, $keyword, $merek, $random;

	$catcontent .= "<div class=\"productsummary\">";
	while (list($photo_id, $photo_cat_id, $photo_filename, $photo_title, $photo_published, $photo_ishot, $keterangansingkat, $harganormal, $diskon, $matauang, $nilai_disc, $harga_disc) = mysql_fetch_row($photo)) {
		$titleurl = array();
		$titleurl["pid"] = $photo_title;
		
		$sqlcatname = "SELECT nama FROM catalogcat WHERE id='$photo_cat_id' ";
		$resultcatname = mysql_query($sqlcatname);
		list($catname) = mysql_fetch_row($resultcatname);
		$titleurl["cat_id"] = $catname;
		mysql_free_result($resultcatname);
						
		$catcontent .= "<div class=\"pstitle\"><h1><a href=\"".$urlfunc->makePretty("?p=catalog&action=viewimages&pid=$photo_id&cat_id=$photo_cat_id", $titleurl)."\">$photo_title</a></h1></div>\r\n";
		$catcontent .= "<div class=\"psphoto\"><a href=\"".$urlfunc->makePretty("?p=catalog&action=viewimages&pid=$photo_id&cat_id=$photo_cat_id", $titleurl)."\"><img src=\"$cfg_thumb_url/$photo_filename\" border=\"0\" alt=\"$photo_title\" title=\"$photo_title\" class=\"photos\"></a></div>\r\n";
		$catcontent .= "<div class=\"pssummary\">$keterangansingkat</div>\r\n";
		$diskon = str_replace(" ","",$diskon);
		if ($harganormal>0) {
			if ($diskon=='') {
				$kelasnormal = 'normalwodisc';
			} else {
				$kelasnormal = 'normalwdisc';
			}
			$catcontent .= "<div class=\"harga\">"._PRICE.": <span class=\"$kelasnormal\">$matauang ".number_format($harganormal,0,',','.')."</span>";
			if ($diskon!='') 
			{	if(substr_count($diskon,"%")==1)
				{	$label_disc = str_replace("%","",$diskon)."%";
				}
				else
				{	$label_disc = $matauang.' '.number_format($diskon,0,',','.');
				}
				$catcontent .= " <span class=\"discprice\">$matauang ".number_format($harga_disc,0,',','.')."</span>";
				$catcontent .= " <span class=\"discvalue\">("._YOUSAVE." ".$label_disc.")</span>";
			}
			$catcontent .= "</div>\r\n";
		}
		$catcontent .= "<div class=\"psseparator\"></div>\r\n";
	}
	$catcontent .= "</div>\r\n";
	return $catcontent;
}

function catstructure($sql) {
	global $urlfunc;
	$cats = new categories();
	$mycats = array();
	$result = mysql_query($sql);
	while($row = mysql_fetch_array($result)) {
		$mycats[] = array('id'=>$row['id'],'nama'=>$row['nama'],'parent'=>$row['parent'],'level'=>0);
	}
	$cats->get_cats($mycats);

	$currlevel = 1;
	$catcontent .= "<ul>\n";
	for ($i=0; $i<count($cats->cats); $i++) {
		
		$titleurl = array();
		$titleurl["cat_id"] = $cats->cats[$i]['nama'];
				
		$selisihlevel=$cats->cats[$i]['level']-$currlevel;
		if ($selisihlevel>0) {
			$catcontent .= "<ul>\n";
			$catcontent .= "<li><a href=\"".$urlfunc->makePretty("?p=catalog&action=images&cat_id=".$cats->cats[$i]['id'], $titleurl)."\">".$cats->cats[$i]['nama']."</a> ";
			$catcontent .= "<a href=\"".$urlfunc->makePretty("?p=catalog&action=images&cat_id=".$cats->cats[$i]['id'], $titleurl)."\"><img alt=\""._OPEN."\" border=\"0\" src=\"../images/open.gif\"></a> ";
			$catcontent .= "<a href=\"".$urlfunc->makePretty("?p=catalog&action=catedit&cat_id=".$cats->cats[$i]['id'], $titleurl)."\"><img alt=\""._EDIT."\" border=\"0\" src=\"../images/modify.gif\"></a> <a href=\"?p=catalog&action=catdel&cat_id=".$cats->cats[$i]['id']."\"><img alt=\""._DEL."\" border=\"0\" src=\"../images/delete.gif\"></a></li>\n";
		}
		if ($selisihlevel==0) {
			$catcontent .= "<li><a href=\"".$urlfunc->makePretty("?p=catalog&action=images&cat_id=".$cats->cats[$i]['id'], $titleurl)."\">".$cats->cats[$i]['nama']."</a> ";
			$catcontent .= "<a href=\"".$urlfunc->makePretty("?p=catalog&action=images&cat_id=".$cats->cats[$i]['id'], $titleurl)."\"><img alt=\""._OPEN."\" border=\"0\" src=\"../images/open.gif\"></a> ";
			$catcontent .= "<a href=\"".$urlfunc->makePretty("?p=catalog&action=catedit&cat_id=".$cats->cats[$i]['id'], $titleurl)."\"><img alt=\""._EDIT."\" border=\"0\" src=\"../images/modify.gif\"></a> <a href=\"?p=catalog&action=catdel&cat_id=".$cats->cats[$i]['id']."\"><img alt=\""._DEL."\" border=\"0\" src=\"../images/delete.gif\"></a></li>\n";
		}
		if ($selisihlevel<0) {
			for ($j=0; $j<-$selisihlevel; $j++) {
				$catcontent .= "</ul>\n";
			}
			$catcontent .= "<li><a href=\"".$urlfunc->makePretty("?p=catalog&action=images&cat_id=".$cats->cats[$i]['id'], $titleurl)."\">".$cats->cats[$i]['nama']."</a> ";
			$catcontent .= "<a href=\"".$urlfunc->makePretty("?p=catalog&action=images&cat_id=".$cats->cats[$i]['id'], $titleurl)."\"><img alt=\""._OPEN."\" border=\"0\" src=\"../images/open.gif\"></a> ";
			$catcontent .= "<a href=\"".$urlfunc->makePretty("?p=catalog&action=catedit&cat_id=".$cats->cats[$i]['id'], $titleurl)."\"><img alt=\""._EDIT."\" border=\"0\" src=\"../images/modify.gif\"></a> <a href=\"?p=catalog&action=catdel&cat_id=".$cats->cats[$i]['id']."\"><img alt=\""._DEL."\" border=\"0\" src=\"../images/delete.gif\"></a></li>\n";
		}
		$currlevel=$cats->cats[$i]['level'];
	}	
	$catcontent .= "</ul>\n";
	return $catcontent;
}


?>
