<?php
include ("kelola/urasi.php");
include ("kelola/fungsi.php");
include ("$cfg_app_path/modul/news/urasi.php");
$sql = "SELECT konstanta, terjemahan FROM translation WHERE modul='news'";
$result = $mysql->query($sql);
while (list($konstanta, $terjemahan) = $mysql->fetch_row($result)) {
	define($konstanta,$terjemahan);
}

$contentxml  = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
$contentxml .= "<rss version=\"2.0\"  xmlns:atom=\"http://www.w3.org/2005/Atom\">\r\n";
$contentxml .= "<channel>\r\n";

$cat_id = fiestolaundry($_GET['cat_id'],11);
$cat_id = ($cat_id!='' && ctype_digit($cat_id)) ? $cat_id : 0 ;

$rsstitle = _NEWSCONTENT;
$rsslink = str_replace('&','&amp;',$cfg_app_url.$_SERVER['REQUEST_URI']);

if ($cat_id>0) {
	$sql = "SELECT nama FROM newscat WHERE id='$cat_id'";
	$result = $mysql->query($sql);
	list($namakategori) = $mysql->fetch_row($result);
	$sql = "SELECT id, judulberita, summary, tglberita FROM newsdata WHERE cat_id='$cat_id' ORDER BY tglberita DESC LIMIT $maxrss";
} else {
	$namakategori = _ALLCAT;
	$sql = "SELECT id, judulberita, summary, tglberita FROM newsdata ORDER BY tglberita DESC LIMIT $maxrss";
}

$contentxml .= "<title>$config_site_titletag: $namakategori</title>\r\n";
$contentxml .= "<link>$rsslink</link>\r\n";
$contentxml .= "<description>$config_site_titletag: $namakategori</description>";
$contentxml .= "<language>$lang</language>\r\n";
$contentxml .= "<atom:link href=\"$rsslink\" rel=\"self\" type=\"application/rss+xml\" />\r\n";

$result = $mysql->query($sql);
while (list($id, $judulberita, $summary, $tglberita) = $mysql->fetch_row($result)) {
	$url = "index.php?p=news&amp;action=shownews&amp;pid=$id";
	
	$contentxml .= "<item>\r\n";
	$contentxml .= "<title>$judulberita</title>\r\n";
	$contentxml .= "<pubDate>".date('D, d M Y H:i:s +0700', strtotime($tglberita))."</pubDate>\r\n";
	$contentxml .= "<description><![CDATA[$summary]]></description>\r\n";
	$contentxml .= "<guid>$cfg_app_url/$url</guid>\r\n";
	$contentxml .= "</item>\r\n";
}
$contentxml .= "</channel>\r\n";
$contentxml .= "</rss>\r\n";
echo $contentxml;
?>
