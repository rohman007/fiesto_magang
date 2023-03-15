<?php

function leastcatoption($cat_id="") {
    global $catmenu, $topcatnamecombo;
	global $mysql;
    
    $cats = new categories();
    $mycats = array();
    $sql = 'SELECT id, nama, parent FROM gallerycat';
    $result = $mysql->query($sql);
    while ($row = $mysql->fetch_array($result)) {
        $mycats[] = array('id' => $row['id'], 'nama' => $row['nama'], 'parent' => $row['parent'], 'level' => 0);
    }
    $cats->get_cats($mycats);

    $catmenu .= "<option value=\"\">----- "._CATEGORY." -----</option>\n";
    for ($i = 0; $i < count($cats->cats); $i++) {
		$sql1 = "SELECT id, nama FROM gallerycat WHERE parent='".$cats->cats[$i]['id']."'";
		$result1 = $mysql->query($sql1);
		if ($mysql->num_rows($result1)==0) {	//ujung (tidak punya parent)
			$cats->cat_map($cats->cats[$i]['id'], $mycats);
			$cat_name = $cats->cats[$i]['nama'];
			$catmenu .= '<option value="' . $cats->cats[$i]['id'] . '"';
			$catmenu .= ( $cats->cats[$i]['id'] == $cat_id) ? " selected>$topcatnamecombo" : ">$topcatnamecombo";
			for ($a = 0; $a < count($cats->cat_map); $a++) {
				$cat_parent_id = $cats->cat_map[$a]['id'];
				$cat_parent_name = $cats->cat_map[$a]['nama'];
				$catmenu .= " / $cat_parent_name";
			}
			$catmenu .= " / $cat_name</option>\r\n";
		}
    }

    $catmenu .= "</select>\r\n";
    return $catmenu;
}

function show_me_the_images($isadmin=FALSE) {
	global $action, $cfg_per_page, $cfg_max_cols, $urlfunc;
	global $cfg_thumb_width, $sql, $cfg_thumb_path, $cfg_thumb_url,$imagefolder;
	global $screen, $pages, $photo, $cat_id, $keyword, $random, $gallerystyle;
	global $cfg_fullsizepics_path, $cfg_fullsizepics_url, $cfg_app_url, $ismobile;
	global $mysql;

	$x = 1;
	if (!$ismobile) $catcontent .= '<table id="gallery_thumbnail" border="0" cellpadding="0" cellspacing="0" width="100%">';
	while (list($photo_id, $photo_cat_id, $photo_filename, $photo_title, $photo_published, $photo_ishot) = $mysql->fetch_row($photo)) {
		$lebar = 100 / $cfg_max_cols;
		if (!$ismobile) {
			if (($x % $cfg_max_cols) == 1 || $cfg_max_cols==1) {
				$imgcell = "<tr>\n<td valign=\"bottom\" align=\"center\" width=\"$lebar%\">\n&nbsp;";
				$txtcell = "<tr>\n<td valign=\"top\" align=\"center\" width=\"$lebar%\">";
			} else {
				$imgcell .= "<td valign=\"bottom\" align=\"center\" width=\"$lebar%\">\n&nbsp;";
				$txtcell .= "<td valign=\"top\" align=\"center\" width=\"$lebar%\">";
			}
		}
		//Junaidi: Bugfix agar selalu ambil dari direktori thumb jika ada thumbnail
		$tempthumb=$cfg_thumb_url;
		if ((file_exists($cfg_thumb_path."/".$photo_filename))&&($photo_filename!='')) {
			$image_stats = getimagesize($cfg_thumb_path."/".$photo_filename); 
			$new_w = $image_stats[0]; 
			$new_h = $image_stats[1]; 
			$if_thumb="yes";
		} elseif ((!file_exists($cfg_thumb_path."/".$photo_filename)) && (file_exists($cfg_fullsizepics_path."/".$photo_filename)) && ($photo_filename!='')) {
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
		}		
		$titleurl = array();
		$titleurl["pid"] = $photo_title;
				
		if ($action=='search') $actiondependent = "keyword=$keyword";
		if ($action=='images') 
		{	$actiondependent = "cat_id=$cat_id";
			$sqlcatname = "SELECT nama FROM gallerycat WHERE id='$cat_id' ";
			$resultcatname = $mysql->query($sqlcatname);
			list($catname) = $mysql->fetch_row($resultcatname);
			$titleurl["cat_id"] = $catname;
			$mysql->free_result($resultcatname);
		}

		if ($isadmin) {
			$imgcell .= "<a href=\"?p=gallery&pid=$photo_id&action=edit&$actiondependent\">";
			if($photo_filename!='')
				$imgcell .= "<img src=\"$cfg_thumb_url/$photo_filename\" border=\"0\" height=\"$new_h\" width=\"$new_w\" alt=\"$photo_title\" title=\"$photo_title\" class=\"photos\">";
			else
				$imgcell .= "<img src=\"$cfg_app_url/images/none.gif\" border=\"0\" class=\"photos\">";
			$imgcell .= "</a>\r\n";
			$txtcell .= "<span class=\"catpublishis$photo_published\" align=\"center\"><span class=\"cathotis$photo_ishot\">$photo_title</span></span>\r\n";
		} else {
			switch ($gallerystyle) {
				case 0:	//plain
					$imgcell .= "<p align=\"center\"><a href=\"".$urlfunc->makePretty("?p=gallery&action=view$action&pid=$photo_id&$actiondependent", $titleurl)."\">";
					if($photo_filename!='')
						$imgcell .= "<img src=\"$cfg_thumb_url/$photo_filename\" border=\"0\" height=\"$new_h\" width=\"$new_w\" alt=\"$photo_title\" title=\"$photo_title\" class=\"photos\">";
					else
						$imgcell .= "<img src=\"$cfg_app_url/images/none.gif\" border=\"0\" class=\"photos\">";
					$imgcell .= "</a></p>\r\n";
					$txtcell .= "<p align=\"center\" class=\"photo_title\">$photo_title</p>\r\n";
					break;
				case 1:	//sexyliightbox
					$photo_desc = htmlspecialchars(strip_tags($photo_desc));
					$titletag = ($photo_desc=='') ? $photo_title : "$photo_title: $photo_desc";
					if($photo_filename!='')
						$imgcell .= "<a rel=\"sexylightbox[gallery]\" href=\"$cfg_fullsizepics_url/$photo_filename\" title=\"$titletag\"><img src=\"$cfg_thumb_url/$photo_filename\" border=\"0\" height=\"$new_h\" width=\"$new_w\" alt=\"$photo_title\" title=\"$photo_title\" class=\"photos\"></a>\r\n";
					else
						$imgcell .= "<a rel=\"sexylightbox[gallery]\" href=\"$cfg_app_url/images/none.gif\"><img src=\"$cfg_app_url/images/none.gif\" border=\"0\" class=\"photos\"></a>\r\n";
					$txtcell .= "<p align=\"center\" class=\"photo_title\">$photo_title</p>\r\n";
					break;
			}
		}
	
		//Junaidi: Bugfix agar selalu ambil dari direktori thumb jika ada thumbnail
		$cfg_thumb_url=$tempthumb;

		// if we're in admin mode, print out the admin links to edit, delete, etc, and whether
		// or not it's a published image
		if ($isadmin) {
			$txtcell .= "<br />[<a href=\"?p=gallery&pid=$photo_id&action=edit&$actiondependent\">"._EDIT."</a>] [<a href=\"?p=gallery&pid=$photo_id&action=delete\">"._DEL."</a>]";
		}
		if (!$ismobile) {
			if (($x % $cfg_max_cols) == 0) {
				$imgcell .= "</td></tr>\n";
				$txtcell .= "</td></tr>\n";
				$catcontent .= $imgcell.$txtcell;
			} else {
				$imgcell .= "</td>\n";
				$txtcell .= "</td>\n";
			}
		} else {
			$catcontent .= $imgcell.$txtcell."<br />";
			$imgcell = '';
			$txtcell = '';
		}
		$x++;
	}
	if (!$ismobile) {
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
	}
	return $catcontent;
}
function show_me_the_images_mobile($isadmin=FALSE) {
	global $action, $cfg_per_page, $cfg_max_cols, $urlfunc;
	global $cfg_thumb_width, $sql, $cfg_thumb_path, $cfg_thumb_url,$imagefolder;
	global $screen, $pages, $photo, $cat_id, $keyword, $random, $gallerystyle;
	global $cfg_fullsizepics_path, $cfg_fullsizepics_url, $cfg_app_url;
	global $mysql;

	while (list($photo_id, $photo_cat_id, $photo_filename, $photo_title, $photo_published, $photo_ishot) = $mysql->fetch_row($photo)) {
		//Junaidi: Bugfix agar selalu ambil dari direktori thumb jika ada thumbnail
		$tempthumb=$cfg_thumb_url;
		if ((file_exists($cfg_thumb_path."/".$photo_filename))&&($photo_filename!='')) {
			$image_stats = getimagesize($cfg_thumb_path."/".$photo_filename); 
			$new_w = $image_stats[0]; 
			$new_h = $image_stats[1]; 
			$if_thumb="yes";
		} elseif ((!file_exists($cfg_thumb_path."/".$photo_filename)) && (file_exists($cfg_fullsizepics_path."/".$photo_filename)) && ($photo_filename!='')) {
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
		}	
		$titleurl = array();
		$titleurl["pid"] = $photo_title;
				
		if ($action=='search') $actiondependent = "keyword=$keyword";
		if ($action=='images') 
		{	$actiondependent = "cat_id=$cat_id";
			$sqlcatname = "SELECT nama FROM gallerycat WHERE id='$cat_id' ";
			$resultcatname = $mysql->query($sqlcatname);
			list($catname) = $resultcatname->fetch_row();
			$titleurl["cat_id"] = $catname;
			// $mysql->free_result -> $resultcatname->close()
			$resultcatname->close();
		}
		switch ($gallerystyle) {
			case 0:	//plain
				$catcontent .= "<p>";
				$catcontent .= "<a href=\"".$urlfunc->makePretty("?p=gallery&action=view$action&pid=$photo_id&$actiondependent", $titleurl)."\">";
				if($photo_filename!='')
					$catcontent .= "<img src=\"$cfg_thumb_url/$photo_filename\" border=\"0\" height=\"$new_h\" width=\"$new_w\" alt=\"$photo_title\" title=\"$photo_title\" class=\"photos\">";
				else
					$catcontent .= "<img src=\"$cfg_app_url/images/none.gif\" border=\"0\" class=\"photos\">";
				$catcontent .= "</a>\r\n";
				$catcontent .= "<span class=\"photo_title\">$photo_title</span>\r\n";
				$catcontent .= "</p>";
				break;
			case 1:	//sexyliightbox dihilangkan untuk versi mobile, hasil sama dengan plain diatas
				$catcontent .= "<p>";
				$catcontent .= "<a href=\"".$urlfunc->makePretty("?p=gallery&action=view$action&pid=$photo_id&$actiondependent", $titleurl)."\">";
				if($photo_filename!='')
					$catcontent .= "<img src=\"$cfg_thumb_url/$photo_filename\" border=\"0\" height=\"$new_h\" width=\"$new_w\" alt=\"$photo_title\" title=\"$photo_title\" class=\"photos\">";
				else
					$catcontent .= "<img src=\"$cfg_app_url/images/none.gif\" border=\"0\" class=\"photos\">";
				$catcontent .= "</a>\r\n";
				$catcontent .= "<span class=\"photo_title\">$photo_title</span>\r\n";
				$catcontent .= "</p>";
				break;
		}
		//Junaidi: Bugfix agar selalu ambil dari direktori thumb jika ada thumbnail
		$cfg_thumb_url=$tempthumb;
	}
	return $catcontent;
}
function cat_title($parent)
{
	global $separatorstyle,$topcatnamenav,$urlfunc;
	global $mysql;
	$cats = new categories();
    $mycats = array();
    $sql = "SELECT id, nama, parent, description FROM gallerycat ORDER BY urutan ";
    $result = $mysql->query($sql);
    while ($row = $mysql->fetch_array($result)) {
        $mycats[] = array('id' => $row['id'], 'nama' => $row['nama'], 'parent' => $row['parent'], 'description' => $row['description'], 'level' => 0);
    }
    $cats->get_cats($mycats);
    $result->close();

    $catnav = "";
    for ($i = 0; $i < count($cats->cats); $i++) {
        $cats->cat_map($cats->cats[$i]['id'], $mycats);
        if ($cats->cats[$i]['id'] == $parent) {
            $catdesc = $cats->cats[$i]['description'];
            for ($a = 0; $a < count($cats->cat_map); $a++) {
                $cat_parent_id = $cats->cat_map[$a]['id'];
                $cat_parent_name = $cats->cat_map[$a]['nama'];
				$titleurl = array();
				$titleurl['parent'] = $cat_parent_name;
                $catnav .= "$separatorstyle<a href=\"".$urlfunc->makePretty("?p=gallery&action=main&parent=$cat_parent_id", $titleurl)."\">$cat_parent_name</a>";
            }
            $catnav .= $separatorstyle . $cats->cats[$i]['nama'];
        }
    }
	if($catnav!="")
	{
	$catnav="<a href=\"".$urlfunc->makePretty("?p=gallery&action=main")."\">$topcatnamenav</a>".$catnav;
	}
	else
	{
	$catnav="$topcatnamenav".$catnav;
	}
	return $catnav;
}

function catstructure($cat_id,$admin=false){
	global $urlfunc,$cfg_app_url,$cfg_file_path,$cfg_file_url,$gallerystyle,$action;
	global $cfg_thumb_path, $cfg_thumb_url,$cfg_fullsizepics_url,$keyword,$photo ;
	global $mysql;
	
	$cats = new categories();
	$mycats = array();
	// $sql="
	// SELECT x.type,x.id,x.nama,x.parent,x.filename,x.total
	// FROM
	// (
	// SELECT 0 type,id, nama, parent,filename,urutan,((select count(id) FROM gallerydata WHERE cat_id=g.id)+(select count(id) FROM gallerycat WHERE parent=g.id)) total FROM gallerycat g WHERE parent=$cat_id
	// UNION ALL 
	// SELECT 1 type,id,title nama,0 parent,filename,0 urutan,0 total FROM gallerydata WHERE cat_id=$cat_id
	// ) x ORDER BY x.type,x.urutan
	// ";
	
	// $result = $mysql->query($sql);
	// $catcontent .= "<ul class=\"list_gallery\">\n";
	$catcontent .= ($admin) ? "<ul class=\"list_gallery\">\n" : "<div class=\"row\">";
	while($row = $mysql->fetch_assoc($photo)) 
	{
	
	if($row['type']==0)
	{
		$fotocustom="";
		if($row['filename']!='')

		{
		$cekfoto="$cfg_file_path/file/gallery/cat/".$row['filename'];
			
			if(file_exists($cekfoto))
			{
			$fotocustom="$cfg_file_url/file/gallery/cat/".$row['filename'];
			}
		}
		
		if($fotocustom=="")
		{
		$sql=$mysql->query("SELECT filename FROM gallerydata WHERE cat_id='".$row['id']."' order by id limit 1");
			if($sql and $sql->num_rows>0)
			{
			list($filename)=$sql->fetch_row();
				$cekfoto="$cfg_thumb_path/$filename";
				
				
				if(file_exists($cekfoto))
				{
				$fotocustom="$cfg_thumb_url/$filename";
				}
			}
		}
		
		if($fotocustom!="")
		{
		$alamatfoto="$cfg_app_url/modul/gallery/images/folder.png";
		}
		else
		{
		$alamatfoto="$cfg_app_url/modul/gallery/images/folder-default.png";
		}
		
		$total=" (".$row['total'].")";
			
		
		if ($admin) {
			
			$catcontent .= "<li>
			<a href=\"?p=gallery&action=main&cat_id=".$row['id']."\">
			<div class=\"gallery_icon\">
			<div style=\"
			background-image:url('$fotocustom');
			background-repeat:no-repeat;
			background-position:center;
			background-size:50px 50px;
			height:164px;
			\"><img src=\"$alamatfoto\"></div></div>
			<div class=\"gallery_name\">".$row['nama'].$total."</div></a> ";
		} else {
			$titleurl = array();
			$titleurl['cat_id'] = $row['nama'];
			$catcontent .= "<div class=\" col-md-4 col-xs-6 thumb\">\n";
			$catcontent .= "
				<a href=\"".$urlfunc->makePretty("?p=gallery&action=main&cat_id=".$row['id'],$titleurl)."\" class=\"thumbnail\">
					<div class=\"gallery_icon\">
					<div style=\"
						background-image:url('$fotocustom');
						background-repeat:no-repeat;
						background-position:center;
						background-size:50px 50px;
						height:164px;
						\">
					<img alt=\"".$row['nama']."\" src=\"$alamatfoto\" class=\"img-responsive\"></div></div>
					<div class=\"name-photo text-center\">".$row['nama']."</div>
				</a>
			";			
		}

		if($admin)
		{
		
		$catcontent .= "<div class=\"gallery_editor\"><a href=\"?p=gallery&action=main&cat_id=".$row['id']."\"><img alt=\"". _OPEN."\" border=\"0\" src=\"../images/open.gif\"></a> ";
		$catcontent .= "<a href=\"?p=gallery&action=catedit&cat_id=".$row['id']."\"><img alt=\"". _EDIT."\" border=\"0\" src=\"../images/modify.gif\"></a> 
		<a href=\"?p=gallery&action=catdel&cat_id=" . $row['id'] . "\"><img alt=\"" . _DEL . "\" border=\"0\" src=\"../images/delete.gif\"></a>
		</div>";
		$catcontent .="</li>\n";
		}

		
	}
	if($row['type']=='1')
	{
	
		$photo_title=$row['nama'];
		if($row['filename']!='')
		{
		$alamatfoto="";
		$cekfoto="$cfg_thumb_path/".$row['filename'];
			if(file_exists($cekfoto))
			{
			$alamatfoto="$cfg_thumb_url/".$row['filename'];
			}
		}
		
		if($alamatfoto=="")
		{
		$alamatfoto="$cfg_app_url/images/none.gif";
		}
			
		$titleurl = array();
		$titleurl['pid'] = $row['nama'];
		// $titleurl['cat_id'] = $row['nama'];
		if($admin)
		{
		$catcontent .= "<li><a href=\"?p=gallery&pid=".$row['id']."&cat_id=$cat_id&action=edit\"><div class=\"gallery_icon\"><img src=\"$alamatfoto\"></div><div class=\"gallery_name\">".$row['nama'] . "</div></a> ";
		$catcontent .= "<div class=\"gallery_editor\"><a href=\"?p=gallery&action=edit&pid=".$row['id']."&cat_id=$cat_id\"><img alt=\"". _EDIT."\" border=\"0\" src=\"../images/modify.gif\"></a> 
		<a href=\"?p=gallery&action=delete&pid=" . $row['id'] . "\"><img alt=\"" . _DEL . "\" border=\"0\" src=\"../images/delete.gif\"></a>
		</div>";
		$catcontent .="</li>\n";
		}
		else
		{
			$option="";
			switch ($gallerystyle) 
			{
			case 0:
			$urlimages=$urlfunc->makePretty("?p=gallery&action=viewimages&cat_id=$cat_id&pid=".$row['id'],$titleurl);
		
			break;
			case 1:
			$urlimages="$cfg_fullsizepics_url/".$row['filename'];
			$option=" data-fancybox-group=\"gallery\" href=\"$cfg_fullsizepics_url/".$row['filename']."\" alt=\"$photo_title\" title=\"$photo_title\" ";
				break;
			}
			// $catcontent .= "<li><a href=\"".$urlimages."\" $option><div class=\"gallery_icon\"><img src=\"$alamatfoto\"></div><div class=\"gallery_name\">".$row['nama'] . "</div></a> ";
			// $catcontent .="</li>\n";
			$catcontent .= "<div class=\" col-md-4 col-xs-6 thumb\">\n";
			$catcontent .= "
				<a href=\"".$urlimages."\" $option class=\"thumbnail fancybox\">
					<img alt=\"".$row['nama']."\" src=\"$alamatfoto\" class=\"img-responsive\">
					<div class=\"name-photo text-center\">".$row['nama']."</div>
				</a>
			";		
			// $catcontent .= "</div>";
		}
		
	}
	$catcontent .= ($admin) ? "</li>\n" : "</div>\n";
	}
	$catcontent .= ($admin) ? "</ul>\r\n" : "</div>\n";
	// $sql=$mysql->query("SELECT id,title,filename FROM gallerydata WHERE cat_id=$cat_id");
	
	// if($sql and $mysql->num_rows($sql)>0)
	// {
	
	// while($d=$mysql->fetch_assoc($sql))
	// {			
	// }	
	// }
	// $catcontent .= "</ul>\n";
	
	return $catcontent;
}
function searchimage($admin=false)
{
global $urlfunc,$cfg_app_url,$cfg_file_path,$cfg_file_url,$gallerystyle,$action;
global $cfg_thumb_path, $cfg_thumb_url,$cfg_fullsizepics_url,$photo,$urlfunc ;
global $mysql;
	
	$catcontent .= "<ul class=\"list_gallery\">\n";
	
		
	if($photo and $mysql->num_rows($photo)>0)
	{
	
	while($d=$mysql->fetch_assoc($photo))
	{
		$photo_title=$d['title'];
		if($d['filename']!='')
		{
		$alamatfoto="";
		$cekfoto="$cfg_thumb_path/".$d['filename'];
			if(file_exists($cekfoto))
			{
			$alamatfoto="$cfg_thumb_url/".$d['filename'];
			}
		}
		
		if($alamatfoto=="")
		{
		$alamatfoto="$cfg_app_url/images/none.gif";
		}
			
		
		if($admin)
		{
		$catcontent .= "<li><a href=\"?p=gallery&pid=".$d['id']."&action=edit\"><div class=\"gallery_icon\"><img src=\"$alamatfoto\"></div><div class=\"gallery_name\">".$d['title'] . "</div></a> ";
		$catcontent .= "<div class=\"gallery_editor\"><a href=\"?p=gallery&action=edit&pid=".$d['id']."\"><img alt=\"". _EDIT."\" border=\"0\" src=\"../images/modify.gif\"></a> 
		<a href=\"?p=gallery&action=delete&pid=" . $d['id'] . "\"><img alt=\"" . _DEL . "\" border=\"0\" src=\"../images/delete.gif\"></a>
		</div>";
		$catcontent .="</li>\n";
		}
		else
		{
			$option="";
			switch ($gallerystyle) 
			{
			case 0:
			$urlimages=$urlfunc->makePretty("?p=gallery&action=viewimages&cat_id=$cat_id&pid=".$d['id'],$d['title']);
		
			break;
			case 1:
			$urlimages="$cfg_fullsizepics_url/".$d['filename'];
			$option=" rel=\"sexylightbox[gallery]\" href=\"$cfg_fullsizepics_url/".$d['filename']."\" alt=\"$photo_title\" title=\"$photo_title\" class=\"photos\"  ";
				break;
			}
		$catcontent .= "<li><a href=\"".$urlimages."\" $option><div class=\"gallery_icon\"><img src=\"$alamatfoto\"></div><div class=\"gallery_name\">".$d['title'] . "</div></a> ";
		$catcontent .="</li>\n";
		}
					
	}	
	}
	$catcontent .= "</ul>\n";
	
	return $catcontent;
}

?>
