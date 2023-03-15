<?php
$sort = "title";
// $action = fiestolaundry($_GET['action'],20);
// $pid = fiestolaundry($_GET['pid'],11);
$tempid = explode('_',$_SEG[2]);
$pid = $tempid[0];
$sql = "SELECT judulfrontend FROM module WHERE nama='download'";
$result = $mysql->query($sql);
list($title) = $mysql->fetch_row($result);

if ($action=='go') {
	$sql = "SELECT filename FROM filedata WHERE id='$pid'";
	$result = $mysql->query($sql);
	list($filename) = $mysql->fetch_row($result);
	$mysql->query("UPDATE filedata SET clickctr=clickctr+1 WHERE id='$pid'");
	header("Location: $cfg_file_url/$filename");
	exit();
} else {
	$sql = "SELECT id,nama FROM filecat ORDER BY urutan";
	$result = $mysql->query($sql);
	while (list($cat_id,$cat_name) = $mysql->fetch_row($result)) {
		$content .= "<h2>$cat_name</h2>\r\n";
		$sql1 = "SELECT id, filename, filesize, title, thumbnail FROM filedata WHERE cat_id='$cat_id' ORDER BY $sort";
		$result1 = $mysql->query($sql1);
		$content .= "<ul class=\"download_conten\">\r\n";
		while (list($pid, $filename, $filesize, $filetitle, $thumb) = $mysql->fetch_row($result1)) {
			$thumbnail = ($thumb != '' && file_exists("$cfg_fullsizepics_path/$thumb")) ? '<img class="thumbnail" src="'.$cfg_thumb_url.'/'.$thumb.'" alt="'.$filetitle.'">' : '';
			
			if ($filetitle=='') $filetitle=$filename;
			
			$titleurl = array();
			$titleurl["pid"] = $filetitle;
				
			// $content .= "<li class=\"link_download\">$filetitle (".convertbyte($filesize).") <a href=\"".$urlfunc->makePretty("?p=download&action=go&pid=$pid", $titleurl)."\"><i class=\"fa fa-cloud-download fa-2\"></i>"._DOWNLOAD."</a></li>\r\n";
			$content .= "<li class=\"xdata\">$thumbnail<a href=\"".$urlfunc->makePretty("?p=download&action=go&pid=$pid", $titleurl)."\"><i class=\"fa fa-download\"></i> $filetitle</a> <span class=\"filesize\">(".convertbyte($filesize).")</span></li>\r\n";
		}
		$content .= "</ul>\r\n";
	}
}
?>
