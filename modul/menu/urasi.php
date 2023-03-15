<?php
$topcatnamecombo = "";		//Name of top level category
$separatorstyle = " / ";	//Categories separator in categories navigation at the top part of the page
if($admin)
{
ob_start();
//css untuk admin
echo <<<END
<!--================================awal Style Catalog=============================================-->
<link type="text/css" href="$cfg_app_url/modul/menu/css/admin.css" rel="stylesheet">
<!--================================akhir Style menu=============================================-->
END;
$style_css['menu']=ob_get_clean();
}

if($admin) 	
{
ob_start();
//js untuk admin
echo <<<END
<script src="$cfg_app_url/js/jquery.ui.touch-punch.js"></script>
<script src="$cfg_app_url/js/jquery.mjs.nestedSortable.js"></script>
<script src="$cfg_app_url/modul/menu/js/menu-backend.js"></script>
<script src="js/bootstrap-editable.js"></script>
END;
$script_js['menu']=ob_get_clean();
}
?>