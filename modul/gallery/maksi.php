<?php
$cat_id=$_GET['cat_id'];
$action=$_GET['action'];
$pid=$_GET['pid'];
$keyword=$_GET['keyword'];
$screen=$_GET['screen'];

//$catmenu = print_menu("gallery",$cat_id);

$sql = "SELECT judulfrontend FROM module WHERE nama='gallery'";
$result = $mysql->query($sql);
list($title) = $mysql->fetch_row($result);
if ($action=='') {

	$sql = "SELECT id, parent, nama FROM gallerycat ORDER BY urutan";
	$cats = new categories();
	$mycats = array();
	$result = $mysql->query($sql);
	while($row = $mysql->fetch_array($result)) {
		$mycats[] = array('id'=>$row['id'],'parent'=>$row['parent'],'nama'=>$row['nama'],'level'=>0);
	}
	$cats->get_cats($mycats);

	$currlevel = 1;
	$catcontent .= "<ul>\r\n";
	for ($i=0; $i<count($cats->cats); $i++) {
		$url = "<li><a href=\"?p=gallery&cat_id=".$cats->cats[$i]['id']."&action=images\">".$cats->cats[$i]['nama']."</a></li>";
		$selisihlevel=$cats->cats[$i]['level']-$currlevel;
		if ($selisihlevel>0) {
			$catcontent .= "<ul>\r\n";
			$catcontent .= "$url\r\n";
		}
		if ($selisihlevel==0) {
			$catcontent .= "$url\r\n";
		}
		if ($selisihlevel<0) {
			for ($j=0; $j<-$selisihlevel; $j++) {
				$catcontent .= "</ul>\r\n";
			}
			$catcontent .= "$url\r\n";
		}
		$currlevel=$cats->cats[$i]['level'];
	}	
	$catcontent .= "</ul>\r\n";
}

if ($action=="images") {
	if (!empty($cat_id)) {
		$sql = "SELECT id FROM gallerycat WHERE id=$cat_id";
		$result = $mysql->query($sql);
		if ($mysql->num_rows($result)>0) {
			$sql = "SELECT id, nama FROM gallerycat WHERE parent='$cat_id'";
			$result = $mysql->query($sql);
			if ($mysql->num_rows($result) > 0) {
				$catsubcat = "<div class=\"titleofsubcatlist\">"._SUBCAT."</div>\n";
				$catsubcat .= "<ul>\n";
				while(list($catsubcat_id, $catsubcat_name) = $mysql->fetch_row($result)) {
					$catsubcat .= "<li><a href=\"?p=gallery&l=id&action=images&cat_id=$catsubcat_id\">$catsubcat_name</a></li>\n";
				}
				$catsubcat .= "</ul>\n";
			}

			$cats = new categories();
			$mycats = array();
			$sql = 'SELECT id, nama, parent, description FROM gallerycat';
			$result = $mysql->query($sql);
			while($row = $mysql->fetch_array($result)) {
				$mycats[] = array('id'=>$row['id'],'nama'=>$row['nama'],'parent'=>$row['parent'],'description'=>$row['description'],'level'=>0);
			}
			$cats->get_cats($mycats);

			$catnav = "<a href=\"?p=gallery\">$topcatnamenav</a>";
			for ($i=0; $i<count($cats->cats); $i++) {
				$cats->cat_map($cats->cats[$i]['id'],$mycats);
				if ($cats->cats[$i]['id'] == $cat_id) {
					$catdesc = $cats->cats[$i]['description'];
					for ($a=0; $a<count($cats->cat_map); $a++) {
						$cat_parent_id = $cats->cat_map[$a]['id'];
						$cat_parent_name = $cats->cat_map[$a]['nama'];
						$catnav .= "$separatorstyle<a href=\"?p=gallery&l=id&action=images&cat_id=$cat_parent_id\">$cat_parent_name</a>";
					}
					$catnav .= $separatorstyle.$cats->cats[$i]['nama'];
				}
			}
				
			// hitung dulu untuk menentukan berapa halaman...
			$sql = "SELECT id FROM gallerydata";
			if (!empty($cat_id)) {
				$sql .= " WHERE cat_id='$cat_id' AND publish='1'";
			} else {
				$sql .= " WHERE publish='1'";
			}
			
			$result = $mysql->query($sql);
			$total_records = $mysql->num_rows($result);
			$pages = ceil($total_records/$cfg_per_page);

			$mysql->free_result($result);

			// get the image information
			if ($screen==''){
				$screen = 0;
			}

			$start = $screen * $cfg_per_page;

			// ... baru kemudian diambil lagi, kali ini dengan limit perhalaman
			$sql = "SELECT id, cat_id, filename, title, keterangan, publish, ishot, keterangan FROM gallerydata";
			if (!empty($cat_id)) {
				$sql .= " WHERE cat_id='$cat_id' AND publish='1'";
			} else {
				$sql .= " WHERE publish='1'";
			}
			$sql .= " ORDER BY cat_id ASC, date DESC, title ASC LIMIT $start, $cfg_per_page";

			$photo = $mysql->query($sql);
			$total_images=$mysql->num_rows($photo);

			if ($total_images=="0") {
				$cattitle = _ERROR;
				$catcontent = _NOIMAGE;
			} else {
				if ($pages>1) $catpage = pagination($namamodul,$screen,"cat_id=$cat_id&action=images");
				$catcontent = show_me_the_images_mobile();
			}
		} else {
			$cattitle = _ERROR;
			$catcontent = _NOCAT;
		}
	} else {
		$cattitle = _ERROR;
		$catcontent = _NOCAT;
	}
}


if (($action=="viewsearch") || ($action=="viewimages")) {

	$cats = new categories();
	$mycats = array();
	$sql = 'SELECT id, nama, parent, description FROM gallerycat';
	$result = $mysql->query($sql);
	while($row = $mysql->fetch_array($result)) {
		$mycats[] = array('id'=>$row['id'],'nama'=>$row['nama'],'parent'=>$row['parent'],'description'=>$row['description'],'level'=>0);
	}
	$cats->get_cats($mycats);

	$catnav = "<a href=\"?p=gallery&l=id\">$topcatnamenav</a>";
	for ($i=0; $i<count($cats->cats); $i++) {
		$cats->cat_map($cats->cats[$i]['id'],$mycats);
		if ($cats->cats[$i]['id'] == $cat_id) {
			//$catdesc = $cats->cats[$i]['description'];
			for ($a=0; $a<count($cats->cat_map); $a++) {
				$cat_parent_id = $cats->cat_map[$a]['id'];
				$cat_parent_name = $cats->cat_map[$a]['nama'];
				$catnav .= "$separatorstyle<a href=\"?p=gallery&l=id&action=images&cat_id=$cat_parent_id\">$cat_parent_name</a>";
			}
			$catnav .= $separatorstyle."<a href=\"?p=gallery&l=id&action=images&cat_id=".$cats->cats[$i]['id']."\">".$cats->cats[$i]['nama']."</a>";
		}
	}
			
	$tambahan = '';
	$num = count($customfield);
	for ($i=0;$i<$num;$i++) {
		if ($i==0) {
			$tambahan .= "$customfield[$i]";
		} else {
			$tambahan .= ", $customfield[$i]";
		}
	}
	$num1 = count($searchedcf);
	for ($i=0;$i<$num1;$i++) {
		$searchtambahan .= " OR ($searchedcf[$i] LIKE '%$keyword%')";
	}

	// if we're editing, get the image details
	if ($num > 0) {
		$sql = "SELECT id, cat_id, filename, date, title, publish, $tambahan FROM gallerydata WHERE id='$pid'";
	} else {
		$sql = "SELECT id, cat_id, filename, date, title, publish FROM gallerydata WHERE id='$pid'";
	}

	$getpic = $mysql->query($sql);
	$hasil = $mysql->fetch_array($getpic);

	if($hasil['filename']!='')
		$catimg .= '<img src="'.$cfg_fullsizepics_url.'/'.$hasil['filename'].'" '.$imagesize.' alt="'.$hasil['title'].'" title="'.$hasil['title'].'" />';
	else
		$catimg .= '<img src="'.$cfg_app_url.'/images/none.gif" border="0" />';

	//$title .= $hasil['title'];
	$catcontent = "<div class=\"gallerytitle\"><h1>".$hasil['title']."</h1></div>\r\n";
	if ($hasil['keterangan']!='') $catcontent .= '<div class="gallerydesc">'.$hasil['keterangan'].'</div>'."\n";
	
	//prevnext
	$sql = "SELECT id, title, filename FROM gallerydata";
	if ($action=="viewsearch") $sql .= " WHERE ((title LIKE '%$keyword%') $searchtambahan)";
	if ($action=="viewimages") $sql .= " WHERE (cat_id='$cat_id')";
	$sql .= " AND (publish='1') ORDER BY urutan ASC, date DESC";
	$result = $mysql->query($sql);
	$num = $mysql->num_rows($result);
	for ($i=0;$i<$num;$i++) {
		if ($mysql->result($result,$i,"id") == $pid) {

			if ($i != 0) {
				$showprevid = $mysql->result($result,$i-1,"id");
				$showprevname = $mysql->result($result,$i-1,"filename");
				$showprevtitle = $mysql->result($result,$i-1,"title");
			} else {
				$showprevid = -1;
			}

			if ($i != $num-1) {
				$shownextid = $mysql->result($result,$i+1,"id");
				$shownextname = $mysql->result($result,$i+1,"filename");
				$shownexttitle = $mysql->result($result,$i+1,"title");
			} else {
				$shownextid = -1;
			}

			$tempthumb=$cfg_thumb_url;
			if ($showprevid != -1) {
				if ((file_exists($cfg_thumb_path."/".$showprevname))&&($showprevname!='')) {
					$image_stats = getimagesize($cfg_thumb_path."/".$showprevname); 
					$new_w = $image_stats[0]; 
					$new_h = $image_stats[1]; 
					$if_thumb="yes";
				} elseif ((!file_exists($cfg_thumb_path."/".$showprevname)) && (file_exists($cfg_fullsizepics_path."/".$showprevname)) && ($showprevname!='')) {
					$image_stats = getimagesize($cfg_fullsizepics_path."/".$showprevname); 
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
				}
				if ($action=='viewsearch') $actiondependent = "keyword=$keyword";
				if ($action=='viewimages') $actiondependent = "cat_id=$cat_id";
				$catprev .= "<a href=\"".$urlfunc->makePretty("?p=gallery&action=$action&pid=$showprevid&$actiondependent", $titleurl)."\">";
				if($showprevname!='')
					$catprev .= "<img src=\"$cfg_thumb_url/$showprevname\" border=\"0\" alt=\"$showprevtitle\" title=\"$showprevtitle\" width=\"$new_w\" height=\"$new_h\">";
				else
					$catprev .= "<img src=\"$cfg_app_url/images/none.gif\" border=\"0\" />";
				$catprev .= "</a>";
				$catprev .= "<br><a href=\"?p=gallery&pid=$showprevid&action=$action&$actiondependent\">&lt;&lt; "._PREVIOUS."</a>";
			} else {
				$catprev .= '&nbsp;';
			}
			$cfg_thumb_url=$tempthumb;


			$tempthumb=$cfg_thumb_url;
			if ($shownextid != -1) {
				if ((file_exists($cfg_thumb_path."/".$shownextname))&&($shownextname!='')) {
					$image_stats = GetImageSize($cfg_thumb_path."/".$shownextname); 
					$new_w = $image_stats[0]; 
					$new_h = $image_stats[1]; 
					$if_thumb="yes";
				} elseif ((!file_exists($cfg_thumb_path."/".$shownextname)) && (file_exists($cfg_fullsizepics_path."/".$shownextname)) && ($shownextname!='')) {
					$image_stats = GetImageSize($cfg_fullsizepics_path."/".$shownextname); 
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
				} 
				if ($action=='viewsearch') $actiondependent = "keyword=$keyword";
				if ($action=='viewimages') $actiondependent = "cat_id=$cat_id";
				$catnext .= "<a href=\"".$urlfunc->makePretty("?p=gallery&action=$action&pid=$shownextid&$actiondependent", $titleurl)."\">";
				if($shownextname!='')
					$catnext .= "<img src=\"$cfg_thumb_url/$shownextname\" border=\"0\" alt=\"$shownexttitle\" title=\"$shownexttitle\" width=\"$new_w\" height=\"$new_h\">";
				else
					$catnext .= "<img src=\"$cfg_app_url/images/none.gif\" border=\"0\" />";
				$catnext .= "</a>";
				$catnext .= "<br><a href=\"?p=gallery&pid=$shownextid&action=$action&$actiondependent\">"._NEXT." &gt;&gt;</a>";
			} else {
				$catnext .= '&nbsp;';
			}
			$cfg_thumb_url=$tempthumb;
		}
	}

}

if ($action == "search") {

		$keyword=strip_tags($keyword);
		$tambahan = '';
		$num = count($searchedcf);
		for ($i=0;$i<$num;$i++) {
			$tambahan .= " OR ($searchedcf[$i] LIKE '%$keyword%')";
		}

		$title .= _SEARCHRESULTS;

		$sql  = "SELECT * FROM gallerydata WHERE ((title LIKE '%$keyword%') $tambahan) AND publish='1'";
		$result = $mysql->query($sql);
		$total_records = $mysql->num_rows($result);
		$pages = ceil($total_records / $cfg_per_page);
		$mysql->free_result($result);

		if ($screen==''){
			  $screen = 0;
		}

		$start = $screen * $cfg_per_page;

		$sql  = "SELECT id, cat_id, filename, title, keterangan, publish FROM gallerydata WHERE ((title LIKE '%$keyword%') $tambahan) AND publish='1' ORDER BY cat_id ASC, date DESC, title ASC LIMIT $start, $cfg_per_page";
		$photo = $mysql->query($sql);
		$total_images=$mysql->num_rows($photo);

		if ($total_images=="0") {
			$cattitle .= _ERROR;
			$catcontent .= _NOSEARCHRESULTS;
		} else {
			$keyword= urlencode($keyword);
			if ($pages>1) $catpage = pagination($namamodul,$screen,"p=gallery&l=id&action=search&keyword=$keyword");
			$catcontent .= show_me_the_images();
		} //end total_images
}  

//$catmenu --> ada, tapi tidak ditampilkan agar tampilan lebih rapi
$content .= ($catnav!='') ? "<div class=\"catnav\">"._CATEGORY.": $catnav</div>\r\n" : "" ;
$content .= ($catdesc!='') ? "<div class=\"catdesc\">$catdesc</div>\r\n" : "";
$content .= ($catsubcat!='') ? "<div class=\"catsubcat\">$catsubcat</div>\r\n" : "";
$content .= ($catpage!='') ? "<div class=\"catpage\">$catpage</div>\r\n" : "" ;
$content .= '
	<p>'.$catimg.'</p>
	<p>'.$catcontent.'</p>
	<p>'.$catprev.'</p>
	<p>'.$catnext.'</p>
';
?>
