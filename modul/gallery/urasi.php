<?php

$cfg_fullsizepics_path = "$cfg_img_path/gallery/large";
$cfg_fullsizepics_url = "$cfg_img_url/gallery/large";
$cfg_thumb_path = "$cfg_img_path/gallery/small";
$cfg_thumb_url = "$cfg_img_url/gallery/small";
$cfg_frame_url = "$cfg_img_url/gallery/frame";
$cfg_cat_path = "$cfg_img_path/gallery/cat";
$cfg_cat_url = "$cfg_img_url/gallery/cat";

$cfg_temp_unzip_path = "$cfg_img_path/gallery/zip";

$sort = "id";

$topcatnamecombo = "";		//Name of top level category
$separatorstyle = " / ";	//Categories separator in categories navigation at the top part of the page
$maxfilesize = 1000000;		//Max file size allowed to be uploaded
$autoscrolltime = 3; // dalam detik

$cfg_cat_thumb_width = 100;

$ispickerused = FALSE;		//IS date time picker javascript used? --> hanya untuk field tambahan

$sortcat = "urutan";

$sql = "SELECT judulfrontend FROM module WHERE nama='gallery'";
$result = $mysql->query($sql);
list($topcatnamenav) = $mysql->fetch_row($result);

$sql = "SELECT name, value FROM config WHERE modul='gallery' ";
$result = $mysql->query($sql);
while (list($name, $value) = $mysql->fetch_row($result)) {
	$$name=$value;
}

$customfield[]='keterangan';
$ketcustomfield[]='Keterangan';
$typecustom[]='TEXT';
$paracustom[]='60,20,usetiny';

$searchedcf[]='keterangan';
$cfg_per_page = $cfg_per_page_gallery;	//hanya agar nama variabel sama dengan catalog, karena di db tabel config nama variabel tidak boleh sama

//Cantumkan nama table yang langsung terkait dengan modul ini, penamaan wajib namamodul'_drop'.
$gallery_drop = array('gallerycat', 'gallerydata');
ob_start();
if($admin)
{
//css untuk admin
echo <<<END
<!--================================awal Style Catalog=============================================-->
<link type="text/css" href="$cfg_app_url/modul/gallery/css/admin.css" rel="stylesheet">
<link href="css/bootstrap-editable.css" rel="stylesheet">
<!--================================akhir Style Catalog=============================================-->
END;

}
else
{


//css untuk  front end
echo <<<END
<link type="text/css" href="$cfg_app_url/modul/gallery/css/basic.css" rel="stylesheet">
END;
}
$style_css['gallery']=ob_get_clean();

ob_start();
if($admin) 	
{
//js untuk admin
echo <<<END
<script src="$cfg_app_url/modul/gallery/js/gallery-backend.js"></script>
END;
}
$script_js['gallery']=ob_get_clean();

$cat_id=$_REQUEST['cat_id'];
$tabmenu="
 <div class='tab-widget'>
<ul class='modul_tab_menu nav nav-tabs' >
<li><a href='?p=gallery'>"._HALUTAMA."</a></li>
<li><a href=\"?p=gallery&action=catnew&cat_id=$cat_id\">" ._ADDCAT. "</a></li>
<li><a href=\"?p=gallery&action=add&cat_id=$cat_id\">" . _ADDPHOTO . "</a></li>
</ul>
</div>
 ";
 
 // <li><a href='?p=gallery&action=uploadzip'>"._UPLOADZIP."</a></li>
// <li><a href='?p=gallery&action=bulkedit'>"._BULKEDIT."</a></li>
?>