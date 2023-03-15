<?php
if ($jenis=='category') 
{	
	if($isi=="0")
	{
	//kalau kategory yang dipilih root => /
	$sql = "SELECT d.id, d.cat_id, d.filename, d.title, d.publish, 
		d.ishot, d.keterangan,'/' nama 	 
		FROM gallerydata d
		WHERE d.cat_id='$isi' AND d.publish='1'  ORDER BY $sort ";
	}
	else
	{
	$sql = "SELECT d.id, d.cat_id, d.filename, d.title, d.publish, 
		d.ishot, d.keterangan, c.nama 	 
		FROM gallerydata d, gallerycat c 
		WHERE d.cat_id='$isi' AND d.publish='1' AND c.id=d.cat_id ORDER BY $sort ";
	}
	
	$result = $mysql->query($sql);
	
	if ($mysql->num_rows($result)>0) 
	{	
		while (list($photo_id, $photo_cat_id, $photo_filename, $photo_title, $photo_published, 
			$photo_ishot, $keterangan, $catname) = $mysql->fetch_row($result)) 
		{	
			$lebar = 100 / $cfg_max_cols;
			
			$titleurl = array();
			$titleurl["pid"] = $photo_title;
			$titleurl["cat_id"] = $catname;
								
			$home .= "
				<div class=\"col-md-4 col-xs-6 thumb\">";
			switch ($gallerystyle) {
				case 1:	//sexyliightbox
					$photo_desc = htmlspecialchars(strip_tags($photo_desc));
					$titletag = ($photo_desc=='') ? $photo_title : "$photo_title: $photo_desc";
					if($photo_filename!='' && file_exists("$cfg_thumb_path/$photo_filename")) {
						$home .= "
							<a data-fancybox-group=\"gallery\" href=\"$cfg_fullsizepics_url/$photo_filename\" class=\"thumbnail fancybox\">";
						$home .= "<img src=\"$cfg_thumb_url/$photo_filename\" class=\"img-responsive\" alt=\"$photo_title\" title=\"$photo_title\">";
					} else {
						$home .= "
							<a rel=\"sexylightbox[gallery]\" href=\"$cfg_app_url/images/none.gif\" class=\"thumbnail\">";
						$home .= "<img src=\"$cfg_app_url/images/none.gif\" class=\"img-responsive\" alt=\"$photo_title\" title=\"$photo_title\">";
					}
					$home .= "<div class=\"name-photo text-center\">$photo_title</div>
						</a>					
					";					
					break;
				default:	//plain
					$home .= "
						<a href=\"".$urlfunc->makePretty("?p=gallery&action=viewimages$action&pid=$photo_id&$actiondependent", $titleurl)."\" class=\"thumbnail\">";
					if($photo_filename!='' && file_exists("$cfg_thumb_path/$photo_filename")) {
						$home .= "<img src=\"$cfg_thumb_url/$photo_filename\" class=\"img-responsive\" alt=\"$photo_title\" title=\"$photo_title\">";
					} else {
						$home .= "<img src=\"$cfg_app_url/images/none.gif\" class=\"img-responsive\" alt=\"$photo_title\" title=\"$photo_title\">";
					}
					$home .= "<div class=\"name-photo text-center\">$photo_title</div>
						</a>					
					";
					break;
			}
			$home .= "
				</div>	<!-- /.col-md-4 col-xs-6 thumb -->\r\n";
		}	
	} 
	else 
	{	$home .= "<p>"._NOPROD."</p>";
	}	
}

if ($jenis=='categorymarquee')
{	
	if($isi=="0")
	{
	//kalau kategory yang dipilih root => /
	$sql = "SELECT d.id, d.cat_id, d.filename, d.title, d.publish, 
		d.ishot, d.keterangan,'/' nama 	 
		FROM gallerydata d
		WHERE d.cat_id='$isi' AND d.publish='1'  ORDER BY $sort ";
	}
	else
	{
	$sql = "SELECT d.id, d.cat_id, d.filename, d.title, d.publish, 
		d.ishot, d.keterangan, c.nama 	 
		FROM gallerydata d, gallerycat c 
		WHERE d.cat_id='$isi' AND d.publish='1' AND c.id=d.cat_id ";
	$sql .= " ORDER BY $sort ";
	}
	
	$result = $mysql->query($sql);
    if ($mysql->num_rows($result) > 0) {
		if ($jcarouselcount == 0) {
            $jcarouselcount++;
        }
		
        $tempjs .= "	
		<script type=\"text/javascript\">
			$(function() {
				$('.carousel-gallery').owlCarousel({
				 
				autoPlay: 3000, //Set AutoPlay to 3 seconds
				 
				items : 4,
				itemsDesktop : [1199,3],
				itemsDesktopSmall : [979,3],
				navigation : true
				 
				});
			});
		</script>
		";
		$script_js['home']=$tempccs.$tempjs;
		
		$home .= "<div class=\"col-lg-12\">";
		$home .= "<div id=\"owl-carousel\" class=\"owl-carousel carousel-gallery\">";
		while (list($photo_id, $photo_cat_id, $photo_filename, $photo_title, $photo_published, 
			$photo_ishot, $keterangan, $catname) = $mysql->fetch_row($result)) 
		{
			$titleurl = array();
			$titleurl["pid"] = $photo_title;
			$titleurl["cat_id"] = $catname;
			// $home .= "<div class=\"item\"><a class=\"thumbnail\" href=\"".$urlfunc->makePretty("?p=gallery&action=viewimages&pid=$photo_id&cat_id=$photo_cat_id", $titleurl)."\">";
			// if($photo_filename!='')
				// $home .= "<img src=\"$cfg_thumb_url/$photo_filename\" class=\"img-responsive\" alt=\"$photo_title\">";
			// else
				// $home .= "<img src=\"$cfg_app_url/images/none.gif\" class=\"img-responsive\">";
			// $home .= "<div class=\"photo_title\">$photo_title</div>\r\n";
			// $home .= "</a>\r\n";
			// $home .= "</div>";	
			switch ($gallerystyle) {
				case 1:	//sexyliightbox
					$home .= "<div class=\"item\"><div class=\"thumb\"><a class=\"thumbnail fancybox\" data-fancybox-group=\"gallery\" href=\"$cfg_fullsizepics_url/$photo_filename\">";
					if($photo_filename!='')
						$home .= "<img src=\"$cfg_thumb_url/$photo_filename\" alt=\"$photo_title\" class=\"img-responsive\">";
					else
						$home .= "<img src=\"$cfg_app_url/images/none.gif\" class=\"img-responsive\">";
					$home .= "<span class=\"photo_title\">$photo_title</span>\r\n";
					$home .= "</a>\r\n";
					$home .= "</div></div>";
					break;
				default:
					$home .= "<div class=\"item\"><div class=\"thumb\"><a class=\"thumbnail\" href=\"".$urlfunc->makePretty("?p=gallery&action=viewimages&pid=$photo_id&cat_id=$photo_cat_id", $titleurl)."\">";
					if($photo_filename!='')
						$home .= "<img src=\"$cfg_thumb_url/$photo_filename\" alt=\"$photo_title\" class=\"img-responsive\">";
					else
						$home .= "<img src=\"$cfg_app_url/images/none.gif\" class=\"img-responsive\">";
					$home .= "<span class=\"photo_title\">$photo_title</span>\r\n";
					$home .= "</a>\r\n";
					$home .= "</div></div>";
				break;
			}			
		}
		$home .= "</div></div>";
	} else {
		$home .= "<p>"._NOPROD."</p>";
	}
}

if ($jenis=='marqueehot') 
{	
	if($isi=="0")
	{
		//kalau kategory yang dipilih root => /
		$sql = "SELECT d.id, d.cat_id, d.filename, d.title, d.publish, 
			d.ishot, d.keterangan,'/' nama 	 
			FROM gallerydata d
			WHERE d.publish='1' AND d.ishot='1' ORDER BY $sort ";
	}
	else
	{
		$sql = "SELECT d.id, d.cat_id, d.filename, d.title, d.publish, 
		d.ishot, d.keterangan  
		FROM gallerydata d
		WHERE d.publish='1' AND d.ishot='1'";
	}
	
	$result = $mysql->query($sql);
	if ($mysql->num_rows($result)>0) {
		$tempjs .= "
		<script type=\"text/javascript\">
			$(function() {
				$('.carousel-product').owlCarousel({
				 
				autoPlay: 3000, //Set AutoPlay to 3 seconds
				 
				items : 4,
				itemsDesktop : [1199,4],
				itemsDesktopSmall : [979,4],
				pagination:false,
				navigation : true
				 
				});
			});
		</script>";
		$script_js['home']=$tempccs.$tempjs;
		$home .= "<div class=\"col-lg-12\">";
		$home .= "<div id=\"owl-carousel\" class=\"owl-carousel carousel-product\">";
		while (list($photo_id, $photo_cat_id, $photo_filename, $photo_title, $photo_title_en, $photo_published, 
			$photo_ishot, $catname) = $mysql->fetch_row($result)) 
		{
			if ($lang == $availangs[1]) {
				if ($photo_title_en != '') {
					$photo_title = $photo_title_en;
				} else {
					$photo_title = $photo_title;
				}
			} else {
				$photo_title = $photo_title;
			}
			$titleurl = array();
			$titleurl["pid"] = $photo_title;
			$titleurl["cat_id"] = $catname;
				
			switch ($gallerystyle) {
				case 1:	//sexyliightbox
					$home .= "<div class=\"item\"><div class=\"thumb\"><a class=\"thumbnail fancybox\" data-fancybox-group=\"gallery\" href=\"$cfg_fullsizepics_url/$photo_filename\">";
					if($photo_filename!='')
						$home .= "<img src=\"$cfg_thumb_url/$photo_filename\" alt=\"$photo_title\" class=\"img-responsive\">";
					else
						$home .= "<img src=\"$cfg_app_url/images/none.gif\" class=\"img-responsive\">";
					$home .= "<span class=\"photo_title\">$photo_title</span>\r\n";
					$home .= "</a>\r\n";
					$home .= "</div></div>";					
					break;
				default:
					$home .= "<div class=\"item\"><div class=\"thumb\"><a class=\"thumbnail\" href=\"".$urlfunc->makePretty("?p=gallery&action=viewimages&pid=$photo_id&cat_id=$photo_cat_id", $titleurl)."\">";
					if($photo_filename!='')
						$home .= "<img src=\"$cfg_thumb_url/$photo_filename\" alt=\"$photo_title\" class=\"img-responsive\">";
					else
						$home .= "<img src=\"$cfg_app_url/images/none.gif\" class=\"img-responsive\">";
					$home .= "<span class=\"photo_title\">$photo_title</span>\r\n";
					$home .= "</a>\r\n";
					$home .= "</div></div>";
				break;
			}	
		}
		$home .= "</div></div>";
	} else {
		$home .= "<div class=\"col-lg-12\"><p>"._NOPROD."</p></div>";
	}
}

if ($jenis=='categorycarousel') {
	$flashfile = "modul/gallery/carousel/3DCarouselDev.swf";
	$xmlfile = "modul/gallery/carousel/xml.php?isi=$isi-".substr($jenis,0,8);
	
	$home .= '
	<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="'.$cfg_max_width.'" height="'.$cfg_carousel_height.'" id="carousel" align="middle">
	<param name="allowScriptAccess" value="sameDomain" />
	<param name="allowFullScreen" value="false" />
	<param name="movie" value="'.$flashfile.'" />
	<param name="quality" value="high" />
	<param name="wmode" value="opaque" />
	<param name="bgcolor" value="'.$carousel_bgcolor.'" />
	<param name="flashvars" value="xmlprovider='.$xmlfile.'" />
	<embed src="'.$flashfile.'" bgcolor="'.$carousel_bgcolor.'" flashvars="xmlprovider='.$xmlfile.'" wmode="opaque" quality="high" width="'.$cfg_max_width.'" height="'.$cfg_carousel_height.'" name="carousel" align="middle" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
	</object>';
}

?>