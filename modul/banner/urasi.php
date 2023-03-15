<?php
$cfg_fullsizepics_path = "$cfg_img_path/banner/large";
$cfg_fullsizepics_url = "$cfg_img_url/banner/large";
$cfg_thumb_path = "$cfg_img_path/banner/small";
$cfg_thumb_url = "$cfg_img_url/banner/small";
$maxfilesize = 500000;		//Max file size allowed to be uploaded
//if(!isset($cfg_max_width)) $cfg_max_width = 600;		//pixels
//if(!isset($cfg_thumb_width)) $cfg_thumb_width = 100;		//pixels

$cfg_max_width_client = 150;
$cfg_max_width_utama = 250;
$cfg_max_height_slider = 82;

$cfg_max_width = 1030;

$cfg_max_cols_page = 3;		//Max number of products per column
$cfg_max_cols_widget = 2;		//Max number of products per column

//Cantumkan nama table yang langsung terkait dengan modul ini, penamaan wajib namamodul'_drop'.
$banner_drop = array('bannercat', 'bannerdata');

ob_start();
echo <<<END
<script>
$(function() {
	$(document).ready(function() {
		$('#banner-slide').owlCarousel({
			navigation : false, // Show next and prev buttons
			slideSpeed : 300,
			autoPlay : true,
			paginationSpeed : 400,
			pagination : false,
			singleItem:true
		});
	});
});
</script>
END;
$script_js['banner'] = ob_get_clean();
?>