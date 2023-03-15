<?php
$sort = "title";
// $nilailebar = $mainblockwidth;
$nilailebar = $cfg_max_width;

if ($jenis=='category') {
	$sql = "SELECT id FROM bannercat WHERE id='$isi'";
	$result = $mysql->query($sql);	
	if ($mysql->num_rows($result)>0) {
		$sql = "SELECT id, filename, title, url FROM bannerdata WHERE cat_id='$isi' AND publish='1' ORDER BY $sort";
		$result = $mysql->query($sql);
		if ($mysql->num_rows($result) > 0) {
			//modif buat slider
			$home .= "<div class=\"col-xs-12\">\r\n";
			$home .= "<div id=\"banner-slide\" class=\"owl-carousel owl-theme\">";
			//
			while (list($photo_id, $photo_filename, $photo_title, $photo_url) = $mysql->fetch_row($result)) {
				$lebar = "";
				$file_img = $cfg_fullsizepics_path."/".$photo_filename;
				if(file_exists($file_img))
				{	list($widthimg, $heightimg, $type, $attr) = getimagesize($file_img);
					if($widthimg > $nilailebar)
					{	$lebar = "width=\"$nilailebar\" ";
					}
				}
				
				$titleurl= array();
				$titleurl["pid"] = $photo_title;
				
				if ($photo_url=='') {
					$home .= "<div class=\"item\"><img src=\"$cfg_fullsizepics_url/$photo_filename\" border=\"0\" alt=\"$photo_title\" title=\"$photo_title\" $lebar ></div>\r\n";
				} else {
					$home .= "<div class=\"item\"><a href=\"".$urlfunc->makePretty("?p=banner&action=go&pid=$photo_id", $titleurl)."\"><img src=\"$cfg_fullsizepics_url/$photo_filename\" alt=\"$photo_title\" title=\"$photo_title\" $lebar ></a></div>\r\n";
				}
			}
			//modif slider
			$home .= "</div>";
			$home .= "</div>";
			//
		} else {
			$home .= _NOPROD;
		}
	} else {
		$home .= _NOCAT;
	}
}

if ($jenis=='random') {
	$cfg_max_cols = $cfg_max_cols_widget;
	$sql = "SELECT id FROM bannercat WHERE id='$isi'";
	$result = $mysql->query($sql);
	
	if ($mysql->num_rows($result)>0) {
		$sql = "SELECT id, filename, title, url FROM bannerdata WHERE cat_id='$isi' AND publish='1' ORDER BY RAND() LIMIT 1";
		$result = $mysql->query($sql);
		if ($mysql->num_rows($result) > 0) {
			while (list($photo_id, $photo_filename, $photo_title, $photo_url) = $mysql->fetch_row($result)) {
				$lebar = "";
				$file_img = $cfg_fullsizepics_path."/".$photo_filename;
				if(file_exists($file_img))
				{	list($widthimg, $heightimg, $type, $attr) = getimagesize($file_img);
					if($widthimg > $nilailebar)
					{	$lebar = "width=\"$nilailebar\" ";
					}
				}
				$titleurl= array();
				$titleurl["pid"] = $photo_title;

				if ($photo_url=='') {
					$home .= "<div class=\"bannerblock\"><img src=\"$cfg_fullsizepics_url/$photo_filename\" border=\"0\" alt=\"$photo_title\" title=\"$photo_title\" $lebar ></div>\r\n";
				} else {
					$home .= "<div class=\"bannerblock\"><a href=\"".$urlfunc->makePretty("?p=banner&action=go&pid=$photo_id", $titleurl)."\"><img src=\"$cfg_fullsizepics_url/$photo_filename\" border=\"0\" alt=\"$photo_title\" title=\"$photo_title\" $lebar ></a></div>\r\n";
				}
			}
		} else {
			$home .= _NOPROD;
		}
	} else {
		$home .= _NOCAT;
	}
}

if ($jenis=='single') {
	$sql = "SELECT id, filename, title, url FROM bannerdata WHERE id='$isi'";
	$result = $mysql->query($sql);
	if ($mysql->num_rows($result) > 0) {
			while (list($photo_id, $photo_filename, $photo_title, $photo_url) = $mysql->fetch_row($result)) {
			$lebar = "";
			$file_img = $cfg_fullsizepics_path."/".$photo_filename;
			if(file_exists($file_img))
			{	list($widthimg, $heightimg, $type, $attr) = getimagesize($file_img);
				if($widthimg > $nilailebar)
				{	$lebar = "width=\"$nilailebar\" ";
				}
			}
			$titleurl= array();
			$titleurl["pid"] = $photo_title;
			
			if ($photo_url=='') {
				$home .= "<div class=\"bannerblock\"><img src=\"$cfg_fullsizepics_url/$photo_filename\" border=\"0\" alt=\"$photo_title\" title=\"$photo_title\" $lebar ></div>\r\n";
			} else {
				$home .= "<div class=\"bannerblock\"><a href=\"".$urlfunc->makePretty("?p=banner&action=go&pid=$photo_id", $titleurl)."\"><img src=\"$cfg_fullsizepics_url/$photo_filename\" border=\"0\" alt=\"$photo_title\" title=\"$photo_title\" $lebar ></a></div>\r\n";
			}
			
		}
	} else {
		$home .= _NOPROD;
	}
}

if ($jenis=='featuredmarquee')
{	
	$sql = "SELECT id, filename, title, url FROM bannerdata WHERE cat_id='$isi'";
	$result = $mysql->query($sql);
    if ($mysql->num_rows($result) > 0) {
		if ($jcarouselcount == 0) {
            $jcarouselcount++;
        }

		$q = $mysql->query("SELECT name, value FROM config WHERE modul='gallery' AND name='gallerystyle'");
		list($name, $value) = $mysql->fetch_row($q);
		$$name = $value;
		
        $tempjs .= "	
		<script type=\"text/javascript\">
			$(function() {
				$('.carousel-banner').owlCarousel({
				 
				autoPlay: 3000, //Set AutoPlay to 3 seconds
				 
				items : 4,
				itemsDesktop : [1199,3],
				itemsDesktopSmall : [979,3],
				navigation : true
				 
				});
			});
		</script>
		";
		
		if ($gallerystyle) {
			$tempjs .= "	
			<script type=\"text/javascript\">
				$(function() {
					$('.image-popup').magnificPopup({
						type: 'image',
						closeOnContentClick: true,
						closeBtnInside: true,
						fixedContentPos: true,
						mainClass: 'mfp-no-margins mfp-with-zoom', // class to remove default margin from left and right side
						gallery: {
							enabled: true
						}
					});
				});
			</script>
			";		
		}
		$script_js['home']=$tempccs.$tempjs;
		
		$home .= "<div class=\"col-lg-12\">";
		$home .= "<div id=\"owl-carousel\" class=\"owl-carousel carousel-banner\">";
		while (list($photo_id, $photo_filename, $photo_title, $photo_url) = $mysql->fetch_row($result)) 
		{
			$titleurl = array();
			$titleurl["pid"] = $photo_title;
			$titleurl["cat_id"] = $catname;
			switch ($gallerystyle) {
				case 1:	//popup
					$home .= "<div class=\"item\"><div class=\"thumb\"><a class=\"thumbnail image-popup\" href=\"$cfg_fullsizepics_url/$photo_filename\" title=\"$photo_title\">";
					if($photo_filename!='')
						$home .= "<img src=\"$cfg_thumb_url/$photo_filename\" alt=\"$photo_title\" class=\"img-responsive\">";
					else
						$home .= "<img src=\"$cfg_app_url/images/none.gif\" class=\"img-responsive\">";
					$home .= "<span class=\"photo_title\">$photo_title</span>\r\n";
					$home .= "</a>\r\n";
					$home .= "</div></div>";
					break;
				default:
					$home .= "<div class=\"item\"><div class=\"thumb\">";
					if($photo_filename!='')
						$home .= "<img src=\"$cfg_thumb_url/$photo_filename\" alt=\"$photo_title\" class=\"img-responsive\">";
					else
						$home .= "<img src=\"$cfg_app_url/images/none.gif\" class=\"img-responsive\">";
					$home .= "<span class=\"photo_title\">$photo_title</span>\r\n";
					$home .= "</div></div>";
				break;
			}			
		}
		$home .= "</div></div>";
	} else {
		$home .= "<div class=\"col-lg-12\"><p>"._NOPROD."</p></div>";
	}
}

?>
