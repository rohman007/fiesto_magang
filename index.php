<?php 

session_start();
header("Cache-control: private");
/*HTTP://URL.COM/SEG[0]/SEG[1]/SEG[2]/SEG[3]/SEG[4]/SEG[5]/ dst -----*/
include ("kelola/urasi.php");

$_SEG			=explode("/",str_replace($subdir,"",$_GET['xparam']));
$_REQUEST['p']		=$_SEG[0];
$_GET['p']		=$_SEG[0];
$_GET['action']	=$_SEG[1];
$_GET['pid']	=$_SEG[2];
$namamodul		=$_SEG[0];
$action			=$_SEG[1];		
$pid			=$_SEG[2];

/*END PRETTY URL*/

#region Lang
// $lang = (!empty($_GET['lang'])) ? $_GET['lang'] : $_SESSION['bahasa'];/* $defaultlang; */
// if($lang!='') {
	// if(in_array($lang,$availangs)) {
		// $_SESSION['bahasa']=$lang;
	// }
// } else {
	// if ($_SESSION['bahasa']!='') {
		// $lang=$_SESSION['bahasa'];
	// } else {
		// $_SESSION['bahasa'] = $availangs[0];
	// }
// }
#endregion Lang

include ("kelola/fungsi.php");

$urlfunc = new PrettyURL();

	if($config_site_isfriendly) 
	{	
		$urlfunc->parseURL();

		if (file_exists("$cfg_app_path/modul/$namamodul/prettyurasi.php")){include("$cfg_app_path/modul/$namamodul/prettyurasi.php");}
		if ($namamodul!='' && file_exists("$cfg_app_path/modul/$namamodul/prettyaksi.php")){include("$cfg_app_path/modul/$namamodul/prettyaksi.php");}
		
	} else {
		$namamodul = $_GET['p'];
		if ($namamodul!='' && file_exists("$cfg_app_path/modul/$namamodul/urasi.php")) include ("$cfg_app_path/modul/$namamodul/urasi.php");
	}

	if ($belimobileversion) {
		$detect = new Mobile_Detect();
		if ($detect->isMobile() && !$detect->isIpad()) {
			$ismobile=true;
		} else {
			//$ismobile=false;
			$ismobile=false;	//jun debug mode matikan untuk live
			$sql = "SELECT basename, extension FROM decoration WHERE basename='mobilelogo'";
			$result = $mysql->query($sql);
			list($basename, $extension) = $mysql->fetch_assoc($result);
			$mobilelogo = '<img src="'.$cfg_file_url.'/file/dekorasi/'.$basename.'.'.$extension.'" border="0" />';
		}
	} else {
		$ismobile=false;
	}

	$sql = "SELECT konstanta, terjemahan FROM translation";
	$result = $mysql->query($sql);
	while (list($konstanta, $terjemahan) = $mysql->fetch_row($result)) {
		define($konstanta,$terjemahan);
	}
	if (file_exists("$cfg_app_path/template/$config_site_templatefolder/fungsi.php")) {
		/*
		jun: 18:38 30/01/2012
		made by pompy for some template
		doesn't removed for backward compatibility
		*/
		include("$cfg_app_path/template/$config_site_templatefolder/fungsi.php");
		$menucat = new categories();
		$display_menu = drawMenu($menucat);
	} else {
		$display_menu = menustructure();
	}
	
	include ("$cfg_app_path/template/$config_site_templatefolder/admin/form_generator/index.php"); 
	
	switch ($namamodul) {
		case '':
			if(file_exists("$cfg_app_path/template/$config_site_templatefolder/custom/home.php")){
				include ("$cfg_app_path/template/$config_site_templatefolder/custom/home.php");
			}else{
				// $display_main_content_block .= showhome('A');	
				header("Location: $cfg_app_url");
				exit;
			}
			
			
			break;
		case 'banner':
			include ("$cfg_app_path/modul/$namamodul/urasi.php");
			include ("$cfg_app_path/modul/$namamodul/fungsi.php");
			include ("$cfg_app_path/modul/$namamodul/aksi.php");
			break;
		default:
			$sql = "SELECT id FROM menutype WHERE modul='$namamodul' AND (modul!='menu' OR jenis='sitemap')";	
			
			$result = $mysql->query($sql);

			if ($mysql->num_rows($result)>0) {
				if (file_exists("$cfg_app_path/modul/$namamodul/urasi.php")) include ("$cfg_app_path/modul/$namamodul/urasi.php");
				if (file_exists("$cfg_app_path/modul/$namamodul/fungsi.php")) include ("$cfg_app_path/modul/$namamodul/fungsi.php");
				if (file_exists("$cfg_app_path/modul/$namamodul/aksi.php")) include ("$cfg_app_path/modul/$namamodul/aksi.php");

				//original code here
				$block  = "<div class=\"content-wrapper\">\r\n";
				$block  .= "<div class=\"container\">\r\n";
				$block .= "\t<div class=\"row\"><div class=\"col-lg-12\"><h1 class=\"page-header\">".$title."</h1>\r\n";
				$block .= "\t</div><div class=\"col-lg-12\">\r\n";
				$block .= $content;
				$block .= "\t</div></div>\r\n";	//end blockcontent
				$block .= "\t<div class=\"blockfooter\"><div class=\"wrapper\"></div></div>\r\n";
				$block .= "</div>\r\n";	//end block
				$block .= "</div>\r\n";	//end block
				// $block .= "<div class=\"blockseparator\"></div>\r\n";
				//end of original code here
				
				
				$display_main_content_block .= $block;
			} else {
				// $display_main_content_block .= showhome('A');
				header("Location: $cfg_app_url");
				exit;
			}
			
			
	}

	$display_upper_sticky_block = showhome('X');
	$display_lower_sticky_block = showhome('Y');
	$display_widget_A = showwidget('A');
	$display_widget_B = showwidget('B');
	$display_footer = stripslashes($config_site_footer);

	switch ($templateversion) {
		case '2':
			$sql = "SELECT basename, extension FROM decoration ORDER BY id";
			$result = $mysql->query($sql);
			$i=0;
			while (list($basename, $extension) = $mysql->fetch_row($result)) {
				$dekorasi[$i] = "<img src=\"$cfg_app_url/file/dekorasi/$basename.$extension\" border=\"0\" />";
				$i++;
			}
			break;
		default:	//backward compatibility
			switch ($config_site_headertype) {
				case 0:
					$flashfileurl = "$cfg_file_url/file/dekorasi/defaultheader.swf";
					$flashfilepath = "$cfg_file_path/file/dekorasi/defaultheader.swf";
					break;
				case 1:
					$flashfileurl = "$cfg_file_url/file/dekorasi/customheader.swf";
					$flashfilepath = "$cfg_file_path/file/dekorasi/customheader.swf";
					break;
			}

			if (file_exists($flashfilepath)) {
				$display_flash_header = '
				<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="'.$flashheaderwidth.'" height="'.$flashheaderheight.'" id="flashheader" align="middle">
				<param name="allowScriptAccess" value="sameDomain" />
				<param name="allowFullScreen" value="false" />
				<param name="movie" value="'.$flashfileurl.'" />
				<param name="quality" value="high" />
				<param name="wmode" value="transparent">
				<embed src="'.$flashfileurl.'" wmode="transparent" quality="high" width="'.$flashheaderwidth.'" height="'.$flashheaderheight.'" name="flashheader" align="middle" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
				</object>';
			} else {
				$sql = "SELECT basename, extension, maxdimension FROM decoration WHERE type='defaultheaderjquery'";
				$result = $mysql->query($sql);
				if ($mysql->num_rows($result)>0) {
					$display_jquery_header .= '<div id="headerslide">';
					while (list($basename, $extension, $maxdimension) = $mysql->fetch_row($result)) {
						$theslides = $basename.".".$extension;
						$imgsize = explode(";",$maxdimension);
						$display_jquery_header .= "<img class=\"headerslideImage\" src=\"$cfg_file_url/file/dekorasi/$theslides\" width=\"$imgsize[1]\" height=\"$imgsize[2]\">";
					}
					$display_jquery_header .= '</div>';
					$jsDocReady = "
						$('#headerslide').fadeSlide({
							slider_interval: 5000,
							fadeOut_duration: 1200,
							fadeIn_duration: 1200
						});";
				}else {
					$sql = "SELECT basename, extension, maxdimension FROM decoration WHERE type='defaultslidejquery'";
					$result = $mysql->query($sql);
					if ($mysql->num_rows($result)>0) {
						$display_jquery_header .= '<div id="headerslide"><ul>';
						while (list($basename, $extension, $maxdimension) = $mysql->fetch_row($result)) {
							$theslides = $basename.".".$extension;
							$imgsize = explode(";",$maxdimension);
							$display_jquery_header .= "<li><img src=\"$cfg_file_url/file/dekorasi/$theslides\" width=\"$imgsize[1]\" height=\"$imgsize[2]\"></li>";
						}
						$display_jquery_header .= '</ul></div>';
						$jsDocReady = "
							$(\"#headerslide\").easySlider({
							auto: true, 
							continuous: true,
							prevText: '',
							nextText: ''
						});";
					}
				}
				$sql = "SELECT basename, extension, maxdimension FROM decoration WHERE type='headerlogo'";
				$result = $mysql->query($sql);
				if ($mysql->num_rows($result)>0) {
					while (list($basename, $extension, $maxdimension) = $mysql->fetch_row($result)) {
						$theslides = $basename.".".$extension;
						$imgsize = explode(";",$maxdimension);
						if (file_exists("$cfg_file_path/file/dekorasi/$theslides")) {
							if ($imgsize[0] == 'max') {
								$display_header .= "<img class=\"defaultheader\" src=\"$cfg_file_url/file/dekorasi/$theslides\" style=\"max-width:$imgsize[1]px; max-height:$imgsize[2]px;\" >";
							}else if ($imgsize[0] == 'fixed') {
								$display_header .= "<img class=\"defaultheader\" src=\"$cfg_file_url/file/dekorasi/$theslides\" width=\"$imgsize[1]\" height=\"$imgsize[2]\">";
							}
						}
					}
				}
				$display_flash_header = '';
			}
			break;
	}

	#region lang
	$urlaktif = trim($_SERVER['REQUEST_URI']);
	$url = bacalinkterjemahan($urlaktif,$lang);
	
	if (in_array($_SESSION['bahasa'], $availangs)) {
		if ($lang == $availangs[0]) {
			$class_active = 'active';
		}
	}
	
	$displaylang = "<div class=\"lang\">";
	foreach($availangs as $idx => $language) {
		// $url_lang 		= "$url$language";
		$url_lang 		= "?lang=$language";
		$title_lang 	= $data_availangs[$idx]["name"];
		$icon_lang 		= ($data_availangs[$idx]["icon"] != "") ? "<img src=\"$cfg_app_url/images/{$data_availangs[$idx]["icon"]}\" alt=\"$title_lang\">" : "";
		
		$displaylang .= "<div>";
		$displaylang .= "<a href=\"$url_lang\" title=\"$title_lang\" class=\"".(($lang == $language) ? "active" : "")."\">";
		$displaylang .= $icon_lang;
		$displaylang .= "<span>";
		$displaylang .= $title_lang;
		$displaylang .= "</span>";
		$displaylang .= "</a>";
		$displaylang .= "</div>";
	}
	$displaylang .= "</div>";
	#endregion lang
	
	logcounter();
	
	$style_css['urasi']="
	<script>
	var cfg_app_url='$cfg_app_url';
	var cfg_template_url='$cfg_app_url/template/$config_site_templatefolder';
	</script>
	";
	
	
	if(strlen($title)>0)$config_site_titletag=$title;
	
	if(count($script_js)>0 and is_array($script_js))
	{
		$script_js=join("\n",$script_js)."\n";
	}
	if(count($style_css)>0 and is_array($style_css))
	{
		$style_css=join("\n",$style_css)."\n";
	}
	
	
	//START RECORD LAST URL
	// if($_REQUEST['p']=='webmember')
	// {
		// if(strlen($_SERVER['QUERY_STRING'])>0)
		// {
		// $_SESSION['URL_BEFORE_LOGIN']=$urlfunc->makePretty("?".$_SERVER['QUERY_STRING']);	
		// }
	// }
	
	//END RECORD LAST URL
	
	/*start testing*/
	/*end testing*/
	
	ob_start();
	
	include ("$cfg_app_path/template/$config_site_templatefolder/html.php");
	
	$temp=ob_get_clean();
	$temp=str_replace("<<<TEMPLATE_URL>>>","$cfg_app_url/template/$config_site_templatefolder",$temp);
	$temp=str_replace("<<<WEB_URL>>>","$cfg_app_url",$temp);
	echo $temp;

?>
