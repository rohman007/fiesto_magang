<?php
include ("../../../kelola/urasi.php");
include ("../urasi.php");


$contentleft = $cfg_thumb_width+70;
if ($warnajudul=='') $warnajudul = "#999999";
if ($warnaket=='') $warnaket = "#595A5E";
if ($warnaharga=='') $warnaharga = "#FEA002";
if ($warnaharganormal=='') $warnaharganormal = "#CC0000";
if ($warnahargadiskon=='') $warnahargadiskon = "#009900";
if ($warnahemat=='') $warnahemat = "#FF0000";

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
				ContentLeft="'.$contentleft.'" 
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
	case 'featured':
		$sql = "SELECT d.id, d.cat_id, d.filename, d.title, d.publish, d.ishot, d.ketsingkat, 
					d.harganormal, d.diskon, d.matauang, 
					if(LOCATE('%',d.diskon)>0,(TRIM(REPLACE(d.diskon,'%',''))/100*d.harganormal),d.diskon) 
					as nilaidiskon,
					d.harganormal-(if(LOCATE('%',d.diskon)>0,(TRIM(REPLACE(d.diskon,'%',''))/100*d.harganormal),
					d.diskon)) as hargadiskon, c.nama  
				FROM catalogdata d, catalogcat c
				WHERE d.ishot='$isi' AND c.id=d.cat_id ORDER BY $sort ";
		break;
	case 'category':
		$sql = "SELECT d.id, d.cat_id, d.filename, d.title, d.publish, d.ishot, d.ketsingkat, 
					d.harganormal, d.diskon, d.matauang, 
					if(LOCATE('%',d.diskon)>0,(TRIM(REPLACE(d.diskon,'%',''))/100*d.harganormal),d.diskon) 
					as nilaidiskon,
					d.harganormal-(if(LOCATE('%',d.diskon)>0,(TRIM(REPLACE(d.diskon,'%',''))/100*d.harganormal),
					d.diskon)) as hargadiskon, c.nama 
			FROM catalogdata d, catalogcat c WHERE d.cat_id='$isi' AND c.id=d.cat_id ORDER BY $sort ";
		break;
}

//die($sql);
$result = mysql_query($sql);
while (list($photo_id, $photo_cat_id, $photo_filename, $photo_title, $photo_published, $photo_ishot, $keterangansingkat, $harganormal, $diskon, $matauang, $catname) = mysql_fetch_row($result)) {	
	$image_file = $cfg_thumb_url.'/'.$photo_filename;
	$name = $photo_title;
	
	$titleurl = array();
	$titleurl["pid"] = $photo_title;
	$titleurl["cat_id"] = $catname;
				
	$link = $urlfunc->makePretty('?p=catalog&action=viewimages&pid='.$photo_id.'&cat_id='.$photo_cat_id, $titleurl);
	
	
	$xml .= '<Image>
			<ImagePath><![CDATA['.$image_file.']]></ImagePath>
			<ImageLink><![CDATA[]]></ImageLink>
			<ImageLinkTarget><![CDATA[_self]]></ImageLinkTarget>
			<ToolTip><![CDATA['.$name.']]></ToolTip>';
	$xml .= '<Content><![CDATA[<a href="'.$link.'"><font size="20" color="'.$warnajudul.'"><b><u>'.$name.'</u></b></font></a>';
	$xml .= '<br /><font size="16" color="'.$warnaket.'">'.$keterangansingkat.'</font>';
	/*
	 
					if ($harganormal>0) {
						if ($diskon!='') 
						{	
							$txtcell .= "<br /><span class=\"discprice\">$matauang ".number_format($harga_disc,0,',','.')."</span>";
							$txtcell .= "<br /><span class=\"discvalue\">".$label_disc."</span>";
						}
					}
	 */
	$diskon = str_replace(" ","",$diskon);
	if ($harganormal>0) {
		if ($diskon=='') {
			$xml .= '<br /><font size="16" color="'.$warnaharga.'"><b>Harga: '.$matauang.' '.number_format($harganormal,0,',','.')."</b></font>";
		} else {
			if(substr_count($diskon,"%")==1)
			{	$nilai_disc = str_replace("%","",$diskon);
				$label_disc = $nilai_disc." %";
				$nilai_disc = $harganormal * ($nilai_disc/100);
				$harga_disc = $harganormal-$nilai_disc;	
			}
			else
			{	$harga_disc = $harganormal-$diskon;	
				$label_disc = $matauang.number_format($diskon,0,',','.');
			}
			$xml .= '<br /><font size="16" color="'.$warnaharganormal.'"><b>Harga normal: '.$matauang.' '.number_format($harganormal,0,',','.').'</b></font>';
			$xml .= '<br /><font size="16" color="'.$warnahargadiskon.'"><b>Harga diskon: '.$matauang.' '.number_format($harga_disc,0,',','.').'</b></font>';
			$xml .= '<br /><font size="16" color="'.$warnahemat.'"><b>Diskon: '.$label_disc.'</b></font>';
		}
	}
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
