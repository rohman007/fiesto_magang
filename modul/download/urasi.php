<?php
$cfg_file_url = "$cfg_img_url/download";
$cfg_file_path = "$cfg_img_path/download";
$cfg_fullsizepics_path = "$cfg_img_path/download/large";
$cfg_fullsizepics_url = "$cfg_img_url/download/large";
$cfg_thumb_path = "$cfg_img_path/download/small";
$cfg_thumb_url = "$cfg_img_url/download/small";

// 1e+8 100MB
$maxfilesize = 1e+8;		//Max file size allowed to be uploaded
$allowedfiletypes = "pdf,zip,xls,doc,ppt,pps,png,gif,jpg,jpeg,odt,ods,cdr,xlsx,docx";
$cfg_thumb_width = 200;

//Cantumkan nama table yang langsung terkait dengan modul ini, penamaan wajib namamodul'_drop'.
$file_drop = array('filecat', 'filedata');
$thisfile="//".param_url();

ob_start();
if(!$admin) {
	//css untuk admin
	echo <<<END
	<link type="text/css" href="$cfg_app_url/modul/download/css/basic.css" rel="stylesheet">
END;
}
$style_css['download']=ob_get_clean();

?>