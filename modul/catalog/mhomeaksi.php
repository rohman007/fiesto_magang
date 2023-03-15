<?php
if ($jenis=='featured' || $jenis=='category') {
	switch ($jenis) {
		case 'featured':
			$sql = "SELECT d.id, d.cat_id, d.filename, d.title, d.publish, d.ishot, d.ketsingkat, 
					d.harganormal, d.diskon, d.matauang, 
					if(LOCATE('%',d.diskon)>0,(TRIM(REPLACE(d.diskon,'%',''))/100*d.harganormal),d.diskon) 
					as nilaidiskon,
					d.harganormal-(if(LOCATE('%',d.diskon)>0,(TRIM(REPLACE(d.diskon,'%',''))/100*d.harganormal),
						d.diskon)) as hargadiskon,
					c.nama  
					FROM catalogdata d, catalogcat c WHERE d.ishot='$isi' AND d.publish='1' AND c.id=d.cat_id ";
			break;
		case 'category':
			$sql = "SELECT d.id, d.cat_id, d.filename, d.title, d.publish, d.ishot, d.ketsingkat, 
					d.harganormal, d.diskon, d.matauang, 
					if(LOCATE('%',d.diskon)>0,(TRIM(REPLACE(d.diskon,'%',''))/100*d.harganormal),d.diskon) 
					as nilaidiskon,
					d.harganormal-(if(LOCATE('%',d.diskon)>0,(TRIM(REPLACE(d.diskon,'%',''))/100*d.harganormal),
						d.diskon)) as hargadiskon,
					c.nama 					 
					FROM catalogdata d, catalogcat c WHERE d.cat_id='$isi' AND d.publish='1' AND c.id=d.cat_id ";
			break;
	}
	$sql .= " ORDER BY $sort ";
	
	$result = $mysql->query($sql);
	
	if ($mysql->num_rows($result)>0) {
		//thumbnail only
		if ($showtype==0) {
			$x = 1;
			while (list($photo_id, $photo_cat_id, $photo_filename, $photo_title, $photo_published, $photo_ishot, $keterangansingkat, $harganormal, $diskon, $matauang, $nilai_disc, $harga_disc, $catname) = $mysql->fetch_row($result)) {
				$titleurl = array();
				$titleurl["pid"] = $photo_title;
				$titleurl["cat_id"] = $catname;
				$home .= '<p>';			
				$home .= '<a href="'.$urlfunc->makePretty('?p=catalog&action=viewimages&pid='.$photo_id.'&cat_id='.$photo_cat_id, $titleurl).'"><img src="'.$cfg_thumb_url.'/'.$photo_filename.'" border="0" alt="'.$photo_title.'" class="photos"></a><br />';
				$home .= '<span class="photo_title">'.$photo_title.'</span><br />';
					
					$diskon = str_replace(" ","",$diskon);
					if ($harganormal>0) {
						if ($diskon=='') {
							$kelasnormal = 'normalwodisc';
						} else {
							$kelasnormal = 'normalwdisc';
						}
						$home .= "<span class=\"$kelasnormal\">$matauang ".number_format($harganormal,0,',','.')."</span><br />";
						if ($diskon!=''){	
							if(substr_count($diskon,"%")==1){	
								$label_disc = str_replace("%","",$diskon)." %";
							}else{	
								$label_disc = $matauang.' '.number_format($diskon,0,',','.');
							}
							$home .= "<span class=\"discprice\">$matauang ".number_format($harga_disc,0,',','.')."</span><br />";
							$home .= "<span class=\"discvalue\">("._YOUSAVE." ".$label_disc.")</span>";
						}
					}
				$home .= '</p>';
			}
		}
		
		//thumbnail plus summary
		if ($showtype==1) {
			$home .= "<div class=\"productsummary\">";
			while (list($photo_id, $photo_cat_id, $photo_filename, $photo_title, $photo_published, $photo_ishot, $keterangansingkat, $harganormal, $diskon, $matauang, $nilai_disc, $harga_disc, $catname) = $mysql->fetch_row($result)) {
				$titleurl = array();
				$titleurl["pid"] = $photo_title;
				$titleurl["cat_id"] = $catname;
				
				$home .= "<div class=\"pstitle\"><h1><a href=\"".$urlfunc->makePretty("?p=catalog&action=viewimages&pid=$photo_id&cat_id=$photo_cat_id", $titleurl)."\">$photo_title</a></h1></div>\r\n";
				$home .= "<div class=\"psphoto\"><a href=\"".$urlfunc->makePretty("?p=catalog&action=viewimages&pid=$photo_id&cat_id=$photo_cat_id", $titleurl)."\"><img src=\"$cfg_thumb_url/$photo_filename\" border=\"0\" alt=\"$photo_title\" title=\"$photo_title\" class=\"photos\"></a></div>\r\n";
				$home .= "<div class=\"pssummary\">$keterangansingkat</div>\r\n";
				
				$diskon = str_replace(" ","",$diskon);
				if ($harganormal>0) {
					if ($diskon=='') {
						$kelasnormal = 'normalwodisc';
					} else {
						$kelasnormal = 'normalwdisc';
					}
					$home .= "<div class=\"harga\">"._PRICE.": <span class=\"$kelasnormal\">$matauang ".number_format($harganormal,0,',','.')."</span>";
					if ($diskon!='') 
					{	if(substr_count($diskon,"%")==1)
						{	$label_disc = str_replace("%","",$diskon)."%";								
						}
						else
						{	$label_disc = $matauang.' '.number_format($diskon,0,',','.');
						}
						$home .= " <span class=\"discprice\">$matauang ".number_format($harga_disc,0,',','.')."</span>";
						$home .= " <span class=\"discvalue\">("._YOUSAVE." ".$label_disc.")</span>";
					}
					$home .= "</div>\r\n";
				}
				$home .= "<div class=\"psseparator\"></div>\r\n";
			}
			$home .= "</div>\r\n";
		}
	} else {
		$home .= "<p>"._NOPROD."</p>";
	}	
}

if ($jenis=='featuredcarousel' || $jenis=='categorycarousel') {
	$flashfile = "modul/catalog/carousel/3DCarouselDev.swf";
	$xmlfile = "modul/catalog/carousel/xml.php?isi=$isi-".substr($jenis,0,8);
	
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

if ($jenis=='featuredmarquee' || $jenis=='categorymarquee') {
	switch ($jenis) {
		case 'featuredmarquee':
			$sql = "SELECT id, cat_id, filename, title, publish, ishot, ketsingkat, 
					harganormal, diskon, matauang, 
					if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal),diskon) 
					as nilaidiskon,
					harganormal-(if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal),
						diskon)) as hargadiskon 
					FROM catalogdata WHERE ishot='$isi'";
			break;
		case 'categorymarquee':
			$sql = "SELECT id, cat_id, filename, title, publish, ishot, ketsingkat, 
					harganormal, diskon, matauang, 
					if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal),diskon) 
					as nilaidiskon,
					harganormal-(if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal),
						diskon)) as hargadiskon 
					FROM catalogdata WHERE cat_id='$isi'";
			break;
	}
	$sql .= " ORDER BY $sort ";
	
	$result = $mysql->query($sql);
	if ($mysql->num_rows($result)>0) {
		if ($jcarouselcount==0) {
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
			</script>
			";
			$jcarouselcount++;
		}
		$home .= "	
		<script type=\"text/javascript\">
			jQuery(document).ready(function() {
				jQuery('#mycategorycarousel$i').jcarousel
					({ 	auto: $autoscrolltime,
						visible: $jumlahtampilanmarquee,
						wrap: 'last',
						initCallback: mycarousel_initCallback
					});
			});
		</script>
		";
		
		$home .= "<ul id=\"mycategorycarousel$i\" class=\"jcarousel-skin\">";
		while (list($photo_id, $photo_cat_id, $photo_filename, $photo_title, $photo_published, $photo_ishot, $keterangansingkat, $harganormal, $diskon, $matauang, $nilai_disc, $harga_disc) = $mysql->fetch_row($result)) {
			$home .= "<li><a href=\"".$urlfunc->makePretty("?p=catalog&action=viewimages&pid=$photo_id&cat_id=$photo_cat_id")."\"><img src=\"$cfg_thumb_url/$photo_filename\" border=\"0\" alt=\"$photo_title\" class=\"photos\"></a>\r\n";
			$home .= "<br /><span class=\"photo_title\">$photo_title</span>\r\n";
				
			$diskon = str_replace(" ","",$diskon);
			if ($harganormal>0) {
				if ($diskon=='') {
					$kelasnormal = 'normalwodisc';
				} else {
					$kelasnormal = 'normalwdisc';
				}
				$home .= "<br /><span class=\"$kelasnormal\">$matauang ".number_format($harganormal,0,',','.')."</span>";
				if ($diskon!='') 
				{	if(substr_count($diskon,"%")==1)
					{	$label_disc = str_replace("%","",$diskon)."%";												
					}
					else
					{	$label_disc = $matauang.' '.number_format($diskon,0,',','.');
					}
					$home .= "<br /><span class=\"discprice\">$matauang ".number_format($harga_disc,0,',','.')."</span>";
					$home .= "<br /><span class=\"discvalue\">("._YOUSAVE." ".$label_disc.")</span>";
				}
				$home .= "</li>";
			}
		}
		$home .= "</ul>";
	} else {
		$home .= "<p>"._NOPROD."</p>";
	}
}

?>
