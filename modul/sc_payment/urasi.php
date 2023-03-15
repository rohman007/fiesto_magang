<?php
$cfg_thumb_path = "$cfg_img_path/sc_payment";
$cfg_thumb_url = "$cfg_img_url/sc_payment";
$maxfilesize = 500000;
$maxwidthlogo=150;		//Max file size allowed to be uploaded
$sort = "bank";
$thisfile=param_url();

//css untuk  front end
ob_start();
echo <<<END
<!--================================awal Style Catalog=============================================-->
<link type="text/css" href="$cfg_app_url/modul/sc_payment/css/basic.css" rel="stylesheet">
<!--================================akhir Style Catalog=============================================-->
END;
$style_css['sc_payment']=ob_get_clean();
?>