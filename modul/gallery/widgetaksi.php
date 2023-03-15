<?php
if ($jenis=='slideshow') {
	$sql  = "SELECT id, cat_id, filename, title, keterangan FROM gallerydata WHERE publish='1' AND ishot='1'";
	$result = $mysql->query($sql);
	if ($mysql->num_rows($result)>0) {
		$widget.= "<div class=\"slideshow\">\r\n";
		while (list($photo_id, $photo_cat_id, $photo_filename, $photo_title, $photo_desc) = $mysql->fetch_row($result)) {
			switch ($gallerystyle) {
				case 0:	//plain
					$widget .= "<a href=\"".$urlfunc->makePretty("?p=gallery&action=viewimages&pid=$photo_id&cat_id=$photo_cat_id", $titleurl)."\">";
					if($photo_filename!='')
						$widget .= "<img src=\"$cfg_thumb_url/$photo_filename\" border=\"0\" />";
					else
						$widget .= "<img src=\"$cfg_app_url/images/none.gif\" border=\"0\" />";
					$widget .= "</a>";
					break;
				case 1:	//sexyliightbox
					$photo_desc = htmlspecialchars(strip_tags($photo_desc));
					$titletag = ($photo_desc=='') ? $photo_title : "$photo_title: $photo_desc";
					if($photo_filename!=''){
						$widget .= "<a rel=\"sexylightbox[gallery]\" href=\"$cfg_fullsizepics_url/$photo_filename\" title=\"$titletag\">";
						$widget .= "<img src=\"$cfg_thumb_url/$photo_filename\" border=\"0\" height=\"$new_h\" width=\"$new_w\" alt=\"$photo_title\" title=\"$photo_title\" class=\"photos\">";
					}else{
						$widget .= "<a rel=\"sexylightbox[gallery]\" href=\"$cfg_app_url/images/none.gif\">";
						$widget .= "<img src=\"$cfg_app_url/images/none.gif\" border=\"0\" class=\"photos\" />";
					}
					$widget .= "</a>\r\n";
					break;
			}
		}
		$widget.= "</div>\r\n";
	} else {
		$widget.= _NOIMAGE;
	}
}

if ($jenis=='slideshowcategory') {
	$sql = "SELECT id FROM gallerycat WHERE id='$isi'";
	
	$result = $mysql->query($sql);	
	if (($result and $mysql->num_rows($result)>0) or $isi=="0") {
		if($isi==0) 
		{
		//jika gambar ada di top
		$sql  = "SELECT d.id, d.cat_id, d.filename, d.title,'/' nama FROM gallerydata d WHERE publish='1' AND d.cat_id='$isi' order by $sort ";
		}
		else
		{
		
		$sql  = "SELECT d.id, d.cat_id, d.filename, d.title, c.nama FROM gallerydata d, gallerycat c WHERE publish='1' AND d.cat_id=c.id AND d.cat_id='$isi' order by d.$sort";
	
		}
		
		$result = $mysql->query($sql);
		if ($mysql->num_rows($result)>0) 
		{	$widget.= "<div class=\"slideshow\">\r\n";
			while (list($photo_id, $photo_cat_id, $photo_filename, $photo_title, $catname) = $mysql->fetch_row($result)) 
			{
				switch ($gallerystyle) {
					case 0:	//plain
						$titleurl = array();
						$titleurl["pid"] = $photo_title;
						$titleurl["cat_id"] = $catname;
						$widget .= "<a href=\"".$urlfunc->makePretty("?p=gallery&action=viewimages&pid=$photo_id&cat_id=$photo_cat_id", $titleurl)."\">";
						if($photo_filename!='')
							$widget .= "<img src=\"$cfg_thumb_url/$photo_filename\" border=\"0\" />";
						else
							$widget .= "<img src=\"$cfg_app_url/images/none.gif\" border=\"0\" />";
						$widget .= "</a>";
						break;
					case 1:	//sexyliightbox
						$photo_desc = htmlspecialchars(strip_tags($photo_desc));
						$titletag = ($photo_desc=='') ? $photo_title : "$photo_title: $photo_desc";
						if($photo_filename!=''){
							$widget .= "<a rel=\"sexylightbox[gallery]\" href=\"$cfg_fullsizepics_url/$photo_filename\" title=\"$titletag\">";
							$widget .= "<img src=\"$cfg_thumb_url/$photo_filename\" border=\"0\" height=\"$new_h\" width=\"$new_w\" alt=\"$photo_title\" title=\"$photo_title\" class=\"photos\">";
						}else{
							$widget .= "<a rel=\"sexylightbox[gallery]\" href=\"$cfg_app_url/images/none.gif\">";
							$widget .= "<img src=\"$cfg_app_url/images/none.gif\" border=\"0\" class=\"photos\" />";
						}
						$widget .= "</a>\r\n";
						break;
				}
			}
			$widget.= "</div>\r\n";
		} 
		else 
		{	$widget.= _NOIMAGE;
		}
	} 
	else 
	{	$widget .= _NOCAT;
	}
}

if ($jenis=='catlist') {
	$sql = "SELECT id, nama, parent FROM gallerycat";
	$cats = new categories();
	$mycats = array();
	$result = $mysql->query($sql);
	while($row = $mysql->fetch_array($result)) {
		$mycats[] = array('id'=>$row['id'],'nama'=>$row['nama'],'parent'=>$row['parent'],'level'=>0);
	}
	$cats->get_cats($mycats);

	$currlevel = 1;
	$widget .= "<ul>\n";
	for ($i=0; $i<count($cats->cats); $i++) {
		$selisihlevel=$cats->cats[$i]['level']-$currlevel;
		if ($selisihlevel>0) {
			$widget .= "<ul>\n";
			$widget .= "<li><a href=\"?p=gallery&cat_id=".$cats->cats[$i]['id']."&action=images\">".$cats->cats[$i]['nama']."</a> ";
		}
		if ($selisihlevel==0) {
			$widget .= "<li><a href=\"?p=gallery&cat_id=".$cats->cats[$i]['id']."&action=images\">".$cats->cats[$i]['nama']."</a> ";
		}
		if ($selisihlevel<0) {
			for ($j=0; $j<-$selisihlevel; $j++) {
				$widget .= "</ul>\n";
			}
			$widget .= "<li><a href=\"?p=gallery&cat_id=".$cats->cats[$i]['id']."&action=images\">".$cats->cats[$i]['nama']."</a> ";
		}
		$currlevel=$cats->cats[$i]['level'];
	}	
	$widget .= "</ul>\n";
}

?>