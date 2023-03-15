<?php
function show_me_the_images() {
	global $action, $cfg_max_cols;
	global $cfg_thumb_width, $sql, $cfg_thumb_path, $cfg_thumb_url,$imagefolder;
	global $screen, $pages, $photo, $cat_id, $keyword, $random;
	global $mysqli;

	$x = 1;
	$catcontent .= '<table id="producttable" border="0" cellpadding="0" cellspacing="0" width="100%">';
	while (list($photo_id, $photo_cat_id, $photo_filename, $photo_title, $photo_published, $photo_ishot) = $photo->fetch_row()) {
		$lebar = 100 / $cfg_max_cols;
		if (($x % $cfg_max_cols) == 1 || $cfg_max_cols==1) {
			$imgcell = "<tr>\n<td valign=\"bottom\" align=\"center\" width=\"$lebar%\">\n&nbsp;";
		} else {
			$imgcell .= "<td valign=\"bottom\" align=\"center\" width=\"$lebar%\">\n&nbsp;";
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
		
		$imgcell .= "<a href=\"".$urlfunc->makePretty("?p=banner&action=go&pid=$photo_id", $titleurl)."\"><img src=\"$cfg_thumb_url/$photo_filename\" border=\"0\" height=\"$new_h\" width=\"$new_w\" alt=\"$photo_title\" title=\"$photo_title\" class=\"photos\"></a>\r\n";
		//Junaidi: Bugfix agar selalu ambil dari direktori thumb jika ada thumbnail
		$cfg_thumb_url=$tempthumb;

		// if we're in admin mode, print out the admin links to edit, delete, etc, and whether
		// or not it's a published image
		if (($x % $cfg_max_cols) == 0) {
			$imgcell .= "</td></tr>\n";
			$catcontent .= $imgcell;
		} else {
			$imgcell .= "</td>\n";
		}
		$x++;
	}
	if (($x-1) % $cfg_max_cols != 0) {
		while ($x % $cfg_max_cols > 0) {
			$imgcell .= "<td valign=\"bottom\" align=\"center\" width=\"$lebar%\">&nbsp;</td>\n";
			$x++;
		}
		$imgcell .= "<td valign=\"bottom\" align=\"center\" width=\"$lebar%\">&nbsp;</td></tr>\n";
		$catcontent .= $imgcell;
	}
	$catcontent .= "</table>\n";
	return $catcontent;
}
function show_me_the_list() {
	global $cfg_thumb_url, $photo, $cfg_fullsizepics_url,$cfg_fullsizepics_path;
	global $mysqli;
	
	$intro = $mysqli->query("SELECT value FROM config WHERE name='bannerlistintro'");
	$show_intro = $intro->fetch_array();
	$catcontent .= "$show_intro[0]";
	
	$catcontent .= "<div class=\"clientdiv\">";
	$catcontent .= "<ul class=\"clientul\">";
	while (list($id, $title, $filename, $publish, $url) = $photo->fetch_row()) {
		$titleurl = array();
		$titleurl["pid"] = $photo_title;
					
		if ($filename!=''){ 
			$catcontent .= "<li class=\"clientli\">";
			//if ($url!='') $catcontent .= "<a href=\"$url\">";
			//else $catcontent .= "<a href=\"#\">";
			
			$catcontent .= "<img src=\"$cfg_fullsizepics_url/$filename\" $fileattr border=\"0\" alt=\"$title\" title=\"$title\" class=\"photos\">";
			//$catcontent .= "</a>";
			$catcontent .= "</li>\r\n";
		}
	}
	$catcontent .= "</ul></div>\r\n";
	return $catcontent;
}
?>
