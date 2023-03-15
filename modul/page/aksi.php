<?php
$action = fiestolaundry($_GET['action'],20);
$pid = fiestolaundry($_GET['pid'],11);

require_once 'aplikasi/schema.php';

if ($action=='') {
	header("Location:$cfg_app_url");
}

if ($action=='view') {
	if ($pid!='') {
		$sql = "SELECT judul, isi FROM page WHERE id='$pid'";
		$result = $mysql->query($sql);
		list($title,$page_content) = $mysql->fetch_row($result);
		$content .= $page_content;
		
		// $schema = new Schema();
		// $content .= $schema->SinglePage($sql);
	}
}
?>