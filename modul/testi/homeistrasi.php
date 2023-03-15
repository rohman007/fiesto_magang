<?php

if ($jenis == 'newest') {
	list($homemaxnews)=explode(';',$isi);
	
	$modulurasi .= "<div class=\"control-group\"><label class=\"control-label\">"._MAXDISPLAYED."</label>\r\n";
	$modulurasi .= "<div class=\"controls\"><input type=\"text\" size=\"2\" name=\"homeconfig[]\" value=\"".$homemaxnews."\"></div></div>\r\n";
}
?>