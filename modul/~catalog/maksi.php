<?php
$cat_id=fiestolaundry($_GET['cat_id'],11);
$action=fiestolaundry($_GET['action'],20);
$pid=fiestolaundry($_GET['pid'],11);
$keyword=fiestolaundry($_GET['keyword'],200);
$screen=fiestolaundry($_GET['screen'],11);
$merek=fiestolaundry($_GET['merek'],11);

//$catmenu = print_menu("catalog",$cat_id);

$sql = "SELECT judulfrontend FROM module WHERE nama='catalog'";
$result = mysql_query($sql);
list($title) = mysql_fetch_row($result);
if ($action=='') {
	$sql = "SELECT id, parent, nama FROM catalogcat ORDER BY urutan";
	$cats = new categories();
	$mycats = array();
	$result = mysql_query($sql);
	while($row = mysql_fetch_array($result)) {
		$mycats[] = array('id'=>$row['id'],'parent'=>$row['parent'],'nama'=>$row['nama'],'level'=>0);
	}
	$cats->get_cats($mycats);

	$currlevel = 1;
	$catcontent .= "<ul>\r\n";
	for ($i=0; $i<count($cats->cats); $i++) {
		$url = "<li><a href=\"?p=catalog&cat_id=".$cats->cats[$i]['id']."&action=images\">".$cats->cats[$i]['nama']."</a></li>";
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
		$sql = "SELECT id, description FROM catalogcat WHERE id='$cat_id'";
		$result = mysql_query($sql);
		list($cat_id, $catdesc) = mysql_fetch_row($result);
		
		$cats = new categories();
		$mycats = array();
		$sql = 'SELECT id, nama, parent, description FROM catalogcat';
		$result = mysql_query($sql);
		while($row = mysql_fetch_array($result)) {
			$mycats[] = array('id'=>$row['id'],'nama'=>$row['nama'],'parent'=>$row['parent'],'description'=>$row['description'],'level'=>0);
		}
		$cats->get_cats($mycats);

		$catnav = "<a href=\"?p=catalog\">$topcatnamenav</a>";
		for ($i=0; $i<count($cats->cats); $i++) {
			$cats->cat_map($cats->cats[$i]['id'],$mycats);
			if ($cats->cats[$i]['id'] == $cat_id) {
				//$catdesc = $cats->cats[$i]['description'];
				for ($a=0; $a<count($cats->cat_map); $a++) {
					$cat_parent_id = $cats->cat_map[$a]['id'];
					$cat_parent_name = $cats->cat_map[$a]['nama'];
					$catnav .= "$separatorstyle<a href=\"?p=catalog&l=id&action=images&cat_id=$cat_parent_id\">$cat_parent_name</a>";
				}
				$catnav .= $separatorstyle.$cats->cats[$i]['nama'];
			}
		}
		
		
		if (mysql_num_rows($result)>0) {
			$sql = "SELECT id, nama FROM catalogcat WHERE parent='$cat_id'";
			$result = mysql_query($sql);
			$totalsubcat = mysql_num_rows($result);
			if ($totalsubcat > 0) {
				//$catsubcat = "<div class=\"titleofsubcatlist\">"._SUBCAT."</div>\n";
				$catsubcat .= "<ul>\n";
				while(list($catsubcat_id, $catsubcat_name) = mysql_fetch_row($result)) {
					$catsubcat .= "<li><a href=\"?p=catalog&l=id&action=images&cat_id=$catsubcat_id\">$catsubcat_name</a></li>\n";
				}
				$catsubcat .= "</ul>\n";
			}

			// hitung dulu untuk menentukan berapa halaman...
			$sql = "SELECT id FROM catalogdata";
			if (!empty($cat_id)) {
				$sql .= " WHERE cat_id='$cat_id' AND publish='1'";
			} else {
				$sql .= " WHERE publish='1'";
			}
			
			$result = mysql_query($sql);
			$total_records = mysql_num_rows($result);
			$pages = ceil($total_records/$cfg_per_page);

			mysql_free_result($result);

			// get the image information
			if ($screen==''){
				$screen = 0;
			}

			$start = $screen * $cfg_per_page;

			// ... baru kemudian diambil lagi, kali ini dengan limit perhalaman
			$sql = "SELECT id, cat_id, filename, title, publish, ishot, ketsingkat, harganormal, diskon, matauang, 
					if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal),diskon) 
					as nilaidiskon,
					harganormal-(if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal),
						diskon)) as hargadiskon 
			FROM catalogdata";
			if (!empty($cat_id)) {
				$sql .= " WHERE cat_id='$cat_id' AND publish='1'";
			} else {
				$sql .= " WHERE publish='1'";
			}
			$sql .= " ORDER BY ".$sort;
			$sql .= " LIMIT $start, $cfg_per_page";

			$photo = mysql_query($sql);
			$total_images=mysql_num_rows($photo);

			if ($total_images=="0") {
				if($totalsubcat==0)
				{	$cattitle .= _ERROR;
					$catcontent .= _NOPROD;
				}
			} else {
				if ($pages>1) $catpage = pagination($namamodul,$screen,"cat_id=$cat_id&action=images");
				if ($showtype==1) $catcontent .= show_me_the_list();
				if ($showtype==0) $catcontent .= show_me_the_images_mobile();
			}
		} else {
			$cattitle .= _ERROR;
			$catcontent .= _NOCAT;
		}
	} else {
		$cattitle .= _ERROR;
		$catcontent .= _NOCAT;
	}
}


if (substr($action,0,4)=='view') {

	$sql = "SELECT cat_id FROM catalogdata WHERE id='$pid'";
	$result = mysql_query($sql);
	list($cat_id) = mysql_fetch_row($result);

	$cats = new categories();
	$mycats = array();
	$sql = 'SELECT id, nama, parent, description FROM catalogcat';
	$result = mysql_query($sql);
	while($row = mysql_fetch_array($result)) {
		$mycats[] = array('id'=>$row['id'],'nama'=>$row['nama'],'parent'=>$row['parent'],'description'=>$row['description'],'level'=>0);
	}
	$cats->get_cats($mycats);

	$catnav = "<a href=\"?p=catalog\">$topcatnamenav</a>";
	for ($i=0; $i<count($cats->cats); $i++) {
		$cats->cat_map($cats->cats[$i]['id'],$mycats);
		if ($cats->cats[$i]['id'] == $cat_id) {
			//$catdesc = $cats->cats[$i]['description'];
			for ($a=0; $a<count($cats->cat_map); $a++) {
				$cat_parent_id = $cats->cat_map[$a]['id'];
				$cat_parent_name = $cats->cat_map[$a]['nama'];
				$catnav .= "$separatorstyle<a href=\"?p=catalog&l=id&action=images&cat_id=$cat_parent_id\">$cat_parent_name</a>";
			}
			$catnav .= $separatorstyle."<a href=\"?p=catalog&l=id&action=images&cat_id=".$cats->cats[$i]['id']."\">".$cats->cats[$i]['nama']."</a>";
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
	/*if ($num > 0) {
		$sql = "SELECT id, cat_id, filename, date, title, publish, $tambahan FROM catalogdata WHERE id='$pid'";
	} else {
		$sql = "SELECT id, cat_id, filename, date, title, publish FROM catalogdata WHERE id='$pid'";
	}*/
	$sql = "SELECT id, cat_id, filename, title, publish, ishot, ketsingkat, keterangan,
					harganormal, diskon, matauang, 
					if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal),diskon) 
					as nilaidiskon,
					harganormal-(if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal),
						diskon)) as hargadiskon 
		FROM catalogdata WHERE id='$pid'";
		
	$getpic = mysql_query($sql);
	$hasil = mysql_fetch_array($getpic);

	$catimg .= '<img src="'.$cfg_fullsizepics_url.'/'.$hasil['filename'].'" '.$imagesize.' alt="'.$hasil['title'].'" title="'.$hasil['title'].'" />';

	//$title .= $hasil['title'];
	$catcontent = "<div class=\"catalogtitle\"><h1>".$hasil['title']."</h1></div>\r\n";
	if ($hasil['keterangan']!='') $catcontent .= '<div id="keterangan">'.$hasil['keterangan'].'</div>'."\n";
	
	$hasil['diskon'] = str_replace(" ","",$hasil['diskon']);
	if ($hasil['harganormal']>0) {
		if ($hasil['diskon']=='') {
			$kelasnormal = 'normalwodisc';
		} else {
			$kelasnormal = 'normalwdisc';
		}
		$catcontent .= '<p>'._PRICE.' <span class="'.$kelasnormal.'">'.$hasil['matauang'].' '.number_format($hasil['harganormal'],0,',','.').'</span>';
		if ($hasil['diskon']!='') 
		{	if(substr_count($hasil['diskon'],"%")==1)
			{	$label_disc = str_replace("%","",$hasil['diskon'])."%";
				
			}
			else
			{	$label_disc = $hasil['matauang'].' '.number_format($hasil['diskon'],0,',','.');
			}
			$catcontent .= ' <span class="discprice">'.$hasil['matauang'].' '.number_format($hasil['hargadiskon'],0,',','.').'</span>';
			$catcontent .= ' <span class="discvalue">('._YOUSAVE.' '.$label_disc.')</span>';
		}
		$catcontent .= '</p>'."\r\n";
		//START ADD TO CATALOG
		if ($pakaicart) {
			$catcontent .= "<div class=\"addtocart\">";
			$catcontent .= "<form action=\"\" method=\"GET\">";
			$catcontent .= "<input type=\"hidden\" name=\"p\" value=\"cart\" />";
			$catcontent .= "<input type=\"text\" name=\"qty\" id=\"qty\" size=\"2\" value=\"1\" style=\"text-align:right\" />";
			$catcontent .= "<input type=\"hidden\" name=\"pid\" value=\"$pid\" />";
			$catcontent .= "<input type=\"hidden\" name=\"action\" value=\"add\" />";
			$catcontent .= "<input type=\"submit\" id=\"addtocartbutton\" value=\"" . _ADDTOCART . "\" />";
			$catcontent .= "</form>";
			$catcontent .= "</div>";
		}
		//END ADD TO CATALOG
	}

	//prevnext
	//SELECT id, cat_id, filename, title, publish, ishot, ketsingkat, 
					
	$sql = "SELECT id, title, filename,
	harganormal, diskon, matauang, 
					if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal),diskon) 
					as nilaidiskon,
					harganormal-(if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal),
						diskon)) as hargadiskon
						 FROM catalogdata";
	if ($action=="viewsearch") $sql .= " WHERE ((title LIKE '%$keyword%') $searchtambahan)";
	if ($action=="viewimages") $sql .= " WHERE (cat_id='$cat_id')";
	if ($action=="viewbrand") $sql .= " WHERE (idmerek='$merek')";
	$sql .= " AND (publish='1') ORDER BY ".$sort;
	$result = mysql_query($sql);
	$num = mysql_num_rows($result);
	for ($i=0;$i<$num;$i++) {
		if (mysql_result($result,$i,"id") == $pid) {

			if ($i != 0) {
				$showprevid = mysql_result($result,$i-1,"id");
				$showprevname = mysql_result($result,$i-1,"filename");
				$showprevtitle = mysql_result($result,$i-1,"title");
			} else {
				$showprevid = -1;
			}

			if ($i != $num-1) {
				$shownextid = mysql_result($result,$i+1,"id");
				$shownextname = mysql_result($result,$i+1,"filename");
				$shownexttitle = mysql_result($result,$i+1,"title");
			} else {
				$shownextid = -1;
			}

			$tempthumb=$cfg_thumb_url;
			if ($showprevid != -1) {
				if (file_exists($cfg_thumb_path."/".$showprevname)) {
					$image_stats = getimagesize($cfg_thumb_path."/".$showprevname); 
					$new_w = $image_stats[0]; 
					$new_h = $image_stats[1]; 
					$if_thumb="yes";
				} elseif ((!file_exists($cfg_thumb_path."/".$showprevname)) && (file_exists($cfg_fullsizepics_path."/".$showprevname))) {
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
				} elseif ((!file_exists($cfg_thumb_path."/".$showprevname)) && (!file_exists($cfg_fullsizepics_path."/".$showprevname))) {
					$showprevname="none.gif";
				}	
				if ($action=='viewsearch') $actiondependent = "keyword=$keyword";
				if ($action=='viewimages') $actiondependent = "cat_id=$cat_id";
				if ($action=='viewbrand') $actiondependent = "merek=$merek";
				$catprev .= "<a href=\"?p=catalog&pid=$showprevid&action=$action&$actiondependent\"><img src=\"$cfg_thumb_url/$showprevname?r=$random\" border=\"0\" alt=\"$showprevtitle\" title=\"$showprevtitle\" width=\"$new_w\" height=\"$new_h\"></a>";
				$catprev .= "<br><a href=\"?p=catalog&pid=$showprevid&action=$action&$actiondependent\">&lt;&lt; "._PREVIOUS."</a>";
			} else {
				$catprev .= '&nbsp;';
			}
			$cfg_thumb_url=$tempthumb;


			$tempthumb=$cfg_thumb_url;
			if ($shownextid != -1) {
				if (file_exists($cfg_thumb_path."/".$shownextname)) {
					$image_stats = getimagesize($cfg_thumb_path."/".$shownextname); 
					$new_w = $image_stats[0]; 
					$new_h = $image_stats[1]; 
					$if_thumb="yes";
				} elseif ((!file_exists($cfg_thumb_path."/".$shownextname)) && (file_exists($cfg_fullsizepics_path."/".$shownextname))) {
					$image_stats = getimagesize($cfg_fullsizepics_path."/".$shownextname); 
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
				} elseif ((!file_exists($cfg_thumb_path."/".$shownextname)) && (!file_exists($cfg_fullsizepics_path."/".$shownextname))) {
					$shownextname="none.gif";
				}
				if ($action=='viewsearch') $actiondependent = "keyword=$keyword";
				if ($action=='viewimages') $actiondependent = "cat_id=$cat_id";
				if ($action=='viewbrand') $actiondependent = "merek=$merek";
				$catnext .= "<a href=\"?p=catalog&pid=$shownextid&action=$action&$actiondependent\"><img src=\"$cfg_thumb_url/$shownextname?r=$random\" border=\"0\" alt=\"$shownexttitle\" title=\"$shownexttitle\" width=\"$new_w\" height=\"$new_h\"></a>";
				$catnext .= "<br><a href=\"?p=catalog&pid=$shownextid&action=$action&$actiondependent\">"._NEXT." &gt;&gt;</a>";
			} else {
				$catnext .= '&nbsp;';
			}
			$cfg_thumb_url=$tempthumb;
		}
	}

}

if ($action == "search") {
	$title .= _SEARCHRESULTS;
	if ($keyword!='') {
		$tambahan = '';
		$num = count($searchedcf);
		for ($i=0;$i<$num;$i++) {
			$tambahan .= " OR ($searchedcf[$i] LIKE '%$keyword%')";
		}

		$sql = "SELECT id FROM catalogdata WHERE ((title LIKE '%$keyword%') $tambahan) AND publish='1'";
		$result = mysql_query($sql);
		$total_records = mysql_num_rows($result);
		$pages = ceil($total_records / $cfg_per_page);

		if ($screen==''){
			  $screen = 0;
		}

		$start = $screen * $cfg_per_page;

		$sql = "SELECT id, cat_id, filename, title, publish, ishot, ketsingkat, harganormal, diskon, matauang, 
					if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal),diskon) 
					as nilaidiskon,
					harganormal-(if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal),
						diskon)) as hargadiskon
						 FROM catalogdata WHERE ((title LIKE '%$keyword%') $tambahan) AND publish='1' ORDER BY $sort LIMIT $start, $cfg_per_page";
		$photo = mysql_query($sql);
		$total_images=mysql_num_rows($photo);

		if ($total_images>0) {
			$keyword= urlencode($keyword);
			if ($pages>1) $catpage = pagination($namamodul,$screen,"p=catalog&action=search&keyword=$keyword");
			if ($showtype==1) $catcontent .= show_me_the_list();
			if ($showtype==0) $catcontent .= show_me_the_images();
		} else {
			$catcontent .= _NOSEARCHRESULTS;
		}
	} else {
		$catcontent .= _NOSEARCHRESULTS;
	}
}  

if ($action == "brand") {
		$sql = "SELECT nama FROM catalogmerek WHERE id='$merek'";
		$result = mysql_query($sql);
		list($title) = mysql_fetch_row($result);

		$sql = "SELECT id FROM catalogdata WHERE idmerek='$merek' AND publish='1'";
		$result = mysql_query($sql);
		$total_records = mysql_num_rows($result);
		$pages = ceil($total_records / $cfg_per_page);

		if ($screen=='') $screen = 0;

		$start = $screen * $cfg_per_page;

		$sql = "SELECT id, cat_id, filename, title, publish, ishot, ketsingkat, harganormal, diskon, matauang, 
					if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal),diskon) 
					as nilaidiskon,
					harganormal-(if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal),
						diskon)) as hargadiskon
		 FROM catalogdata WHERE idmerek='$merek' AND publish='1' ORDER BY $sort LIMIT $start, $cfg_per_page";
		$photo = mysql_query($sql);
		$total_images=mysql_num_rows($photo);

		if ($total_images=="0") {
			$catcontent .= _NOPROD;
		} else {
			$keyword= urlencode($keyword);
			if ($pages>1) $catpage = pagination($namamodul,$screen,"p=catalog&action=brand&merek=$merek");
			if ($showtype==1) $catcontent .= show_me_the_list();
			if ($showtype==0) $catcontent .= show_me_the_images();
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
