<?php
include ("kelola/urasi.php");
include ("kelola/fungsi.php");
include ("kelola/lang/$lang/definisi.php");
include ("$cfg_app_path/modul/news/lang/$lang/definisi.php");
$sql = "SELECT name, value FROM config WHERE modul='news'";
$result = $mysql->query($sql);
while (list($name, $value) = $mysql->fetch_row($result)) {
	$$name=$value;
}

$pid=fiestolaundry($_GET['pid'],11);
$sql = "SELECT judulberita,isiberita,tglberita,thumb FROM newsdata WHERE id='$pid' AND publish='1'";
$result = $mysql->query($sql);
list ($judulberita,$isiberita,$tglberita,$thumb)=$mysql->fetch_row($result);
if ($thumb != '') {
	$_filename = explode(':', $thumb);
	$image = '<img src="'.$cfg_app_url.'/file/news/large/'.$_filename[0].'"/>';
}
echo '
<html>
<head>
<style type="text/css">
	body {
		font-family:arial;
		font-size:11pt;
	}
</style>
</head>
<body onLoad="print()">
<h1>'.$judulberita.'</h1>
'.$image.'
<div>'.$isiberita.'</div>
<br /><hr />
';
echo "<p style=\"font-style:italic;font-size:10pt\">"._PUBLISHEDAT." <a href=\"$cfg_app_url/index.php?p=news&action=shownews&pid=$pid\">$cfg_app_url/index.php?p=news&action=shownews&pid=$pid</a> ";
if ($isdateshown) echo _ONDATE." ".tglformat($tglberita)."</p>\r\n";

echo '
</body>
</html>';
?>