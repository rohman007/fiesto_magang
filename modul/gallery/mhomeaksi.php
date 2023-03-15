<?php

if ($jenis=='category')
{	
	$sql = "SELECT d.id, d.cat_id, d.filename, d.title, d.publish, 
		d.ishot, d.keterangan, c.nama 	 
		FROM gallerydata d, gallerycat c 
		WHERE d.cat_id='$isi' AND d.publish='1' AND c.id=d.cat_id ";
	$sql .= " ORDER BY $sort ";
	
	$result = $mysql->query($sql);
	
	if ($mysql->num_rows($result)>0) 
	{	
		while (list($photo_id, $photo_cat_id, $photo_filename, $photo_title, $photo_published, 
			$photo_ishot, $keterangan, $catname) = $mysql->fetch_row($result)){	
			$titleurl = array();
			$titleurl["pid"] = $photo_title;
			$titleurl["cat_id"] = $catname;
			switch ($gallerystyle) {
				default:	//plain
					$home .= "<p>";
					$home .= "<a href=\"".$urlfunc->makePretty("?p=gallery&action=viewimages&pid=$photo_id&cat_id=$photo_cat_id", $titleurl)."\">";
					if($photo_filename!='')
						$home .= "<img src=\"$cfg_thumb_url/$photo_filename\" border=\"0\" alt=\"$photo_title\" title=\"$photo_title\" class=\"photos\" />";
					else
						$home .= "<img src=\"$cfg_app_url/images/none.gif\" border=\"0\" class=\"photos\" />";
					$home .= "</a><br />";
					$home .= "<span class=\"photo_title\">$photo_title</span>\r\n";
					$home .= "</p>";
					break;
			}
		}
	} else {	
		$home .= "<p>"._NOPROD."</p>";
	}	
}

if ($jenis=='categorymarquee') 
{	$sql = "SELECT d.id, d.cat_id, d.filename, d.title, d.publish, 
		d.ishot, d.keterangan, c.nama 	 
		FROM gallerydata d, gallerycat c 
		WHERE d.cat_id='$isi' AND d.publish='1' AND c.id=d.cat_id ";
	$sql .= " ORDER BY $sort ";
	
		
	$result = $mysql->query($sql);
	if ($mysql->num_rows($result)>0) {
		$home .= "<script type=\"text/javascript\" src=\"$cfg_app_url/js/jcarousel/jquery.jcarousel.pack.js\"></script>\r\n";
		$home .= "<link href=\"$cfg_app_url/js/jcarousel/jquery.jcarousel.css\" rel=\"stylesheet\" type=\"text/css\" />\r\n";
		$home .= "<link href=\"$cfg_app_url/template/$config_site_templatefolder/css/jcarousel/skin.css\" rel=\"stylesheet\" type=\"text/css\" />\r\n";
		$home .= "	
		<script type=\"text/javascript\">
			function mycarousel_initCallback(carousel) {
				// Disable autoscrolling if the user clicks the prev or next button.
				carousel.buttonNext.bind('click', function() 
				{	carousel.startAuto(0);	});

				carousel.buttonPrev.bind('click', function() 
				{	carousel.startAuto(0);	});

					// Pause autoscrolling if the user moves with the cursor over the clip.
					carousel.clip.hover
					(	function() 
						{	carousel.stopAuto();	}, 
						function() 
						{	carousel.startAuto();	}
					);
			};

			jQuery(document).ready(function() {
				jQuery('#mycategorycarousel$homeblockid').jcarousel
					({ 	auto: $autoscrolltime,
						visible: $jumlahtampilanmarquee,
						wrap: 'last',
						initCallback: mycarousel_initCallback
					});
			});
		</script>";
		
		$home .= "<ul id=\"mycategorycarousel$homeblockid\" class=\"jcarousel-skin\" width=\"$cfg_max_width\">";
		while (list($photo_id, $photo_cat_id, $photo_filename, $photo_title, $photo_published, 
			$photo_ishot, $keterangan, $catname) = $mysql->fetch_row($result)) 
		{
			$titleurl = array();
			$titleurl["pid"] = $photo_title;
			$titleurl["cat_id"] = $catname;
				
			$home .= "<li><a href=\"".$urlfunc->makePretty("?p=gallery&action=viewimages&pid=$photo_id&cat_id=$photo_cat_id", $titleurl)."\">";
			if($photo_filename!='')
				$home .= "<img src=\"$cfg_thumb_url/$photo_filename\" border=\"0\" alt=\"$photo_title\" class=\"photos\" />";
			else
				$home .= "<img src=\"$cfg_app_url/images/none.gif\" border=\"0\" class=\"photos\" />";
			$home .= "</a>\r\n";
			$home .= "<br /><span class=\"photo_title\">$photo_title</span>\r\n";
			$home .= "</li>";	
		}
		$home .= "</ul>";
	} else {
		$home .= "<p>"._NOPROD."</p>";
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
