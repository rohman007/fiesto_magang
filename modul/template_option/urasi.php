<?php

$cfg_decoration_path = "$cfg_app_path/file/template_option";
$cfg_decoration_url = "$cfg_app_url/file/template_option";
$maxfilesize = 5000000;
$allowedtypes = "gif,jpg,jpeg,png,ico";

//Cantumkan nama table yang langsung terkait dengan modul ini, penamaan wajib namamodul'_drop'.
$template_option_drop = array('template_option');
$style_css['template_option']='
<style>
.section_to {
    margin: 0;
    border: 2px solid red;
    padding: 12px;
}
.section_to.Strength.Point img {
    width: 140px;
}
</style>
';

?>