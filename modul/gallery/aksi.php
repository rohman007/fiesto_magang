<?php
// $cat_id=$_GET['cat_id'];
// $action=$_GET['action'];
// $pid=$_GET['pid'];
$tempcid = explode('_',$_SEG[2]);
$cat_id = $tempcid[0];
$temppid = explode('_',$_SEG[3]);
$pid = $temppid[0];
$keyword=$_GET['keyword'];
$screen=$_GET['screen'];

//$catmenu = print_menu("catalog",$cat_id);

$sql = "SELECT judulfrontend FROM module WHERE nama='gallery'";
$result = $mysql->query($sql);
list($title) = $mysql->fetch_row($result);
 if ($action=='' or $action=='main' or $action=='images') 
 {
	$titleurl = array();
	// $cat_id=$_GET['cat_id']!=''?$_GET['cat_id']:0;
	$cat_id=$cat_id!=''?$cat_id:0;
 	$catnav=cat_title($cat_id);
	$sql="
	SELECT x.type,x.id,x.nama,x.parent,x.filename,x.total
	FROM
	(
	SELECT 0 type,id, nama, parent,filename,urutan,((select count(id) FROM gallerydata WHERE cat_id=g.id)+(select count(id) FROM gallerycat WHERE parent=g.id)) total FROM gallerycat g WHERE parent=$cat_id
	UNION ALL 
	SELECT 1 type,id,title nama,0 parent,filename,id urutan,0 total FROM gallerydata WHERE cat_id=$cat_id
	) x ORDER BY x.type,x.urutan
	";
	
    $result = $mysql->query($sql);
    $total_records = $mysql->num_rows($result);
    $pages = ceil($total_records / $cfg_per_page);

    $mysql->free_result($result);

    
    if ($screen == '') {
        $screen = 0;
    }

    $start = $screen * $cfg_per_page;
    $sql .= " LIMIT $start, $cfg_per_page";

    $photo = $mysql->query($sql);
    $total_images = $mysql->num_rows($photo);

    if ($total_images == "0") {
        if ($total_subcat == 0) {
            $catcontent .= "<p>" . _NOPHOTO . "</p>";
        }
    } else {
        if ($pages > 1) {
           	$catpage = aksipagination($namamodul, $screen, "action=images&cat_id=$cat_id",$titleurl);
    }
	}
    $catcontent.= catstructure($cat_id);


}

 if ($action=="images") {

	// $titleurl = array();
	// if (!empty($cat_id)) {
		// $sql = "SELECT id FROM gallerycat WHERE id=$cat_id";
		// $result = $mysql->query($sql);
		// if ($mysql->num_rows($result)>0) {
		     // $sql = "SELECT nama FROM gallerycat WHERE id='$cat_id'";
            // $result = $mysql->query($sql);
			// list($cat_name) = $mysql->fetch_row($result);

			// $sql = "SELECT id, nama FROM gallerycat WHERE parent='$cat_id'";
			// $result = $mysql->query($sql);
			// if ($mysql->num_rows($result) > 0) {
				// //$catsubcat = "<div class=\"titleofsubcatlist\">"._SUBCAT."</div>\n";
				// $catsubcat .= "<ul>\n";
				// while(list($catsubcat_id, $catsubcat_name) = $mysql->fetch_row($result)) {
					// $catsubcat .= "<li><a href=\"".$urlfunc->makePretty("?p=gallery&action=images&cat_id=$catsubcat_id")."\">$catsubcat_name</a></li>\n";
				// }
				// $catsubcat .= "</ul>\n";
			// }

			// $cats = new categories();
			// $mycats = array();
			// $sql = 'SELECT id, nama, parent, description FROM gallerycat';
			// $result = $mysql->query($sql);
			// while($row = $mysql->fetch_array($result)) {
				// $mycats[] = array('id'=>$row['id'],'nama'=>$row['nama'],'parent'=>$row['parent'],'description'=>$row['description'],'level'=>0);
			// }
			// $cats->get_cats($mycats);
			// $catnav = "<a href=\"".$urlfunc->makePretty("?p=gallery")."\">$topcatnamenav</a>";
			// for ($i=0; $i<count($cats->cats); $i++) {
				// $cats->cat_map($cats->cats[$i]['id'],$mycats);
				// if ($cats->cats[$i]['id'] == $cat_id) {
					// $catdesc = $cats->cats[$i]['description'];
					// for ($a=0; $a<count($cats->cat_map); $a++) {
						// $cat_parent_id = $cats->cat_map[$a]['id'];
						// $cat_parent_name = $cats->cat_map[$a]['nama'];
						// $titleurl['cat_id']=$cat_parent_name;
						// $catnav .= "$separatorstyle<a href=\"".$urlfunc->makePretty("?p=gallery&action=images&cat_id=$cat_parent_id",$titleurl)."\">$cat_parent_name</a>";
					// }
					// $catnav .= $separatorstyle.$cats->cats[$i]['nama'];
				// }
			// }
				
			// // hitung dulu untuk menentukan berapa halaman...
			// $sql = "SELECT id FROM gallerydata";
			// if (!empty($cat_id)) {
				// $sql .= " WHERE cat_id='$cat_id' AND publish='1'";
			// } else {
				// $sql .= " WHERE publish='1'";
			// }
			
			// $result = $mysql->query($sql);
			// $total_records = $mysql->num_rows($result);
			// $pages = ceil($total_records/$cfg_per_page);

			// $mysql->free_result($result);

			// // get the image information
			// if ($screen==''){
				// $screen = 0;
			// }

			// $start = $screen * $cfg_per_page;

			// // ... baru kemudian diambil lagi, kali ini dengan limit perhalaman
			// $sql = "SELECT id, cat_id, filename, title, keterangan, publish, ishot, keterangan FROM gallerydata";
			// if (!empty($cat_id)) {
				// $sql .= " WHERE cat_id='$cat_id' AND publish='1'";
			// } else {
				// $sql .= " WHERE publish='1'";
			// }
			// $sql .= " ORDER BY cat_id ASC, date DESC, title ASC LIMIT $start, $cfg_per_page";

			// $photo = $mysql->query($sql);
			// $total_images=$mysql->num_rows($photo);

			// if ($total_images>0) {
				// $titleurl = array();
				// $titleurl['cat_id']=$cat_name;
				// $catpage = aksipagination($namamodul, $screen, "action=images&cat_id=$cat_id",$titleurl);
				// $catcontent = show_me_the_images();
			// }
		// } else {
			// $cattitle = _ERROR;
			// $catcontent = _NOCAT;
		// }
	// } else {
		// $cattitle = _ERROR;
		// $catcontent = _NOCAT;
	// }
}


if (($action=="viewsearch") || ($action=="viewimages")) {

	// $cats = new categories();
	// $mycats = array();
	// $sql = 'SELECT id, nama, parent, description FROM gallerycat';
	// $result = $mysql->query($sql);
	// while($row = $mysql->fetch_array($result)) {
		// $mycats[] = array('id'=>$row['id'],'nama'=>$row['nama'],'parent'=>$row['parent'],'description'=>$row['description'],'level'=>0);
	// }
	// $cats->get_cats($mycats);
	// $titleurl = array();
	// $catnav = "<a href=\"".$urlfunc->makePretty("?p=gallery")."\">$topcatnamenav</a>";
	// for ($i=0; $i<count($cats->cats); $i++) {
		// $cats->cat_map($cats->cats[$i]['id'],$mycats);
		// if ($cats->cats[$i]['id'] == $cat_id) {
			// //$catdesc = $cats->cats[$i]['description'];
			// for ($a=0; $a<count($cats->cat_map); $a++) {
				// $cat_parent_id = $cats->cat_map[$a]['id'];
				// $cat_parent_name = $cats->cat_map[$a]['nama'];
				// $titleurl['cat_id']=$cat_parent_name;
				// $catnav .= "$separatorstyle<a href=\"".$urlfunc->makePretty("?p=gallery&action=images&cat_id=$cat_parent_id",$titleurl)."\">$cat_parent_name</a>";
			// }
			// $titleurl['cat_id']=$cats->cats[$i]['nama'];
			// $catnav .= $separatorstyle."<a href=\"".$urlfunc->makePretty("?p=gallery&action=images&cat_id=".$cats->cats[$i]['id'],$titleurl)."\">".$cats->cats[$i]['nama']."</a>";
		// }
	// }
	$catnav=cat_title($cat_id);		
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
		$sql = "SELECT id, cat_id, filename, title, publish, $tambahan FROM gallerydata WHERE id='$pid'";
	} else {
		$sql = "SELECT id, cat_id, filename, title, publish FROM gallerydata WHERE id='$pid'";
	}
	
	$getpic = $mysql->query($sql);
	$hasil = $mysql->fetch_array($getpic);
	//add filter width img by roemly
	$lebar="";
	$file_img = $cfg_fullsizepics_path."/".$hasil['filename'];
		if(file_exists($file_img))
			{	
				list($widthimg, $heightimg, $type, $attr) = getimagesize($file_img);
				if($widthimg > $mainblockwidth)
				{	$lebar = "width=\"$mainblockwidth\" ";
				}
			}
	//end		
	$catimg .= '<div style="text-align:center" class="detail-image">';
	if($hasil['filename']!='')
		$catimg .= '<img '.$lebar.' src="'.$cfg_fullsizepics_url.'/'.$hasil['filename'].'" '.$imagesize.' alt="'.$hasil['title'].'" title="'.$hasil['title'].'" />';
	else
		$catimg .= '<img '.$lebar.' src="'.$cfg_app_url.'/images/none.gif" border="0" />';
	$catimg .= '</div>';

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
				$titleurl["pid"] = $showprevtitle;
				$catprev .= "<a href=\"".$urlfunc->makePretty("?p=gallery&action=$action&pid=$showprevid&$actiondependent", $titleurl)."\">";
				if($showprevname!='')
					$catprev .= "<img src=\"$cfg_thumb_url/$showprevname\" border=\"0\" alt=\"$showprevtitle\" title=\"$showprevtitle\" width=\"$new_w\" height=\"$new_h\">";
				else
					$catprev .= "<img src=\"$cfg_app_url/images/none.gif\" border=\"0\">";
				$catprev .= "</a>";
				$catprev .= "<br><a href=\"".$urlfunc->makePretty("?p=gallery&action=$action&pid=$showprevid&$actiondependent", $titleurl)."\">&lt;&lt; "._PREVIOUS."</a>";
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
				$titleurl["pid"] = $shownexttitle;
				$catnext .= "<div align=\"center\"><a href=\"".$urlfunc->makePretty("?p=gallery&action=$action&pid=$shownextid&$actiondependent", $titleurl)."\">";
				if($shownextname!='')
					$catnext .= "<img src=\"$cfg_thumb_url/$shownextname\" border=\"0\" alt=\"$shownexttitle\" title=\"$shownexttitle\" width=\"$new_w\" height=\"$new_h\">";
				else
					$catnext .= "<img src=\"$cfg_app_url/images/none.gif\" border=\"0\" >";
				$catnext .= "</a>";
				$catnext .= "<br><a href=\"".$urlfunc->makePretty("?p=gallery&action=$action&pid=$shownextid&$actiondependent", $titleurl)."\">"._NEXT." &gt;&gt;</a></div>";
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
			if ($pages>1) $catpage = aksipagination($namamodul,$screen,"p=gallery&action=search&keyword=$keyword");
			$catcontent .= show_me_the_images();
		} //end total_images
}  

//$catmenu --> ada, tapi tidak ditampilkan agar tampilan lebih rapi
$content .= ($catnav!='') ? "<div class=\"catnav\">"._CATEGORY.": $catnav</div><br />\r\n" : "" ;
$content .= ($catdesc!='') ? "<div class=\"catdesc\">$catdesc</div>\r\n" : "";
$content .= ($catsubcat!='') ? "<div class=\"catsubcat\">$catsubcat</div>\r\n" : "";
$content .= ($catpage!='') ? "<div class=\"catpage\">$catpage</div>\r\n" : "" ;
if ($ismobile) {
	$content .= '<div>' . $catimg . ' ' . $catcontent . '</div>';
	$content .= ( $catnav != '') ? "<br /><div class=\"catnav\">" . _CATEGORY . ": $catnav</div>\r\n" : "";
} else {
	// $content .= '<table width="100%" border="0">
			// <tr>
				// <td width="2%">&nbsp;&nbsp;</td>
				// <td colspan="2" width="96%">
				// <div id="gallery">'.$catimg.' '.$catcontent.'</div></td>
				// <td width="2%">&nbsp;&nbsp;</td>
			// </tr>
			// <tr>
			  // <td width="2%">&nbsp;</td>
			  // <td align="center" width="48%">'.$catprev.'</td>
			  // <td align="center" width="48%">'.$catnext.'</td>
			  // <td width="2%">&nbsp;</td>
			// </tr>
		// </table>
	// ';
	$content .= $catimg.' '.$catcontent;
}
?>
