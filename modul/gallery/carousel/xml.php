<?php
include ("../../../kelola/urasi.php");
include ("../urasi.php");

if (file_exists("$cfg_app_path/template/$config_site_templatefolder/urasi.php")) include ("$cfg_app_path/template/$config_site_templatefolder/urasi.php");

header("content-type:text/xml; charset=utf-8");
$xml='<?xml version="1.0" encoding="utf-8" ?>
			<Images
				FlashWidth="'.$cfg_max_width.'" 
				FlashHeight="'.$cfg_carousel_height.'" 
				FlashLeft="0" 
				FlashTop="0" 
				FlashBgColor="'.$carousel_bgcolor.'" 
				FlashBgImage="False" 
				FlashBgImagePath="" 
				FlashBgPosition="top left" 
				FlashBgPositionX="0" 
				FlashBgPositionY="0" 
				FlashBgRepeat="repeat" 
				FlashQuality="high" 
				FlashScale="noscale" 
				FlashMenu="False" 
				PerformanceFPS="24" 
				PerformanceLoadTime="7" 
				CarouselRadiusX="'.$carousel_radius_x.'" 
				CarouselRadiusY="'.$carousel_radius_y.'" 
				CarouselPerspective="30" 
				CarouselCenterLeft="0" 
				CarouselCenterTop="20" 
				CarouselSpeed="3" 
				CarouselSpeedFixed="False" 
				CarouselNavigation="" 
				CarouselInitialRotation="0" 
				CarouselRandomImages="0" 
				ReflectionVisible="True" 
				ReflectionImageAlpha="40" 
				ReflectionReflectionAlpha="40" 
				ReflectionDropOff="60" 
				ReflectionDistance="-20" 
				ToolTipAlign="center" 
				ToolTipBold="TRUE" 
				ToolTipHTMLAntiAliasing="False" 
				ToolTipFontColor="#FEA002" 
				ToolTipFontSize="14" 
				ToolTipHTML="False" 
				ToolTipLeading="0" 
				ToolTipUnderline="False" 
				ToolTipVisible="TRUE" 
				ToolTipDefaultTop="-10" 
				FrameVisible="False" 
				FrameBorderWidth="3" 
				FrameBorderColor="#FF0000" 
				FrameBorderAlpha="50" 
				FrameFillColor1="" 
				FrameFillColor2="" 
				FrameFillAlpha1="50" 
				FrameFillAlpha2="50" 
				FrameFillTransition="100" 
				FrameFillRotation="0" 
				ContentPageEnabled="True" 
				ContentHTML="True" 
				ContentHTMLAntiAliasing="False" 
				ContentAlign="justify" 
				ContentBold="False" 
				ContentFont="Verdana" 
				ContentFontColor="#222222" 
				ContentFontSize="14" 
				ContentLeading="0" 
				ContentLeft="170" 
				ContentTop="50" 
				ContentUnderline="False" 
				ContentWidth="380" 
				Content2HTML="True" 
				Content2HTMLAntiAliasing="False" 
				Content2Align="justify" 
				Content2Bold="TRUE" 
				Content2Font="Verdana" 
				Content2FontColor="#222222" 
				Content2FontSize="14" 
				Content2Leading="0" 
				Content2Left="165" 
				Content2Top="100" 
				Content2Underline="True" 
				Content2Width="380"
				Content2Visible="False"  
				ContentImageLeft="80" 
				ContentImageTop="120"  >
			';		

list($isi,$jenis) = explode('-',$_GET['isi']);
switch ($jenis) {
	case 'category':
		$sql = "SELECT d.id, d.cat_id, d.filename, d.title, d.publish, 
			d.ishot, d.keterangan, c.nama 	 
			FROM gallerydata d, gallerycat c 
			WHERE d.cat_id='$isi' AND d.publish='1' AND c.id=d.cat_id ";
		$sql .= " ORDER BY $sort ";
		break;
}

//die($sql);
$result = mysql_query($sql);
while (list($photo_id, $photo_cat_id, $photo_filename, $photo_title, $photo_published, 
			$photo_ishot, $keterangan, $catname) = mysql_fetch_row($result)) {	
	$image_file = $cfg_thumb_url.'/'.$photo_filename;
	$name = $photo_title;
	
	$titleurl = array();
	$titleurl["pid"] = $photo_title;
	$titleurl["cat_id"] = $catname;
				
	$link = $urlfunc->makePretty('?p=gallery&action=viewimages&pid='.$photo_id.'&cat_id='.$photo_cat_id, $titleurl);
	
	
	$xml .= '<Image>
			<ImagePath><![CDATA['.$image_file.']]></ImagePath>
			<ImageLink><![CDATA[]]></ImageLink>
			<ImageLinkTarget><![CDATA[_self]]></ImageLinkTarget>
			<ToolTip><![CDATA['.$name.']]></ToolTip>';
	$xml .= '<Content><![CDATA[<a href="'.$link.'"><font size="20" color="#0000CC"><b><u>'.$name.'</u></b></font></a>';
	$xml .= ']]></Content>
			<ContentLink><![CDATA[]]></ContentLink>';
			
	$xml .= '<ContentLinkTarget><![CDATA[]]></ContentLinkTarget>';
	$xml .= '<Content2><![CDATA[]]></Content2>';
	$xml .= '<Content2Link><![CDATA['.$link.']]></Content2Link>
			<Content2LinkTarget><![CDATA[]]></Content2LinkTarget>
			</Image>';
}

$xml.='</Images>';
echo $xml;

?>
