<?php

$action = fiestolaundry($_GET['action'],20);
$pid = fiestolaundry($_GET['pid'],11);
$cat_id = fiestolaundry($_GET['cat_id'],11);
$sql = "SELECT judulfrontend FROM module WHERE nama='banner'";
$result = mysql_query($sql);
list($title) = mysql_fetch_row($result);

if ($action=='go') {
	$sql = "SELECT url FROM bannerdata WHERE id='$pid'";
	$result = mysql_query($sql);
	list($url) = mysql_fetch_row($result);
	mysql_query("UPDATE bannerdata SET clickctr=clickctr+1 WHERE id='$pid'");
	header("Location:http://$url");
}

if ($action=='list') {
	// ... baru kemudian diambil lagi, kali ini dengan limit perhalaman-> contoh fasilitator
	$sql = "SELECT id, title, filename, publish, url 
		FROM bannerdata WHERE publish='1' AND cat_id='$cat_id'";

	$photo = mysql_query($sql);
	$total_images=mysql_num_rows($photo);
	
	if ($total_images=="0") {
		$content .= _ERROR;
		$content .= _NOBANNER;
	} else {
		$titleurl = array();
		$titleurl["cat_id"] = $catname;
		mysql_query("UPDATE bannerdata SET clickctr=clickctr+1 WHERE id='$pid'");
		$content .= show_me_the_list();
	}
}
?>
