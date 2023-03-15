<?php 
	error_reporting(0);
/*
if ($template_option == 1 && file_exists("$cfg_app_path/template/$config_site_templatefolder/istrasi.php")) {
	// ambil nama template dulu
	$sql = "SELECT name, value, modul FROM config WHERE name='templatefolder'";
	$result = $mysql->query($sql);
	list($name, $value) = $mysql->fetch_row($result);
	$value = addslashes($value);
	eval('$config_site_'.$name.'=stripslashes($value);');
	
	include ("$cfg_app_path/template/$config_site_templatefolder/istrasi.php");
}
*/

ob_start();
include ("$cfg_app_path/template/$config_site_templatefolder/admin/form_generator/index.php"); 

$template = new TemplateForm();
echo $template->form();

$admincontent=ob_get_clean();

?>