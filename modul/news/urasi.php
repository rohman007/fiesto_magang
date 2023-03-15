<?php

//$isdateshown = FALSE;
//$issourceshown = FALSE;
//$issummary = FALSE;
//Dipindah ke database, set dari menu kelola: Umum
$maxfilesize = 2000000;	
$cfg_max_width = 710;
$cfg_fullsizepics_path = "$cfg_img_path/news/large";
$cfg_fullsizepics_url = "$cfg_img_url/news/large";
$cfg_thumb_path = "$cfg_img_path/news/small";
$cfg_thumb_url = "$cfg_img_url/news/small";
$topcatnamecombo = "";		//Name of top level category
$maxsendnews = 10;
//$max_news_total = 2;		//Jika terlalu banyak, berita yang lama bisa di-del semua sampai tersisa sebatas ini
$maxrss = 20;
$thisfile='//'.param_url();

$sql = "SELECT name, value FROM config WHERE modul='news'";
$result = $mysql->query($sql);
while (list($name, $value) = $mysql->fetch_row($result)) {
	$$name=$value;
}

$searchedcf=array("judulberita","summary","isiberita","tglberita");
ob_start();

if ($admin) {
	echo <<<END
	<!--================================awal Style News=============================================-->
	<link type="text/css" href="$cfg_app_url/modul/news/css/admin.css" rel="stylesheet">
	<link href="css/bootstrap-editable.css" rel="stylesheet">
	<!--================================akhir Style News=============================================-->
END;
}

$style_css['news']=ob_get_clean();

ob_start();

if ($admin) {
	echo <<<END
	<script src="$cfg_app_url/js/jquery.mjs.nestedSortable.js"></script>
	<script src="$cfg_app_url/modul/news/js/news-backend.js"></script>
	<script src="js/bootstrap-editable.js"></script>
END;
} else {
	include 'ajaxInfinitiveScroll.php';
}
$script_js['news']=ob_get_clean();
$tabmenu="
<div class='tab-widget'>
<ul class='modul_tab_menu nav nav-tabs' >
<li><a href='?p=news'>"._HALUTAMA."</a></li>
<li><a href='?p=news&action=main'>"._CATEGORYDASHBOARD."</a></li>
<li><a href='?p=news&action=clean'>"._DELOLDNEWS."</a></li>
</ul>
";
?>
