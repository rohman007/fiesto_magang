<?php

$cfg_fullsizepics_path = "$cfg_img_path/event/large";
$cfg_fullsizepics_url = "$cfg_img_url/event/large";
$cfg_thumb_path = "$cfg_img_path/event/small";
$cfg_thumb_url = "$cfg_img_url/event/small";

$maxfilesize = 5000000;		//Max file size allowed to be uploaded

//pindah ke urasi template: $cfg_max_width = 600;		//pixels


//Cantumkan nama table yang langsung terkait dengan modul ini, penamaan wajib namamodul'_drop'.
$event_drop = array('event');
$thisfile="//".param_url();
?>