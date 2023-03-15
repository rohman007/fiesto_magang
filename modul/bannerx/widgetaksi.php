<?php
$sort = "title";

if($posisi=="A")
{	$nilailebar = $widgetawidth;
}
elseif($posisi=="B")
{	$nilailebar = $widgetbwidth;
}

if ($jenis=='category') {
	$sql = "SELECT id FROM bannercat WHERE id='$isi'";
	$result = mysql_query($sql);	
	if (mysql_num_rows($result)>0) {
		$sql = "SELECT id, filename, title,url FROM bannerdata WHERE cat_id='$isi' AND publish='1' ORDER BY $sort";
		$result = mysql_query($sql);
		if (mysql_num_rows($result) > 0) {
			while (list($photo_id, $photo_filename, $photo_title,$url) = mysql_fetch_row($result)) {
				$lebar = "";
				$file_img = $cfg_fullsizepics_path."/".$photo_filename;
				if(file_exists($file_img))
				{	list($widthimg, $heightimg, $type, $attr) = getimagesize($file_img);
					if($widthimg > $nilailebar)
					{	$lebar = "width=\"$nilailebar\" ";
					}
				}
				$titleurl = array();
				$titleurl["pid"] = $photo_title;
				// $widget .= "<div class=\"bannerwidget\">";
				$widget_img ="<div class=\"bannerwidget\"><img src=\"$cfg_fullsizepics_url/$photo_filename\" class=\"img-responsive\" alt=\"$photo_title\" title=\"$photo_title\" $lebar ></div> ";	
				if(strlen($url)>0)
				{
				$widget .="<a href=\"".$urlfunc->makePretty("?p=banner&action=go&pid=$photo_id", $titleurl)."\">";
				$widget .=$widget_img;
				$widget .="	</a>";
				}
				else
				{
					$widget .=$widget_img;
				}
				// $widget .="	</div>\r\n";
			}
		} else {
			$widget .= _NOPROD;
		}
	} else {
		$widget .= _NOCAT;
	}
}

if ($jenis=='random') {
	$cfg_max_cols = $cfg_max_cols_widget;
	$sql = "SELECT id FROM bannercat WHERE id='$isi'";
	$result = mysql_query($sql);
	
	if (mysql_num_rows($result)>0) {
		$sql = "SELECT id, filename, title, url FROM bannerdata WHERE cat_id='$isi' AND publish='1' ORDER BY RAND() LIMIT 1";
		$result = mysql_query($sql);
		if (mysql_num_rows($result) > 0) {
			while (list($photo_id, $photo_filename, $photo_title, $photo_url) = mysql_fetch_row($result)) {
				$lebar = "";
				$file_img = $cfg_fullsizepics_path."/".$photo_filename;
				if(file_exists($file_img))
				{	list($widthimg, $heightimg, $type, $attr) = getimagesize($file_img);
					if($widthimg > $nilailebar)
					{	$lebar = "width=\"$nilailebar\" ";
					}
				}
				$titleurl = array();
				$titleurl["pid"] = $photo_title;
				if(strlen($photo_url)==0)
					$widget .= "<div class=\"bannerwidget\"><img src=\"$cfg_fullsizepics_url/$photo_filename\" class=\"img-responsive\" alt=\"$photo_title\" title=\"$photo_title\" $lebar ></div>\r\n";
				else
					$widget .= "<div class=\"bannerwidget\"><a href=\"".$urlfunc->makePretty("?p=banner&action=go&pid=$photo_id", $titleurl)."\"><img src=\"$cfg_fullsizepics_path/$photo_filename\" class=\"img-responsive\" alt=\"$photo_title\" title=\"$photo_title\" $lebar ></a></div>\r\n";
			}
		} else {
			$widget .= _NOPROD;
		}
	} else {
		$widget .= _NOCAT;
	}
}

if ($jenis=='single') {
	$sql = "SELECT id, filename, title, url FROM bannerdata WHERE id='$isi'";
	$result = mysql_query($sql);
	if (mysql_num_rows($result) > 0) {
		while (list($photo_id, $photo_filename, $photo_title, $photo_url) = mysql_fetch_row($result)) 
		{
			$lebar = "";
			$file_img = $cfg_fullsizepics_path."/".$photo_filename;
			if(file_exists($file_img))
			{	list($widthimg, $heightimg, $type, $attr) = getimagesize($file_img);
				if($widthimg > $nilailebar)
				{	$lebar = "width=\"$nilailebar\" ";
				}
			}
			$titleurl = array();
			$titleurl["pid"] = $photo_title;
		
			if (strlen($photo_url)==0) {
				$widget .= "<div class=\"bannerwidget\"><img src=\"$cfg_fullsizepics_url/$photo_filename\" class=\"img-responsive\" alt=\"$photo_title\" title=\"$photo_title\" $lebar ></div>\r\n";
			} else {
	 		$widget .= "<div class=\"bannerwidget\"><a href=\"".$urlfunc->makePretty("?p=banner&action=go&pid=$photo_id", $titleurl)."\"><img src=\"$cfg_fullsizepics_url/$photo_filename\" class=\"img-responsive\" alt=\"$photo_title\" title=\"$photo_title\" $lebar ></a></div>\r\n";
			}
		}
	} else {
		$widget .= _NOPROD;
	}
}

?>
