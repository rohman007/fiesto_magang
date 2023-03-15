<?php
session_start();
header("Cache-control: private");

$isloadfromprint = true;
include ("urasi.php");
include ("fungsi.php");
include ("lang/$lang/definisi.php");
include ("../modul/member/urasi.php");
include ("../modul/member/lang/$lang/definisi.php");

// modul order dibuat generik
include ("../modul/".$_GET['p']."/lang/$lang/definisi.php");
include ("../modul/".$_GET['p']."/urasi.php");	
include ("../modul/".$_GET['p']."/fungsi.php");	
include ("../modul/".$_GET['p']."/istrasi.php");	

if (!isset($_SESSION['s_userid'])) { 
	include ("login.php"); 
	$thefile = implode("", file("lang/$lang/viewadmin.html"));
	$thefile = addslashes($thefile);
	$thefile = "\$r_file=\"".$thefile."\";";
	eval($thefile);
	print $r_file;
	exit();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title><?php echo $modultag.$config_site_titletag; ?></title>
<style type="text/css">
	body {
		font-family:arial;
		font-size:11pt;
	}
</style>
</head>
<body onLoad="javascript:print()">
<?php
echo $admincontent;
?>
